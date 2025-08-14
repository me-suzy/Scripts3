<?php
// +-----------------------------------------------------------------------+
// | WebSupport 2.2                                                        |
// +-----------------------------------------------------------------------+
// | Copyright (c) 2001 IRC-HTML Productions                               |
// +-----------------------------------------------------------------------+
// | This source file is subject to the current license contained in       |
// | readme.txt that is bundled with this package.                         |
// | If you have not read the license, or do not agree to it please delete |
// | this software immediately. Use of the program constitutes agreement   |
// | with the license.                                                     |
// +-----------------------------------------------------------------------+
// | Author         : Kenneth Schwartz                                     |
// | Supplied by    : CyKuH [WTN]                                          |
// | Distribution   : via WebForum, ForumRU and associated file dumps      |
// |                      (c) WTN Team `2002                               |
// +-----------------------------------------------------------------------+
// $Id: admin.php,v 2.2.3 2001/04/17 21:45:49 ks-wsdev Exp $
require_once('ws.inc');
// No need to edit anything below this point
function checkPass($pass) {
	global $admin_pass;
	if ($pass == $admin_pass) {
		return true;
	}
	else {
		logBox('Incorrect Password. Please try again.');
	}
}
function logBox($message=0) {
	global $admin_url, $image_url;
	adheader('WebSupport Login', 1);
	?>
	<font size=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Use of secured computer and network facilities requires prior authorization. Unauthorized access is prohibited.  Usage may be subject to security testing and monitoring. Abuse is subject to criminal, civil, and extra-legal prosecution.</font><br><br>
	<?php
	if ($message != '0') {
		print("<font size=2 color=red>$message</font><br><br>");
	}
	?>
	<table border=0 cellspacing=1 cellpadding=1 align=center>
	<tr>
	<td align=center>
	<form action="<?php print($admin_url); ?>?form=login" method=post>
	<font size=2>
	Password: <input type=password size=10 name=password><br><br><input type=submit value=Login></font>
	</form>
	</td>
	</tr>
	</table>
    <?php
    footer();
	exit;
}
if (!isset($form)) {
	if (isset($WsUser)) {
		checkPass($WsUser);
	}
	else {
		logBox();
	}
	if (isset($sort)) {
		if ($sort == 'asc') {
			$sort = $ascending;
			$get_query = "SELECT *, ELT(Status, 'Open', 'Assigned', 'Resolved') FROM $table WHERE Status<>'2' ORDER BY Ticket ASC";
		}
		elseif ($sort == 'des') {
			$sort = $descending;
			$get_query = "SELECT *, ELT(Status, 'Open', 'Assigned', 'Resolved') FROM $table WHERE Status<>'2' ORDER BY Ticket DESC";
		}
	}
	else {
		$sort = $descending;
		$get_query = "SELECT *, ELT(Status, 'Open', 'Assigned', 'Resolved') FROM $table WHERE Status<>'2' ORDER BY Ticket DESC";
	}
	adheader($t_viewreq, 1);
	?>
	<br>
	<table align=center border=1 bgcolor="#f4f4f4" cellpadding=2>
	<tr>
	<td align=center><font size=2 color="#000090"><b><?php print($t_ticket); ?></b>&nbsp;<?php print($sort); ?></font></td>
	<td align=center><font size=2 color="#000090"><b><?php print($t_stat); ?> / <?php print($t_pri); ?></b></font></td>
	<td align=center><font size=2 color="#000090"><b><?php print($t_det); ?></b></font></td>
	<td align=center><font size=2 color="#000090"><b>Date / Time</b></font></td>
  	<td align=center><font size=2 color="#000090"><b>IP</b></font></td>
    <td align=center><font size=2 color="#000090"><b><?php print($t_act); ?></b></font></td> 
	</tr>
	<?php
	$mysql_result = @mysql_query($get_query, $ws_mysql);
	if ($mysql_result) {
		while($result = @mysql_fetch_row($mysql_result)) {
			$result[6] = stripslashes($result[6]);
			$result[11] = stripslashes($result[11]);
			$date = explode('-', $result[1]);
			?>
			<tr> 
			<td align=center><font size=1><?php print($result[0]); ?></Font></td>
			<td align=center><font size=1><?php print($result[12]); ?> / <?php print($result[7]); ?><br></font></td>
			<td><font size=1 color="#000090"><?php print($t_subject); ?>: <b><?php print($result[11]); ?></b><br><?php print($t_subby); ?> <b><?php print($result[2]); ?></b></font></td>
			<td align=center><font size=1 color="#000090"><b><?php print("$date[1]-$date[2]-$date[0] $date[3]:$date[4]:$date[5]"); ?></b></font></td>
          	<td align=center><font size=1 color="#000090"><b><?php print($result[10]); ?></b></font></td>
            <td align=center><font size=1 color="#000090"><a href="<?php print($admin_url); ?>?form=edit&ticket=<?php print($result[0]); ?>">[<?php print($t_view); ?>/<?php print($t_edit); ?>]</a><br><a href="<?php print($admin_url); ?>?form=email&amp;ticket=<?php print($result[0]); ?>">[<?php print($t_email); ?>]</a><br><a href="<?php print($admin_url); ?>?form=del&amp;ticket=<?php print($result[0]); ?>">[<?php print($t_delete); ?>]</a></font></td>
			</tr>
			<?php
		}
	}
	else {
		?>
		<tr> 
            <td align=center colspan=4><font size=2><?php print($t_servdown); ?></font></td>
		</tr>
		<?php	
	}
	?>
	</table><br><center><font size=2 color="#000090"><?php print($t_view); ?> <a href="<?php print($admin_url); ?>?form=resolved"><?php print($t_res); ?></a> <?php print($t_tkts); ?>.</font><br>
	<?php
	adfooter();
	exit;
}
elseif ($form == 'login') {
	if (checkPass($password)) {
		setcookie('WsUser', $admin_pass);
       	adheader('Logged In', 1);
		print("<center><font size=2>You are now logged in. Please <a href=\"$admin_url\">return</a> to the administration center.</font></center>");
      	adfooter();
	}
}
elseif ($form == 'resolved') {
if (!isset($WsUser)) {
	logBox();
}
else {
	checkPass($WsUser);
}
	if (isset($sort)) {
		if ($sort == 'asc') {
			$sort = $ascendingr;
			$get_query = "SELECT *, ELT(Status, 'Open', 'Assigned', 'Resolved') FROM $table WHERE Status='2' ORDER BY Ticket ASC";
		}
		elseif ($sort == 'des') {
			$sort = $descendingr;
			$get_query = "SELECT *, ELT(Status, 'Open', 'Assigned', 'Resolved') FROM $table WHERE Status='2' ORDER BY Ticket DESC";
		}
	}
	else {
		$sort = $descendingr;
		$get_query = "SELECT *, ELT(Status, 'Open', 'Assigned', 'Resolved') FROM $table WHERE Status='2' ORDER BY Ticket DESC";
	}
	adheader($t_viewres);
	?>
	<br>
	<table align=center border=1 bgcolor="#f4f4f4" cellpadding=2>
	<tr>
	<td align=center><font size=2 color="#000090"><b><?php print($t_ticket); ?></b>&nbsp;<?php print($sort); ?></font></td>
	<td align=center><font size=2 color="#000090"><b><?php print($t_stat); ?> / <?php print($t_pri); ?></b></font></td>
	<td align=center><font size=2 color="#000090"><b><?php print($t_det); ?></b></font></td>
	<td align=center><font size=2 color="#000090"><b>Date / Time</b></font></td>
  	<td align=center><font size=2 color="#000090"><b>IP</b></font></td>
    <td align=center><font size=2 color="#000090"><b><?php print($t_act); ?></b></font></td> 
	</tr>
	<?php
	$mysql_result = @mysql_query($get_query, $ws_mysql);
	if ($mysql_result) {
		while($result = @mysql_fetch_row($mysql_result)) {
			$result[6] = stripslashes($result[6]);
			$date = explode('-', $result[1]);
			?>
			<tr> 
				<tr> 
				<td align=center><font size=1><?php print($result[0]); ?></font></td>
				<td align=center><font size=1><?php print($result[12]); ?> / <?php print($result[7]); ?><br></font></td>
			    <td><font size=1 color="#000090"><?php print($t_subject); ?>: <b><?php print($result[11]); ?></b><br><?php print($t_subby); ?> <b><?php print($result[2]); ?></b></font></td>
			    <td align=center><font size=1 color="#000090"><b><?php print("$date[1]-$date[2]-$date[0] $date[3]:$date[4]:$date[5]"); ?></b></font></td>
          	    <td align=center><font size=1 color="#000090"><b><?php print($result[10]); ?></b></font></td>
                <td align=center><font size=1 color="#000090"><a href="<?php print($admin_url); ?>?form=edit&ticket=<?php print($result[0]); ?>">[<?php print($t_view); ?>/<?php print($t_edit); ?>]</a><br><a href="<?php print($admin_url); ?>?form=email&amp;ticket=<?php print($result[0]); ?>">[<?php print($t_email); ?>]</a><br><a href="<?php print($admin_url); ?>?form=del&amp;ticket=<?php print($result[0]); ?>">[<?php print($t_delete); ?>]</a></font></td>
				</tr>
			</tr>
		<?php
		}
	}
	else {
		?>
		<tr> 
				<td align=center colspan=4><font size=2><?php print($t_servdown); ?></font></td>
		</tr>
		<?php	
	}
	?>
	</table><br><center><font size=2 color="#000090"><?php print($t_view); ?> <a href="<?php print($admin_url); ?>"><?php print($t_new); ?> / <?php print($t_ass); ?></a> <?php print($t_tkts); ?>.</font></center><br>
	<?php
	adfooter();
	exit;
}
elseif ($form == 'edit') {
	if (!isset($WsUser)) {
		logBox();
	}
	else {
		checkPass($WsUser);
	}
	$result = @mysql_fetch_row(@mysql_query("SELECT *, ELT(Status, 'Open', 'Assigned', 'Resolved') FROM $table WHERE Ticket=$ticket", $ws_mysql));
	$result[8] = stripslashes($result[8]);
	$result[6] = stripslashes($result[6]);
	$result[11] = stripslashes($result[11]);
	$date = explode('-', $result[1]);
	adheader("$t_up $t_ticket ID $ticket", 1);
	?>
	<br>
	<blockquote>
	<font size=2>
	<b><?php print($t_datesub); ?></b>: <?php print("$date[1]-$date[2]-$date[0] at $date[3]:$date[4]:$date[5]"); ?><br>
	<b><?php print($t_stat); ?></b>: <?php print($result[12]); ?><br>
	<b><?php print($t_pri); ?></b>: <?php print($result[7]); ?><br>
	<b><?php print($t_name); ?></b>: <?php print($result[2]); ?><br>
	<b><?php print($t_email); ?></b>: <?php print($result[5]); ?><br>
	<b><?php print($t_user); ?></b>: <?php print($result[3]); ?><br>
	<b><?php print($t_domain); ?></b>: <?php print($result[4]); ?><br>
	<b>IP</b>: <?php print($result[10]); ?><br>
	<b><?php print($t_subject); ?></b>: <?php print($result[11]); ?><br><br>
	<b><?php print($t_prob); ?></b>:<br><?php print($result[6]); ?><br><hr><br>
	<b><?php print($t_taken); ?></b>:<br>
	<form method=post action="<?php print($admin_url); ?>?form=update">
	<center><textarea rows=5 name="solution" cols="45"><?php print($result[8]); ?></textarea></center><br>
	<b><?php print($t_new); ?> <?php print($t_stat); ?></b>:<br>
	<select name="nstatus">
	<option value=1><?php print($t_ass); ?>
	<option value=2><?php print($t_res); ?>
	</select><br><br>
    <input type=checkbox name=notify value=1 checked> Notify user of ticket modification.<br><br>
	<input type=hidden name=ticket value="<?php print($result[0]); ?>">
	<input type=submit value="<?php print($t_save); ?>">
	</form>
	</font>
	</blockquote>
	<?php
	adfooter();
	exit;
}
elseif ($form == 'update') {
	if (!isset($WsUser)) {
		logBox();
	}
	else {
		checkPass($WsUser);
	}
	$solution = htmlspecialchars($solution);
	$query = @mysql_query("UPDATE $table SET Solution='$solution', Status='$nstatus' WHERE Ticket=$ticket");
	$ticket_info = @mysql_fetch_row(@mysql_query("SELECT * FROM $table WHERE Ticket=$ticket", $ws_mysql));
	if ($notify) {
		$data = "$ticket_info[2],\n\n";
		$data .= "$t_ecomp\n\n";
		$data .= "$t_eview:\n";
		$data .= "$script_url?view\n";
		$data .= "$t_enum ($ticket) $t_euser ($ticket_info[3]).\n\n";
		$data .= "$t_eregards\n";
		$data .= "$title Team\n";
		mail($ticket_info[5], "$t_esup $ticket $t_uped", $data, $headers);
	}
	adheader("$t_up $t_ticket $ticket", 1);
	?>
	<blockquote>
	<font size="2">
	<br><p><?php print("$t_mod1 $ticket $t_mod2<br><center>$t_returnad</center>"); ?></p>
	</font>
	</blockquote>
	<?php
	adfooter();
	exit;
}
elseif ($form == 'logout') {
	if (isset($WsUser)) {
		setcookie('WsUser');
	}
   	adheader('Logged Out', 1);
	print("<center><font size=2>You are now logged out. Please <a href=\"$admin_url\">login</a> if you would like to continue.</font></center>");
   	footer();
}
elseif ($form == 'email') {
	if (!isset($WsUser)) {
		logBox();
	}
	else {
		checkPass($WsUser);
	}
	$result = @mysql_fetch_row(@mysql_query("SELECT Ticket, Email, Subject FROM $table WHERE Ticket=$ticket", $ws_mysql));
	adheader("$t_emuser $result[0]");
	$result[2] = stripslashes($result[2]);
	?>
	<br>
	<blockquote>
	<form method=post action="<?php print($admin_url); ?>?form=send">
	<input type=hidden name=ticket value="<?php print($result[0]); ?>">
	<table cellpadding=0 cellspacing=0 width="100%">
	<tr>
	<td valign=top><font size=2><?php print($t_to); ?>:</font></td>
	<td valign=top><input type=text size=30 name=to value="<?php print($result[1]); ?>" readonly></td>
	</tr>
	<tr>
	<td valign=top><font size=2><?php print($t_from); ?>:</font></td>
	<td valign=top><input type=text size=30 name=from value="<?php print($notifywho[0]); ?>" readonly></td>
	</tr>
	<tr>
	<td valign=top><font size=2><?php print($t_subject); ?>:</font></td>
	<td valign=top><input type=text size=30 name=subject value="RE: <?php print($result[2]); ?>"></td>
	</tr>
	<tr>
	<td valign=top><font size=2><?php print($t_message); ?>:</font></td>
	<td valign=top><textarea rows=10 name=message cols=40></textarea></td>
	</tr>
	<tr>
	<td valign=middle align=center colspan=2><br><input type=submit name=Submit value="<?php print($t_sendmsg); ?>"></td>
	</tr>
	</table>
	</form>
	</blockquote>
	<?php
	adfooter();
	exit;
}
elseif ($form == 'send') {
	if (!isset($WsUser)) {
		logBox();
	}
	else {
		checkPass($WsUser);
	}
	$message = stripslashes($message);
	$to = trim($to);
	mail($to, "$subject", $message, $headers);
	adheader("$t_emuser $ticket", 1);
	?>
	<font size=2>
	<br><?php print("$t_sent1 \"$to\" $t_sent2 \"$subject\". $t_returnad"); ?><br>
	</font>
	<?php
	adfooter();
	exit;
}
elseif ($form == 'del') {
	if (!isset($WsUser)) {
		logBox();
	}
	else {
		checkPass($WsUser);
	}
	if (isset($confirm)) {
		if ($delete == 1) {
			header("Location: $admin_url?form=resolved");
		}
		else {
			$query = @mysql_query("DELETE FROM $table WHERE Ticket=$ticket");
			adheader($t_del, 1);
			?>
			<center><br><font size=2><?php print($t_ticket); ?> <b><?php print($ticket); ?></b> <?php print($t_removed); ?><br><?php print($t_returnad); ?><br></font></center>
			<?php
			adfooter();
			exit;
		}
	}
	else {
		adheader($t_del);
		?>
		<center>	
		<font size=2>
		<form method=post action="<?php print($admin_url); ?>?form=del">
		<input type=hidden name=ticket value="<?php print($ticket); ?>"><br><br>
		<?php print($t_wdelt); ?> <b><?php print($ticket); ?></b>?<br><br>
		<select name=delete><option value=1 selected><?php print($t_no); ?></option><option value=2><?php print($t_yes); ?></option></select><br><br>
		<input type=submit name=confirm value=Confirm>
		</form></font></center>
		<?php
		adfooter();
		exit;
	}
}
elseif ($form == 'optimize') {
	if (!isset($WsUser)) {
		logBox();
	}
	else {
		checkPass($WsUser);
	}
	$query = @mysql_query("OPTIMIZE TABLE $table");
	if ($query) {
		adheader($t_optimize, 1);
		?>
		<center><br><font size=2><?php print("$t_optsuccess<br>$t_returnad"); ?><br></font></center>
		<?php
		adfooter();
		exit;
	}
	else {
		adheader($t_optimize, 1);
		?>
		<br><font size=2><?php print("$t_optnot<br>$t_returnad"); ?><br></font>
		<?php
		adfooter();
		exit;
	}
}
else {
	error_page("<li>$t_illegal<br>");
}
?>