<?php
error_reporting(7);
require("./global.php");

// script variables and functions
// -----------------------------------------
$script = 'vbhlinstall.php';
$version = '3.6';
$threadlink = 'http://www.vbulletin.org/forum/showthread.php?s=&threadid=36756';

// check if the field exists
// by Freddie Bingham
function dofields($field, $table) {
  global $DB_site;

  $DB_site->reporterror = 0;
  $DB_site->query("SELECT COUNT(" . $field . ") AS count FROM " . $table);
  $errno = $DB_site->errno;
  if (!$errno) {
    $errno = 0;
  }
  $DB_site->reporterror = 1;
  if ($errno) {
    return 0;
  } else {
    return 1;
  }
}

// do the templates
function dotemplate($title, $template, $templatesetid=-1) {
  global $DB_site,$step;

  if ($step != 2) {
    $DB_site->reporterror = 1;
  }
  $DB_site->query("INSERT INTO template (templateid,templatesetid,title,template) VALUES (NULL,'$templatesetid','" . addslashes($title) . "','" . addslashes($template) . "')");
  echo "Installing <font color='#006699'>$title</font> template... Done.<br>\n";
}

// remove the templates
function killtemplate($title, $templatesetid=-1) {
  global $DB_site,$step;

  if ($step != 2) {
    $DB_site->reporterror = 1;
  }
  $DB_site->query("DELETE FROM template WHERE title='" . addslashes($title) . "' AND templatesetid=-1");
  echo "Removing <font color='#FF3300'>$title</font> template... Done.<br>\n";
}

// do the queries
function doqueries() {
  global $DB_site,$step,$query,$explain;

  if ($step != 2) {
    $DB_site->reporterror = 1;
  }

  while (list($key,$val) = each($query)) {
    echo "$explain[$key]<br>\n";
    echo "<"."!-- ".htmlspecialchars($val)." --".">\n\n";
    $DB_site->query($val);
  }
  unset ($query);
  unset ($explain);
}

// do the input code
function doinput($title,$name,$value="",$htmlise=1,$size=35) {
  if ($htmlise) {
    $value = htmlspecialchars($value);
  }

  echo "<tr class='" . getrowbg() . "'>\n";
  echo "<td>$title</td>\n";
  echo "<td><p id='submitrow'><input type='text' size='$size' name='$name' value='$value'></p></td>\n";
  echo "</tr>\n";
}

