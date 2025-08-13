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

$CP_HVARS = array (

	'BODY_BGCOLOR'			=> '#F0F0F0',
	'TABLE_BORDER_COLOR'		=> '#000000',
	'TABLE_BORDER_SIZE'		=> '1',
	'TABLE_CELL_1'			=> '#FFFFFF',
	'TABLE_CELL_2'			=> '#F0F0F0',

	'TABLE_HEADER_COLOR'		=> '#415E8D',
	'TABLE_HEADER_TEXT_COLOR'	=> '#FFFFFF',

	'TABLE_HEADER2_COLOR'		=> '#4D71AA',
	'TABLE_HEADER2_TEXT_COLOR'	=> '#FFFFFF',

	'INPUT_TEXT_COLOR'		=> '#000000',
	'INPUT_BGCOLOR'			=> '#FFFFFF',

	'ERROR_HIGHLIGHT_COLOR'		=> '#DD0000',
	
	'TEMPLATE_UNCHANGED_COLOR'	=> '#000000',
	'TEMPLATE_CHANGED_COLOR'	=> '#184A9C',

	'TEXT_COLOR'			=> '#000000'	
	);
 
function show_cp_header($nomargin=0, $body_bgcolor="")
{
	global $CP_HVARS, $TS_SCRIPTS, $OPTIONS;
 
 	if(!$body_bgcolor)
		$body_bgcolor = $CP_HVARS['BODY_BGCOLOR'];

 	echo "<html>";
	
	echo "
	<style type=\"text/css\">
		
	body {
	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;
	SCROLLBAR-BASE-COLOR:  $CP_HVARS[TABLE_HEADER2_COLOR];
	SCROLLBAR-ARROW-COLOR: #FFFFFF;
	}

	input,textarea,select {
	FONT-SIZE: 10px;
	FONT-FAMILY: Verdana,Arial,Helvetica,sans-serif;
	COLOR: #000000;
	BACKGROUND-COLOR: #FFFFFF;
	BORDER: 1px solid black
	}

	.inputlist {
	FONT-SIZE: 10px;
	FONT-FAMILY: Verdana,Arial,Helvetica,sans-serif;
	FONT-WEIGHT: bold;
	COLOR: $CP_HVARS[INPUT_TEXT_COLOR];
	BACKGROUND-COLOR: $CP_HVARS[INPUT_BGCOLOR];
	BORDER: 1px solid black
	}
	
		
	.button {
	FONT-SIZE: 10px;
	FONT-FAMILY: Verdana,Arial,Helvetica,sans-serif;
	FONT-WEIGHT: bold;
	COLOR: #FFFFFF;
	BACKGROUND-COLOR: $CP_HVARS[TABLE_HEADER2_COLOR];
	BORDER: 1px solid
	}

	.header1link {
	COLOR: #FFFFFF
	}
	
	.report {
	FONT-SIZE: 8pt;
	FONT-FAMILY: verdana,arial;
	BACKGROUND-COLOR: #FAFAFA
	BORDER: 1 solid
	}

	</style>
	";
	

	if($nomargin)
	{
		echo "<body link=\"black\" vlink=\"black\" bgcolor=\"$body_bgcolor\" topmargin=0 leftmargin=0 bottommargin=0 rightmargin=0 marginwidth=0 marginheight=0> ";
	}
	else
	{
		echo "<body link=\"black\" vlink=\"black\" bgcolor=\"$body_bgcolor\"> ";		
	}

	echo "<center>";
	
} // end function show_cp_header()

function show_section_info($name, $description)
{

	?>

	<font size=2 face="verdana,arial,tahoma,helvetica">
	<big><big><big><b><?php echo $name; ?></b></big></big></big><br>
	<?php echo $description;?>
	<br><br>
	
	<?php

} // end function show_section_info()

function show_cp_footer()
{

	echo "</td></tr></table>";
	echo "<br><br><font size=2 face=\"verdana,arial,tahoma,helvetica\"><center><small>QuizShock " . TS_VERSION . "<br>";
	echo "Copyright (c)2001-2003 Pineapple Technologies. All Rights Reserved.</small><br><br>";

} // end function show_cp_footer();

