<HTML>
<HEAD>
<TITLE>ExcelXML</TITLE>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<LINK href="svo.css" rel="stylesheet">
</HEAD>

<?php


include_once('class/CExcelXMLTableRead.class');
include_once('class/CExcelXMLTableShowHTML.class');
include_once('examples.php');


$ExcelTableRead = new CExcelXMLTableRead('file/note.xml');
$ExcelTableRead->loadTable();
$ExcelTableRead->loadStyles();


$ExcelTableHTML = new CExcelXMLTableShowHTML();

$ExcelTableHTML->setStyles($ExcelTableRead->getStyles());
$ExcelTableHTML->setTables($ExcelTableRead->getTables());




?>

<DIV align='CENTER'>| &#169; <A href="mailto:sergeyvo@ngs.ru">Sergey Ovchinnikov</A> |</DIV>
<A name='note_1'></A><H1>ExcelXML (read and output to HTML) - NOTES</H1>

<DIV class="text">


<DIV style="PADDING-RIGHT:0px;PADDING-BOTTOM:15px;PADDING-TOP:10px;" align='CENTER'>| <A href="work.php">Test a script</A> |</DIV>


<TABLE border="0" cellspacing="0" cellspacing="0"><TR><TD  class="text">

<DIV style="PADDING-LEFT:0px;PADDING-BOTTOM:7px;"><A href="#note_1">ExcelXML (read and output to HTML)</A> NOTES</DIV>

<DIV style="PADDING-LEFT:20px;PADDING-BOTTOM:7px;"><A href="#note_1-e1">example</A> (Initialization of classes)</DIV>

<DIV style="PADDING-LEFT:20px;PADDING-BOTTOM:3px;"><A href="#note_1-1">Show Excel document</DIV>

<DIV style="PADDING-LEFT:40px;PADDING-BOTTOM:3px;"><A href="#note_1-1-e1">example 1</A> (Shows Excel document with not changed Excel styles)</DIV>

<DIV style="PADDING-LEFT:40px;PADDING-BOTTOM:3px;"><A href="#note_1-1-e2">example 2</A> (Shows Excel document with changed Excel styles)</DIV>

<DIV style="PADDING-LEFT:40px;PADDING-BOTTOM:7px;"><A href="#note_1-1-e3">example 3</A> (Shows Excel document with changed Excel styles)</DIV>

<DIV style="PADDING-LEFT:20px;PADDING-BOTTOM:3px;"><A href="#note_1-2">Access to data of Excel document</A></DIV>

<DIV style="PADDING-LEFT:40px;PADDING-BOTTOM:3px;"><A href="#note_1-2-e1">example 1</A> (Accass to data to Excel tables)</DIV>

<DIV style="PADDING-LEFT:40px;PADDING-BOTTOM:7px;"><A href="#note_1-2-e2">example 2</A> (Accass to data to Excel style sheet)</DIV>

<DIV style="PADDING-LEFT:0px;PADDING-BOTTOM:3px;"><A href="work.php">To test a script</A> (To check up work of a script on the document)</DIV>

</DIV>

</TD>
</TR>
</TABLE>

<BR>

<DIV class="text"><B>If you had questions or offers communicate with me by mail <A href='mailto:sergeyvo@ngs.ru'>sergeyvo@ngs.ru</A></B></DIV>

<br>
<div class="text"><b>
WEB development (PHP, ASP, mySQL, PostgeSQL, MS SQL, HTML, JavaScript, XML).<br>
My online resume <a href="http://serj.mywdk.com/resume">http://serj.mywdk.com/resume</a>
</b></div>


<BR><BR>

<DIV class="text">
use class <B>CExcelXMLTableRead.class</B> for load ExcelXML document <BR>
use class <B>CExcelXMLTableShowHTML.class</B> for output of ExcelXML to HTML <BR><BR>
</DIV>
<A name='note_1-e1'></A><H3>example</H3>
<DIV class="text"  style="PADDING-BOTTOM:10px;">Initialization of classes</DIV>

</DIV>
<?


echo "<TABLE cellspacing=\"0\" cellpadding=\"0\"><TR>";
echo "<TD valign='top' bgColor=\"#EFEFEF\" class=\"code\">";
Highlight_string("<?".$example0."?>");
echo "</TR></TABLE>";


echo "<A name='note_1-1'></A><H2>Show Excel document</H2>";

