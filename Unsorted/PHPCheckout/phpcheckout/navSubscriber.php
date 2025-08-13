<!-- start of navSubscriber.php -->

<table class="favcolor" border=0 align="left" cellpadding=0><tr valign="middle">
<form name="backToAdminForm" method="post" action="admin.php">
<td valign="middle" align="center">
<input class="input" type="submit" name="Process" value="<-- Admin">
</td>
</form>


<form method="post" action="workWithSubscribers.php">
<td valign="middle" align="center">
<input class="input" type="submit" value="Subscribe">
<input type="hidden" name="task" value="Subscribe">
</td>
</form>


<form method="post" action="workWithSubscribers.php">
<td valign="middle" align="center">
<input class="input" type="submit" value="Unsubscribe">
<input type="hidden" name="task" value="Unsubscribe">
</td>
</form>


<form method="post" action="workWithSubscribers.php">
<td valign="middle" align="center">
<input class="input" type="submit" value="Launch Mailer">

</td>
</form>


</tr></table><br><br>
<!-- end of navSubscriber.php -->