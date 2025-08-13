<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: ./admin/index.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Administration Panel
#  version           	: 1.58
#  last modified     	: 06/05/2002
#
#################################################################################################



header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

                                                      // always modified

header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1

header ("Pragma: no-cache");                          // HTTP/1.0



#  Include Configs & Variables

#################################################################################################

require ("../config.php");

if (is_file("../sales_config.php")) {include ("../sales_config.php");}

if (is_file("../picturelib_config.php")) {include ("../picturelib_config.php");}

$menusep=" || ";

$redirect="";



function dateToStr($date) {             //input Format 2000-11-24, output

    global $dateformat;



    $temp=explode(" ",$date);

    list($y,$m,$d)=explode("-",$temp[0]);

    list($hh,$mm,$ss)=explode(":",$temp[1]);

    if ($y=="00" && $m=="00") {

	return "NULL";

    } else {

	return date($dateformat,mktime($hh,$mm,$ss,$m,$d,$y));

    }

}





function suppr($file) { 		// Delete a file on all Operating-System's

    $delete = @unlink($file);

    if (@file_exists($file)) {

	$filesys = eregi_replace("/","\\",$file);

	$delete = @system("del $filesys");

	if (@file_exists($file)) {

	    $delete = @chmod ($file, 0775);

	    $delete = @unlink($file);

	    $delete = @system("del $filesys");

	}

    }

}



function selectyesno($name,$value) {

    if ($value=="yes") {

	$retval="<select name=\"$name\"><option value=\"yes\" SELECTED>Yes</option><option value=\"no\">No</option></select>\n";

    }else {

	$retval="<select name=\"$name\"><option value=\"yes\">Yes</option><option value=\"no\" SELECTED>No</option></select>\n";

    }

return $retval;

}



function selectyesnominmax($name,$value) {

    if ($value=="yes") {

	$retval="<select name=\"$name\"><option value=\"yes\" SELECTED>Yes</option><option value=\"minmax\">MinMax</option><option value=\"no\">No</option></select>\n";

    } elseif ($value=="minmax") {

	$retval="<select name=\"$name\"><option value=\"yes\">Yes</option><option value=\"minmax\" SELECTED>MinMax</option><option value=\"no\">No</option></select>\n";

    }else {

	$retval="<select name=\"$name\"><option value=\"yes\">Yes</option><option value=\"minmax\">MinMax</option><option value=\"no\" SELECTED>No</option></select>\n";

    }

return $retval;

}



function selecttype($name,$value) {

    if ($value=="text") $seltext="SELECTED";

    if ($value=="select") $selselect="SELECTED";

    if ($value=="url") $selurl="SELECTED";

    if ($value=="checkbox") $selcheck="SELECTED";



    $retval="<select name=\"$name\">

	<option value=\"text\" $seltext>Text</option>

	<option value=\"url\" $selurl>URL</option>

	<option value=\"select\" $selselect>Select</option>

	<option value=\"checkbox\" $selcheck>Checkbox</option>

	</select>\n";



return $retval;



}



#  Header

#################################################################################################

echo "<html>\n";

echo " <head>\n";

echo "  <title>phpBazar-AdminPanel</title>\n";

echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../$STYLE\">\n";

echo "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n";

echo "  <meta name=\"robots\" content=\"index, nofollow\">\n";

echo "  <meta name=\"revisit-after\" content=\"20 days\">\n";

echo "		<SCRIPT language=javascript><!--\n";

echo "		function jsconfirm() {\n";

echo "		return confirm (\"You really want to do this ?\") }\n";

echo "		// --></SCRIPT>\n";

echo " </head>\n";





echo "<body bgcolor=\"$bgcolor\" link=\"$linkcolor\">\n";



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_menu\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

echo"        <div class=\"mainheader\">phpBazar Administration-Panel</div>\n";

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"  </tr>\n";

echo" </table>\n";



include ("../spacer.inc");



echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_menu\" height=\"400\">\n";

echo"   <tr><td>\n";





#  Menu

#################################################################################################

echo" <table align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"150\" height=\"100%\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

echo"          <div class=\"smallleft\">\n";

echo"          <a href=\"$PHP_SELF\">Home</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=members\">Members</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=memberform\">MemberForms</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=cats\">Categories</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=subcats\">Subcategories</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=adapproval\">Ad approval</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=admove\">Ad move</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=picofday\">PicOfTheDay</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=newsletter\">Newsletter</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=votes\">Votes</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=smilies\">Smilies</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=badwords\">Bad Words</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=banned_ips\">Banned IPs</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=banned_users\">Banned Users</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=resend_delayed_confirms\">Resend Confirms</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=schedule\">Scheduled Tasks</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=logging\">Logging</a>\n";

echo"          <br><hr><a href=\"$PHP_SELF?action=confirm_flush\" onclick=\"return jsconfirm()\">Flush Confirms</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=deleted_user_flush\" onclick=\"return jsconfirm()\">Flush deleted Users</a>\n";

echo"          <br><br><a href=\"$PHP_SELF?action=timeoutad_flush\" onclick=\"return jsconfirm()\">Flush timeout Ads</a>\n";
echo"          <br><br><a href=\"$PHP_SELF?action=nullified\">Nillified Info</a>\n";

if ($sales_option) {

    echo"          <br><hr><a href=\"salesadmin.php\">Sales Admin-Panel</a>\n";

}

echo"          </div>\n";

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";



#include ("../spacer.inc");



#  Main Window

#################################################################################################

echo" <table align=\"right\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"580\" height=\"100%\">\n";

echo"   <tr>\n";

echo"    <td class=\"class1\">\n";

echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"100%\">\n";

echo"       <tr>\n";

echo"        <td class=\"class2\">\n";

echo"	      <center><br>\n";







if ($action == "memberformupdate" && $id) {

    mysql_connect($server, $db_user, $db_pass);

    mysql_db_query($database, "UPDATE config SET value='$enable',value2='$signup',value3='$type',value4='$option',value5='$public' WHERE id ='$id'") or die(mysql_error());

    mysql_close();

    $redirect="$PHP_SELF?action=memberform";

}



if ($action == "memberform") {

    echo"<b>Edit Memberforms</b><br><br>\n";



    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "select * FROM config WHERE type='member'") or die("Database Query Error");



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" width=\"100\">\n";

    echo "      <b>Fieldname</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"50\">\n";

    echo "      <b>Enable</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"50\">\n";

    echo "      <b>Public</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"50\">\n";

    echo "      <b>Signup</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"74\">\n";

    echo "      <b>Type</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\">\n";

    echo "      <b>Options</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"50\">\n";

    echo "      <b></b>";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

        echo "<form action=\"$PHP_SELF\" method=\"POST\" style=display:inline;>\n";

	echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\" width=\"100\">\n";

	echo "     <div class=\"smallleft\">$db[name]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"50\">\n";

	echo "     <div class=\"smallcenter\">".selectyesno("enable",$db[value])."</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"50\">\n";

	echo "     <div class=\"smallcenter\">".selectyesno("public",$db[value5])."</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"50\">\n";

	echo "     <div class=\"smallcenter\">".selectyesno("signup",$db[value2])."</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"74\">\n";

	if ($db[name]!="sex" && $db[name]!="newsletter") {

	    echo "     <div class=\"smallcenter\">".selecttype("type",$db[value3])."</div>";

	}

        echo "    </td>\n";

        echo "    <td class=\"class3\">\n";

	if ($db[name]!="sex" && $db[name]!="newsletter") {

	    echo "	   <div class=\"smallleft\"><input type=\"text\" name=\"option\" value=\"".htmlspecialchars($db[value4])."\" size=32></div>\n";

	}

        echo "    </td>\n";

	echo "    <td class=\"class3\" width=\"50\">\n";

	echo "	   <div class=\"smallcenter\"><input type=\"submit\" value=\"Save\"></div>\n";

	echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

        echo "<input type=\"hidden\" name=\"id\" value=\"$db[id]\">\n";

        echo "<input type=\"hidden\" name=\"action\" value=\"memberformupdate\"></form>\n";



    }



    mysql_close();

    echo "<br>\n";

    echo "<div class=\"smallleft\">HINT: For Field-Name definition, see ./lang/XX/variables.php - Parameters: \$memf_*<br>

    If TYPE=Select, OPTION holds the select-values eg.: value1|value2|value3 or a txt-file in ./lang/XX-dir eg.:sortoption1.inc</div>\n";

    echo "<br><br>\n";



}



if ($action == "schedule") {

    echo"<b>Run 'scheduled tasks' manual</b><br><small>(with raw output)</small><br><br>\n";

    echo"<a href=\"notify.php\" target=\"_blank\">Notify</a><br><br>\n";

    echo"<a href=\"backup.php\" target=\"_blank\">Backup</a><br><br>\n";

    echo"<a href=\"cleanup.php\" target=\"_blank\">Cleanup</a><br><br>\n";



}





if ($action == "newsletter") {

    echo"<b>Send Newsletter</b><br><br>\n";

    echo"<form enctype=\"multipart/form-data\" name=\"doit\" action=\"$PHP_SELF?action=send_newsletter\" method=POST>\n";

    echo"<table cellpadding=0 cellspacing=0>\n";

    echo"<tr><td><b>From:</b></td><td><input type=\"text\" name=\"from\" size=30 value=\"$admin_email\"></td></tr>\n";

    echo"<tr><td valign=\"top\"><b>To:</b></td><td><input type=\"text\" name=\"to\" size=30>\n";

    echo"<div class=\"smallleft\">AND<input type=\"checkbox\" name=\"newslettermembers\">Newsletter Members<br>\n";

    echo"OR<input type=\"checkbox\" name=\"allmembers\">All Members<br>\n";

    echo"<div class=\"smallleft\">AND <input type=\"text\" name=\"whereclause\"> <small>(SQL where-clause on userdata-table, eg. \"ads<1\")</small></div></td></tr>\n";

    echo"<tr><td><b>Cc:</b></td><td><input type=\"text\" name=\"cc\" size=30></td></tr>\n";

    echo"<tr><td><b>Bcc:</b></td><td><input type=\"text\" name=\"bcc\" size=30></td></tr>\n";

    echo"<tr><td><b>Attachment:&nbsp;</b></td><td><input type=\"file\" name=\"pic1\" size=30></td></tr>\n";

    echo"<tr><td>&nbsp;</td><td><input type=\"file\" name=\"pic2\" size=30></td></tr>\n";

    echo"<tr><td>&nbsp;</td><td><input type=\"file\" name=\"pic3\" size=30></td></tr>\n";

    echo"<tr><td><b>Subject:</b></td><td><input type=\"text\" name=\"subject\" size=30>\n";

    echo" <input type=\"checkbox\" name=\"html\">HTML</td></tr></table>\n";

    echo"<textarea name=\"body\" rows=10 cols=50 wrap=\"message\">\n";

    echo"</textarea><br><br>\n";

    echo"<input type=\"submit\" name=\"sendmail\" value=\"Send\">\n";

    echo"</form>\n";



    echo "<small>HINT: To add a unsubscribe link to your message, you can add a similar line like this:<br><br>\n";

    echo "<table><tr><td><small>    --<br>    To unsubscribe our newsletter, goto your profile<br>\n";

    echo "    $url_to_start/members.php?choice=myprofile<br>\n";

    echo "    remove the newsletter-checkbox and click the [Update] Button.<br></small></td></tr></table>\n";

}





