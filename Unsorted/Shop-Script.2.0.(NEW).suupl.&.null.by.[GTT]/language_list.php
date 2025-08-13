<?

/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



	//this file indicates listing of all available languages

class Language
{
	var $description; //language name
	var $filename; //language PHP constants file
	var $template; //template filename
}

	//whole list of languages
	$lang_list = array();

	//to add new languages add similiar structures

	$lang_list[0] = new Language();
	$lang_list[0]->description = "Ðóññêèé";
	$lang_list[0]->filename = "includes/language/russian.php";
	$lang_list[0]->template = "templates/tmpl1/rus.html";

	$lang_list[1] = new Language();
	$lang_list[1]->description = "English";
	$lang_list[1]->filename = "includes/language/english.php";
	$lang_list[1]->template = "templates/tmpl1/eng.html";

?>