function goToURL(URL) { window.location = URL; }

function formHandler(form) {
var windowprops = "height=600,width=700,location=no,"
+ "scrollbars=yes,menubars=no,toolbars=no,resizable=no";

var URL = form.site.options[form.site.selectedIndex].value;
popup = window.open(URL,"MenuPopup",windowprops);
form.site.value = '';

}

function formHandler1(form) {

var URL = form.site.options[form.site.selectedIndex].value;
location.href = URL;

}

function pageFormHandler(form) {
    var URL = form.field.options[form.field.selectedIndex].value;
    window.location = URL;
}

function CheckAll(){
    for (var i=0;i<document.moveform.elements.length;i++){
         var e = document.moveform.elements[i];
         if (e.name != "allbox")
             e.checked = document.moveform.allbox.checked;
    }
}

function Start(page) {OpenWin = this.open(page, 'CtrlWindow', 'toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=no,width=700,height=600');}

function Start1(page) {OpenWin = this.open(page, '_blank', 'toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=775,height=500');}

function Start2(page) {
    commentWindow = this.open('', '_blank', 'toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=no,width=400,height=400');
    commentWindow.location.href = page;
    if (commentWindow.opener == null) commentWindow.opener = self;
}
function startHelp(page) {OpenWin = this.open(page, '_help', 'toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=600,height=500,top=10,left=10');}

function newsearch () {
    var search=document.usersearch.search.value;
    var place="users.php?search=" + search;
    msg=open(place,"NewWindow","toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=no,width=600,height=400");
}

function linkpage() {
    msgWindow = window.open('','targetname','width=200,height=450,screenX=400,screenY=400,top=400,left=400,scrollbars=yes');
    msgWindow.location.href = 'statlinks.php';
    if (msgWindow.opener == null) msgWindow.opener = self;
}

function showlink(url) {
    opener.location.href = url;
}



function confirmDelete(page){
    if (confirm("Are you Sure you want to Delete?")){
        window.location.href=page;
    }
}



function setMenuForm(par){

    document.f.link.value = par;
    document.f.title.value = '';
}


function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


function menuEdit(parm){
    if (parm == 1){
        var URL = "menu.php?action=modify&menu_id=" + document.forms[0].cat_id.options[document.forms[0].cat_id.selectedIndex].value;
        window.location = URL;
    }
    else if (parm == 2){
        var URL = "menu.php?action=modify&menu_id=" + document.forms[0].cat_id_sub.options[document.forms[0].cat_id_sub.selectedIndex].value;
        window.location = URL;
    }
    else {
        var URL = "menu.php?action=modify&menu_id=" + document.forms[0].cat_id_sub_sub.options[document.forms[0].cat_id_sub_sub.selectedIndex].value;
        window.location = URL;
    }
}

function forumEdit(parm){
    if (parm == 1){
        var URL = "forums.php?action=modifyForum&forum_id=" + document.forms[0].cat_id.options[document.forms[0].cat_id.selectedIndex].value;
        window.location = URL;
    }
    else {
        var URL = "forums.php?action=modifyForum&forum_id=" + document.forms[0].forum_id.options[document.forms[0].forum_id.selectedIndex].value;
        window.location = URL;
    }
}

function faqEdit(parm){
    if (parm == 1){
        var URL = "faq.php?action=modify&subaction=cat&faq_cat_id=" + document.forms[0].faq_cat_id.options[document.forms[0].faq_cat_id.selectedIndex].value;
        window.location = URL;
    }
    else {
        var URL = "faq.php?action=modify&subaction=faq&faq_id=" + document.forms[0].faq_id.options[document.forms[0].faq_id.selectedIndex].value;
        window.location = URL;
    }
}

function isEmailAddr(email)
{
  var result = false;
  var theStr = new String(email);
  var index = theStr.indexOf("@");
  if (index > 0)
  {
    var pindex = theStr.indexOf(".",index);
    if ((pindex > index+1) && (theStr.length > pindex+1))
	result = true;
  }
  return result;
}

function validRequired(formField,fieldLabel)
{
	var result = true;

	if (formField.value == "")
	{
		var alertText = 'Please enter a value for ' + fieldLabel;
		alert(alertText);
		formField.focus();
		result = false;
	}

	return result;
}

function allDigits(str)
{
	return inValidCharSet(str,"0123456789");
}

function inValidCharSet(str,charset)
{
	var result = true;

	for (var i=0;i<str.length;i++)
		if (charset.indexOf(str.substr(i,1))<0)
		{
			result = false;
			break;
		}

	return result;
}

function validEmail(formField,fieldLabel,required)
{
	var result = true;

	if (required && !validRequired(formField,fieldLabel))
		result = false;

	if (result && ((formField.value.length < 3) || !isEmailAddr(formField.value)) )
	{
	    var alertText = 'Please enter a complete email address in the form: yourname@yourdomain.com';
		alert(alertText);
		formField.focus();
		result = false;
	}

  return result;

}

function insertImage(file, title){
    var caretPos = window.opener.doc_html.text.caretPos;
    if (caretPos == null){ alert("You must have the cursor placed somewhere in the text, where you want the image to appear"); }
    else {
        //box=prompt ("How do you want this Image Aligned? (left|center|right)", "left");
        //caretPos.text = '<img src=' + file + ' alt=' + title + ' border=0 align=' + box + '>';
        caretPos.text = '<img src=' + file + ' alt=' + title + ' border=0>';
    }
    window.close();
}

function toggleTheme(url, c_img){
    var img1 = "images/theme_select.gif";
    var img2 = "images/theme_select1.gif";
    if (document[c_img].src.indexOf(img1)!= -1) document[c_img].src = img2;
    else document[c_img].src = img1;

    for (var i=0; i<document.images.length; i++){
        if (document.images[i] != document[c_img] && document.images[i].src.indexOf(img1) != -1){
            document.images[i].src = img2;
        }
    }
    window.location.href = url;
}

function displaySubs(the_sub){
    document.getElementById(the_sub).style.display = "";
}

function banUserForm(){
    selectAllOptions(document.f.list2);
    document.f.list3.value = getSelectedValues(document.f.list2);
    displaySubs('actionResult');
    document.f.submit();
    if (document.f.ips.value.length > 0){
        document.f.list4.options[document.f.list4.options.length] = new Option(document.f.ips.value,document.f.ips.value, false, false);
        document.f.ips.value = '';
        selectAllOptions(document.f.list4);
        document.f.list6.value = getSelectedValues(document.f.list4);

    }

    document.f.list2.value = '';

}

function removeIP(from){
    selectAllOptions(document.f.list2);
    document.f.list3.value = getSelectedValues(document.f.list2);
    document.f.submit();
    for (var i=(from.options.length-1); i>=0; i--) {
        var o = from.options[i];
        if (o.selected) { from.options[i] = null; }
    }
    from.selectedIndex = -1;
    document.f.list2.value = '';
}

var submitcount=0;
function submitOnce(){
    if (submitcount == 0){
        submitcount++;
        var submitMessage = "  Please Wait...  ";
        document.restore.submit.value = submitMessage;
        return true;
    }
    else { return false; }
}







