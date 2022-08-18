<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$id_Produto = filter_input(INPUT_GET, "id_Produto", FILTER_SANITIZE_NUMBER_INT);
//$id_Produto = "1";

if(!empty($id_Produto))
{
   $query_produto = "SELECT id_Produto, Nome_prod, Desc_prod, Desc_medida, unidade_medidas_idUnidade_medidas, Image, Codigo_Barras, Categoria_Prod_idCategoria, Categoria, Marca_idMarca, Desc_Marca, concat('../img_prod/',Categoria_diretorio,'/',Image) AS caminho FROM produto
                    INNER JOIN unidade_medidas
                    ON unidade_medidas.idUnidade_medidas = produto.unidade_medidas_idUnidade_medidas
                    INNER JOIN categoria_prod
                    ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                    INNER JOIN marca
                    ON produto.Marca_idMarca = marca.idMarca
                    WHERE id_Produto=:id_Produto
                    LIMIT 1";

   $result_produto = $pdo->prepare($query_produto); 
   $result_produto->bindValue(':id_Produto', $id_Produto, PDO::PARAM_INT);
   $result_produto->execute();

   if(($result_produto) AND ($result_produto->rowCount() !=0))
   {
     $row_produto = $result_produto->fetch(PDO::FETCH_ASSOC);
     $retorna = ['status' => true, 'dados' => $row_produto];
   }
   else
   {
     $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Produto encontrado#1</div>"];
   }
}
else
{
  $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Produto encontrado#2</div>"];
}

echo json_encode($retorna);