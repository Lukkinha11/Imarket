$(document).ready(function() {
    $('#listar_funcionarios').DataTable( {
        scrollX: true,
        "processing": true,
        "serverSide": true,
        "ajax": "list_funcionarios.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewFuncionarios = document.getElementById("inserir");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadFuncionariosModal"));
if(formNewFuncionarios)
{   
    formNewFuncionarios.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm = new FormData(formNewFuncionarios);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_funcionarios.php',
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
            formNewFuncionarios.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_funcionarios').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editFuncionariosModal"));
async function editFunc(idFuncionarios)
{
    //Enviar os dados para um arquivo php
    const dados = await fetch("vizualizar_funcionarios.php?idFuncionarios=" + idFuncionarios);

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
        document.getElementById("editId").value = resposta['dados'].idFuncionarios;
        document.getElementById("txtEditNome").value = resposta['dados'].Nome;
        document.getElementById("txtEditSobrenome").value = resposta['dados'].Sobrenome;
        document.getElementById("txtEditLogin").value = resposta['dados'].Login;
        document.getElementById("Editstatus").value = resposta['dados'].Status;
        document.getElementById("txtEditCPF").value = resposta['dados'].CPF;
        document.getElementById("txtEditDataNascimento").value = resposta['dados'].Data_nasc;
        document.getElementById("edit_selectacesso").value = resposta['dados'].Acesso_idAcesso;
        document.getElementById("txtEditEmail").value = resposta['dados'].Email;
        document.getElementById("Editcargo").value = resposta['dados'].Cargo_idCargo;
        document.getElementById("txtEditDDD").value = resposta['dados'].DDD;
        document.getElementById("txtEditTelefone").value = resposta['dados'].Telefone;
        document.getElementById("cep_modal").value = resposta['dados'].Cep;
        document.getElementById("rua_modal").value = resposta['dados'].Logradouro;
        document.getElementById("bairro_modal").value = resposta['dados'].Bairro;
        document.getElementById("cidade_modal").value = resposta['dados'].Cidade;
        document.getElementById("uf_modal").value = resposta['dados'].Estado;
        document.getElementById("txtEditComplemento").value = resposta['dados'].Complemento;
    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditFunc = document.getElementById("form-edit-func");
if(formEditFunc)
{
    //Verifica se o usuário clicou no botão
    formEditFunc.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dadosFormFunc = new FormData(formEditFunc);

        //Enviar os dados para um arquivo php
        const dados_edit_func = await fetch("editar_funcionarios.php",
        {
            method: "POST",
            body: dadosFormFunc
        });

        //ler a constante dados
        const resposta = await dados_edit_func.json();

        //Retorno true ou false da varavel $retorna
        if(resposta['status'])
        {
            //Fechar a janela Modal
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
            document.getElementById("msgAlertErroEdit").innerHTML = "";

            //Limpar o formulário
            formEditFunc.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_funcionarios').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarFunc(idFuncionarios)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados = await fetch("apagar_funcionarios.php?idFuncionarios=" + idFuncionarios);
        const resposta = await dados.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_funcionarios').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}

