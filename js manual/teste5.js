$('#adicionarFruta').on('click', function (e) {

  e.preventDefault();

  var $fruta = $('#frutas option:selected');

  // ignorar se fôr a primeira opção do select
  if ($fruta.val() == '0') return false;

  // verificar se o produto já está na tabela
  if ($('#carrinhoCompras input[name^=' + $fruta.val() + ']').length) return false;

  var novaLinha = '\
  <tr> \
  <td>' + $fruta.text() + '</td> \
  <td><input name="' + $fruta.val() + '" value="1" /></td> \
  <td><button>-</button></td> \
  </tr> ';
  $('#carrinhoCompras ').append($(novaLinha));

  
  console.log(novaLinha);
});

$('#carrinhoCompras').on('click', 'button', function () {
  $(this).closest('tr').remove();
});
