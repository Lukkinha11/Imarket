<?php

    
    session_start();

     $_dbHostname = "localhost";
     $_dbName = "mercado_digital";
     $_dbUsername = "root";
     $_dbPassword = "";

    global $pdo;

    try{

        //orientada a objetos com PDO
        $pdo = new PDO("mysql:dbname=".$_dbName."; host=".$_dbHostname, $_dbUsername, $_dbPassword,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo"CONECTADO COM SUCESSO!";

    }catch(PDOException $e)
    {
        echo "<h1>Algo deu errado: " . $e->getMessage() . "</h1><pre>";
        exit;
    }

?>