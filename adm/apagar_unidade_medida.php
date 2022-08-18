<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idUnidade_medidas = filter_input(INPUT_GET, "idUnidade_medidas", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idUnidade_medidas))
{
   $query_delete_medida = "DELETE FROM unidade_medidas WHERE idUnidade_medidas =:idUnidade_medidas";

   $result_medida = $pdo->prepare($query_delete_medida);
   $result_medida->bindValue(":idUnidade_medidas", $idUnidade_medidas , PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_medida->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Unidade de Medida apagada com sucesso!
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
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> Unidade de Medida não apagada com sucesso!</div>"];
}

echo json_encode($retorna);