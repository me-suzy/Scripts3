<?

################################################################################################

#

#  project           	: phpListPro

#  filename          	: admin.php

#  last modified by  	: Erich Fuchs

#  e-mail            	: office@smartisoft.com

#  purpose           	: Admin-Panel

#

#################################################################################################



#  Include Configs & Variables

#################################################################################################

$returnpath="../";

require ($returnpath."config.php");



#  Start mySQL

#################################################################################################

mysql_connect($server, $db_user, $db_pass) or die ("Database Connect Error");



if ($action=="resetuser") {

    #$listvar[counter]++;

    $sql = "UPDATE variables SET counter='1'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

    mysql_close();

    header("location: $list_url/admin/admin.php$catlink");

} elseif ($action=="resetlist" && $days_to_reset) {

    if ($setinactive_on_reset) {

	$sql = "UPDATE sites SET inactive='1' WHERE votes=0";

	mysql_db_query($database, $sql) or die(geterrdesc($sql));

    }

    if ($setactive_on_reset) {

	$sql = "UPDATE sites SET inactive='0' WHERE votes>0";

	mysql_db_query($database, $sql) or die(geterrdesc($sql));

    }

    // reset list

    $sql = "UPDATE sites SET votes='0',hits='0',rating='5.0'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

    // set new resettimestamp

    $newtimestamp=time()+($days_to_reset*24*3600);

    $sql = "UPDATE variables SET resettimestamp='$newtimestamp'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

    mysql_close();

    header("location: $list_url/admin/admin.php$catlink");

} elseif ($action=="update") {

    $sql = "UPDATE sites SET site_name='$site_name',site_addr='$site_address',site_desc='$site_description',email='$email_address',

        name='$webmaster_name',password='$password',banner_addr='$banner_address',banner_width='$banner_width',

        banner_height='$banner_height',votes='$votes',hits='$hits',rating='$rating',approved='$approved',

	emailapproved='$emailapproved',tilt_time='$tilt_time',cat='$cat',inactive='$inactive' WHERE id='$id'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

    mysql_close();



    echo"<html><head><title>$list_name - AdminPanel</title><style type=\"text/css\">";

    include("$returnpath$style");

    echo"</style><meta http-equiv=\"refresh\" content=\"2; URL=$list_url/admin/admin.php?offset=$offset$catlink2\"></head><body>";

    echo"<div class=\"maincenter\"><p><b>$list_name - AdminPanel</b></p>\n";

    echo "Site updated successfully!<p><small>Redirect to Admin-Panel, please wait ...</small>";

    echo "</body></html>";

    exit;

} elseif ($action=="delete") {

    $sql = "DELETE FROM sites WHERE id='$id'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));



    mysql_close();

    echo"<html><head><title>$list_name - AdminPanel</title><style type=\"text/css\">";

    include("$returnpath$style");

    echo"</style><meta http-equiv=\"refresh\" content=\"2; URL=$list_url/admin/admin.php?offset=$offset$catlink2\"></head><body>";

    echo"<div class=\"maincenter\"><p><b>$list_name - AdminPanel</b></p>\n";

    echo "Site deleted successfully!<p><small>Redirect to Admin-Panel, please wait ...</small>";

    echo "</body></html>";

    exit;



} elseif ($webmasterapprove) {

    $sql = "UPDATE sites SET approved='1' WHERE id='$webmasterapprove'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));



    mysql_close();

    echo"<html><head><title>$list_name - AdminPanel</title><style type=\"text/css\">";

    include("$returnpath$style");

    echo"</style><meta http-equiv=\"refresh\" content=\"2; URL=$list_url/admin/admin.php?offset=$offset$catlink2\"></head><body>";

    echo"<div class=\"maincenter\"><p><b>$list_name - AdminPanel</b></p>\n";

    echo "Site approved successfully!<p><small>Redirect to Admin-Panel, please wait ...</small>";

    echo "</body></html>";

    exit;



} elseif ($action == "send_newsletter") {



    echo"<html><head><title>$list_name - AdminPanel</title><style type=\"text/css\">";

    include("$returnpath$style");

    echo"</style><meta http-equiv=\"refresh\" content=\"5; URL=$list_url/admin/admin.php$catlink\"></head><body>";

    echo"<div class=\"maincenter\"><p><b>$list_name - AdminPanel</b></p>\n";



    require ($returnpath."library_mail.php");

    $mail = new phpmailer();



    $mail->From     = "$from";

    $mail->FromName = "$bazar_name";

    $mail->WordWrap = 75;

    $mail->UseMSMailHeaders = true;

    $mail->AddCustomHeader("X-Mailer: $list_name $list_ver - Email Interface");

    $mail->Subject = stripslashes($subject);

    $mail->Body    = "$body";

    if ($html) { $mail->IsHTML(true); }

    if ($pic1 != "none" && $pic1) { $mail->AddAttachment("$pic1", "$pic1_name"); }

    if ($pic2 != "none" && $pic2) { $mail->AddAttachment("$pic2", "$pic2_name"); }

    if ($pic3 != "none" && $pic3) { $mail->AddAttachment("$pic3", "$pic3_name"); }



    echo "<b>Send Newsletter</b><br><br></center><small>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;



    if ($to && $from) {                 // send newsletter to entered recipient

        $mail->AddAddress("$to");

        if ($cc) {$mail->AddCC("$cc");}

        if ($bcc) {$mail->AddBCC("$bcc");}

        if(!$mail->Send()) {

            echo "There was an error sending the message";

            exit;

        }

        echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

        echo"      Mail to $to with subject: $subject -> <b>sent</b> !";

        echo "    </td>\n";

        echo "  </tr>\n";

        $mail->ClearAllRecipients();

        $count++;

    }





    if ($allmembers && $from) {			// send newsletter to all members

	$sql  = "select * FROM sites";

	if ($cat) {$sql.=" WHERE cat='$cat'";}

	$sql .= " GROUP BY email";

        $result = mysql_db_query($database, $sql) or die("Database Query Error");

	while ($db = mysql_fetch_array($result)) {



	    $body2 = str_replace("|id|","$db[id]","$body");

	    $body2 = str_replace("|password|","$db[password]",$body2);

	    $body2 = str_replace("|name|","$db[name]",$body2);

	    $body2 = str_replace("|site_addr|","$db[site_addr]",$body2);

	    $body2 = str_replace("|site_name|","$db[site_name]",$body2);



	    $mail->Body    = "$body2";

            $mail->AddAddress("$db[email]", "$db[name]");

	    if(!$mail->Send()) {

                echo "There was an error sending the message";

                exit;

            } else {

		echo "Mail to $to2 with subject: $subject -> <b>sent</b> !<br>";

	    }

	    $count++;

            $mail->ClearAllRecipients();

	}

    }





    echo "<br>$count Newsletters sent.<p></small>\n";

    mysql_close();

    echo "Newsletter sent successfully!<p><small>Redirect to Admin-Panel, please wait ...</small>";

    echo "</body></html>";

    exit;





}





