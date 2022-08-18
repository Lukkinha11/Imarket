<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela
$colunas = [
    0 => 'idCliente',
    1 => 'Nome',
    2 => 'Sobrenome',
    3 => 'Data_nasc',
    4 => 'Email',
    5 => 'Senha',
    6 => 'CPF',
    7 => 'Telefone',
    8 => 'DDD',
    9 => 'Endereco_idEndereco',
    10 => 'Complemento',
    11 => 'Referencia',
    12 => 'Chave',
    13 => 'Chave_senha',
    14 => 'Situacao_cliente_idSituacao_cliente',
    15 => 'Criado',
    16 => 'Modificado'
];

//Obter a quantidade de resgistros no banco de dados(Contar)

$query_qnt_cliente = "SELECT COUNT(idCliente) AS qnt_cliente FROM cliente";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_cliente .= " WHERE idCliente LIKE :idCliente";
    $query_qnt_cliente .= " OR Nome LIKE :Nome";
    $query_qnt_cliente .= " OR Sobrenome LIKE :Sobrenome";
    $query_qnt_cliente .= " OR Data_nasc LIKE :Data_nasc";
    $query_qnt_cliente .= " OR Email LIKE :Email";
    $query_qnt_cliente .= " OR Senha LIKE :Senha";
    $query_qnt_cliente .= " OR CPF LIKE :CPF";
    $query_qnt_cliente .= " OR Telefone LIKE :Telefone";
    $query_qnt_cliente .= " OR DDD LIKE :DDD";
    $query_qnt_cliente .= " OR Endereco_idEndereco LIKE :Endereco_idEndereco";
    $query_qnt_cliente .= " OR Complemento LIKE :Complemento";
    $query_qnt_cliente .= " OR Referencia LIKE :Referencia";
    $query_qnt_cliente .= " OR Chave LIKE :Chave";
    $query_qnt_cliente .= " OR Chave_senha LIKE :Chave_senha";
    $query_qnt_cliente .= " OR Situacao_cliente_idSituacao_cliente LIKE :Situacao_cliente_idSituacao_cliente";
    $query_qnt_cliente .= " OR Criado LIKE :Criado";
    $query_qnt_cliente .= " OR Modificado LIKE :Modificado";
}

//Preparar a QUERY
$result_qnt_cliente = $pdo->prepare($query_qnt_cliente);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_cliente->bindValue(':idCliente', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_cliente->bindValue(':Nome', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Sobrenome', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Data_nasc', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Email', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Senha', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':CPF', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Telefone', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':DDD', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Endereco_idEndereco', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_cliente->bindValue(':Complemento', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Referencia', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Chave', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Chave_senha', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Situacao_cliente_idSituacao_cliente', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_cliente->bindValue(':Criado', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_cliente->bindValue(':Modificado', $valor_pesq, PDO::PARAM_STR);
}

$result_qnt_cliente->execute();
$row_qnt_cliente = $result_qnt_cliente->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados

$query_cliente = "SELECT idCliente, Nome, Sobrenome, date_format(Data_nasc, '%d/%m/%Y') AS Data_nasc, Email, CPF, DDD, Telefone, CEP, Bairro, Logradouro, Cidade, Estado, Complemento, Referencia FROM cliente
                    INNER JOIN endereco
                    ON endereco.idEndereco = cliente.Endereco_idEndereco";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_cliente .= " WHERE idCliente LIKE :idCliente";
    $query_cliente .= " OR Nome LIKE :Nome";
    $query_cliente .= " OR Sobrenome LIKE :Sobrenome";
    $query_cliente .= " OR Data_nasc LIKE :Data_nasc";
    $query_cliente .= " OR Email LIKE :Email";
    $query_cliente .= " OR CPF LIKE :CPF";
    $query_cliente .= " OR DDD LIKE :DDD";
    $query_cliente .= " OR Telefone LIKE :Telefone";
    $query_cliente .= " OR CEP LIKE :CEP";
    $query_cliente .= " OR Bairro LIKE :Bairro";
    $query_cliente .= " OR Logradouro LIKE :Logradouro";
    $query_cliente .= " OR Cidade LIKE :Cidade";
    $query_cliente .= " OR Estado LIKE :Estado";
    $query_cliente .= " OR Complemento LIKE :Complemento";
    $query_cliente .= " OR Referencia LIKE :Referencia";
}

//Ordenar os registros na tabela

$query_cliente .=" ORDER BY ". $colunas[$dados_requisicao['order'][0]['column']] . " " .
                    $dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_cliente = $pdo->prepare($query_cliente);
$result_cliente->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_cliente->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_cliente->bindValue(':idCliente', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Nome', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Sobrenome', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Data_nasc', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Email', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':CPF', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':DDD', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Telefone', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':CEP', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Bairro', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Logradouro', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Cidade', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Estado', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Complemento', $valor_pesq, PDO::PARAM_STR);
    $result_cliente->bindValue(':Referencia', $valor_pesq, PDO::PARAM_STR);
}

//Executar a QUERY
$result_cliente->execute();

while($row_cliente = $result_cliente->fetch(PDO::FETCH_ASSOC))
{
    extract($row_cliente);

    $registro = [];
    $registro[] = $idCliente;
    $registro[] = $Nome;
    $registro[] = $Sobrenome;
    $registro[] = $Data_nasc;
    $registro[] = $Email;
    $registro[] = $CPF;
    $registro[] = $DDD;
    $registro[] = $Telefone;
    $registro[] = $CEP;
    $registro[] = $Bairro;
    $registro[] = $Logradouro;
    $registro[] = $Cidade;
    $registro[] = $Estado;;
    $registro[] = $Complemento;
    $registro[] = $Referencia;
    $dados[] = $registro;
}

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']),//Para cada requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_cliente['qnt_cliente']),//Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_cliente['qnt_cliente']),//Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela cliente 
];

// Retornar os dados como objeto para o Javascript
echo json_encode($resultado);