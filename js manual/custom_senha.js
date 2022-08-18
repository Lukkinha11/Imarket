let senha = document.getElementById('senha');
let senhaC = document.getElementById('senhaC');

function validarSenha() {
  if (senha.value != senhaC.value) {
    senhaC.setCustomValidity("Senhas diferentes!");
    senhaC.reportValidity();
    return false;
  } else {
    senhaC.setCustomValidity("");
    return true;
  }
}

// verificar também quando o campo for modificado, para que a mensagem suma quando as senhas forem iguais
senhaC.addEventListener('input', validarSenha);

//==================================================================================================================================================================================
let edit_senha = document.getElementById('edit_senha');
let edit_senhaC = document.getElementById('edit_senhaC');

function validarSenha() {
  if (edit_senha.value != edit_senhaC.value) {
    edit_senhaC.setCustomValidity("Senhas diferentes!");
    edit_senhaC.reportValidity();
    return false;
  } else {
    edit_senhaC.setCustomValidity("");
    return true;
  }
}

// verificar também quando o campo for modificado, para que a mensagem suma quando as senhas forem iguais
edit_senhaC.addEventListener('input', validarSenha);
//==================================================================================================================================================================================
function verificaForcaSenha() 
{
	var numeros = /([0-9])/;
	var alfabeto = /([a-zA-Z])/;
	var chEspeciais = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;

	if($('#senha').val().length<6) 
	{
		$('#senha-status').html("<span style='color:red'>Fraco, insira no mínimo 6 caracteres</span>");
	} else {  	
		if($('#senha').val().match(numeros) && $('#senha').val().match(alfabeto) && $('#senha').val().match(chEspeciais))
		{            
			$('#senha-status').html("<span style='color:green'><b>Forte</b></span>");
		} else {
			$('#senha-status').html("<span style='color:orange'>Médio, insira um caracter especial</span>");
		}
	}
}
