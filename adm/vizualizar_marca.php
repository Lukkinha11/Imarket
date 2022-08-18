<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idMarca = filter_input(INPUT_GET, "idMarca", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idMarca))
{
   $query_marca = "SELECT idMarca, Desc_Marca FROM marca 
                    WHERE idMarca=:idMarca LIMIT 1";

   $result_marca = $pdo->prepare($query_marca); 
   $result_marca->bindValue(':idMarca', $idMarca, PDO::PARAM_INT);
   $result_marca->execute();

   if(($result_marca) AND ($result_marca->rowCount() !=0))
   {
        $row_marca = $result_marca->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_marca];
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