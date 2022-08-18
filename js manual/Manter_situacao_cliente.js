$(document).ready(function() {
    $('#listar_situacao_cliente').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "list_situacao_cliente.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewSituacaoCliente = document.getElementById("form-cad-sitcliente");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadSituacaoClienteModal"));
if(formNewSituacaoCliente)
{   
    formNewSituacaoCliente.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm = new FormData(formNewSituacaoCliente);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_situacao_cliente.php',
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
            formNewSituacaoCliente.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_situacao_cliente').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editSituacaoClienteModal"));
async function editSituacaoCliente(idSituacao_cliente)
{
    //Enviar os dados para um arquivo php
    const dados = await fetch("vizualizar_situacao_cliente.php?idSituacao_cliente=" + idSituacao_cliente);

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
        document.getElementById("editId").value = resposta['dados'].idSituacao_cliente;
        document.getElementById("editnome_sitcliente").value = resposta['dados'].Nome_situacao;
    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditSituacaoCliente = document.getElementById("form-edit-sitcliente");
if(formEditSituacaoCliente)
{
    //Verifica se o usuário clicou no botão
    formEditSituacaoCliente.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm = new FormData(formEditSituacaoCliente);

        //Enviar os dados para um arquivo php
        const dados = await fetch("editar_situacao_cliente.php",
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
            formEditSituacaoCliente.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_situacao_cliente').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarSituacaoCliente(idSituacao_cliente)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_situacao_cliente.php?idSituacao_cliente=" + idSituacao_cliente);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_situacao_cliente').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}
