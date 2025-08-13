<?php
include("stconfig.php");

//Submit disabled
if($submityn=="Y"){

// Template: Submit function disabled
eval("\$submitdisabled = \"".fetchtemplate('submit_disabled')."\";");
echo "$submitdisabled";
exit;} 
else { 

if($submit){

$re = mysql_query("SELECT * from st_banned");
if ($re){
  $therow = mysql_num_rows ($re);
  for ($i = 0; $i < $therow; $i++){
    $banned = mysql_result ($re, $i, "banned_url");
 
if (ereg($banned,$url)) {
// Template: Banned URL message
eval("\$submitbannedurl = \"".fetchtemplate('submit_bannedurl')."\";");
echo "$submitbannedurl";
exit;
}

if (ereg($banned,$email)) {
// Template: Banned email message
eval("\$submitbannedemail = \"".fetchtemplate('submit_bannedemail')."\";");
echo "$submitbannedemail";
exit;
}
}
}


##### START: Looking for reciprical Link ##########

$open = @fopen("$url", "r");
if(!$open){ 
// Template: Can't find URL message
eval("\$cantfindurl = \"".fetchtemplate('submit_cantfindurl')."\";");
echo "$cantfindurl";
die();
}

if($recipyn=="Y"){
if($open){
	$read = fread($open, 17500);
	fclose($open);
	$read = strtolower($read);
	$recipek = "<a href=\"$recip\"";
	$recipcheck= substr_count($read, "$recipek");
	if(!$recipcheck){ $rec = "N";} else { $rec = "Y";}
if($rec=="N"){

// Template: No recipical links in url
eval("\$submitnorecip = \"".fetchtemplate('submit_norecip')."\";");
echo "$submitnorecip";
die();
}
}
}
else {
	$rec = "N";}
##### END: Looking for reciprical Link #############



if($email=="" || $name=="" || $url=="" || $des==""){

// Template: One of the fields are empty
eval("\$submitfieldempty = \"".fetchtemplate('submit_fieldempty')."\";");
echo "$submitfieldempty";
exit;
}

$sql1 = mysql_query("SELECT COUNT(*) FROM st_links WHERE url = '$url'");
if (mysql_result($sql1,0,0)>0) {

// Template: URL already exists
eval("\$submiturlexists = \"".fetchtemplate('submit_urlexists')."\";");
echo "$submiturlexists";
exit;
}

$min = "1";
$max = "10000000";
$total = mt_rand($min, $max);
$confirm = ($total + $total);

$sql2 = @mysql_query("INSERT INTO st_links SET email='$email', name='$name', url='$url', des='$des', ip='$ip', rec='$rec', numpics='$numpics', catid='$catid', date=NOW(), approved='N', cf='$confirm'");
if(!$sql2){
    echo("<p>Database error adding gallery:  Please report this error to to $adminemail <br><br>" .
        mysql_error() . "</p>");
}

// Template: Get email confirm message
eval("\$confirmemail = \"".fetchtemplate('submit_confirmemail')."\";");
// Template: Get email confirm subject
eval("\$confirmemailsubject = \"".fetchtemplate('submit_confirmemailsubject')."\";");
mail ($email,$confirmemailsubject,$confirmemail,"From: \"$sitename\" <$adminemail>");
// Gallery accepted message
eval("\$submitaccepted = \"".fetchtemplate('submit_accepted')."\";");
echo "$submitaccepted";
exit;} // End of submit
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?=$sitetitle?> - Submit galleries</TITLE>
<META NAME="Generator" CONTENT="SexTraffic.net">
<META NAME="Keywords" CONTENT="<?=$keywords?>">
<META NAME="Description" CONTENT="<?=$content?>">
</HEAD>
<body bgcolor="<?=$background?>" text="<?=$text?>" leftmargin="0" bottomMargin="0" rightMargin="0" topmargin="0" marginwidth="0" marginheight="0">
<style type=text/css>
<!--
A:link{text-decoration: none; color: <?=$linkcolor?>;}
A:visited{text-decoration: none; color: <?=$linkcolor?>;}
A:active{text-decoration: none; color: <?=$linkcolor2?>;}
A:hover{text-decoration: none; color: <?=$linkcolor2?>;}
-->
</style>

<?php
// Before submit message
eval("\$submitbefore = \"".fetchtemplate('submit_before')."\";");
echo "$submitbefore";?>

<form action="<?=$PHP_SELF?>" method ="POST">
<input type="hidden" name="ip" value="<?=$REMOTE_ADDR?>">
  <table width="650" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td>
        <table cellspacing="0" cellpadding="5" border="1" width="100%" align="center" bordercolor="#DDDFF7">
          <tr>
            <td align="center" colspan="2" bgcolor="#003399"><font face="Arial" size="3"><b><font color="#FFFFFF">Post Your Gallery</font></b></font></td>
          </tr>
          <tr bgcolor="#E1E1FB">
            <td colspan="2" bgcolor="#E1E1FB"><div align="center"><font size="2" face="Arial">Enter the following information below to submit your gallery for approval.  The script will check to see that the URLs you have entered are valid, so make sure your server is up and running before you submit here.</font></div></td>
          </tr>
       </table>
      </td>
    </tr>
    <tr>
      <td>
        <table cellspacing="0" cellpadding="4" width="100%" align="center" border="0">
          <tr>
            <td width="200" bgcolor="#F2F2F2"><div align="right"><font face="Arial" size="2"><b>Name</b></font></div></td>
            <td bgcolor="#F2F2F2"><input type="text" name="name" size="30"></td>
          </tr>
	      <tr>
            <td width="200" bgcolor="#F9FBFF"><div align="right"><font face="Arial" size="2"><b>E-Mail Address</b></font></div></td>
            <td bgcolor="#F9FBFF"><input type="text" name="email" size="30"></td>
          </tr>
          <tr>
            <td bgcolor="#F2F2F2" width="200"><div align="right"><font face="Arial" size="2"><b>Gallery URL</b></font></div></td>
            <td bgcolor="#F2F2F2"><input type="text" name="url" size="50"></td>
          </tr>
          <tr bgcolor="#F9FBFF"><td width="200"><div align="right"><font face="Arial" size="2"><b>Description</b></font></div></td>
            <td><input type="text" name="des" size="50"></td>
          </tr>
          <tr>
            <td bgcolor="#F2F2F2" width="200"><div align="right"><font face="Arial" size="2"><b>Pics/Movies</b></font></div></td>
            <td bgcolor="#F2F2F2"><input type="text" name="numpics" size="5"></td>
          </tr>
          <tr bgcolor="#F9FBFF">
            <td width="200"><div align="right"><font face="Arial" size="2"><b>Category</b></font></div></td>
            <td><font size="2" face="Arial"><select name="catid">
                <?php
$sql = mysql_query("SELECT * FROM st_categories WHERE visable='Y' ORDER BY catname");
while($result = mysql_fetch_array($sql)){
$catname = $result["catname"];
$cid =  $result["cid"];

echo "<option value='$cid'>$catname</option>";
}
?>
              </select>
        </font></td>
          </tr>
          <tr bgcolor="#E1E1FB">
            <td colspan="2" align="center"><input type="submit" name="submit" value="Post This Gallery"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  </form>
<?php
// After submit
eval("\$submitafter = \"".fetchtemplate('submit_after')."\";");
echo "$submitafter";}
eval("\$cpr = \"".fetchtemplate('index_copyright')."\";"); echo "$cpr";?> 
</body>
</html>