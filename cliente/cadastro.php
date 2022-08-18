 <main class="flex-fill">
     <div class="container">
         <h2>Informe seus dados, por favor</h2>

         <?php
                //Criptografando a senha com a função do PHP
                //echo password_hash(123456, PASSWORD_DEFAULT);
                
                ini_set('default_charset', 'utf-8');
                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\SMTP;
                use PHPMailer\PHPMailer\Exception;
                global $pdo;
                require_once '../class/conexao2.php';
                require_once 'validate_cadastro.php';              
                require_once '../lib/src/PHPMailer.php'; 
                require_once '../lib/src/SMTP.php'; 
                require_once '../lib/src/Exception.php';              
            
            ?>

         <?php
            //error_reporting(0);
            ob_start();
            unset($_POST["confirmacad"]);
            $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $validate = new Validate();
			
            if(isset($data['confirmacad']))
            {   
				$valido = true;
                if($validate->isCpf($data['cpf']))
                {
					if(strlen($data['ddd']) != 4)
					{
						echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
								<strong>ATENÇÃO!</strong> TAMANHO DO DDD É INVÁLIDO
								<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
							</div>";
							$valido = false;
					}
					if(strlen($data['telefone']) != 10)
					{
						echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
								<strong>ATENÇÃO!</strong> NÚMERO DE TELEFONE INVÁLIDO!
								<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
							</div>";
							$valido = false;
					}
					if($validate->valida_email($data['email']))
					{
						if(strlen($data['senha']) < 6)
						{
							echo"<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
									<strong>ATENÇÃO!</strong> A senha deve ter no mínimo 6 caracteres!</strong>
									<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
								</div>";						 	 
								$valido = false;
						}
                        elseif(strlen($data['senha']) != strlen($data['senhaC']))
						{
							echo"<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
									<strong>ATENÇÃO!</strong> SENHA E CONFIRMAÇÃO DE SENHA NÃO SÃO IGUAIS!
									<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
								</div>";
							$valido = false;	
						}
                        elseif(!$validate->senhaValida($data['senha']) AND !$validate->senhaValida($dados['senhaC']))
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
						
						$campos = Array("nome", "sobrenome", "data_nasc", "email", "senha", "cpf", "telefone", "ddd", "complemento", "referencia", "cep", "rua", "bairro", "cidade", "uf");
						//validação dos campos individualmente
						foreach ($campos as $campo)
						{
							if (empty(trim(strip_tags($_POST[$campo]))))
							{   
								echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
										<strong>ATENÇÃO!</strong> Por favor preencha o Campo: <strong>$campo!</strong>
										<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
									</div>";								 
								$valido = false;
							}
						}
						if ($valido)
						{
							$nome = $_POST["nome"];
							$sobrenome = $_POST["sobrenome"];
							$data_nasc = $_POST["data_nasc"];
							$email = $_POST["email"];
							$senha = $_POST["senha"];
							$cpf = $_POST["cpf"];
							$telefone = $_POST["telefone"];
							$ddd = $_POST["ddd"];
							$complemento = $_POST["complemento"];
							$referencia = $_POST["referencia"];
							$cep = $_POST["cep"];
							$rua = $_POST["rua"];
							$bairro = $_POST["bairro"];
							$cidade = $_POST["cidade"];
							$uf = $_POST["uf"];
							//resto do código

							$data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
							$validate->dateEmMysql($data['data_nasc']);

                            $pdo->beginTransaction();

                            //Verifica se o cep do cliente a ser cadastrado existe no banco de dados
                            $query_cep = "SELECT idEndereco, Cep FROM endereco WHERE Cep=:Cep LIMIT 1";

                            $result_cep = $pdo->prepare($query_cep);
                            $result_cep->bindValue(':Cep', $data['cep'], PDO::PARAM_STR);
                            
                            $result_cep->execute();

                            if(($result_cep) AND ($result_cep->rowCount() !=0))
                            {
                                $row_cep = $result_cep->fetch(PDO::FETCH_ASSOC);
        
                                extract($row_cep);

                                $query_cli ="INSERT INTO cliente(Nome, Sobrenome, Data_nasc, Email, Senha, CPF, Telefone, DDD, Endereco_idEndereco, Complemento, Referencia, Chave, Situacao_cliente_idSituacao_cliente, Criado)						
										                  VALUES(:Nome, :Sobrenome, :Data_nasc, :Email, :Senha, :CPF, :Telefone, :DDD, :Endereco_idEndereco, :Complemento, :Referencia, :Chave, 3, NOW())";

                                $add_cli = $pdo->prepare($query_cli);
                                $add_cli->bindValue(':Nome', $validate->Conta(trim($data['nome'], PDO::PARAM_STR)));
                                $add_cli->bindValue(':Sobrenome', $validate->Conta(trim($data['sobrenome'], PDO::PARAM_STR)));
                                $add_cli->bindValue(':Data_nasc', $validate->Conta($data['data_nasc'], PDO::PARAM_STR));
                                $add_cli->bindValue(':Email', $validate->Conta(trim($data['email'], PDO::PARAM_STR)));
                                $add_cli->bindValue(':Senha',$validate->Conta(trim($data['senha'], PDO::PARAM_STR)));
                                $add_cli->bindValue(':CPF', $validate->Conta(trim($data['cpf'], PDO::PARAM_STR)));
                                $add_cli->bindValue(':Telefone', $validate->Conta(trim($data['telefone'], PDO::PARAM_STR)));
                                $add_cli->bindValue(':DDD', $validate->Conta(trim($data['ddd'], PDO::PARAM_STR)));
                                $add_cli->bindValue(':Endereco_idEndereco', $idEndereco, PDO::PARAM_INT);
                                $add_cli->bindValue(':Complemento', $validate->Conta(trim($data['complemento'], PDO::PARAM_STR)));
                                $add_cli->bindValue(':Referencia', $validate->Conta(trim($data['referencia'], PDO::PARAM_STR)));
                                $chave = password_hash($data['email'] . date("Y-m-d H:i,s"), PASSWORD_DEFAULT);
                                $add_cli->bindValue(':Chave', $chave, PDO::PARAM_STR);

                                try
                                {
                                    if($add_cli->execute())
                                    {
                                        $pdo->commit();

                                        if($add_cli->rowCount())
                                        {
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

                                                $mail->setFrom('imarket.atendimento@gmail.com.br'); //QUEM ESTÁ ENVIANDO O EMAIL
                                                $mail->addAddress($data['email'], $data['nome']);

                                                $mail->isHTML(true);
                                                $mail->Subject = 'Confirmação de Cadastro';
                                                $mail->Body = "Prezado(a)" . " " . $data['nome'] . ".<br><br>Agradecemos a sua solicitação de cadastro em nosso site!<br><br>
                                                para que possamos liberar o seu cadastro em nosso sitema, solicitamos a confirmação do email clicando no link abaixo: <br><br>
                                                <a href='http://localhost/imarket/cliente/conf_email.php?chave=$chave'>Clique aqui</a><br><br>
                                                Esta menssagem foi enviada a você pela empresa iMarket LTDA.<br><br>Você está recebendo este email porque está
                                                cadastrado no banco de dados da empresa iMarket LTDA.<br><br>Nenhum email enviado pela empresa iMarket LTDA tem 
                                                arquivos anexados ou solicita o preenchimento de senhas e informações cadastrais.<br><br>";

                                                $mail->AltBody = "Prezado(a)" . " "  . $data['nome'] . ".\n\nAgradecemos a sua solicitação de cadastro em nosso site!\n\n
                                                Para que possamos liberar o seu cadastro em nosso sitema, solicitamos a confirmação do email clicando no link abaixo: \n\n
                                                http://localhost/imarket/cliente/conf_email.php?chave=$chave \n\n
                                                Esta menssagem foi enviada a você pela empresa iMarket LTDA.\n\nVocê está recebendo este email porque está
                                                cadastrado no banco de dados da empresa iMarket LTDA.\n\nNenhum email enviado pela empresa iMarket LTDA tem 
                                                arquivos anexados ou solicita o preenchimento de senhas e informações cadastrais.\n\n";

                                                if($mail->send())
                                                {
                                                
                                                }else
                                                {
                                                    echo "Email não enviado com sucesso";
                                                }

                                            }
                                            catch (Exception $e) 
                                            {
                                                echo "Erro ao enviar menssagem: {$mail->ErrorInfo}";
                                            }
                                            unset($_SESSION['data']);
                                            $_SESSION['data'] = $data['data_nasc'];
                                            $_SESSION['nome_cli'] = $data['nome'];
                                            $_SESSION['email_cli'] = $data['email'];
                                            header("Location: ?page=confcad");
                                            exit();
                                                
                                        }
                                        else
                                        {
                                            echo"ERRO";
                                        }
                                    }
                                }
                                catch(PDOException $e) 
                                {
                                    $pdo->rollBack();
                                    if($e->getCode() == 23000)
                                    {
                                        echo "<div class='alert alert-danger text-center' role='alert'>O Email ou CPF já está cadastrado no sistema! <a href='index.php?page=login'>Fazer Login</a></div>";
                                    }
                                    else
                                    {  
                                        echo "<div class='alert alert-danger' role='alert'>Algo deu errado no cliente#1: ". $e->getMessage() ."</div>";
                                    }
                                }
                            }
                            else
                            {
                                //Não existindo o cep do funcionário no banco de dados o sistema cadastra na tabela de endereço
                                $query_end = "INSERT INTO endereco(Cep, Logradouro, Bairro, Cidade, Estado) 
                                                            VALUES(:Cep, :Logradouro, :Bairro, :Cidade, :Estado)";

                                $add_end = $pdo->prepare($query_end);
                                $add_end->bindValue(':Cep', $validate->Conta(trim($data['cep'], PDO::PARAM_STR)));
                                $add_end->bindValue(':Logradouro', $validate->Conta(trim($data['rua'], PDO::PARAM_STR)));
                                $add_end->bindValue(':Bairro', $validate->Conta(trim($data['bairro'], PDO::PARAM_STR)));
                                $add_end->bindValue(':Cidade', $validate->Conta(trim($data['cidade'], PDO::PARAM_STR)));
                                $add_end->bindValue(':Estado', $validate->Conta(trim($data['uf'], PDO::PARAM_STR)));

                                try
                                {
                                    if($add_end->execute())
                                    {
                                        $id_endereco = $pdo->lastInsertId();

                                        $query_cli ="INSERT INTO cliente(Nome, Sobrenome, Data_nasc, Email, Senha, CPF, Telefone, DDD, Endereco_idEndereco, Complemento, Referencia, Chave, Situacao_cliente_idSituacao_cliente, Criado)						
										                          VALUES(:Nome, :Sobrenome, :Data_nasc, :Email, :Senha, :CPF, :Telefone, :DDD, :Endereco_idEndereco, :Complemento, :Referencia, :Chave, 3, NOW())";

                                        $add_cli = $pdo->prepare($query_cli);
                                        $add_cli->bindValue(':Nome', $validate->Conta(trim($data['nome'], PDO::PARAM_STR)));
                                        $add_cli->bindValue(':Sobrenome', $validate->Conta(trim($data['sobrenome'], PDO::PARAM_STR)));
                                        $add_cli->bindValue(':Data_nasc', $validate->Conta($data['data_nasc'], PDO::PARAM_STR));
                                        $add_cli->bindValue(':Email', $validate->Conta(trim($data['email'], PDO::PARAM_STR)));
                                        $add_cli->bindValue(':Senha',$validate->Conta(trim($data['senha'], PDO::PARAM_STR)));
                                        $add_cli->bindValue(':CPF', $validate->Conta(trim($data['cpf'], PDO::PARAM_STR)));
                                        $add_cli->bindValue(':Telefone', $validate->Conta(trim($data['telefone'], PDO::PARAM_STR)));
                                        $add_cli->bindValue(':DDD', $validate->Conta(trim($data['ddd'], PDO::PARAM_STR)));
                                        $add_cli->bindValue(':Endereco_idEndereco', $id_endereco, PDO::PARAM_INT);
                                        $add_cli->bindValue(':Complemento', $validate->Conta(trim($data['complemento'], PDO::PARAM_STR)));
                                        $add_cli->bindValue(':Referencia', $validate->Conta(trim($data['referencia'], PDO::PARAM_STR)));
                                        $chave = password_hash($data['email'] . date("Y-m-d H:i,s"), PASSWORD_DEFAULT);
                                        $add_cli->bindValue(':Chave', $chave, PDO::PARAM_STR);

                                        try
                                        {
                                            if($add_cli->execute())
                                            {
                                                $pdo->commit();

                                                if($add_cli->rowCount())
                                                {
                                                    $mail = new PHPMailer(true);
                                                    try
                                                    {
                                                        $mail->CharSet = "UTF-8";
                                                        $mail->SMTPDebug =SMTP::DEBUG_SERVER;
                                                        $mail->isSMTP();
                                                        $mail->Host = 'smtp.gmail.com';
                                                        $mail->SMTPAuth = true;
                                                        $mail->Username = '1191.ti@gmail.com';
                                                        $mail->Password = 'Info2021*';
                                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                                        $mail->Port = 587;

                                                        $mail->setFrom('1191.ti@gmail.com');
                                                        $mail->addAddress($data['email'], $data['nome']);

                                                        $mail->isHTML(true);
                                                        $mail->Subject = 'Confirmação de Cadastro';
                                                        $mail->Body = "Prezado(a)". " " . $data['nome'] . ".<br><br>Agradecemos a sua solicitação de cadastro em nosso site!<br><br>
                                                        para que possamos liberar o seu cadastro em nosso sitema, solicitamos a confirmação do email clicando no link abaixo: <br><br>
                                                        <a href='http://localhost/imarket/cliente/conf_email.php?chave=$chave'>Clique aqui</a><br><br>
                                                        Esta menssagem foi enviada a voçê pela empresa iMarket LTDA.<br><br>Você está recebendo este email porque está
                                                        cadastrado no banco de dados da empresa iMarket LTDA.<br><br>Nenhum email enviado pela empresa iMarket LTDA tem 
                                                        arquivos anexados ou solicita o preenchimento de senhas e informações cadastrais.<br><br>";

                                                        $mail->AltBody = "Prezado(a)" . $data['nome'] . ".\n\nAgradecemos a sua solicitação de cadastro em nosso site!\n\n
                                                        Para que possamos liberar o seu cadastro em nosso sitema, solicitamos a confirmação do email clicando no link abaixo: \n\n
                                                        http://localhost/imarket/cliente/conf_email.php?chave=$chave \n\n
                                                        Esta menssagem foi enviada a voçê pela empresa iMarket LTDA.\n\nVocê está recebendo este email porque está
                                                        cadastrado no banco de dados da empresa iMarket LTDA.\n\nNenhum email enviado pela empresa iMarket LTDA tem 
                                                        arquivos anexados ou solicita o preenchimento de senhas e informações cadastrais.\n\n";

                                                        if($mail->send())
                                                        {
                                                        
                                                        }else
                                                        {
                                                            echo "Email não enviado com sucesso";
                                                        }

                                                    }
                                                    catch (Exception $e) 
                                                    {
                                                        echo "Erro ao enviar menssagem: {$mail->ErrorInfo}";
                                                    }
                                                    unset($_SESSION['data']);
                                                    $_SESSION['data'] = $data['data_nasc'];
                                                    $_SESSION['nome_cli'] = $data['nome'];
                                                    $_SESSION['email_cli'] = $data['email'];
                                                    header("Location: ?page=confcad");
                                                    exit();     
                                                }
                                                else
                                                {
                                                    echo"ERRO";
                                                }
                                            }
                                        }
                                        catch(PDOException $e) 
                                        {
                                            $pdo->rollBack();
                                            if( $e->getCode() == 23000)
                                            {
                                               echo "<div class='alert alert-danger text-center' role='alert'>O Email ou CPF já está cadastrado no sistema! <a href='index.php?page=login'>Fazer Login</a></div>";
                                            }
                                            else
                                            {  
                                                echo "<div class='alert alert-danger' role='alert'>Algo deu errado no cliente#2: ". $e->getMessage() ."</div>";
                                            }
                                        }   
                                    } 
                                }
                                catch(PDOException $e) 
                                {
                                    $pdo->rollBack();
                                    echo "<div class='alert alert-danger' role='alert'>Algo deu errado no endereco: ". $e->getMessage() ."</div>";
                                }
                            }
						}
					}else
					{
						echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
								<strong>ATENÇÃO!</strong> EMAIL INVÁLIDO!
								<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
							</div>";
					}					
				}
				else
                {
                    echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
							<strong>ATENÇÃO!</strong> CPF INVÁLIDO!
							<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
						</div>";
                }   
            }
         ?>

         <hr>
         <form method="POST" name="inserir" id="inserir">
             <div class="row">
                 <div class="col-sm-16 col-md-6">
                     <fieldset class="row gx-3">
                         <legend>Dados Pessoais</legend>
                         <div class="mb-3">
                             <label for="txtNome" class="form-label">Nome</label>
                             <input type="text" value="<?php if(isset($data['nome'])) echo $data['nome'];?>" autofocus name="nome" class="form-control" id="txtNome" >
                         </div>
                         <div class="mb-3">
                             <label for="txtSobrenome" class="form-label">Sobrenome</label>
                             <input type="text" name="sobrenome" value="<?php if(isset($data['sobrenome'])) echo $data['sobrenome'];?>" class="form-control" id="txtSobrenome" >
                         </div>
                         <div class=" mb-3 col-md-6 col-xl-4">
                             <label for="txtCPF" CPF class="form-label">CPF</label>
                             <span class="form-text">(somente números)</span>
                             <input type="text" name="cpf" class="form-control" value="<?php if(isset($data['cpf'])) echo $data['cpf'];?>" id="txtCPF" maxlength="14" value="" oninput="maskCPF(this)" >
                         </div>
                         <div class="mb-3 col-md-6 col-xl-4">
                             <label for="txtDataNascimento" class="form-label">Data de Nascimento</label>
                             <input type="date" class="form-control" value="<?php if(!empty($data['data_nasc'])){echo $data['data_nasc'];} ?>" name="data_nasc" id="txtDataNascimento"  type="date" max="2999-12-31" >
                         </div>
                     </fieldset>
                     <fieldset>
                         <legend>Contatos</legend>
                         <div class="mb-3 col-md-8">
                             <label for="txtEmail" class="form-label">E-mail</label>
                             <input type="email" name="email" value="<?php if(isset($data['email'])) echo $data['email'];?>" class="form-control" id="txtEmail" >
                         </div>                      
                         <div class="mb-3 col-md-4">
                             <label for="txtDDD" class="form-label">DDD</label>
                             <span class="form-text">(somente números)</span>
                             <input type="tel" class="form-control" id="txtDDD" value="<?php if(isset($data['ddd'])) echo $data['ddd'];?>" name="ddd" placeholder="61" maxlength="4" oninput="maskDDD(this)" value="" >
                         </div>
						 <div class="mb-3 col-md-6">
                             <label for="txtTelefone" class="form-label">Telefone</label>
                             <span class="form-text">(somente números)</span>
                             <input type="tel" class="form-control" value="<?php if(isset($data['telefone'])) echo $data['telefone'];?>" id="txtTelefone" name="telefone" maxlength="10" placeholder="999999999" value="" oninput="maskPhone(this)" >
                         </div>
                     </fieldset>
                 </div>
                 <div class="col-sm-12 col-md-6">
                     <fieldset class="row">
                         <legend>Endereço</legend>
                         <div class="mb-3 col-md-6 col-lg-4">
                             <label for="cep" class="form-label mascCEP">CEP</label>
                             <div class="input-group">
                                 <input type="text" class="form-control" value="<?php if(isset($data['cep'])) echo $data['cep'];?>" id="cep" name="cep" min="9" maxlength="9" onblur="pesquisacep(this.value);">
                                 <span class="input-group-text p-1">
                                     <svg class="bi" width="24" height="24" fill="currentColor">
                                         <use xlink:href="../icons/bi.svg#hourglass-split" />
                                     </svg>
                                 </span>
                             </div>
                         </div>
                         <div class="mb-3 col-md-8">
                             <label for="bairro" class="form-label mascCEP">Logradouro</label>
                             <input type="text" class="form-control" value="<?php if(isset($data['rua'])) echo $data['rua'];?>" id="rua" name="rua" >
                         </div>
                         <div class="mb-3 col-md-12">
                             <label for="rua" class="form-label mascCEP">Bairro</label>
                             <input type="text" class="form-control" value="<?php if(isset($data['bairro'])) echo $data['bairro'];?>" id="bairro" name="bairro" >
                         </div>
                         <div class="mb-3 col-md-6">
                             <label for="cidade" class="form-label mascCEP">Cidade</label>
                             <input type="text" class="form-control" value="<?php if(isset($data['cidade'])) echo $data['cidade'];?>" id="cidade" name="cidade">
                         </div>
                         <div class="mb-3 col-md-6">
                             <label for="uf" class="form-label mascCEP">Estado</label>
                             <input type="text" class="form-control" value="<?php if(isset($data['uf'])) echo $data['uf'];?>" id="uf" name="uf">
                         </div>
                         <div class="mb-3">
                                <label for="txtComplemento" class="form-label">Complemento</label>
                                <input type="text" name="complemento" value="<?php if(isset($data['complemento'])) echo $data['complemento'];?>" class="form-control" id="txtComplemento" >
                         </div>
                         <div class="mb-3">
                                <label for="txtReferencia" class="form-label">Ponto de Referência</label>
                                <input type="text" class="form-control" value="<?php if(isset($data['referencia'])) echo $data['referencia'];?>" name="referencia" id="txtReferencia">
                         </div>
                         <!--<div class="mb-3 col-md-6 col-lg-8 align-self-end">
                             <textarea class="form-control text-muted bg-light" style="height:68px">Digite o CEP para buscar o endereço.</textarea>
                         </div>-->
                     </fieldset>
                     <fieldset>
                         <legend>Senha de Acesso</legend>
                         <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="senha" id="senha"  class="form-control" id="txtSenha" >
                         </div>
                         <div class="mb-3">
                                <label for="senhaC" class="form-label">Confirmação da Senha</label>
                                <input type="password" name="senhaC" id="senhaC"  class="form-control" id="txtConfSenha" >
                         </div>
                     </fieldset>
                 </div>
             </div>
             <hr>
             <!--<div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" value="" id="chkPromocoes">
                    <label for="chkPromocoes" class="form-check-label">Desejo receber informações sobre promoções.</label>
             </div>-->
             <div class="mb-3">
                    <a href="?pg=index" class="btn btn-light btn-outline-danger">Cancelar</a>
                    <input type="submit" id="enviar" value="Criar Cadastro" name="confirmacad" class="btn btn-success">               
             </div>
         </form>
     </div>
 </main>

    <!--                 
    <div style="height: 273px" class="d-block d-md-none"></div>
    <div style="height: 153px" class="d-none d-md-block d-lg-none"></div>
    <div style="height: 129px" class="d-none d-lg-block"></div>
    -->
 <script src="../js manual/custom_senha_cli.js"></script>   
 <script src="../js manual/custom_checkout.js"></script>
 <script src="../js manual/custom_data_nasc.js"></script>