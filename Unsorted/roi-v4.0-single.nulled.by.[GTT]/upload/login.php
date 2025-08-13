<?php

include("defines.php");

function LoginTo(){
  session_start();
  if(!isset($_SESSION['pass']) || $_SESSION['pass'] != PASSWORD){
   header("Location: index.php");
   exit(1);
  }
}

LoginTo();

?>
