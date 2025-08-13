<?php
include ("header.php");

if($access[cancategory]=="1"){

if ($submit){

  $sql = "INSERT INTO st_categories SET cid='$cid', catname='$catname', advert='$advert', visable='$visable'";
  if (mysql_query($sql)) {
    echo("<font size='2' face='arial'><b>Category added</b></font>");
  } else {
    echo("<p>Problem : " .
         mysql_error() . "</p>");
  }

?>
<meta http-equiv ="Refresh" content = "4 ; URL=categories.php">
<?php

exit; } // End submit

?>

<form action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="cid" value="<?=$cid?>">
  <table width="550" border="0" cellspacing="0" cellpadding="5" bgcolor="<?=$admincolor3?>">
  <tr>
    <td><div align="left"><b><font face="Arial" size="3" color="<?=$admincolor4?>">Add Category</font></b></div></td>
  </tr>
</table>

  <table width="550" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="250"><b><font face="Arial" size="2">Name</font></b></td>
      <td width=""><font face="Arial" size="2"><input type="text" name="catname" size="45"></font></td>
    </tr>
    <tr>
      <td width="250" valign="middle"><b><font face="Arial" size="2">Active:<br></font></b>
	  <font face="Verdana" size="1">Do you want this category displayed?</font></td>
      <td width=""><font face="Arial" size="2"><input type="radio" name="visable" value="Y" checked>Yes
	    <input type="radio" name="visable" value="N">No</font></td>
    </tr>
    <tr>
      <td width="250" valign="middle"><font size="2" face="Arial"><b>Advert/Banner:</b></font></td>
      <td width=""><textarea name="advert" cols="45" rows="5"></textarea></td>
    </tr>
  </table>
  <table width="550" border="0" cellspacing="5" cellpadding="0" bgcolor="<?=$admincolor3?>">
  <tr>
    <td>
      <div align="center">
        <input type="submit" name="submit" value="SUBMIT">
    </div>
    </td>
  </tr>
</table>
</form>

<?php 
}
include("footer.php");
?>