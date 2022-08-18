<?php
    if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome'])) AND (!isset($_SESSION['acesso'])))
    {
        header("Location: ../index.php");
    }

    $id_func = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="shortcut icon" href="../img_prod/icon/favicon.ico">
        <link rel="shortcut icon" href="../img_prod/icon/favicon.ico.ico">
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../css manual/styles.css" />
        <link rel="stylesheet" href="../css manual/custom_dataTables.bootstrap5.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <!--<link rel="stylesheet" href="../css manual/cdnjs_cloudflare_com.css" />-->
        
        <script src="../js manual/jquery_3_6_0.min.js"></script>
        <script src="../bootstrap/js/bootstrap.bundle.js" type="text/javascript"></script>
        
        <script src="../js manual/jquery-3.5.1_datatables.js" type="text/javascript"></script>
        <script src="../js manual/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../js manual/custom_dataTables.bootstrap5.min.js" type="text/javascript"></script>
        
        <title>iMarket ADM</title>
    </head>
    <style>
        .conteudo{
        width:100%;
        height: calc(100vh - 116px);
        background-color:#fff;
        overflow: auto;
        color: #fff;
        }
        
        .pequeno {
            width: 20%;
        }

        .medio {
            width: 50%;
        }
    </style>

    <body>
            <div class="d-flex" id="wrapper">
                <!-- Sidebar -->
                <div class="bg-white" id="sidebar-wrapper">
                    <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
                            class="fas fa fa-wrench me-2"></i>IMARKET
                    </div>
                    <div class="list-group list-group-flush my-3 conteudo">
                        <a href="controle.php?page=dash" class="list-group-item list-group-item-action bg-transparent second-text active">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        
                        <?php
                            if($_SESSION['acesso'] == 1)//ADMINISTARDOR
                            {
                        ?>
                                <a href="controle.php?page=cargo" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-journal-text me-2"></i>Cargos
                                </a>
                                <a href="controle.php?page=sit_cli" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-person-check-fill me-2"></i>Status do Cliente
                                </a>
                                <a href="controle.php?page=desc_pag" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-piggy-bank-fill me-2"></i>Descrição do Pagamento
                                </a>
                                <a href="controle.php?page=forma_pag" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-currency-dollar me-2"></i>Forma de Pagamento                        
                                </a>
                                <a href="controle.php?page=func" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-person-video2 me-2"></i>Funcionários
                                </a>
                                <a href="controle.php?page=access" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-pin-angle-fill me-2"></i>Controle de Acesso
                                </a>
                        <?php
                            }
                        ?>
                        
                        <?php
                            if($_SESSION['acesso'] == 1 || $_SESSION['acesso'] == 2)//ADMINISTARDOR E GERENTE
                            {
                        ?>
                                
                                <a href="controle.php?page=cat_prod" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas fa-shopping-cart me-2"></i>Categoria de Produtos
                                </a>
                                <a href="controle.php?page=buy" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-shield-check me-2"></i>Status da Compra
                                </a>
                                <a href="controle.php?page=item" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas fa-gift me-2"></i>Status do Item
                                </a>
                                <a href="controle.php?page=stock" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-table me-2"></i>Nota Fornecedor
                                </a>
                                <a href="controle.php?page=upd_stock" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas fa-shopping-cart me-2"></i> Disponibilidade de Estoque
                                </a>
                                <a href="manter_relatorio_vendas.php" target="_blank" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-file-earmark-pdf-fill me-2"></i>Relatório de Vendas Semanal
                                </a>
                                
                        <?php
                            }
                        ?>

                        <?php
                            if($_SESSION['acesso'] == 1 || $_SESSION['acesso'] == 2 || $_SESSION['acesso'] == 3)//ADMINISTARDOR GERENTE E COMUM
                            {
                        ?>
                                <a href="controle.php?page=prod" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-bag-fill me-2"></i>Produto
                                </a>
                                <a href="controle.php?page=cli" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-person-check-fill me-2"></i>Cliente
                                </a>
                                <a href="controle.php?page=marca" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas fa-paperclip me-2"></i>Marca
                                </a>
                                <a href="controle.php?page=preco" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-cash-coin me-2"></i>Preço
                                </a>
                                <a href="controle.php?page=status" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-truck me-2"></i>Status da Entrega
                                </a>
                                <a href="controle.php?page=dador" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i class="fas bi bi-person-lines-fill me-2"></i>Fornecedores
                                </a>
                                <a href="controle.php?page=medida" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                                    <i  class="fas fa-paperclip me-2"></i>Unidade de Medida
                                </a>
                                <a href="controle.php?page=sair" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                                    <i class="fas fa-power-off me-2"></i>Logout
                                </a>
                        <?php
                            }
                        ?>
                        
                        <!--<a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                                class="fas fa-gift me-2"></i>Products</a>
                        <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                                class="fas fa-gift me-2"></i>Products</a>
                        <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                                class="fas fa-comment-dots me-2"></i>Chat</a>
                        <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                                class="fas fa-map-marker-alt me-2"></i>Outlet</a>-->
                        
                    </div>
                </div>
                <!-- /#sidebar-wrapper -->

                <!-- Page Content -->
                <div id="page-content-wrapper">
                    <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                            <h2 class="fs-2 m-0">Dashboard</h2>
                        </div>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user me-2"></i>Olá <?php echo $_SESSION['nome']; ?>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item btn" href="controle.php?page=my_account">Minha Conta</a></li>
                                        <!--<li><a class="dropdown-item" href="#">Settings</a></li>-->
                                        <li><a class="dropdown-item" href="controle.php?page=sair">Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="container-fluid px-4">
                        <div class="row g-3 my-2">
                            <div class="col-md-3">
                                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                                    <div>
                                        <?php
                                            ini_set('default_charset', 'utf-8');
                                            require_once '../class/conexao2.php';
                                            global $pdo;

                                            $query_qtd_prod = "SELECT count(*) AS Quant_prod FROM produto";
                                            $result_qtd_prod = $pdo->prepare($query_qtd_prod);
                                            $result_qtd_prod->execute();

                                            $row_qtd_prod = $result_qtd_prod->fetch(PDO::FETCH_ASSOC);
                                            extract($row_qtd_prod);
                                        ?>
                                        <h3 class="fs-2 text-center"><?php echo $Quant_prod ?></h3>
                                        <p class="fs-5">Produtos</p>
                                    </div>
                                    <i class="fas fa-gift fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                                    <div>
                                        <?php
                                            ini_set('default_charset', 'utf-8');
                                            require_once '../class/conexao2.php';
                                            global $pdo;

                                            $query_qtd_vendas = "SELECT count(Status_compra_idStatus_compra) AS Quant_Vendas FROM carrinho_compra
                                                                 WHERE Status_compra_idStatus_compra in(1,2)";
                                            $result_qtd_vendas = $pdo->prepare($query_qtd_vendas);
                                            $result_qtd_vendas->execute();

                                            $row_qtd_vendas = $result_qtd_vendas->fetch(PDO::FETCH_ASSOC);
                                            extract($row_qtd_vendas);
                                        ?>
                                        <h3 class="fs-2 text-center"><?php echo $Quant_Vendas ?></h3>
                                        <p class="fs-5">Vendas</p>
                                    </div>
                                    <i
                                        class="fas fa-hand-holding-usd fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                                    <div>
                                        <?php
                                            ini_set('default_charset', 'utf-8');
                                            require_once '../class/conexao2.php';
                                            global $pdo;

                                            $query_qtd_cliente = "SELECT count(*) AS Quant_cli FROM cliente
                                                                    WHERE MONTH(Criado) = MONTH(NOW())";
                                            $result_qtd_cliente = $pdo->prepare($query_qtd_cliente);
                                            $result_qtd_cliente->execute();

                                            $row_qtd_cliente = $result_qtd_cliente->fetch(PDO::FETCH_ASSOC);
                                            extract($row_qtd_cliente);
                                        ?>
                                        <h3 class="fs-2 text-center"><?php echo $Quant_cli ?></h3>
                                        <p class="fs-5">Clientes do Mês</p>
                                    </div>
                                    <i class="bi bi-people-fill fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                                    <div>
                                        <?php
                                            ini_set('default_charset', 'utf-8');
                                            require_once '../class/conexao2.php';
                                            global $pdo;

                                            $query_qtd_vendas = "select concat(round(ifnull(sum(um),0) / ifnull(sum(tudo),0) * 100,0),'%') participacao 
                                                                    from(select 
                                                                    case when Status_compra_idStatus_compra in (1,2)
                                                                    then count(Status_compra_idStatus_compra) end um,
                                                                    case when Status_compra_idStatus_compra in (1,2,3,4)
                                                                    then count(Status_compra_idStatus_compra) end tudo
                                                                    from carrinho_compra
                                                                    WHERE WEEK(Data_Compra) = WEEK(NOW())
                                                                    group by Status_compra_idStatus_compra)b";

                                            $result_qtd_vendas = $pdo->prepare($query_qtd_vendas);
                                            $result_qtd_vendas->execute();

                                            $row_qtd_vendas = $result_qtd_vendas->fetch(PDO::FETCH_ASSOC);
                                            extract($row_qtd_vendas);
                                        ?>
                                        <h3 class="fs-2 text-center"><?php echo $participacao ?></h3>
                                        <p class="fs-5">Vendas Efetivadas na Semana</p>
                                    </div>
                                    <i class="fas fa-chart-line fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                                </div>
                            </div>
                        </div>
                        