 <main class="flex-fill">
     <div class="container">
         <div class="row justify-content-center">
                <?php
                //Criptografando a senha com a função do PHP
                //echo password_hash(123456, PASSWORD_DEFAULT);
                
                ini_set('default_charset', 'utf-8');
                require_once '../class/conexao2.php';
                global $pdo;
                ?>
                
                <?php
                    ob_start();
                    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                
                    if(!empty($dados['SendLogin']))
                    {
                        //var_dump($dados); 
                        $query_email = "SELECT idCliente, Nome, Email, Senha, Situacao_cliente_idSituacao_cliente, Estado FROM cliente
                                        INNER JOIN endereco
                                        ON endereco.idEndereco = cliente.Endereco_idEndereco 
                                        WHERE Email =:email
                                        LIMIT 1";
                        $result_email = $pdo->prepare($query_email);
                        $result_email->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                        $result_email->execute();

                        if(($result_email) AND ($result_email->rowCount() !=0))
                        {
                            $row_email = $result_email->fetch(PDO::FETCH_ASSOC);
                            //var_dump($row_email);
                            if($row_email['Situacao_cliente_idSituacao_cliente'] !=1)
                            {
                                $_SESSION['msg'] = "<div class='alert alert-danger text-center' role='alert'>
                                                        ATENÇÃO: O Email não foi confirmado!
                                                    </div>";
                            }
                            elseif(password_verify($dados['senha'], $row_email['Senha']))
                            {
                                $_SESSION['id'] = $row_email['idCliente'];
                                $_SESSION['nome'] = $row_email['Nome'];
                                $_SESSION['estado'] = $row_email['Estado'];
                                header("Location: ?pag=dice");
                                exit();
                            }else
                            {
                                $_SESSION['msg'] = "<div class='alert alert-danger text-center' role='alert'>
                                                        Email ou Senha Inválidos!
                                                    </div>";
                            }
                        }else
                        {
                            $_SESSION['msg'] = "<div class='alert alert-danger text-center' role='alert'>
                                                   Email ou Senha Inválidos!
                                                </div>";
                        }
                        //'".$dados['email']."' 
                    }
                    if(isset( $_SESSION['msg']))
                    {
                        echo  $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }
                ?>
             <form method="POST" action="" class="col-sm-10 col-md-8 col-lg-6">
                 <h2 class="mb-3">Identifique-se, por favor</h2>
                 <div class="form-floating mb-3">
                     <input type="email" class="form-control" name="email" 
                     value="<?php if(isset($dados['email'])) echo $dados['email'];?>" autofocus id="txtEmail" placeholder=" " required>
                     <label for="txtEmail">E-mail</label>
                 </div>
                 <div class="form-floating mb-3">
                     <input type="password" class="form-control" name="senha" 
                     value="<?php if(isset($dados['senha'])) echo $dados['senha'];?>" id="txtSenha" placeholder=" " required>
                     <label for="txtSenha">Senha</label>
                 </div>

                 <input class="btn btn-lg btn-primary" type="submit" value="Entrar" name="SendLogin"></input>

                 <p class="mt-3">
                     Ainda não é cadastrado? <a href="index.php?page=cad">Clique aqui</a> para se casdastrar.
                 </p>
                 <p class="mt-3">
                     Esqueceu sua senha? <a href="index.php?page=recsenha">Clique aqui</a> para recuperá-la
                 </p>
             </form>
         </div>
     </div>
 </main>

 <!--                 
    <div style="height: 273px" class="d-block d-md-none"></div>
    <div style="height: 153px" class="d-none d-md-block d-lg-none"></div>
    <div style="height: 129px" class="d-none d-lg-block"></div>
    -->