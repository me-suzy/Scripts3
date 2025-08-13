<?php require("inc.layout.php"); ?>

<?php require("inc.auth.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<table width="90%" border="0" align="center">
  <tr> 
    <td class=tdark colspan="3"> 
      <div align="center"><b>PHPFootball Main Modules</b></div>
    </td>
  </tr>
<?php
$query = "SELECT * FROM Help";
$result = mysql_query($query) or die ("Failed read help<br>Debug info: $query");
$num_res = mysql_num_rows($result);
for ($i=0; $i<$num_res; $i++){ $myrow = mysql_fetch_assoc($result); }
foreach ($myrow as $row_n=> $row_v){
	echo "<tr><td class=tddd><b>$row_n</b></td></tr><tr><td class=td>$row_v</td></tr>";
}
?>
</table>

<p>&nbsp;</p>

<table width="90%" border="0" align="center">
  <tr> 
    <td class=tdark colspan="2"> 
      <div align="center"><b>PHPFootball Bottom Control Tabs</b></div>
    </td>
  </tr>
  <tr> 
    <td class=tddd width="33%"><b>The show tab</b></td>
    <td class=tddd width="33%"><b>The filter tab</b></td>
  </tr>
  <tr> 
    <td class=td width="33%" height="101"> 
      <p>This allows you to do case-insensitive searches <br>
        It allows pattern matching so you can use `_' to match any single character 
        and `%' to match an arbitrary number of characters.<br>
        <b>Examples :</b> <br>
        find text beginning with `lon': <b>lon%</b><br>
	find text ending with `ited': <b>%ited</b><br>
        find text containing a `word': <b>%word%</b><br>
	find text containing exactly 5 chars' : <b>_____</b><br>
	You can even enter a date boundary to only show the results in a time frame
      </p>
    </td>
    <td class=td width="33%" height="101">This allows you to see a list of all things unique 
      ( all teams , all event types , all town names in wich games were played etc..<br><br>
	First you select from the 2 dropdown boxes the Cathegory then the Section to get the results for.</td>
  </tr>
</table>
<p>&nbsp;</p>

<?php if (in_array("$userlev", $admins)) { echo "
<table width=90% border=0 align=center>
  <tr> 
    <td class=tdark colspan=3> 
      <div align=center><b>Administration help topics</b></div>
    </td>
  </tr>
  <tr> 
    <td class=td width=99% height=101 colspan=3> 
      <p>
Only Administrators are allowed access to the PHPFootball Control Pannel and only they see this help section<br>
The PHPFootball CP can be used to manage the PHPFootball setup , configuration, layout and to manage and use available  modules<br>
From there you can also do data backup in order to ease migration, updating and to be safe in case of server failure<br>
The layout panel allows admins to setup custom header/footer content and what data is presented on the indexes and how<br>
Nevertheless functionall the late data presenting configuration for the indexes is somewhat crude at the moment and needs improving<br>
Most of the times however the admin will use the Manage Modules tab as from there the actual wizards for each module are acessed<br>
Adminsitrators see in a white table on the bottom of all data displays the coman that was used to create that display for website use<br>
	</p>
    </td>
  </tr>
<tr>
    <td class=tdark width=33%> 
      <center>Data entering guidelines</center>
    </td>
</tr>
<tr>
    <td class=tddd width=33%> 
      Website addresses
    </td>
</tr>
<tr>
    <td class=td width=33%> 
      <p>Website adreses must be 50 characters maximum and must include a heading www. and NOT include a heading http:// or ending /</p>
    </td>
</tr>
<tr>
    <td class=tddd width=33%> 
      Email addresses
    </td>
</tr>
<tr>
    <td class=td width=33%>
	<p>Email adresses must be maximum 30 characters big but they dont have any other restrictions beside a valid format</p> 
	</td>
</tr>
</table>
<p>&nbsp;</p>
"; } ?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
