function validateEmail(emailAddress) {
   var match = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$/.test(emailAddress);
   return match;
}



function CheckRegisterForm(RegisterForm)
    {
        if (RegisterForm.username.value == "" ) { alert( "Please choose a username!" );
        RegisterForm.username.focus();
        return false; }
        
        if (RegisterForm.password.value == "" ) { alert( "Please choose a password!" );
        RegisterForm.password.focus();
        return false; }
        
        if (RegisterForm.email.value == "" ) { alert( "Please enter your email address." );
        RegisterForm.email.focus();
        return false; }

        checkEmail = RegisterForm.email.value
	if ((checkEmail.indexOf('@') < 0) || ((checkEmail.charAt(checkEmail.length-4) != '.') && (checkEmail.charAt(checkEmail.length-3) != '.')))
	{alert("Your emails address is invalid!.");
	RegisterForm.email.focus();
	return false; }


   // return true;
}


function CheckTellForm(tellform)
    {
        if (tellform.name.value == "" ) { alert( "ÇáÑÌÇÁ ÃÏÎá ÇáÅÓã ÈÇáßÇãá" );
        tellform.name.focus();
        return false; }

        if (tellform.email.value == "" ) { alert( "ÃÏÎá ÇáÈÑíÏ ÇáÅáßÊÑæäí" );
        tellform.email.focus();
        return false; }

        checkEmail = tellform.email.value
	if ((checkEmail.indexOf('@') < 0) || ((checkEmail.charAt(checkEmail.length-4) != '.') && (checkEmail.charAt(checkEmail.length-3) != '.')))
	{alert("ÇáÈÑíÏ  ÇáÅáßÊÑæäí  ÎØÃ!.");
	tellform.email.focus();
	return false; }


        if (tellform.fname.value == "" ) { alert( "ÃÏÎá ÅÓã ÕÏíÞß" );
        tellform.fname.focus();
        return false; }



         if (tellform.femail.value == "" ) { alert( "ÃÏÎá ÇáÈÑíÏ ÇáÇáßÊÑæäí ÇáÎÇÕ ÈÕÏíÞß" );
        tellform.femail.focus();
        return false; }

        checkEmail = tellform.femail.value
	if ((checkEmail.indexOf('@') < 0) || ((checkEmail.charAt(checkEmail.length-4) != '.') && (checkEmail.charAt(checkEmail.length-3) != '.')))
	{alert("ÇáÈÑíÏ  ÇáÅáßÊÑæäí  ÎØÃ!.");
	tellform.femail.focus();
	return false; }



   // return true;
}


var newwindow;
function pop(url)
{
        newwindow=window.open(url,'poppage', 'toolbars=0, scrollbars=1, location=0, statusbars=1, menubars=0, resizable=0, width=500, height=400');
        if (window.focus) {newwindow.focus()}
}

     function popimg(url)
    {
        newwindow=window.open(url,'name','height=500,width=650,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,status=yes');
	if (window.focus) {newwindow.focus()}
    }



<!-- ; 
var newwindow; 
var wheight = 0, wwidth = 0; 
function viewimg(url, title, iwidth, iheight, colour) { 
var pwidth, pheight; 

if ( !newwindow || newwindow.closed ) { 
pwidth=iwidth+30; 
pheight=iheight+30; 
newwindow=window.open('','htmlname','width=' + pwidth +',height=' +pheight + ',resizable=1,top=50,left=10'); 
wheight=iheight; 
wwidth=iwidth; 
} 

if (wheight!=iheight || wwidth!=iwidth ) { 
pwidth=iwidth+30; 
pheight=iheight+60; 
newwindow.resizeTo(pwidth, pheight); 
wheight=iheight; 
wwidth=iwidth; 
} 

newwindow.document.clear(); 
newwindow.focus(); 
newwindow.document.writeln('<html> <head> <title>' + title + '<\/title> <\/head> <body bgcolor= \"' + colour + '\"> <center>'); 
newwindow.document.writeln('<a titl="ÅÖÛØ ÇáÕæÑÉ ááÅÛáÇÞ" href="javascript:window.close();"><img src=' + url + ' border=0></a>'); 
newwindow.document.writeln('<\/center> <\/body> <\/html>'); 
newwindow.document.close(); 
newwindow.focus(); 
} 

// Routines to tidy up popup windows when page is left 
// Call with an onUnload="tidy5()" in body tag 

function tidy5() { 
if (newwindow && !newwindow.closed) { newwindow.close(); } 
} 
