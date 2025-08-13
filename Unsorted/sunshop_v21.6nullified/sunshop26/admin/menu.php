<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.6
// Supplied by          : Stive [WTN], CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
require("adminglobal.php");

if (!isset($IN_USER)) {
	header("location:login.php");
	exit;
} 
$auth = adminauthenticate($IN_USER, $IN_PW);
if ($auth == invalid) {
    setcookie("IN_PW", "");
    setcookie("IN_USER", "");
    header("location:login.php?invalid=yes");
	exit;
}
else {
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	<html>
	<head>
		<title>SunShop Administration Menu</title>
		<style type="text/css">
		/* Body Settings */
		body            { font-family : Arial, Verdana, Sans-Serif; color : #000000; font-size : 10px; background-color : #FFFFFF; }
		/* Table Settings */
		td              { font-family : Arial, Verdana, Sans-Serif; color : #000000; font-size : 10px; }
		/* Link Settings */
		a               { font-family : Arial, Verdana, Sans-Serif; color : #0000FF; font-size : 10px; text-decoration : none;}
		/* Link Hover Settings */
		a:hover         { font-family : Arial, Verdana, Sans-Serif; color : #FF0000; font-size : 10px; text-decoration : underline; }
		/* Strong Settings */
		strong          { font-family : Arial, Verdana, Sans-Serif; font-size : 10px; font-weight : bold; }
		/* You will not need to change below */
		.small          { font-family : Arial, Verdana, Sans-Serif; font-size : 10px; text-decoration : none;}
		a:hover.small   { font-family : Arial, Verdana, Sans-Serif; color : #333333; font-size : 10px; text-decoration : underline; }
		</style>
		<script language="JavaScript">
		<!--
		function jsconfirm(num) {
		   if (confirm("Are you sure you want to delete all of the products?")) {
		       return true;
	       } else {
		       return false;
	       }
		}
		// -->
		</script>
	</head>
	<body>
	<div align="center"><table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Registered Users</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/people_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewusers" target="main">View Users</a><br>
				<img src="images/email_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=emailusers" target="main">Email Users</a><br>
			</td>
		</tr>
	</table><br>
	
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Categories / Subcategories</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewcat" target="main">View Categories</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=addcat" target="main">Add Categories</a><br>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewsubcat" target="main">View Subcategories</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=addsubcat" target="main">Add Subcategories</a><br>
			</td>
		</tr>
	</table><br>
	
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Products</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewitems" target="main">View Products</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=additems" target="main">Add Products</a><br>
                <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=import" target="main">Import Products</a><br>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="backup/export.php?action=export" target="main">Export Products</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=deleteall" target="main" onclick="return jsconfirm();">Delete All</a><br>
			</td>
		</tr>
	</table><br>
	
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Specials / Coupons</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewspecials" target="main">View Specials</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=addspecial" target="main">Add Specials</a><br>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewcoupons" target="main">View Coupons</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=addcoupons" target="main">Add Coupons</a><br>
			</td>
		</tr>
	</table><br>
	
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Transactions</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/pie_chart_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewtransactions" target="main">View Transactions</a><br>
			</td>
		</tr>
	</table><br>
	
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Templates</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/pallette_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=templates" target="main">Edit Templates</a><br>
			</td>
		</tr>
	</table><br>
	
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Settings</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/gear_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=settings" target="main">Edit Settings</a><br>
				<img src="images/gear_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=nullification" target="main">Nullification info</a><br>
			</td>
		</tr>
	</table><br>
	
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Backup</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/disk_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=backup" target="main">Backup Database</a><br>
			</td>
		</tr>
	</table></div>    
	</body>
	</html>
<?PHP
}
?>