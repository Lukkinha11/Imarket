$(document).ready(function() {
    $('#listar_status_item').DataTable( {
        scrollX: true,
        "processing": true,
        "serverSide": true,
        "ajax": "list_status_item.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewStatusItem = document.getElementById("form-cad-statusitem");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadStatusItemModal"));
if(formNewStatusItem)
{   
    formNewStatusItem.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm_status_item = new FormData(formNewStatusItem);

        //Enviar os dados para um arquivo php
        const dados_status_compra = await fetch('cadastrar_status_item.php',
        {
            method: "POST",
            body: dadosForm_status_item
        });

        //Lê a variavel $retorna do arquivo php
        const resposta = await dados_status_compra.json();

        //Mostrar menssagens
        if(resposta['status'])
        {
            document.getElementById("msgAlertErroCad").innerHTML = "";
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Limpar o formulário
            formNewStatusItem.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_status_item').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editStatusItemModal"));
async function editStatusItem(idStatus_item)
{
    //Enviar os dados para um arquivo php
    const dados_vizu_status_compra = await fetch("vizualizar_status_item.php?idStatus_item=" + idStatus_item);

    // ler a constante dados
    const resposta = await dados_vizu_status_compra.json();
    //console.log(resposta);

    if(resposta['status'])
    {
        document.getElementById("msgAlertErroEdit").innerHTML = "";
        document.getElementById("msgAlert").innerHTML = "";

        //Carregar janela modal
        editModal.show();

        //Enviar id do campo a ser alterado para o input que está oculto, bem como o valor do campo a ser alterado
        document.getElementById("editId").value = resposta['dados'].idStatus_item;
        document.getElementById("edit_status_item").value = resposta['dados'].Status_item;
    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditStatusItem = document.getElementById("form-edit-statusitem");
if(formEditStatusItem)
{
    //Verifica se o usuário clicou no botão
    formEditStatusItem.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm_edit_status_item = new FormData(formEditStatusItem);

        //Enviar os dados para um arquivo php
        const dados_result_item = await fetch("editar_status_item.php",
        {
            method: "POST",
            body: dadosForm_edit_status_item
        });

        //ler a constante dados
        const resposta = await dados_result_item.json();

        //Retorno true ou false da varavel $retorna
        if(resposta['status'])
        {
            //Fechar a janela Modal
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
            document.getElementById("msgAlertErroEdit").innerHTML = "";

            //Limpar o formulário
            formEditStatusItem.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_status_item').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarStatusItem(idStatus_item)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados_delete_status_compra = await fetch("apagar_status_item.php?idStatus_item=" + idStatus_item);
        const resposta = await dados_delete_status_compra.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
            
            //Atualizar a lista de registros
            listarDataTables = $('#listar_status_item').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}