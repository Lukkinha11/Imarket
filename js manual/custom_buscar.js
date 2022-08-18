
$(function()
{
    //Pesquisar produtos sem refresh na página
    $("#pesquisa").keyup(function()
    {
        var pesquisa = $(this).val();

        //Verifica se há algo digitado
        if(pesquisa != '')
        {
            var dados = 
            {
                palavra : pesquisa
            }
            $.post('busca.php', dados, function(retorna)
            {
                //Mostra dentrol da ul os resultdos obtidos
                $(".resultado").html(retorna);
            });  
        }
        else
        {
            //$(".resultado").html('row');
        }
        
        
    });
});