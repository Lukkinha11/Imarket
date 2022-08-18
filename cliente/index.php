<?php

//session_start();
ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

require_once 'topo.php';

//aponta o diretório padrão do projeto
define("DIR", dirname(__FILE__)); 
define("DS", DIRECTORY_SEPARATOR);

//faço a inclusão do arquivo Loader
include_once DIR.DS.'App'.DS.'Loader.php';

//Instâncio o Loader
$loader = new App\Loader();
$loader->register();

$pdoo               = include_once ('../class/conexao2.php');
$Product_Repository = new App\Model\Product\Product_Repository_PDO($pdo);

$page   = isset($_GET['page']) ? $_GET['page'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
 
switch($page)
{
    
    case 'cart' :
        $sessionCart = new App\Model\Shopping\Cart_Session();
        $cart = new App\Controller\Cart($Product_Repository, $sessionCart);
        call_user_func_array(array($cart, $action), array());
    break;
    case 'contato' :
        include_once("contato.php");
    break;
    case 'login':
        include_once("login.php");
    break;
    case 'cad':
        include_once("cadastro.php");
    break;
    case 'confcad':
        include_once("confirmarcadastro.php");
    break;
    case 'confcont':
        include_once("confirmcontato.php");
    break;
    case 'sair':
        include_once("logout_cli.php");
    break;
    case 'confrecupsenha':
        include_once("confirmrecupsenha.php");
    break;
    case 'entrega':
        include_once("check_endereco.php");
    break;
    case 'buy':
        include_once("checkout_pag.php");
    break;
    case 'finish':
        include_once("finish.php");
    break;
    case 'recsenha':
        include_once("recuperarsenha.php");
    break;
    case 'cadnewsenha':
        include_once("cadastronovasenha.php");
    break;
    case 'confcadsenha':
        include_once("confirmcadastrosenha.php");
    break;
    case 'priv':
        include_once("privacidade.php");
    break;
    case 'termo':
        include_once("termos.php");
    break;
    case 'nos':
        include_once("quemsomos.php");
    break;
    case 'dev':
        include_once("trocas.php");
    break;
    case 'det':
        include_once("view_prod.php");
    break;

    default :        
      $home = new App\Controller\Home($Product_Repository);
        call_user_func_array(array($home, $action), array());
}        



require_once 'rodape.php';