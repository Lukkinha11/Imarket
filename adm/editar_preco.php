<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($dados);

if(empty(trim($dados['idpreco'])))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];

}elseif(empty(trim($dados['edit_valorunit'])))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Valor Unitário!</div>"]; 
}
elseif(empty(trim($dados['edit_valorcomercio'])))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Valor de Comercialização!</div>"]; 
}
elseif(empty(trim($dados['edit_valorpromo'])))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Valor Promocional!</div>"]; 
}
elseif(empty(trim($dados['edit_prod'])))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Preencha o campo Produto!</div>"]; 
}
else
{
    $a = brl2decimal($dados['edit_valorunit'],2);
    $b = brl2decimal($dados['edit_valorcomercio'],2);
    $c= brl2decimal($dados['edit_valorpromo'],2);
    
    
    $query_preco  = "UPDATE preco SET Valor_unit=:Valor_unit, Valor_prod=:Valor_prod, Valor_novo=:Valor_novo, Status_preco=:Status_preco, Produto_Id_Produto=:Produto_Id_Produto WHERE idPreco=:idPreco";
    
    $edit_preco = $pdo->prepare($query_preco);
    $edit_preco->bindValue(':Valor_unit', $a, PDO::PARAM_STR);
    $edit_preco->bindValue(':Valor_prod', $b, PDO::PARAM_STR);
    $edit_preco->bindValue(':Valor_novo', $c, PDO::PARAM_STR);
    $edit_preco->bindValue(':Status_preco', $dados['edit_statuspreco'], PDO::PARAM_STR);
    $edit_preco->bindValue(':Produto_Id_Produto', $dados['id_prodedit'], PDO::PARAM_INT);
    $edit_preco->bindValue(':idPreco', $dados['idpreco'], PDO::PARAM_INT);
    
    try
    {
        if($edit_preco->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Preço editado com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }
    }catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Preço a ser Editado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
       
    }
}

echo json_encode($retorna);

function brl2decimal($brl, $casasDecimais ) 
{
    // Se já estiver no formato USD, retorna como float e formatado
    if(preg_match('/^\d+\.{1}\d+$/', $brl))
        return (float) number_format($brl, $casasDecimais, '.', '');
    // Tira tudo que não for número, ponto ou vírgula
    $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
    // Tira o ponto
    $decimal = str_replace('.', '', $brl);
    // Troca a vírgula por ponto
    $decimal = str_replace(',', '.', $decimal);
    return (float) number_format($decimal, $casasDecimais, '.', '');
}