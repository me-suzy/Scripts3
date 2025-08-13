<? require("admheader.php"); ?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td bgcolor="lightgrey">
   
  &nbsp; Users 
 </td>
</tr>

<tr bgcolor="white">
 <td width="100%">

<?
if (empty($offset))
{
 $offset=0;
}
$limit = 20;
require("../functions.php");
?>
<form method='post' action='list_users.php'>

 
Simple search:
 
<input type="text" name="find" value="<? echo $find ?>" />
<input type="submit" name="submit" value="submit" />
</form>

 
<a href="javascript:history.go(-1)">Back</a>


<?

if ($delete AND $userid)
{
           delete_user("$userid");
}
if ($creditpkg)
{
            
			$c = $n_c + $creditpkg;
	
	
			$sql_credits = "update $usr_tbl set credits = $c where email = '$email'";
            $result = mysql_query($sql_credits);
			
			print "<hr /> User has been given the package with $creditpkg credits. <hr />";
}


if ($credituseridsubtract)
{
            
			$sql_credits = "update $usr_tbl set credits = credits - 1 where email = '$credituseridsubtract'";
            $result = mysql_query($sql_credits);
			print "<hr /> User $credituserid has been removed 1 credit/ad. <hr />";
}

if ($banid)
{
				$sql_ban = "update $usr_tbl set status = 1 where email = '$banid'";
            	$result = mysql_query($sql_ban);
            	print "<hr /> User $banid has been blocked from login. <hr />";	
}
if ($unbanid)
{
				$sql_ban = "update $usr_tbl set status = '' where email = '$unbanid'";
            	$result = mysql_query($sql_ban);
            	print "<hr /> User $unbanid has been activated. <hr />";	
}
if ($delapp)
{
				$sql_ban = "update $usr_tbl set approve = 0 where email = '$userid'";
            	$result = mysql_query($sql_ban);
            	print "<hr /> User $userid is now marked as <i>not approved yet</i> member. <hr />";	
}

$result = mysql_query("select * from $usr_tbl where email = '$email'");
$numrows=mysql_num_rows($result);


$row = mysql_fetch_array($result);
$userid = $row["email"];
$name = $row["name"];
$email = $row["email"];
$registered  = $row["registered"];
$num_ads = $row["num_ads"];
$credits = $row["credits"];
$registered = $row["registered"];
$status = $row["status"];
$verify = $row["verify"];
$months = $row["months"];
$approve = $row["approve"];

$year=substr($row[registered],0,4);
$month=substr($row[registered],4,2);
$day=substr($row[registered],6,2);
$num_logged = $row["num_logged"];
$regdate = "$day.$month.$year";


$year=substr($row[last_login],0,4);
$month=substr($row[last_login],4,2);
$day=substr($row[last_login],6,2);


$last_login = "$day.$month.$year";

$year=substr($row[approve_from],0,4);
$month=substr($row[approve_from],4,2);
$day=substr($row[approve_from],6,2);

if (!$approve_from)
{
	$year=date(Y);
	$month=date(m);
	$day=date(d);

	
}
 
	$today = mktime ("Ymd", mktime(date("H"),date("i"),date("s"),$month,$day,$year) );
	$today_h = date ("d.m.Y", mktime(date("H"),date("i"),date("s"),$month,$day,$year) );
                
	$expire = mktime ("Ymd", mktime(date("H"),date("i"),date("s"),$month+$months,$day,$year) );
    $expire_h = date ("d.m.Y", mktime(date("H"),date("i"),date("s"),$month+$months,$day,$year) );
	$expire_s = date ("Ymd", mktime(date("H"),date("i"),date("s"),$month+$months,$day,$year) );


$sql_result99 = mysql_query ("select siteid from $ads_tbl where ad_username = '$email'");
$num = mysql_num_rows($sql_result99);
for ($i=0; $i<$num; $i++)
{
	$row99 = mysql_fetch_array($sql_result99);
	$siteid = $row99["siteid"];
	
	$r2 = mysql_query("select id from $pic_tbl where pictures_siteid = $siteid");
	$ant = mysql_num_rows($r2);
	 
	$num_pics = $num_pics + $ant;	
}



