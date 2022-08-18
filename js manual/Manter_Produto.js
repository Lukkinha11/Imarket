$(document).ready(function() {
    $('#listar_produto').DataTable( {
        scrollX: true,
        "processing": true,
        "serverSide": true,
        "ajax": "list_produto.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//PESQUISAR MARCA
async function carregar_marca(valor)
{
    //Conta a quantidade de caracteres
    if(valor.length >= 1) 
    {
        //console.log("Pesquisar: " + valor);

        const dados = await fetch('select_categoria.php?Desc_Marca=' + valor);

        const resposta = await dados.json();

        //console.log(resposta);

        var html = "<ul class='list-group position-fixed'>";

        if(resposta['erro'])
        {
            html += "<li class='list-group-item disabled'>" + resposta['msg'] + "</li>";
        }
        else
        {
            for(i = 0; i < resposta['dados'].length; i++)
            {
                html += "<li class='list-group-item list-group-item-action' onclick='get_id_marca(" + resposta['dados'][i].idMarca + "," + JSON.stringify(resposta['dados'][i].Desc_Marca) + ")'>" + resposta['dados'][i].Desc_Marca + "</li>";
            }     
        }

        html += '</ul>';

        document.getElementById('resultado_pesquisa').innerHTML = html;
        document.getElementById('resultado_pesquisa2').innerHTML = html;
    }
}

function get_id_marca(idMarca, Desc_Marca)
{
    //console.log("id da marca selecionada: " + idMarca);
    //console.log("Desc_Marca da marca selecionada: " + Desc_Marca);

    document.getElementById('nome_marcaprod').value = Desc_Marca;
    document.getElementById('id_marca').value = idMarca;
    document.getElementById('editnome_marcaprod').value = Desc_Marca;
    document.getElementById('id_marcaedit').value = idMarca;
}

const fechar = document.getElementById('nome_marcaprod');
document.addEventListener('click', function(event)
{
    const validar_clique = fechar.contains(event.target);
    if(!validar_clique)
    {
        document.getElementById('resultado_pesquisa').innerHTML = "";
        document.getElementById('resultado_pesquisa2').innerHTML = "";
    }
});

//Receber dados do formulario de cadastro
const formNewproduto = document.getElementById("form-cad-produto");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadProdutoModal"));
if(formNewproduto)
{   
    formNewproduto.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm = new FormData(formNewproduto);

        //console.log(dadosForm);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_produto.php',
        {
            method: "POST",
            body: dadosForm
        });


        //Lê a variavel $retorna do arquivo php
        const resposta = await dados.json();

        //console.log(resposta);

        //Mostrar menssagens
        if(resposta['status'])
        {
            document.getElementById("msgAlertErroCad").innerHTML = "";
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Limpar o formulário
            formNewproduto.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_produto').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}


async function vizuProduto(id_Produto)
{
    //console.log("Acessou: " + id_Produto);
    //Enviar os dados para um arquivo php
    const dados = await fetch("vizualizar_produto.php?id_Produto=" + id_Produto);

    //await fetch("manter_produto.php?id_Produto=" + id_Produto);

    // ler a constante dados
    const resposta = await dados.json();
    //console.log(resposta);

    if(resposta['status'])
    {
        //document.getElementById("msgAlertErroEdit").innerHTML = "";
        
        //Carregar janela modal
        const vizuModal = new bootstrap.Modal(document.getElementById("vizuProdutoModal"));
        vizuModal.show();

        //Enviar id do campo a ser alterado para o input que está oculto, bem como o valor do campo a ser alterado
        document.getElementById("idprod").innerHTML = resposta['dados'].id_Produto ;
        document.getElementById("nomeprod").innerHTML = resposta['dados'].Nome_prod;
        document.getElementById("descprod").innerHTML = resposta['dados'].Desc_prod;
        document.getElementById("unmedida").innerHTML = resposta['dados'].Desc_medida;
        document.getElementById("imgprod").src = resposta['dados'].caminho;
        document.getElementById("cdprod").innerHTML = resposta['dados'].Codigo_Barras;
        document.getElementById("catprod").innerHTML = resposta['dados'].Categoria;
        document.getElementById("marcaprod").innerHTML = resposta['dados'].Desc_Marca;

        document.getElementById("msgAlert").innerHTML = "";

    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}


const editModal = new bootstrap.Modal(document.getElementById("editProdutoModal"));
async function editProduto(id_Produto)
{
    //console.log("Editar:" + idTipo_Logradouro);

   const dados = await fetch('vizualizar_produto.php?id_Produto='+id_Produto);
   const resposta = await dados.json();
   //console.log(resposta);
   
   if(resposta['status'])
   {
        document.getElementById("msgAlertErroEdit").innerHTML = "";
        document.getElementById('msgAlert').innerHTML = "";

        editModal.show();  

        document.getElementById("editId").value = resposta['dados'].id_Produto;
        document.getElementById("editnome_produto").value = resposta['dados'].Nome_prod;
        document.getElementById("editnome_descprod").value = resposta['dados'].Desc_prod;
        document.getElementById("edit_und_medida").value = resposta['dados'].unidade_medidas_idUnidade_medidas;
        document.getElementById("editnome_cdbarras").value = resposta['dados'].Codigo_Barras;
        document.getElementById("edit_categoria").value = resposta['dados'].Categoria_Prod_idCategoria;
        document.getElementById("editnome_marcaprod").value = resposta['dados'].Desc_Marca;
        document.getElementById("id_marcaedit").value = resposta['dados'].Marca_idMarca;
        document.getElementById("nome_img").value = resposta['dados'].Image;
   }
   else
   {
        document.getElementById('msgAlert').innerHTML = resposta['msg'];
   }
}


const formEditProduto = document.getElementById("form-edit-produto");
if(formEditProduto)
{
    //Verifica se o usuário clicou no botão
    formEditProduto.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm = new FormData(formEditProduto);

        //Enviar os dados para um arquivo php
        const dados = await fetch("editar_produto.php",
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
            formEditProduto.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_produto').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarProduto(id_Produto)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_produto.php?id_Produto=" + id_Produto);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_produto').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}