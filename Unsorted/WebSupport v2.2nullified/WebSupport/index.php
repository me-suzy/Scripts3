<?php
// +-----------------------------------------------------------------------+
// | WebSupport 2.2                                                        |
// +-----------------------------------------------------------------------+
// | Copyright (c) 2001 IRC-HTML  Productions                              |
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
// $Id: index.php,v 2.2.3 2001/04/17 21:45:49 ks-wsdev Exp $
require_once('ws.inc');
/* - No need to edit anything below this point - */
$form = "$QUERY_STRING";
if (!$form) {
	inheader($t_request);
	?>
	<br>
	<font size=1><?php print($t_submit); ?></font>
	<form method=post action="<?php print($script_url); ?>?new">
	<table border=0 cellpadding=1 cellspacing=5>
	<tr>
	<td align=right><font size=1> <?php print($t_name); ?> </font></td>
	<td><input type=text size=20 maxlength=256 name=name></td>
	</tr>
	<tr>
	<td align=right><font size=1> <?php print($t_email); ?> </font></td>
	<td><input type=text size=20 maxlength="256" name=email></td>
	</tr>
	<tr>
	<td align=right><font size=1> <?php print($t_user); ?> </font></td>
	<td><input type=text size=20 maxlength=256 name=username></td>
	</tr>
	<tr>
	<td align=right><font size=1> <?php print($t_domain); ?> </font></td>
	<td><input type=text size=20 maxlength=256 name=domain></td>
	</tr>
	<tr>
	<td align=right><font size=1> <?php print($t_subject); ?> </font></td>
	<td><input type=text size=20 maxlength=60 name=subject></td>
	</tr>
	<tr>
	<td align=right><font size=1> <?php print($t_prob); ?> </font></td>
	<td><textarea rows=5 name=problem cols=35></textarea></td>
	</tr>
	<tr>
	<td align=right><font size=1> <?php print($t_pri); ?> </font></td>
	<td><font size=1><select name=priority>
	<option value=Highest><?php print($t_prihight); ?>
	<option value=High><?php print($t_prihigh); ?>
	<option value=Medium><?php print($t_primed); ?>
	<option value=Low><?php print($t_prilow); ?>
	</select></font></td>
	</tr>
	<tr>
	<td></td>
	<td align=left><input type=submit name=Submit value="<?php print($t_submitform); ?> &gt; &gt;"></td>
	</tr>
	<tr><td align=center colspan=2><br><font size=2><?php print($t_status); ?></font></center></td></tr>
	</table>
	</form>
	<?php
	footer();
	exit;
}
elseif ($form == 'new') {
	$check = @mysql_fetch_row(@mysql_query("SELECT Ticket FROM $table WHERE Email='$email' AND Date='$date' AND Username='$username'", $ws_mysql));
	if ($check) {
		error_page($t_dupe);
	}
	$date = date("Y-m-d-H-i-s");
	if (check_fields($name, $username, $domain, $email, $problem, $subject)) {
		$problem = htmlspecialchars($problem);
		$addv = @mysql_query("INSERT INTO $table VALUES('', '$date', '$name', '$username', '$domain', '$email', '$problem', '$priority', '', '0', '$REMOTE_ADDR', '$subject')", $ws_mysql);
		$results = @mysql_fetch_row(@mysql_query("SELECT * FROM $table WHERE Email='$email' AND Date='$date' AND Username='$username'", $ws_mysql));
		$ticket = $results[0];
		$problem = stripslashes($problem);
		$subject = stripslashes($subject);
		$data = "$name,\n\n";
		$data .= "$t_ereq\n";
		$data .= "$t_eis: $ticket\n\n";
		$data .= "$t_ekeep:\n";
		$data .= "$script_url?view\n\n";
		$data .= "$t_eregards\n";
		$data .= "$title Team\n";
		mail($email, "$t_esup $ticket", $data, $headers);
		$data = "$t_enew\n";
		$data .= "$t_ticket: $ticket\n";
		$data .= "$t_name: $name\n";
		$data .= "$t_email: $email\n";
		$data .= "$t_subject: $subject\n";
		$data .= "$t_pri: $priority\n\n\n";
		$data .= "$t_elink: $admin_url\n";
		if ($notify_icq) {
			for ($i = 0; $i < count($icq_num); $i++) {
				$toicq .= $icq_num[$i] . "@pager.icq.com, ";
			}
			$headers = "From: $name <$email>\n";
			$headers .= "X-Sender: <$email>\n"; 
			$headers .= "X-Mailer: WebSupport 2.0\n";
			$headers .= "X-Priority: 1\n";
			mail($toicq, "$t_esup $ticket", $data, $headers);
		}
		if ($notify) {
			$tosupport = $notifywho[0];
			if (count($notifywho) > 1) {
				for ($i = 1; $i < count($notifywho); $i++) {
					$tosupport .= ', ' . $notifywho[$i];
				}
			}	
			$headers = "From: $name <$email>\n";
			$headers .= "X-Sender: <$email>\n"; 
			$headers .= "X-Mailer: WebSupport 2.0\n";
			$headers .= "X-Priority: 1\n";
			$headers .= "Return-Path: $name <$email>\n";
			mail($tosupport, "$t_esup $ticket", $data, $headers);
		}
		inheader($t_submitted);
		?>
		<blockquote>
		<font size=2>
		<br><p><?php print($t_sub1); ?> <b><?php print($email); ?></b>. <?php print($t_sub2); ?></p>
		<p>
		<?php print($t_ticket); ?>: <b><?php print($ticket); ?></b><br>
		<?php print($t_name); ?>: <b><?php print($name); ?></b><br>
		<?php print($t_user); ?>: <b><?php print($username); ?></b><br>
		<?php print($t_domain); ?>: <b><?php print($domain); ?></b><br>
		<?php print($t_pri); ?>: <b><?php print($priority); ?></b><br>
		<?php print($t_subject); ?>: <b><?php print($subject); ?></b><br>
		<?php print($t_prob); ?>: <i><?php print($problem); ?></i>
		</p>
		<p><?php print($t_thk); ?>
		</font>
		</blockquote>
		<?php
		footer();
		exit;
	}
}
elseif ($form == 'view') {
	inheader($t_viewstat);
	?>
	<br>
	<form method=post action="<?php print($script_url); ?>?show">
	<table border=0 cellpadding=1 cellspacing=5>
	<tr>
	<td width=100 align=right><font size=1> <?php print($t_ticket); ?> </font></td>
	<td><input type=text size=20 maxlength=256 name=ticket></td>
	</tr>
	<tr>
	<td align=right><font size=1> <?php print($t_user); ?> </font></td>
	<td><input type=text size=20 maxlength=256 name=username></td>
	</tr>
	<tr>
	<td align=right colspan=2>&nbsp;<input type=submit name=View value="<?php print($t_view); ?> &gt; &gt;"></td>
	</tr>
	</table>
	</form>
	<?php
	footer();
	exit;
}
elseif ($form == 'show') {
	$result = @mysql_fetch_row(@mysql_query("SELECT *,  ELT(Status, 'Open', 'Assigned', 'Resolved') FROM $table WHERE Ticket=$ticket", $ws_mysql));
	if ($result) {
		$result[8] = stripslashes($result[8]);
		$result[6] = stripslashes($result[6]);
		if ($result[0] == "$ticket" && $result[3] == "$username") {
			$date = explode('-', $result[1]);
			inheader($t_stat);
			?>
			<br>
			<blockquote>
			<font size="2">
			<p>
			<b><?php print($t_datesub); ?></b>: <?php print("$date[1]-$date[2]-$date[0]  $date[3]:$date[4]:$date[5]"); ?><br>
			<b><?php print($t_stat); ?></b>: <?php print($result[12]); ?><br>
			<b><?php print($t_pri); ?></b>: <?php print($result[7]); ?><br>
			<b><?php print($t_name); ?></b>: <?php print($result[2]); ?><br>
			<b><?php print($t_email); ?></b>: <?php print($result[5]); ?><br>
			<b><?php print($t_user); ?></b>: <?php print($result[3]); ?><br>
			<b><?php print($t_domain); ?></b>: <?php print($result[4]); ?><br>
			<b><?php print($t_subject); ?></b>: <?php print($result[11]); ?><br><br>
			<b><?php print($t_prob); ?></b>:<br>
			<?php print($result[6]); ?><br><br>
           	<form method=post action="<?php print($script_url); ?>?update">
            <input type=hidden name=ticket value=<?php print($ticket); ?>>
        	<center><textarea rows=5 name="problem" cols="45"><?php print($result[8]); ?></textarea></center><br>
            <div align=right><input type=submit name=subit value="Append to Ticket"></div>
            </form>
            <br><b><?php print($t_taken); ?></b>:<br>
			<?php
			if (strlen($result[8]) < 2) { print($t_progress); }
			else { print($result[8]); }
			?><br><br>
			</font>
			</blockquote>
			<?php
			footer();
			exit;
		}
		if ($result[0] == "$ticket" && $result[3] != "$username") {
			error_page("<li>$t_comb<br>");
		}
	}
	else {
		error_page("<li>$t_noexist<br>");
	}
}
elseif ($form == 'update') {
   	$result = @mysql_fetch_row(@mysql_query("SELECT * FROM $table WHERE Ticket=$ticket", $ws_mysql));
	$problem = htmlspecialchars($problem);
    $result[6] = stripslashes($result[6]);
  	$date = date("d-m-Y H-i-s");
    $problem = addslashes($result[6] . "<br>The following was added on $date:<br>" . $problem);
   	$query = mysql_query("UPDATE $table SET Problem='$problem' WHERE Ticket=$ticket");
	inheader($t_stat);
    ?>
    <center><font size=2><br>The modifications to ticket <?php print($ticket); ?> were successfully completed.<br><br><a href="<?php print($script_url); ?>">Support Home</a></font></center>
    <?php
    footer();
}
else {
	error_page("<li>$t_illegal<br>");
}
?>