<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela
$colunas =[
    0 => 'idPreco',
    1 => 'Valor_unit',
    2 => 'Valor_prod',
    3 => 'Valor_novo',
    4 => 'Status_preco',
    5 => 'Produto_Id_Produto'
];

// Obter a quantidade de resgistros do banco de dados
$query_qnt_preco = "SELECT COUNT(idPreco) AS qnt_preco FROM preco";

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_preco .= " WHERE idPreco LIKE :idPreco";
    $query_qnt_preco .= " OR Valor_unit LIKE :Valor_unit";
    $query_qnt_preco .= " OR Valor_prod LIKE :Valor_prod";
    $query_qnt_preco .= " OR Valor_novo LIKE :Valor_novo";
    $query_qnt_preco .= " OR Status_preco LIKE :Status_preco";
    $query_qnt_preco .= " OR Produto_Id_Produto LIKE :Produto_Id_Produto";
}

//Preparar a QUERY
$result_qnt_preco = $pdo->prepare($query_qnt_preco);

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_preco->bindValue(':idPreco', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_preco->bindValue(':Valor_unit', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_preco->bindValue(':Valor_prod', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_preco->bindValue(':Valor_novo', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_preco->bindValue(':Status_preco', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_preco->bindValue(':Produto_Id_Produto', $valor_pesq, PDO::PARAM_INT);
}

$result_qnt_preco->execute(); 
$row_qnt_preco =  $result_qnt_preco->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados
$query_preco = "SELECT idPreco, Valor_unit, Valor_prod, Valor_novo, Status_preco, Nome_prod FROM preco
                    INNER JOIN produto
                    ON preco.Produto_Id_Produto = produto.id_Produto";

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $query_preco .= " WHERE idPreco LIKE :idPreco";
    $query_preco .= " OR Valor_unit LIKE :Valor_unit";
    $query_preco .= " OR Valor_prod LIKE :Valor_prod";
    $query_preco .= " OR Valor_novo LIKE :Valor_novo";
    $query_preco .= " OR Status_preco LIKE :Status_preco";
    $query_preco .= " OR Nome_prod LIKE :Nome_prod";
}

//Ordenar os registros
$query_preco .=" ORDER BY " . $colunas[$dados_requisicao['order'][0]['column']] . " " . 
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_preco = $pdo->prepare($query_preco);
$result_preco->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_preco->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_preco->bindValue(':idPreco', $valor_pesq, PDO::PARAM_STR);
    $result_preco->bindValue(':Valor_unit', $valor_pesq, PDO::PARAM_STR);
    $result_preco->bindValue(':Valor_prod', $valor_pesq, PDO::PARAM_STR);
    $result_preco->bindValue(':Valor_novo', $valor_pesq, PDO::PARAM_STR);
    $result_preco->bindValue(':Status_preco', $valor_pesq, PDO::PARAM_STR);
    $result_preco->bindValue(':Nome_prod', $valor_pesq, PDO::PARAM_STR);
}

$result_preco->execute();

while($row_preco = $result_preco->fetch(PDO::FETCH_ASSOC))
{
    extract($row_preco);
    //var_dump($row_tipo_logradouro);

    $registro = [];
    $registro[] = $idPreco;
    $registro[] = "R$ ". number_format($Valor_unit, 2, ',' , '.');
    $registro[] = "R$ ". number_format($Valor_prod, 2, ',' , '.');
    $registro[] = "R$ ". number_format($Valor_novo, 2, ',' , '.');
    $registro[] = $Status_preco;
    $registro[] = $Nome_prod;
    $registro[] = "<button type='button' id='$idPreco' class='btn btn-outline-primary btn-sm'title='Editar Registro'
                        onclick='editPreco($idPreco)'><i class='bi bi-pencil-square'></i>
                    </button>
                    <button type='button' id='$idPreco' class='btn btn-outline-danger btn-sm' title='Excluir Registro'
                        onclick='apagarPreco($idPreco)'><i class='bi bi-file-earmark-x'></i>
                    </button>";  
    $dados[] = $registro;

    //<button type='button' id='$idTipo_Logradouro' class='btn btn-outline-primary'
                    //onclick='vizuTipoLogradouro($idTipo_Logradouro)'>Vizualizar</button>
}
//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript
$resultado = [
    "draw" => intval($dados_requisicao['draw']), //para cada requisição é enviado um nº como parâmetro
    "recordsTotal" => intval($row_qnt_preco['qnt_preco']), //quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_preco['qnt_preco']), //total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela preço

];

//var_dump($resultado);

//Retornar os dados em formato de objeto para o JavaScript
echo json_encode($resultado);