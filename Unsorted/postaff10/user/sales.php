<?
  session_start();

  include "../affconfig.php";
  include "./lang/$language";

  if(!aff_check_security())
  {
    aff_redirect('index.php');
    exit;
  }
  
  include "header.php"; 
    	
  mysql_connect($server, $db_user, $db_pass) 
    or die ("Database CONNECT Error (line 18)"); 
  $result = mysql_db_query($database, "select * from sales where refid = '".$_SESSION['aff_valid_user']."' ORDER BY date and time") 
    or die ("Database INSERT Error"); 
		
  echo "<br><br><font face=arial>".AFF_S_SALES.": ";
  print mysql_num_rows($result);
  print "<br><br>";
  
  if (mysql_num_rows($result)) 
  {
    print "<font face=arial><TABLE  border=1 cellspacing=0 cellpadding=5>";
    echo "<TR><TH>".AFF_G_DATE."</TH><TH>".AFF_G_TIME."</TH>";
    echo "<TH>".AFF_S_EARNED."</TH></TR>";
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
      print $qry[payment];
      print " ";
      print $currency; 
      print "</TD>";
      print "</TR>";
    }
    print "</TABLE>";
  }
  
  print "<br><br>".AFF_S_TOTAL.": ";
  mysql_connect($server, $db_user, $db_pass) 
    or die ("Database CONNECT Error (line 47)"); 
  $set = mysql_db_query($database, "select SUM(payment) AS total from sales where refid = '".$_SESSION['aff_valid_user']."'") 
    or die ("Database INSERT Error (line 48)"); 
  $row = mysql_fetch_array( $set );
  print ($row['total'] != '' ? $row['total'] : '0');
	print " "; 
	print $currency; 
	print "<br><br>";

  include "footer.php";  
?>
 