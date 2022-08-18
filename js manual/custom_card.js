/* Máscaras ER */
function mascara(o, f) {
  v_obj = o
  v_fun = f
  setTimeout("execmascara()", 1)
}
function execmascara() {
  v_obj.value = v_fun(v_obj.value)
}
function mcc(v) {
  v = v.replace(/\D/g, "");
  v = v.replace(/^(\d{4})(\d)/g, "$1 $2");
  v = v.replace(/^(\d{4})\s(\d{4})(\d)/g, "$1 $2 $3");
  v = v.replace(/^(\d{4})\s(\d{4})\s(\d{4})(\d)/g, "$1 $2 $3 $4");
  return v;
}
function id(el) {
  return document.getElementById(el);
}
window.onload = function () {
  id('cc').onkeypress = function () {
    mascara(this, mcc);
  }
}