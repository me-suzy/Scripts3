<?php
session_start();
include('config.php');
if($_SESSION['amfradmin'] != $ausername || $_SESSION['amfrpassw'] != $apassword) {
session_destroy();
header('Location: login.php');
exit;
}
$link = mysql_connect($host, $dbuser, $dbpass);
echo '<HTML>
<HEAD>
<TITLE>Admin</TITLE>
</HEAD>
<BODY BGCOLOR="white" LINK="blue" ALINK="blue" VLINK="blue">
<CENTER>
<H1>Manage users</H1>';
$action = $_REQUEST['action'];
$aauser = $_REQUEST['aauser'];
$email = $_REQUEST['aaemail'];
$message = $_POST['message'];
$key = $_REQUEST['key'];
if($action == "delete" && $aauser) {
$sql = "SELECT * FROM amfr_users WHERE user = '$aauser'";
$result = mysql_db_query($dbname, $sql);
while ($row = mysql_fetch_assoc($result)) {
$domain2 = $row["domain"];
}
$sql = "DELETE FROM amfr_users WHERE user = '$aauser'";
$result3 = mysql_db_query($dbname, $sql);
$d2 = $domain2.".".$domain;
$result3 = file_get_contents("http://$cpuser:$cppass@localhost:2086/scripts/killacct?domain=$aauser&user=$aauser&submit-user=Terminate");
echo "$aauser deleted!<BR>";
echo "<A HREF='admin.php'>Back</A>";
}
elseif($action == "email" && $email) {
echo '<FORM ACTION="admin.php" METHOD="post">
Message: <TEXTAREA NAME="message" ROWS="15" COLS="80"></TEXTAREA><BR>
<INPUT TYPE="hidden" NAME="aaemail" VALUE="'.$email.'">
<INPUT TYPE="hidden" NAME="action" VALUE="email2">
<INPUT TYPE="submit" VALUE="Send Email">
</FORM>
<A HREF="admin.php">Back</A>';
}
elseif($action == "keys") {
echo '<A HREF="admin.php?action=newkey">Add new key</A><BR><A HREF="admin.php?action=listkeys">List keys</A><BR><A HREF="admin.php?action=delkeys">Delete keys</A><BR><A HREF="admin.php">Back</A>';
}
elseif($action == "newkey") {
echo '<FORM ACTION="admin.php" METHOD="post">
New key: <INPUT TYPE="text" NAME="key"><BR>
<INPUT TYPE="hidden" NAME="action" VALUE="newkey2">
<INPUT TYPE="submit" VALUE="Add Key">
</FORM>
<A HREF="admin.php?action=keys">Back</A>';
}
elseif($action == "newkey2" && $key) {
$sql = "INSERT INTO amfr_keys values('$key')";
$result10 = mysql_db_query($dbname, $sql);
echo '<B>Key inserted!</B><BR><A HREF="admin.php?action=keys">Back</A>';
}
elseif($action == "delkey2" && $key) {
$sql = "DELETE FROM amfr_keys WHERE amfrkey='$key'";
$result11 = mysql_db_query($dbname, $sql);
echo '<B>Key deleted!</B><BR><A HREF="admin.php?action=keys">Back</A>';
}
elseif($action == "listkeys") {
if($mkeys == "yes")
{
echo "<B>Keys:</B><BR>";
}
else
{
echo "<B>Unused keys:</B><BR>";
}
$sql = "SELECT * FROM amfr_keys";
$ukeys = mysql_db_query($dbname, $sql);
while ($row = mysql_fetch_assoc($ukeys)) {
$key = $row['amfrkey'];
echo "$key<BR>";
}
if($mkeys == "yes")
{
echo "<BR><B>Key usage:</B><BR>";
}
else
{
echo "<BR><B>Used keys:</B><BR>";
}
$sql = "SELECT * FROM amfr_used_keys";
$dkeys = mysql_db_query($dbname, $sql);
while ($row = mysql_fetch_assoc($dkeys)) {
$key = $row['amfrkey'];
$user = $row['user'];
echo "$key - Used by $user<BR>";
}
echo '<BR><A HREF="admin.php?Action=keys">Back</A>';
}
elseif($action == "delkeys") {
echo "<B>Click on a key to delete it:</B><BR>";
$sql = "SELECT * FROM amfr_keys";
$delkeys = mysql_db_query($dbname, $sql);
while ($row = mysql_fetch_assoc($delkeys)) {
$key = $row['amfrkey'];
echo "<A HREF='admin.php?action=delkey2&key=$key'>$key</A><BR>";
}
echo '<A HREF="admin.php?action=keys">Back</A>';
}
elseif($action == "email2" && $email && $message) {
mail($email, 'Free Hosting Message', $message, 'From: Automailer');
echo "<B>Message sent!</B><BR><A HREF='admin.php'>Back</A>";
}
elseif($action == "massmail") {
echo '<FORM ACTION="admin.php" METHOD="post">
Message: <TEXTAREA NAME="message" ROWS="15" COLS="80"></TEXTAREA><BR>
<INPUT TYPE="hidden" NAME="action" VALUE="mmail">
<INPUT TYPE="submit" VALUE="Send Email">
</FORM>
<A HREF="admin.php">Back</A>';
}
elseif($action == "mmail" && $message) {
$sql = "SELECT * FROM amfr_users";
$result8 = mysql_db_query($dbname, $sql);
while ($row = mysql_fetch_assoc($result8)) {
mail($email, "Free Hosting Message", $message, "From: Automailer");
}
echo "<B>Messages sent!</B><BR><A HREF='admin.php'>Back</A>";
}
else {
echo '<A HREF="admin.php?action=massmail">Mass mail users</A><BR><A HREF="admin.php?action=keys">Manage keys</A><BR>';
echo "Users:<BR>";
$sql = "SELECT * FROM amfr_users";
$result8 = mysql_db_query($dbname, $sql);
while ($row = mysql_fetch_assoc($result8)) {
$user = $row['user'];
$email = $row['email'];
$domain = $row['domain'];
echo "<A HREF='http://$domain'>$user</A> | <A HREF='admin.php?action=delete&aauser=$user'>Delete</A> | <A HREF='admin.php?action=email&aaemail=".urlencode($email)."'>Email ($email)</A><BR>";
}
}
echo "</CENTER></BODY></HTML>";
mysql_close($link);
?>