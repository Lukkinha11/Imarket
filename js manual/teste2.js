$(function()
{
    // já cria logo uma cópia do TBODY original
    var copia = document.querySelector("#tabela-herdeiro tbody").outerHTML;
 
     $(document).on("click", "#tabela-herdeiro button", function(){
 
       var tr = $(this).closest("tbody");
       
       tr.fadeOut(400, function(){
         this.remove(); 
       }); 
 
     });
 
     $("#adicionar").on("click", function(){
         $("#tabela-herdeiro").append(copia);
     });          
 
});