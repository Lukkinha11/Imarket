 <?php
    ob_start();
    ini_set('default_charset','utf-8');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    global $pdo;
    require_once '../class/conexao2.php';
    require_once 'validate_cadastro.php';              
    require_once '../lib/src/PHPMailer.php'; 
    require_once '../lib/src/SMTP.php'; 
    require_once '../lib/src/Exception.php';
    require_once '../lib/vendor/autoload.php'; 

    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $validate = new Validate();
    //var_dump($dados);

    //se existir a session ele imprime a mesma e depois destroí 
    if(isset($_SESSION['alert']))
    {
        echo $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
    
    //verifico se o usuário clicou no botão e o mesmo não está vazio
    if(!empty($dados['SendRecupSenha']))
    {
        //verificação se o campo email não está vazio
        if(!empty($dados['email']))
        {
            //verificação se o email digitado é valido
            if($validate->valida_email($dados['email']))
            {
                //o email sendo válido executa a query abaixo
                $query_verifica_email = "SELECT idCliente, Nome, Email FROM cliente
                                         WHERE Email =:Email
                                         LIMIT 1";

                $result_email = $pdo->prepare($query_verifica_email);
                $result_email->bindValue(':Email', $dados['email'], PDO::PARAM_STR);
                $result_email->execute();

                //a variável $result_email retornar um valor diferente de 0 entra no if
                if(($result_email) AND ($result_email->rowCount() != 0))
                {
                    $row_email = $result_email->fetch(PDO::FETCH_ASSOC);
                    $chave_recup_senha = password_hash($row_email['idCliente'], PASSWORD_DEFAULT);
                    $_SESSION['email'] = $row_email['Email'];
                    
                    //faz update na campo Chave_senha passando o valor que está na mesma
                    $query_up_senha = "UPDATE cliente SET Chave_senha =:Chave_senha
                                       WHERE idCliente =:idCliente
                                       LIMIT 1";
                    
                    $result_up_senha = $pdo->prepare($query_up_senha);
                    $result_up_senha->bindValue(':Chave_senha', $chave_recup_senha, PDO::PARAM_STR);
                    $result_up_senha->bindValue(':idCliente', $row_email['idCliente'], PDO::PARAM_INT);

                    //executando o update ele envia o email para o cliente
                    if($result_up_senha->execute())
                    {
                        $link = "http://localhost/imarket/cliente/cadastronovasenha.php?chave=$chave_recup_senha";

                        $mail = new PHPMailer(true);

                        try
                        {
                            $mail->CharSet = "UTF-8";
                            $mail->SMTPDebug =SMTP::DEBUG_SERVER;
                            $mail->isSMTP();
                            $mail->Host = 'smtp.mailtrap.io';
                            $mail->SMTPAuth = true;
                            $mail->Username = '8530fb73e12c0d';
                            $mail->Password = 'f1b0746516edc6';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 2525;

                            $mail->setFrom('imarket.atendimento@gmail.com'); //QUEM ESTÁ ENVIANDO O EMAIL
                            $mail->addAddress($row_email['Email'], $row_email['Nome']); //DESTINÁTARIO

                            $mail->isHTML(true);
                            $mail->Subject = 'Recuperação de Senha';
                            $mail->Body = "Prezado(a)" . $row_email['nome'] .".<br><br>Você solicitou uma alteração de senha<br><br>
                            Para continuar o processo de recuperação da sua senha, clique no link abaixo: <br><br>
                            <a href='".$link."'>Clique aqui</a></a><br><br> Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha
                            permanecerá a mesma até que você ative este código.<br><br>";

                            $mail->AltBody = "Prezado(a)" . $row_email['nome'] ."\n\n\Você solicitou uma alteração de senha\n\n\
                            Para continuar o processo de recuperação da sua senha, clique no link abaixo: \n\n\
                            ".$link." Clique aqui\n\n\Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha
                            permanecerá a mesma até que você ative este código.\n\n\ ";

                            if($mail->send())
                            {
                               
                            }else
                            {
                                echo "Email não enviado com sucesso";
                            }

                        } catch (Exception $e) 
                        {
                            echo "Erro ao enviar menssagem: {$mail->ErrorInfo}";
                        }
                        header("Location: ?page=confrecupsenha");
                        exit();
                    }

                }else
                {
                    echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                        <strong>ATENÇÃO!</strong> Email não cadastrado!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                }
            }
            else
            {
                echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                        <strong>ATENÇÃO!</strong> EMAIL INVÁLIDO!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            }
        }else
        {
            echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                    <strong>ATENÇÃO!</strong> Preencha o campo Email!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
        
        
    }
 ?>
 
 <main class="flex-fill">
     <div class="container">
         <div class="row justify-content-center">
             <form action="" class="col-sm-10 col-md-8 col-lg-6" method="POST">
                 <h2 class="mb-3">Recuperação de Senha</h2>
                 <div class="form-floating mb-3">
                     <input type="email" name="email" class="form-control" autofocus id="txtEmail" placeholder=" ">
                     <label for="txtEmail">E-mail</label>
                 </div>
                 <button type="submit" class="btn btn-lg btn-primary" name="SendRecupSenha" value="recuperar">Recuperar Senha</button>  
                 <p class="mt-3">
                    Ainda não é cadastrado? <a href="index.php?page=cad">Clique aqui</a> para se casdastrar.
                 </p>
                 
             </form>
         </div>
     </div>
 </main>

    <!--
        <p class="mt-3">
                    TESTE <a href="index.php?page=cadnewsenha">Clique aqui</a> para se casdastrar.
                 </p>                 
    <div style="height: 273px" class="d-block d-md-none"></div>
    <div style="height: 153px" class="d-none d-md-block d-lg-none"></div>
    <div style="height: 129px" class="d-none d-lg-block"></div>
    -->   