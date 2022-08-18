<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idDesc_pag = filter_input(INPUT_GET, "idDesc_pag", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idDesc_pag))
{
   $query_desc_pag = "SELECT idDesc_pag, Forma_pag, Quant FROM desc_pag 
                    WHERE idDesc_pag=:idDesc_pag LIMIT 1";

   $result_desc_pag = $pdo->prepare($query_desc_pag); 
   $result_desc_pag->bindValue(':idDesc_pag', $idDesc_pag, PDO::PARAM_INT);
   $result_desc_pag->execute();

   if(($result_desc_pag) AND ($result_desc_pag->rowCount() !=0))
   {
        $row_desc_pag = $result_desc_pag->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_desc_pag];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Descrição de Pagamento encontrada</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Descrição de Pagamento encontrada</div>"];
}

echo json_encode($retorna);