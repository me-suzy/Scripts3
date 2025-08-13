var whitespace = " \t\n\r";
function isEmpty(s){
return ((s == null) || (s.length == 0))}
function isWhitespace (s){
var i;
if (isEmpty(s)) return true;
for (i = 0; i < s.length; i++){
var c = s.charAt(i);
if (whitespace.indexOf(c) == -1) return false;}
return true;}

function dataValidate(theForm){
if (theForm.pgNum.value == ""){
alert("Please enter a valid Page Number Only.");
theForm.pgNum.focus();theForm.pgNum.select();return (false);}
var strField = new String(theForm.pgNum.value);
if (isWhitespace(strField)) return true;
var i = 0;
for (i = 0; i < strField.length; i++)
if (strField.charAt(i) < '0' || strField.charAt(i) > '9'){
alert("Page Number must be a valid numeric entry.  Please do not use any non-numeric symbols.");
theForm.pgNum.focus();
theForm.pgNum.select();
return false;}return true;}

function popup(){ window.open("popup.htm","popup","width=350,height=220,resizable=yes,scrollbars=no,status=no,toolbars=no")}

function ask(){
var agree=confirm("Are you sure you want to completely remove ALL items from your cart?");
if (agree)
return true;
else
return false;}
