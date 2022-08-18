var beneficiarios = [];

var lista = $('#tbLista tbody');  

$(lista).find("tr").each(function(index, tr) 
{
  beneficiarios.push(JSON.stringify({ "cpf": $(tr).find('td:eq(0)').html(), "nome": $(tr).find('td:eq(1)').html() }))
});

console.log(beneficiarios);