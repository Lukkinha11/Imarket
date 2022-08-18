<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idStatus_Entrega = filter_input(INPUT_GET, "idStatus_Entrega", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idStatus_Entrega))
{
   $query_delete_status = "DELETE FROM status_entrega WHERE idStatus_Entrega=:idStatus_Entrega";

   $result_status = $pdo->prepare($query_delete_status);
   $result_status->bindValue(":idStatus_Entrega", $idStatus_Entrega, PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_status->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Status da Entrega apagada com sucesso!
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
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> Status da Entrega não apagada com sucesso!</div>"];
}

echo json_encode($retorna);