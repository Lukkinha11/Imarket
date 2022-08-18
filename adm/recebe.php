 <?php
 

function validaImage($diretorio) 
{
	$erro = "";
	$novonome = "";

	$retorno = [];
	$retorno = $erro;
	$retorno = $novonome;

	$retorno = array();
 
	$arq['pasta']   = "$diretorio"; //tmp_name
	$arq['tamanho'] = 1024 * 1024 * 2; //size
	$arq['ext']     = array('jpeg','jpg','png');//type 
	
	$arq['erros'][0] = "Não houve erro";
	$arq['erros'][1] = "Tamanho maximo excede limite PHP";
	$arq['erros'][2] = "Tamanho maximo excede limite HTML";
	$arq['erros'][3] = "Arquivo carregado parcialmente";
	$arq['erros'][4] = "Nenhum arquivo enviado";

	if($_FILES['image']['error']!= 0)
	{
		array_push($retorno," Não foi possivel carregar o arquivo".$arq['erros'][$_FILES['image']['error'] ]);
	}
	
	$tmp = explode(".",$_FILES['image']['name']);//quebrando o nome do arquivo em uma array
	$extensao = strtolower(end($tmp));//avança pra ultima posição da array

	if(array_search($extensao,$arq['ext'])===false)//verifica se é do tipo esperado
	{

		array_push($retorno," Imagem inválida, Extensões aceitas: JPEG, JPG, PNG");
		//return $erro;
		
		
	}elseif($_FILES['image']['size'] > $arq['tamanho'])//tamanho excede
	{
		array_push($retorno, "Tamanho não permitido, por favor carregue um arquivo menor que 2MB");
		
	}
	else
	{	 
		//mover arquivo
		$novonome = time().'.'.$extensao;//vou salvar o arquivo timestap com a extensao original
		if(move_uploaded_file($_FILES['image']['tmp_name'],$arq['pasta'].$novonome))
		{
			//Secho "sucesso";
		}else{
			
			//echo "erro";
		}
	}
	//$novonome = "95222582582582582";
	array_push($retorno, $novonome);
	return $retorno;
}
 ?>