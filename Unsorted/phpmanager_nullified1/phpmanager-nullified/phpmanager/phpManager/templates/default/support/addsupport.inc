<table width="590" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="590" align="center" valign="top">
<table width="590" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
<tr>
<td width="590" align="center" class="summary_message">
- Submit Support Ticket-
<br>
<br>
</td>
</tr>
<tr>
<td width="420" align="right" class="main_bold">
<form method="post" action="<?php echo $PHP_SELF ?>?addnew=yes">
<input type="hidden" name="id" value="<?php echo $id ?>">
<input type="hidden" name="username" value="<?php echo $username ?>">
Contact Email: <input type="text" name="email" class="formfield">
<br>
URL (If Applicable): <input type="text" name="url" class="formfield">
<br>
Subject: <input type="text" name="subject" class="formfield">
<br>
<br>
</td>
</tr>
<tr>
<td width="570" align="center" class="main">
Description:
<br>
<textarea cols="94" rows="16" name="description" class="formfield"></textarea>
</td>
</tr>
<tr>
<td width="570" align="center" class="main">
<input type="Submit" name="submit" value="Submit" class="formfield">
</td>
</tr>
</table>
</td>
</tr>
</table>