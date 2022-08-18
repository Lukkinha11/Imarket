<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$nome_fornecedor = filter_input(INPUT_GET, "Razao_social", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if(!empty($nome_fornecedor))
{
    $pesq_fornecedor = "%" . $nome_fornecedor . "%";
    
    $query_fornecedor = "SELECT idFornecedor, Razao_social FROM fornecedor 
                        WHERE Razao_social LIKE :Razao_social LIMIT 20";
    
    $result_fornecedor = $pdo->prepare($query_fornecedor); 
    $result_fornecedor->bindValue(':Razao_social', $pesq_fornecedor, PDO::PARAM_STR);
    $result_fornecedor->execute();

    if(($result_fornecedor) AND ($result_fornecedor->rowCount() !=0))
    {
        while($row_fornecedor = $result_fornecedor->fetch(PDO::FETCH_ASSOC))
        {
            $dados[] = [
                'idFornecedor' => $row_fornecedor['idFornecedor'],
                'Razao_social' => $row_fornecedor['Razao_social']
            ];
        }

        $retorna = ['erro' => false, 'dados' => $dados];
    }
    else
    {
        $retorna = ['erro' => true, 'msg' => "Nenhum Fornecedor encontrado"];
    }
}
else
{
    $retorna = ['erro' => true, 'msg' => "Nenhum Fornecedor encontrado2"];
}

echo json_encode($retorna);
