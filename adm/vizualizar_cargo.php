<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idCargo = filter_input(INPUT_GET, "idCargo", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idCargo))
{
   $query_cargo = "SELECT idCargo, Desc_cargo, Salario FROM cargo 
                    WHERE idCargo=:idCargo LIMIT 1";

   $result_cargo = $pdo->prepare($query_cargo); 
   $result_cargo->bindValue(':idCargo', $idCargo, PDO::PARAM_INT);
   $result_cargo->execute();

   if(($result_cargo) AND ($result_cargo->rowCount() !=0))
   {
        $row_cargo = $result_cargo->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_cargo];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Cargo encontrado</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Cargo encontrado</div>"];
}

echo json_encode($retorna);