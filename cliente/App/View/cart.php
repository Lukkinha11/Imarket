<main class="flex-fill">
     <div class="container">
         <h2>Carrinho de compras</h2>
         <hr>
         <?php  
                ini_set('default_charset', 'utf-8');
                require_once '../class/conexao2.php';
                global $pdo;
                ob_start();

                $cart = isset($_SESSION['cart']) ? unserialize($_SESSION['cart']) : array();
                if(count($cart) > 0)
                {      
                    if(!isset($_SESSION['id']) AND !isset($_SESSION['nome']))
                    {
                        echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                <strong>ATENÇÃO!</strong> Você deve está logado para finalizar a compra! <a href='index.php?page=login'>Fazer Login</a>
                            </div>";
                    }elseif( isset($_POST['finish']))
                    {
                        header("Location: ?page=entrega");
                       
                    }
                }

                $_SESSION['dados'] = array();

                
                
            ?>
            <?php foreach($cartItems as $item) : ?>
                <ul class="list-group mb-3">
                    <li class="list-group-item py-3">
                        <div class="row gx-3">
                            <div class="col-4 col-md-3 col-lg-2">
                                <a href="#">
                                    <img src='<?php echo"../img_prod/"; echo $item->getProduct()->getCategoria_diretorio(); echo"/"; echo $item->getProduct()->getImage(); ?>'class="img-thumbnail">
                                </a>
                            </div>
                            <div class="col-8 col-md-9 col-lg-7 col-xl-8 text-left align-self-center">
                                <h4>
                                    <b>
                                        <a href="#" class="text-decoration-none text-danger"><?php echo $item->getProduct()->getName(); ?></a>
                                    </b>
                                </h4>
                                <h4>
                                    <small><?php echo $item->getProduct()->getDesc(); ?></small>
                                </h4>
                            </div>
                            <div class="col-6 offset-6 col-sm-6 offset-sm-6 col-md-4 offset-md-8
                                col-lg-3 offset-lg-0 col-xl-2 align-self-center mt-3">
                                
                                    <form action="index.php?page=cart&action=update" method="POST">
                                        <div class="input-group">
                                            <input type="hidden"  name="id" class="form-control text-center border-dark" value="<?php echo $item->getProduct()->getId(); ?>" />
                                            <input max="10" min="1" type="number" style="margin-left: -20px;" name="quantity" class="form-control text-center border-dark rounded-start" value="<?php echo $item->getQuantity(); ?>" />
                                            <button type="submit" class="btn btn-outline-success btn-sm" >
                                                Atualizar  <i class="bi-arrow-repeat" style="font-size: 16px;"></i>
                                            </button>
                                            <button type="button" onclick="window.location.href='index.php?page=cart&action=delete&id=<?php echo $item->getProduct()->getId(); ?>'" class="btn btn-outline-danger border-dark btn-sm" >
                                                <svg class="bi" width="16" height="16" fill="currentColor">
                                                    <use xlink:href="../icons/bi.svg#trash"></use>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-end mt-2">
                                                <small class="text-secondary">Valor do produto: R$ <?php echo number_format($item->getProduct()->getPrice(), 2, ',' , '.'); echo" "; echo $item->getProduct()->getSigla(); ?></small><br>
                                                <span class="text-dark">Subtotal do Item: R$ <?php echo number_format($item->getQuantity() * $item->getProduct()->getPrice(), 2, ',' , '.'); ?></span>
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </li>
                    <?php
                     array_push(
                         $_SESSION['dados'],
                         array(
                             'id_produto' => $item->getProduct()->getId(),
                             'quantidade' => $item->getQuantity(),
                             'preco' => $item->getProduct()->getPrice(),
                             'sub_total' => $item->getQuantity() * $item->getProduct()->getPrice(),
                             'total' => number_format($cartTotal, 2, ',' , '.'),
                             'tot' => $cartTotal
                         ) 
                     );
                     
                    ?>
                        <?php endforeach; ?>

                        <?php
                            //echo '<pre>';
                            //var_dump($_SESSION['dados']);
                            //$cont = 1;
                            //foreach($_SESSION['dados'] as $produtos)
                            //{
                                //var_dump($produtos['id_produto']);       
                             //echo $cont++;      
                               
                                
                            //}

                            //echo '<pre>';
                        ?>
                        
                    
                        <?php
                            $cart = isset($_SESSION['cart']) ? unserialize($_SESSION['cart']) : array();
                            if(count($cart) == 0)
                            {
                            //header("Location: index.php?page=home");
                        ?>   
                            <div class="text-muted">
                                <div class="text-center mt-5">
                                    <BR></BR>
                                    <p>
                                        <h1>Aaah não💔 Seu Carrinho está vazio</h1>
                                    </p>
                                    <p>
                                        <h3>Não perca tempo nossos produtos esperam por você 😉</h3>
                                    </p>
                                    <p>
                                    <h3>( ◑‿◑)ɔ┏🍇--🍖┑٩(^◡^ )</h3>
                                    </p>
                                </div>
                            </div>
                        <?php
                            }
                            else
                            {
                        ?>
                    <li class="list-group-item pt-3 pb-0">
                        <div class="text-end footer">
                            <form action="" method="POST">
                            <h4 class="text-dark mb-3">Valor Total: R$ <?php echo number_format($cartTotal, 2, ',' , '.'); ?></h4>
                            <a href="?pg=index" class="btn btn-outline-success btn-lg mb-3">Continuar Comprando</a>   
                                <button class="btn btn-primary btn-lg ms-2 mb-3" name="finish" type="submit">Finalizar Compra</button>
                            </form>
                        </div>
                    </li>
                </ul>
                        <?php
                            }
                        ?>
     </div>
 </main>    
    <!-- 
        onclick="window.location.href='index.php?page=entrega'"                
    <div style="height: 273px" class="d-block d-md-none"></div>
    <div style="height: 153px" class="d-none d-md-block d-lg-none"></div>
    <div style="height: 129px" class="d-none d-lg-block"></div>
    -->  