function start_form_table($width="100%",$cellpadding=5)
{
	global $CP_HVARS;

	?>
	<table cellpadding=0 cellspacing=0 border=0 width="<?php echo $width; ?>">
	<tr><td bgcolor="<?php echo $CP_HVARS['TABLE_BORDER_COLOR'];?>">
	<table cellpadding=<?php echo $cellpadding;?> cellspacing="<?php echo $CP_HVARS['TABLE_BORDER_SIZE'];?>" border=0 width=100%>
	<?php

} // end function start_form_table()

function end_table($num=1)
{

	for($i=1;$i<=$num;$i++)
		echo "</table>";

} // end function end_table()

function start_form($action="", $method="POST")
{
 
	global $PHP_SELF;
	if(!$action)
		$action = $PHP_SELF;

	echo "<form method=$method name=form action=\"$action\">";

} // end function start_form()

function end_form()
{
	echo "</form>";

} // end function end_form()

function start_table_cell($bgcolor="",$width="",$align="",$valign="",$colspan=1)
{
 
	global $CP_HVARS;

	echo "<td";
	if($bgcolor)
		echo " bgcolor=\"$bgcolor\"";
	if($align)
		echo " align=$align";
	if($valign)
		echo " valign=$valign";
	if($width)
		echo " width=$width";
 
	if($colspan)
		echo " colspan=$colspan";
 
	echo ">";
	echo "<font size=2 face=\"verdana,tahoma,arial\" color=\"$CP_HVARS[TEXT_COLOR]\">";


} // end function start_table_cell()

function end_table_cell()
{
	echo "</font></td>";

} // end function end_table_cell()

function start_table_row()
{
 	echo "<tr>";

} // end function start_table_row()

function end_table_row()
{
	echo "</tr>";

} // end function end_table_row()
 
function do_option_info_cell($name,$description,$currbg,$width="")
{
	start_table_cell($currbg,$width,"","top");
	echo "<b>$name</b><br>";
	echo "<small>$description</small>";

	end_table_cell();

} // end function do_option_info_cell()
function do_table_header($header,$colspan=1,$align="")
{

	global $CP_HVARS;
	start_table_row();
	start_table_cell($CP_HVARS['TABLE_HEADER_COLOR'],"",$align,"",$colspan);
	echo "<font color=\"$CP_HVARS[TABLE_HEADER_TEXT_COLOR]\">$header</font>";

	end_table_cell();

	end_table_row();

} // end function do_table_header()

function do_col_header($header,$colspan=1,$align="center",$width="")
{

	global $CP_HVARS;
	start_table_cell($CP_HVARS['TABLE_HEADER2_COLOR'],$width,$align,"",$colspan);
	echo "<small><b><font color=\"$CP_HVARS[TABLE_HEADER2_TEXT_COLOR]\">$header</font></b></small>";

	end_table_cell();

} // end function do_col_header()

function do_inputtext($name,$value="",$error=0,$size="",$class="input",$text="", $return=0)
{
 
	global $CP_HVARS;
	$txt = "<table cellpadding=2><tr><td";
 
	if($error) 
		$txt .= " bgcolor=\"$CP_HVARS[ERROR_HIGHLIGHT_COLOR]\"";

	
	$txt .=">";


	$txt .= "<input name=\"$name\"";
	if($value != NULL)
	{
		$value = htmlspecialchars(stripslashes($value));

		$txt .= " value=\"$value\"";
		
	} // end if
	if($class)
		$txt .= " class=$class";
	if($size)
		$txt .= " size=$size";

	$txt .= ">";
	if($text)
	{
		$txt .= "</td><td>";
		$txt .= $text;
		
	} // end if
  
	$txt .= "</td></tr></table>";


	if($return)
		return $txt;
	else
		echo $txt;

} // end function do_inputtext()

function do_inputpassword($name,$value="",$error=0, $size="",$class="input", $text="", $return=0)
{
 
	global $CP_HVARS;
	$txt = "<table cellpadding=2><tr><td";
 
	if($error) 
		$txt .= " bgcolor=\"$CP_HVARS[ERROR_HIGHLIGHT_COLOR]\"";

	
	$txt .= ">";

	$txt .= "<input type=password name=\"$name\"";
	if($value)
	{
		$value = htmlspecialchars(stripslashes($value));

		$txt .= " value=\"$value\"";
	 }

	 // If they specified a class for CSS
	 if($class)
		$txt .= " class=$class";

	 // size of the textbox
	 if($size)
		$txt .= " size=$size";

	 $txt .= ">";

	 // if they specified some extra text to show after it, show that
	 if($text)
	{
		end_table_cell();
		start_table_cell();
		$txt .= $text;
	}
  
	$txt .= "</td></tr></table>";

	if($return)
		return $txt;
	else
		echo $txt;

} // function do_inputpassword()

