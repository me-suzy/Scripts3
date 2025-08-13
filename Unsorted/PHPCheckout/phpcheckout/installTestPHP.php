<?php require_once("configure.php");?>
<?php $isphp = $_REQUEST['isphp'];?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>

		<script language="Javascript" src="appinstaller.js"></script>
		<script language="Javascript">loadCSSFile();</script>
		<title>Install Helper - a DreamRiver Software Product - Easily install DreamRiver Software - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download</title>
	</head> 

<body>
<!-- START of body -->

<h1>DreamRiver Install Helper</h1>

<H2>phpcheckout<sup>TM</sup></H2>



<!-- start of shows % installation completed -->
<table width="60%" border=5 bgcolor="silver">
	<tr>
		<td align="center" width="30%" style="color:white;background-color:darkseagreen;">30%</td>
		<td>&nbsp;</td>
	</tr>
</table>
<!-- end of shows % installation completed -->




<form name="startForm">
<table bgcolor="silver" cellspacing=3 cellpadding=10 width="80%">
<tr>
	<th colspan=2>Install Helper</th>
</tr>
<tr>
	<td bgcolor="white" valign="top">
		<h4>Test for PHP and PHP Version</h4>
		

		<p>
		The install helper has already tested for PHP. The  
		helper has found:
		</p>

		
		<ul>
			<li>PHP is: 
			<?php 
			if(isset($isphp)){echo"WORKING";}else{echo"NOT working.";}
			?>
			<?php 
				if(isset($isphp)) {
					$myPHPVersion = phpversion();
					echo"<li>PHP version is: " . $myPHPVersion;
				}?> 
		</ul>	
		<p>
			<?php if(isset($isphp)):?>
				<?php 
				$version_compareResult =  version_compare($myPHPVersion, "4.1.2", "ge");	
				if($version_compareResult == 1) {
					// http://www.php.net/manual/en/function.version-compare.php
					echo"To continue, please click on the &quot;Next&quot; button.";
				}else{
					echo"YOU NEED TO UPGRADE to Version 4.1.2 or higher!";
					echo"<br>This installation CANNOT proceed.";
				}?>
			<?php endif;?>
		</p>
		

		<p></p>
	</td>
	<td bgcolor="ivory" align="center">
		<img src="installBOP.jpg" width="180" height="180" border=0>
	</td>
</tr>
<tr>
	<td align="right" colspan=2>
		<hr>
		<input type="button" name="back" value="Back" onclick="history.back(1)">
		<?php if($isphp == "testOnlyValue"):?>
			<?php if($myPHPVersion >= 4):?>
				<input type="button" name="goNext" value="Next" class="submit" onclick='location.href="installConfigureSecret.php"'>
			<?php endif;?>
		<?php endif;?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="cancel" value="Cancel" onclick='location.href="installCancel.php"'>
	</td>
</tr>
</table>
</form>


</body>
</html>