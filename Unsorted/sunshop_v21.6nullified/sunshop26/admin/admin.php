<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.6
// Supplied by          : Stive [WTN], CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
$version="2.6";

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
    
	if ($action == "package") {
	   showpl($id);
	}
	if ($action == "deleteaccount") {
		deleteaccount ($id);
	}
	if ($action == "deletespecial") {
		deletespecial ($id);
	}
	if ($action == "deleteitem") {
		deleteitem ($id);
	}
	if ($action == "updateaccount") {
		updateaccount ($username, $password, $password2, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email);
	}
	if ($action == "deletecat") {
		deletecat ($id);
	}
	if ($action == "deletesubcat") {
		deletesubcat ($id);
	}
	if ($action == "deletetransaction") {
		deletetransaction ($id);
	}
	if ($action == "updatecat") {
		updatecat ($categoryid, $title, $stitle, $displayorder);
	}
	if ($action == "updatesubcat") {
		updatesubcat ($categoryid, $subcategoryid, $title, $stitle, $displayorder);
	}
	if ($action == "storecoupon") {
		$DB_site->query("INSERT INTO ".$dbprefix."coupons VALUES(NULL,'$couponid','$discount','$type','0')");
		print("Coupon sucessfully added!");
	}
	if ($action == "updatecoupon") {
		$DB_site->query("UPDATE ".$dbprefix."coupons set couponid='$couponid', discount='$discount', type='$type' where id='$id'");
		print("Coupon sucessfully updated!");
	}
    ?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	<html>
	<head>
	    <style type="text/css">
		/* Body Settings */
		body            { font-family : Arial, Verdana, Sans-Serif; color : #000000; font-size : 11px; background-color : #FFFFFF; }
		/* Table Settings */
		td              { font-family : Arial, Verdana, Sans-Serif; color : #000000; font-size : 11px; }
		/* Link Settings */
		a               { font-family : Arial, Verdana, Sans-Serif; color : #0000FF; font-size : 11px; text-decoration : none;}
		/* Link Hover Settings */
		a:hover         { font-family : Arial, Verdana, Sans-Serif; color : #FF0000; font-size : 11px; text-decoration : underline; }
		/* Strong Settings */
		strong          { font-family : Arial, Verdana, Sans-Serif; font-size : 11px; font-weight : bold; }
		/* You will not need to change below */
		.small          { font-family : Arial, Verdana, Sans-Serif; font-size : 10px; text-decoration : none;}
		a:hover.small   { font-family : Arial, Verdana, Sans-Serif; color : #333333; font-size : 10px; text-decoration : underline; }
		</style>
	<?PHP
	if ($action == "viewtransactions") {
	?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete transaction number " + num + "?\n You may want to print this transaction before you delete it.")) {
	       return true;
       } else {
	       return false;
       }
	}
	// -->
	</script>
	<?PHP
	}
	if ($action == "viewitems") {
	?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete item number " + num + "?")) {
	       return true;
       } else {
	       return false;
       }
	}
	// -->
	</script>
	<?PHP
	}
	if ($action == "viewspecials") {
	?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete special number " + num + "?")) {
	       return true;
       } else {
	       return false;
       }
	}
	// -->
	</script>
	<?PHP
	}
	if ($action == "viewcoupons") {
	?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete coupon " + num + "?")) {
	       return true;
       } else {
	       return false;
       }
	}
	// -->
	</script>
	<?PHP
	}
	if ($action == "viewusers") {
	?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete user number " + num + "?")) {
	       return true;
       } else {
	       return false;
       }
	}
	// -->
	</script>
	<?PHP
	}
	if ($action == "viewtransaction") {
	?>
	<SCRIPT Language="Javascript">
	function printit(){  
		if (NS) {
		    window.print() ;  
		} else {
		    var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
	    	document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
		    WebBrowser1.ExecWB(6, 2);//Use a 1 vs. a 2 for a prompting dialog box    WebBrowser1.outerHTML = "";  
		}
	}
	</script>
	<?PHP
	}
	if ($action == "viewcat") {
	?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete category number " + num + "?\nRemember you will delete ALL items and subcategories in that category!")) {
	       return true;
       } else {
	       return false;
       }
	}
	// -->
	</script>
	<?PHP
	}
	if ($action == "viewsubcat") {
	?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete subcategory number " + num + "?\nRemember you will delete ALL items in that subcategory!")) {
	       return true;
       } else {
	       return false;
       }
	}
	// -->
	</script>
	<?PHP
	}
	?>
		<title>SunShop Administration: Main</title>
	</head>
	</body>
	<?PHP
	if ($action == "") {
	    $num = 0;
	    $temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where status='Pending'");
		while ($row=$DB_site->fetch_array($temps)) {
		    $num++;
		}
		$temps=$DB_site->query("SELECT license FROM ".$dbprefix."options");
		$row=$DB_site->fetch_array($temps);
		$license = $row[license];
		?>
	    <div align="center"><img src="../images/sunshop.gif" width="295" height="50" border="0" alt=""></div><br><br>
		Thank you for choosing SunShop <?PHP echo $version ?> to power your online store. There are currently 
		<font color="Red"><b><?PHP echo $num ?></b></font> transactions pending approval.
		<br><br>
		<b><font color="Navy">Extra Info:</font></b>
		<blockquote>
		<?PHP
		echo "&copy  WTN Team `2002";
		?>
		</blockquote>
		</div>
	    <?PHP
	}
	if ($action == "import") {
		?>
		<div align="left"><b><font color="Navy">Import CSV File</font></b><br><br>
		<form action="admin.php?action=storecsv" method="post" enctype="multipart/form-data">
		<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="20000000">
		<table width="95%" border="0" cellspacing="2" cellpadding="3">
			<tr>
				<td width="100" valign="top" bgcolor="Silver"><b>CSV File:</b></td>
				<td bgcolor="Silver"><input type="file" name="users_file" size="40">
				</td>
			</tr>
			<tr>
				<td width="100">&nbsp;</td>
				<td><input type="submit" name="Submit" value="Continue"></td>
			</tr>
		</table>
		CSV Import Guidelines:<br> 
		For importing guidleines and instruction please read "import.txt" located in the main directory. 
		</div><br>	
		<?PHP
	}
	if ($action == "deleteall") {
		$DB_site->query("DELETE FROM ".$dbprefix."items");
	    print("Done. All products have been deleted.");
	}
	if ($action == "storecsv") {
		echo "<font color=\"Navy\"><b>File Information:</b></font><blockquote>";
		echo "<B>Remote File Name:</B> $users_file<BR>";
		echo "<B>Local File Name:</B> $users_file_name<BR>";
		echo "<B>Local File Size:</B> $users_file_size<BR>";
		if (isset($users_file_type)) {
		echo "<B>Local File Type:</B> $users_file_type</blockquote>"; }
		
		if (!$fp=fopen ($users_file, 'r')) {
			print("Could not open local file!");
		} else {
		    fclose ($fp);
			$filearray = file($users_file);
			for ($i=0; $i<count($filearray); $i++) {
			    $itemsarray = explode(",", trim(addslashes($filearray[$i])));
				$DB_site->query("INSERT INTO ".$dbprefix."items VALUES ('$itemsarray[0]','$itemsarray[1]','$itemsarray[2]','$itemsarray[3]','$itemsarray[4]','$itemsarray[5]','$itemsarray[6]','$itemsarray[7]','$itemsarray[8]','$itemsarray[9]','$itemsarray[10]','$itemsarray[11]','$itemsarray[12]','$itemsarray[13]','$itemsarray[14]','$itemsarray[15]','$itemsarray[16]','$itemsarray[17]','$itemsarray[18]','$itemsarray[19]','$itemsarray[20]')");
			}
			print("Done. CSV file imported sucessfully.");
		}
	
	}
	if ($action == "emailusers") {
		print("
			<b><font color=\"Navy\">Email All Users:</font></b><br><br>
			<blockquote><form action=\"admin.php\" method=\"post\">
			<input type=\"hidden\" name=\"action\" value=\"sendemail\">
			<table width=\"95%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
				<tr>
					<td valign=\"top\" bgcolor=\"silver\" width=\"50\"><b>Subject:</b></td>
					<td bgcolor=\"silver\"><input type=\"text\" name=\"subject\" size=\"40\"></td>
				</tr>
				<tr>
					<td valign=\"top\" width=\"50\"><b>Message:</b></td>
					<td><textarea cols=\"40\" rows=\"10\" name=\"message\"></textarea></td>
				</tr>
				<tr>
					<td bgcolor=\"silver\" width=\"50\">&nbsp;</td>
					<td bgcolor=\"silver\"><input type=\"submit\" name=\"Submit\" value=\"Send Email\"></td>
				</tr>
			 </table><br>
			 There are a few variables that can be used in these emails to make them more personal.<br>
			 <b>\$name</b> - Will display there name.<br>
			 <b>\$username</b> - Will display there user name.<br>
			 <b>\$password</b> - Will display there password.<br>
			 <b>\$userid</b> - Will display there userid number.<br>
			 </blockquote></form>
		");
	}
	if ($action == "sendemail") {
	    $temps=$DB_site->query("SELECT * FROM ".$dbprefix."options");
	    while ($row=$DB_site->fetch_array($temps)) {
			$title = stripslashes($row[title]);
			$contactemail = stripslashes($row[contactemail]);
		} 
	    $temps=$DB_site->query("SELECT * FROM ".$dbprefix."user");
		while ($row=$DB_site->fetch_array($temps)) {
		    $name = $row[name];
			$userid = $row[userid];
			$username = $row[username];
			$password = $row[password];
			eval("\$temp1 = dovars(\"".$subject."\");");
			eval("\$temp = dovars(\"".$message."\");");
			$temp = stripslashes ($temp);
			$temp1 = stripslashes ($temp1);
			mail($row[email], $temp1, $temp, "From: \"".$title."\" <" . $contactemail . ">");
		}
		echo "<p>Email have been sent successfully.</p>";
	}
	if ($action == "updatetemplates") {
		$DB_site->query("UPDATE ".$dbprefix."template set temp='$checkout' where name='checkout'");
		$DB_site->query("UPDATE ".$dbprefix."template set temp='$signup' where name='signup'");
		$DB_site->query("UPDATE ".$dbprefix."template set temp='$shipmethod' where name='shipmethod'");
		$DB_site->query("UPDATE ".$dbprefix."template set temp='$shipaddressok' where name='shipaddressok'");
		echo "<p>Templates have been saved successfully.</p>";
	}
	if ($action == "templates") {
		print("<b><font color=\"Navy\">Edit Templates:</font></b><br><br>
		       <form action=\"admin.php\" method=\"post\">
			   <input type=\"hidden\" name=\"action\" value=\"updatetemplates\">
			   <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	    ");
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."template");
		while ($row=$DB_site->fetch_array($temps)) {
		    print("
					<tr>
						<td width=\"100\" valign=\"top\"><b>$row[name]</b></td>
						<td><textarea cols=\"40\" rows=\"5\" name=\"$row[name]\">$row[temp]</textarea></td>
					</tr>
			");
		}
		print("<tr>
		       <td width=\"100\">&nbsp;</td>
		       <td><input type=\"submit\" name=\"Submit\" value=\"Make Changes\">&nbsp;&nbsp;<input type=\"Reset\"></td>
			   </tr>
			   </table></form><br>
			   <div align=\"left\">\"checkout\" is shown when a user who is logged in views their cart.<br>
			   \"signup\" is shown at the bottom of the signup page.<br>
			   \"shipmethod\" is shown when ups realtime shipping calculating is turned on.<br>
			   \"shipaddressok\" is shown when UPS realtime shipping calculating is not on.</div>
	    ");

	}
	if ($action == "viewusers") {
	    if ($start == "") { $start = 0; }
	    $place = "silver";
		$number = 0; $total = 0;
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user ORDER by userid");
		while ($row=$DB_site->fetch_array($temps)) {
			$total++;
		}
		$total--;
		$end = $start + 100;
		$prev = $start - 100;
		$temp = $start + 1;
		if ($total < $end) { $end = $total; }
		print ("
		<div align=\"center\"><font color=\"Navy\"><b>Registered Users</b> (<b>$temp</b> thru <b>$end</b> of <b>$total</b>)</font><br>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
			<tr bgcolor=\"navy\">
				<td><div align=\"center\"><b><font color=\"White\">User ID</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Name</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Phone</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Last Visit</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Action</font></b></div></td>
			</tr>
		");
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user ORDER by userid LIMIT $start,100");
		while ($row=$DB_site->fetch_array($temps)) {
		    if ($row[name] != "Admin_Account") {
			    $number++;
				?>
				<tr bgcolor="<?PHP echo $place ?>">
					<td><div align="center"><?PHP echo $row[userid] ?></div></td>
					<td><div align="center"><?PHP echo $row[name] ?></div></td>
					<td><div align="center"><?PHP echo $row[phone] ?></div></td>
					<td><div align="center"><?PHP echo $row[lastvisit] ?></div></td>
					<td align="center"><a href="admin.php?action=viewaccount&id=<?PHP echo $row[userid] ?>">View</a>&nbsp;|&nbsp;<a href="admin.php?action=editaccount&id=<?PHP echo $row[userid] ?>">Edit</a>&nbsp;|&nbsp;<a href="admin.php?action=deleteaccount&id=<?PHP echo $row[userid] ?>" onclick="return jsconfirm(<?PHP echo $row[userid] ?>);">Delete</a></td>
				</tr>
				<?PHP
				if ($place == "silver") { $place = "white"; } else { $place = "silver"; }
			}
		}
		if ($number == 0) {
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td>&nbsp;</td>
				<td align="center">No Current Users</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?PHP
		}
		print ("</table></div>");
		print("
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
		<tr>
		");
		if (!($prev < 0)) {
			print("
			<td><div align=\"left\">
			<a href=\"admin.php?action=viewusers&start=$prev\"><img src=\"../images/previous.gif\" border=\"0\" alt=\"Previous\"></a>
			</div></td>
			");
		}
		if ($end < $total) {
			print("<td><div align=\"right\">
			<a href=\"admin.php?action=viewusers&start=$end\"><img src=\"../images/next.gif\" border=\"0\" alt=\"Next\"></a>
			</div></td>
			");
		}
		print("</tr></table><br>");
	}
	if ($action == "editaccount") {
		editaccount ($id);
	}
	if ($action == "viewaccount") {
		viewaccount ($id);
	}
	if ($action == "editcoupon") {
		editcoupon ($id);
	}
	if ($action == "addcoupons") {
		addcoupon();
	}
	if ($action == "deletecoupon") {
		$DB_site->query("DELETE FROM ".$dbprefix."coupons where id='$id'");
	    print("Coupon deleted sucessfully.");
	}
	if ($action == "viewcoupons") {
	    $place = "silver";
		$number = 0;
		print ("
		<div align=\"center\"><font color=\"Navy\"><b>Coupons</b></font><br>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
			<tr bgcolor=\"navy\">
			    <td><div align=\"center\"><b><font color=\"White\">Coupon ID</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Coupon Number</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Discount</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Number Used</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Action</font></b></div></td>
			</tr>
		");
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."coupons ORDER by sold");
		while ($row=$DB_site->fetch_array($temps)) {
		    $number++;
			?>
			<tr bgcolor="<?PHP echo $place ?>">
			    <td align="center"><?PHP echo $row[id] ?></td>
				<td align="center"><?PHP echo $row[couponid] ?></td>
				<td align="center"><?PHP echo $row[discount] ?></td>
				<td align="center"><?PHP echo $row[sold] ?></td>
				<td align="center"><a href="admin.php?action=editcoupon&id=<?PHP echo $row[id] ?>">Edit</a>&nbsp;|&nbsp;<a href="admin.php?action=deletecoupon&id=<?PHP echo $row[id] ?>" onclick="return jsconfirm(<?PHP echo $row[id] ?>);">Delete</a></td>
			</tr>
			<?PHP
			if ($place == "silver") { $place = "white"; } else { $place = "silver"; }
		}
		if ($number == 0) {
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center">No Current Coupons</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?PHP
		}
		print ("</table></div>");
	}
	if ($action == "viewcat") {
	    $place = "silver";
		$number = 0;
		print ("
		<div align=\"center\"><font color=\"Navy\"><b>Categories</b></font><br>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
			<tr bgcolor=\"navy\">
				<td><div align=\"center\"><b><font color=\"White\">Cat. ID</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Title</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Short Title</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Products In Cat.</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Display Order</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Action</font></b></div></td>
			</tr>
		");
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by displayorder");
		while ($row=$DB_site->fetch_array($temps)) {
		    $number++;
			$tot = categorytot ($row[categoryid]);
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td align="center"><?PHP echo $row[categoryid] ?></td>
				<td align="center"><?PHP echo $row[title] ?></td>
				<td align="center"><?PHP echo $row[stitle] ?></td>
				<td align="center"><?PHP echo $tot ?></td>
				<td align="center"><?PHP echo $row[displayorder] ?></td>
				<td align="center"><a href="admin.php?action=editcat&id=<?PHP echo $row[categoryid] ?>">Edit</a>&nbsp;|&nbsp;<a href="admin.php?action=deletecat&id=<?PHP echo $row[categoryid] ?>" onclick="return jsconfirm(<?PHP echo $row[categoryid] ?>);">Delete</a></td>
			</tr>
			<?PHP
			if ($place == "silver") { $place = "white"; } else { $place = "silver"; }
		}
		if ($number == 0) {
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td>&nbsp;</td>
				<td align="center">No Current Categories</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?PHP
		}
		print ("</table></div>");
	}
	if ($action == "editcat") {
		editcat ($id);
	}
	if ($action == "addcat") {
		addcat ();
	}
	if ($action == "storecat") {
		$DB_site->query("INSERT INTO ".$dbprefix."category (categoryid,title,stitle,displayorder) VALUES (NULL,'".addslashes($title)."','".addslashes($stitle)."','$displayorder')");
        echo "<p>New category inputed successfully!";
	}
	if ($action == "viewsubcat") {
	    $place = "silver";
		$number = 0;
		print ("
		<div align=\"center\"><font color=\"Navy\"><b>Subcategories</b></font><br>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
			<tr bgcolor=\"navy\">
				<td><div align=\"center\"><b><font color=\"White\">Sub ID</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Cat.</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Title</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Products In Subcat.</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Display Order</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Action</font></b></div></td>
			</tr>
		");
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory ORDER by displayorder");
		while ($row=$DB_site->fetch_array($temps)) {
		    $number++;
			$tot = subcategorytot ($row[subcategoryid]);
			$catname = getcategoryname ($row[categoryid]);
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td align="center"><?PHP echo $row[subcategoryid] ?></td>
				<td align="center"><?PHP echo $catname ?></td>
				<td align="center"><?PHP echo $row[title] ?></td>
				<td align="center"><?PHP echo $tot ?></td>
				<td align="center"><?PHP echo $row[displayorder] ?></td>
				<td align="center"><a href="admin.php?action=editsubcat&id=<?PHP echo $row[subcategoryid] ?>">Edit</a>&nbsp;|&nbsp;<a href="admin.php?action=deletesubcat&id=<?PHP echo $row[subcategoryid] ?>" onclick="return jsconfirm(<?PHP echo $row[subcategoryid] ?>);">Delete</a></td>
			</tr>
			<?PHP
			if ($place == "silver") { $place = "white"; } else { $place = "silver"; }
		}
		if ($number == 0) {
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td>&nbsp;</td>
				<td align="center">No Current Subcategories</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?PHP
		}
		print ("</table></div>");
	}
	if ($action == "editsubcat") {
		editsubcat ($id);
	}
	if ($action == "addsubcat") {
		addsubcat ();
	}
	if ($action == "storesubcat") {
		$DB_site->query("INSERT INTO ".$dbprefix."subcategory (subcategoryid, categoryid,title,stitle,displayorder) VALUES (NULL,'$categoryid','".addslashes($title)."','".addslashes($stitle)."','$displayorder')");
        echo "<p>New subcategory inputed successfully!";
	}
	if ($action == "viewitems") {
	    if ($start == "") { $start = 0; }
	    $place = "silver";
		$number = 0; $total = 0;
		
		if ($cat == "none") {
			$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where title like '%$for%'");
		}
		elseif ($cat == "" || $cat == "all") {
			$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items ORDER by itemid");
		} else {
		   $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where categoryid='$cat'");
		}
		while ($row=$DB_site->fetch_array($temps)) {
			$total++;
		}
		$end = $start + 100;
		$prev = $start - 100;
		print ("
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td>
		<form action=\"admin.php?action=viewitems\" method=\"post\">
		<b>Category:</b> <select name=\"cat\"><option value=\"all\" selected>All</option>");
		$temp=$DB_site->query("SELECT * FROM ".$dbprefix."category");
		while ($row=$DB_site->fetch_array($temp)) {
		     print("<option value=\"$row[categoryid]\">$row[title]</option>"); 
		}
		$temp = $start+1;
		if ($total < $end) { $end = $total; }
		print ("
		</select>&nbsp;<input type=\"image\" name=\"Submit\" src=\"../images/go.gif\" border=\"0\">
		</td></form><td>
		<form action=\"admin.php?action=viewitems\" method=\"post\">
		<input type=\"hidden\" name=\"cat\" value=\"none\">
		<b>Product Name Contains:</b> <input type=\"text\" name=\"for\">&nbsp;<input type=\"image\" name=\"Submit\" src=\"../images/go.gif\" border=\"0\">
		</td></tr></table></form>
		<div align=\"center\"><font color=\"Navy\"><b>Products</b> (<b>$temp</b> thru <b>$end</b> of <b>$total</b>)</font><br>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
			<tr bgcolor=\"navy\">
				<td><div align=\"center\"><b><font color=\"White\">Product ID</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Title</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Price</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">In Stock</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Action</font></b></div></td>
			</tr>
		");
		
		
		if ($cat == "none") {
			$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where title like '%$for%' ORDER by itemid LIMIT $start,100");
		}
		elseif ($cat == "" || $cat == "all") {
			$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items ORDER by itemid LIMIT $start,100");
		} else {
		   $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where categoryid='$cat' ORDER by itemid LIMIT $start,100");
		}
		while ($row=$DB_site->fetch_array($temps)) {
		    $number++;
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td align="center"><?PHP echo $row[itemid] ?></td>
				<td align="center"><?PHP echo stripslashes($row[title]); ?></td>
				<td align="center"><?PHP echo $row[price] ?></td>
				<td align="center"><?PHP echo $row[quantity] ?></td>
				<td align="center"><a href="admin.php?action=edititem&id=<?PHP echo $row[itemid] ?>">Edit</a>&nbsp;|&nbsp;<a href="admin.php?action=deleteitem&id=<?PHP echo $row[itemid] ?>" onclick="return jsconfirm(<?PHP echo $row[itemid] ?>);">Delete</a></td>
			</tr>
			<?PHP
			if ($place == "silver") { $place = "white"; } else { $place = "silver"; }
		}
		if ($number == 0) {
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td>&nbsp;</td>
				<td align="center">No Current Items</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?PHP
		}
		print ("</table></div>");
		print("
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
		<tr>
		");
		if (!($prev < 0)) {
			print("
			<td><div align=\"left\">
			<a href=\"admin.php?action=viewitems&start=$prev&cat=$cat&for=$for\"><img src=\"../images/previous.gif\" border=\"0\" alt=\"Previous\"></a>
			</div></td>
			");
		}
		if ($end < $total) {
			print("<td><div align=\"right\">
			<a href=\"admin.php?action=viewitems&start=$end&cat=$cat&for=$for\"><img src=\"../images/next.gif\" border=\"0\" alt=\"Next\"></a>
			</div></td>
			");
		}
		print("</tr></table><br>");
	}
	if ($action == "edititem") {
		edititem ($id);
	}
	if ($action == "additems") {
		additems ();
	}
	if ($action == "storeitem") {
	    $price = number_format($price,2,".","");
		$DB_site->query("INSERT INTO ".$dbprefix."items VALUES (NULL,'$categoryid','$subcategoryid','".addslashes($title)."','$imagename','$thumb','".addslashes($poverview)."','".addslashes($pdetails)."','$quantity','$sold','$price','$weight','$viewable','$option1','$option12','$option2','$option22','$option3','$option32', '$payop', '$sku')");
		echo "New item stored successfully";
	}
	if ($action == "updateitem") {
	    $price = number_format($price,2,".","");
		$DB_site->query("Update ".$dbprefix."items set categoryid='$categoryid', subcategoryid='$subcategoryid', title='".addslashes($title)."', imagename='$imagename', thumb='$thumb', poverview='".addslashes($poverview)."', pdetails='".addslashes($pdetails)."', quantity='$quantity', price='$price', weight='$weight', viewable='$viewable', option1='$option1', option12='$option12', option2='$option2', option22='$option22', option3='$option3', option32='$option32', payop='$payop', sku='$sku' where itemid='$id'");
		echo "Item $id was successfully updated";
	}
	if ($action == "addspecial") {
		addspecial ();
	}
	if ($action == "editspecial") {
		editspecial ($id);
	}
	if ($action == "viewspecials") {
	    if ($start == "") { $start = 0; }
	    $place = "silver";
		$number = 0;
		$total = 0;
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials ORDER by specialid LIMIT $start,100");
		while ($row=$DB_site->fetch_array($temps)) {
			$total++;
		}
		$end = $start + 100;
		$prev = $start - 100;
		$temp = $start+1;
		if ($total < $end) { $end = $total; }
		print ("
		<div align=\"center\"><font color=\"Navy\"><b>Specials</b> (<b>$temp</b> thru <b>$end</b> of <b>$total</b>)</font><br>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
			<tr bgcolor=\"navy\">
				<td><div align=\"center\"><b><font color=\"White\">Special ID</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Product #</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Product Title</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Sale Price</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Action</font></b></div></td>
			</tr>
		");
		
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials ORDER by specialid LIMIT $start,100");
		while ($row=$DB_site->fetch_array($temps)) {
		    $number++;
			$iteminfo = iteminfo ($row[itemid]);
			$temp8 = number_format($row[sprice],2,".","");
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td align="center"><?PHP echo $row[specialid] ?></td>
				<td align="center"><?PHP echo $row[itemid] ?></td>
				<td align="center"><?PHP echo $iteminfo[title] ?></td>
				<td align="center"><?PHP echo $temp8 ?></td>
				<td align="center"><a href="admin.php?action=editspecial&id=<?PHP echo $row[specialid] ?>">Edit</a>&nbsp;|&nbsp;<a href="admin.php?action=deletespecial&id=<?PHP echo $row[specialid] ?>" onclick="return jsconfirm(<?PHP echo $row[specialid] ?>);">Delete</a></td>
			</tr>
			<?PHP
			if ($place == "silver") { $place = "white"; } else { $place = "silver"; }
		}
		if ($number == 0) {
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td>&nbsp;</td>
				<td>No Current Specials</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?PHP
		}
		print ("</table></div>");
		print("
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
		<tr>
		");
		if (!($prev < 0)) {
			print("
			<td><div align=\"left\">
			<a href=\"admin.php?action=viewspecials&start=$prev\"><img src=\"../images/previous.gif\" border=\"0\" alt=\"Previous\"></a>
			</div></td>
			");
		}
		if ($end < $total) {
			print("<td><div align=\"right\">
			<a href=\"admin.php?action=viewspecials&start=$end\"><img src=\"../images/next.gif\" border=\"0\" alt=\"Next\"></a>
			</div></td>
			");
		}
		print("</tr></table><br>");
	}
	if ($action == "updatespecial") {
	    $temp8 = number_format($sprice,2,".","");
		$DB_site->query("Update ".$dbprefix."specials set sdescription='".addslashes($sdescription)."', sprice='$temp8' where specialid='$id'");
		echo "Special $id was successfully updated";
	}
	if ($action == "updatetransaction") {
		$DB_site->query("Update ".$dbprefix."transaction set status='$status', ccstatus='$ccstatus' where orderid='$id'");
		echo "Transaction $id was successfully updated";
	}
	if ($action == "storespecial") {
	    $temp8 = number_format($sprice,2,".","");
		$DB_site->query("INSERT INTO ".$dbprefix."specials (specialid,itemid,sdescription,sprice) VALUES (NULL,'$itemid','".addslashes($sdescription)."','$temp8')");
		echo "New special stored successfully";
	}
	if ($action == "viewtransactions") {
	    if ($start == "") { $start = 0; }
	    $place = "silver";
		$number = 0; $total = 0;
		if ($fdate == "") {
			if ($id == "" && $name == "") {
				$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction ORDER by orderid");
			} else {
			    if ($name != "") {
					$temp3=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$name'");
			        while ($row3=$DB_site->fetch_array($temp3)) {
						$id = $row3[userid];
					}
				}
				$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where userid='$id'");
			}
		} else {
			$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where tdate='$fdate' ORDER by orderid");
		} 
		while ($row=$DB_site->fetch_array($temps)) {
			$total++;
		}
		$end = $start + 100;
		$prev = $start - 100;
		$temp = $start+1;
		if ($total < $end) { $end = $total; }
		print ("
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td>
		<form action=\"admin.php?action=viewtransactions\" method=\"post\">
		<b>By User ID:</b> <input type=\"text\" name=\"id\" size=\"4\">&nbsp;<input type=\"image\" name=\"Submit\" src=\"../images/go.gif\" border=\"0\">
		</td></form><td>
		<form action=\"admin.php?action=viewtransactions\" method=\"post\">
		<b>By Username:</b> <input type=\"text\" name=\"name\" size=\"10\">&nbsp;<input type=\"image\" name=\"Submit\" src=\"../images/go.gif\" border=\"0\">
		</td><td>
		<form action=\"admin.php?action=viewtransactions\" method=\"post\">
		<b>By Date:</b> <input type=\"text\" name=\"fdate\" size=\"10\">&nbsp;<input type=\"image\" name=\"Submit\" src=\"../images/go.gif\" border=\"0\">
		</td>
		</tr>
		</table><br>
		<div align=\"center\"><font color=\"Navy\"><b>Transactions</b> (<b>$temp</b> thru <b>$end</b> of <b>$total</b>)</font><br>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
			<tr bgcolor=\"navy\">
				<td><div align=\"center\"><b><font color=\"White\">Tansaction ID</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Username</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Date</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Method</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Status</font></b></div></td>
				<td><div align=\"center\"><b><font color=\"White\">Action</font></b></div></td>
			</tr>
		");
		if ($fdate == "") {
			if ($id == "" && $name == "") {
				$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction ORDER by orderid LIMIT $start,100");
			} else {
			    if ($name != "") {
					$temp3=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$name' LIMIT $start,100");
			        while ($row3=$DB_site->fetch_array($temp3)) {
						$id = $row3[userid];
					}
				}
				$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where userid='$id' LIMIT $start,100");
			}
		} else {
			$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where tdate='$fdate' ORDER by orderid LIMIT $start,100");
		} 
		while ($row=$DB_site->fetch_array($temps)) {
		    $number++;
			$temp=$DB_site->query("SELECT * FROM ".$dbprefix."user where userid='$row[userid]'");
		    while ($row1=$DB_site->fetch_array($temp)) {
				$username = $row1[username];
			}
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td align="center"><?PHP echo $row[orderid] ?></td>
				<td align="center"><?PHP echo $username ?></td>
				<td align="center"><?PHP echo $row[tdate] ?></td>
				<td align="center"><?PHP echo $row[method] ?></td>
				<td align="center"><?PHP echo $row[status] ?></td>
				<td align="center"><a href="admin.php?action=viewtransaction&id=<?PHP echo $row[orderid] ?>">View</a>&nbsp;|&nbsp;<a href="admin.php?action=deletetransaction&id=<?PHP echo $row[orderid] ?>" onclick="return jsconfirm(<?PHP echo $row[orderid] ?>);">Delete</a></td>
			</tr>
			<?PHP
			if ($place == "silver") { $place = "white"; } else { $place = "silver"; }
		}
		if ($number == 0) {
			?>
			<tr bgcolor="<?PHP echo $place ?>">
				<td>&nbsp;</td>
				<td>No Transactions</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?PHP
		}
		print ("</table></div>");
		print("
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
		<tr>
		");
		if (!($prev < 0)) {
			print("
			<td><div align=\"left\">
			<a href=\"admin.php?action=viewtransactions&start=$prev&id=$id&name=$name&fdate=$fdate\"><img src=\"../images/previous.gif\" border=\"0\" alt=\"Previous\"></a>
			</div></td>
			");
		}
		if ($end < $total) {
			print("<td><div align=\"right\">
			<a href=\"admin.php?action=viewtransactions&start=$end&id=$id&name=$name&fdate=$fdate\"><img src=\"../images/next.gif\" border=\"0\" alt=\"Next\"></a>
			</div></td>
			");
		}
		print("</tr></table><br>");
	}
	if ($action == "viewtransaction") {
		viewtransaction ($id);
	}
	if ($action == "settings") {
		showpref ();
	}
	if ($action == "nullification") {
		nullification ();
	}
	
	if ($action=="backup") {
	  ?>
	  <b><font color="Navy">Database Backup</font></b><br><br>
	  
	  <form action="admin.php" method="post">
	  <input type="hidden" name="action" value="dobackup">
	  <table width="90%" border="0" cellspacing="0" cellpadding="3">
		<tr>
			<td colspan="2" align="center" bgcolor="Navy"><font color="White"><b>Choose Tables</b></font></td>
		</tr>
	
	  <?PHP
	  $color = "white";
	  $result=$DB_site->query("SHOW tables");
	  while ($currow=$DB_site->fetch_array($result)) {
	    print("
		    <tr bgcolor=\"$color\">
			<td>$currow[0]</td>
			<td>Yes<input type=\"radio\" name=\"table[$currow[0]]\" value=\"1\" checked>&nbsp;&nbsp;No<input type=\"radio\" name=\"table[$currow[0]]\" value=\"0\"></td>
			</tr>
		");
		if ($color == "white") { $color = "silver"; } else { $color = "white"; }
	  }
	  $dater = date("m-d-Y",time());
	  print("
	    <tr bgcolor=\"$color\">
			<td valign=\"top\">Save As:</td>
			<td><input type=\"text\" name=\"filename\" value=\"./backup/sunshop-$dater.sql\" size=\"60\"><br>(Make sure PHP can write to this directory. You can create the directory \"backup\" if neccessary and change the attributes to allow writing to the directory.)</td>
		</tr>
		<tr>
		   <td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"Backup Data\"></td>
		</tr>
	  </table>
	  </form>
	  ");
	}
	
	if ($action == "dobackup") {
	
	  $filehandle=fopen($filename,"w");
	  $result=$DB_site->query("SHOW tables");
	  while ($currow=$DB_site->fetch_array($result)) {
	        if ($table[$currow[0]] == 1) {
				fwrite($filehandle,sqldumptable($currow[0])."\n\n");
				echo "<p>Backing up $currow[0]</p>";
			}
	  }
	  fclose($filehandle);
	
	  echo "<p>Data backup sucessfully!</p>";
	
	}
	
	if ($action == "updatesettings") {
	    $title = addslashes($title);
		$hometitle = addslashes($hometitle);
		$shopurl = addslashes($shopurl);
		$homeurl = addslashes($homeurl);
		$securepath = addslashes($securepath);
		$companyname = addslashes($companyname);
		$address = addslashes($address);
		$city = addslashes($city);
		$state = addslashes($state);
		$zip = addslashes($zip);
		$country = addslashes($country);
		$phone = addslashes($phone);
		$faxnumber = addslashes($faxnumber);
		$contactemail = addslashes($contactemail);
		$taxrate = addslashes($taxrate);
		$shipups = addslashes($shipups);
		$grnd = addslashes($grnd);
		$nextdayair = addslashes($nextdayair);
		$seconddayair = addslashes($seconddayair);
		$threeday = addslashes($threeday);
		$canada = addslashes($canada);
		$worldwidex = addslashes($worldwidex);
		$worldwidexplus = addslashes($worldwidexplus);
		$fixedshipping = addslashes($fixedshipping);
		$method = addslashes($method);
		$rate = addslashes($rate);
		$productpath = addslashes($productpath);
		$catimage = addslashes($catimage);
		$catopen = addslashes($catopen);
		$viewcartimage = addslashes($viewcartimage);
		$viewaccountimage = addslashes($viewaccountimage);
		$checkoutimage = addslashes($checkoutimage);
		$helpimage = addslashes($helpimage);
		$cartimage = addslashes($cartimage);
		$tablehead = addslashes($tablehead);
		$tableheadtext = addslashes($tableheadtext);
		$tableborder = addslashes($tableborder);
		$tablebg = addslashes($tablebg);
		$shipchart = addslashes($shipchart);
		$ship1p1 = addslashes($ship1p1);
		$ship1us = addslashes($ship1us);
		$ship1ca = addslashes($ship1ca);
		$ship2 = addslashes($ship2);
		$ship2p1 = addslashes($ship2p1);
		$ship2p2 = addslashes($ship2p2);
		$ship2us = addslashes($ship2us);
		$ship2ca = addslashes($ship2ca);
		$ship3 = addslashes($ship2);
		$ship3p1 = addslashes($ship3p1);
		$ship3p2 = addslashes($ship3p2);
		$ship3us = addslashes($ship3us);
		$ship3ca = addslashes($ship3ca);
		$ship4p1 = addslashes($ship4p1);
		$ship4us = addslashes($ship4us);
		$ship4ca = addslashes($ship4ca);
		$visa = addslashes($visa);
		$mastercard = addslashes($mastercard);
		$discover = addslashes($discover);
		$amex = addslashes($amex);
		$check = addslashes($check);
		$fax = addslashes($fax);
		$moneyorder = addslashes($moneyorder);
		$cc = addslashes($cc);
	    $payable = addslashes($payable);
		$paypal = addslashes($paypal);
	    $paypalemail = addslashes($paypalemail);
	    $shopimage = addslashes($shopimage);
		$centercolor = addslashes($centercolor);
		$centerborder = addslashes($centerborder);
		$centerheader = addslashes($centerheader);
		$centerfont = addslashes($centerfont);
		$centerbg = addslashes($centerbg);
		$header1 = addslashes($header1);
		$footer = addslashes($footer);
		$myheader = addslashes($myheader);
		$myfooter = addslashes($myfooter);
		$useheader = addslashes($useheader);
		$usefooter = addslashes($usefooter);
		$css = addslashes($css);
		$thumbwidth1 = addslashes($thumbwidth1);
		$thumbheight1 = addslashes($thumbheight1);
		$picwidth1 = addslashes($picwidth1);
		$picheight1 = addslashes($picheight1);
		$showstock = addslashes($showstock);
		$showitem = addslashes($showitem);
		$showintro = addslashes($showintro);
		$shopintro = addslashes($shopintro);
		$orderby = addslashes($orderby);
		$outofstock = addslashes($outofstock);
		$cs = addslashes($cs);
		$showprice = addslashes($showprice);
		$po = addslashes($po);
		$license = addslashes($license);
		$handling = addslashes($handling);
		$imagel = addslashes($imagel);
		
		$DB_site->query("UPDATE ".$dbprefix."options set title='$title', hometitle='$hometitle', shopurl='$shopurl', 
		homeurl='$homeurl', securepath='$securepath', header1='$header1', footer='$footer', companyname='$companyname',
		address='$address', city='$city', state='$state', zip='$zip', country='$country', phone='$phone', faxnumber='$faxnumber', 
		contactemail='$contactemail', taxrate='$taxrate', shipups='$shipups', grnd='$grnd', nextdayair='$nextdayair', 
		seconddayair='$seconddayair', threeday='$threeday', canada='$canada', worldwidex='$worldwidex', worldwidexplus='$worldwidexplus',
		fixedshipping='$fixedshipping', method='$method', rate='$rate', productpath='$productpath1', catimage='$catimage', 
		catopen='$catopen', viewcartimage='$viewcartimage', viewaccountimage='$viewaccountimage', checkoutimage='$checkoutimage', 
		helpimage='$helpimage', cartimage='$cartimage', tablehead='$tablehead', tableheadtext='$tableheadtext', tableborder='$tableborder', 
		tablebg='$tablebg', shipchart='$shipchart',	ship1p1='$ship1p1', ship1us='$ship1us', ship1ca='$ship1ca', ship2='$ship2', 
		ship2p1='$ship2p1', ship2p2='$ship2p2', ship2us='$ship2us', ship2ca='$ship2ca', ship3='$ship3', ship3p1='$ship3p1', 
		ship3p2='$ship3p2', ship3us='$ship3us', ship3ca='$ship3ca', ship4p1='$ship4p1', ship4us='$ship4us', ship4ca='$ship4ca', 
		visa='$visa', mastercard='$mastercard', discover='$discover', amex='$amex', check='$check', fax='$fax', 
		moneyorder='$moneyorder', cc='$cc', payable='$payable', paypal='$paypal', paypalemail='$paypalemail', shopimage='$shopimage',
		useheader='$useheader', usefooter='$usefooter', myheader='$myheader', myfooter='$myfooter', centerborder='$centerborder', 
		centerheader='$centerheader', centercolor='$centercolor', centerfont='$centerfont', centerbg='$centerbg', css='$css', 
		thumbheight='$thumbheight1', thumbwidth='$thumbwidth1', picheight='$picheight1', picwidth='$picwidth1', showstock='$showstock', 
		showitem='$showitem', showintro='$showintro', shopintro='$shopintro', orderby='$orderby', outofstock='$outofstock', cs='$cs',
		showprice='$showprice', po='$po', license='$license', handling='$handling', imagel='$imagel'");
		
		echo "<p>Settings have been saved successfully.</p>";
	}
?>
    <br><br>
	<div align="center" class="small"><font color="Gray">"Sunshop" &copy;Copyright 2001-2002 <!--CyKuH-->Turnkey Solutions</font>
    </div>
	</body>
	</html>
<?PHP
}
