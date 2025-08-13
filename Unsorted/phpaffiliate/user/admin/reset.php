<?
  session_start();

  // check session variable

  if (session_is_registered("valid_admin"))
  {
    
	  include "../../config.php"; 
	  
	  {
	  	  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 12)"); 
		  mysql_db_query($database, "DELETE FROM sales WHERE refid = '$ref'") or die("Database INSERT Error"); 
		  mysql_db_query($database, "DELETE FROM clickthroughs WHERE refid = '$ref'") or die("Database INSERT Error"); 
	}
	  
	  
	     include "../header.290"; 
      echo "<p><font face=arial>
    
    	<a href=payout.php>Change Your Payout Amount</a><br>
    	<a href=clicks.php>View Click Throughs</a><br>
    	<a href=sales.php>View Sales</a><br>
    	<a href=banners.php>Add/Edit/Delete A Banner</a><br>
    	<a href=remove.php>Delete An Affiliate</a><br>
    	Reward Affiliates<br>
    	<a href=contact.php>Contact Affiliates</a><br>";
    	
    	echo "<br>Affiliate Records Have Been Reset<br><b><font color=red>WARNING! - </b>This process will also reset all clickthroughs and sales for the selected affiliate to prevent multiple payouts.</font>";
    		
  print "<br><br>";
  
	
  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 34)"); 
  $result = mysql_db_query($database, "select * from sales ORDER BY date and time") or die ("Database INSERT Error"); 
		
  print "<br><br><font face=arial>Affiliates Achieving Sales: ";
  print "<br><font size=2>Multiple references to a single affiliate will show below, each representing a different sale. Once you have rewarded the affiliate once, these other references will dissappear.</font>";
  print mysql_num_rows($result);
  print "<br><br>";
  
  if (mysql_num_rows($result)) {
    print "<font face=arial><TABLE>";
    while ($qry = mysql_fetch_array($result)) {
      print "<TR>";
      print "<TD><font size=3><a href=summary.php?aff=";
      print $qry[refid];
      print ">";
      print $qry[refid];
      print "</a></TD></TR>";
    }
    print "</TABLE>";
    }
    print "<br><br>Your Affiliates Have Earned ";
    mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 55)"); 
    $set = mysql_db_query($database, "select SUM(payment) AS total from sales") or die ("Database INSERT Error"); 
    $row = mysql_fetch_array( $set );
    print $row['total'];
	print " ";
	print $currency; 
	print " This Month<br><br>";
    }
 
   else
  {
       include "../header.290"; 
    echo "<p>Only the administrator may see this page.</p>";
  }

   include "../footer.290";   
?>
