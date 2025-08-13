<? require("admheader.php"); ?>

<?
if (empty($offset))
{
 $offset=0;
}
$limit = 20;

?>
<form method=post action=list_users.php>
Simple search: <input type=text name=find value="<? echo $find ?>">
<input type=submit name=submit value=Submit>
</form>
<p />

<form method=post action=list_users.php>
Show people not logged in for days: 
<input type=text name=days value="100" size=3>
<input type=submit name=submit value=Submit>
</form>



Show only people who: 
<a href='list_users.php?wantSpecial=1'>Want to be featured</a> | 
<a href='list_users.php?isSpecial=1'>Is featured</a> |
<a href='list_users.php'>All</a> 
<p />


<?
$old_date = date ("Ymd", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$days,date("Y")));
$old_date_konv = date ("d.m.Y", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$days,date("Y")));


if ($delete AND $userid)
{
           $sql = "delete from user where username = '$userid'";
           $result = mysql_query($sql);
           
           $sql = "delete from matchprofiles where username_p1 = '$userid' OR username_p2 = '$userid'";
           $result = mysql_query($sql);
           
           $sql = "delete from mail where mail_to = '$userid'";
           $result = mysql_query($sql);
           
}

if ($banid)
{
				$sql_ban = "update user set ban = 1 where username = '$banid'";
            	$result = mysql_query($sql_ban);
            	print "<hr><font face='Verdana' size='1'>User $banid has been blocked from login.</font><hr>";	
}
if ($make_unspecial)
{
				$sql = "update user set isSpecial = 0 where username = '$make_unspecial'";
            	$result = mysql_query($sql);
            	print "<hr><font face='Verdana' size='1'>User $make_unspecial has been made UNSPECIAL.</font><hr>";	
}
if ($make_special)
{
				$sql = "update user set isSpecial = 1 where username = '$make_special'";
            	$result = mysql_query($sql);
            	$sql = "update user set wantSpecial = 0 where username = '$make_special'";
            	$result = mysql_query($sql);
            	
            	print "<hr><font face='Verdana' size='1'>User $make_special has been made SPECIAL.</font><hr>";	
}



if ($banid_remove)
{
				$sql_ban = "update user set ban = '' where username = '$banid_remove'";
            	$result = mysql_query($sql_ban);
            	print "<hr><font face='Verdana' size='1'>User $banid_remove has been activated.</font><hr>";	
}

       
           
if (!$order)
{
		$order = "username";	
}	


if ($find)
{
	
	$sql_links = "select * from user where username like '%$find%' OR email like '%$find%' order by $order limit $offset,$limit";
	$sql_total = "select * from user where username like '%$find%' OR email like '%$find%'";
}
elseif ($days)
{
	$sql_links = "select * from user where lastlogin<$old_date order by lastlogin limit $offset, $limit";
	$sql_total = "select * from user where lastlogin<$old_date";
	$sql_cnt = "select count(*) from user where lastlogin<$old_date";
	$result_cnt = mysql_query($sql_cnt);
	$rad_cnt = mysql_fetch_array($result_cnt);
	$num_del = $rad_cnt["count(*)"];
		
	print "<hr>Do you want delete these <b>$num_del</b> users ?<br><a href='list_users.php?massdel=$days'><font color=red>Yes, delete
	users</a></font> that have not logged in since $old_date_konv !	</a><hr>";
		
	if ($sql_del)
	{
			$sql_links = "delete from user where lastlogin<$old_date";
			mysql_query($sql_del);
			print "<hr>Old users deleted!<hr>";
	}
   		
}
elseif ($isSpecial)
{
		$sql_links = "select * from user where isSpecial = 1 order by $order limit $offset,$limit";
		$sql_total = "select * from user where isSpecial=1";
	
}
elseif ($wantSpecial)
{
		$sql_links = "select * from user where wantSpecial = 1 order by $order limit $offset,$limit";
		$sql_total = "select * from user where wantSpecial=1";
}
else 
{
		$sql_links = "select * from user order by $order limit $offset,$limit";
		$sql_total = "select * from user";
		
}
$linkparam = "&order=$order&days=$days&isSpecial=$isSpecial&wantSpecial=$wantSpecial&old_date=$old_date&find=$find";

print "<hr>$sql_links<hr>";


//$sql_links = "select * from $usr_tbl order by email desc limit $fra, $til";
$sql_result = mysql_query ($sql_links);
$num_links =  mysql_num_rows($sql_result);

// Link generator
$numresults = mysql_query($sql_total);
$numrows=mysql_num_rows($numresults);


if ($numrows > 0)
{
		print "<font face='Verdana' size='1'><b>$numrows</b> users was found matching your criteria.</font><p>";
}


