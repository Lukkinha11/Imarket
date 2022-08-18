    <?php
        ob_start();
        ini_set('default_charset','utf-8');
        require_once '../class/conexao2.php';
        global $pdo;

        if(!isset($_SESSION['id']) AND !isset($_SESSION['nome']))
        {
                header("Location: index.php");
                exit();                    
        }else
        {
            $userid = $_SESSION['id'];
            $query_dados = "SELECT idCliente, Nome, Sobrenome, Cep, Logradouro, Bairro, Cidade, Estado, Complemento, Referencia 
                            FROM cliente,
                                endereco
                            WHERE cliente.Endereco_idEndereco = endereco.idEndereco
                            AND idCliente ='".$userid."' LIMIT 1";
            $result_cli = $pdo->prepare($query_dados);
            $result_cli->execute();

            $row_cli = $result_cli->fetch(PDO::FETCH_ASSOC);
            
            extract($row_cli);
                                    
        }
    ?>
 <main class="flex-fill">
     <div class="container">
        <form action="" method="POST">
            <h2>Selecione o Endereço de Entrega</h2>
            <br>
            <h3 class="mb-4">
                Selecione o endereço de entrega e clique em <b>Continuar</b> para
                prosseguir para a <b>selecão da forma de pagamento.</b>
            </h3>
            <?php
                $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                //var_dump($data);

                if(!empty($data))
                {

                    if(!isset($_POST['endereco']))
                    {
                        echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                        <strong>ATENÇÃO!</strong> Selecione o Endereço de Entrega!
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                    }
                    elseif($Estado != "DF")
                    {
                        echo"<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                                        <strong>OPS 💔!</strong> Infelismente ainda não entregamos em sua região, estamos trabalhando o mais rápido para expandir!
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                    }
                    else
                    {
                        header("Location: ?page=buy");
                    }
                }

            ?>
            <div class="d-flex justify-content-around flex-wrap border rounded-top pt-4 px-3">
                <div class="mb-4 mx-2">
                    <input type="radio" class="btn-check" name="endereco" autocomplete="off" id="end1">
                    <label for="end1" class="btn btn-outline-danger p-4 h-100 w-100">
                        <h3>
                            <b class="text-dark">Minha Casa</b><br>
                            <hr>
                            <?php echo $Nome . " " . $Sobrenome ?> <br>
                            Endereço: <?php echo $Logradouro ?> <br>
                            Bairro: <?php echo $Bairro ?> <br>
                            Cidade: <?php echo $Cidade ?> <br>
                            Estado: <?php echo $Estado ?> <br>
                            Referência: <?php echo $Referencia ?> <br>
                            Cep: <?php echo $Cep ?> <br>
                        </h3>
                    </label>
                </div>
            </div>
            <div class="text-end border border-top-0 rounded-bottom p-4 pb-0">
                <a href="index.php?page=cart" class="btn btn-outline-success btn-lg mb-3">Voltar ao Carrinho de Compras</a>
                <button class="btn btn-primary btn-lg ms-2 mb-3" type="submit" name="continuar" value="continuar">Continuar</button>
            </div>     
        </form>
    </div>
 </main>