function do_inputtext_plain($name,$value="",$size="",$class="input", $return=0)
{

	$txt = "<input name=\"$name\"";
	if($value != NULL)
	{
		$value = htmlspecialchars(stripslashes($value));

		$txt .= " value=\"$value\"";
	}
	if($class)
		$txt .= " class=$class";
	if($size)
		$txt .= " size=$size";

	$txt .= ">";

	if($return)
		return $txt;
	 else
		echo $txt;


} // end function do_inputtext_plain

function hlink($href, $text, $return=0)
{
 
	$txt .= "<a href=\"$href\">$text</a>";

	if($return)
		return $txt;
	else
		echo $txt;

} // end function hlink()

function hlinkb($href, $text, $return=0)
{
	$txt .= "<small><a href=\"$href\">[$text]</a></small>";

	if($return)
		return $txt;
	else
		echo $txt;

} // end function hlinkb()

function do_textarea($name,$value="",$error=0,$cols,$rows,$class="input", $return=0)
{
 
	global $CP_HVARS;
	$txt = "<table cellpadding=2><tr><td";
 
	if($error)
		$txt .= " bgcolor=\"$CP_HVARS[ERROR_HIGHLIGHT_COLOR]\"";

	$txt .= ">";

	$txt .= "<textarea name=\"$name\" nowrap cols=$cols rows=$rows";
	if($class)
		$txt .= " class=$class";

	$txt .= ">";
	if($value)
	{
		$txt .= htmlspecialchars(stripslashes($value));
	}
	$txt .= "</textarea></td></tr></table>";

	if($return)
		return $txt;
	else
		echo $txt;

} // end function do_textarea()

function do_checkbox($name,$value,$currvalue="",$class="input")
{

	echo "<input type=checkbox name=\"$name\" value=$value";
	if($currvalue == $value)
		echo " checked";
	if($class)
		echo " class=\"$class\"";
	echo ">";

} // end function do_checkbox
function do_radio ($name, $value, $currvalue)
{
 
	echo "<input type=radio name=\"$name\" value=$value";
	if($currvalue == $value)
		echo " checked";
	if($class)
		echo " class=\"$class\"";
 
	echo ">";

} // end function do_radio()
 
function do_yesnoradio($name,$currvalue,$class="")
{
 
	echo "<b>Yes</b> ";
	echo "<input type=radio name=\"$name\" value=1";
	if($currvalue == 1)
		echo " checked";
	if($class)
		echo " class=\"$class\"";
	echo ">";
	echo " &nbsp;<b>No</b> ";
	echo "<input type=radio name=\"$name\" value=0";
 
	if($currvalue == 0)
		echo " checked";
 
	if($class)
		echo " class=\"$class\"";
	echo ">";

} // end function do_yesnoradio()

function do_submitbutton($name,$value,$class="button", $return=0)
{
 
	$txt = "<input type=submit name=\"$name\" value=\"$value\"";
	if($class)
		$txt .= " class=\"$class\"";
	$txt .= ">";

	if($return)
		return $txt;
	else
		echo $txt;
} // end function do_submitbutton()

function do_resetbutton($value="", $class="button")
{
	$value = "Reset";

	echo "<input type=reset value=\"$value\"";
	if($class)
		echo " class=\"$class\"";
	echo ">";

} // end function do_resetbutton()
	
function show_status_message($message)
{
	global $CP_HVARS;

	?>
	<table cellpadding=0 cellspacing=0 border=0 width=100%>
	<tr><td bgcolor="<?php echo $CP_HVARS['TABLE_BORDER_COLOR'];?>">
	<table cellpadding=5 width=100% cellspacing="<?php echo $CP_HVARS['TABLE_BORDER_SIZE'];?>"$
	<tr><td bgcolor="<?php echo $CP_HVARS['TABLE_HEADER_COLOR'];?>" align=center>

	<font size=2 face="verdana,arial" size=2 color="<?php echo $CP_HVARS['TABLE_HEADER_TEXT_COLOR'];?>">
 
	<?php echo $message;?>
 
	</td></tr>
	</table></table>
	<br>
	<?php

} // end function show_status_message()
	