print("<table width='100%' border='0' cellpadding=2 cellspacing=0>");
print "<tr><td valign=top><font face='Verdana' size='1'><a href='list_users.php?order=username asc&find=$find'><b>USERNAME</b></a></font></td><td valign=top><font face='Verdana' size='1'><a href='list_users.php?order=email asc&find=$find'><b>EMAIL</b></a></font></td><td valign=top><font face='Verdana' size='1'><a href='list_users.php?order=lastlogin desc&find=$find'><b>LAST LOGIN</b></a></font></td><td valign=top><font face='Verdana' size='1'><a href='list_users.php?order=lastviewed desc&find=$find'><b>LAST VIEWED</b></a></font></td><td valign=top><font face='Verdana' size='1'># MATCH</font></td><td valign=top><font face='Verdana' size='1'>ACTION</font><p></td></tr>";

for ($i=0; $i<$num_links; $i++)
{
            
	        		$row = mysql_fetch_array($sql_result);
	                $userid = $row["username"];
	                $name = $row["name"];
	                $email = $row["email"];
	                $registered  = $row["registered"];
	                $lastlogin = $row["lastlogin"];
	                $lastviewed = $row["lastviewed"];
	                $credits = $row["credits"];
	                $registered = $row["registered"];
	                $status = $row["status"];
	                $ban = $row["ban"];
	                $isSpecial = $row["isSpecial"];
	                $wantSpecial = $row["wantSpecial"];
	                $year=substr($row[lastlogin],0,4);
	                $month=substr($row[lastlogin],4,2);
	                $day=substr($row[lastlogin],6,2);
	                $lastlogin = "$day.$month.$year";
	                
					$year=substr($row[lastviewed],0,4);
	                $month=substr($row[lastviewed],4,2);
	                $day=substr($row[lastviewed],6,2);
	                $lastviewed = "$day.$month.$year";
	                
	                
	                if ($col == "#EBEBEB")
	                {
	                	$col = "#FFFFFF";	
	                }
	                else
	                {
	                	$col = "#EBEBEB";	
	                }
	               
	                $result2 = mysql_query ("select count(*) from matchprofiles where (username_p1 = '$userid') OR (username_p2 = '$userid')");
  					$rad = mysql_fetch_array($result2);
  					$num_match = $rad["count(*)"];
	                
	                print "<tr bgcolor=$col><td><font face='Verdana' size='1'><a href='../search.php?siteid=$siteid&adv=1'>$userid</a></font></td><td><font face='Verdana' size='1'>$email</font></td><td><font face='Verdana' size='1'>$lastlogin</font></td><td><font face='Verdana' size='1'>$lastviewed</font></td><td><font face='Verdana' size='1'>$num_match</font></td><td><font face='Verdana' size='1'>";
	                if ($credits_option)
	                {
	                	print "<a href='list_users.php?credituserid=$userid&credits=$credits'>Add Credit</a> | ";
	                	print "<a href='list_users.php?credituseridsubtract=$userid&credits=$credits'>Subtr. Credit</a> | ";
	                	
	                }
	                if (!$ban)
	                {
	                	print "<a href='list_users.php?banid=$userid'>Ban</a> | ";
	                }
	                elseif ($ban)
	                {
	                	print "<a href='list_users.php?banid_remove=$userid'>Activate</a> |";
	                	
	                }
	                
	                if ($isSpecial)
	                {
	                		print "<a href='list_users.php?make_unspecial=$userid&$linkparam'>Make UnSpecial</a> | ";
	                }
	                elseif ($wantSpecial)
	                {
	                		print "<b><a href='list_users.php?make_special=$userid$linkparam'>Require special</a></b> | ";
	                }
	                else 
	                {
	                			print "<a href='list_users.php?make_special=$userid$linkparam'>Make Special</a> | ";
	                }
	                
	                
	                
	                print "<a href='list_users.php?delete=$userid$linkparam'>Delete</a></font></td></tr>";
}
print("</table>");
?>
<p>
<?
if (!$find)
{
?>
<table border="0" cellpadding="1" cellspacing="0" width="100%">
<tr><td><font face='Verdana' size='1'>Results:</font></td></tr>
<tr><td>
<?

if ($offset==1) { // bypass PREV link if offset is 0
    $prevoffset=$offset-20;
    print "<font face=Verdana size=1><a href=\"$PHP_SELF?offset=$prevoffset$URLNEXT&$linkparam\">PREV</a> </font>\n";
}

// calculate number of pages needing links
$pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division
if ($numrows%$limit) {
    // has remainder so add one page
    $pages++;
}

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<font face=Verdana size=1><a href=\"$PHP_SELF?offset=$newoffset$URLNEXT&$linkparam\">$i</a> </font>\n";
}

// check to see if last page
if (!(($offset/$limit)==$pages) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<font face=Verdana size=1><a href=\"$PHP_SELF?offset=$newoffset$URLNEXT&$linkparam\">NEXT</a></font>\n";
}
?>
<?
} // End if not search
?>

<? require("admfooter.php"); ?>