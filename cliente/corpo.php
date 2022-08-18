 <header class="container">
     <div id="myCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel">
         <div class="carousel-indicators">
             <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
             <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
             <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
         </div>
         <div class="carousel-inner">
             <div class="carousel-item active text-center" data-interval="1000">
                 <img src="../img/slides_rotativos/slide01.jpeg" alt="" class="img-fluid d-none d-md-block">
                 <img src="../img/slides_rotativos/slide01small.jpeg" alt="" class="img-fluid d-block d-md-none">
             </div>
             <div class="carousel-item  text-center" data-interval="3000">
                 <img src="../img/slides_rotativos/slide01.jpeg" alt="" class="img-fluid d-none d-md-block">
                 <img src="../img/slides_rotativos/slide01small.jpeg" alt="" class="img-fluid d-block d-md-none">
             </div>
             <div class="carousel-item  text-center" data-interval="3000">
                 <img src="../img/slides_rotativos/slide01.jpeg" alt="" class="img-fluid d-none d-md-block">
                 <img src="../img/slides_rotativos/slide01small.jpeg" alt="" class="img-fluid d-block d-md-none">
             </div>
         </div>
         <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
             <span class="carousel-control-prev-icon" aria-hidden="true"></span>
             <span class="visually-hidden">Previous</span>
         </button>
         <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
             <span class="carousel-control-next-icon" aria-hidden="true"></span>
             <span class="visually-hidden">Next</span>
         </button>
     </div>
     <hr class="mt-3" />
 </header>

 <main class="flex-fill">
     <div class="container">
         <div class="row">
             <div class="col-12 col-md-5">
                 <form action="" method="POST" id="form-pesquisa" class="justify-content-center justify-content-md-start mb-3 mb-md-0">
                     <div class="input-group input-group-sm">
                         <input type="text" name="pesquisa" id="pesquisa" class="form-control" placeholder="Digite aqui o que procura">
                         <button type="submit" name="enviar" value="pesquisar" class="btn btn-primary">
                             Buscar
                         </button>
                     </div>
                 </form>
                 <ul class="resultado">
                        
                </ul>
             </div>
             
             <div class="col-12 col-md-7">
                 <div class="d-flex flex-row-reverse justify-content-center justify-content-md-start">
                     <form class="d-inline-block">
                         <select class="form-select form-select-sm">
                             <option value="">Ordenar pelo nome</option>
                             <option value="">Ordenar pelo maior preço</option>
                             <option value="">Ordenar pelo menor preço</option>
                         </select>
                     </form>
                     <nav class="d-inline-block me-3">
                         <ul class="pagination pagination-sm my-0">
                             <li class="page-item">
                                 <button class="page-link">1</button>
                             </li>
                             <li class="page-item">
                                 <button class="page-link">2</button>
                             </li>
                             <li class="page-item disabled">
                                 <button class="page-link">3</button>
                             </li>
                             <li class="page-item">
                                 <button class="page-link">4</button>
                             </li>
                             <li class="page-item">
                                 <button class="page-link">5</button>
                             </li>
                             <li class="page-item">
                                 <button class="page-link">6</button>
                             </li>
                         </ul>
                     </nav>
                 </div>
             </div>
         </div>
         <hr class="mt-3">
         <h2 class="display-4 mt-5 mb-5">Produtos<h2>

                 <?php

                    ini_set('default_charset', 'utf-8');
                    require_once '../class/conexao2.php';
                    global $pdo;

                    if (!isset($_GET['idcat'])) {
                        $query_prod = "SELECT id_Produto, Nome_prod, Desc_prod, Image,  Valor_prod, Valor_novo FROM produto
                            INNER JOIN preco
                            ON produto.Preco_idPreco = preco.idPreco
                            ORDER BY id_produto";
                    } else {
                        $query_prod = "SELECT id_Produto, Nome_prod, Desc_prod, Image,  Valor_prod, Valor_novo FROM produto
                            INNER JOIN preco
                            ON produto.Preco_idPreco = preco.idPreco
							and Categoria_Prod_idCategoria=" . $_GET['idcat'] . "
                            ORDER BY id_produto";
                    }
                    $result_prod = $pdo->prepare($query_prod);
                    $result_prod->execute();

                    ?>
                    

                 <div class="row g-3">

                     <?php
                            while ($row_prod = $result_prod->fetch(PDO::FETCH_ASSOC)) {

                                extract($row_prod);
                                //$row_prod = array_unique($Image);
                                /*echo "<img src='../img_prod/$id_Produto - $Nome_prod/$Image'><br>";
                                    echo "ID: $id_Produto <br>";
                                    echo "Nome: $Nome_prod <br>";
                                    echo "Preço: R$ ". number_format($Valor_prod, 2, ",", ".")."<br>";
                                    echo "<hr>";
                                    */
                                ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                        <div class="card text-center bg-light">
                                            <img src='<?php echo "../img_prod/$id_Produto - $Nome_prod/$Image"; ?>' class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo "$Nome_prod <br>"; ?></h5>
                                                <div class="card-text"> R$ <?php echo number_format($Valor_novo, 2, ",", "."); ?></div>
                                            </div>
                                            <div class="card-footer">
                                                <form action="" class="d-block">
                                                    <a href="" class="btn btn-danger">Adicionar ao Carrinho <i class="bi-cart"></i></a>
                                                    <a href="?pg=det&id=<?php echo $id_Produto ?>" class="btn btn-primary">Ver Detalhes <i class="bi-search"></i></a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        ?>
                 </div>
                 <hr class="mt-3">
                 <div class="row pb-3">
                     <div class="col-12">
                         <div class="d-flex flex-row-reverse justify-content-center justify-content-md-center">
                             <nav class="d-inline-block ">
                                 <ul class="pagination pagination-sm my-0">
                                     <li class="page-item">
                                         <button class="page-link">1</button>
                                     </li>
                                     <li class="page-item">
                                         <button class="page-link">2</button>
                                     </li>
                                     <li class="page-item disabled">
                                         <button class="page-link">3</button>
                                     </li>
                                     <li class="page-item">
                                         <button class="page-link">4</button>
                                     </li>
                                     <li class="page-item">
                                         <button class="page-link">5</button>
                                     </li>
                                     <li class="page-item">
                                         <button class="page-link">6</button>
                                     </li>
                                 </ul>
                             </nav>
                         </div>
                     </div>
                 </div>
     </div>
 </main>
 <script src="../js manual/custom_buscar.js"></script>
 <!--                 
    <div style="height: 273px" class="d-block d-md-none"></div>
    <div style="height: 153px" class="d-none d-md-block d-lg-none"></div>
    <div style="height: 129px" class="d-none d-lg-block"></div>
    -->