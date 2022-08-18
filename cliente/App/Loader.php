<?php

namespace App;

//A calsse Loader chama as classes quando forem instânciadas
class Loader
{
    //Função responsável por registrar as informações do autoload
    public function register()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    //Função responsável por chamar as classes dos arquivos apartir do diretorio padrão
    public function autoload($class)
    {
        $class = DIR.DS.str_replace("\\", DS, $class). '.php';
        include_once $class;
    }
}


?>