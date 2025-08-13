function shoHelp(fArg){
var tooltipOBJ = eval("document.all['tt" + fArg + "']");
var tooltipOffsetTop = tooltipOBJ.scrollHeight + 20;
var testTop = (document.body.scrollTop + event.clientY) - tooltipOffsetTop;
var testLeft = event.clientX - 90;
var tooltipAbsLft = (testLeft < 0) ? 10 : testLeft;
var tooltipAbsTop = (testTop < document.body.scrollTop) ? document.body.scrollTop + 10 : testTop;
tooltipOBJ.style.posLeft = tooltipAbsLft;
tooltipOBJ.style.posTop = tooltipAbsTop;
tooltipOBJ.style.visibility = "visible";}

function hideHelp(fArg){
var tooltipOBJ = eval("document.all['tt" + fArg + "']");
tooltipOBJ.style.visibility = "hidden";}

document.L9 = new Object();
document.L9.menu = new Object();
document.L9.menu.dropdown = new Array();

function hideShipping(){
var i;
if (document.all){
for(i = 0; i < document.all('container').all.length; i++){
if ((document.all('container').all[i].className == 'header') || (document.all('container').all[i].className == 'links')){
document.L9.menu.dropdown[document.L9.menu.dropdown.length] = document.all('container').all[i];}}} else if (document.getElementsByTagName && document.getElementById){
var contained = document.getElementById('container').getElementsByTagName('div');
for(i = 0; i < contained.length; i++){ if ((contained[i].getAttribute('class') == 'header') || (contained[i].getAttribute('class') == 'links')){
document.L9.menu.dropdown[document.L9.menu.dropdown.length]= contained[i];}}}
}
function showhide(item){
if(document.L9.menu.dropdown.length){
if (document.L9.menu.dropdown[item + 1].style.display == 'none'){
document.L9.menu.dropdown[item + 1].style.display = '';
document.checkout.SAME.checked = false;
} else {
document.L9.menu.dropdown[item + 1].style.display = 'none';
document.checkout.SAME.checked = true;
}}}

