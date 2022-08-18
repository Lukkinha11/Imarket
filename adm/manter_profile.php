                        <?php
                            ob_start();
                            ini_set('default_charset','utf-8');
                            require_once 'validate_cadastro.php'; 
                            require_once '../class/conexao2.php';
                            global $pdo;

                            ob_start();
                            ini_set('default_charset','utf-8');
                            if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome'])) AND (!isset($_SESSION['acesso'])) OR ($_SESSION['status_acesso'] == 1))
                            {
                                header("Location: ../index.php");
                                exit();
                            }

                            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                            $validate = new Validate();

                            if(isset($dados['alterar']) )
                            {
                                if(isset($dados['senha']) AND isset($dados['senhaC']) AND isset($dados['login']))
                                {
                                    if(empty(trim($dados['login'])))
                                    {
                                        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preecha o Campo Login!</div>";
                                    }
                                    elseif(empty($dados['senha']))
                                    {
                                        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preecha o Campo Senha!</div>";
                                    }
                                    elseif(empty($dados['senhaC']))
                                    {
                                        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Preecha o Campo Confirmação de Senha!</div>";
                                    }
                                    elseif(!$validate->senhaValida($dados['senha']) AND !$validate->senhaValida($dados['senhaC']) AND strlen($dados['senhaC'] < 6))
                                    {
                                        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Insira uma senha de no mínimo 6 caracteres sendo:<br>
                                                                *1 caractere sendo Número,<br>
                                                                *1 caractere Maiúsculo,<br>
                                                                *1 caractere Minúsculo,<br>
                                                                *1 caractere Especial execeto: = e -.
                                                            </div>";
                                    }
                                    elseif($dados['senha'] != $dados['senhaC'])
                                    {
                                        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'><strong>Erro:</strong> Senha e Confirmação de Senha não são iguais!</div>";
                                    }
                                    else
                                    {
                                        $novasenha = password_hash($dados['senhaC'], PASSWORD_DEFAULT);
                                        //echo $novasenha ;

                                        $query_upd_func = "UPDATE funcionarios SET Senha=:Senha, Login=:Login WHERE idFuncionarios=:idFuncionarios";
                                        $result_upd_func = $pdo->prepare($query_upd_func);
                                        $result_upd_func->bindValue(':Senha', $novasenha, PDO::PARAM_STR);
                                        $result_upd_func->bindValue(':Login', $dados['login'], PDO::PARAM_STR);
                                        $result_upd_func->bindValue(':idFuncionarios', $_SESSION['id'], PDO::PARAM_INT);

                                        if($result_upd_func->execute())
                                        {
                                            $_SESSION['msg_dados'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                                                        <strong>Sucesso!</strong> Dados editados com sucesso!
                                                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                                                    </div>";

                                            header("Location: ../index.php");
                                            unset($_SESSION['id'] );
                                            unset($_SESSION['nome'] );
                                            unset($_SESSION['acesso'] );
                                            unset($_SESSION['status_acesso'] );
                                            exit();
                                        }
                                    }
                                }  
                            }    
                        ?>
                        <div class="row my-5">
                            <div class="d-flex justify-content-between align-items-center pt-3 pb-2">
                                <h3 class="fs-4 mb-3">Minha Conta</h3>
                            </div>
                            <span id="msgAlert"></span>
                            <div style="background-color: white; padding-bottom: 10px; padding-top:10px">
                                <div class="col" >
                                    <?php
                                        $query_func = "SELECT idFuncionarios, Matricula, Nome, Sobrenome, date_format(Data_nasc, '%d/%m/%Y') AS Data_nasc, Desc_Cargo, Login, CPF, Email, Telefone, DDD, Cep, Logradouro, Bairro, Cidade, Estado, Complemento, date_format(Admissao, '%d/%m/%Y') AS Admissao FROM funcionarios
                                                        INNER JOIN cargo
                                                        ON funcionarios.Cargo_idCargo = cargo.idCargo
                                                        INNER JOIN endereco
                                                        ON funcionarios.Endereco_idEndereco = endereco.idEndereco 
                                                        WHERE idFuncionarios=:idFuncionarios";
                                        
                                        $result_func = $pdo->prepare($query_func);
                                        $result_func->bindValue(':idFuncionarios', $_SESSION['id'], PDO::PARAM_INT);
                                        $result_func->execute();

                                        while($row_func = $result_func->fetch(PDO::FETCH_ASSOC))
                                        {
                                            extract($row_func); 
                                    ?>
                                            <form method="POST" class="row g-3">
                                                <?php
                                                    if(isset($_SESSION['msg']))
                                                    {
                                                        echo  $_SESSION['msg'];
                                                        unset($_SESSION['msg']); 
                                                    }
                                                ?>
                                                <label for="inputInfo" class="form-label">Somente os campos com * podem ser alterados!</label>
                                                <div class="col-md-2">
                                                    <label for="inputMatricula" class="form-label">Matrícula</label>
                                                    <input type="text" name="matricula" class="form-control" id="inputMatricula" value="<?php echo $Matricula; ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="inputNome" class="form-label">Nome</label>
                                                    <input type="text" name="nome" class="form-control" id="inputNome" value="<?php echo $Nome; ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="inputSobrenome" class="form-label">Sobrenome</label>
                                                    <input type="text" name="sobrenome" class="form-control" id="inputSobrenome" value="<?php echo $Sobrenome; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputDataNasc" class="form-label">Data de Nascimento</label>
                                                    <input type="text" name="data_nasc" class="form-control" id="inputDataNasc" value="<?php echo $Data_nasc; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputCargo" class="form-label">Cargo</label>
                                                    <input type="text" name="cargo" class="form-control" id="inputCargo" value="<?php echo $Desc_Cargo; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputLogin" class="form-label">*Login</label>
                                                    <input type="text" name="login" class="form-control" id="inputLogin" value="<?php echo $Login; ?>" disabled>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputCPF" class="form-label">CPF</label>
                                                    <input type="text" name="cpf" class="form-control" id="inputCPF" value="<?php echo $CPF; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputEmail" class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control" id="inputEmail" value="<?php echo $Email; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputDDD" class="form-label">DDD</label>
                                                    <input type="text" name="ddd" class="form-control" id="inputDDD" value="<?php echo $DDD; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputTelefone" class="form-label">Telefone</label>
                                                    <input type="text" name="telefone" class="form-control" id="inputTelefone" value="<?php echo $Telefone; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputCEP" class="form-label">CEP</label>
                                                    <input type="text" name="cep" class="form-control" id="inputCEP" value="<?php echo $Cep; ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="inputLogradouro" class="form-label">Logradouro</label>
                                                    <input type="text" name="logradouro" class="form-control" id="inputLogradouro" value="<?php echo $Logradouro; ?>" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="inputCidade" class="form-label">Cidade</label>
                                                    <input type="text" name="cidade" class="form-control" id="inputCidade" value="<?php echo $Cidade; ?>" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="inputEstado" class="form-label">Estado</label>
                                                    <input type="text" name="estado" class="form-control" id="inputEstado" value="<?php echo $Estado; ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="inputComplemento" class="form-label">Complemento</label>
                                                    <input type="text" name="complemento" class="form-control" id="inputComplemento" value="<?php echo $Complemento; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputAdmissao" class="form-label">Admissão</label>
                                                    <input type="text" name="admissao" class="form-control" id="inputAdmissao" value="<?php echo $Admissao; ?>" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="senha" class="form-label">*Senha</label>
                                                    <input type="password" name="senha" onkeyup="this.value = Trim(this.value )" value="<?php if(isset($dados['senha'])) echo $dados['senha'];?>" class="form-control" id="senha" disabled>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="senhaC" class="form-label">*Confrimação de Senha</label>
                                                    <input type="password" name="senhaC" onkeyup="this.value = Trim(this.value )" value="<?php if(isset($dados['senhaC'])) echo $dados['senhaC'];?>" class="form-control" id="senhaC" disabled>
                                                </div>
                                                <div class="col-12">
                                                    <button type="button" class="btn btn-danger" onclick="toogle_disabled( false )">Habilitar campos para alteração</button>
                                                    <button type="submit" name="alterar" class="btn btn-success">Salvar alterações</button>
                                                </div>
                                            </form>
                                    <?php
                                        }//Fim While
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js manual/custom_senha_cli.js"></script>
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
            <script type="text/javaScript">
                function Trim(str)
                {
                    return str.replace(/^\s+|\s+$/g,"");
                }
            </script><!-- /#page-content-wrapper 
            <script src="../js manual/Manter_perfil.js"></script>-->