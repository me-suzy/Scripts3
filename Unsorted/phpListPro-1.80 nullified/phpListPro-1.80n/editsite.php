<?

################################################################################################
#
#  project           	: phpListPro
#  filename          	: editsite.php
#  last modified by  	: Erich Fuchs
#  supplied by          : CyKuH [WTN]
#  nullified by      	: CyKuH [WTN]          
#  purpose           	: Edit a Site-entry
#
#################################################################################################

#  Include Configs & Variables

#################################################################################################

require("config.php");

include($editheader);

mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");



if ($action=="getpwd") {

echo "

<table class=\"standard\">

<tr><td><center><b>$lang[getlost2]</center></b></td></tr>

<tr><td>

<form action=\"editsite.php\" method=\"post\">

<div class=\"mainleft\">$lang[emailaddr]</div>

<input type=\"text\" name=\"email_address\" size=\"20\"><p>

<input type=\"hidden\" name=\"action\" value=\"getpwdnow\">

<input type=\"hidden\" name=\"cat\" value=\"$cat\">

<input name=\"Send_submit\" value=\"$lang[getid]\" onclick=\"exit=false\" type=\"submit\">

</td></tr></table></FORM>

";



} elseif ($action=="getpwdnow") {



    $sql = "SELECT * FROM sites WHERE email='$email_address' ";

    $query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

    $result = mysql_fetch_array($query);



    if ($result) {

	mail_getidpwd($result);

        echo "$lang[getidok]";

    } else {

	echo "$lang[getidnok] ($email_address)";

    }



} elseif ($action=="checkpwd") {



    $sql = "SELECT * FROM sites WHERE id='$id' AND password='$password'";

    $query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

    $result = mysql_fetch_array($query);



    if ($result) {



    if ($show_categories) {

        $catstr.="<select name=\"cat\">";

        $selected[$result[cat]]="SELECTED";

        for($i = 0; $i<count($category); $i++) {

            $catstr.= "<option value=\"$i\" $selected[$i]>$category[$i]</option>\n";

	}

        $catstr.="</select><br>";

    } else {

	$catstr="<input type=\"hidden\" name=\"cat\" value=\"$result[cat]\">";

    }



    echo "

    <table class=\"standard\">

    <tr><td><center><b>$lang[edityoursite]</b></center></td></tr>

    <tr><td>

    <FORM ACTION=\"editsite.php\" METHOD=\"POST\">

    $catstr

      <div class=\"mainleft\">$lang[yourname]<br></div>

      <INPUT TYPE=\"text\" NAME=\"webmaster_name\" VALUE=\"".stripslashes($result[name])."\" MAXLENGTH=\"30\">

    <br>

      <div class=\"mainleft\">$lang[password]<br></div>

      <INPUT TYPE=\"text\" NAME=\"password\" VALUE=\"$result[password]\" SIZE=\"15\" MAXLENGTH=\"20\">

    <br>

      <div class=\"mainleft\">$lang[emailaddr]<br></div>

      <INPUT TYPE=\"text\" NAME=\"email_address\" VALUE=\"$result[email]\" MAXLENGTH=\"50\">

    <br>

    <br>

      <div class=\"mainleft\">$lang[sitetitle]<br></div>

      <INPUT TYPE=\"text\" NAME=\"site_name\" VALUE=\"".stripslashes($result[site_name])."\" SIZE=\"50\" MAXLENGTH=\"100\">

    <br>

      <div class=\"mainleft\">$lang[siteurl]<br></div>

      <INPUT TYPE=\"text\" NAME=\"site_address\" VALUE=\"$result[site_addr]\" SIZE=\"50\" MAXLENGTH=\"200\">

    <br>

      <div class=\"mainleft\">$lang[bannerurl]<br></div>

      <INPUT TYPE=\"text\" NAME=\"banner_address\" VALUE=\"$result[banner_addr]\" SIZE=\"50\" MAXLENGTH=\"200\">

    <br>

      <div class=\"mainleft\">$lang[bannerw]<small>($max_banner_width MAX)</small><br></div>

      <INPUT TYPE=\"text\" NAME=\"banner_width\" VALUE=\"$result[banner_width]\" SIZE=\"4\" MAXLENGTH=\"5\">

    <br>

      <div class=\"mainleft\">$lang[bannerh]<small>($max_banner_height MAX)</small><br></div>

      <INPUT TYPE=\"text\" NAME=\"banner_height\" VALUE=\"$result[banner_height]\" SIZE=\"4\" MAXLENGTH=\"5\">

    <br>

      <div class=\"mainleft\">$lang[sitedesc]<br></div>

      <INPUT TYPE=\"text\" NAME=\"site_description\" VALUE=\"".stripslashes($result[site_desc])."\" SIZE=\"60\" MAXLENGTH=\"750\">

    <br>

    <INPUT TYPE=\"hidden\" NAME=\"id\" VALUE=\"$result[id]\">

    <INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"submit\">

    <input name=submit_form value=\"$lang[submit]\" onclick=\"exit=false\" type=\"submit\">

    </td></tr></table></FORM></center>

    ";



} else {

    echo "$lang[editidnok]";

}



} elseif ($action=="submit") {



  if(empty($site_name) || (empty($site_address) || $site_address == "http://")|| empty($password) || empty($email_address) ||

	empty($webmaster_name) || empty($site_description) || check_bad_words($site_description) || check_bad_words($site_name) || $result ||

	($banner_width > $max_banner_width && !empty($banner_address) && $banner_address != "http://") ||

	($banner_height > $max_banner_height && !empty($banner_address) && $banner_address != "http://") ||

	(getbannersize($banner_address) > $max_banner_filesize && $max_banner_filesize && !empty($banner_address) && $banner_address != "http://") ||

	(!getbannersize($banner_address) && !empty($banner_address) && $banner_address != "http://")

	) {





    echo "<table class=\"standard\"><tr><td>";

    echo "<center><b>$lang[error]<hr></b></center>";

    echo "</td></tr><td>\n";



    if($result){ echo "$lang[error_duplicate]<br>"; }

    if(empty($site_name)){ echo "$lang[error_emptysiten]<br>"; }

    if(empty($site_address) || $site_address == "http://"){ echo "$lang[error_emptysitea]<br>"; }

    if(empty($password)){ echo "$lang[error_emptypass]<br>"; }

    if(empty($email_address)){ echo "$lang[error_emptyemail]<br>"; }

    if(empty($webmaster_name)){ echo "$lang[error_emptyname]<br>"; }

    if(empty($site_description)){ echo "$lang[error_emptydesc]<br>"; }

    if($banner_width > $max_banner_width && !empty($banner_address) && $banner_address != "http://"){ echo "$lang[error_bannerw]<br>"; }

    if($banner_height > $max_banner_height && !empty($banner_address) && $banner_address != "http://"){ echo "$lang[error_bannerh]<br>"; }

    if(getbannersize($banner_address) > $max_banner_filesize && $max_banner_filesize && !empty($banner_address) && $banner_address != "http://") { echo "$lang[error_banners] ($max_banner_filesize Byte)<br>"; }

    if(!getbannersize($banner_address) && !empty($banner_address) && $banner_address != "http://") { echo "$lang[error_bannernok]<br>"; }

    if(check_bad_words($site_description)){ echo "$lang[error_baddesc]<br>"; }

    if(check_bad_words($site_name)){ echo "$lang[error_badname]<br>"; }



    echo "</td></tr></table></center>";



  } else {



    $site_name              = addslashesnew(strip_tags($site_name));

    $email_address          = addslashesnew(strip_tags($email_address));

    $webmaster_name         = addslashesnew(strip_tags($webmaster_name));

    $site_description       = addslashesnew(strip_tags($site_description));

    if($banner_address == "http://") {$banner_address = ""; }



    $sql = "UPDATE sites SET site_name='$site_name',site_addr='$site_address',site_desc='$site_description',email='$email_address',

        name='$webmaster_name',password='$password',banner_addr='$banner_address',banner_width='$banner_width',

	banner_height='$banner_height', cat='$cat' WHERE id='$id'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));



    $site_name = stripslashes ($site_name);

    $site_description = stripslashes ($site_description);

    $webmaster_name = stripslashes ($webmaster_name);

    if($notify_email){

        mail_editnotifyadm($email_address,$webmaster_name,$id,$password,$site_name,$site_address,$site_description);

    }

    echo "$lang[editsuccess]";

  }



} else {



echo "

<table class=\"standard\">

<tr><td><center><b>$lang[enterdata]</center></b></td></tr>

<tr><td>

<form action=\"editsite.php\" method=\"POST\">

<div class=\"mainleft\">$lang[siteid]<br></div>

<input type=\"text\" NAME=\"id\" SIZE=\"20\" MAXLENGTH=\"20\"><br>

<div class=\"mainleft\">$lang[password]<br></div>

<input type=\"password\" NAME=\"password\" SIZE=\"20\" MAXLENGTH=\"20\"><br>

<input type=\"hidden\" name=\"action\" value=\"checkpwd\">

<input type=\"hidden\" name=\"cat\" value=\"$cat\">

<input type=\"hidden\" name=\"site\" value=\"\"><p>

<input name=\"enter\" value=\"$lang[editsite]\" onclick=\"exit=false\" type=\"submit\">

</form>

<form action=\"editsite.php\" method=\"POST\">

<input type=\"hidden\" name=\"action\" value=\"getpwd\">

<input type=\"hidden\" name=\"cat\" value=\"$cat\">

<input type=\"hidden\" name=\"site\" value=\"\">

<input name=\"enter\" value=\"$lang[getlost]\" onclick=\"exit=false\" type=\"submit\">

</td></tr></table></FORM></center>

";



}



mysql_close();



echo "

<p><center>

<input type=submit value=$lang[back] onclick=javascript:history.back(1)>&nbsp;

<input type=submit value=$lang[done] onclick=javascript:window.location.href=\"$list_url/list.php$catlink\";exit=false;></center><p>

";



include($editfooter);



?>
