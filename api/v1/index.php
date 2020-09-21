<?php

// Criando um cabeçalho HTTP para informar ao navegador o tipo de retorno
header('Content-Type: application/json; charset: utf-8');

// Require para trazer a classe
require_once 'classe/Estoque.php';

// Vamos criar uma Classe Rest que será responsável por checar se houve uma requisição válida e pra qual recurso é
// a requisição
class Rest {
    public static function open($requisicao) {
        // Aqui vamos separar a url, caso tenha uma barra nela com a função explode
        $url = explode('/', $_REQUEST['url']);
        // Agora a variável url é um array
        // var_dump($url);
        // Vamos criar uma variável para guardar o primeiro indice do array que será o recursos acessado
        // Estamos utilizando a função ucfirst para deixar a primeira letra da classa em maíusculo, é um padrão
        $classe = ucfirst($url['0']);
        // Após receber o primeiro resgistro e guarda lo na varivel classe vamos apagar a posição 0 
        array_shift($url);
        // Agora a posição 0 do array contém o verbo que será utilizado no recurso
        $metodo = $url['0'];
        array_shift($url);
        // Agora vamos receber os parâmetros
        // Primeiro definimos como um array vazio
        $parametros = array();
        // Lembrando que retiramos da $url a primera e a segunda posição do array que veio da requisição, entendemos
        // que o que sobra são so parametros da url
        $parametros = $url;

        try {
            // Vamos checar se a classe existe no sistema
            if (class_exists($classe)) {
                if(method_exists($classe, $metodo)) {
                    // Aqui vamos pegar a classe e o método e executa-los e retornar a informação solicitada
                    $retorno = call_user_func_array(array(new $classe, $metodo), $parametros);

                    return json_encode(array('status' => 'sucesso', 'dados' => $retorno ));
                } else {
                    // Estamos criando um json para retornar uma mensagem de erro caso o metodo não exista dentro da classe
                    return json_encode(array('status' => 'erro', 'dados' => 'Método inexistente!'));
                }
            } else {
                // Estamos criando um json para retornar uma mensagem de erro caso a classe não exista no sistema
                return json_encode(array('status' => 'erro', 'dados' => 'Classe inexistente!'));
            }
        } catch (Exception $e) {
            return json_encode(array('status' => 'erro', 'dados' => $e->getMessage()));
        }
    }
}

// Checando se houve alguma requisição no Servidor
if(isset($_REQUEST)) {
    // Aqui estamos utilizando a função open da classe Rest para testar se houve uma requisição
    echo Rest::open($_REQUEST);
}