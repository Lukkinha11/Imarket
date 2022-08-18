<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idAcesso ',
    1 => 'Nivel '
];

//Obter a quantidade de resgistros no banco de dados(Contar)

$query_qnt_acesso = "SELECT COUNT(idAcesso) AS qnt_acesso FROM acesso";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_acesso .= " WHERE idAcesso LIKE :idAcesso ";
    $query_qnt_acesso .= "OR Nivel LIKE :Nivel ";
}

//Preparar a QUERY
$result_qnt_acesso = $pdo->prepare($query_qnt_acesso);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_acesso->bindValue(':idAcesso', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_acesso->bindValue(':Nivel', $valor_pesq, PDO::PARAM_STR);
}

$result_qnt_acesso->execute();
$row_qnt_acesso = $result_qnt_acesso->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados

$query_acesso = "SELECT idAcesso, Nivel FROM acesso ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_acesso .= " WHERE idAcesso LIKE :idAcesso ";
    $query_acesso .= "OR Nivel LIKE :Nivel ";
}

//Ordenar os registros na tabela

$query_acesso .=" ORDER BY ". $colunas[$dados_requisicao['order'][0]['column']] . " " .
                    $dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_acesso = $pdo->prepare($query_acesso);
$result_acesso->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_acesso->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_acesso->bindValue(':idAcesso', $valor_pesq, PDO::PARAM_STR);
    $result_acesso->bindValue(':Nivel', $valor_pesq, PDO::PARAM_STR);
}

//Executar a QUERY
$result_acesso->execute();

while($row_acesso = $result_acesso->fetch(PDO::FETCH_ASSOC))
{
    extract($row_acesso);

    $registro = [];
    $registro[] = $idAcesso;
    $registro[] = $Nivel;
    $registro[] = "<button type='button' id='$idAcesso' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editAcesso($idAcesso)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idAcesso' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarAcesso($idAcesso)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']),//Para cada requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_acesso['qnt_acesso']),//Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_acesso['qnt_acesso']),//Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela acesso 
];

// Retornar os dados como objeto para o Javascript
echo json_encode($resultado);