<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: library.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose           	: Library File
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require("config.php");



if ($HTTP_COOKIE_VARS["Language"] && $show_languages){

    $language_user=$HTTP_COOKIE_VARS["Language"];

}

$language_dir=$languagebase_dir."/".$language_user;

if (!is_file("$language_dir/variables.php")) {$language_dir=$languagebase_dir."/".$language_default;}

require("$language_dir/variables.php");



if (is_file("sales.php")) {

    include ("sales.php");

    require("$language_dir/sales_variables.php");

}



list($USERNAME)=explode(":",$phpBazar);



if (!strstr("$PHP_SELF","frametop.php")) {

    mysql_connect($server, $db_user, $db_pass);

    mysql_db_query($database, "INSERT INTO useronline VALUES ('$timestamp','$ip','$PHP_SELF','$USERNAME')");

    mysql_close();

}





#################################################################################################





#  Functions

#################################################################################################





function died($message) {		//when we die, than with a nice window ;-)

    if(!$message) {

	$message = "There was an unknown error !";

    }



    $errormessage=rawurlencode($message);

    echo "<script language=\"JavaScript\">

    history.back(1);

    var winl = (screen.width - 300) / 2;

    var wint = (screen.height - 150) / 2;

    window.open(\"message.php?msg=$errormessage&msgheader=Error\",\"Error\",\"width=300,height=150,top=\"+wint+\",left=\"+winl+\",resizeable=no\");

    </script>\n";



    exit;

}



function memberfield($signup,$fieldname,$name,$value) {

    global $database,$server,$db_user,$db_pass,$language_dir,$memb_newpublic,$image_dir
,$ad_no,$ad_yes;



    $retval=false;

    mysql_connect($server, $db_user, $db_pass);

    $result=mysql_db_query($database, "SELECT * FROM config WHERE type='member' AND name='$fieldname'") or died(mysql_error());

    $field=mysql_fetch_array($result);

    if ($field[value]=="yes" && ($signup=="0" || ($signup=="1" && $field[value2]=="yes") || ($signup=="2" && $field[value5]=="yes")) ) { // if enabled

      if ($signup=="2") {
  // show Memberdetails

	if ($field[value3]!="checkbox") {

	    if ($fieldname=="homepage") {

                if ($value && substr($value,0,7)!="http://") {$value="http://".$value;}

		$retval="

	         <tr>

        	  <td class=\"gbtable2\"><div class=\"maininputleft\">$name : </div></td>

                  <td class=\"gbtable2\"><div class=\"maininputright\"><a href=\"$value\" target=\"_blank\">$value</a></div></td>

	         </tr>

	         ";



	    } else {

		$retval="

	         <tr>

        	  <td class=\"gbtable2\"><div class=\"maininputleft\">$name : </div></td>

                  <td class=\"gbtable2\"><div class=\"maininputright\">".htmlspecialchars($value)."</div></td>

	         </tr>

	         ";

	    }

	} else {

	    $retval="

	        <tr>

                <td class=\"gbtable2\"><div class=\"maininputleft\">$name : </div></td>
";

            if ($value) {

		$retval.="

        	    <td class=\"gbtable2\"><img src=\"$image_dir/icons/checked2.gif\" border=\"0\" alt=\"$ad_yes\"

        	    onmouseover=\"window.status='$ad_yes'; return true;\"

        	    onmouseout=\"window.status=''; return true;\"></td>\n";

            } else {

	        $retval.="

            	    <td class=\"gbtable2\"><img src=\"$image_dir/icons/signno.gif\" border=\"0\" alt=\"$ad_no\"

            	    onmouseover=\"window.status='$ad_no'; return true;\"

            	    onmouseout=\"window.status=''; return true;\"></td>\n";

	    }

	    $retval.="

	        </tr>";



        }



      } else {
  // signup

	if (($signup=="1" || $signup=="0") && $field[value5]=="yes") {$publicinfo="$memb_newpublic";} else {$publicinfo="";}

	if ($field[value3]=="text" || $field[value3]=="") {

	    $retval="

	     <tr>

              <td><div class=\"maininputleft\">$name $publicinfo: </div></td>

              <td><input type=text name=\"$field[name]\" value=\"".htmlspecialchars($value)."\"$readonly></td>

             </tr>

	     ";

	} elseif ($field[value3]=="url") {

	    if (!$value) {$value="http://";} elseif ($value && substr($value,0,7)!="http://") {$value="http://".$value;}

	    $retval="

	     <tr>

              <td><div class=\"maininputleft\">$name $publicinfo: </div></td>

              <td><input type=text name=\"$field[name]\" value=\"".htmlspecialchars($value)."\"$readonly></td>

             </tr>

	     ";

	} elseif ($field[value3]=="select") {

	    if (!$value) {

	        $optionstr.="<option value=\"\">--------</option>";

	    }

	    if (is_file("./$language_dir/$field[value4]")) {

		$filename = "./$language_dir/$field[value4]";

		$fd = fopen ($filename, "r");

		$optionstr.= str_replace("\"$value\"","\"$value\" SELECTED",fread ($fd, filesize ($filename)));

		fclose ($fd);

	    } else {

		$options=explode("|",$field[value4]);

		for ($i=0; $i<count($options); $i++) {

		    if (!$signup && $options[$i]=="$value") {$selected="SELECTED";} else {$selected="";}

		    $optionstr.="<option value=\"".htmlspecialchars($options[$i])."\" $selected>".htmlspecialchars($options[$i])."</option>";

		}

	    }

	    $retval="

	     <tr>

              <td><div class=\"maininputleft\">$name $publicinfo: </div></td>

              <td><select name=\"$field[name]\">

	      $optionstr

	      </select></td>

             </tr>

	    ";

	} elseif ($field[value3]=="checkbox") {

	    if ($signup && $field[value4]) $checked="CHECKED";

	    if (!$signup && $value) $checked="CHECKED";

	    $retval="

	     <tr>

              <td><div class=\"maininputleft\">$name $publicinfo: </div></td>

              <td><input type=checkbox name=\"$field[name]\" $checked></td>

             </tr>

	    ";

	}

      }

    }

    mysql_close();



    return $retval;

}



function adfield($cat,$fieldname,$name="",$value="") {

    global $database,$server,$db_user,$db_pass,$language_dir;



    $retval=false;

    $result=mysql_db_query($database, "SELECT * FROM config WHERE type='cat' AND name='$fieldname' AND value='$cat'") or died(mysql_error());

    $field=mysql_fetch_array($result);

    if ($field[value2]=="yes") { // if enabled

	if ($field[value3]=="text" || $field[value3]=="url" || $field[value3]=="") {

	    if (!$value) {$value=$field[value4];}

	    $retval="

	     <tr>

              <td><div class=\"maininputleft\">$name : </div></td>

              <td><input type=text name=\"in[$field[name]]\" value=\"".htmlspecialchars($value)."\"> ".htmlspecialchars($field[value5])." </td>

             </tr>

	     ";

	     if ($field[value3]=="url") {$retval.="<!--url-->";}

	} elseif ($field[value3]=="select") {

	    if (!$value) {

	        $optionstr.="<option value=\"\">--------</option>";

	    }

	    if (is_file("./$language_dir/$field[value4]")) {

		$filename = "./$language_dir/$field[value4]";

		$fd = fopen ($filename, "r");

		$optionstr.= str_replace("\"$value\"","\"$value\" SELECTED",fread ($fd, filesize ($filename)));

		fclose ($fd);

	    } else {

		$options=explode("|",$field[value4]);

		for ($i=0; $i<count($options); $i++) {

		    if (!$signup && $options[$i]=="$value") {$selected="SELECTED";} else {$selected="";}

		    $optionstr.="<option value=\"".htmlspecialchars($options[$i])."\" $selected>".htmlspecialchars($options[$i])."</option>";

		}

	    }

	    $retval="

	     <tr>

              <td><div class=\"maininputleft\">$name : </div></td>

              <td><select name=\"in[$field[name]]\">

	      $optionstr

	      </select> $field[value5] </td>

             </tr>

	    ";

	} elseif ($field[value3]=="checkbox") {

	    if ($signup && $field[value4]) $checked="CHECKED";

	    if (!$signup && $value) $checked="CHECKED";

	    $retval="

	     <tr>

              <td><div class=\"maininputleft\">$name : </div></td>

              <td><input type=checkbox name=\"in[$field[name]]\" $checked></td>

             </tr>

	    ";

	}

    }

    return $retval;

}



function adfieldunit($cat,$fieldname) {

    global $database;

    $retval=false;

    $result=mysql_db_query($database, "SELECT * FROM config WHERE type='cat' AND name='$fieldname' AND value='$cat'") or died(mysql_error());

    $field=mysql_fetch_array($result);

    if ($field[value5]) {$retval=$field[value5];}

    return $retval;

}





function searchfield($cat,$fieldname,$name="",$value="",$fieldsize="") {

    global $database,$server,$db_user,$db_pass,$language_dir;



    $retval=false;

    $result=mysql_db_query($database, "SELECT * FROM config WHERE type='cat' AND name='$fieldname' AND value='$cat'") or died(mysql_error());

    $field=mysql_fetch_array($result);

    if ($field[value2]=="yes" && $field[value6]!="no") { // if enabled

	if ($field[value3]=="text" || $field[value3]=="") {

	    $retval="

	     <tr>

              <td><div class=\"maininputleft\">$name : </div></td>

              <td><input type=\"text\" name=\"in[$field[name]]\" value=\"".htmlspecialchars($value)."\" size=\"$fieldsize\"> ".htmlspecialchars($field[value5])." </td>

             </tr>

	     ";

	} elseif ($field[value3]=="select") {

	    if (!$value) {

	        $optionstr="<option value=\"\">--------</option>";

	    }

	    if (is_file("./$language_dir/$field[value4]")) {

		$filename = "./$language_dir/$field[value4]";

		$fd = fopen ($filename, "r");

		$optionstr.= str_replace("\"$value\"","\"$value\" SELECTED",fread ($fd, filesize ($filename)));

		fclose ($fd);

	    } else {

		$options=explode("|",$field[value4]);

		for ($i=0; $i<count($options); $i++) {

	    	    if (!$signup && $options[$i]=="$value") {$selected="SELECTED";} else {$selected="";}

	    	    $optionstr.="<option value=\"".htmlspecialchars($options[$i])."\" $selected>".htmlspecialchars($options[$i])."</option>";

		}

	    }



	    if ($field[value6]=="minmax") {

		$retval="

	    	    <tr>

            	    <td><div class=\"maininputleft\">$name : </div></td>

            	    <td><select name=\"in[$field[name]]\">

	    	    $optionstr

	    	    </select> - <select name=\"in2[$field[name]]\">

	    	    $optionstr

	    	    </select> $field[value5] </td>

            	    </tr>

		    ";

	    } else {

		$retval="

	    	    <tr>

            	    <td><div class=\"maininputleft\">$name : </div></td>

            	    <td><select name=\"in[$field[name]]\">

	    	    $optionstr

	    	    </select> $field[value5] </td>

            	    </tr>

		    ";

	    }

	} elseif ($field[value3]=="checkbox") {

	    if ($signup && $field[value4]) $checked="CHECKED";

	    if (!$signup && $value) $checked="CHECKED";

	    $retval="

	     <tr>

              <td><div class=\"maininputleft\">$name : </div></td>

              <td><input type=checkbox name=\"in[$field[name]]\" $checked></td>

             </tr>

	    ";

	}

    }

    return $retval;

}





function logging($db,$uid,$username,$event,$ext) {

    global $logging_enable,$database,$server,$db_user,$db_pass,$ip,$client,$timestamp,$REMOTE_HOST;



    if ($logging_enable) {



        if ($db) {mysql_connect($server, $db_user, $db_pass);}

	mysql_db_query($database, "INSERT INTO logging (timestamp,userid,username,ip,ipname,client,event,ext)

				VALUES ('$timestamp','$uid','$username','$ip','$REMOTE_HOST','$client','$event','$ext')") or died(mysql_error());

        if ($db) {mysql_close();}



    }



}



function getfile($filename) {

    $fd = fopen ($filename, "r");

    $contents = fread ($fd, filesize ($filename));

    fclose ($fd);

    return $contents;

}



function addslashesnew($string) {

    if (get_magic_quotes_gpc()==1) {

	return $string;

    } else {

	return addslashes($string);

    }

}



function suppr($file) {

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



function dateToTime($date) { 		//input Format 2000-11-24, output Format: Unixtimestamp

    list($y,$m,$d)=explode("-",substr($date,0,10));

    return mktime(0,0,0,$m,$d,$y);

}



function dateToStr($date) { 		//input Format 2000-11-24, output

    global $dateformat;



    if ($date!="0000-00-00 00:00:00") {

        $temp=explode(" ",$date);

	list($y,$m,$d)=explode("-",$temp[0]);

        list($hh,$mm,$ss)=explode(":",$temp[1]);

	return date($dateformat,mktime($hh,$mm,$ss,$m,$d,$y));

    }

}



function str_repeats($input, $mult) { 	//str_repeat() - replacement (backward-comp.)

    $ret = "";

    while ($mult > 0) {

	$ret .= $input;

	$mult --;

    }

    return $ret;

}





function isbanned($userid) {

    global $ip,$database;



    $ban_query = mysql_db_query($database, "SELECT * FROM banned_ips") or died("Database Query Error");



    while ($ips = mysql_fetch_row($ban_query)) {

        if ($ips["0"] == $ip) {

            return 1;

            exit;

        }

    }



    if ($userid) {  // if $userid is empty IGNORE user_banned_check

	$ban_query2 = mysql_db_query($database, "SELECT * FROM banned_users") or died("Database Query Error");



	while ($users = mysql_fetch_row($ban_query2)) {

    	    if ($users["0"] == $userid) {

        	return 1;

        	exit;

    	    }

	}

    }



    return 0;

}



function encode_msg ($msg) {

    global $image_dir,$database;



    if ($msg) {

        $msg = addslashesnew($msg);   // Add SQL compatibilty

        $msg = str_replace("\n", "<BR>", $msg); // Replace newline with <br>

	$result = mysql_db_query($database, "SELECT * FROM smilies") or died("Query Error");

        while ($db = mysql_fetch_array($result)) {

	    $msg = str_replace($db[code], "<img src=".$image_dir."/smilies/".$db[file].">", $msg); // Smilie

        }

    }



    return $msg;

}



function decode_msg ($msg) {

    global $image_dir,$database;



    if ($msg) {

#        $msg = stripslashes($msg);   // Remove SQL compatibilty

        $msg = str_replace("<BR>", "\n", $msg); // Replace newline with <br>

	$result = mysql_db_query($database, "SELECT * FROM smilies") or died("Query Error");

        while ($db = mysql_fetch_array($result)) {

	    $msg = str_replace("<img src=".$image_dir."/smilies/".$db[file].">",$db[code],$msg); // Smilie

        }

    }



    return $msg;

}



function wordwrap_msg($msg, $maxwordlen=40) {  // Looooooong String Break

    $eachword = explode(" " , eregi_replace("<BR>"," ",$msg));          // temp remove <BR>

    for ($i=0; $i<count($eachword); $i++) {

        if (strlen($eachword[$i])>$maxwordlen) {

    	    $msg = eregi_replace($eachword[$i], chunk_split($eachword[$i],$maxwordlen), $msg); // replace long w

        }

    }

    return $msg;

}



function badwords ($msg,$mod) {

    global $database;

    $msg=wordwrap_msg($msg);

    $eachword = explode(" " , eregi_replace("<BR>"," ",$msg));		// temp remove <BR>

	$result = mysql_db_query($database, "SELECT * FROM badwords") or died("Query Error");

	while ($db = mysql_fetch_array($result)) {

	    for ($i=0; $i<count($eachword); $i++) {

		if (is_int(strpos($eachword[$i],$db[badword]))) {

    		    if ($mod) {

	        	$msg = eregi_replace($eachword[$i], "<span class=\"censored\">".$eachword[$i]."</span>", $msg); // Badword

		    } else {

            		$msg = eregi_replace($eachword[$i], str_repeats("*", strlen($eachword[$i])), $msg); // Badword

		    }

		}

	    }

	}

    return $msg;

}



function badwordsmail ($msg) {

    global $database;

    $eachword = explode(" ",$msg);

	$result = mysql_db_query($database, "SELECT * FROM badwords") or died("Query Error");

	while ($db = mysql_fetch_array($result)) {

	    for ($i=0; $i<count($eachword); $i++) {

		if (is_int(strpos($eachword[$i],$db[badword]))) {

    		    $msg = eregi_replace($eachword[$i], str_repeats("*", strlen($eachword[$i])), $msg); // Badword

		}

	    }

	}

    return stripslashes($msg);

}





function strip_array ($in) {  //foreach()-Replacement !!!



    reset($in);

    while ($array=each($in)) {

	$ckey=$array['key'];

	$cvalue=$array['value'];

	$cvalue = str_replace("'", "''", $cvalue);

	$cvalue = stripslashes($cvalue);

	$cvalue = strip_tags($cvalue);

	$out[$ckey] = $cvalue;

    }



    return $out;

}



function open_sales_window($value="") {

	echo "<script language=javascript>

	    window.open(\"sales_buy.php\",\"Buy_Membership\",\"width=780,height=550,top=10,left=10,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\");

	    location.replace('classified.php$value');

	    </script>";

}



function ico_email($value,$align="left") {

    global $sales_lang_noaccess,$ad_sendemail,$image_dir;



    if (!$value) {



	echo "<a href=\"sales_buy.php\"

	    onClick='enterWindow=window.open(\"sales_buy.php\",\"Window\",\"width=780,height=550,top=10,left=10,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'

	    onmouseover=\"window.status='$sales_lang_noaccess'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/email.gif\" border=\"0\" alt=\"$sales_lang_noaccess\" align=\"$align\" vspace=\"2\"
</a>\n";



    } else {



	echo "<a href=\"sendmail.php?$value\"

	    onClick='enterWindow=window.open(\"sendmail.php?$value\",\"EMail\",\"width=600,height=430,top=100,left=100,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'

	    onmouseover=\"window.status='$ad_sendemail'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/email.gif\" border=\"0\" alt=\"$ad_sendemail\" align=\"$align\" vspace=\"2\"></a>\n";



    }

}



function ico_icq($value,$align="left") {

    global $sales_lang_noaccess,$ad_icq,$image_dir;



    if (!$value) {



	echo "<a href=\"sales_buy.php\"

	    onClick='enterWindow=window.open(\"sales_buy.php\",\"Window\",\"width=780,height=550,top=10,left=10,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'

	    onmouseover=\"window.status='$sales_lang_noaccess'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/icq.gif\" border=\"0\" alt=\"$sales_lang_noaccess\" align=\"$align\" vspace=\"2\"
</a>\n";



    } else {



	echo "<a href=\"http://wwp.icq.com/".$value."\" target=\"_blank\"

	    onmouseover=\"window.status='$ad_icq'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/icq.gif\" border=\"0\" alt=\"$ad_icq\" align=\"$align\" vspace=\"2\"></a>\n";



    }

}



function ico_url($value,$align="left") {

    global $sales_lang_noaccess,$ad_gotourl,$image_dir;



    if (!$value) {



	echo "<a href=\"sales_buy.php\"

	    onClick='enterWindow=window.open(\"sales_buy.php\",\"Window\",\"width=780,height=550,top=10,left=10,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'

	    onmouseover=\"window.status='$sales_lang_noaccess'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/home.gif\" border=\"0\" alt=\"$sales_lang_noaccess\" align=\"$align\" vspace=\"2\"
</a>\n";



    } else {



	echo "<a href=\"$value\" target=\"_blank\"

	    onmouseover=\"window.status='$ad_gotourl ($value)'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/home.gif\" border=\"0\" alt=\"$ad_gotourl\" align=\"$align\" vspace=\"2\"></a>\n";



    }

}



function ico_friend($value,$align="left") {

    global $ad_sendlink,$image_dir;



	echo "   <a href=\"sendmail.php?value\"

	    onClick='enterWindow=window.open(\"sendmail.php?$value\",\"EMail\",\"width=600,height=430,top=100,left=100,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'

	    onmouseover=\"window.status='$ad_sendlink'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/lightbulb2.gif\" border=\"0\" alt=\"$ad_sendlink\" align=\"$align\" vspace=\"2\"></a>\n";



}



function ico_print($value,$align="left") {

    global $ad_print,$image_dir;



	echo "   <a href=\"javascript:window.print()\"

	    onClick='javascript:window.print();'

	    onmouseover=\"window.status='$ad_print'; return true;\"

    	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/print.gif\" border=\"0\" alt=\"$ad_print\" align=\"$align\" vspace=\"2\"></a>\n";



}



function ico_favorits($value,$align="left") {

    global $ad_favorits,$image_dir;



	echo "   <a href=\"favorits.php?$value\"

	    onClick='enterWindow=window.open(\"favorits.php?$value\",\"Window\",\"width=400,height=200,top=200,left=200\"); return false'

            onmouseover=\"window.status='$ad_favorits'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

    	    <img src=\"$image_dir/icons/checked.gif\" border=\"0\" alt=\"$ad_favorits\" align=\"$align\" vspace=\"2\"></a>\n";



}



function ico_adrating($value,$align="left") {

    global $ad_rating,$image_dir;



	echo "   <a href=\"adrating.php?$value\"

	    onClick='enterWindow=window.open(\"adrating.php?$value\",\"Window\",\"width=180,height=180,top=200,left=200\"); return false'

	    onmouseover=\"window.status='$ad_rating'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/handup.gif\" border=\"0\" alt=\"$ad_rating\" align=\"$align\" vspace=\"2\"></a>\n";



}



function ico_info($value,$align="left") {

    global $ad_member,$image_dir;



	echo "   <a href=\"members.php?$value\"

	    onmouseover=\"window.status='$ad_member'; return true;\"

	    onmouseout=\"window.status=''; return true;\">

	    <img src=\"$image_dir/icons/info.gif\" border=\"0\" alt=\"$ad_member\" align=\"$align\" vspace=\"2\"></a>\n";



}



#  Classes

#################################################################################################



class authlib {



	function register ($username, $password, $password2, $email, $sex, $acceptterms ,

		    $newsletter, $firstname, $lastname, $address, $zip, $city, $state, $country,

		    $phone, $cellphone, $icq, $homepage, $hobbys, $field1, $field2, $field3,

		    $field4, $field5, $field6, $field7, $field8, $field9, $field10 ) {



  	    global $chat_interface,$chat_enable,$chat_database,$chat_server,$chat_db_user,$chat_db_pass,

		    $forum_interface,$forum_enable,$forum_database,$forum_server,$forum_db_user,$forum_db_pass,

		    $gender,$genders,$admin_email,$reg_notify,$url_to_start,$mail_msg,$server,$db_user,$db_pass,

		    $database,$secret,$error,$no_confirmation,$auto_login;



		if (!$username || !$password || !$password2 || !$email || !$acceptterms) {

			return $error[14];

		} else {

		    if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$", $email)) {

#		    if (!eregi("^([a-z0-9]+)(([a-z0-9._-]+))*[@]([a-z0-9]+)([._-]([a-z0-9]+))*[.]([a-z0-9]){2}([a-z0-9])?$", $email)) {

			return $error[4];

		    }

		    if ($sex == "") {

			return $error[11];

		    }

		    if (strlen($username) < 3) {

		    	return $error[1];

		    }

		    if (strlen($username) > 20) {

		    	return $error[2];

		    }

		    if (!ereg("^[[:alnum:]_-]+$", $username)) {

			return $error[3];

		    }

		    if ($password != $password2) {

			return $error[0];

		    }

		    if (strlen($password) < 3) {

			return $error[5];

		    }

		    if (strlen($password) > 20) {

			return $error[6];

		    }

		    if (!ereg("^[[:alnum:]_-]+$", $password)) {

			return $error[7];

		    }

		    mysql_connect($server, $db_user, $db_pass);

		    mysql_select_db($database);



		    $query = mysql_query("select id from login where username = '$username'");

		    $result = mysql_num_rows($query);



		    if ($result > 0) {

		        mysql_close();

			return $error[12];

		    }



		    $query = mysql_query("select id from userdata where email = '$email'");

		    $result = mysql_num_rows($query);



		    if ($result > 0) {

		    	mysql_close();

			return $error[13];

		    }



		    if ($no_confirmation) {



			$is_success_first = mysql_query("insert into userdata (username, email, sex,

			    newsletter, firstname, lastname, address, zip, city, state, country,

			    phone, cellphone, icq, homepage, hobbys, field1, field2, field3,

			    field4, field5, field6, field7, field8, field9, field10, registered )

			    values ('$username', '$email', '$sex',

			    '$newsletter', '$firstname', '$lastname', '$address', '$zip', '$city', '$state', '$country',

			    '$phone', '$cellphone', '$icq', '$homepage', '$hobbys', '$field1', '$field2', '$field3',

			    '$field4', '$field5', '$field6', '$field7', '$field8', '$field9', '$field10', '$timestamp' )") or died(mysql_error());

			if ($is_success_first) {

			    $is_success_second = mysql_query("insert into login (username, password) values ('$username', '$password')");

			    if ($is_success_second) {



				// only if forum-interface

				if ($is_success_second && $forum_database && $forum_enable && $forum_interface) {

				    include ("$forum_interface");

				}

		                // only if chat-interface

	                        if ($is_success_second && $chat_database && $chat_enable && $chat_interface) {

				    include ("$chat_interface");

				}

			    }

			}



			$mailto = "$email";

			$subject = "$mail_msg[0]";

			$message = "$mail_msg[1]$username\n\n$mail_msg[2]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$sex\n\n$mail_msg[7]";

			$from = "From: $admin_email\r\nReply-to: $admin_email\r\n";



			@mail($mailto, $subject, $message, $from);



			if ($auto_login) {

			    $login=$this->login($username, $password);

			    if ($login!="2") {

				$retval="$error[15]";

			    } else {

				$retval=3;

			    }

			}



		    } else {



			$hash = substr(md5($secret.$username),0,10);

			$is_success = mysql_query("insert into confirm values

			('$hash', '$username', '$password', '$email', '$sex', now(),

			'$newsletter', '$firstname', '$lastname', '$address', '$zip', '$city', '$state',

			'$country', '$phone', '$cellphone', '$icq', '$homepage', '$hobbys', '$field1',

			'$field2', '$field3', '$field4', '$field5', '$field6', '$field7', '$field8',

			'$field9', '$field10')") or died(mysql_error());

			mysql_close();



			if (!$is_success) {

				return $error[16];

			}



			$confirmurl = ("$url_to_start" . "/confirm.php?hash=" . "$hash" . "&nick=" . "$username");

			$aolconfirmurl = ("AOL: <A HREF=\" $url_to_start" . "/confirm.php?hash=" . "$hash" . "&nick=" . "$username \">CLICK HERE</A>");



			$mailto = "$email";

			$subject = "$mail_msg[0]";

			if (strstr($mailto,"aol")) { // For AOL-Users

			    $message = "$mail_msg[1]$username\n\n$mail_msg[2]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$sex\n\n$mail_msg[6]\n\n$aolconfirmurl\n\n$mail_msg[7]";

			} else {

			    $message = "$mail_msg[1]$username\n\n$mail_msg[2]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$sex\n\n$mail_msg[6]\n\n$confirmurl\n\n$mail_msg[7]";

			}

			$from = "From: $admin_email\r\nReply-to: $admin_email\r\n";



			@mail($mailto, $subject, $message, $from);



		    }



		    logging("1","","$username","AUTH: new registration","Password: $password, EMail: $email, Sex: $sex");



		    if ($reg_notify) {

			    $mailto = "$reg_notify";

 			    $subject = "NOTIFY $mail_msg[0]";

			    $message = "$mail_msg[8]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$sex\n";

			    $from = "From: $admin_email\r\nReply-to: $admin_email\r\n";



			    @mail($mailto, $subject, $message, $from);

		    }



		    return 2;

		}

	}



	function login ($username, $password) {

  	    global $server,$db_user,$db_pass,$database,$secret,$error,$login_cookie_time,$cookiepath,$timestamp;

		if (!$username || !$password) {

                        return $error[14];

		}

		else {

			if (!eregi("^[[:alnum:]_-]+$", $username)) {

				return $error[3];

			}

			if (!eregi("^[[:alnum:]_-]+$", $password)) {

				return $error[7];

			}





			mysql_connect($server, $db_user, $db_pass);

			mysql_select_db($database);



			$md5password=md5($password);

			$query = mysql_query("select id from login where username = '$username' and (password = '$password' OR password = '$md5password')");

			$result = mysql_num_rows($query);



			$query2 = mysql_query("select level from userdata where username = '$username'");

			$result2 = mysql_num_rows($query2);



			mysql_close();



			if ($result < 1) {

				logging("1","","$username","AUTH: bad login","Password: $password");

				return $error[26]; //Not found

			}
 else {

				list ($id) = mysql_fetch_row($query);

				list ($level) = mysql_fetch_row($query2);

				$password = md5($password);

				$hash = md5($username.$password.$level.$secret);

				$cookietime=$timestamp+(3600*$login_cookie_time);

				setcookie("phpBazar", "$username:$password:$hash:$id:$level", "$cookietime", "$cookiepath");

				logging("1","","$username","AUTH: login","");

				return 2;

			}

		}

	}



	function is_logged () {

		global $phpBazar, $secret;

		$session_vars = explode(":", $phpBazar);

		$hash = md5($session_vars[0].$session_vars[1].$session_vars[4].$secret);

		if ($hash != $session_vars[2]) {

		    return false;

		} else {

		    if ($session_vars[4]>7) {$moderator=true;}  // Moderator Check

	    	    return array($session_vars[0], $session_vars[3], $moderator, $session_vars[1]);

		}

	}



	function logout () {

		global $cookiepath,$phpBazar;

		$session_vars = explode(":", $phpBazar);



		setcookie("phpBazar", "", "0", "$cookiepath");

		logging("1","","$session_vars[0]","AUTH: logout","");

	}



        function edit_retrieve ($id) {

  	    global $admin_email,$url_to_start,$mail_msg,$server,$db_user,$db_pass,$database,$secret,$error;

		mysql_connect($server, $db_user, $db_pass);

		mysql_select_db($database);

		$query = mysql_query("select * from userdata where id = '$id'");

		mysql_close();

		list ($id, $username, $email, $sex, $newsletter, $level, $votes, $lastvotedate, $ads,

			$lastaddate, $firstname, $lastname, $address, $zip, $city, $state, $country,

			$phone, $cellphone, $icq, $homepage, $hobbys, $field1, $field2, $field3,

			$field4, $field5, $field6, $field7, $field8, $field9, $field10) = mysql_fetch_row($query);

		return array($email, $sex, $newsletter, $level, $votes, $lastvotedate, $ads,

			$lastaddate, $firstname, $lastname, $address, $zip, $city, $state, $country,

			$phone, $cellphone, $icq, $homepage, $hobbys, $field1, $field2, $field3,

			$field4, $field5, $field6, $field7, $field8, $field9, $field10);

	}



	function edit ($id, $sex, $newsletter, $firstname, $lastname, $address, $zip, $city, $state, $country,

		    $phone, $cellphone, $icq, $homepage, $hobbys, $field1, $field2, $field3, $field4,

		    $field5, $field6, $field7, $field8, $field9, $field10 ) {



 	    global $gender,$genders,$server,$db_user,$db_pass,$database,$secret,$error;

	#	if ($firstname && (!eregi("^[a-z ]+$", $firstname))) {

	#		return $error[8];

	#	}

	#	if ($lastname && (!eregi("^[[:alnum:]_-]+$", $lastname))) {

	#		return $error[8];

	#	}

		if (ereg("[^0-9]", $icq)) {

			return $error[10];

		}

		if ($sex == "") {

			return $error[11];

		}

    	        mysql_connect($server, $db_user, $db_pass);

		mysql_select_db($database);

		$query = mysql_query("update userdata set sex = '$sex',

				                      newsletter = '$newsletter',

						      firstname = '$firstname',

						      lastname = '$lastname',

						      address = '$address',

						      zip = '$zip',

						      city = '$city',

						      state = '$state',

						      country = '$country',

						      phone = '$phone',

						      cellphone = '$cellphone',

						      icq = '$icq',

						      homepage = '$homepage',

						      hobbys = '$hobbys',

						      field1 = '$field1',

						      field2 = '$field2',

						      field3 = '$field3',

						      field4 = '$field4',

						      field5 = '$field5',

						      field6 = '$field6',

						      field7 = '$field7',

						      field8 = '$field8',

						      field9 = '$field9',

						      field10 = '$field10'

						                                 where id = '$id'");

		mysql_close();



		logging("1","$id","","AUTH: updated data","");



		if (!$query) {

			$error[20];

		}

		return 2;

	}



	function confirm ($hash, $username) {

  	    global $chat_interface,$chat_enable,$chat_database,$chat_server,$chat_db_user,$chat_db_pass,

		    $forum_interface,$forum_enable,$forum_database,$forum_server,$forum_db_user,$forum_db_pass,

		    $confirm_mail,$conf_notify,$admin_email,$url_to_start,$mail_msg,$server,$db_user,$db_pass,

		    $database,$secret,$error,$timestamp,$auto_login;



		if (!$hash || !$username) {

			return $error[14];

		}

		else {

			mysql_connect($server, $db_user, $db_pass);

			mysql_select_db($database);



			$query = mysql_query("select * from confirm where mdhash = '$hash' AND username = '$username'");

			$result = mysql_num_rows($query);



			if ($result < 1) {

				mysql_close();

				return $error[15];

			}



			list($hd,$username,$password,$email,$sex,$date,$newsletter,$firstname,

			    $lastname,$address,$zip,$city,$state,$country,$phone,$cellphone,$icq,

			    $homepage,$hobbys,$field1,$field2,$field3,$field4,$field5,$field6,

			    $field7,$field8,$field9,$field10) = mysql_fetch_row($query);



			//calculate a possible id-difference, if only login-data will deleted

			$result = mysql_query("SELECT * FROM userdata");

		    	$tmpid1 = mysql_num_rows($result);

			$result = mysql_query("SELECT * FROM login");

			$tmpid2 = mysql_num_rows($result);



			if ($tmpid1 != $tmpid2) {

			    $diff = $tmpid1-$tmpid2;

			    for ($i = 0; $i < $diff; $i++) {

				mysql_query("insert into login (username, password) values ('$timestamp', '$timestamp')");

				}

			    }

			// end id-difference



			$is_success_first = mysql_query("insert into userdata (username, email, sex,

			    newsletter, firstname, lastname, address, zip, city, state, country,

			    phone, cellphone, icq, homepage, hobbys, field1, field2, field3,

			    field4, field5, field6, field7, field8, field9, field10, registered )

			    values ('$username', '$email', '$sex',

			    '$newsletter', '$firstname', '$lastname', '$address', '$zip', '$city', '$state', '$country',

			    '$phone', '$cellphone', '$icq', '$homepage', '$hobbys', '$field1', '$field2', '$field3',

			    '$field4', '$field5', '$field6', '$field7', '$field8', '$field9', '$field10', '$timestamp' )") or died(mysql_error());

			if ($is_success_first) {

			    $is_success_second = mysql_query("insert into login (username, password) values ('$username', '$password')");

			    if ($is_success_second) {

				$is_success_third = mysql_query("delete from confirm where username = '$username'");



				// only if forum-interface

				if ($is_success_second && $forum_database && $forum_enable && $forum_interface) {

				    include ("$forum_interface");

				}

		                // only if chat-interface

	                        if ($is_success_second && $chat_database && $chat_enable && $chat_interface) {

				    include ("$chat_interface");

				}

			    }

			}



			mysql_close();



			if (!$is_success_first) {

				return $error[16];

			}

			if (!$is_success_second) {

			# Registration Error

				return $error[17];

			}

			if (!$is_success_third) {

			# Alert, Purge Account!!!

				return 2;

			}



			logging("1","","$username","AUTH: confirmed registration","");



			$retval=2;

			if ($auto_login) {

			    $login=$this->login($username, $password);

			    if ($login!="2") {

				$retval="$error[15]";

			    } else {

				$retval=3;

			    }

			}



			if ($conf_notify) {

			    $mailto = "$conf_notify";

 			    $subject = "NOTIFY $mail_msg[0]";

			    $message = "$mail_msg[8]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$sex\n";

			    $from = "From: $admin_email\r\nReply-to: $admin_email\r\n";



			    @mail($mailto, $subject, $message, $from);

			}





			if ($confirm_mail) {

			    $mailto = "$email";

			    $subject = "NOTIFY $mail_msg[9]";

			    $message = "$mail_msg[10]$username\n\n$mail_msg[11]";

			    $from = "From: $admin_email\r\nReply-to: $admin_email\r\n";



			    @mail($mailto, $subject, $message, $from);

			}



			return $retval;

		}

	}





	function lostpwd ($email) {

  	    global $admin_email,$url_to_start,$mail_msg,$server,$db_user,$db_pass,$database,$secret,$error;

		if (!$email) {

			return $error[14];

		}

		mysql_connect($server, $db_user, $db_pass);

		mysql_select_db($database);



		$query = mysql_query("select login.password, login.username from login, userdata where userdata.email = '$email' and login.id = userdata.id");

		$result = mysql_num_rows($query);



		mysql_close();



		if ($result < 1) {

			return $error[19];

		}

		list($password, $username) = mysql_fetch_row($query);



		$confirmurl = ("$url_to_start" . "/confirm.php?hash=" . "$hash" . "&username=" . "$username");



		$mailto = "$email";

		$subject = "$mail_msg[12]";

		$message = "$mail_msg[13]$username\n\n$mail_msg[14]$username\n$mail_msg[3]$password\n\n$mail_msg[15]";

		$from = "From: $admin_email\r\nReply-to: $admin_email\r\n";



		@mail($mailto, $subject, $message, $from);



		logging("1","","$username","AUTH: lost password sent","");



		return 2;

	}



	function chemail ($id, $email, $email2) {

  	    global $admin_email,$url_to_start,$mail_msg,$server,$db_user,$db_pass,$database,$secret,$error;

		if ($email != $email2) {

			return $error[14];

		}

		else {

			if (!eregi("^([a-z0-9]+)([._-]([a-z0-9]+))*[@]([a-z0-9]+)([._-]([a-z0-9]+))*[.]([a-z0-9]){2}([a-z0-9])?$", $email)) {

				return $error[4];

			}

			mysql_connect($server, $db_user, $db_pass);

			mysql_select_db($database);

			$query = mysql_query("select id from userdata where email = '$email'");

			$result = mysql_num_rows($query);

			if ($result > 0) {

				list($id_from_db) = mysql_fetch_row($query);

				if ($id_from_db != $id) {

					mysql_close();

					return $error[13];

				}

				return $error[23];

			}

			$mdhash = substr(md5($id.$email.$secret),0,10);

			$query = mysql_query("insert into confirm_email values ('$id', '$email', '$mdhash', now())");

			if (!$query) {

				mysql_close();

				$error[20];

			}



			$confirmurl = ("$url_to_start" . "/confirm_email.php?mdhash=" . "$mdhash" . "&id=" . "$id" . "&email=" . "$email");



			$mailto = "$email";

			$subject = "$mail_msg[16]";

			$message = "$mail_msg[17]\n\n$confirmurl\n\n$mail_msg[18]";

			$from = "From: $admin_email\r\nReply-to: $admin_email\r\n";



			@mail($mailto, $subject, $message, $from);



			logging("1","$id","","AUTH: new email change","");



			return 2;

		}

	}



	function confirm_email($id, $email, $mdhash) {

  	    global $server,$db_user,$db_pass,$database,$secret,$error;



		if (!$id || !$email || !$mdhash) {

			return $error[14];

		}

		else {

			mysql_connect($server, $db_user, $db_pass);

			mysql_select_db($database);

			$query = mysql_query("select * from confirm_email where id = '$id' AND email = '$email' AND mdhash = '$mdhash'");

			$result = mysql_num_rows($query);

			if ($result < 1) {

				mysql_close();

				return $error[15];

			}

			$update = mysql_query("update userdata set email = '$email' where id = '$id'");

			$delete = mysql_query("delete from confirm_email where email = '$email'");

			mysql_close();



			logging("1","$id","","AUTH: confirmed email change","");



			return 2;

		}

	}



	function confirm_ad($id, $hash) {

  	    global $server,$db_user,$db_pass,$database,$timeoutconfirm,$error;



		if (!$id || !$hash) {

			return $error[14];

		}

		else {

			mysql_connect($server, $db_user, $db_pass);

			mysql_select_db($database);

			$query = mysql_query("select * from ads where id = '$id' AND timeoutnotify = '$hash'");

			$result = mysql_num_rows($query);

			if ($result < 1) {

				mysql_close();

				return $error[15];

			}

			$update = mysql_query("update ads set timeoutnotify = '',timeoutdays = timeoutdays+$timeoutconfirm where id = '$id'");

			mysql_close();

			return 2;

		}

	}





	function chpass ($id, $password, $password2) {

  	    global $server,$db_user,$db_pass,$database,$error;

		if ($password != $password2) {

			return $error[0];

		}

		else {

			if (strlen($password) < 3) {

				return $error[5];

			}

			if (strlen($password) > 20) {

				return $error[6];

			}

			if (!ereg("^[[:alnum:]_-]+$", $password)) {

				return $error[7];

			}

			mysql_connect($server, $db_user, $db_pass);

			mysql_select_db($database);

			$query = mysql_query("update login set password = '$password' where id = '$id'");

			mysql_close();

			if (!$query) {

				return $error[21];

			}



			logging("1","$id","","AUTH: password changed","New Password: $password");



			return 2;

		}

	}



	function delete($id) {

  	    global $server,$db_user,$db_pass,$database,$error,$timestamp;

		mysql_connect($server, $db_user, $db_pass);

		mysql_select_db($database);

		$delstring="deleted_".$timestamp;

		if ($really_del_memb) { //if set really delete it

		    $query = mysql_query("update ads set deleted='1' where userid = '$id'");

		    $query = mysql_query("delete from login where id = '$id'");

		    $query = mysql_query("delete from userdata where id = '$id'");

		} else {		// or only overwrite the password :-) better

		    $query = mysql_query("update ads set deleted='1' where userid = '$id'");

		    $query = mysql_query("update login set password='$delstring' where id = '$id'");

		}

		mysql_close();



		logging("1","$id","","AUTH: deleted","");



		return 2;

	}





}



$authlib = new authlib;



?>