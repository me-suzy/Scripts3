<?php
require("admin.php");
require "../lib/mysql.lib";
$db = c();
  
  $mem= f(q("select login, pswd from members where id='$id'"));
  header("Location: ../login.php?action=submit&login=$mem[login]&pswd=$mem[pswd]");
  
?>
