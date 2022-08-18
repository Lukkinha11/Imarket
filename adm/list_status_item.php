<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idStatus_item',
    1 => 'Status_item'
];

//Obter a quantidade de resgistros no banco de dados(Contar)

$query_qnt_status_item = "SELECT COUNT(idStatus_item) AS qnt_status_item FROM status_item";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_status_item .= " WHERE idStatus_item LIKE :idStatus_item ";
    $query_qnt_status_item .= " OR Status_item LIKE :Status_item ";
}

//Preparar a QUERY
$result_qnt_status_item = $pdo->prepare($query_qnt_status_item);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_status_item->bindValue(':idStatus_item', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_status_item->bindValue(':Status_item', $valor_pesq, PDO::PARAM_STR);
}

$result_qnt_status_item->execute();
$row_qnt_status_item = $result_qnt_status_item->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados

$query_status_item = "SELECT idStatus_item, Status_item FROM status_item ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_status_item .= " WHERE idStatus_item LIKE :idStatus_item ";
    $query_status_item .= "OR Status_item LIKE :Status_item ";
}

//Ordenar os registros na tabela

$query_status_item .=" ORDER BY ". $colunas[$dados_requisicao['order'][0]['column']] . " " .
                    $dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_status_item = $pdo->prepare($query_status_item);
$result_status_item->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_status_item->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_status_item->bindValue(':idStatus_item', $valor_pesq, PDO::PARAM_STR);
    $result_status_item->bindValue(':Status_item', $valor_pesq, PDO::PARAM_STR);
}

//Executar a QUERY
$result_status_item->execute();

while($row_status_item = $result_status_item->fetch(PDO::FETCH_ASSOC))
{
    extract($row_status_item);

    $registro = [];
    $registro[] = $idStatus_item;
    $registro[] = $Status_item;
    $registro[] = "<button type='button' id='$idStatus_item' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editStatusItem($idStatus_item)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idStatus_item' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarStatusItem($idStatus_item)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']),//Para cada requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_status_item['qnt_status_item']),//Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_status_item['qnt_status_item']),//Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela status_item
];

// Retornar os dados como objeto para o Javascript
echo json_encode($resultado);