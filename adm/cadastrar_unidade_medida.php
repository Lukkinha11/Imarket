<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(empty(trim($dados['nome_descmedida'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Descrição da Unidade de Medida!</div>"];
}
elseif(empty(trim($dados['nome_sigla'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Sigla da Unidade de Medida!</div>"];
}
else
{
    $query_unidade_medida = "INSERT INTO unidade_medidas (Desc_medida, Sigla) VALUES (:Desc_medida, :Sigla)";

    $cad_unidade_medida = $pdo->prepare($query_unidade_medida);
    $cad_unidade_medida->bindValue(':Desc_medida', $dados['nome_descmedida'], PDO::PARAM_STR);
    $cad_unidade_medida->bindValue(':Sigla', $dados['nome_sigla'], PDO::PARAM_STR);

    try
    {
        if($cad_unidade_medida->execute())
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
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> A Descrição da Unidade de Medida ou a Sigla da Unidade de Medida a ser cadastrada já está sendo ultilizada!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
       
    }
}

echo json_encode($retorna);