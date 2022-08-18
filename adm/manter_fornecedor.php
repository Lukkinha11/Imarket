                        <?php
                            ob_start();
                            ini_set('default_charset','utf-8');
                            if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome'])) AND (!isset($_SESSION['acesso'])) OR ($_SESSION['status_acesso'] == 1))
                            {
                                header("Location: ../index.php");
                                exit();
                            }
                            elseif($_SESSION['acesso'] == 3)
                            {
                                header("Location: ../verifica_senha.php");
                                exit();
                            }
                            //var_dump($_SESSION['status_acesso']);
                        ?>
                        <div class="row my-5">
                            <div class="d-flex justify-content-between align-items-center pt-3 pb-2">
                                <h3 class="fs-4 mb-3">Cadastro de Fornecedores</h3>
                                <button type="button" class="btn btn-success"  id="cadastrar" data-bs-toggle="modal" data-bs-target="#cadFornecedorModal">Cadastrar Fornecedor</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_fornecedor" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Nome Fantasia</th>
                                            <th>Razão Social</th>
                                            <th>CNPJ</th>
                                            <th>DDD</th>
                                            <th>Telefone</th>
                                            <th>Email</th>
                                            <th>CEP</th>
                                            <th>Logradouro</th>
                                            <th>Bairro</th>
                                            <th>Cidade</th>
                                            <th>Estado</th>
                                            <th>Complemento</th>
                                            <th>Referencia</th>
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
            <div class="modal fade" id="cadFornecedorModal" tabindex="-1" aria-labelledby="cadFornecedorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadFornecedorModalLabel">Cadastro de Fornecedores</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <hr>
                            <form method="POST" id="form-cad-fornecedor" autocomplete="off">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <fieldset class="row gx-3">
                                            <legend>Dados do Fornecedor</legend>
                                            <div class="mb-3">
                                                <label for="txtNomeFantasia" class="form-label">Nome Fantasia</label>
                                                <input type="text" name="nome_fantasia" class="form-control" id="txtNomeFantasia"  autofocus>
                                            </div>
                                            <div class="mb-3">
                                                <label for="txtRazaoSocial" class="form-label">Razão Social</label>
                                                <input type="text" name="razao_social" class="form-control" id="txtRazaoSocial" >
                                            </div>
                                            <div class="mb-3">
                                                <label for="txtCNPJ" CPF class="form-label">CNPJ</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="text" name="cnpj" class="form-control" id="txtCNPJ" maxlength="18" value="" oninput="maskCPF(this)" >
                                            </div>
                                        </fieldset>
                                        <fieldset class="row">
                                            <legend>Contatos do Fornecedor</legend>
                                            <div class="mb-3 col-md-6">
                                                <label for="txtEmail" class="form-label">E-mail</label>
                                                <input type="email" name="email" class="form-control" id="txtEmail" >
                                            </div>                     
                                            <div class="mb-3 col-md-6">
                                                <label for="txtDDD" class="form-label">DDD</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="tel" class="form-control" id="txtDDD" name="ddd" placeholder="61" maxlength="4" oninput="maskDDD(this)">
                                            </div>
                                            <div class="mb-3">
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
                                            <div class="mb-3">
                                                <label for="txtReferencia" class="form-label">Ponto de Referência</label>
                                                <input type="text" class="form-control" name="referencia" id="txtReferencia">
                                            </div>
                                            <!--<div class="mb-3 col-md-6 col-lg-8 align-self-end">
                                                <textarea class="form-control text-muted bg-light" style="height:68px">Digite o CEP para buscar o endereço.</textarea>
                                            </div>-->
                                        </fieldset>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" value="cadastrar" name="confirmacad" class="btn btn-success">Criar Cadastro</button>            
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
            <div class="modal fade" id="editFornecedorModal" tabindex="-1" aria-labelledby="editFornecedorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="eeditFornecedorModalLabel">Editar Fornecedor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroEdit"></span>
                            <form method="POST" id="form-edit-fornecedor">
                                <input type="hidden" name="idfornecedor" id="editId"> 
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <fieldset class="row gx-3">
                                            <legend>Dados do Fornecedor</legend>
                                            <div class="mb-3">
                                                <label for="edit_NomeFantasia" class="form-label">Nome Fantasia</label>
                                                <input type="text" name="edit_nome_fantasia" class="form-control" id="edit_NomeFantasia">
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_RazaoSocial" class="form-label">Razão Social</label>
                                                <input type="text" name="edit_razao_social" class="form-control" id="edit_RazaoSocial" >
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_CNPJ" CPF class="form-label">CNPJ</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="text" name="edit_cnpj" class="form-control" id="edit_CNPJ" maxlength="18" value="" oninput="maskCPF(this)" >
                                            </div>
                                        </fieldset>
                                        <fieldset class="row">
                                            <legend>Contatos do Fornecedor</legend>
                                            <div class="mb-3 col-md-6">
                                                <label for="edit_Email" class="form-label">E-mail</label>
                                                <input type="email" name="edit_email" class="form-control" id="edit_Email" >
                                            </div>                     
                                            <div class="mb-3 col-md-6">
                                                <label for="edit_DDD" class="form-label">DDD</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="tel" class="form-control" id="edit_DDD" name="edit_ddd" placeholder="61" maxlength="4" oninput="maskDDD(this)">
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_Telefone" class="form-label">Telefone</label>
                                                <span class="form-text">(somente números)</span>
                                                <input type="tel" class="form-control"  id="edit_Telefone" name="edit_telefone" maxlength="10" placeholder="999999999" oninput="maskPhone(this)" >
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
                                                <input type="text" class="form-control" id="rua_modal" name="rua_modal" >
                                            </div>
                                            <div class="mb-3 col-md-12">
                                                <label for="rua" class="form-label mascCEP">Bairro</label>
                                                <input type="text" class="form-control" id="bairro_modal" name="bairro_modal" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="cidade" class="form-label mascCEP">Cidade</label>
                                                <input type="text" class="form-control" id="cidade_modal" name="cidade_modal" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="uf" class="form-label mascCEP">Estado</label>
                                                <input type="text" class="form-control" id="uf_modal" name="uf_modal" >
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_Complemento" class="form-label">Complemento</label>
                                                <input type="text" name="edit_complemento" class="form-control" id="edit_Complemento" >
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_Referencia" class="form-label">Ponto de Referência</label>
                                                <input type="text" class="form-control" name="edit_referencia" id="edit_Referencia">
                                            </div>
                                            <!--<div class="mb-3 col-md-6 col-lg-8 align-self-end">
                                                <textarea class="form-control text-muted bg-light" style="height:68px">Digite o CEP para buscar o endereço.</textarea>
                                            </div>-->
                                        </fieldset>
                                    </div>
                                </div>
                                <hr>
                                <button type="submit" value="Salvar" class="btn btn-outline-success">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js manual/Manter_fornecedor.js"></script>
            <script src="../js manual/custom_checkout.js"></script>