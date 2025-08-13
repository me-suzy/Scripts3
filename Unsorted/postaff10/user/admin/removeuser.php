<?
  session_start();

  include "../../affconfig.php";
  include "../lang/$language";

  if(!aff_admin_check_security())
  {
    aff_redirect('index.php');
    exit;
  }
  
  if ($_REQUEST['deleteuser'])
	{
	    mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 13)"); 
	    mysql_db_query($database, "DELETE FROM affiliates WHERE refid = '".$_POST['deleteuser']."' LIMIT 1") 
        or die("Database INSERT Error"); 
        
    aff_redirect('affiliates.php');        
	}
  
  include "header.php";
  
  include "footer.php";
?>
