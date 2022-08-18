<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela
$colunas =[
    0 => 'idFornecedor',
    1 => 'Nome_Fantasia',
    2 => 'Razao_social',
    3 => 'CNPJ',
    4 => 'Telefone',
    5 => 'DDD',
    6 => 'Email',
    7 => 'Complemento',
    8 => 'Endereco_idEndereco',
    9 => 'Referencia'
];

// Obter a quantidade de resgistros do banco de dados
$query_qnt_fornecedor = "SELECT COUNT(idFornecedor) AS qnt_fornecedor FROM fornecedor";

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_fornecedor .= " WHERE idFornecedor LIKE :idFornecedor ";
    $query_qnt_fornecedor .= " OR Nome_Fantasia LIKE :Nome_Fantasia ";
    $query_qnt_fornecedor .= " OR Razao_social LIKE :Razao_social ";
    $query_qnt_fornecedor .= " OR CNPJ LIKE :CNPJ ";
    $query_qnt_fornecedor .= " OR Telefone LIKE :Telefone ";
    $query_qnt_fornecedor .= " OR DDD LIKE :DDD ";
    $query_qnt_fornecedor .= " OR Email LIKE :Email ";
    $query_qnt_fornecedor .= " OR Complemento LIKE :Complemento ";
    $query_qnt_fornecedor .= " OR Endereco_idEndereco LIKE :Endereco_idEndereco ";
    $query_qnt_fornecedor .= " OR Referencia LIKE :Referencia ";
}

//Preparar a QUERY
$result_qnt_fornecedor = $pdo->prepare($query_qnt_fornecedor);

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_fornecedor->bindValue(':idFornecedor', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_fornecedor->bindValue(':Nome_Fantasia', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_fornecedor->bindValue(':Razao_social', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_fornecedor->bindValue(':CNPJ', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_fornecedor->bindValue(':Telefone', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_fornecedor->bindValue(':DDD', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_fornecedor->bindValue(':Email', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_fornecedor->bindValue(':Complemento', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_fornecedor->bindValue(':Endereco_idEndereco', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_fornecedor->bindValue(':Referencia', $valor_pesq, PDO::PARAM_STR);
    
}

$result_qnt_fornecedor->execute(); 
$row_qnt_fornecedor =  $result_qnt_fornecedor->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados
$query_fornecedor = "SELECT idFornecedor, Nome_Fantasia, Razao_social, CNPJ, DDD, Telefone, Email, Cep, Logradouro, Bairro, Cidade, Estado, Complemento, Referencia FROM fornecedor
                        INNER JOIN endereco
                        ON fornecedor.Endereco_idEndereco = endereco.idEndereco ";

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $query_fornecedor .= " WHERE idFornecedor LIKE :idFornecedor ";
    $query_fornecedor .= " OR Nome_Fantasia LIKE :Nome_Fantasia ";
    $query_fornecedor .= " OR Razao_social LIKE :Razao_social ";
    $query_fornecedor .= " OR CNPJ LIKE :CNPJ ";
    $query_fornecedor .= " OR DDD LIKE :DDD ";
    $query_fornecedor .= " OR Telefone LIKE :Telefone ";
    $query_fornecedor .= " OR Email LIKE :Email ";
    $query_fornecedor .= " OR Cep LIKE :Cep ";
    $query_fornecedor .= " OR Logradouro LIKE :Logradouro" ;
    $query_fornecedor .= " OR Bairro LIKE :Bairro ";
    $query_fornecedor .= " OR Cidade LIKE :Cidade ";
    $query_fornecedor .= " OR Estado LIKE :Estado ";
    $query_fornecedor .= " OR Complemento LIKE :Complemento ";
    $query_fornecedor .= " OR Referencia LIKE :Referencia ";
}

//Ordenar os registros
$query_fornecedor .=" ORDER BY " . $colunas[$dados_requisicao['order'][0]['column']] . " " . 
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_fornecedor = $pdo->prepare($query_fornecedor);
$result_fornecedor->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_fornecedor->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_fornecedor->bindValue(':idFornecedor', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Nome_Fantasia', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Razao_social', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':CNPJ', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':DDD', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Telefone', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Email', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Cep', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Logradouro', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Bairro', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Cidade', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Estado', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Complemento', $valor_pesq, PDO::PARAM_STR);
    $result_fornecedor->bindValue(':Referencia', $valor_pesq, PDO::PARAM_STR);
    
}

$result_fornecedor->execute();

while($row_preco = $result_fornecedor->fetch(PDO::FETCH_ASSOC))
{
    extract($row_preco);
    //var_dump($row_tipo_logradouro);

    $registro = [];
    $registro[] = $idFornecedor;
    $registro[] = $Nome_Fantasia;
    $registro[] = $Razao_social;
    $registro[] = $CNPJ;
    $registro[] = $DDD;
    $registro[] = $Telefone; 
    $registro[] = $Email; 
    $registro[] = $Cep; 
    $registro[] = $Logradouro;  
    $registro[] = $Bairro;
    $registro[] = $Cidade;
    $registro[] = $Estado;
    $registro[] = $Complemento;
    $registro[] = $Referencia;
    $registro[] = "<button type='button' id='$idFornecedor' class='btn btn-outline-primary btn-sm edit'title='Editar Registro'
                        onclick='editFornecedor($idFornecedor)'><i class='bi bi-pencil-square'></i>
                    </button>
                    <button type='button' id='$idFornecedor' class='btn btn-outline-danger btn-sm mt-1' title='Excluir Registro'
                        onclick='apagarFornecedor($idFornecedor)'><i class='bi bi-file-earmark-x'></i>
                    </button>";  
    $dados[] = $registro;

    //<button type='button' id='$idTipo_Logradouro' class='btn btn-outline-primary'
                    //onclick='vizuTipoLogradouro($idTipo_Logradouro)'>Vizualizar</button>
}
//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript
$resultado = [
    "draw" => intval($dados_requisicao['draw']), //para cada requisição é enviado um nº como parâmetro
    "recordsTotal" => intval($row_qnt_fornecedor['qnt_fornecedor']), //quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_fornecedor['qnt_fornecedor']), //total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela fornecedores

];

//var_dump($resultado);

//Retornar os dados em formato de objeto para o JavaScript
echo json_encode($resultado);