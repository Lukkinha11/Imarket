<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idForma_Pag = filter_input(INPUT_GET, "idForma_Pag", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idForma_Pag))
{
   $query_delete_forma_pag = "DELETE FROM forma_pagamento WHERE idForma_Pag=:idForma_Pag";

   $result_forma_pag = $pdo->prepare($query_delete_forma_pag);
   $result_forma_pag->bindValue(":idForma_Pag", $idForma_Pag, PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_forma_pag->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Forma de Pagamento apagada com sucesso!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>"
                    ];
        }

   }catch(PDOException $e)
   {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Não é possivel deletar o registro, pois o mesmo possui referencia em outra tabela</div>"];

        }else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
   }

   
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> Forma de Pagamento não apagada com sucesso!</div>"];
}

echo json_encode($retorna);