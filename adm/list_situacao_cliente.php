<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idSituacao_cliente ',
    1 => 'Nome_situacao '
];

//Obter a quantidade de registros do banco de dados

$query_qnt_situacao_cliente = "SELECT COUNT(idSituacao_cliente) AS qnt_situacao_cli FROM situacao_cliente";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_situacao_cliente .= " WHERE idSituacao_cliente LIKE :idSituacao_cliente";
    $query_qnt_situacao_cliente .= " OR Nome_situacao LIKE :Nome_situacao ";
}

//Preparar a QUERY

$result_qnt_situacao_cliente = $pdo->prepare($query_qnt_situacao_cliente);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_qnt_situacao_cliente->bindValue(':idSituacao_cliente', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_situacao_cliente->bindValue(':Nome_situacao', $valor_pesq, PDO::PARAM_STR);
}

//Executa a QUERY

$result_qnt_situacao_cliente->execute();
$row_qnt_situacao_cliente = $result_qnt_situacao_cliente->fetch(PDO::FETCH_ASSOC);

//Recuperar os registos do banco de dados

$query_situacao_cliente = "SELECT idSituacao_cliente, Nome_situacao FROM situacao_cliente ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_situacao_cliente .= " WHERE idSituacao_cliente LIKE :idSituacao_cliente";
    $query_situacao_cliente .= " OR Nome_situacao LIKE :Nome_situacao ";
}

//Ordenar os registros
$query_situacao_cliente .= " ORDER BY " .$colunas[$dados_requisicao['order'][0]['column']] . " " .
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_situacao_cliente = $pdo->prepare($query_situacao_cliente);
$result_situacao_cliente->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_situacao_cliente->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_situacao_cliente->bindValue(':idSituacao_cliente', $valor_pesq, PDO::PARAM_STR);
    $result_situacao_cliente->bindValue(':Nome_situacao', $valor_pesq, PDO::PARAM_STR);
}

//Executa a Query

$result_situacao_cliente->execute();

while($row_situacao_cliente = $result_situacao_cliente->fetch(PDO::FETCH_ASSOC))
{
    //var_dump($row_categoria);
    extract($row_situacao_cliente);

    $registro = [];
    $registro[] = $idSituacao_cliente;
    $registro[] = $Nome_situacao;
    $registro[] = "<button type='button' id='$idSituacao_cliente' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editSituacaoCliente($idSituacao_cliente)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idSituacao_cliente' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarSituacaoCliente($idSituacao_cliente)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']), //Para cara requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_situacao_cliente['qnt_situacao_cli']), //Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_situacao_cliente['qnt_situacao_cli']), //Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela situacao_cliente
];

//Retorno os dados em formato de objeto para o Javascript
echo json_encode($resultado);
