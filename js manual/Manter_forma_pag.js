$(document).ready(function() {
    $('#listar_forma_pag').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "list_forma_pag.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewFormaPag = document.getElementById("form-cad-formapag");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadFormaPagModal"));
if(formNewFormaPag)
{   
    formNewFormaPag.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm = new FormData(formNewFormaPag);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_forma_pag.php',
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
            formNewFormaPag.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_forma_pag').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editFormaPagModal"));
async function editFormaPag(idForma_Pag)
{
    //Enviar os dados para um arquivo php
    const dados = await fetch("vizualizar_forma_pag.php?idForma_Pag=" + idForma_Pag);

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
        document.getElementById("editId").value = resposta['dados'].idForma_Pag;
        document.getElementById("edit_descpag").value = resposta['dados'].Descricao;
        document.getElementById("edit_formapag").value = resposta['dados'].Desc_pag_idDesc_pag;
    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditFormaPag = document.getElementById("form-edit-formapag");
if(formEditFormaPag)
{
    //Verifica se o usuário clicou no botão
    formEditFormaPag.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm = new FormData(formEditFormaPag);

        //Enviar os dados para um arquivo php
        const dados = await fetch("editar_forma_pag.php",
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
            formEditFormaPag.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_forma_pag').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarFormaPag(idForma_Pag)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_forma_pag.php?idForma_Pag=" + idForma_Pag);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_forma_pag').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}