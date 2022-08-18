<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idStatus_Entrega',
    1 => 'Status'
];

//Obter a quantidade de registros do banco de dados

$query_qnt_status = "SELECT COUNT(idStatus_Entrega) AS qnt_status FROM status_entrega";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_status .= " WHERE idStatus_Entrega LIKE :idStatus_Entrega";
    $query_qnt_status .= " OR Status LIKE :Status ";
}

//Preparar a QUERY

$result_qnt_status = $pdo->prepare($query_qnt_status);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_qnt_status->bindValue(':idStatus_Entrega', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_status->bindValue(':Status', $valor_pesq, PDO::PARAM_STR);
}

//Executa a QUERY

$result_qnt_status->execute();
$row_qnt_status = $result_qnt_status->fetch(PDO::FETCH_ASSOC);

//Recuperar os registos do banco de dados

$query_status = "SELECT idStatus_Entrega, Status FROM status_entrega ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_status .= " WHERE idStatus_Entrega LIKE :idStatus_Entrega";
    $query_status .= " OR Status LIKE :Status ";
}

//Ordenar os registros
$query_status .= " ORDER BY " .$colunas[$dados_requisicao['order'][0]['column']] . " " .
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_status = $pdo->prepare($query_status);
$result_status->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_status->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_status->bindValue(':idStatus_Entrega', $valor_pesq, PDO::PARAM_STR);
    $result_status->bindValue(':Status', $valor_pesq, PDO::PARAM_STR);
}

//Executa a Query

$result_status->execute();

while($row_status = $result_status->fetch(PDO::FETCH_ASSOC))
{
    //var_dump($row_categoria);
    extract($row_status);

    $registro = [];
    $registro[] = $idStatus_Entrega;
    $registro[] = $Status;
    $registro[] = "<button type='button' id='$idStatus_Entrega' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editStatusEntrega($idStatus_Entrega)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idStatus_Entrega' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarStatusEntrega($idStatus_Entrega)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']), //Para cara requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_status['qnt_status']), //Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_status['qnt_status']), //Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela status_categoria
];

//Retorno os dados em formato de objeto para o Javascript
echo json_encode($resultado);
