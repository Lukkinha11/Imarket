$(document).ready(function() {
    $('#listar_unidade_medida').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "list_unidade_medida.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewUnidadeMedida = document.getElementById("form-cad-unmedida");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadUnidadeMedidaModal"));
if(formNewUnidadeMedida)
{   
    formNewUnidadeMedida.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm = new FormData(formNewUnidadeMedida);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_unidade_medida.php',
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
            formNewUnidadeMedida.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_unidade_medida').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editUnidadeMedidaModal"));
async function editUnMedida(idUnidade_medidas)
{
    //Enviar os dados para um arquivo php
    const dados = await fetch("vizualizar_unidade_medida.php?idUnidade_medidas=" + idUnidade_medidas);

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
        document.getElementById("editId").value = resposta['dados'].idUnidade_medidas;
        document.getElementById("edit_descmedida").value = resposta['dados'].Desc_medida;
        document.getElementById("edit_sigla").value = resposta['dados'].Sigla;
    }
    else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditUnMedida = document.getElementById("form-edit-unmedida");
if(formEditUnMedida)
{
    //Verifica se o usuário clicou no botão
    formEditUnMedida.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm = new FormData(formEditUnMedida);

        //Enviar os dados para um arquivo php
        const dados = await fetch("editar_unidade_medida.php",
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
            formEditUnMedida.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_unidade_medida').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarUnMedida(idUnidade_medidas)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_unidade_medida.php?idUnidade_medidas=" + idUnidade_medidas);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_unidade_medida').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}

