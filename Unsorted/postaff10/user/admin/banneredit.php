<?
  session_start();

  include "../../affconfig.php";
  include "../lang/$language";

  if(!aff_admin_check_security())
  {
    aff_redirect('index.php');
    exit;
  }
  
	mysql_connect($server, $db_user, $db_pass) 
    or die ("Database CONNECT Error (line 11)"); 
      
  if($_POST['commited'] == 'yes')
  {
		mysql_db_query($database, "UPDATE banners SET name = '".$_POST['bannername']."', description = '".$_POST['bannerdesc']."' WHERE number = '".$_POST['edit']."'") 
      or die ("Database INSERT Error"); 
	      
    aff_redirect('banners.php');
  }
  
  include "header.php"; 
    	
	$result = mysql_db_query($database, "select * from banners WHERE number = '".$_REQUEST['edit']."' ORDER BY name asc") 
    or die ("Database Error"); 
		
  print "<br><br><font face=arial>Details For Banner ";
  print $edit;
  print ": ";
  print "<br><br>";
  
  if (mysql_num_rows($result)) 
  {
    print "<font face=arial><form method=post action=banneredit.php><TABLE width=100% align=center>";
    while ($qry = mysql_fetch_array($result)) 
    {
      print "<TR>";
      print "<TD>".AFF_B_BANNERNAME."</TD><TD><input type=text name=bannername value=";
      print $qry[name];
      print "></TD></TR><TR><TD>".AFF_B_BANNERDESCRIPTION."</TD><TD><textarea name=bannerdesc cols=50 rows=5>";
      print $qry[description];
      print "</textarea></TD></TR>";
      print "<input type=hidden name=edit value=".$_REQUEST['edit'].">";
      
    }
    
    print "<TR><TD colspan=2><center><input type=hidden name=commited value=yes><input type=submit value=".AFF_B_CHANGEBANNER."></center></TD></TR>";
    print "</TABLE>";
    print "</form>";
    print "<br><br>";
  }

  include "footer.php"; 
?>
