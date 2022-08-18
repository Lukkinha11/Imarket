<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idTipo_Logradouro = filter_input(INPUT_GET, "idTipo_Logradouro", FILTER_SANITIZE_NUMBER_INT);

//$idTipo_Logradouro = "1000";

if(!empty($idTipo_Logradouro))
{
   $query_tipo_logradouro = "SELECT idTipo_Logradouro, Tipo_Logradouro FROM tipo_logradouro 
                             WHERE idTipo_Logradouro=:idTipo_Logradouro LIMIT 1";

   $result_tipo_logradouro = $pdo->prepare($query_tipo_logradouro); 
   $result_tipo_logradouro->bindValue(':idTipo_Logradouro', $idTipo_Logradouro, PDO::PARAM_INT);
   $result_tipo_logradouro->execute();

   if(($result_tipo_logradouro) AND ($result_tipo_logradouro->rowCount() !=0))
   {
        $row_tipo_logradouro = $result_tipo_logradouro->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_tipo_logradouro];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Tipo de Logradouro encontrado</div>"];
   }

   
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Tipo de Logradouro encontrado</div>"];
}

echo json_encode($retorna);