// templates code
// -----------------------------------------
$tplname01 = 'home';
$tplcode01 = '{htmldoctype}
<html>
<head>
<!-- no cache headers -->
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="no-cache">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<!-- end no cache headers -->
<!-- do not edit the links in this area, instead add your information to it -->
<meta name="keywords" content="teckwizards,vbulletin,forum,bbs,discussion,jelsoft">
<meta name="description" content="vbHome (lite) is a welcome page powered by vBulletin. To visit the author\'s website, go to http://www.teckwizards.com/ . To find out about vBulletin, go to http://www.vbulletin.com/ .">
<!-- end -->
<title>$hometitle</title>
$homeheadinclude
</head>
<body>
$homeheader
<table cellpadding="4" cellspacing="{tableinnerborderwidth}" border="0" {tableinnerextra} width="100%">
<tr>
<td width="220" valign="top">
<table cellpadding="{tableouterborderwidth}" cellspacing="0" border="0" bgcolor="{tablebordercolor}" {tableouterextra} width="100%">
<tr>
<td>
<table cellpadding="4" cellspacing="{tableinnerborderwidth}" border="0" {tableinnerextra} width="100%">
<tr id="cat">
<td bgcolor="{categorybackcolor}"><smallfont><font color="{categoryfontcolor}">$welcometext</font></smallfont></td>
</tr>
$userloggedinout
$pminfo
$search
</table>
</td>
</tr>
</table>
<br>
$latestpoll
$advertisement
<table cellpadding="{tableouterborderwidth}" cellspacing="0" border="0" bgcolor="{tablebordercolor}" {tableouterextra} width="100%">
<tr>
<td>
<table cellpadding="4" cellspacing="{tableinnerborderwidth}" border="0" {tableinnerextra} width="100%">
<tr id="cat">
<td bgcolor="{categorybackcolor}"><smallfont><a href="$bburl/index.php?s="$session[sessionhash]"><b>Latest Forum Discussions</b></a></smallfont></td>
</tr>
$threadbits
</table>
</td>
</tr>
</table>
$links
</td>
<td width="7">&nbsp;</td>
<td valign="top">$articlebits</td>
</tr>
</table>
$homefooter
</body>
</html>';
$tplname02 = 'home_articlebits';
$tplcode02 = '<table cellpadding="{tableouterborderwidth}" cellspacing="0" border="0" bgcolor="{tablebordercolor}" {tableouterextra} width="100%">
<tr>
<td>
<table cellpadding="4" cellspacing="{tableinnerborderwidth}" border="0" {tableinnerextra} width="100%">
<tr id="cat">
<td bgcolor="{categorybackcolor}"><normalfont>$article[icon] <a href="$bburl/showthread.php?s=$session[sessionhash]&threadid=$article[threadid]"><b>$article[title]</b></a><normalfont></td>
</tr>
<tr>
<td bgcolor="{secondaltcolor}"><smallfont>Posted by <a href="$bburl/member.php?s=$session[sessionhash]&action=getinfo&userid=$article[postuserid]">$article[postusername]</a> on $article[date] <font color="{timecolor}">$article[time]</font> &nbsp; &nbsp; - $article[replycount] comment$pluralcomment</smallfont></td>
</tr>
<tr>
<td bgcolor="{firstaltcolor}"><normalfont>$article[avatar]$article[message]<br></normalfont></td>
</tr>
<tr>
<td bgcolor="{secondaltcolor}" align="right"><smallfont>$articlecomments &nbsp; <a href="$bburl/printarticle.php?s=$session[sessionhash]&threadid=$article[threadid]" target="_blank"><img border="0" src="{imagesfolder}/articleprint.gif" align="absmiddle" alt="Printer Friendly version"></a> <a href="$bburl/sendtofriend.php?s=$session[sessionhash]&threadid=$article[threadid]" target="_blank"><img border="0" src="{imagesfolder}/articleemail.gif" align="absmiddle" alt="Email this Article to a friend"></a></smallfont></td>
</tr>
</table>
</td>
</tr>
</table>
<br>';
$tplname03 = 'home_headinclude';
$tplcode03 = '<meta http-equiv="MSThemeCompatible" content="Yes">
<style type="text/css">
BODY {
  SCROLLBAR-BASE-COLOR: {categorybackcolor};
  SCROLLBAR-ARROW-COLOR: {categoryfontcolor};
}
SELECT {
  FONT-FAMILY: Verdana,Arial,Helvetica,sans-serif;
  FONT-SIZE: 11px;
  COLOR: #000000;
  BACKGROUND-COLOR: #CFCFCF
}
TEXTAREA, .bginput {
  FONT-SIZE: 10px;
  FONT-FAMILY: Verdana,Arial,Helvetica,sans-serif;
  COLOR: #000000;
  BACKGROUND-COLOR: #CFCFCF
}
A:link, A:visited, A:active {
  COLOR: {linkcolor};
}
A:hover {
  COLOR: {hovercolor};
}
#cat A:link, #cat A:visited, #cat A:active {
  COLOR: {categoryfontcolor};
  TEXT-DECORATION: none;
}
#cat A:hover {
  COLOR: {categoryfontcolor};
  TEXT-DECORATION: underline;
}
#ltlink A:link, #ltlink A:visited, #ltlink A:active {
  COLOR: {linkcolor};
  TEXT-DECORATION: none;
}
#ltlink A:hover {
  COLOR: {hovercolor};
  TEXT-DECORATION: underline;
}
.thtcolor {
  COLOR: {tableheadtextcolor};
}
</style>
$headnewpm';
$tplname04 = 'home_header';
$tplcode04 = '<!-- logo and buttons -->
<center>
<table border="0" width="{tablewidth}" cellpadding="0" cellspacing="0">
<tr>
<td valign="top" align="left" background="{imagesfolder}/menu_background.gif"><a href="$bburl/index.php?s=$session[sessionhash]"><img src="{titleimage}" border="0" alt="$hometitle"></a></td>
<td valign="bottom" align="right" background="{imagesfolder}/menu_background.gif" nowrap>
<!-- toplinks -->
<a href="$bburl/usercp.php?s=$session[sessionhash]"><img src="{imagesfolder}/top_profile.gif" alt="Here you can view your subscribed threads, work with private messages and edit your profile and preferences" border="0"></a>
<a href="$bburl/register.php?s=$session[sessionhash]&action=signup"><img src="{imagesfolder}/top_register.gif" alt="Registration is free!" border="0"></a>
<a href="$bburl/calendar.php?s=$session[sessionhash]"><img src="{imagesfolder}/top_calendar.gif" alt="Calendar" border="0"></a>
<a href="$bburl/memberlist.php?s=$session[sessionhash]"><img src="{imagesfolder}/top_members.gif" alt="Find other members" border="0"></a>
<a href="$bburl/misc.php?s=$session[sessionhash]&action=faq"><img src="{imagesfolder}/top_faq.gif" alt="Frequently Asked Questions" border="0"></a>
<a href="$bburl/search.php?s=$session[sessionhash]"><img src="{imagesfolder}/top_search.gif" alt="Search" border="0"></a>
<a href="$bburl/index.php?s=$session[sessionhash]"><img src="{imagesfolder}/top_home.gif" alt="Home" border="0"></a>
<!-- <a href="$bburl/member.php?s=$session[sessionhash]&action=logout"><img src="{imagesfolder}/top_logout.gif" alt="Logout" border="0"></a> -->
&nbsp;
<!-- /toplinks -->
</td>
</tr>
</table>
<!-- /logo and buttons -->
<!-- content table -->
<table bgcolor="{pagebgcolor}" width="{tablewidth}" cellpadding="10" cellspacing="0" border="0">
<tr>
<td>';
$tplname05 = 'home_footer';
$tplcode05 = '</td>
</tr>
</table>
<!-- /content area table -->
</center>

