<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idStatus_item = filter_input(INPUT_GET, "idStatus_item", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idStatus_item))
{
   $query_status_item = "SELECT idStatus_item, Status_item FROM status_item 
                    WHERE idStatus_item =:idStatus_item LIMIT 1";

   $result_status_item = $pdo->prepare($query_status_item); 
   $result_status_item->bindValue(':idStatus_item', $idStatus_item, PDO::PARAM_INT);
   $result_status_item->execute();

   if(($result_status_item) AND ($result_status_item->rowCount() !=0))
   {
     $row_status_item = $result_status_item->fetch(PDO::FETCH_ASSOC);
     $retorna = ['status' => true, 'dados' => $row_status_item];
   }
   else
   {
     $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Status do item encontrado #1</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Status do item encontrado #2</div>"];
}

echo json_encode($retorna);