if ($action == "send_newsletter") {



    $count=0;

    $count2=0;

    require ("../library_mail.php");

    $mail = new phpmailer;



    $subject=stripslashes(urldecode($subject));

    $body=stripslashes(urldecode($body));



    $mail->From     = "$from";

    $mail->FromName = "$bazar_name";

#    $mail->WordWrap = 75;

    $mail->UseMSMailHeaders = true;

    $mail->AddCustomHeader("X-Mailer: $bazar_name $bazar_version - Email Interface");

    $mail->Subject = $subject;

    $mail->Body    = $body;

    if ($html) { $mail->IsHTML(true); }





    if (function_exists("ini_get")) {

	$upl_tmp_dir=ini_get('upload_tmp_dir');

    } else {

	$upl_tmp_dir=get_cfg_var('upload_tmp_dir');

    }



    if ($fix_tmp_dir) { // only for fixing on some servers, normally NOT used - set in config.php

        $tmp_dir = $fix_tmp_dir;

    } elseif ($upl_tmp_dir) {

        $tmp_dir = $upl_tmp_dir;

    } else {

        $tmp_dir = dirname(tempnam('', ''));

    }



    if ($pic1 != "none" && $pic1) {

	$pic1_file=$tmp_dir."/pic1.tmp";

	copy ($pic1,$pic1_file);

    }

    if ($pic1_file) {$mail->AddAttachment("$pic1_file", "$pic1_name"); }



    if ($pic2 != "none" && $pic2) {

	$pic2_file=$tmp_dir."/pic2.tmp";

	copy ($pic2,$pic2_file);

    }

    if ($pic2_file) {$mail->AddAttachment("$pic2_file", "$pic2_name"); }



    if ($pic3 != "none" && $pic3) {

	$pic3_file=$tmp_dir."/pic3.tmp";

	copy ($pic3,$pic3_file);

    }

    if ($pic3_file) {$mail->AddAttachment("$pic3_file", "$pic3_name"); }



    echo "<b>Send Newsletter</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";



    if ($to && $from) {  		// send newsletter to entered recipient

	$mail->AddAddress("$to");

	if ($cc) {$mail->AddCC("$cc");}

	if ($bcc) {$mail->AddBCC("$bcc");}

	if(!$mail->Send()) {

	    echo "   <tr>\n";

    	    echo "    <td class=\"class3\">\n";

	    echo "<b> There was an error sending the message to $to !!!</b>";

    	    echo "    </td>\n";

	    echo "  </tr>\n";

	}

 else {

    	    echo "   <tr>\n";

            echo "    <td class=\"class3\">\n";

	    echo"	<small>Mail to $to with subject: ".stripslashes($subject)." -> <b>sent</b> !</small>";

    	    echo "    </td>\n";

	    echo "  </tr>\n";

	}

	$mail->ClearAllRecipients();

	$count++;

    }



    if (($newslettermembers || $allmembers) && $from) {			// send newsletter to all members

	$sql="select username,email FROM userdata WHERE 1=1";

	if ($newslettermembers) {

	    $sql.=" AND (newsletter='1' OR newsletter='on')";

	}

	if ($whereclause) {

	    $sql.=" AND (".stripslashes(urldecode($whereclause)).")";

	}

	if (!$counter) {$counter=0;}

	$count+=$counter;

	$sql.=" LIMIT $counter,25";



        $result = mysql_db_query($database, $sql) or die("ERROR: $sql<br>".mysql_error());

	while ($db = mysql_fetch_array($result)) {

	    if (! get_cfg_var('safe_mode')) {set_time_limit(0);}

	    $mail->AddAddress("$db[email]", "$db[username]");

	    if(!$mail->Send()) {

		echo "   <tr>\n";

    		echo "    <td class=\"class3\">\n";

		echo "<b> There was an error sending the message to $to !!!</b>";

    		echo "    </td>\n";

		echo "  </tr>\n";

	    } else {

		echo "   <tr>\n";

    		echo "    <td class=\"class3\">\n";

		echo "     <small>Mail to ".$db[username]." [".$db[email]."] with subject: $subject -> <b>sent</b> !</small>";

    		echo "    </td>\n";

		echo "  </tr>\n";

	    }

	    flush();

	    $mail->ClearAllRecipients();

	    $count++;

	    $count2++;

	}

    }



    echo "</table>\n";

    echo "<br>$count Newsletters sent.<br>\n";

    mysql_close;



    if ($count2==25) {

	$redirect="newsletter";

	echo "<small>... sending in progress - please be patient ...</small>";

    }
 else {

	if ($pic1_file) {suppr($pic1_file);}

	if ($pic2_file) {suppr($pic2_file);}

	if ($pic3_file) {suppr($pic3_file);}

	echo "DONE";

    }



}



if ($action == "picofday") {

    mysql_connect($server, $db_user, $db_pass);

    $query=mysql_db_query($database, "SELECT * FROM config WHERE type='config' AND name='fix_adoftheday'") or die("Database Query Error");

    $db=mysql_fetch_array($query);

    mysql_close();



    echo "<b>PictureAd of the Day</b><br><br>\n";

    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<input type=\"hidden\" name=\"action\" value=\"picofdayfix\">\n";

    echo "Fix to Ad-Number <br> \n";

    echo "<input type=text name=\"adid\" size=\"10\" maxlength=\"14\" value=\"$db[value]\">\n";

    echo "<br><input type=submit value=\"FixAd (no random)\" onclick=\"return jsconfirm()\">\n";

    echo "</form>\n";



    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<input type=\"hidden\" name=\"action\" value=\"picofdaynew\">\n";

    echo "<input type=submit value=\"RenewAd (random)\" onclick=\"return jsconfirm()\"></td>\n";

    echo "</form>\n";



}



if ($action == "picofdayfix") {

    mysql_connect($server, $db_user, $db_pass);

    mysql_db_query($database, "DELETE FROM config WHERE type='config' AND name='fix_adoftheday'") or die("Database Query Error");

    mysql_db_query($database, "INSERT INTO config (type,name,value) VALUES ('config','fix_adoftheday','$adid')") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=picofday";

}



if ($action == "picofdaynew") {

    mysql_connect($server, $db_user, $db_pass);

    mysql_db_query($database, "DELETE FROM config WHERE type='config' AND name='fix_adoftheday'") or die("Database Query Error");

    mysql_db_query($database, "DELETE FROM favorits WHERE userid>'100000000'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=picofday";

}





if ($action == "timeoutad_flush") {

    echo "<b>Flush timeout Ads</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";



	if ($really_del_memb) {

	    $result = mysql_db_query($database, "select * FROM ads WHERE (TO_DAYS(addate)<TO_DAYS(now())-(durationdays+timeoutdays)) OR deleted='1'")

	    		or die("Database Query Error");

	} else {

	    $result = mysql_db_query($database, "select * FROM ads WHERE TO_DAYS(addate)<TO_DAYS(now())-(durationdays+timeoutdays)")

	    		or die("Database Query Error");

	}

	while ($db = mysql_fetch_array($result)) {



	    // Subtract Counter in userdata-DB

	    mysql_db_query($database,"update userdata set ads=ads-1,lastaddate=now() where id='$db[userid]'")

	    or die("Database Query Error - userdata");



	    // Subtract Counter in adcat-DB

	    mysql_db_query($database,"update adcat set ads=ads-1 where id='$db[catid]'")

	    or die("Database Query Error - adcat");



	    // Subtract Counter in adsubcat-DB

	    mysql_db_query($database,"update adsubcat set ads=ads-1 where id='$db[subcatid]'")

	    or die("Database Query Error - adsubcat");



	    // Delete Pictures if any ...

    	    if (!$pic_database && $db[picture] && is_file("$bazar_dir/$pic_path/$db[picture]")) {

        	suppr("$bazar_dir/$pic_path/$db[picture]");

    	    } elseif ($db[picture]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[picture]'") or die("Database Query Error");

	    }

    	    if (!$pic_database && $db[_picture] && is_file("$bazar_dir/$pic_path/$db[_picture]")) {

        	suppr("$bazar_dir/$pic_path/$db[_picture]");

    	    } elseif ($db[_picture]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[_picture]'") or die("Database Query Error");

    	    }



    	    if (!$pic_database && $db[picture2] && is_file("$bazar_dir/$pic_path/$db[picture2]")) {

        	suppr("$bazar_dir/$pic_path/$db[picture2]");

    	    } elseif ($db[picture2]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[picture2]'") or die("Database Query Error");

	    }

    	    if (!$pic_database && $db[_picture2] && is_file("$bazar_dir/$pic_path/$db[_picture2]")) {

        	suppr("$bazar_dir/$pic_path/$db[_picture2]");

    	    } elseif ($db[_picture2]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[_picture2]'") or die("Database Query Error");

    	    }



    	    if (!$pic_database && $db[picture3] && is_file("$bazar_dir/$pic_path/$db[picture3]")) {

        	suppr("$bazar_dir/$pic_path/$db[picture3]");

    	    } elseif ($db[picture3]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[picture3]'") or die("Database Query Error");

	    }

    	    if (!$pic_database && $db[_picture3] && is_file("$bazar_dir/$pic_path/$db[_picture3]")) {

        	suppr("$bazar_dir/$pic_path/$db[_picture3]");

    	    } elseif ($db[_picture3]) {

		mysql_db_query($database,"delete from pictures where picture_name = '$db[_picture3]'") or die("Database Query Error");

    	    }



            // Delete Attachments if any ...

            if ($db[attachment1] && is_file("$bazar_dir/$att_path/$db[attachment1]")) {

                suppr("$bazar_dir/$att_path/$db[attachment1]");

            }

            if ($db[attachment2] && is_file("$bazar_dir/$att_path/$db[attachment2]")) {

                suppr("$bazar_dir/$att_path/$db[attachment2]");

            }

            if ($db[attachment3] && is_file("$bazar_dir/$att_path/$db[attachment3]")) {

                suppr("$bazar_dir/$att_path/$db[attachment3]");

            }



    	    // Delete Entry from favorits-DB

	    mysql_db_query($database,"delete from favorits where adid = '$db[id]'")

	    or die("Database Query Error");



	    // Delete Entry from ads-DB

	    mysql_db_query($database,"delete from ads where id = '$db[id]'")

	    or die("Database Query Error - ads");



	    echo "   <tr>\n";

    	    echo "    <td class=\"class3\">\n";

	    echo "Ad ID: <b>$db[id]</b> Header: <b>$db[header]</b> deleted<br>\n";

    	    echo "    </td>\n";

	    echo "  </tr>\n";

	    $count++;

        }

    echo "</table>\n";

    echo "<br>$count timeout Ads deleted from [ads]-Table.<br>\n";

    mysql_close;

}







if ($action == "adapproval") {



    if ($adapproval) {

    $count=0;

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "select * FROM ads WHERE publicview!='1' ORDER by addate ASC") or die("Database Query Error");



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\">\n";

    echo "      <b>ID</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"30\">\n";

    echo "      <b>CID</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"30\">\n";

    echo "      <b>SID</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"30\">\n";

    echo "      <b>UID</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"140\">\n";

    echo "      <b>Header</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"150\">\n";

    echo "      <b>Text</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"right\" width=\"160\">\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

	echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "     <div class=\"smallcenter\">$db[id]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">$db[catid]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=admove&value=$db[id]&value2=$db[subcatid]\">$db[subcatid]</a></div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">$db[userid]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"140\">\n";

	echo "     <div class=\"smallcenter\">$db[header]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"150\">\n";

	echo "     <div class=\"smallcenter\">$db[text]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" align=\"right\" width=\"160\">\n";

	echo "	   <div class=\"smallcenter\"><a href=\"$PHP_SELF\" onClick='enterWindow=window.open(\"$url_to_start/classified_search_submit.php?in[searchmode]=simple&in[adid]=$db[id]\",\"Detail\",\"width=780,height=550,top=10,left=10,scrollbars=yes\"); return false'>Detail</a>\n";

	echo "	   $menusep<a href=\"$PHP_SELF?action=approve_ad&value=$db[id]\">Appr.</a>\n";

	echo "	   $menusep<a href=\"$PHP_SELF?action=delete_ad&value=$db[id]\" onclick=\"return jsconfirm()\">Delete</a></div>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }

    mysql_close();

    echo "<br>$count unapproved ADs in [ads]-Table.<br><br>\n";



    } else {

    echo "Ad approval is turned OFF (see \"config.php\")<br>\n";

    }

}



