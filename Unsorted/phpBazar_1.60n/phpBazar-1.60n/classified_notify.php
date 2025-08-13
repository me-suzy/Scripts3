<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_notify.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Member's can view & delete there notify-cat entry's
#
#################################################################################################





#  Connect to the DB

#################################################################################################



mysql_connect($server, $db_user, $db_pass);



#  Get Entrys for current page

#################################################################################################

if ($login_check) {$mod=$login_check[2];}

if ($login_check) {$uid=$login_check[1];}

if ($uid) {  // show the ads list



 $result = mysql_db_query($database, "SELECT * FROM userdata WHERE id='$uid'") or died("Record NOT Found");

 $db = mysql_fetch_array($result);

 $amount = mysql_db_query($database, "SELECT count(userid) FROM notify WHERE userid='$uid'") or died("Record NOT Found");

 $amount_array = mysql_fetch_array($amount);



 if ($amount_array[0]) {



  echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

  echo "<tr><td><div class=\"smallright\">\n";

  echo "$admy_member$db[username]\n";

  echo "</div></td>\n";

  echo "</table>\n";



  echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

  $result = mysql_db_query($database, "SELECT * FROM notify WHERE userid='$uid'") or died("Favorits - Record NOT Found");

  while ($tempdb = mysql_fetch_array($result)) {

    $query = mysql_db_query($database, "SELECT * FROM adsubcat WHERE id='$tempdb[subcatid]'") or died("Ads - Record NOT Found");

    $db = mysql_fetch_array($query);



    if ($db) {

	$query2 = mysql_db_query($database, "SELECT id,name FROM adcat WHERE id='$db[catid]'") or died("Ads - Record NOT Found");

	$dbc = mysql_fetch_array($query2);

	echo " <tr>\n";

	echo "   <td class=\"classcat1\">\n";

	if ($db[picture]) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}

	echo "   </td>\n";

	echo "   <td class=\"classcat2\">\n";

	echo "   <a href=\"classified.php?catid=$db[catid]&subcatid=$db[id]\" onmouseover=\"window.status='$db[description]';

                 return true;\" onmouseout=\"window.status=''; return true;\">$dbc[name]/$db[name]</a> ($db[ads])<br>\n";

	echo "   <div class=\"smallleft\">\n";

	echo "   <a href=\"notify.php?delid=$db[id]\"

	      onClick='enterWindow=window.open(\"notify.php?delid=$db[id]\",\"Delete\",\"width=400,height=200,top=100,left=100\"); return false'

	      onmouseover=\"window.status='$adnot_delete'; return true;\"

	      onmouseout=\"window.status=''; return true;\">

	     <img src=\"$image_dir/icons/trash.gif\" border=\"0\" alt=\"$adnot_delete\" align=\"right\" vspace=\"2\"></a>\n";

	echo "   $db[description]\n";

	echo "   </div>";

	echo "   </td>\n";



    }

  } //End while

 echo "</table>\n";

 } else {

    echo $mess_noentry;

 }

} else { // NO Login



}



# End of Page reached

#################################################################################################





#  Disconnect DB

#################################################################################################

mysql_close();

?>