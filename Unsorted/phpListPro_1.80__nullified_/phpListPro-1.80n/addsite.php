<?

################################################################################################
#
#  project           	: phpListPro
#  filename          	: addsite.php
#  last modified by  	: Erich Fuchs
#  supplied by          : CyKuH [WTN]
#  nullified by      	: CyKuH [WTN]          
#  purpose           	: Add a Site-entry
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require ("config.php");

include($addheader);



if ($action=="submit") {



  mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");



  $sql = "SELECT site_addr FROM sites WHERE site_addr='$site_address' ";

  $query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

  $result = mysql_fetch_array($query);



  if($result){ $dupe_address = 1; }



  // Check Input

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



    // What it does if the forms are all correct

    $id = time();

    $site_name 		= addslashesnew(strip_tags($site_name));

    $email_address	= addslashesnew(strip_tags($email_address));

    $webmaster_name 	= addslashesnew(strip_tags($webmaster_name));

    $site_description 	= addslashesnew(strip_tags($site_description));

    if (!$cat) {$cat=0;}

    if ($banner_address == "http://") {$banner_address = ""; }



    if ($webmasterapproval) {$approved=0;} else {$approved=1;}

    if ($emailapproval) {$emailapproved=0;} else {$emailapproved=1;}



    $sql = "INSERT INTO sites VALUES ('$id','0','0','$site_name','$site_address','$site_description','$email_address',

	'$webmaster_name','$password','$banner_address','$banner_width','$banner_height','5','$approved','$emailapproved','0','$cat','0')";

    $query = mysql_db_query($database, $sql) or die(geterrdesc($sql));



    // takes out all slashes then sends out emails



    $site_name = stripslashes ($site_name);

    $site_description = stripslashes ($site_description);

    $webmaster_name = stripslashes ($webmaster_name);

    mail_newnotifyuser($email_address,$webmaster_name,$id,$password,$site_name,$site_address,$site_description);

    if($notify_email){

        mail_newnotifyadm($email_address,$webmaster_name,$id,$password,$site_name,$site_address,$site_description);

    }



    echo "<table class=\"standard\"><tr><td>";

    echo "<center><b>$lang[addthanks]</b><hr></center>";

    echo "</td></tr><tr><td>\n";

    echo "$lang[addloginid]<b>$id</b>$lang[addsendmail]<br>";

    echo "$lang[addhint]<p>";

    if ($webmasterapproval) {

	echo "$lang[addapproval]<br>";

    }

    echo "$lang[addplease]<p>";

    help($id);

    echo "</td></tr></table>";

  }



  echo "

  <p>

  <FORM action=\"$list_url/list.php$catlink\" method=\"POST\">

  <INPUT TYPE=\"submit\" name=\"Done_submit\" onclick=\"exit=false\" VALUE=\"$lang[done]\">

  <p>

  ";



  mysql_close();



} else {



    if($auto_entersize) {

	$banner_height=$max_banner_height;

	$banner_width=$max_banner_width;

    }



    echo "

    <table class=\"standard\">

    <tr><td><center><b>$lang[addrules]</b></center></td></tr>

    <tr><td>";

    if (is_file("rules.inc")) {

	include ("rules.inc");

    } else {

	echo $lang[rules];

    }





    if ($show_categories) {

	$catstr.="<select name=\"cat\">";

        $selected[$cat]="SELECTED";

	for($i = 0; $i<count($category); $i++) {

	    $catstr.= "<option value=\"$i\" $selected[$i]>$category[$i]</option>\n";

        }

	$catstr.="</select><br>";

    }



    echo "

    </td></tr></table>

    <br><br>

    <table class=\"standard\">

    <tr><td><center><b>$lang[addyoursite]</b></center></td></tr>

    <tr><td>



    <FORM ACTION=\"addsite.php\" METHOD=\"POST\">

    $catstr

      <div class=\"mainleft\">$lang[yourname]<br></div>

      <INPUT TYPE=\"text\" NAME=\"webmaster_name\" VALUE=\"\" MAXLENGTH=\"30\">

    <br>

      <div class=\"mainleft\">$lang[password]<br></div>

      <INPUT TYPE=\"text\" NAME=\"password\" VALUE=\"\" SIZE=\"15\" MAXLENGTH=\"20\">

    <br>

      <div class=\"mainleft\">$lang[emailaddr]<br></div>

      <INPUT TYPE=\"text\" NAME=\"email_address\" VALUE=\"\" MAXLENGTH=\"50\">

    <br>

    <br>

      <div class=\"mainleft\">$lang[sitetitle]<br></div>

      <INPUT TYPE=\"text\" NAME=\"site_name\" VALUE=\"\" SIZE=\"50\" MAXLENGTH=\"100\">

    <br>

      <div class=\"mainleft\">$lang[siteurl]<br></div>

      <INPUT TYPE=\"text\" NAME=\"site_address\" VALUE=\"http://\" SIZE=\"50\" MAXLENGTH=\"200\">

    <br>

      <div class=\"mainleft\">$lang[bannerurl]<br></div>

      <INPUT TYPE=\"text\" NAME=\"banner_address\" VALUE=\"http://\" SIZE=\"50\" MAXLENGTH=\"200\">

    <br>

      <div class=\"mainleft\">$lang[bannerw]<small>($max_banner_width MAX)</small><br></div>

      <INPUT TYPE=\"text\" NAME=\"banner_width\" VALUE=\"$banner_width\" SIZE=\"4\" MAXLENGTH=\"5\">

    <br>

      <div class=\"mainleft\">$lang[bannerh]<small>($max_banner_height MAX)</small><br></div>

      <INPUT TYPE=\"text\" NAME=\"banner_height\" VALUE=\"$banner_height\" SIZE=\"4\" MAXLENGTH=\"5\">

    <br>

      <div class=\"mainleft\">$lang[sitedesc]<br></div>

      <INPUT TYPE=\"text\" NAME=\"site_description\" VALUE=\"\" SIZE=\"60\" MAXLENGTH=\"750\">

    <br>

    <INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"submit\">

    <input name=submit_form value=\"$lang[submit]\" onclick=\"exit=false\" type=\"submit\">

    </td></tr></table></FORM></center>

    ";



}



include("$addfooter");

?>