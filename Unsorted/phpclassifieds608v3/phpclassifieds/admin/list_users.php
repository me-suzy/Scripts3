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
<input type="submit" name="submit" value="Submit" />
</form>

<?

if ($delete AND $userid)
{
           delete_user("$userid");
}


if ($approve)
{
				$sql_app = "update $usr_tbl set approve = 1 where email = '$userid'";
            	$result = mysql_query($sql_app);
            	print "<hr /> User $userid has been granted approval. <hr />";	
            	
            	require "config/mail.inc.php";
            	
            	$sendto = "$userid";
				$from = "$from_adress_mail";
				$subject = "$approve_user_title";
				$message = "$approve_user_msg";
				$headers = "From: $from\r\n";
				
				
				mail($sendto, $subject, $message, $headers);
            	
            	
}




           $numresults = mysql_query("select * from $usr_tbl order by email desc");
           $numrows=mysql_num_rows($numresults);
		
			if (!$order)
           	{
           		$order = "approve";	
           	}	
           
           
           if (!$find)
           {           

           	$sql_links = "select * from $usr_tbl order by $order limit $offset,$limit";
           }
           elseif ($find)
           {
           	$sql_links = "select * from $usr_tbl where email like '%$find%' OR name like '%$find%' order by $order limit 40";
           }
           //$sql_links = "select * from $usr_tbl order by email desc limit $fra, $til";
           $sql_result = mysql_query ($sql_links);
           $num_links =  mysql_num_rows($sql_result);
           
           if ($num_links > 40 AND $find)
           {
           		print " To many hits (>40). <p />";
           }
           
           if ($num_links > 0)
           {
           		print " <b>$num_links</b> users in this screen. <p />";
           }
           
			
			print("<table width='100%' border='0' cellpadding='2' cellspacing='0'>");
			print "<tr><td valign='top'> <a href='list_users.php?order=email asc&amp;find=$find'><b>EMAIL</b></a> </td><td valign='top'> <a href='list_users.php?order=name asc&amp;find=$find'><b>NAME</b></a> </td><td valign='top'> <a href='list_users.php?order=registered desc&amp;find=$find'><b>SIGNED UP</b></a> </td><td valign='top'> # ADS </td><td valign='top'> # CREDITS </td><td valign='top'> ACTION </td></tr>";
	        
			for ($i=0; $i<$num_links; $i++)
	        {
	                
	        		$row = mysql_fetch_array($sql_result);
	                $userid = $row["email"];
	                $name = $row["name"];
	                $email = $row["email"];
	                $registered  = $row["registered"];
	                $num_ads = $row["num_ads"];
	                $credits = $row["credits"];
	                $registered = $row["registered"];
	                $status = $row["status"];
	                $approve = $row["approve"];
	                $months = $row["months"];
	                $year=substr($row[registered],0,4);
	                $month=substr($row[registered],4,2);
	                $day=substr($row[registered],6,2);
	                $regdate = "$day.$month.$year";
	                $sql_result99 = mysql_query ("select siteid from $ads_tbl where ad_username = '$email'");
	                $row99 = mysql_fetch_array($sql_result99);
	                $siteid = $row99["siteid"];
	                
	                if ($col == "#EBEBEB")
	                {
	                	$col = "#FFFFFF";	
	                }
	                else
	                {
	                	$col = "#EBEBEB";	
	                }
	               
	                
	                
	                print "<tr bgcolor='$col'><td> <a href='../search.php?siteid=$siteid&amp;adv=1'>$userid</a> </td><td> <a href='user.php?email=$email'>$name</a> </td><td> $regdate </td><td> $num_ads </td><td> $credits </td><td> <a href='list_users.php?userid=$userid&amp;delete=1'>Delete user</a> ";
	              
	              
	                if (!$approve AND $member_ab)
	                {
	                	print " | <a href='list_users.php?userid=$userid&amp;approve=1'>Approve user($months)</a></td></tr>";
	                }
	                else 
	                {
	                	print "</td></tr>";	
	                }
	                
	                
	                
	        }
			print("</table>");
?>
<p />
<?
if (!$find)
{
?>
<table border="0" cellpadding="1" cellspacing="0" width="100%">
<tr><td> Results: </td></tr>
<tr><td>
<?
if ($offset==1) { // bypass PREV link if offset is 0
    $prevoffset=$offset-20;
    print " <a href=\"$PHP_SELF?offset=$prevoffset$URLNEXT&amp;order=$order\">PREV</a>  \n";
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
    print " <a href=\"$PHP_SELF?offset=$newoffset$URLNEXT&amp;order=$order\">$i</a>  \n";
}

// check to see if last page
if (!(($offset/$limit)==$pages) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print " <a href=\"$PHP_SELF?offset=$newoffset$URLNEXT&amp;order=$order\">NEXT</a> \n";
}
?>
<?
} // End if not search
?>
</td></tr></table>
</td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
