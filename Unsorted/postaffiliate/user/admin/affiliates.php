<?
  session_start();

  include "../../affconfig.php";
  include "../lang/$language";

  if(!aff_admin_check_security())
  {
    aff_redirect('index.php');
    exit;
  }
  
  include "header.php"; 
  
  mysql_connect($server, $db_user, $db_pass) 
    or die ("Database CONNECT Error (line 32)");
  
  echo "<p><b>".AFF_A_DELETEAFF."</b>";
  echo "<br><font color=red>".AFF_D_WARNING."</font>";
    		
	echo "<form method=post action=removeuser.php>
  <table border=0 cellspacing=0 cellpadding=0>
    <tr align=center valign=middle> 
      <td width=200><font face=Arial, Helvetica, sans-serif>".AFF_A_DELETEAFF."</font></td>
      <td width=70> 
        <select name=deleteuser>";
          
  $result = mysql_db_query($database, "select * from affiliates") 
    or die ("Database INSERT Error"); 
  
  if (mysql_num_rows($result)) 
  {
    while ($qry = mysql_fetch_array($result)) 
    {
      print "<option value=".$qry[refid].">".$qry[refid]."</option>";
    }
    print "</select>";
  }

	echo "</td>
      <td width=50> 
        <p> 
          <input type=submit value=".AFF_G_DELETE.">
        </p>
        </td>
    </tr>
  </table>
</form>";
    	  
  echo "</p><br>";
  
  echo "<p><b>".AFF_A_REWARDAFF."</b>";
  echo "<br><font color=red>".AFF_R_WARNING."</font>";
  
  $result = mysql_db_query($database, "select refid, sum(payment) as payments, count(payment) as salescount from sales group by refid ORDER BY date and time") 
    or die ("Database INSERT Error"); 
		
  print "<br><br><font face=arial>Affiliates Achieving Sales: ";
  print "<br><br>";
  
  if (mysql_num_rows($result)) 
  {
    print "<font face=arial><TABLE border=1 cellspacing=0 cellpadding=3>";
    print "<TR><TH>".AFF_A_AFFILIATE."</TH><TH>".AFF_A_SALESCOUNT."</TH><TH>".AFF_A_EARNED."</TH>";
    print "<TH>".AFF_G_ACTION."</TH></TR>";
    
    $sumall = 0;
    
    while ($qry = mysql_fetch_array($result)) 
    {
      $sumall += $qry['payments'];
      print "<TR>";
      
      echo "<td align=center>".$qry['refid']."</td>";
      echo "<td align=center>".$qry['salescount']."</td>";
      echo "<td align=center>".$qry['payments']."</td>";
      
      echo "<TD>";
      echo "<a href=invoice.php?aff=".$qry[refid]." target=_invoice>".AFF_A_INVOICE."</a>";
      echo "&nbsp;|&nbsp;<a href=reset.php?aff=".$qry[refid].">".AFF_A_RESET."</a>";
      echo "</TD></TR>";
    }
    
    echo "<TR><TH colspan=2>&nbsp;&nbsp;".AFF_A_TOTAL."</td><TH align=center>$sumall</td><td>&nbsp;</td></tr>";
    print "</TABLE>";
  }
  
  echo "</p>";
    
  include "footer.php";   
?>
