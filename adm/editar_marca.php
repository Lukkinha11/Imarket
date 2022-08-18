<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($dados);

if(empty($dados['idmarca']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty($dados['marca']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Marca!</div>"]; 
}
else
{
    $query_marca  = "UPDATE marca SET Desc_Marca=:Desc_Marca WHERE idMarca=:idMarca";
    $edit_marca = $pdo->prepare($query_marca);
    $edit_marca->bindValue(':Desc_Marca',$dados['marca'], PDO::PARAM_STR);
    $edit_marca->bindValue(':idMarca',$dados['idmarca'], PDO::PARAM_INT);
    
    try
    {
        if($edit_marca->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Marca editada com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> A Marca a ser Editada já está sendo ultilizada!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
       
    }
}

echo json_encode($retorna);