<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$nome_marca = filter_input(INPUT_GET, "Desc_Marca", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if(!empty($nome_marca))
{
    $pesq_marca = "%" . $nome_marca . "%";
    
    $query_marca = "SELECT idMarca, Desc_Marca FROM marca 
                    WHERE Desc_Marca LIKE :Desc_Marca LIMIT 20";
    
    $result_marca = $pdo->prepare($query_marca); 
    $result_marca->bindValue(':Desc_Marca', $pesq_marca, PDO::PARAM_STR);
    $result_marca->execute();

    if(($result_marca) AND ($result_marca->rowCount() !=0))
    {
        while($row_marca = $result_marca->fetch(PDO::FETCH_ASSOC))
        {
            $dados[] = [
                'idMarca' => $row_marca['idMarca'],
                'Desc_Marca' => $row_marca['Desc_Marca']
            ];
        }

        $retorna = ['erro' => false, 'dados' => $dados];
    }
    else
    {
        $retorna = ['erro' => true, 'msg' => "Nenhuma marca encontrada"];
    }
}
else
{
    $retorna = ['erro' => true, 'msg' => "Nenhuma marca encontrada"];
}

echo json_encode($retorna);
