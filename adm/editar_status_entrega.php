<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($dados);

if(empty($dados['idstatus']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty($dados['status']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Status!</div>"]; 

}
else
{   
    $query_status  = "UPDATE status_entrega SET Status=:Status WHERE idStatus_Entrega=:idStatus_Entrega";

    $edit_status = $pdo->prepare($query_status);
    $edit_status->bindValue(':Status', $dados['status'], PDO::PARAM_STR);
    $edit_status->bindValue(':idStatus_Entrega',$dados['idstatus'], PDO::PARAM_INT);
    
    try
    {
        if($edit_status->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Status da Entrega editada com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }
    catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Status da Entrega a ser Editado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
        
        
    }
}

echo json_encode($retorna);