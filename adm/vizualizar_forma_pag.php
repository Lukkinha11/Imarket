<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idForma_Pag = filter_input(INPUT_GET, "idForma_Pag", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idForma_Pag))
{
   $query_forma_pag = "SELECT idForma_Pag, Descricao, Desc_pag_idDesc_pag  FROM forma_pagamento 
                    WHERE idForma_Pag=:idForma_Pag LIMIT 1";

   $result_forma_pag = $pdo->prepare($query_forma_pag); 
   $result_forma_pag->bindValue(':idForma_Pag', $idForma_Pag, PDO::PARAM_INT);
   $result_forma_pag->execute();

   if(($result_forma_pag) AND ($result_forma_pag->rowCount() !=0))
   {
        $row_forma_pag = $result_forma_pag->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_forma_pag];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Forma de Pagamento encontrada</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Forma de Pagamento encontrada</div>"];
}

echo json_encode($retorna);