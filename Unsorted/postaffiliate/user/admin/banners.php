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
    or die ("Database CONNECT Error (line 22)");
    
  $result = mysql_db_query($database, "select * from banners ORDER BY name asc") 
    or die ("Database Error"); 
		
  echo "<br><a href=banneradd.php>".AFF_B_ADDBANNER."</a><br>";
    
  print "<br><br><font face=arial>".AFF_B_BANNERSOFFER.":<br><br>";
  
  if (mysql_num_rows($result)) 
  {
    print "<font face=arial><TABLE width=100% align=center>";
    while ($qry = mysql_fetch_array($result)) 
    {
      print "<TR>";
      print "<TD width=10><font size=4>";
      print $qry[number];
      print "</TD>";
      print "<TD><font size=4 align=left>".$qry[name]." - <a href=banneredit.php?edit=".$qry[number].">".AFF_B_EDITBANNER."</a>";
      print "&nbsp;&nbsp;<a href=bannerdelete.php?delete=".$qry[number].">".AFF_B_DELETEBANNER."</a>";
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

  include "footer.php"; 
?>
