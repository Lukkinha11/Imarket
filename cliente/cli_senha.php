            <?php
                ob_start();
                ini_set('default_charset','utf-8');
                require_once '../class/conexao2.php';
                require_once 'validate_cadastro.php'; 
                global $pdo;

                if(!isset($_SESSION['id']) AND !isset($_SESSION['nome']))
                {
                        header("Location: index.php");
                        exit();                    
                }
                $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $userid = $_SESSION['id'];
                $valido = true;
                $validate = new Validate();
            ?>

            <div class="col-8">
                <form action="" method="POST" class="row mb-3">
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">                           
                                <input type="password" name="Senha_Atual" class="form-control" value="<?php if(isset($data['Senha_Atual'])) echo $data['Senha_Atual'];?>" id="txtSenha" disabled>
                                <label for="txtSenha" class="form-label">Senha Atual</label >
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="password" class="form-control" name="Nova_Senha" value="<?php if(isset($data['Nova_Senha'])) echo $data['Nova_Senha'];?>" id="txtNova_Senha" maxlength="10" disabled>
                            <label for="txtNova_Senha" class="form-label">Nova Senha</label>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <input type="password" class="form-control" name="Confirmacao_Senha" value="<?php if(isset($data['Confirmacao_Senha'])) echo $data['Confirmacao_Senha'];?>" id="txtConfSenha" disabled>
                            <label for="txtConfSenha" class="form-label">Confirmação da Senha</label>
                        </div>
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-danger" type="button" name="habilitar" value="habilitar"  onclick="toogle_disabled( false )">Habilitar Campos Para Edição</button>
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-success" name="alterar" value="enviar" type="submit">Salvar Alterações</button>
                    </div>
                </form> 
                <?php

                    if(isset($_POST['alterar']))
                    {
                        if(isset($_POST['Senha_Atual']) AND isset($_POST['Nova_Senha']) AND isset($_POST['Confirmacao_Senha']))
                        {
                            $campos = Array('Senha_Atual', 'Nova_Senha', 'Confirmacao_Senha');
                            $cont = 1;
                            foreach ($campos as $campo)
                            {  
                                if(empty(strip_tags($_POST[$campo])))
                                {
                                    if($cont == 1)
                                    {
                                        $t = 'Senha Atual';
                                    }
                                    elseif($cont == 2)
                                    {
                                        $t = 'Nova Senha';
                                    }
                                    else
                                    {
                                        $t = 'Confirmação de senha';
                                    }   
                                    echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                            <strong>ATENÇÃO!</strong> Por favor preencha o Campo: <strong>$t!</strong>
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>"; 
                                    
                                    $valido = false;
                                }
                                $cont++;
                            }
                            
                            $query_senha = "SELECT idCliente, Senha FROM cliente WHERE idCliente ='".$userid."' LIMIT 1";
                            $result_senha = $pdo->prepare($query_senha);
                            $result_senha->execute();

                            while($row_senha = $result_senha->fetch(PDO::FETCH_ASSOC))
                            {
                                extract($row_senha);
                            }

                            $hash = $Senha;

                            if(password_verify($data['Senha_Atual'], $hash))
                            {
                                if(strlen($data['Nova_Senha']) < 6 AND strlen($data['Confirmacao_Senha']) < 6)
                                {
                                    echo"<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                                        <strong>ATENÇÃO!</strong> A senha deve ter no mínimo 6 caracteres!</strong>
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                                    $valido = false;
                                }
                                elseif(!$validate->senhaValida($_POST['Nova_Senha']) AND !$validate->senhaValida($_POST['Confirmacao_Senha']))
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
                                elseif($data['Nova_Senha'] == $data['Confirmacao_Senha'])
                                {
                                    if($valido)
                                    {
                                        $Senha_Atual = $_POST['Senha_Atual'];
                                        $Nova_Senha = $_POST['Nova_Senha'];
                                        $Confirmacao_Senha = $_POST['Confirmacao_Senha'];

                                        $novasenha = password_hash($data['Confirmacao_Senha'], PASSWORD_DEFAULT);
                                    
                                        $query_up_senha = "UPDATE cliente SET Senha =:Senha, Modificado = NOW()
                                                            WHERE idCliente =:idCliente
                                                            LIMIT 1";
                                        
                                        $result_up_senha = $pdo->prepare($query_up_senha);
                                        $result_up_senha->bindValue(':Senha', $novasenha, PDO::PARAM_STR);
                                        $result_up_senha->bindValue(':idCliente', $userid, PDO::PARAM_INT);

                                        if($result_up_senha->execute())
                                        {
                                            $_SESSION['msg'] = "<div class='alert alert-success alert-dismissible fade show text-center' role='alert'>
                                                                        <strong>Senha atualizada com sucesso!</strong> 
                                                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                                                    </div>";

                                            unset($_SESSION['id']);
                                            unset($_SESSION['nome']);
                                            unset($_SESSION['estado']);
                                            header("Location: index.php?page=login");
                                            exit();
                                        }
                                    } 
                                }
                                else
                                {
                                    echo"<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                                            <strong>ATENÇÃO!</strong> Senha e Confirmação de Senha não são iguais!
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>";
                                }
                            }
                            else
                            {
                                echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                        <strong>ATENÇÃO!</strong> Sua senha atual não equivale a senha cadastrada no site!
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                            } 
                        } 
                    }
                ?>                   
            </div>
        </div>
     </div>
 </main>
 <script src="../js manual/custom_checkout.js"></script>
 <script type="text/javascript">
    function toogle_disabled( bool )
    {
        var input = document.getElementsByTagName('input');
        var textarea = document.getElementsByTagName('textarea');

        for( var i=0; i<=(input.length-1); i++ )
        {
            if( input[i].type!='button' ) 
                input[i].disabled = bool;
        }
        for( var i=0; i<=(textarea.length-1); i++ )
        {
            textareat[i].disabled = bool;
        }
    }
</script>