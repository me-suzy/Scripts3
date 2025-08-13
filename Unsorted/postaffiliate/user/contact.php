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
    	
  print "<p><br>".AFF_C_CONTACT." <a href=mailto:";
  print $emailinfo;
  print ">";
  print $emailinfo;
  print "</a>.";

  include "footer.php"; 
?>