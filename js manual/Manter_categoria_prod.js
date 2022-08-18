$(document).ready(function() {
    $('#listar_categoria_prod').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "list_categoria_prod.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

//Receber dados do formulario de cadastro
const formNewCat = document.getElementById("form-cad-catprod");
const fecharModalCad = new bootstrap.Modal(document.getElementById("cadCategoriaPordutoModal"));
if(formNewCat)
{
    formNewCat.addEventListener("submit", async(e) =>
    {
        //Não recarregar a pagina
        e.preventDefault();

        const dadosForm = new FormData(formNewCat);

        //Enviar os dados para um arquivo externo
        const dados = await fetch("cadastrar_categoria_prod.php",
        {
            method: "POST",
            body: dadosForm
        });

        //ler a variavel $retorna do arquivo php
        const resposta = await dados.json();

        //Exibir menssagens
        if(resposta['status'])
        {
            document.getElementById("msgAlertErroCad").innerHTML = "";
            document.getElementById("msgAlert").innerHTML = resposta['msg'];

            //Limpar o formulário
            formNewCat.reset();

            //Fechar a janela modal
            fecharModalCad.hide();

            //Atualizar o Data Tables
            listarDataTables = $('#listar_categoria_prod').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroCad").innerHTML = resposta['msg'];
        }
    });
}

const editModal = new bootstrap.Modal(document.getElementById("editCategoriaPordutoModal"));
async function editCategoria(idCategoria)
{
    //Enviar os dados para um arquivo externo
    const dados = await fetch('vizualizar_categoria.php?idCategoria=' + idCategoria);

    //ler a variavel $retorna do arquivo php
    const resposta = await dados.json();

    //console.log(resposta);

    if(resposta['status'])
    {
        document.getElementById("msgAlertErroEdit").innerHTML = "";
        document.getElementById("msgAlert").innerHTML = "";
        editModal.show();

        document.getElementById("editId").value = resposta['dados'].idCategoria;
        document.getElementById("edit_catprod").value = resposta['dados'].Categoria;
        document.getElementById("edit_catdir").value = resposta['dados'].Categoria_diretorio;
    }
    else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }
}

const formEditCat = document.getElementById("form-edit-catprod");
if(fecharModalCad)
{
    //Receber dados do formulario
    formEditCat.addEventListener("submit", async(e) =>
    {
        //Não recarregar a pagina
        e.preventDefault();

        const dadosForm = new FormData(formEditCat);

         //Enviar os dados para um arquivo externo
        const dados = await fetch("editar_categoria_prod.php",
        {
            method: "POST",
            body: dadosForm
        });

        const resposta = await dados.json();

        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
            document.getElementById("msgAlertErroEdit").innerHTML = "";

            //Limpar o fomulário
            formEditCat.reset();
            //Fechar a janela Modal
            editModal.hide(); 

            //Atualizar o Data Tables
            listarDataTables = $('#listar_categoria_prod').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlertErroEdit").innerHTML = resposta['msg'];
        }

    });
}

async function apagarCategoria(idCategoria)
{
    var confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?")

    if(confirmar)
    {
        const dados = await fetch("apagar_categoria_prod.php?idCategoria=" + idCategoria)
    
        const resposta = await dados.json();

        if(resposta['status'])
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg']; 
            
            //Atualizar o Data Tables
            listarDataTables = $('#listar_categoria_prod').DataTable();
            listarDataTables.draw();
        }
        else
        {
            document.getElementById("msgAlert").innerHTML = resposta['msg'];
        }
    }

    
}
