<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idFuncionarios = filter_input(INPUT_GET, "idFuncionarios", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idFuncionarios))
{
   $query_delete_funcionarios = "DELETE FROM funcionarios WHERE idFuncionarios=:idFuncionarios";

   $result_delete_funcionarios = $pdo->prepare($query_delete_funcionarios);
   $result_delete_funcionarios->bindValue(":idFuncionarios", $idFuncionarios, PDO::PARAM_INT);
   //$tret = $result_tp_logardouro->execute();

   try
   {
        if($result_delete_funcionarios->execute())
        {
        $retorna = ['status' => true, 'msg' => 
                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Sucesso!</strong> Funcionário apagado com sucesso!
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
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> Funcionário não apagado com sucesso!</div>"];
}

echo json_encode($retorna);