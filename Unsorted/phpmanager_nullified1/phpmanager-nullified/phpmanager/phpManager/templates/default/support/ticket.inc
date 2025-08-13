<table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="170" class="main_bold">
Ticket Number: <span class="summary_message"><?php echo $ticketno ?></span>
</td>
<td width="200" class="main_bold">
Username: <span class="summary_message"><?php echo $username ?></span>
</td>
<td width="200" class="main_bold">
Time: <span class="summary_message"><?php echo $date ?></span>
</td>
</tr>
<tr>
<td width="170" class="main_bold">
Status: <span class="summary_message"><?php echo $status ?></span>
</td>
<td width="200" class="main_bold">
Dealt By: <span class="summary_message"><?php echo $admin ?></span>
</td>
<td width="200" class="main_bold">
Subject: <span class="summary_message"><?php echo $subject ?></span>
</td>
</tr>
<tr>
<td width="570" colspan="3">
<table width="570" border="0" cellspacing="0" cellpadding="0"
<tr>
<td width="285" class="main_bold">
Email: <a href="mailto:<?php echo $email ?>" class="link"><?php echo $email ?></a>
</td>
<td width="285" class="main_bold">
URL: <a href="<?php echo $url ?>" target="_blank" class="link"><?php echo $url ?></a></span>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="570" colspan="3" align="center" class="main_bold">
<br>
- Description -
</td>
</tr>
<tr>
<td width="570" colspan="3">
<textarea cols="95" rows="13" class="formfield" readonly><?php echo $description ?></textarea>
</td>
</tr>
</table>