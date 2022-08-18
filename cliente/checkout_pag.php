        <?php
            ob_start();
            ini_set('default_charset','utf-8');
            require_once '../class/conexao2.php';
            require_once 'validate_cadastro.php';
            global $pdo;

            if(!isset($_SESSION['id']) AND !isset($_SESSION['nome']) OR $_SESSION['estado'] != "DF")
            {
                header("Location: index.php");
                exit();                    
            }

            if(isset($_SESSION['id']))
            {
                $userid = $_SESSION['id'];

                foreach($_SESSION['dados'] as $prod)
                {
                    $tot = $prod['tot'];
                }
            }

            

            

            //var_dump($_SESSION['dados']);
            //var_dump($_SESSION['id']);

            //unset($_POST["pagar"]);
            $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $validate = new Validate();

            

            //var_dump($data);

            if(isset($_POST['pagar']))
            {
                $parcela = $data['quant_parcelas'];
                $pagamento =$data['forma_pag'];
                
                if(!isset($_POST['pag']) AND !isset($_POST['pix']))
                {
                    echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                            <strong>ATENÇÃO!</strong> Selecione Pix ou outra forma de pagamento de sua preferência!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                }
                elseif(isset($_POST['pag']))
                {
                    if(!empty($data['pagar']))
                    {  
                        $valido = true;
                        $campos = Array("forma_pag", "numero_card", "nome_card", "data_validade", "codigo_seg", "cpf", "quant_parcelas");
                        //validação dos campos individualmente
                        foreach ($campos as $campo)
                        {
                            if (empty(strip_tags(trim($_POST[$campo]))))
                            {   
                                echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                        <strong>ATENÇÃO!</strong> Por favor preencha o Campo: <strong>$campo!</strong>
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                                    //var_dump($campo);
                                    
                                $valido = false;
                            }
                        }
                        if($validate->isCpf($data['cpf']))
                        {
                            if(strlen($data['numero_card']) != 19)
                            {
                                echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                        <strong>ATENÇÃO!</strong> NÚMERO DO CARTÃO É INVÁLIDO
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                                $valido = false;
                            }
                            if($data['forma_pag'] == "Selecione" || $data['quant_parcelas'] == "Selecione")
                            {
                                echo"<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                                    <strong>ATENÇÃO!</strong> Selecione entre Crédito e Débito e o número de parcelas de sua preferência!</strong>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                                $valido = false;
                            }
                            if($valido)
                            {
                                $forma_pag      = $_POST['forma_pag'];
                                $numero_card    = $_POST['numero_card'];
                                $nome_card      = $_POST['nome_card'];
                                $data_validade  = $_POST['data_validade'];
                                $codigo_seg     = $_POST['codigo_seg'];
                                $cpf            = $_POST['cpf'];
                                $quant_parcelas = $_POST['quant_parcelas'];

                                $pdo->beginTransaction();

                                try
                                {
                                    $query_cart = "INSERT INTO carrinho_compra(Cliente_idCliente, Forma_Pagamento_idForma_Pag, Data_Compra, Status_compra_idStatus_compra)
                                                    VALUES(:Cliente_idCliente, $pagamento, NOW(), :Status_compra_idStatus_compra)";

                                    $cli_cart = $pdo->prepare($query_cart);
                                    $cli_cart->bindvalue(':Cliente_idCliente', $userid, PDO::PARAM_INT);
                                    $cli_cart->bindvalue(':Status_compra_idStatus_compra', 4, PDO::PARAM_INT);

                                    if($cli_cart->execute())
                                    {
                                        $idCarrinho_Compra = $pdo->lastInsertId();

                                        try
                                        {
                                            foreach($_SESSION['dados'] as $produtos)
                                            {      
                                                                                
                                                $query_itens = "INSERT INTO itens_carrinho(Carrinho_Compra_idCarrinho_Compra, Produto_Id_Produto, Quant_prod, Valor_unit, Valor_total, Status_item_idStatus_item)
                                                                                    VALUES($idCarrinho_Compra, :Produto_Id_Produto, :Quant_prod, :Valor_unit, :Valor_total, :Status_item_idStatus_item)";

                                                $cli_itens = $pdo->prepare($query_itens);
                                                $cli_itens->bindValue(':Produto_Id_Produto', $produtos['id_produto'], PDO::PARAM_INT);
                                                $cli_itens->bindValue(':Quant_prod', $produtos['quantidade'], PDO::PARAM_STR);
                                                $cli_itens->bindValue(':Valor_unit', $produtos['preco'], PDO::PARAM_STR);
                                                $cli_itens->bindValue(':Valor_total', $produtos['sub_total'], PDO::PARAM_STR);
                                                $cli_itens->bindValue(':Status_item_idStatus_item', 2, PDO::PARAM_INT);

                                                if($cli_itens->execute())
                                                {
                                                    try
                                                    {
                                                        $soma = 0;
                                                        $val_press = 0;

                                                        for($x = 1;  $x<=$parcela; $x++)
                                                        {
                                                            $query_contas_receber = "INSERT INTO contas_receber(Valor, Parcela, Data_Pag, Data_Venc, carrinho_compra_idCarrinho_Compra)
                                                                                    VALUES(:Valor, $x, CURDATE(), CURDATE() + INTERVAL 30 * $x DAY, $idCarrinho_Compra)";
                                                            if($x < $parcela)
                                                            {
                                                                $val_press =  round($tot / $parcela,2) ;
                                                                $soma += $val_press;
                                                            }
                                                            else
                                                            {
                                                                $val_press = $tot - $soma;
                                                            }
                                            
                                                            $cli_contas_receber = $pdo->prepare($query_contas_receber);
                                                            $cli_contas_receber->bindValue(':Valor', $val_press, PDO::PARAM_STR);

                                                            $cli_contas_receber->execute();
                                                        }
                                                    }
                                                    catch(PDOException $e)
                                                    {
                                                        $pdo->rollBack();
                                                        echo "<div class='alert alert-danger' role='alert'>Algo deu errado no contas_receber PDOException: ". $e->getMessage() ."</div>";
                                                    }
                                                }
                                            }
                                        }
                                        catch(PDOException $e)
                                        {
                                            $pdo->rollBack();
                                            echo "<div class='alert alert-danger' role='alert'>Algo deu errado no itens_carrinho PDOException: ". $e->getMessage() ."</div>";
                                        }
                                    }
                                }
                                catch(PDOException $e)
                                {
                                    $pdo->rollBack();
                                    echo "<div class='alert alert-danger' role='alert'>Algo deu errado no carrinho_compra PDOException: ". $e->getMessage() ."</div>";
                                }

                                $pdo->commit();

                                if($cli_contas_receber->rowCount())
                                {
                                    unset($_SESSION['dados']);
                                    unset($_SESSION['cart']);
                                    header("Location: ?page=finish");
                                    exit();
                                }     
                            }
                        }
                        else
                        {
                            echo"<div class='alert alert-primary alert-dismissible fade show text-center' role='alert'>
                                <strong>ATENÇÃO!</strong> CPF É INVÁLIDO!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                        }     
                    } 
                }
                elseif(isset($_POST['pix']))
                {    
                    $pdo->beginTransaction();

                    try
                    {
                        $query_cart = "INSERT INTO carrinho_compra(Cliente_idCliente, Forma_Pagamento_idForma_Pag, Data_Compra, Status_compra_idStatus_compra)
                                        VALUES(:Cliente_idCliente, 3, NOW(), :Status_compra_idStatus_compra)";

                        $cli_cart = $pdo->prepare($query_cart);
                        $cli_cart->bindvalue(':Cliente_idCliente', $userid, PDO::PARAM_INT);
                        $cli_cart->bindvalue(':Status_compra_idStatus_compra', 4, PDO::PARAM_INT);
                        

                        if($cli_cart->execute())
                        {
                            $id_carrinho = $pdo->lastInsertId();

                            try
                            {
                                foreach($_SESSION['dados'] as $produtos)
                                {
                                    //var_dump($produtos['id_produto']);       
                                    
                                    $query_itens = "INSERT INTO itens_carrinho(Carrinho_Compra_idCarrinho_Compra, Produto_Id_Produto, Quant_prod, Valor_unit, Valor_total, Status_item_idStatus_item)
                                                    VALUES($id_carrinho, :Produto_Id_Produto, :Quant_prod, :Valor_unit, :Valor_total, :Status_item_idStatus_item)";

                                    $cli_itens = $pdo->prepare($query_itens);
                                    $cli_itens->bindValue(':Produto_Id_Produto', $produtos['id_produto'], PDO::PARAM_INT);
                                    $cli_itens->bindValue(':Quant_prod', $produtos['quantidade'], PDO::PARAM_STR);
                                    $cli_itens->bindValue(':Valor_unit', $produtos['preco'], PDO::PARAM_STR);
                                    $cli_itens->bindValue(':Valor_total', $produtos['sub_total'], PDO::PARAM_STR);
                                    $cli_itens->bindValue(':Status_item_idStatus_item', 2, PDO::PARAM_INT);

                                    if($cli_itens->execute())
                                    {
                                        try
                                        {
                                            $query_contas_receber = "INSERT INTO contas_receber(Valor, Parcela, Data_Pag, Data_Venc, carrinho_compra_idCarrinho_Compra)
                                                                                         VALUES(:Valor, 1, CURDATE(), CURDATE(), $id_carrinho)";
                    
                                            $cli_contas_receber = $pdo->prepare($query_contas_receber);
                                            $cli_contas_receber->bindValue(':Valor', $tot, PDO::PARAM_STR);

                                            $cli_contas_receber->execute();
                                            
                                            
                                        }
                                        catch(PDOException $e)
                                        {
                                            $pdo->rollBack();
                                            echo "<div class='alert alert-danger' role='alert'>Algo deu errado no contas_receber#2 PDOException: ". $e->getMessage() ."</div>";
                                        }
                                    } 
                                }
                            }
                            catch(PDOException $e)
                            {
                                $pdo->rollBack();
                                echo "<div class='alert alert-danger' role='alert'>Algo deu errado no itens_carrinho#2 PDOException: ". $e->getMessage() ."</div>";
                            }
                        }
                    }
                    catch(PDOException $e)
                    {
                        $pdo->rollBack();
                        echo "<div class='alert alert-danger' role='alert'>Algo deu errado no carrinho_compra#2 PDOException: ". $e->getMessage() ."</div>";
                    }

                    $pdo->commit();

                    if($cli_contas_receber->rowCount())
                    {
                        unset($_SESSION['dados']);
                        unset($_SESSION['cart']);
                        header("Location: ?page=finish");
                        exit();
                    }
                }     
            }
        ?>
 <main class="flex-fill">
     <div class="container">
         <h2>Selecione a Forma de Pagamento</h2>
         <br>
         <h3 class="mb-4">
             Selecione a forma de pagamento e clique em <b>Pagar</b> para
             prosseguir com a <b>conclusão do pedido</b>.
         </h3>
         <div class="d-flex justify-content-between flex-wrap border rounded-top pt-4 px-3">
             <div class="mb-4 mx-2 flex-even">
             <form action="" method="POST">
                 <input type="radio" class="btn-check" name="pag"  id="pag1">
                 <label for="pag1" class="btn btn-outline-danger p-4 h-100 w-100">
                     <h3>
                         <b class="text-dark">Cartão</b>
                     </h3>
                     <hr>
                     
                         <div class="form-group mx-sm-0 mb-3">
                             <select class="form-select" name="forma_pag" id="pagamento" aria-label="Default select example">
                                 <option>Selecione</option>
                                 <?php

                                    ini_set('default_charset', 'utf-8');
                                    require_once '../class/conexao2.php';
                                    global $pdo;

                                    $query_pag = "SELECT idForma_pag, Descricao FROM forma_pagamento ORDER BY idForma_Pag LIMIT 2";
                                    $result_pag = $pdo->prepare($query_pag);
                                    $result_pag->execute();

                                    while($dados = $result_pag->fetch(PDO::FETCH_ASSOC))
                                    {
                                        extract($dados);
                                        $opcaoSalva = $data['forma_pag'];

                                        ?>
                                            
                                            <option value="<?php echo $idForma_pag ?>" <?php echo ($idForma_pag == $opcaoSalva) ? 'selected' : 'Selecione' ?>><?php echo $Descricao ?></option>
                                                                    
                                        <?php
                                    }

                                 ?>
                             </select>
                         </div>
                         <div class="form-floating mb-3 ">
                             <input type="text" class="form-control" name="numero_card"  value="<?php if(isset($data['numero_card'])) echo $data['numero_card'];?>" autofocus id="cc" maxlength="19" onkeypress="mascara(this.value)" placeholder=" ">
                             <label for="cc" class="text-black-50">Número do Cartão</label>
                         </div>
                         <div class="form-floating mb-3">
                             <input type="text" class="form-control" name="nome_card" value="<?php if(isset($data['nome_card'])) echo $data['nome_card'];?>" id="txtNome" placeholder=" ">
                             <label for="txtNome" class="text-black-50">Nome Impresso no Cartão</label>
                         </div>
                         <div class="form-floating mb-3">
                             <input type="text" class="form-control" name="data_validade" value="<?php if(isset($data['data_validade'])) echo $data['data_validade'];?>" id="outra_data" maxlength="10" onkeypress="mascaraData(this)" placeholder=" " onkeypress="mascaraData( this, event )">
                             <label for="outra_data" class="text-black-50">Validade (mm/aa)</label>
                         </div>
                         <div class="form-floating mb-3">
                             <input type="text" class="form-control" name="codigo_seg" value="<?php if(isset($data['codigo_seg'])) echo $data['codigo_seg'];?>" id="txtCodigo" placeholder=" ">
                             <label for="txtCodigo" class="text-black-50">Código de Segurança</label>
                         </div>
                         <div class="form-floating mb-3">
                             <input type="text" class="form-control" id="txtCPF" name="cpf" value="<?php if(isset($data['cpf'])) echo $data['cpf'];?>" maxlength="14" value="" placeholder=" " oninput="maskCPF(this)">
                             <label for="txtCPF" CPF class="text-black-50">CPF do Titular<span class="text-black-50">(somente números)</span></label>
                         </div>
                         <div class="form-floating">
                             <select name="quant_parcelas" id="parcelas" class="form-select ">
                                 <option>Selecione</option>
                                 <?php

                                    ini_set('default_charset', 'utf-8');
                                    require_once '../class/conexao2.php';
                                    global $pdo;

                                    $query_formapag = "SELECT idDesc_pag, Forma_pag, Quant FROM desc_pag";
                                    $result_pag = $pdo->prepare($query_formapag);
                                    $result_pag->execute();

                                    while($dados = $result_pag->fetch(PDO::FETCH_ASSOC))
                                    {
                                        extract($dados);
                                        $opcaoSalva = $data['quant_parcelas'];

                                        ?>  
                                            <option value="<?php echo $idDesc_pag ?>" <?php echo ($idDesc_pag == $opcaoSalva) ? 'selected' : 'Selecione' ?>><?php echo $Quant . "x" ?></option>                          
                                        <?php
                                    }

                                 ?>
                             </select>
                             <label for="quant_parcelas" class="text-black-50">Parcelamento</label>
                         </div> 
                 </label>
             </div>
             <div class="mb-4 mx-2 flex-even">
                 <input type="radio" class="btn-check" name="pix" id="pag2">
                 <label for="pag2" class="btn btn-outline-danger p-4 h-100 w-100">
                     <h3>
                         <b class="text-dark">Pix</b>
                     </h3>
                     <hr>
                     
                        <?php foreach($_SESSION['dados'] as $produtos) : ?>
                            <?php $produtos['total'] ?>
                        <?php endforeach; ?>
                        <h4>Valor da Compra: <b>R$ <?php echo $produtos['total'] ?></b></h4>
                         <br>
                         <p class="text-dark">
                             Pague sua compra com a chave pix abaixo.
                         </p>
                         <p>
                         <h3><b class="text-dark">522703856165988</b></h3>
                         </p>
                     
                 </label>
             </div>
         </div>
         <div class="text-end border border-top-0 rounded-bottom p-4 pb-0 mb-5">
             <a href="index.php?page=entrega" class="btn btn-outline-success btn-lg mb-3">Voltar Endereço</a>
             <button class="btn btn-primary btn-lg ms-2 mb-3" type="submit" name="pagar" value="pagar" >Pagar</button>
         </div>
         </form><!--Fechamento do primeiro form onclick="window.location.href='index.php?page=finish'"-->
     </div>
 </main>
 <script src="../js manual/custom_card.js"></script>

 <script src="../js manual/custom_validadecard.js"></script>

 <script src="../js manual/custom_checkout.js"></script>

<script type="text/javascript">
    var selectServicos = document.querySelector('#parcelas')

    var selectProfissional = document.querySelector('#pagamento')

    selectProfissional.onchange = function(evento){ // função que vai ser executada cada vez que o valor do select for mudado, passando o evento como parametro
    var id_filtro = evento.target.value // pega o valor do evento, que vai ser o do select profissional

    fetch('quant_parcelas_post.php?id_filtro='+id_filtro) // faz a requisição para a url, passando o filtro como parâmetro
       .then(response => response.text()) // avisa que a proxima resposta da promise deve ser um texto
       .then(options => selectServicos.innerHTML = options)  // exibe os valores dentro do seu select, que foram retornados do seu backend
    }
</script>
