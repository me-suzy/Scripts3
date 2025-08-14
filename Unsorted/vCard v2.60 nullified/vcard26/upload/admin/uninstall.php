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
 ?>
 <HTML>
	<HEAD>
	<title>vCard Unistall Script</title>
        <style type="text/css">
        BODY            {background-color: #F0F0F0; color: #3F3849; font-family: Verdana; font-size: 12px;
        UL              {font-family: Verdana; font-size: 12px}
        LI              {font-family: Verdana; font-size: 12px}
        P               {font-family: Verdana; font-size: 12px}
        TD              {font-family: Verdana; font-size: 12px}
        TR              {font-family: Verdana; font-size: 12px}
        H2              {font-family: Verdana; font-size: 14px}

        A               {font-family: Verdana; text-decoration: none;}
        A:HOVER         {font-family: Verdana; COLOR: #FFAC00;}
        A:ACTIVE        {font-family: Verdana; COLOR: #FFAC00;}

        FORM            {font-family: Verdana; font-size: 10px}
        SELECT          {font-family: Verdana; font-size: 10px}
        INPUT           {font-family: Verdana; font-size: 10px}
        TEXTAREA        {font-family: Verdana; font-size: 10px}
        .title          {font-family: Verdana; font-size: 25px}
        </style>
        </HEAD>
<BODY bgcolor="#CCCCCC" text="#3F3849" link="#3F3849" vlink="#3F3849" alink="#3F3849" leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">
<p class="title"><b>vCard Uninstall Script</font></b></p><br>
<hr size="1" noshade>
<?php
$step = $HTTP_GET_VARS['step'];
if (empty($step))
{
    echo "<p><b>Are you sure, uninstall vCard database tables and them contents?</b></p>";
    echo "<p>Yes, I'm sure. <a href=\"$PHP_SELF?step=2\">Click here to continue --></a></p>";
}

if ($step == 2)
{
	include "./config.inc.php";
	include("./db_mysql.inc.php");
	include("./functions.inc.php");
	
	$DB_site = new DB_Sql_vc;
	$DB_site->server = $hostname;
	$DB_site->user = $dbUser;
	$DB_site->password = $dbPass;
	$DB_site->database = $dbName;
	$DB_site->connect();
	$dbPass = "";
	$DB_site->password = "";
	
	//*********************************************
	$DB_site->query("DROP TABLE IF EXISTS vcard_abook ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_account ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_address ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_attach ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_cards ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_cardsgroup ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_category ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_emaillog ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_event ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_pattern ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_poem ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_rating ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_replace ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_search ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_session ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_setting ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_settinggroup ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_sound ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_stamp ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_stats ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_template ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_template_o ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_user ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_word ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_searchlog ");
	$DB_site->query("DROP TABLE IF EXISTS vcard_statsextfile");
	$DB_site->query("DROP TABLE IF EXISTS vcard_spam");
	$DB_site->query("DROP TABLE IF EXISTS vcard_cache");
	$DB_site->query("DROP TABLE IF EXISTS vcard_useronline");
	
	echo "Drop all vCard Tables: DONE...";
	echo "<p>Do you want Reinstall vCard again? <a href=\"install.php\">Click here to Install --></a></p>";
}

?>

