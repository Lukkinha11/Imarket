<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idPreco = filter_input(INPUT_GET, "idPreco", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idPreco))
{
   $query_preco = "SELECT idPreco, format(Valor_unit,2,'de_DE') AS Valor_unit, format(Valor_prod,2,'de_DE') AS Valor_prod, format(Valor_novo,2,'de_DE') AS Valor_novo, Status_preco, Nome_prod, Produto_Id_Produto FROM preco
                    INNER JOIN produto
                    ON preco.Produto_Id_Produto = produto.id_Produto
                    WHERE idPreco=:idPreco LIMIT 1";

   $result_preco = $pdo->prepare($query_preco); 
   $result_preco->bindValue(':idPreco', $idPreco, PDO::PARAM_INT);
   $result_preco->execute();

   if(($result_preco) AND ($result_preco->rowCount() !=0))
   {
        $row_preco = $result_preco->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_preco];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Preço encontrado</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Preço encontrado</div>"];
}

echo json_encode($retorna);