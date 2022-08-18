<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idCategoria = filter_input(INPUT_GET, "idCategoria", FILTER_SANITIZE_NUMBER_INT);
//$idCategoria = "1000";

if(!empty($idCategoria))
{
   $query_categoria = "SELECT idCategoria, Categoria, Categoria_diretorio FROM categoria_prod 
                    WHERE idCategoria=:idCategoria LIMIT 1";

   $result_categoria = $pdo->prepare($query_categoria); 
   $result_categoria->bindValue(':idCategoria', $idCategoria, PDO::PARAM_INT);
   $result_categoria->execute();

   if(($result_categoria) AND ($result_categoria->rowCount() !=0))
   {
        $row_categoria = $result_categoria->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_categoria];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Categoria encontrada</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhuma Categoria encontrada</div>"];
}

echo json_encode($retorna);