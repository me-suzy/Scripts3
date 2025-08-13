<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!--START OF adminConfirmAccess.php -->
<HTML>
	<HEAD>
		<?php require_once("configure.php");?>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		<TITLE>Confirm Access -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
</HEAD> 

<body>
<!-- START of body -->


<blockquote>
	<p><br></p>
	<h1>Confirm Access?</h1>

	<ul>
		<li>if the activity below <b>matches</b> your usage click &quot;Confirm Access&quot;</li>
		<li>if the activity below <b>does not match</b> your usage click &quot;Problem!&quot;</li>
	</ul>


<table bgcolor="sandybrown" border=15 cellpadding=5>
<tr>
	<th colspan=2>Confirm Access?</th>
</tr>
<tr>
	<td><b>Activity</b></td><td><b>Usage</b></td>
</tr>
<tr>
	<td>Login Attempts to Authenticate: </td><td><?php echo $sessionloginAttempts;?></td>
</tr>
<tr>
	<td>Total Authentication Pool: </td><td><?php echo $uses;?></td>
</tr>
</table>

<br><br>

	<form method="post" name="adminConfirmAccessProblemForm" action="adminresult.php">
		<!--input type="hidden" name="formuser" value="<?php echo $formuser;?>"-->
		<!--input type="hidden" name="formpassword" value="<?php echo $formpassword;?>"-->
		<input type="hidden" name="uses" value="<?php echo $uses;?>">
		<input type="hidden" name="goal" value="Confirm Access">
		<input type="hidden" name="task" value="Problem!">
		<input type="submit" name="submit" value="Problem!">
	</form>



	<form method="post" name="adminConfirmAccessOKForm" action="http://www.dreamriver.com/license/admin.php">
		<!--input type="hidden" name="formuser" value="<?php echo $formuser;?>"-->
		<!--input type="hidden" name="formpassword" value="<?php echo $formpassword;?>"-->
		<input type="hidden" name="sessionloginAttempts" value="<?php echo $sessionloginAttempts;?>">
		<input type="hidden" name="uses" value="<?php echo $uses;?>">
		<input type="hidden" name="goal" value="Confirm Access">
		<input type="hidden" name="task" value="Proceed">
		<input type="hidden" name="serveraddress" value="<?php echo $_SERVER["SERVER_ADDR"];?>">
		<input type="hidden" name="servername" value="<?php echo $_SERVER["SERVER_NAME"];?>">
		<input type="hidden" name="ip" value="<?php echo $ip;?>">
		<input type="hidden" name="product" value="<?php echo PRODUCTNAME;?>">
		<input type="hidden" name="version" value="<?php echo INSTALLVERSION;?>">
		<input type="hidden" name="email" value="<?php echo SYSTEMEMAIL;?>">
		<input type="hidden" name="adminhome" value="<?php echo ADMINHOME;?>">
		<input type="hidden" name="installpath" value="<?php echo INSTALLPATH;?>">
		<input type="hidden" name="preAdminConfirmAccess" value="<?php echo $HTTP_REFERER;?>">
		<input type="submit" name="submit" value="Confirm Access" class="submit">
	</form>


</blockquote>		
<!--END of adminConfirmAccess.php -->