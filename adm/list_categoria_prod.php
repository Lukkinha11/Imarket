<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idCategoria',
    1 => 'Categoria',
    2 => 'Categoria_diretorio'
];

//Obter a quantidade de registros do banco de dados

$query_qnt_categoria = "SELECT COUNT(idCategoria) AS qnt_categoria FROM categoria_prod";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_categoria .= " WHERE idCategoria LIKE :idCategoria";
    $query_qnt_categoria .= " OR Categoria LIKE :Categoria ";
    $query_qnt_categoria .= " OR Categoria_diretorio LIKE :Categoria_diretorio ";
}

//Preparar a QUERY

$result_qnt_categoria = $pdo->prepare($query_qnt_categoria);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_qnt_categoria->bindValue(':idCategoria', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_categoria->bindValue(':Categoria', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_categoria->bindValue(':Categoria_diretorio', $valor_pesq, PDO::PARAM_STR);
}

//Executa a QUERY

$result_qnt_categoria->execute();
$row_qnt_categoria = $result_qnt_categoria->fetch(PDO::FETCH_ASSOC);

//Recuperar os registos do banco de dados

$query_categoria = "SELECT idCategoria, Categoria, Categoria_diretorio FROM categoria_prod ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_categoria .= " WHERE idCategoria LIKE :idCategoria";
    $query_categoria .= " OR Categoria LIKE :Categoria ";
    $query_categoria .= " OR Categoria_diretorio LIKE :Categoria_diretorio ";
}

//Ordenar os registros
$query_categoria .= " ORDER BY " .$colunas[$dados_requisicao['order'][0]['column']] . " " .
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_categoria = $pdo->prepare($query_categoria);
$result_categoria->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_categoria->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_categoria->bindValue(':idCategoria', $valor_pesq, PDO::PARAM_STR);
    $result_categoria->bindValue(':Categoria', $valor_pesq, PDO::PARAM_STR);
    $result_categoria->bindValue(':Categoria_diretorio', $valor_pesq, PDO::PARAM_STR);
}

//Executa a Query

$result_categoria->execute();

while($row_categoria = $result_categoria->fetch(PDO::FETCH_ASSOC))
{
    //var_dump($row_categoria);
    extract($row_categoria);

    $registro = [];
    $registro[] = $idCategoria;
    $registro[] = $Categoria;
    $registro[] = $Categoria_diretorio;
    $registro[] = "<button type='button' id='$idCategoria' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editCategoria($idCategoria)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idCategoria' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarCategoria($idCategoria)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']), //Para cara requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_categoria['qnt_categoria']), //Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_categoria['qnt_categoria']), //Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela categoria_prod
];

//Retorno os dados em formato de objeto para o Javascript
echo json_encode($resultado);
