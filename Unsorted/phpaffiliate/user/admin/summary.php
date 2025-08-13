<?
  session_start();

  // check session variable

  if (session_is_registered("valid_admin"))
  {
       include "../header.290"; 
       include "../../config.php"; 
       
       
      echo "<p><font face=arial>
    
    	<a href=payout.php>Change Your Payout Amount</a><br>
    	<a href=clicks.php>View Click Throughs</a><br>
    	<a href=sales.php>View Sales</a><br>
    	<a href=banners.php>Add/Edit/Delete A Banner</a><br>
    	<a href=remove.php>Delete An Affiliate</a><br>
    	Reward Affiliates<br>
    	<a href=contact.php>Contact Affiliates</a><br>";
    	
    	echo "<br><b><font color=red>WARNING! - </b>This process will also reset all clickthroughs and sales for the selected affiliate to prevent multiple payouts.</font>";
    		
  print "<br><br><font face=arial>Affiliate ID: ";
  print $aff;
  print "<br>Total Earnings Owed: ";
  
  
  {
  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 30)"); 
 
  $set = mysql_db_query($database, "select SUM(payment) AS total from sales WHERE refid = '$aff'") or die ("Database INSERT Error"); 
  $row = mysql_fetch_array( $set );
  print $row['total'];
  $money = $row['total'];
  
    }
  
  
    print "<br><br><u><b>Step 1</b></u><br><a href=invoice.php?aff=";
    print $aff;
    print "&reward=";
    print $money;
    print " target=_blank>Click Here</a> To Get Invoice";
    print "<br><br><u><b>Step 2</b></u><br><a href=reset.php?ref=";
    print $aff;
    print ">Click Here</a> To Reset Affiliate Records. (You Don't Want To Pay Them Twice!)";
    print "<br><br>";
    
    	  
  }

   else
  {
       include "../header.290"; 
    echo "<p>Only the administrator may see this page.</p>";
  }

   include "../footer.290";   
?>
