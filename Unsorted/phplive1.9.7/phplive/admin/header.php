<table cellspacing=1 cellpadding=2 border=0 width="90%">
<tr bgColor="#E2E2F1">
	<td align="center" width="20%"><span class="smalltxt">
		<a href="./index.php?sid=<? echo $sid ?>&deptid=<? echo $deptid ?>"><img src="../pics/folders/home.gif" width="32" height="32" border=0 alt=""></a><br>
		<a href="./index.php?sid=<? echo $sid ?>&deptid=<? echo $deptid ?>">Home</a>
	</td>
	<td align="center" width="20%"><span class="smalltxt">
		<a href="canned.php?action=canned_responses&sid=<? echo $sid ?>&deptid=<? echo $deptid ?>"><img src="../pics/folders/canned.gif" width="32" height="32" border=0 alt=""></a><br>
		<a href="canned.php?action=canned_responses&sid=<? echo $sid ?>&deptid=<? echo $deptid ?>">Canned Responses</a>
	</td>
	<td align="center" width="20%"><span class="smalltxt">
		<a href="canned.php?action=canned_commands&sid=<? echo $sid ?>&deptid=<? echo $deptid ?>"><img src="../pics/folders/canned.gif" width="32" height="32" border=0 alt=""></a><br>
		<a href="canned.php?action=canned_commands&sid=<? echo $sid ?>&deptid=<? echo $deptid ?>">Canned Commands</a>
	</td>
	<td align="center" width="20%"><span class="smalltxt">
		<a href="prefs.php?sid=<? echo $sid ?>&deptid=<? echo $deptid ?>"><img src="../pics/folders/prefs.gif" width="32" height="32" border=0 alt=""></a><br>
		<a href="prefs.php?sid=<? echo $sid ?>&deptid=<? echo $deptid ?>">Preferences</a>
	</td>
	<td align="center" width="20%"><span class="smalltxt">
		<a href="index.php?action=edit_password&sid=<? echo $sid ?>&deptid=<? echo $deptid ?>"><img src="../pics/folders/profile.gif" width="32" height="32" border=0 alt=""></a><br>
		<a href="index.php?action=edit_password&sid=<? echo $sid ?>&deptid=<? echo $deptid ?>">Edit Password</a>
	</td>
</tr>
<tr bgColor="#C4C4E1">
	<td colspan=6 align="center"><span class="smalltxt">
	(If you are not <? echo $admin['name'] ?>, <a href="../index.php?action=logout&sid=<? echo $sid ?>">click here</a>)
	&nbsp;
	&nbsp; [ <img src="../pics/dot.gif" width="5" height="5"> <a href="../index.php?action=logout&sid=<? echo $sid ?>">Logout</a> ]
	</td>
</tr>
</table>