<?
  session_start();
  include "../affconfig.php";
  
  $_SESSION['aff_valid_user'] = '';
  unset($_SESSION['aff_valid_user']);
  
  aff_redirect('index.php');
?>

