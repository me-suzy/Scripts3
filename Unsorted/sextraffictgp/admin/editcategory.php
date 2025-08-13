<?php
include ("header.php");

if($access[cancategory]=="1"){

if ($submit){

  $sql = "UPDATE st_categories SET catname='$catname', visable='$visable', advert='$addvert' WHERE cid=$cid";
  if (mysql_query($sql)) {
    echo("");
  } else {
    echo("<p>Problem : " .
         mysql_error() . "</p>");
  }

?>
<meta http-equiv ="Refresh" content = "0 ; URL=categories.php">
<?php

exit; }// End of Submit



$editcat = mysql_query("SELECT * FROM st_categories WHERE cid='$cid'");
if (!$editcat) {
    echo("<p>Error fetching categorie(s): " .
      mysql_error() . "</p>");
    exit();
}
$editcat = mysql_fetch_array($editcat);
$catname = $editcat["catname"];
$cid = $editcat["cid"];
$visable = $editcat["visable"];
$advert = $editcat["advert"];
?>

<form action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="cid" value="<?=$cid?>">
  <table width="550" border="0" cellspacing="0" cellpadding="7" bgcolor="<?=$admincolor3?>">
<tr>
<td>
<div align="left"><font face="Arial" size="3" color="<?=$admincolor4?>"><b>Edit Category</b></font></div></td>
</tr>
</table>

<table width="550" border="0" cellspacing="0" cellpadding="5">
   <tr>
     <td width="250"><font face="Arial" size="2"><b>Category Name</b></font></td>
     <td><input type="text" name="catname" size="45" value="<?=$catname?>"></td>
   </tr>
   <tr>
     <td width="250"><font face="Arial" size="2"><b>Advert/banner:</b></font></td>
     <td><textarea name="addvert" cols="45" rows="5"><?=$advert?></textarea></td>
  </tr>
   <tr>
     <td width="250" valign="middle"><font size="2" face="Arial"><b>Active:</b></font><br>
       <font face="Verdana" size="1">Do you want this category displayed?</font><br>
     </td>
     <td><font face="Arial, Helvetica, sans-serif" size="2">
       <input type="radio" name="visable" value="Y" <?php if($visable=="Y") { print "Checked";}?>>Yes<br>
       <input type="radio" name="visable" value="N" <?php if($visable=="N") { print "Checked";}?>>No</font></td>
   </tr>
 </table>

 <table width="550" border="0" cellspacing="5" cellpadding="0" bgcolor="<?=$admincolor3?>">
<tr>
<td><div align="center"><input type="submit" name="submit" value="SUBMIT"></div></td>
</tr>
</table>
</form>


<?php
}
include("footer.php");
?>