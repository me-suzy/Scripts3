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
    or die ("Database CONNECT Error (line 20)"); 
  $result = mysql_db_query($database, "select * from clickthroughs where refid = '".$_SESSION['aff_valid_user']."' ORDER BY date and time") 
    or die ("Database INSERT Error"); 
		
  echo "<br><br><font face=arial>".AFF_C_CLICKS.": ";
  print mysql_num_rows($result);
  print "<br><br>";
  
  if (mysql_num_rows($result)) 
  {
    echo "<font face=arial><TABLE border=1 cellspacing=0 cellpadding=3>";
    echo "<TR><TH>".AFF_G_DATE."</TH><TH>".AFF_G_TIME."</TH>";
    echo "<TH>".AFF_C_REFERREDFROM."</TH></TR>";
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
