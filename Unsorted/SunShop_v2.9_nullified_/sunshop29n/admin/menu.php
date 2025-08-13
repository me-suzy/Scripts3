<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.9
// Supplied by          : CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
require("adminglobal.php");

if(!(session_is_registered("IN_USER"))) {
	header("location:login.php");
	exit;
} else {
	$userinfo = getuser("");
	$access = getuseraccess("");
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
	<?PHP if ($access[1] == 1 || $userinfo[name] == "Admin_Account") {?>
	<div align="center"><table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Registered Users</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/people_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewusers" target="main">View Users</a><br>
				<img src="images/email_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=emailusers" target="main">Email Users</a><br>
				<img src="images/people_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewusers&admin=1" target="main">View Admins</a><br>
				<img src="images/people_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=addadmin" target="main">Add Admin</a><br>
			</td>
		</tr>
	</table><br>
	<?PHP } if ($access[2] == 1 || $userinfo[name] == "Admin_Account") {?>
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
	<?PHP } if ($access[3] == 1 || $userinfo[name] == "Admin_Account") {?>
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Products Settings</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewitems" target="main">View Products</a><br>
				<?PHP
				$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where quantity<='0'");
				$numo=$DB_site->num_rows($temps);
				if ($numo > 0) { ?>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewitems&type=outofstock" target="main"><font color="#FF0000">Out Of Stock</font></a><br>
			    <?PHP
				}
				?>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=additems" target="main">Add Products</a><br>
                <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=import" target="main">Import Products</a><br>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="backup/export.php?action=export" target="main">Export Products</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=deleteall" target="main" onclick="return jsconfirm();">Delete All</a><br>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewoptions" target="main">View Options</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=addoption" target="main">Add Options</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewfields" target="main">View Custom Fields</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=addfields" target="main">Add Custom Fields</a><br>
			</td>
		</tr>
	</table><br>
	<?PHP } if ($access[4] == 1 || $userinfo[name] == "Admin_Account") {?>
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Specials / Coupons / Discounts</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewspecials" target="main">View Specials</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=addspecial" target="main">Add Specials</a><br>
				<img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewcoupons" target="main">View Coupons</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=addcoupons" target="main">Add Coupons</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewdiscounts" target="main">View Discounts</a><br>
			    <img src="images/star_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=adddiscount" target="main">Add Discounts</a><br>
			</td>
		</tr>
	</table><br>
	<?PHP } if ($access[5] == 1 || $userinfo[name] == "Admin_Account") {?>
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
	<?PHP } if ($access[6] == 1 || $userinfo[name] == "Admin_Account") {?>
	<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="Navy">
		<tr>
			<td bgcolor="Navy"><div align="left"><b><font color="White">Templates</font></b></div></td>
		</tr>
		<tr>
			<td>
				<img src="images/pallette_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=viewtemplates" target="main">Edit Templates</a><br>
			    <img src="images/pallette_small.gif" width="16" height="16" border="0" alt="">&nbsp;<a href="admin.php?action=importtemplates" target="main">Import Templates</a><br>
			</td>
		</tr>
	</table><br>
	<?PHP } if ($access[7] == 1 || $userinfo[name] == "Admin_Account") {?>
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
	<?PHP } if ($access[8] == 1 || $userinfo[name] == "Admin_Account") {?>
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
	<?PHP } ?> 
	</body>
	</html>
<?PHP
}
?>