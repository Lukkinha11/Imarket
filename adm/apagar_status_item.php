<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idStatus_item = filter_input(INPUT_GET, "idStatus_item", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idStatus_item))
{
   $query_delete_status_item = "DELETE FROM status_item WHERE idStatus_item=:idStatus_item";

   $result_status_item = $pdo->prepare($query_delete_status_item);
   $result_status_item->bindValue(":idStatus_item", $idStatus_item, PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_status_item->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Status do Item apagado com sucesso!
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
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Status do Item não apagado com sucesso!</div>"];
}

echo json_encode($retorna);