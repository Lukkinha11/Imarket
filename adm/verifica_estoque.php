<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idCarrinho_Compra = filter_input(INPUT_GET, "idCarrinho_Compra", FILTER_SANITIZE_NUMBER_INT);
//$idMarca = "1000";

if(!empty($idCarrinho_Compra))
{
     
    $query_select_estoque = "SELECT Carrinho_Compra_idCarrinho_Compra, estoque.Produto_Id_Produto AS Id_produto, Nome_prod, Quant_prod, Quant_estoque
                            FROM itens_carrinho
                            INNER JOIN estoque
                            ON estoque.Produto_Id_Produto = itens_carrinho.Produto_Id_Produto
                            INNER JOIN produto
                            ON produto.id_Produto = itens_carrinho.Produto_Id_Produto
                            WHERE Carrinho_Compra_idCarrinho_Compra = $idCarrinho_Compra";

    $result_select_estoque = $pdo->prepare($query_select_estoque);
    $result_select_estoque->execute();
    $cont=0;
    $produto = array();

    $row = $result_select_estoque->rowCount();
   

    while ($dado_estoque = $result_select_estoque->fetch(PDO::FETCH_ASSOC)) 
    {
        extract($dado_estoque);
        if($Quant_prod > $Quant_estoque)
        {            
            array_push($produto, $Nome_prod);
            $cont++;       
        }
    }
    //var_dump($array);
    //print_r($produto);
    //$retorna = ['status' => false, 'info' => $produto];

    if($cont > 0 && $cont != $row)
    {
        //O código abaixo retorna false para o javascript e o mesmo abre a modal dando as opções de cancelar a compra ou atender parcialmente
        $result_select_estoque->execute();

        if($id_estoque = $result_select_estoque->fetch(PDO::FETCH_ASSOC))
        {
            $retorna = ['status' => false, 'dados' => $id_estoque];
        }        
    }
    elseif($cont == $row)
    {
        //Ao entrar nessa validação assume-se que na compra do cliente não há estoque para o(s) produto(s) e o sistema cancela a compra
        try
        {
            $query_upd_carrinho = "UPDATE carrinho_compra SET Status_compra_idStatus_compra = 3 WHERE idCarrinho_Compra = $idCarrinho_Compra";
                                                                                           // 3 = Pedido Cancelado
           
            $result_upd_carrinho = $pdo->prepare($query_upd_carrinho);
            if($result_upd_carrinho->execute())
            {
                try
                {
                    $query_upd_itens_carrinho = "UPDATE itens_carrinho SET Status_item_idStatus_item = 2 WHERE Carrinho_Compra_idCarrinho_Compra = $idCarrinho_Compra";
                                                                                                    // 2 = Item não Atendido
                    
                    
                    $result_upd_itens_carrinho = $pdo->prepare($query_upd_itens_carrinho);
                    if($result_upd_itens_carrinho->execute())
                    {
                        $retorna = ['status' => true, 'msg' =>  
                                        "<div id='exeption' class='alert alert-danger alert-dismissible fade show' role='alert'>
                                            <strong>ATENÇÃO:</strong> Carrinho cancelado por não haver estoque suficiente! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>"
                                    ];
                    }
                }
                catch(PDOException $e)
                {
                    $pdo->rollBack();
                    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no update do itens_carrinho#1: ". $e->getMessage() ."</div>"];
                }
            }           
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no update carrinho_compra#1: ". $e->getMessage() ."</div>"];
        }
    }
    elseif($cont == 0)
    {
        //Vai executar a transação abaixo quando houver disponibilidade de estoque para todos os produtos 
        $pdo->beginTransaction();
        try
        {
            $query_upd_carrinho = "UPDATE carrinho_compra SET Status_compra_idStatus_compra = 1 WHERE idCarrinho_Compra = $idCarrinho_Compra";
                                                                                            //1 Pedido Atendido
            
            $result_upd_carrinho = $pdo->prepare($query_upd_carrinho);

            if($result_upd_carrinho->execute())
            {
                try
                {
                    $query_upd_itens_carrinho = "UPDATE itens_carrinho SET Status_item_idStatus_item = 1 WHERE Carrinho_Compra_idCarrinho_Compra = $idCarrinho_Compra";
                                                                                                     //1 Item Atendido
                    
                    $result_upd_itens_carrinho = $pdo->prepare($query_upd_itens_carrinho);

                    if($result_upd_itens_carrinho->execute())
                    {    
                        try
                        {
                            $result_select_estoque->execute();
                            while ($estoque = $result_select_estoque->fetch(PDO::FETCH_ASSOC)) 
                            {
                                extract($estoque);

                                $query_update_estoque = "UPDATE estoque 
                                                        SET Quant_estoque = Quant_estoque - $Quant_prod
                                                        WHERE Produto_Id_Produto = $Id_produto";

                                $updade_estoque = $pdo->prepare($query_update_estoque);
                                $updade_estoque->execute();
                            }
                            $pdo->commit();
                            $retorna = ['status' => true, 'msg' =>  
                                            "<div id='exeption' class='alert alert-success alert-dismissible fade show' role='alert'>
                                                <strong>Sucesso!</strong> Compra efetivada com sucesso! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                            </div>"
                                        ];
                        }
                        catch(PDOException $e)
                        {
                            $pdo->rollBack();
                            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no update do estoque: ". $e->getMessage() ."</div>"];
                        }               
                    }
                }
                catch(PDOException $e)
                {
                    $pdo->rollBack();
                    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no update do itens_carrinho#1: ". $e->getMessage() ."</div>"];
                }
            }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no update carrinho_compra#1: ". $e->getMessage() ."</div>"];
        }
    }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Não é possivel verificar disponibilidade do produto(s) no estoque!</div>"];
}

echo json_encode($retorna);