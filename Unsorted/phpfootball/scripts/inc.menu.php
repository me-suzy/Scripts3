<?php include("inc.auth.php"); ?>

<?php
$date = date('Y-m-d');

if ($userlev == "guest")  { echo "Not Logged in &nbsp;"; }
if ($userlev == "user" | in_array("$userlev", $admins) ) {
	echo "Logged in as : $user &nbsp;";
	echo "<a href=cookie.php?logout=1 target=_parent>Logout</a> ";
	echo "Userlevel : $userlev &nbsp;";
}
if (in_array("$userlev", $admins) ) {
echo "<a href=index.php target=_parent>Admin</a> ";
}
echo "Date: $date &nbsp;";
echo "<a href=scripts/help.php target=_parent>Help</a> ";
?>
