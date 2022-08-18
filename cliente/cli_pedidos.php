            <?php
            ob_start();
            ini_set('default_charset','utf-8');
            require_once '../class/conexao2.php';
            global $pdo;

            $x = 1;

            $qtd = 10;
            $esc = 'DESC';

            $ordenacao = array('DESC'=>'Mais novos primeiro', 'ASC'=>'Mais antigos primeiro');

            $quatidade = array(10=>'10', 20=>'20', 30=>'30', 40=>'50', 100=>'100');

            if(!isset($_SESSION['id']) AND !isset($_SESSION['nome']))
            {
                header("Location: index.php");
                exit();                    
            }
            else
            {
                if(isset($_POST['filtro']) AND isset($_POST['quantidade']))
                {
                    $esc = $_POST['filtro'];
                    $qtd = $_POST['quantidade'];

                    $userid = $_SESSION['id'];
                    $query_compras = "SELECT idCliente, idCarrinho_Compra, idForma_Pag, concat(Descricao, ' ', Quant, 'x') AS contpag, COUNT(idCarrinho_Compra) AS Total_de_compras, SUM(Valor_total) AS total_venda, date_format(Data_Compra, '%d/%m/%Y') AS Data_Compra, Status_compra from cliente
                                        INNER JOIN carrinho_compra
                                        ON carrinho_compra.Cliente_idCliente = cliente.idCliente
                                        INNER JOIN itens_carrinho
                                        ON itens_carrinho.Carrinho_Compra_idCarrinho_Compra = carrinho_compra.idCarrinho_Compra
                                        INNER JOIN forma_pagamento
                                        ON carrinho_compra.Forma_Pagamento_idForma_Pag = forma_pagamento.idForma_Pag
                                        INNER JOIN desc_pag
                                        ON forma_pagamento.Desc_pag_idDesc_pag = desc_pag.idDesc_pag
                                        INNER JOIN status_compra
                                        ON status_compra.idStatus_compra = carrinho_compra.Status_compra_idStatus_compra
                                        WHERE idCliente = " .$userid. " 
                                        GROUP BY idCarrinho_Compra
                                        ORDER BY Data_Compra $esc LIMIT $qtd";

                    $result_compras = $pdo->prepare($query_compras);
                    $result_compras->execute();   
                }
                else
                {
                    $userid = $_SESSION['id'];
                    $query_compras = "SELECT idCliente, idCarrinho_Compra, idForma_Pag, concat(Descricao, ' ', Quant, 'x') AS contpag, COUNT(idCarrinho_Compra) AS Total_de_compras, SUM(Valor_total) AS total_venda, date_format(Data_Compra, '%d/%m/%Y') AS Data_Compra, Status_compra from cliente
                                        INNER JOIN carrinho_compra
                                        ON carrinho_compra.Cliente_idCliente = cliente.idCliente
                                        INNER JOIN itens_carrinho
                                        ON itens_carrinho.Carrinho_Compra_idCarrinho_Compra = carrinho_compra.idCarrinho_Compra
                                        INNER JOIN forma_pagamento
                                        ON carrinho_compra.Forma_Pagamento_idForma_Pag = forma_pagamento.idForma_Pag
                                        INNER JOIN desc_pag
                                        ON forma_pagamento.Desc_pag_idDesc_pag = desc_pag.idDesc_pag
                                        INNER JOIN status_compra
                                        ON status_compra.idStatus_compra = carrinho_compra.Status_compra_idStatus_compra
                                        WHERE idCliente = " .$userid. " 
                                        GROUP BY idCarrinho_Compra
                                        ORDER BY Data_Compra $esc LIMIT $qtd";

                    $result_compras = $pdo->prepare($query_compras);   
                    $result_compras->execute();   
                }
            }

            ?>
            <div class="col-8">
                <form action="" method="POST" class="row mb-3">
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">
                            <select name="filtro" onchange="this.form.submit()" id="filtro" class="form-select">
                                <?php
                                    foreach($ordenacao as $v=>$vv)
                                    {
                                        $selected = (isset($_POST['filtro']) && $_POST['filtro'] == $v) ? 'selected' : '';

                                        echo '<option value="'.$v.'" '.$selected.'>'.$vv.'</option>';
                                    }
                                ?>
                            </select>          
                            <label for="">Ordenação</label>
                        </div>             
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">
                            <select name="quantidade"  onchange="this.form.submit()" id="quantidade" class="form-select">    
                                <?php
                                    foreach($quatidade as $q=>$qq)
                                    {
                                        $selected = (isset($_POST['quantidade']) && $_POST['quantidade'] == $q) ? 'selected' : '';

                                        echo '<option value="'.$q.'" '.$selected.'>'.$qq.'</option>';
                                    }
                                ?>
                            </select>
                            <label for="">Quantidade de Pedidos</label>
                        </div>           
                    </div>
                </form>
                        
                <?php
                    while($row_compras = $result_compras->fetch(PDO::FETCH_ASSOC))
                    {
                        $cont = 1;
                        extract($row_compras);

                        $query_produtos = "SELECT idCliente, id_Produto, idCarrinho_Compra, Nome_prod, Quant_prod, itens_carrinho.Valor_unit, Valor_total, Descricao, Forma_pag, Quant, date_format(Data_Compra, '%d/%m/%Y') AS Data_Compra From cliente
                                            INNER JOIN carrinho_compra
                                            ON carrinho_compra.Cliente_idCliente = cliente.idCliente
                                            INNER JOIN itens_carrinho
                                            ON itens_carrinho.Carrinho_Compra_idCarrinho_Compra = carrinho_compra.idCarrinho_Compra
                                            INNER JOIN produto
                                            ON itens_carrinho.Produto_Id_Produto = produto.id_Produto
                                            INNER JOIN preco
                                            ON preco.Produto_Id_Produto = produto.id_Produto
                                            INNER JOIN forma_pagamento
                                            ON carrinho_compra.Forma_Pagamento_idForma_Pag = forma_pagamento.idForma_Pag
                                            INNER JOIN desc_pag
                                            ON forma_pagamento.Desc_pag_idDesc_pag = desc_pag.idDesc_pag
                                            WHERE idCarrinho_Compra = " .$idCarrinho_Compra. " ";

                        $result_prod = $pdo->prepare($query_produtos);
                        $result_prod->execute();
                        
                        //$row_prod = $result_prod->fetch(PDO::FETCH_ASSOC);
                        
                        //extract($row_prod);
                        
                ?>
                        <div class="accordion" id=<?php echo"'divPedidos$x'"; ?>>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target=<?php echo"'#pedido0$x'"; ?>>
                                        <b>Pedido <?php echo $x ?></b>
                                        <span class="mx-1">(Realizado em <?php echo $Data_Compra ?>)</span>
                                        <span class="mx-1"><b>Status da Compra:</b> <?php echo $Status_compra ?></span>
                                    </button>
                                </h2>
                                <?php
                                    while($row_produtos = $result_prod->fetch(PDO::FETCH_ASSOC))
                                    {
                                        extract($row_produtos);
                                ?>     
                                                <?php
                                                if($cont ==1)
                                                    {    
                                                ?>
                                        <div id=<?php echo"'pedido0$x'"; ?> class="accordion-collapse collapse" data-bs-parent=<?php echo"'#divPedidos$x'"; ?> >
                                            <div class="accordion-body">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Produto</th>
                                                                <th class="text-end">R$ Unit.</th>
                                                                <th class="text-center">Qtde.</th>
                                                                <th class="text-end">Total</th>
                                                            </tr>
                                                        </thead>
                                                    <?php
                                                        }
                                                    ?> 
                                                                        
                                                        <tbody>
                                                            <tr>
                                                                <td><?php echo $Nome_prod; ?></td>
                                                                <td class="text-end">R$ <?php echo number_format($Valor_unit, 2, ',' , '.'); ?></td>
                                                                <td class="text-center"><?php echo $Quant; ?></td>
                                                                <td class="text-end">R$ <?php  echo number_format($total= $Valor_unit * $Quant, 2, ',' , '.'); ?></td>
                                                            </tr>
                                                        </tbody>
                                                                    <?php
                                                                        if($cont == $Total_de_compras)
                                                                        {
                                                                            $cont = 0;
                                                                    ?>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-end" colspan="3">Valor dos Produtos</th>
                                                                    
                                                                    <td class="text-end">R$ <?php echo number_format($total_venda, 2, ',' , '.'); ?></td>
                                                                    
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-end" colspan="3">Valor do Frete</th>
                                                                    <td class="text-end">Grátis</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-end" colspan="3">Valor a Pagar</th>
                                                                    <td class="text-end">R$ <?php echo number_format($total_venda, 2, ',' , '.'); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-end" colspan="3">Forma de Pagamento</th>
                                                                    <td class="text-end"><?php echo $contpag; ?></td>
                                                                </tr> 
                                                            </tfoot> 
                                                    </table>
                                                    <?php
                                                        }
                                                        $cont ++; //FIM IF  
                                                    ?>
                                            </div>
                                        </div>
                                <?php
                                    }//FIM SEGUNDO WHILE
                                ?>
                                
                            </div>
                            <br>
                        </div>
                <?php
                $x++;
                  }//FIM RPIMEITO WHILE
                ?>    
            </div>
        </div>
     </div>
 </main>
    <script type="text/javaScript">
      document.getElementById('filtro').addEventListener('change', function() {
        this.form.submit();
        });              
    </script>
 

