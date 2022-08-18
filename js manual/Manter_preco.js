$(document).ready(function() {
    $('#listar_preco').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "list_preco.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

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

//Função para listar produtos
async function carregar_produtos(valor)
{
    //Se a quantidade de caracteres for maior que 2 lista os produtos
    if(valor.length >= 1)
    {
        //console.log("Pesquisar: " + valor);

        //Enviar os dados para um arquivo php
        const dados = await fetch('select_produto.php?Nome_prod=' + valor);

        //Lê a variavel $retorna do arquivo php
        const resposta = await dados.json();

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
        document.getElementById('resultado_pesquisa2').innerHTML = html;
    }
}

//Função para receber o id do produto
function get_id_produto(id_Produto, Nome_prod)
{
    //console.log("ID do produto selecionado: " + id_Produto);

    document.getElementById("id_produto").value = id_Produto;
    document.getElementById('nome_prod').value = Nome_prod;
    document.getElementById("edit_nome_prod").value = Nome_prod;
    document.getElementById('id_prodedit').value = id_Produto;
}

//Fechar a lista após ser selecionado
const fechar = document.getElementById("nome_prod");

//Evento para validar o click na lista
document.addEventListener('click', function(event){

    const validar_clique = fechar.contains(event.target);

    //Se o validar_clique for diferente de true acessa o if
    if(!validar_clique)
    {
        document.getElementById('resultado_pesquisa').innerHTML = "";
        document.getElementById('resultado_pesquisa2').innerHTML = "";
    }
});



//Receber dados do formulario de cadastro
const formNewPreco = document.getElementById("form-cad-preco");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadPrecoModal"));
if(formNewPreco)
{   
    formNewPreco.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm = new FormData(formNewPreco);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_preco.php',
        {
            method: "POST",
            body: dadosForm
        });

        //Lê a variavel $retorna do arquivo php
        const resposta = await dados.json();

        //Mostrar menssagens
        if(resposta['status'])
        {
            document.getElementById("msgAlertErroCad").innerHTML = "";
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Limpar o formulário
            formNewPreco.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_preco').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editPrecoModal"));
async function editPreco(idPreco )
{
    //Enviar os dados para um arquivo php
    const dados = await fetch("vizualizar_preco.php?idPreco=" + idPreco );

    // ler a constante dados
    const resposta = await dados.json();
    //console.log(resposta);

    if(resposta['status'])
    {
        document.getElementById("msgAlertErroEdit").innerHTML = "";
        document.getElementById("msgAlert").innerHTML = "";

        //Carregar janela modal
        editModal.show();

        //Enviar id do campo a ser alterado para o input que está oculto, bem como o valor do campo a ser alterado
        document.getElementById("editId").value = resposta['dados'].idPreco;
        document.getElementById("edit_valorunit").value = resposta['dados'].Valor_unit;
        document.getElementById("edit_valorcomercio").value = resposta['dados'].Valor_prod;
        document.getElementById("edit_valorpromo").value = resposta['dados'].Valor_novo;
        document.getElementById("edit_nome_prod").value = resposta['dados'].Nome_prod;
        document.getElementById("id_prodedit").value = resposta['dados'].Produto_Id_Produto;
        document.getElementById("edit_status").value = resposta['dados'].Status_preco;
    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditPreco = document.getElementById("form-edit-preco");
if(formEditPreco)
{
    //Verifica se o usuário clicou no botão
    formEditPreco.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm = new FormData(formEditPreco);

        //Enviar os dados para um arquivo php
        const dados = await fetch("editar_preco.php",
        {
            method: "POST",
            body: dadosForm
        });

        //ler a constante dados
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna
        if(resposta['status'])
        {
            //Fechar a janela Modal
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
            document.getElementById("msgAlertErroEdit").innerHTML = "";

            //Limpar o formulário
            formEditPreco.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_preco').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarPreco(idPreco)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_preco.php?idPreco=" + idPreco);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_preco').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}
