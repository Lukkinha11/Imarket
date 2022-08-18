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
                                <h3 class="fs-4 mb-3">Cadastro de Nota Fiscal</h3>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadEstoqueModal">Cadastrar Nota Fiscal</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_estoque" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Nº NF</th>
                                            <th>Serie NF</th>
                                            <th>Valor NF</th>
                                            <th>Data da Compra</th>
                                            <th>Descrição</th>
                                            <th>Fornecedor</th>
                                            <th>Forma de Pagamento</th>
                                            <th>Parcela(s)</th>
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
            <div class="modal fade" id="cadEstoqueModal" tabindex="-1" aria-labelledby="cadEstoqueModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadEstoqueModalLabel">Cadastro de Nota Fiscal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <!--<span type="hidden" id="result"></span> -->
                            <form id="form-cad-estoque" autocomplete="off">
                                <div class="row">
                                    <div class="col">
                                        <label for="num_nf" class="col-form-label">Número da Nota Fiscal:</label>
                                        <input type="text" name="num_nf" class="form-control" id="num_nf" onkeypress='return filtroTeclas(event)' placeholder="Insira o número da NF"> 
                                    </div>
                                    <div class="col">
                                        <label for="serie_nf" class="col-form-label">Serie da Nota Fiscal:</label>
                                        <input type="text" name="serie_nf" class="form-control" id="serie_nf" maxlength="10" onkeypress='return filtroTeclas(event)' placeholder="Insira a serie da NF"> 
                                    </div>
                                    <div class="col">
                                        <label for="valor_nf" class="col-form-label">Valor da Nota Fiscal R$:</label>
                                        <input type="text" name="valor_nf" class="form-control" id="valor_nf" onKeyPress="return(moeda(this,'.',',',event))" placeholder="Insira o valor da NF"> 
                                    </div>
                                    <div class="col">
                                        <label for="data_nf" class="col-form-label">Data da Compra:</label>
                                        <input type="date" class="form-control" name="data_nf" id="data_nf" max="2999-12-31" >
                                    </div>
                                </div>
                                <div class="mb-3 mt-3">
                                    <label for="desc_nf" class="col-form-label">Descrição da Nota Fiscal:</label>
                                    <input type="text" name="desc_nf" class="form-control" id="desc_nf"  placeholder="Informe a descrição da NF"> 
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="InputProduto" class="col-form-label">Produto:</label>
                                        <input type="text" name="produto" class="form-control" id="InputProduto" placeholder="Pesquise o nome do produto" onkeyup="carregar_produtos(this.value)">
                                        <input type="hidden" name="id_produto" class="form-control" id="id_produto">
                                        <span id="resultado_pesquisa"></span>  
                                    </div>
                                    <div class="col">
                                        <label for="InputQtd" class="col-form-label">Quantidade da Compra:</label>
                                        <input type="text" name="qtd_compra" class="form-control only-number" data-accept-comma="1" id="InputQtd" placeholder="Informe a quantidade de comprada">
                                    </div>
                                    <div class="col">
                                        <label for="InputValor" class="col-form-label">Valor Unitário do Produto R$:</label>
                                        <input type="text" name="valor_unit" class="form-control" id="InputValor" onKeyPress="return(moeda(this,'.',',',event))" placeholder="Insira o valor valor_unit da NF"> 
                                    </div>
                                    <div class="col">
                                        <button id="btn-adicionar" type="button" value="adicionar" style="margin-top: 37px;" class="btn btn-outline-primary">Adicionar</button>
                                    </div>
                                    
                                </div>
                                <span id="notice" class="mt-5"></span>
                                <table  id="tbLista" class="table table-striped mt-4 nota-lista">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Produto</th>
                                            <th scope="col">Quantidade</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody id="tabela-nota">
                
                                    </tbody>

                                </table>

                                <div class="row mt-3">
                                    
                                    <div class="col">
                                        <label for="Nome_fornecedor" class="col-form-label">Fornecedor:</label>
                                        <input type="text" name="fornecedor" class="form-control" id="Nome_fornecedor" placeholder="Pesquise o nome do fornecedor" onkeyup="carregar_fornecedor(this.value)">
                                        <input type="hidden" name="id_fornecedor" class="form-control" id="id_fornecedor">
                                        <span id="resultado_fornecedor"></span>  
                                    </div>
                                    <div class="col">
                                        <label for="pagamento" class="col-form-label">Forma de Pagamento:</label>
                                        <select id="pagamento" name="formapag" class="form-select">
                                            <option selected>Selecione</option>
                                            <?php

                                                ini_set('default_charset', 'utf-8');
                                                require_once '../class/conexao2.php';
                                                global $pdo;

                                                $query_pag = "SELECT idForma_pag, Descricao FROM forma_pagamento";
                                                $result_pag = $pdo->prepare($query_pag);
                                                $result_pag->execute();

                                                while($dados = $result_pag->fetch(PDO::FETCH_ASSOC))
                                                {
                                                    extract($dados);

                                                    ?>
                                                        
                                                        <option value="<?php echo $idForma_pag ?>"><?php echo $Descricao ?></option>
                                                                                
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="parcelas" class="col-form-label">Parcelamento:</label>
                                        <select id="parcelas" name="descpag" class="form-select">
                                            <option selected>Selecione</option>
                                            <?php

                                                ini_set('default_charset', 'utf-8');
                                                require_once '../class/conexao2.php';
                                                global $pdo;

                                                $query_formapag = "SELECT idDesc_pag, Forma_pag, Quant FROM desc_pag";
                                                $result_pag = $pdo->prepare($query_formapag);
                                                $result_pag->execute();

                                                while($dados = $result_pag->fetch(PDO::FETCH_ASSOC))
                                                {
                                                    extract($dados);

                                                    ?>
                                                        
                                                        <option value="<?php echo $idDesc_pag ?>"><?php echo $Quant . "x" ?></option>
                                                                                
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" id="cadastrar" value="cadastrar" class="btn btn-success mt-3">Cadastrar Nota Fiscal</button>
                                
                                <div id="status"></div>
                                <div id="result"></div>
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
            </div>
            <script src="../js manual/jquery_ajax.js"></script>
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" crossorigin="anonymous"></script>-->
           
            <script src="../js manual/Manter_estoque.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" crossorigin="anonymous"></script>
            <script type="text/javascript">
                var selectServicos = document.querySelector('#parcelas')

                var selectProfissional = document.querySelector('#pagamento')

                selectProfissional.onchange = function(evento){ // função que vai ser executada cada vez que o valor do select for mudado, passando o evento como parametro
                var id_filtro = evento.target.value // pega o valor do evento, que vai ser o do select profissional

                fetch('quant_parcelas_adm_post.php?id_filtro='+id_filtro) // faz a requisição para a url, passando o filtro como parâmetro
                .then(response => response.text()) // avisa que a proxima resposta da promise deve ser um texto
                .then(options => selectServicos.innerHTML = options)  // exibe os valores dentro do seu select, que foram retornados do seu backend
                }
            </script>
