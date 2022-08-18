            <?php
                ob_start();
                ini_set('default_charset','utf-8');
                require_once '../class/conexao2.php';
                global $pdo;

                if(!isset($_SESSION['id']) AND !isset($_SESSION['nome']))
                {
                    header("Location: index.php");
                    exit();                    
                }
                else
                {
                    $userid = $_SESSION['id'];
                    $query_dados = "SELECT Email, Telefone, DDD FROM cliente
                                    WHERE idCliente ='".$userid."' LIMIT 1";
                    $result_cli = $pdo->prepare($query_dados);
                    $result_cli->execute();
                }
            ?>

            <div class="col-8">
                <?php
                    while($row_cli = $result_cli->fetch(PDO::FETCH_ASSOC))
                    {
                        extract($row_cli);
                ?>
                    <form action="" class="row mb-3" method="POST">
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="DDD" id="txtDDD" maxlength="4" oninput="maskDDD(this)" value=<?php echo $DDD ?> disabled>
                                <label for="txtDDD" class="form-label">DDD</label>               
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="Telefone" id="txtTelefone" maxlength="10" value=<?php echo $Telefone ?> oninput="maskPhone(this)" disabled>
                                <label for="txtTelefone" class="form-label">Telefone</label>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-floating">                           
                                        <input type="email" class="form-control" value=<?php echo $Email ?> id="txtEmail" readonly>
                                        <label for="txtEmail" class="form-label">E-mail</label>
                                </div>
                            </div>
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button class="btn btn-danger" type="button"  onclick="toogle_disabled( false )">Habilitar Campos Para Edição</button>
                        </div>
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button class="btn btn-success" name="alterar" value="enviar" type="submit">Salvar Dados Editados</button>
                        </div>
                    </form>    
                <?php
                    }
                ?>
                <?php

                    $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    //var_dump($data);

                    $valido = true;

                    if(!empty($data['Telefone']) || !empty($data['DDD']))
                    {
                        //var_dump($data);
                        $campos = Array("Telefone","DDD");

                        foreach ($campos as $campo)
                        {
                            if (empty(strip_tags($_POST[$campo])))
                            {   
                                echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                        <strong>ATENÇÃO!</strong> Por favor preencha o Campo: <strong>$campo!</strong>
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>"; 
                                
                                $valido = false;
                            }
                        }
                        if(strlen($data['Telefone']) != 10)
                        {
                            echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                    <strong>ATENÇÃO!</strong> NÚMERO DE TELEFONE INVÁLIDO!
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                                $valido = false;
                        }
                        if(strlen($data['DDD']) != 4)
                        {
                            echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                    <strong>ATENÇÃO!</strong> TAMANHO DO DDD É INVÁLIDO
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                                $valido = false;
                        }
                        if($valido)
                        {
                            $Telefone = $_POST['Telefone'];
                            $DDD = $_POST['DDD'];

                            $query_up_cont = "UPDATE cliente SET Telefone=:Telefone, DDD=:DDD, Modificado = NOW() WHERE idCliente ='".$userid."' ";
                            $update_cli = $pdo->prepare($query_up_cont);
                            $update_cli->bindValue(':Telefone', $data['Telefone'], PDO::PARAM_STR);
                            $update_cli->bindValue(':DDD', $data['DDD'], PDO::PARAM_STR);
                            $update_cli->execute();

                            if($update_cli->rowCount())
                            {
                                header("Location: ?pag=contact");
                            }
                        }
                    }
                    else
                    {
                        //echo "ERRO";
                    }
                ?>
            </div>
        </div>
     </div>
 </main>
 <script src="../js manual/custom_checkout.js"></script>
 <script type="text/javascript">
    function toogle_disabled( bool )
    {
        var input = document.getElementsByTagName('input');
        var textarea = document.getElementsByTagName('textarea');

        for( var i=0; i<=(input.length-1); i++ )
        {
            if( input[i].type!='button' ) 
                input[i].disabled = bool;
        }
        for( var i=0; i<=(textarea.length-1); i++ )
        {
            textareat[i].disabled = bool;
        }
    }
</script>
 

