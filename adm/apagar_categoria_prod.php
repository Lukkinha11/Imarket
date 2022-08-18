<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idCategoria = filter_input(INPUT_GET, "idCategoria", FILTER_SANITIZE_NUMBER_INT);

//$idMarca = "";

if(!empty($idCategoria))
{
    //Select para recuperar o caminho da pasta a ser deletada do sistema
    $query_caregoria_dir = "SELECT idCategoria, Categoria_diretorio, CONCAT('../img_prod/',Categoria_diretorio) AS Diretorio FROM categoria_prod
                            WHERE idCategoria=:idCategoria";
    
    $result_categoria_dir = $pdo->prepare($query_caregoria_dir);
    $result_categoria_dir->bindValue(":idCategoria", $idCategoria, PDO::PARAM_INT);
    $result_categoria_dir->execute();

    $row_dir = $result_categoria_dir->fetch(PDO::FETCH_ASSOC);
    extract($row_dir);

    $query_delete_categoria = "DELETE FROM categoria_prod WHERE idCategoria=:idCategoria";

   $result_categoria = $pdo->prepare($query_delete_categoria);
   $result_categoria->bindValue(":idCategoria", $idCategoria, PDO::PARAM_INT);

   try
   {
        if($result_categoria->execute())
        {
            
            function delTree($Diretorio) 
            { 
                $files = array_diff(scandir($Diretorio), array('.','..')); 
                foreach ($files as $file)
                { 
                  (is_dir("$Diretorio/$file")) ? delTree("$Diretorio/$file") : unlink("$Diretorio/$file"); 
                } 
                return rmdir($Diretorio); 
            }
            
            if(delTree($Diretorio))
            {
                $retorna = ['status' => true, 'msg' => 
                                "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>Sucesso!</strong> Categoria apagada com sucesso!
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>"
                            ];
            }
            else
            {
                $retorna = ['status' => false, 'msg' => 
                                "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                     Não foi possivel excluir o diretório da pasta 
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>"
                            ];
            }  
        }
   }
   catch(PDOException $e)
   {
        if( $e->getCode() == 23000)
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Não é possivel deletar o registro, pois o mesmo possui referencia em outra tabela</div>"];

        }else
        {
            $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Algo deu errado: ". $e->getMessage() ."</div>"];
        }
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Categoria não apagada com sucesso!</div>"];
}

echo json_encode($retorna);