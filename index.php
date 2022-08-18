<?php

    ini_set('default_charset', 'utf-8');
    require_once 'class/conexao2.php';
    global $pdo;

    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    //echo password_hash('Admin@123', PASSWORD_DEFAULT);

    if(isset($dados['Entrar']))
    {
        //var_dump($dados);

        if(empty(trim($dados['login'])))
        {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Login!</div>";
        }
        elseif(empty(trim($dados['senha'])))
        {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preencha o campo Senha!</div>";
        }
        else
        {
            $query_funcionario = "SELECT idFuncionarios, Nome, Login, Senha, Acesso_idAcesso, Situacao_acesso FROM funcionarios
                                    WHERE Login=:Login LIMIT 1";
        
            $result_funcionario = $pdo->prepare($query_funcionario);
            $result_funcionario->BindValue(':Login', $dados['login'], PDO::PARAM_STR);

            $result_funcionario->execute();

            if(($result_funcionario) AND ($result_funcionario->rowCount() !=0))
            {
                $row_funcionario = $result_funcionario->fetch(PDO::FETCH_ASSOC);
                extract($row_funcionario);

                if(password_verify($dados['senha'], $Senha))
                {
                    //Situacao_acesso == 0 quer dizer que não é o primeiro acesso do colaborador
                    if($Situacao_acesso == 0)
                    {
                        $_SESSION['id'] = $idFuncionarios;
                        $_SESSION['nome'] = $Nome;
                        $_SESSION['acesso'] = $Acesso_idAcesso;
                        $_SESSION['status_acesso'] = $Situacao_acesso;
                        header("Location: adm/controle.php?page=dash");
                        exit();
                    }
                    //Situacao_acesso == 1 quer dizer que é o primeiro acesso do colaborador
                    elseif($Situacao_acesso == 1)
                    {
                        $_SESSION['id'] = $idFuncionarios;
                        $_SESSION['nome'] = $Nome;
                        $_SESSION['acesso'] = $Acesso_idAcesso;
                        $_SESSION['status_acesso'] = $Situacao_acesso;
                        header("Location: verifica_senha.php");
                        exit();
                    }     
                }
                else
                {
                    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Não foi encontrado nenhum usuário com senha e login informados!</div>";
                }
            }
            else
            {
                $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Login ou Senha Inválidos!</div>";
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
        
        <title>Login</title>
    </head>
    <body id="fundo">
        <div class="card" id="telalogin">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <?php
                            if(isset($_SESSION['msg']))
                            {
                                echo  $_SESSION['msg'];
                                unset($_SESSION['msg']); 
                            }
                            elseif(isset($_SESSION['msg_dados']))
                            {
                                echo  $_SESSION['msg_dados'];
                                unset($_SESSION['msg_dados']);  
                            }
                        ?>
                        <label for="InputLogin">Login</label>
                        <input type="text" class="form-control" id="InputLogin" name="login" placeholder="Informe seu Login" value="<?php if(isset($dados['login'])){echo $dados['login'];} ?>" autofocus>
                        <small class="form-text text-muted">Jamais compartilhe seu login e senha com mais ninguém.</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Senha</label>
                        <input type="password" class="form-control" name="senha" id="exampleInputPassword1" placeholder="Informe sua senha" value="<?php if(isset($dados['senha'])){echo $dados['senha'];} ?>">
                    </div><br>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" name="Entrar" type="submit">ENTRAR</button>
                    </div>
                </form>         
            </div>
        </div>
    </body>
</html>