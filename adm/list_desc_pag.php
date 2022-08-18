<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idDesc_pag',
    1 => 'Forma_pag',
    2 => 'Quant'
];

//Obter a quantidade de registros do banco de dados

$query_qnt_desc_pag = "SELECT COUNT(idDesc_pag) AS qnt_descpag FROM desc_pag";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_desc_pag .= " WHERE idDesc_pag LIKE :idDesc_pag";
    $query_qnt_desc_pag .= " OR Forma_pag LIKE :Forma_pag ";
    $query_qnt_desc_pag .= " OR Quant LIKE :Quant ";
}

//Preparar a QUERY

$result_qnt_desc_pag = $pdo->prepare($query_qnt_desc_pag);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_qnt_desc_pag->bindValue(':idDesc_pag', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_desc_pag->bindValue(':Forma_pag', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_desc_pag->bindValue(':Quant', $valor_pesq, PDO::PARAM_STR);
}

//Executa a QUERY

$result_qnt_desc_pag->execute();
$row_qnt_desc_pag = $result_qnt_desc_pag->fetch(PDO::FETCH_ASSOC);

//Recuperar os registos do banco de dados

$query_desc_pag = "SELECT idDesc_pag, Forma_pag, Quant FROM desc_pag ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_desc_pag .= " WHERE idDesc_pag LIKE :idDesc_pag";
    $query_desc_pag .= " OR Forma_pag LIKE :Forma_pag ";
    $query_desc_pag .= " OR Quant LIKE :Quant ";
}

//Ordenar os registros
$query_desc_pag .= " ORDER BY " .$colunas[$dados_requisicao['order'][0]['column']] . " " .
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_desc_pag = $pdo->prepare($query_desc_pag);
$result_desc_pag->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_desc_pag->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_desc_pag->bindValue(':idDesc_pag', $valor_pesq, PDO::PARAM_STR);
    $result_desc_pag->bindValue(':Forma_pag', $valor_pesq, PDO::PARAM_STR);
    $result_desc_pag->bindValue(':Quant', $valor_pesq, PDO::PARAM_STR);
}

//Executa a Query

$result_desc_pag->execute();

while($row_desc_pag = $result_desc_pag->fetch(PDO::FETCH_ASSOC))
{
    //var_dump($row_categoria);
    extract($row_desc_pag);

    $registro = [];
    $registro[] = $idDesc_pag;
    $registro[] = $Forma_pag;
    $registro[] = $Quant."x";
    $registro[] = "<button type='button' id='$idDesc_pag' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editDescPag($idDesc_pag)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idDesc_pag' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarDescPag($idDesc_pag)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']), //Para cara requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_desc_pag['qnt_descpag']), //Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_desc_pag['qnt_descpag']), //Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela desc_pag
];

//Retorno os dados em formato de objeto para o Javascript
echo json_encode($resultado);