function show_errors($errors)
{
	global $CP_HVARS;

	?>
	<table cellpadding=0 cellspacing=0 border=0 width=100%>
	<tr><td bgcolor="<?php echo $CP_HVARS['TABLE_BORDER_COLOR'];?>">
	<table cellpadding=5 width=100% cellspacing="<?php echo $CP_HVARS['TABLE_BORDER_SIZE'];?>"$
	<tr><td bgcolor="<?php echo $CP_HVARS['TABLE_CELL_1'];?>">

	<font size=2 face="verdana,arial" size=2 color="<?php echo $CP_HVARS['TEXT_COLOR'];?>">

	<center><b>Your changes could not be saved due to the following errors:</b></center>
	<blockquote><blockquote><blockquote><ul type=square>
	<?php
 
	$num_errors = count($errors);
	for($i=0;$i<$num_errors;$i++)
 		echo "<li>$errors[$i]</li>";
	
	 ?>
	</blockquote></blockquote></blockquote></ul>
 	</td></tr>
	</table></table>
	<br>
	<?php

} // end function show_errors()

function switch_bgcolor($currbg)
{
	global $CP_HVARS;
 
	if($currbg == $CP_HVARS['TABLE_CELL_1'])
		$currbg = $CP_HVARS['TABLE_CELL_2'];
 
	else
		$currbg = $CP_HVARS['TABLE_CELL_1'];
 
	return $currbg;

} // end function switch_bgcolor()
function space($numspaces=1)
{
 
	for($i=1;$i<=$numspaces;$i++)
	 	echo "&nbsp;";
		
} // end function space()

function br( $num=1 )
{
 	echo str_repeat('<br />', $num);
}

function do_inputhidden($name,$value)
{
	echo "<input type=hidden name=\"$name\" value=\"$value\">";

} // end function do_inputhidden()
 
function do_select_from_query($name, $value, $query, $option, $optvalue, $class="input", $extra="", $extrav="", $return=0)
{
	global $db;
	$txt = "<select name=\"$name\"";

	if($class)
		$txt .= " class=$class";

	$txt .= ">";

	$result = $db->query($query);
	if($extra)
		$txt .= "<option value=$extrav>$extra</option>";

	while($row = $db->fetch_array($result))
	{
		$o = $row[$option];
		$v = $row[$optvalue];
		$txt .= "<option value=\"$v\"";
		if($value == $row[$optvalue])
			$txt .= " selected";
		$txt .= ">$o</option>";

	} // end while


	$txt .= "</select>";


	if($return)
		return $txt;
	else
		echo $txt;

} // end function do_select_from_query()
 
function do_select_from_array($name, $value, $array, $class="input", $return=0)
{
	$txt = "<select name=\"$name\"";

	if($class)
		$txt .= " class=$class";

	$txt .= ">";

	while(@list($k,$v) = @each($array))
	{
		$txt .= "<option value=\"$v\"";
		if($value == $v)
			$txt .= " selected";
		$txt .= ">$k</option>";

	} // end while

	$txt .= "</select>";
	if($return)
		return $txt;
	else
		echo $txt;

} // end function do_select_from_array()
 
function do_select_from_directory($name, $value, $directory, $extension, $class="input", $multiple=0, $size=0)
{
	echo "<select name=\"$name\"";

	if($class)
		echo " class=$class";
	if($multiple)
		echo " multiple size=$size";

	echo ">";
	$dir = @opendir($directory);
	while($filename = @readdir($dir))
	{
		if(eregi("\.$extension\$", $filename))
		{
			echo "<option value=\"$filename\"";
			if($value == $filename)
				echo " selected";
			echo ">$filename</option>";
		}
		
	} // end while


	echo "</select>";


	echo $txt;

} // end function do_select_from_array()

