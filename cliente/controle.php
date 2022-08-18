<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;
require_once 'topo.php';

if(isset($_GET['pag']))
{
        require_once 'menu.php';
        $pg = $_GET['pag'];
        switch($pg)
        {
                case 'dice':
                        include_once("cli_dados.php");
                break;
                case 'contact':
                        include_once("cli_contatos.php");
                break;
                case 'address':
                        include_once("cli_endereco.php");
                break;
                case 'pass':
                        include_once("cli_senha.php");
                break;
                case 'request':
                        include_once("cli_pedidos.php");
                break;                
                default:
                include_once("index.php");
                break;
        }
}else{
        require_once 'index.php';
}

require_once 'rodape.php';