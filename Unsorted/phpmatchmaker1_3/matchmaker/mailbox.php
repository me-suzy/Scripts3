<?
require_once("php_inc.php"); 
session_start();
include("header_inc.php");
db_connect();

if (session_is_registered("valid_user"))
{
 //print "<form method=post action=profile.php>";
 do_html_heading("Mailbox");
 member_menu();
			
 if ($delete)
 {
 $result = mysql_query("delete from mail where mailid = $mailid AND mail_to = '$valid_user'");
 				 if ($result)
				 {
				 	print "<p>Message with id $mailid is successfully deleted.";
				 }

 
 }


if (!$msgid)
{

 print "<form method=post action=mailbox.php>";
 print "<input type='hidden' name='delete' value='1'>";
?>

<p>

<table border="0" cellpadding="2" width="100%">
  <tr>
    <td bgcolor="#D8D4D8" width="10"><font size="2" face="Arial"><input type="checkbox" name="C1" value="ON"></font></td>
    <td bgcolor="#D8D4D8" width="10"><font size="2" face="Arial" color="#585458"><b>Status
      </b></font></td>
	  <td bgcolor="#D8D4D8"><font size="2" face="Arial" color="#585458"><b>Subject</b></font></td>
		    <td bgcolor="#D8D4D8"><font size="2" face="Arial" color="#585458"><b>From</b></font></td>
   
    <td bgcolor="#D8D4D8" width="100"><font size="2" face="Arial" color="#585458"><b>Date</b></font></td>
  </tr>
<?	
}
if ($msgid)
{
 $string = "select * from mail where mail_to = '$valid_user' AND mailid = $msgid";
}
else
{
 $string = "select * from mail where mail_to = '$valid_user' order by maildate, mailid desc";
}

$result = mysql_query($string);
while ($row=mysql_fetch_array($result)) 
{
 $mailid = $row[mailid];
 $mail_to = $row[mail_to];
 $mail_from = $row[mail_from];
 $title = $row[title];
 $maildate = $row[maildate];
 $message = nl2br($row[message]);
 $status = $row[status];

 $year = substr($maildate,0,4);
 $month = substr($maildate,4,2);
 $day = substr($maildate,6,2);
 $formatted_date = "$year-$month-$day"; 
 
if (!$msgid)
{ 

 print "<tr><td bgcolor='#F8F4F8' width='10'>";
 print "<font size='2' face='Arial'>";
 print "<input type='checkbox' name='mailid' value='$mailid'></font></td>";
 print "<td bgcolor='#F8F4F8' width='10'>";
 print "<font size='2' face='Arial' color='#585458'>$status</font></td>";
 print "<td bgcolor='#F8F4F8'><font size='2' face='Arial' color='#585458'>";
 print "<a href='mailbox.php?msgid=$mailid'>$title</a></font></td>";
 print "<td bgcolor='#F8F4F8'><font size='2' face='Arial' color='#585458'>";
  print "<a href='detail.php?profile=$mail_from'>$mail_from</a></font></td>";
 print "<td bgcolor='#F8F4F8' width='100'><font size='2' face='Arial' color='#585458'>";
 print "$formatted_date</font></td>";
 print "</tr>";
}
}
?>
</table>
<?

if (!$msgid)
{
 print "<p><input type='submit' value='Delete selected' name='submit'></form>";
}


?>



<? if ($msgid) { ?>
<p>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="#D8D4D8" colspan="4" valign="top">
      <font face="Arial" size="2">&nbsp; <b><? echo $title ?></b></font>
    </td>
  </tr>
  <tr>
<td bgcolor="#F8F4F8" valign="top"></td>
    <td bgcolor="#F8F4F8" valign="top">
		<font color="#585458" size="2" face="Arial">From:<br>
      To:<br>
      Date:<br>
      Status:</font></td>
    <td bgcolor="#F8F4F8" valign="top">
		<font color="#585458" size="2" face="Arial">
<?
print "<a href='detail.php?profile=$mail_from'>$mail_from</a><br>";
print "$mail_to<br>";
print "$formatted_date<br>";
if (!$status)
{
 $status = "New";
}
print "$status";
?>
</font></td>
    <td bgcolor="#F8F4F8" valign="top"></td>
  </tr>
  <tr>
    <td bgcolor="#F8F4F8" valign="top" width="2"></td>
    <td bgcolor="#F8F4F8" valign="top" colspan="3"><font size="2" face="Arial">
		
      <hr noshade size="1" align="left" width="98%">

		<? echo $message ?></font></td>
   
  </tr>
</table>


<?
$t = "Re: " . $title; 
print "<p>&nbsp;<a href='send.php?profile=$mail_from&title=$t'>Reply email</a>";
$read_string = "update mail set status = 'Read', maildate = '$maildate' where mailid = $mailid";
$result = mysql_query($read_string);



} ?>


<?



// ----- END OF CONTENT ----------- // 
}
else
{
	 		print "Session expired, please logon again.";
			exit;
}
include("footer_inc.php");
?>