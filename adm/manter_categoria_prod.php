                        <?php
                            ob_start();
                            ini_set('default_charset','utf-8');
                            if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome'])) AND (!isset($_SESSION['acesso'])) OR ($_SESSION['status_acesso'] == 1))
                            {
                                header("Location: ../index.php");
                            }
                            elseif($_SESSION['acesso'] == 3)
                            {
                                header("Location: ../index.php");
                                exit();
                            }
                        ?>
                        <div class="row my-5">
                            <div class="d-flex justify-content-between align-items-center pt-3 pb-2">
                                <h3 class="fs-4 mb-3">Cadastro Categoria Produto</h3>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadCategoriaPordutoModal">Cadastrar Categoria</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_categoria_prod" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Categoria</th>
                                            <th>Categoria Diretório</th>
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
            <div class="modal fade" id="cadCategoriaPordutoModal" tabindex="-1" aria-labelledby="cadCategoriaPordutoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadCategoriaPordutoModalLabel">Cadastro Categoria Produto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <form method="POST" id="form-cad-catprod">
                                <div class="mb-3">
                                    <label for="nome_catprod" class="col-form-label">Categoria:</label>
                                    <input type="text" name="catprod" class="form-control" id="nome_catprod" placeholder="Insira a categoria a ser cadastrada"> 
                                </div>
                                <div class="mb-3">
                                    <label for="nome_catdir" class="col-form-label">Categoria Diretório:</label>
                                    <input type="text" name="catdir" class="form-control" id="nome_catdir" placeholder="Insira o diretório sem espaço"> 
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
            <div class="modal fade" id="editCategoriaPordutoModal" tabindex="-1" aria-labelledby="editCategoriaPordutoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoriaPordutoModalLabel">Editar Categoria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroEdit"></span>
                            <form method="POST" id="form-edit-catprod">
                                <input type="hidden" name="idcatprod" id="editId"> 
                                <div class="mb-3">
                                    <label for="nome_catprod" class="col-form-label">Categoria:</label>
                                    <input type="text" name="catprod" class="form-control" id="edit_catprod" placeholder="Insira a categoria a ser cadastrada"> 
                                </div>
                                <div class="mb-3">
                                    <label for="nome_catdir" class="col-form-label">Categoria Diretório:</label>
                                    <input type="text" name="catdir" class="form-control" id="edit_catdir" placeholder="Insira o diretório sem espaço"> 
                                </div>
                                <button type="submit" value="Salvar" class="btn btn-outline-success btn1 ">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js manual/Manter_categoria_prod.js"></script>