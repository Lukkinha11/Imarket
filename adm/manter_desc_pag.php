                        <?php
                            ob_start();
                            ini_set('default_charset','utf-8');
                            if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome'])) AND (!isset($_SESSION['acesso'])) OR ($_SESSION['status_acesso'] == 1))
                            {
                                header("Location: ../index.php");
                            }
                            elseif($_SESSION['acesso'] >= 2)
                            {
                                header("Location: ../index.php");
                                exit();
                            }
                        ?>
                        <div class="row my-5">
                            <div class="d-flex justify-content-between align-items-center pt-3 pb-2">
                                <h3 class="fs-4 mb-3">Cadastro Descrição do Pagamento</h3>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadDescPagModal">Cadastrar Descrição do Pagamento</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_desc_pag" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Forma de Pagamento</th>
                                            <th>Quantidade Parcelas</th>
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
            <div class="modal fade" id="cadDescPagModal" tabindex="-1" aria-labelledby="cadDescPagModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadDescPagModalLabel">Cadastro Descrição Pagamento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <form method="POST" id="form-cad-descpag">
                                <div class="mb-3">
                                    <label for="nome_formapag" class="col-form-label">Forma de Pagamento:</label>
                                    <input type="text" name="formapag" class="form-control" id="nome_formapag" placeholder="Insira a forma de pagamento"> 
                                </div>
                                <div class="mb-3">
                                    <label for="nome_quantparcelas" class="col-form-label">Quantidade Parcelas:</label>
                                    <input type="number" min="1" max="6" name="quantparcelas" class="form-control" id="nome_quantparcelas" placeholder="Insira a quantidade de parcelas"> 
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
            <div class="modal fade" id="editDecPagModal" tabindex="-1" aria-labelledby="editDecPagModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDecPagModalLabel">Editar Descrição de Pagamento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroEdit"></span>
                            <form method="POST" id="form-edit-descpag">
                                <input type="hidden" name="iddescpag" id="editId"> 
                                <div class="mb-3">
                                    <label for="editnome_formapag" class="col-form-label">Forma de Pagamento:</label>
                                    <input type="text" name="editformapag" class="form-control" id="editnome_formapag" placeholder="Insira a forma de pagamento"> 
                                </div>
                                <div class="mb-3">
                                    <label for="editnome_quantparcelas" class="col-form-label">Quantidade de Parcelas:</label>
                                    <input type="text" name="editquantparcelas" class="form-control" id="editnome_quantparcelas"  placeholder="Insira a quantidade de parcelas"> 
                                </div>
                                <button type="submit" value="Salvar" class="btn btn-outline-success btn1 ">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js manual/Manter_desc_pag.js"></script>