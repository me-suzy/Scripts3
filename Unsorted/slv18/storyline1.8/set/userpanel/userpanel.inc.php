<table border='0' width='100%' cellspacing='4'>
	<tr>
		<td class='cleardis'>
<?
if ( !$_SESSION["uid"] ) {
?>
	<form method='post' action='base/trans.php?login'>
	<table border='0' width='100%'>
		<tr>
			<td width='20%' class='cleardisp'>User Name</td> <td> <input type='text' name='loginusername'></td>
		</tr><tr>
			<td width='20%' class='cleardisp'>Password</td> <td> <input type='password' name='loginpassword'></td>
		</tr><tr>
			<td colspan='2' class='cleardisp'><input type='submit' value='login' name='loginsubmit'></td>
		</tr>
	</table>
<?
} else {
	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$table = $dl->select("urealname","sl18_users",array('uid'=>$_SESSION["uid"]));

?>

	<table border='0' width='100%'>
		<tr class='catdis'>
			<td colspan='2'>Welcome to your userpanel, <?=$table[0]["urealname"]?></td>
		</tr><tr>
			<td width='20%' valign='top' class='heavydis'>
				&#8226; <a href='userpanel.php?add'>Add story</a><br>
				&#8226; <a href='userpanel.php?edit'>Edit Story</a><br>
				&#8226; <a href='userpanel.php?delete'>Delete Story</a>
				<br><br>
				&#8226; <a href='userpanel.php?profile'>Profile</a><br>
				&#8226; <a href='userpanel.php?recs'>Recs</a><br>
				&#8226; <a href='userpanel.php?revs'>Remove Reviews</a>
				<br><br>
				&#8226; <a href='base/trans.php?logout'>Log Out</a>
				
			</td>
			<td valign='top'>
<?

			if ($_SERVER["QUERY_STRING"] == "add")
				include(SL_ROOT_PATH."/set/userpanel/add.inc.php");

			elseif ($_SERVER["QUERY_STRING"] == "edit")
				include(SL_ROOT_PATH."/set/userpanel/edit.inc.php");

			elseif ($_SERVER["QUERY_STRING"] == "delete")
				include(SL_ROOT_PATH."/set/userpanel/delete.inc.php");

			elseif ($_SERVER["QUERY_STRING"] == "profile")
				include(SL_ROOT_PATH."/set/userpanel/profile.inc.php");

			elseif ($_SERVER["QUERY_STRING"] == "recs")
				include(SL_ROOT_PATH."/set/userpanel/recs.inc.php");

			elseif ($_SERVER["QUERY_STRING"] == "revs")
				include(SL_ROOT_PATH."/set/userpanel/reviews.inc.php");

			else
				include(SL_ROOT_PATH."/set/userpanel/main.inc.php");
?>
			</td>
		</tr>
	</table>

<?
}
?>
		</td>
	</tr>
</table>