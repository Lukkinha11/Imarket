<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(empty(trim($dados['status'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Status!</div>"];
}
else
{
    $query_status = "INSERT INTO status_entrega (Status) VALUES (:Status)";

    $cad_status = $pdo->prepare($query_status);
    $cad_status->bindValue(':Status', $dados['status'], PDO::PARAM_STR);

    try
    {
        if($cad_status->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Cadastro efetuado com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }
    catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Status da Entrega a ser cadastrado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
       
    }
}

echo json_encode($retorna);