var onSubmit = function(e){
    alert('form enviada');
    return false;
   };
  
document.getElementsByTagName('form')[0].submit = onSubmit;