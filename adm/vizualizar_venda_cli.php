<?php
ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idCarrinho_Compra = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//var_dump($idCarrinho_Compra);

$query_venda_cli = "SELECT idCarrinho_Compra, Nome, CONCAT(DDD, ' ', Telefone) AS Telefone, Email, Nome_prod,  Desc_prod, Quant_prod, CONCAT(Quant_estoque, ' ', Sigla) AS Quant_estoque FROM carrinho_compra
                    INNER JOIN cliente
                    ON cliente.idCliente = carrinho_compra.Cliente_idCliente
                    INNER JOIN itens_carrinho
                    ON carrinho_compra.idCarrinho_Compra = itens_carrinho.Carrinho_Compra_idCarrinho_Compra
                    INNER JOIN produto
                    ON produto.id_Produto = itens_carrinho.Produto_Id_Produto
                    INNER JOIN estoque
                    ON estoque.idEstoque = produto.id_Produto
                    INNER JOIN unidade_medidas
                    ON unidade_medidas.idUnidade_medidas = produto.unidade_medidas_idUnidade_medidas
                    WHERE idCarrinho_Compra =:idCarrinho_Compra";

$result_venda_cli = $pdo->prepare($query_venda_cli); 
$result_venda_cli->bindValue(':idCarrinho_Compra', $idCarrinho_Compra, PDO::PARAM_INT);
$result_venda_cli->execute();

$cont = 0;

?>

<div class="container-fluid px-4">
    <div class="row my-5">
        <h3 class="fs-4 mb-3">Detalhes da Venda</h3>
        <div class="col mt-3">
                <?php

                    while($row_venda = $result_venda_cli->fetch(PDO::FETCH_ASSOC) )
                    {
                        extract($row_venda)
                ?>      
                        <tbody><?php 
                                    if($cont==0)
                                    { 
                                        echo"<p class='h6'>
                                                <b>Compra:</b> #$idCarrinho_Compra &nbsp &nbsp <b>Cliente:</b> $Nome &nbsp &nbsp
                                                <b>Telefone:</b> $Telefone &nbsp &nbsp <b>Email:</b> $Email
                                            </p>";
                                ?>
            <table class="table bg-white rounded shadow-sm  table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Produto</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Quantidade Comprada</th>
                                <th scope="col">Quantidade em Estoque</th>
                            </tr>
                            <?php } ?>
                        </thead>
                            <tr>
                                <td><?php echo $Nome_prod; ?></td>
                                <td><?php echo $Desc_prod; ?></td>
                                <td><?php echo $Quant_prod; ?></td>
                                <td><?php echo $Quant_estoque; ?></td>
                            </tr>
                        </tbody>
                <?php
                        $cont++;
                    }

                ?>
                
            </table>
        </div>
    </div>
</div>

