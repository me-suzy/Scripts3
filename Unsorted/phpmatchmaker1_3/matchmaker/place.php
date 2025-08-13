<?
require_once("php_inc.php"); 
session_start();
db_connect();
include("header_inc.php");
do_html_heading("User sorted by place");
member_menu();
?>			
<p>

<table border="0" cellpadding="2" width="100%">
  <tr>
    <td bgcolor="#D8D4D8" width="10"><font size="2" face="Arial">
				<b>Place</b></font>
		</td>
    
		<td bgcolor="#D8D4D8" width="10"><font size="2" face="Arial" color="#585458">
				<b>Male</b></font>
		</td>
	  <td bgcolor="#D8D4D8"><font size="2" face="Arial" color="#585458">
				<b>Femaile</b></font>
		</td>
		    
		<td bgcolor="#D8D4D8"><font size="2" face="Arial" color="#585458">
				<b>Couple</b></font>
		</td>

  </tr>
<?	
$string = "select DISTINCT user.place from user where place <> '' order by place asc";
$result = mysql_query($string);
while ($row=mysql_fetch_array($result)) 
{
 $place = $row[place];


 print "<tr><td bgcolor='#F8F4F8' width='50%'>";
 print "<font size='2' face='Arial'>";
 print "$place</font></td>";
 print "<td bgcolor='#F8F4F8' width='15%'>";
 print "<font size='2' face='Arial' color='#585458'><a href='search_result.php?gsearch=1&psearch=1&sex=Male&place=$place'>Male</a></font></td>";
 print "<td bgcolor='#F8F4F8' width='15%'><font size='2' face='Arial' color='#585458'>";
 print "<a href='mailbox.php?msgid=$mailid'><a href='search_result.php?gsearch=1&psearch=1&sex=Female&place=$place'>Female</a></font></td>";
 print "<td bgcolor='#F8F4F8' width='15%'><font size='2' face='Arial' color='#585458'>";
 print "<a href='detail.php?profile=$mail_from'><a href='search_result.php?gsearch=1&psearch=1&sex=Couple&place=$place'>Couple</a></font></td>";
 print "</tr>";

}
?>
</table>
<?
include("footer_inc.php");
?>