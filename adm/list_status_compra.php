<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idStatus_compra',
    1 => 'Status_compra'
];

//Obter a quantidade de resgistros no banco de dados(Contar)

$query_qnt_status_compra = "SELECT COUNT(idStatus_compra) AS qnt_status_compra FROM status_compra";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_status_compra .= " WHERE idStatus_compra LIKE :idStatus_compra ";
    $query_qnt_status_compra .= "OR Status_compra LIKE :Status_compra ";
}

//Preparar a QUERY
$result_qnt_status_compra = $pdo->prepare($query_qnt_status_compra);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_status_compra->bindValue(':idStatus_compra', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_status_compra->bindValue(':Status_compra', $valor_pesq, PDO::PARAM_STR);
}

$result_qnt_status_compra->execute();
$row_qnt_status_compra = $result_qnt_status_compra->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados

$query_status_compra = "SELECT idStatus_compra, Status_compra FROM status_compra ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_status_compra .= " WHERE idStatus_compra LIKE :idStatus_compra ";
    $query_status_compra .= "OR Status_compra LIKE :Status_compra ";
}

//Ordenar os registros na tabela

$query_status_compra .=" ORDER BY ". $colunas[$dados_requisicao['order'][0]['column']] . " " .
                    $dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_status_compra = $pdo->prepare($query_status_compra);
$result_status_compra->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_status_compra->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_status_compra->bindValue(':idStatus_compra', $valor_pesq, PDO::PARAM_STR);
    $result_status_compra->bindValue(':Status_compra', $valor_pesq, PDO::PARAM_STR);
}

//Executar a QUERY
$result_status_compra->execute();

while($row_status_compra = $result_status_compra->fetch(PDO::FETCH_ASSOC))
{
    extract($row_status_compra);

    $registro = [];
    $registro[] = $idStatus_compra;
    $registro[] = $Status_compra;
    $registro[] = "<button type='button' id='$idStatus_compra' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editStatusCompra($idStatus_compra)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idStatus_compra' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarStatusCompra($idStatus_compra)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']),//Para cada requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_status_compra['qnt_status_compra']),//Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_status_compra['qnt_status_compra']),//Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela status_compra 
];

// Retornar os dados como objeto para o Javascript
echo json_encode($resultado);