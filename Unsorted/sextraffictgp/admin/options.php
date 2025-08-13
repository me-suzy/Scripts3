<?php
include("header.php");

if($access[cansettings]=="1"){
if ($submit){
$sql = "UPDATE st_options SET tablewidth='$tablewidth_o', sitename='$sitename_o', header='$header_o', footer='$footer_o', 	limitlinks='$limitlinks_o', displaydate='$displaydate_o', keywords='$keywords_o', content='$content_o', sitetitle='$sitetitle_o', background='$background_o', text='$text_o', linkcolor='$linkcolor_o', linkcolor2='$linkcolor2_o', archivelimit='$archivelimit_o',  datecolor='$datecolor_o', siteurl='$siteurl_o', adminemail='$adminemail_o', recip='$recip_o', submityn='$submityn_o', submitynreason='$submitynreason_o', orderedby='$orderedby_o', turncatliston='$turncatliston_o', wayorder='$wayorder_o', recipyn='$recipyn_o' WHERE op='1'";

if (mysql_query($sql)) {
  echo("<font size='3' face='arial'><b>Options updated</b></font>");
} else {
  echo("<p>Error updating site options: Message -- : " .
       mysql_error() . "</p>");
}
?>
<meta http-equiv ="Refresh" content = "2 ; URL=options.php">
<?php
exit; }// End Submit

$options = mysql_fetch_array(mysql_query("SELECT * FROM st_options WHERE op='1'"));
$op = $options["op"];
$tablewidth = $options["tablewidth"];
$sitename = $options["sitename"];
$header = $options["header"];
$footer = $options["footer"];
$limitlinks = $options["limitlinks"];
$displaydate = $options["displaydate"];
$keywords = $options["keywords"];
$content = $options["content"];
$sitetitle = $options["sitetitle"];
$background = $options["background"];
$text = $options["text"];
$linkcolor = $options["linkcolor"];
$linkcolor2 = $options["linkcolor2"];
$archivelimit = $options["archivelimit"];
$datecolor = $options["datecolor"];
$siteurl = $options["siteurl"];
$adminemail = $options["adminemail"];
$recip = $options["recip"];
$submityn = $options["submityn"];
$submitynreason   = $options["submitynreason"];
$recipyn = $options["recipyn"];
$turncatliston = $options["turncatliston"];
$orderedby = $options["orderedby"];
$wayorder = $options["wayorder"];

?>

<!--- START OF OPTIONS FORM   --->
<form action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="op" value="1">
  <table width="550" border="0" cellspacing="0" cellpadding="5" bgcolor="<?=$admincolor3?>">
    <tr>
      <td><font face="Arial" size="3" color="#FFFFFF"><b>TGP Information</b></font></td>
    </tr>
  </table>
  <table width="550" border="0" cellspacing="0" cellpadding="5">
    <tr bgcolor="f7f7f7">
      <td width="48%" bgcolor="f7f7f7"><font size="2" face="Arial"><b>Site name:</b></font></td>
      <td width="52%"><input type="text" name="sitename_o" value="<?=$sitename?>" size="35"></td>
    </tr>
	<tr bgcolor="f7f7f7">
      <td width="48%" bgcolor="f7f7f7"><font size="2" face="Arial"><b>Site URL:</b></font></td>
      <td width="52%"><input type="text" name="siteurl_o" value="<?=$siteurl?>" size="35"></td>
    </tr>
    <tr bgcolor="#EFEFEF">
      <td width="48%"><font size="2" face="Arial"><b>Site title:</b></font></td>
      <td width="52%"><input type="text" name="sitetitle_o" value="<?=$sitetitle?>" size="35"></td>
    </tr>
    <tr bgcolor="f7f7f7">
      <td width="48%" bgcolor="f7f7f7"><font face="Arial" size="2"><b>Admin email:</b></font></td>
      <td width="52%"><input type="text" name="adminemail_o" value="<?=$adminemail?>" size="35"></td>
    </tr>
    <tr bgcolor="#EFEFEF">
      <td width="48%"><font size="2" face="Arial"><b>Keywords:</b></font><br>
	  <font  face="Verdana" size="1">(e.g. Place a commer between words)</font></td>
      <td width="52%"><input type="text" name="keywords_o" value="<?=$keywords?>" size="35"></td>
    </tr>
    <tr bgcolor="f7f7f7">
      <td width="48%"><font size="2" face="Arial"><b>Content:</b></font><br>
	  <font  face="Verdana" size="1">(e.g. A short description of your tgp)</font></td>
      <td width="52%"><input type="text" name="content_o" value="<?=$content?>" size="35"></td>
    </tr>
    <tr bgcolor="#EFEFEF">
      <td width="48%"><font size="2" face="Arial"><b>Table width:</b></font><br>
	  <font face="Verdana" size="1">(e.g. 90% OR 100% OR  750)</font></td>
      <td width="52%"><input type="text" name="tablewidth_o" value="<?=$tablewidth?>" size="4"></td>
    </tr>
  </table>
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="5" bgcolor="<?=$admincolor3?>">
    <tr>
      <td><font face="Arial" size="3" color="#FFFFFF"><b>Submit information</b></font></td>
    </tr>
  </table>
  <table width="550" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="48%" bgcolor="f7f7f7"><font size="2" face="Arial"><b>Disable 
        submission:</b></font></td>
      <td width="52%" bgcolor="f7f7f7"> 
        <select name="submityn_o">
          <option value="N" <?php if($submityn=="N"){ echo "selected";}?>>No</option>
          <option value="Y" <?php if($submityn=="Y"){ echo "selected";}?>>Yes</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td width="48%" bgcolor="f7f7f7"><font size="2"><b><font face="Arial">If 
        Yes, reason:</font></b></font></td>
      <td width="52%" bgcolor="f7f7f7"> 
        <textarea name="submitynreason_o" cols="30" rows="3"><?=$submitynreason?></textarea>
      </td>
    </tr>
    <tr> 
      <td width="48%">&nbsp;</td>
      <td width="52%">&nbsp;</td>
    </tr>
    <tr> 
      <td width="48%" bgcolor="f7f7f7"><font size="2" face="Arial"><b>Resipical 
        link required:</b></font></td>
      <td width="52%" bgcolor="f7f7f7"> 
        <select name="recipyn_o">
          <option value="N" <?php if($recipyn=="N"){ echo "selected";}?>>No</option>
          <option value="Y" <?php if($recipyn=="Y"){ echo "selected";}?>>Yes</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td width="48%" bgcolor="f7f7f7"><font size="2"><b><font face="Arial">If 
        Yes, URL:</font></b></font></td>
      <td width="52%" bgcolor="f7f7f7"> 
        <input type="text" name="recip_o" value="<?=$recip?>" size="35">
      </td>
    </tr>
  </table>
<br>
<table width="550" border="0" cellspacing="0" cellpadding="5" bgcolor="<?=$admincolor3?>">
 <tr>
  <td><font face="Arial" size="3" color="#FFFFFF"><b>TGP colors</b></font></td>
 </tr>
</table>
<table width="550" border="0" cellspacing="0" cellpadding="5">
    <tr bgcolor="#EFEFEF"> 
      <td width="48%"><font size="2" face="Arial"><b>Background color:</b></font></td>
      <td width="52%">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="150"><input type="text" name="background_o" value="<?=$background?>"></td>
            <td> 
              <table width="40" border="1" cellspacing="0" cellpadding="0" bgcolor="<?=$background?>" bordercolor="#000000">
                <tr> 
                  <td>&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr bgcolor="f7f7f7"> 
      <td width="48%"><font size="2" face="Arial"><b>Text color:</b></font></td>
      <td width="52%">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="150"><input type="text" name="text_o" value="<?=$text?>"></td>
            <td> 
              <table width="40" border="1" cellspacing="0" cellpadding="0" bgcolor="<?=$text?>" bordercolor="#000000">
                <tr> 
                  <td>&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr bgcolor="#EFEFEF"> 
      <td width="48%"><font size="2" face="Arial"><b>Link color:</b></font></td>
      <td width="52%"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="150"><input type="text" name="linkcolor_o" value="<?=$linkcolor?>"></td>
            <td> 
              <table width="40" border="1" cellspacing="0" cellpadding="0" bgcolor="<?=$linkcolor?>" bordercolor="#000000">
                <tr> 
                  <td>&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr bgcolor="f7f7f7"> 
      <td width="48%"><font size="2"><b><font face="Arial">Link mouse over color:</font></b></font></td>
      <td width="52%"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="150"><input type="text" name="linkcolor2_o" value="<?=$linkcolor2?>"></td>
            <td> 
              <table width="40" border="1" cellspacing="0" cellpadding="0" bgcolor="<?=$linkcolor2?>" bordercolor="#000000">
                <tr> 
                  <td>&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr bgcolor="#EFEFEF"> 
      <td width="48%"><b><font size="2" face="Arial">Date color:</font></b></td>
      <td width="52%"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="150"><input type="text" name="datecolor_o" value="<?=$datecolor?>"></td>
            <td> 
              <table width="40" border="1" cellspacing="0" cellpadding="0" bgcolor="<?=$datecolor?>" bordercolor="#000000">
                <tr> 
                  <td>&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="5" bgcolor="<?=$admincolor3?>">
    <tr>
      <td><font face="Arial" size="3" color="#FFFFFF"><b>Limits</b></font></td>
    </tr>
  </table>
  <table width="550" border="0" cellspacing="0" cellpadding="5">
    <tr bgcolor="f7f7f7"> 
      <td width="48%" bgcolor="f7f7f7"><font size="2" face="Arial"><b>Link number limit:</b></font><br><font face="Verdana" size="1">(numer of links to show up in each category on the index page)</font></td>
      <td width="52%"><input type="text" name="limitlinks_o" value="<?=$limitlinks?>"></td>
    </tr>
    <tr bgcolor="#EFEFEF"> 
      <td width="48%" bgcolor="#EFEFEF"><font face="Arial" size="2"><b>Display date:</b></font></td>
      <td width="52%"><input type="text" name="displaydate_o" value="<?=$displaydate?>"></td>
    </tr>
    <tr bgcolor="f7f7f7"> 
      <td width="48%" bgcolor="f7f7f7"><font face="Arial" size="2"><b>Archive link limit:</b></font></td>
      <td width="52%" bgcolor="f7f7f7"><input type="text" name="archivelimit_o" value="<?=$archivelimit?>"></td>
    </tr>
    <tr bgcolor="#EFEFEF">
      <td width="48%" bgcolor="#EFEFEF"><font size="2" face="Arial"><b>Turn categorys 
        on:</b></font></td>
      <td width="52%" bgcolor="#EFEFEF"><font size="2" face="Arial"><b><input type="radio" name="turncatliston_o" value="Y" <?php if($turncatliston=="Y"){ echo " checked";}?>>Yes<br>
        <input type="radio" name="turncatliston_o" value="N" <?php if($turncatliston=="N"){ echo " checked";}?>> No</b></font></td>
    </tr>
    <tr bgcolor="f7f7f7"> 
      <td width="48%"><font size="2"><b><font face="Arial">Order categories by:</font></b></font></td>
      <td width="52%"> <font size="2" face="Arial"> <b> 
        <input type="radio" name="orderedby_o" value="catname" <?php if($orderedby=="catname"){ echo " checked";}?>>
        Category Name<br>
        <input type="radio" name="orderedby_o" value="catorder" <?php if($orderedby=="catorder"){ echo " checked";}?>>
        Category Order</b></font></td>
    </tr>
    <tr bgcolor="#EFEFEF"> 
      <td width="48%"><font size="2"><b><font face="Arial">Order categories by:</font></b></font></td>
      <td width="52%"><font size="2" face="Arial"><b> 
        <input type="radio" name="wayorder_o" value="ASC" <?php if($wayorder=="ASC"){ echo " checked";}?>>
        ASC<br>
        <input type="radio" name="wayorder_o" value="DESC" <?php if($wayorder=="DESC"){ echo " checked";}?>>
        DESC</b></font></td>
    </tr>
  </table>
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="5" bgcolor="<?=$admincolor3?>">
    <tr>
      <td><font face="Arial" size="3" color="#FFFFFF"><b>Header &amp; footer details</b></font></td>
    </tr>
  </table>
  <table width="550" border="0" cellspacing="0" cellpadding="5">
    <tr bgcolor="f7f7f7">
      <td width="" bgcolor="#EFEFEF"><font size="2" face="Arial"><b>Header</b></font></td>
    </tr>
    <tr bgcolor="f7f7f7">
      <td width="" bgcolor="#EFEFEF"><textarea name="header_o" rows="10" cols="60"><?=$header?></textarea></td>
    </tr>
    <tr bgcolor="f7f7f7">
      <td width="" bgcolor="f7f7f7"><font size="2" face="Arial"><b>Footer</b></font></td>
    </tr>
    <tr bgcolor="f7f7f7">
      <td width="" bgcolor="f7f7f7"><textarea name="footer_o" rows="10" cols="60"><?=$footer?></textarea></td>
    </tr>
  </table>
  <br>
  <br>
<table width="550" border="0" cellspacing="0" cellpadding="5">
<tr>
<td><div align="center"><input type="submit" name="submit" value="Submit"><input type="reset" name="Reset" value="Reset"></div></td>
</tr>
</table>
</form>
<!--- END OF OPTIONS FORM   --->


<?php
}
include("footer.php");
?>
