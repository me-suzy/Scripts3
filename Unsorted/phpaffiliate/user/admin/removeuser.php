<?
  session_start();

  // check session variable

  if (session_is_registered("valid_admin"))
  {
    
	  include "../../config.php"; 
	  
	  if ($deleteuser)
    {
	    mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 13)"); 
	    mysql_db_query($database, "DELETE FROM affiliates WHERE refid = '$deleteuser' LIMIT 1") or die("Database INSERT Error"); 
	}
	  
	  
	     include "../header.290"; 
      echo "<p><font face=arial>
    
    	<a href=payout.php>Change Your Payout Amount</a><br>
    	<a href=clicks.php>View Click Throughs</a><br>
    	<a href=sales.php>View Sales</a><br>
    	<a href=banners.php>Add/Edit/Delete A Banner</a><br>
    	Delete An Affiliate<br>
    	<a href=reward.php>Reward Affiliates</a><br>
    	<a href=contact.php>Contact Affiliates</a><br>";
    	
    	echo "<br>Affiliate Has Now Been Deleted!<br><b><font color=red>WARNING! - </b>All deletions are final and cannot be recovered</font>";
    		
  print "<br><br>";
  
	echo "<form method=post action=removeuser.php>
  <table border=0 cellspacing=0 cellpadding=0>
    <tr align=center valign=middle> 
      <td width=200><font face=Arial, Helvetica, sans-serif>Delete The Affiliate:</font></td>
      <td width=70> 
        <select name=deleteuser>";
          
        mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 40)"); 
  		$result = mysql_db_query($database, "select * from affiliates") or die ("Database INSERT Error"); 
		if (mysql_num_rows($result)) {
    	while ($qry = mysql_fetch_array($result)) {
      print "<option value=";
      print $qry[refid];
      print ">";
      print $qry[refid];
      print "</option>";
    }
    print "</select>";
  }

	echo "</td>
      <td width=50> 
        <p> 
          <input type=submit value=Delete>
        </p>
        </td>
    </tr>
  </table>
</form>";
    	  
  }

   else
  {
       include "../header.290"; 
    echo "<p>Only the administrator may see this page.</p>";
  }

   include "../footer.290";   
?>
