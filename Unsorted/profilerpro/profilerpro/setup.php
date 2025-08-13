<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//  Program Name         : RedTempest Profiler Pro                           //
//  Release Version      : 1.0.0                                             //
//  Program Author       : Eric Fredin ( RedTempest )                        //
//  Supplied by          : Scoons [WTN]                                      //
//  Nullified by         : CyKuH [WTN]                                       //
//  Distribution         : via WebForum, ForumRU and associated file dumps   //
//                                                                           //
//            Copyright 2000 RedTempest. All Rights Reserved.                //
///////////////////////////////////////////////////////////////////////////////
// SETUP Script variables

$sql = array(
    "user" => "root",    // Username
    "pass" => "",     // Password
    "host" => "localhost",     // Host
    "db"   => "rt"     // Database
);

// End variables

// Connect to the MySQL server
$link = mysql_connect($sql[host],$sql[user],$sql[pass]) or exit("Unable to connect to the MySQL database");
mysql_select_db($sql[db]) or exit("Unable to select the MySQL database $mysql[db]");

if(!$action){
    echo "

        <form method=post action='?action=step'>
        <center><table border=0 cellpadding=2 cellspacing=1 width=80%>
<table bgcolor=336699>
          <tr>
            <td colspan=2 width=100% bgcolor=336699 align=center><font face=tahoma size=2 color=white>Profiler Pro Setup</font></td>
          </tr>

          <tr>
            <td colspan=2 width=100% bgcolor=eeeeee align=center><font face=tahoma size=2 color=black>Nullified by WTN Team 2000-2002</font></td>
          </tr>
          <tr>
            <td width=25% align=right><font face=tahoma size=2>PHP Directory</font></td>
            <td width=75%><input type=text name='inp[dir_php]' size=60 value='$DOCUMENT_ROOT'></td>
          </tr>
          <tr>
            <td width=25% align=right><font face=tahoma size=2>PHP URL</font></td>
            <td width=75%><input type=text name='inp[url_php]' size=60 value='http://'></td>
          </tr>
          <tr>
            <td width=25% align=right><font face=tahoma size=2>Username</font></td>
            <td width=75%><input type=text name='inp[alias]' size=20 value=''></td>
          </tr>
          <tr>
            <td width=25% align=right><font face=tahoma size=2>Password</font></td>
            <td width=75%><input type=password name='inp[pass]' size=20 value=''></td>
          </tr>
          <tr>
            <td width=25% align=right><font face=tahoma size=2>Confirm</font></td>
            <td width=75%><input type=password name='inp[pass2]' size=20 value=''></td>
          </tr>
          <tr>
            <td width=25%>&nbsp;</td>
            <td width=75%><input type=submit value='Run Setup'></td>
          </tr>
        </table></center></form>

    ";
}

