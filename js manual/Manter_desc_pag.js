$(document).ready(function() {
    $('#listar_desc_pag').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "list_desc_pag.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewDescPag = document.getElementById("form-cad-descpag");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadDescPagModal"));
if(formNewDescPag)
{   
    formNewDescPag.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm = new FormData(formNewDescPag);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_desc_pag.php',
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
            formNewDescPag.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_desc_pag').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editDecPagModal"));
async function editDescPag(idDesc_pag)
{
    //Enviar os dados para um arquivo php
    const dados = await fetch("vizualizar_desc_pag.php?idDesc_pag=" + idDesc_pag);

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
        document.getElementById("editId").value = resposta['dados'].idDesc_pag;
        document.getElementById("editnome_formapag").value = resposta['dados'].Forma_pag;
        document.getElementById("editnome_quantparcelas").value = resposta['dados'].Quant;

    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditDescPag = document.getElementById("form-edit-descpag");
if(formEditDescPag)
{
    //Verifica se o usuário clicou no botão
    formEditDescPag.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm = new FormData(formEditDescPag);

        //Enviar os dados para um arquivo php
        const dados = await fetch("editar_desc_pag.php",
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
            formEditDescPag.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_desc_pag').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarDescPag(idDesc_pag)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_desc_pag.php?idDesc_pag=" + idDesc_pag);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_desc_pag').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}