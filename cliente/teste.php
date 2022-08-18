<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
</head>
<body>
    <form>
		<input type="submit" onclick="toogle_disabled( false )" value="Habilitar" />
		<input type="button" onclick="toogle_disabled( true )" value="Desabilitar" />

		<br /><br />        
		Nome: <input type="text" name="nome" disabled>
		Local: <input type="text" name="local" >
		<br>
		Nome: <input type="text" name="nome2" >
		Local: <input type="text" name="local2" >

	</form>
</body>
</html>