$(document).ready(function() {
    $('#listar_estoque').DataTable( {
        scrollX: true,
        "processing": true,
        "serverSide": true,
        "ajax": "list_nota_fornecedor.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );
//======================================================================================================================================================================================================================

//Acessa os elemtops HTML do manter_estoque 
const DOMStrings = 
{
    btn: '#btn-adicionar',
    btn_cad: '#cadastrar',
    adicionaProduto: '#form-cad-estoque',
    tabelaNota: '#tabela-nota',
    error: '#notice',
    erro_cad : '#msgAlertErroCad'
    //btnexcluir: '#deletar'
}

var teste = 0;

//Inicia a modal com o botão de cadastro oculto
var btn_cadastrar = document.getElementById("cadastrar");
btn_cadastrar.setAttribute('style', 'display: none;');

$().ready(function() 
            {
                setTimeout(function () {
                    $('#tt').hide(); // "foo" é o id do elemento que seja manipular.
                }, 4000); // O valor é representado em milisegundos.
            });


//Após o botão: btn-adicionar, ser clicado acessa a função
document.querySelector(DOMStrings.btn).addEventListener('click', function(e) 
{
    //Não da refresh na página
    e.preventDefault();

    let form = document.querySelector(DOMStrings.adicionaProduto);
    $().ready(function() 
    {
        setTimeout(function () {
            $('#alerta').hide(); // "alerta" é o id do elemento que seja manipular.
        }, 4000); // O valor é representado em milisegundos.
    });

    if (form.InputProduto.value === ''  || form.InputQtd.value === '' || form.InputValor.value === '') 
    {
        return document.querySelector(DOMStrings.error).innerHTML = "<div id='alerta' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> É necessário preencher os campos Produto, Quantidade e Valor Unitário para adicionar! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }    
    if(form.InputQtd.value <=0)
    {
        return document.querySelector(DOMStrings.error).innerHTML =  "<div id='alerta' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> A Quantidade não pode ser igual ou menor que 0! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
    if(form.InputValor.value < 1)
    {
        return document.querySelector(DOMStrings.error).innerHTML =  "<div id='alerta' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> O Valor Unitário não pode ser igual ou menor que 0! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
    if(form.id_produto.value === '')
    {
        return document.querySelector(DOMStrings.error).innerHTML = "<div id='alerta' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> Produto não encontrado! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }


    let nota = criaNota(form);


    //Cria os elementos
    var notaTr = document.createElement("tr");
    var idTd = document.createElement("td");
    var produtoTd = document.createElement("td");
    var qtd_compraTd = document.createElement("td");
    var valor_unitTd = document.createElement("td");
    const btnexcluir = document.createElement("button");
          btnexcluir.setAttribute('type', 'button');
          btnexcluir.setAttribute('class', 'btn btn-info');
          //btnexcluir.classList.add('btn');
          //btnexcluir.classList.add('btn-danger');
          btnexcluir.innerHTML = "Excluir";
        //btnexcluir.className()+= "button"
        //btnexcluir.classList.add('btn-outline-primary');

    // aqui e passado "this" para a função, e this é o botao
    btnexcluir.setAttribute('onclick', 'Excluir(this)');

    //Obtem os valores dos inputs
    idTd.textContent = nota.idprod;
    produtoTd.textContent = nota.produto;
    qtd_compraTd.textContent = nota.quantidade;
    valor_unitTd.textContent = nota.valorUnit;
    //num_nfTd.textContent = passageiro.numeroNf;

    //Cria e adiciona na tabela os valores pegados dos inputs
    notaTr.appendChild(idTd);
    notaTr.appendChild(produtoTd);
    notaTr.appendChild(qtd_compraTd);
    notaTr.appendChild(valor_unitTd);
    notaTr.appendChild(btnexcluir);
    notaTr.id = 'linha' + teste;
    teste++;
    document.getElementById("tabela-nota").appendChild(notaTr);
 
    var tabelaNota = document.querySelector(DOMStrings.tabelaNota);
    tabelaNota.appendChild(notaTr);

    document.querySelector(DOMStrings.error).innerHTML = '';

    //Setar num nos campos após adicionar na tabela
    document.getElementById("InputProduto").value = null;
    document.getElementById("id_produto").value = null;
    document.getElementById("InputQtd").value = null;
    document.getElementById("InputValor").value = null;

    //Mostra o botão de cadastrar
    btn_cadastrar.setAttribute('style', 'display: block;');

   

    //form.reset();

    //form.inputNome.focus();

    //Ler linhas da tabela
    //var DadosTabela = [];

    //var lista = $('#tbLista tbody');  

    //$(lista).find("tr").each(function(index, tr) 
    //{
    
        //DadosTabela.push(JSON.stringify({ "#": $(tr).find('td:eq(0)').html(), "Produto": $(tr).find('td:eq(1)').html(),
                                          //"Quantidade": $(tr).find('td:eq(2)').html(), "Valor Unitário": $(tr).find('td:eq(3)').html()
                                        //}))
    
    //});

    //console.log(DadosTabela);

    //let json = JSON.parse(DadosTabela);

    //console.log(DadosTabela.length);

    
    

    //json.DadosTabela.forEach(linhas => 
        //console.log(linhas));

    //for(i=1; i <= DadosTabela.length; i++)
    //{
        /*const input = document.createElement("input");
        input.setAttribute('id', 'prod'+i);
        input.setAttribute('type', 'hidden');
        input_prod = document.getElementById('prod'+i).value = json.Produto;*/

        //console.log(json.Produto);
    //}
    //console.log( document.getElementById('prod1').value = json.Produto);
})


function AtualizaTabela()
{
    var DadosTabela = [];

    var lista = $('#tbLista tbody');  

    $(lista).find("tr").each(function(index, tr) 
    {
    
        DadosTabela.push(JSON.stringify({ "ID": $(tr).find('td:eq(0)').html(), 
                                          "Quantidade": $(tr).find('td:eq(2)').html(), 
                                          "Valor": $(tr).find('td:eq(3)').html()
                                        }))
    
    });
    //console.log(DadosTabela.length);
    if(DadosTabela.length < 1)
    {
        btn_cadastrar.setAttribute('style', 'display: none;');
    }
    return DadosTabela;
}

function criaNota(form) 
{
    let nota = {
        idprod: form.id_produto.value,
        produto: form.InputProduto.value,
        quantidade: form.InputQtd.value,
        valorUnit: form.InputValor.value.replace(",",".")
    }

    return nota;
}


function Excluir(botao) 
{
    var tabela = document.getElementById('tabela-nota');
    // a partir do botao, pega a linha com parentNode, e o indice da linha com rowIndex
    tabela.deleteRow(botao.parentNode.parentNode.rowIndex);

    var table = AtualizaTabela();

    //console.log(table);

}
//======================================================================================================================================================================================================================

//Permitir Apenas Números no input
var filtroTeclas = function(event) 
{
    return ((event.charCode >= 48 && event.charCode <= 57) || (event.keyCode == 45 || event.charCode == 46));
}
//======================================================================================================================================================================================================================

//Permitir Apenas Números e Vírgulas
jQuery(function($) 
{
    $(document).on('keypress', 'input.only-number', function(e) {
      var $this = $(this);
      var key = (window.event)?event.keyCode:e.which;
      var dataAcceptDot = $this.data('accept-dot');
      var dataAcceptComma = $this.data('accept-comma');
      var acceptDot = (typeof dataAcceptDot !== 'undefined' && (dataAcceptDot == true || dataAcceptDot == 1)?true:false);
      var acceptComma = (typeof dataAcceptComma !== 'undefined' && (dataAcceptComma == true || dataAcceptComma == 1)?true:false);
  
          if((key > 47 && key < 58)
        || (key == 46 && acceptDot)
        || (key == 44 && acceptComma)) {
          return true;
        } 
        else 
        {
            return (key == 8 || key == 0)?true:false;
        }
    });
});
//======================================================================================================================================================================================================================

//Funcão para valor R$
function moeda(a, e, r, t) 
{
    let n = ""
    , h = j = 0
    , u = tamanho2 = 0
    , l = ajd2 = ""
    , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
        h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++);
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2
    ) 
    {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}
//===============================================================================================================================================================================

//Função para listar produtos
async function carregar_produtos(valor)
{
    //Se a quantidade de caracteres for maior que 1 lista os produtos
    if(valor.length >= 1)
    {
        //console.log("Pesquisar: " + valor);

        //Enviar os dados para um arquivo php
        const dados_prod = await fetch('select_produto.php?Nome_prod=' + valor);

        //Lê a variavel $retorna do arquivo php
        const resposta = await dados_prod.json();

        //console.log(resposta);

        //Criação da lista
        var html = "<ul class='list-group position-fixed'>";

        //Verifica se o retorno do arquivo foi true ou seja que exite um erro
        if(resposta['erro'])
        {
            html += "<li class='list-group-item disabled'>" + resposta['msg'] + "</li>";
        }
        else
        {
            //Laço de repetição para ler as informações do array dados!
            for(i = 0; i < resposta['dados'].length; i++)
            {
                html += "<li class='list-group-item list-group-item-action' onclick='get_id_produto(" + resposta['dados'][i].id_Produto + "," + JSON.stringify(resposta['dados'][i].Nome_prod) + ")'>" + resposta['dados'][i].Nome_prod + "</li>";
            }     
        }

        html += '</ul>';

        // JSON.stringify(resposta['dados'][i].Nome_prod) = Converte o valor para uma string

        document.getElementById('resultado_pesquisa').innerHTML = html;
    }
}

//Função para receber o id do produto
function get_id_produto(id_Produto, Nome_prod)
{
    document.getElementById("id_produto").value = id_Produto;
    document.getElementById('InputProduto').value = Nome_prod;
}

//Fechar a lista após ser selecionado
const fechar = document.getElementById("InputProduto");

//Evento para validar o click na lista
document.addEventListener('click', function(event){

    const validar_clique = fechar.contains(event.target);

    //Se o validar_clique for diferente de true acessa o if
    if(!validar_clique)
    {
        document.getElementById('resultado_pesquisa').innerHTML = "";
    }
});
//======================================================================================================================================================================================================================

//FUNÇÃO PARA LISTAR FORNECEDORES
async function carregar_fornecedor(valor)
{
    //Se a quantidade de caracteres for maior ou igual a 1 lista os produtos
    if(valor.length >= 1)
    {
        //console.log("Pesquisar:" +valor);

        //Enviar os dados para um arquivo php
        const dados_fornecedor = await fetch('select_fornecedor.php?Razao_social=' + valor);

        //Lê a variavel $retorna do arquivo php
        const resposta = await dados_fornecedor.json();

        //console.log(resposta);

        //Criação da lista
        var html = "<ul class='list-group position-fixed'>";

        //Verifica se o retorno do arquivo foi true ou seja que exite um erro
        if(resposta['erro'])
        {
            html += "<li class='list-group-item disabled'>" + resposta['msg'] + "</li>";
        }
        else
        {
            //Laço de repetição para ler as informações do array dados!
            for(i = 0; i < resposta['dados'].length; i++)
            {
                html += "<li class='list-group-item list-group-item-action' onclick='get_id_fornecedor(" + resposta['dados'][i].idFornecedor + "," + JSON.stringify(resposta['dados'][i].Razao_social) + ")'>" + resposta['dados'][i].Razao_social + "</li>";
            }   
        }


        html += '</ul>';

        // JSON.stringify(resposta['dados'][i].Nome_prod) = Converte o valor para uma string

        document.getElementById('resultado_fornecedor').innerHTML = html;
    }
}

//Função para receber o id do fornecedor
function get_id_fornecedor(idFornecedor, Razao_social)
{
    document.getElementById("id_fornecedor").value = idFornecedor;
    document.getElementById('Nome_fornecedor').value = Razao_social;
}

//Fechar a lista após ser selecionado
const fechar_lista_fornecedor = document.getElementById("Nome_fornecedor");

//Evento para validar o click na lista
document.addEventListener('click', function(event){

    const validar_clique = fechar_lista_fornecedor.contains(event.target);

    //Se o validar_clique for diferente de true acessa o if
    if(!validar_clique)
    {
        document.getElementById('resultado_fornecedor').innerHTML = "";
    }
});
//===============================================================================================================================================================================

const formNewEstoque = document.getElementById("form-cad-estoque");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadEstoqueModal"));
document.querySelector(DOMStrings.btn_cad).addEventListener('click', function(e) 
{
    //Não da refresh na página
    e.preventDefault();

    let form = document.querySelector(DOMStrings.adicionaProduto);
    $().ready(function() 
    {
        setTimeout(function () {
            $('#exeption').hide(); // "exeption" é o id do elemento que seja manipular.
        }, 4000); // O valor é representado em milisegundos.
    });

    if (form.num_nf.value === '') 
    {
        return document.querySelector(DOMStrings.erro_cad).innerHTML = "<div id='exeption' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> Preencha o campo Número da Nota Fiscal! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
    if( form.serie_nf.value === '')
    {
        return document.querySelector(DOMStrings.erro_cad).innerHTML =  "<div id='exeption' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> Preencha o campo Serie da Nota Fiscal! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
    if( form.valor_nf.value === '')
    {
        return document.querySelector(DOMStrings.erro_cad).innerHTML =  "<div id='exeption' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> Preencha o campo Valor da Nota Fiscal! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
    if( form.data_nf.value === '')
    {
        return document.querySelector(DOMStrings.erro_cad).innerHTML =  "<div id='exeption' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> Preencha o campo Data da Compra! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }    
    if( form.desc_nf.value === '')
    {
        return document.querySelector(DOMStrings.erro_cad).innerHTML =  "<div id='exeption' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> Preencha o campo Descrição da Nota Fiscal! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
    if( form.id_fornecedor.value === '')
    {
        return document.querySelector(DOMStrings.erro_cad).innerHTML =  "<div id='exeption' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> Informe o Nome do Fornecedor! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
    if( form.pagamento.value === 'Selecione')
    {
        return document.querySelector(DOMStrings.erro_cad).innerHTML =  "<div id='exeption' class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Erro:</strong> Selecione a Forma de Pagamento! <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }

    var table_nf = AtualizaTabela();
    var dados_tbl = JSON.stringify(table_nf);
    
        $.ajax({
            url: '../adm/cadastrar_estoque.php',
            type: 'POST',
            data: {'num_nf': form.num_nf.value, 'serie_nf': form.serie_nf.value, 'tabela': dados_tbl, 'valor_nf': form.valor_nf.value, 
                   'data_nf': form.data_nf.value, 'desc_nf': form.desc_nf.value, 'id_fornecedor': form.id_fornecedor.value, 'pagamento': form.pagamento.value, 'parcelas': form.parcelas.value},
            success: function (result) {
                // Retorno se tudo ocorreu normalmente
                $('#statuss').html('OK')
                $('#msgAlert').html(result)
                if(formNewEstoque)
                {
                    //Fecha a Modal após ser efetuado o cadastro
                    fecharModalCad.hide();
                    //Atualizar a lista de registros
                    listarDataTables = $('#listar_estoque').DataTable();
                    listarDataTables.draw();
                    //Limpa as linhas da Tabela
                    $("#tbLista tr").remove();
                    //Limpa o formulário
                    formNewEstoque.reset();
                    //Oculta o botão de cadastro
                    btn_cadastrar.setAttribute('style', 'display: none;');
                    //Oculta a menssagem de sucesso
                    $().ready(function() {
                        setTimeout(function (){
                            $('#msgAlert').hide(); // "msgAlert" é o id do elemento que seja manipular.
                        }, 4000); // O valor é representado em milisegundos.
                    });
                }            
            }
        });

    /*$(document).ready(function () {

            $("#form-cad-estoque").submit(function(){
                alert("Form foi enviado");
                console.log("sucesso2");
                return false;
            })

        })*/
        
        
        
        /*if(formNewEstoque)
        {   
            formNewEstoque.addEventListener("submit", async(e) =>
            {
                //Não dá refresh na página
                e.preventDefault();

                const dadosForm = new FormData(formNewEstoque);

                //Enviar os dados para um arquivo php
                const dados_estoque = await fetch('cadastrar_estoque.php',
                {
                    method: "POST",
                    body: dadosForm
                });

                console.log("sucesso");
                //console.log(dadosForm);

                //Lê a variavel $retorna do arquivo php
                const resposta = await dados_estoque.json();

                //Mostrar menssagens
                if(resposta['status'])
                {
                    document.getElementById("msgAlertErroCad").innerHTML = "";
                    document.getElementById("msgAlert").innerHTML = resposta['msg'];

                    //Limpar o formulário
                    formNewEstoque.reset();

                    //Fechar modal ao cadastrar
                    fecharModalCad.hide();

                    //Atualizar a tabela
                    //listarDataTables = $('#listar_marca').DataTable();
                    //listarDataTables.draw();
                }else
                {
                    document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
                }
            });
        }*/
})

//===============================================================================================================================================================================

async function apagarNF(idCompra_fornecedor)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_nota_fornecedor.php?idCompra_fornecedor=" + idCompra_fornecedor);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_estoque').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}