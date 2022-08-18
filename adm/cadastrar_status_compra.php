<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(empty(trim($dados['status_compra'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Status Compra!</div>"];
}
else
{
    $query_status_compra = "INSERT INTO status_compra(Status_compra ) VALUES(:Status_compra)";

    $cad_status_compra = $pdo->prepare($query_status_compra);
    $cad_status_compra->bindValue(':Status_compra', $dados['status_compra'], PDO::PARAM_STR);

    try
    {
        if($cad_status_compra->execute())
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
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Status da Compra a ser cadastrado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        } 
    }
}

echo json_encode($retorna);