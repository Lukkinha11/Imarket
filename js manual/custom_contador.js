var itemCount = 0;

$('.add').click(function (){
  itemCount ++;
  $('#itemCount').html(itemCount);
}); 

$('.clear').click(function() {
  itemCount = 0;
  $('#itemCount').html('').css('display', 'none');
  $('#cartItems').html('');
}); 
