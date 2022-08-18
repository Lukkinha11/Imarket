<?php

namespace App\Model\Product;

class Product_Repository_PDO implements Product_Repository
{
    private $pdo;
    
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getProducts()
    {
        $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
        $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

        //Definir a quantidade de registros por pagina
        $limite_resultado = 24;

        //Calcular o início da visualização
        $inicio = ($limite_resultado * $pagina) - $limite_resultado;

        if(!isset($_GET['idcat']) AND !isset($_GET['filtro_prod'])) 
        {
            $stmt = $this->pdo->prepare("SELECT id_Produto, Nome_prod, Desc_prod, Image, Categoria_Prod_idCategoria, Categoria, Categoria_diretorio, Valor_prod, Valor_novo, Sigla FROM produto
                                        INNER JOIN preco
                                        ON preco.Produto_Id_Produto = produto.id_Produto
                                        INNER JOIN categoria_prod
                                        ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                                        INNER JOIN unidade_medidas
                                        ON unidade_medidas.idUnidade_medidas = produto.unidade_medidas_idUnidade_medidas
                                         ORDER BY Nome_prod
                                         LIMIT $inicio,  $limite_resultado");

            $stmt->setFetchMode(\PDO::FETCH_CLASS, 'App\Model\Product\Product');
            $stmt->execute();
            return $stmt->fetchAll();
        } 
        elseif(isset($_GET['filtro_prod']))
        {        
            if($_GET['filtro_prod'] == "Nome_prod")
            {
                //echo "Nome_prod";

                $stmt = $this->pdo->prepare("SELECT id_Produto, Nome_prod, Desc_prod, Image, Categoria_Prod_idCategoria, Categoria, Categoria_diretorio, Valor_prod, Valor_novo, Sigla FROM produto
                                            INNER JOIN preco
                                            ON preco.Produto_Id_Produto = produto.id_Produto
                                            INNER JOIN categoria_prod
                                            ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                                            INNER JOIN unidade_medidas
                                            ON unidade_medidas.idUnidade_medidas = produto.unidade_medidas_idUnidade_medidas
                                            ORDER BY Nome_prod ASC
                                            LIMIT $inicio,  $limite_resultado");

                $stmt->setFetchMode(\PDO::FETCH_CLASS, 'App\Model\Product\Product');
                $stmt->execute();
                return $stmt->fetchAll();

            }
            elseif($_GET['filtro_prod'] == "DESC")
            {
                //echo "DESC";

                $stmt = $this->pdo->prepare("SELECT id_Produto, Nome_prod, Desc_prod, Image, Categoria_Prod_idCategoria, Categoria, Categoria_diretorio, Valor_prod, Valor_novo, Sigla FROM produto
                                            INNER JOIN preco
                                            ON preco.Produto_Id_Produto = produto.id_Produto
                                            INNER JOIN categoria_prod
                                            ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                                            INNER JOIN unidade_medidas
                                            ON unidade_medidas.idUnidade_medidas = produto.unidade_medidas_idUnidade_medidas
                                            ORDER BY Valor_novo DESC 
                                            LIMIT $inicio,  $limite_resultado");

                $stmt->setFetchMode(\PDO::FETCH_CLASS, 'App\Model\Product\Product');
                $stmt->execute();
                return $stmt->fetchAll();
            }
            elseif($_GET['filtro_prod'] == "ASC")
            {
                //echo "ASC";

                $stmt = $this->pdo->prepare("SELECT id_Produto, Nome_prod, Desc_prod, Image, Categoria_Prod_idCategoria, Categoria, Categoria_diretorio, Valor_prod, Valor_novo, Sigla FROM produto
                                            INNER JOIN preco
                                            ON preco.Produto_Id_Produto = produto.id_Produto
                                            INNER JOIN categoria_prod
                                            ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                                            INNER JOIN unidade_medidas
                                            ON unidade_medidas.idUnidade_medidas = produto.unidade_medidas_idUnidade_medidas
                                            ORDER BY Valor_novo ASC 
                                            LIMIT $inicio,  $limite_resultado");

                $stmt->setFetchMode(\PDO::FETCH_CLASS, 'App\Model\Product\Product');
                $stmt->execute();
                return $stmt->fetchAll();
            }   
        }
        else
        {
            $stmt = $this->pdo->prepare("SELECT id_Produto, Nome_prod, Desc_prod, Image, Categoria_Prod_idCategoria, Categoria, Categoria_diretorio, Valor_prod, Valor_novo, Sigla FROM produto
                                        INNER JOIN preco
                                        ON preco.Produto_Id_Produto = produto.id_Produto
                                        INNER JOIN categoria_prod
                                        ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                                        INNER JOIN unidade_medidas
                                        ON unidade_medidas.idUnidade_medidas = produto.unidade_medidas_idUnidade_medidas
                                         AND Categoria_Prod_idCategoria=" . $_GET['idcat'] . " 
                                         ORDER BY Valor_novo
                                         LIMIT $inicio,  $limite_resultado");
            $stmt->setFetchMode(\PDO::FETCH_CLASS, 'App\Model\Product\Product');
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    public function getProduct($id)
    {
        $stmt = $this->pdo->prepare("SELECT id_Produto, Nome_prod, Desc_prod, Image, Categoria_Prod_idCategoria, Categoria, Categoria_diretorio, Valor_prod, Valor_novo, Sigla FROM produto
                                    INNER JOIN preco
                                    ON preco.Produto_Id_Produto = produto.id_Produto
                                    INNER JOIN categoria_prod
                                    ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
                                    INNER JOIN unidade_medidas
                                    ON unidade_medidas.idUnidade_medidas = produto.unidade_medidas_idUnidade_medidas
                                     WHERE id_Produto = :id");
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'App\Model\Product\Product');
        $stmt->execute();
        return $stmt->fetch();
    }
}