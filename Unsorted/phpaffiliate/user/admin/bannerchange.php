<?
  session_start();

  // check session variable

  if (session_is_registered("valid_admin"))
  {
	  
	  include "../../config.php"; 
 
	  { 
		  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 12)"); 
		  mysql_db_query($database, "UPDATE banners SET name = '$bannername', description = '$bannerdesc' WHERE number = '$no'") or die ("Database INSERT Error"); 
		  
	}
	  
       include "../header.290"; 
      echo "<p><font face=arial>
    
    	<a href=payout.php>Change Your Payout Amount</a><br>
    	<a href=clicks.php>View Click Throughs</a><br>
    	<a href=sales.php>View Sales</a><br>
    	Add/Edit/Delete A Banner<br>
    	<a href=remove.php>Delete An Affiliate</a><br>
    	<a href=reward.php>Reward Affiliates</a><br>
    	<a href=contact.php>Contact Affiliates</a><br>";
    	
    	echo "<br>Banner Edit Complete<br><a href=banneradd.php>Add A Banner</a>";
    	
	mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 30)"); 
  $result = mysql_db_query($database, "select * from banners ORDER BY name asc") or die ("Database INSERT Error"); 
		
  print "<br><br><font face=arial>Here Are The Banners You Offer: ";
  print "<br><br>";
  
  if (mysql_num_rows($result)) {
    print "<font face=arial><TABLE width=100% align=center>";
    while ($qry = mysql_fetch_array($result)) {
      print "<TR>";
      print "<TD width=10><font size=4>";
      print $qry[number];
      print "</TD>";
      print "<TD><font size=4 align=left>";
      print $qry[name];
      print " - <a href=banneredit.php?edit=";
      print $qry[number];
      print ">Edit Banner</a>     <a href=bannerdelete.php?delete=";
      print $qry[number];
      print ">Delete Banner</a>";
      print "</TD></TR><TR>";
      print "<TD colspan=2 align=center><font size=4><center>";
      print "<img src=http://"; 
      print $domain; 
      print "/images/";
      print $qry[image];
      print "></TD>";
      print "</TR>";
      print "<tr><td><br><br></td><td></td></tr>";
      
    }
    print "</TABLE>";
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
