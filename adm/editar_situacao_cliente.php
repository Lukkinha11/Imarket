<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($dados);

if(empty($dados['idsitcli']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty($dados['editstatus_cli']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Status do Cliente!</div>"]; 

}
else
{   
    $query_situacao_cliente  = "UPDATE situacao_cliente SET Nome_situacao =:Nome_situacao WHERE idSituacao_cliente =:idSituacao_cliente ";

    $edit_situacao_cliente = $pdo->prepare($query_situacao_cliente);
    $edit_situacao_cliente->bindValue(':Nome_situacao', $dados['editstatus_cli'], PDO::PARAM_STR);
    $edit_situacao_cliente->bindValue(':idSituacao_cliente',$dados['idsitcli'], PDO::PARAM_INT);
    
    try
    {
        if($edit_situacao_cliente->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Status do Cliente editado com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }
    catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Status do Cliente a ser Editado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
        
        
    }
}

echo json_encode($retorna);