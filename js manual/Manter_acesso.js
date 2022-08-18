$(document).ready(function() {
    $('#listar_acesso').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "list_acesso.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewAcesso = document.getElementById("form-cad-acesso");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadAcessoModal"));
if(formNewAcesso)
{   
    formNewAcesso.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm_cad = new FormData(formNewAcesso);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_acesso.php',
        {
            method: "POST",
            body: dadosForm_cad
        });

        //Lê a variavel $retorna do arquivo php
        const resposta = await dados.json();

        //Mostrar menssagens
        if(resposta['status'])
        {
            document.getElementById("msgAlertErroCad").innerHTML = "";
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Limpar o formulário
            formNewAcesso.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_acesso').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editAcessoModal"));
async function editAcesso(idAcesso)
{
    //Enviar os dados para um arquivo php
    const dados = await fetch("vizualizar_acesso.php?idAcesso=" + idAcesso);

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
        document.getElementById("editId").value = resposta['dados'].idAcesso;
        document.getElementById("edit_acesso").value = resposta['dados'].Nivel;
    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditAcesso = document.getElementById("form-edit-acesso");
if(formEditAcesso)
{
    //Verifica se o usuário clicou no botão
    formEditAcesso.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosForm_edit = new FormData(formEditAcesso);

        //Enviar os dados para um arquivo php
        const dados = await fetch("editar_acesso.php",
        {
            method: "POST",
            body: dadosForm_edit
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
            formEditAcesso.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_acesso').DataTable();
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

async function apagarAcesso(idAcesso)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_acesso.php?idAcesso=" + idAcesso);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
            

            //Atualizar a lista de registros
            listarDataTables = $('#listar_acesso').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}