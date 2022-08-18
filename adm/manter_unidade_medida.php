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
                                <h3 class="fs-4 mb-3">Cadastro Unidades de Medidas</h3>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadUnidadeMedidaModal">Cadastrar Unidade de Medida</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_unidade_medida" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Descrição</th>
                                            <th>Sigla</th>
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
            <div class="modal fade" id="cadUnidadeMedidaModal" tabindex="-1" aria-labelledby="cadUnidadeMedidaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadUnidadeMedidaModalLabel">Cadastro Unidade de Medida</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <form method="POST" id="form-cad-unmedida">
                                <div class="mb-3">
                                    <label for="nome_descmedida" class="col-form-label">Descrição da Unidade de Medida:</label>
                                    <input type="text" name="nome_descmedida" class="form-control" id="nome_descmedida" placeholder="Insira a descrição da unidade de medida ex: Kilo"> 
                                </div>
                                <div class="mb-3">
                                    <label for="nome_sigla" class="col-form-label">Sigla da Unidade de Medida:</label>
                                    <input type="text" name="nome_sigla" class="form-control" id="nome_sigla" placeholder="Insira a sigla da unidade de medida ex: Kg"> 
                                </div>
                                <button type="submit" value="cadastrar" class="btn btn-outline-success">Cadastrar</button>
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
            <div class="modal fade" id="editUnidadeMedidaModal" tabindex="-1" aria-labelledby="editUnidadeMedidaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUnidadeMedidaModalLabel">Editar Unidade de Medidas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroEdit"></span>
                            <form method="POST" id="form-edit-unmedida">
                                <input type="hidden" name="idunmedida" id="editId"> 
                                <div class="mb-3">
                                    <label for="edit_descmedida" class="col-form-label">Descrição da Unidade de Medida:</label>
                                    <input type="text" name="edit_descmedida" class="form-control" id="edit_descmedida" placeholder="Insira a descrição da unidade de medida ex: Kilo"> 
                                </div>
                                <div class="mb-3">
                                    <label for="edit_sigla" class="col-form-label">Sigla da Unidade de Medida:</label>
                                    <input type="text" name="edit_sigla" class="form-control" id="edit_sigla" placeholder="Insira a sigla da unidade de medida ex: Kg"> 
                                </div>
                                <button type="submit" value="Salvar" class="btn btn-outline-success">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js manual/Manter_unidade_medida.js"></script>