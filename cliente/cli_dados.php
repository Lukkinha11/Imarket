
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
                $query_dados = "SELECT Nome, Sobrenome, Data_nasc, CPF FROM cliente
                                WHERE idCliente ='".$userid."' LIMIT 1";
                $result_cli = $pdo->prepare($query_dados);
                $result_cli->execute();
            }

            //echo $userid;

            ?>
            <div class="col-8">
                <?php
                    while($row_cli = $result_cli->fetch(PDO::FETCH_ASSOC))
                    {
                        extract($row_cli);
                    
                ?>
                    <form action="" class="row mb-3">
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="txtNome" value= <?php echo $Nome ?> readonly>
                                <label for="txtNome">Nome</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="txtSobrenome" value= <?php echo $Sobrenome ?>  readonly>
                                <label for="txtSobrenome">Sobrenome</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="txtCPF" maxlength="14" value="<?php echo $CPF ?>" oninput="maskCPF(this)" readonly>
                                <label for="txtCPF" CPF class="form-label">CPF</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-floating">                     
                                <input type="date" class="form-control" id="txtDataNascimento" value="<?php echo $Data_nasc ?>"  type="date" max="2999-12-31" readonly>
                                <label for="txtDataNascimento" class="form-label">Data de Nascimento</label>
                            </div>
                        </div>
                    </form>
                <?php
                    }
                ?>
            </div>
        </div>
     </div>
 </main>
 <script src="../js manual/custom_checkout.js"></script>

