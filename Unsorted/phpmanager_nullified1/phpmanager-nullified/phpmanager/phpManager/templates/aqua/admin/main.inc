<div id="logbar">
<span class="main">
<form method="post" action="<?php echo $PHP_SELF ?>">
Logged on as: <?php echo $logonname ?> | <?php echo strftime("$dateformat",time()); ?> | <a href="index.php" class="link">Logout</a>
| <select name="new_language" class="formfield">
<option value="<?php echo $language ?>">Language</option>
<option value="english.inc">English</option>
<option value="french.inc">French</option>
<option value="german.inc">German</option>
<option value="italian.inc">Italian</option>
<option value="spanish.inc">Spanish</option>
</select>
| <select name="new_template" class="formfield">
<option value="<?php echo $template ?>">Theme</option>
<option value="default">Default</option>
<option value="aqua">Aqua</option>
</select>
| <input type="submit" name="submit" value="OK" class="formfield">
</form>
</span>
</div>

<div id="maindivision">
<table width="620" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="620" align="center" class="main">
&nbsp;
</td>
</tr>
<tr>
<td width="620" align="center">
<iframe name="clients" frameborder="0" src="main.php" width="620" height="550"></iframe>
</td>
</tr>
<tr>
<td width="620" align="center" class="main">
Copyright © 2001 - 2002
<a href="http://www.phpmanager.com" class="copyrightlink" target="_blank">phpManager</a>
, All Rights Reserved |
<a href="contact.php" class="copyrightlink" target="clients">Contact Us</a>
</td>
</tr>
</table>
</div>