<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(empty(trim($dados['marca'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Marca!</div>"];
}
else
{
    $query_marca = "INSERT INTO marca (Desc_Marca) VALUES (:Desc_Marca)";

    $cad_marca = $pdo->prepare($query_marca);
    $cad_marca->bindValue(':Desc_Marca', $dados['marca'], PDO::PARAM_STR);

    try
    {
        if($cad_marca->execute())
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
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> A Marca a ser cadastrada já está sendo ultilizada!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
       
    }
}

echo json_encode($retorna);