if ($action == "delete_ad") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "DELETE FROM ads WHERE id = '$value'") or die("Database Delete Error");

    mysql_close();

    $redirect="$PHP_SELF?action=adapproval";

}



if ($action == "approve_ad") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "SELECT * FROM ads WHERE id='$value'") or die("Record NOT Found");

    $db = mysql_fetch_array($result);

    $result = mysql_db_query($database, "UPDATE ads SET publicview='1' WHERE id ='$value'") or die("Database Query Error");

    if ($db[publicview]==0) {

	$result = mysql_db_query($database, "update adcat set ads=ads+1 where id='$db[catid]'") or die("Database Query Error");

	$result = mysql_db_query($database, "update adsubcat set ads=ads+1,notify='1' where id='$db[subcatid]'") or die("Database Query Error");

	$result = mysql_db_query($database, "update userdata set ads=ads+1,lastaddate=now() where id='$db[userid]'") or die("Database Query Error");

    }



    mysql_close();

    $redirect="$PHP_SELF?action=adapproval";

}



if ($action == "logging" || $action == "loggingsearch") {



    if ($logging_enable) {

    mysql_connect($server, $db_user, $db_pass);



    #  Calculate Page-Numbers

    #################################################################################################

    $perpage = 50;

    $pperpage = 9;				//!!! ONLY 5,7,9,11,13 !!!!

    if (empty($offset)) $offset = 0;

    if (empty($poffset)) $poffset = 0;

    if (empty($sort)) $sort = "asc";

    if (empty($orders)) $orders = "timestamp";

    if ($orders == "timestamp") $sort = "desc";



    if ($action == "loggingsearch" && $value) {

	$sqlsearch="WHERE username LIKE '%$value%' OR

			  event LIKE '%$value%' OR

			  ext LIKE '%$value%' OR

			  ip LIKE '%$value%'

	 ";

    }



    $amount = mysql_db_query($database, "SELECT count(*) FROM logging $sqlsearch");

    $amount_array = mysql_fetch_array($amount);

    $pages = ceil($amount_array["0"] / $perpage);

    $actpage = ($offset+$perpage)/$perpage;

    $maxpoffset = $pages-$pperpage;

    $middlepage=($pperpage-1)/2;

    if ($maxpoffset<0) {$maxpoffset=0;}



    echo "<table width=\"100%\"><tr><td class=classadd1><b>Logging</b></td><td class=classadd1>\n";



    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<input type=\"hidden\" name=\"action\" value=\"loggingsearch\">\n";

    echo "<input type=text name=\"value\" size=\"20\" maxlength=\"20\" value=\"$value\">\n";

    echo "<input type=submit value=Search>\n";

    echo "</form>\n";

    echo "</td></tr></table>\n";

    echo "<div class=\"smallleft\">Display $perpage Events per page, sort on $orders $sort</div>\n";

    echo "<div class=\"smallleft\">HINT: to remove old log-events and workout missing userdata, run scheduled task <a href=\"cleanup.php\" target=\"_blank\">Cleanup</a></div>\n";



    echo "<div class=\"mainpages\">\n";



    if ($pages) {                                       // print only when pages > 0

        echo "$ad_pages\n";



        if ($offset) {

	    $noffset=$offset-$perpage;

    	    $npoffset = $noffset/$perpage-$middlepage;

            if ($npoffset<0) {$npoffset=0;}

	    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	    echo "[<a href=\"$PHP_SELF?action=logging&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\"><</a>] ";

	}



        for($i = $poffset; $i< $poffset+$pperpage && $i < $pages; $i++) {

	    $noffset = $i * $perpage;

    	    $npoffset = $noffset/$perpage-$middlepage;

    	    if ($npoffset<0) {$npoffset = 0;}

    	    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	    $actual = $i + 1;

    	    if ($actual==$actpage) {

 		echo "(<a href=\"$PHP_SELF?action=logging&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\">$actual</a>) ";

    	    } else {

 		echo "[<a href=\"$PHP_SELF?action=logging&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\">$actual</a>] ";

	    }

	}



	if ($offset+$perpage<$amount_array["0"]) {

    	    $noffset=$offset+$perpage;

    	    $npoffset = $noffset/$perpage-$middlepage;

    	    if ($npoffset<0) {$npoffset=0;}

    	    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	    echo "[<a href=\"$PHP_SELF?action=logging&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\">></a>] ";

        }

    }



    echo "</div>\n";



    $result = mysql_db_query($database, "select * FROM logging $sqlsearch ORDER by $orders $sort LIMIT $offset, $perpage") or die(mysql_error());



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" width=\"80\">\n";

    echo "      <a href=\"$PHP_SELF?action=logging&orders=timestamp&sort=desc&offset=$offset&poffset=$poffset\">Time</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"100\">\n";

    echo "      <a href=\"$PHP_SELF?action=logging&orders=userid&sort=asc&offset=$offset&poffset=$poffset\">UserID</a>";

    echo "      <a href=\"$PHP_SELF?action=logging&orders=username&sort=asc&offset=$offset&poffset=$poffset\">Username</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"100\">\n";

    echo "      <a href=\"$PHP_SELF?action=logging&orders=event&sort=asc&offset=$offset&poffset=$poffset\">Event</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\">\n";

    echo "      <a href=\"$PHP_SELF?action=logging&orders=ext&sort=asc&offset=$offset&poffset=$poffset\">Ext</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"100\">\n";

    echo "      <a href=\"$PHP_SELF?action=logging&orders=ip&sort=desc&offset=$offset&poffset=$poffset\">IP-Addr</a>";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

	echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\" width=\"80\">\n";

	echo "     <div class=\"smallleft\">".date($dateformat,$db[timestamp])."</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"100\">\n";

	if (!$db[userid]) {$db[userid]="unknown";}

	if (!$db[username]) {$db[username]="unknown";}

	echo "     <div class=\"smallleft\">ID: $db[userid]</div>";

	echo "     <div class=\"smallleft\">$db[username]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"100\">\n";

	echo "     <div class=\"smallleft\">$db[event]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\">\n";

	echo "     <div class=\"smallleft\">$db[ext]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"100\">\n";

	echo "     <div class=\"smallleft\">$db[ip]</div>";

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }

    mysql_close();

    echo "<br>$amount_array[0] LogEvents found in [logging]-Table.<br><br>\n";



    } else {

    echo "Logging is turned OFF (see \"config.php\")<br>\n";

    }



}







if ($action == "members" || $action == "membersearch") {

    mysql_connect($server, $db_user, $db_pass);



    #  Calculate Page-Numbers

    #################################################################################################

    $perpage = 50;

    $pperpage = 9;				//!!! ONLY 5,7,9,11,13 !!!!

    if (empty($sort)) $sort = "asc";

    if (empty($offset)) $offset = 0;

    if (empty($poffset)) $poffset = 0;

    if (empty($orders)) $orders = "id";



    if ($action == "membersearch" && $value) {

	$sqlsearch="WHERE username LIKE '%$value%' OR email LIKE '%$value%'";

    }



    $amount = mysql_db_query($database, "SELECT count(*) FROM userdata $sqlsearch");

    $amount_array = mysql_fetch_array($amount);

    $pages = ceil($amount_array["0"] / $perpage);

    $actpage = ($offset+$perpage)/$perpage;

    $maxpoffset = $pages-$pperpage;

    $middlepage=($pperpage-1)/2;

    if ($maxpoffset<0) {$maxpoffset=0;}



    echo "<table width=\"100%\"><tr><td class=classadd1><b>Members</b></td><td class=classadd1>\n";



    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<input type=\"hidden\" name=\"action\" value=\"membersearch\">\n";

    echo "<input type=text name=\"value\" size=\"20\" maxlength=\"20\" value=\"$value\">\n";

    echo "<input type=submit value=Search>\n";

    echo "</form>\n";

    echo "</td></tr></table>\n";



    echo "<div class=\"mainpages\">\n";



    if ($pages) {                                       // print only when pages > 0

        echo "$ad_pages\n";





        if ($offset) {

	    $noffset=$offset-$perpage;

    	    $npoffset = $noffset/$perpage-$middlepage;

            if ($npoffset<0) {$npoffset=0;}

	    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	    echo "[<a href=\"$PHP_SELF?action=members&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\"><</a>] ";

	}



        for($i = $poffset; $i< $poffset+$pperpage && $i < $pages; $i++) {

	    $noffset = $i * $perpage;

    	    $npoffset = $noffset/$perpage-$middlepage;

    	    if ($npoffset<0) {$npoffset = 0;}

    	    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	    $actual = $i + 1;

    	    if ($actual==$actpage) {

 		echo "(<a href=\"$PHP_SELF?action=members&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\">$actual</a>) ";

    	    } else {

 		echo "[<a href=\"$PHP_SELF?action=members&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\">$actual</a>] ";

	    }

	}



	if ($offset+$perpage<$amount_array["0"]) {

    	    $noffset=$offset+$perpage;

    	    $npoffset = $noffset/$perpage-$middlepage;

    	    if ($npoffset<0) {$npoffset=0;}

    	    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}

	    echo "[<a href=\"$PHP_SELF?action=members&orders=$orders&sort=$sort&offset=$noffset&poffset=$npoffset\">></a>] ";

        }

    }



    echo "</div>\n";



    $result = mysql_db_query($database, "select * FROM userdata $sqlsearch ORDER by $orders $sort LIMIT $offset, $perpage") or die("Database Query Error");

    $result2 = mysql_db_query($database, "select * FROM login ") or die("Database Query Error");



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\">\n";

    echo "      <a href=\"$PHP_SELF?action=members&orders=id&sort=asc&offset=$offset&poffset=$poffset\">ID</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"150\">\n";

    echo "      <a href=\"$PHP_SELF?action=members&orders=username&sort=asc&offset=$offset&poffset=$poffset\">Username</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">\n";

    echo "      <a href=\"$PHP_SELF?action=members&orders=email&sort=asc&offset=$offset&poffset=$poffset\">E-Mail</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"30\">\n";

    echo "      <a href=\"$PHP_SELF?action=members&orders=level&sort=desc&offset=$offset&poffset=$poffset\">Lev</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"30\">\n";

    echo "      <a href=\"$PHP_SELF?action=members&orders=votes&sort=desc&offset=$offset&poffset=$poffset\">Vot</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"30\">\n";

    echo "      <a href=\"$PHP_SELF?action=members&orders=ads&sort=desc&offset=$offset&poffset=$poffset\">Ads</a>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

    echo "	<div class=\"smallright\">per page: $perpage <br>sort: $orders</div>\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

	$result2 = mysql_db_query($database, "select * from login WHERE id=$db[id]") or die("Database Query Error");

	$db2 = mysql_fetch_array($result2);

	echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "     <div class=\"smallcenter\">$db[id]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"150\">\n";

	echo "     <div class=\"smallcenter\">$db[username]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"200\">\n";

	echo "     <div class=\"smallcenter\"><a href=\"mailto:$db[email]\">$db[email]</a></div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">$db[level]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">$db[votes]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">$db[ads]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

	if (substr($db2[password],0,8) == "deleted_") {

	    echo "	   <div class=\"smallcenter\">deleted</div>\n";

	} else {

	    echo "	   <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=edit_member&value=$db[id]\">Edit</a>\n";

	    echo "	   $menusep<a href=\"$PHP_SELF?action=delete_member&value=$db[id]\">Delete</a></div>\n";

	}

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }

    mysql_close();

    echo "<br>$amount_array[0] Members found in [userdata]-Table.<br><br>\n";

}



