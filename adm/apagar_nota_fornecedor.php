<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idCompra_fornecedor = filter_input(INPUT_GET, "idCompra_fornecedor", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idCompra_fornecedor))
{
    $query_estoque = "SELECT idCompra_fornecedor, Nome_prod, e.Produto_Id_Produto AS id_prod, Quantidade_Compra, Quant_estoque
                        FROM compra_fornecedor,
                            itens_compra_fornecedor icf,
                            estoque e,
                            produto p
                        WHERE idCompra_fornecedor = $idCompra_fornecedor
                        AND idCompra_fornecedor = Compra_Fornecedor_idCompra_fornecedor
                        AND icf.Produto_Id_Produto = e.Produto_Id_Produto
                        AND e.Produto_Id_Produto = p.id_Produto";

    $result_estoque = $pdo->prepare($query_estoque);
    $result_estoque->execute();
    $cont = 0;
    while ($dado_estoque = $result_estoque->fetch(PDO::FETCH_ASSOC)) 
    {
        extract($dado_estoque);
        if($Quantidade_Compra > $Quant_estoque)
        {
            $retorna = ['status' => false, 'msg' =>  
                            "<div id='exeption' class='alert alert-danger alert-dismissible fade show' role='alert'>
                                <strong>Erro:</strong> A nota Fiscal não pode ser excluída! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>"
                        ];
            $cont++;
        }
    }

    if($cont == 0)
    {
        try
        {
            while ($row_estoque = $result_estoque->fetch(PDO::FETCH_ASSOC)) 
            {
                extract($row_estoque);

                $query_update_estoque = "UPDATE estoque 
                                        INNER JOIN produto
                                        ON produto.id_Produto = estoque.Produto_Id_Produto
                                        SET Quant_estoque = Quant_estoque - $Quantidade_Compra
                                        WHERE Produto_Id_Produto = $id_prod";

                $updade_estoque = $pdo->prepare($query_update_estoque);
                $updade_estoque->execute();
            }
        }
        catch(PDOException $e)
        { 
            $pdo->rollBack();
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no update do estoque: ". $e->getMessage() ."</div>"];  
        }

        $pdo->beginTransaction();

        try
        {
                $query_delete_contas_pagar = "DELETE FROM contas_pagar WHERE Compra_Fornecedor_idCompra_fornecedor =:Compra_Fornecedor_idCompra_fornecedor";
                $result_delete_contas_pagar = $pdo->prepare($query_delete_contas_pagar);
                $result_delete_contas_pagar->bindValue(":Compra_Fornecedor_idCompra_fornecedor", $idCompra_fornecedor , PDO::PARAM_INT);

                if($result_delete_contas_pagar->execute())
                {
                    try
                    {
                        $query_delete_itens_fornecedor = "DELETE FROM itens_compra_fornecedor WHERE Compra_Fornecedor_idCompra_fornecedor =:Compra_Fornecedor_idCompra_fornecedor";
                        $result_delete_itens_fornecedor = $pdo->prepare($query_delete_itens_fornecedor);
                        $result_delete_itens_fornecedor->bindValue(":Compra_Fornecedor_idCompra_fornecedor", $idCompra_fornecedor, PDO::PARAM_INT);

                        if($result_delete_itens_fornecedor->execute())
                        {
                            try
                            {
                                $query_delete_compra_fornecedor = "DELETE FROM compra_fornecedor WHERE idCompra_fornecedor =:idCompra_fornecedor";
                                $result_delete_compra_fornecedor = $pdo->prepare($query_delete_compra_fornecedor);
                                $result_delete_compra_fornecedor->bindValue(":idCompra_fornecedor", $idCompra_fornecedor, PDO::PARAM_INT);

                                if($result_delete_compra_fornecedor->execute())
                                {
                                    $pdo->commit();
                                    $retorna = ['status' => true, 'msg' => 
                                                    "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                                        <strong>Sucesso!</strong> Nota Fiscal apagada com sucesso!
                                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                                    </div>"
                                                ];
                                }
                            }
                            catch(PDOException $e)
                            {
                                $pdo->rollBack();
                                $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no compra_fornecedor: ". $e->getMessage() ."</div>"];  
                            }
                        }
                    }
                    catch(PDOException $e)
                    {
                        $pdo->rollBack();
                        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no itens_fornecedor: ". $e->getMessage() ."</div>"];  
                    }
                }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();    
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado no update do estoque: ". $e->getMessage() ."</div>"];  
        }
    }   
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>#1 Nota Fiscal não apagada com sucesso!</div>"];
}

echo json_encode($retorna);