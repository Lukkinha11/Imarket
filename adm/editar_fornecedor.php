<?php

ob_start();
ini_set('default_charset','utf-8');
require_once 'validate_cadastro.php'; 
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$validate = new Validate();
                        
if(empty($dados['idfornecedor']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];
} 
elseif(empty(trim($dados['edit_nome_fantasia'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Nome Fantasia!</div>"];
}
elseif(empty(trim($dados['edit_razao_social'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Razão Social!</div>"];
}
elseif(empty(trim($dados['edit_cnpj'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo CNPJ!</div>"];
}
elseif(!$validate->validar_cnpj($dados['edit_cnpj']))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> CNPJ Inválido!</div>"];
}
elseif(empty(trim($dados['edit_email'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Email!</div>"];
}
elseif(!$validate->valida_email($dados['edit_email']))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Email Inválido!</div>"];
}
elseif(empty(trim($dados['edit_ddd'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo DDD!</div>"];
}
elseif(strlen($dados['edit_ddd']) != 4)
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> DDD Inválido!</div>"];
}
elseif(empty(trim($dados['edit_telefone'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Telefone!</div>"];
}
elseif(strlen($dados['edit_telefone']) != 10)
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Telefone Inválido!</div>"];
}
elseif(empty(trim($dados['cep_modal'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Cep!</div>"];
}
elseif(empty(trim($dados['rua_modal'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Logradouro!</div>"];
}
elseif(empty(trim($dados['bairro_modal'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Bairro!</div>"];
}
elseif(empty(trim($dados['cidade_modal'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Cidade!</div>"];
}
elseif(empty(trim($dados['uf_modal'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Estado!</div>"];
}
elseif(empty(trim($dados['edit_complemento'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Complemento!</div>"];
}
elseif(empty(trim($dados['edit_referencia'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Ponto de Referência!</div>"];
}
else
{
    $pdo->beginTransaction();

    //Verifica se o cep do funcionário a ser cadastrado existe no banco de dados
    $query_cep = "SELECT idEndereco, Cep FROM endereco WHERE Cep=:Cep LIMIT 1";

    $result_cep = $pdo->prepare($query_cep);
    $result_cep->bindValue(':Cep', $dados['cep_modal'], PDO::PARAM_STR);
    
    $result_cep->execute();

    //Se existir o cep, recupero o id e associo ao fornecedor a ser cadastrado
    if(($result_cep) AND ($result_cep->rowCount() !=0))
    {
        $row_cep = $result_cep->fetch(PDO::FETCH_ASSOC);
        
        extract($row_cep);

        $query_fornecedor = "UPDATE fornecedor 
                             SET Nome_Fantasia=:Nome_Fantasia, Razao_social=:Razao_social, CNPJ=:CNPJ, Telefone=:Telefone, DDD=:DDD, Email=:Email, 
                                 Complemento=:Complemento, Endereco_idEndereco=:Endereco_idEndereco, Referencia=:Referencia
                            WHERE idFornecedor=:idFornecedor";

        $cad_fornecedor = $pdo->prepare($query_fornecedor);
        $cad_fornecedor->bindValue(':Nome_Fantasia', $dados['edit_nome_fantasia'], PDO::PARAM_STR);
        $cad_fornecedor->bindValue(':Razao_social', $dados['edit_razao_social'], PDO::PARAM_STR);
        $cad_fornecedor->bindValue(':CNPJ', $dados['edit_cnpj'], PDO::PARAM_STR);
        $cad_fornecedor->bindValue(':Telefone', $dados['edit_telefone'], PDO::PARAM_STR);
        $cad_fornecedor->bindValue(':DDD', $dados['edit_ddd'], PDO::PARAM_STR);
        $cad_fornecedor->bindValue(':Email', $dados['edit_email'], PDO::PARAM_STR);
        $cad_fornecedor->bindValue(':Complemento', $dados['edit_complemento'], PDO::PARAM_STR);
        $cad_fornecedor->bindValue(':Endereco_idEndereco', $idEndereco, PDO::PARAM_INT);
        $cad_fornecedor->bindValue(':Referencia', $dados['edit_referencia'], PDO::PARAM_STR);
        $cad_fornecedor->bindValue(':idFornecedor', $dados['idfornecedor'], PDO::PARAM_INT);

        try
        {
            if($cad_fornecedor->execute())
            {
                $pdo->commit();

                $retorna = ['status' => true, 'msg' => 
                                "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>Sucesso!</strong> Fornecedor editado com sucesso!
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>"
                            ];
            }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();

            if( $e->getCode() == 23000)
            {
                $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Fornecedor a ser cadastrado já está sendo ultilizado!</div>"];
            }
            else
            {
                $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no fornecedor#1: ". $e->getMessage() ."</div>"];
            }
        }
    }
    else
    {
        //Não existindo o cep do funcionário no banco de dados o sistema cadastra na tabela de endereço

        $query_end = "INSERT INTO endereco (Cep, Logradouro, Bairro, Cidade, Estado) 
                                     VALUES(:Cep, :Logradouro, :Bairro, :Cidade, :Estado)";

        $add_end = $pdo->prepare($query_end);
        $add_end->bindValue(':Cep', $dados['cep_modal'], PDO::PARAM_STR);
        $add_end->bindValue(':Logradouro', $dados['rua_modal'], PDO::PARAM_STR);
        $add_end->bindValue(':Bairro', $dados['bairro_modal'], PDO::PARAM_STR);
        $add_end->bindValue(':Cidade', $dados['cidade_modal'], PDO::PARAM_STR);
        $add_end->bindValue(':Estado', $dados['uf_modal'], PDO::PARAM_STR);

        try
        {
            if($add_end->execute())
            {
                $id_endereco = $pdo->lastInsertId();

                $query_fornecedor = "UPDATE fornecedor 
                                        SET Nome_Fantasia=:Nome_Fantasia, Razao_social=:Razao_social, CNPJ=:CNPJ, Telefone=:Telefone, DDD=:DDD, Email=:Email, 
                                            Complemento=:Complemento, Endereco_idEndereco=:Endereco_idEndereco, Referencia=:Referencia
                                    WHERE idFornecedor=:idFornecedor";

                $cad_fornecedor = $pdo->prepare($query_fornecedor);
                $cad_fornecedor->bindValue(':Nome_Fantasia', $dados['edit_nome_fantasia'], PDO::PARAM_STR);
                $cad_fornecedor->bindValue(':Razao_social', $dados['edit_razao_social'], PDO::PARAM_STR);
                $cad_fornecedor->bindValue(':CNPJ', $dados['edit_cnpj'], PDO::PARAM_STR);
                $cad_fornecedor->bindValue(':Telefone', $dados['edit_telefone'], PDO::PARAM_STR);
                $cad_fornecedor->bindValue(':DDD', $dados['edit_ddd'], PDO::PARAM_STR);
                $cad_fornecedor->bindValue(':Email', $dados['edit_email'], PDO::PARAM_STR);
                $cad_fornecedor->bindValue(':Complemento', $dados['edit_complemento'], PDO::PARAM_STR);
                $cad_fornecedor->bindValue(':Endereco_idEndereco', $id_endereco, PDO::PARAM_INT);
                $cad_fornecedor->bindValue(':Referencia', $dados['edit_referencia'], PDO::PARAM_STR);
                $cad_fornecedor->bindValue(':idFornecedor', $dados['idfornecedor'], PDO::PARAM_INT);

                try
                {
                    if($cad_fornecedor->execute())
                    {
                        $pdo->commit();

                        $retorna = ['status' => true, 'msg' => 
                                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                            <strong>Sucesso!</strong> Fornecedor editado com sucesso!
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>"
                                    ];
                    }
                }
                catch(PDOException $e)
                {
                    $pdo->rollBack();

                    if( $e->getCode() == 23000)
                    {
                        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O Fornecedor a ser cadastrado já está sendo ultilizado!</div>"];
                    }
                    else
                    {
                        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no fornecedor#2: ". $e->getMessage() ."</div>"];
                    }
                }
            }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();

            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no endereço: ". $e->getMessage() ."</div>"];
        }
    }
}

echo json_encode($retorna);