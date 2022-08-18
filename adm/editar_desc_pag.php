<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($dados);

if(empty($dados['iddescpag']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty($dados['editformapag']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Forma de Pagamento!</div>"]; 

}elseif(empty($dados['editquantparcelas']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Quantidade de Parcelas!</div>"]; 
}
else
{    
    $query_desc_pag  = "UPDATE desc_pag SET Forma_pag=:Forma_pag ,Quant=:Quant WHERE idDesc_pag=:idDesc_pag";

    $edit_desc_pag = $pdo->prepare($query_desc_pag);
    $edit_desc_pag->bindValue(':Forma_pag', $dados['editformapag'], PDO::PARAM_STR);
    $edit_desc_pag->bindValue(':Quant', $dados['editquantparcelas'], PDO::PARAM_STR);
    $edit_desc_pag->bindValue(':idDesc_pag',$dados['iddescpag'], PDO::PARAM_INT);
    
    try
    {
        if($edit_desc_pag->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Descrição de Pagemento editada com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }
    catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O campo Descrição de Pagamento ou a Quantidade Parcelas a ser Editado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
        
        
    }
}

echo json_encode($retorna);