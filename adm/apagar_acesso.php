<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idAcesso = filter_input(INPUT_GET, "idAcesso", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idAcesso))
{
   $query_delete_acesso = "DELETE FROM acesso WHERE idAcesso=:idAcesso";

   $result_acesso = $pdo->prepare($query_delete_acesso);
   $result_acesso->bindValue(":idAcesso", $idAcesso, PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_acesso->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Acesso apagado com sucesso!
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
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Nível de Acesso não apagado com sucesso!</div>"];
}

echo json_encode($retorna);