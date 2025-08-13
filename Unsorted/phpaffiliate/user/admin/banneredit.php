<?
  session_start();

  // check session variable

  if (session_is_registered("valid_admin"))
  {
      include "../../config.php"; 
	  include "../header.290"; 
      echo "<p><font face=arial>
    
    	<a href=payout.php>Change Your Payout Amount</a><br>
    	<a href=clicks.php>View Click Throughs</a><br>
    	<a href=sales.php>View Sales</a><br>
    	Add/Edit/Delete A Banner<br>
    	<a href=remove.php>Delete An Affiliate</a><br>
    	<a href=reward.php>Reward Affiliates</a><br>
    	<a href=contact.php>Contact Affiliates</a><br>";
    	
    	echo "<br><a href=banneradd.php>Add A Banner</a>";
    	
	mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 22)"); 
	$result = mysql_db_query($database, "select * from banners WHERE number = '$edit' ORDER BY name asc") or die ("Database INSERT Error"); 
		
  print "<br><br><font face=arial>Details For Banner ";
  print $edit;
  print ": ";
  print "<br><br>";
  
  if (mysql_num_rows($result)) {
    print "<font face=arial><form method=post action=bannerchange.php><TABLE width=100% align=center>";
    while ($qry = mysql_fetch_array($result)) {
      print "<TR>";
      print "<TD>Banner Name: </TD><TD><input type=text name=bannername value=";
      print $qry[name];
      print "></TD></TR><TR><TD>Banner Description: </TD><TD><textarea name=bannerdesc cols=50 rows=5>";
      print $qry[description];
      print "</textarea></TD></TR>";
      print "<input type=hidden name=no value=";
      print $edit;
      print ">";
      
    }
    print "<TR><TD colspan=2><center><input type=submit value=Change Banner></center></TD></TR>";
    print "</TABLE>";
    print "</form>";
    print "<br><br>";
    }
    	
  
  }

   else
  {
       include "../header.290"; 
    echo "<p>Only the administrator may see this page.</p>";
  }

     include "../footer.290"; 
?>