function load_data_from_db($table,$id,$where="")
{
	global $db;

	$query = "SELECT * FROM $table WHERE id=$id";
	if($where)
		$query .= " $where";
 
	$result = $db->query($query);
	
	return $db->fetch_array($result);


} // end function load_data_from_db()
 
function is_valid_id($id, $new=1)
{
	if(!$id)
		return 0;

	if(ereg("[^0-9]", $id))
	{
		if($new && $id != "new")
			return 0;
		else
			return 1;

		return 0;
	}
	else
		return 1;

} // end function is_valid_id()
 
function num_categories($userid)
{
	global $db, $ts_user;
	if($ts_user->is_admin($userid))
		return $db->query_one_result("SELECT COUNT(*) FROM ts_categories");
	else
		return $db->query_one_result("SELECT COUNT(*) FROM ts_categories WHERE user_id=$userid");
 
} // end function num_categories()
 
function get_question_category($id)
{
	global $db;

	return $db->query_one_result("SELECT category_id FROM ts_questions WHERE id=$id");

} // end function get_question_category()
 
function do_record_page_nav($query_options, $total_records, $record_name, $link_to)
{

	$current_page = $query_options[page];
 
	if($total_records > $query_options[records_per_page])
	{
 
 		$text .= " <font size=2>$total_records $record_name found. Showing $query_options[records_per_page] per page.</font>";
		$text .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$num_pages = ceil($total_records / $query_options[records_per_page]);

		$next_page = $current_page+1;
		$prev_page = $current_page-1;
		$last_page = $num_pages;
		if($current_page>2)
		{
			$query_options[page] = 1;
			
			$text .= " <a href=\"$link_to?" . array_to_get_url("query_options", $query_options) . "\">&lt;&lt;</a>";
		}
		if($current_page>1)
		{
			$query_options[page] = $prev_page;
			
			$text .= " <a href=\"$link_to?" . array_to_get_url("query_options", $query_options) . "\">&lt;</a>";
			
		}
		
		$text .= "&nbsp;&nbsp;Page $current_page of $num_pages &nbsp;&nbsp;";
		if($current_page<$num_pages)
		{
			$query_options[page] = $next_page;
			
			$text .= " <a href=\"$link_to?" . array_to_get_url("query_options", $query_options) . "\">&gt;</a>";
		}
		if($current_page<$num_pages-1)
		{
			$query_options[page] = $last_page;
			$text .= " <a href=\"$link_to?" . array_to_get_url("query_options", $query_options) . "\">&gt;&gt;</a>";
		}
		
		start_table_row();
		 do_col_header($text, 4, "right");
		end_table_row();
 
	 } // end if


} // end function do_record_page_nav()
	 

function is_num_between($value, $min, $max)
{
	if(!is_num($value))
		return 0;

	if($value <= $max && $value >= $min)
		return 1;
	else
		return 0;

} // end function is_num_between()

function is_blank($value)
{
	if(str_replace(" ", "", $value) == NULL)
		return 1;
	else
		return 0;

} // end function is_blank()

function is_num($value)
{

	if($value != NULL)
	{
		if( !ereg("^-?[0-9]+\$", $value) )
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}
	else
	{
		return 1;
	}

} // end function is_num()
function is_email($email)
{
	if( @function_exists("preg_match") )
	{
		if( preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}| [0-9]{1,3})(\]?)\$/", $email) )
		{
			return 1;
		}
		else
		{
			return 0;
		}

	} // end if
	else
	{
		@list($user, $domain) = @explode('@', $email);
		@list($host, $ext) = @explode('.', $domain);
		if( !$ext )
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}

} // end function is_email()

function show_admin_login_form()
{

	echo "<br><br><br><center>"; 
 	start_form();
	start_form_table(500);
	do_table_header("<b>You are not logged in as a valid administrator</b>");
	start_table_row();
	$currbg = switch_bgcolor($currbg);
	start_table_cell($currbg);
	echo "<center><font size=2 face=\"verdana,arial\">";
	echo "<b>Username:</b> <input name=ts_username size=15 class=input>";
	echo " <b>Password:</b> <input name=ts_password type=password class=input size=15> ";
	do_submitbutton("login", "Login", "button");
	echo "</center>";
	end_table_cell();
	end_table_row();
	end_table(2);
	
	end_form();
	
} // end function show_admin_login_form()

?>
