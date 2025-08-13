<?
  session_start();

  include "../../affconfig.php";
  include "../lang/$language";

  if(!aff_admin_check_security())
  {
    aff_redirect('index.php');
    exit;
  }
  
  if ($_REQUEST['aff'])
	{
    mysql_connect($server, $db_user, $db_pass) 
      or die ("Database CONNECT Error (line 12)"); 
    
    mysql_db_query($database, "DELETE FROM sales WHERE refid = '".$_REQUEST['aff']."'") 
      or die("Database INSERT Error"); 
    
    mysql_db_query($database, "DELETE FROM clickthroughs WHERE refid = '".$_REQUEST['aff']."'") 
      or die("Database INSERT Error"); 
        
    aff_redirect('affiliates.php');         
	}
	  
  include "footer.php";   
?>
