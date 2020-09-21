<?php

class Estoque {
    public function mostrar() {
        $con = new PDO('mysql: host=localhost; dbname=filial;','root','123r1xx@');
        $sql = "SELECT * FROM estoque ORDER BY id ASC";
        $sql = $con->prepare($sql);
        $sql->execute();

        $resultados = array();

        while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        if(!$resultados) {
            throw new Exception("Nenhum produto no estoque!");
        }
        return $resultados;
    }
}