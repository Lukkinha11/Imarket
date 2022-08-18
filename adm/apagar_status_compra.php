<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idStatus_compra = filter_input(INPUT_GET, "idStatus_compra", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idStatus_compra))
{
   $query_delete_status_compra = "DELETE FROM status_compra WHERE idStatus_compra=:idStatus_compra";

   $result_status_compra = $pdo->prepare($query_delete_status_compra);
   $result_status_compra->bindValue(":idStatus_compra", $idStatus_compra, PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_status_compra->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Status da Compra apagado com sucesso!
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
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Status da Compra não apagado com sucesso!</div>"];
}

echo json_encode($retorna);