if ($action == "edit_member") {

    echo "<b>Edit Member</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "select * from userdata where id='$value'") or die("Database Query Error");

    $db = mysql_fetch_array($result);

    $result2 = mysql_db_query($database, "select * from login where id='$value'") or die("Database Query Error");

    $db2 = mysql_fetch_array($result2);

    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    echo "<input type=\"hidden\" name=\"action\" value=\"save_edit_member\">\n";

    echo "<input type=\"hidden\" name=\"in[id]\" value=\"$value\">\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">ID (read only): </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text readonly name=\"in[id]\" size=\"25\" maxlength=\"50\" value=\"$db[id]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Username : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[username]\" size=\"25\" maxlength=\"50\" value=\"$db[username]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Password : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[password]\" size=\"25\" maxlength=\"50\" value=\"$db2[password]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">E-Mail : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[email]\" size=\"25\" maxlength=\"50\" value=\"$db[email]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Gender : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[sex]\" size=\"25\" maxlength=\"50\" value=\"$db[sex]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Newsletter : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[newsletter]\" size=\"25\" maxlength=\"50\" value=\"$db[newsletter]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Level : </div></td>\n";

    $selected[$db[level]]="SELECTED";

    echo "<td class=\"classadd2\"><select name=\"in[level]\">\n";

    echo "<option value=\"0\" $selected[0]>0  [Junior Member]</option>\n";

    echo "<option value=\"1\" $selected[1]>1  [Member]</option>\n";

    echo "<option value=\"2\" $selected[2]>2  [Senior Member]</option>\n";

    echo "<option value=\"3\" $selected[3]>3  [Paying Member (rw)]</option>\n";

    echo "<option value=\"4\" $selected[4]>4  [Paying Member (w)]</option>\n";

    echo "<option value=\"5\" $selected[5]>5  [Paying Member (a)]</option>\n";

    echo "<option value=\"6\" $selected[6]>6  [Paying Member (rwa)]</option>\n";

    echo "<option value=\"7\" $selected[7]>7  [Paying Member (wa)]</option>\n";

    echo "<option value=\"8\" $selected[8]>8  [Moderator]</option>\n";

    echo "<option value=\"9\" $selected[9]>9  [Administrator]</option>\n";

    echo "</select><small><br>SalesOptions: Pay for r=read, w=write, a=answer</small></td>\n";

    echo "</tr>\n";



    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Votes : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[votes]\" size=\"25\" maxlength=\"50\" value=\"$db[votes]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Last Votedate : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[lastvotedate]\" size=\"25\" maxlength=\"50\" value=\"".dateToStr($db[lastvotedate])."\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Ads (read only): </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text readonly name=\"in[ads]\" size=\"25\" maxlength=\"50\" value=\"$db[ads]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Last Addate : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[lastaddate]\" size=\"25\" maxlength=\"50\" value=\"".dateToStr($db[lastaddate])."\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Firstname : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[firstname]\" size=\"25\" maxlength=\"50\" value=\"$db[firstname]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Lastname : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[lastname]\" size=\"25\" maxlength=\"50\" value=\"$db[lastname]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Address : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[address]\" size=\"25\" maxlength=\"50\" value=\"$db[address]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Zip : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[zip]\" size=\"25\" maxlength=\"50\" value=\"$db[zip]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">City : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[city]\" size=\"25\" maxlength=\"50\" value=\"$db[city]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">State : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[state]\" size=\"25\" maxlength=\"50\" value=\"$db[state]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Country : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[country]\" size=\"25\" maxlength=\"50\" value=\"$db[country]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Phone : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[phone]\" size=\"25\" maxlength=\"50\" value=\"$db[phone]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Cellphone : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[cellphone]\" size=\"25\" maxlength=\"50\" value=\"$db[cellphone]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">ICQ : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[icq]\" size=\"25\" maxlength=\"50\" value=\"$db[icq]\"></td>\n";

    echo "</tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Homepage : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[homepage]\" size=\"25\" maxlength=\"50\" value=\"$db[homepage]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Hobbies : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[hobbys]\" size=\"25\" maxlength=\"50\" value=\"$db[hobbys]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field1 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field1]\" size=\"25\" maxlength=\"50\" value=\"$db[field1]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field2 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field2]\" size=\"25\" maxlength=\"50\" value=\"$db[field2]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field3 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field3]\" size=\"25\" maxlength=\"50\" value=\"$db[field3]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field4 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field4]\" size=\"25\" maxlength=\"50\" value=\"$db[field4]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field5 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field5]\" size=\"25\" maxlength=\"50\" value=\"$db[field5]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field6 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field6]\" size=\"25\" maxlength=\"50\" value=\"$db[field6]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field7 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field7]\" size=\"25\" maxlength=\"50\" value=\"$db[field7]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field8 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field8]\" size=\"25\" maxlength=\"50\" value=\"$db[field8]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field9 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field9]\" size=\"25\" maxlength=\"50\" value=\"$db[field9]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field10 : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[field10]\" size=\"25\" maxlength=\"50\" value=\"$db[field10]\"></td>\n";

    echo "</tr>\n";



    echo "<tr>\n";

    echo "<td class=\"classadd2\"></td>\n";

    echo "<td class=\"classadd2\"><br><input type=submit value=Submit></td>\n";

    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";

    mysql_close();



}



if ($action == "save_edit_member") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "UPDATE login SET username='$in[username]',password='$in[password]'

							  WHERE id='$in[id]'") or die("Database Query Error");



    $result = mysql_db_query($database, "UPDATE userdata SET username='$in[username]',

							     email='$in[email]',

							     sex='$in[sex]',

							     newsletter='$in[newsletter]',

							     level='$in[level]',

							     votes='$in[votes]',

							     lastvotedate='$in[lastvotedate]',

							     ads='$in[ads]',

							     lastaddate='$in[lastaddate]',

							     firstname='$in[firstname]',

							     lastname='$in[lastname]',

							     address='$in[address]',

							     zip='$in[zip]',

							     city='$in[city]',

							     state='$in[state]',

							     country='$in[country]',

							     phone='$in[phone]',

							     cellphone='$in[cellphone]',

							     icq='$in[icq]',

							     homepage='$in[homepage]',

							     hobbys='$in[hobbys]',

    		                                             field1 = '$in[field1]',

	                                                     field2 = '$in[field2]',

                                                             field3 = '$in[field3]',

                                                             field4 = '$in[field4]',

                                                             field5 = '$in[field5]',

                                                             field6 = '$in[field6]',

                                                             field7 = '$in[field7]',

                                                             field8 = '$in[field8]',

                                                             field9 = '$in[field9]',

                                                             field10 = '$in[field10]'

							     WHERE id='$in[id]'") or die(mysql_error());

    mysql_close();

    $redirect="$PHP_SELF?action=members";

}







if ($action == "delete_member") {

    $delstring="deleted_".$timestamp;

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "UPDATE login SET password='$delstring' where id = '$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=members";

}





if ($action == "cats") {

    echo "<b>Categories</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from adcat order by id") or die("Database Query Error");



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" width=\"20\">\n";

    echo "      <b>ID</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\">\n";

    if ($sales_option) {$sales_head="Sales";} else {$sales_head="&nbsp;";}

    echo "      <b>$sales_head</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"150\">\n";

    echo "      <b>Name</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"150\">\n";

    echo "      <b>Description</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"30\">\n";

    echo "      <b>Ads</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"50\">\n";

    echo "      <b>Pic</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

    echo "	 <a href=\"$PHP_SELF?action=new_cat\">New</a>\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

        echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\" width=\"20\">\n";

	echo "     <div class=\"smallcenter\">$db[id]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\">\n";

	if ($sales_option && $db[sales]) {$sales_row=$db[sales];} else {$sales_row="&nbsp;";}

	echo "     <div class=\"smallcenter\">$sales_row</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"150\">\n";

	echo "     <div class=\"smallcenter\">$db[name]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"150\">\n";

	echo "     <div class=\"smallcenter\">$db[description]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">$db[ads]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"50\">\n";

	if ($db[picture]) {

	    echo "     <div class=\"smallcenter\"><img src=\"$url_to_start/$db[picture]\"></div>";

	} else {

	    echo "&nbsp;";

	}

        echo "    </td>\n";

        echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

	echo "	   <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=edit_cat&value=$db[id]\">Edit</a>\n";

	echo "	   $menusep<a href=\"$PHP_SELF?action=delete_cat&value=$db[id]\" onclick=\"return jsconfirm()\">Delete</a><br>\n";

	echo "	   <a href=\"$PHP_SELF?action=duplicate_cat&value=$db[id]\" onclick=\"return jsconfirm()\">Duplicate</a></div>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }



    mysql_close();

    echo "<br>$count Categories in [cats]-Table.<br><br>\n";

}





