<?php
#$Id: contact.tpl.php,v 1.3 2003/08/08 19:26:23 ryan Exp $
//! Contact Template

$text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=bg align=center>";
$text .= "<tr><td colspan=2 class=title align=center>" . $this->textArray['Contact'] . " " . $this->siteName . "</td></tr>";
$text .= "<tr><td width=100% class=main><table border=0 cellpadding=2 cellspacing=0 align=center width=100%>";
$text .= "<form method=post action=index.php?id=$id onsubmit=\"return validateForm(this)\">";
$text .= "<tr><td colspan=2 class=main><input type=hidden name=confirm value=1>";
$text .= "<tr><td align=right class=mainbold>" . $this->textArray['Name'] . "<font color=red>*</font> : </td>";
$text .= "<td align=left class=main><input type=text size=43 name=emailname value=''></td></tr>";
$text .= "<tr><td align=right class=mainbold>" . $this->textArray['Email'] . "<font color=red>*</font> : </td>";
$text .= "<td align=left class=main><input type=text size=43 name=email value=''></td></tr>";
$text .= "<tr><td align=right class=mainbold>" . $this->textArray['Website'] . " : </td>";
$text .= "<td align=left class=main><input type=text value='http://' size=43 name=url></td></tr>";
$text .= "<tr><td align=right class=mainbold valign=top>" . $this->textArray['Comments'] . " : </td>";
$text .= "<td align=left class=main><textarea name=comments cols=43 rows=15></textarea></td></tr>";
$text .= "<tr><td align=center colspan=2 class=main><input type=submit class=submit value=" . $this->textArray['Send'] . "><br><font color=red>*</font>" . $this->textArray['required'] . "</td></tr>";
$text .= "</form></table></td></tr></table>";

$text .= "<script Language=JavaScript>";
$text .= "function validateForm(theForm)";
$text .= "{";
$text .= "    if (!validRequired(theForm.emailname,\"" . $this->textArray['Name'] . "\"))";
$text .= "        return false;";
$text .= "    if (!validEmail(theForm.email,\"" . $this->textArray['Email'] . "\",true))";
$text .= "        return false;";
$text .= "    return true;";
$text .= "}";
$text .= "</script>";

?>
