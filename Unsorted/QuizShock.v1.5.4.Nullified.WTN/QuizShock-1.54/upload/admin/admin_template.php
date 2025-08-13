<?php
/************************************************************************/
/*  Program Name         : QuizShock                                    */
/*  Program Version      : 1.5.4                                        */
/*  Program Author       : Pineapple Technologies                       */
/*  Supplied by          : CyKuH [WTN]                                  */
/*  Nullified by         : CyKuH [WTN]                                  */
/*  Distribution         : via WebForum and Forums File Dumps           */
/*                  (c) WTN Team `2004                                  */
/*   Copyright (c)2002 Pineapple Technologies. All Rights Reserved.     */
/************************************************************************/

require("../script_ext.inc");
require("admin_global" . $script_ext);

show_cp_header();
show_section_info("Edit Template", "Use the form below to edit a template.");

$form_options = get_global_input("form_options");

if(!isset($form_options[id]))
	$form_options[id] = get_global_input("id");

global $form_options_error;

if(is_valid_id($form_options['id']))
{

	if(isset($form_options['submit']))
	{
		if(!($errors = check_input()))
		{
			$db_stuff['data'] = str_replace("\r", "", $form_options['data']);
			$db_stuff['changed'] = 1;
						
			$db->update_from_array($db_stuff, ts_templates, $form_options[id]);
			
			show_status_message("Your changes have been saved to the database");
			cache_templates();
		}
	}
	elseif($form_options[id])
	{
		$form_options = load_data_from_db(ts_templates, $form_options[id]);
	
	}

} // end if is_valid_id()

show_form($errors);

show_cp_footer();

function show_form($errors=array())
{
 // displays the category adding/editing form

 global $form_options,$form_options_error, $db;
 
 // if we got errors, show them
 if(count($errors))
 	show_errors($errors);

 start_form();
 start_form_table();

 do_table_header("<b>Editing Template</b> - <small>$form_options[the_key]</small>", 2);

 $currbg = switch_bgcolor($currbg);

 // template id in a hidden variable
 do_inputhidden("form_options[id]", $form_options['id']);

 ////////// Template NAME //////////

 $currbg = switch_bgcolor($currbg);

 start_table_row();
 
 // show the info cell on the left
 // containing the name and the description
 do_option_info_cell("Template Name", "The name of this template.",$currbg, "35%");
  
 // start a new table cell
 start_table_cell($currbg, "65%");

 // template id in a hidden variable
 do_inputhidden("form_options[the_key]", $form_options['the_key']);

 echo "<b>$form_options[the_key]</b>";

 end_table_cell();
 end_table_row();


 ////////// Template Description //////////

 $currbg = switch_bgcolor($currbg);

 start_table_row();
 
 // show the info cell on the left
 // containing the name and the description
 do_option_info_cell("Description", "A short description of this template.",$currbg, "35%");
  
 // start a new table cell
 start_table_cell($currbg, "65%");


 do_inputhidden("form_options[description]", $form_options['description']);

 echo "$form_options[description]";

 end_table_cell();
 end_table_row();


 ////////// Template Data //////////

 $currbg = switch_bgcolor($currbg);

 start_table_row();

 // show the info cell on the left
 // containing the name and the description
 do_option_info_cell("Template","This is the template from which the corresponding page will be generated from.",$currbg, "35%");
  
 // start a new table cell
 start_table_cell($currbg, "65%");

 // Show the text input box
 do_textarea("form_options[data]", str_replace("\r", "", $form_options['data']), $form_options_error['data'], 80, 20);

 end_table_cell();
 end_table_row();

 $currbg = switch_bgcolor($currbg);
 start_table_row();
 start_table_cell($currbg, "", "center", "", 2);
 do_submitbutton("form_options[submit]", "Save Changes");
 space(2);
 do_resetbutton();
 end_table_cell();
 end_table_row();
 end_table(2);

} // end function show_form()

function check_input()
{
 global $form_options,$form_options_error;


 // make sure it's not too long
 if(strlen($form_options['data']) > 65536)
 {
	$errors[] = "<b>Template</b> can not exceed 64 kilobytes in length.";
	$form_options_error[$i] = 1;
 }

	
 // That's it, return the errors
 return $errors;

} // end function check_input

?>
