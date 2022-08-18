<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($dados);

if(empty($dados['idtstatuscompra']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty(trim($dados['edit_status_compra'])))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Status da Compra!</div>"]; 
}
else
{
    $query_status_compra  = "UPDATE status_compra SET Status_compra=:Status_compra WHERE idStatus_compra=:idStatus_compra";
    $edit_status_compra = $pdo->prepare($query_status_compra);
    $edit_status_compra->bindValue(':Status_compra',$dados['edit_status_compra'], PDO::PARAM_STR);
    $edit_status_compra->bindValue(':idStatus_compra',$dados['idtstatuscompra'], PDO::PARAM_INT);
    
    try
    {
        if($edit_status_compra->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Status da Compra editado com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Status da Compra a ser Editado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
       
    }
}

echo json_encode($retorna);