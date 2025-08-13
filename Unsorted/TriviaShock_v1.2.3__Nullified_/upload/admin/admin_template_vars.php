<?php
require("../script_ext.inc");
require("admin_global" . $script_ext);

show_cp_header();
show_section_info("Edit Template Variables", "Template variables control the basic color scheme and look of your trivia site.");

global $form_options_error;

$form_options = get_global_input("form_options");
$form_buttons = get_global_input("form_buttons");
if(isset($form_buttons[submit_savechanges]))
{
 
	if(!($errors = check_input()))
		save_changes();
	cache_templates();

	show_status_message("Your changes have been saved to the database.");
}
elseif(isset($form_buttons[submit_restoredefaults]))
{
	restore_defaults();
	
	show_status_message("Restored all template variables to their default values.");
}

show_form($errors);

show_cp_footer();

function check_input()
{
 // checks consistency on theme variables
 global $form_options, $form_options_error, $db, $DB_TABLES;

 $result = $db->query("SELECT * FROM $DB_TABLES[TEMPLATE_VARS] ORDER BY id ASC");

 // check each message
 while($row = $result->fetch_array())
 {	 
	$i = $row[id];
	
	if(strlen($form_options[$i]) > 65535)
	{
		$errors[] = "<b>$row[the_key]</b> is too long, it must not exceed 65535 characters";
		$form_options_errors[$i] = 1;
	}
	if($form_options[$i] == NULL)
	{
		$errors[] = "<b>$row[the_key]</b> is blank!";
		$form_options_errors[$i] = 1;
	}
							
 } // end while

 return $errors;

} // end function check_input()

function show_form($errors=array())
{
 // This function displays a form to change any option.
 
 // used to keep up with options posted from the form
 global $form_options, $form_options_error, $db, $DB_TABLES;

 // show errors, if we got any
 if(count($errors))
 	show_errors($errors);

 start_form();
 
 start_form_table();

 do_table_header("<b>Editing Template Variables</b>", 2);

 $result = $db->query("SELECT * FROM $DB_TABLES[TEMPLATE_VARS]");

 // for each option in this group
 while($row = $result->fetch_array())
 {
	$i = $row[id];

 
	if(!$form_options['submit_savechanges'])
	{
		$form_options[$i] = $row['the_value'];
	}
	$currbg = switch_bgcolor($currbg);
	start_table_row();
	 do_option_info_cell($row[the_key],$row[description],$currbg, "25%");
 	 start_table_cell($currbg,"75%");
	   do_inputtext("form_options[$i]", $form_options[$i], $form_options_error[$i], 75);
	 end_table_cell();
	end_table_row();

 } // end while $row

 // show submit buttons
 $currbg = switch_bgcolor($currbg);
 start_table_row();
  start_table_cell($currbg, "", "center", "", 2);
  do_submitbutton("form_buttons[submit_savechanges]","Save Changes");
  space(2);
  do_submitbutton("form_buttons[submit_restoredefaults]","Restore Default Values");
  end_table_cell();
 end_table_row();

 end_table(2);
  
} // end function show_form()

function save_changes()
{
 global $form_options, $db, $DB_TABLES;
 
 // unset the submit variable since we don't want to write that to the datbase
 
 // This function saves all of their option changes to the database
 @reset($form_options);
 while(@list($i, $value) = @each($form_options))
 {
 
	$db->query("UPDATE $DB_TABLES[TEMPLATE_VARS] SET the_value='$value' WHERE id=$i");
	
 } // end while

} // end function save_changes()

function restore_defaults()
{
 global $form_options, $db, $DB_TABLES;
 
 // This function restores all of the default "out of the box" template values
 
 $db->query("UPDATE $DB_TABLES[TEMPLATE_VARS] SET the_value=default_value");

} // end function restore_default

?>
