<?php
/////////////////////////////////////////////////////////////////////////////
//                                                                         //
// Program Name         : PHPCart                                          //
// Release Version      : 3.1                                              //
// Program Author       : Mr B Wiencierz                                   //
// Home Page            : http://www.phpcart.co.uk                         //
// Supplied by          : CyKuH [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                      //
// Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                         //
//                All code is ©2000-2004 Barry Wiencierz.                  //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////
function pageHeader() {
require("version.php");
?>
<head>
<title>The Simple e-Commerce Solution!</title>
<link rel="stylesheet" type="text/css" href="admin.css">
<script language="javascript">
function switch_nav(selectobj)
{	
	nav = selectobj.options[selectobj.selectedIndex].value;
	window.location = nav;
}
</script>
</head>
<body>
<a name="top"></a>

<center>
<table border="0" cellpadding="0" cellspacing="0" width="760">
<tr>
<td>


<table border="0" width="760" cellpadding="0" cellspacing="0" align="center">
<tr>


	<td align="left" bgcolor="#FFFFFF" valign="middle" width="556"><a href="index.php"><img border="0" src="../PHPCart.gif" width="301" height="72"></a></td>
	<td align="right" bgcolor="#FFFFFF" valign="middle" width="200">
  <center>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <table border="0" cellpadding="1" cellspacing="1" width="160">
    <tr>
      <td>
<div style="padding:6px">
<fieldset class="fieldset">
<div align="center">
<center><table cellpadding="0" cellspacing="3" border="0" width="160">
<tr><td><p align="center">Current Version: <b><?php echo $version;?>&nbsp;</b><br>Latest Version: <b><script language="javascript" type="text/javascript">document.write(phpcart_version);</script></b>&nbsp;&nbsp;</p></td></tr></table>
</center>
</div>
</fieldset>
</div>

<script language="Javascript" type="text/javascript">
<!--
if (typeof(phpcart_version) != "undefined" && isNewerVersion("<?php echo $version;?>", phpcart_version)) {
	document.write("<center><b><a href=\""+phpcart_link+"\">Update Available!</a></b></center>");
}
//-->
</script>

	</td>
    </tr>
  </table>
  </center>
  <b>&nbsp;</b></td>
</tr>
</table>

<div align="center">
<div style="width:760px" align="left">
<form method="GET">
<table cellpadding="6" cellspacing="0" border="0" width="100%" class="page">
<tr>
<td class="tfoot" align="left">


		<div class="smallfont">
                <select name="nav" id="ressel" onchange="switch_nav(this)">
		<optgroup label="Admin Options">
			<option value="index.php" selected> Admin Home</option>
			<option value="main.php"> Admin News</option>
			<option value="configuration.php"> Cart Configuration</option>
		</optgroup>
		<optgroup label="Cart Layout">
			<option value="header.php"> Edit Header</option>
			<option value="footer.php"> Edit Footer</option>
		</optgroup>
		<optgroup label="Payment Configuration">
			<option value="payment.php"> Gateway Settings</option>
		</optgroup>
		<optgroup label="Order Details">
			<option value="search.php"> Search Orders</option>
		</optgroup>
		</select>

				</div>
</td>
<td class="tfoot" align="right"></td>
</tr>
</table>

		
</div>
</div>



<div align="center">
<div class="page" style="width:760px; text-align:left">
<div style="padding:0px 25px 0px 25px">

<div align="center">
  <center>
  <table border="0" cellpadding="4" cellspacing="5" width="710">
    <tr>
      <td>
</form>
<?php
echo $body;
$GLOBALS["pageHeaderSent"] = TRUE;
}
function pageFooter() {
$date = date("Y");
?>
</td>
    </tr>
  </table>
  </center>
</div>
</div>	
</div>
</div>

<div align="center">
<div style="width:760px" align="left">
	
<table cellpadding="6" cellspacing="0" border="0" width="100%" class="page">
<tr><td class="tfoot" align="right"><div class="smallfont"><p align="center"><font size="1" color="#FFFFFF">Copyright &copy;2000 - <span class="date"><?php echo $date;?></span> - <!--CyKuH [WTN]-->PHPCart.</font></div></td></tr>
</table>

<br>

<div align="center"><div class="smallfont" align="center">&nbsp;</div></div>
</div>
</div>

</td>
</tr>
</table>
</center>
</body>


<?php } ?>