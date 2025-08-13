<? session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"> 

<html>
<head>

<title>PHP Pro Publish</title>
<!--  META -->


<STYLE  TYPE="text/css"><!--
A:link { color: blue; text-decoration: none}
A:visited { color: blue; text-decoration: none}
A:hover { color: #808080; text-decoration: none}

 
.meny
 {
	font-size : 13;
	font-face : Arial,Verdana;

}

.code {
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size: 80%;
	line-height: 120%;
	color:#000000;
}

.meny {
	font-family:Arial,sans-serif;
	font-size: 12;
	line-height: 120%;
	color:#000000;
}

.menyoverskrift {
	font-family:Verdana,Arial,sans-serif;
	font-size: 12;
	line-height: 120%;
	color:#000000;
}

.gentxt {
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size: 80%;
	line-height: 120%;
	color:#000000;
}

.articlebody {
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size: 80%;
	line-height: 120%;
	color:#000000;
}

//-->
</style>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function openWin(page) {
OpenWin = this.open(page, "CtrlWindow", "toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=450,height=500");
}
// End -->
</SCRIPT>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function Start1(page) {
OpenWin = this.open(page, "CtrlWindow", "toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=450");
}
// End -->
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function Start2(page) {
OpenWin = this.open(page, "CtrlWindow", "toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=450");
}
// End -->
</SCRIPT>

</head>

<body bgcolor="#FFFFFF" leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<table border=0 width="760" height="100%" cellpadding=0 cellspacing=0>
<tr height=70>
<td width=100% bgcolor="#CCCCCC" align="center" colspan="3">

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td colspan=2>

	
</td></tr>

<tr>
	<td>

	</td>

	<td align="right">
	</td>
</tr>
</table>


</td>
</tr>

<tr height=1>
	<td colspan=3 bgcolor="#000000"><img src="spacer.gif" border=0 width=1 height=1></td>
</tr>

<tr valign="top">
<td width=150>
	
<table width=130 border="0" cellpadding="0" cellspacing="0" height="100%">
<tr>
<td colspan=2 bgcolor="#8484B5" align="center" height="4" valign="top">
	<p align="left">
	&nbsp;<span class="menyoverskrift"><font color="white"><b>Mainmenu</b></font></span><img src="spacer.gif" border=0 width=1 height=17>
	</p>	
</td>
</tr>

<tr>
	<td colspan=2 bgcolor="#000000"><img src="spacer.gif" border=0 width=1 height=1></td>
</tr>

<tr>
<td bgcolor="#EAEAEA" valign="top"><img src="spacer.gif" width=10 height=1 border=0></td>
<td bgcolor="#EAEAEA" valign="top">

<!-- Hovedmeny -->
<p class="meny">
 <a href="index.php">Frontpage</a><br>
 <a href="">Link2</a><br>
 <a href="">Link3</a><br>
 <a href="">Link4</a><br>
 <a href="login.php">Login</a><br>
 <a href="">About us</a><br>
</p>
<p>
</td>
</tr>
                		
<tr>
	<td colspan=2 bgcolor="#000000"><img src="spacer.gif" border=0 width=1 height=1></td>
</tr>

<tr>
	<td colspan=2 bgcolor="#8484B5" align="center" height="4" valign="top">
	<p align="left"><span class="menyoverskrift"><font color="white">&nbsp;<b>Articles</b></font></span><br></p>
	</td>
</tr>
                
                
<tr>
	<td colspan=2 bgcolor="#000000"><img src="spacer.gif" border=0 width=1 height=1></td>
</tr>
                
                
<tr>
	<td bgcolor="#EAEAEA" height="141" valign="top"><img src="spacer.gif" width=10 height=1 border=0></td>
	<td bgcolor="#EAEAEA" height="141" valign="top">
	
<span class="meny">
<? include "cat_list.php"; ?>
</span>


<p><p>
	</td>
</tr>
                
<tr>
	<td colspan=2 bgcolor="#000000"><img src="spacer.gif" border=0 width=1 height=1></td>
</tr> 

                          


<tr>
	<td colspan=2 bgcolor="#000000"><img src="spacer.gif" border=0 width=1 height=1></td>
</tr>
                
<tr>
	<td colspan=2 bgcolor="#8484B5" align="center" height="4" valign="top">
	<p align="left"><b><span class="menyoverskrift"><font color="white">&nbsp;Searchengine</font></span></b><br></p>
	</td>
</tr>
                
                
<tr>
	<td colspan=2 bgcolor="#000000"><img src="spacer.gif" border=0 width=1 height=1></td>
</tr>

<tr>
	<td bgcolor="#EAEAEA" height="100%" valign="top">&nbsp;</td>
	<td bgcolor="#EAEAEA" height="100%" valign="top">
	
<? include "search_include.php"; ?>



	</td>
</tr>
</table>

</td>
<td width=1></td>
<td width="100%">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>
	<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<tr><td bgcolor="#EAEAEA" valign="top"><font color="#0000FF">&nbsp; </font><font face="Arial" size="2"><a href="http://www.deltascripts.com"><font color="#0000FF">DeltaScripts.com</font></a></td>
	<td bgcolor="#EAEAEA" align="right" valign="top">
	<?  
	$today = date("d.m.Y H:i:s"); 
	print("<small>$today</small>");	 
	?>
		</td>
	</tr>
	</table>
</td>
</tr>

<tr height=1>
	<td bgcolor="#000000"><img src="spacer.gif" border=0 width=1 height=1></td>
</tr>

<tr>
	<td width="99%" valign="top">
	<table width="100%" border="0" cellpadding="9" cellspacing="0">
	<tr>
		<td bgcolor="#FFFFFF" valign="top">
