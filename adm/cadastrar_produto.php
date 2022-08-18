<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
require_once 'recebe.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$Categoria_dir = $dados['categoria'];

//$arquivo = $_FILES['image'];

if(empty(trim($dados['produto'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Produto!</div>"];
}
elseif(empty(trim($dados['und_medida'])) || $dados['und_medida'] == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione a Unidade de Medida do produto!</div>"];
}
elseif(empty(trim($dados['codigo_barras'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Código de Barras!</div>"];
}
elseif(strlen(trim($dados['codigo_barras'])) !=13)
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Insira um código de barras de 13 caracteres!</div>"];
}
elseif(empty(trim($dados['descricao_prod'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Descrição do Produto!</div>"];
}
elseif(empty(trim($Categoria_dir)) || $Categoria_dir == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione a categoria do produto!</div>"];
}
elseif(empty(trim($dados['marca_produto'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo marca!</div>"];
}
elseif(empty($_FILES['image']['name']))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Defina uma imagem para o produto!</div>"];
}
elseif(!empty($_FILES['image']['name']))
{
    $query_dir = "SELECT idCategoria, Categoria_diretorio FROM categoria_prod WHERE idCategoria=$Categoria_dir";
    $result_dir = $pdo->prepare($query_dir);
    $result_dir->execute();
    $row_dir =  $result_dir->fetch(PDO::FETCH_ASSOC);
    extract($row_dir);

    $diretorio = "../img_prod/$Categoria_diretorio/";

    $t = validaImage($diretorio);
    if(strlen($t[0]) > 20)
    {
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong>".$t[0]."!</div>"];
    }
    else
    {
        $pdo->beginTransaction();

        $query_produto = "INSERT INTO produto (Nome_prod, Desc_prod, Image, Codigo_Barras, Categoria_Prod_idCategoria, Marca_idMarca, unidade_medidas_idUnidade_medidas) 
                                        VALUES (:Nome_prod, :Desc_prod, :Image, :Codigo_Barras, :Categoria_Prod_idCategoria, :Marca_idMarca, :unidade_medidas_idUnidade_medidas)";

        $cad_produto = $pdo->prepare($query_produto);
        $cad_produto->bindValue(':Nome_prod', $dados['produto'], PDO::PARAM_STR);
        $cad_produto->bindValue(':Desc_prod', $dados['descricao_prod'], PDO::PARAM_STR);
        $cad_produto->bindValue(':Image',$t[0], PDO::PARAM_STR);
        $cad_produto->bindValue(':Codigo_Barras', $dados['codigo_barras'], PDO::PARAM_STR);
        $cad_produto->bindValue(':Categoria_Prod_idCategoria', $Categoria_dir, PDO::PARAM_INT);
        $cad_produto->bindValue(':Marca_idMarca', $dados['id_marca'], PDO::PARAM_INT);
        $cad_produto->bindValue(':unidade_medidas_idUnidade_medidas', $dados['und_medida'], PDO::PARAM_INT);

        try
        {
            if($cad_produto->execute())
            {
                $id_produto = $pdo->lastInsertId();

                $query_estoque = "INSERT INTO estoque (Quant_estoque, Produto_Id_Produto)
                                                VALUES(:Quant_estoque, :Produto_Id_Produto)";
                
                $cad_estoque = $pdo->prepare($query_estoque);
                $cad_estoque->bindValue(':Quant_estoque', 0, PDO::PARAM_STR);
                $cad_estoque->bindValue(':Produto_Id_Produto', $id_produto, PDO::PARAM_INT);

                try
                {
                    if($cad_estoque->execute())
                    {
                        $pdo->commit();

                        $retorna = ['status' => true, 'msg' => 
                                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                            <strong>Sucesso!</strong> Cadastro efetuado com sucesso, atribua um preço ao produto para ser mostrado na vitrine!
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>"
                                    ];
                    }
                }
                catch(PDOException $e)
                {
                    $pdo->rollBack();

                    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
                }
            }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();
            
            if( $e->getCode() == 23000)
            {
                $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Produto a ser cadastrado já está sendo ultilizado!</div>"];
            }
            else
            {
                $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
            }
        
        }
    }
    
    //
    //$retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>deu certo:</strong>".$t[0]."</div>"];
}

echo json_encode($retorna);
