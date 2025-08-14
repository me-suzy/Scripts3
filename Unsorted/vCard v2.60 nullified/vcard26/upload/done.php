<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
$templatesused = 'cardsent,categories_list,categories_list_maincat,categories_list_subcat';
$chaceitemused = 'categories_extended_list';
require('./lib.inc.php');

$ctimespamtbl = time()-3600;
$clean = $DB_site->query("DELETE FROM vcard_spam WHERE date < '$ctimespamtbl' ");

if($vcachesys != 1)
{
	$categories_list = get_html_cat_extended_list();
}else{
	$categories_list = get_vc_cached_item('categories_extended_list');
}

$topx = "$MsgTop $gallery_toplist_value $MsgPostcards";
$topx_list_cat 	= $topx_list;
eval("make_output(\"".get_template("cardsent")."\");");
echo "</form>";
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>