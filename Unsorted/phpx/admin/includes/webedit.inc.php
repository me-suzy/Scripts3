<?php
//insert image via this interface
#$Id: webedit.inc.php,v 1.5 2003/09/09 15:02:14 ryan Exp $
$text .= "<tr><td class=main><table cellspacing=0 cellpadding=2 border=0 width=100%>";
$text .= "<tr>";
$text .= "<td class=main align=center></td>";
$text .= $text1;
$text .= "<td class=main>&nbsp;</td>";
$text .= "<td class=main width=100>&nbsp;<a href=''  onclick='link(); return false;' ><img src='images/link_2.gif' alt='Link' border=0 ></a>";
$text .= "<a href=''  onclick='StartImagePage(); return false;' ><img src='images/img_2.gif' alt='Image' border=0 ></a>";
$text .= "<a href=''  onclick='createTable(); return false;' ><img src='images/table_2.gif' alt='Table' border=0 ></a></td>";
$text .= "<td class=main>&nbsp;</td>";
$text .= "<td class=main><a href=''  onclick='setHTMLCode1(0); return false;' ><img src='images/enter_3.gif' alt='Line Return' border=0 ></a>";
$text .= "<a href=''  onclick='setHTMLCode1(2); return false;' ><img src='images/nbsp_3.gif' alt='Space' border=0 ></a>";
$text .= "<a href=''  onclick='setHTMLCode1(1); return false;' ><img src='images/hr_3.gif' alt='Line' border=0 ></a></td>";
$text .= "<td class=main>&nbsp;</td>";
$text .= "<td class=main><a href=''  onclick='setHTMLCode(0); return false;' ><img src='images/neg_3.gif' alt='Bold' border=0 ></a>";
$text .= "<a href=''  onclick='setHTMLCode(2); return false;' ><img src='images/curs_3.gif' alt='Italics' border=0 ></a>";
$text .= "<a href=''  onclick='setHTMLCode(4); return false;' ><img src='images/subry_3.gif' alt='Underline' border=0 ></a>&nbsp;&nbsp;";
$text .= "<a href=''  onclick='setHTMLCode(6); return false;' ><img src='images/align_left_3.gif' alt='Align Left' border=0 ></a>";
$text .= "<a href=''  onclick='setHTMLCode(10); return false;' ><img src='images/align_center_3.gif' alt='Align Center' border=0 ></a>";
$text .= "<a href=''  onclick='setHTMLCode(8); return false;' ><img src='images/align_right_3.gif' alt='Align Right' border=0 ></a>";
$text .= "<a href=''  onclick='setHTMLCode(12); return false;' ><img src='images/indent_3.gif' alt='Indent' border=0 ></a>&nbsp;&nbsp;";
$text .= "<a href=''  onclick='uList(0); return false;' ><img src='images/unord_list_3.gif' alt='Unordered List' border=0 ></a>";
$text .= "<a href=''  onclick='uList(1); return false;' ><img src='images/numbered_list.gif' alt='Numbered List' border=0 ></a>&nbsp;&nbsp;";
$text .= "<a href='' onclick='findReplace(); return false;' ><img src='images/search.gif' alt='Find & Replace' border=0></a></td>";
$text .= "<td class=main>&nbsp;</td>";
$text .= "</tr>";
$text .= "</table>";
$text .= "<tr><td class=main valign=top width=100%>";
$text .= "<textarea name=\"text\" rows=\"30\" cols=\"165\" wrap=\"virtual\" onselect='storeCaret(this);'  onclick='storeCaret(this);' onkeyup='storeCaret(this);'>";
$text .= "$body ";
$text .= "</textarea>";
$text .= "</table></td></tr>";
$text .= "<script language=javascript>bodyLoad();</script>";
?>
