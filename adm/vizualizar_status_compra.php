<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idStatus_compra = filter_input(INPUT_GET, "idStatus_compra", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idStatus_compra))
{
   $query_status_compra = "SELECT idStatus_compra, Status_compra FROM status_compra 
                    WHERE idStatus_compra =:idStatus_compra LIMIT 1";

   $result_status_compra = $pdo->prepare($query_status_compra); 
   $result_status_compra->bindValue(':idStatus_compra', $idStatus_compra, PDO::PARAM_INT);
   $result_status_compra->execute();

   if(($result_status_compra) AND ($result_status_compra->rowCount() !=0))
   {
        $row_status_compra = $result_status_compra->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_status_compra];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Status de Compra encontrado #1</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Status de Compra encontrado #2</div>"];
}

echo json_encode($retorna);