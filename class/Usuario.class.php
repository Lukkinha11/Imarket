<?php

class Usuario{

    public function login($login, $senha) {
        
        require 'conexao2.php';
        global $pdo;

        $sql = "SELECT login, senha, idFuncionarios FROM funcionarios WHERE login = :login AND senha = :senha";
        $sql = $pdo->prepare($sql);
        $sql->bindValue("login", $login);
        $sql->bindValue("senha", md5($senha));
        $sql->execute();

        if($sql->rowCount() > 0)
        {
            $dado = $sql->fetch();

            //echo $dado['idFuncionarios'];

            $_SESSION['idFunc'] = $dado['idFuncionarios'];

            return true;

        }else{

            //header("Location: ../index.html");
    
            return false;
        }


    }
    public function logged($id){
        global $pdo;

        $array = array();

        $sql = "SELECT Nome, Sobrenome FROM funcionarios WHERE idFuncionarios = :idFuncionarios";
        $sql = $pdo->prepare($sql);
        $sql->bindValue("idFuncionarios", $id);
        $sql->execute();

        if($sql->rowCount() > 0)
        {
            $array = $sql->fetch();
        }

        return $array;

    }
}

?>