<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



// ADMIN :: voting module


		echo "<a href=\"admin.php\"><u>".ADMIN_MAIN_MENU."</u></a> : <u><font>".ADMIN_VOTING."</font></u> :<br><br>";

		$f = file("cfg/voting.txt");

		if (!isset($new_voting))
		{

			$r = file("cfg/voting_results.txt");
			$m = $r[0] ? $r[0] : 0;
			$m = max($m, 1);
			for ($i=0; $i<count($r); $i++) if ($m < $r[$i]) $m = $r[$i];

			//show results
			echo "<table bgcolor=#AAAAAA cellspacing=1 cellpadding=1 border=0 width=50%>";
			echo "<tr bgcolor=white><td colspan=2 width=99%><b>$f[0]</b></td><td width=1%>".VOTES_FOR_ITEM_STRING."</td></tr>\n";
			for ($i=1; $i<count($f); $i++)
			{

					echo "<tr bgcolor=#F5F5F5><td width=30%>$f[$i]</td>";
					echo "<td>";

					if ($r[$i-1] > 0)
					{
						echo "<table cellspacing=0 cellpadding=0><tr><td width=70%><nobr>";
						for ($j = 0; $j< 80*$r[$i-1]/$m; $j++) echo "<img src=\"images/voter.gif\">";
						echo "</nobr></td></tr></table>";
					}

					echo "</td><td width=1% align=center>".$r[$i-1]."\n";
					echo "</td></tr>\n";

			}
			echo "</table>";

?>

<p>[ <a href="admin.php?path=voting&new_voting=yes"><?=ADMIN_START_NEW_POLL;?></a> ]

<?
		} else { //new poll
			$question = $f[0];
			$answers = "";
			for ($i=1; $i<count($f); $i++) $answers .= $f[$i];
?>

<form action="admin.php?path=voting" method=post>

<font class=average color=#555555><?=ADMIN_POLL_WARNING;?></font>

<p><table border=0>

<tr>
<td align=right><?=ADMIN_POLL_QUESTION;?></td>
<td>
<input type=text name=question size=50 value="<?=trim($question); ?>">
</td>
</tr>

<tr>
<td align=right><?=ADMIN_POLL_OPTIONS;?></td>
<td>
<textarea name=answers cols=50 rows=10><?=trim($answers); ?></textarea>
</td>
</tr>

</table>

<input type=submit name=save_voting value="<?=SAVE_BUTTON;?>">
<input type=reset value="<?=RESET_BUTTON;?>">

</form>

<?
		};
?>