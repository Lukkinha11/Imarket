<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idUnidade_medidas = filter_input(INPUT_GET, "idUnidade_medidas", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idUnidade_medidas))
{
   $query_unidade_medida = "SELECT idUnidade_medidas, Desc_medida, Sigla FROM unidade_medidas 
                               WHERE idUnidade_medidas=:idUnidade_medidas LIMIT 1";

   $result_unidade_medida = $pdo->prepare($query_unidade_medida); 
   $result_unidade_medida->bindValue(':idUnidade_medidas', $idUnidade_medidas, PDO::PARAM_INT);
   $result_unidade_medida->execute();

   if(($result_unidade_medida) AND ($result_unidade_medida->rowCount() !=0))
   {
        $row_unidade_medida = $result_unidade_medida->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_unidade_medida];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Unidade de Medida encontrada</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Unidade de Medida encontrada</div>"];
}

echo json_encode($retorna);