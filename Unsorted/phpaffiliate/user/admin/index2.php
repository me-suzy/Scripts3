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
    	<a href=reward.php>Reward Affiliates</a><br>
    	<a href=contact.php>Contact Affiliates</a><br><p></p>";
    	
  }

   else
  {
       include "../header.290"; 
    echo "<p>Only the administrator may see this page.</p>";
  }

     include "../footer.290"; 
?>
