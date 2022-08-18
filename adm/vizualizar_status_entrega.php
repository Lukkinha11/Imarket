<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idStatus_Entrega = filter_input(INPUT_GET, "idStatus_Entrega", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idStatus_Entrega))
{
   $query_status = "SELECT idStatus_Entrega, Status FROM status_entrega 
                    WHERE idStatus_Entrega=:idStatus_Entrega LIMIT 1";

   $result_status = $pdo->prepare($query_status); 
   $result_status->bindValue(':idStatus_Entrega', $idStatus_Entrega, PDO::PARAM_INT);
   $result_status->execute();

   if(($result_status) AND ($result_status->rowCount() !=0))
   {
        $row_status = $result_status->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_status];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Marca encontrada</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Marca encontrada</div>"];
}

echo json_encode($retorna);