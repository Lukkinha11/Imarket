 <main class="flex-fill">
     <div class="container">
         <h2>Confirmação de Cadastro</h2>
         <hr>
         <p>
             Caro(a) <strong><?php echo isset($_SESSION['nome_cli']) ? $_SESSION['nome_cli'] : 0;?></strong>
         </p>
         <p>
             Obrigado por se cadastrar em nosso mercado digital. Um e-mail de confrimação foi enviado
             para <b><?php echo  $_SESSION['email_cli'];?></b>. Clique no link de confirmação enviado no seu email
             para concluirmos seu cadastro.
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
             <a href="?pg=index" class="btn btn-primary">Voltar à Página Principal</a>
         </p>
     </div>
 </main>

     <!--                 
    <div style="height: 273px" class="d-block d-md-none"></div>
    <div style="height: 153px" class="d-none d-md-block d-lg-none"></div>
    <div style="height: 129px" class="d-none d-lg-block"></div>
    -->  