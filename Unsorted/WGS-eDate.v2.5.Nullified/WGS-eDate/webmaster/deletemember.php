<?
  require("../settings.php");
  require("../lib/mysql.lib");
  require("_header.php");

$db = c();

if (!$id) exit;

if ($sure)
{
if (!$mid) exit;
$r=q("DELETE FROM pictures where member='$mid'");
$r=q("DELETE FROM event where user_id='$mid'");
$r=q("DELETE FROM members where id='$mid'");
$r=q("DELETE FROM profiles where id='$mid'");
echo " Deleted.";
}else{
$mem=f(q("select * from members where id='$id'"));

echo "<b>DELETE MEMBER !</b><br><br>Username : $mem[login]<br>Full name : $mem[fname] $mem[lname]<br>Email : $mem[email]<br><br>Are you sure you want to delete this member with all attached information (pictures, inbox messages, profile) ? <B><a href=deletemember.php?mid=$id&id=$id&sure=1>YES!</a></B>";
}

	
d($db);
  require("_footer.php");
?>