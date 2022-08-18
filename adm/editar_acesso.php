<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($dados);

if(empty($dados['idacesso']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty($dados['edit_acesso']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Nível de Acesso!</div>"]; 
}
else
{
    $query_acesso  = "UPDATE acesso SET Nivel=:Nivel WHERE idAcesso=:idAcesso";
    $edit_acesso = $pdo->prepare($query_acesso);
    $edit_acesso->bindValue(':Nivel',$dados['edit_acesso'], PDO::PARAM_STR);
    $edit_acesso->bindValue(':idAcesso',$dados['idacesso'], PDO::PARAM_INT);
    
    try
    {
        if($edit_acesso->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Nível de acesso editado com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Acesso a ser Editado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
       
    }
}

echo json_encode($retorna);