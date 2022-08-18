<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idFornecedor = filter_input(INPUT_GET, "idFornecedor", FILTER_SANITIZE_NUMBER_INT);
//$idFornecedor = "1000";

if(!empty($idFornecedor))
{
   $query_fornecedor = "SELECT idFornecedor, Nome_Fantasia, Razao_social, CNPJ, Telefone, DDD, Email, Complemento, Endereco_idEndereco, Cep, Logradouro, Bairro, Cidade, Estado, Referencia 
                         FROM fornecedor
                         INNER JOIN endereco
                         ON endereco.idEndereco = fornecedor.Endereco_idEndereco
                         WHERE idFornecedor=:idFornecedor LIMIT 1";

   $result_fornecedor = $pdo->prepare($query_fornecedor); 
   $result_fornecedor->bindValue(':idFornecedor', $idFornecedor, PDO::PARAM_INT);
   $result_fornecedor->execute();

   if(($result_fornecedor) AND ($result_fornecedor->rowCount() !=0))
   {
        $row_fornecedor = $result_fornecedor->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_fornecedor];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Fornecedor encontrado#2</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Fornecedor encontrado#1</div>"];
}

echo json_encode($retorna);

//Nenhum Fornecedor encontrado#1 = Não está enviando o ID do fornecedor para a modal

//Nenhum Fornecedor encontrado#2 = Não encontrou nenhum ID do fornecedor no select