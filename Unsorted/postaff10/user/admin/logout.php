<?
  session_start();
  include "../../affconfig.php";
  
  $_SESSION['aff_valid_admin'] = '';
  unset($_SESSION['aff_valid_admin']);
  
  aff_redirect('index.php');
?>

