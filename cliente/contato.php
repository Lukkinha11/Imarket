    <main class="flex-fill">
        <div class="container">
            <div class="row justify-content-center">             
                <?php
                    ini_set('default_charset', 'utf-8');
                    use PHPMailer\PHPMailer\PHPMailer;
                    use PHPMailer\PHPMailer\SMTP;
                    use PHPMailer\PHPMailer\Exception;
                    global $pdo;
                    require_once '../class/conexao2.php';              
                    require_once '../lib/src/PHPMailer.php'; 
                    require_once '../lib/src/SMTP.php'; 
                    require_once '../lib/src/Exception.php';
                ?>

                <?php
                    ob_start();
                    //unset($_POST["confmsg"]);
                    $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
					//var_dump($data);

                    $valido = true;
					if(!empty($data['confmsg']))
					{
						if(isset($_POST['confmsg']))
						{
							$campos = array("Nome","Email","Assunto","Menssagem");
                            //validação dos campos individualmente
                            foreach ($campos as $campo)
                            {
                                if (empty(strip_tags($_POST[$campo])))
                                {   
                                    echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                            <strong>ATENÇÃO!</strong> Por favor preencha o Campo: <strong>$campo!</strong>
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>";
                                        //var_dump($campo);
                                    
                                    $valido = false;
                                }
                            }

                            if($valido)
                            {
                                $Nome = $_POST['Nome'];
                                $Email = $_POST['Email'];
                                $Assunto = $_POST['Assunto'];
                                $Mensagem = $_POST['Menssagem'];
                                
                                
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

                                    $mail->setFrom($data['Email']); //QUEM ESTÁ ENVIANDO
                                    $mail->addAddress('imarket.contato@gmail.com.br'); //QUEM ESTÁ RECEBENDO

                                    $mail->isHTML(true);
                                    $mail->Subject = 'Confirmação de Contato';
                                    $mail->Body = "<p>Nome Completo: ".$data['Nome']."</p> 
                                                <p>Email: ".$data['Email']."</p> 
                                                <p>Assunto: ".$data['Assunto']."</p>
                                                <p>Mensagem: ".$data['Menssagem']."</p>";
                                    $mail->AltBody = " ".$data['Nome']." ".$data['Email']." ".$data['Assunto']." ".$data['Menssagem']." ";

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
                                header("Location: ?page=confcont");
                                exit();
                            }
						}
					}
                ?>

                <form method="POST" action="" class="col-sm-10 col-md-8 col-lg-6">
                 <h2 class="mb-3">Entre em contato</h2>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" value="<?php if(isset($data['Nome'])) echo $data['Nome'];?>" name="Nome" autofocus id="txtNome" placeholder=" ">
                        <label for="txtNome">Nome Completo</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="Email" value="<?php if(isset($data['Email'])) echo $data['Email'];?>" class="form-control"  id="txtEmail" placeholder=" ">
                        <label for="txtEmail">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="Assunto" value="<?php if(isset($data['Assunto'])) echo $data['Assunto'];?>" class="form-control"  id="txtAssunto" placeholder=" ">
                        <label for="txtAssunto">Assunto</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea type="text" class="form-control" name="Menssagem" value="<?php if(isset($data['Menssagem'])) echo $data['Menssagem'];?>" id="txtMensagem" placeholder=" " style="height: 200px;"></textarea>
                        <label for="txtMensagem">Mensagem</label>
                    </div>
                    <button class="btn btn-lg btn-primary" type="submit" value="enviar" name="confmsg">Enviar Mensagem</button>
                        <p class="mt-3">
                        Faremos nosso melhor para responder sua mensagem em até 1 dia útil.
                    </p>
                    <p class="mt-3">
                        Atenciosamente,
                        <br>Central de Relacionamento iMarket.
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