<table width="570" border="0" cellspacing="0" cellpadding="0">
        <tr>
		<td width="570" height="360" align="center" class="main">
<form method="post" action="modifystaff.php">
<table width="570" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
        <tr>
		<td width="570" align="center" class="summary_message">
            - View Staff Member -
		<br>
		<br>
            </td>
        </tr>
        <tr>
		<td width="570" align="center" class="main_bold">
		Staff ID: <input type="Text" size="3" name="id" value="<?php echo $id ?>" class="formfield" readonly>
		&nbsp;
		Username: <input type="Text" size="10" name="adminname" value="<?php echo $adminname ?>" class="formfield">
		&nbsp;  
		Group: <select name="new_access" class="formfield">
			<option value="<?php echo $access ?>"><?php echo $access ?></option>
			<option value="Superadmin">Superadmin</option>
			<option value="Admin">Admin</option>
			<option value="Support">Support</option>
			</select>
            <br>
		<br>
		<center>
		<input type="Submit" name="submit" value="Edit Staff" class="formfield">
		&nbsp;
		<button onclick="window.location='deletestaff.php?id=<?php echo $id ?>&delete=yes'" class="formfield">Delete Staff</button>
		&nbsp;
		<button onclick="window.location='viewstaff.php'" class="formfield">Back</button>
		</center>
		</td>
	</tr>
</table>
</form>
<form method="post" action="modifystaff.php?password=yes">
<table width="570" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
        <tr>
		<td width="570" align="center" class="summary_message">
            - Change Password -
		<br>
		<br>
                </td>
        </tr>
        <tr>
		<td width="570" class="main_bold" align="center">
		<input type="hidden" name="id" value="<?php echo $id ?>">
		New Password: <input type="Text" size="32" name="password" class="formfield" value="">
		&nbsp;
		<input type="Submit" name="submit" value="Change" class="formfield">
		</td>
	</tr>
</table>
</form>
</td>
</tr>
</table>