<?php

class conexao{

    private $_dbHostname = "localhost";
    private $_dbName = "mercado_digital";
    private $_dbUsername = "root";
    private $_dbPassword = "";
    global $_con;

    //global $this;

    function __construct() {
        $this->conectar();
    }

    function conectar() {

        try{
            $this->_con = new PDO("mysql:host=$this->_dbHostname;dbname=$this->_dbName", $this->_dbUsername, $this->_dbPassword);
            $this->_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo"CONECTADO COM SUCESSO!";
        } catch (PDOExeption $e) {
            echo "<h1>Algo deu errado: " . $e->getMessage() . "</h1><pre>";
            echo print_r($e);
        }
    }
}

?>