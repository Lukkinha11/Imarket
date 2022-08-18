<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
require_once 'recebe.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$Categoria_dir = $dados['editcategoria'];

$Nome_img = $dados['nome_img'];

//var_dump($dados);

if(empty($dados['idproduto']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty($dados['editnome_produto']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Produto!</div>"]; 
}
elseif(empty(trim($dados['edit_und_medida'])) || $dados['edit_und_medida'] == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione a Unidade de Medida do produto!</div>"];
}
elseif(empty($dados['editnome_cdbarras']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Código de Barras!</div>"]; 
}
elseif(strlen($dados['editnome_cdbarras']) !=13)
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Insira um código de barras de 13 caracteres!</div>"]; 
}
elseif(empty($dados['editnome_descprod']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Descrição do Produto!</div>"]; 
}
elseif(empty($Categoria_dir) || $Categoria_dir == "Selecione")
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Selecione uma categoria!</div>"]; 
}
elseif(empty($dados['editnome_marcaprod']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Marca!</div>"]; 
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
        
        $query_produto  = "UPDATE produto SET Nome_prod=:Nome_prod, Desc_prod=:Desc_prod, Image=:Image, Codigo_Barras=:Codigo_Barras, Categoria_Prod_idCategoria=:Categoria_Prod_idCategoria, 
                                              Marca_idMarca=:Marca_idMarca, unidade_medidas_idUnidade_medidas=:unidade_medidas_idUnidade_medidas  WHERE id_Produto=:id_Produto";
        $edit_produto = $pdo->prepare($query_produto);
        $edit_produto->bindValue(':Nome_prod',$dados['editnome_produto'], PDO::PARAM_STR);
        $edit_produto->bindValue(':Desc_prod',$dados['editnome_descprod'], PDO::PARAM_STR);
        $edit_produto->bindValue(':Image',$t[0], PDO::PARAM_STR);
        $edit_produto->bindValue(':Codigo_Barras',$dados['editnome_cdbarras'], PDO::PARAM_STR);
        $edit_produto->bindValue(':Categoria_Prod_idCategoria',$Categoria_dir, PDO::PARAM_INT);
        $edit_produto->bindValue(':Marca_idMarca',$dados['id_marcaedit'], PDO::PARAM_INT);
        $edit_produto->bindValue(':unidade_medidas_idUnidade_medidas',$dados['edit_und_medida'], PDO::PARAM_INT);
        $edit_produto->bindValue(':id_Produto',$dados['idproduto'], PDO::PARAM_INT);
        
        try
        {
            if($edit_produto->execute())
            {
                $retorna = ['status' => true, 'msg' => 
                                "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>Sucesso!</strong> Produto editado com sucesso!
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>"
                            ];

               if(file_exists($diretorio.$Nome_img))
               {
                    unlink($diretorio.$Nome_img);
               }
               else
               {
                    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> Arquivo não encontrado</div>"];
               }
                                        


            }
        }catch(PDOException $e)
        {
            if( $e->getCode() == 23000)
            {
                $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Produto a ser Editado já está sendo ultilizado!</div>"];
            }
            else
            {
                $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
            }
        
        }
    }
}elseif(empty($_FILES['image']['name']))
{
    $query_produto  = "UPDATE produto SET Nome_prod=:Nome_prod, Desc_prod=:Desc_prod, Codigo_Barras=:Codigo_Barras, Categoria_Prod_idCategoria=:Categoria_Prod_idCategoria, 
                                            Marca_idMarca=:Marca_idMarca, unidade_medidas_idUnidade_medidas=:unidade_medidas_idUnidade_medidas  WHERE id_Produto=:id_Produto";
    $edit_produto = $pdo->prepare($query_produto);
    $edit_produto->bindValue(':Nome_prod',$dados['editnome_produto'], PDO::PARAM_STR);
    $edit_produto->bindValue(':Desc_prod',$dados['editnome_descprod'], PDO::PARAM_STR);
    $edit_produto->bindValue(':Codigo_Barras',$dados['editnome_cdbarras'], PDO::PARAM_STR);
    $edit_produto->bindValue(':Categoria_Prod_idCategoria',$Categoria_dir, PDO::PARAM_INT);
    $edit_produto->bindValue(':Marca_idMarca',$dados['id_marcaedit'], PDO::PARAM_INT);
    $edit_produto->bindValue(':unidade_medidas_idUnidade_medidas',$dados['edit_und_medida'], PDO::PARAM_INT);
    $edit_produto->bindValue(':id_Produto',$dados['idproduto'], PDO::PARAM_INT);
    
    try
    {
        if($edit_produto->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Produto editada com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Produto a ser Editado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
    
    }
}

echo json_encode($retorna);