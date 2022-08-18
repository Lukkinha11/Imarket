<?php
    ini_set('default_charset','utf-8');
    require_once '../class/conexao2.php';
    global $pdo;
    ob_start();
                            
                        

    /*$file= file_get_contents('C:\wamp64\www\Imarket\adm\html_relatorio_venda.php');

    //referenciar o DomPDF com o namespace
    use Dompdf\Dompdf;

    //include autoloader DOMPDF
    require_once 'dompdf/autoload.inc.php';

    //Instância do DOMPDF
    $dompdf = new Dompdf();

    //Carrega o Html para dentro da classe
    //$dompdf->loadHtml('<b>Olá Mundo</b>');
    $dompdf->loadHtml($file);

    //Renderizar o Arquivo PDF
    $dompdf->render();

    //Imprime o conteúdo do arquivo pdf na tela
    header('Content-type: application/pdf');
    echo $dompdf->output();*/

//===============================================================================================================================================================================  
if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome'])) AND (!isset($_SESSION['acesso'])) OR ($_SESSION['status_acesso'] == 1))
{
    header("Location: ../index.php");
}
elseif($_SESSION['acesso'] == 3)
{
    header("Location: ../index.php");
    exit();
}
    
//referenciar o DomPDF com o namespace
    use Dompdf\Dompdf;

    //include autoloader DOMPDF
    require_once 'dompdf/autoload.inc.php';

    //Construção do layout pdf
    $html = "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>";
    $html .= "<script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js' integrity='sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p' crossorigin='anonymous'></script>";
    $html .= "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js' integrity='sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF' crossorigin='anonymous'></script>";
    $html .= "<title>Relatório de Vendas</title>";
    $html .= "<h1 style='text-align: center;'>Relatório Semanal de Vendas iMarket</h1>";
    $html .= "<hr><br>";
    $html .= "<table border=1>";
    $html .= "<thead style='text-align: center;'>";
    $html .= "<th scope='col'>DIA DA SEMANA</th>";
    $html .= "<th scope='col'>PEDIDOS ABERTOS</th>";
    $html .= "<th scope='col'>PEDIDOS CANCELADOS</th>";
    $html .= "<th scope='col'>PEDIDOS ATENDIDOS</th>";
    $html .= "<th scope='col'>PEDIDOS PARCIALMENTE ATENDIDOS</th>";
    $html .= "</thead><br>";
    

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
    
            $html .= "<tbody  style='text-align: center;'>";
            $html .= "<tr>";
            $html .= "<td scope='row'> $dia </td>";
            $html .= "<td> $ABERTO </td>";
            $html .= "<td> $CANCELADO </td>";
            $html .= "<td> $ATENDIDO </td>";
            $html .= "<td> $PARCIALMENTE </td>";
            $html .= "</tr>"; 
            $html .= "</tbody>";  

        }//FIM WHILE

    $html .= "</table>";  
    $html .= "<div style='display: flex'>"; 
    

        $query_tot_vendas = "SELECT count(Status_compra_idStatus_compra) AS Quant_Vendas FROM carrinho_compra
                            WHERE Status_compra_idStatus_compra in(1,2)
                            AND WEEK(Data_compra) =  WEEK(NOW())";

        $result_tot_vendas = $pdo->prepare($query_tot_vendas);
        $result_tot_vendas->execute();
        while($row_tot = $result_tot_vendas->fetch(PDO::FETCH_ASSOC))
        {
            extract($row_tot);

            $html .= "<div  style='width: 50% !important'>";  
            $html .= "  <p style='width: 50% !important'> TOTAL DE VENDAS: <br>";  
            $html .= "      $Quant_Vendas";  
            $html .= "  </p>";  
            $html .= "</div>";    

        }//FIM WHILE
        
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
        
            $html .= "<div style='width: 50% !important'>";  
            $html .= "  <p >RENDA GERADA NA SEMANA: <br>";  
            $html .= "     R$ " .number_format($Renda_Semanal, 2, ',' , '.')."";  
            $html .= "  </p>";  
            $html .= "</div>";  
            
        }//FIM WHILE
        
    $html .= "</div>";
    
    $dompdf = new Dompdf();

    $dompdf->loadHtml($html);

    //Renderizar o html
    $dompdf->render();

    //Exibir a página
    $dompdf->stream(
        "relatorio_semanal_vendas.pdf",
        array(
            "Attachment" => false //Para realizar o download automático mudar para true
        )
    );
