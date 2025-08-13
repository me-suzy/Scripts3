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

	echo "<form method=post action=changeclick.php>
  <table border=0 cellspacing=0 cellpadding=0>
    <tr align=center valign=middle> 
      <td width=200><font face=Arial, Helvetica, sans-serif>".AFF_S_SHOWCLICKSFOR."</font></td>
      <td width=70> 
        <select name=change>";
          
  mysql_connect($server, $db_user, $db_pass) 
    or die ("Database CONNECT Error (line 29)"); 
    
  $result = mysql_db_query($database, "select * from affiliates") 
    or die ("Database Error"); 
    
	if (mysql_num_rows($result)) 
  {
    while ($qry = mysql_fetch_array($result)) 
      echo "<option value=".$qry[refid].">".$qry[refid]."</option>";
    print "</select>";
  }

	echo "</td>
      <td width=50> 
        <p> 
          <input type=submit value=Show>
        </p>
        </td>
    </tr>
  </table>
</form>";

  // protection against script injection
  $change = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['change']);
    	
  $result = mysql_db_query($database, "select * from clickthroughs where refid = '$change' ORDER BY date and time") 
    or die ("Database Error"); 
		
  print "<br><br><font face=arial>".AFF_C_CLICKSFROM.": ".$change." ".AFF_C_TOSITE.": ".mysql_num_rows($result)."<br><br>";
  
  if (mysql_num_rows($result)) 
  {
    print "<font face=arial><TABLE border=1 cellspacing=0 cellpadding=3>";
    print "<TR><TH>".AFF_G_DATE."</TH><TH>".AFF_G_TIME."</TH>";
    print "<TH>".AFF_C_REFERRERURL."</TH></TR>";
    while ($qry = mysql_fetch_array($result)) 
    {
      print "<TR>";
      print "<TD><font size=2>";
      print $qry[date];
      print "</TD>";
      print "<TD><font size=2>";
      print $qry[time];
      print "</TD>";
      print "<TD><font size=2>";
      print $qry[refferalurl];
      print "</TD>";
      print "</TR>";
    }
    print "</TABLE>";
  }

  include "footer.php"; 
?>
