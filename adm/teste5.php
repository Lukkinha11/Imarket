

<?php



/*$palavra = "99281-5262";

$Categoria_diretorio = str_replace("-", "", $palavra);

//echo $Categoria_diretorio;

$presenca = rand($Categoria_diretorio, 99999);
echo $presenca;*/

$cnpj = "11.444.777/0001-61";

function validar_cnpj($cnpj)
{
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	
	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;

	// Verifica se todos os digitos são iguais
	if (preg_match('/(\d)\1{13}/', $cnpj))
		return false;	

	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
		return false;

	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
}

//var_dump(validar_cnpj('11.444.777/0001-61'));

if(validar_cnpj($cnpj) == true)
{
    echo "CNPJ VÁLIDO";
}
else
{
    echo"CNPJ INVÁLIDO";
}