echo "<A name='note_1-1-e1'></A><H3>example 1</H3>";
echo "<DIV class=\"text\"  style=\"PADDING-BOTTOM:10px;\">Shows Excel document  with not changed Excel styles</DIV>";
echo "<TABLE cellspacing=\"0\" cellpadding=\"0\" width=100%>";
echo "<TR><TD class=\"bright\" style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215 style=\"PADDING-LEFT: 15px;\">Code:</TD><TD style=\"border-bottom: 1pt solid #999999;\">&nbsp;</TD><TD style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215>Result:</TD></TR>";
echo "<TR><TD valign='top' bgColor=\"#EFEFEF\"  class=\"bright\" style=\"PADDING-LEFT: 15px;PADDING-RIGHT: 10px;PADDING-TOP: 10px;PADDING-BOTTOM: 10px;\">";
Highlight_string("<?".$example1."?>");
echo "<TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD><TD valign='top'  style=\"PADDING-TOP:10px;\">";
$ExcelTableHTML->showHTMLExcelTable();
echo "</TD></TR></TABLE>";





echo "<A name='note_1-1-e2'></A><H3>example 2</H3>";
echo "<DIV class=\"text\"  style=\"PADDING-BOTTOM:10px;\">Shows Excel document with changed Excel styles</DIV>";
echo "<TABLE cellspacing=\"0\" cellpadding=\"0\" width=100%>";
echo "<TR><TD class=\"bright\" style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215 style=\"PADDING-LEFT: 15px;\">Code:</TD><TD style=\"border-bottom: 1pt solid #999999;\">&nbsp;</TD><TD style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215>Result:</TD></TR>";
echo "<TR><TD valign='top' bgColor=\"#EFEFEF\"  class=\"bright\" style=\"PADDING-LEFT: 15px;PADDING-RIGHT: 10px;PADDING-TOP: 10px;PADDING-BOTTOM: 10px;\">";
Highlight_string("<?".$example2."?>");
echo "<TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD><TD valign='top'  style=\"PADDING-TOP:10px;\">";
// use EXCEL styles
$ExcelTableHTML->setActiveStyles(
	array(
		'font_size'      => 'off',
		'font_family'    => 'on',
		'font_color'     => 'on',
		'font_bold'      => 'on',
		'font_italic'    => 'on',
		'font_underline' => 'on',

		'column_width'   => 'on',
		'row_height'     => 'on',

		'text_align'     => 'off',
		'text_valign'    => 'on',

		'bg_color'       => 'off',

		'number_format'  => 'on'
	)
);

// default styles for display table to HTML
$ExcelTableHTML->setDefaultStyles(
	array(
		'table_border'      => 1,
		'table_bordercolor' => "#CCCCCC",
		'table_cellspacing' => 0,
		'table_cellpadding' => 0,
		'table_style'       => "border-collapse:collapse;",

		'font_size'      => '11',
		'font_family'    => '', 
		'font_color'     => '',
		'font_bold'      => '',
		'font_italic'    => '',
		'font_underline' => '',

		'text_align'     => '',
		'text_valign'    => '',

		'bg_color'       => '#F8F8F8',

		'column_width'   => '64',
		'row_height'     => '15',
	)
);

$ExcelTableHTML->showHTMLExcelTable();
echo "</TD></TR></TABLE>";






echo "<A name='note_1-1-e3'></A><H3>example 3</H3>";
echo "<DIV class=\"text\"  style=\"PADDING-BOTTOM:10px;\">Shows Excel document with changed Excel styles</DIV>";
echo "<TABLE cellspacing=\"0\" cellpadding=\"0\" width=100%>";
echo "<TR><TD class=\"bright\" style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215 style=\"PADDING-LEFT: 15px;\">Code:</TD><TD style=\"border-bottom: 1pt solid #999999;\">&nbsp;</TD><TD style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215>Result:</TD></TR>";
echo "<TR><TD valign='top' bgColor=\"#EFEFEF\"  class=\"bright\" style=\"PADDING-LEFT: 15px;PADDING-RIGHT: 10px;PADDING-TOP: 10px;PADDING-BOTTOM: 10px;\">";
Highlight_string("<?".$example3."?>");
echo "<TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD><TD valign='top'  style=\"PADDING-TOP:10px;\">";
// use EXCEL styles
$ExcelTableHTML->setActiveStyles(
	array(
		'font_size'      => 'off',
		'font_family'    => 'off',
		'font_color'     => 'off',
		'font_bold'      => 'off',
		'font_italic'    => 'off',
		'font_underline' => 'on',

		'column_width'   => 'off',
		'row_height'     => 'off',

		'text_align'     => 'off',
		'text_valign'    => 'on',

		'bg_color'       => 'off',

		'number_format'  => 'off'
	)
);

