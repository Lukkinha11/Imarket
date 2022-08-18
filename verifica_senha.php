<?php

    ini_set('default_charset', 'utf-8');
    require_once 'class/conexao2.php';
    require_once 'adm/validate_cadastro.php';
    global $pdo;

    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $validate = new Validate();

    //var_dump($_SESSION['id']);
    
    if(isset($dados['Cadastrar']))
    {
        //var_dump($dados);
        if(empty(trim($dados['senha'])))
        {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Senha!</div>";
        }
        elseif(empty(trim($dados['senhaC'])))
        {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Confirmação de Senha!</div>";
        }
        elseif(!$validate->senhaValida($dados['senha']) AND !$validate->senhaValida($dados['senhaC']))
        {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Insira uma senha de no mínimo 6 caracteres sendo:<br>
                                                                                                                 *1 caractere sendo Número,<br>
                                                                                                                 *1 caractere Maiúsculo,<br>
                                                                                                                 *1 caractere Minúsculo,<br>
                                                                                                                 *1 caractere Especial execeto: = e -.</div>";
        }
        else
        {
            //criptografar senha
            $Senha = $dados['senhaC'];
            $Senha = password_hash($Senha, PASSWORD_DEFAULT);
            
            $query_senha = "UPDATE funcionarios SET Senha=:Senha, Situacao_acesso=:Situacao_acesso WHERE idFuncionarios=:idFuncionarios";

            $result_senha = $pdo->prepare($query_senha);
            $result_senha->bindValue(':Senha', $Senha, PDO::PARAM_STR);
            $result_senha->bindValue(':Situacao_acesso', 0, PDO::PARAM_STR);
            $result_senha->bindValue(':idFuncionarios', $_SESSION['id'], PDO::PARAM_INT);

            if($result_senha->execute())
            {
                $query_funcionario = "SELECT idFuncionarios, Nome, Acesso_idAcesso, Situacao_acesso FROM funcionarios
                                      WHERE idFuncionarios=:idFuncionarios LIMIT 1";

                $result_funcionario = $pdo->prepare($query_funcionario);
                $result_funcionario->BindValue(':idFuncionarios', $_SESSION['id'], PDO::PARAM_INT);
    
                $result_funcionario->execute();

                if(($result_funcionario) AND ($result_funcionario->rowCount() !=0))
                {
                    $row_funcionario = $result_funcionario->fetch(PDO::FETCH_ASSOC);
                    extract($row_funcionario);

                    $_SESSION['id'] = $idFuncionarios;
                    $_SESSION['nome'] = $Nome;
                    $_SESSION['acesso'] = $Acesso_idAcesso;
                    $_SESSION['status_acesso'] = $Situacao_acesso;
                    header("Location: adm/controle.php?page=dash");
                    exit();
                }
                else
                {
                    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Não existe seu cadastro no sistema!</div>";
                }
                
                
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="shortcut icon" href="img_prod/icon/favicon.ico.ico">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="css manual/estilos.css">
        <link rel="stylesheet" href="css manual/css.css">
        
        <script src="bootstrap/js/bootstrap.bundle.js" type="text/javascript"></script>
        
        
        <title>Primeiro Acesso</title>
    </head>
    <body id="fundo">
        <div class="card" id="telalogin">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <?php
                            if(isset( $_SESSION['msg']))
                            {
                                echo  $_SESSION['msg'];
                                unset( $_SESSION['msg']); 
                            }
                        ?>
                        <label for="senha">Cadastre uma nova Senha</label>
                        <input type="password" class="form-control" name="senha" id="senha" placeholder="Cadastre uma nova senha" onkeyup="this.value = Trim(this.value )">
                        <small class="form-text text-muted">Jamais compartilhe seu login e senha com mais ninguém.</small>
                    </div>
                    <div class="form-group">
                        <label for="senhaC">Confirmação de Senha</label>
                        <input type="password" class="form-control" name="senhaC" id="senhaC" placeholder="Confirme a senha" onkeyup="this.value = Trim(this.value )">
                    </div><br>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" name="Cadastrar" type="submit">CADASTRAR</button>
                    </div>
                </form>         
            </div>
        </div>
    </body>
</html>
<script src="js manual/custom_senha_cli.js"></script>
<script type="text/javaScript">
    function Trim(str)
    {
        return str.replace(/^\s+|\s+$/g,"");
    }
</script>