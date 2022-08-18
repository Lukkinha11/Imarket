<?php
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
//var_dump($id);
?>
    <main class="mb-5 pb-5 mb-md-0">
        <?php
                ini_set('default_charset','utf-8');
                require_once '../class/conexao2.php';
                global $pdo;

                $query_prod = "SELECT id_Produto, Nome_prod, Desc_prod, Image, Categoria_Prod_idCategoria, Categoria_diretorio, Valor_prod, Valor_novo FROM produto
                                INNER JOIN preco
                                ON preco.Produto_Id_Produto = produto.id_Produto
                                INNER JOIN categoria_prod
                                ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                                WHERE id_Produto =:id";
                $result_prod = $pdo->prepare($query_prod);
                $result_prod -> bindParam(':id', $id, PDO::PARAM_INT);
                $result_prod->execute();
                $row_prod = $result_prod->fetch(PDO::FETCH_ASSOC);
                extract($row_prod);
                //var_dump ($row_prod);

            ?>
     <div class="container">
       <div class="row g-3">
           <div class="col-12 col-sm-6">
               <img src='<?php echo "../img_prod/$Categoria_diretorio/$Image"; ?>' class="img-thumbnail">
               <br class="clearfix">
               <div class="row my-3 gx-3">
                   <div class="col-3">
                       <img src='<?php echo "../img_prod/$Categoria_diretorio/$Image"; ?>' alt="" class="img-thumbnail" id="imgProduto" onclick="trocarImagem(this)">
                   </div>
               </div>
            </div>
               <div class="col-12 col-sm-6">
                    <h1 class="text-center mb-3"><?php echo $Nome_prod; ?></h1>
                    <p>
                        Caro cliente, todos os produtos são selecionados por profissionais capacitados, e armazanados em locais com muita
                        higiene, dando a você todo o conforto e segurança para a sua compra.
                    </p>
                    
                    <?php
                        if($Valor_novo < $Valor_prod)
                        {
                    ?>
                            <p> <strike> <h2 class="text-muted"> De R$ <?php echo number_format($Valor_prod, 2, ",", "."); ?> </h2></strike></p>
                            <h2><p> Por R$  <?php echo number_format($Valor_novo, 2, ",", "."); ?></p></h2>
                            <h5 class="mb-3"> <small> Descrição: <?php echo $Desc_prod; ?> </small></h5>
                    <?php        
                        }
                        else
                        {
                    ?>
                            <h1><p> R$ <?php echo number_format($Valor_prod, 2, ",", "."); ?></p></h1>
                            <h5 class="mb-3"> <small> Descrição: <?php echo $Desc_prod; ?> </small></h5>
                    <?php
                        }
                    ?>

                    <p>
                        <form action="index.php?page=cart&action=add" method="POST" class="d-block">
                            <input type="hidden" name="id" value="<?php echo $id_Produto; ?>" />
                            <a class="btn btn-lg btn-outline-success mb-3 mb-xl-0 me-2" href="index.php?page=index">
                                <i class="bi-reply-all"></i> Continuar Comprando
                            </a>
                            <button type="submit" name="addcart"  class="btn btn-lg btn-danger add">
                                <i class="bi-cart"></i> Adicionar ao Carrinho
                            </button>
                        </form>
                    </p>
               </div>
            </div>  
        </div>
    </main>