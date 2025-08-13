<!-- Menu -->
<table border="0" width="150">
<tr>
<td align="center" class="LOGINHD">
<font class="LOGINHD">
Admin Menu
</font>
</td>
</tr>
<tr>
<td class="TBLROW">
<a href="AdminEnterFixture.php">Enter Fixture</a><br>
<!-- TODO
<a href="AdminRemoveFixture.php">Remove Fixture</a><br>
-->
<a href="AdminEnterResult.php">Enter Result</a><br>
<a href="ShowUsers.php">Show Users</a><br>
<a href="EmailAllUsers.php">Email All Users</a><br>
<?php
  if ($logfile != "") {
?>
<a href="ViewLog.php">View Log</a><br>
<?php
  }
?>
</td>
</tr>
</table>
<!-- End Menu -->
