<?
session_start();
require_once("php_inc.php");
include("header_inc.php");
db_connect();

if (session_is_registered("valid_user"))
{
 do_html_heading("Options");
 member_menu();
 
 
 
 if ($submit)
 {
 	$sql = "update user set wantSpecial = 1, prefs = '$new_prefs', email = '$new_email' where username = '$valid_user'";
 	$resu = mysql_query($sql);
 	print "<p /><b>Updated!</b><p />";
 }
 
 
 $sql = "select * from user where username = '$valid_user'";
 $result_sq = mysql_query($sql);
 $row = mysql_fetch_array($result_sq);
 $username = $row["username"];
 $prefs = $row["prefs"];
 $email = $row["email"];
	
 
 ?> 

<form method="post" action="option.php">
<b>Request special status</b>
<br />
Special status makes your image rotate randomly on our frontpage,
wich may increase the number of people visiting your ad. 
<br />
<input type='checkbox' name='wantSpecial'> Yes, I want this service
<p />

<b>Default admin delivery method</b><br />
<input type="radio" name="new_prefs" value='local' <? if ($prefs == 'local') { echo "checked"; } ?>>Deliver it locally<br />
<input type="radio" name="new_prefs" value='email' <? if ($prefs == 'email') { echo "checked"; } ?>>Deliver it to emailaddress<br />
<p /> 

<b>My emailaddress</b><br />
<input type='text' name='new_email' value='<? echo $email ?>'>
<p />


<input type="submit" name="submit" value="Update">
</form>
	        
<?
}
require("footer_inc.php");
?>