<p align="center"><smallfont>
<!-- Do not remove this copyright notice or edit the links -->
Powered by: <a href="http://www.teckwizards.com" target="_blank" style="text-decoration: none">vbHome (lite) v$vbhlversion</a> and <a href="http://www.vbulletin.com" target="_blank" style="text-decoration: none">vBulletin v$versionnumber</a><br>
Copyright &copy;2000 - 2002, Jelsoft Enterprises Limited<br>
<!-- Do not remove this copyright notice or edit the links -->
$copyrighttext</smallfont></p>';
$tplname06 = 'home_threadbits';
$tplcode06 = '<tr bgcolor="{firstaltcolor}">
<td><smallfont><img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7"><a href="$bburl/showthread.php?s=$session[sessionhash]&threadid=$thread[threadid]"><b>$thread[title]</b></a><br>
$thread[date] <font color="{timecolor}">$thread[time]</font> <a href="$bburl/member.php?s=$session[sessionhash]&action=getinfo&userid=$thread[postuserid]">$thread[postusername]</a><br>
Views: $thread[views] &nbsp; Replies: $thread[replycount]</smallfont></td>
</tr>';
$tplname07 = 'home_welcomeguest';
$tplcode07 = '<normalfont>Hello, <b>visitor</b>.</normalfont>';
$tplname08 = 'home_welcomeuser';
$tplcode08 = '<normalfont>Hello, <b>$username</b>.<br></normalfont>';
$tplname09 = 'home_articlecomment';
$tplcode09 = '- last <a href="$bburl/newreply.php?s=$session[sessionhash]&action=newreply&threadid=$article[threadid]" title="Make a Comment">comment</a> by $article[lastposter]';
$tplname10 = 'home_articlenocomment';
$tplcode10 = '- make a <a href="$bburl/newreply.php?s=$session[sessionhash]&action=newreply&threadid=$article[threadid]">comment</a>';
$tplname11 = 'home_pollresult';
$tplcode11 = '$option[question]
<table border="0" cellpadding="0" cellspacing="0">
<tr onmouseover="this.title=\'$option[votes] votes ($option[percent]%)\'">
<td><img border="0" src="{imagesfolder}/polls/bar1-l.gif" width="3" height="10"></td>
<td><img border="0" src="{imagesfolder}/polls/bar1.gif" width="$option[barnumber]" height="10"></td>
<td><img border="0" src="{imagesfolder}/polls/bar1-r.gif" width="3" height="10"></td>
</tr>
</table>';
$tplname12 = 'home_polloption_multiple';
$tplcode12 = '<input type="checkbox" name="optionnumber[$option[number]]" value="yes">$option[question]<br>
';
$tplname13 = 'home_polloption';
$tplcode13 = '<input type="radio" name="optionnumber" value="$option[number]">$option[question]<br>
';
$tplname14 = 'home_pollcomment';
$tplcode14 = '<img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7"><a href="$bburl/showthread.php?s=$session[sessionhash]&threadid=$poll[threadid]">Discuss This Poll</a> &nbsp;';
$tplname15 = 'home_pollresults';
$tplcode15 = '<table cellpadding="{tableouterborderwidth}" cellspacing="0" border="0" bgcolor="{tablebordercolor}" {tableouterextra} width="100%">
<tr>
<td>
<table cellpadding="4" cellspacing="{tableinnerborderwidth}" border="0" {tableinnerextra} width="100%">
<tr id="cat">
<td bgcolor="{categorybackcolor}"><smallfont><font color="{categoryfontcolor}"><b>Web Site Poll</b></font></smallfont></td>
</tr>
<tr>
<td bgcolor="{secondaltcolor}"><smallfont><b>$poll[question]</b></smallfont></td>
</tr>
<tr>
<td bgcolor="{firstaltcolor}"><smallfont>$pollbits<br>
<img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7"><b>Total: $poll[numbervotes] vote$pluralvote</b><br>
<img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7"><font color="{timecolor}">$pollstatus</font></smallfont></td>
</tr>
<tr>
<td bgcolor="{secondaltcolor}"><smallfont>$pollcomments$poll[edit]</smallfont></td>
</tr>
</table>
</td>
</tr>
</table>
<br>';
$tplname16 = 'home_pollresults_closed';
$tplcode16 = 'This poll is closed.';
$tplname17 = 'home_pollresults_voted';
$tplcode17 = 'You have voted on this poll.';
$tplname18 = 'home_polloptions';
$tplcode18 = '<table cellpadding="{tableouterborderwidth}" cellspacing="0" border="0" bgcolor="{tablebordercolor}" {tableouterextra} width="100%">
<tr>
<td>
<table cellpadding="4" cellspacing="{tableinnerborderwidth}" border="0" {tableinnerextra} width="100%">
<tr id="cat">
<td bgcolor="{categorybackcolor}"><smallfont><font color="{categoryfontcolor}"><b>Web Site Poll</b></font></smallfont></td>
</tr>
<form action="$bburl/poll.php" method="post">
<input type="hidden" name="s" value="$session[dbsessionhash]">
<input type="hidden" name="action" value="pollvote">
<input type="hidden" name="pollid" value="$poll[pollid]">
<tr>
<td bgcolor="{secondaltcolor}"><smallfont><b>$poll[question]</b></smallfont></td>
</tr>
<tr>
<td bgcolor="{firstaltcolor}"><smallfont>$pollbits<br>
$pollcomments <img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7"><a href="$bburl/poll.php?s=$session[sessionhash]&action=showresults&pollid=$poll[pollid]">View Results</a></smallfont></td>
</tr>
<tr>
<td bgcolor="{secondaltcolor}"><smallfont><input type="submit" value="Vote" name="button" class="bginput">$poll[edit]</smallfont></td>
</tr>
</form>
</table>
</td>
</tr>
</table>
<br>';
$tplname19 = 'home_pollreview';
$tplcode19 = '<img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7"><a href="$bburl/showthread.php?s=$session[sessionhash]&threadid=$poll[threadid]">Poll Review</a> &nbsp;';
$tplname20 = 'home_pmloggedin';
$tplcode20 = '<tr id="cat">
<td bgcolor="{categorybackcolor}"><smallfont><a href="$bburl/private.php?s=$session[sessionhash]" title="Open Your Private Messages $inboxname"><b>Private Messages</b></a></smallfont></td>
</tr>
<tr>
<td bgcolor="{firstaltcolor}"><smallfont>You have $newpm[messages] new message$pluralpm since your last visit to our web site.</smallfont></td>
</tr>';
$tplname21 = 'home_userloggedin';
$tplcode21 = '<tr>
<td bgcolor="{firstaltcolor}"><smallfont>Since your last visit, there are:<br>
<img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7">$count[headlines] new article$pluralarticle released<br>
<img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7">$count[threads] new forum thread$pluralthread started</smallfont></td>
</tr>';
$tplname22 = 'home_userloggedout';
$tplcode22 = '<form action="$bburl/member.php" method="post" name="login">
<input type="hidden" name="s" value="$session[sessionhash]">
<input type="hidden" name="action" value="login">
<tr>
<td bgcolor="{firstaltcolor}"><smallfont>You have to <a href="$bburl/register.php?s=$session[sessionhash]">register</a> before you can post to our site.<br>
<br>
If you are a member, please enter<br>
your username and password:<br>
<img border="0" src="{imagesfolder}/clear.gif" width="1" height="4"><br>
<input type="text" name="username" size="16" class="bginput"> <input type="password" name="password" size="12" class="bginput"> $gobutton</smallfont></td>
<script> document.login.username.focus(); </script>
</tr>
</form>';
$tplname23 = 'home_link';
$tplcode23 = '<br>
<table cellpadding="{tableouterborderwidth}" cellspacing="0" border="0" bgcolor="{tablebordercolor}" {tableouterextra} width="100%">
<tr>
<td>
<table cellpadding="4" cellspacing="{tableinnerborderwidth}" border="0" {tableinnerextra} width="100%">
<tr id="cat">
<td bgcolor="{categorybackcolor}"><smallfont><font color="{categoryfontcolor}"><b>Friends and Affiliates</b></font></smallfont></td>
</tr>
<tr>
<td bgcolor="{firstaltcolor}"><smallfont><img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7"><a href="http://www.vbulletin.com" target="_blank">vBulletin.com Web Site</a><br>
<img border="0" src="{imagesfolder}/arrow.gif" width="7" height="7"><a href="http://www.vbulletin.org/forum/" target="_blank">vBulletin.org Forum</a></smallfont></td>
</tr>
</table>
</td>
</tr>
</table>';
$tplname24 = 'home_advertisement';
$tplcode24 = '<table cellpadding="{tableouterborderwidth}" cellspacing="0" border="0" bgcolor="{tablebordercolor}" {tableouterextra} width="100%">
<tr>
<td>
<table cellpadding="4" cellspacing="{tableinnerborderwidth}" border="0" {tableinnerextra} width="100%">
<tr id="cat">
<td bgcolor="{categorybackcolor}"><smallfont><font color="{categoryfontcolor}"><b>Visit Our Sponsor</b></font></smallfont></td>
</tr>
<tr>
<td bgcolor="{firstaltcolor}"><smallfont>Your banner here...<br>
<br></smallfont></td>
</tr>
</table>
</td>
</tr>
</table>
<br>';
$tplname25 = 'home_printarticle';
$tplcode25 = '{htmldoctype}
<html>
<head>
<title>$hometitle - $thread[title]</title>
<style>
body      { color: #000000; font-size: 13px; font-family: Verdana, Arial, Helvetica, Sans-Serif; background-color: #FFFFFF }
a:link    { color: #0066CC; text-decoration: none }
a:visited { color: #993399; text-decoration: none }
a:hover   { color: #FF3300; text-decoration: none }
a:active  { color: #993399; text-decoration: none }
</style>
</head>
<body topmargin="25" leftmargin="25">
<ul>
<li type="square"><b><font size="4">$hometitle</font></b></li>
<li type="circle">$homeurl</li>
</ul>
$articlebits
<smallfont>Powered by: vbHome (lite) v$vbhlversion and vBulletin v$versionnumber<br>
Copyright &copy; 2000 - 2002 Jelsoft Enterprises Limited<br>
$copyrighttext</smallfont>
</body>
</html>';
$tplname26 = 'home_printarticlebit';
$tplcode26 = '$article[icon] <b><a href="showthread.php?s=$session[sessionhash]&threadid=$threadid">$thread[title]</a></b>
<hr color="#000000" size="1" noshade>
Posted by $article[username] on $article[postdate] <font color="{timecolor}">$article[posttime]</font>
<hr color="#000000" size="1" noshade>
$article[message]
<hr color="#000000" size="1" noshade>';
$tplname27 = 'home_search';
$tplcode27 = '<tr id="cat">
<td bgcolor="{categorybackcolor}"><smallfont><font color="{categoryfontcolor}"><b>Web Site Search</b></font></smallfont></td>
</tr>
<form action="$bburl/search.php" method="post" name="search">
<input type="hidden" name="s" value="$session[sessionhash]">
<input type="hidden" name="forumchoice" value="-1">
<input type="hidden" name="searchin" value="subject">
<input type="hidden" name="searchdate" value="-1">
<input type="hidden" name="action" value="simplesearch">
<input type="hidden" name="booleanand" value="yes">
<tr>
<td bgcolor="{firstaltcolor}"><smallfont><input type="text" name="query" size="32" class="bginput"> $gobutton<br>
<a href="$bburl/search.php?s=$session[sessionhash]">Advanced Search</a></smallfont></td>
</tr>
</form>';
$tplname28 = 'home_articlelink';
$tplcode28 = '<smallfont> <nobr>... <a href="$bburl/showthread.php?s=$session[sessionhash]&threadid=$article[threadid]">read more</a></nobr></smallfont>';
$tplname29 = 'home_avatar';
$tplcode29 = '<a href="$bburl/member.php?s=$session[sessionhash]&action=getinfo&userid=$article[postuserid]"><img border="0" src="$avatarurl" align="right" hspace="10" vspace="10" alt="View $article[postusername]\'s Profile"></a>';

cpheader("<title>vbHome (lite) v".($version)." Install Script - by TECK</title>");

echo "<table cellpadding='2' cellspacing='0' border='0' align='center' width='90%' class='tblborder'>\n";
echo "<tr>\n";
echo "<td>\n";
echo "<table bgcolor='#524A5A' cellpadding='3' cellspacing='0' border='0' width='100%'>\n";
echo "<tr>\n";
echo "<td width='200'><a href='$threadlink' target='_blank'><img src='cp_logo.gif' width='160' height='49' border='0' alt='Click here for support...'></a></td>\n";
echo "<td><font size='2' color='#F7DE00'><b>vbHome (lite) v$version Install Script (by TECK)</b></font><br>\n";
echo "<font size='1' color='#F7DE00'>(For support click <a href='$threadlink' target='_blank'><font color='#F7DE00'>here</font></a>)</font></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "<br>\n";
echo "<table cellpadding='1' cellspacing='0' border='0' align='center' width='90%' class='tblborder'>\n";
echo "<tr>\n";
echo "<td>\n";
echo "<table cellpadding='4' cellspacing='0' border='0' width='100%' class='secondalt'>\n";


// INSTALL SCRIPT
// --------------------------------------------------------------------
if ($step == '') {
  $step = 1;
}

if ($step == 1) {
// install information
// -----------------------------------------
maketableheader("Introduction");
echo "<tr>\n";
echo "<td>Welcome to <b>vbHome (lite)</b> version $version!<br>\n";
echo "Running this script will install the home (vBulletin powered) page onto your server.<p>\n";
echo "vbHome (lite) will turn your home page, from a regular html based page to a vBulletin one. That means you can easily insert\n";
echo "all your vBulletin php code, options, templates, etc. into the index.php home page file, included in this package.<p>\n";
echo "<font color='#006699'>THE SCRIPT WILL COMPLETE THE FOLLOWING STEPS:</font>\n";
echo "<ul type='circle'>\n";
echo "<li><font size='1'>tables and fields database verification</font></li>\n";
echo "<li><font size='1'>modify needed settings onto the database</font></li>\n";
echo "<li><font size='1'>install needed templates</font></li>\n";
echo "<li><font size='1'>install vbHome (lite) options</font></li>\n";
echo "</ul>\n";
echo "IMPORTANT: Make sure you read first the <font color='#006699'><b>readmefirst.htm</b></font> file!<p></td>\n";
echo "<tr class='" . getrowbg() . "'>\n";
echo "<td><a href='$script?s=$session[sessionhash]&step=" . ($step + 1) . "'><b>Continue »</a> &nbsp; &nbsp; <font size='1'><a href='$script?s=$session[sessionhash]&step=10'>Uninstall?</b></a></font></td>\n";
echo "</tr>\n";

}  // end step 1

if ($step >= 2) {
  include("./config.php");
}

if ($step == 2) {
// check if vbHome (lite) is installed
// -----------------------------------------
maketableheader("Database verification");
echo "<tr>\n";
echo "<td>Checking to see if vbHome (lite) is already installed onto your server...<p>\n";

  if (dofields("articleid", "thread")) {
    echo "<font color='#FF0000'><b>ERROR:</b></font> <font color='#006699'>vbHome (lite)</font> is installed!<br>\n";
    echo "You already installed this script onto your server. Some of the fields this script attempts to create are present in your database.<p></td>\n";
    echo "<tr class='" . getrowbg()  ."'>\n";
    echo "<td><a href='$script?s=$session[sessionhash]&step=10'><b>Uninstall »</b></a></td>\n";
    echo "</tr>\n";
  } else {
    echo "Success! The database doesn't contain any vbHome (lite) fields.<p></td>\n";
    echo "</tr>\n";
    echo "<tr class='" . getrowbg() . "'>\n";
    echo "<td><a href='$script?s=$session[sessionhash]&step=" . ($step + 1) . "'><b>Continue »</b></a></td>\n";
    echo "</tr>\n";
  }

}  // end step 2

if ($step == 3) {
// alter tables and install templates
// -----------------------------------------
maketableheader("Database table modifications and templates set installation");
echo "<tr>\n";
echo "<td>\n";

  $DB_site->reporterror = 1;

  $query[] = "ALTER TABLE thread ADD articleid int(10) unsigned DEFAULT '0' NOT NULL AFTER pollid";
  $explain[] = "Adding to table <i>thread</i> the <font color='#006699'>articleid</font> field... Done.";

  $query[] = "ALTER TABLE thread ADD INDEX (articleid)";
  $explain[] = "Indexing the <font color='#006699'>articleid</font> field... Done.";

  doqueries();

  if ($DB_site->errno != 0) {
    echo "<p><font color='#FF0000'><b>ERROR:</b></font> The script reported errors during the setup.</p>\n";
    echo "Error number: " . $DB_site->errno . "<br>\n";
    echo "Error description: " . $DB_site->errdesc . "</p><p>";
  } else {
    echo "<p>Table modifications completed successfully.</p><p>\n";
  }

  dotemplate($tplname01, $tplcode01);
  dotemplate($tplname02, $tplcode02);
  dotemplate($tplname03, $tplcode03);
  dotemplate($tplname04, $tplcode04);
  dotemplate($tplname05, $tplcode05);
  dotemplate($tplname06, $tplcode06);
  dotemplate($tplname07, $tplcode07);
  dotemplate($tplname08, $tplcode08);
  dotemplate($tplname09, $tplcode09);
  dotemplate($tplname10, $tplcode10);
  dotemplate($tplname11, $tplcode11);
  dotemplate($tplname12, $tplcode12);
  dotemplate($tplname13, $tplcode13);
  dotemplate($tplname14, $tplcode14);
  dotemplate($tplname15, $tplcode15);
  dotemplate($tplname16, $tplcode16);
  dotemplate($tplname17, $tplcode17);
  dotemplate($tplname18, $tplcode18);
  dotemplate($tplname19, $tplcode19);
  dotemplate($tplname20, $tplcode20);
  dotemplate($tplname21, $tplcode21);
  dotemplate($tplname22, $tplcode22);
  dotemplate($tplname23, $tplcode23);
  dotemplate($tplname24, $tplcode24);
  dotemplate($tplname25, $tplcode25);
  dotemplate($tplname26, $tplcode26);
  dotemplate($tplname27, $tplcode27);
  dotemplate($tplname28, $tplcode28);
  dotemplate($tplname29, $tplcode29);

  if ($DB_site->errno != 0) {
    echo "<p><font color='#FF0000'><b>ERROR:</b></font> The script reported errors while modifying the database. Continue only if you are sure that they are not serious.</p>\n";
    echo "Error number: " . $DB_site->errno . "<br>\n";
    echo "Error description: " . $DB_site->errdesc . "</p><p></td>";
  } else {
    echo "<p>Templates installed successfully.</p><p></td>\n";
  }

echo "<tr class='" . getrowbg() . "'>\n";
echo "<td><a href='$script?s=$session[sessionhash]&step=" . ($step + 1) . "'><b>Continue »</b></a></td>\n";
echo "</tr>\n";

}  // end step 3


if ($step == 4) {
// set the options
// -----------------------------------------
maketableheader("Setting the vbHome (lite) options");
echo "<tr>\n";
echo "<td>\n";

  $query[] = "INSERT INTO settinggroup VALUES (99,'vbHome Page','99')";
  $explain[] = "Adding new options group... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Articles Forum','articleforum','2','The forum ID assigned to your articles home page.','','1')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Journalists Usergroup','articlegroup','8','The usergroup ID assigned to your journalists.','','2')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Number of Articles displayed','articlemax','10','The number of articles listed on your home page. Set this to 0 to disable it.','','3')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Maximum Characters per article','articlemaxchars','5000','The maximum number of characters you want to allow per article. Set this to 0 to disable it.','','4')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Html Code in article?','articlehtml','0','Enable or disable the Html Code option. You must enable it ONLY if the Articles Forum have that option On.','yesno','5')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Icon in article?','articleicon','0','Enable or disable the Icon option.','yesno','6')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Avatar in article?','articleavatar','0','Enable or disable the Avatar option.','yesno','7')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Smilies in article?','articlesmilies','0','Enable or disable the Smilies option.','yesno','8')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Maximum Number of latest threads','threadmax','10','The maximum number of your latest forum threads. Set this to 0 to disable it.','','9')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Latest Threads title length','threadmaxchars','32','The maximum number of characters for your latest threads title. Set this to 0 to disable it.','','10')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Web Site Search?','activesearch','0','Enable or disable the Web Site Search option.','yesno','11')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Private Messages?','activepm','0','Enable or disable the Private Messages option.','yesno','12')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Advertisements?','activeadvertise','0','Enable or disable the Advertisement option.','yesno','13')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Affiliate Links?','activelinks','0','Enable or disable the Affiliate Links option.','yesno','14')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Poll?','activepoll','0','Enable or disable the Poll option.','yesno','15')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Enable Smilies in poll question?','activepollsmilies','0','Enable or disable the Smilies in poll question.','yesno','16')";
  $explain[] = "Adding option... Done.";

  $query[] = "INSERT INTO setting VALUES (NULL,99,'Allowed Users to post a poll','activepollusers','1,3','If you would like to allow more then one user to post polls then enter the User ID\'s separated by commas.','','17')";
  $explain[] = "Adding option... Done.";

  doqueries();

  if ($DB_site->errno != 0) {
    echo "<p><font color='#FF0000'><b>ERROR:</b></font> The script reported errors while setting the options.</p>\n";
    echo "Error number: " . $DB_site->errno . "<br>\n";
    echo "Error description: " . $DB_site->errdesc . "</p><p></td>";
  } else {
    echo "<p>Options set successfully.</p><p></td>\n";
  }

echo "<tr>\n";
echo "<td colspan='2'>Setup completed!<br>\n";
echo "The file the you need to delete: <font color='#006699'>$script</font><p></td>\n";
echo "<tr class='" . getrowbg() . "'>\n";
echo "<td><a href='index.php?s=$session[sessionhash]'><b>Control Panel »</b></a></td>\n";
echo "</tr>\n";

}  // end step 4


if ($step == 10) {
// uninstall script
// -----------------------------------------
maketableheader("Uninstall vbHome (lite)");
echo "<tr>\n";
echo "<td>We are sorry you did not like <b>vbHome (lite)</b> version $version.<br>\n";
echo "Running this uninstall script will remove the table modifications and templates from your server.<p>\n";
echo "<b>YOU MUST <font color='#FF3300'>REVERT TO ORIGINAL</font> YOUR vbHome (lite) TEMPLATES BEFORE YOU PROCEED!!!</b><p>\n";
echo "<font color='#006699'>THE UNINSTALL SCRIPT WILL COMPLETE THE FOLLOWING STEPS:</font>\n";
echo "<ul type='circle'>\n";
echo "<li><font size='1'>remove vBulletin table alterations</font></li>\n";
echo "<li><font size='1'>remove templates</font></li>\n";
echo "</ul>\n";
echo "IMPORTANT: Make sure YOU ALSO REMOVE the <font color='#006699'><b>newthread.php</b></font> modifications!<p></td>\n";
echo "<tr class='" . getrowbg() . "'>\n";
echo "<td><a href='$script?s=$session[sessionhash]&step=" . ($step + 1) . "'><b>Continue »</b></a></td>\n";
echo "</tr>\n";

}  // end step 10

if ($step == 11) {
// drop fields and remove templates
// -----------------------------------------
maketableheader("Droping the fields and removing script elements");
echo "<tr>\n";
echo "<td>\n";

  if (dofields("articleid", "thread")) {

    $query[] = "ALTER TABLE thread DROP articleid";
    $explain[] = "Removing from table <i>thread</i> the <font color='#FF3300'>articleid</font> field... Done.";

    $query[] = "ALTER TABLE thread DROP INDEX articleid";
    $explain[] = "Removing the <b>thread</b> table the <font color='#FF3300'>articleid</font> index... Done.";

    $query[] = "DELETE FROM settinggroup WHERE settinggroupid=99";
    $explain[] = "Removing options group... Done.";

    $query[] = "DELETE FROM setting WHERE settinggroupid=99";
    $explain[] = "Removing options... Done.";

    doqueries();

    if ($DB_site->errno != 0) {
      echo "<p><font color='#FF0000'><b>ERROR:</b></font> The script reported errors during the uninstall.</p>\n";
      echo "Error number: " . $DB_site->errno . "<br>\n";
      echo "Error description: " . $DB_site->errdesc . "</p><p>";
    } else {
      echo "<p>Table alterations completed successfully.</p><p>\n";
    }

    killtemplate($tplname01);
    killtemplate($tplname02);
    killtemplate($tplname03);
    killtemplate($tplname04);
    killtemplate($tplname05);
    killtemplate($tplname06);
    killtemplate($tplname07);
    killtemplate($tplname08);
    killtemplate($tplname09);
    killtemplate($tplname10);
    killtemplate($tplname11);
    killtemplate($tplname12);
    killtemplate($tplname13);
    killtemplate($tplname14);
    killtemplate($tplname15);
    killtemplate($tplname16);
    killtemplate($tplname17);
    killtemplate($tplname18);
    killtemplate($tplname19);
    killtemplate($tplname20);
    killtemplate($tplname21);
    killtemplate($tplname22);
    killtemplate($tplname23);
    killtemplate($tplname24);
    killtemplate($tplname25);
    killtemplate($tplname26);
    killtemplate($tplname27);
    killtemplate($tplname28);
    killtemplate($tplname29);

    if ($DB_site->errno != 0) {
      echo "<p><font color='#FF0000'><b>ERROR:</b></font> The script reported errors during the uninstall.</p>\n";
      echo "Error number: " . $DB_site->errno . "<br>\n";
      echo "Error description: " . $DB_site->errdesc . "</p><p></td>";
    } else {
      echo "<p>If reverted to original, templates removed successfully.</p><p></td>\n";
    }

  } else { // perform only the templates removal, just in case you forgot to revert them

    killtemplate($tplname01);
    killtemplate($tplname02);
    killtemplate($tplname03);
    killtemplate($tplname04);
    killtemplate($tplname05);
    killtemplate($tplname06);
    killtemplate($tplname07);
    killtemplate($tplname08);
    killtemplate($tplname09);
    killtemplate($tplname10);
    killtemplate($tplname11);
    killtemplate($tplname12);
    killtemplate($tplname13);
    killtemplate($tplname14);
    killtemplate($tplname15);
    killtemplate($tplname16);
    killtemplate($tplname17);
    killtemplate($tplname18);
    killtemplate($tplname19);
    killtemplate($tplname20);
    killtemplate($tplname21);
    killtemplate($tplname22);
    killtemplate($tplname23);
    killtemplate($tplname24);
    killtemplate($tplname25);
    killtemplate($tplname26);
    killtemplate($tplname27);
    killtemplate($tplname28);
    killtemplate($tplname29);

    if ($DB_site->errno != 0) {
      echo "<p><font color='#FF0000'><b>ERROR:</b></font> The script reported errors during the uninstall.</p>\n";
      echo "Error number: " . $DB_site->errno . "<br>\n";
      echo "Error description: " . $DB_site->errdesc . "</p><p></td>";
    } else {
      echo "<p>If reverted to original, templates removed successfully.</p><p></td>\n";
    }
  }

echo "<tr>\n";
echo "<td colspan='2'>Uninstall completed!<br>\n";
echo "The file the you need to delete: <font color='#006699'>$script</font><p></td>\n";
echo "<tr class='".getrowbg()."'>\n";
echo "<td><a href='index.php?s=$session[sessionhash]'><b>Control Panel »</b></a></td>\n";
echo "</tr>\n";

}  // end step 11

echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

cpfooter();

?>