<?php
include ("header.php");

if($access[cangalleries]=="1"){
if($actions==""){
?>
<meta http-equiv ="Refresh" content = "2 ; URL=links.php?actions=links">
<?php
}


#######################################################
## START OF VALIDATING LINKS
#######################################################
if($actions == "notvalidate"){
?>
<form action="<?=$PHP_SELF?>" method="post">
<?php
$editlink = mysql_query("SELECT *, date_format(date,'%D %b<br>%H:%i (%p)') AS formatted FROM st_links WHERE approved='N' and confirm=''");
?>
<table width="100%" bgcolor="<?=$admincolor3?>" border="0" cellspacing="0" cellpadding="6">
  <tr>
    <td width="150"><b><font face="Arial" size="3" color="#FFFFFF">NOT Validate Links</font></b></td>
	<td width=""><div align="right"><font color="white" size="2" face="arial">
	<img src="images/bluearrow.gif" border="0"> R = Recipical Link &nbsp;&nbsp;
	<img src="images/bluearrow.gif" border="0"> D = Delete &nbsp;&nbsp;</font></div></td>
	</tr>
</table>

<table width="100%" bgcolor="<?=$admincolor3?>" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><font face="Verdana" size="2" color="#FFFF00"><b>Note:</b> These galleries has NOT been email confirmed by the submitter.</font></td>
	</tr>
</table>

<table width="100%" border="1" bordercolor="#FFFFFF" cellspacing="0" cellpadding="4">
  <tr>
    <td width="5%"><div align="center"><b><font size="2" face="Arial">ID</font></b></div></td>
	<td width="20%"><div align="center"><b><font size="2" face="Arial">Name/IP</font></b></div></td>
    <td width="10%"><div align="center"><b><font size="2" face="Arial">Date</font></b></div></td>
    <td width="10%"><div align="center"><b><font size="2" face="Arial">Category/Pics</font></b></div></td>
    <td width="45%"><div align="center"><b><font size="2" face="Arial">URL/ Description</font></b></div></td>
    <td width="5%"><div align="center"><b><font size="2" face="Arial">R</font></b></div></td>
    <td width="5%"><div align="center"><b><font size="2" face="Arial">D</font></b></div></td>
  </tr>

<?php
$trbg = "#D1D2E4";

while($re = mysql_fetch_array($editlink)) {
$linkid = $re["linkid"];
$catid = $re["catid"];
$url = $re["url"];
$des = $re["des"];
$name = $re["name"];
$email = $re["email"];
$ip = $re["ip"];
$numpics = $re["numpics"];
$date = $re["formatted"];
$rec = $re["rec"];

if($rec=="N" || $rec==""){ $rec = "<img src='images/recipno.jpg' alt='NO'>";}
if($rec=="Y"){ $rec = "<img src='images/recipyes.jpg' alt='YES'>";}

$sql = mysql_query("SELECT * FROM st_categories WHERE cid='$catid'");
$results = mysql_fetch_array($sql);
$catname = $results["catname"];
?>

<tr bgcolor="<?=$trbg?>">
  <td width="5%"><div align="center"><font size="2" face="Arial"><?=$linkid?></font></div></td>
  <td width="20%"><div align="center"><font size="2" face="Arial"><a href="mailto:<?=$email?>"><b><?=$name?></b></a><br></font><font size="1" face="verdana"><?=$ip?></font></div></td>
  <td width="10%"><div align="center"><font size="1" face="verdana"><?=$date?></font></div></td>
  <td width="10%"><div align="center"><font size="2" face="Arial"><?=$catname?></font><br><font size="2" face="Arial"><?=$numpics?></font></div></td>
  <td width="45%"><div align="center"><font size="2" face="Arial"><a href="<?=$url?>" target="_blank"><?=$url?></a><br> - <a href="links.php?linkid=<?=$linkid?>&actions=editlink"><?=$des?></a> - </font></div></td>
  <td width="5%"><div align="center"><font size="2" face="Arial"><?=$rec?></font></div></td>
  <td width="5%"><div align="center"><input type="checkbox" name="deleteit[<?=$linkid?>]" value="Y"></div></td>
</tr>
<?php 
if($trbg=="#D1D2E4") $trbg = "#EFEFEF"; else $trbg = "#D1D2E4";
}
?>
</table>

<table width="90%" border="0" cellspacing="0" cellpadding="5" align="center">
<tr>
<td width="50%"><div align="right"><input type=reset value="Reset Form"></div></td>
<td width="50%" align="left"><input type="submit" name="vasubmit" value="SUBMIT"></div></td>
</tr>
</table>

</form>
<?php
}



if($actions=="links"){
?>
<table width="100%" bgcolor="<?=$admincolor3?>" border="0" cellspacing="0" cellpadding="6">
<tr>
<td><b><font face="Arial" size="3" color="#FFFFFF">Search <?=$sitename?></font></b></td>
</tr>
</table>

<form action="searchlink.php" method="get">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="250"><b><font size="4" face="Arial">Search:</font></b> <font size="2" face="Arial">Name, url &amp; description</font></td>
<td> 
<input type="text" name="searchtext" size="35" >
<input type="submit" name="submit" value="Search">
</td>
</tr>
</table>
</form>

<form action="searchid.php" method="get">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="250"><font size="4"><font face="Arial"><b>Find:</b></font>
</font> <font size="2" face="Arial">Link ID number</font>
</td>
<td>
<input type="text" name="linkid" size="35" >
<input type="submit" name="submit" value="Search">
</td>
</tr>
</table>
</form>

<?php
}


#######################################################
## START OF SUBMITTING VALIDATING LINKS
#######################################################
if ($vasubmit){

// Approve submitted link
while(@list($linkid)=each($checkid)) {
$ret = mysql_fetch_array(mysql_query("SELECT * FROM st_links WHERE linkid=$linkid"));

$linkid = $ret["linkid"];
$catid = $ret["catid"];
$url = $ret["url"];
$des = $ret["des"];
$name = $ret["name"];
$email = $ret["email"];
$ip = $ret["ip"];
$numpics = $ret["numpics"];
$date = $ret["date"];
$rec = $ret["rec"];

$ret2 = mysql_fetch_array(mysql_query("SELECT catname FROM st_categories WHERE cid=$catid"));
$catname = $ret2["catname"];

$sql = mysql_query("UPDATE st_links SET approved='Y', date=NOW() WHERE linkid=$linkid");

$message = "Your submission has been approved at $sitename

Url: $url
Description: $des
Category: $catname
Number of pics: $numpics

Name: $name
Email: $email

Regards,
$sitename
$siteurl
";

mail($email,"Your submission has been approved",
     $message, "From:$sitetitle <$adminemail>");
}
echo "<font size='3' face='arial'>Emails approvals sent!</font><br><br>";


// Delete submitted link
while(@list($linkid)=each($deleteit)) {
$sql3 = mysql_query("DELETE FROM st_links WHERE linkid=$linkid");
    if (!$sql3) {	
  echo("<p><br>".
       "Error: " . mysql_error() . "</p>");
  exit();
}
echo "<font size='3' face='arial'>Deleted submissions!</font>";
}



?>
<meta http-equiv ="Refresh" content = "5 ; URL=links.php?actions=validate">
<?php
exit;}


#######################################################
## START OF VALIDATING LINKS
#######################################################
if($actions == "validate"){
?>
<form action="<?=$PHP_SELF?>" method="post">
<?php
$editlink = mysql_query("SELECT *, date_format(date,'%D %b<br>%H:%i (%p)') AS formatted FROM st_links WHERE approved='N' and confirm='Y'");
?>
<table width="100%" bgcolor="<?=$admincolor3?>" border="0" cellspacing="0" cellpadding="6">
  <tr>
    <td width="150"><b><font face="Arial" size="3" color="#FFFFFF">Validate Links</font></b></td>
	<td width=""><div align="right"><font color="white" size="2" face="arial">
	<img src="images/bluearrow.gif" border="0"> R = Recipical Link &nbsp;&nbsp;
	<img src="images/bluearrow.gif" border="0"> D = Delete &nbsp;&nbsp;
	<img src="images/bluearrow.gif" border="0"> A = Approve &nbsp;&nbsp;</font></div></td>
	</tr>
</table>

<table width="100%" border="1" bordercolor="#FFFFFF" cellspacing="0" cellpadding="4">
  <tr>
    <td width="5%"><div align="center"><b><font size="2" face="Arial">ID</font></b></div></td>
	<td width="15%"><div align="center"><b><font size="2" face="Arial">Name/IP</font></b></div></td>
    <td width="10%"><div align="center"><b><font size="2" face="Arial">Date</font></b></div></td>
    <td width="10%"><div align="center"><b><font size="2" face="Arial">Category/Pics</font></b></div></td>
    <td width="45%"><div align="center"><b><font size="2" face="Arial">URL/ Description</font></b></div></td>
    <td width="5%"><div align="center"><b><font size="2" face="Arial">R</font></b></div></td>
    <td width="5%"><div align="center"><b><font size="2" face="Arial">D</font></b></div></td>
    <td width="5%"><div align="center"><b><font size="2" face="Arial">A</font></b></div></td>
  </tr>

<?php
$trbg = "#D1D2E4";

while($re = mysql_fetch_array($editlink)) {
$linkid = $re["linkid"];
$catid = $re["catid"];
$url = $re["url"];
$des = $re["des"];
$name = $re["name"];
$email = $re["email"];
$ip = $re["ip"];
$numpics = $re["numpics"];
$date = $re["formatted"];
$rec = $re["rec"];

if($rec=="N" || $rec==""){ $rec = "<img src='images/recipno.jpg' alt='NO'>";}
if($rec=="Y"){ $rec = "<img src='images/recipyes.jpg' alt='YES'>";}

$sql = mysql_query("SELECT * FROM st_categories WHERE cid='$catid'");
$results = mysql_fetch_array($sql);
$catname = $results["catname"];
?>

<tr bgcolor="<?=$trbg?>">
  <td width="5%"><div align="center"><font size="2" face="Arial"><?=$linkid?></font></div></td>
  <td width="15%"><div align="center"><font size="2" face="Arial"><a href="mailto:<?=$email?>"><b><?=$name?></b></a><br></font><font size="1" face="verdana"><?=$ip?></font></div></td>
  <td width="10%"><div align="center"><font size="1" face="verdana"><?=$date?></font></div></td>
  <td width="10%"><div align="center"><font size="2" face="Arial"><?=$catname?></font><br><font size="2" face="Arial"><?=$numpics?></font></div></td>
  <td width="45%"><div align="center"><font size="2" face="Arial"><a href="<?=$url?>" target="_blank"><?=$url?></a><br> - <a href="links.php?linkid=<?=$linkid?>&actions=editlink"><?=$des?></a> - </font></div></td>
  <td width="5%"><div align="center"><font size="2" face="Arial"><?=$rec?></font></div></td>
  <td width="5%"><div align="center"><input type="checkbox" name="deleteit[<?=$linkid?>]" value="Y"></div></td>
  <td width="5%"><div align="center"><input type="checkbox" name="checkid[<?=$linkid?>]" value="Y"></div></td>
</tr>
<?php 
if($trbg=="#D1D2E4") $trbg = "#EFEFEF"; else $trbg = "#D1D2E4";
}
?>
</table>

<table width="90%" border="0" cellspacing="0" cellpadding="5" align="center">
<tr>
<td width="50%"><div align="right"><input type=reset value="Reset Form"></div></td>
<td width="50%" align="left"><input type="submit" name="vasubmit" value="SUBMIT"></div></td>
</tr>
</table>

</form>
<?php
}


#######################################################
## START OF SUBMITTED ADDING LINK
#######################################################

if($addlinksubmit){

$sql = mysql_query("SELECT COUNT(*) FROM st_links WHERE url = '$url'");
    if (!$sql) {	
  echo("<p>A database error occurred in processing your submission. If this error persists, please contact <a href=mailto:$adminemail><b>$adminemail</b></a> with this error.<br>".
       "Error: " . mysql_error() . "</p>");
  exit();
}

if (mysql_result($sql,0,0)>0) {
?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="15" align="center">
<tr>
<td>
<center>
<b><font size="4" face="Arial">There is an error....</font><font size="3" face="Arial"><br>
</font></b><font size="3" face="Arial">A link already exists with that <b>'url'.</b> 
Please go <a href="javascript:history.back(-1)"><b>back</b></a> and try another.</font>
</center>
</td>
</tr>
</table>
<?php
exit;
}
$sql = mysql_query("INSERT INTO st_links SET date=NOW(), confirm='Y', numpics='$numpics', approved='Y', catid='$catid', url='$url', des='$des', name='$name', email='$email', clicks='1'");
if ($sql){
echo("<font size='2' face='arial'><b>Link added</b></font>");
} else {
echo("Error: " .
mysql_error() . "");
}
?>
<meta http-equiv ="Refresh" content = "0 ; URL=links.php">
<?php
}


#######################################################
## START OF ADDING LINK
#######################################################

if($actions == "addlink"){?>
<FORM ACTION="<?=$PHP_SELF?>" METHOD=POST>
<table width="550" border="0" cellspacing="0" cellpadding="7">
<tr>
<td bgcolor="<?=$admincolor3?>"><font face="Arial" size="3"><b><font face="Arial" color="#FFFFFF">Add a gallery</font></b></font></td>
</tr>
</table>
  <table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr> 
      <td width="120" height="7"><div align="right"><font face="Arial" size="2"><b>URL:</b></font></div></td>
      <td valign="top" height="7"><input type="text" value="http://" name="url" size="45"></td>
    </tr>
    <tr> 
      <td valign="top" width="120"><div align="right"><font face="Arial" size="2"><b>Description:</b></font></div></td>
      <td valign="top"><input type="text" name="des" size="45"></td>
    </tr>
    <tr> 
      <td><div align="right" width="120"><font face="Arial" size="2"><b>Category:</b></font></div></td>
      <td valign="top"><select name="catid" size="1">
<?php
$s = mysql_query("SELECT * FROM st_categories WHERE visable='Y' ORDER BY catname ASC");
while($r= mysql_fetch_array($s)){
$catname = $r["catname"];
$cid = $r["cid"];
?>
<option value="<?=$cid?>"><?=$catname?></option>
<?php } ?>
</select></td>
    </tr>
    <tr>
      <td><div align="right"><b><font size="2" face="Arial">Pics:</font></b></div></td>
      <td valign="top"><input type="text" name="numpics" size="5"></td>
    </tr>
  </table>  
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr>
      <td bgcolor="<?=$admincolor3?>" valign="top"><b><font face="Arial" size="3" color="#FFFFFF">Contact information</font></b></td>
    </tr>
  </table>
  <table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr>
      <td width="120"><div align="right"><b><font face="Arial" size="2">Your name:</font></b></div></td>
      <td valign="top"><input type="text" name="name" size="45"></td>
    </tr>
    <tr>
      <td width="120"><div align="right"><b><font face="Arial" size="2">Your email: </font></b></div></td>
      <td valign="top"><input type="text" name="email" size="45"></td>
    </tr>
  </table>
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr>
      <td valign="top" width="120" bgcolor="<?=$admincolor3?>">&nbsp;</td>
      <td valign="top" colspan="4" bgcolor="<?=$admincolor3?>">
        <input type="submit" name="addlinksubmit" value="SUBMIT">
      </td>
    </tr>
  </table>
</form>
<?php
}



#######################################################
## START TOP 20 HIT LINKS
#######################################################

if($actions == "top20"){

$sql = mysql_query("SELECT * FROM st_links ORDER BY clicks DESC LIMIT 20");?>

<table width="100%" bgcolor="<?=$admincolor3?>" border="0" cellspacing="0" cellpadding="6">
  <tr>
    <td width="60"><div align="center"><font color="#FFFFFF"><b><font face="Arial" size="3">Clicks</font></b></font></div></td>
    <td><b><font face="Arial" size="3" color="#FFFFFF">Top 20 clicked galleries</font></b></td>
  </tr>
</table>

<?php
echo "<table width='100%' border='0' cellspacing='0' cellpadding='4'><tr>";
while ($results = mysql_fetch_array($sql)) {
$des = $results["des"];
$url = $results["url"];
$clicks = $results["clicks"];

echo "<td width='60'><div align='center'><font size='2' face='arial'>$clicks</font></div></td>
<td><font size='3' face='arial'><b><a href='$url'>$des</a></font></b><font color='#666666' size='2'> - $url</font></td></tr>";
}
echo "</table>";
}


#######################################################
## START SUBMIT EDIT LINK
#######################################################
if ($submiteditlink){

  $sql = "UPDATE st_links SET catid='$catid', url='$url', des='$des', numpics='$numpics', name='$name', email='$email', approved='$approved' WHERE linkid=$linkid";
  if (mysql_query($sql)) {
    echo(" <font size='4' face='arial'><b>Thank you! <br><br> Link updated!</b></font>");
  } else {
    echo("<p>Problem : " .
         mysql_error() . "</p>");
  }
?>
<meta http-equiv ="Refresh" content = "0 ; URL=links.php?actions=links">
<?php
}


#######################################################
## START EDIT LINK
#######################################################

if($actions=="editlink"){
$sql2 = mysql_query("SELECT * FROM st_links WHERE linkid='$linkid'");
$re = mysql_fetch_array($sql2);

$linkid = $re["linkid"];
$catid = $re["catid"];
$url = $re["url"];
$des = $re["des"];
$name = $re["name"];
$email = $re["email"];
$clicks = $re["clicks"];
$date = $re["date"];
$approved = $re["approved"];
$numpics = $re["numpics"];

if($re=="0"){?>

<table width="550" border="0" cellspacing="0" cellpadding="3">
 <tr> 
  <td><font size="2" face="Arial"><b>Sorry - no link in your database under this number was found.</b><br><br>Please go <a href="javascript:history.back(-1)"><b>back</b></a> and choose a differrent criteria and try again</font></td>
 </tr>
</table>
<?php include("footer.php"); exit;
}

?>
<FORM ACTION="<?php echo($PHP_SELF); ?>" METHOD=POST>
<input type="hidden" name="linkid" value="<?=$linkid?>">
<table width="550" border="0" cellspacing="0" cellpadding="7">
<tr>
<td bgcolor="<?=$admincolor3?>"><font face="Arial" size="3"><b><font face="Arial" color="#FFFFFF">Edit a gallery </font></b></font></td>
</tr>
</table>

  <table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr> 
      <td width="120" height="7"><div align="right"><font face="Arial" size="2"><b>URL:</b></font></div></td>
      <td valign="top" height="7"><input type="text" name="url" size="40" value="<?=$url?>">&nbsp;&nbsp;<a href="actions.php?linkid=<?=$linkid?>&action=deletelink"><img src="images/icon-del.gif" alt="Delete this link" border="0"></a></td>
    </tr>
    <tr> 
      <td valign="top" width="120">
        <div align="right"><font face="Arial" size="2"><b>Description:</b></font></div>
      </td>
      <td valign="top"><input type="text" name="des" size="40" value="<?=$des?>"></td>
    </tr>
    <tr> 
      <td><div align="right" width="120"><font face="Arial" size="2"><b>Category: </b></font></div></td>
      <td valign="top">
        <select name="catid" size="1">
          <?php
$s = mysql_query("select * from st_categories WHERE visable='Y' order by catname ASC");
while($r = mysql_fetch_array($s)){
$catname = $r["catname"];
$cid = $r["cid"];
?>
          <option value="<?=$cid?>" <?php if($cid==$catid){ echo "selected";}?>>
          <?=$catname?>
          </option>
          <?php } ?>
        </select>
      </td>
    </tr>
    <tr>
      <td><div align="right"><b><font size="2" face="Arial">Pics:</font></b></div></td>
      <td valign="top"><input type="text" name="numpics" size="5" value="<?=$numpics?>"></td>
    </tr>
  </table>  
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr>
      <td bgcolor="<?=$admincolor3?>" valign="top"><b><font face="Arial" size="3" color="#FFFFFF">Contact information</font></b></td>
    </tr>
  </table>
  <table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr>
      <td width="120"><div align="right"><b><font face="Arial" size="2"> Name:</font></b></div>
      </td>
      <td valign="top"><b><font face="Arial" size="2"><input type="text" name="name" size="45"  value="<?=$name?>"></font></b></td>
    </tr>
    <tr>
      <td width="120"><div align="right"><b><font face="Arial" size="2">Email: </font></b></div>
      </td>
      <td valign="top"><b><font face="Arial" size="2"><input type="text" name="email" size="45"   value="<?=$email?>"></font></b></td>
    </tr>
  </table>
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr>
      <td bgcolor="<?=$admincolor3?>" valign="top"><b><font face="Arial" size="3" color="#FFFFFF">Approval</font></b></td>
    </tr>
  </table>

<table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr>
      <td width="120"><div align="right"><b><font face="Arial" size="2">Gallery approved:</font></b></div></td>
      <td valign="top">
	    <input type="radio" name="approved" <?php if($approved=="Y"){ echo "checked";}?> value="Y"><font face="Arial" size="2"><b> Yes</b><br>
        <input type="radio" name="approved" <?php if($approved=="N"){ echo "checked";}?> value="N"><b> No</b></font></td>
    </tr>
  </table>
  <br>
 <table width="550" border="0" cellspacing="0" cellpadding="7">
  <tr>
   <td valign="top" width="120" bgcolor="<?=$admincolor3?>">&nbsp;</td>
   <td valign="top" colspan="4" bgcolor="<?=$admincolor3?>">
   <input type="submit" name="submiteditlink" value="UPDATE LINK">
  </td>
 </tr>
</table>
</form>
<?php
}
}

include("footer.php");
?>