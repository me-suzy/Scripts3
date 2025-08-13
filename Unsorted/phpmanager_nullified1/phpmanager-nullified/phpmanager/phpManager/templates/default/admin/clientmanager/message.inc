<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center" valign="top">
<table width="570" border="0" cellspacing="0" cellpadding="2" style="border: 1 solid #000000">
<tr>
<td width="570" align="center" class="summary_message">
- Send Message -
<br>
<br>
</td>
</tr>
<tr>
<td width="420" align="right" class="main">
<form method="post" action="<?php echo $PHP_SELF ?>">
Clients Email Address: <input type="text" name="email" class="formfield">
<br>
Subject: <input type="text" name="subject" class="formfield">
<br>
Send To All clients: <input type="radio" name="allclients" value="Yes">Yes <input type="radio" name="allclients" value="No" checked>No
<br>
<br>
</td>
</tr>
<tr>
<td width="570" align="center" class="main">
Message:
<br>
<textarea cols="70" rows="12" name="message" class="formfield"></textarea>
</td>
</tr>
<tr>
<td width="570" align="center" class="main">
<input type="Submit" name="submit" value="Send" class="formfield">
</td>
</tr>
</table>
</td>
</tr>
</table>