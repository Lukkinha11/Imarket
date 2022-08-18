                        <?php
                            ob_start();
                            ini_set('default_charset','utf-8');
                            if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome'])) AND (!isset($_SESSION['acesso'])) OR ($_SESSION['status_acesso'] == 1))
                            {
                                header("Location: ../index.php");
                                exit();
                            }
                            
                        ?>
                        <div class="row my-5">
                            <div class="d-flex justify-content-between align-items-center pt-3 pb-2">
                                <h3 class="fs-4 mb-3">Cadastro de Preços</h3>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadPrecoModal">Cadastrar Preço</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_preco" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Valor Unitário</th>
                                            <th>Valor de Comercialização</th>
                                            <th>Valor Promocional</th>
                                            <th>Status do Preço</th>
                                            <th>Produto</th>
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
            <div class="modal fade" id="cadPrecoModal" tabindex="-1" aria-labelledby="cadPrecoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadPrecoModalLabel">Cadastro de Preços</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <form method="POST" id="form-cad-preco" autocomplete="off">
                                <span>Os campos com * são obrigatórios</span>
                                <div class="mb-3">
                                    <label for="nome_prod" class="col-form-label">*Produto:</label>
                                    <input type="text" name="prod" class="form-control" id="nome_prod" placeholder="Pesquise o nome produto" onkeyup="carregar_produtos(this.value)">
                                    <input type="hidden" name="id_produto" class="form-control" id="id_produto">
                                    <span id="resultado_pesquisa"></span> 
                                </div>
                                <div class="mb-3">
                                    <label for="nome_valorunit" class="col-form-label">*Valor Unitário(de Compra) R$:</label>
                                    <input type="text" name="valorunit" class="form-control" id="nome_valorunit" placeholder="Insira o valor unitário do produto" onKeyPress="return(moeda(this,'.',',',event))"> 
                                </div>
                                <div class="mb-3">
                                    <label for="nome_valorcomercio" class="col-form-label">*Valor de Comercialização R$:</label>
                                    <input type="text" name="valorcomercio" class="form-control" id="nome_valorcomercio" placeholder="Insira o valor de comercialização do produto" onKeyPress="return(moeda(this,'.',',',event))"> 
                                </div>
                                <div class="mb-3">
                                    <label for="nome_valorpromo" class="col-form-label">*Valor Promocional R$:</label>
                                    <input type="text" name="valorpromo" class="form-control" id="nome_valorpromo" placeholder="Insira o valor promocional do produto" onKeyPress="return(moeda(this,'.',',',event))"> 
                                </div>
                                <div class="mb-3">
                                    <label for="nome_status" class="col-form-label">Status do Preço:</label>
                                    <input type="text" name="statuspreco" class="form-control" id="nome_status" placeholder="Insira o status do preço. Ex: Promocional"> 
                                </div>
                                <button type="submit" value="cadastrar" class="btn btn-outline-success btn1 ">Cadastrar</button>
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
            <div class="modal fade" id="editPrecoModal" tabindex="-1" aria-labelledby="editPrecoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPrecoModal">Editar de Preços</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <span id="msgAlertErroEdit"></span>
                                <form method="POST" id="form-edit-preco" autocomplete="off">
                                    <input type="hidden" name="idpreco" id="editId"> 
                                    <span>Os campos com * são obrigatórios</span>
                                    <div class="mb-3">
                                        <label for="nome_prod" class="col-form-label">*Produto:</label>
                                        <input type="text" name="edit_prod" class="form-control" id="edit_nome_prod" placeholder="Pesquise o nome produto" onkeyup="carregar_produtos(this.value)">
                                        <input type="hidden" name="id_prodedit" class="form-control" id="id_prodedit">
                                        <span id="resultado_pesquisa2"></span> 
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_valorunit" class="col-form-label">*Valor Unitário(de Compra) R$:</label>
                                        <input type="text" name="edit_valorunit" class="form-control" id="edit_valorunit" placeholder="Insira o valor unitário do produto" onKeyPress="return(moeda(this,'.',',',event))"> 
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_valorcomercio" class="col-form-label">*Valor de Comercialização R$:</label>
                                        <input type="text" name="edit_valorcomercio" class="form-control" id="edit_valorcomercio" placeholder="Insira o valor de comercialização do produto" onKeyPress="return(moeda(this,'.',',',event))"> 
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_valorpromo" class="col-form-label">*Valor Promocional R$:</label>
                                        <input type="text" name="edit_valorpromo" class="form-control" id="edit_valorpromo" placeholder="Insira o valor promocional do produto" onKeyPress="return(moeda(this,'.',',',event))"> 
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_status" class="col-form-label">Status do Preço:</label>
                                        <input type="text" name="edit_statuspreco" class="form-control" id="edit_status" placeholder="Insira o status do preço. Ex: Promocional"> 
                                    </div>
                                    <button type="submit" value="cadastrar" class="btn btn-outline-success btn1 ">Cadastrar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js manual/Manter_preco.js"></script>