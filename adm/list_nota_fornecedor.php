<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idCompra_fornecedor',
    1 => 'Descricao_NF',
    2 => 'Numero_NF',
    3 => 'Serie_NF',
    4 => 'Valor_NF',
    5 => 'Data_Compra',
    6 => 'Fornecedor_idFornecedor',
    7 => 'Forma_Pagamento_idForma_Pag'
];

//Obter a quantidade de resgistros no banco de dados(Contar)

$query_qnt_estoque = "SELECT COUNT(idCompra_fornecedor) AS qnt_compra_fornecedor FROM compra_fornecedor";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_estoque .= " WHERE idCompra_fornecedor LIKE :idCompra_fornecedor ";
    $query_qnt_estoque .= " OR Descricao_NF LIKE :Descricao_NF ";
    $query_qnt_estoque .= " OR Numero_NF LIKE :Numero_NF ";
    $query_qnt_estoque .= " OR Serie_NF LIKE :Serie_NF ";
    $query_qnt_estoque .= " OR Valor_NF LIKE :Valor_NF ";
    $query_qnt_estoque .= " OR Data_Compra LIKE :Data_Compra ";
    $query_qnt_estoque .= " OR Fornecedor_idFornecedor LIKE :Fornecedor_idFornecedor ";
    $query_qnt_estoque .= " OR Forma_Pagamento_idForma_Pag LIKE :Forma_Pagamento_idForma_Pag ";
}

//Preparar a QUERY
$result_qnt_estoque = $pdo->prepare($query_qnt_estoque);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_estoque->bindValue(':idCompra_fornecedor', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_estoque->bindValue(':Descricao_NF', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_estoque->bindValue(':Numero_NF', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_estoque->bindValue(':Serie_NF', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_estoque->bindValue(':Valor_NF', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_estoque->bindValue(':Data_Compra', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_estoque->bindValue(':Fornecedor_idFornecedor', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_estoque->bindValue(':Forma_Pagamento_idForma_Pag', $valor_pesq, PDO::PARAM_INT);
}

$result_qnt_estoque->execute();
$row_qnt_estoque = $result_qnt_estoque->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados

$query_estoque = "SELECT * FROM (
    SELECT idCompra_fornecedor, Numero_NF, Serie_NF,  DATE_FORMAT(Data_Compra, '%d/%m/%Y') as Data_Compra, Descricao_NF, Nome_Fantasia, Descricao, sum(Valor_NF) AS Valor_NF, count(*) AS Parcelas FROM compra_fornecedor
    INNER JOIN fornecedor
    ON fornecedor.idFornecedor = compra_fornecedor.Fornecedor_idFornecedor
    INNER JOIN forma_pagamento
    ON forma_pagamento.idForma_Pag = compra_fornecedor.Forma_Pagamento_idForma_Pag
    INNER JOIN contas_pagar
    ON contas_pagar.Compra_Fornecedor_idCompra_fornecedor = compra_fornecedor.idCompra_fornecedor
    GROUP BY idCompra_fornecedor, Numero_NF, Serie_NF,  Data_Compra, Descricao_NF, Nome_Fantasia, Descricao) Parcelas ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_estoque .= " WHERE idCompra_fornecedor LIKE :idCompra_fornecedor ";
    $query_estoque .= " OR Numero_NF LIKE :Numero_NF ";
    $query_estoque .= " OR Serie_NF LIKE :Serie_NF ";
    $query_estoque .= " OR Valor_NF LIKE :Valor_NF ";
    $query_estoque .= " OR Data_Compra LIKE :Data_Compra ";
    $query_estoque .= " OR Descricao_NF LIKE :Descricao_NF ";
    $query_estoque .= " OR Nome_Fantasia LIKE :Nome_Fantasia ";
    $query_estoque .= " OR Descricao LIKE :Descricao ";
    $query_estoque .= " OR Parcelas LIKE :Parcelas ";
}

//Ordenar os registros na tabela

$query_estoque .=" ORDER BY ". $colunas[$dados_requisicao['order'][0]['column']] . " " .
                    $dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_estoque = $pdo->prepare($query_estoque);
$result_estoque->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_estoque->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_estoque->bindValue(':idCompra_fornecedor', $valor_pesq, PDO::PARAM_STR);
    $result_estoque->bindValue(':Numero_NF', $valor_pesq, PDO::PARAM_STR);
    $result_estoque->bindValue(':Serie_NF', $valor_pesq, PDO::PARAM_STR);
    $result_estoque->bindValue(':Valor_NF', $valor_pesq, PDO::PARAM_STR);
    $result_estoque->bindValue(':Data_Compra', $valor_pesq, PDO::PARAM_STR);
    $result_estoque->bindValue(':Descricao_NF', $valor_pesq, PDO::PARAM_STR);
    $result_estoque->bindValue(':Nome_Fantasia', $valor_pesq, PDO::PARAM_STR);
    $result_estoque->bindValue(':Descricao', $valor_pesq, PDO::PARAM_STR);
    $result_estoque->bindValue(':Parcelas', $valor_pesq, PDO::PARAM_STR);
}

//Executar a QUERY
$result_estoque->execute();

while($row_estoque = $result_estoque->fetch(PDO::FETCH_ASSOC))
{
    extract($row_estoque);

    $registro = [];
    $registro[] = $idCompra_fornecedor;
    $registro[] = $Numero_NF;
    $registro[] = $Serie_NF;
    $registro[] = "R$ ". number_format($Valor_NF, 2, ',' , '.');
    $registro[] = $Data_Compra;
    $registro[] = $Descricao_NF;
    $registro[] = $Nome_Fantasia;
    $registro[] = $Descricao;
    $registro[] = $Parcelas."x";
    $registro[] = "<button type='button' id='$idCompra_fornecedor' class='btn btn-outline-danger btn-sm' title='Excluir Registro'
                        onclick='apagarNF($idCompra_fornecedor)'><i class='bi bi-file-earmark-x'></i>
                    </button>";               
    $dados[] = $registro;
}

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']),//Para cada requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_estoque['qnt_compra_fornecedor']),//Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_estoque['qnt_compra_fornecedor']),//Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela compra_fornecedor 
];

// Retornar os dados como objeto para o Javascript
echo json_encode($resultado);