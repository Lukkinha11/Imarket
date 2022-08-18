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

        //var_dump($_SESSION['dados']);
    ?>
 <main class="flex-fill">
     <div class="container text-center">
         <h2>Obrigado!</h2>
         <hr>
         <h3>Anote o número do seu pedido:</h3>
         <h2 class="text-danger"><b>009528</b></h2>
         <p>
             Em breve seu pedido será entregue. Qualquer dúvida,
             entre em contato conosco e informe o número do pedido
             para que possamos te ajudar.
         </p>
         <p>
             Tenha um ótimo dia♥!
         </p>
         <p>
             Atenciosamente,
         </p>
         <p>
             Equipe iMarket.
         </p>
         <p>
             <a href="?pg=index" class="btn-btn-primary btn-lg">Voltar à Página Principal</a>
         </p>
     </div>
 </main>

 