<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
// print("<h3>$name_of_site</h3>");
include_once("member_header.php");
check_valid_user();
?>

<table border="0" cellspacing="1" width="100%">
<tr>
    <td width="100%" valign="top"><b><? echo $la_change; ?></b><br />
<?
if ($submit)
{
 include "array.php";
 if ($months_new<>"")
 {
 	$from_date = date("Ymd");
 	$reset = ", approve='0', approve_from='$from_date'";
 	
 }
 $sql_update = "update $usr_tbl set name='$new_name', usr_1='$usr_1_inn',usr_2='$usr_2_inn',usr_3='$usr_3_inn',usr_4='$usr_4_inn',usr_5='$usr_5_inn',hide_email='$hide_email',emelding='$emelding',months='$months_new',request_credits='$credits' $reset where email = '$valid_user'";
 $result = mysql_query($sql_update);
 print "$la_info_changed";
 
}
$result = mysql_query ("select * from $usr_tbl where email = '$valid_user'");
$row = mysql_fetch_array($result);
$userid = $row["email"];
$name = $row["name"];
$email = $row["email"];
$registered  = $row["registered"];
$num_ads = $row["num_ads"];
$hide_email = $row["hide_email"];
$emelding = $row["emelding"];
$usr_1 = $row["usr_1"];
$usr_2 = $row["usr_2"];
$usr_3 = $row["usr_3"];
$usr_4 = $row["usr_4"];
$usr_5 = $row["usr_5"];
$credits = $row["credits"];
$months = $row["months"];
$approve_from = $row["approve_from"];
?>
    
    
<form method="post" action="change.php">
<table width="100%" cellspacing="0">
<tr>
	<td valign="top"><? echo $add_user_name ?><p /></td>
   	<td valign="top">
   		<input type="text" name="new_name" value="<? print $name; ?>" />
   </td>
</tr>

<?
if ($approve_mem AND $member_ab)
{
    
	print "<tr><td>$la_change_abb:<br />";
	 
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
	
	// Membership expires
	print "$la_expires $expire_h";
	
	print "<p /></td><td>";

	print "<select name='months_new'>"; 
    print "<option>$la_nochange</option>"; 
    $array = split(",", $member_ab);
    
    foreach ($array as $element)
    {
    	print "<option value='$element'>$element</option>";
    }
    
    print "</select>";
    
    
    
    print "</td></tr>";
}


if ($credits_option)
{
	
	print "<tr><td valign=\"top\">";
	print "$la_credits_remaining: $credits<br />";
	print "$la_more_credits";
	print "
	<td>
	<select name='credits'>
	<option value='0'>$la_nochange</option>\n";
	
	$arr = file("admin/config/credits.inc.php");
	$ant = count($arr);
	
	while($ant > $cnt)
	{
		list($n1,$n2) = split(":", $arr[$cnt]);
		print "<option value='$n1'>$n2</option>\n";
	
		$cnt++;
		
	}	
	
	print "</select><p /></td></tr>";	
}

?>
     


<tr>
	<td valign="top"><? echo $la_news ?></td>
   	<td valign="top">
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" align="left"><input type="checkbox" name="emelding" value="1" <? if ($emelding == 1) { print "checked='checked'"; } ?> 
              /></td>
            <td valign="top" align="left"><img border="0" src="http://www.wirpoint.ch/images/spacerbig.gif" width="3" height="3"><br>
		<? print "$la_no_email_please"; ?></td>
          </tr>
          <tr>
            <td valign="top" align="left"><input type="checkbox" name="hide_email" value="1" <? if ($hide_email == 1) { print "checked='checked'"; } ?> 
              /></td>
            <td valign="top" align="left"><img border="0" src="http://www.wirpoint.ch/images/spacerbig.gif" width="3" height="3"><br>
       	<? print "$la_hide_email"; ?></td>
          </tr>
        </table>
       	
   </td>
</tr>

 
 
<? include "fields.inc.php"; ?>
<tr>
	<td colspan="2" align="left">
	&nbsp;<p />
		<input type="submit" name="submit" value="<? echo $la_change_info ?>" />
	</td>
</tr>
 </table>
 </form>
 </td></tr></table>
<?
include_once("member_footer.php");
include_once("admin/config/footer.inc.php");
?>
