<?php

    ini_set('default_charset', 'utf-8');
    require_once '../class/conexao2.php';
    global $pdo;

    $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    //Definir a quantidade de registros por pagina
    $limite_resultado = 24;

    //Contar a quantidade de registros no banco de dados
    $query_qnt_registros = "SELECT COUNT(id_Produto) AS num_result FROM produto";
    $result_qnt = $pdo->prepare($query_qnt_registros);
    $result_qnt->execute();
    $row_qnt_registros =  $result_qnt->fetch(\PDO::FETCH_ASSOC);

    $quant_paginas = ceil($row_qnt_registros['num_result'] / $limite_resultado);

    //maximo de links
    $maximo_link = 2;

    $ordenacao_prod = array('Nome_prod'=>'Ordenar pelo nome', 'DESC'=>'Ordenar pelo maior preço', 'ASC'=>'Ordenar pelo menor preço');


    if(isset($_SESSION['filtro_prod']))
    {
        var_dump($_SESSION['filtro_prod']);
    }

    /*if(isset($_GET['filtro_prod']))
    {   
        if($_GET['filtro_prod'] == "Nome_prod")
        {
            echo "Nome_prod";
        }
        elseif($_GET['filtro_prod'] == "DESC")
        {
            echo "DESC";
        }
        elseif($_GET['filtro_prod'] == "ASC")
        {
            echo "ASC";
        }   
    }*/
?>
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
                 <img src="../img/slides_rotativos/slide02.jpg" alt="" class="img-fluid d-none d-md-block">
                 <img src="../img/slides_rotativos/slide02small.jpg" alt="" class="img-fluid d-block d-md-none">
             </div>
             <div class="carousel-item  text-center" data-interval="3000">
                 <img src="../img/slides_rotativos/slide03.jpeg" alt="" class="img-fluid d-none d-md-block">
                 <img src="../img/slides_rotativos/slide03small.jpeg" alt="" class="img-fluid d-block d-md-none">
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
     <hr class="mt-3"/>
 </header>

    <main class="flex-fill">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-5">
                    <form action="" method="POST" id="form-pesquisa" class="justify-content-center justify-content-md-start mb-3 mb-md-0">
                        <div class="input-group input-group-sm">
                            <input type="text" name="pesquisa" id="pesquisa" class="form-control" placeholder="Digite aqui o que procura">
                            <input type="button" name="enviar" value="Buscar" class="btn btn-primary">
                        </div>
                    </form>
                </div>
             
             <div class="col-12 col-md-7">
                 <div class="d-flex flex-row-reverse justify-content-center justify-content-md-start">
                     <form method="GET" class="d-inline-block">
                         <select name="filtro_prod" id="filtro_prod" onchange="this.form.submit()" class="form-select form-select-sm">
                                <?php
                                    foreach($ordenacao_prod as $v=>$vv)
                                    {
                                        $selected = (isset($_GET['filtro_prod']) && $_GET['filtro_prod'] == $v) ? 'selected' : '';

                                        echo '<option value="'.$v.'" '.$selected.'>'.$vv.'</option>';
                                    }
                                ?>
                         </select>
                     </form>
                     <nav class="d-inline-block me-3">
                         <ul class="pagination pagination-sm my-0">
                                <?php

                                    echo "<li class='page-item'>
                                            <a href='index.php?page=1' class='page-link' role='button'>Primeira</a>
                                        </li>";

                                    for($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <=$pagina - 1; $pagina_anterior++)
                                    {
                                        if($pagina_anterior >= 1)
                                        {
                                            echo "<li class='page-item'>
                                                    <a href='index.php?page=$pagina_anterior' class='page-link' role='button'>$pagina_anterior</a>
                                                </li>";
                                        }        
                                    }

                                    echo "<li class='page-item disabled'>
                                            <a href='#' class='page-link' role='button'>$pagina</a>
                                        </li>";

                                    for($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++)
                                    {
                                        if($proxima_pagina <= $quant_paginas)
                                        {
                                            echo "<li class='page-item'>
                                                    <a href='index.php?page=$proxima_pagina' class='page-link' role='button' >$proxima_pagina</a>
                                                </li>";
                                        }
                                    }

                                    echo "<li class='page-item'>
                                            <a href='index.php?page=$quant_paginas' class='page-link' role='button' >Última</a>
                                        </li>";
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <hr class="mt-3">
            <h2 class="display-4 mt-5 mb-5">Produtos<h2>
            <div class="row g-3 resultado" id="produtos">
                <?php foreach($products as $product) : ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card text-center bg-light">	
                            <img src='<?php echo"../img_prod/";echo $product->getCategoria_diretorio(); echo"/"; echo $product->getImage(); ?>' class="card-img-top" alt="...">							 
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product->getName(); ?></h5>
                                <div class="h4">R$ <?php echo number_format($product->getPrice(), 2, ',' , '.'); echo" "; echo $product->getSigla(); ?></div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <form action="index.php?page=cart&action=add" method="POST" class="d-block">	
                                    <div class="d-grid gap-2 ">
                                        <input type="hidden" name="id" value="<?php echo $product->getId(); ?>" />
                                        <button  type="submit" name="addcart" class="btn btn-outline-secondary add" title="Adicionar ao Carrinho" ><strong>ADICIONAR</strong> <img src="../icons/add-to-cart.png" alt=""></button>
                                        <a href="index.php?page=det&id=<?php echo $product->getId(); ?>" class="btn btn-outline-secondary"><strong>DETALHES</strong>  👀</a>
                                    </div>
                                </form>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>	
            </div>
                
            <hr class="mt-3">
            <div class="row pb-3">
                <div class="col-12">
                    <div class="d-flex flex-row-reverse justify-content-center justify-content-md-center">
                        <nav class="d-inline-block">
                            <ul class="pagination pagination-sm my-0">
                                <?php

                                    echo "<li class='page-item'>
                                            <a href='index.php?page=1' class='page-link' role='button'>Primeira</a>
                                        </li>";

                                    for($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <=$pagina - 1; $pagina_anterior++)
                                    {
                                        if($pagina_anterior >= 1)
                                        {
                                            echo "<li class='page-item'>
                                                    <a href='index.php?page=$pagina_anterior' class='page-link' role='button'>$pagina_anterior</a>
                                                </li>";
                                        }        
                                    }

                                    echo "<li class='page-item disabled'>
                                            <a href='#' class='page-link' role='button'>$pagina</a>
                                        </li>";

                                    for($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++)
                                    {
                                        if($proxima_pagina <= $quant_paginas)
                                        {
                                            echo "<li class='page-item'>
                                                    <a href='index.php?page=$proxima_pagina' class='page-link' role='button' >$proxima_pagina</a>
                                                </li>";
                                        }
                                    }

                                    echo "<li class='page-item'>
                                            <a href='index.php?page=$quant_paginas' class='page-link' role='button' >Última</a>
                                        </li>";
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </main>

 <script src="../js manual/custom_page.js"></script>
 <!--                 
    <div style="height: 273px" class="d-block d-md-none"></div>
    <div style="height: 153px" class="d-none d-md-block d-lg-none"></div>
    <div style="height: 129px" class="d-none d-lg-block"></div>
    -->