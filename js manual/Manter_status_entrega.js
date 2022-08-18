$(document).ready(function() {
    $('#listar_status_entrega').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "list_status_entrega.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewStatus = document.getElementById("form-cad-statusentrega");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadStatusEntregaModal"));
if(formNewStatus)
{   
    formNewStatus.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm = new FormData(formNewStatus);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_status_entrega.php',
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
            formNewStatus.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_status_entrega').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editStatusEntregaModal"));
async function editStatusEntrega(idStatus_Entrega)
{
    //Enviar os dados para um arquivo php
    const dados = await fetch("vizualizar_status_entrega.php?idStatus_Entrega=" + idStatus_Entrega);

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
        document.getElementById("editId").value = resposta['dados'].idStatus_Entrega;
        document.getElementById("editnome_status").value = resposta['dados'].Status;
    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditStatus = document.getElementById("form-edit-status");
if(formEditStatus)
{
    //Verifica se o usuário clicou no botão
    formEditStatus.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm = new FormData(formEditStatus);

        //Enviar os dados para um arquivo php
        const dados = await fetch("editar_status_entrega.php",
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
            formEditStatus.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_status_entrega').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarStatusEntrega(idStatus_Entrega)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_status_entrega.php?idStatus_Entrega=" + idStatus_Entrega);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_status_entrega').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
    
    
}