#  HTML Header Start

#################################################################################################

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

                                                      // always modified

header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1

header ("Pragma: no-cache");                          // HTTP/1.0





echo"<html><head><title>$list_name - AdminPanel</title><style type=\"text/css\">";

include("$returnpath$style");

echo"</style></head><body>";

echo"<div class=\"maincenter\"><p><b>$list_name - AdminPanel</b></p>\n";





#  The List-Section

#################################################################################################



function starttable($fromsite,$tosite,$list_name="") {

    global $lang,$show_rating;



    echo "<table class=\"list\" align=\"center\" cellspacing=\"1\">";

    if ($list_name) {

	echo"<tr><td class=\"listtitle\" colspan=5><div class=\"listtitle\">$list_name</div></td></tr>";

    }



    echo"<tr>";

    echo"<td class=\"listhead\"><div class=\"listhead\">$lang[rank]<div></td>";

    echo"<td class=\"listhead\"><div class=\"listhead\">$lang[site] [$fromsite - $tosite]<div></td>";

    echo"<td class=\"listhead\"><div class=\"listhead\">$lang[votes]<div></td>";

    echo"<td class=\"listhead\"><div class=\"listhead\">$lang[hits]<div></td>";

    if ($show_rating) {

	echo"<td class=\"listhead\"><div class=\"listhead\">$lang[rate]<div></td>";

    }

    echo"</tr>\n";

}





