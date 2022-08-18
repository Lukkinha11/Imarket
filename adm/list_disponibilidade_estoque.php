<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela

$colunas = [
    0 => 'idCarrinho_Compra',
    1 => 'Cliente_idCliente ',
    2 => 'Forma_Pagamento_idForma_Pag',
    3 => 'Data_Compra',
    4 => 'Status_compra_idStatus_compra'
];

//Obter a quantidade de registros do banco de dados

$query_qnt_itens_carrinho = "SELECT COUNT(idCarrinho_Compra) AS qnt_carrinho FROM carrinho_compra ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_itens_carrinho .= " WHERE idCarrinho_Compra LIKE :idCarrinho_Compra ";
    $query_qnt_itens_carrinho .= " OR Cliente_idCliente LIKE :Cliente_idCliente ";
    $query_qnt_itens_carrinho .= " OR Forma_Pagamento_idForma_Pag LIKE :Forma_Pagamento_idForma_Pag ";
    $query_qnt_itens_carrinho .= " OR Data_Compra LIKE :Data_Compra ";
    $query_qnt_itens_carrinho .= " OR Status_compra_idStatus_compra LIKE :Status_compra_idStatus_compra ";
    
    
}

//Preparar a QUERY

$result_qnt_itens_carrinho = $pdo->prepare($query_qnt_itens_carrinho);

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_qnt_itens_carrinho->bindValue(':idCarrinho_Compra', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_itens_carrinho->bindValue(':Cliente_idCliente', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_itens_carrinho->bindValue(':Forma_Pagamento_idForma_Pag', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_itens_carrinho->bindValue(':Data_Compra', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_itens_carrinho->bindValue(':Status_compra_idStatus_compra', $valor_pesq, PDO::PARAM_INT);
}

//Executa a QUERY

$result_qnt_itens_carrinho->execute();
$row_qnt_itens_carrinho = $result_qnt_itens_carrinho->fetch(PDO::FETCH_ASSOC);

//Recuperar os registos do banco de dados

$query_itens_carrinho = "SELECT idCarrinho_Compra, Nome, DATE_FORMAT(Data_Compra, '%d/%m/%Y') AS Data_Compra, sum(Valor_total) as Valor_total, Status_compra FROM carrinho_compra
                        INNER JOIN cliente
                        ON cliente.idCliente = carrinho_compra.Cliente_idCliente
                        INNER JOIN itens_carrinho
                        ON carrinho_compra.idCarrinho_Compra = itens_carrinho.Carrinho_Compra_idCarrinho_Compra
                        INNER JOIN status_compra
                        ON carrinho_compra.Status_compra_idStatus_compra = status_compra.idStatus_compra
                        WHERE idStatus_compra = 4
                        GROUP BY idCarrinho_Compra, Nome, Data_Compra, Status_compra ";

//Acessa o IF quando há parâmetros de pesquisa

if(!empty($dados_requisicao['search']['value']))
{
    $query_itens_carrinho .= " OR idCarrinho_Compra LIKE :idCarrinho_Compra ";
    $query_itens_carrinho .= " OR Nome LIKE :Nome ";
    $query_itens_carrinho .= " OR Data_Compra LIKE :Data_Compra ";
    $query_itens_carrinho .= " OR Valor_total LIKE :Valor_total ";
    $query_itens_carrinho .= " OR Status_compra LIKE :Status_compra ";
}

//Ordenar os registros
$query_itens_carrinho .= " ORDER BY " .$colunas[$dados_requisicao['order'][0]['column']] . " " .
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_itens_carrinho = $pdo->prepare($query_itens_carrinho);
$result_itens_carrinho->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_itens_carrinho->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";

    $result_itens_carrinho->bindValue(':idCarrinho_Compra', $valor_pesq, PDO::PARAM_STR);
    $result_itens_carrinho->bindValue(':Nome', $valor_pesq, PDO::PARAM_STR);
    $result_itens_carrinho->bindValue(':Data_Compra', $valor_pesq, PDO::PARAM_STR);
    $result_itens_carrinho->bindValue(':Valor_total', $valor_pesq, PDO::PARAM_STR);
    $result_itens_carrinho->bindValue(':Status_compra', $valor_pesq, PDO::PARAM_STR);
}

//Executa a Query

$result_itens_carrinho->execute();

while($row_itens_carrinho = $result_itens_carrinho->fetch(PDO::FETCH_ASSOC))
{
    //var_dump($row_categoria);
    extract($row_itens_carrinho);

    $registro = [];
    $registro[] = $idCarrinho_Compra;
    $registro[] = $Nome;
    $registro[] = $Data_Compra;
    $registro[] = "R$ ". number_format($Valor_total, 2, ',' , '.');
    $registro[] = $Status_compra;
    $registro[] = "<button type='button' id='$idCarrinho_Compra' class='btn btn-outline-success'title='Verificar Disponibilidade'
                        onclick='VerificaEstoque($idCarrinho_Compra)' target=blank><i class='bi bi-check2-square'></i>
                   </button>
                    <a href=?page=venda&id=$idCarrinho_Compra target=_blank class='btn btn-outline-warning' role='button' aria-pressed='true'><i class='bi bi-binoculars-fill'></i></a>";
    $dados[] = $registro;
}

//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript

$resultado = [
    "draw" => intval($dados_requisicao['draw']), //Para cara requisição é enviado um número como parâmetro
    "recordsTotal" => intval($row_qnt_itens_carrinho['qnt_carrinho']), //Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_itens_carrinho['qnt_carrinho']), //Total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela categoria_prod
];

//Retorno os dados em formato de objeto para o Javascript
echo json_encode($resultado);
