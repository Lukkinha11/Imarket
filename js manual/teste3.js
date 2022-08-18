var teste = 0;

function salvarDados(){
  var nome       = document.getElementById("nome").value;
  var valor      = document.getElementById("valor").value;
  var vencimento = document.getElementById("vencimento").value;

  if (!(nome == '' || valor == '' || vencimento == '')) {
    var tr = document.createElement("tr");
    var td1 = document.createElement("td");
    var td2 = document.createElement("td"); 
    var td3 = document.createElement("td");
    var btn = document.createElement("button");                 
    td1.innerHTML = nome; 
    td2.innerHTML = valor;
    td3.innerHTML = vencimento;
    btn.innerHTML = "deletar";

    // aqui e passado "this" para a função, e this é o botao
    btn.setAttribute('onclick', 'deletar(this)');

    tr.appendChild(td1); 
    tr.appendChild(td2);
    tr.appendChild(td3);
    tr.appendChild(btn);              
    tr.id = 'linha' + teste;
    teste++;
    document.getElementById("campo").appendChild(tr);

    limparCampos();
  }else {
    alert('Todos os campos precisam estar preenchidos !!');
    }
}
function limparCampos(){
  var nome       = document.getElementById("nome");
  var valor      = document.getElementById("valor");
  var vencimento = document.getElementById("vencimento");
  
  nome.value       = '';
  valor.value      = '';
  vencimento.value = '';

}

function deletar(botao) {
  var tabela = document.getElementById('campo');
  // a partir do botao, pega a linha com parentNode, e o indice da linha com rowIndex
 tabela.deleteRow(botao.parentNode.rowIndex); 
}