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
                    $query_dados = "SELECT idCliente, Cep, Logradouro, Bairro, Cidade, Estado, Complemento, Referencia 
                                    FROM cliente,
                                        endereco
                                    WHERE cliente.Endereco_idEndereco = endereco.idEndereco
                                    AND idCliente ='".$userid."' LIMIT 1";
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
                    <form action="" method="POST" class="row mb-3">
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="cep" name="Cep" maxlength="9" onblur="pesquisacep(this.value);" value="<?php echo $Cep ?>" disabled>
                                <label for="cep" class="form-label mascCEP">CEP</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="rua" name="Logradouro" value="<?php echo $Logradouro ?>" readonly>
                                <label for="bairro" class="form-label mascCEP">Logradouro</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 mb-3">
                            <div class="form-floating">
                            <input type="text" class="form-control" id="bairro" name="Bairro" value="<?php echo $Bairro ?>" readonly>
                            <label for="rua" class="form-label mascCEP">Bairro</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">                     
                                <input type="text" class="form-control" id="cidade" name="Cidade" value="<?php echo $Cidade ?>" readonly>
                                <label for="cidade" class="form-label mascCEP">Cidade</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">                     
                                <input type="text" class="form-control" id="uf" name="Estado" value="<?php echo $Estado ?>" readonly>
                                <label for="uf" class="form-label mascCEP">Estado</label>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-floating">                     
                                <input type="text" class="form-control" id="txtComplemento" name="Complemento" value="<?php echo $Complemento ?>" disabled>
                                <label for="txtComplemento" class="form-label">Complemento</label>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-floating">                     
                                <input type="text" class="form-control" id="txtReferencia" name="Referencia" value="<?php echo $Referencia ?>" disabled>
                                <label for="txtReferencia" class="form-label">Ponto de Referência</label>
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

                    if(!empty($data['Cep']) || !empty($data['Logradouro']) || !empty($data['Bairro']) || !empty($data['Cidade'])
                       || !empty($data['Estado']) || !empty($data['Complemento']) || !empty($data['Referencia']))
                    {
                        $campos = Array("Cep","Logradouro","Bairro", "Cidade", "Estado", "Complemento", "Referencia");
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
                        if($valido)
                        {
                            $Cep = $_POST['Cep'];
                            $Logradouro = $_POST['Logradouro'];
                            $Bairro = $_POST['Bairro'];
                            $Cidade = $_POST['Cidade'];
                            $Estado = $_POST['Estado'];
                            $Complemento = $_POST['Complemento'];
                            $Referencia = $_POST['Referencia'];

                            //$query_up_cont = "UPDATE cliente SET Cep=:Cep, Logradouro=:Logradouro, Bairro=:Bairro, Cidade=:Cidade, Estado=:Estado, Tipo_logradouro_idTipo_Logradouro=:Tipo_Logradouro, Complemento=:Complemento, 
                                                     //Referencia=:Referencia WHERE idCliente ='".$userid."' ";
                                                     
                            $pdo->beginTransaction();

                            //Verifica se o cep do funcionário a ser cadastrado existe no banco de dados
                            $query_cep = "SELECT idEndereco, Cep FROM endereco WHERE Cep=:Cep LIMIT 1";

                            $result_cep = $pdo->prepare($query_cep);
                            $result_cep->bindValue(':Cep', $data['Cep'], PDO::PARAM_STR);
                            
                            $result_cep->execute();

                            //Se existir o cep, recupero o id e associo ao funcionário a ser cadastrado
                            if(($result_cep) AND ($result_cep->rowCount() !=0))
                            {
                                $row_cep = $result_cep->fetch(PDO::FETCH_ASSOC);
                                
                                extract($row_cep);

                                $query_up_cont = "UPDATE cliente SET Endereco_idEndereco=:Endereco_idEndereco, Complemento=:Complemento, Referencia=:Referencia
                                                    WHERE idCliente ='".$userid."' ";

                                $update_cli = $pdo->prepare($query_up_cont);
                                $update_cli->bindValue(':Endereco_idEndereco', $idEndereco, PDO::PARAM_INT);
                                $update_cli->bindValue(':Complemento', $data['Complemento'], PDO::PARAM_STR);
                                $update_cli->bindValue(':Referencia', $data['Referencia'], PDO::PARAM_STR);

                                try
                                {
                                    if($update_cli->execute())
                                    {
                                        $pdo->commit();

                                        if($update_cli->rowCount())
                                        {
                                            header("Location: ?pag=address");
                                        }
                                    }
                                }
                                catch(PDOException $e)
                                {
                                    $pdo->rollBack();

                                    echo "<div class='alert alert-danger' role='alert'>Algo deu errado no cliente#1: ". $e->getMessage() ."</div>";  
                                }
                            }
                            else
                            {
                                //Não existindo o cep do funcionário no banco de dados o sistema cadastra na tabela de endereço
                                $query_end = "INSERT INTO endereco(Cep, Logradouro, Bairro, Cidade, Estado) 
                                                            VALUES(:Cep, :Logradouro, :Bairro, :Cidade, :Estado)";

                                $add_end = $pdo->prepare($query_end);
                                $add_end->bindValue(':Cep', $data['Cep'], PDO::PARAM_STR);
                                $add_end->bindValue(':Logradouro', $data['Logradouro'], PDO::PARAM_STR);
                                $add_end->bindValue(':Bairro', $data['Bairro'], PDO::PARAM_STR);
                                $add_end->bindValue(':Cidade', $data['Cidade'], PDO::PARAM_STR);
                                $add_end->bindValue(':Estado', $data['Estado'], PDO::PARAM_STR);

                                try
                                {
                                    if($add_end->execute())
                                    {
                                        $id_endereco = $pdo->lastInsertId();

                                        $query_up_cont = "UPDATE cliente SET Endereco_idEndereco=:Endereco_idEndereco, Complemento=:Complemento, Referencia=:Referencia
                                                            WHERE idCliente ='".$userid."' ";
    
                                        $update_cli = $pdo->prepare($query_up_cont);
                                        $update_cli->bindValue(':Endereco_idEndereco', $id_endereco, PDO::PARAM_INT);
                                        $update_cli->bindValue(':Complemento', $data['Complemento'], PDO::PARAM_STR);
                                        $update_cli->bindValue(':Referencia', $data['Referencia'], PDO::PARAM_STR);

                                        try
                                        {
                                            if($update_cli->execute())
                                            {
                                                $pdo->commit();

                                                if($update_cli->rowCount())
                                                {
                                                    header("Location: ?pag=address");
                                                }
                                            }
                                        }
                                        catch(PDOException $e)
                                        {
                                            $pdo->rollBack();

                                            echo "<div class='alert alert-danger' role='alert'>Algo deu errado no cliente#2: ". $e->getMessage() ."</div>";  
                                        }
                                    }           
                                }
                                catch(PDOException $e)
                                {
                                    $pdo->rollBack();

                                    echo "<div class='alert alert-danger' role='alert'>Algo deu errado no endereco: ". $e->getMessage() ."</div>";  
                                }
                            }
                            
                            /*$query_up_cont = "UPDATE cliente 
                                              INNER JOIN endereco 
                                              ON cliente.Endereco_idEndereco = endereco.idEndereco
                                              INNER JOIN tipo_logradouro
                                              ON  endereco.Tipo_Logradouro_idTipo_Logradouro = tipo_logradouro.idTipo_Logradouro
                                              SET endereco.Cep = :Cep, endereco.Logradouro = :Logradouro,
                                                  endereco.Bairro = :Bairro, endereco.Cidade = :Cidade, 
                                                  endereco.Estado = :Estado,
                                                  Tipo_logradouro_idTipo_Logradouro = :Tipo_Logradouro, 
                                                  cliente.Complemento = :Complemento, 
                                                  cliente.Referencia = :Referencia,
                                                  Modificado = NOW()
                                              WHERE idCliente ='".$userid."' ";

                            $update_cli = $pdo->prepare($query_up_cont);

                            $update_cli->bindValue(':Cep', $data['Cep'], PDO::PARAM_STR);
                            $update_cli->bindValue(':Logradouro', $data['Logradouro'], PDO::PARAM_STR);
                            $update_cli->bindValue(':Bairro', $data['Bairro'], PDO::PARAM_STR);
                            $update_cli->bindValue(':Cidade', $data['Cidade'], PDO::PARAM_STR);
                            $update_cli->bindValue(':Estado', $data['Estado'], PDO::PARAM_STR);
                            $update_cli->bindValue(':Tipo_Logradouro', $data['Tipo_Logradouro'], PDO::PARAM_INT);
                            $update_cli->bindValue(':Complemento', $data['Complemento'], PDO::PARAM_STR);
                            $update_cli->bindValue(':Referencia', $data['Referencia'], PDO::PARAM_STR);
                            
                            $update_cli->execute();

                            if($update_cli->rowCount())
                            {
                                header("Location: ?pag=address");
                            }*/
                            
                        }
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
        var select = document.getElementsByTagName('select');

        for( var i=0; i<=(input.length-1); i++ )
        {
            if( input[i].type!='button' ) 
                input[i].disabled = bool;
        }
        for( var i=0; i<=(textarea.length-1); i++ )
        {
            textareat[i].disabled = bool;
        }
        for( var i=0; i<=(select.length-1); i++ )
        {
            select[i].disabled = bool;
        }
    }
</script>