if ($action == "edit_cat" || $action == "new_cat") {

    echo "<b>Edit Categories</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    if ($action == "edit_cat") {

        $result = mysql_db_query($database, "select * from adcat where id='$value'") or die("Database Query Error");

	$db = mysql_fetch_array($result);

    }

    echo "<form action=\"$PHP_SELF\" method=\"POST\">\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    if ($action == "edit_cat") {

	echo "<input type=\"hidden\" name=\"action\" value=\"save_edit_cat\">\n";

        echo "<input type=\"hidden\" name=\"in[id]\" value=\"$value\">\n";

    } else {

        echo "<input type=\"hidden\" name=\"action\" value=\"save_new_cat\">\n";

	$db[ads]=0;

	$db[id]="AUTO";

    }



    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">ID : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text readonly name=\"in[id]\" size=\"25\" maxlength=\"50\" value=\"$db[id]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Name : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[name]\" size=\"25\" maxlength=\"50\" value=\"$db[name]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Description : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[description]\" size=\"25\" maxlength=\"50\" value=\"$db[description]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">LongDescription : </div></td>\n";

    echo "<td class=\"classadd2\" colspan=5><textarea rows=\"5\" name=\"in[longdescription]\" cols=\"40\">$db[longdescription]</textarea></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Ads (read only): </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text readonly name=\"in[ads]\" size=\"25\" maxlength=\"50\" value=\"$db[ads]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Picture : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[picture]\" size=\"25\" maxlength=\"50\" value=\"$db[picture]\"></td>\n";

    echo "</tr>\n";



    echo "<tr>\n";

    echo "<td colspan=7><hr></td>\n";

    echo "</tr></table>\n";



    echo "<table align=\"center\" width=\"100%\">\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\">&nbsp;</td>\n";

    echo "<td class=\"classadd2\">Name</td>\n";

    echo "<td class=\"classadd2\">Enable</td>\n";

    echo "<td class=\"classadd2\">Type</td>\n";

    echo "<td class=\"classadd2\">Option</td>\n";

    echo "<td class=\"classadd2\">Unit</td>\n";

    echo "<td class=\"classadd2\">Search</td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">SField : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[sfield]\" size=\"25\" maxlength=\"50\" value=\"$db[sfield]\"></td>\n";

        $result = mysql_db_query($database, "SELECT * FROM config where TYPE='cat' AND name='sfield' AND value='$value'") or die(mysql_error());

	$dbc = mysql_fetch_array($result);

	echo "<td class=\"classadd2\">\n";

	echo selectyesno("in[enablesfield]","$dbc[value2]")."</td>\n";

	echo "<td class=\"classadd2\">\n";

	echo "<div class=\"smallleft\">Text</div></td>\n";

	echo "<td class=\"classadd2\">\n";

	echo "&nbsp;</td>\n";

    echo "</tr>\n";



    for($i = 1; $i<= 20; $i++) {

	$fields="field".$i;

        $result = mysql_db_query($database, "SELECT * FROM config where TYPE='cat' AND name='$fields' AND value='$value'") or die(mysql_error());

	$dbc = mysql_fetch_array($result);

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\">Field$i : </div></td>\n";

	echo "<td class=\"classadd2\">\n";

	echo "<input type=text name=\"in[field$i]\" size=\"25\" maxlength=\"50\" value=\"$db[$fields]\"></td>\n";

	echo "<td class=\"classadd2\">\n";

	echo selectyesno("in[enablefield$i]","$dbc[value2]")."</td>\n";

	echo "<td class=\"classadd2\">\n";

	echo selecttype("in[selectfield$i]","$dbc[value3]")."</td>\n";

	echo "<td class=\"classadd2\">\n";

	echo "<input type=text name=\"in[optionfield$i]\" size=\"20\" maxlength=\"255\" value=\"".htmlspecialchars($dbc[value4])."\"></td>\n";

	echo "<td class=\"classadd2\">\n";

	echo "<input type=text name=\"in[unitfield$i]\" size=\"3\" maxlength=\"10\" value=\"$dbc[value5]\"></td>\n";

	echo "<td class=\"classadd2\">\n";

	echo selectyesnominmax("in[minmaxfield$i]","$dbc[value6]")."</td>\n";

	echo "</tr>\n";

    }



    echo "<tr>\n";

    echo "<td colspan=7><div class=smallcenter>HINT: If TYPE=Select, OPTION holds the select-values eg.:value1|value2|value3 or a txt-file in ./lang/XX-dir eg.:sortoption1.inc, UNIT the unit-value eg.:kg, set MIN/MAX-SEARCH to [Yes] if you like to search between 2 values.</div><br><hr></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\">&nbsp;</td>\n";

    echo "<td class=\"classadd2\">Image</td>\n";

    echo "<td class=\"classadd2\" colspan=2>Alt-Text</td>\n";

    echo "<td class=\"classadd2\">Enable</td>\n";

    echo "</tr>\n";



    for($i = 1; $i<= 10; $i++) {

	$icons="icon".$i;

	$iconsalt="icon".$i."alt";

        $result = mysql_db_query($database, "SELECT * FROM config where TYPE='cat' AND name='$icons' AND value='$value'") or die(mysql_error());

	$dbc = mysql_fetch_array($result);

	echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\">Icon$i : </div></td>\n";

	echo "<td class=\"classadd2\">\n";

	echo "<input type=text name=\"in[icon$i]\" size=\"25\" maxlength=\"50\" value=\"$db[$icons]\"></td>\n";

	echo "<td class=\"classadd2\" colspan=2>\n";

	echo "<input type=text name=\"in[$iconsalt]\" size=\"22\" maxlength=\"50\" value=\"$db[$iconsalt]\"></td>\n";

	echo "<td class=\"classadd2\">\n";

	echo selectyesno("in[enableicon$i]","$dbc[value2]")."</td>\n";

	echo "</tr>\n";

    }



    echo "<tr>\n";

    echo "<td colspan=7><hr></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Passphrase : </div></td>\n";

    echo "<td class=\"classadd2\" colspan=5><input type=\"text\" name=\"in[passphrase]\" value=\"$db[passphrase]\" size=\"25\" maxlength=\"32\"></td>\n";

    echo "</tr>\n";



    if ($sales_option) {

        echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\">Sales : </div></td>\n";

	echo "<td class=\"classadd2\">\n";

        echo "<input type=text name=\"in[sales]\" size=\"1\" maxlength=\"1\" value=\"$db[sales]\">\n";

	echo "&nbsp;<small>SalesOptions: Pay for 1=read&write, 2=write, 3=answer<br></small></td>\n";

	echo "</tr>\n";



        echo "<tr>\n";

	echo "<td class=\"classadd1\"><div class=\"maininputleft\">SalesBase : </div></td>\n";

	echo "<td class=\"classadd2\">\n";

        echo "<input type=text name=\"in[salesbase]\" size=\"1\" maxlength=\"1\" value=\"$db[salesbase]\">\n";

	echo "&nbsp;<small>SalesBaseOptions: 1=timebased, 2=eventbased<br></small></td>\n";

	echo "</tr>\n";

    }



    echo "<tr>\n";

    echo "<td class=\"classadd2\"></td>\n";

    echo "<td class=\"classadd2\"><br><input type=\"submit\" value=\"Save\"></td>\n";

    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";

    mysql_close();



}



if ($action == "save_edit_cat") {

    mysql_connect($server, $db_user, $db_pass);



    // update configuration

    $sql="UPDATE config SET value2='$in[enablesfield]' WHERE type='cat' AND name='sfield' AND value='$in[id]'";

    mysql_db_query($database, $sql) or die(mysql_error().$sql);



    for($i = 1; $i<= 20; $i++) {

	$enable=$in["enablefield".$i];

	$select=$in["selectfield".$i];

	$option=$in["optionfield".$i];

	$unit=$in["unitfield".$i];

	$minmax=$in["minmaxfield".$i];

	$sql="UPDATE config SET value2='$enable',value3='$select',value4='$option',value5='$unit',value6='$minmax' WHERE type='cat' AND name='field$i' AND value='$in[id]'";

	mysql_db_query($database, $sql) or die(mysql_error().$sql);

    }



    for($i = 1; $i<= 10; $i++) {

	$enable=$in["enableicon".$i];

	$sql="UPDATE config SET value2='$enable' WHERE type='cat' AND name='icon$i' AND value='$in[id]'";

	mysql_db_query($database, $sql) or die(mysql_error().$sql);

    }



    // update cat

    $sql="UPDATE adcat SET id='$in[id]',

	 name='$in[name]',

	 description='$in[description]',

	 longdescription='$in[longdescription]',

	 ads='$in[ads]',

	 picture='$in[picture]',

	 sfield='$in[sfield]',

	 field1='$in[field1]',

	 field2='$in[field2]',

	 field3='$in[field3]',

	 field4='$in[field4]',

	 field5='$in[field5]',

	 field6='$in[field6]',

	 field7='$in[field7]',

	 field8='$in[field8]',

	 field9='$in[field9]',

	 field10='$in[field10]',

	 field11='$in[field11]',

	 field12='$in[field12]',

	 field13='$in[field13]',

	 field14='$in[field14]',

	 field15='$in[field15]',

	 field16='$in[field16]',

	 field17='$in[field17]',

	 field18='$in[field18]',

	 field19='$in[field19]',

	 field20='$in[field20]',

	 icon1='$in[icon1]',

	 icon2='$in[icon2]',

	 icon3='$in[icon3]',

	 icon4='$in[icon4]',

	 icon5='$in[icon5]',

	 icon6='$in[icon6]',

	 icon7='$in[icon7]',

	 icon8='$in[icon8]',

	 icon9='$in[icon9]',

	 icon10='$in[icon10]',

	 icon1alt='$in[icon1alt]',

	 icon2alt='$in[icon2alt]',

	 icon3alt='$in[icon3alt]',

	 icon4alt='$in[icon4alt]',

	 icon5alt='$in[icon5alt]',

	 icon6alt='$in[icon6alt]',

	 icon7alt='$in[icon7alt]',

	 icon8alt='$in[icon8alt]',

	 icon9alt='$in[icon9alt]',

	 icon10alt='$in[icon10alt]',

	 passphrase='$in[passphrase]'";

    if ($sales_option) {$sql .=" ,sales='$in[sales]' ,salesbase='$in[salesbase]'";}

    $sql .=" WHERE id='$in[id]'";

    mysql_db_query($database, $sql) or die(mysql_error());





    mysql_close();

    $redirect="$PHP_SELF?action=cats";

}



