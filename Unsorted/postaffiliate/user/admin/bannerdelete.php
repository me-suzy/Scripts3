<?
  session_start();

  include "../../affconfig.php";
  include "../lang/$language";

  if(!aff_admin_check_security())
  {
    aff_redirect('index.php');
    exit;
  }
  
  if ($_REQUEST['delete'])
	{
		  mysql_connect($server, $db_user, $db_pass) 
        or die ("Database CONNECT Error (line 13)");
        
		  mysql_db_query($database, "DELETE FROM banners WHERE number = ".$_REQUEST['delete']." LIMIT 1") 
        or die("Database DELETE Error"); 
        
    aff_redirect('banners.php');        
	}
  
  include "header.php";
  
  include "footer.php";
?>
