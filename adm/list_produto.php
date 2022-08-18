<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados_requisicao = $_REQUEST;

//Lista de colunas na tabela
$colunas =[
    0 => 'id_Produto',
    1 => 'Nome_prod',
    2 => 'Desc_prod',
    3 => 'Image',
    4 => 'Codigo_Barras',
    5 => 'Categoria_Prod_idCategoria',
    6 => 'Marca_idMarca',
    7 => 'unidade_medidas_idUnidade_medidas'
];

// Obter a quantidade de resgistros do banco de dados
$query_qnt_produto = "SELECT COUNT(id_Produto) AS qnt_produto FROM produto";

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $query_qnt_produto .= " WHERE id_Produto LIKE :id_Produto ";
    $query_qnt_produto .= " OR Nome_prod LIKE :Nome_prod ";
    $query_qnt_produto .= " OR Desc_prod LIKE :Desc_prod ";
    $query_qnt_produto .= " OR Image LIKE :Image ";
    $query_qnt_produto .= " OR Codigo_Barras LIKE :Codigo_Barras ";
    $query_qnt_produto .= " OR Categoria_Prod_idCategoria LIKE :Categoria_Prod_idCategoria ";
    $query_qnt_produto .= " OR Marca_idMarca LIKE :Marca_idMarca ";
    $query_qnt_produto .= " OR unidade_medidas_idUnidade_medidas LIKE :unidade_medidas_idUnidade_medidas ";
}

//Preparar a QUERY
$result_qnt_produto = $pdo->prepare($query_qnt_produto);

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_qnt_produto->bindValue(':id_Produto', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_produto->bindValue(':Nome_prod', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_produto->bindValue(':Desc_prod', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_produto->bindValue(':Image', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_produto->bindValue(':Codigo_Barras', $valor_pesq, PDO::PARAM_STR);
    $result_qnt_produto->bindValue(':Categoria_Prod_idCategoria', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_produto->bindValue(':Marca_idMarca', $valor_pesq, PDO::PARAM_INT);
    $result_qnt_produto->bindValue(':unidade_medidas_idUnidade_medidas', $valor_pesq, PDO::PARAM_INT);
}

$result_qnt_produto->execute(); 
$row_qnt_produto =  $result_qnt_produto->fetch(PDO::FETCH_ASSOC);

//Recuperar os registros do banco de dados
$query_produto = "SELECT id_Produto, Nome_prod, Desc_prod, Sigla, Image, Codigo_Barras, Categoria, Desc_Marca FROM produto
                    INNER JOIN unidade_medidas
                    ON unidade_medidas.idUnidade_medidas = produto.unidade_medidas_idUnidade_medidas
                    INNER JOIN categoria_prod
                    ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                    INNER JOIN marca
                    ON produto.Marca_idMarca = marca.idMarca";

//Acessa o IF quando há parâmetros de pesquisa
if(!empty($dados_requisicao['search']['value']))
{
    $query_produto .= " WHERE id_Produto LIKE :id_Produto";
    $query_produto .= " OR Nome_prod LIKE :Nome_prod";
    $query_produto .= " OR Desc_prod LIKE :Desc_prod";
    $query_produto .= " OR Sigla LIKE :Sigla";
    $query_produto .= " OR Image LIKE :Image";
    $query_produto .= " OR Codigo_Barras LIKE :Codigo_Barras";
    $query_produto .= " OR Categoria LIKE :Categoria";
    $query_produto .= " OR Desc_Marca LIKE :Desc_Marca";
}

//Ordenar os registros
$query_produto .=" ORDER BY " . $colunas[$dados_requisicao['order'][0]['column']] . " " . 
$dados_requisicao['order'][0]['dir'] . " LIMIT :inicio, :quantidade";

$result_produto = $pdo->prepare($query_produto);
$result_produto->bindValue(':inicio', $dados_requisicao['start'], PDO::PARAM_INT);
$result_produto->bindValue(':quantidade', $dados_requisicao['length'], PDO::PARAM_INT);

//Acessa o IF quando há parâmetros de pesquisa(DEFINIR TODOS COMO PARAM_STR)
if(!empty($dados_requisicao['search']['value']))
{
    $valor_pesq = "%" . $dados_requisicao['search']['value'] . "%";
    $result_produto->bindValue(':id_Produto', $valor_pesq, PDO::PARAM_STR);
    $result_produto->bindValue(':Nome_prod', $valor_pesq, PDO::PARAM_STR);
    $result_produto->bindValue(':Desc_prod', $valor_pesq, PDO::PARAM_STR);
    $result_produto->bindValue(':Sigla', $valor_pesq, PDO::PARAM_STR);
    $result_produto->bindValue(':Image', $valor_pesq, PDO::PARAM_STR);
    $result_produto->bindValue(':Codigo_Barras', $valor_pesq, PDO::PARAM_STR);
    $result_produto->bindValue(':Categoria', $valor_pesq, PDO::PARAM_STR);
    $result_produto->bindValue(':Desc_Marca', $valor_pesq, PDO::PARAM_STR);
}

$result_produto->execute();

while($row_produto = $result_produto->fetch(PDO::FETCH_ASSOC))
{
    extract($row_produto);
    //var_dump($row_tipo_logradouro);

    $registro = [];
    $registro[] = $id_Produto;
    $registro[] = $Nome_prod;
    $registro[] = $Desc_prod;
    $registro[] = $Sigla;
    $registro[] = $Image;
    $registro[] = $Codigo_Barras;
    $registro[] = $Categoria;
    $registro[] = $Desc_Marca;
    $registro[] = "<button type='button' id='$id_Produto' class='btn btn-outline-warning btn-sm'title='Vizualizar Registro'
                        onclick='vizuProduto($id_Produto)'><i class='bi bi-binoculars-fill'></i>
                    </button>
                    <button type='button' id='$id_Produto' class='btn btn-outline-primary btn-sm'title='Editar Registro'
                        onclick='editProduto($id_Produto)'><i class='bi bi-pencil-square'></i>
                   </button>
                   <button type='button' id='$id_Produto' class='btn btn-outline-danger btn-sm' title='Excluir Registro'
                        onclick='apagarProduto($id_Produto)'><i class='bi bi-file-earmark-x'></i>
                   </button>";  
    $dados[] = $registro;

    //<button type='button' id='$idTipo_Logradouro' class='btn btn-outline-primary'
                    //onclick='vizuTipoLogradouro($idTipo_Logradouro)'>Vizualizar</button>
}
//var_dump($dados);

//Cria o array de informações a serem retornadas para o Javascript
$resultado = [
    "draw" => intval($dados_requisicao['draw']), //para cada requisição é enviado um nº como parâmetro
    "recordsTotal" => intval($row_qnt_produto['qnt_produto']), //quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($row_qnt_produto['qnt_produto']), //total de registros quando houver pesquisa
    "data" => $dados //Array de dados com os registros retornados da tabela produto

];

//var_dump($resultado);

//Retornar os dados em formato de objeto para o JavaScript
echo json_encode($resultado);