<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idCargo  ',
    1 => 'Desc_cargo  ',
    2 => 'Salario '
];

//Obter a quantidade de registros do banco de dados

$query_qnt_cargo = "SELECT COUNT(idCargo) AS qnt_cargo FROM cargo";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_cargo .= " WHERE idCargo LIKE :idCargo";
    $query_qnt_cargo .= " OR Desc_cargo LIKE :Desc_cargo ";
    $query_qnt_cargo .= " OR Salario LIKE :Salario ";
}

//Preparar a QUERY

$result_qnt_cargo = $pdo->prepare($query_qnt_cargo);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_qnt_cargo->bindValue(':idCargo', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_cargo->bindValue(':Desc_cargo', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cargo->bindValue(':Salario', $valor_pesq, PDO::PARAM_STR);
}

//Executa a QUERY

$result_qnt_cargo->execute();
$row_qnt_cargo = $result_qnt_cargo->fetch(PDO::FETCH_ASSOC);

//Recuperar os registos do banco de dados

$query_cargo = "SELECT idCargo, Desc_cargo, Salario FROM cargo ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_cargo .= " WHERE idCargo LIKE :idCargo";
    $query_cargo .= " OR Desc_cargo LIKE :Desc_cargo ";
    $query_cargo .= " OR Salario LIKE :Salario ";
}

//Ordenar os registros
$query_cargo .= " ORDER BY " .$colunas[$dados_requisicao['order'][0]['column']] . " " .
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_cargo = $pdo->prepare($query_cargo);
$result_cargo->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_cargo->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_cargo->bindValue(':idCargo', $valor_pesq, PDO::PARAM_STR);
    $result_cargo->bindValue(':Desc_cargo', $valor_pesq, PDO::PARAM_STR);
    $result_cargo->bindValue(':Salario', $valor_pesq, PDO::PARAM_STR);
}

//Executa a Query

$result_cargo->execute();

while($row_cargo = $result_cargo->fetch(PDO::FETCH_ASSOC))
{
    //var_dump($row_categoria);
    extract($row_cargo);

    $registro = [];
    $registro[] = $idCargo;
    $registro[] = $Desc_cargo;
    $registro[] = "R$ ". number_format($Salario, 2, ',' , '.');
    $registro[] = "<button type='button' id='$idCargo' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editCargo($idCargo)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idCargo' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarCargo($idCargo)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']), //Para cara requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_cargo['qnt_cargo']), //Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_cargo['qnt_cargo']), //Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela cargo
];

//Retorno os dados em formato de objeto para o Javascript
echo json_encode($resultado);
