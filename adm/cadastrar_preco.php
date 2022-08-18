<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(empty(trim($dados['valorunit'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Valor Unitário!</div>"];
}
elseif(empty(trim($dados['valorcomercio'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Valor de Comercialização!</div>"];
}
elseif(empty(trim($dados['valorpromo'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Valor Promocional!</div>"];
}
elseif(empty(trim($dados['prod'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Produto!</div>"];
}
else
{
    $a = brl2decimal($dados['valorunit'],2);
    $b = brl2decimal($dados['valorcomercio'],2);
    $c= brl2decimal($dados['valorpromo'],2);

    //$x =3;



    $query_preco = "INSERT INTO preco (Valor_unit, Valor_prod, Valor_novo, Status_preco, Produto_Id_Produto) VALUES (:Valor_unit, :Valor_prod, :Valor_novo, :Status_preco, :Produto_Id_Produto)";

    $cad_preco = $pdo->prepare($query_preco);
       
    $cad_preco->bindValue(':Valor_unit', $a, PDO::PARAM_STR);
    $cad_preco->bindValue(':Valor_prod', $b, PDO::PARAM_STR);
    $cad_preco->bindValue(':Valor_novo', $c, PDO::PARAM_STR);
    $cad_preco->bindValue(':Status_preco', $dados['statuspreco'], PDO::PARAM_STR);
    $cad_preco->bindValue(':Produto_Id_Produto', $dados['id_produto'], PDO::PARAM_INT);
    
    
    
   

   
    /*$cad_preco->bindValue(':Valor_unit',brl2decimal($brl1), PDO::PARAM_STR);
    $cad_preco->bindValue(':Valor_prod', brl2decimal($brl2), PDO::PARAM_STR);
    $cad_preco->bindValue(':Valor_novo', brl2decimal($brl3), PDO::PARAM_STR);
    $cad_preco->bindValue(':Status_preco', $dados['statuspreco'], PDO::PARAM_STR);
    $cad_preco->bindValue(':Produto_Id_Produto', $dados['id_produto'], PDO::PARAM_INT);*/


    try
    {
        if($cad_preco->execute())
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
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
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