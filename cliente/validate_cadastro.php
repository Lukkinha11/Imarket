<?php

class Validate
{
    function Conta($x)
    {         
        if(strlen($x) != 0)
        {
            return $x;
        }else{
            echo"<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                    <strong>ATENÇÃO! PREEENCHA OS CAMPOS CORRETAMENTE</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    }

    function isCpf($cpf)
    {
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $digitoUm = 0;
        $digitoDois = 0;
		if (strlen($cpf) != 11) 
		{
        return false;
		}

        for($i = 0, $x = 10; $i <= 8; $i++, $x--)
        {
            $digitoUm += $cpf[$i] * $x;
        }
        for($i = 0, $x = 11; $i <=9; $i++, $x--)
        {
            if(str_repeat($i, 11) == $cpf)
            {
                return false;
            }
            $digitoDois += $cpf[$i] * $x;
        }

        $calculoUm   = (($digitoUm%11) < 2) ? 0 : 11-($digitoUm%11);
        $calculoDois = (($digitoDois%11) < 2) ? 0 : 11-($digitoDois%11);

        if($calculoUm <> $cpf[9] || $calculoDois <> $cpf[10])
        {
            return false;
        }
        return true;
    }

    function ValidaData($data)
    {
        // data é menor que 8
        if ( strlen($data) < 8){
            return false;
        }else{
            // verifica se a data possui
            // a barra (/) de separação
            if(strpos($data, "/") !== FALSE){
                //
                $partes = explode("/", $data);
                // pega o dia da data
                $dia = $partes[0];
                // pega o mês da data
                $mes = $partes[1];
                // prevenindo Notice: Undefined offset: 2
                // caso informe data com uma única barra (/)
                $ano = isset($partes[2]) ? $partes[2] : 0;
    
                if (strlen($ano) < 4) {
                    return false;
                } else 
                {
                    // verifica se a data é válida
                    if ( !checkdate( $mes , $dia , $ano )                   // se a data for inválida
                        || $ano < 1900                                     // ou o ano menor que 1900
                        || mktime( 0, 0, 0, $mes, $dia, $ano ) > time() )  // ou a data passar de hoje
                    {
                    echo 'Data inválida';
                    }
                        //if (checkdate($mes, $dia, $ano)) 
                        //{
                            // return true;
                        //} 
                    else 
                    {
                        return false;
                    }
                }
            }else{
                return false;
            }
        }
    }

    function dateEmMysql($dateSql){
        $ano= substr($dateSql, 6);
        $mes= substr($dateSql, 3,-5);
        $dia= substr($dateSql, 0,-8);
        return $ano."-".$mes."-".$dia;
    }
	function valida_email($email) 
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
    function senhaValida($senha) 
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[#$!%&@*?])(?=.*[0-9])[\w$@#!%*?]{6,}$/', $senha);
    }
}

