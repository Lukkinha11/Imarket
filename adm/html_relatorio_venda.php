<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <title>Relatório de Vendas</title>

    </head>

    <body>
        <h1  style="text-align: center;">Relatório Semanal de Vendas iMarket</h1>
        <hr>
        <table class="table">
            <thead style="text-align: center;">
                <tr>
                    <th scope="col">DIA DA SEMANA</th>
                    <th scope="col">PEDIDOS ABERTOS</th>
                    <th scope="col">PEDIDOS CANCELADOS</th>
                    <th scope="col">PEDIDOS ATENDIDOS</th>
                    <th scope="col">PEDIDOS PARCIALMENTE ATENDIDOS</th>
                </tr>
            </thead><br>

            <?php
                ob_start();
                ini_set('default_charset','utf-8');
                require_once '../class/conexao2.php';
                global $pdo;

                $query_traduzida = "SET lc_time_names = 'pt_BR'";
                $result_traduzida = $pdo->prepare($query_traduzida);
                $result_traduzida->execute();
                //--SELECT ndia, upper(dia) dia, ifnull(sum(AB),0) 'ABERTO', ifnull(sum(CANC),0) 'CANCELADO',  ifnull(sum(PARCI),0) 'PARCIALMENTE', ifnull(sum(ATENDI),0) 'ATENDIDO'
                //SELECT ndia, upper(dia) dia, ifnull(sum(AB),0)+truncate(rand()*100,0)+20 'ABERTO', ifnull(sum(CANC),0)+truncate(rand()*100,0)+20 'CANCELADO',  ifnull(sum(PARCI),0)+truncate(rand()*100,0)+20 'PARCIALMENTE', ifnull(sum(ATENDI),0)+truncate(rand()*100,0)+20 'ATENDIDO'
                $query_venda = "SELECT ndia, upper(dia) dia, ifnull(sum(AB),0) 'ABERTO', ifnull(sum(CANC),0) 'CANCELADO',  ifnull(sum(PARCI),0) 'PARCIALMENTE', ifnull(sum(ATENDI),0) 'ATENDIDO'
                                FROM
                                (SELECT DAYOFWEEK(Data_Compra) ndia, dayname(Data_Compra) dia, Status_compra_idStatus_compra,
                                CASE
                                WHEN Status_compra_idStatus_compra = 4 
                                    THEN COUNT(*)
                                END 'AB',
                                CASE
                                WHEN Status_compra_idStatus_compra = 3 
                                THEN COUNT(*)
                                END 'CANC',
                                CASE
                                WHEN Status_compra_idStatus_compra = 2 
                                THEN COUNT(*)
                                END 'PARCI',
                                CASE 
                                WHEN Status_compra_idStatus_compra = 1 
                                THEN COUNT(*)
                                END 'ATENDI'
                                FROM carrinho_compra
                                WHERE WEEK(Data_Compra) = WEEK(NOW())
                                GROUP BY  DAYOFWEEK(Data_Compra), dayname(Data_Compra), Status_compra_idStatus_compra)G
                                GROUP BY DIA
                                ORDER BY ndia";
                                
                $result_venda = $pdo->prepare($query_venda);
                $result_venda->execute();
                while($row = $result_venda->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);
            ?>
                    <tbody  style="text-align: center;">
                        <tr>
                            <th scope="row"><?php echo $dia; ?></th>
                            <td><?php echo $ABERTO; ?></td>
                            <th><?php echo $PARCIALMENTE; ?></th>
                            <th><?php echo $CANCELADO; ?></th>
                            <th><?php echo $ATENDIDO; ?></th>
                        </tr>
                    </tbody>
            <?php
                }//FIM WHILE
            ?>
        </table>

        <div class="row g-3">
            <?php
                $query_tot_vendas = "SELECT sum(Status_compra_idStatus_compra) AS Quant_Vendas FROM carrinho_compra
                                    WHERE Status_compra_idStatus_compra in(1,2)
                                    AND WEEK(Data_compra) =  WEEK(NOW())";

                $result_tot_vendas = $pdo->prepare($query_tot_vendas);
                $result_tot_vendas->execute();
                while($row_tot = $result_tot_vendas->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row_tot);
            ?>
                <div class="col-md-6">
                    <p class="h4">TOTAL DE VENDAS: <br>
                        <p><?php echo $Quant_Vendas; ?></p>
                    </p>
                </div>
            <?php
                }//FIM WHILE
            ?>

            
            <?php
                $query_renda = "SELECT sum(Valor_total) AS Renda_Semanal FROM carrinho_compra
                                INNER JOIN itens_carrinho
                                ON itens_carrinho.Carrinho_Compra_idCarrinho_Compra = carrinho_compra.idCarrinho_Compra
                                WHERE Status_compra_idStatus_compra in(1,2)
                                AND WEEK(Data_compra) =  WEEK(NOW())";

                $result_renda = $pdo->prepare($query_renda);
                $result_renda->execute();

                while($row_renda = $result_renda->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row_renda);
            ?>  
                    <div class="col-md-6">
                        <p class="h4">RENDA GERADA NA SEMANA: <br>
                            <p>R$ <?php echo number_format($Renda_Semanal, 2, ',' , '.'); ?></p>
                        </p>
                    </div>  
            <?php
                }//FIM WHILE
            ?>
        </div>
    </body>
</html>

