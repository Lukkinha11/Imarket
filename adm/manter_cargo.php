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
                                <h3 class="fs-4 mb-3">Cadastro de Cargos</h3>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadCargoModal">Cadastrar Cargo</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_cargo" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Descrição do Cargo</th>
                                            <th>Salário</th>
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
            <div class="modal fade" id="cadCargoModal" tabindex="-1" aria-labelledby="cadCargoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadCargoModalLabel">Cadastro de Cargo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <form method="POST" id="form-cad-cargo">
                                <div class="mb-3">
                                    <label for="nome_cargo" class="col-form-label">Descrição do Cargo:</label>
                                    <input type="text" name="cargo" class="form-control" id="nome_cargo" placeholder="Insira o a descrição do cargo"> 
                                </div>
                                <div class="mb-3">
                                    <label for="nome_salario" class="col-form-label">Salário(R$):</label>
                                    <input type="text" name="salario" class="form-control" id="nome_salario" placeholder="Insira o salário" onKeyPress="return(moeda(this,'.',',',event))"> 
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
            <div class="modal fade" id="editCargoModal" tabindex="-1" aria-labelledby="eeditCargoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCargoModalLabel">Editar Cargo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroEdit"></span>
                            <form method="POST" id="form-edit-cargo">
                                <input type="hidden" name="idcargo" id="editId"> 
                                <div class="mb-3">
                                    <label for="editnome_cargo" class="col-form-label">Descrição do Cargo:</label>
                                    <input type="text" name="editcargo" class="form-control" id="editnome_cargo" placeholder="Insira o a descrição do cargo"> 
                                </div>
                                <div class="mb-3">
                                    <label for="editnome_salario" class="col-form-label">Salário(R$):</label>
                                    <input type="text" name="editsalario" class="form-control" id="editnome_salario" placeholder="Insira o salário" onKeyPress="return(moeda(this,'.',',',event))"> 
                                </div>
                                <button type="submit" value="Salvar" class="btn btn-outline-success btn1 ">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js manual/Manter_cargo.js"></script>