if ($action == "save_new_cat") {

    mysql_connect($server, $db_user, $db_pass);

    $sql="INSERT INTO adcat (name,description,longdescription,ads,picture,sfield,

			    field1,field2,field3,field4,field5,field6,field7,field8,field9,field10,

			    field11,field12,field13,field14,field15,field16,field17,field18,field19,field20,

			    icon1,icon2,icon3,icon4,icon5,icon6,icon7,icon8,icon9,icon10,

			    icon1alt,icon2alt,icon3alt,icon4alt,icon5alt,icon6alt,icon7alt,icon8alt,icon9alt,icon10alt,passphrase";

    if ($sales_option) {$sql .=",sales,salesbase";}

    $sql.=") VALUES ('$in[name]','$in[description]','$in[longdescription]','$in[ads]','$in[picture]','$in[sfield]',

			    '$in[field1]','$in[field2]','$in[field3]','$in[field4]','$in[field5]','$in[field6]','$in[field7]','$in[field8]','$in[field9]','$in[field10]',

			    '$in[field11]','$in[field12]','$in[field13]','$in[field14]','$in[field15]','$in[field16]','$in[field17]','$in[field18]','$in[field19]','$in[field20]',

			    '$in[icon1]','$in[icon2]','$in[icon3]','$in[icon4]','$in[icon5]','$in[icon6]','$in[icon7]','$in[icon8]','$in[icon9]','$in[icon10]',

			    '$in[icon1alt]','$in[icon2alt]','$in[icon3alt]','$in[icon4alt]','$in[icon5alt]','$in[icon6alt]','$in[icon7alt]','$in[icon8alt]','$in[icon9alt]','$in[icon10alt]','$in[passphrase]'";

    if ($sales_option) {$sql .=",'$in[sales]','$in[salesbase]'";}

    $sql .=")";

    mysql_db_query($database, $sql) or die(mysql_error());



    // read the new cat-ID

    $result = mysql_db_query($database, "SELECT * FROM adcat WHERE name='$in[name]' AND description='$in[description]'") or die(mysql_error());

    $db=mysql_fetch_array($result);



    // insert configuration

    $sql="INSERT INTO config (type,name,value,value2) VALUES ('cat','sfield','$db[id]','$in[enablesfield]')";

    mysql_db_query($database, $sql) or die(mysql_error().$sql);



    for($i = 1; $i<= 20; $i++) {

    $enable=$in["enablefield".$i];

    $select=$in["selectfield".$i];

    $option=$in["optionfield".$i];

    $unit=$in["unitfield".$i];

    $minmax=$in["minmaxfield".$i];

    mysql_db_query($database,"INSERT INTO config (type,name,value,value2,value3,value4,value5,value6) VALUES

				('cat','field$i','$db[id]','$enable','$select','$option','$unit','$minmax')") or die(mysql_error());

    }



    for($i = 1; $i<= 10; $i++) {

    $enable=$in["enableicon".$i];

    mysql_db_query($database,"INSERT INTO config (type,name,value,value2) VALUES

				('cat','icon$i','$db[id]','$enable')") or die(mysql_error());

    }



    mysql_close();

    $redirect="$PHP_SELF?action=cats";

}



if ($action == "delete_cat") {

    mysql_connect($server, $db_user, $db_pass);

    mysql_db_query($database, "DELETE FROM adcat WHERE id='$value'") or die(mysql_error());

    mysql_db_query($database, "DELETE FROM config WHERE type='cat' AND value='$value'") or die(mysql_error());

    mysql_close();

    $redirect="$PHP_SELF?action=cats";

}



if ($action == "duplicate_cat") {

    mysql_connect($server, $db_user, $db_pass);

    $query=mysql_db_query($database, "SELECT * FROM adcat WHERE id='$value'") or die(mysql_error());

    $db=mysql_fetch_array($query);

    mysql_db_query($database, "INSERT INTO adcat

				(name, description, longdescription, ads, human, picture, sfield,

				field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, field11,

				field12, field13, field14, field15, field16, field17, field18, field19, field20,

				icon1, icon1alt, icon2, icon2alt, icon3, icon3alt, icon4, icon4alt, icon5, icon5alt,

				icon6, icon6alt, icon7, icon7alt, icon8, icon8alt, icon9, icon9alt, icon10, icon10alt,

				passphrase) VALUES

				('$db[name]', '$db[description]', '$db[longdescription]', '0', '$db[human]', '$db[picture]',

				'$db[sfield]', '$db[field1]', '$db[field2]', '$db[field3]', '$db[field4]', '$db[field5]', '$db[field6]', '$db[field7]', '$db[field8]', '$db[field9]', '$db[field10]',

				'$db[field11]', '$db[field12]', '$db[field13]', '$db[field14]', '$db[field15]', '$db[field16]', '$db[field17]', '$db[field18]', '$db[field19]', '$db[field20]',

				'$db[icon1]', '$db[icon1alt]', '$db[icon2]', '$db[icon2alt]', '$db[icon3]', '$db[icon3alt]', '$db[icon4]', '$db[icon4alt]', '$db[icon5]', '$db[icon5alt]', '$db[icon6]',

				'$db[icon6alt]', '$db[icon7]', '$db[icon7alt]', '$db[icon8]', '$db[icon8alt]', '$db[icon9]', '$db[icon9alt]', '$db[icon10]', '$db[icon10alt]',

				'$db[passphrase]')") or die(mysql_error());

    // read the new cat-ID

    $newid=mysql_insert_id();

    // insert rest of config

    $query=mysql_db_query($database, "SELECT * FROM config WHERE type='cat' AND value='$value'") or die(mysql_error());

    while ($db=mysql_fetch_array($query)) {

	mysql_db_query($database,"INSERT INTO config (type,name,value,value2,value3,value4,value5,value6)

			    VALUES ('$db[type]','$db[name]','$newid','$db[value2]','$db[value3]','$db[value4]',

			    '$db[value5]','$db[value6]')") or die(mysql_error());



    }



    mysql_close();

    $redirect="$PHP_SELF?action=cats";

}





if ($action == "subcats") {

    echo "<b>Subcategories</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from adsubcat order by id") or die("Database Query Error");



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\">\n";

    echo "      <b>ID</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"45\">\n";

    echo "      <b>CatID</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"150\">\n";

    echo "      <b>Name</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"150\">\n";

    echo "      <b>Description</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"30\">\n";

    echo "      <b>Ads</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"30\">\n";

    echo "      <b>Pic</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

    echo "	 <a href=\"$PHP_SELF?action=new_subcat\">New</a>\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

        echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "     <div class=\"smallcenter\">$db[id]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"45\">\n";

	echo "     <div class=\"smallcenter\">$db[catid]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"150\">\n";

	echo "     <div class=\"smallcenter\">$db[name]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"150\">\n";

	echo "     <div class=\"smallcenter\">$db[description]&nbsp;</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"30\">\n";

	echo "     <div class=\"smallcenter\">$db[ads]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"30\">\n";

	if ($db[picture]) {

	    echo "     <div class=\"smallcenter\"><img src=\"$url_to_start/$db[picture]\"></div>";

	}

        echo "    </td>\n";

        echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

	echo "	   <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=edit_subcat&value=$db[id]\">Edit</a>\n";

	echo "	   $menusep<a href=\"$PHP_SELF?action=delete_subcat&value=$db[id]&value2=$db[catid]&value3=$db[ads]\" onclick=\"return jsconfirm()\">Delete</a><br>\n";

	echo "	   <a href=\"$PHP_SELF?action=duplicate_subcat&value=$db[id]\" onclick=\"return jsconfirm()\">Duplicate</a></div>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }



    mysql_close();

    echo "<br>$count Subcategories in [subcats]-Table.<br><br>\n";

}





if ($action == "edit_subcat" || $action == "new_subcat") {

    echo "<b>Edit Subcategories</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    if ($action == "edit_subcat") {

        $result = mysql_db_query($database, "select * from adsubcat where id='$value'") or die("Database Query Error");

	$db = mysql_fetch_array($result);

    }

    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    if ($action == "edit_subcat") {

	echo "<input type=\"hidden\" name=\"action\" value=\"save_edit_subcat\">\n";

        echo "<input type=\"hidden\" name=\"in[id]\" value=\"$value\">\n";

    } else {

        echo "<input type=\"hidden\" name=\"action\" value=\"save_new_subcat\">\n";

	$db[ads]=0;

	$db[id]="AUTO";

    }



    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">ID : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text readonly name=\"in[id]\" size=\"25\" maxlength=\"50\" value=\"$db[id]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Cat ID : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[catid]\" size=\"25\" maxlength=\"50\" value=\"$db[catid]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Name : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[name]\" size=\"25\" maxlength=\"50\" value=\"$db[name]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Description : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[description]\" size=\"25\" maxlength=\"50\" value=\"$db[description]\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Ads (read only): </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text readonly name=\"in[ads]\" size=\"25\" maxlength=\"50\" value=\"$db[ads]\"></td>\n";

    echo "</tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Picture : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"in[picture]\" size=\"25\" maxlength=\"50\" value=\"$db[picture]\"></td>\n";

    echo "</tr>\n";



    echo "<tr>\n";

    echo "<td class=\"classadd2\"></td>\n";

    echo "<td class=\"classadd2\"><br><input type=submit value=Submit></td>\n";

    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";

    mysql_close();



}



if ($action == "save_edit_subcat") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "UPDATE adsubcat SET id='$in[id]',

							     catid='$in[catid]',

							     name='$in[name]',

							     description='$in[description]',

							     ads='$in[ads]',picture='$in[picture]'

							     WHERE id='$in[id]'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=subcats";

}



if ($action == "save_new_subcat") {

    mysql_connect($server, $db_user, $db_pass);

    mysql_db_query($database, "INSERT INTO adsubcat (catid,name,description,ads,picture)

			    VALUES('$in[catid]','$in[name]','$in[description]','$in[ads]','$in[picture]')")

			    or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=subcats";

}



if ($action == "delete_subcat") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "UPDATE adcat SET ads=ads-$value3 WHERE id='$value2'") or die("Database Query Error");

    $result = mysql_db_query($database, "DELETE FROM adsubcat WHERE id='$value'") or die("Database Query Error");



    mysql_close();

    $redirect="$PHP_SELF?action=subcats";

}



if ($action == "duplicate_subcat") {

    mysql_connect($server, $db_user, $db_pass);

    $query=mysql_db_query($database, "SELECT * FROM adsubcat WHERE id='$value'") or die(mysql_error());

    $db=mysql_fetch_array($query);

    mysql_db_query($database, "INSERT INTO adsubcat (catid,name,description,ads,picture)

			    VALUES('$db[catid]','$db[name]','$db[description]','0','$db[picture]')") or die(mysql_error());

    mysql_close();

    $redirect="$PHP_SELF?action=subcats";

}





if ($action == "resend_delayed_confirms") {

    echo "<b>Resend Confirmations</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from confirm where date_add(date, interval 1 day) < now()") or die("Database Query Error");

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    while ($db = mysql_fetch_array($result)) {

	$confirmurl = ("$url_to_start" . "/confirm.php?hash=" . "$db[mdhash]" . "&username=" . "$db[username]");

	$mailto = "$db[email]";

	$subject = "$bazar_name Registration confirmation";

	$message = "Dear $db[username],\n\nPlesae click the following URL, to confirm your registration:\n\n$confirmurl\n\nThank you\nYour Webmaster";

	$from = "From: $admin_email\r\nReply-to: $admin_email\r\n";

	@mail($mailto, $subject, $message, $from);

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "Resent Confirmation-Mail to User: <b>[$db[username]]$db[email]</b><br>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

	$count++;

    }

    echo "</table>\n";

    mysql_close();

    echo "<br>$count Confirmation-Mails sent.<br><br>\n";

}





if ($action == "confirm_flush") {

    echo "<b>Flush Confirmations</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from confirm where date_add(date, interval 2 day) < now()") or die("Database Query Error");

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    while ($db = mysql_fetch_array($result)) {

	$deletename = $db[username];

	$query = mysql_db_query($database, "delete from confirm where username='$deletename'") or die("Database Delete Error");

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "Unconfirmed User <b>$deletename</b> deleted<br>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

	$count++;

    }

    echo "</table>\n";

    echo "<br>$count unconfirmed Members deleted from [confirm]-Table.<br>\n";



    $count=0;

    $result = mysql_db_query($database, "select * from confirm_email where date_add(date, interval 2 day) < now()") or die("Database Query Error");

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    while ($db = mysql_fetch_array($result)) {

	$deletename = $db[email];

	$query = mysql_db_query($database, "delete from confirm_email where email='$deletename'") or die("Database Delete Error");

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "Unconfirmed E-Mail <b>$deletename</b> deleted<br>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

	$count++;

    }

    mysql_close();

    echo "</table>\n";

    echo "<br>$count unconfirmed E-Mails deleted from [confirm_email]-Table.<br><br>\n";



}





if ($action == "deleted_user_flush") {

    echo "<b>Flush deleted Members</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from login where password like 'deleted_%'") or die("Database Query Error");

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    while ($dbl = mysql_fetch_array($result)) {

      $deletename = $dbl[username];

      $uid = $dbl[id];

      $query = mysql_db_query($database, "delete from userdata where username='$deletename'") or die("Database Delete Error");

      $query2 = mysql_db_query($database, "delete from login where username='$deletename'") or die("Database Delete Error");



      // delete all Ads from this User



      $result2 = mysql_db_query($database, "SELECT * FROM ads WHERE userid=$uid");

      while ($db = mysql_fetch_array($result2)) {





	// Subtract Counter in userdata-DB

	mysql_db_query($database,"update userdata set ads=ads-1,lastaddate=now() where id='$db[userid]'")

	or died("Database Query Error");



	// Subtract Counter in adcat-DB

	mysql_db_query($database,"update adcat set ads=ads-1 where id='$db[catid]'")

	or died("Database Query Error");



	// Subtract Counter in adsubcat-DB

	mysql_db_query($database,"update adsubcat set ads=ads-1 where id='$db[subcatid]'")

	or died("Database Query Error");



        // Delete Pictures if any ...

        if (!$pic_database && $db[picture] && is_file("$bazar_dir/$pic_path/$db[picture]")) {

            suppr("$bazar_dir/$pic_path/$db[picture]");

        } elseif ($db[picture]) {

    	    mysql_db_query($database,"delete from pictures where picture_name = '$db[picture]'") or died("Database Query Error");

	}

	if (!$pic_database && $db[_picture] && is_file("$bazar_dir/$pic_path/$db[_picture]")) {

	    suppr("$bazar_dir/$pic_path/$db[_picture]");

	} elseif ($db[_picture]) {

	    mysql_db_query($database,"delete from pictures where picture_name = '$db[_picture]'") or died("Database Query Error");

	}



        if (!$pic_database && $db[picture2] && is_file("$bazar_dir/$pic_path/$db[picture2]")) {

            suppr("$bazar_dir/$pic_path/$db[picture2]");

        } elseif ($db[picture2]) {

    	    mysql_db_query($database,"delete from pictures where picture_name = '$db[picture2]'") or died("Database Query Error");

	}

	if (!$pic_database && $db[_picture2] && is_file("$bazar_dir/$pic_path/$db[_picture2]")) {

	    suppr("$bazar_dir/$pic_path/$db[_picture2]");

	} elseif ($db[_picture2]) {

	    mysql_db_query($database,"delete from pictures where picture_name = '$db[_picture2]'") or died("Database Query Error");

	}



        if (!$pic_database && $db[picture3] && is_file("$bazar_dir/$pic_path/$db[picture3]")) {

            suppr("$bazar_dir/$pic_path/$db[picture3]");

        } elseif ($db[picture3]) {

    	    mysql_db_query($database,"delete from pictures where picture_name = '$db[picture3]'") or died("Database Query Error");

	}

	if (!$pic_database && $db[_picture3] && is_file("$bazar_dir/$pic_path/$db[_picture3]")) {

	    suppr("$bazar_dir/$pic_path/$db[_picture3]");

	} elseif ($db[_picture3]) {

	    mysql_db_query($database,"delete from pictures where picture_name = '$db[_picture3]'") or died("Database Query Error");

	}





	// Delete Entry from favorits-DB

	mysql_db_query($database,"delete from favorits where adid = '$db[id]'")

	or died("Database Query Error");



	// Delete Entry from ads-DB

	mysql_db_query($database,"delete from ads where id = '$db[id]'")

	or died("Database Query Error");



      }



      echo "   <tr>\n";

      echo "    <td class=\"class3\">\n";

      echo "User <b>$deletename</b> deleted<br>\n";

      echo "    </td>\n";

      echo "  </tr>\n";

      $count++;

    }

    mysql_close();

    echo "</table>\n";

    echo "<br>$count Members deleted from [login] and [userdata]-Table.<br><br>\n";

}





