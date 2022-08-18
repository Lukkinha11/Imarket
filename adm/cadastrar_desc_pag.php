<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(empty(trim($dados['formapag'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Forma de Pagamento!</div>"];
}
elseif(empty(trim($dados['quantparcelas'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Quantidade Parcelas!</div>"];
}
else
{   
    $query_desc_pag = "INSERT INTO desc_pag (Forma_pag, Quant ) VALUES (:Forma_pag, :Quant)";

    $cad_desc_pag = $pdo->prepare($query_desc_pag);
    $cad_desc_pag->bindValue(':Forma_pag', $dados['formapag'], PDO::PARAM_STR);
    $cad_desc_pag->bindValue(':Quant', $dados['quantparcelas'], PDO::PARAM_STR);


    try
    {
        if($cad_desc_pag->execute())
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
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> A Descrição de Pagamento ou a Quantidade Parcelas a ser cadastrada já está sendo ultilizada!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
       
    }
}

echo json_encode($retorna);