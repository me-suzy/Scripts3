<?php
include("member.php");

if ($picture=="none") $picture="";

  if ($upload)
  {
   
   if ((!$url)&&($picture)) 
   {
     if (!copy($picture,"pictures/m".$auth."_".$picture_name))  echo ("<br>Failed to upload '$picture_name' ... <br>\n");
    $url="m".$auth."_".$picture_name;
   };

if ($type=="Main") q("update pictures set type='Public' where type='Main' and member='$auth'");
if ($url) q("insert into pictures values('','$auth','$url','$description','$type','".strtotime(date("d M Y H:i:s"))."','$uploadpicturedisabled')");
};

if ($edit&&$picid)
{
 if ($type=="Main") q("update pictures set type='Public' where type='Main' and member='$auth'");
 q("update pictures set details='$description', type='$type' where id='$picid' and member='$auth'");
};

if ($delete&&$picid)
{
 q("delete from pictures where id='$picid' and member='$auth'");
};

$tpic=f(q("select count(*) as nr from pictures where member='$auth'"));

if ($tpic[nr]<$tm0pics)
{?>

<form action="manage_pictures.php" method="post" enctype="multipart/form-data" name="form1">
  <table width="600" border="0" cellpadding="2" cellspacing="1" align="center">
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><font size="2" face="Arial, Helvetica, sans-serif"><strong>Add 
        new picture &gt;&gt; <?php echo "($tpic[nr]/$tm0pics)";?></strong></font></td>
    </tr>
    <tr> 
      <td height="3" colspan="2" bgcolor="#000000"> </td>
    </tr>
    <tr bgcolor="#f0f0f0"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Upload Picture</font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="picture" type="file" id="picture" size="60">
        </font></td>
     <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Or display from url</TD>
      <TD><input name="url" type="text" id="url" size="60" value=""></TD>
    </TR>
	<tr bgcolor="#f0f0f0"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Description</font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <textarea name="description" cols="60" rows="4" wrap="VIRTUAL" id="description"></textarea>
        </font></td>
    </tr>
    <tr bgcolor="#f0f0f0"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Type</font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <select name="type" id="type">
          <option value="Main" selected>Main</option>
          <option value="Public">Public</option>
          <option value="Private">Private</option>
        </select>
        </font></td>
    </tr>
    <tr bgcolor="#f0f0f0"> 
      <td colspan="2"><div align="center">
          <input name="upload" type="hidden" id="upload" value="1">
          <input type="reset" name="Submit2" value="Reset">
          <input type="submit" name="Submit" value="Upload">
        </div></td>
    </tr>
  </table>
</form><?php 
}else echo "You can't have more that $tm0pics pictures for your current rank.";

$r=q("select * from pictures where member='$auth'");
if (!e($r)) while ($pic=f($r))
{?>
<form action="manage_pictures.php" method="post" enctype="multipart/form-data" name="form1">
  <table width="600" border="0" cellpadding="2" cellspacing="1" align="center">
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><font size="2" face="Arial, Helvetica, sans-serif"><strong>Edit 
        picture details &gt;&gt;</strong><em> </em></font></td>
    </tr>
    <tr> 
      <td height="3" colspan="2" bgcolor="#000000"> </td>
    </tr>
    <tr bgcolor="#f0f0f0"> 
      <td colspan="2"><div align="center"><a target='ntm3k_picture' href='<?php echo piurl($pic[picture]); ?>'><IMG SRC="<?php echo piurl($pic[picture]); ?>" width='200' border=1 alt='Full size!'></A></div></td>
    </tr>
    <tr bgcolor="#f0f0f0"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Description</font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <textarea name="description" cols="60" rows="4" wrap="VIRTUAL" id="description"><?php echo $pic[details]; ?></textarea>
        </font></td>
    </tr>
    <tr bgcolor="#f0f0f0"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Type</font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <select name="type" id="type">
          <option value="Main" <?php if ($pic[type]=="Main") echo "selected";?>>Main</option>
          <option value="Public" <?php if ($pic[type]=="Public") echo "selected";?>>Public</option>
          <option value="Private" <?php if ($pic[type]=="Private") echo "selected";?>>Private</option>
        </select>
        </font></td>
    </tr>
    <tr bgcolor="#f0f0f0"> 
      <td colspan="2"><div align="center">
          <input name="edit" type="hidden" id="edit" value="1">
		  <input name="picid" type="hidden" id="picid" value="<?php echo $pic[id];?>">
          <input type="reset" name="Submit22" value="Reset">
          <input type="submit" name="Submit3" value="Save Changes">
          <input type="button" name="Submit222" value="Delete Picture" onclick="document.location='manage_pictures.php?picid=<?php echo $pic[id];?>&delete=1&picname=<?php echo $pic[picture];?>'">
        </div></td>
    </tr>
  </table>
</form>
<?}else echo "<br><center> No pictures uploaded. </center>";
include("_footer.php");
?>


