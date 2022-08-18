var formulario = document.getElementById("inserir");
var nascimento = document.getElementById("txtDataNascimento");
var enviar = document.getElementById("enviar");

var mensagemErro = function (event, input, mensagem) 
{
  //input.setCustomValidity(mensagem);
  alert(mensagem);
  event.preventDefault();
}

formulario.addEventListener("submit", function (event) {
  var data = nascimento.value;
  //nenhuma data informada
  if (!data) {
    return mensagemErro(event, nascimento, "Campo nascimento não informado");
  }

  //O browser não realizou a conversão de forma nativa
  if (!(data instanceof Date)) {
    data = data.split('/').reverse().join('-');
    data = Date.parse(data);
    if (!isNaN(data)) {
      data = new Date(data);
    }
  }

  //a data informada não é valida
  if (!data) {
    return mensagemErro(event, nascimento, "Campo nascimento não é valido");
  }

  var atual = new Date();
  data.setFullYear(data.getFullYear() + 18);  

  //menor de 15 anos.
  if (data >= atual) {
    return mensagemErro(event, nascimento, "Necessário ter no mínimo 18 anos para obter o cadastro no sistema!");
  }
})
