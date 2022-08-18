<?php

namespace App\Model\Product;

class Product{

    private $id_Produto;
    private $Nome_prod;
    private $Valor_novo;
    private $Image;
    private $Desc_prod;
    private $Categoria_diretorio;
    private $Sigla;

    public function setId($id_Produto)
    {
        
        if(!is_int($id_Produto))
        {
            throw new \InvalidArgumentException("O id precisa ser inteiro");
        }
        $this->id_Produto = $id_Produto;
    }

    public function setName($Nome_prod){
        
        if(empty($Nome_prod))
        {
            throw new \InvalidArgumentException("É obrigatório ter o nome");
        }
        $this->Nome_prod = $Nome_prod;
    }

    public function setPrice($Valor_novo){
        
        if(!is_float($Valor_novo))
        {
            throw new \InvalidArgumentException("O precço precisa ser um float");
        }
        $this->Valor_novo = $Valor_novo;
    }

    public function setImage($Image){
        
        if(empty($Image))
        {
            throw new \InvalidArgumentException("É obrigatório ter uma imagem");
        }
        $this->Image = $Image;
    }

    public function setDesc($Desc_prod){
        
        if(empty($Desc_prod))
        {
            throw new \InvalidArgumentException("É obrigatório ter uma descrição");
        }
        $this->Desc_prod = $Desc_prod;
    }

    public function setCategoria($Categoria_diretorio){
        
        if(empty($Categoria_diretorio))
        {
            throw new \InvalidArgumentException("É obrigatório ter uma categoria");
        }
        $this->Categoria_diretorio = $Categoria_diretorio;
    }

    public function setSigla($Sigla)
    {    
        if(empty($Sigla))
        {
            throw new \InvalidArgumentException("Erro");
        }
        $this->Sigla = $Sigla;
    }

    public function getId()
    {
        return $this->id_Produto;
    }

    public function getName()
    {
        return $this->Nome_prod;
    }

    public function getPrice()
    {
        return $this->Valor_novo;
    }

    public function getImage()
    {
        return $this->Image;
    }

    public function getDesc()
    {
        return $this->Desc_prod;
    }

    public function getCategoria_diretorio()
    {
        return $this->Categoria_diretorio;
    }

    public function getSigla()
    {
        return $this->Sigla;
    }
}

