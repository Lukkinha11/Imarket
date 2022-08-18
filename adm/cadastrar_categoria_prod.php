<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(empty(trim($dados['catprod'])))
{ 
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Categoria!</div>"];

}elseif(empty(trim($dados['catdir'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Categoria Diretório!</div>"];
}
else
{
    $palavra = $dados['catdir'];

    $Categoria_diretorio = str_replace(" ", "_", $palavra);
    
    $query_cat_prod = "INSERT INTO categoria_prod (Categoria, Categoria_diretorio) VALUES (:Categoria, :Categoria_diretorio)";

    $cad_categoria = $pdo->prepare($query_cat_prod);
    $cad_categoria->bindValue(':Categoria', $dados['catprod'], PDO::PARAM_STR);
    $cad_categoria->bindValue(':Categoria_diretorio', $Categoria_diretorio, PDO::PARAM_STR);

    try
    {
        if($cad_categoria->execute())
        {
            $retorna = ['status' => true, 'msg' => 
                            "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Sucesso!</strong> Cadastro efetuado com sucesso!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
        }

        mkdir("../img_prod/$Categoria_diretorio", 0755, true);
    }
    catch(PDOException $e)
    {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> A Categoria ou o Diretótio a ser cadastrado já está sendo ultilizado!</div>"];
        }
        else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
        
    }
}

echo json_encode($retorna);