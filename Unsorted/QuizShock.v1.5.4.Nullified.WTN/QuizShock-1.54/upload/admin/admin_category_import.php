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

$HTTP_POST_FILES = ( is_array($_FILES) ) ? $_FILES : $HTTP_POST_FILES;

show_section_info("Import Question Category", "Import a TriviaShock question category file (<b>.tsc</b> file).");

$form_options = get_global_input("form_options");
if(!isset($HTTP_POST_FILES))
{
	$HTTP_POST_FILES[tsc_file]['name'] = get_global_input("tsc_file_name");
	$HTTP_POST_FILES[tsc_file]['size'] = get_global_input("tsc_file_size");
	$HTTP_POST_FILES[tsc_file]['tmp_name'] = get_global_input("tsc_file");
}
if(isset($form_options['submit']))
{
	if(!$HTTP_POST_FILES[tsc_file])
	$errors[] = "You didn't specify a file to upload!";
	elseif(!is_uploaded_file($HTTP_POST_FILES[tsc_file][tmp_name]))
		$errors[] = "Invalid file upload. Please make sure to select a file from your local computer to upload.";
	elseif(!$HTTP_POST_FILES[tsc_file][size])
		$errors[] = "The file you uploaded is empty!";
	if(count($errors))
		show_form($errors);
	else
	{
		$tsc_file = implode("\n", file($HTTP_POST_FILES[tsc_file][tmp_name]));
		$tsc_data = @unserialize($tsc_file);
		if(!validate_file($tsc_data))
		{
			$errors[] = "The question category file you uploaded is not valid or has been 
			corrupted. Please ensure that you have uploaded a valid question
			category file.";
		}
		elseif($tsc_data[version] != '1.0')
		{
			$errors[] = "This import script can only handle version <b>1.0</b> files. The file you upload is a version <b>$tsc_data[version]</b> file and cannot be used by this program. You may need to upgrade your software.";
		}
		if(count($errors))
		{
			show_form($errors);
		}
		else
		{
			@list($category_name, $total_questions) = import_category($tsc_data);

			show_status_message("Category <b>" . $category_name . "</b> ($total_questions questions) successfully imported.");

		} // end else


	} // end else

} // end if isset($submit)
else
{
	show_form();
}

show_cp_footer();

function show_form($errors=array())
{

	if(count($errors))
		show_errors($errors);

	echo "<form action=\"$TS_SCRIPTS[CATEGORY_IMPORT]\" ENCTYPE=\"multipart/form-data\" method=POST>";

	start_form_table();
	do_table_header("<b>Import Question Category</b>", 2);

	$currbg = switch_bgcolor($currbg);
	start_table_row();
	do_option_info_cell("Question Category File", "Select the question category file (from your computer) to upload and import.", $currbg);

	start_table_cell($currbg, "75%");
	echo "<input type=file name=\"tsc_file\" ENCTYPE=\"multipart/form-data\" size=35>";
	end_table_cell();
	end_table_row();

	start_table_row();
	$currbg = switch_bgcolor($currbg);
	start_table_cell($currbg, "", "center", "", 2);
	do_submitbutton("form_options[submit]", "Import Question Category");
	br();
	echo "<small>Please be patient. Large category imports could take a few moments</small>";
	end_table_cell();
	end_table_row();
	end_table(2);

	end_form();


} // end function show_form

function validate_file($tsc_data)
{
	if(!is_array($tsc_data))
	{
echo $tsc_data;
		$bad_file = true;
		echo "p";
	}
	if($tsc_data[type] != 'question category')
		$bad_file = true;
	if(empty($tsc_data[category][name]) || empty($tsc_data[category][description]))
		$bad_file = true;
	if($bad_file)
		return 0;
	else
		return 1;

} // end function validate_file

function import_category($tsc_data)
{

	global $db;

	$category_name = $tsc_data[category][name];
	$category_description = $tsc_data[category][description];
	while($db->query_one_result("SELECT COUNT(*) FROM ts_categories WHERE name='$category_name'"))
		$category_name = $tsc_data[category][name] . '_' . ++$x;
	$db->query("INSERT INTO ts_categories (name,description) VALUES ('$category_name', '$category_description')");
	$category_id = $db->query_one_result("SELECT id FROM ts_categories ORDER BY id DESC LIMIT 1");
	$num_questions = count($tsc_data['category']['questions']);
	for($q=0;$q<$num_questions;$q++)
	{
		$question = $tsc_data[category][questions][$q][question];
		$answer_notes = $tsc_data[category][questions][$q][answer_notes];
		$db->query("INSERT INTO ts_questions (question, answer_notes, category_id) VALUES ('$question', '$answer_notes', '$category_id')");
		$question_id = $db->query_one_result("SELECT id FROM ts_questions WHERE category_id='$category_id' ORDER BY id DESC LIMIT 1");
		$num_answers = count($tsc_data[category][questions][$q][answers]);
		for($a=0;$a<$num_answers;$a++)
		{
			$answer = $tsc_data[category][questions][$q][answers][$a][answer];
			$correct = $tsc_data[category][questions][$q][answers][$a][correct];
			$answer_order = $tsc_data[category][questions][$q][answers][$a][answer_order];
			$db->query("INSERT INTO ts_answers (answer, correct, answer_order, question_id) VALUES ('$answer', '$correct', '$answer_order', '$question_id')");

		} // end for $a (answers)

		$total_questions++;

	} // end for $q (questions)

	return array($category_name, $total_questions);

} // end function import_category


?>
