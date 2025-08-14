<?

$example0='
include_once(\'class/CExcelXMLTableRead.class\');
include_once(\'class/CExcelXMLTableShowHTML.class\');

$ExcelTableRead = new CExcelXMLTableRead(\'file/test.xml\');
// \'file/test.xml\' - path to ExcelXML document

$ExcelTableRead->loadTable();
// read Excel lists with tables from ExcelXML document

$ExcelTableRead->loadStyles();
// read style sheet from ExcelXML document


$ExcelTableHTML = new CExcelXMLTableShowHTML();

$ExcelTableHTML->setStyles($ExcelTableRead->getStyles());
// transfers tables array to CExcelXMLTableShowHTML class
// ExcelTableRead->getStyles() - return Excel style sheet array

$ExcelTableHTML->setTables($ExcelTableRead->getTables());
// transfers style sheet array to CExcelXMLTableShowHTML class
// ExcelTableRead->getTables() - return Excel lists with tables array
';


$example1='
$ExcelTableHTML->showHTMLExcelTable(); 
// show Excel document
';


$example2='
// use EXCEL styles
$ExcelTableHTML->setActiveStyles(
	array(
		\'font_size\'      => \'off\',
		\'font_family\'    => \'on\',
		\'font_color\'     => \'on\',
		\'font_bold\'      => \'on\',
		\'font_italic\'    => \'on\',
		\'font_underline\' => \'on\',

		\'column_width\'   => \'on\',
		\'row_height\'     => \'on\',

		\'text_align\'     => \'off\',
		\'text_valign\'    => \'on\',

		\'bg_color\'       => \'off\',

		\'number_format\'  => \'on\'
	)
);

// default styles for display table to HTML
$ExcelTableHTML->setDefaultStyles(
	array(
		\'table_border\'      => 1,
		\'table_bordercolor\' => \'#CCCCCC\',
		\'table_cellspacing\' => 0,
		\'table_cellpadding\' => 0,
		\'table_style\'       => \'border-collapse:collapse;\',

		\'font_size\'      => \'11\',
		\'font_family\'    => \'\', 
		\'font_color\'     => \'\',
		\'font_bold\'      => \'\',
		\'font_italic\'    => \'\',
		\'font_underline\' => \'\',

		\'text_align\'     => \'\',
		\'text_valign\'    => \'\',

		\'bg_color\'       => \'#F8F8F8\',

		\'column_width\'   => \'64\',
		\'row_height\'     => \'15\',
	)
);

$ExcelTableHTML->showHTMLExcelTable();
';





$example3='
// use EXCEL styles
$ExcelTableHTML->setActiveStyles(
	array(
		\'font_size\'      => \'off\',
		\'font_family\'    => \'off\',
		\'font_color\'     => \'off\',
		\'font_bold\'      => \'off\',
		\'font_italic\'    => \'off\',
		\'font_underline\' => \'on\',

		\'column_width\'   => \'off\',
		\'row_height\'     => \'off\',

		\'text_align\'     => \'off\',
		\'text_valign\'    => \'on\',

		\'bg_color\'       => \'off\',

		\'number_format\'  => \'off\'
	)
);

// default styles for display table to HTML
$ExcelTableHTML->setDefaultStyles(
	array(
		\'table_border\'      => 1,
		\'table_bordercolor\' => "#000000",
		\'table_cellspacing\' => 0,
		\'table_cellpadding\' => 0,
		\'table_style\'       => "border-collapse:collapse;",

		\'font_size\'      => \'10\',
		\'font_family\'    => \'\', 
		\'font_color\'     => \'#FFFFFF\',
		\'font_bold\'      => \'on\',
		\'font_italic\'    => \'\',
		\'font_underline\' => \'\',

		\'text_align\'     => \'center\',
		\'text_valign\'    => \'\',

		\'bg_color\'       => \'#FF7837\',

		\'column_width\'   => \'40\',
		\'row_height\'     => \'10\',
	)
);

$ExcelTableHTML->showHTMLExcelTable();
';


?>