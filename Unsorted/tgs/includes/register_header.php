<?php
if(!session_is_registered("sessionUser"))
{
	session_register("sessionUser");
}
if(!session_is_registered("sessionSiteId"))
{
	session_register("sessionSiteId");
}
include("includes/config.php");
include("includes/db_inc.php");
?>
<div align="center">
<table border="0" cellpadding="0" width="300" style="border: 1 solid #FFF8D0" height="100" bgcolor="#FFF8D0">
<tr class="text"><td width="600" valign="top" align="left" height="100">