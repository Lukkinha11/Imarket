 <?php
 ob_start();
 require_once '../class/conexao2.php';
 require_once 'topo.php';
 global $pdo;
 

 $chave = filter_input(INPUT_GET, "chave", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

 if(!empty($chave))
 {
    $query_chave_cli = "SELECT idCliente FROM cliente WHERE chave=:chave LIMIT 1";
    $result_chave_cli = $pdo->prepare($query_chave_cli);
    $result_chave_cli->bindParam(':chave', $chave, PDO::PARAM_STR);
    $result_chave_cli->execute();

    if(($result_chave_cli) AND ($result_chave_cli->rowCount() !=0))
    {
        $row_chave_cli = $result_chave_cli->fetch(PDO::FETCH_ASSOC);
        extract($row_chave_cli);

        $query_update_cli = "UPDATE cliente SET Situacao_cliente_idSituacao_cliente = 1, Chave =:chave WHERE idCliente = $idCliente ";
        $update_cli = $pdo->prepare($query_update_cli);
        $chave = NULL;
        $update_cli->bindParam(':chave', $chave, PDO::PARAM_STR);
        if($update_cli->execute())
        {
            ?>
                <main class="flex-fill">
                    <div class="container">
                        <h2>Cadastro Efetuado Com Sucesso!</h2>
                        <hr>
                        <p>
                            Caro(a) Cliente <strong></strong>
                        </p>
                        <p>
                            Seu cadastro foi efetuado com sucesso, faça o login em nosso site e aproveite o máximo em nossas ofertas!
                        </p>
                        <p>
                            Desde já, agradecemos pela confiança e BOAS COMPRAS!!♥
                        </p>
                        <p>
                            Atenciosamente,
                        </p>
                        <p>
                            <b>
                                Central de Relacionamento iMarket.
                            </b>
                        </p>
                        <p>
                            <a href="index.php?page=index.php" class="btn btn-primary">Voltar à Página Principal</a>
                        </p>
                    </div>
                </main>

            <?php
        }
        else
        {
            $_SESSION['alert'] = "<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                                    <strong>ATENÇÃO!</strong> Email não Confirmado!
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
            header("Location: index.php");
        }

    }
    else
    {
        $_SESSION['alert'] = " <div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
									<strong>ATENÇÃO!</strong> Endereço inválido!
									<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
								</div>";
        header("Location: index.php");
    }

 }/*else
 {
     $_SESSION['alert'] = "<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                                <strong>ERRO! Endereço</strong>
                            </div>";
     header("Location: index.php");
 }*/

 ?>
 
     <!--                 
    <div style="height: 273px" class="d-block d-md-none"></div>
    <div style="height: 153px" class="d-none d-md-block d-lg-none"></div>
    <div style="height: 129px" class="d-none d-lg-block"></div>
    -->  

<?php 
require_once 'rodape.php';
?>