<form method="post" action="<?php echo $PHP_SELF?>">
<input type="hidden" name="logonname" value="<?php echo $logonname ?>">
<input type="hidden" name="logonid" value="<?php echo $logonid ?>">
<table width="570" border="0" cellspacing="0" cellpadding="0">
        <tr>
		<td width="570" height="360" align="center" class="main">
		<table width="570" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
        	<tr>
			<td width="570" align="center" class="summary_message">
            	- Add Staff Member -
			<br>
			<br>
                	</td>
        	</tr>
        	<tr>
			<td width="570" class="main_bold">
			Username:<input type="Text" size="15" name="adminname" class="formfield">
			&nbsp;
			Password:<input type="Text" size="15" name="password" class="formfield">
			&nbsp;  
			Group: <select name="new_access" class="formfield">
			<option value="Superadmin">Superadmin</option>
			<option value="Admin">Admin</option>
			<option value="Support">Support</option>
			</select>
            	<br>
			<br>
			<center>
			<input type="Submit" name="submit" value="Add Staff" class="formfield">
			</center>
			</td>
		</tr>
		</table>
		</td>
	 </tr>
</table>
</form>