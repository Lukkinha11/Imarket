var usuario = {
  'nome': 'João',
  'profissao': 'Engenheiro',
  'cidade': 'São Paulo'
}

var dados = JSON.stringify(usuario);

$.ajax({
  type: 'POST',
  url: '../adm/teste12.php',
  data: {data: dados},
  success: function(res){
      console.log(res)
  },
  error: function(jqXHR, textStatus, errorThrown){
      console.log(errorThrown)
  }
})