if ($site) {	// Edit Site



$sql = "SELECT * FROM sites WHERE id='$site'";

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

$result = mysql_fetch_array($query);



if ($result) {

echo "<table class=\"standard\"><tr><td>";

echo "<center><b>View/Edit site ID: $result[id]</b></center>";

echo "</td></tr><td>\n";



?>



<FORM ACTION="admin.php" METHOD="POST">

  <INPUT TYPE="hidden" NAME="id" VALUE="<?echo $result[id];?>">

  <div class="mainleft">Name:<br></div>

  <INPUT TYPE="text" NAME="webmaster_name" VALUE="<?echo $result[name];?>" MAXLENGTH="30">

<br>

  <div class="mainleft">Password:<br></div>

  <INPUT TYPE="text" NAME="password" VALUE="<?echo $result[password];?>" SIZE="15" MAXLENGTH="20">

<br>

  <div class="mainleft">Email:<br></div>

  <INPUT TYPE="text" NAME="email_address" VALUE="<?echo $result[email];?>" MAXLENGTH="50">

  <input type=button value="Send E-Mail" onclick=javascript:window.location.href="admin.php?action=newsletter&sendto=<?echo $result[email];?>";exit=false;>

<br>

<br>

  <div class="mainleft">Site Title:<br></div>

  <INPUT TYPE="text" NAME="site_name" VALUE="<?echo $result[site_name];?>" SIZE="50" MAXLENGTH="100">

<br>

  <div class="mainleft">Site URL:<br></div>

  <INPUT TYPE="text" NAME="site_address" VALUE="<?echo $result[site_addr];?>" SIZE="50" MAXLENGTH="200">

<br>

  <div class="mainleft">Banner URL:<br></div>

  <INPUT TYPE="text" NAME="banner_address" VALUE="<?echo $result[banner_addr];?>" SIZE="50" MAXLENGTH="200">

<br>

  <div class="mainleft">Banner width: <small>(<? echo $max_banner_width; ?> MAX)</small><br></div>

  <INPUT TYPE="text" NAME="banner_width" VALUE="<?echo $result[banner_width];?>" SIZE="4" MAXLENGTH="5">

<br>

  <div class="mainleft">Banner height: <small>(<? echo $max_banner_height; ?> MAX)</small><br></div>

  <INPUT TYPE="text" NAME="banner_height" VALUE="<?echo $result[banner_height];?>" SIZE="4" MAXLENGTH="5">

<br>

  <div class="mainleft">Site Description:<br></div>

  <INPUT TYPE="text" NAME="site_description" VALUE="<?echo $result[site_desc];?>" SIZE="70" MAXLENGTH="750">

<br>

  <div class="mainleft">Votes:<br></div>

  <INPUT TYPE="text" NAME="votes" VALUE="<?echo $result[votes];?>" SIZE="10" MAXLENGTH="10">

<br>

  <div class="mainleft">Hits:<br></div>

  <INPUT TYPE="text" NAME="hits" VALUE="<?echo $result[hits];?>" SIZE="10" MAXLENGTH="10">

<br>

  <div class="mainleft">Rating:<br></div>

  <INPUT TYPE="text" NAME="rating" VALUE="<?echo $result[rating];?>" SIZE="10" MAXLENGTH="10">

<br><br>

  <div class="mainleft">Approved:<br></div>

  <INPUT TYPE="text" NAME="approved" VALUE="<?echo $result[approved];?>" SIZE="1" MAXLENGTH="1">

<br>

  <div class="mainleft">E-Mail Approved:<br></div>

  <INPUT TYPE="text" NAME="emailapproved" VALUE="<?echo $result[emailapproved];?>" SIZE="1" MAXLENGTH="1">

<br>

  <div class="mainleft">Site Inactive:<br></div>

  <INPUT TYPE="text" NAME="inactive" VALUE="<?echo $result[inactive];?>" SIZE="1" MAXLENGTH="1">

<br>

  <div class="mainleft">Tilt Time:<br></div>

  <INPUT TYPE="text" NAME="tilt_time" VALUE="<?echo $result[tilt_time];?>" SIZE="14" MAXLENGTH="14">

<br><br>

  <div class="mainleft">Category:<br></div>

  <INPUT TYPE="text" NAME="cat" VALUE="<?echo $result[cat];?>" SIZE="6" MAXLENGTH="6">

<br><br>



<INPUT TYPE="hidden" NAME="offset" VALUE="<?echo $offset;?>">

<INPUT TYPE="hidden" NAME="action" VALUE="update">

<input name=submit_form value="Update" type=submit>



<?

echo "</td></tr></table></FORM></center>";

} else {

    echo "<p>ERROR: Site not found !!!</p>";

}



} elseif ($delete) {

$sql = "SELECT * FROM sites WHERE id='$delete'";

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

$result = mysql_fetch_array($query);



if ($result) {

echo "<table class=\"standard\"><tr><td>";

echo "<center><b>Delete site ID: $result[id]</b></center>";

echo "</td></tr><td>\n";



?>



<FORM ACTION="admin.php" METHOD="POST">

  <INPUT TYPE="hidden" NAME="id" VALUE="<?echo $result[id];?>">

  <div class="mainleft">Site Title:<br></div>

  <INPUT TYPE="text" NAME="site_name" VALUE="<?echo $result[site_name];?>" SIZE="50" MAXLENGTH="100" READONLY>

<br>

  <div class="mainleft">Site Description:<br></div>

  <INPUT TYPE="text" NAME="site_description" VALUE="<?echo $result[site_desc];?>" SIZE="70" MAXLENGTH="750" READONLY>

<br>

<INPUT TYPE="hidden" NAME="action" VALUE="delete">

<INPUT TYPE="hidden" NAME="cat" VALUE="<? echo $cat; ?>">

<INPUT TYPE="hidden" NAME="offset" VALUE="<? echo $offset; ?>">

<input name=submit_form value="Delete" type=submit>



<?

echo "</td></tr></table></FORM></center>";

} else {

    echo "<p>ERROR: Site not found !!!</p>";

}



} elseif ($votelogs) {

$sql = "SELECT * FROM votes WHERE id='$votelogs' ORDER BY timestamp DESC";

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));



