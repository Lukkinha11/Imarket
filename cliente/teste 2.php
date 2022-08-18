<?php
var_dump($_POST['filtro'] == 'a');

?>

<form action="#" method="POST" >
    <select name="filtro" onchange="document.getElementsByTagName('form')[0].submit()">
       <option value="a">Ordem A-Z</option>
       <option value="z">Ordem Z-A</option>
       <option value="preco">Ordem por preços</option>
    </select>
</form>

<script src="../js manual/teste8.js"></script> 