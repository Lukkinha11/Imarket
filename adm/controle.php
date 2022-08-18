<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

require_once 'topo.php';


if(isset($_GET['page']))
{
    //require_once 'menu.php';
    $pg = $_GET['page'];
    switch($pg)
    {
        case 'prod':
            include_once("manter_produto.php");
        break;
        case 'venda':
            include_once("vizualizar_venda_cli.php");
        break;
        case 'tipo_log':
            include_once("manter_tipo_logradouro.php");
        break;
        case 'cat_prod':
            include_once("manter_categoria_prod.php");
        break;  
        case 'marca':
            include_once("manter_marca.php");
        break; 
        case 'status':
            include_once("manter_status_entrega.php");
        break;
        case 'sit_cli':
            include_once("manter_situacao_cliente.php");
        break; 
        case 'cargo':
            include_once("manter_cargo.php");
        break;  
        case 'desc_pag':
            include_once("manter_desc_pag.php");
        break; 
        case 'forma_pag':
            include_once("manter_forma_pag.php");
        break; 
        case 'preco':
            include_once("manter_preco.php");
        break;
        case 'func':
            include_once("manter_funcionarios.php");
        break;     
        case 'dador':
            include_once("manter_fornecedor.php");
        break;   
        case 'stock':
            include_once("manter_estoque.php");
        break;
        case 'upd_stock':
            include_once("manter_disponibilidade_estoque.php");
        break;
        case 'medida':
            include_once("manter_unidade_medida.php");
        break;
        case 'access':
            include_once("manter_acesso.php");
        break;
        case 'buy':
            include_once("manter_status_compra.php");
        break;
        case 'item':
            include_once("manter_status_item.php");
        break;
        case 'sair':
            include_once("logout.php");
        break;
        case 'dash':
            include_once("dashboard.php");
        break;
        case 'my_account':
            include_once("manter_profile.php");
        break;
        case 'cli':
            include_once("manter_cliente.php");
        break;
        case 'report':
            include_once("manter_relatorio_vendas.php");
        break;              
        default:
        include_once("index.php");
        break;
    }
}else
{
    require_once 'index.php';
}

require_once 'rodape.php';