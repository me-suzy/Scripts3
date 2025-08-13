<div id="topdivision">
<form method="post" action="<?php echo $PHP_SELF ?>">
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="250"><img src="../templates/<?php echo $template ?>/images/logo.gif" width="250" height="40"></td>
<td width="500" class="main">
Logged on as: <?php echo $clientname ?> | <?php echo strftime("$dateformat",time()); ?> | <a href="index.php" class="link">Logout</a>
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
</td>
</tr>
<tr>
</table>
</form>
</div>