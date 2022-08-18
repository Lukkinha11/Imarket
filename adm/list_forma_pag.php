<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idForma_Pag',
    1 => 'Descricao',
    2 => 'Quant',
    3 => 'Desc_pag_idDesc_pag'
];

//Obter a quantidade de registros do banco de dados

$query_qnt_forma_pag = "SELECT COUNT(idForma_Pag) AS qnt_formapag FROM forma_pagamento";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_forma_pag .= " WHERE idForma_Pag LIKE :idForma_Pag";
    $query_qnt_forma_pag .= " OR Descricao LIKE :Descricao ";
    $query_qnt_forma_pag .= " OR Quant LIKE :Quant ";
    $query_qnt_forma_pag .= " OR Desc_pag_idDesc_pag LIKE :Desc_pag_idDesc_pag ";
    
}

//Preparar a QUERY

$result_qnt_forma_pag = $pdo->prepare($query_qnt_forma_pag);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_qnt_forma_pag->bindValue(':idForma_Pag', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_forma_pag->bindValue(':Descricao', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_forma_pag->bindValue(':Quant', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_forma_pag->bindValue(':Desc_pag_idDesc_pag', $valor_pesq, PDO::PARAM_STR);
}

//Executa a QUERY

$result_qnt_forma_pag->execute();
$row_qnt_forma_pag = $result_qnt_forma_pag->fetch(PDO::FETCH_ASSOC);

//Recuperar os registos do banco de dados

$query_forma_pag = "SELECT idForma_Pag, Descricao, Forma_pag, Quant FROM forma_pagamento
                    INNER JOIN desc_pag
                    ON forma_pagamento.Desc_pag_idDesc_pag = desc_pag.idDesc_pag";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_forma_pag .= " WHERE idForma_Pag LIKE :idForma_Pag";
    $query_forma_pag .= " OR Descricao LIKE :Descricao ";
    $query_forma_pag .= " OR Forma_pag LIKE :Forma_pag ";
    $query_forma_pag .= " OR Quant LIKE :Quant ";
}

//Ordenar os registros
$query_forma_pag .= " ORDER BY " .$colunas[$dados_requisicao['order'][0]['column']] . " " .
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_forma_pag = $pdo->prepare($query_forma_pag);
$result_forma_pag->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_forma_pag->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_forma_pag->bindValue(':idForma_Pag', $valor_pesq, PDO::PARAM_STR);
    $result_forma_pag->bindValue(':Descricao', $valor_pesq, PDO::PARAM_STR);
    $result_forma_pag->bindValue(':Forma_pag', $valor_pesq, PDO::PARAM_STR);
    $result_forma_pag->bindValue(':Quant', $valor_pesq, PDO::PARAM_STR);
}

//Executa a Query

$result_forma_pag->execute();

while($row_forma_pag = $result_forma_pag->fetch(PDO::FETCH_ASSOC))
{
    //var_dump($row_categoria);
    extract($row_forma_pag);

    $registro = [];
    $registro[] = $idForma_Pag;
    $registro[] = $Descricao;
    $registro[] = $Forma_pag;
    $registro[] = $Quant;
    $registro[] = "<button type='button' id='$idForma_Pag' class='btn btn-outline-primary'title='Editar Registro'
                        onclick='editFormaPag($idForma_Pag)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$idForma_Pag' class='btn btn-outline-danger' title='Excluir Registro'
                        onclick='apagarFormaPag($idForma_Pag)'><i class='bi bi-file-earmark-x'></i>
                   </button>";
    $dados[] = $registro;
}

//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']), //Para cara requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_forma_pag['qnt_formapag']), //Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_forma_pag['qnt_formapag']), //Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela desc_pag
];

//Retorno os dados em formato de objeto para o Javascript
echo json_encode($resultado);
