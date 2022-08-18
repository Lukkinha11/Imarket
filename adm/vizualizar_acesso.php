<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idAcesso = filter_input(INPUT_GET, "idAcesso", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idAcesso))
{
   $query_acesso = "SELECT idAcesso, Nivel FROM acesso 
                    WHERE idAcesso=:idAcesso LIMIT 1";

   $result_acesso = $pdo->prepare($query_acesso); 
   $result_acesso->bindValue(':idAcesso', $idAcesso, PDO::PARAM_INT);
   $result_acesso->execute();

   if(($result_acesso) AND ($result_acesso->rowCount() !=0))
   {
        $row_acesso = $result_acesso->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_acesso];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Acesso encontrado #1</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Acesso encontrado #2</div>"];
}

echo json_encode($retorna);