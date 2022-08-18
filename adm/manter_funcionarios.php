                        <?php
                            ob_start();
                            ini_set('default_charset','utf-8');
                            if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome'])) AND (!isset($_SESSION['acesso'])) OR ($_SESSION['status_acesso'] == 1))
                            {
                                header("Location: ../index.php");
                                exit();
                            }
                            elseif($_SESSION['acesso'] >= 2)
                            {
                                header("Location: ../index.php");
                                exit();
                            }
                        ?>
                        <div class="row my-5">
                            <div class="d-flex justify-content-between align-items-center pt-3 pb-2">
                                <h3 class="fs-4 mb-3">Cadastro de Funcionários</h3>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadFuncionariosModal">Cadastrar Funcionários</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_funcionarios" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Matrícula</th>
                                            <th>Nome</th>
                                            <th>Sobrenome</th>
                                            <th>Data de Nascimento</th>
                                            <th>Cargo</th>
                                            <th>Login</th>
                                            <th>CPF</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>DDD</th>
                                            <th>CEP</th>
                                            <th>Logradouro</th>
                                            <th>Bairro</th>
                                            <th>Cidade</th>
                                            <th>Estado</th>
                                            <th>Complemento</th>
                                            <th>Status</th>
                                            <th>Admissão</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /#page-content-wrapper -->

            <!-- Modal Cadastrar -->
            <div class="modal fade" id="cadFuncionariosModal" tabindex="-1" aria-labelledby="cadFuncionariosModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadFuncionariosModalLabel">Cadastro de Funcionários</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <hr>
                            <form method="POST" id="inserir">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <fieldset class="row gx-3">
                                            <legend>Dados Pessoais</legend>
                                            <div class="mb-3">
                                                <label for="txtNome" class="form-label">Nome</label>
                                                <input type="text" name="nome" class="form-control" id="txtNome"  autofocus>
                                            </div>
                                            <div class="mb-3">
                                                <label for="txtSobrenome" class="form-label">Sobrenome</label>
                                                <input type="text" name="sobrenome" class="form-control" id="txtSobrenome" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="txtLogin" class="form-label">Login</label>
                                                <input type="text" name="login" class="form-control" id="txtLogin" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="selectstatus" class="form-label">Status do Funcionário</label>
                                                <span class="form-text">(Ex: ATIVO)</span>
                                                <select class="form-select" id="selectstatus" name="status" aria-label="Default select example">
                                                    <option>Selecione</option>
                                                    <option value="ATIVO">ATIVO</option>
                                                    <option value="INATIVO">INATIVO</option>
                                                </select>
                                            </div>      
                                            <div class=" mb-3 col-md-6 col-xl-4">
                                                <label for="txtCPF" CPF class="form-label">CPF</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="text" name="cpf" class="form-control" id="txtCPF" maxlength="14" value="" oninput="maskCPF(this)" >
                                            </div>
                                            <div class="mb-3 col-md-6 col-xl-4">
                                                <label for="txtDataNascimento" class="form-label">Data de Nascimento</label>
                                                <input type="date" class="form-control" name="data_nasc" id="txtDataNascimento" max="2999-12-31" >
                                            </div>
                                            <div class="mb-3 col-md-6 col-xl-4">
                                                <label for="selectacesso" class="form-label">Nível de Acesso</label>
                                                <select class="form-select" id="selectacesso" name="acesso" aria-label="Default select example">
                                                    <option>Selecione</option>
                                                    <?php

                                                        ini_set('default_charset', 'utf-8');
                                                        require_once '../class/conexao2.php';
                                                        global $pdo;
                                                        
                                                        $query_acesso = "SELECT idAcesso, Nivel FROM acesso order by idAcesso";
                                                        $result_acesso = $pdo->prepare($query_acesso);
                                                        $result_acesso->execute();

                                                        while ($dado_acesso = $result_acesso->fetch(PDO::FETCH_ASSOC)) 
                                                            {
                                                                ?>
                                                                
                                                                    <option value="<?php echo $dado_acesso['idAcesso'] ?>"><?php echo $dado_acesso['Nivel'] ?></option>
                                                                                        
                                                                <?php
                                                            }
                                                        ?>
                                                </select>
                                            </div> 
                                        </fieldset>
                                        <fieldset class="row">
                                            <legend>Contatos</legend>
                                            <div class="mb-3 col-md-6">
                                                <label for="txtEmail" class="form-label">E-mail</label>
                                                <input type="email" name="email" class="form-control" id="txtEmail" >
                                            </div> 
                                            <div class="mb-3 col-md-6">
                                                <label for="selectcargo" class="form-label">Cargo</label>
                                                <span class="form-text">(Ex: Diretor)</span>
                                                <select class="form-select" id="selectcargo" name="cargo" aria-label="Default select example">
                                                    <option>Selecione</option>
                                                    <?php

                                                        ini_set('default_charset', 'utf-8');
                                                        require_once '../class/conexao2.php';
                                                        global $pdo;
                                                        
                                                        $query_cargo = "SELECT idCargo, Desc_cargo FROM cargo order by idCargo";
                                                        $result_cargo = $pdo->prepare($query_cargo);
                                                        $result_cargo->execute();

                                                        while ($dado_cargo = $result_cargo->fetch(PDO::FETCH_ASSOC)) 
                                                            {
                                                                ?>
                                                                
                                                                    <option value="<?php echo $dado_cargo['idCargo'] ?>"><?php echo $dado_cargo['Desc_cargo'] ?></option>
                                                                                        
                                                                <?php
                                                            }
                                                        ?>
                                                </select>
                                            </div>                     
                                            <div class="mb-3 col-md-6">
                                                <label for="txtDDD" class="form-label">DDD</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="tel" class="form-control" id="txtDDD" name="ddd" placeholder="61" maxlength="4" oninput="maskDDD(this)">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="txtTelefone" class="form-label">Telefone</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="tel" class="form-control"  id="txtTelefone" name="telefone" maxlength="10" placeholder="999999999" oninput="maskPhone(this)" >
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <fieldset class="row">
                                            <legend>Endereço</legend>
                                            <div class="mb-3 col-md-6 col-lg-4">
                                                <label for="cep" class="form-label mascCEP">CEP</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="cep" name="cep" min="9" maxlength="9" onblur="pesquisacep(this.value);" value="" >
                                                    <span class="input-group-text p-1">
                                                        <svg class="bi" width="24" height="24" fill="currentColor">
                                                            <use xlink:href="../icons/bi.svg#hourglass-split" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-md-8">
                                                <label for="bairro" class="form-label mascCEP">Logradouro</label>
                                                <input type="text" class="form-control" id="rua" name="rua" >
                                            </div>
                                            <div class="mb-3 col-md-12">
                                                <label for="rua" class="form-label mascCEP">Bairro</label>
                                                <input type="text" class="form-control" id="bairro" name="bairro" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="cidade" class="form-label mascCEP">Cidade</label>
                                                <input type="text" class="form-control" id="cidade" name="cidade" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="uf" class="form-label mascCEP">Estado</label>
                                                <input type="text" class="form-control" id="uf" name="uf" >
                                            </div>
                                            <div class="mb-3">
                                                <label for="txtComplemento" class="form-label">Complemento</label>
                                                <input type="text" name="complemento" class="form-control" id="txtComplemento" >
                                            </div>
                                            <!--<div class="mb-3">
                                                <label for="txtReferencia" class="form-label">Ponto de Referência</label>
                                                <input type="text" class="form-control" name="referencia" id="txtReferencia">
                                            </div>
                                            <div class="mb-3 col-md-6 col-lg-8 align-self-end">
                                                <textarea class="form-control text-muted bg-light" style="height:68px">Digite o CEP para buscar o endereço.</textarea>
                                            </div>-->
                                        </fieldset>
                                        <fieldset>
                                            <legend>Senha de Acesso</legend>
                                            <div class="mb-3">
                                                <label for="txtSenha" class="form-label">Senha</label>
                                                <input type="password" name="senha" id="senha" class="form-control" id="txtSenha" onkeyup="this.value = Trim(this.value )" />
                                                <span id="senha-status"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="txtConfSenha" class="form-label">Confirmação da Senha</label>
                                                <input type="password" name="senhaC" id="senhaC" class="form-control" id="txtConfSenha" onkeyup="this.value = Trim(this.value )" />
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" value="cadastrar" name="confirmacad" onclick="return validarSenha()" class="btn btn-success">Criar Cadastro</button>            
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Vizualizar
            <div class="modal fade" id="vizuTipoLogradouroModal" tabindex="-1" aria-labelledby="vizuTipoLogradouroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="vizuTipoLogradouroModalLabel">Detalhes do Tipo Logradouro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <dl class="row">
                                <dt class="col-sm-3">ID</dt>
                                <dd class="col-sm-9"><span id="idTipoLogradouro"></span></dd>

                                <dt class="col-sm-3">Tipo Logradouro</dt>
                                <dd class="col-sm-9"><span id="TipoLogradouro"></span></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>-->

            <!-- Modal Editar -->
            <div class="modal fade" id="editFuncionariosModal" tabindex="-1" aria-labelledby="editFuncionariosModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editFuncionariosModalLabel">Editar Cadastro de Funcionários</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroEdit"></span>
                            <hr>
                            <form method="POST" id="form-edit-func">
                                <input type="hidden" name="idfuncionarios" id="editId"> 
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <fieldset class="row gx-3">
                                            <legend>Dados Pessoais</legend>
                                            <div class="mb-3">
                                                <label for="txtEditNome" class="form-label">Nome</label>
                                                <input type="text" name="edit_nome" class="form-control" id="txtEditNome"  autofocus>
                                            </div>
                                            <div class="mb-3">
                                                <label for="txtEditSobrenome" class="form-label">Sobrenome</label>
                                                <input type="text" name="edit_sobrenome" class="form-control" id="txtEditSobrenome" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="txtEditLogin" class="form-label">Login</label>
                                                <input type="text" name="edit_login" class="form-control" id="txtEditLogin" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="Editstatus" class="form-label">Status do Funcionário</label>
                                                <span class="form-text">(Ex: ATIVO)</span>
                                                <select class="form-select" id="Editstatus" name="edit_status" aria-label="Default select example">
                                                    <option>Selecione</option>
                                                    <option value="ATIVO">ATIVO</option>
                                                    <option value="INATIVO">INATIVO</option>
                                                </select>
                                            </div>      
                                            <div class=" mb-3 col-md-6 col-xl-4">
                                                <label for="txtEditCPF" CPF class="form-label">CPF</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="text" name="edit_cpf" class="form-control" id="txtEditCPF" maxlength="14" value="" oninput="maskCPF(this)">
                                            </div>
                                            <div class="mb-3 col-md-6 col-xl-4">
                                                <label for="txtEditDataNascimento" class="form-label">Data de Nascimento</label>
                                                <input type="date" class="form-control" name="edit_data_nasc" id="txtEditDataNascimento" max="2999-12-31">
                                            </div>
                                            <div class="mb-3 col-md-6 col-xl-4">
                                                <label for="edit_selectacesso" class="form-label">Nível de Acesso</label>
                                                <select class="form-select" id="edit_selectacesso" name="edit_acesso" aria-label="Default select example">
                                                    <option>Selecione</option>
                                                    <?php

                                                        ini_set('default_charset', 'utf-8');
                                                        require_once '../class/conexao2.php';
                                                        global $pdo;
                                                        
                                                        $query_acesso_modal = "SELECT idAcesso, Nivel FROM acesso order by idAcesso";
                                                        $result_acesso_modal = $pdo->prepare($query_acesso_modal);
                                                        $result_acesso_modal->execute();

                                                        while ($dado_acesso_modal = $result_acesso_modal->fetch(PDO::FETCH_ASSOC)) 
                                                            {
                                                                ?>
                                                                
                                                                    <option value="<?php echo $dado_acesso_modal['idAcesso'] ?>"><?php echo $dado_acesso_modal['Nivel'] ?></option>
                                                                                        
                                                                <?php
                                                            }
                                                        ?>
                                                </select>
                                            </div>
                                        </fieldset>
                                        <fieldset class="row">
                                            <legend>Contatos</legend>
                                            <div class="mb-3 col-md-6">
                                                <label for="txtEditEmail" class="form-label">E-mail</label>
                                                <input type="email" name="edit_email" class="form-control" id="txtEditEmail" >
                                            </div> 
                                            <div class="mb-3 col-md-6">
                                                <label for="Editcargo" class="form-label">Cargo</label>
                                                <span class="form-text">(Ex: Diretor)</span>
                                                <select class="form-select" id="Editcargo" name="edit_cargo">
                                                    <option>Selecione</option>
                                                    <?php

                                                        ini_set('default_charset', 'utf-8');
                                                        require_once '../class/conexao2.php';
                                                        global $pdo;
                                                        
                                                        $query_cargo = "SELECT idCargo, Desc_cargo FROM cargo order by idCargo";
                                                        $result_cargo = $pdo->prepare($query_cargo);
                                                        $result_cargo->execute();

                                                        while ($dado_cargo = $result_cargo->fetch(PDO::FETCH_ASSOC)) 
                                                            {
                                                                ?>
                                                                
                                                                    <option value="<?php echo $dado_cargo['idCargo'] ?>"><?php echo $dado_cargo['Desc_cargo'] ?></option>
                                                                                        
                                                                <?php
                                                            }
                                                        ?>
                                                </select>
                                            </div>                     
                                            <div class="mb-3 col-md-6">
                                                <label for="txtEditDDD" class="form-label">DDD</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="tel" class="form-control" id="txtEditDDD" name="edit_ddd" placeholder="61" maxlength="4" oninput="maskDDD(this)">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="txtEditTelefone" class="form-label">Telefone</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="tel" class="form-control"  id="txtEditTelefone" name="edit_telefone" maxlength="10" placeholder="999999999" oninput="maskPhone(this)" >
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <fieldset class="row">
                                            <legend>Endereço</legend>
                                            <div class="mb-3 col-md-6 col-lg-4">
                                                <label for="cep" class="form-label mascCEP">CEP</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="cep_modal" name="cep_modal" min="9" maxlength="9" onblur="pesquisacep_modal(this.value);" value="" >
                                                    <span class="input-group-text p-1">
                                                        <svg class="bi" width="24" height="24" fill="currentColor">
                                                            <use xlink:href="../icons/bi.svg#hourglass-split" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-md-8">
                                                <label for="bairro" class="form-label mascCEP">Logradouro</label>
                                                <input type="text" class="form-control" id="rua_modal" name="rua_modal" readonly>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="rua" class="form-label mascCEP">Bairro</label>
                                                <input type="text" class="form-control" id="bairro_modal" name="bairro_modal" readonly>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="cidade" class="form-label mascCEP">Cidade</label>
                                                <input type="text" class="form-control" id="cidade_modal" name="cidade_modal" readonly>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="uf" class="form-label mascCEP">Estado</label>
                                                <input type="text" class="form-control" id="uf_modal" name="uf_modal" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="txtEditComplemento" class="form-label">Complemento</label>
                                                <input type="text" name="edit_complemento" class="form-control" id="txtEditComplemento" >
                                            </div>
                                            <!--<div class="mb-3">
                                                <label for="txtEditReferencia" class="form-label">Ponto de Referência</label>
                                                <input type="text" class="form-control" name="edit_referencia" id="txtEditReferencia">
                                            </div>
                                            <div class="mb-3 col-md-6 col-lg-8 align-self-end">
                                                <textarea class="form-control text-muted bg-light" style="height:68px">Digite o CEP para buscar o endereço.</textarea>
                                            </div>-->
                                        </fieldset>
                                        <fieldset>
                                            <legend>Cadastrar Nova Senha</legend>
                                            <div class="mb-3">
                                                <label for="txtEditSenha" class="form-label">Senha</label>
                                                <input type="password" name="edit_senha" id="edit_senha" class="form-control" id="txtEditSenha" onkeyup="this.value = Trim(this.value )" />
                                                <span id="senha-status"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="txtEditConfSenha" class="form-label">Confirmação da Senha</label>
                                                <input type="password" name="edit_senhaC" id="edit_senhaC" class="form-control" id="txtEditConfSenha" onkeyup="this.value = Trim(this.value )" />
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" value="cadastrar" name="confirmacad" onclick="return validarSenha()" class="btn btn-success">Salvar Cadastro</button>            
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                                                
            <script type="text/javaScript">
                function Trim(str)
                {
                    return str.replace(/^\s+|\s+$/g,"");
                }
            </script>
            <script src="../js manual/Manter_funcionarios.js"></script>
            <script src="../js manual/custom_checkout.js"></script>
            <script src="../js manual/custom_data_nasc.js"></script>
            <script src="../js manual/custom_senha.js"></script>