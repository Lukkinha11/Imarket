<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idSituacao_cliente = filter_input(INPUT_GET, "idSituacao_cliente", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idSituacao_cliente))
{
   $query_situacao_cliente = "SELECT idSituacao_cliente, Nome_situacao FROM situacao_cliente 
                    WHERE idSituacao_cliente=:idSituacao_cliente LIMIT 1";

   $result_situacao_cliente = $pdo->prepare($query_situacao_cliente); 
   $result_situacao_cliente->bindValue(':idSituacao_cliente', $idSituacao_cliente, PDO::PARAM_INT);
   $result_situacao_cliente->execute();

   if(($result_situacao_cliente) AND ($result_situacao_cliente->rowCount() !=0))
   {
        $row_situacao_cliente = $result_situacao_cliente->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_situacao_cliente];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Status do Cliente encontrado</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Status do Cliente encontrado</div>"];
}

echo json_encode($retorna);