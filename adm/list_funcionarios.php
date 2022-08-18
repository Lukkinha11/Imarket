<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela
$colunas =[
    0 => 'idFuncionarios',
    1 => 'Matricula',
    2 => 'Nome',
    3 => 'Sobrenome',
    4 => 'Data_nasc',
    5 => 'Cargo_idCargo',
    6 => 'Login',
    7 => 'Senha',
    8 => 'Email',
    9 => 'CPF',
    10 => 'Telefone',
    11 => 'DDD',
    12 => 'Endereco_idEndereco',
    13 => 'Complemento',
    14 => 'Status',
    15 => 'Admissao'
];

// Obter a quantidade de resgistros do banco de dados
$query_qnt_funcionarios = "SELECT COUNT(idFuncionarios) AS qnt_funcionarios FROM funcionarios";

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_funcionarios .= " WHERE idFuncionarios LIKE :idFuncionarios";
    $query_qnt_funcionarios .= " OR Matricula LIKE :Matricula";
    $query_qnt_funcionarios .= " OR Nome LIKE :Nome";
    $query_qnt_funcionarios .= " OR Sobrenome LIKE :Sobrenome";
    $query_qnt_funcionarios .= " OR Data_nasc LIKE :Data_nasc";
    $query_qnt_funcionarios .= " OR Cargo_idCargo LIKE :Cargo_idCargo";
    $query_qnt_funcionarios .= " OR Login LIKE :Login";
    $query_qnt_funcionarios .= " OR Email LIKE :Email";
    $query_qnt_funcionarios .= " OR CPF LIKE :CPF";
    $query_qnt_funcionarios .= " OR Telefone LIKE :Telefone";
    $query_qnt_funcionarios .= " OR DDD LIKE :DDD";
    $query_qnt_funcionarios .= " OR Endereco_idEndereco LIKE :Endereco_idEndereco";
    $query_qnt_funcionarios .= " OR Complemento LIKE :Complemento";
    $query_qnt_funcionarios .= " OR Status LIKE :Status";
    $query_qnt_funcionarios .= " OR Admissao LIKE :Admissao";
}

//Preparar a QUERY
$result_qnt_funcionarios = $pdo->prepare($query_qnt_funcionarios);

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_funcionarios->bindValue(':idFuncionarios', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_funcionarios->bindValue(':Matricula', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':Nome', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':Sobrenome', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':Data_nasc', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':Cargo_idCargo', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_funcionarios->bindValue(':Login', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':Email', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':CPF', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':Telefone', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':DDD', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':Endereco_idEndereco', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_funcionarios->bindValue(':Complemento', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':Status', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_funcionarios->bindValue(':Admissao', $valor_pesq, PDO::PARAM_STR);
    
}

$result_qnt_funcionarios->execute(); 
$row_qnt_funcionarios =  $result_qnt_funcionarios->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados
$query_funcionarios = "SELECT idFuncionarios, Matricula, Nome, Sobrenome, date_format(Data_nasc, '%d/%m/%Y') AS Data_nasc, Desc_cargo, Login, Senha, CPF, Email, Telefone, DDD, Cep, Logradouro, Bairro, Cidade, Estado, Complemento, Status, date_format(Admissao, '%d/%m/%Y') AS Admissao FROM funcionarios
                        INNER JOIN cargo
                        ON funcionarios.Cargo_idCargo = cargo.idCargo
                        INNER JOIN endereco
                        ON funcionarios.Endereco_idEndereco = endereco.idEndereco";

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $query_funcionarios .= " WHERE idFuncionarios LIKE :idFuncionarios";
    $query_funcionarios .= " OR Matricula LIKE :Matricula";
    $query_funcionarios .= " OR Nome LIKE :Nome";
    $query_funcionarios .= " OR Sobrenome LIKE :Sobrenome";
    $query_funcionarios .= " OR Data_nasc LIKE :Data_nasc";
    $query_funcionarios .= " OR Desc_cargo LIKE :Desc_cargo";
    $query_funcionarios .= " OR Login LIKE :Login";
    $query_funcionarios .= " OR CPF LIKE :CPF";
    $query_funcionarios .= " OR Email LIKE :Email";
    $query_funcionarios .= " OR Telefone LIKE :Telefone";
    $query_funcionarios .= " OR DDD LIKE :DDD";
    $query_funcionarios .= " OR Cep LIKE :Cep";
    $query_funcionarios .= " OR Logradouro LIKE :Logradouro";
    $query_funcionarios .= " OR Bairro LIKE :Bairro";
    $query_funcionarios .= " OR Cidade LIKE :Cidade";
    $query_funcionarios .= " OR Estado LIKE :Estado";
    $query_funcionarios .= " OR Complemento LIKE :Complemento";
    $query_funcionarios .= " OR Status LIKE :Status";
    $query_funcionarios .= " OR Admissao LIKE :Admissao";
}

//Ordenar os registros
$query_funcionarios .=" ORDER BY " . $colunas[$dados_requisicao['order'][0]['column']] . " " . 
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_funcionarios = $pdo->prepare($query_funcionarios);
$result_funcionarios->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_funcionarios->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_funcionarios->bindValue(':idFuncionarios', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Matricula', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Nome', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Sobrenome', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Data_nasc', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Desc_cargo', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Login', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':CPF', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Email', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Telefone', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':DDD', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Cep', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Logradouro', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Bairro', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Cidade', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Estado', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Complemento', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Status', $valor_pesq, PDO::PARAM_STR);
    $result_funcionarios->bindValue(':Admissao', $valor_pesq, PDO::PARAM_STR);
}

$result_funcionarios->execute();

while($row_preco = $result_funcionarios->fetch(PDO::FETCH_ASSOC))
{
    extract($row_preco);
    //var_dump($row_tipo_logradouro);

    $registro = [];
    $registro[] = $idFuncionarios;
    $registro[] = $Matricula;
    $registro[] = $Nome;
    $registro[] = $Sobrenome;
    $registro[] = $Data_nasc;
    $registro[] = $Desc_cargo;
    $registro[] = $Login; 
    $registro[] = $CPF; 
    $registro[] = $Email; 
    $registro[] = $Telefone; 
    $registro[] = $DDD; 
    $registro[] = $Cep; 
    $registro[] = $Logradouro;  
    $registro[] = $Bairro;
    $registro[] = $Cidade;
    $registro[] = $Estado;
    $registro[] = $Complemento;
    $registro[] = $Status;
    $registro[] = $Admissao;
    $registro[] = "<button type='button' id='$idFuncionarios' class='btn btn-outline-primary btn-sm'title='Editar Registro'
                        onclick='editFunc($idFuncionarios)'><i class='bi bi-pencil-square'></i>
                    </button>
                    <button type='button' id='$idFuncionarios' class='btn btn-outline-danger btn-sm mt-1' title='Excluir Registro'
                        onclick='apagarFunc($idFuncionarios)'><i class='bi bi-file-earmark-x'></i>
                    </button>";  
    $dados[] = $registro;

    //<button type='button' id='$idTipo_Logradouro' class='btn btn-outline-primary'
                    //onclick='vizuTipoLogradouro($idTipo_Logradouro)'>Vizualizar</button>
}
//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript
$resultado = [
    "draw" => intval($dados_requisicao['draw']), //para cada requisição é enviado um nº como parâmetro
    "recordsTotal" => intval($row_qnt_funcionarios['qnt_funcionarios']), //quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_funcionarios['qnt_funcionarios']), //total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela funcionários

];

//var_dump($resultado);

//Retornar os dados em formato de objeto para o JavaScript
echo json_encode($resultado);