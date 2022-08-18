<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$Forma_pag = $dados['edit_formapag'];

//var_dump($dados);

if(empty($dados['idformapag']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty($dados['edit_descpag']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha a Descrição de Pagamento!</div>"]; 

}elseif(empty($Forma_pag) || $Forma_pag == "Selecione")
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Selecione a Forma de Pagamento!</div>"]; 
}
else
{    
    $query_forma_pag  = "UPDATE forma_pagamento SET Descricao=:Descricao, Desc_pag_idDesc_pag=:Desc_pag_idDesc_pag WHERE idForma_Pag=:idForma_Pag";

    $edit_forma_pag = $pdo->prepare($query_forma_pag);
    $edit_forma_pag->bindValue(':Descricao', $dados['edit_descpag'], PDO::PARAM_STR);
    $edit_forma_pag->bindValue(':Desc_pag_idDesc_pag', $Forma_pag, PDO::PARAM_INT);
    $edit_forma_pag->bindValue(':idForma_Pag',$dados['idformapag'], PDO::PARAM_INT);
    
    try
    {
        if($edit_forma_pag->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Forma de Pagemento editada com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }
    catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> A Descrição do Pagamento ou a Forma de Pagamento a ser Editada já está sendo ultilizada!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
        
        
    }
}

echo json_encode($retorna);