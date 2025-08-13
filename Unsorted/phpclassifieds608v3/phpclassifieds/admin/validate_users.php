<? require("admheader.php"); ?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td bgcolor="lightgrey">
 
&nbsp; Subscription / Credit <br />
</td>
</tr>

<tr bgcolor="white">
<td width="100%">

<h3>Subscription AND/OR credit screen</h3>
 
Subscription mode is:
<? 
if ($approve_mem) { 
	print "<font color='green'>Activated</font>"; 	
}
else { print "<font color='red'>Deactivated</font>";}
?>
<br />
Credit mode is:
<? 
if ($credits_option) { print "<font color='green'>Activated</font>"; }
else { print "<font color='red'>Deactivated</font>";}
?>
<p>
Awaiting means that the user have requested extensions of his/her 
subscription period through the file Change.php in the member area.
</p>
 

<?

// Expire users

$result_top = mysql_query ("select * from $usr_tbl");
while ($row = mysql_fetch_array($result_top))
{
 $email = $row["email"];
 $months = $row["months"];
 $approve = $row["approve"];
 $approve_from = $row["approve_from"];
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
	
	
	$today_s = date("Ymd");
	
	
	if ($expire_s <= $today_s)
	{
		$sq = "update $usr_tbl set approve=0 where email='$email'";
		$re = mysql_query($sq);

		
	}
	
	
	
	//print "<tr><td> <a href='user.php?email=$email'>$email</a> </td><td> $today_h </td><td> $expire_h $ex </td></tr>";
}

//


// SQL COMMANDS START


// Approve
if ($app)
{
	$sql = "update $usr_tbl set approve = 1 where email='$app'";
	$result = mysql_query($sql);
	
	if ($result)
	{
		print "User $app is now approved for the new period.";	
	}
}


if ($reqc)
{
	$sql = "select credits from  $usr_tbl where email='$reqc'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$credits = $row[credits];
	
	$tot = $cre + $credits;
	
	$sql = "update $usr_tbl set credits = '$tot', request_credits='0' where email='$reqc'";
	$result_2= mysql_query($sql);
	
	if ($result_2)
	{
		print "User $reqc have been granted $cre credits.";	
		
	}
	
}





// SQL COMMANDS END
?>
<p />
<table border="0" width="70%">
<!-- <tr><td> User</a></td><td> From date</a></td><td> To date</a></td></tr> -->

<?

if ($approve_mem AND $credits_option)
{
	$order = "order by approve,request_credits asc";	
}
else
{
	$order = "";	
}

$result_top = mysql_query ("select * from $usr_tbl where approve = 0 OR credits = 0 $order");
while ($row = mysql_fetch_array($result_top))
{
 $email = $row["email"];
 $months = $row["months"];
 $approve = $row["approve"];
 $credits = $row["credits"];
 $request_credits = $row["request_credits"];
 
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
	
	
	$today_s = date("Ymd");
	
	if ($approve)
	{
		$ex = "(approved)";
	}
	else 
	{
		$ex = "(awaiting)";	
	}
	
	if ($expire_s <= $today_s)
	{
		$sq = "update $usr_tbl set approve=0 where email='$email'";
		$re = mysql_query($sq);
		$ex = "(expired)";
		
		
	}
	
	if ($request_credits)
	{
		$req = "<a href='validate_users.php?reqc=$email&amp;cre=$request_credits'>(Request $request_credits)</a>";	
	}
	else 
	{
		$req = "(Have $credits credits)";	
	}

	
	
	if ($ex == "(awaiting)")
	{
		$ex = "<a href='validate_users.php?app=$email'>$ex</a>";
	}
	elseif ($ex == "(awaiting)")
	{
		$ex = "<a href='validate_users.php?email=$email'>$ex</a>";
	}
	
	
	print "<tr>
	
	
	<td> <a href='user.php?email=$email'>$email</a> </td> 
	<td> $today_h </td> 
	<td> $expire_h </td> 
	<td> $ex </td> 
	<td> $req </td> 
	
	
	
	</tr>";
}

?>


</table>

 
</td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
