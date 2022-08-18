<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idSituacao_cliente = filter_input(INPUT_GET, "idSituacao_cliente", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idSituacao_cliente))
{
   $query_delete_situacao_cliente = "DELETE FROM situacao_cliente WHERE idSituacao_cliente=:idSituacao_cliente";

   $result_situacao_cliente = $pdo->prepare($query_delete_situacao_cliente);
   $result_situacao_cliente->bindValue(":idSituacao_cliente", $idSituacao_cliente, PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_situacao_cliente->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Status do Cliente apagado com sucesso!
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
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> Status do Cliente não apagado com sucesso!</div>"];
}

echo json_encode($retorna);