<?php
require("../script_ext.inc");
require("admin_global" . $script_ext);

$form_options[restore_default] = get_global_input("restore_default");

show_cp_header();

show_section_info("Browse Templates", "Below is a list of each template used to generate the HTML of your trivia page");
if(isset($form_options[restore_default]))
{
	$db->query("UPDATE $DB_TABLES[TEMPLATES] SET data=default_data, changed=0 WHERE id=$form_options[restore_default]");
	$the_key = $db->query_one_result("SELECT the_key FROM $DB_TABLES[TEMPLATES] WHERE id=$form_options[restore_default]");
	
	cache_templates();
	
	show_status_message("Restored default template for <b>$the_key</b>");
	
}

echo "<table>";
echo "<font size=3 face=\"verdana,arial,tahoma\">";
echo "Templates which have not been changed from their default are <b><font color=\"$CP_HVARS[TEMPLATE_UNCHANGED_COLOR]\">this color</font></b><br>";
echo "Templates which have been changed are shown in <b><font color=\"$CP_HVARS[TEMPLATE_CHANGED_COLOR]\">this color</font></b><br><br>";


show_template_list();
echo "</table>";

show_cp_footer();

function show_template_list($parent=0)
{
	global $db, $DB_TABLES, $TS_SCRIPTS, $CP_HVARS;
	$result = $db->query("SELECT id,the_key,description,changed FROM $DB_TABLES[TEMPLATES] WHERE parent=$parent ORDER BY id ASC");
	echo "<blockquote>";
	
	while(@list($id, $the_key, $description, $changed) = $result->fetch_array())
	{
		if($changed)
			echo "<font color=\"$CP_HVARS[TEMPLATE_CHANGED_COLOR]\">";
		else
			echo "<font color=\"$CP_HVARS[TEMPLATE_UNCHANGED_COLOR]\">";
			
		echo "<b>$the_key</b></font>";
		
		space(4);
		echo hlinkb("$TS_SCRIPTS[TEMPLATE]?id=$id", "Edit", 1);
		
		space(2);
		echo hlinkb("$TS_SCRIPTS[TEMPLATE_BR]?restore_default=$id", "Restore Default", 1) .
		
		"<br><small>$description</small>";
		show_template_list($id);
	}
	
	echo "</blockquote>";


} // end function show_template_list()


?>
