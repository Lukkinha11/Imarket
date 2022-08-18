<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$nome_produto = filter_input(INPUT_GET, "Nome_prod", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if(!empty($nome_produto))
{
    $pesq_produto = "%" . $nome_produto . "%";
    
    $query_produto = "SELECT id_Produto, Nome_prod FROM produto 
                    WHERE Nome_prod LIKE :Nome_prod LIMIT 20";
    
    $result_produto = $pdo->prepare($query_produto); 
    $result_produto->bindValue(':Nome_prod', $pesq_produto, PDO::PARAM_STR);
    $result_produto->execute();

    if(($result_produto) AND ($result_produto->rowCount() !=0))
    {
        while($row_produto = $result_produto->fetch(PDO::FETCH_ASSOC))
        {
            $dados[] = [
                'id_Produto' => $row_produto['id_Produto'],
                'Nome_prod' => $row_produto['Nome_prod']
            ];
        }

        $retorna = ['erro' => false, 'dados' => $dados];
    }
    else
    {
        $retorna = ['erro' => true, 'msg' => "Nenhum Produto encontrado"];
    }
}
else
{
    $retorna = ['erro' => true, 'msg' => "Nenhum Produto encontrado"];
}

echo json_encode($retorna);
