<?php

ob_start();
ini_set('default_charset','utf-8');
require_once 'validate_cadastro.php'; 
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$validate = new Validate();

if(empty($dados['idfuncionarios']))
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel editar o campo selecionado!</div>"];
}                        
elseif(empty(trim($dados['edit_nome'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Nome!</div>"];
}
elseif(empty(trim($dados['edit_sobrenome'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Sobrenome!</div>"];
}
elseif(empty(trim($dados['edit_login'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Login!</div>"];
}
elseif(empty(trim($dados['edit_status'])) || $dados['edit_status'] == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione um Status para o Funcionário!</div>"];
}
elseif(empty(trim($dados['edit_cpf'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo CPF!</div>"];
}
elseif(!$validate->isCpf($dados['edit_cpf']))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> CPF Inválido!</div>"];
}
elseif(empty(trim($dados['edit_data_nasc'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Data de Nascimento!</div>"];
}
elseif(empty(trim($dados['edit_acesso'])) || $dados['edit_acesso'] == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione o Nível de Acesso!</div>"];
}
elseif(empty(trim($dados['edit_email'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Email!</div>"];
}
elseif(!$validate->valida_email($dados['edit_email']))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Email Inválido!</div>"];
}
elseif(empty(trim($dados['edit_cargo'])) || $dados['edit_cargo'] == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione um Cargo!</div>"];
}
elseif(empty(trim($dados['edit_ddd'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo DDD!</div>"];
}
elseif(empty(trim($dados['edit_telefone'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Telefone!</div>"];
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
elseif(empty(trim($dados['edit_senha'])) AND empty(trim($dados['edit_senhaC'])))
{
    $pdo->beginTransaction();

    //Retira espaços no login
    $palavra = $dados['edit_login'];

    $Login = str_replace(" ", "_", $palavra);

    //Verifica se o cep do funcionário a ser cadastrado existe no banco de dados
    $query_cep = "SELECT idEndereco, Cep FROM endereco WHERE Cep=:Cep LIMIT 1";

    $result_cep = $pdo->prepare($query_cep);
    $result_cep->bindValue(':Cep', $dados['cep_modal'], PDO::PARAM_STR);
    
    $result_cep->execute();

    //Se existir o cep, recupero o id e associo ao funcionário a ser cadastrado
    if(($result_cep) AND ($result_cep->rowCount() !=0))
    {
        $row_cep = $result_cep->fetch(PDO::FETCH_ASSOC);
        
        extract($row_cep);
        
        $query_update_funcionarios = "UPDATE funcionarios SET Nome=:Nome, Sobrenome=:Sobrenome, Data_nasc=:Data_nasc, Cargo_idCargo=:Cargo_idCargo, Login=:Login, Email=:Email, CPF=:CPF,
                                                      Telefone=:Telefone, DDD=:DDD, Endereco_idEndereco=:Endereco_idEndereco, Complemento=:Complemento, Status=:Status, Acesso_idAcesso=:Acesso_idAcesso
                                        WHERE idFuncionarios=:idFuncionarios";
        
        $result_update_funcionarios = $pdo->prepare($query_update_funcionarios);
        $result_update_funcionarios->bindValue(':Nome', $dados['edit_nome'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Sobrenome', $dados['edit_sobrenome'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Data_nasc', $dados['edit_data_nasc'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Cargo_idCargo', $dados['edit_cargo'], PDO::PARAM_INT);
        $result_update_funcionarios->bindValue(':Login', $dados['edit_login'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Email', $dados['edit_email'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':CPF', $dados['edit_cpf'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Telefone', $dados['edit_telefone'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':DDD', $dados['edit_ddd'], PDO::PARAM_STR);        
        $result_update_funcionarios->bindValue(':Endereco_idEndereco', $idEndereco, PDO::PARAM_INT);
        $result_update_funcionarios->bindValue(':Complemento', $dados['edit_complemento'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Status', $dados['edit_status'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Acesso_idAcesso', $dados['edit_acesso'], PDO::PARAM_INT);
        $result_update_funcionarios->bindValue(':idFuncionarios', $dados['idfuncionarios'], PDO::PARAM_INT);

        try
        {
            if($result_update_funcionarios->execute())
            {
                $pdo->commit();

                $retorna = ['status' => true, 'msg' => 
                                "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>Sucesso!</strong> Funcionário editado com sucesso!
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>"
                            ];
            }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();

            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no funcionarios#1: ". $e->getMessage() ."</div>"];  
        }
    }
    else
    {
        //Não existindo o cep do funcionário no banco de dados o sistema cadastra na tabela de endereço
        $query_end = "INSERT INTO endereco(Cep, Logradouro, Bairro, Cidade, Estado) 
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
                //Retira espaços no login
                $palavra = $dados['edit_login'];

                $Login = str_replace(" ", "_", $palavra);
                
                $id_endereco = $pdo->lastInsertId();

                $query_update_funcionarios = "UPDATE funcionarios SET Nome=:Nome, Sobrenome=:Sobrenome, Data_nasc=:Data_nasc, Cargo_idCargo=:Cargo_idCargo, Login=:Login, Email=:Email, CPF=:CPF,
                                                      Telefone=:Telefone, DDD=:DDD, Endereco_idEndereco=:Endereco_idEndereco, Complemento=:Complemento, Status=:Status, Acesso_idAcesso=:Acesso_idAcesso
                                                WHERE idFuncionarios=:idFuncionarios";
        
                $result_update_funcionarios = $pdo->prepare($query_update_funcionarios);
                $result_update_funcionarios->bindValue(':Nome', $dados['edit_nome'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Sobrenome', $dados['edit_sobrenome'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Data_nasc', $dados['edit_data_nasc'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Cargo_idCargo', $dados['edit_cargo'], PDO::PARAM_INT);
                $result_update_funcionarios->bindValue(':Login', $dados['edit_login'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Email', $dados['edit_email'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':CPF', $dados['edit_cpf'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Telefone', $dados['edit_telefone'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':DDD', $dados['edit_ddd'], PDO::PARAM_STR);        
                $result_update_funcionarios->bindValue(':Endereco_idEndereco', $id_endereco, PDO::PARAM_INT);
                $result_update_funcionarios->bindValue(':Complemento', $dados['edit_complemento'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Status', $dados['edit_status'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Acesso_idAcesso', $dados['edit_acesso'], PDO::PARAM_INT);
                $result_update_funcionarios->bindValue(':idFuncionarios', $dados['idfuncionarios'], PDO::PARAM_INT);

                try
                {
                    if($result_update_funcionarios->execute())
                    {
                        $pdo->commit();

                        $retorna = ['status' => true, 'msg' => 
                                        "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                            <strong>Sucesso!</strong> Funcionário editado com sucesso!
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>"
                                    ];
                    }
                }
                catch(PDOException $e)
                {
                    $pdo->rollBack();

                    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no funcionarios#1: ". $e->getMessage() ."</div>"];
                    
                }
            }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();

            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado endereço: ". $e->getMessage() ."</div>"];
        }
    }
}
elseif($dados['edit_senha'] != $dados['edit_senhaC'])
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Senha e Confirmação de Senha não são iguais!</div>"];
}
elseif(!$validate->senhaValida($dados['edit_senha']) AND !$validate->senhaValida($dados['edit_senhaC']))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Insira uma senha de no mínimo 6 caracteres sendo:<br>
                                                                                                                 *1 caractere sendo Número,<br>
                                                                                                                 *1 caractere Maiúsculo,<br>
                                                                                                                 *1 caractere Minúsculo,<br>
                                                                                                                 *1 caractere Especial execeto: = e -.</div>"];
}
else
{
    $pdo->beginTransaction();

    $palavra = $dados['edit_login'];

    $Login = str_replace(" ", "_", $palavra);

    //Criptografar Senha
    $Senha = password_hash($dados['edit_senhaC'], PASSWORD_DEFAULT);

    //Verifica se o cep do funcionário a ser cadastrado existe no banco de dados
    $query_cep = "SELECT idEndereco, Cep FROM endereco WHERE Cep=:Cep LIMIT 1";

    $result_cep = $pdo->prepare($query_cep);
    $result_cep->bindValue(':Cep', $dados['cep_modal'], PDO::PARAM_STR);
    
    $result_cep->execute();

    //Se existir o cep, recupero o id e associo ao funcionário a ser cadastrado
    if(($result_cep) AND ($result_cep->rowCount() !=0))
    {
        $row_cep = $result_cep->fetch(PDO::FETCH_ASSOC);
        
        extract($row_cep);
        
        $query_update_funcionarios = "UPDATE funcionarios SET Nome=:Nome, Sobrenome=:Sobrenome, Data_nasc=:Data_nasc, Cargo_idCargo=:Cargo_idCargo, Login=:Login, Senha=:Senha, Email=:Email, CPF=:CPF,
                                                      Telefone=:Telefone, DDD=:DDD, Endereco_idEndereco=:Endereco_idEndereco, Complemento=:Complemento, Status=:Status, Acesso_idAcesso=:Acesso_idAcesso, Situacao_acesso=:Situacao_acesso
                                        WHERE idFuncionarios=:idFuncionarios";
        
        $result_update_funcionarios = $pdo->prepare($query_update_funcionarios);
        $result_update_funcionarios->bindValue(':Nome', $dados['edit_nome'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Sobrenome', $dados['edit_sobrenome'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Data_nasc', $dados['edit_data_nasc'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Cargo_idCargo', $dados['edit_cargo'], PDO::PARAM_INT);
        $result_update_funcionarios->bindValue(':Login', $Login, PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Senha', $Senha, PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Email', $dados['edit_email'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':CPF', $dados['edit_cpf'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Telefone', $dados['edit_telefone'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':DDD', $dados['edit_ddd'], PDO::PARAM_STR);        
        $result_update_funcionarios->bindValue(':Endereco_idEndereco', $idEndereco, PDO::PARAM_INT);
        $result_update_funcionarios->bindValue(':Complemento', $dados['edit_complemento'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Status', $dados['edit_status'], PDO::PARAM_STR);
        $result_update_funcionarios->bindValue(':Acesso_idAcesso', $dados['edit_acesso'], PDO::PARAM_INT);
        $result_update_funcionarios->bindValue(':Situacao_acesso', 1, PDO::PARAM_INT);
        $result_update_funcionarios->bindValue(':idFuncionarios', $dados['idfuncionarios'], PDO::PARAM_INT);
        
        try
        {
            if($result_update_funcionarios->execute())
            {
                $pdo->commit();

                $retorna = ['status' => true, 'msg' => 
                                "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>Sucesso!</strong> Funcionario editado com sucesso!
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>"
                            ];
            }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();

            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no funcionarios#2: ". $e->getMessage() ."</div>"];
            
        }
    }
    else
    {
        //Não existindo o cep do funcionário no banco de dados o sistema cadastra na tabela de endereço
       $query_end = "INSERT INTO endereco(Cep, Logradouro, Bairro, Cidade, Estado) 
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
                $palavra = $dados['edit_login'];

                $Login = str_replace(" ", "_", $palavra);

                //Criptografar Senha
                $Senha = password_hash($dados['edit_senhaC'], PASSWORD_DEFAULT);

                $id_endereco = $pdo->lastInsertId();

                $query_update_funcionarios = "UPDATE funcionarios SET Nome=:Nome, Sobrenome=:Sobrenome, Data_nasc=:Data_nasc, Cargo_idCargo=:Cargo_idCargo, Login=:Login, Senha=:Senha, Email=:Email, CPF=:CPF,
                                                                    Telefone=:Telefone, DDD=:DDD, Endereco_idEndereco=:Endereco_idEndereco, Complemento=:Complemento, Status=:Status, Acesso_idAcesso=:Acesso_idAcesso, Situacao_acesso=:Situacao_acesso
                                                WHERE idFuncionarios=:idFuncionarios";

                $result_update_funcionarios = $pdo->prepare($query_update_funcionarios);
                $result_update_funcionarios->bindValue(':Nome', $dados['edit_nome'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Sobrenome', $dados['edit_sobrenome'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Data_nasc', $dados['edit_data_nasc'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Cargo_idCargo', $dados['edit_cargo'], PDO::PARAM_INT);
                $result_update_funcionarios->bindValue(':Login', $Login, PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Senha', $Senha, PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Email', $dados['edit_email'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':CPF', $dados['edit_cpf'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Telefone', $dados['edit_telefone'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':DDD', $dados['edit_ddd'], PDO::PARAM_STR);        
                $result_update_funcionarios->bindValue(':Endereco_idEndereco', $id_endereco, PDO::PARAM_INT);
                $result_update_funcionarios->bindValue(':Complemento', $dados['edit_complemento'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Status', $dados['edit_status'], PDO::PARAM_STR);
                $result_update_funcionarios->bindValue(':Acesso_idAcesso', $dados['edit_acesso'], PDO::PARAM_INT);
                $result_update_funcionarios->bindValue(':Situacao_acesso', 1, PDO::PARAM_INT);
                $result_update_funcionarios->bindValue(':idFuncionarios', $dados['idfuncionarios'], PDO::PARAM_INT);

                try
                {
                    if($result_update_funcionarios->execute())
                    {
                        $pdo->commit();

                        $retorna = ['status' => true, 'msg' => 
                                    "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                        <strong>Sucesso!</strong> Funcionário editado com sucesso!
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>"
                                ];
                    }
                }
                catch(PDOException $e)
                {
                    $pdo->rollBack();

                    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no funcionarios#1: ". $e->getMessage() ."</div>"];
                }
            }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();

            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado endereço: ". $e->getMessage() ."</div>"];
        }
    }
}

echo json_encode($retorna);