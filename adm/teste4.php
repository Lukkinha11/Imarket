

<?php

$senha = "123!Aa";

function senhaValida($senha) 
{
    //return preg_match('/^(?=.*\D)(?=.*[# $@!%&*?]){6,}$/', $senha);

    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[#$!%&@*?])(?=.*[0-9])[\w$@#!%*?]{6,}$/', $senha);
    
    /*if(preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[# $@!%&*?])(?=.*[0-9])[\w$@]{6,}$/', $senha))
    {
        echo "Senha OK";
        return true;
    }else
    {
        echo "Senha inválida";
        return false;
    }*/
}

//echo $senha;
//var_dump(senhaValida($senha));

if(senhaValida($senha) == 0)
{
    echo "Senha inválida";
}
else
{
    echo "Senha OK";
}

$palavra = "99281-5262";

$Categoria_diretorio = str_replace("", "", $palavra);

echo $palavra;

$presenca = rand(10000, 99999);
echo $presenca;
?>

<script type="text/javaScript">
function Trim(str){
	return str.replace(/^\s+|\s+$/g,"");
}
</script>

<input type="text" name="tal" onkeyup="this.value = Trim( this.value )" />

<html>
 <head>
    <title>Apostila JavaScript Progressivo</title>
 </head>
 <body>
 <button onclick="gerar()">Gerar</button><br />
 Gerado: <input id="resp"/>

 <script type="text/javascript">
  function gerar()
  {
    var resp = document.getElementById('resp');
    resp.innerHTML = Math.floor(10000* Math.random() + 10000);
  }
 </script>
 </body>
</html>
