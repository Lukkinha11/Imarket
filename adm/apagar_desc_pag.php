<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idDesc_pag = filter_input(INPUT_GET, "idDesc_pag", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idDesc_pag))
{
   $query_delete_desc_pag = "DELETE FROM desc_pag WHERE idDesc_pag=:idDesc_pag";

   $result_desc_pag = $pdo->prepare($query_delete_desc_pag);
   $result_desc_pag->bindValue(":idDesc_pag", $idDesc_pag, PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_desc_pag->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Descrição de Pagamento apagada com sucesso!
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
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> Descrição de Pagamento não apagada com sucesso!</div>"];
}

echo json_encode($retorna);