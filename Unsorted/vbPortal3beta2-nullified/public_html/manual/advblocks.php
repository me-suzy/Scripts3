<html>
<head>
	<title>Advance Blocks System</title>
</head>
<body bgcolor="ffffff" text="000000">
	<font face="Verdana,Arial,Helvetica" size="-1">
		<div align="center"><font size="+2"><b>vbPortal Blocks System</b></font></div>
		<p>
		    Advanced block system modified for vbPortal by <a href="mailto:joneswaj@vbportals.com">William Jones &lt;webmaster@phpportals.com&gt;</a>.
            If you have any questions, comments and/or suggestions, please feel free to email me.
			The Advanced add-on was originally created for Php-Nuke by Patrick Kellum &lt;webmaster@quahog-library.com&gt;</a>.
			
		</p>
<?php
switch($op) {
	case 'AdvBlocksAdmin':
	default:
?>
		<div align="center"><font size="+1"><b>Admin Menu</b></font></div>
		<p>
			<b>Fixed System Blocks</b><br>
			These blocks are speciality blocks, only one of each of them exists.<br>
			<br>
			<b>User Defined Blocks</b><br>
			These blocks are created by the website admin, multiple blocks can be of the same type.<br>
			<br>
			<b>Add a New Block</b><br>
			You can add a new block here.  Only 'User Defined Blocks' can be added.<br>
			<br>
			<b>Title:</b><br>
			The title of the block.<br>
			<b>[drop down menu]:</b><br>
			Select the type of block you want to add here.<br>
			<br>
		</p>
<?php
		break;
}
?>
		<p align="center"><img src="../images/manual.jpg"></p>
	</font>
</body>
</html>