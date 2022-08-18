function maskCPF(numberCPF)
{
    var cpf = numberCPF.value;
   
    if(isNaN(cpf[cpf.length - 1]))
    {//Proibir caracteres que não seja número
        numberCPF.value = cpf.substring(0, cpf.length - 1);
        return;
    }

    if(cpf.length === 3 || cpf.length === 7)
    {
        numberCPF.value += ".";    
    }
    if(cpf.length === 11)
    {
        numberCPF.value += "-"; 
    }
}

//================================================================================================================================================================================

function maskPhone(numberPhone)
{
    var phone = numberPhone.value;

    if(phone.length < 10)
    {
        phone = phone.replace(/\D/g, "");
        //phone = phone.replace(/^(\d{2})(\d)/g, "($1)$2");
        phone = phone.replace(/(\d)(\d{3})$/, "$1-$2");
        numberPhone.value = phone;
    }
}

//================================================================================================================================================================================

function maskDDD(numberDDD)
{
    var ddd = numberDDD.value;

    if(ddd.length < 4)
    {
        //ddd = ddd.replace(/\D/g, "");
        //ddd = ddd.replace(/^(\d{2})(\d)/g, "($1)");
        ddd = ddd.replace(/^(\d\d)(\d{0,5})/,"($1)");
        numberDDD.value = ddd;
    }
}
//================================================================================================================================================================================
function limpa_formulário_cep() 
{   
    //Limpa valores do formulário de cep.
    document.getElementById('rua').value=("");
    document.getElementById('bairro').value=("");
    document.getElementById('cidade').value=("");
    document.getElementById('uf').value=("");
}

function meu_callback(conteudo) 
{
    if (!("erro" in conteudo)) 
    { 
        //Desabilita os campos
        document.getElementById("rua").readOnly = true;
        document.getElementById("bairro").readOnly = true;
        document.getElementById("cidade").readOnly = true;
        document.getElementById("uf").readOnly = true;

        //Verifica se o retorno da API trouxe os campos rua e bairro vazios
        if((document.getElementById('rua').value=(conteudo.logradouro).length == 0) && (document.getElementById('bairro').value=(conteudo.bairro).length == 0))
        {
            //Deixa os campos rua e bairro habilitados
            document.getElementById('rua').readOnly = false;
            document.getElementById('bairro').readOnly = false;
             
            //Limpa o formulário para impedir que apareça true nos campos
            limpa_formulário_cep();
            
            //Prenche os campos abaixo com o retorno da API
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);
        }
        else
        {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);
        }
    } //end if.
    else 
    {
        //CEP não Encontrado.
        limpa_formulário_cep();
        alert("CEP não encontrado.");
    }
}

function pesquisacep(valor) 
{
    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") 
    {
        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if(validacep.test(cep)) 
        {
            document.getElementById('cep').value = cep.substring(0,5)+"-"+cep.substring(5);
        
            //Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('rua').value="...";
            document.getElementById('bairro').value="...";
            document.getElementById('cidade').value="...";
            document.getElementById('uf').value="...";
        
            
            //Cria um elemento javascript.
            var script = document.createElement('script');

            //Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);

        } //end if.
        else 
        {
            //cep é inválido.
            limpa_formulário_cep();
            alert("Formato de CEP inválido.");
        }
    } //end if.
    else 
    {
        //cep sem valor, limpa formulário.
        limpa_formulário_cep();
    }
};
//===============================================================================================================================================================================================================================
function limpa_formulário_cep_modal() 
{   
    //Limpa valores do formulário de cep.
    document.getElementById('rua_modal').value=("");
    document.getElementById('bairro_modal').value=("");
    document.getElementById('cidade_modal').value=("");
    document.getElementById('uf_modal').value=("");
}

function meu_callback_modal(conteudo) 
{
    if (!("erro" in conteudo)) 
    { 
        //Desabilita os campos
        document.getElementById("rua_modal").readOnly = true;
        document.getElementById("bairro_modal").readOnly = true;
        document.getElementById("cidade_modal").readOnly = true;
        document.getElementById("uf_modal").readOnly = true;

        //Verifica se o retorno da API trouxe os campos rua e bairro vazios
        if((document.getElementById('rua_modal').value=(conteudo.logradouro).length == 0) && (document.getElementById('bairro_modal').value=(conteudo.bairro).length == 0))
        {
            //Deixa os campos rua e bairro habilitados
            document.getElementById('rua_modal').readOnly = false;
            document.getElementById('bairro_modal').readOnly = false;
             
            //Limpa o formulário para impedir que apareça true nos campos
            limpa_formulário_cep_modal();
            
            //Prenche os campos abaixo com o retorno da API
            document.getElementById('cidade_modal').value=(conteudo.localidade);
            document.getElementById('uf_modal').value=(conteudo.uf);
        }
        else
        {
            //Atualiza os campos com os valores.
            document.getElementById('rua_modal').value=(conteudo.logradouro);
            document.getElementById('bairro_modal').value=(conteudo.bairro);
            document.getElementById('cidade_modal').value=(conteudo.localidade);
            document.getElementById('uf_modal').value=(conteudo.uf);
        }
    } //end if.
    else 
    {
        //CEP não Encontrado.
        limpa_formulário_cep_modal();
        alert("CEP não encontrado.");
    }
}

