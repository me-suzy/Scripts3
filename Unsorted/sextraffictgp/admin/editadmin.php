<?php
include("header.php");

if($access[canadmin]=="1"){


if($submitaddadmin){
$addadmin = mysql_query("INSERT INTO st_admin SET admin_username='$admin_username', admin_password='$admin_password', admin_email='$admin_email', admin_groupid='$admin_groupid'");
if(!$addadmin) {
echo("<p> Error adding admin : " .
mysql_error() . "</p>");
}
?>
<meta http-equiv ="Refresh" content = "0 ; URL=editadmin.php">
<?php
exit;
}


if($actions=="addadmin"){?>

  <table width="700" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td bgcolor="#333366"><b><font face="Arial" size="3" color="#FFFFFF">Add Admin Account</font></b></td>
    </tr>
  </table>
<form action="<?=$PHP_SELF?>" method="post">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="150"><div align="right"><b><font size="2" face="Arial">Admin Name</font></b></div></td>
      <td><input type="text" name="admin_username" size="45"></td>
    </tr>
    <tr> 
      <td width="150"><div align="right"><b><font size="2" face="Arial">Password</font></b></div></td>
      <td><input type="text" name="admin_password" size="45"></td>
    </tr>
    <tr> 
      <td width="150"><div align="right"><b><font size="2" face="Arial">Email</font></b></div></td>
      <td><input type="text" name="admin_email" size="45"></td>
    </tr>
    <tr> 
      <td width="150"><div align="right"><b><font size="2" face="Arial">Group ID</font></b></div></td>
      <td> 
        <select name="admin_groupid">
          <?php
$s2 = mysql_query("SELECT * FROM st_admin_group");
while($r2 = mysql_fetch_array($s2)){
$group_id = $r2["usergroupid"];
$usertitle = $r2["usertitle"];
echo "<option value='$group_id'>$usertitle</option>";}?>
        </select>
      </td>
    </tr>
  </table>
  <table width="500" border="0" cellspacing="0" cellpadding="5">
    <tr>
  <td width="100%"><div align="center"><input type="submit" name="submitaddadmin" value="SUBMIT"></div></td>
 </tr>
</table>

</form>
<?php
include("footer.php");
exit;}

############################################################
############################################################



if($action=="editadmin"){
$sql = mysql_fetch_array(mysql_query("SELECT * FROM st_admin WHERE id='$id'"));
$admin_username = $sql["admin_username"];
$admin_password = $sql["admin_password"];
$admin_email = $sql["admin_email"];
$admin_groupid = $sql["admin_groupid"];

?>
  <table width="700" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td bgcolor="#333366"><b><font face="Arial" size="3" color="#FFFFFF">Edit Admin &nbsp; <?=$admin_username?></font><font face="Arial" size="3" color="#FFFFFF">
	  <input type="hidden" name="id" value="<?=$id?>"></font></b></td>
    </tr>
  </table>
<form action="<?=$PHP_SELF?>" method="post">
		    <input type="hidden" name="id" value="<?=$id?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="150"><div align="right"><b><font size="2" face="Arial">Admin Name</font></b></div></td>
      <td><input type="text" name="admin_username" size="45" value="<?=$admin_username?>"></td>
    </tr>
    <tr> 
      <td width="150"><div align="right"><b><font size="2" face="Arial">Password</font></b></div></td>
      <td><input type="text" name="admin_password" size="45" value="<?=$admin_password?>"></td>
    </tr>
    <tr> 
      <td width="150"><div align="right"><b><font size="2" face="Arial">Email</font></b></div></td>
      <td><input type="text" name="admin_email" size="45" value="<?=$admin_email?>"></td>
    </tr>
    <tr> 
      <td width="150"><div align="right"><b><font size="2" face="Arial">Group ID</font></b></div></td>
      <td> 
        <select name="admin_groupid">
          <?php
$s2 = mysql_query("SELECT * FROM st_admin_group");
while($r2 = mysql_fetch_array($s2)){
$group_id = $r2["usergroupid"];
$usertitle = $r2["usertitle"];
echo "<option value='$group_id'"; if($group_id == $admin_groupid){ echo " selected";}echo ">$usertitle</option>";}?>
        </select>
      </td>
    </tr>
  </table>
  <table width="500" border="0" cellspacing="0" cellpadding="5">
    <tr>
  <td width="100%"><div align="center"><input type="submit" name="edituseryes" value="SUBMIT"></div></td>
 </tr>
</table>

</form>
<?php
include("footer.php");
exit;}


if($edituseryes){
$updateadmin = mysql_query("UPDATE st_admin SET admin_username='$admin_username', admin_password='$admin_password', admin_email='$admin_email', admin_groupid='$admin_groupid' WHERE id='$id'");
if(!$updateadmin) {
echo("<p> Error editing admin : " .
mysql_error() . "</p>");
}
?>
<meta http-equiv ="Refresh" content = "0 ; URL=editadmin.php">
<?php
exit;
}


############################################################
############################################################


if($submitgroup){
$sql = mysql_query("INSERT INTO st_admin_group SET usertitle='$usertitle', cansettings='$cansettings', canbanwords='$canbanwords', cancategory='$cancategory', cantemplates='$cantemplates', cangalleries='$cangalleries', canstats='$canstats', canadmin='$canadmin'");
if ($sql){
echo("<font size='2' face='arial'><b>Access rules added!</b></font>");
} else {
echo("Error: " .
mysql_error() . "");
}
?>
<meta http-equiv ="Refresh" content = "1 ; URL=editadmin.php?action=groups">
<?php
exit;}



if($action=="addgroup"){ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td bgcolor="#333366"><font size="4"><b><font face="Arial" color="#FFFFFF">Add admin group</font></b></font></td>
  </tr>
</table>
<form action="<?=$PHP_SELF?>" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">User Title:</font></div></td>
    <td><input type="text" name="usertitle" size="25"></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit settings:</font></div></td>
    <td><input type="checkbox" name="cansettings" value="1"><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit/add ban words:</font></div></td>
    <td><input type="checkbox" name="canbanwords" value="1"><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit/add categories:</font></div></td>
    <td><input type="checkbox" name="cancategory" value="1"><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit templates:</font></div></td>
    <td><input type="checkbox" name="cantemplates" value="1"><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit/add/search galleries:</font></div></td>
    <td><input type="checkbox" name="cangalleries" value="1"><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can view statistics:</font></div></td>
    <td><input type="checkbox" name="canstats" value="1"><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit/add admins:</font></div></td>
    <td><input type="checkbox" name="canadmin" value="1"><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
</table><br>
<table width="500" border="0" cellspacing="0" cellpadding="5">
<tr>
<td width="50%"><div align="right"><input type=reset value="Reset Form"></div></td>
<td width="50%" align="left"><input type="submit" name="submitgroup" value="SUBMIT"></div></td>
</tr>
</table>

</form>
<?php
include("footer.php");
exit;}

################################################
################################################

if($editgrs){
$sql = mysql_query("UPDATE st_admin_group SET usertitle='$usertitle', cansettings='$cansettings', canbanwords='$canbanwords', cancategory='$cancategory', cantemplates='$cantemplates', cangalleries='$cangalleries', canstats='$canstats', canadmin='$canadmin' WHERE usergroupid='$usergroupid'");
if ($sql){
echo("<font size='2' face='arial'><b>Access rules updated!</b></font>");
} else {
echo("Error: " .
mysql_error() . "");
}
?>
<meta http-equiv ="Refresh" content = "1 ; URL=editadmin.php?action=groups">
<?php
exit;}


if($action=="editgroup"){
$r = mysql_fetch_array(mysql_query("SELECT * FROM st_admin_group WHERE usergroupid='$usergroupid'"));
$usergroupid = $r["usergroupid"];
$usertitle = $r["usertitle"];
$cansettings = $r["cansettings"];
$canbanwords = $r["canbanwords"];
$cancategory = $r["cancategory"];
$cantemplates = $r["cantemplates"];
$cangalleries = $r["cangalleries"];
$canstats = $r["canstats"];
$canadmin = $r["canadmin"];
?>

<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td bgcolor="#333366"><font size="4"><b><font face="Arial" color="#FFFFFF">Edit admin group settings</font></b></font></td>
  </tr>
</table>
<form action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="usergroupid" value="<?=$usergroupid?>">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">User Title:</font></div></td>
    <td><input type="text" name="usertitle" size="25" value="<?=$usertitle?>"></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit settings:</font></div></td>
    <td><input type="checkbox" name="cansettings" value="1" <?php if($cansettings=="1"){ echo" checked";}?>><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit/add ban words:</font></div></td>
    <td><input type="checkbox" name="canbanwords" value="1" <?php if($canbanwords=="1"){ echo" checked";}?>><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit/add categories:</font></div></td>
    <td><input type="checkbox" name="cancategory" value="1" <?php if($cancategory=="1"){ echo" checked";}?>><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit templates:</font></div></td>
    <td><input type="checkbox" name="cantemplates" value="1" <?php if($cantemplates=="1"){ echo" checked";}?>><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit/add/search galleries:</font></div></td>
    <td><input type="checkbox" name="cangalleries" value="1" <?php if($cangalleries=="1"){ echo" checked";}?>><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can view statistics:</font></div></td>
    <td><input type="checkbox" name="canstats" value="1" <?php if($canstats=="1"){ echo" checked";}?>><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
  <tr> 
    <td width="250"><div align="right"><font size="2" face="Arial">Can edit/add admins:</font></div></td>
    <td><input type="checkbox" name="canadmin" value="1" <?php if($canadmin=="1"){ echo" checked";}?>><font size="1" face="Verdana">(tick if yes)</font></td>
  </tr>
</table><br>
<table width="500" border="0" cellspacing="0" cellpadding="5">
<tr>
<td width="50%"><div align="right"><input type=reset value="Reset Form"></div></td>
<td width="50%" align="left"><input type="submit" name="editgrs" value="SUBMIT"></div></td>
</tr>
</table>

</form>
<?php
include("footer.php");
exit;}


######################################################
######################################################


if($action=="groups"){
$font1 = "<font size='1' face='verdana'>"; 
$font2 = "<font size='2' face='arial'>"; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="5">
 <tr>
 <td bgcolor="#333366"><font face="Arial" size="4" color="#FFFFFF"><b>Admin Acess settings</b></font></td>
 </tr>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="4">
 <tr bgcolor="<?=$admincolor3?>"> 
  <td width="20%"><div align="center"><font color="#FFFFFF" size="1" face="verdana">Admin Title</font></div></td>
  <td width="5%"><div align="center"><font color="#FFFFFF" size="1" face="verdana">Edit</font></div></td>
  <td width="5%"><div align="center"><font color="#FFFFFF" size="1" face="verdana">Delete</font></div></td>
  <td><div align="center"><font color="#FFFFFF" size="1" face="verdana">Can edit settings</font></div></td>
  <td><div align="center"><font color="#FFFFFF" size="1" face="verdana">Can add/edit banned words</font></div></td>
  <td><div align="center"><font color="#FFFFFF" size="1" face="verdana">Can add/edit categories</font></div></td>
  <td><div align="center"><font color="#FFFFFF" size="1" face="verdana">Can edit templates</font></div></td>
  <td><div align="center"><font color="#FFFFFF" size="1" face="verdana">Can edit galleries</font></div></td>
  <td><div align="center"><font color="#FFFFFF" size="1" face="verdana">Can view stats</font></div></td>
  <td><div align="center"><font color="#FFFFFF" size="1" face="verdana">Can add/edit admins</font></div></td>
</tr>
<?php

$trbg = "$admincolor1";

$sql = mysql_query("SELECT * FROM st_admin_group ORDER BY usergroupid");
while($r = mysql_fetch_array($sql)){
$usergroupid = $r["usergroupid"];
$usertitle = $r["usertitle"];
$cansettings = $r["cansettings"];
$canbanwords = $r["canbanwords"];
$cancategory = $r["cancategory"];
$cantemplates = $r["cantemplates"];
$cangalleries = $r["cangalleries"];
$canstats = $r["canstats"];
$canadmin = $r["canadmin"];

if($cansettings=="1"){ $cansettings = "Yes";} else { $cansettings = "&nbsp;";}
if($canbanwords=="1"){ $canbanwords = "Yes";} else { $canbanwords = "&nbsp;";}
if($cancategory=="1"){ $cancategory = "Yes";} else { $cancategory = "&nbsp;";}
if($cantemplates=="1"){ $cantemplates = "Yes";} else { $cantemplates = "&nbsp;";}
if($cangalleries=="1"){ $cangalleries = "Yes";} else { $cangalleries = "&nbsp;";}
if($canstats=="1"){ $canstats = "Yes";} else { $canstats = "&nbsp;";}
if($canadmin=="1"){  $canadmin = "Yes";} else { $canadmin = "&nbsp;";}

?>
<tr bgcolor="<?=$trbg?>"> 
 <td width="20%"><?=$font1?><?=$usertitle?></font></td>
 <td width="5%"><div align="center"><?=$font2?><a href="editadmin.php?usergroupid=<?=$usergroupid?>&action=editgroup"><b> edit </b></a></font></div></td>
 <td width="5%"><div align="center"><?=$font2?><a href="actions.php?usergroupid=<?=$usergroupid?>&action=deletegroup"><b> delete </b></a></font></div></td>
 <td><div align="center"><?=$font1?><?=$cansettings?></font></div></td>
 <td><div align="center"><?=$font1?><?=$canbanwords?></font></div></td>
 <td><div align="center"><?=$font1?><?=$cancategory?></font></div></td>
 <td><div align="center"><?=$font1?><?=$cantemplates?></font></div></td>
 <td><div align="center"><?=$font1?><?=$cangalleries?></font></div></td>
 <td><div align="center"><?=$font1?><?=$canstats?></font></div></td>
 <td><div align="center"><?=$font1?><?=$canadmin?></font></div></td>
<?php
if($trbg=="$admincolor1") $trbg = "$admincolor2"; else $trbg = "$admincolor1";

}
echo "</tr></table>";
include("footer.php");
exit;}
?>

<table width="100%" border="0" cellspacing="0" cellpadding="5">
 <tr>
 <td bgcolor="#333366"><font face="Arial" size="4" color="#FFFFFF"><b>Admin Users</b></font></td>
 </tr>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="4">
  <tr bgcolor="<?=$admincolor3?>"> 
    <td width="5%"><div align="center"><b><font color="#FFFFFF" size="2" face="Arial">ID</font></b></div></td>
    <td width="10%"><b><font color="#FFFFFF" size="2" face="Arial">Admin</font></b></td>
    <td><b><font color="#FFFFFF" size="2" face="Arial">Email</font></b></td>
    <td><b><font color="#FFFFFF" size="2" face="Arial">Group</font></b></td>
    <td width="10%"><div align="center"><b><font color="#FFFFFF" size="2" face="Arial">Edit</font></b></div></td>
    <td width="10%"><div align="center"><b><font color="#FFFFFF" size="2" face="Arial">Delete</font></b></div></td>
  </tr>

<?php
$sql = mysql_query("SELECT * FROM st_admin ORDER BY id");
while($r = mysql_fetch_array($sql)){
$id = $r["id"];
$admin_username = $r["admin_username"];
$admin_email = $r["admin_email"];
$admin_groupid = $r["admin_groupid"];

$sql2 = mysql_fetch_array(mysql_query("SELECT * FROM st_admin_group WHERE usergroupid='$admin_groupid'"));
$usertitle = $sql2["usertitle"];

?>
  <tr> 
    <td bgcolor="<?=$admincolor1?>" width="5%"><div align="center"><font face="Arial" size="2"><?=$id?></font></div></td>
    <td bgcolor="<?=$admincolor1?>" width="10%"><font face="Arial" size="2"><?=$admin_username?></font></td>
    <td bgcolor="<?=$admincolor1?>"><a href="mailto:<?=$admin_email?>"><b><font face="Arial" size="2">Email</font></b></a></td>
    <td bgcolor="<?=$admincolor1?>"><font face="Arial" size="2"><?=$usertitle?></font></td>
    <td  width="10%" bgcolor="<?=$admincolor1?>"><div align="center"><font face="Arial" size="2"><b><a href="editadmin.php?id=<?=$id?>&action=editadmin">Edit</a></b></font></div></td>
    <td  width="10%" bgcolor="<?=$admincolor1?>"><div align="center"><font face="Arial" size="2"><b><a href="actions.php?id=<?=$id?>&action=deleteadmin">Delete</a></b></font></div></td>
<?php
}
echo "</tr></table>";
}

include("footer.php");
?>