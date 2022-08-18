<?php

ini_set('default_charset', 'utf-8');
require_once '../class/conexao2.php';
global $pdo;

$query_prod = "SELECT id_Produto, Nome_prod, Desc_prod, Image,  Valor_prod, Valor_novo FROM produto
                INNER JOIN preco
                ON preco.Produto_Id_Produto = produto.id_Produto
                ORDER BY id_Produto";

$result_prod = $pdo->prepare($query_prod);
$result_prod->execute();

$dados = "";

while($row_prod = $result_prod->fetch(PDO::FETCH_ASSOC))
{
    extract($row_prod);
    $dados .= "<tr>
                    <td>$id_Produto<td>
                    <td>$Nome_prod<td>
                    <td>$Valor_novo<td>
                <tr>";
}

echo $dados;
