<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* ======================                                               */
/* Based on Automated FAQ                                               */
/* Copyright (c) 2001 by                                                */
/*    Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)         */
/*    Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)                 */
/* http://www.phpnuke.web.id                                            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

if (!isset($mainfile)) { include("mainfile.php"); }
global  $Pmenu,$breadcrumb,$sitename;
$Pmenu="P_thememenu_faq";
$breadcrumb="Frequently Asked Questions";
getvbpvars();
function ShowFaq($id_cat, $categories) {
    global $bgcolor2, $sitename, $prefix;
    OpenTable();
    echo"<center><font size=\"2\"><b>$sitename "._FAQ2."</b></font></center><br><br>"
	."<a name=\"top\"><br>"
	.""._CATEGORY.": <a href=\"modules.php?op=modload&amp;name=FAQ&amp;file=index\">"._MAIN."</a> -> $categories"
	."<br><br>"
	."<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\">"
	."<tr bgcolor=\"$bgcolor2\"><td colspan=\"2\"><font size=\"3\"><b>"._QUESTION."</b></font></td></tr><tr><td colspan=\"2\">";
    $result = mysql_query("select id, id_cat, question, answer from $prefix"._faqAnswer." where id_cat='$id_cat'");
    while(list($id, $id_cat, $question, $answer) = mysql_fetch_row($result)) {
	echo"<br><strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"#$id\">$question</a>";
    }
    echo "</td></tr></table>
    <br>";
}

function ShowFaqAll($id_cat) {
    global $bgcolor2, $prefix;
    echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\">"
	."<tr bgcolor=\"$bgcolor2\"><td colspan=\"2\"><font size=\"3\"><b>"._ANSWER."</b></font></td></tr>";
    $result = mysql_query("select id, id_cat, question, answer from $prefix"._faqAnswer." where id_cat='$id_cat'");
    while(list($id, $id_cat, $question, $answer) = mysql_fetch_row($result)) {
	echo"<tr><td><a name=\"$id\">"
	    ."<strong><big>&middot;</big></strong>&nbsp;&nbsp;<b>$question</b>"
	    ."<p align=\"justify\">$answer</p>"
	    ."<a href=\"#top\">"._BACKTOTOP."</a>"
	    ."<br><br>"
	    ."</td></tr>";
    }
    echo "</table><br><br>"
	."<div align=\"center\"><b>[ <a href=\"modules.php?op=modload&amp;name=FAQ&amp;file=index\">"._BACKTOFAQINDEX."</a> ]</b></div>";
}

if (!$myfaq) {
    include("header.php");
    OpenTable();
    echo "<center><font size=\"3\">"._FAQ2."</font></center><br><br>"
	."<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\">"
    ."<tr><td bgcolor=\"$bgcolor2\"><font size=\"3\"><b><a href=\"$bburl/misc.php?s=&action=faq\">"._VBULLETIN_FORUMS."</a></b>
	</font></td></tr><tr><td>";
    echo "<strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"$bburl/misc.php?s=&action=faq&page=1\">"._USER_MAINTENANCE."</a>";
	echo "<br><strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"$bburl/misc.php?s=&action=faq&page=2\">"._GENERAL_USEAGE."</a>";
	echo "<br><strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"$bburl/misc.php?s=&action=faq&page=3\">"._READING_POSTING."</a></td></tr>";
	echo "<tr><td bgcolor=\"$bgcolor2\"><font size=\"3\"><b>"._VBPCATEGORIES."</b></font></td></tr><tr><td>";
    $result = mysql_query("select id_cat, categories from $prefix"._faqCategories."");
    while(list($id_cat, $categories) = mysql_fetch_row($result)) {
	$catname = urlencode($categories);
	echo"<strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"modules.php?op=modload&amp;name=FAQ&amp;file=index&amp;myfaq=yes&amp;id_cat=$id_cat&amp;categories=$catname\">$categories</a><br>";
    }
    echo "</td></tr></table>";
    CloseTable();
    include("footer.php");
} else {
    include("header.php");
    ShowFaq($id_cat, $categories);
    ShowFaqAll($id_cat);
    CloseTable();
    include("footer.php");
}

?>