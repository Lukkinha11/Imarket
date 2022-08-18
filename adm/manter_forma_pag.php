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
                                <h3 class="fs-4 mb-3">Cadastro Forma de Pagamento</h3>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadFormaPagModal">Cadastrar Forma de Pagamento</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_forma_pag" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Descrição do Pagamento</th>
                                            <th>Forma de Pagamento</th>
                                            <th>Parcela</th>
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
            <div class="modal fade" id="cadFormaPagModal" tabindex="-1" aria-labelledby="cadFormaPagModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadFormaPagModalLabel">Cadastro Forma de Pagamento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <form method="POST" id="form-cad-formapag">
                                <div class="mb-3">
                                    <label for="nome_descpag" class="col-form-label">Descrição do Pagamento:</label>
                                    <input type="text" name="descpag" class="form-control" id="nome_descpag" placeholder="Insira a descrição do pagamento">
                                </div>
                                <div class="mb-3">
                                    <div class="col">
                                        <label for="nome_formapag" class="col-form-label">Quantidade de Parcelas:</label>
                                        <select id="nome_formapag" name="formapag" class="form-select">
                                            <option selected>Selecione</option>
                                            <?php

                                                ini_set('default_charset', 'utf-8');
                                                require_once '../class/conexao2.php';
                                                global $pdo;

                                                $query_pag = "SELECT idDesc_pag, Quant  FROM desc_pag order by idDesc_pag";
                                                $result_pag = $pdo->prepare($query_pag);
                                                $result_pag->execute();

                                                while ($data = $result_pag->fetch(PDO::FETCH_ASSOC)) 
                                                {
                                                    ?>
                                                    
                                                    <option value="<?php echo $data['idDesc_pag'] ?>"><?php echo $data['Quant'] ?>x</option>
                                                                            
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" value="cadastrar" class="btn btn-outline-success mt-3 ">Cadastrar</button>
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
            <div class="modal fade" id="editFormaPagModal" tabindex="-1" aria-labelledby="editFormaPagModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editFormaPagModalLabel">Editar Forma de Pagamento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroEdit"></span>
                            <form method="POST" id="form-edit-formapag">
                                <input type="hidden" name="idformapag" id="editId"> 
                                <div class="mb-3">
                                    <label for="edit_descpag" class="col-form-label">Descrição do Pagamento:</label>
                                    <input type="text" name="edit_descpag" class="form-control" id="edit_descpag" placeholder="Insira a descrição do pagamento">
                                    <!--<input type="text" id="edit_formapag"  name="edit_formapag">  -->
                                </div>
                                <div class="mb-3">
                                    <div class="col">
                                        <label for="edit_formapag" class="col-form-label">Quantidade de Parcelas:</label>
                                        <select id="edit_formapag" name="edit_formapag" class="form-select">
                                            <option selected>Selecione</option>
                                            <?php

                                                ini_set('default_charset', 'utf-8');
                                                require_once '../class/conexao2.php';
                                                global $pdo;

                                                $query_pag = "SELECT idDesc_pag, Quant  FROM desc_pag order by idDesc_pag";
                                                $result_pag = $pdo->prepare($query_pag);
                                                $result_pag->execute();

                                                while ($data = $result_pag->fetch(PDO::FETCH_ASSOC)) 
                                                {
                                                    ?>
                                                    
                                                    <option value="<?php echo $data['idDesc_pag'] ?>"><?php echo $data['Quant'] ?>x</option>
                                                                            
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" value="Salvar" class="btn btn-outline-success mt-3 ">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js manual/Manter_forma_pag.js"></script>