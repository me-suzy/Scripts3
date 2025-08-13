<?php
#$Id: login.tpl.php,v 1.1 2003/08/08 19:26:23 ryan Exp $

$text = "<table border=0 cellpadding=0 cellspacing=1 width=200 class=outline align=center>";
$text .= "<tr><td colspan=2 class=title align=center>" . $this->textArray['Login'] . "</td></tr>";
$text .= "<tr><td width=100% class=main><table border=0 cellpadding=2 cellspacing=0 align=center width=100%>";
$text .= "<form method=post action=users.php?action=login onsubmit=\"return validateForm(this)\">";
$text .= "<tr><td colspan=2 class=main><input type=hidden name=confirm value=1><input type=hidden name=url value='$url'>";
$text .= "$code";
$text .= "</td></tr>";
$text .= "<tr><td align=right class=mainbold>" . $this->textArray['Username'] . ": </td>";
$text .= "<td align=left class=main><input type=text size=20 name=username value='$username'></td></tr>";
$text .= "<tr><td align=right class=mainbold>" . $this->textArray['Password'] . ": </td>";
$text .= "<td align=left class=main><input type=password size=20 name=password value=''></td></tr>";
$text .= "<tr><td align=center colspan=2 class=main>" . $this->textArray['Remember Me'] . " <input type=checkbox name=remember></td></tr>";
$text .= "<tr><td align=center colspan=2 class=main><input type=submit class=submit value=" . $this->textArray['Login'] . "></td></tr>";
$text .= "<tr><td class=main colspan=2 align=center><a href=users.php?action=signup>" . $this->textArray['Register Here'] . "</a></td></tr>";
$text .= "<tr><td class=main colspan=2 align=center><a href=users.php?action=password>" . $this->textArray['Lost Password'] . "</a></td></tr>";
$text .= "</form></table></td></tr></table>";
$text .= "<script Language=JavaScript>";
$text .= "function validateForm(theForm)";
$text .= "{";
$text .= "    if (!validRequired(theForm.username,\"" . $this->textArray['Username'] . "\"))";
$text .= "        return false;";
$text .= "    if (!validRequired(theForm.password,\"" . $this->textArray['Password'] . "\"))";
$text .= "        return false;";
$text .= "    return true;";
$text .= "}";
$text .= "</script>";

?>
