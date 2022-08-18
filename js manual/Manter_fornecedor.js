$(document).ready(function() {
    $('#listar_fornecedor').DataTable( {
        scrollX: true,
        "processing": true,
        "serverSide": true,
        "ajax": "list_fornecedor.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Máscara CNPJ
document.getElementById('txtCNPJ').addEventListener('input', function (e) 
{
    var x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})/);
    e.target.value = !x[2] ? x[1] : x[1] + '.' + x[2] + '.' + x[3] + '/' + x[4] + (x[5] ? '-' + x[5] : '');
});

//Receber dados do formulario de cadastro
const formNewFornecedor = document.getElementById("form-cad-fornecedor");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadFornecedorModal"));
if(formNewFornecedor)
{   
    formNewFornecedor.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        const dadosForm = new FormData(formNewFornecedor);

        //Enviar os dados para um arquivo php
        const dados = await fetch('cadastrar_fornecedor.php',
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
            formNewFornecedor.reset();

            //Fechar modal ao cadastrar
            fecharModalCad.hide();

            //Atualizar a tabela
            listarDataTables = $('#listar_fornecedor').DataTable();
            listarDataTables.draw();
        }else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editFornecedorModal"));
async function editFornecedor(idFornecedor)
{
    //Enviar os dados para um arquivo php
    const dados_vizu_fornecedor = await fetch("vizualizar_fornecedor.php?idFornecedor=" + idFornecedor);

    // ler a constante dados
    const resposta = await dados_vizu_fornecedor.json();
    //console.log(resposta);

    if(resposta['status'])
    {
        document.getElementById("msgAlertErroEdit").innerHTML = "";
        document.getElementById("msgAlert").innerHTML = "";

        //Carregar janela modal
        editModal.show();

        //Enviar id do campo a ser alterado para o input que está oculto, bem como o valor do campo a ser alterado
        document.getElementById("editId").value = resposta['dados'].idFornecedor;
        document.getElementById("edit_NomeFantasia").value = resposta['dados'].Nome_Fantasia;
        document.getElementById("edit_RazaoSocial").value = resposta['dados'].Razao_social;
        document.getElementById("edit_CNPJ").value = resposta['dados'].CNPJ;
        document.getElementById("edit_Email").value = resposta['dados'].Email;
        document.getElementById("edit_DDD").value = resposta['dados'].DDD;
        document.getElementById("edit_Telefone").value = resposta['dados'].Telefone;
        document.getElementById("cep_modal").value = resposta['dados'].Cep;
        document.getElementById("rua_modal").value = resposta['dados'].Logradouro;
        document.getElementById("bairro_modal").value = resposta['dados'].Bairro;
        document.getElementById("cidade_modal").value = resposta['dados'].Cidade;
        document.getElementById("uf_modal").value = resposta['dados'].Estado;
        document.getElementById("edit_Complemento").value = resposta['dados'].Complemento;
        document.getElementById("edit_Referencia").value = resposta['dados'].Referencia;
    }
    else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditFornecedor = document.getElementById("form-edit-fornecedor");
if(formEditFornecedor)
{
    //Verifica se o usuário clicou no botão
    formEditFornecedor.addEventListener("submit", async(e) =>
    {
        //Não dá refresh na página
        e.preventDefault();

        //Receber os dados do formulário
        const dados_edit_fornecedor = new FormData(formEditFornecedor);

        //Enviar os dados para um arquivo php
        const dados_result_fornecedor = await fetch("editar_fornecedor.php",
        {
            method: "POST",
            body: dados_edit_fornecedor
        });

        //ler a constante dados
        const resposta = await dados_result_fornecedor.json();

        //Retorno true ou false da varavel $retorna
        if(resposta['status'])
        {
            //Fechar a janela Modal
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
            document.getElementById("msgAlertErroEdit").innerHTML = "";

            //Limpar o formulário
            formEditFornecedor.reset();
            editModal.hide();

            //Atualizar a lista de registros
            listarDataTables = $('#listar_fornecedor').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }
    });
}

async function apagarFornecedor(idFornecedor)
{
    //confirmação de exclusão do registro
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        // A constante dados irá receber o retorno do arquivo
        const dados_delete_fornecedor = await fetch("apagar_fornecedor.php?idFornecedor=" + idFornecedor);
        const resposta = await dados_delete_fornecedor.json();

        //Retorno true ou false da varavel $retorna e exibe as mensagens
        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Atualizar a lista de registros
            listarDataTables = $('#listar_fornecedor').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }
}