// default styles for display table to HTML
$ExcelTableHTML->setDefaultStyles(
	array(
		'table_border'      => 1,
		'table_bordercolor' => "#000000",
		'table_cellspacing' => 0,
		'table_cellpadding' => 0,
		'table_style'       => "border-collapse:collapse;",

		'font_size'      => '10',
		'font_family'    => '', 
		'font_color'     => '#FFFFFF',
		'font_bold'      => 'on',
		'font_italic'    => '',
		'font_underline' => '',

		'text_align'     => 'center',
		'text_valign'    => '',

		'bg_color'       => '#FF7837',

		'column_width'   => '40',
		'row_height'     => '10',
	)
);

$ExcelTableHTML->showHTMLExcelTable();
echo "</TD></TR></TABLE>";





echo "<A name='note_1-2'></A><H2>Access to data of Excel document</H2>";


echo "<A name='note_1-2-e1'></A><H3>example 1</H3>";
echo "<DIV class=\"text\"  style=\"PADDING-BOTTOM:10px;\">Accass to data to Excel tables</DIV>";
echo "<TABLE cellspacing=\"0\" cellpadding=\"0\" width=100%>";
echo "<TR><TD class=\"bright\" style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215 style=\"PADDING-LEFT: 15px;\">Code:</TD><TD style=\"border-bottom: 1pt solid #999999;\">&nbsp;</TD><TD style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215>Result:</TD></TR>";
echo "<TR><TD valign='top' bgColor=\"#EFEFEF\"  class=\"bright\" style=\"PADDING-LEFT: 15px;PADDING-RIGHT: 10px;PADDING-TOP: 10px;PADDING-BOTTOM: 10px;\">";
Highlight_string("<?\n".'$ExcelTableRead->getTables();'."\n?>");
echo "<TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD><TD valign='top'  style=\"PADDING-TOP:10px;\">";
pr($ExcelTableRead->getTables());
echo "</TD></TR></TABLE>";



echo "<A name='note_1-2-e2'></A><H3>example 2</H3>";
echo "<DIV class=\"text\"  style=\"PADDING-BOTTOM:10px;\">Accass to data to Excel style sheet</DIV>";
echo "<TABLE cellspacing=\"0\" cellpadding=\"0\" width=100%>";
echo "<TR><TD class=\"bright\" style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215 style=\"PADDING-LEFT: 15px;\">Code:</TD><TD style=\"border-bottom: 1pt solid #999999;\">&nbsp;</TD><TD style=\"border-bottom: 1pt solid #999999;\"><B><I><FONT color=#FF6215>Result:</TD></TR>";
echo "<TR><TD valign='top' bgColor=\"#EFEFEF\"  class=\"bright\" style=\"PADDING-LEFT: 15px;PADDING-RIGHT: 10px;PADDING-TOP: 10px;PADDING-BOTTOM: 10px;\">";
Highlight_string("<?\n".'$ExcelTableRead->getStyles();'."\n?>");
echo "<TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD><TD valign='top'  style=\"PADDING-TOP:10px;\">";
pr($ExcelTableRead->getStyles());
echo "</TD></TR></TABLE>";




   



#============================================================================
#        PRINT DEDUB MODE
#============================================================================
function pr($data) {

	echo "<PRE><FONT size=1>";
	print_r($data);
	echo "</FONT></PRE>";
}

?>


<BR>


<DIV class="text"><B>If you had questions or offers communicate with me by mail <A href='mailto:sergeyvo@ngs.ru'>sergeyvo@ngs.ru</A></B></DIV>
<br>
<div class="text"><b>
WEB development (PHP, ASP, mySQL, PostgeSQL, MS SQL, HTML, JavaScript, XML).<br>
My online resume <a href="http://serj.mywdk.com/resume">http://serj.mywdk.com/resume</a>
</b></div>



<DIV style="PADDING-RIGHT:0px;PADDING-BOTTOM:15px;PADDING-TOP:15px;" align='CENTER'>| &#169; <A href="mailto:sergeyvo@ngs.ru">Sergey Ovchinnikov</A> |</DIV>
