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
                                <h3 class="fs-4 mb-3">Relação de clientes no sistema</h3>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_cliente" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Nome</th>
                                            <th>Sobrenome</th>
                                            <th>Data Nascimento</th>
                                            <th>Email</th>
                                            <th>CPF</th>
                                            <th>DDD</th>
                                            <th>Telefone</th>
                                            <th>CEP</th>
                                            <th>Bairro</th>
                                            <th>Logradouro</th>
                                            <th>Cidade</th>
                                            <th>Estado</th>
                                            <th>Complemento</th>
                                            <th>Referencia</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /#page-content-wrapper -->

            <!-- Modal Cadastrar 
            <div class="modal fade" id="cadTipoLogradouroModal" tabindex="-1" aria-labelledby="cadTipoLogradouroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadTipoLogradouroModalLabel">Cadastro Tipo Logradouro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <form method="POST" id="form-cad-tplogradouro">
                                <div class="mb-3">
                                    <label for="nome_tplogradouro" class="col-form-label">Tipo do Logradouro:</label>
                                    <input type="text" name="tplogradouro" class="form-control" id="nome_tplogradouro" placeholder="Insira o tipo de nome do logradouro"> 
                                </div>
                                <button type="submit" value="cadastrar" class="btn btn-outline-success btn1 ">Cadastrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>-->

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

            <!-- Modal Editar
            <div class="modal fade" id="editTipoLogradouroModal" tabindex="-1" aria-labelledby="editTipoLogradouroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTipoLogradouroModalLabel">Editar Tipo Logradouro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroEdit"></span>
                            <form method="POST" id="form-edit-tplogradouro">
                                <input type="hidden" name="idtplogradouro" id="editId"> 
                                <div class="mb-3">
                                    <label for="nome_tplogradouro" class="col-form-label">Tipo do Logradouro:</label>
                                    <input type="text" name="tplogradouro" class="form-control" id="editnome_tplogradouro" placeholder="Insira o tipo de nome do logradouro"> 
                                </div>
                                <button type="submit" value="Salvar" class="btn btn-outline-success btn1 ">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>-->
            <script src="../js manual/Manter_cliente.js"></script>