function pesquisacep_modal(valor) 
{
    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") 
    {
        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if(validacep.test(cep)) 
        {
            document.getElementById('cep_modal').value = cep.substring(0,5)+"-"+cep.substring(5);
        
            //Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('rua_modal').value="...";
            document.getElementById('bairro_modal').value="...";
            document.getElementById('cidade_modal').value="...";
            document.getElementById('uf_modal').value="...";
        
            
            //Cria um elemento javascript.
            var script = document.createElement('script');

            //Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback_modal';

            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);

        } //end if.
        else 
        {
            //cep é inválido.
            limpa_formulário_cep_modal();
            alert("Formato de CEP inválido.");
        }
    } //end if.
    else 
    {
        //cep sem valor, limpa formulário.
        limpa_formulário_cep_modal();
    }
};
/*function limpa_formulário_cep() 
{
    for (var i = 1; i <= 2; i++)
    {
        //Limpa valores do formulário de cep.
        document.getElementById('rua'+i).value=("");
        document.getElementById('bairro'+i).value=("");
        document.getElementById('cidade'+i).value=("");
        document.getElementById('uf'+i).value=("");
    }
    
}

function meu_callback(conteudo) 
{
    if (!("erro" in conteudo)) 
    { 
        var cont = 1;
        //Desabilita os campos
        for (var i = 1; i <= 2; i++)
        {
            document.getElementById("rua"+i).readOnly = true;
            document.getElementById("bairro"+i).readOnly = true;
            document.getElementById("cidade"+i).readOnly = true;
            document.getElementById("uf"+i).readOnly = true;

            //Verifica se o retorno da API trouxe os campos rua e bairro vazios
            if((document.getElementById('rua'+i).value=(conteudo.logradouro).length == 0) && (document.getElementById('bairro'+i).value=(conteudo.bairro).length == 0))
            {
                document.getElementById('rua'+i).readOnly = false;
                document.getElementById('bairro'+i).readOnly = false;
                if(cont == 1)
                {
                    limpa_formulário_cep();
                }
                
                document.getElementById('cidade'+i).value=(conteudo.localidade);
                document.getElementById('uf'+i).value=(conteudo.uf);

            }
            else
            {
                //Atualiza os campos com os valores.
                
                document.getElementById('rua'+i).value=(conteudo.logradouro);
                document.getElementById('bairro'+i).value=(conteudo.bairro);
                document.getElementById('cidade'+i).value=(conteudo.localidade);
                document.getElementById('uf'+i).value=(conteudo.uf);
            }

            cont++;
        }    
        //document.getElementById('rua').value=(conteudo.logradouro);
        //document.getElementById('bairro').value=(conteudo.bairro);
        //document.getElementById('cidade').value=(conteudo.localidade);
        //document.getElementById('uf').value=(conteudo.uf);
    } //end if.
    else 
    {
        //CEP não Encontrado.
        limpa_formulário_cep();
        alert("CEP não encontrado.");
    }
}

function pesquisacep(valor) 
{
 
    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") 
    {

        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if(validacep.test(cep)) 
        {
            
            
            
            for (var i = 1; i >= 1; i++)
            {
                document.getElementById('cep'+i).value = cep.substring(0,5)+"-"+cep.substring(5);
            
                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua'+i).value="...";
                document.getElementById('bairro'+i).value="...";
                document.getElementById('cidade'+i).value="...";
                document.getElementById('uf'+i).value="...";
            }
            
            //Cria um elemento javascript.
            var script = document.createElement('script');

            //Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);

        } //end if.
        else 
        {
            //cep é inválido.
            limpa_formulário_cep();
            alert("Formato de CEP inválido.");
        }
    } //end if.
    else 
    {
        //cep sem valor, limpa formulário.
        limpa_formulário_cep();
    }
};*/