if($action == 'step'){
    if($inp[dir_php] == '' || $inp[url_php] == '' || $inp[alias] == '' || $inp[pass] == ''){
        echo "You did not fill out all the form fields.";
        exit;
    }
    if($inp[pass] != $inp[pass2]){
        echo "Your passwords did not match.";
        exit;
    }
    if(!is_dir($inp[dir_php])){
        echo "The path you entered for your PHP directory does not appear to be valid.";
        exit;
    }
    
    $inp[pass] = md5($inp[pass]);
    
    setcookie("inp_user[alias]",$inp[alias],time+300000000,"/");
    setcookie("inp_user[pass]",$inp[pass],time+3600,"/");

    mysql_query("CREATE TABLE profiler_apps (id tinyint(4) NOT NULL auto_increment, alias varchar(20) NOT NULL default '', pass varchar(32) NOT NULL default '', email varchar(50) NOT NULL default '', KEY id (id))");
    mysql_query("CREATE TABLE profiler_cal (id int(11) NOT NULL auto_increment, dday tinyint(4) NOT NULL default '0', dmonth tinyint(4) NOT NULL default '0', dyear int(11) NOT NULL default '0', event text NOT NULL, subject varchar(15) NOT NULL default '0', KEY id (id))");
    mysql_query("CREATE TABLE profiler_com (id int(11) NOT NULL auto_increment, postid int(11) NOT NULL default '0', name varchar(50) NOT NULL default '', email varchar(50) NOT NULL default '', date varchar(100) NOT NULL default '', comments text NOT NULL, KEY id (id))");
    mysql_query("CREATE TABLE profiler_data (name varchar(18) NOT NULL default '', value text NOT NULL, type varchar(18) NOT NULL default '')");
    mysql_query("CREATE TABLE profiler_mssg (id int(11) NOT NULL auto_increment, toid int(11) NOT NULL default '0', fromid int(11) NOT NULL default '0', fromalias varchar(18) NOT NULL default '', subject varchar(50) NOT NULL default '', message text NOT NULL, stamp timestamp(14) NOT NULL, KEY id (id))");
    mysql_query("CREATE TABLE profiler_news (id int(11) NOT NULL auto_increment, subject varchar(100) NOT NULL default '', date varchar(100) NOT NULL default '', news text NOT NULL, author varchar(50) NOT NULL default '', email varchar(100) NOT NULL default '', comment tinyint(4) NOT NULL default '0', KEY id (id))");
    mysql_query("CREATE TABLE profiler_users (id tinyint(4) NOT NULL auto_increment, alias varchar(20) NOT NULL default '', pass varchar(32) NOT NULL default '', email varchar(50) NOT NULL default '', status tinyint(4) NOT NULL default '1', login timestamp(14) NOT NULL, rank varchar(18) NOT NULL default '', faction varchar(18) NOT NULL default '', image varchar(4) NOT NULL default '0', KEY id (id))");

    mysql_query("insert into profiler_data values ('dir_php','$inp[dir_php]','var')");
    mysql_query("insert into profiler_data values ('url_php','$inp[url_php]','var')");
    mysql_query("insert into profiler_users (alias,pass,status) values ('$inp[alias]', '$inp[pass]', '3')");
    mysql_query("insert into profiler_data values ('news_show','8','pref')");
    mysql_query("insert into profiler_data values('fontface','tahoma','pref')");
    mysql_query("insert into profiler_data values('fontsize1','2','pref')");
    mysql_query("insert into profiler_data values('fontsize2','2','pref')");
    mysql_query("insert into profiler_data values('fontcolor1','black','pref')");
    mysql_query("insert into profiler_data values('fontcolor2','white','pref')");
    mysql_query("insert into profiler_data values('bgcolor1','336699','pref')");
    mysql_query("insert into profiler_data values('bgcolor2','003366','pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('info_image', '1', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('info_image_height', '50', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('info_image_width', '50', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('info_image_dir', 'images', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('news_date', 'l F d, Y', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('news_layout', '<a name=post<id>></a><center><table border=0 cellpadding=0 cellspacing=0 width=90%><tr><td width=100% colspan=2><font face=tahoma size=2><a href=mailto:<email>><author></a> - <b><subject></b> - <date></td></tr><tr><td width=100% colspan=100%><font face=tahoma size=2><news></td></tr><tr><td width=75%> </td><td width=25% align=right><font face=tahoma size=2><comments></td></tr></table></center><br>', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('news_show', '8', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('main_layout', 'Welcome to the site<br><b>Current News:</b><br><viewnews>', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('comment_layout', '<name>-<email>-<date><br><comments><br><br>', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('rank_layout', '<table border=0 cellpadding=1 cellspacing=1 width=100%><tr><td width=10%><id></td><td width=15%><alias></td><td width=15%><email></td><td width=15%><rank></td><td width=15%><faction></td><td width=15%><Last Name></td><td width=15%><First Name></td></tr></table>', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('code', '1', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('code_text', 'Thou shalt not steel or your hand will be sacrificed to the devil!', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('profile_layout', 'Alias: <alias><br>Name: <First Name> <Last Name><br>ICQ: <ICQ><br>', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('headline_layout', '<a href=\'profiler.php#post<id>\'><subject></a><br>', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('rank_option', '1', 'pref')");
    
    mysql_query("INSERT INTO profiler_data VALUES ('user_info', '', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('user_info_name', '', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('ranks', '', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('ranks_name', '', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('factions', '', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('factions_name', '', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('clantag', '', 'pref')");
    mysql_query("INSERT INTO profiler_data VALUES ('required', '', 'pref')");
    
    echo "
        <center><table border=0 cellpadding=2 cellspacing=1 width=80%>
<table bgcolor=336699>
          <tr>
            <td colspan=2 width=100% bgcolor=336699 align=center><font face=tahoma size=2 color=white>Profiler Pro Setup</font></td>
          </tr>
          <tr>
            <td colspan=2 width=100% bgcolor=eeeeee align=center><font face=tahoma size=2 color=black>Nullified by WTN Team 2000-2002</font></td>
          </tr>
          <tr>
            <td width=100% colspan=2><font face=tahoma size=2>
            Profiler has been correctly set up.<br><br>
            It is recommended that you edit the <a href='profiler.php?action=set'>script settings</a> before you procede.<br>
            You should also delete this script (setup.php) for security purposes. It will no longer be needed.
            </td>
          </tr>
        </table></center>
    ";
}

?>
