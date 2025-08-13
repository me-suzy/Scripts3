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

  echo "<br><H4>Welcome</h4>";
  
  include "footer.php";    
?>
