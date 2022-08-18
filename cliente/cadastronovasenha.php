<?php
    ob_start();
    ini_set('default_charset','utf-8');
    require_once '../class/conexao2.php';
    require_once 'topo.php';
    require_once 'validate_cadastro.php';
    global $pdo;

    $validate = new Validate();
         
    $chave = filter_input(INPUT_GET, 'chave', FILTER_DEFAULT);
    //var_dump($chave);

    //verifica se a chave de verificação não está vazia 
    if(!empty($chave))
    {
        //faz uma pesquisa para verificar se existe algum cliente com a chave de verificação
        $query_verifica_email = "SELECT idCliente FROM cliente
                                WHERE Chave_senha =:Chave_senha
                                LIMIT 1";

        $result_email = $pdo->prepare($query_verifica_email);
        $result_email->bindValue(':Chave_senha', $chave, PDO::PARAM_STR);
        $result_email->execute();

        //verifica se a query de pesquisa retornou um resultado diferente de 0
        if(($result_email) AND ($result_email->rowCount() != 0))
        {
            $row_email = $result_email->fetch(PDO::FETCH_ASSOC);

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($dados);

            if(!empty($dados['cadnewsenha']))
            {
                if(!empty($dados['senha']) AND !empty($dados['senhaC']))
                {
                    if(!$validate->senhaValida($dados['senha']) AND !$validate->senhaValida($dados['senhaC']))
                    {
                        echo"<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                                <strong>ATENÇÃO!</strong> Insira uma senha de no mínimo 6 caracteres sendo:<br>
                                                            *1 caractere sendo Número,<br>
                                                            *1 caractere Maiúsculo,<br>
                                                            *1 caractere Minúsculo,<br>
                                                            *1 caractere Especial execeto: = e -.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";	
                    }
                    elseif($dados['senha'] == $dados['senhaC'])
                    {
                       $novasenha = password_hash($dados['senhaC'], PASSWORD_DEFAULT);
                       $chave_senha = NULL;

                       $query_up_senha = "UPDATE cliente SET Senha =:Senha, Chave_senha = :Chave_senha 
                                          WHERE idCliente =:idCliente
                                          LIMIT 1";
                    
                        $result_up_senha = $pdo->prepare($query_up_senha);
                        $result_up_senha->bindValue(':Senha', $novasenha, PDO::PARAM_STR);
                        $result_up_senha->bindValue(':Chave_senha', $chave_senha, PDO::PARAM_STR);
                        $result_up_senha->bindValue(':idCliente', $row_email['idCliente'], PDO::PARAM_INT);

                        if($result_up_senha->execute())
                        {
                            $_SESSION['msg'] = "<div class='alert alert-success alert-dismissible fade show text-center' role='alert'>
                                                        <strong>Senha atualizada com sucesso!</strong> 
                                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                                    </div>";
                            header("Location: index.php?page=login");
                            exit();
                        }
                    }
                    else
                    {
                        echo"<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                                <strong>ATENÇÃO!</strong> Senha e Confirmação de Senha não são iguais!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                    }
                }else
                {
                    echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                        <strong>ATENÇÃO!</strong> Preencha os campos senha e confirmação se senha!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                }
            }

            
        }
        //se a query de pesquisa retornar valor igual a 0 redireciona o usuário para a tela de Recuperação de Senha
        else
        {
            $_SESSION['alert'] = "<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                    <strong>Erro:</strong> Link inválido, solicite um novo link para recuperar a senha!
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
            header("Location: index.php?page=recsenha");
            exit();
        }
    }
    //se a chave de verificação estiver vazia na url redireciona o usuário para a tela de Recuperação de Senha 
    else
    {
        $_SESSION['alert'] = "<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                <strong>Erro:</strong> Link inválido, solicite um novo link para recuperar a senha!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
        header("Location: index.php?page=recsenha");
        exit();
    }
 ?>
<main class="flex-fill">
    <div class="container">
        <div class="row justify-content-center">
            <form action="" method="POST" class="col-sm-10 col-md-8 col-lg-6">
                <h2 class="mb-3">Cadastrar Nova senha</h2>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="senha" name="senha" autofocus id="txtNovaSenha" placeholder=" ">
                    <label for="txtNovaSenha">Digite aqui uma nova senha</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="senhaC" name="senhaC" autofocus id="txtConfSenha" placeholder=" ">
                    <label for="txtConfSenha">Repita a senha criada acima</label>
                </div>
                <button class="btn btn-lg btn-primary" name="cadnewsenha" value="cadnewsenha" type="submit">Cadastrar Nova Senha</button>
                <!--<a href="index.php?page=confcadsenha"  type="button"></a>-->
            </form>
        </div>
    </div>
</main>
<script src="../js manual/custom_senha_cli.js"></script> 
<?php 
require_once 'rodape.php';
?>