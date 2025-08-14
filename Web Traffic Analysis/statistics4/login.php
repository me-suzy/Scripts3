<?php
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + showlog - detailed visitorstatistics with PHP 4.2.0 or higher
// + no database is required at all
// + Initially written by Daniel Sokoll 2000 - http://www.sirsocke.de
// +
// + Release v4.0.0:	05.01.2005 - Daniel Sokoll
// + Last Changes:	19.09.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$login = array();
include_once("config/statistics.conf.php");
include_once($tpl_file);

// --- check rights and init session ---
if(isset($_POST["u_name"]) && isset($_POST["u_pass"]) && isset($_POST["login"])) {
  for($i=1;$i<=sizeof($login);$i++) {
    if($_POST["u_name"] == $login[$i]["user"] && $_POST["u_pass"] == $login[$i]["pass"]) {
      session_control();
      $_SESSION["SHOWLOG"] = $login[$i]["right"];
      header("Location: ".$login_accept."?id=".session_id());
      exit;
    }
  }
  header("Location: ".$login_denied);
  exit;
}

// --- get languagespecific contexts ---
if (file_exists($lang_dir.$lang_code.".dat")) $lang = parse_ini_file($lang_dir.$lang_code.".dat",TRUE);
else { 
  $show = "error";
  $error_string[$lang_file] = "not found";  
}

// --- first part of the layout ---
show_document("top");

// --- Output now --- ?>
<script language="JAVASCRIPT" type="text/javascript"><!--
window.defaultStatus = " ";

function check_form() {
  if(document.data.u_name.value == "") {
    document.data.u_name.focus();
    return false;
  }
  if(document.data.u_pass.value == "") {
    document.data.u_pass.focus();
    return false;
  }
  return true;
}
// --></script>
<?php show_hr(); ?>
<form method="post" action="login.php" name="data" onsubmit="return check_form()">
<table border="0" cellspacing="1" cellpadding="2" width="100%" class="bgcolor" align="center" summary="SHOWlog loginform"><?php
if(isset($_GET["error"])) { ?>
<tr>
<td colspan="2" class="error"><?php 
if($_GET["error"] == 3) echo $lang["error"]["database"]; 
else echo $lang["error"]["login"]; 
?></td>
</tr>
<?php } ?><tr bgcolor="<?php echo $color["DEF"][2]; ?>">
<td colspan="2"><?php echo $lang["login"]["header"]; ?>:</td>
</tr><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td width="33%"><?php echo $lang["login"]["name"]; ?>:</td>
<td width="66%" align="center"><input type="text" name="u_name" value="" size="15"></td>
</tr><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td><?php echo $lang["login"]["pwd"]; ?>:</td>
<td align="center"><input type="password" name="u_pass" size="15"></td>
</tr><tr bgcolor="<?php echo $color["DEF"][2]; ?>">
<td colspan="2" align="right"><input type="submit" name="login" value="einloggen"></td>
</tr></table></form>
<?php show_hr(); ?>
<script language="JAVASCRIPT" type="text/javascript"><!--
  setTimeout('document.data.u_name.focus()', 300);
// --></script>
<?php // --- second part of the layout ---
show_document("bottom"); ?>