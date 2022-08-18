<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idCarrinho_Compra = $_POST['idcarrinho'];

if(isset($_POST['parcialmente']))
{
    $pdo->beginTransaction();

    $query_select_estoque = "SELECT Carrinho_Compra_idCarrinho_Compra, estoque.Produto_Id_Produto AS Id_produto, Nome_prod, Quant_prod, Quant_estoque
                            FROM itens_carrinho
                            INNER JOIN estoque
                            ON estoque.Produto_Id_Produto = itens_carrinho.Produto_Id_Produto
                            INNER JOIN produto
                            ON produto.id_Produto = itens_carrinho.Produto_Id_Produto
                            WHERE Carrinho_Compra_idCarrinho_Compra = $idCarrinho_Compra";

    $result_select_estoque = $pdo->prepare($query_select_estoque);
    $result_select_estoque->execute();
    while ($dado_estoque = $result_select_estoque->fetch(PDO::FETCH_ASSOC)) 
    {
        extract($dado_estoque);
        //Ao entrar no if abaixo o sistema irá da baixa nos itens do carrinho como atendido e dá baixa no estoque de acordo com a quantidade comprada
        if($Quant_prod <= $Quant_estoque)
        {                  
            try
            {
                $query_upd_itens_carrinho = "UPDATE itens_carrinho SET Status_item_idStatus_item = 1 WHERE Carrinho_Compra_idCarrinho_Compra = $idCarrinho_Compra AND Produto_Id_Produto = $Id_produto";
                                                                                                 //1 Pedido Atendido
                $result_upd_itens_carrinho = $pdo->prepare($query_upd_itens_carrinho);

                if($result_upd_itens_carrinho->execute())
                {
                    try
                    {
                        $query_update_estoque = "UPDATE estoque 
                                                SET Quant_estoque = Quant_estoque - $Quant_prod
                                                WHERE Produto_Id_Produto = $Id_produto";

                        $updade_estoque = $pdo->prepare($query_update_estoque);
                        $updade_estoque->execute();   
                    }
                    catch(PDOException $e)
                    {
                        $pdo->rollBack();
                       echo "<div class='alert alert-danger' role='alert'>Algo deu errado no update do estoque: ". $e->getMessage() ."</div>";
                    }
                }
            }
            catch(PDOException $e)
            {
                $pdo->rollBack();
                echo "<div class='alert alert-danger' role='alert'>Algo deu errado no update do itens_carrinho: ". $e->getMessage() ."</div>";
            }
        }
        //Ao entrar no elseif abaixo o sistema irá da baixa apenas nos itens que não tem estoque suficiente 
        elseif($Quant_prod > $Quant_estoque)
        {     
            try
            {
                $query_upd_itens_carrinho = "UPDATE itens_carrinho SET Status_item_idStatus_item = 2 WHERE Carrinho_Compra_idCarrinho_Compra = $idCarrinho_Compra AND Produto_Id_Produto = $Id_produto";
                                                                                                // 2 = Item Não Atendido
                $result_upd_itens_carrinho = $pdo->prepare($query_upd_itens_carrinho);

                $result_upd_itens_carrinho->execute();
            }
            catch(PDOException $e)
            {
                $pdo->rollBack();
                echo "<div class='alert alert-danger' role='alert'>Algo deu errado no update do itens_carrinho: ". $e->getMessage() ."</div>";
            }   
        }
    }
    try
    {
        //Depois te ter feito das validações no if e no elseif o sistema ira atualizar o carrinho de compra do cliente como Pedido Atendido Parcialmente
        
        $query_upd_carrinho = "UPDATE carrinho_compra SET Status_compra_idStatus_compra = 2 WHERE idCarrinho_Compra = $idCarrinho_Compra";
                                                                                        //2 Pedido Atendido Parcialmente    
        $result_upd_carrinho = $pdo->prepare($query_upd_carrinho);
        $result_upd_carrinho->execute();

        $pdo->commit();

        echo "<div id='exeption' class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Sucesso:</strong> Baixa parcial no estoque efetuada com sucesso! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";           
    }
    catch(PDOException $e)
    {
        $pdo->rollBack();
        echo "<div class='alert alert-danger' role='alert'>Algo deu errado no update carrinho_compra: ". $e->getMessage() ."</div>";
    }
}
elseif(isset($_POST['cancelar']))
{
    //Caso o cliente desejar cancelar a compra por não haver estoque suficiente para atende-lo o sistema irá dá baixa nos itens do carrinho como Item Não Atendido, e irá cancelar a compra do cliente
    
    $pdo->beginTransaction();

    $query_select_estoque = "SELECT Carrinho_Compra_idCarrinho_Compra, estoque.Produto_Id_Produto AS Id_produto, Nome_prod, Quant_prod, Quant_estoque
                            FROM itens_carrinho
                            INNER JOIN estoque
                            ON estoque.Produto_Id_Produto = itens_carrinho.Produto_Id_Produto
                            INNER JOIN produto
                            ON produto.id_Produto = itens_carrinho.Produto_Id_Produto
                            WHERE Carrinho_Compra_idCarrinho_Compra = $idCarrinho_Compra";

    $result_select_estoque = $pdo->prepare($query_select_estoque);

    $result_select_estoque->execute();

    while ($dado_estoque = $result_select_estoque->fetch(PDO::FETCH_ASSOC)) 
    {
        extract($dado_estoque);
        try
        {
            $query_upd_itens_carrinho = "UPDATE itens_carrinho SET Status_item_idStatus_item = 2 WHERE Carrinho_Compra_idCarrinho_Compra = $idCarrinho_Compra";
                                                                                                //2 Item Não Atendido                              
            $result_upd_itens_carrinho = $pdo->prepare($query_upd_itens_carrinho);

            $result_upd_itens_carrinho->execute();
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();
            echo "<div class='alert alert-danger' role='alert'>Algo deu errado no update do itens_carrinho: ". $e->getMessage() ."</div>";
        }
    }
    try
    {
        $query_upd_carrinho = "UPDATE carrinho_compra SET Status_compra_idStatus_compra = 3 WHERE idCarrinho_Compra = $idCarrinho_Compra";
                                                                                        //3 Pedido Cancelado
        $result_upd_carrinho = $pdo->prepare($query_upd_carrinho);
        $result_upd_carrinho->execute();

        $pdo->commit();

        echo "<div id='exeption' class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Atenção:</strong> Compra cancelada com sucesso por preferência do cliente! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";           
    }
    catch(PDOException $e)
    {
        $pdo->rollBack();
        echo "<div class='alert alert-danger' role='alert'>Algo deu errado no update carrinho_compra: ". $e->getMessage() ."</div>";
    }
}