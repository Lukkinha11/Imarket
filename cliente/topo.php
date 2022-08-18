 <?php

    ini_set('default_charset', 'utf-8');
    require_once '../class/conexao2.php';
    global $pdo;
    
?>
 <!DOCTYPE html>
 <html lang="pt-br">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <link rel="shortcut icon" href="../img_prod/icon/favicon.ico.ico">
     <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
     <link rel="stylesheet" href="../css manual/estilos.css">
     <link rel="canonical" href="../bootstrap-5-examples/carousel">
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
	 <script src="../js manual/custom_buscar.js"></script>
	 
     


     <title>iMARKET</title>

 </head>

 <body>
     <div class="d-flex flex-column wrapper">
         <nav class="navbar navbar-expand-lg navbar-dark bg-warning border-bottom shadow-sm mb-3">
             <div class="container">
                 <a class="navbar-brand" href="index.php?page=index"><strong>IMARKET</strong></a>
                 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                     <span class="navbar-toggler-icon"></span>
                 </button>
                 <div class="collapse navbar-collapse" id="navbarsExample07">
                     <ul class="navbar-nav flex-grow-1">
                         <li class="nav-item">
                             <a href="index.php?page=contato" class="nav-link text-white">CONTATO</a>
                         </li>
                         <li class="nav-item dropdown">
                             <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 DEPARTAMENTOS
                             </a>
                             <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                 <?php

                                    ini_set('default_charset', 'utf-8');
                                    require_once '../class/conexao2.php';
                                    global $pdo;

                                    $cat_prod = "SELECT idCategoria, categoria FROM categoria_prod";
                                    $result_cat = $pdo->prepare($cat_prod);
                                    $result_cat->execute();

                                    if ($result_cat->rowCount() > 0) {
                                        while ($dados = $result_cat->fetch(PDO::FETCH_ASSOC)) {
                                            extract($dados);
                                    ?>

                                         <li><a class="dropdown-item" href="?page=prod&idcat=<?php echo $idCategoria; ?>"><?php echo $categoria; ?></a></li>

                                 <?php
                                        }
                                    }

                                    ?>
                                 <!--
                                <li><a class="dropdown-item" href="#">Açougue & Congelados</a></li>
                                <li><a class="dropdown-item" href="#">Bazar & Perfumaria</a></li>
                                <li><a class="dropdown-item" href="#">Bebidas</a></li>
                                <li><a class="dropdown-item" href="#">Frios & Laticínios</a></li>
                                <li><a class="dropdown-item" href="#">Hortfrut</a></li>
                                <li><a class="dropdown-item" href="#">Mercearia</a></li>
                                <li><a class="dropdown-item" href="#">Limpeza</a></li>
                                <li><a class="dropdown-item" href="#">Padaria & Confeitaria</a></li>
                                <li><a class="dropdown-item" href="#">Utilidades Gerais</a></li>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                -->
                             </ul>
                         </li>
                     </ul>

                     <div class="align-self-end">
                         <ul class="navbar-nav flex-grow-1">
                             <?php
                              if(!isset($_SESSION['id']) AND !isset($_SESSION['nome']))
                              {
                                echo "<li class='nav-item'>";
                                echo    "<a href='index.php?page=login' class='nav-link text-white'>ENTRAR</a>";
                                echo "</li>";
                                echo "<li class='nav-item'>";
                                echo    "<a href='index.php?page=cad' class='nav-link text-white'>QUERO ME CADASTRAR</a>";
                                echo "</li>";
                              }else
                              {
                                echo "<li class='nav-item dropdown'>";
                                            echo "<a class='nav-link dropdown-toggle text-white' href='#' id='navbarDropdown' role='button' data-bs-toggle='dropdown' aria-expanded='false'>";
                                                echo"Olá ". $_SESSION['nome'];
                                            echo "</a>";
                                            echo "<ul class='dropdown-menu' aria-labelledby='navbarDropdown'>";
                                                echo "<li><a class='dropdown-item' href='controle.php?pag=dice'>Minha Conta</a></li>";
                                                echo "<li><a class='dropdown-item' href='index.php?page=sair'>Sair</a></li>";
                                            echo "</ul>";
                                echo "</li>";
                              }
                             ?>
                             <li class="nav-item">
                                <?php
                                 //$itens = unserialize($_SESSION['cart']);

                                 $cart = isset($_SESSION['cart']) ? unserialize($_SESSION['cart']) : array();
                                ?>
                             <span class="badge rounded-pill bg-light text-danger position-absolute ms-4 mt-0" title=" Seu carrinho está com <?php echo count($cart); ?> produto(s) ">
                                    <?php 
                                        
                                            echo count($cart) == 0 ? "0" :count($cart);
                                        
                                        
                                            /*if(count($itens)==0)
                                            {
                                                echo "0";
                                            }else{
                                                
                                                echo count($itens);
                                            }*/
                                    ?>                                                              
                            </span>
                                 <a href="index.php?page=cart" class="nav-link text-white">
                                     <i class="bi-cart" style="font-size: 22px;line-height:22px"></i>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </nav>
        <?php
        if(isset($_SESSION['alert']))
        {
            echo $_SESSION['alert'];
            unset($_SESSION['alert']);
        }
        ?>