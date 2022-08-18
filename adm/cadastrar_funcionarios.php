<?php

ob_start();
ini_set('default_charset','utf-8');
require_once 'validate_cadastro.php'; 
require_once '../class/conexao2.php';
global $pdo;

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$validate = new Validate();
                        
if(empty(trim($dados['nome'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Nome!</div>"];
}
elseif(empty(trim($dados['sobrenome'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Sobrenome!</div>"];
}
elseif(empty(trim($dados['login'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Login!</div>"];
}
elseif(strlen($dados['login']) > 20)
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Tamanho do Login é Inválido!</div>"];
}
elseif(empty(trim($dados['status'])) || $dados['status'] == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione um Status para o Funcionário!</div>"];
}
elseif(empty(trim($dados['cpf'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo CPF!</div>"];
}
elseif(!$validate->isCpf($dados['cpf']))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> CPF Inválido!</div>"];
}
elseif(empty(trim($dados['data_nasc'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Data de Nascimento!</div>"];
}
elseif(empty(trim($dados['acesso'])) || $dados['acesso'] == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione o Nível de Acesso!</div>"];
}
elseif(empty(trim($dados['email'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Email!</div>"];
}
elseif(!$validate->valida_email($dados['email']))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Email Inválido!</div>"];
}
elseif(empty(trim($dados['cargo'])) || $dados['cargo'] == "Selecione")
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Selecione um Cargo!</div>"];
}
elseif(empty(trim($dados['ddd'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo DDD!</div>"];
}
elseif(strlen($dados['ddd']) != 4)
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> DDD Inválido!</div>"];
}
elseif(empty(trim($dados['telefone'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Telefone!</div>"];
}
elseif(strlen($dados['telefone']) != 10)
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Telefone Inválido!</div>"];
}
elseif(empty(trim($dados['cep'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Cep!</div>"];
}
elseif(empty(trim($dados['rua'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Logradouro!</div>"];
}
elseif(empty(trim($dados['bairro'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Bairro!</div>"];
}
elseif(empty(trim($dados['cidade'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Cidade!</div>"];
}
elseif(empty(trim($dados['uf'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Estado!</div>"];
}
elseif(empty(trim($dados['complemento'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Complemento!</div>"];
}
elseif(empty(trim($dados['senha'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Senha!</div>"];
}
elseif(empty(trim($dados['senhaC'])))
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Confirmação de Senha!</div>"];
}
elseif($dados['senha'] != $dados['senhaC'])
{
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Senha e Confirmação de Senha não são iguais!</div>"];
}
elseif(!$validate->senhaValida($dados['senha']) AND !$validate->senhaValida($dados['senhaC']))
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

    //Verifica se o cep do funcionário a ser cadastrado existe no banco de dados
    $query_cep = "SELECT idEndereco, Cep FROM endereco WHERE Cep=:Cep LIMIT 1";

    $result_cep = $pdo->prepare($query_cep);
    $result_cep->bindValue(':Cep', $dados['cep'], PDO::PARAM_STR);
    
    $result_cep->execute();

    //Se existir o cep, recupero o id e associo ao funcionário a ser cadastrado
    if(($result_cep) AND ($result_cep->rowCount() !=0))
    {
        $row_cep = $result_cep->fetch(PDO::FETCH_ASSOC);
        
        extract($row_cep);

        //Gerar um número aleatório apartir do número de telefone
        $numero = $dados['telefone'];

        $novo_numero = str_replace("-", "", $numero);

        $matricula = rand($novo_numero, 99999);

        $palavra = $dados['login'];

        $Login = str_replace(" ", "_", $palavra);

        //Criptografar Senha
        $Senha = password_hash($dados['senhaC'], PASSWORD_DEFAULT);

        //Formatar data de nascimento para o mysql
        //$validate->dateEmMysql($dados['data_nasc']);

        $query_func = "INSERT INTO funcionarios (Matricula, Nome, Sobrenome, Data_nasc, Cargo_idCargo, Login, Senha, Email, CPF, Telefone, DDD, Endereco_idEndereco, Complemento, Status, Admissao, Acesso_idAcesso, Situacao_acesso) 
                                        VALUES (:Matricula, :Nome, :Sobrenome, :Data_nasc, :Cargo_idCargo, :Login, :Senha, :Email, :CPF, :Telefone, :DDD, :Endereco_idEndereco, :Complemento, :Status, CURDATE(), :Acesso_idAcesso, :Situacao_acesso)";

        $cad_func = $pdo->prepare($query_func);
        $cad_func->bindValue(':Matricula', $matricula, PDO::PARAM_STR);
        $cad_func->bindValue(':Nome', $dados['nome'], PDO::PARAM_STR);
        $cad_func->bindValue(':Sobrenome', $dados['sobrenome'], PDO::PARAM_STR);
        $cad_func->bindValue(':Data_nasc', $dados['data_nasc'], PDO::PARAM_STR);
        $cad_func->bindValue(':Cargo_idCargo', $dados['cargo'], PDO::PARAM_INT);
        $cad_func->bindValue(':Login', $Login, PDO::PARAM_STR);
        $cad_func->bindValue(':Senha', $Senha, PDO::PARAM_STR);
        $cad_func->bindValue(':Email', $dados['email'], PDO::PARAM_STR);
        $cad_func->bindValue(':CPF', $dados['cpf'], PDO::PARAM_STR);
        $cad_func->bindValue(':Telefone', $dados['telefone'], PDO::PARAM_STR);
        $cad_func->bindValue(':DDD', $dados['ddd'], PDO::PARAM_STR);
        $cad_func->bindValue(':Endereco_idEndereco', $idEndereco, PDO::PARAM_INT);
        $cad_func->bindValue(':Complemento', $dados['complemento'], PDO::PARAM_STR);
        $cad_func->bindValue(':Status', $dados['status'], PDO::PARAM_STR);
        $cad_func->bindValue(':Acesso_idAcesso', $dados['acesso'], PDO::PARAM_INT);
        $cad_func->bindValue(':Situacao_acesso', 1, PDO::PARAM_STR);

        try
        {
            if($cad_func->execute())
            {
                $pdo->commit();

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
            $pdo->rollBack();

            if( $e->getCode() == 23000)
            {
                $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O CPF ou Email já está cadastrado no sistema!</div>"];
            }
            else
            {
                $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no funcionarios: ". $e->getMessage() ."</div>"];
            }
        }
    }
    else
    {
        //Não existindo o cep do funcionário no banco de dados o sistema cadastra na tabela de endereço
        $query_end = "INSERT INTO endereco(Cep, Logradouro, Bairro, Cidade, Estado) 
                                VALUES(:Cep, :Logradouro, :Bairro, :Cidade, :Estado)";

        $add_end = $pdo->prepare($query_end);
        $add_end->bindValue(':Cep', $dados['cep'], PDO::PARAM_STR);
        $add_end->bindValue(':Logradouro', $dados['rua'], PDO::PARAM_STR);
        $add_end->bindValue(':Bairro', $dados['bairro'], PDO::PARAM_STR);
        $add_end->bindValue(':Cidade', $dados['cidade'], PDO::PARAM_STR);
        $add_end->bindValue(':Estado', $dados['uf'], PDO::PARAM_STR);

        try
        {
            if($add_end->execute())
            {
                $id_endereco = $pdo->lastInsertId();

                //Gerar um número aleatório apartir do número de telefone
                $numero = $dados['telefone'];

                $novo_numero = str_replace("-", "", $numero);

                $matricula = rand($novo_numero, 99999);

                $palavra = $dados['login'];

                $Login = str_replace(" ", "_", $palavra);

                //Criptografar Senha
                $Senha = password_hash($dados['senhaC'], PASSWORD_DEFAULT);

                //Formatar data de nascimento para o mysql
                //$validate->dateEmMysql($dados['data_nasc']);

                $query_func = "INSERT INTO funcionarios (Matricula, Nome, Sobrenome, Data_nasc, Cargo_idCargo, Login, Senha, Email, CPF, Telefone, DDD, Endereco_idEndereco, Complemento, Status, Admissao, Acesso_idAcesso, Situacao_acesso) 
                                                VALUES (:Matricula, :Nome, :Sobrenome, :Data_nasc, :Cargo_idCargo, :Login, :Senha, :Email, :CPF, :Telefone, :DDD, :Endereco_idEndereco, :Complemento, :Status, CURDATE(), :Acesso_idAcesso, :Situacao_acesso)";

                $cad_func = $pdo->prepare($query_func);
                $cad_func->bindValue(':Matricula', $matricula, PDO::PARAM_STR);
                $cad_func->bindValue(':Nome', $dados['nome'], PDO::PARAM_STR);
                $cad_func->bindValue(':Sobrenome', $dados['sobrenome'], PDO::PARAM_STR);
                $cad_func->bindValue(':Data_nasc', $dados['data_nasc'], PDO::PARAM_STR);
                $cad_func->bindValue(':Cargo_idCargo', $dados['cargo'], PDO::PARAM_INT);
                $cad_func->bindValue(':Login', $Login, PDO::PARAM_STR);
                $cad_func->bindValue(':Senha', $Senha, PDO::PARAM_STR);
                $cad_func->bindValue(':Email', $dados['email'], PDO::PARAM_STR);
                $cad_func->bindValue(':CPF', $dados['cpf'], PDO::PARAM_STR);
                $cad_func->bindValue(':Telefone', $dados['telefone'], PDO::PARAM_STR);
                $cad_func->bindValue(':DDD', $dados['ddd'], PDO::PARAM_STR);
                $cad_func->bindValue(':Endereco_idEndereco', $id_endereco, PDO::PARAM_INT);
                $cad_func->bindValue(':Complemento', $dados['complemento'], PDO::PARAM_STR);
                $cad_func->bindValue(':Status', $dados['status'], PDO::PARAM_STR);
                $cad_func->bindValue(':Acesso_idAcesso', $dados['acesso'], PDO::PARAM_INT);
                $cad_func->bindValue(':Situacao_acesso', 1, PDO::PARAM_INT);

                try
                {
                    if($cad_func->execute())
                    {
                        $pdo->commit();

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
                    $pdo->rollBack();

                    if( $e->getCode() == 23000)
                    {
                        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'> O CPF ou Email já está cadastrado no sistema!</div>"];
                    }
                    else
                    {
                        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no funcionarios: ". $e->getMessage() ."</div>"];
                    }
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