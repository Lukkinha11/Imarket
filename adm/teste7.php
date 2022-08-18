<!DOCTYPE html>
<html lang="pt-br">
<body>
   <form action="" method="POST">
      Nome: <input type="text" id="nome"> 
      Valor: <input type="number" step="any" id="valor"> 
      Vencimento: <input type="date" id="vencimento"> 
      <button id="botao-salvar" onclick="salvarDados()">Salvar</button>
      <table border="1" id="campo">
         <legend>Contas a Pagar</legend>
         <tr>
            <th>Nome</th>
            <th>Valor</th>
            <th>Vencimento</th>
         </tr>
      </table>
   </form>
  
</body>
<script src="../js manual/teste3.js"></script>