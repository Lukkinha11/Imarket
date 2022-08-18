<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idMarca',
    1 => 'Desc_Marca',
];

//Obter a quantidade de resgistros no banco de dados(Contar)

$query_qnt_marca = "SELECT COUNT(idMarca) AS qnt_marca FROM marca";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_marca .= " WHERE idMarca LIKE :idMarca ";
    $query_qnt_marca .= "OR Desc_Marca LIKE :Desc_Marca ";
}

//Preparar a QUERY
$result_qnt_marca = $pdo->prepare($query_qnt_marca);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_marca->bindValue(':idMarca', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_marca->bindValue(':Desc_Marca', $valor_pesq, PDO::PARAM_STR);
}

$result_qnt_marca->execute();
$row_qnt_marca = $result_qnt_marca->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados

$query_marca = "SELECT idMarca, Desc_Marca FROM marca ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_marca .= " WHERE idMarca LIKE :idMarca ";
    $query_marca .= "OR Desc_Marca LIKE :Desc_Marca ";
}

//Ordenar os registros na tabela

$query_marca .=" ORDER BY ". $colunas[$dados_requisicao['order'][0]['column']] . " " .
                    $dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_marca = $pdo->prepare($query_marca);
$result_marca->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_marca->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_marca->bindValue(':idMarca', $valor_pesq, PDO::PARAM_STR);
    $result_marca->bindValue(':Desc_Marca', $valor_pesq, PDO::PARAM_STR);
}

//Executar a QUERY
$result_marca->execute();

while($row_marca = $result_marca->fetch(PDO::FETCH_ASSOC))
{
    extract($row_marca);

    $registro = [];
    $registro[] = $idMarca;
    $registro[] = $Desc_Marca;
    $registro[] = "<button type='button' id='$idMarca' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editMarca($idMarca)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idMarca' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarMarca($idMarca)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']),//Para cada requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_marca['qnt_marca']),//Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_marca['qnt_marca']),//Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela marca 
];

// Retornar os dados como objeto para o Javascript
echo json_encode($resultado);