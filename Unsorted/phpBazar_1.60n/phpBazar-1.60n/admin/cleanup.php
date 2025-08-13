<?

#################################################################################################
#
#  project              : phpBazar
#  filename             : ./admin/cleanupdb.php
#  last modified by     : Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose              : Database-Cleanup
#
#################################################################################################





#  Include Configs & Variables

#################################################################################################

require_once ("../config.php");



function suppr($file) {

    $delete = @unlink($file);

    if (@file_exists($file)) {

        $filesys = eregi_replace("/","\\",$file);

	$delete = @system("del $filesys");

	if (@file_exists($file)) {

    	    $delete = @chmod ($file, 0777);

            $delete = @unlink($file);

    	    $delete = @system("del $filesys");

    	}

    }

}





#################################################################################################

mysql_connect($server, $db_user, $db_pass) or die ("Database connect Error");



// table adcat - update ads counter

$result = mysql_db_query($database, "SELECT id,ads FROM adcat") or die(mysql_error());

while ($db = mysql_fetch_array($result)) {

    $query = mysql_db_query($database, "SELECT id FROM ads WHERE catid=$db[id] AND publicview=1") or die(mysql_error());

    $count=mysql_num_rows($query);

    if ($db[ads]!=$count) {

	mysql_db_query($database, "UPDATE adcat SET ads='$count' WHERE id=$db[id]") or die(mysql_error());

    }

}



// table adsubcat - update ads counter

$result = mysql_db_query($database, "SELECT id,ads FROM adsubcat") or die(mysql_error());

while ($db = mysql_fetch_array($result)) {

    $query = mysql_db_query($database, "SELECT id FROM ads WHERE subcatid=$db[id] AND publicview=1") or die(mysql_error());

    $count=mysql_num_rows($query);

    if ($db[ads]!=$count) {

	mysql_db_query($database, "UPDATE adsubcat SET ads='$count' WHERE id=$db[id]") or die(mysql_error());

    }

}



// table ads - update member ads counter

$result = mysql_db_query($database, "SELECT id FROM userdata") or die(mysql_error());

while ($db = mysql_fetch_array($result)) {

    $query = mysql_db_query($database, "SELECT id FROM ads WHERE userid=$db[id]") or die(mysql_error());

    $count=mysql_num_rows($query);

    if ($db[ads]!=$count) {

	mysql_db_query($database, "UPDATE userdata SET ads='$count' WHERE id=$db[id]") or die(mysql_error());

    }

}





// table notify - delete entries with invalid member

$result = mysql_db_query($database, "SELECT userid FROM notify GROUP BY userid") or die(mysql_error());

while ($db = mysql_fetch_array($result)) {

    $query = mysql_db_query($database, "SELECT * FROM login WHERE id='$db[userid]' AND password NOT LIKE 'deleted%'") or die(mysql_error());

    $count=mysql_num_rows($query);

    if (!$count) {

	mysql_db_query($database, "DELETE FROM notify WHERE userid='$db[userid]'") or die(mysql_error());

    }

}



// table login - delete entries with invalid member

$result = mysql_db_query($database, "SELECT id,username FROM login") or die(mysql_error());

while ($db = mysql_fetch_array($result)) {

    $query = mysql_db_query($database, "SELECT * FROM userdata WHERE id='$db[id]' AND username='$db[username]'") or die(mysql_error());

    $count=mysql_num_rows($query);

    if (!$count) {

	mysql_db_query($database, "DELETE FROM login WHERE id='$db[id]'") or die(mysql_error());

    }

}



// table login - insert missing entries

$result = mysql_db_query($database, "SELECT id,username FROM userdata") or die(mysql_error());

while ($db = mysql_fetch_array($result)) {

    $query = mysql_db_query($database, "SELECT * FROM login WHERE id='$db[id]' AND username='$db[username]'") or die(mysql_error());

    $count=mysql_num_rows($query);

    if (!$count) {

	$password=time();

	mysql_db_query($database, "INSERT INTO login (id,username,password) VALUES ('$db[id]','$db[username]','$password')") or die(mysql_error());

    }

}





// optimize db

$result = mysql_list_tables($database) or die(mysql_error());

$i = 0;

while ($i < mysql_num_rows($result)) {

    $tb_names[$i] = mysql_tablename($result, $i);

    $tablename= $tb_names[$i];

    mysql_query("OPTIMIZE TABLE $tablename");

    $i++;

}

echo "         Database cleanup finished\n";





// remove unused pictures

