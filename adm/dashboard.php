                        <div class="row my-5">
                            <h3 class="fs-4 mb-3">Relatório de Vendas</h3>
                            <?php
                                ob_start();
                                ini_set('default_charset','utf-8');
                                require_once '../class/conexao2.php';
                                global $pdo;
                            ?>
                            <head>
                                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                    <script type="text/javascript">
                                        google.charts.load('current', {
                                            'packages': ['bar']
                                        });
                                        google.charts.setOnLoadCallback(drawChart);

                                        function drawChart() 
                                        {
                                            var data = google.visualization.arrayToDataTable([
                                                ['Dia', 'Pedido em Aberto', 'Pedido Cancelado', 'Pedido Parcialmente Atendido', 'Pedido Atendido'],
                            <?php
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
                                    ['<?php echo "$dia" ?>', <?php echo  $ABERTO ?>, <?php echo $CANCELADO ?>, <?php echo $PARCIALMENTE ?>, <?php echo $ATENDIDO ?>],
                                <?php
                                }//FIM WHILE
                                ?>
                                            ])//FIM var data;

                                            var options = {
                                                chart: {
                                                    title: 'Relatório de vendas semanal',
                                                    //subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                                                }
                                            };

                                            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                                            chart.draw(data, google.charts.Bar.convertOptions(options));
                                        }//FIM function drawChart
                                    </script>
                                
                            </head>

                            <body>
                                <div id="columnchart_material" style="width: 2000px; height: 700px;"></div>
                            </body>
                        </div>