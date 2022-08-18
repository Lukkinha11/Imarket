<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idUnidade_medidas',
    1 => 'Desc_medida',
    2 => 'Sigla'
];

//Obter a quantidade de registros do banco de dados

$query_qnt_unidade_medida = "SELECT COUNT(idUnidade_medidas) AS qnt_medidas FROM unidade_medidas";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_unidade_medida .= " WHERE idUnidade_medidas LIKE :idUnidade_medidas";
    $query_qnt_unidade_medida .= " OR Desc_medida LIKE :Desc_medida ";
    $query_qnt_unidade_medida .= " OR Sigla LIKE :Sigla ";
}

//Preparar a QUERY

$result_qnt_unidade_medida = $pdo->prepare($query_qnt_unidade_medida);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_qnt_unidade_medida->bindValue(':idUnidade_medidas', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_unidade_medida->bindValue(':Desc_medida', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_unidade_medida->bindValue(':Sigla', $valor_pesq, PDO::PARAM_STR);
}

//Executa a QUERY

$result_qnt_unidade_medida->execute();
$row_qnt_unidade_medida = $result_qnt_unidade_medida->fetch(PDO::FETCH_ASSOC);

//Recuperar os registos do banco de dados

$query_unidade_medida = "SELECT idUnidade_medidas, Desc_medida, Sigla FROM unidade_medidas ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_unidade_medida .= " WHERE idUnidade_medidas LIKE :idUnidade_medidas";
    $query_unidade_medida .= " OR Desc_medida LIKE :Desc_medida ";
    $query_unidade_medida .= " OR Sigla LIKE :Sigla ";
}

//Ordenar os registros
$query_unidade_medida .= " ORDER BY " .$colunas[$dados_requisicao['order'][0]['column']] . " " .
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_unidade_medida = $pdo->prepare($query_unidade_medida);
$result_unidade_medida->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_unidade_medida->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_unidade_medida->bindValue(':idUnidade_medidas', $valor_pesq, PDO::PARAM_STR);
    $result_unidade_medida->bindValue(':Desc_medida', $valor_pesq, PDO::PARAM_STR);
    $result_unidade_medida->bindValue(':Sigla', $valor_pesq, PDO::PARAM_STR);
}

//Executa a Query

$result_unidade_medida->execute();

while($row_unidade_medida = $result_unidade_medida->fetch(PDO::FETCH_ASSOC))
{
    //var_dump($row_categoria);
    extract($row_unidade_medida);

    $registro = [];
    $registro[] = $idUnidade_medidas;
    $registro[] = $Desc_medida;
    $registro[] = $Sigla;
    $registro[] = "<button type='button' id='$idUnidade_medidas' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editUnMedida($idUnidade_medidas)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idUnidade_medidas' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarUnMedida($idUnidade_medidas)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']), //Para cara requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_unidade_medida['qnt_medidas']), //Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_unidade_medida['qnt_medidas']), //Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela unidade_medidas
];

//Retorno os dados em formato de objeto para o Javascript
echo json_encode($resultado);
