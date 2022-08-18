<?php
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$post = json_decode($_POST['tabela'], true);
/*var_dump($_POST['num_nf']); //Número da nota Fiscal
var_dump($_POST['serie_nf']); //Serie da NOta Fiscal
var_dump($_POST['valor_nf']); //Valor da Nota Fiscal
var_dump($_POST['data_nf']); //Data da Nota Fiscal
var_dump($_POST['desc_nf']); //Descriçao da Nota Fiscal
var_dump($_POST['id_fornecedor']); //Id do Fornecedor
var_dump($_POST['pagamento']); //ID do pagamento
var_dump($_POST['parcelas']); //ID das parclas

var_dump($post);

foreach($post as $v)
{
    if(is_string($v))
    {
        $a = json_decode($v);
        echo $a->Quantidade;
        echo $a->ID;
    }
}

echo "VAI DA CERTO";*/



$parcela = $_POST['parcelas'];

$brl = $_POST['valor_nf'];

$b = str_replace('.', '', $brl);
$b = str_replace(',', '.', $b);

//die;

$pdo->beginTransaction();

try
{
    $query_compra_fornecedor = "INSERT INTO compra_fornecedor (Descricao_NF, Numero_NF, Serie_NF, Valor_NF, Data_Compra, Fornecedor_idFornecedor, Forma_Pagamento_idForma_Pag) 
                                                                VALUES (:Descricao_NF, :Numero_NF, :Serie_NF, :Valor_NF, :Data_Compra, :Fornecedor_idFornecedor, :Forma_Pagamento_idForma_Pag)";

    $cad_compra_fornecedor = $pdo->prepare($query_compra_fornecedor);
    $cad_compra_fornecedor->bindValue(':Descricao_NF',$_POST['desc_nf'], PDO::PARAM_STR);
    $cad_compra_fornecedor->bindValue(':Numero_NF',$_POST['num_nf'], PDO::PARAM_STR);
    $cad_compra_fornecedor->bindValue(':Serie_NF',$_POST['serie_nf'], PDO::PARAM_STR);
    $cad_compra_fornecedor->bindValue(':Valor_NF', $b, PDO::PARAM_STR);
    $cad_compra_fornecedor->bindValue(':Data_Compra',$_POST['data_nf'], PDO::PARAM_STR);
    $cad_compra_fornecedor->bindValue(':Fornecedor_idFornecedor',$_POST['id_fornecedor'], PDO::PARAM_INT);
    $cad_compra_fornecedor->bindValue(':Forma_Pagamento_idForma_Pag',$_POST['pagamento'], PDO::PARAM_INT);

    if($cad_compra_fornecedor->execute())
    {
        $id_compra_fornecedor = $pdo->lastInsertId();

        try
        {
            foreach($post as $v)
            {                
                if(is_string($v))
                {
                    $a = json_decode($v);

                    $query_itens_compra = "INSERT INTO itens_compra_fornecedor(Quantidade_Compra, Valor_unit, Compra_Fornecedor_idCompra_fornecedor, Produto_Id_Produto)
                                                                        VALUES(:Quantidade_Compra, :Valor_unit, :Compra_Fornecedor_idCompra_fornecedor, :Produto_Id_Produto)";

                    $cad_itens_compra = $pdo->prepare($query_itens_compra);
                    $cad_itens_compra->BindValue(':Quantidade_Compra',$a->Quantidade, PDO::PARAM_STR);
                    $cad_itens_compra->BindValue(':Valor_unit',$a->Valor, PDO::PARAM_STR);
                    $cad_itens_compra->BindValue(':Compra_Fornecedor_idCompra_fornecedor', $id_compra_fornecedor, PDO::PARAM_INT);
                    $cad_itens_compra->BindValue(':Produto_Id_Produto',$a->ID, PDO::PARAM_INT);
                    
                    $cad_itens_compra->execute();

                    $query_update_estoque = "UPDATE estoque 
                                            INNER JOIN produto
                                            ON produto.id_Produto = estoque.Produto_Id_Produto
                                            SET Quant_estoque = Quant_estoque + $a->Quantidade
                                            WHERE Produto_Id_Produto = $a->ID";

                    $updade_estoque = $pdo->prepare($query_update_estoque);
                    $updade_estoque->execute();
                }
            }
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();
            echo "<div class='alert alert-danger' role='alert'>Algo deu errado no itens_compra_fornecedor PDOException: ". $e->getMessage() ."</div>";
        }
        try
        {
            $soma = 0;
            $val_press = 0;
            $br = $b;

            for($x = 1;  $x<=$parcela; $x++)
            {   
                $query_contas_pagar = "INSERT INTO contas_pagar(Valor, Parcela, Compra_Fornecedor_idCompra_fornecedor, Data_Venc)
                                            VALUES(:Valor, $x, :Compra_Fornecedor_idCompra_fornecedor, CURDATE() + INTERVAL 30 * $x DAY)";

                if($x < $parcela)
                {
                    $val_press =  round($br / $parcela,2) ;
                    $soma += $val_press;
                }
                else
                {
                    $val_press = $br - $soma;
                }
                
                $cad_contas_pagar = $pdo->prepare($query_contas_pagar);
                $cad_contas_pagar->bindValue(':Valor', $val_press, PDO::PARAM_STR);
                $cad_contas_pagar->bindValue(':Compra_Fornecedor_idCompra_fornecedor', $id_compra_fornecedor, PDO::PARAM_INT);

                $cad_contas_pagar->execute();
            }
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <strong>Sucesso!</strong> Cadastro efetuado com sucesso!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                 </div>";
        }
        catch(PDOException $e)
        {
            $pdo->rollBack();
            echo "<div class='alert alert-danger' role='alert'>Algo deu errado no contas_pagar PDOException: ". $e->getMessage() ."</div>";
        }
    }
}
catch(PDOException $e)
{
    $pdo->rollBack();
    echo "<div class='alert alert-danger' role='alert'>Algo deu errado no compra_fornecedor PDOException: ". $e->getMessage() ."</div>";
}

$pdo->commit();