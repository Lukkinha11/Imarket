<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idFornecedor = filter_input(INPUT_GET, "idFornecedor", FILTER_SANITIZE_NUMBER_INT);

//$idFornecedor = "";

if(!empty($idFornecedor))
{
   $query_delete_fornecedor = "DELETE FROM fornecedor WHERE idFornecedor=:idFornecedor";

   $result_fornecedor = $pdo->prepare($query_delete_fornecedor);
   $result_fornecedor->bindValue(":idFornecedor", $idFornecedor, PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_fornecedor->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Fornecedor apagado com sucesso!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>"
                    ];
        }
   }
   catch(PDOException $e)
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
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Fornecedor não apagado com sucesso!</div>"];
}

echo json_encode($retorna);