echo "<table class=\"standard\" cellspacing=\"1\" cellpadding=\"4\"><tr><td colspan=\"4\">";

echo "<center><b>Votelogs from site ID: $votelogs</b></center>";

echo "</td></tr>\n";

echo "<tr><td><b>IP-Address</b></td><td><b>Timestamp</b></td><td><b>Refer-URL</b></td><td><b>Status</b></td></tr>\n";

while ($list = mysql_fetch_array($query)) {

    echo "<tr><td>$list[ip]</td>\n";

    echo "<td>".date("M/j/Y h:i a T",$list[timestamp])."</td>\n";

    echo "<td>$list[refer]</td>\n";

    echo "<td>$list[status]</td></tr>\n";

}

echo "</table></center><br>";

echo "<a href=\"$list_url/admin/admin.php?offset=$offset$catlink2\" target=_self>Back</a><p>";





} elseif ($action=="newsletter") {



    echo"<b>Send Newsletter</b><br><br>\n";

    echo"<form enctype=\"multipart/form-data\" name=\"doit\" action=\"$SELF_PHP?action=send_newsletter\" method=POST>\n";

    echo"<table cellpadding=0 cellspacing=0>\n";

    echo"<tr><td><b>From:</b></td><td><input type=\"text\" name=\"from\" size=30 value=\"$admin_email\"></td></tr>\n";

    echo"<tr><td valign=\"top\"><b>To:</b></td><td><input type=\"text\" name=\"to\" size=30 value=\"$sendto\">\n";

    echo"<div class=\"smallleft\"><input type=\"checkbox\" name=\"allmembers\">All Members";

    if ($cat) {echo" f.Cat:<INPUT TYPE=\"text\" NAME=\"cat\" VALUE=\"$cat\" size=6>";}

    echo"</div></td></tr>\n";

    echo"<tr><td><b>Cc:</b></td><td><input type=\"text\" name=\"cc\" size=30></td></tr>\n";

    echo"<tr><td><b>Bcc:</b></td><td><input type=\"text\" name=\"bcc\" size=30></td></tr>\n";

    echo"<tr><td><b>Attachment:&nbsp;</b></td><td><input type=\"file\" name=\"pic1\" size=30></td></tr>\n";

    echo"<tr><td>&nbsp;</td><td><input type=\"file\" name=\"pic2\" size=30></td></tr>\n";

    echo"<tr><td>&nbsp;</td><td><input type=\"file\" name=\"pic3\" size=30></td></tr>\n";

    echo"<tr><td><b>Subject:</b></td><td><input type=\"text\" name=\"subject\" size=30>\n";

    echo" <input type=\"checkbox\" name=\"html\">HTML</td></tr></table>\n";

    echo"<textarea name=\"body\" rows=13 cols=70 wrap=\"message\">\n";

    echo"</textarea><br><br>\n";

    echo"<div class=\"smallcenter\">Placeholders in text: |id| |password| |name| |site_addr| |site_name|<p></div>";

    echo"<input type=\"submit\" name=\"sendmail\" value=\"Send\"><p>\n";





} else {	// Main Window



echo"<table><tr><td><form enctype=\"text\" action=\"$SELF_PHP\" method=POST>\n";

echo"<div class=\"smallleft\">ID: <input type=\"text\" name=\"site\" size=14><INPUT TYPE=\"hidden\" NAME=\"cat\" VALUE=\"$cat\" size=6>\n";

echo"<input type=\"submit\" name=\"action\" value=\"View/Edit\"></form></div></td>\n";

echo"<td><form enctype=\"text\" action=\"$SELF_PHP\" method=POST>\n";

echo"<div class=\"smallleft\">ID: <input type=\"text\" name=\"delete\" size=14><INPUT TYPE=\"hidden\" NAME=\"cat\" VALUE=\"$cat\" size=6>\n";

echo"<input type=\"submit\" name=\"action\" value=\"Delete\"></form></div></td></tr></table>\n";



echo"<a href=\"$list_url/admin/admin.php?action=resetlist$catlink2\" target=_self>Reset List Counter</a>

 || <a href=\"$list_url/admin/admin.php?action=resetuser$catlink2\" target=_self>Reset User Counter</a>

 || <a href=\"$list_url/admin/admin.php?action=newsletter$catlink2\" target=_self>Newsletter</a>

</p>";





$sql = "SELECT id FROM sites";

if ($cat) {$sql.=" WHERE cat='$cat'";}

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

$listcountall=mysql_num_rows($query);



$timestamp=time();

if (empty($perpage) && $breaks_perpage>0) {$perpage = $breaks_perpage*$break_time;} else {$perpage = $listcountall;}

if (empty($offset)) {$offset = 0;}



$sql = "SELECT * FROM sites WHERE 1=1";

if ($cat) {$sql.=" AND cat='$cat'";}

if ($search) {$sql.=" AND (site_name LIKE '%".$search."%' OR site_desc LIKE '%".$search."%')";}

$psql = $sql." ORDER BY $listsorting DESC, $listsorting2 DESC LIMIT 0, $listcountall";

$sql .= " ORDER BY $listsorting DESC, $listsorting2 DESC LIMIT $offset, $perpage";

$pquery = mysql_db_query($database, $psql) or die(geterrdesc($psql));

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

$pagecount=mysql_num_rows($pquery);

$listcount=mysql_num_rows($query);





#  PageNumbers

#################################################################################################



$pagesstr.="<table class=\"menu\" align=\"center\"><tr><td align=\"left\" width=\"1\">\n";

if ($show_categories) {



    echo"

	<SCRIPT LANGUAGE=\"JavaScript\"><!--

	function changecat(newcat) {

	    exit=false;

	    site = \"admin.php?cat=\"+(newcat);

	    if (newcat!=0) {

		top.location.href=site;

	    } else {

		top.location.href=\"admin.php\";

	    }

	}

	//--></SCRIPT>";



    $pagesstr.="<form action=\"$PHP_SELF\" name=\"cats\" method=\"POST\">\n";

    $pagesstr.="<select name=\"cats\" onchange=\"changecat(this.options[this.selectedIndex].value)\">";

    $selected[$cat]="SELECTED";

    for($i = 0; $i<count($category); $i++) {

    	$pagesstr.= "<option value=\"$i\" $selected[$i]>$category[$i]</option>\n";

    }



    $pagesstr.="</select>";



}

$pagesstr.="</td>\n";



$pagesstr.="<td align=\"left\" height=\"1\"><form action=\"$PHP_SELF\" name=\"searching\" method=\"POST\">\n";

$pagesstr.="&nbsp;<input type=\"text\" name=\"search\" size=\"15\" maxlength=\"20\" value=\"$search\">\n";

#$pagesstr.="<input type=\"hidden\" name=\"cat\" value=\"$cat\">\n";

$pagesstr.="<input type=\"submit\" value=\"Search\" name=\"submit_form\"></form></td>";



$pagesstr.="<td align=\"right\">\n";

$pages = ceil($pagecount / $perpage);

$actpage = ($offset+$perpage)/$perpage;

if ($pages>1) {				// print only when pages > 0

    $pagesstr.="<small>$lang[pages]\n";

    for($i = 0; $i < $pages; $i++) {

    	$noffset = $i * $perpage;

	$actual = $i + 1;

        if ($actual==$actpage) {

 	    $pagesstr.="(<b>$actual</b>) ";

        } else {

 	    $pagesstr.="[<a href=\"admin.php?offset=$noffset$catlink2\" onclick=\"exit=false\">$actual</a>] ";

	}

    }

}







$pagesstr.="</small></td></tr></table>\n";





#  The List-Section

#################################################################################################



echo $pagesstr;



if ($break_afterbanners && $max_banners>0 && $max_banners<$break_time && $offset==0) {

    $tosite=$max_banners;

} else {

    $tosite=$break_time+$offset;

}



starttable($offset+1,$tosite,$list_name);



$break_timecount=$break_time+$offset;

$breakcount=1;

$i=$offset+1;

while (($list = mysql_fetch_array($query))) {

    if(!empty($list[banner_height]) && !empty($list[banner_width]) && !empty($list[banner_addr]) ){

	$show_banner = "<a href=\"$list_url/out.php?site=$list[id]\" target=_blank onmouseover=\"window.status='$list[site_addr]'; return true;\" onmouseout=\"window.status=''; return true;\">\n";

	$show_banner .= "<img src=\"$list[banner_addr]\" border=0 width=\"$list[banner_width]\" height=\"$list[banner_height]\"></a><br>\n";

    } else {

	$show_banner = "";

    }



    if ($show_newicon && $list[id]>($timestamp-24*3600*$show_newicon)) {

	$show_newiconstr = "<img src=\"../images/new.gif\" border=\"0\" vspace=\"2\">";

    } else {

	$show_newiconstr = "";

    }

    if ($list[tilt_time]>$timestamp) {$nr="[$i]";} else {$nr="$i";}

    if ($list[inactive]) {$nr="($nr)";} else {$nr="$nr";}

    echo "  <tr><td class=\"listrowrank\" valign=\"top\">

	    <div class=\"listrowrank\">$nr<br></div>

	    <a href=\"$list_url/admin/admin.php?votelogs=$list[id]&offset=$offset$catlink2\" target=_self

	    onmouseover=\"window.status='Votelogs from site ID: $list[id]'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"../images/logs.gif\" border=\"0\" vspace=\"2\" alt=\"Votelogs from site ID: $list[id]\"></a><br>

	    <a href=\"$list_url/admin/admin.php?site=$list[id]&offset=$offset$catlink2\" target=_self

	    onmouseover=\"window.status='View/Edit site ID: $list[id]'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"../images/edit.gif\" border=\"0\" alt=\"View/Edit site ID: $list[id]\"></a><br>

	    <a href=\"$list_url/admin/admin.php?delete=$list[id]&offset=$offset$catlink2\" target=_self

	    onmouseover=\"window.status='Delete site ID: $list[id]'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"../images/trash.gif\" border=\"0\" alt=\"Delete site ID: $list[id]\"></a><br>";

    if ($list[approved]==0) {

	echo "  <a href=\"$list_url/admin/admin.php?webmasterapprove=$list[id]&offset=$offset$catlink2\" target=_self

	    onmouseover=\"window.status='Approve site ID: $list[id]'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"../images/checked.gif\" border=\"0\" alt=\"Approve site ID: $list[id]\"></a><br>";

    }

    if ($list[emailapproved]==0) {

	echo "<img src=\"../images/email.gif\" border=\"0\" alt=\"E-Mail not approved ID: $list[id]\"><br>";

    }





    echo "$show_newiconstr</div></td>\n";

    echo "<td class=\"listrowsite\">$show_banner\n";

    echo "<a href=\"$list_url/out.php?site=$list[id]\" target=_blank onmouseover=\"window.status='$list[site_addr]'; return true;\" onmouseout=\"window.status=''; return true;\">".stripslashes($list[site_name])."</a><br>\n";

    echo "<div class=\"listrowsite\">".stripslashes($list[site_desc])."</div></td>\n";

    echo "<td class=\"listrowvotes\"><div class=\"listrowvotes\">$list[votes]</div></td>\n";

    echo "<td class=\"listrowhits\"><div class=\"listrowhits\">$list[hits]</div></td>\n";

    if ($show_rating) {

	echo "<td class=\"listrowrate\"><div class=\"listrowrate\">".round($list[rating]*10)/10;



	if($show_ratingbar) {

	    $bar_in_width = $list[rating]*10;

	    $bar_in_height = $ratingbar_height-1;

	    if($bar_in_width >= $ratingbar_high) {

	        $ratingbarin="ratingbarinhigh";

	    } else {

	        $ratingbarin="ratingbarin";

	    }

	    echo "<table align=center border=0 cellspacing=0 cellpadding=1 width=\"$ratingbar_width\" height=\"$bar_height\">\n";

	    echo " <tr>\n";

	    echo "  <td class=\"ratingbarout\">\n";

	    echo "   <table align=left border=0 cellspacing=0 cellpadding=0 width=\"$bar_in_width%\" height=\"$bar_in_height\">\n";

	    echo "    <tr>\n";

	    echo "     <td class=\"$ratingbarin\">\n";

	    echo "        <div class=\"ratingbarspace\">&nbsp;<div>\n";                        // Netscape Patch !!! shit upgrade to IE

	    echo "     </td>\n";

	    echo "    </tr>\n";

	    echo "   </table>\n";

	    echo "  </td>\n";

	    echo " </tr>\n";

	    echo "</table>\n";

	}



	echo "</div></td></tr>\n";

    }



    // Break's



    if($break_afterbanners && $max_banners == $i && $listcount+$offset!=$i) {

	echo "</table>\n";

	echo "<p>";

	if ($max_banners>=$break_time) {

	    $break_timecount+=$break_time;

	    $breakcount++;

	    starttable($max_banners+1,$break_timecount);

	} else {

	    $breakcount++;

    	    starttable($max_banners+1,$break_time);

	}

    } elseif($break_timecount == $i && $listcount+$offset!=$i) {

	echo "</table>\n";

	echo "<p>";

	$break_timecount+=$break_time;

	$breakcount++;

	starttable($i+1,$break_timecount);



    }



    // End Break's



    $i++;



} //end while



echo "</table></div>";



#  The Footer-Section

#################################################################################################

echo $pagesstr;





// Variables Routine

$sql = "SELECT * FROM variables";

$query = mysql_db_query($database, $sql) or die(geterrdesc($sql));

$listvar = mysql_fetch_array($query);



$listvar[counter]++;

$sql = "UPDATE variables SET counter='$listvar[counter]'";

mysql_db_query($database, $sql) or die(geterrdesc($sql));



if ($listvar[resettimestamp]<$timestamp && $days_to_reset) { //time to reset the list ?

    // reset list

    $sql = "UPDATE sites SET votes='0',hits='0',rating='5.0'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

    // set new resettimestamp

    $newtimestamp=$timestamp+($days_to_reset*24*3600);

    $sql = "UPDATE variables SET resettimestamp='$newtimestamp'";

    mysql_db_query($database, $sql) or die(geterrdesc($sql));

}

// End Variables



echo "<div class=\"footer\">\n";

if ($show_useronline) {

    $timeout=time()-300;  // value in seconds

    $result=mysql_db_query($database, "INSERT INTO useronline VALUES ('$timestamp','$REMOTE_ADDR')");

    $result=mysql_db_query($database, "DELETE FROM useronline WHERE timestamp<$timeout");

    $result=mysql_db_query($database, "SELECT DISTINCT ip FROM useronline") or die("Useronline Error");

    $user  =mysql_num_rows($result);

    if ($user==1) {

	$useronline=", $user $lang[useronline]";

    } else {

	$useronline=", $user $lang[usersonline]";

    }

}

if ($show_counter) {

    $usercounter=", $listvar[counter] $lang[userssatisfied]";

}

echo" $listcountall $lang[sitesindb]$useronline$usercounter<br>";

if ($days_to_reset) {

    echo "$lang[inoutresets] $days_to_reset $lang[days],";

    echo "$lang[next]: ".date("M/j/Y h:i a T", $listvar[resettimestamp])."<br><br>\n";

}

echo "PHPListPro Ver. $list_ver &copy;2001 <a href=http://www.smartisoft.com target=_blank>NETonE</a><br>\n";

echo "</div>\n";



}

#  End mySQL

#################################################################################################

mysql_close();



?>

</body>

</html>
