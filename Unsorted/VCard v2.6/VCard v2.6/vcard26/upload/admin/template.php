<?php
/***************************************************************************
 *   script               : vCard PRO
 *   copyright            : (C) 2001-2002 Belchior Foundry
 *   website              : www.belchiorfoundry.com
 *
 *   This program is commercial software; you can´t redistribute it under
 *   any circumstance without explicit authorization from Belchior Foundry.
 *   http://www.belchiorfoundry.com/
 *
 ***************************************************************************/
define('IN_VCARD', true);
require('./lib.inc.php');

check_lvl_access($canedittemplate);

dothml_pageheader();

$fieldsize = optionbynavigator("70","50");
// ############################# local function ########################
function do_javascript() {
	global $msg_button_find,$msg_button_copy,$msg_button_preview;
	
	$buttonextra="
<SCRIPT LANGUAGE=\"JavaScript\">
function SelectAll() {
  var tempval = eval(\"document.name.template\")
  tempval.focus()
  tempval.select()
  if (document.all){
    therange=tempval.createTextRange()
    therange.execCommand(\"Copy\")
    window.status=\"Contents highlighted and copied to clipboard!\"
    setTimeout(\"window.status=''\",1800)
  }
}
function displayHTML() {
  var inf = document.name.template.value;
  win = window.open(\", \", 'popup', 'toolbar = no, status = no, scrollbars=yes');
  win.document.write(\"\" + inf + \"\");
}
var NS4 = (document.layers);    // Which browser?
var IE4 = (document.all);
var win = window;    // window to search.
var n   = 0;
function findInPage(str) {
  var txt, i, found;
  if (str == '')
    return false;
  if (NS4) {
    if (!win.find(str))
      while(win.find(str, false, true))
        n++;
    else
      n++;
    if (n == 0)
      alert('Not found.');
  }
  if (IE4) {
    txt = win.document.body.createTextRange();
    for (i = 0; i <= n && (found = txt.findText(str)) != false; i++) {
      txt.moveStart('character', 1);
      txt.moveEnd('textedit');
    }
    if (found) {
      txt.moveStart('character', -1);
      txt.findText(str);
      txt.select();
      txt.scrollIntoView();
      n++;
    } else {
      if (n > 0) {
        n = 0;
        findInPage(str);
      }
      else
        alert('Not found.');
    }
  }
  return false;
}
</script>
<input name=\"string\" type=\"text\" accesskey=\"t\" size=\"20\" onChange=\"n=0;\">
<input type=\"button\" value=\"$msg_button_find\" accesskey=\"f\" onClick=\"javascript:findInPage(document.name.string.value)\">&nbsp;&nbsp;&nbsp;
<input type=\"button\" value=\"$msg_button_preview\" accesskey=\"p\" onclick=\"javascript:displayHTML()\">
<input type=\"button\" value=\"$msg_button_copy\" accesskey=\"c\" onclick=\"javascript:SelectAll()\">";
	echo "<tr class=\"".get_row_bg()."\" valign=\"top\">\n<td><p>&nbsp;</p></td>\n<td><p>$buttonextra</p></td>\n</tr>\n";
}
// ############################# DB ACTION #############################
if ($action == 'template_modify')
{
	$result = $DB_site->query("
		UPDATE vcard_template
		SET template='".addslashes($HTTP_POST_VARS[template])."'
		WHERE template_id='$HTTP_POST_VARS[template_id]'
		");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = "";
}

if ($action == 'template_add')
{
	$query[1] = " INSERT INTO vcard_template (template_id,title,template) VALUES (NULL,'".addslashes($HTTP_POST_VARS['title'])."','".addslashes($HTTP_POST_VARS['template'])."') ";
	$result = $DB_site->query($query[1]);
	dohtml_result($result,"$msg_admin_reg_add");
	$action = "";
}

if ($action == 'update_cache')
{
	$result = $DB_site->query("UPDATE vcard_cache SET content='2000-01-01 00:00:00' WHERE title='cachedate' ");
	$DB_site->query("UPDATE vcard_cache SET date='2000-01-01 00:00:00' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = "";
}


// ############################# ACTION SCREENS #############################
// SCREEN = EDIT
if ($action == 'template_edit')
{
	$result = $DB_site->query_first("SELECT * FROM vcard_template WHERE title='$HTTP_GET_VARS[title]' ");
	extract($result);
dohtml_form_header("template","template_modify",0,1);
dohtml_table_header("edit","$msg_admin_menu_edit");
dohtml_form_label($msg_admin_name,$title);
$template = stripslashes($template);
dohtml_form_textarea($msg_admin_template,"template",$template,25,80);
dohtml_form_hidden("template_id",$template_id);
do_javascript();
dohtml_form_footer($msg_admin_reg_update);
dothml_pagefooter();
exit;
}

// SCREEN = ADD EVENT
if($action == 'add')
{
dohtml_form_header("template","template_add",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
dohtml_form_input($msg_admin_name,"title",$title);
dohtml_form_textarea($msg_admin_template,"template",$template,25,80);
do_javascript();
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// VIEW = DEFAULT TEMPLATE
if ($action == 'view_or')
{
	$templates=$DB_site->query_first("SELECT template_id,title,template FROM vcard_template_o WHERE title='".urldecode($HTTP_GET_VARS['title'])."'");
	extract($templates);
dohtml_form_header("template","",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
dohtml_form_label($msg_admin_name,$title);
dohtml_form_textarea($msg_admin_template,"template",$template,25,80);
do_javascript();
dohtml_form_footer($msg_button_back);
dothml_pagefooter();
exit;
}

if (is_array($query) && $debug == 1)
{
	echo "<blockquote><b>Queries Executed:</b> <font size='1'>(useful for copy/pasting into upgrade scripts)</font><br><textarea rows='10' cols='100' style='color:red'>\n";
 	while(list($queryindex,$querytext)=each($query))
	{
		echo "\$DB_site->query(\"".htmlspecialchars($querytext)."\");\n";
	}
	echo "</textarea></blockquote>\n";
}
// SCREEN = DEFAULT
if (empty($action))
{
	dohtml_table_header("edit","$msg_admin_menu_edit",2);
	$template_array = $DB_site->query(" SELECT title FROM vcard_template WHERE title <>'options' ORDER BY title ");
	$html .= "<table border=0><tr><td><b>$msg_admin_name</b> &nbsp;</td><td>&nbsp;</td></tr>\n";
	while ($template_item = $DB_site->fetch_array($template_array))
	{
		//extract($event);
		$title 	= stripslashes(htmlspecialchars($template_item[title]));
		// Display list of categories
		//$Month = cexpr($template_month<10,"$MsgMonth[$template_month]","$template_month");
		$html .= "<tr class=\"".get_row_bg()."\"><td> $title </td><td> &nbsp; <a href=\"template.php?action=template_edit&title=".urlencode($title)."&s=$s\">[$msg_admin_menu_edit]</a>&nbsp; <a href=\"template.php?action=view_or&title=".urlencode($title)."&s=$s\">[$msg_admin_template_seeoriginal]</a></td></tr>\n";
	}
	$DB_site->free_result($template_array);
	$html .= "</table>";
	dohtml_form_label($msg_admin_template,$html);
	dohtml_table_footer();
	
	dohtml_form_header("template","update_cache",0,1);
	dohtml_table_header("update",$msg_admin_update,2);
	dohtml_form_label($msg_admin_cache_update,$msg_admin_cache_note);
	dohtml_form_footer($msg_button_update);
	dothml_pagefooter();
	exit;
}
?>