if ($add_months)
{
	$date = date(Ymd);
	$date_h = date("d.m.Y");
	$sql = "update $usr_tbl set months = '$add_months', approve_from='$date', approve=1 where email = '$email'";
	$result = mysql_query($sql);
	print "<p /><b>Updated!</b><br />User $email is now granted $add_months from this date ($date_h). ";
	print "Login will be blocked from login when subscription expires.";
	
	$subject = $sub_title;
	$message = $sub_msg;

	$sendto = "$email";
	$headers .= "From: $name_of_site<$from_adress_mail>\n";
	$headers .= "Reply-To: <$from_adress_mail>\n";
	$headers .= "X-Sender: <$from_adress_mail>\n";
	$headers .= "X-Mailer: PHP4\n"; //mailer
	$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
	$headers .= "Return-Path: <$from_adress_mail>\n";
	
	mail($sendto, $subject, $message, $headers);
}





print "
<p />
<b>Username/email:</b><br />
$name (<a href='mailto:$email'>$email</a>)
<p />

<b>Some dates:</b><br />
Last login: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$last_login<br /> 
Registered: &nbsp;&nbsp;&nbsp;$regdate<br />
Expires:  &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$expire_h<br /> 

<p />


<b>Simple stats:</b><br />
Num ads:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$num_ads<br />
Num pictures:&nbsp;&nbsp;$num_pics<br />
Logged in:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$num_logged
<p />
";

print "<b>Status:</b><br />";
if ($verify AND $opt_verify)
{
	print "This user has <u>not yet</u> confirmed/validated his/her emailaddress.<br />";	
}
elseif (!$verify AND $opt_verify)
{
	print "The user have confirmed/validated his/her emailaddress.<br />";	
}

if ($status == 1)
{
	print "This user is blocked from login by your request (admin).<br />";	
}
elseif ($approve_mem AND $approve)
{
		print "All privelegies.<br />";	
}
elseif (!$approve_mem)
{
		print "All privelegies.<br />";	
}	

if ($approve_mem AND !$approve)
{
		print "This user is awaiting validation for <b>$months</b> new months.<br />";	
}



print "<p /><b>Options:</b><br />
<a href='user.php?banid=$email&amp;email=$email'>Ban user</a><br />
<a href='user.php?unbanid=$email&amp;email=$email'>UnBan user</a><br />
<a href='user.php?userid=$email&amp;delete=1&amp;email=$email'>Delete user</a><br />
<a href='user.php?userid=$email&amp;delapp=1&amp;email=$email'>Remove approval</a><br />
<a href='user.php?email=$email&amp;credituseridsubtract=$email'>Remove one credit</a><br />";

        	
print "<p /><b>Credits</b>:<br />";
print "Current credits: $credits<br />";
print "<form method='post' action='user.php'>
<input type='hidden' name='n_c' value='$credits' />
<input type='hidden' name='email' value='$email' />
<select name='creditpkg'>";


$arr = file("config/credits.inc.php");
$ant = count($arr);

while($ant > $cnt)
{
	list($n1,$n2) = split(":", $arr[$cnt]);
	print "<option value='$n1'>$n2</option>\n";

	$cnt++;
	
}	

print "</select><input type='submit' name='submit' value='Add package' /></form><p /><p />";




print "<form method='post' action='user.php'>
<input type='hidden' name='old_months' value='$months' />
<input type='hidden' name='email' value='$email' />";
print "<p /><b>Subscription</b>:<br />";
print "Add monts and hereby activating the user.<br />";
print "<select name='add_months'>"; 

$array = split(",", $member_ab);
foreach ($array as $element)
{
    	print "<option value='$element'>$element</option>";
}
   
print "</select><input type='submit' name='submit' value='Add months' /></form>";

?>
 

<p />


</td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