if ($action == "") {



    if (strstr (getenv('HTTP_USER_AGENT'), 'Mozilla/4.7')) {       // NetScape 4.7x Detection

	echo"<Script language=Javascript>alert('This AdminPanel is NOT compatible with Netscape 4.xx - PLEASE UPDATE YOUR BROWSER');</Script>";

    }



    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "select id from userdata") or die("Database Query Error");

    $users=mysql_num_rows($result);

    $result = mysql_db_query($database, "select id from ads") or die("Database Query Error");

    $ads=mysql_num_rows($result);

    $result = mysql_db_query($database, "select id from guestbook") or die("Database Query Error");

    $gbentrys=mysql_num_rows($result);

    $result = mysql_db_query($database, "select id from ads WHERE publicview='0'") or die("Database Query Error");

    $adapprovals=mysql_num_rows($result);

    $result = mysql_db_query($database, "select id from ads WHERE TO_DAYS(addate)<TO_DAYS(now())-(durationdays+timeoutdays)") or die("Database Query Error");

    $timeoutads=mysql_num_rows($result);

    $result = mysql_db_query($database, "select username from confirm") or die("Database Query Error");

    $confirms=mysql_num_rows($result);

    $result = mysql_db_query($database, "select id from confirm_email") or die("Database Query Error");

    $econfirms=mysql_num_rows($result);

    $result = mysql_db_query($database, "select * from banned_ips") or die("Database Query Error");

    $banips=mysql_num_rows($result);

    $result = mysql_db_query($database, "select * from banned_users") or die("Database Query Error");

    $banusers=mysql_num_rows($result);

    $result = mysql_db_query($database, "select * from votes") or die("Database Query Error");

    $votes=mysql_num_rows($result);

    $result = mysql_db_query($database, "select * from badwords") or die("Database Query Error");

    $badwords=mysql_num_rows($result);

    $result = mysql_db_query($database, "select * from smilies") or die("Database Query Error");

    $smilies=mysql_num_rows($result);

    $result = mysql_db_query($database, "select userid from logging") or die("Database Query Error");

    $logging =mysql_num_rows($result);



    mysql_close();

    echo "     <b>Site Statistics</b><br><br>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "     <div align=\"right\"><b>Value&nbsp;&nbsp;</b></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <b>Count<br></b>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Registered Members : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$users<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Classified Ads : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$ads<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Guestbook Entries : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$gbentrys<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Timeout Ads : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$timeoutads<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Waiting Ad approvals : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$adapprovals<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Waiting Confirms : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$confirms<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Waiting EMail Confirms : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$econfirms<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Banned Members : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$banusers<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Banned IPs : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$banips<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Votes : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$votes<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Badwords : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$badwords<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Smilies : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$smilies<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">Log Events : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$logging<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo "    <br><b>Code Status</b><br><br>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "     <div align=\"right\"><b>Code&nbsp;&nbsp;</b></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\"><b>Ver.<br></b></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">phpBazar : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$bazar_version<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">phpPicLib : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$pl_version<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" align=\"center\">\n";

    echo "      <div class=\"smallright\">phpSales : </div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"center\" width=\"50\">\n";

    echo "      <div class=\"smallcenter\">$sales_version<br></div>\n";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"200\">&nbsp;\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo " </table>\n";



    echo "<div class=\"smallleft\"><br>HINT: For Administration of Ads- and Guestbook-Entries:

	       <br>Login to phpBazar with Admin- or Moderator-Level & use the Administration-Functions! ->

	       <img src=\"$url_to_start/$image_dir/icons/trash.gif\">

	       <img src=\"$url_to_start/$image_dir/icons/reply.gif\"></div>\n";





}

if ($action == "badwords") {

    echo "<b>Bad Words</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from badwords") or die("Database Query Error");

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\">\n";

    echo "      <b>Bad Word</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

    echo "	<a href=\"$PHP_SELF?action=new_badword\">New</a>\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

        echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "     <div class=\"smallcenter\">$db[badword]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

	echo "	   <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=edit_badword&value=$db[badword]\">Edit</a>\n";

	echo "	   $menusep<a href=\"$PHP_SELF?action=delete_badword&value=$db[badword]\" onclick=\"return jsconfirm()\">Delete</a></div>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }

    mysql_close();

    echo "<br>$count Bad Words in [badwords]-Table.<br><br>\n";

}



if ($action == "admove") {

    echo "<b>Move Ad to another Sub-Category</b><br><br>\n";

    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    echo "<input type=\"hidden\" name=\"action\" value=\"admovenow\">\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Ad-Number : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"adid\" size=\"10\" maxlength=\"14\" value=\"$value\"></td>\n";

    echo "</tr><tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">move to SubCat-ID : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"subcatid\" size=\"10\" maxlength=\"14\" value=\"$value2\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd2\"></td>\n";

    echo "<td class=\"classadd2\"><br><input type=submit value=MoveAd onclick=\"return jsconfirm()\"></td>\n";

    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";

}



if ($action == "admovenow") {

    echo "<b>Move Ad to another Sub-Category</b><br><br>\n";

    if ($adid>0 && $subcatid>0) {

        mysql_connect($server, $db_user, $db_pass);



	$result = mysql_db_query($database, "SELECT * FROM ads WHERE id='$adid'") or die("Database Query Error");

	$db = mysql_fetch_array($result);

	$adcatid=$db[catid];

	$adsubcatid=$db[subcatid];

	$result2 = mysql_db_query($database, "SELECT * FROM adcat WHERE id='$adcatid'") or die("Database Query Error");

	$result3 = mysql_db_query($database, "SELECT * FROM adsubcat WHERE id='$adsubcatid'") or die("Database Query Error");

	$result4 = mysql_db_query($database, "SELECT * FROM adsubcat WHERE id='$subcatid'") or die("Database Query Error");



	if ($db && $result2 && $result3 && $result4) {

	    $db4 = mysql_fetch_array($result4);

	    $adcatid2=$db4[catid];

	    $adsubcatid2=$db4[id];

	    if ($adsubcatid2<>$adsubcatid) {



	      // Move Ad in ads-DB

	      mysql_db_query($database,"update ads set catid='$adcatid2',subcatid='$adsubcatid2' where id='$adid'") or die("Database Query Error - ad");



	      if ($db[publicview]) {



	        // Subtract Counter in adcat-DB

		mysql_db_query($database,"update adcat set ads=ads-1 where id='$adcatid'") or die("Database Query Error - adcat");



		// Subtract Counter in adsubcat-DB

		mysql_db_query($database,"update adsubcat set ads=ads-1 where id='$adsubcatid'") or die("Database Query Error - adsubcat");



	        // Add Counter in adcat-DB

		mysql_db_query($database,"update adcat set ads=ads+1 where id='$adcatid2'") or die("Database Query Error - adcat");



		// Add Counter in adsubcat-DB

		mysql_db_query($database,"update adsubcat set ads=ads+1 where id='$adsubcatid2'") or die("Database Query Error - adsubcat");



	      }



	      echo "DONE - Ad:$adid (Cat:$adcatid SubCat:$adsubcatid) moved to Cat:$adcatid2 SubCat:$adsubcatid2";



	    } else {

    		echo "ERROR: cann't move, same SubCat !!!";

	    }

	} else {

    	    echo "ERROR: Record NOT found !!!";

	}

	mysql_close();

    } else {

	echo "ERROR: Empty or wrong Value entered !!!";

    }



}



if ($action == "edit_badword" || $action == "new_badword") {

    echo "<b>Edit Bad Word</b><br><br>\n";

    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    if ($action == "edit_badword") {

	echo "<input type=\"hidden\" name=\"action\" value=\"save_edit_badword\">\n";

    } else {

        echo "<input type=\"hidden\" name=\"action\" value=\"save_new_badword\">\n";

    }

    echo "<input type=\"hidden\" name=\"value\" value=\"$value\">\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Bad Word : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"newvalue\" size=\"25\" maxlength=\"50\" value=\"$value\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd2\"></td>\n";

    echo "<td class=\"classadd2\"><br><input type=submit value=Submit></td>\n";

    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";



}



if ($action == "save_edit_badword") {



    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "UPDATE badwords SET badword='$newvalue' WHERE badword='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=badwords";



}



if ($action == "save_new_badword") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "INSERT INTO badwords (badword) VALUES('$newvalue')") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=badwords";

}



if ($action == "delete_badword") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "DELETE FROM badwords WHERE badword='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=badwords";

}







if ($action == "banned_ips") {

    echo "<b>Banned IP-Addresses</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from banned_ips") or die("Database Query Error");

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\">\n";

    echo "      <b>IP-Address</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

    echo "	<a href=\"$PHP_SELF?action=new_banned_ip\">New</a>\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";

    while ($db = mysql_fetch_array($result)) {

        echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "     <div class=\"smallcenter\">$db[0]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

	echo "	   <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=edit_banned_ip&value=$db[ip]\">Edit</a>\n";

	echo "	   $menusep<a href=\"$PHP_SELF?action=delete_banned_ip&value=$db[ip]\" onclick=\"return jsconfirm()\">Delete</a></div>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }

    mysql_close();

    echo "<br>$count Banned IPs in [banned_ips]-Table.<br><br>\n";

}



if ($action == "nullified") {
    echo "<b>Nullified Info</b><br><br>\n";
    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";
    echo "   <tr>\n";
    echo "    <td class=\"class3\">\n";
    echo "      <b>Program Name</b>";
    echo "    </td>\n";
    echo "    <td class=\"class3\" align=\"left\" width=\"50%\">\n";
    echo "      <b>phpBazar</b>";
    echo "    </td>\n";
    echo "  </tr>\n";

    echo "   <tr>\n";
    echo "    <td class=\"class3\">\n";
    echo "      <b>Release Version</b>";
    echo "    </td>\n";
    echo "    <td class=\"class3\" align=\"left\" width=\"50%\">\n";
    echo "      <b>$bazar_version</b>";
    echo "    </td>\n";
    echo "  </tr>\n";

    echo "   <tr>\n";
    echo "    <td class=\"class3\">\n";
    echo "      <b>Program Author</b>";
    echo "    </td>\n";
    echo "    <td class=\"class3\" align=\"left\" width=\"50%\">\n";
    echo "      <b>Erich Fuchs</b>";
    echo "    </td>\n";
    echo "  </tr>\n";

    echo "   <tr>\n";
    echo "    <td class=\"class3\">\n";
    echo "      <b>Supplied by</b>";
    echo "    </td>\n";
    echo "    <td class=\"class3\" align=\"left\" width=\"50%\">\n";
    echo "      <b>Reseting</b>";
    echo "    </td>\n";
    echo "  </tr>\n";

    echo "   <tr>\n";
    echo "    <td class=\"class3\">\n";
    echo "      <b>Nullified by</b>";
    echo "    </td>\n";
    echo "    <td class=\"class3\" align=\"left\" width=\"50%\">\n";
    echo "      <b>CyKuH [WTN]</b>";
    echo "    </td>\n";
    echo "  </tr>\n";

    echo "   <tr>\n";
    echo "    <td class=\"class3\">\n";
    echo "      <b>Distribution</b>";
    echo "    </td>\n";
    echo "    <td class=\"class3\" align=\"left\" width=\"50%\">\n";
    echo "      <b>via ForumRU, WebForum and associated file dumps</b>";
    echo "    </td>\n";
    echo "  </tr>\n";

    echo "   <tr>\n";
    echo "    <td class=\"class3\">\n";
    echo "      <b>Note</b>";
    echo "    </td>\n";
    echo "    <td class=\"class3\" align=\"left\" width=\"50%\">\n";
    echo "      <b>This software contained several callback features!</b>";
    echo "    </td>\n";
    echo "  </tr>\n";

    echo "</table>\n";
}

