<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$id_Produto = filter_input(INPUT_GET, "id_Produto", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";


if(!empty($id_Produto))
{
    //Select para recuperar o id do estoque
    $query_estoque = "SELECT idEstoque FROM estoque WHERE Produto_Id_Produto=:Produto_Id_Produto";
    $result_estoque = $pdo->prepare($query_estoque);
    $result_estoque->bindValue(":Produto_Id_Produto", $id_Produto, PDO::PARAM_INT);
    $result_estoque->execute();

    $row_estoque = $result_estoque->fetch(PDO::FETCH_ASSOC);
    extract($row_estoque);
    
    //Excluão do produto na tabela de estoque
    $query_delete_estoque = "DELETE FROM estoque WHERE idEstoque=:idEstoque";
    $result_delete_estoque = $pdo->prepare($query_delete_estoque);
    $result_delete_estoque->bindValue(":idEstoque", $idEstoque, PDO::PARAM_INT);

    try
    {
        if($result_delete_estoque->execute())
        {
            //Select para excluir a imagem do produto na categoria_diretorio do mesmo
            $query_img = "SELECT id_Produto, Image, concat('../img_prod/',Categoria_diretorio,'/',Image) AS caminho FROM produto
                            INNER JOIN categoria_prod
                            ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                            WHERE id_Produto=:id_Produto
                            LIMIT 1";
    
            $result_img = $pdo->prepare($query_img);
            $result_img->bindValue(":id_Produto", $id_Produto, PDO::PARAM_INT);
            $result_img->execute();
    
            $row_img = $result_img->fetch(PDO::FETCH_ASSOC);
            extract($row_img);
        
            
            //Exclusão do produto
            $query_delete_produto = "DELETE FROM produto WHERE id_Produto=:id_Produto";
    
            $result_produto = $pdo->prepare($query_delete_produto);
            $result_produto->bindValue(":id_Produto", $id_Produto, PDO::PARAM_INT);

            try
            {
                if($result_produto->execute())
                {
                    $retorna = ['status' => true, 'msg' => 
                                    "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                        <strong>Sucesso!</strong> Produto apagado com sucesso!
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>"
                                ];
    
                    if(file_exists($caminho))
                    {
                        unlink($caminho);
                    }
                    else
                    {
                        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> Arquivo não encontrado</div>"];
                    }
                }
            }
            catch(PDOException $e)
            {
                if( $e->getCode() == 23000)
                {
                    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Não é possivel deletar o registro, pois o mesmo possui referencia em outra tabela</div>"];
                }
                else
                {
                    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
                }
            }
        }
    }
    catch(PDOException $e)
    {
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no estoque: ". $e->getMessage() ."</div>"];
        }
    }  
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> Produto não apagado com sucesso!</div>"];
}

echo json_encode($retorna);