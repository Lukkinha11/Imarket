 <?php
    ob_start();
    ini_set('default_charset','utf-8');
    require_once '../class/conexao2.php';
 ?>
 
 <main class="flex-fill">
     <div class="container">
         <h2>Instruções enviadas</h2>
         <hr>
         <p>
             Caro cliente,
         </p>
         <p>
             As instruções para a realização de cadastro de uma nova senha foram enviadas para o
             seu email <b><?php echo $_SESSION['email'];?></b>. Abra a mensagem que lhe enviamos e siga as
             instruções corretamente para cadastrar uma nova senha.
         </p>
         <p>
             Desde já, agradecemos pela confiança em nossos serviços.
         </p>
         <p>
             Atenciosamente,
         </p>
         <p>
             <b>
                 Central de Relacionamento iMarket
             </b>
         </p>
         <p>
             <a href="index.php?page=index" class="btn btn-primary">Voltar à Página Principal</a>
         </p>
     </div>
 </main>

    <!--                 
    <div style="height: 273px" class="d-block d-md-none"></div>
    <div style="height: 153px" class="d-none d-md-block d-lg-none"></div>
    <div style="height: 129px" class="d-none d-lg-block"></div>
    -->  