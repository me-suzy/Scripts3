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
    	Add/Edit/Delete A Banner<br>
    	<a href=remove.php>Delete An Affiliate</a><br>
    	<a href=reward.php>Reward Affiliates</a><br>
    	<a href=contact.php>Contact Affiliates</a><br>";
    	
    	echo "<br><a href=banneradd.php>Add A Banner</a>";
    	print "<br><br>";
    	
    	echo "<form method=post action=banneradd2.php><table width=500 border=0 cellspacing=0 cellpadding=0>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2>Banner Name: </font></td>
      <td> <font face=Arial, Helvetica, sans-serif size=2> 
        <input type=text name=bannername size=40>
        </font></td>
    </tr>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2>Banner URL:</font></td>
      <td><font face=Arial, Helvetica, sans-serif size=1>http://$domain/images/</font><font face=Arial, Helvetica, sans-serif size=2> 
        <input type=text name=bannerurl value=image.gif>
        </font></td>
    </tr>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2>Banner Description:</font></td>
      <td> <font face=Arial, Helvetica, sans-serif size=2> 
        <textarea name=bannerdesc cols=40 rows=6></textarea>
        </font></td>
    </tr>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2></font></td>
      <td><font face=Arial, Helvetica, sans-serif size=2></font></td>
    </tr>
    <tr valign=top> 
      <td colspan=2>
        <div align=center><font face=Arial, Helvetica, sans-serif size=2></font><font face=Arial, Helvetica, sans-serif size=2>
          <input type=submit name=Submit value=Add&nbsp;Banner&nbsp;Info>
          </font></div>
      </td>
    </tr>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2></font></td>
      <td><font face=Arial, Helvetica, sans-serif size=2></font></td>
    </tr>
  </table></form>";
    	
  }

   else
  {
       include "../header.290"; 
    echo "<p>Only the administrator may see this page.</p>";
  }

     include "../footer.290"; 
  
?>
