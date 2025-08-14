<html>
<body>
<div align=center>


<?
require("mod.guests.php");
$guests = new guests();

if (!empty($name) && !empty($email) && !empty($nick))
  $guests->add_res($name, $email, $nick, $clan);
else
  $guests->add_form();
?>


</div>
</body>
</html>