if ($action == "edit_banned_ip" || $action == "new_banned_ip") {

    echo "<b>Edit Banned IP-Address</b><br><br>\n";

    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    if ($action == "edit_banned_ip") {

	echo "<input type=\"hidden\" name=\"action\" value=\"save_edit_banned_ip\">\n";

    } else {

        echo "<input type=\"hidden\" name=\"action\" value=\"save_new_banned_ip\">\n";

    }

    echo "<input type=\"hidden\" name=\"value\" value=\"$value\">\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">IP : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"newvalue\" size=\"25\" maxlength=\"50\" value=\"$value\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd2\"></td>\n";

    echo "<td class=\"classadd2\"><br><input type=submit value=Submit></td>\n";

    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";



}



if ($action == "save_edit_banned_ip") {



    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "UPDATE banned_ips SET ip='$newvalue' WHERE ip='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=banned_ips";



}



if ($action == "save_new_banned_ip") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "INSERT INTO banned_ips (ip) VALUES('$newvalue')") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=banned_ips";

}



if ($action == "delete_banned_ip") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "DELETE FROM banned_ips WHERE ip='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=banned_ips";

}









if ($action == "banned_users") {

    echo "<b>Banned Members</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from banned_users") or die("Database Query Error");

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\">\n";

    echo "      <b>User-Id</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

    echo "	 <a href=\"$PHP_SELF?action=new_banned_user\">New</a>\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

        echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "     <div class=\"smallcenter\">$db[userid]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

	echo "	   <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=edit_banned_user&value=$db[userid]\">Edit</a>\n";

	echo "	   $menusep<a href=\"$PHP_SELF?action=delete_banned_user&value=$db[userid]\" onclick=\"return jsconfirm()\">Delete</a></div>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }

    mysql_close();

    echo "<br>$count Banned Members in [banned_users]-Table.<br><br>\n";

}



if ($action == "edit_banned_user" || $action == "new_banned_user") {

    echo "<b>Edit Banned User</b><br><br>\n";

    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    if ($action == "edit_banned_user") {

	echo "<input type=\"hidden\" name=\"action\" value=\"save_edit_banned_user\">\n";

    } else {

        echo "<input type=\"hidden\" name=\"action\" value=\"save_new_banned_user\">\n";

    }

    echo "<input type=\"hidden\" name=\"value\" value=\"$value\">\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">User-Id : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"newvalue\" size=\"25\" maxlength=\"50\" value=\"$value\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd2\"></td>\n";

    echo "<td class=\"classadd2\"><br><input type=submit value=Submit></td>\n";

    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";



}



if ($action == "save_edit_banned_user") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "UPDATE banned_users SET userid='$newvalue' WHERE userid='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=banned_users";



}



if ($action == "save_new_banned_user") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "INSERT INTO banned_users (userid) VALUES($newvalue)") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=banned_users";

}



if ($action == "delete_banned_user") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "DELETE FROM banned_users WHERE userid='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=banned_users";

}





if ($action == "votes") {

    echo "<b>Votes</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from votes") or die("Database Query Error");

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\" width=\"50\">\n";

    echo "      <b>ID</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"\">\n";

    echo "      <b>Count</b>";

    echo "    </td>\n";

#    echo "    <td class=\"class3\">\n";

#    echo "      <b>Answer</b>";

#    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

    echo "	 <a href=\"$PHP_SELF?action=new_vote\">New</a>\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

        echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\" width=\"50\">\n";

	echo "     <div class=\"smallcenter\">$db[id]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"\">\n";

	echo "     <div class=\"smallcenter\">$db[votes]</div>";

        echo "    </td>\n";

#        echo "    <td class=\"class3\">\n";

#	echo "     <div class=\"smallcenter\">$db[name]</div>";

#        echo "    </td>\n";

        echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

	echo "	   <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=edit_vote&value=$db[name]&value2=$db[votes]&value3=$db[id]\">Edit</a>\n";

	echo "	   $menusep<a href=\"$PHP_SELF?action=delete_vote&value=$db[id]\" onclick=\"return jsconfirm()\">Delete</a></div>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }

    mysql_close();

    echo "<br>$count Vote Answers in [votes]-Table.<br><br>\n";

}



if ($action == "edit_vote" || $action == "new_vote") {

    echo "<b>Edit Vote</b><br><br>\n";

    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    if ($action == "edit_vote") {

	echo "<input type=\"hidden\" name=\"action\" value=\"save_edit_vote\">\n";

    } else {

        echo "<input type=\"hidden\" name=\"action\" value=\"save_new_vote\">\n";

    }

    echo "<input type=\"hidden\" name=\"value\" value=\"$value\">\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">ID : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"newvalue3\" size=\"25\" maxlength=\"50\" value=\"$value3\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Answer : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"newvalue\" size=\"25\" maxlength=\"50\" value=\"$value\"></td>\n";

    echo "</tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Votes : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"newvalue2\" size=\"25\" maxlength=\"50\" value=\"$value2\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd2\"></td>\n";

    echo "<td class=\"classadd2\"><br><input type=submit value=Submit></td>\n";

    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";



}



if ($action == "save_edit_vote") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "UPDATE votes SET id='$newvalue3', name='$newvalue',votes='$newvalue2' WHERE name='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=votes";



}



if ($action == "save_new_vote") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "INSERT INTO votes (id,name,votes) VALUES('$newvalue3','$newvalue','$newvalue2')") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=votes";

}



if ($action == "delete_vote") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "DELETE FROM votes WHERE id='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=votes";

}







if ($action == "smilies") {

    echo "<b>Smilies</b><br><br>\n";

    mysql_connect($server, $db_user, $db_pass);

    $count=0;

    $result = mysql_db_query($database, "select * from smilies") or die("Database Query Error");

    echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

    echo "   <tr>\n";

    echo "    <td class=\"class3\">\n";

    echo "      <b>Code</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"100\">\n";

    echo "      <b>Image</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"100\">\n";

    echo "      <b>File</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" width=\"100\">\n";

    echo "      <b>Name</b>";

    echo "    </td>\n";

    echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

    echo "	 <a href=\"$PHP_SELF?action=new_smilie\">New</a>\n";

    echo "    </td>\n";

    echo "  </tr>\n";

    echo "</table>\n";



    while ($db = mysql_fetch_array($result)) {

        echo " <table align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\">\n";

	echo "   <tr>\n";

        echo "    <td class=\"class3\">\n";

	echo "     <div class=\"smallcenter\">$db[code]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"100\">\n";

	echo "     <img src=\"$url_to_start/images/smilies/$db[file]\" alt=\"$db[name]\">";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"100\">\n";

	echo "     <div class=\"smallcenter\">$db[file]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" width=\"100\">\n";

	echo "     <div class=\"smallcenter\">$db[name]</div>";

        echo "    </td>\n";

        echo "    <td class=\"class3\" align=\"right\" width=\"90\">\n";

	echo "	   <div class=\"smallcenter\"><a href=\"$PHP_SELF?action=edit_smilie&value=$db[code]&value2=$db[file]&value3=$db[name]\">Edit</a>\n";

	echo "	   $menusep<a href=\"$PHP_SELF?action=delete_smilie&value=$db[code]\" onclick=\"return jsconfirm()\">Delete</a></div>\n";

        echo "    </td>\n";

	echo "  </tr>\n";

        echo "</table>\n";

	$count++;

    }

    mysql_close();

    echo "<br>$count Smilies in [smilies]-Table.<br><br>\n";

}



if ($action == "edit_smilie" || $action == "new_smilie") {

    echo "<b>Edit Smilie</b><br><br>\n";

    echo "<form action=\"$PHP_SELF\" method=\"GET\">\n";

    echo "<table align=\"center\" width=\"100%\">\n";

    if ($action == "edit_smilie") {

	echo "<input type=\"hidden\" name=\"action\" value=\"save_edit_smilie\">\n";

    } else {

        echo "<input type=\"hidden\" name=\"action\" value=\"save_new_smilie\">\n";

    }

    echo "<input type=\"hidden\" name=\"value\" value=\"$value\">\n";

    echo "<tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Code : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"newvalue\" size=\"25\" maxlength=\"50\" value=\"$value\"></td>\n";

    echo "</tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">File : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"newvalue2\" size=\"25\" maxlength=\"50\" value=\"$value2\"></td>\n";

    echo "</tr>\n";

    echo "<td class=\"classadd1\"><div class=\"maininputleft\">Name : </div></td>\n";

    echo "<td class=\"classadd2\">\n";

    echo "<input type=text name=\"newvalue3\" size=\"25\" maxlength=\"50\" value=\"$value3\"></td>\n";

    echo "</tr>\n";

    echo "<tr>\n";

    echo "<td class=\"classadd2\"></td>\n";

    echo "<td class=\"classadd2\"><br><input type=submit value=Submit></td>\n";

    echo "</tr>\n";

    echo "</table>\n";

    echo "</form>\n";



}



if ($action == "save_edit_smilie") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "UPDATE smilies SET code='$newvalue',file='$newvalue2',name='$newvalue3' WHERE code='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=smilies";



}



if ($action == "save_new_smilie") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "INSERT INTO smilies (code,file,name) VALUES('$newvalue','$newvalue2','$newvalue3')") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=smilies";

}



if ($action == "delete_smilie") {

    mysql_connect($server, $db_user, $db_pass);

    $result = mysql_db_query($database, "DELETE FROM smilies WHERE code='$value'") or die("Database Query Error");

    mysql_close();

    $redirect="$PHP_SELF?action=smilies";

}



if ($redirect && $redirect!="newsletter") {echo "<b>DONE</b>";}



#  Footer

#################################################################################################

echo"	      </center>\n";

echo"        </td>\n";

echo"       </tr>\n";

echo"      </table>\n";

echo"    </td>\n";

echo"   </tr>\n";

echo" </table>\n";



echo"   </td></tr>\n";

echo" </table>\n";





#  PLEASE DO NOT REMOVE OR EDIT THIS COPYRIGHT-NOTICE !!! THANKS !!!

#################################################################################################

echo "<p><div class=\"footer\">phpBazar Ver. $bazar_version &copy 2001-".date("Y")." by SmartISoft<!--CyKuH [WTN]--></div>\n";



echo "</body>\n";

echo "</html>\n";



if ($redirect && $redirect=="newsletter") {

    echo "

    	    <form method=\"POST\" name=\"form\" id=\"form\" action=\"$PHP_SELF\">

            <input type=hidden name=\"action\" value=\"send_newsletter\">

            <input type=hidden name=\"counter\" value=\"$count\">

            <input type=hidden name=\"from\" value=\"$from\">

            <input type=hidden name=\"subject\" value=\"".urlencode($subject)."\">

            <input type=hidden name=\"body\" value=\"".urlencode($body)."\">

            <input type=hidden name=\"html\" value=\"$html\">

            <input type=hidden name=\"pic1_file\" value=\"$pic1_file\">

            <input type=hidden name=\"pic2_file\" value=\"$pic2_file\">

            <input type=hidden name=\"pic3_file\" value=\"$pic3_file\">

            <input type=hidden name=\"pic1_name\" value=\"$pic1_name\">

            <input type=hidden name=\"pic2_name\" value=\"$pic2_name\">

            <input type=hidden name=\"pic3_name\" value=\"$pic3_name\">

            <input type=hidden name=\"newslettermembers\" value=\"$newslettermembers\">

            <input type=hidden name=\"allmembers\" value=\"$allmembers\">

	    </form>

	    <script language=javascript>document.form.submit();</script>

    ";

} elseif ($redirect) {

    echo "<script language=javascript>location.href=\"$redirect\";</script>";

}

?>