$(document).ready(function() {
    $('#listar_disponibilidade_estoque').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "list_disponibilidade_estoque.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );

const editModal = new bootstrap.Modal(document.getElementById("VerificaModal"));
async function VerificaEstoque(idCarrinho_Compra)
{
    //Enviar os dados para um arquivo php
    const dados = await fetch("verifica_estoque.php?idCarrinho_Compra=" + idCarrinho_Compra);

    // ler a constante dados
    const resposta = await dados.json();
    //console.log(resposta);

    if(resposta['status'])
    {
        document.getElementById("msgAlertErroEdit").innerHTML = "";
        document.getElementById("msgAlert").innerHTML = resposta['msg'];

        //Atualizar a lista de registros
        listarDataTables = $('#listar_disponibilidade_estoque').DataTable();
        listarDataTables.draw();  
    }
    else
    {
        //Carregar janela modal
        editModal.show();

        /*for(i = 0; i < 1; i++)
        {
            document.getElementById("teste").value = resposta[i];
        }*/

        //Enviar id do campo a ser alterado para o input que está oculto, bem como o valor do campo a ser alterado
        document.getElementById("idcarrinho").value = resposta['dados'].Carrinho_Compra_idCarrinho_Compra;

       
    }
}

async function vizuVenda(idCarrinho_Compra)
{
    //console.log("Acessou: " + id_Produto);
    //Enviar os dados para um arquivo php

    window.location.href="http://www.google.com_blank";
    /*const dados = await fetch("vizualizar_venda_cli.php?idCarrinho_Compra=" + idCarrinho_Compra);

    // ler a constante dados
    const resposta = await dados.json();
    //console.log(resposta);

    if(resposta['status'])
    {
        //document.getElementById("msgAlertErroEdit").innerHTML = "";
        
        //Carregar janela modal
        const vizuModal = new bootstrap.Modal(document.getElementById("vizuVendaModal"));
        vizuModal.show();

        //Enviar id do campo a ser alterado para o input que está oculto, bem como o valor do campo a ser alterado
        /*document.getElementById("idprod").innerHTML = resposta['dados'].id_Produto ;
        document.getElementById("nomeprod").innerHTML = resposta['dados'].Nome_prod;
        document.getElementById("descprod").innerHTML = resposta['dados'].Desc_prod;
        document.getElementById("unmedida").innerHTML = resposta['dados'].Desc_medida;
        document.getElementById("imgprod").src = resposta['dados'].caminho;
        document.getElementById("cdprod").innerHTML = resposta['dados'].Codigo_Barras;
        document.getElementById("catprod").innerHTML = resposta['dados'].Categoria;
        document.getElementById("marcaprod").innerHTML = resposta['dados'].Desc_Marca;

        document.getElementById("msgAlert").innerHTML = "";

    }else
    {
        document.getElementById("msgAlert").innerHTML = resposta['msg'];
    }*/
}

//Acessa os elemtops HTML do manter_estoque 
const DOMStrings = 
{
    btn_cad: '#Parcialmente',
    btn_cancel: '#Cancelar',
    formulario: '#form-verifica-estoque',
    error: '#notice',
    //erro_cad : '#msgAlertErroCad'
    //btnexcluir: '#deletar'
}

const formEstoque = document.getElementById("form-verifica-estoque");

document.querySelector(DOMStrings.btn_cad).addEventListener('click', function(e) 
{
    //Não da refresh na página
    e.preventDefault();

    let form = document.querySelector(DOMStrings.formulario);
    
    $.ajax({
        url: '../adm/excessao_estoque.php',
        type: 'POST',
        data: {'idcarrinho': form.idcarrinho.value, 'parcialmente': form.Parcialmente.value},
        success: function (result) {
            // Retorno se tudo ocorreu normalmente
            $('#statuss').html('OK')
            $('#msgAlert').html(result)
            if(formEstoque)
            {   
                //Fecha a Modal após ser efetuado o cadastro
                editModal.hide();
                //Atualizar a lista de registros
                listarDataTables = $('#listar_disponibilidade_estoque').DataTable();
                listarDataTables.draw();
            }         
        }
    });
})

document.querySelector(DOMStrings.btn_cancel).addEventListener('click', function(e) 
{
    //Não da refresh na página
    e.preventDefault();

    let form = document.querySelector(DOMStrings.formulario);
    
    $.ajax({
        url: '../adm/excessao_estoque.php',
        type: 'POST',
        data: {'idcarrinho': form.idcarrinho.value, 'cancelar': form.Cancelar.value},
        success: function (result) {
            // Retorno se tudo ocorreu normalmente
            $('#statuss').html('OK')
            $('#msgAlert').html(result)
            if(formEstoque)
            {
                //Fecha a Modal após ser efetuado o cadastro
                editModal.hide();
                //Atualizar a lista de registros
                listarDataTables = $('#listar_disponibilidade_estoque').DataTable();
                listarDataTables.draw();
            }         
        }
    });
})