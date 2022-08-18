<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($dados);

if(empty(trim($dados['idunmedida'])))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty(trim($dados['edit_descmedida'])))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Descrição da Unidade de Medida!</div>"];

}elseif(empty(trim($dados['edit_sigla'])))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: reencha o campo Sigla da Unidade de Medida!</div>"];
}
else
{    
    $query_unidade_medidas  = "UPDATE unidade_medidas SET Desc_medida=:Desc_medida, Sigla=:Sigla WHERE idUnidade_medidas=:idUnidade_medidas";

    $edit_unidade_medidas = $pdo->prepare($query_unidade_medidas);
    $edit_unidade_medidas->bindValue(':Desc_medida', $dados['edit_descmedida'], PDO::PARAM_STR);
    $edit_unidade_medidas->bindValue(':Sigla', $dados['edit_sigla'], PDO::PARAM_STR);
    $edit_unidade_medidas->bindValue(':idUnidade_medidas',$dados['idunmedida'], PDO::PARAM_INT);
    
    try
    {
        if($edit_unidade_medidas->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Unidade de Medida editada com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }
    catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O campo Descrição da Unidade de Medida ou Sigla da Unidade de Medida a ser Editado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
        
        
    }
}

echo json_encode($retorna);