if ($pic_database && $pic_enable) {



  $result = mysql_db_query($database, "SELECT picture_name FROM pictures") or die(mysql_error());

  while ($db = mysql_fetch_array($result)) {

    if (!strstr($db[picture_name],"_")) {

	$query = mysql_db_query($database, "SELECT id FROM ads WHERE picture='$db[picture_name]' OR picture2='$db[picture_name]' OR picture3='$db[picture_name]'") or die(mysql_error());

	$count=mysql_num_rows($query);

	if (!$count) {

	    mysql_db_query($database, "DELETE FROM pictures WHERE picture_name='$db[picture_name]' OR picture_name='_$db[picture_name]'") or die(mysql_error());

	    echo "         Picture $db[picture_name] deleted.\n";

	}

    }

  }

  echo "         Picture cleanup finished\n";



} elseif (!$pic_database && $pic_enable) {



  unset($retVal);

  $handle=opendir("$bazar_dir/$pic_path");

  while ($file = readdir($handle)) {

    if (eregi(".+\.png$|.+\.gif$|.+\.jpg$",$file) && !eregi("_+|-+",$file)) {

        $retVal[count($retVal)+1] = $file;

    }

  }

  closedir($handle);



  if (is_array($retVal)) {

    sort($retVal);

    while (list($key, $val) = each($retVal)) {

	if ($val != "." && $val != "..") {

	    $query = mysql_db_query($database, "SELECT id FROM ads WHERE picture='$val' OR picture2='$val' OR picture3='$val'") or die(mysql_error());

	    $count=mysql_num_rows($query);

	    if (!$count) {

		suppr("$bazar_dir/$pic_path/".$val);

		echo "         File $val deleted.\n";

		if (is_file("$bazar_dir/$pic_path/_".$val)) {

		    suppr("$bazar_dir/$pic_path/_".$val);

		    echo "         File _$val deleted.\n";

		}

	    }

	}

    }

  }

  echo "         Picture cleanup finished\n";

}



// remove unused attachments

if ($att_enable) {

  unset($retVal);

  $handle=opendir("$bazar_dir/$att_path");

  while ($file = readdir($handle)) {

    if (eregi(".+\.pdf$|.+\.doc$|.+\.txt$",$file) && !eregi("_+|-+",$file) ) {

        $retVal[count($retVal)] = $file;



    }

  }

  closedir($handle);



  if (is_array($retVal)) {

    sort($retVal);

    while (list($key, $val) = each($retVal)) {

	if ($val != "." && $val != "..") {

	    $query = mysql_db_query($database, "SELECT id FROM ads WHERE attachment1='$val' OR

				    attachment2='$val' OR attachment3='$val'") or die(mysql_error());

	    $count=mysql_num_rows($query);

	    if (!$count) {

		suppr("$bazar_dir/$att_path/".$val);

		echo "         File $val deleted.\n";

	    }

	}

    }

  }

  echo "         Attachment cleanup finished\n";

}





// remove old webmails & attachments

if ($webmail_enable && $webmail_storedays) {

    $deletestamp=$timestamp-($webmail_storedays*3600*24);

    $result=mysql_db_query($database, "SELECT * FROM webmail WHERE timestamp<'$deletestamp'") or die(mysql_error());

    while ($db = mysql_fetch_array($result)) {

	if ($db[attachment1] && is_file("$bazar_dir/$webmail_path/$db[attachment1]")) {suppr("$bazar_dir/$webmail_path/$db[attachment1]");}

	if ($db[attachment2] && is_file("$bazar_dir/$webmail_path/$db[attachment2]")) {suppr("$bazar_dir/$webmail_path/$db[attachment2]");
}

	if ($db[attachment3] && is_file("$bazar_dir/$webmail_path/$db[attachment3]")) {suppr("$bazar_dir/$webmail_path/$db[attachment3]");
}

	mysql_db_query($database, "DELETE FROM webmail WHERE id='$db[id]'") or die(mysql_error());

    }

    echo "         WebMail prune old mails & attachments finished\n";



}





// remove old logevents

if ($logging_enable && $logging_days) {

    $deletestamp=$timestamp-($logging_days*3600*24);

    mysql_db_query($database, "DELETE FROM logging WHERE timestamp<'$deletestamp'") or die(mysql_error());

    echo "         Logging prune old events finished\n";

}



// workout missing usernames and userids in logevents

if ($logging_enable) {

    $result = mysql_db_query($database, "SELECT * FROM logging WHERE userid='0' OR username=''") or die(mysql_error());

    while ($db = mysql_fetch_array($result)) {

    if (!$db[userid] && $db[username]) {

	$query = mysql_db_query($database, "SELECT id,username FROM userdata WHERE username='$db[username]'") or die(mysql_error());

	$dbu = mysql_fetch_array($query);

	if ($dbu[id]) {

	    mysql_db_query($database, "UPDATE logging SET userid='$dbu[id]' WHERE ip='$db[ip]' AND timestamp='$db[timestamp]'") or die(mysql_error());

	}

    } elseif (!$db[username] && $db[userid])

	$query = mysql_db_query($database, "SELECT id,username FROM userdata WHERE id='$db[userid]'") or die(mysql_error());

	$dbu = mysql_fetch_array($query);

	if ($dbu[id]) {

	    mysql_db_query($database, "UPDATE logging SET username='$dbu[username]' WHERE ip='$db[ip]' AND timestamp='$db[timestamp]'") or die(mysql_error());

	}

    }

    echo "         Logging workout finished\n";

}





mysql_close();

?>

