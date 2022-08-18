<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$Forma_pag = $dados['formapag'];

if(empty(trim($dados['descpag'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Descrição do Pagamento!</div>"];
}
elseif(empty($Forma_pag) || $Forma_pag == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione a Forma de Pagamento!</div>"];
}
else
{   
    $query_forma_pag = "INSERT INTO forma_pagamento (Descricao, Desc_pag_idDesc_pag) VALUES (:Descricao, :Desc_pag_idDesc_pag)";

    $cad_forma_pag = $pdo->prepare($query_forma_pag);
    $cad_forma_pag->bindValue(':Descricao', $dados['descpag'], PDO::PARAM_STR);
    $cad_forma_pag->bindValue(':Desc_pag_idDesc_pag', $Forma_pag, PDO::PARAM_INT);


    try
    {
        if($cad_forma_pag->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Cadastro efetuado com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }
    catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> A Descrição do Pagamento ou a Forma de Pagamento a ser cadastrada já está sendo ultilizada!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
       
    }
}

echo json_encode($retorna);