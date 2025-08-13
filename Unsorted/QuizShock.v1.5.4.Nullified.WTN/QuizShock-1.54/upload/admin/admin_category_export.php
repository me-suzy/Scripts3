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

$form_options[category_id] = get_global_input("category_id");
$go = get_global_input("go");
if(!category_exists($form_options[category_id]))
{
	error_out("Whoops", "The category you specified does not exist!");
}
if(!$go)
{
	show_cp_header();
	show_section_info("Export Category", "Save a question category to a file.");
	echo "<meta http-equiv=\"refresh\" content=\"1;URL=$TS_SCRIPTS[CATEGORY_EXPORT]?category_id=$form_options[category_id]&go=1\">";
	show_status_message("Now exporting category: <b>" . get_category_name($form_options[category_id]) . "</b>. The file will be sent to you in a few moments.<br><br> <small><a href=\"$TS_SCRIPTS[CATEGORY_EXPORT]?category_id=$form_options[category_id]&go=1\" class=header1link>(Click here if your browser does not automatically forward you.)</a>");

	show_cp_footer();

	exit;
}
$tsc_data = array();
@set_magic_quotes_runtime(1);

$tsc_data[type] = 'question category';
$tsc_data[version] = '1.0';
$result = $db->query("SELECT name,description FROM ts_categories WHERE id=$form_options[category_id]");
$row = $db->fetch_array($result);
$tsc_data[category][name] = $row['name'];
$tsc_data[category][description] = $row['description'];
$result = $db->query("SELECT * FROM ts_questions WHERE ts_questions.category_id=$form_options[category_id]");

$question_number=0;
while($row = $db->fetch_array($result))
{
	$tsc_data[category][questions][$question_number][question] = $row[question];
	$tsc_data[category][questions][$question_number][answer_notes] = $row[answer_notes];
	$result2 = $db->query("SELECT * FROM ts_answers WHERE question_id=$row[id] ORDER BY id ASC");
	
	$answer_number=0;
	while($row2 = $db->fetch_array($result2))
	{
		$tsc_data[category][questions][$question_number][answers][$answer_number][answer] = $row2[answer];
		$tsc_data[category][questions][$question_number][answers][$answer_number][correct] = $row2[correct];
		$tsc_data[category][questions][$question_number][answers][$answer_number][answer_order] = $row2[answer_order];
		$answer_number++;
	}
	
	$question_number++;
}
$filename = strtolower(str_replace(" ", "_", $tsc_data[category][name])) . ".tsc";

header("Content-Type: application/ts-category");

if(eregi("IE", $HTTP_USER_AGENT))
	$content_disp = "inline";
else
	$content_disp = "attachment";

header("Content-Disposition:  " . $content_disp . "; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
echo @serialize($tsc_data);

?>
