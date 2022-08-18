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
                                <h3 class="fs-4 mb-3">Cadastro de Produtos</h3>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadProdutoModal">Cadastrar Produto</button>
                            </div>
                            <span id="msgAlert"></span>
                            <div class="col">
                                <table id="listar_produto" class="table table-hover table-light table-striped display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <br>
                                            <th>#</th>
                                            <th>Nome do Produto</th>
                                            <th>Descrição do Produto</th>
                                            <th>KG/UN</th>
                                            <th>Imagem</th>
                                            <th>Código de Barras</th>
                                            <th>Categoria</th>
                                            <th>Marca</th>
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
            <div class="modal fade" id="cadProdutoModal" tabindex="-1" aria-labelledby="cadProdutoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadProdutoModalLabel">Cadastro de Produtos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroCad"></span>
                            <form method="POST" id="form-cad-produto" enctype="multipart/form-data" autocomplete="off">
                                <div class="row">
                                    <div class="col">
                                        <label for="nome_produto" class="col-form-label">Produto:</label>
                                        <input type="text" name="produto" class="form-control" id="nome_produto" placeholder="Insira o nome do produto"> 
                                    </div>
                                    <div class="col">
                                        <label for="und_medida" class="col-form-label">Unidade de Medida:</label>
                                        <select id="und_medida" name="und_medida" class="form-select">
                                            <option selected>Selecione</option>
                                            <?php

                                                ini_set('default_charset', 'utf-8');
                                                require_once '../class/conexao2.php';
                                                global $pdo;

                                                $query_und = "SELECT idUnidade_medidas, Desc_medida FROM unidade_medidas order by idUnidade_medidas";
                                                $result_und = $pdo->prepare($query_und);
                                                $result_und->execute();

                                                while ($data = $result_und->fetch(PDO::FETCH_ASSOC)) 
                                                {
                                                    ?>
                                                    
                                                    <option value="<?php echo $data['idUnidade_medidas'] ?>"><?php echo $data['Desc_medida'] ?></option>
                                                                            
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="nome_cdbarras" class="col-form-label">Código de Barras:</label>
                                        <input type="text" name="codigo_barras" maxlength="13" class="form-control" id="nome_cdbarras" placeholder="Insira o código de barras de 13 digítos"> 
                                    </div>
                                </div>
                                <div class="mb-3 mt-3">
                                    <label for="nome_descprod" class="col-form-label">Descrição do Produto:</label>
                                    <input type="text" name="descricao_prod" class="form-control" id="nome_descprod" placeholder="Insira a descrição do produto"> 
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="cat_prod" class="col-form-label">Categoria</label>
                                        <select id="cat_prod" name="categoria" class="form-select">
                                            <option selected>Selecione</option>
                                            <?php

                                                ini_set('default_charset', 'utf-8');
                                                require_once '../class/conexao2.php';
                                                global $pdo;

                                                $query_cat = "SELECT idCategoria, Categoria FROM categoria_prod order by idCategoria";
                                                $result_cat = $pdo->prepare($query_cat);
                                                $result_cat->execute();

                                                while ($data = $result_cat->fetch(PDO::FETCH_ASSOC)) 
                                                {
                                                    ?>
                                                    
                                                    <option value="<?php echo $data['idCategoria'] ?>"><?php echo $data['Categoria'] ?></option>
                                                                            
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="nome_marcaprod" class="col-form-label">Marca do Produto:</label>
                                        <input type="text" onkeyup="carregar_marca(this.value)" name="marca_produto" class="form-control" id="nome_marcaprod" placeholder="Pesquise a marca do produto">
                                        <input type="hidden" name="id_marca" class="form-control" id="id_marca">
                                        <span id="resultado_pesquisa"></span> 
                                    </div>        
                                </div>
                                <div class="mb-3 mt-3">
                                    <label for="image" class="form-label">Escolha uma imagem para o produto</label>
                                    <input class="form-control" name="image" type="file" id="image">
                                </div>
                                <button type="submit" value="cadastrar" class="btn btn-outline-success btn1 mt-3">Cadastrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Vizualizar -->
            <div class="modal fade" id="vizuProdutoModal" tabindex="-1" aria-labelledby="vizuProdutoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="vizuProdutoModalLabel">Detalhes do Produto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <dl class="row">
                                <dt class="col-sm-3">ID:</dt>
                                <dd class="col-sm-9"><span id="idprod"></span></dd>

                                <dt class="col-sm-3">Nome do Produto:</dt>
                                <dd class="col-sm-9"><span id="nomeprod"></span></dd>

                                <dt class="col-sm-3">Descrição do Produto:</dt>
                                <dd class="col-sm-9"><span id="unmedida"></span></dd>

                                <dt class="col-sm-3">Unidade de Medida:</dt>
                                <dd class="col-sm-9"><span id="descprod"></span></dd>
                            
                                <dt class="col-sm-3">Código de Barras:</dt>
                                <dd class="col-sm-9"><span id="cdprod"></span></dd>

                                <dt class="col-sm-3">Categoria:</dt>
                                <dd class="col-sm-9"><span id="catprod"></span></dd>

                                <dt class="col-sm-3">Marca:</dt>
                                <dd class="col-sm-9"><span id="marcaprod"></span></dd>

                                <dt class="col-sm-3">Imagem:</dt>
                                <div class="card col-sm-9 " style="width: 18rem;">
                                    <img id="imgprod" class="card-img-top">
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Editar -->
            <div class="modal fade" id="editProdutoModal" tabindex="-1" aria-labelledby="editProdutoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProdutoModalLabel">Editar Produto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="msgAlertErroEdit"></span>
                            <form method="POST" action="" id="form-edit-produto" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" name="idproduto" id="editId"> 
                                <div class="row">
                                    <div class="col">
                                        <label for="editnome_produto" class="col-form-label">Produto:</label>
                                        <input type="text" name="editnome_produto" class="form-control" id="editnome_produto" placeholder="Insira o nome do produto"> 
                                    </div>
                                    <div class="col">
                                        <label for="edit_und_medida" class="col-form-label">Unidade de Medida:</label>
                                        <select id="edit_und_medida" name="edit_und_medida" class="form-select">
                                            <option selected>Selecione</option>
                                            <?php

                                                ini_set('default_charset', 'utf-8');
                                                require_once '../class/conexao2.php';
                                                global $pdo;

                                                $query_und = "SELECT idUnidade_medidas, Desc_medida FROM unidade_medidas order by idUnidade_medidas";
                                                $result_und = $pdo->prepare($query_und);
                                                $result_und->execute();

                                                while ($data = $result_und->fetch(PDO::FETCH_ASSOC)) 
                                                {
                                                    ?>
                                                    
                                                    <option value="<?php echo $data['idUnidade_medidas'] ?>"><?php echo $data['Desc_medida'] ?></option>
                                                                            
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="editnome_cdbarras" class="col-form-label">Código de Barras:</label>
                                        <input type="text" name="editnome_cdbarras" max="13" min="13" class="form-control" id="editnome_cdbarras" placeholder="Insira o código de barras de 13 digítos"> 
                                    </div>
                                </div>
                                <div class="mb-3 mt-3">
                                    <label for="editnome_descprod" class="col-form-label">Descrição do Produto:</label>
                                    <input type="text" name="editnome_descprod" class="form-control" id="editnome_descprod" placeholder="Insira a descrição do produto">
                                    <!--<input type="text" id="edit_categoria"  name="editcategoria"> -->
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="edit_categoria" class="col-form-label">Categoria</label>
                                        <select id="edit_categoria" name="editcategoria" class="form-select">  
                                            <option>Selecione</option>
                                            <?php

                                                ini_set('default_charset', 'utf-8');
                                                require_once '../class/conexao2.php';
                                                global $pdo;

                                                $query_cat = "SELECT idCategoria, Categoria FROM categoria_prod order by idCategoria";
                                                $result_cat = $pdo->prepare($query_cat);
                                                $result_cat->execute();

                                                while ($data = $result_cat->fetch(PDO::FETCH_ASSOC)) 
                                                {   
                                                    ?>
                                                    
                                                    <option value="<?php echo $data['idCategoria'] ?>"><?php echo $data['Categoria'] ?></option>
                                                                            
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="editnome_marcaprod" class="col-form-label">Marca do Produto:</label>
                                        <input type="text" onkeyup="carregar_marca(this.value)" name="editnome_marcaprod" class="form-control" id="editnome_marcaprod" placeholder="Pesquise a marca do produto">
                                        <input type="hidden" name="id_marcaedit" class="form-control" id="id_marcaedit">
                                        <span id="resultado_pesquisa2"></span> 
                                    </div>        
                                </div>
                                <div class="mb-3 mt-3">
                                    <label for="editimage" class="form-label">Escolha uma imagem para o produto</label>
                                    <input class="form-control" name="image" type="file" id="editimage">
                                    <input type="hidden" name="nome_img" class="form-control" id="nome_img">
                                </div>
                                <button type="submit" value="Salvar" class="btn btn-outline-success btn1 mt-3">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js manual/Manter_produto.js"></script>
            