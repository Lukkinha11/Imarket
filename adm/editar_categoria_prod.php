<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($dados);

if(empty($dados['idcatprod']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty($dados['catprod']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Categoria!</div>"]; 

}elseif(empty($dados['catdir']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Categoria Diretório!</div>"]; 
}
else
{
    $palavra = $dados['catdir'];

    $Categoria_diretorio = str_replace(" ", "_", $palavra);
    
    $query_categoria  = "UPDATE categoria_prod SET Categoria=:Categoria, Categoria_diretorio=:Categoria_diretorio WHERE idCategoria=:idCategoria";

    $edit_categoria = $pdo->prepare($query_categoria);
    $edit_categoria->bindValue(':Categoria', $dados['catprod'], PDO::PARAM_STR);
    $edit_categoria->bindValue(':Categoria_diretorio', $Categoria_diretorio, PDO::PARAM_STR);
    $edit_categoria->bindValue(':idCategoria',$dados['idcatprod'], PDO::PARAM_INT);
    
    try
    {
        if($edit_categoria->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Categoria editada com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }
    catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> A Categoria ou o Diretótio a ser Editado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
        
        
    }
}

echo json_encode($retorna);