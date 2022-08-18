$(document).ready(function() {
    $('#listar_status_compra').DataTable( {
        scrollX: true,
        "processing": true,
        "serverSide": true,
        "ajax": "list_status_compra.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewStatusCompra = document.getElementById("form-cad-statuscompra");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadStatusCompraModal"));
if(formNewStatusCompra)
{   
    formNewStatusCompra.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm_status_compra = new FormData(formNewStatusCompra);

        //Enviar os dados para um arquivo php
        const dados_status_compra = await fetch('cadastrar_status_compra.php',
        {
            method: "POST",
            body: dadosForm_status_compra
        });

        //Lê a variavel $retorna do arquivo php
        const resposta = await dados_status_compra.json();

        //Mostrar menssagens
        if(resposta['status'])
        {
            document.getElementById("msgAlertErroCad").innerHTML = "";
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Limpar o formulário
            formNewStatusCompra.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_status_compra').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editStatusCompraModal"));
async function editStatusCompra(idStatus_compra)
{
    //Enviar os dados para um arquivo php
    const dados_vizu_status_compra = await fetch("vizualizar_status_compra.php?idStatus_compra=" + idStatus_compra);

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
        document.getElementById("editId").value = resposta['dados'].idStatus_compra ;
        document.getElementById("edit_status_compra").value = resposta['dados'].Status_compra ;
    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditStatusCompra = document.getElementById("form-edit-statuscompra");
if(formEditStatusCompra)
{
    //Verifica se o usuário clicou no botão
    formEditStatusCompra.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm_edit_status_compra = new FormData(formEditStatusCompra);

        //Enviar os dados para um arquivo php
        const dados = await fetch("editar_status_compra.php",
        {
            method: "POST",
            body: dadosForm_edit_status_compra
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
            formEditStatusCompra.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_status_compra').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
            // Iniciará quando todo o corpo do documento HTML estiver pronto.
            $().ready(function() 
            {
                setTimeout(function () {
                    $('#msgAlertErroEdit').hide(); // "foo" é o id do elemento que seja manipular.
                }, 2500); // O valor é representado em milisegundos.
            });
        }
    });
}

async function apagarStatusCompra(idStatus_compra)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados_delete_status_compra = await fetch("apagar_status_compra.php?idStatus_compra=" + idStatus_compra);
        const resposta = await dados_delete_status_compra.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
            

            //Atualizar a lista de registros
            listarDataTables = $('#listar_status_compra').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}