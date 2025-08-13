<?php
#$Id: forums.inc.php,v 1.46 2003/10/20 14:59:48 ryan Exp $
require_once("includes/functions.inc.php");
require_once("includes/text.inc.php");

//stats


class forumModule {

    function forumModule($textArray, $templates){


        $this->action = $_GET['action'];
        $this->core = new core();
        $this->ssl = $this->core->dbCall("secure");
        if ($_SERVER["SERVER_PORT"] != 443 && $this->ssl == 1){
            $head = "Location: https://" . $_SERVER["HTTP_HOST"] .  $_SERVER["REQUEST_URI"];
            header($head);
        }
        $this->security = new security();
        $this->core->textArray = $textArray;
        $this->core->templates = $templates;

        $active = $this->core->dbCall("forums");
        if ($active != 1){ $this->body = $textArray['Forums are currently offline']; }
        else {

            $this->textArray = $textArray;
            $this->templates = $templates;
            list($allow_html, $allow_xcode, $allow_smiles, $censor_words, $image_tags) = mysql_fetch_row(mysql_query("select allow_html, allow_xcode, allow_smiles, censor_words, image_tags from forums_config")) or die(mysql_error());
            $this->text = new textFunctions();
            $this->text->html = $allow_html;
            $this->text->xcode = $allow_xcode;
            $this->text->smiles = $allow_smiles;
            $this->text->words = $censor_words;
            $this->text->images = $image_tags;

            if (isset($_GET['action'])){

                $forums = new forumPost();
                $forums->templates = $this->templates;
                $forums->textArray = $this->textArray;
                $forums->core = $this->core;
                $forums->core->templates = $this->templates;
                $forums->core->textArray = $this->textArray;
                $forums->text = $this->text;
                $forums->security = $this->security;
                switch($_GET['action']){
                    case post:
                        $forums->createPost();
                        $this->body = $forums->body;
                        break;
                    case poll:
                        $forums->createPoll();
                        $this->body = $forums->body;
                        break;
                    case preview:
                        $forums->previewPost();
                        $this->body = $forums->body;
                        break;
                    case edit:
                        $forums->editPost();
                        $this->body = $forums->body;
                        break;
                    case vote:
                        $forums->forumVote();
                        $this->body = $forums->body;
                        break;
                    case admin:
                        $adminForums = new adminForums($this->security);
                        $this->body = $adminForums->body;
                        break;
                    case search:
                        $this->searchForums();
                        break;
                    case report:
                        $this->reportPost();
                        break;
                    case xcode:
                        $this->explainXCode();
                        break;
                    case monitor:
                        $this->monitorForum();
                        break;
                    case monitort:
                        $this->monitorTopic();
                        break;
                    case ignore:
                        $this->ignoreUser();
                        break;
                    case pm:
                        $this->pmUser();
                        break;
                    case dismiss:
                        $this->dismissAlert();
                        break;

                }
            }
            else {
                $forums = new viewForumClass();
                $forums->templates = $this->templates;
                $forums->textArray = $this->textArray;
                $forums->core = new core();
                $forums->core->templates = $this->templates;
                $forums->core->textArray = $this->textArray;
                $forums->text = $this->text;
                $forums->security = $this->security;

                if (isset($_GET['post_id'])){
                    $forums->viewPost();
                    $this->body = $forums->body;

                }
                else if (isset($_GET['topic_id'])){
                    $forums->viewTopic();
                    $this->body = $forums->body;

                }
                else if (isset($_GET['forum_id'])){
                    $forums->viewForum();
                    $this->body = $forums->body;

                }
                else if (isset($_GET['cat_id'])){
                    $forums->viewCat();
                    $this->body = $forums->body;

                }
                else {
                    $forums->viewAllForums();
                    $this->body = $forums->body;

                }
            }
        }

    }
    function searchForums(){
        if (isset($_GET['search_id'])){
            $forums = new viewForumClass();
            $forums->templates = $this->templates;
            $forums->textArray = $this->textArray;
            $forums->core = new core();
            $forums->text = $this->text;
            $forums->security = $this->security;
            $forums->searchResults();
            $this->body = $forums->body;
        }
        else {

            $this->createProgressPage();
            $version = mysql_get_server_info();
            $version = substr($version, 0, 1);
            $hold = array();

            if ($version == "4"){
                $result = mysql_query("select p.forum_id, p.topic_id, p2.title, p2.user_id, t.posts, t.reads, t.last_post_id, p3.date, p3.user_id from forums_posts p, forums_posts p2, forums_posts p3, forums_topics t where match(p.title,p.post) against('$_POST[keywords]' in boolean mode) and p.topic_id = t.topic_id and p2.post_id = t.post_id and p3.post_id = t.last_post_id") or die(mysql_error());
            }
            else {
                $result = mysql_query("select p.forum_id, p.topic_id, p2.title, p2.user_id, t.posts, t.reads, t.last_post_id, p3.date, p3.user_id from forums_posts p, forums_posts p2, forums_posts p3, forums_topics t where match(p.title,p.post) against('$_POST[keywords]') and p.topic_id = t.topic_id and p2.post_id = t.post_id and p3.post_id = t.last_post_id");
            }
            $date = $this->core->getTime(time(), 1);
            $rows = mysql_num_rows($result);
            mysql_query("insert into forums_search_control (date, rows) values ('$date', '$rows')");
            $search_id = mysql_insert_id();
            $x=0;
            while(list($forum_id, $topic_id, $title, $user_id, $posts, $reads, $last_post_id, $last_post_date, $last_post_user_id) = mysql_fetch_row($result)){
                $secure = $this->security->forumSecure($forum_id, 0, 0);
                if ($secure == 1 && !in_array($topic_id, $hold)){
                    $title = addslashes($title);
                    mysql_query("insert into forums_search values ('$search_id', '$forum_id', '$topic_id', '$title', '$user_id', '$posts', '$reads', '$last_post_id', '$last_post_date', '$last_post_user_id')") or die(mysql_error());
                    $x++;
                    $hold[] = $topic_id;
                }
            }
            mysql_query("update forums_search_control set rows = '$x' where search_id = '$search_id'");
            print "<script language=javascript>window.location = 'forums.php?action=search&search_id=$search_id';</script>";
        }
    }

    function createProgressPage(){

        $text .= "<html>";
        $text .= "<head>";
        $text .= "<title>Working</title>";
        $text .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$this->templates/style.css\"></link>";
        $text .= "</head>";
        $text .= "<body marginwidth=0 marginheight=0 topmargin=0 leftmargin=0>";
        $text .= "<br><br><br><br><br><br><br>";
        $text .= "<table border=0 width=400 align=center cellpadding=0 cellspacing=1 class=outline>";
        $text .= "<tr><td class=title align=center>Working</td></tr>";
        $text .= "<tr><td class=main><table border=0 width=100% align=center cellpadding=2 cellspacing=0>";
        $text .= "<tr><td class=main align=center><b>Working.  Please wait.</b></td></tr>";
        $text .= "<tr><td class=main align=center><img src=admin/images/searching.gif alt='Working' border=0></td></tr>";
        $text .= "<tr><td class=main align=center>This page will automatically forward when complete.</td></tr></table>";
        $text .= "</td></tr></table></td></tr></table>";
        $text .= "</body>";
        $text .= "</html>";
        print($text);
        flush();
    }

    function reportPost(){

        $siteName = $this->core->dbCall("siteName");
        $email = $this->core->dbCall("webmasterEmail");
        $siteURL = $this->core->dbCall("siteURL");
        $headers = "From: $siteName " . $this->textArray['Forums'] . " <$email> \r\n";
        $headers .= "X-Sender: <$email>\r\n";
        $headers .= "X-Mailer: PHP\r\n";
        $headers .= "X-Priority: 1\r\n";
        $headers .= "Reply-To: $email\r\n";

        $post_id = $_GET['post_id'];
        list($forum_id, $topic_id) = mysql_fetch_row(mysql_query("select forum_id, topic_id from forums_posts where post_id = '$post_id'"));
        $subject = $this->textArray['Forum Post Report'];
        $message = $this->textArray['A forum post has been reported for moderation'] . ".\r\n\r\n";
        $message .= "$siteURL" . "/forums.php?forum_id=$forum_id&topic_id=$topic_id&post_id=$post_id#$post_id \r\n\r\n";
        $message .= $this->textArray['Do not reply to this message'];

        $to = array();
        $result = mysql_query("select u.email from users u, forums_mod f where f.forum_id = '$forum_id' and u.user_id = f.user_id");
        while(list($email) = mysql_fetch_row($result)){
            if (!in_array($email, $to)){ $to[] = $email; }
        }

        $result = mysql_query("select email from users where admin = '1'");
        while(list($email) = mysql_fetch_row($result)){
            if (!in_array($email, $to)){ $to[] = $email; }
        }

        foreach($to as $t){
            mail($t, $subject, $message, $headers);
        }
        header("HTTP/1.0 204 No Content");
    }

    function explainXCode(){
        $langFile = "lang/" . $this->core->dbCall("lang") . "_xcode.inc.php";
        require($langFile);
        $elements = count($xTitle);

        for($i=0; $i<$elements; $i++){
            $text .= "<table border=0 cellpadding=2 cellspacing=1 width=95% align=center class=outline>";
            $text .= "<tr><td class=medtitle>$xTitle[$i]</td></tr>";
            $text .= "<tr><td class=main>";
            $text .= $bodyText[$i];
            $text .= "</td></tr></table><br><br>";
        }
        $this->body = $text;
    }

    function monitorForum(){
        if (isset($_COOKIE[PXL])){
            $forum_id = $_GET['forum_id'];
            list($count) = mysql_fetch_row(mysql_query("select count(forum_notify_id) from forums_notify where user_id = '$_COOKIE[PXL]' and forum_id = '$forum_id'"));
            if ($count == 0){ mysql_query("insert into forums_notify values ('', '$_COOKIE[PXL]', '$forum_id', '')"); }
            header("HTTP/1.0 204 No Content");
        }
        else { header("Location: error.php?error=401"); }
    }

    function monitorTopic(){
        if (isset($_COOKIE[PXL])){
            $topic_id = $_GET['topic_id'];
            $result = mysql_query("select count(forum_notify_id) from forums_notify where user_id = '$_COOKIE[PXL]' and topic_id = '$topic_id'") or die(mysql_error());
            list($count) = mysql_fetch_row($result);
            if ($count == 0){ mysql_query("insert into forums_notify values ('', '$_COOKIE[PXL]', '', '$topic_id')") or die(mysql_error()); }
            header("HTTP/1.0 204 No Content");
        }
        else { header("Location: error.php?error=401"); }
    }

    function ignoreUser(){
        $url = "forums.php?forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&post_id=$_GET[post_id]#$_GET[post_id]";

        if (!isset($_COOKIE[PXL]) || ($_COOKIE[PXL] == $_GET[ignore_id])){ header("Location: $url"); }
        mysql_query("insert into forums_ignore values ('$_COOKIE[PXL]', '$_GET[ignore_id]')");
        mysql_query("update forums_pm set deleted = '1' where user_id = '$_COOKIE[PXL]' and from_user_id = '$_GET[ignore_id]'");
        header("Location: $url");
    }

    function dismissAlert(){
        setcookie("DISMISS", 1, 0, '', '', $this->ssl);
        header("HTTP/1.0 204 No Content");
    }

    function pmUser(){
        $allow = $this->core->forumCall("allow_pm");
        if ($allow != 1){ header("Location: error.php?error=403"); }
        if ($_GET['subaction'] == "inbox"){
            //setcookie("DISMISS", '', time() - 9999, '', $_SERVER["HTTP_HOST"]);
            if (isset($_GET[pm_id])){
                $dateFormat = $this->core->dbCall("dateFormat");
                mysql_query("update forums_pm set view = '1' where pm_id = '$_GET[pm_id]'");
                list($user_id, $username, $subject, $body, $date, $check) = mysql_fetch_row(mysql_query("select u.user_id, u.username, p.subject, p.body, p.date, p.user_id from forums_pm p, users u where u.user_id = p.from_user_id and p.pm_id = '$_GET[pm_id]'"));
                $body = $this->text->convertText($body, 2);
                $subject = $this->text->convertText($subject, 2);
                $date = $this->core->getTime($date);
                $date = date($dateFormat . " H:i", $date);
                if (!isset($_COOKIE[PXL])){ $text = $this->core->login(0, "forums.php?action=pm&subaction=inbox&pm_id=$_GET[pm_id]"); }
                else if ($check != $_COOKIE[PXL]){ header("Location: error.php?error=403"); }
                else {
                    $i=1;
                    $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
                    require("includes/formscript.inc.php");
                    $text .= "<tr><td class=title align=center>" . $this->textArray['PM'] . " " . $this->textArray['Detail'] . "</td></tr>";
                    $text .= "<tr><td width=100% class=main><table border=0 cellpadding=4 cellspacing=1 align=center width=100%>";
                    $text .= "<tr><td class=mainbold width=150>" . $this->textArray['Subject'] . ": </td><td class=main>$subject</td></tr>";
                    $text .= "<tr><td class=mainbold-alt width=150>" . $this->textArray['Date'] . ": </td><td class=main-alt>$date</td></tr>";
                    $text .= "<tr><td class=mainbold width=150>" . $this->textArray['From'] . ": </td><td class=main>";
                    $text .= "$username";
                    if ($_COOKIE['PXL'] != $user_id){
                        $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('email" . $i . "', '', 'images/email_user_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('users.php?action=email&user_id=$user_id')><img src=images/email_user.gif name=email" . $i . " alt='" . $this->textArray['Email'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>";
                        $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('pm" . $i . "', '', 'images/pm_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=pm&user_id=$user_id')><img src=images/pm.gif name=pm" . $i . " alt='" . $this->textArray['PM'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>";
                        $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('profile" . $i . "', '', 'images/user_profile_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('users.php?action=view&user_id=$user_id')><img src=images/user_profile.gif name=profile" . $i . " alt='" . $this->textArray['User Profile'] . "' border=0></span>";
                        $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('ignore" . $i . "', '', 'images/ignore_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=confirmIgnore('forums.php?action=ignore&ignore_id=$user_id&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&post_id=$post_id#$post_id')><img src=images/ignore.gif name=ignore" . $i . " alt='" . $this->textArray['Ignore'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>";
                    }
                    $text .= "</td></tr>";
                    $text .= "<tr><td class=mainbold-alt width=150 valign=top>" . $this->textArray['Body'] . ": </td><td class=main-alt>$body</td></tr>";
                    $text .= "<tr><td class=main colspan=2><input class=submit type=button value='" . $this->textArray['Reply'] . "' onClick=goToURL('forums.php?action=pm&pm_id=$_GET[pm_id]')> <input class=submit type=button value=" . $this->textArray['Delete'] . " onClick=confirmDelete('forums.php?action=pm&subaction=delete&pm_id=$_GET[pm_id]')></td></tr>";
                    $text .= "</table></td></tr></table>";
                    $text .= $this->pmSubBox();
                }

            }
            else {
                if (!isset($_COOKIE[PXL])){ $text = $this->core->login(0, "forums.php"); }
                else {
                    $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
                    require("includes/formscript.inc.php");
                    $text .= "<form method=post action=forums.php?action=pm&subaction=delete name=pm>";
                    $text .= "<tr><td class=title align=center>" . $this->textArray['PM'] . " " . $this->textArray['Inbox'] . "</td></tr>";
                    $text .= "<tr><td width=100% class=main><table border=0 cellpadding=2 cellspacing=1 align=center width=100%>";
                    $text .= "<tr><td class=medtitle align=center>" . $this->textArray['Date'] . "</td><td class=medtitle align=center>" . $this->textArray['Subject'] . "</td><td class=medtitle align=center>" . $this->textArray['From'] . "</td>";
                    $text .= "<td class=medtitle align=center width=80>" . $this->textArray['Delete'] . " <input name=allbox type=checkbox value=1 onClick='CheckAll();'></td></tr>";
                    $dateFormat = $this->core->dbCall("dateFormat");
                    $result = mysql_query("select u.username, u.user_id, p.subject, p.date, p.view, p.pm_id from forums_pm p, users u where p.user_id = '$_COOKIE[PXL]' and p.deleted = '0' and u.user_id = p.from_user_id order by p.date desc")or die(mysql_error());
                    $count = mysql_num_rows($result);
                    if ($count != 0){
                        $i=1;
                        $x=1;
                        while(list($username, $user_id, $subject, $date, $view, $pm_id) = mysql_fetch_row($result)){
                            $subject = $this->text->convertText($subject, 2);
                            if ($x == 1){ $class="main"; $bold="mainbold"; $x++; }
                            else { $class="main-alt"; $bold="mainbold-alt"; $x=1; }
                            if ($view == 0){ $class=$bold; }
                            $date = $this->core->getTime($date);
                            $date = date($dateFormat . " H:i", $date);
                            $text .= "<tr><td class=$class width=150>$date</td><td class=$class><a href=forums.php?subaction=inbox&action=pm&pm_id=$pm_id>$subject</a></td>";
                            $text .= "<td class=$class valign=middle>$username";
                            if ($_COOKIE['PXL'] != $user_id){
                                $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('email" . $i . "', '', 'images/email_user_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('users.php?action=email&user_id=$user_id')><img src=images/email_user.gif name=email" . $i . " alt='" . $this->textArray['Email'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>";
                                $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('pm" . $i . "', '', 'images/pm_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=pm&user_id=$user_id')><img src=images/pm.gif name=pm" . $i . " alt='" . $this->textArray['PM'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>";
                                $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('profile" . $i . "', '', 'images/user_profile_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('users.php?action=view&user_id=$user_id')><img src=images/user_profile.gif name=profile" . $i . " alt='" . $this->textArray['User Profile'] . "' border=0></span>";
                                $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('ignore" . $i . "', '', 'images/ignore_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=confirmIgnore('forums.php?action=ignore&ignore_id=$user_id&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&post_id=$post_id#$post_id')><img src=images/ignore.gif name=ignore" . $i . " alt='" . $this->textArray['Ignore'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>";
                            }
                            $text .= "</td><td class=$class><input type=checkbox name=delete[] value=$pm_id></td></tr>";
                            $i++;
                        }
                        $text .= "<tr><td class=main align=right colspan=4><input type=submit class=submit value='" . $this->textArray['Delete'] . "'></td></form></tr>";
                    }
                    else {
                        $text .= "<tr><td class=attn align=center colspan=4>" . $this->textArray['No Results Found'] . "</td></tr>";
                    }
                    $text .= "</table></td></tr></table>";
                    $text .= $this->pmSubBox();
                }
            }

            $this->body = $text;

        }
        else if ($_GET['subaction'] == "outbox"){
            if (isset($_GET[pm_id])){
                $dateFormat = $this->core->dbCall("dateFormat");
                list($user_id, $username, $subject, $body, $date, $check) = mysql_fetch_row(mysql_query("select u.user_id, u.username, p.subject, p.body, p.date, p.from_user_id from forums_pm p, users u where u.user_id = p.user_id and p.from_user_id = '$_COOKIE[PXL]' and p.pm_id = '$_GET[pm_id]'"));
                $body = $this->text->convertText($body, 2);
                $subject = $this->text->convertText($subject, 2);
                $date = $this->core->getTime($date);
                $date = date($dateFormat . " H:i", $date);
                if ($check != $_COOKIE[PXL]){ header("Location: error.php?error=403"); }
                $i=1;
                $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
                require("includes/formscript.inc.php");
                $text .= "<tr><td class=title align=center>" . $this->textArray['PM'] . " " . $this->textArray['Detail'] . "</td></tr>";
                $text .= "<tr><td width=100% class=main><table border=0 cellpadding=4 cellspacing=1 align=center width=100%>";
                $text .= "<tr><td class=mainbold width=150>" . $this->textArray['Subject'] . ": </td><td class=main>$subject</td></tr>";
                $text .= "<tr><td class=mainbold-alt width=150>" . $this->textArray['Date'] . ": </td><td class=main-alt>$date</td></tr>";
                $text .= "<tr><td class=mainbold width=150>" . $this->textArray['From'] . ": </td><td class=main>";
                $text .= "$username";
                $text .= "</td></tr>";
                $text .= "<tr><td class=mainbold-alt width=150 valign=top>" . $this->textArray['Body'] . ": </td><td class=main-alt>$body</td></tr>";

                $text .= "</table></td></tr></table>";

            }
            else {
                if (!isset($_COOKIE[PXL])){ $text = $this->core->login(0, "forums.php"); }
                else {
                    $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
                    require("includes/formscript.inc.php");
                    $text .= "<form method=post action=forums.php?action=pm&subaction=delete name=pm>";
                    $text .= "<tr><td class=title align=center>" . $this->textArray['PM'] . " " . $this->textArray['Outbox'] . "</td></tr>";
                    $text .= "<tr><td width=100% class=main><table border=0 cellpadding=2 cellspacing=1 align=center width=100%>";
                    $text .= "<tr><td class=medtitle align=center>" . $this->textArray['Date'] . "</td><td class=medtitle align=center>" . $this->textArray['Subject'] . "</td><td class=medtitle align=center>" . $this->textArray['To'] . "</td></tr>";

                    $dateFormat = $this->core->dbCall("dateFormat");
                    $result = mysql_query("select u.username, u.user_id, p.subject, p.date, p.view, p.pm_id from forums_pm p, users u where p.from_user_id = '$_COOKIE[PXL]' and p.deleted = '0' and u.user_id = p.user_id order by p.date desc")or die(mysql_error());
                    $count = mysql_num_rows($result);
                    if ($count != 0){
                        $i=1;
                        $x=1;
                        while(list($username, $user_id, $subject, $date, $view, $pm_id) = mysql_fetch_row($result)){
                            $subject = $this->text->convertText($subject, 2);
                            if ($x == 1){ $class="main"; $bold="mainbold"; $x++; }
                            else { $class="main-alt"; $bold="mainbold-alt"; $x=1; }
                            $date = $this->core->getTime($date);
                            $date = date($dateFormat . " H:i", $date);
                            $text .= "<tr><td class=$class width=150>$date</td><td class=$class><a href=forums.php?subaction=outbox&action=pm&pm_id=$pm_id>$subject</a></td>";
                            $text .= "<td class=$class valign=middle>$username";
                            $text .= "</td></tr>";
                            $i++;
                        }

                    }
                    else {
                        $text .= "<tr><td class=attn align=center colspan=4>" . $this->textArray['No Results Found'] . "</td></tr>";
                    }
                    $text .= "</table></td></tr></table>";
                    $text .= $this->pmSubBox();
                }
            }

            $this->body = $text;
        }
        else if ($_GET['subaction'] == "delete"){
            if (!isset($_COOKIE[PXL])){ header("Location: error.php?error=403"); }
            if (isset($_GET[pm_id])){ mysql_query("update forums_pm set deleted = '1' where user_id = '$_COOKIE[PXL]' and pm_id = '$_GET[pm_id]'"); }
            else {
                foreach($_POST[delete] as $d){
                    mysql_query("update forums_pm set deleted = '1' where user_id = '$_COOKIE[PXL]' and pm_id = '$d'") or die(mysql_error());
                }
            }
            header("Location: forums.php?action=pm&subaction=inbox");
        }
        else {
            list($allow_html, $allow_xcode, $allow_smiles, $allow_polls, $image_tags) = mysql_fetch_row(mysql_query("select allow_html, allow_xcode, allow_smiles, polls, image_tags from forums_config")) or die(mysql_error());
            if ($_POST['confirm'] == 1){
                if ($_POST['user_id'] == '' && $_POST['list1'] != ''){ $_POST[user_id] = $_POST[list1]; }

                if (isset($_POST['preview'])){

                    $_POST['subject'] = stripslashes($_POST['subject']);
                    $_POST['post'] = stripslashes($_POST['post']);
                    $subject = stripslashes($this->text->convertText($_POST['subject'], 1, 0));
                    $post = stripslashes($this->text->convertText($_POST['post'], 1, 1));
                    $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
                    $text .= "<tr><td class=medtitle>" . $this->textArray['Preview'] . " " . $this->textArray['PM'] . "</td></tr>";
                    $text .= "<td class=main valign=top style=\"word-wrap: break-word\"><span class=small>Subject: $subject</span><br><hr>$post</td></tr>";
                    $text .= "</table><p>";

                    $_POST['confirm'] = 0;
                    $this->pmUser();

                    $this->body = $text . $this->body;
                }
                else {
                    $user_id = $_POST['user_id'];
                    if ($_COOKIE['VALID'] != 1){ die("Relay Forbidden"); }
                    $siteName = $this->core->dbCall("siteName");
                    $webmasterEmail = $this->core->dbCall("webmasterEmail");
                    $siteURL = $this->core->dbCall("siteURL");
                    $subject = $this->text->convertText($_POST['subject'], 1, 0);
                    $post = $this->text->convertText($_POST['post'], 1, 1);
                    $date = $this->core->getTime(time(), 1);
                    $quota = $this->core->forumCall("pm_inbox_size");
                    list($mess) = mysql_fetch_row(mysql_query("select count(pm_id) from forums_pm where user_id = '$user_id' and deleted = '0'"));
                    if ($quota > $mess){
                        mysql_query("insert into forums_pm values ('', '$user_id', '$_COOKIE[PXL]', '$subject', '$post', '$date', '0', '0')");
                        $pm_id = mysql_insert_id();
                        list($ignore) = mysql_fetch_row(mysql_query("select count(user_id) from forums_ignore where user_id = '$user_id' and ignore_user_id = '$_COOKIE[PXL]'")) or die(mysql_error());
                        if ($ignore != 0){ mysql_query("update forums_pm set delete = '1' where pm_id = '$pm_id'"); }

                        list($pm_email, $to) = mysql_fetch_row(mysql_query("select pm_email, email from users where user_id = '$_POST[user_id]'"));
                        if ($pm_email == 1 && $ignore == 0){
                            list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$_COOKIE[PXL]'"));
                            $subject = $this->text->convertText($subject, 2);
                            $headers = "From: $siteName - " . $this->textArray['PM'] . " <$webmasterEmail>\r\n";
                            $headers .= "X-Sender: <$webmasterEmail>\r\n";
                            $headers .= "X-Mailer: PHP\r\n";
                            $headers .= "X-Priority: 3\r\n";
                            $headers .= "Reply-To: $webmasterEmail\r\n";
                            $subject = "" . $this->textArray['PM'] . " " . $this->textArray['via'] . " $siteName : $subject";

                            $message = "$username " . $this->textArray['has sent you a'] . " " . $this->textArray['PM'] . "\r\n";
                            $message .= $this->textArray['click here to view'] . ": $siteURL" . "/forums.php?action=pm&pm_id=$pm_id&subaction=inbox \r\n\r\n";
                            $message .= $this->textArray['Do not reply to this message'];

                            mail($to, $subject, $message, $headers);
                        }
                    }
                    else {
                        $post = $this->textArray['This message was returned.  The user you were sending to is overlimit and cannot accept any more messages.'] . "<br><br>#=========================<br>" . $post;
                        $subject = $this->textArray['RETURNED'] . ": $subject";
                        mysql_query("insert into forums_pm values ('', '$_COOKIE[PXL]', '$_COOKIE[PXL]', '$subject', '$post', '$date', '0', '0')");
                    }
                    header("Location: forums.php?action=pm&subaction=inbox");
                }

            }
            else {
                if (isset($_GET[pm_id])){
                    $reply=1;
                    list($subject, $body, $user_id) = mysql_fetch_row(mysql_query("select subject, body, from_user_id from forums_pm where pm_id = '$_GET[pm_id]' and user_id = '$_COOKIE[PXL]'"));
                    if (substr_count($subject, "RE:") == 0){ $_POST[subject] = "RE: " . $this->text->convertText($subject, 0, 0); }
                    else { $_POST[subject] = $this->text->convertText($subject, 0, 0); }
                    $_POST[post] = "\r\n\r\n\r\n#============================\r\n" . $this->textArray['Orignal Message'] . ":\r\n" . $this->text->convertText($body, 0, 1);
                    $_POST[user_id] = $user_id;
                }
                if ($_GET[user_id] == ''){ $_GET[user_id] = $_POST[user_id]; }

                if (!isset($_COOKIE["PXL"])){ $this->body = $this->core->login(0, "forums.php?action=pm&user_id=$_GET[user_id]"); }
                else {
                    $create = new forumPost();
                    $create->templates = $this->templates;
                    $create->textArray = $this->textArray;
                    setcookie("VALID", 1, 0, '', '', $this->ssl);

                    list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$_GET[user_id]'"));
                    require("includes/formscript.inc.php");
                    $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
                    $text .= "<tr><td class=title align=center>" . $this->textArray['PM'] . ": $username</td></tr>";
                    $text .= "<tr><td width=100% class=main><table border=0 cellpadding=2 cellspacing=1 align=center width=100%>";
                    $text .= "<form method=post name=forum action=forums.php?action=pm onsubmit=\"return validateForm(this)\">";
                    $text .= "<script language=\"JavaScript\" src=\"includes/post.js\"></script>";
                    $text .= "<input type=hidden name=confirm value=1><input type=hidden name=user_id value=$_GET[user_id]>";
                    if ($allow_smiles == 1){ $smiles = $create->createSmileForm(); }
                    if ($_GET['user_id'] == ''){  $text .= $this->userSelectForm(); }
                    $text .= "<tr><td class=mainbold>" . $this->textArray['Subject'] . ": </td><td class=main><input type=text value='$_POST[subject]' size=45 name=subject></td></tr>";
                    if ($allow_xcode == 1){ $xcode = $create->createXCodeForm($image_tags); $xdesc = "<span style='font-size:9px;'><a href=forums.php?action=xcode target=new>XCode</a> " . $this->textArray['is On'] . "</span><br>"; }
                    else { $xcode = ''; $xdesc = "<span style='font-size:9px;'><a href=forums.php?action=xcode>XCode</a> " . $this->textArray['is Off'] . "</span><br>"; }
                    if ($allow_html == 1){ $html = "<span style='font-size:9px;'>HTML " . $this->textArray['is On'] . "</span><br>"; }
                    else { $html = "<span style='font-size:9px;'>HTML " . $this->textArray['is Off'] . "</span><br>"; }
                    if ($image_tags == 1){ $images = "<span style='font-size:9px;'>[img] " . $this->textArray['is On'] . "</span>"; }
                    else { $images = "<span style='font-size:9px;'>[img] " . $this->textArray['is Off'] . "</span>"; }
                    $text .= "<tr><td class=main-alt valign=top><b>Xcode:</b> <br>$xdesc $html $images</td><td class=main-alt>$xcode</td></tr>";

                    $text .= "<tr><td class=main-alt valign=top><b>" . $this->textArray['Post'] . " :<br><br>";
                    $text .= $smiles;

                    $text .= "</td><td class=main-alt><textarea name=post cols=75% rows=15 wrap=virtual onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);' >$_POST[post]</textarea></td></tr>";
                    $text .= "<tr><td class=main align=center colspan=2><input type=submit name=submit value='" . $this->textArray['Submit'] . " " . $this->textArray['PM'] . "' class=submit> ";
                    $text .= "<input type=submit name=preview value='" . $this->textArray['Preview'] . " " . $this->textArray['PM'] . "' class=submit></form></td></tr>";

                    $text .= "<script Language=JavaScript>";
                    $text .= "function validateForm(theForm)";
                    $text .= "{";
                    $text .= "    if (!validRequired(theForm.post,'" . $this->textArray['Post'] . "'))";
                    $text .= "        return false;";
                    $text .= "    if (!validRequired(theForm.subject,'" . $this->textArray['Subject'] . "'))";
                    $text .= "        return false;";
                    $text .= "    return true;";
                    $text .= "}";
                    $text .= "</script>";
                    $text .= "</table></td></tr></table>";
                    $text .= $this->pmSubBox();
                    $this->body = $text;
                }
            }
        }
    }

    function pmSubBox(){
        list($new) = mysql_fetch_row(mysql_query("select count(pm_id) from forums_pm where view = '0' and user_id = '$_COOKIE[PXL]' and deleted = '0'"));
        list($total) = mysql_fetch_row(mysql_query("select count(pm_id) from forums_pm where user_id = '$_COOKIE[PXL]' and deleted = '0'"));
        $quota = $this->core->forumCall("pm_inbox_size");
        $barColor = $this->core->dbCall("barColor");

        $points = 100/$quota;
        $mess = round($total*$points);
        $warn='';
        $c = $quota * .85;
        if ($total > $c){ $warn = $this->textArray['You are approaching your quota.  Please delete older items']; }
        $qu = "<table border=0 cellpadding=2 cellspacing=1 class=outline width=150>";
        $qu .= "<tr><td class=medtitle colspan=3>" . $this->textArray['Inbox'] . " " . $this->textArray['Quota'] . " &nbsp;&nbsp;&nbsp;<span class=small>$total/$quota</span></td></tr>";
        $qu .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=125 align=center>";
        $qu .= "<tr><td class=small>0%</td><td class=small align=center>50%</td><td class=small align=right>100%</td></tr>";
        $qu .= "<tr><td class=main colspan=3 width=100><img src=images/$barColor.gif height=15 width=$mess></td></tr>";
        $qu .= "<tr><td class=attn colspan=3>$warn</td></tr>";
        $qu .= "</table></td></tr></table>";

        $text = "<br><br><table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
        $text .= "<tr><td class=title align=center>" . $this->textArray['PM'] . "</td></tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=1 align=center width=95%>";
        $text .= "<tr><td class=main>$qu</td><td align=right valign=top class=main>$new " . $this->textArray['New'] . " " . $this->textArray['Messages'] . ", $total " . $this->textArray['Messages'] . "</td></tr>";
        $text .= "<tr><td class=main><input class=submit type=button value='" . $this->textArray['Create'] . " " . $this->textArray['PM'] . "' onClick=goToURL('forums.php?action=pm')></td>";
        $text .= "</form><form method=post action=users.php?action=login name=f>";
        $text .= "<input type=hidden name=url value='forums.php'><input type=hidden name=confirm value=1></form>";
        $text .= "<script language=javascript>";
        $text .= "function submitLogout(){ ";
        $text .= "document.f.submit(); } </script>";
        $text .= "<td class=main align=right><input class=submit type=button value='" . $this->textArray['Logout'] . "' onclick=submitLogout()> <input class=submit type=button value='" . $this->textArray['Inbox'] . "' onClick=goToURL('forums.php?action=pm&subaction=inbox')> <input class=submit type=button value='" . $this->textArray['Outbox'] . "' onClick=goToURL('forums.php?action=pm&subaction=outbox')></td></tr>";
        $text .= "</table></td></tr></table>";
        return $text;
    }

    function userSelectForm(){

        $hold = array();
        $result = mysql_query("select ignore_user_id from forums_ignore where user_id = '$_COOKIE[PXL]'");
        while(list($user_id) = mysql_fetch_row($result)){
            $hold[] = $user_id;
        }

        $result = mysql_query("select user_id, username from users where suspend != '1' and user_id != '$_COOKIE[PXL]' order by username");
        while(list($user_id, $username) = mysql_fetch_row($result)){
            if (!in_array($user_id, $hold)){ $poss .= "<option value=$user_id>$username</option>"; }
        }

        $text .= "<tr><td class=mainbold>" . $this->textArray['Select User'] . ": </td>";
        $text .= "<td class=main><input type=text name=searchForm size=25 onKeyUp=\"autoComplete(this,this.form.list1,'text',true)\"> <select name=list1>$poss</select>";
        $text .= "</td></tr>";
        return $text;
    }


}

class viewForumClass {

    var $templates;
    var $textArray;
    var $text;
    var $core;

    function viewAllForums(){

        $siteName = $this->core->dbCall("siteName");

        $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
        $text .= "<tr><td class=title align=center>$siteName " . $this->textArray['Forums'] . "</td></tr>";
        $text .= "</table><table border=0 cellpadding=2 cellspacing=1 align=center width=95% class=outline>";
        $text .= "<tr><td class=medtitle colspan=2>" . $this->textArray['Forum'] . "</td><td class=medtitle>" . $this->textArray['Topics'] . "</td>";
        $text .= "<td class=medtitle>" . ucfirst($this->textArray['posts']) . "</td><td class=medtitle>" . $this->textArray['Last Post'] . "</td></tr>";
        $result = mysql_query("select cat_id, name from forums_cat where comments != '1' order by ord");
        while(list($cat_id, $cat_name) = mysql_fetch_row($result)){
            $cat_name = $this->text->convertText($cat_name, 2);
            $text .= "<tr><td class=subtitle colspan=5><a href=forums.php?cat_id=$cat_id>$cat_name</a></td></tr>";
            $text .= $this->forumCat($cat_id);
        }
        $text .= "</table>";
        $pm = $this->newPM();
        $text .= "<table border=0 cellpadding=0 cellspacig=0 width=95% align=center><tr><td class=small>$pm</td></tr><table><p><br>";
        $text .= $this->subBox();
        $this->body = $text;

    }

    function viewCat(){
        $cat_id = $_GET['cat_id'];
        $siteName = $this->core->dbCall("siteName");
        list($name, $comments) = mysql_fetch_row(mysql_query("select name, comments from forums_cat where cat_id = '$cat_id'"));

        if ($comments != '0'){ header("Location: error.php?error=403"); }
        $name = $this->text->convertText($name, 2);
        $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=main align=center>";
        $text .= "<tr><td class=main-large><a href=forums.php>$siteName " . $this->textArray['Forums'] . "</a> -> $name</td></tr>";
        $text .= "</table><table border=0 cellpadding=2 cellspacing=1 align=center width=95% class=outline>";
        $text .= "<tr><td class=medtitle colspan=2>" . $this->textArray['Forum'] . "</td><td class=medtitle>" . $this->textArray['Topics'] . "</td>";
        $text .= "<td class=medtitle>" . ucfirst($this->textArray['posts']) . "</td><td class=medtitle>" . $this->textArray['Last Post'] . "</td></tr>";
        $text .= $this->forumCat($cat_id);
        $text .= "</table>";
        $pm = $this->newPM();
        $text .= "<table border=0 cellpadding=0 cellspacig=0 width=95% align=center><tr><td class=small>$pm</td></tr><table><p><br>";
        $text .= $this->subBox();
        $this->body = $text;
    }


    function forumCat($cat_id){
        $dateFormat = $this->core->dbCall("dateFormat");
        list($temp) = mysql_fetch_row(mysql_query("select templast from forums_session where sess_id = '$_COOKIE[PXLF]'"));
        $result1 = mysql_query("select f.forum_id, f.name, f.fdescription, f.level, f.posts, f.topics, p.topic_id, p.post_id, p.date, u.username, u.user_id from forums_forums f, forums_posts p, users u where f.cat_id = '$cat_id' and f.post_id = p.post_id and p.user_id = u.user_id order by f.ord");
        $x=1;

        while(list($forum_id, $name, $desc, $level, $posts, $topics, $topic_id, $post_id, $date, $user, $post_user_id) = mysql_fetch_row($result1)){
            $secure = $this->security->forumSecure($forum_id, 0, 0);
            if ($secure == 1){
                $mods = '';
                $result2 = mysql_query("select u.username, u.user_id from forums_mod m, users u where u.user_id = m.user_id and m.forum_id = '$forum_id'");
                while(list($username, $user_id) = mysql_fetch_row($result2)){
                    $mods .= "<a href=users.php?action=view&user_id=$user_id>$username</a>, ";
                }
                if ($x == 1){ $class="main"; $x++; }
                else { $class="main-alt"; $x=1; }
                $name = $this->text->convertText($name, 2);
                $desc = $this->text->convertText($desc, 2);
                $icon = $this->findIcon(0, 0, 0, $temp, $date);

                $text .= "<tr><td class=$class>$icon</td>";
                $text .= "<td class=$class><div class=forum><a href=forums.php?forum_id=$forum_id>$name</a></div>$desc<br>";
                if ($mods != ''){ $text .= $this->textArray['Moderators'] . ": " . substr($mods, 0, -2); }
                $text .= "</div></td>";
                $text .= "<td class=$class align=center>$topics</td><td class=$class align=center>$posts</td>";
                $text .= "<td class=$class>";
                if ($date != '' && $date != '0'){
                    $date = $this->core->getTime($date);
                    $text .= "<div class=small>" . date($dateFormat . " H:i", $date) . "<br>" . $this->textArray['by'] . " <a href=users.php?action=view&user_id=$post_user_id>$user</a>";
                    $text .= " <a href=forums.php?forum_id=$forum_id&topic_id=$topic_id&post_id=$post_id#$post_id><img src=images/view_last_post.gif width=16 height=16 alt='" . $this->textArray['View'] . " " . $this->textArray['Last Post'] . "' border=0></a></div>";
                }
                else { $text .= "<div class=small>No Posts</div>"; }
                $text .= "</td></tr>";
            }

        }
        return $text;
    }

    function subBox($flag=''){
        $allow_pm = $this->core->forumCall("allow_pm");
        $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
        $text .= "<tr><td class=title align=center> " . $this->textArray['Forum'] . " " . $this->textArray['Options'] . "</td></tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 align=center width=95% class=main>";

        $time = time() - 300;
        $time = $this->core->getTime($time, 1);

        $result = mysql_query("select user_id from forums_session where last > '$time'");
        $total = mysql_num_rows($result);
        list($posts) = mysql_fetch_row(mysql_query("select count(post_id) from forums_posts"));
        list($users) = mysql_fetch_row(mysql_query("select count(user_id) from users"));
        list($last_user_id, $last_user_name) = mysql_fetch_row(mysql_query("select user_id, username from users where actkey is null order by user_id desc limit 0,1"));
        $guests=0;
        $members=0;
        $hidden=0;
        $member_list = ucfirst($this->textArray['members']) . " " . ucfirst($this->textArray['Online']) . ": ";
        while(list($user_id) = mysql_fetch_row($result)){
            if ($user_id == '0'){ $guests++; }
            else {
                list($username, $view_online) = mysql_fetch_row(mysql_query("select username, view_online from users where user_id = '$user_id'"));
                if ($view_online == 1){ $members++; $member_list .= "<a href=users.php?action=view&user_id=$user_id>$username</a>, "; }
                else { $hidden++; }
            }
        }
        if ($members == 0){ $member_list .= $this->textArray['None'] . "  "; }
        $text .= "<tr><td class=subtitle colspan=2>" . $this->textArray['Who is'] . " " . ucfirst($this->textArray['online']) . "</td></tr>";
        $text .= "<tr><td class=main colspan=2>" . $this->textArray['In total'] . " $users " . $this->textArray['users'] . " " . $this->textArray['have posted'] . " $posts " . $this->textArray['posts'];
        if (!isset($_COOKIE["PXL"])){ $text .= "<br>" . $this->textArray['More forums/posts may be visible if you login']; }
        $text .= "</td></tr>";
        $text .= "<tr><td class=main colspan=2><a href=users.php?action=view&user_id=$last_user_id>$last_user_name</a> " . $this->textArray['is our newest user'] . "</td></tr>";
        if ($total == 1){ $text .= "<tr><td class=main colspan=2>" . $this->textArray['Currently there is'] . " $total " . $this->textArray['user'] . " " . $this->textArray['online'] . "."; }
        else { $text .= "<tr><td class=main colspan=2>" . $this->textArray['Currently there are'] . " $total " . $this->textArray['users'] . " " . $this->textArray['online'] . "."; }
        $text .= " $members " . $this->textArray['members'] . ", $hidden " . $this->textArray['hidden'] . ", $guests " . $this->textArray['guests'] . ".<br>";
        $text .= substr($member_list, 0, -2) . "</td></tr>";


        $text .= "<tr><td class=subtitle colspan=2></td></tr>";
        $text .= "<tr><td class=main>";
        $text .= "<form method=post action=forums.php?action=search><input type=hidden name=confirm value=1>";
        $text .= $this->textArray['Search'] . " " . $this->textArray['Forums'] . ": <input type=text name=keywords value='' size=18>";
        $text .= " <input type=submit class=submit value='" . $this->textArray['Search'] . "'></form></td>";
        $text .= "<td class=main align=right>";
        if (isset($_COOKIE[PXL])){
            list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$_COOKIE[PXL]'"));
            $text .= "<script language=javascript>";
            $text .= "function submitLogout(){ ";
            $text .= "document.f.submit(); } </script>";

            $text .= "<form method=post action=users.php?action=login name=f>";
            $text .= "<input type=hidden name=url value='$_SERVER[REQUEST_URI]'><input type=hidden name=confirm value=1>";
            $text .= "" . $this->textArray['Logged in as'] . " <a href=users.php?action=view&user_id=$_COOKIE[PXL]>$username</a> | ";
            if ($allow_pm == 1){ $text .= "<a href=forums.php?action=pm&subaction=inbox>" . $this->textArray['Inbox'] . "</a> |"; }
            $text .= " <a href='javascript:submitLogout()'>" . $this->textArray['Logout'] . "</a></form>";
        }
        else {
            $text .= "<form method=post action=users.php?action=login><input type=hidden name=confirm value=1>";
            $text .= "<input type=hidden name=url value='$_SERVER[REQUEST_URI]'>";
            $text .= "<div class=small>" . $this->textArray['Login with username and password'] . ":<br></div>";
            $text .= "<input type=text value='' name=username size=8> <input type=password name=password value='' size=8> ";
            $text .= "<input type=submit class=submit value='" . $this->textArray['Login'] . "'><br><input type=checkbox name=remember checked>" . $this->textArray['Remember Me'] . "<br>";
            $text .= "<a href=users.php?action=signup>" . $this->textArray['Register Here'] . "</a> | <a href=users.php?action=password>" . $this->textArray['Lost Password'] . "</a>";

        }
        $text .= "</td></form></tr></table></td></tr></table><p>";
        return $text;
    }

    function findIcon($sticky, $locked, $posts, $temp, $last_date){
        $threshold = $this->core->forumCall("popular_threshold");
        $image = "images/";
        $alt = '';
        if ($temp < $last_date){
            $image .= "new";
            $alt .= $this->textArray['New'];
        }
        if ($posts >= $threshold){
            if ($image == "images/"){
                $image .= "hot";
                $alt .= $this->textArray['Hot'];
            }
            else {
                $image .= "_hot";
                $alt .= " " . $this->textArray['Hot'];
            }
        }
        if ($sticky == 1){
            if ($image == "images/"){
                $image .= "sticky";
                $alt .= $this->textArray['Sticky'];
            }
            else {
                $image .= "_sticky";
                $alt .= " " . $this->textArray['Sticky'];
            }
        }
        if ($locked == 1){
            if ($image == "images/"){
                $image .= "locked";
                $alt .= $this->textArray['Locked'];
            }
            else {
                $image .= "_locked";
                $alt .= " " . $this->textArray['Locked'];
            }
        }

        if ($image == "images/"){
            $image .= "topic.gif";
            $alt .= $this->textArray['Topic'];
        }
        else {
            $image .= "_topic.gif";
            $alt .= " " . $this->textArray['Topic'];
        }

        $icon = "<img src=$image alt='$alt' border=0>";
        return $icon;
    }



    function viewPost(){
        $posts_per_page = $this->core->forumCall("posts_per_page");
        list($count) = mysql_fetch_row(mysql_query("select posts from forums_topics where topic_id = '$_GET[topic_id]'"));
        $count++;
        list($prev) = mysql_fetch_row(mysql_query("select count(post_id) from forums_posts where topic_id = '$_GET[topic_id]' and post_id < '$_GET[post_id]'"));
        $prev = $prev/$posts_per_page;

        if (substr_count($prev, ".") > 0){
            $prev = substr($prev, 0, strpos($prev, "."));
        }
        $limit = $prev*$posts_per_page;
        $_GET['limit'] = $limit;
        $this->viewTopic();
    }

    function searchResults(){

        $this->core = new core();
        $this->text = new textFunctions();
        $this->security = new security();

        $topics_per_page = $this->core->forumCall("topics_per_page");
        $posts_per_page = $this->core->forumCall("posts_per_page");
        $siteName = $this->core->dbCall("siteName");
        $dateFormat = $this->core->dbCall("dateFormat");

        if (isset($_GET['limit'])){ $limit = $_GET['limit']; }
        else { $limit = 0; }
        list($rows) = mysql_fetch_row(mysql_query("select rows from forums_search_control where search_id = '$_GET[search_id]'"));
        $result = mysql_query("select s.forum_id, s.topic_id, s.title, s.user_id, s.posts, s.reads, s.last_post_id, s.last_post_date, s.last_post_user_id, u.username, u2.username, f.name, t.sticky, t.locked from forums_search s, users u, users u2, forums_forums f, forums_topics t where s.search_id = '$_GET[search_id]' and s.topic_id = t.topic_id and f.forum_id = s.forum_id and u2.user_id = s.user_id and u.user_id = s.last_post_user_id order by s.last_post_date desc limit $limit, $topics_per_page");

        list($temp) = mysql_fetch_row(mysql_query("select templast from forums_session where sess_id = '$_COOKIE[PXLF]'"));

        $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=main align=center>";
        $text .= "<tr><td class=main-large><a href=forums.php>$siteName " . $this->textArray['Forums'] . "</a> -> " . $this->textArray['Search Results'] . "</td>";
        $text .= "<td valign=middle class=main-large align=right></td>";


        $text .= "<td class=main-large align=right> </td></tr>";
        $text .= "</table><table border=0 cellpadding=2 cellspacing=1 align=center width=95% class=outline>";
        $text .= "<tr><td class=medtitle colspan=2>" . $this->textArray['Topics'] . "</td><td class=medtitle align=center>" . $this->textArray['Forum'] . "</td><td class=medtitle align=center>" . $this->textArray['Replies'] . "</td>";
        $text .= "<td class=medtitle align=center>" . $this->textArray['Reads'] . "</td><td class=medtitle align=center>" . $this->textArray['Author'] . "</td><td class=medtitle>" . $this->textArray['Last Post'] . "</td></tr>";
        $more = $this->core->pageTab($limit, $rows, "forums.php?action=search&search_id=$_GET[search_id]", $topics_per_page);
        if ($rows > 0){ $text .= "<tr><td class=main colspan=7>$more</td></tr>"; }

        $x=1;
        while(list($forum_id, $topic_id, $title, $user_id, $posts, $reads, $last_post_id, $last_date, $last_user_id, $last_user_name, $username, $forum_name, $sticky, $locked) = mysql_fetch_row($result)){
            $more2='';
            if ($posts > $posts_per_page){
                $more2 = " <font size=-2> Page :";
                $b=1;
                for($x=0; $x < $posts; $x += $posts_per_page){
                    $more2 .= " <a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$topic_id&limit=$x>$b</a> ";
                    $b++;
                }
                $more2 .= "</font>";
            }
            $posts--;
            $last_date = $this->core->getTime($last_date);
            $last_post = "<div class=small>" . date($dateFormat . " H:i", $last_date) . "<br>";
            $last_post .= $this->textArray['by'] . " <a href=users.php?action=view&user_id=$last_user_id>$last_username</a>";
            $last_post .= " <a href=forums.php?forum_id=$forum_id&topic_id=$topic_id&post_id=$last_post_id><img src=images/view_last_post.gif width=16 height=16 alt='" . $this->textArray['View'] . " " . $this->textArray['Last Post'] . "' border=0></a></div>";


            $title = $this->text->convertText($title, 2);
            if ($x == 1){ $class = "main"; $x++; }
            else { $class = "main-alt"; $x=1; }
            $icon = $this->findIcon($sticky, $locked, $posts, $temp, $last_date);
            $text .= "<tr><td class=$class>$icon</td><td class=$class><a href=forums.php?forum_id=$forum_id&topic_id=$topic_id>$title</a> $more2</td>";
            $text .= "<td class=$class><a href=forums.php?forum_id=$forum_id>$forum_name</a></td><td class=$class align=center>$posts</td><td class=$class align=center>$reads</td>";
            $text .= "<td class=$class align=center><a href=users.php?action=view&user_id=$user_id>$username</a></td>";
            $text .= "<td class=$class>$last_post</td></tr>";
        }
        if ($rows > 0){ $text .= "<tr><td class=main colspan=7>$more</td></tr>"; }
        else { $text .= "<tr><td class=main colspan=7>" . $this->textArray['No Results Found'] . "</td></tr>"; }

        $text .= "</table><p>";
        $text .= $this->subBox();
        $this->body = $text;
    }

    function viewForumPoll($forum_poll_id, $locked){


        list($poll_text, $total) = mysql_fetch_row(mysql_query("select forum_poll_text, total from forums_polls where forum_poll_id = '$forum_poll_id'"));
        list($check) = mysql_fetch_row(mysql_query("select count(forum_poll_user_id) from forums_polls_users where forum_poll_id = '$forum_poll_id' and user_id = '$_COOKIE[PXL]'"));

        $text = "<table border=0 cellpadding=2 cellspacing=1 align=center width=80% class=outline>";
        $text .= "<tr><td class=medtitle>$poll_text</td></tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=1 cellspacing=0 align=center width=100%>";
        $result = mysql_query("select option_id, option_text, option_results from forums_poll_results where forum_poll_id = '$forum_poll_id'") or die(mysql_error());
        if (isset($_COOKIE['PXL']) && $check == 0 && $locked != "1"){
            $text .= "<form method=post action=forums.php?action=vote>";
            $text .= "<input type=hidden name=forum_poll_id value=$forum_poll_id>";
            while(list($id, $option, $results) = mysql_fetch_row($result)){
                $option = $this->text->convertText($option);
                $text .= "<tr><td class=main width=40 align=center><input type=radio value=$id name=options></td><td class=mainbold> $option </td></tr>";
            }
            $text .= "<tr><td class=main align=center colspan=2><input type=submit class=submit value='" . $this->textArray['Vote'] . "'></td></tr></form>";

        }
        else {
            $s = $total/100;
            $barColor = $this->core->dbCall("barColor");

            while(list($id, $option, $results) = mysql_fetch_row($result)){
                $option = $this->text->convertText($option);

                if ($s > 0){
                    $perc = ($results / $s);
                    $width = $perc * 2;
                    $perc = round($perc);
                }
                else {
                    $perc = "0";
                    $width = "0";
                }

                $text .= "<tr><td class=mainbold>$option : </td>";
                $text .= "<td class=main><img src=images/$barColor.gif alt='$results' width=$width height=10> ($perc%) ($results)</td></tr>";
            }
            $text .= "<tr><td class=main colspan=2 align=center>" . $this->textArray['Total'] . " " . $this->textArray['Votes'] . ": $total</td></tr>";

        }
        $text .= "</table></td></tr></table><p>";
        return $text;
    }

    function createTopicHeader($comment_id){

        list($comment_type) = mysql_fetch_row(mysql_query("select comment_type from forums_forums where forum_id = '$_GET[forum_id]'"));

        switch($comment_type){
            case 1: //NEWS
                $dateFormat = $this->core->dbCall("dateFormat");

                $active = $this->core->dbCall("news_comments");
                if ($active != 1){ header("Location: error.php?error=403"); }
                $result = mysql_query("select title, post, date, news_cat_id, more, user_id, size from news where news_id = '$comment_id'");
                list($title, $post, $date, $news_cat_id, $more, $user_id, $size) = mysql_fetch_row($result);
                $date = $this->core->getTime($date);
                $date = date($dateFormat . " H:i", $date);
                $title = $this->text->convertText($title, 2);
                $post = $this->text->convertText($post, 2);
                $more = "<br><br>" . $this->text->convertText($more, 2);
                $news_categories = $this->core->dbCall("news_categories");
                $news_images = $this->core->dbCall("news_images");
                $news_align = $this->core->dbCall("news_image_align");

                if ($news_align == 1){ $news_align = "left"; }
                else { $news_align = "right"; }

                if ($news_categories == 1){
                    list($cat_name, $cat_image) = mysql_fetch_row(mysql_query("select news_cat_title, news_cat_image from news_cat where news_cat_id = '$news_cat_id'"));
                    $cat_name = stripslashes($cat_name);
                    if ($news_images == 1){ $cat_image = "<img src=images/news/$cat_image alt='$cat_name' border=0 align=$news_align>"; }
                    $category = " <a href=news.php?news_cat_id=$news_cat_id&action=view_cat>$cat_name</a> : ";
                }
                include("$this->templates/files/news.tpl.php");

                $text .= "<div align=center class=main>";
                $text .= "<a href=news.php>" . $this->textArray['Back to'] . " " . $this->textArray['News'] . "</a> | <a href=news.php?news_id=$comment_id>" . $this->textArray['Back to'] . " " . $this->textArray['News Story'] . "</a></div><br>";

                $commentHeader = $text;
                $text = '';
                break;

            case 2: // GALLERY
                //$active = $this->core->dbCall("gallery_comments");
                $active = 1;
                if (!$active) { header ("Location: error.php?error=403"); }
                $queryString = "SELECT c.cat_id AS cat_id, c.name AS category_name, i.name AS name, i.file_type AS file_type, i.description AS description FROM gallery_images AS i, gallery_cat AS c WHERE i.image_id=$comment_id AND c.cat_id = i.cat_id";
                $resultSet = mysql_query($queryString);
                list($cat_id, $cat_name, $img_name, $img_file_type, $img_desc) = mysql_fetch_row($resultSet);
                $commentHeader  = "<table border=0 cellpadding=3 cellspacing=1 width=95% align=center class=outline>";
                $commentHeader .= "\t<tr>\n";
                $commentHeader .= "\t\t<td class=\"main\"><img src=\"images/gallery/category_$cat_id/$comment_id.$img_file_type\" border=\"0\" /></td>\n";
                $commentHeader .= "\t<tr>\n";
                break;

            case 7: //FAQ
                $active = $this->core->dbCall("faq_comments");
                if ($active != 1){ header("Location: error.php?error=403"); }
                $result = mysql_query("select c.title, c.faq_cat_id, f.answer, f.question from faq f, faq_cat c where c.faq_cat_id = f.faq_cat_id and f.faq_id = '$comment_id'");
                list($title, $cat_id, $an, $qu) = mysql_fetch_row($result);
                $title = $this->text->convertText($title, 2);
                $an = $this->text->convertText($an, 2);
                $qu = $this->text->convertText($qu, 2);
                $commentHeader = "<table border=0 cellpadding=3 cellspacing=1 width=95% align=center class=outline>";
                $commentHeader .= "<tr><td class=title><a href=faq.php?faq_cat_id=$cat_id>$title</a> -> <a href=faq.php?faq_cat_id=$cat_id#$comment_id>$qu</a></td></tr>";
                $commentHeader .= "<tr><td class=main>$an</td></tr>";
                $commentHeader .= "</table><br>";

                $commentHeader .= "<div align=center class=main>";
                $commentHeader .= "<a href=faq.php>" . $this->textArray['Back to'] . " " . $this->textArray['FAQ'] . "</a> | <a href=faq.php?faq_cat_id=$cat_id#$comment_id>" . $this->textArray['Back to'] . " " . $this->textArray['FAQ'] . " " . $this->textArray['Item'] . "</a></div><br>";
                break;
        }

        return $commentHeader;
    }

    function viewTopic(){


        $posts_per_page = $this->core->forumCall("posts_per_page");
        $siteName = $this->core->dbCall("siteName");

        $c = $this->security->forumSecure($_GET['forum_id'], 0, 1);
        $admin = $this->security->forumSecure($_GET['forum_id'], 1, 0);
        if ($c == 2){ $this->body = $this->core->login(0, "forums.php?forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&post_id=$_GET[post_id]#$_GET[post_id]"); }
        else {
            if (isset($_COOKIE["PXL"])){ $adminFun = $this->security->forumAdmin($_GET['forum_id']); }
            else { $adminFun = 0; }
            $allow_edit = $this->core->forumCall("edit_post");

            list($cat_name, $cat_id, $forum_name) = mysql_fetch_row(mysql_query("select c.name, c.cat_id, f.name from forums_forums f, forums_cat c where c.cat_id = f.cat_id and f.forum_id = '$_GET[forum_id]'"));
            $cat_name = $this->text->convertText($cat_name, 2);
            $forum_name = $this->text->convertText($forum_name, 2);
            $dateFormat = $this->core->dbCall("dateFormat");

            if ($_GET['limit'] == ''){ $limit = 0; }
            else {$limit = $_GET['limit']; }

            list($topic_name, $reads, $sticky, $locked, $posts, $topic_date, $forum_poll_id, $comment_id) = mysql_fetch_row(mysql_query("select p.title, t.reads, t.sticky, t.locked, t.posts, t.date, t.forum_poll_id, t.comment_id from forums_topics t, forums_posts p where t.topic_id = '$_GET[topic_id]' and t.post_id = p.post_id"));
            list($next_topic) = mysql_fetch_row(mysql_query("select topic_id from forums_topics where forum_id = '$_GET[forum_id]' and date > '$topic_date' order by date asc limit 0,1"));
            list($prev_topic) = mysql_fetch_row(mysql_query("select topic_id from forums_topics where forum_id = '$_GET[forum_id]' and date < '$topic_date' order by date desc limit 0,1"));

            if ($comment_id != 0){ $commentHeader = $this->createTopicHeader($comment_id); }

            $topic_name = $this->text->convertText($topic_name, 2);

            if (isset($_COOKIE[PXL]) && $admin != 1){
                list($admin) = mysql_fetch_row(mysql_query("select admin from users where user_id = '$_COOKIE[PXL]'"));
                if ($admin == 0){
                    list($admin) = mysql_fetch_row(mysql_query("select count(forum_mod_id) from forums_mod where user_id = '$_COOKIE[PXL]' and forum_id = '$_GET[forum_id]'"));
                }
            }

            $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=main align=center>";
            $text .= "<script language=javascript>";
            $text .= "MM_preloadImages('images/add_topic_over.gif', 'images/add_reply_over.gif', 'images/website_over.gif', 'images/email_user_over.gif', 'images/user_profile_over.gif', 'images/quote_over.gif', 'images/edit_over.gif', 'images/pm_over.gif', 'images/ignore_over.gif');";
            $text .= "</script>";

            $lockForm = "<a href=forums.php?action=admin&subaction=lock&topic_id=$_GET[topic_id]&forum_id=$_GET[forum_id]>";
            if ($locked == 1){ $lockForm .= $this->textArray['Unlock'] . "</a>"; }
            else { $lockForm .= $this->textArray['Lock'] . "</a>"; }

            require("includes/formscript.inc.php");
            if ($this->noShow != "1" && $comment_id != 0){
                if ($adminFun == 1){

                    $text .= "<tr><td><table border=0 cellpadding=1 cellspacing=1 width=100% class=outline>";
                    $text .= "<tr><td class=medtitle colspan=2>" . $this->textArray['Admin Functions'] . "</td></tr>";
                    $text .= "<tr><td class=main>$lockForm</td></tr>";
                    $text .= "</table></td></tr>";
                }
            }


            else if ($this->noShow != "1"){
                $text .= "<tr>";
                $text .= "<td class=main-large colspan=2><a name=top><a href=forums.php>$siteName " . $this->textArray['Forums'] . "</a> -> <a href=forums.php?cat_id=$cat_id>$cat_name</a> -> <a href=forums.php?forum_id=$_GET[forum_id]>$forum_name</a> -> $topic_name </td></tr>";
                $text .= "<tr><td><table border=0 cellpadding=1 cellspacing=0 width=100%>";
                $text .= "<tr><td class=small width=33%>";
                if ($prev_topic != ''){ $text .= "<a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$prev_topic>"; }
                $text .= "<img src=images/prev_arrow.gif alt='" . $this->textArray['Previous'] . " " . $this->textArray['Topic'] . "' border=0>" . $this->textArray['Previous'] . " " . $this->textArray['Topic'] . "";
                if ($prev_topic != ''){ $text .= "</a>"; }
                $text .= " - ";
                if ($next_topic != ''){ $text .= "<a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$next_topic>"; }
                $text .= "" . $this->textArray['Next'] . " " . $this->textArray['Topic'] . " <img src=images/next_arrow.gif border=0 alt='" . $this->textArray['Next'] . " " . $this->textArray['Topic'] . "'>";
                if ($next_topic != ''){ $text .= "</a>"; }
                $text .= "</td>";
                $pm = $this->newPM();
                $text .= "<td class=small align=center width=33%>$pm</td>";

                $text .= "<td class=small align=right width=33%>";
                if ($posts > $posts_per_page){
                    $postTab = $this->core->forumPostTab($limit, $posts, "forums.php?forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]", $posts_per_page);
                    $text .= $postTab;
                }
                $text .= "</td></tr></table></td></tr>";

                if ($adminFun == 1){
                    $text .= "<tr><td class=main colspan=2><table border=0 cellpadding=0 cellspacing=1 class=outline width=100%>";
                    $text .= "<tr><td class=medtitle colspan=2>" . $this->textArray['Admin Functions'] . "</td></tr>";
                    $text .= "<tr><td class=main><table border=0 cellpadding=0 cellspacing=1 width=100%>";

                    $stickyForm = "<a href=forums.php?action=admin&subaction=sticky&topic_id=$_GET[topic_id]&forum_id=$_GET[forum_id]>";
                    if ($sticky == 1){ $stickyForm .= $this->textArray['Make'] . " " . $this->textArray['Un-Sticky'] . "</a>"; }
                    else { $stickyForm .= $this->textArray['Make'] . " " . $this->textArray['Sticky'] . "</a>"; }

                    $moveForm = "<form method=post action=forums.php?action=admin&subaction=move&topic_id=$_GET[topic_id]><select name=forum_id><option value=''></option>";
                    $moveResult = mysql_query("select forum_id, name from forums_forums where forum_id != '$_GET[forum_id]' order by cat_id, ord");
                    while(list($f_id, $f_name) = mysql_fetch_row($moveResult)){
                        $moveForm .= "<option value=$f_id>$f_name</option>";
                    }
                    $moveForm .= "</select> <input class=submit type=submit value='" . $this->textArray['Move'] . "'></form>";



                    $text .= "<tr><td class=main>&nbsp;$lockForm | $stickyForm</td><td class=main align=right>$moveForm</td></tr>";
                    $text .= "</table></td></tr></table><br></td></tr>";
                }
            }
            $text .= "</table><br>";
            if ($comment_id != 0){
                $text .= $commentHeader;
            }



            if ($forum_poll_id != "0"){ $text .= $this->viewForumPoll($forum_poll_id, $locked); }
            $text .= "<table border=0 cellpadding=2 cellspacing=1 align=center width=95% class=outline>";
            $text .= "<tr><td class=medtitle nowrap=nowrap>" . $this->textArray['Author'] . "</td><td class=medtitle>";
            $text .= "<table border=0 cellpadding=0 cellspacing=0 border=0 width=100%><tr><td class=medtitle>" . $this->textArray['Post'] . "</td>";

            $text .= "<td class=medtitle align=right>";
            if ($admin == 1 && $this->noShow != 1){
                if ($comment_id == "0"){ $text .= "<span onMouseOver=\"style.cursor='pointer'; MM_swapImage('addTopic', '', 'images/add_topic_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=post&forum_id=$_GET[forum_id]')><img src=images/add_topic.gif name=addTopic alt='" . $this->textArray['Add Topic'] . "' border=0 > " . $this->textArray['Add Topic'] . "</span>"; }
                if ($locked != 1){ $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('reply', '', 'images/add_reply_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=post&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&post_id=777555xxx777')><img src=images/add_reply.gif name=reply alt='" . $this->textArray['Reply'] . "' border=0> " . $this->textArray['Post'] . " " . $this->textArray['Reply'] . "</span>"; }
                else { $text .= "<img src=images/lock.gif alt='" . $this->textArray['Topic'] . " " . $this->textArray['Locked'] . "' border=0>" . $this->textArray['Topic'] . " " . $this->textArray['Locked'] . ""; }
            }
            $text .= "</td>";
            $text .= "</tr>";
            $text .= "</table></td></tr>";
            $ignoreList = array();
            if (isset($_COOKIE[PXL])){

                $result = mysql_query("select ignore_user_id from forums_ignore where user_id = '$_COOKIE[PXL]'");
                while(list($ignore_id) = mysql_fetch_row($result)){
                    $ignoreList[] = "$ignore_id";
                }
            }
            $allow_pm = $this->core->forumCall("allow_pm");
            $allow_flags = $this->core->forumCall("allow_flags");
            $allow_imood = $this->core->forumCall("allow_imood");
            $result = mysql_query("select p.post_id, u.user_id, u.username, u.regdate, u.sig, u.attach_sig, u.rank, u.posts, u.imood, u.av_id, u.remote_avatar, u.website, u.loc, u.view_online, p.title, p.post, p.date, p.ip_addr, t.post_id, u.flag_id, u.imood from forums_posts p, users u, forums_topics t where u.user_id = p.user_id and p.topic_id = '$_GET[topic_id]' and t.topic_id = p.topic_id order by p.post_id asc limit $limit, $posts_per_page") or die(mysql_error());
            $i=1;
            $x=1;
            while(list($post_id, $user_id, $username, $regdate, $sig, $attach_sig, $rank, $posts1, $imood, $av_id, $remote_avatar, $website, $loc, $view_online, $title, $post, $post_date, $ip_addr, $topic_post, $flag_id, $imood) = mysql_fetch_row($result)){
                $editCheck = 0;
                $p_id=$post_id;
                if ($i == 1){ $p1_id=$post_id; }
                if ($x == 1){ $class="main"; $bold = "mainbold"; $x++; }
                else { $class="main-alt"; $bold = "mainbold-alt"; $x=1; }

                $online=0;

                $title = $this->text->convertText($title, 2);
                $post = $this->text->convertText($post, 2);
                $post_date = $this->core->getTime($post_date);
                $post_date = date($dateFormat . " H:i", $post_date);
                $regdate = $this->core->getTime($regdate);
                $regdate = date($dateFormat, $regdate);
                if ($loc != ''){ $loc = $this->text->convertText($loc, 2) . "<br>"; }
                if ($attach_sig == 1 && $sig != ''){ $sig = "<hr width=250 align=left>" . $this->text->convertText($sig, 2); }
                if ($imood != ''){ $imood = $this->textArray['I\'m Feeling'] . "...<br><img src=http://www.imood.com/query.cgi?email=" . $imood . "&type=1&trans=1 alt=Imood border=0>"; }
                if ($rank == '' || $rank == 0){ list($rank) = mysql_fetch_row(mysql_query("select rank_name from forums_ranks where rank_min < '$posts1' and rank_spec != '1' order by rank_min desc limit 0,1")); }
                else { list($rank) = mysql_fetch_row(mysql_query("select rank_name from forums_ranks where rank_id = '$rank'")); }
                $avatar = '';
                if ($av_id != 0){
                    list($file) = mysql_fetch_row(mysql_query("select file from forums_avatars where av_id = '$av_id'"));
                    $avatar = "<img src=images/avatars/$file alt='" . $this->textArray['Avatar'] . "' width=45 height=45 border=0>";
                }
                else if ($remote_avatar != ''){ $avatar = "<img src=$remove_avatar alt='" . $this->textArray['Avatar'] . "' width=45 height=45 border=0>"; }
                $online = "<img src=images/user_offline.gif alt='" . ucfirst($this->textArray['user']) . " " . $this->textArray['Offline'] . "' border=0> ";
                if ($view_online == 1){
                    list($last) = mysql_fetch_row(mysql_query("select last from forums_session where user_id = '$user_id'"));
                    $time = $this->core->getTime(time(), 1) - 300;


                    if ($last > $time){ $online = "<img src=images/user_online.gif alt='" . ucfirst($this->textArray['user']) . " " . $this->textArray['Online'] . "' border=0> "; }
                }
                if ($website != ''){ $website = " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('website" . $i . "', '', 'images/website_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURLNew('$website')><img src=images/website.gif name=website" . $i . " alt='" . $this->textArray['Website'] . "' border=0></span>"; }


                $text .= "<tr><td class=$class valign=top nowrap=nowrap><a name=#$post_id><b>$username</b>";
                if (!in_array($user_id, $ignoreList)){
                    if ($user_id != '1'){ $text .= "<span class=small><br>$rank<br>$avatar<br>" . $this->textArray['Joined'] . ": $regdate<br>$loc"  . ucfirst($this->textArray['posts']) . ": $posts1</span>"; }
                    if ($allow_imood == 1 && $imood != ''){ $text .= "<span class=small><br><br>" . $imood . "</span>"; }
                    if ($allow_flags == 1 && $flag_id != '' && $flag_id != 0 && $flag_id != "1"){
                        list($flag_name, $flag_file) = mysql_fetch_row(mysql_query("select flag_name, flag_file from forums_flags where flag_id = '$flag_id'"));
                        $text .= "<p align=center><img src=images/flags/$flag_file alt='$flag_name' border=0></p>";
                    }
                    $text .= "<br><br><br>";
                    if ($locked != 1 && $admin == 1){ $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('quote" . $i . "', '', 'images/quote_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=post&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&quote_id=$post_id')><img src=images/quote.gif name=quote" . $i . " alt='" . $this->textArray['Quote'] . "' border=0>" . $this->textArray['Quote'] . "</span><br>"; }

                    if ($adminFun == 0 && $allow_edit == 1){ if ($user_id == $_COOKIE[PXL]){ $editCheck = 1; } }
                    if ($editCheck == 1 || $adminFun == 1){ $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('edit" . $i . "', '', 'images/edit_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=edit&post_id=$post_id&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]')><img src=images/edit.gif name=edit" . $i . " alt='" . $this->textArray['Edit'] . " " . $this->textArray['Post'] . "' border=0>" . $this->textArray['Edit'] . " " . $this->textArray['Post'] . "</span>"; }




                    $text .= "</td><td class=$class valign=top width=* style=\"word-wrap: break-word\"><span class=small>Posted: $post_date Subject: $title</span><br><hr>$post<br>$sig";
                    $text .= "</td></tr>";
                    $text .= "<tr><td class=$bold><a href=#top>" . $this->textArray['Back to Top'] . "</td>";
                    $text .= "<td class=$class><table border=0 cellpadding=0 cellspacing=0 width=100%><tr>";
                    $text .= "<td class=$class>";
                    if ($user_id != '1'){
                        $text .= "$online $website ";
                        if ($_COOKIE[PXL] != $user_id){ $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('email" . $i . "', '', 'images/email_user_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('users.php?action=email&user_id=$user_id')><img src=images/email_user.gif name=email" . $i . " alt='" . $this->textArray['Email'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>"; }
                        if ($_COOKIE[PXL] != $user_id && $allow_pm == 1){ $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('pm" . $i . "', '', 'images/pm_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=pm&user_id=$user_id')><img src=images/pm.gif name=pm" . $i . " alt='" . $this->textArray['PM'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>"; }
                        $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('profile" . $i . "', '', 'images/user_profile_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('users.php?action=view&user_id=$user_id')><img src=images/user_profile.gif name=profile" . $i . " alt='" . $this->textArray['User Profile'] . "' border=0></span>";
                        if ($_COOKIE[PXL] != $user_id){ $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('ignore" . $i . "', '', 'images/ignore_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=confirmIgnore('forums.php?action=ignore&ignore_id=$user_id&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&post_id=$post_id#$post_id')><img src=images/ignore.gif name=ignore" . $i . " alt='" . $this->textArray['Ignore'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>"; }

                    }
                    if ($adminFun == 1){
                        $ip = $ip_addr;
                        require("includes/formscript.inc.php");
                        $reportMod = "<a href=javascript:confirmDelete('forums.php?action=admin&subaction=delete&post_id=$post_id')>";
                        if ($post_id == $topic_post){ $reportMod .= "Delete Topic</a>"; }
                        else { $reportMod .= "Delete Post</a>"; }
                    }
                    else {
                        $ip = "Logged";
                        $reportMod = "<div class=attn id=$post_id style=\"display:none\">" . $this->textArray['Post Reported'] . "</div><a href=javascript:reportPost('$post_id')>Report Post to Moderator</a>";
                    }

                    $text .= "</td><td class=$class align=right><span class=small> $reportMod | IP: $ip</span></td>";
                }
                else {
                    $text .= "</td><td class=$class valign=top width=* style=\"word-wrap: break-word\">" . ucfirst($this->textArray['user']) . " " . $this->textArray['Ignored'];
                    $text .= "</td></tr>";
                    $text .= "<tr><td class=$bold><a href=#top>" . $this->textArray['Back to Top'] . "</td>";
                    $text .= "<td class=$class><table border=0 cellpadding=0 cellspacing=0 width=100%><tr>";
                    $text .= "<td class=$class>";
                    $text .= "</td><td class=$class align=right></td>";
                }
                $text .= "</td></tr></table></td></tr>";
                $text .= "<tr><td class=subtitle colspan=2 height=5></td></tr>";
                $i++;
            }

            $text .= "<td class=medtitle align=right colspan=2>";
            if ($admin == 1 && $this->noShow != 1){
                $text = str_replace("&post_id=777555xxx777", "&post_id=$p1_id", $text);
                if ($comment_id == "0"){ $text .= "<span onMouseOver=\"style.cursor='pointer'; MM_swapImage('addTopic1', '', 'images/add_topic_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=post&forum_id=$_GET[forum_id]')><img src=images/add_topic.gif name=addTopic1 alt='" . $this->textArray['Add Topic'] . "' border=0 > " . $this->textArray['Add Topic'] . "</span>"; }
                if ($locked != 1){ $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('reply1', '', 'images/add_reply_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=post&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&post_id=$p_id')><img src=images/add_reply.gif name=reply1 alt='" . $this->textArray['Reply'] . "' border=0> " . $this->textArray['Post'] . " " . $this->textArray['Reply'] . "</span>"; }
                else { $text .= "<img src=images/lock.gif alt='" . $this->textArray['Topic'] . " " . $this->textArray['Locked'] . "' border=0>" . $this->textArray['Topic'] . " " . $this->textArray['Locked'] . ""; }
            }
            $text .= "</td></tr>";

            $text .= "</table>";
            if ($this->noShow != 1){
                $text .= "<table border=0 cellpadding=0 cellspacing=1 width=95% class=main align=center>";
                $text .= "<tr><td class=small>";
                if ($prev_topic != ''){ $text .= "<a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$prev_topic>"; }
                $text .= "<img src=images/prev_arrow.gif alt='" . $this->textArray['Previous'] . " " . $this->textArray['Topic'] . "' border=0>" . $this->textArray['Previous'] . " " . $this->textArray['Topic'] . "";
                if ($prev_topic != ''){ $text .= "</a>"; }
                $text .= " - ";
                if ($next_topic != ''){ $text .= "<a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$next_topic>"; }
                $text .= "" . $this->textArray['Next'] . " " . $this->textArray['Topic'] . " <img src=images/next_arrow.gif border=0 alt='" . $this->textArray['Next'] . " " . $this->textArray['Topic'] . "'>";
                if ($next_topic != ''){ $text .= "</a>"; }
                $text .= "</td>";
                $text .= "<td class=small align=right>";
                if ($posts > $posts_per_page){
                    $postTab = $this->core->forumPostTab($limit, $posts, "forums.php?forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]", $posts_per_page);
                    $text .= $postTab;
                }
                $text .= "</td></tr>";
                if (isset($_COOKIE['PXL'])){
                    list($count) = mysql_fetch_row(mysql_query("select count(forum_notify_id) from forums_notify where user_id = '$_COOKIE[PXL]' and topic_id = '$_GET[topic_id]'"));
                    if ($count == 0){ $text .= "<tr><td class=main><div class=attn id=monitor style=\"display:none\">" . $this->textArray['Monitoring'] . " " . $this->textArray['Topic'] . "</div><div class=small id=remove><a href=forums.php?action=monitort&topic_id=$_GET[topic_id] onclick=\"displaySubs('monitor'); displaySubs1('remove')\">" . $this->textArray['Monitor'] . " " . $this->textArray['Topic'] . "</a></div></td></tr>"; }
                    else { $text .= "<tr><td class=main><div class=attn id=monitor>" . $this->textArray['Monitoring'] . " " . $this->textArray['Topic'] . "</div></td></tr>"; }
                }
                //$text .= "</tr>";
                $text .= "</table><p>";
                $text .= $this->subBox();
            }

            $this->body = $text;
        }

    }

    function viewForum(){

        $c = $this->security->forumSecure($_GET['forum_id'], 0, 1);
        $admin = $this->security->forumSecure($_GET['forum_id'], 1, 0);

        if ($c == 2){ $this->body = $this->core->login(0, "forums.php?forum_id=$_GET[forum_id]"); }
        else {

            $topics_per_page = $this->core->forumCall("topics_per_page");
            $posts_per_page = $this->core->forumCall("posts_per_page");
            $siteName = $this->core->dbCall("siteName");
            $dateFormat = $this->core->dbCall("dateFormat");

            if (isset($_GET['limit']) && $_GET['limit'] != ''){ $limit = $_GET['limit']; }
            else { $limit = 0; }

            list($cat_name, $cat_id, $forum_name, $topics, $comment_type) = mysql_fetch_row(mysql_query("select c.name, c.cat_id, f.name, f.topics, f.comment_type from forums_cat c, forums_forums f where f.forum_id = '$_GET[forum_id]' and f.cat_id = c.cat_id"));
            if ($comment_type != '0'){ header("Location: error.php?error=403"); }
            $cat_name = $this->text->convertText($cat_name, 2);
            $forum_name = $this->text->convertText($forum_name, 2);

            list($num_of_sticky) = mysql_fetch_row(mysql_query("select count(topic_id) from forums_topics where sticky = '1' and forum_id = '$_GET[forum_id]'"));
            if ($num_of_sticky < $limit){
                $limit1 = $limit - $num_of_sticky;
                $result = mysql_query("select t.topic_id, p.title, p.date, u.user_id, u.username, t.reads, t.last_post_id, t.posts, t.locked, t.sticky, u2.user_id, u2.username, p2.date, t.forum_poll_id from forums_topics t, forums_posts p, users u, users u2, forums_posts p2 where t.forum_id = '$_GET[forum_id]' and p.post_id = t.post_id and p.user_id = u.user_id and t.sticky != '1' and t.last_post_id = p2.post_id and p2.user_id = u2.user_id and t.forum_poll_id != '-1' order by p2.date desc limit $limit1, $topics_per_page") or die(mysql_error());
            }
            else {
                $checkFlag=1;
                $result = mysql_query("select t.topic_id, p.title, p.date, u.user_id, u.username, t.reads, t.last_post_id, t.posts, t.locked, t.sticky, u2.user_id, u2.username, p2.date, t.forum_poll_id from forums_topics t, forums_posts p, users u, users u2, forums_posts p2 where t.forum_id = '$_GET[forum_id]' and p.post_id = t.post_id and p.user_id = u.user_id and t.sticky = '1' and t.last_post_id = p2.post_id and p2.user_id = u2.user_id and t.forum_poll_id != '-1' order by p2.date desc limit $limit, $topics_per_page") or die(mysql_error());
                $rows = mysql_num_rows($result);

            }

            list($temp) = mysql_fetch_row(mysql_query("select templast from forums_session where sess_id = '$_COOKIE[PXLF]'"));

            $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=main align=center>";
            $text .= "<script language=javascript>";
            $text .= "MM_preloadImages('images/add_topic_over.gif');";
            $text .= "</script>";
            $text .= "<tr>";
            $text .= "<td class=main-large><a href=forums.php>$siteName " . $this->textArray['Forums'] . "</a> -> <a href=forums.php?cat_id=$cat_id>$cat_name</a> -> $forum_name </td>";
            $text .= "<td valign=middle class=main-large align=right>";
            if ($admin == 1){ $text .= "<span onMouseOver=\"style.cursor='pointer'; MM_swapImage('addTopic', '', 'images/add_topic_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=post&forum_id=$_GET[forum_id]')><img src=images/add_topic.gif name=addTopic alt='" . $this->textArray['Add Topic'] . "' border=0 ><b> " . $this->textArray['Add Topic'] . "</b></span>"; }
            $text .= "</td></tr>";

            $pm = $this->newPM();
            $text .= "<tr><td class=small>$pm</td></tr>";
            $text .= "</table><table border=0 cellpadding=2 cellspacing=1 align=center width=95% class=outline>";
            $text .= "<tr><td class=medtitle colspan=2>" . $this->textArray['Topics'] . "</td><td class=medtitle align=center>" . $this->textArray['Replies'] . "</td>";
            $text .= "<td class=medtitle align=center>" . $this->textArray['Reads'] . "</td><td class=medtitle align=center>" . $this->textArray['Author'] . "</td><td class=medtitle>" . $this->textArray['Last Post'] . "</td></tr>";
            $more = $this->core->pageTab($limit, $topics, "forums.php?forum_id=$_GET[forum_id]", $topics_per_page);
            $text .= "<tr><td class=main colspan=6>$more</td></tr>";

            $x=1;
            while(list($topic_id, $title, $date, $user_id, $username, $reads, $last_post_id, $posts, $locked, $sticky, $last_user_id, $last_username, $last, $poll_id) = mysql_fetch_row($result)){
                $more2='';
                if ($posts > $posts_per_page){
                    $more2 = " <font size=-2> Page :";
                    $b=1;
                    for($xx=0; $xx < $posts; $xx += $posts_per_page){
                        $more2 .= " <a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$topic_id&limit=$xx>$b</a> ";
                        $b++;
                    }
                    $more2 .= "</font>";
                }
                $posts--;

                $last_date = $this->core->getTime($last);
                $last_post = "<div class=small>" . date($dateFormat . " H:i", $last_date) . "<br>";
                $last_post .= $this->textArray['by'] . " <a href=users.php?action=view&user_id=$last_user_id>$last_username</a>";
                $last_post .= " <a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$topic_id&post_id=$last_post_id><img src=images/view_last_post.gif width=16 height=16 alt='" . $this->textArray['View'] . " " . $this->textArray['Last Post'] . "' border=0></a></div>";
                if ($poll_id != 0){ $poll = "<img src=images/survey.gif border=0 alt='" . $this->textArray['Survey'] . "'>"; }
                else { $poll = ''; }

                $title = $this->text->convertText($title, 2);
                if ($x == 1){ $class = "main"; $x++; }
                else { $class = "main-alt"; $x=1; }
                $icon = $this->findIcon($sticky, $locked, $posts, $temp, $last);
                $text .= "<tr><td class=$class>$icon</td><td class=$class>$poll<a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$topic_id>$title</a> $more2</td>";
                $text .= "<td class=$class align=center>$posts</td><td class=$class align=center>$reads</td>";
                $text .= "<td class=$class align=center><a href=users.php?action=view&user_id=$user_id>$username</a></td>";
                $text .= "<td class=$class>$last_post</td></tr>";
            }
            if ($rows < $topics_per_page && isset($checkFlag)){

                $num = $topics_per_page - $rows;
                $result = mysql_query("select t.topic_id, p.title, p.date, u.user_id, u.username, t.reads, t.last_post_id, t.posts, t.locked, t.sticky, u2.user_id, u2.username, p2.date, t.forum_poll_id from forums_topics t, forums_posts p, users u, users u2, forums_posts p2 where t.forum_id = '$_GET[forum_id]' and p.post_id = t.post_id and p.user_id = u.user_id and t.sticky != '1' and t.last_post_id = p2.post_id and p2.user_id = u2.user_id and t.forum_poll_id != '-1' order by p2.date desc limit 0, $num") or die(mysql_error());
                while(list($topic_id, $title, $date, $user_id, $username, $reads, $last_post_id, $posts, $locked, $sticky, $last_user_id, $last_username, $last, $poll_id) = mysql_fetch_row($result)){
                    $more2='';
                    if ($posts > $posts_per_page){
                        $more2 = " <font size=-2> Page :";
                        $b=1;
                        for($xx=0; $xx < $posts; $xx += $posts_per_page){
                            $more2 .= " <a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$topic_id&limit=$xx>$b</a> ";
                            $b++;
                        }
                        $more2 .= "</font>";
                    }
                    $posts--;
                    $last_date = $this->core->getTime($last);
                    $last_post = "<div class=small>" . date($dateFormat . " H:i", $last_date) . "<br>";
                    $last_post .= $this->textArray['by'] . " <a href=users.php?action=view&user_id=$last_user_id>$last_username</a>";
                    $last_post .= " <a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$topic_id&post_id=$last_post_id><img src=images/view_last_post.gif width=16 height=16 alt='" . $this->textArray['View'] . " " . $this->textArray['Last Post'] . "' border=0></a></div>";

                    if ($poll_id != 0){ $poll = "<img src=images/survey.gif border=0 alt='" . $this->textArray['Survey'] . "'>"; }
                    else { $poll = ''; }

                    $title = $this->text->convertText($title, 2);
                    if ($x == 1){ $class = "main"; $x++; }
                    else { $class = "main-alt"; $x=1; }

                    $icon = $this->findIcon($sticky, $locked, $posts, $temp, $last);

                    $text .= "<tr><td class=$class>$icon</td><td class=$class>$poll<a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$topic_id>$title</a> $more2</td>";
                    $text .= "<td class=$class align=center>$posts</td><td class=$class align=center>$reads</td>";
                    $text .= "<td class=$class align=center><a href=users.php?action=view&user_id=$user_id>$username</a></td>";
                    $text .= "<td class=$class>$last_post</td></tr>";
                }
            }
            $text .= "<tr><td class=main colspan=6>$more</td></tr>";
            $text .= "<tr><td valign=middle class=medtitle align=right colspan=6>";
            if ($admin == 1){ $text .= "<span onMouseOver=\"style.cursor='pointer'; MM_swapImage('addTopic1', '', 'images/add_topic_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=post&forum_id=$_GET[forum_id]')><img src=images/add_topic.gif name=addTopic1 alt='" . $this->textArray['Add Topic'] . "' border=0 ><b> " . $this->textArray['Add Topic'] . "</b></span>"; }
            $text .= "</td></tr>";
            if (isset($_COOKIE['PXL'])){
                list($count) = mysql_fetch_row(mysql_query("select count(forum_notify_id) from forums_notify where user_id = '$_COOKIE[PXL]' and forum_id = '$_GET[forum_id]'"));
                if ($count == 0 ){ $text .= "<tr><td class=main colspan=6><div class=attn id=monitor style=\"display:none\">" . $this->textArray['Monitoring'] . " " . $this->textArray['Forum'] . "</div><div class=small id=remove><a href=forums.php?action=monitor&forum_id=$_GET[forum_id] onclick=\"displaySubs('monitor'); displaySubs1('remove')\">" . $this->textArray['Monitor'] . " " . $this->textArray['Forum'] . "</a></div></td></tr>"; }
                else {  $text .= "<tr><td class=main colspan=6><div class=attn id=monitor>" . $this->textArray['Monitoring'] . " " . $this->textArray['Forum'] . "</div></td></tr>"; }
            }
            $text .= "</table><p>";
            $text .= $this->subBox();
            $this->body = $text;
        }
    }

    function newPM(){
        $allow_pm = $this->core->forumCall("allow_pm");

        if (isset($_COOKIE[PXL]) && !isset($_COOKIE[DISMISS]) && $allow_pm == 1){
            list($count) = mysql_fetch_row(mysql_query("select count(pm_id) from forums_pm where user_id = '$_COOKIE[PXL]' and view = '0' and deleted = '0'"));
            if ($count != 0){

                $text = "<div class=small id=pm style=\"display:none\"></div>";
                $text .= "<div class=small id=pm1><img src=images/pm3.gif border=0 alt=" . $this->textArray['New'] . "> " . $this->textArray['You have'] . " $count " . $this->textArray['unread private messages'] . ". &nbsp;<a href=forums.php?action=pm&subaction=inbox>" . $this->textArray['View'] . "</a> | <a href=forums.php?action=dismiss onclick=\"displaySubs('pm'); displaySubs1('pm1')\">" . $this->textArray['Dismiss'] . "</a>";
            }
        }
        return $text;
    }
}

class adminForums {

    function adminForums($security){
        $action = $_GET['subaction'];
        $adminCheck = $security->forumAdmin($_GET['forum_id']);
        if ($adminCheck != 1){ header("Location: error.php?error=403"); }

        switch($action){
            case delete:
                $this->deletePost();
                break;
            case move:
                $this->moveTopic();
                break;
            case sticky:
                $this->stickyTopic();
                break;
            case lock:
                $this->lockTopic();
                break;
        }
    }

    function deletePost(){
        $post_id = $_GET['post_id'];
        list($forum_id, $topic_id, $user_id) = mysql_fetch_row(mysql_query("select forum_id, topic_id, user_id from forums_posts where post_id = '$post_id'"));
        list($check) = mysql_fetch_row(mysql_query("select count(topic_id) from forums_topics where topic_id = '$topic_id' and post_id = '$post_id'"));
        list($comment_type) = mysql_fetch_row(mysql_query("select comment_type from forums_forums where forum_id = '$forum_id'"));
        if ($check == 1){
            list($count) = mysql_fetch_row(mysql_query("select count(post_id) from forums_posts where topic_id = '$topic_id'"));
            if ($count != 1 && $comment_type != 0){
                list($new_post_id) = mysql_fetch_row(mysql_query("select post_id from forums_posts where topic_id = '$topic_id' order by post_id asc"));
                mysql_query("update forums_topics set posts = posts - 1, post_id = '$new_post_id' where topic_id = '$topic_id'");
                mysql_query("update forums_forums set posts = posts - 1 where forum_id = '$forum_id'");
                mysql_query("update users set posts = posts - 1 where user_id = '$user_id'");
                mysql_query("delete from forums_posts where post_id = '$post_id'");
                $url = "forums.php?forum_id=$forum_id&topic_id=$topic_id";
            }
            else {
                mysql_query("update forums_forums set topics = topics - 1, posts = posts - $count where forum_id = '$forum_id'");
                list($poll_id) = mysql_fetch_row(mysql_query("select forum_poll_id from forums_topics where topic_id = '$topic_id'"));
                if ($poll_id != 0){
                    mysql_query("delete from forums_polls where forum_poll_id = '$poll_id'");
                    mysql_query("delete from forums_poll_results where forum_poll_id = '$poll_id'");
                    mysql_query("delete from forums_polls_users where forum_poll_id = '$poll_id'");
                }
                $result = mysql_query("select user_id from forums_posts where topic_id = '$topic_id'");
                while(list($users) = mysql_fetch_row($result)){
                    mysql_query("update users set posts = posts - 1 where user_id = '$users'");
                }
                mysql_query("delete from forums_posts where topic_id = '$topic_id'");
                mysql_query("delete from forums_topics where topic_id = '$topic_id'");
                mysql_query("delete from forums_notify where topic_id = '$topic_id'");
                $url = "forums.php?forum_id=$forum_id";
            }
        }
        else {
            mysql_query("update users set posts = posts - 1 where user_id = '$user_id'");
            mysql_query("delete from forums_posts where post_id = '$post_id'");
            list($last_post) = mysql_fetch_row(mysql_query("select post_id from forums_posts where topic_id = '$topic_id' order by post_id desc limit 0,1"));
            mysql_query("update forums_topics set posts = posts - 1, last_post_id = '$last_post' where topic_id = '$topic_id'");
            mysql_query("update forums_forums set posts = posts - 1 where forum_id = '$forum_id'");
            $url = "forums.php?forum_id=$forum_id&topic_id=$topic_id";
        }
        list($last_post) = mysql_fetch_row(mysql_query("select post_id from forums_posts where forum_id = '$forum_id' order by post_id desc limit 0,1"));
        mysql_query("update forums_forums set post_id = '$last_post' where forum_id = '$forum_id'");
        header("Location: $url");
    }

    function moveTopic(){
        $topic_id = $_GET['topic_id'];
        $forum_id = $_POST['forum_id'];
        list($old) = mysql_fetch_row(mysql_query("select forum_id from forums_topics where topic_id = '$topic_id'"));
        mysql_query("update forums_posts set forum_id = '$forum_id' where topic_id = '$topic_id'");
        mysql_query("update forums_topics set forum_id = '$forum_id' where topic_id = '$topic_id'");
        list($count) = mysql_fetch_row(mysql_query("select count(post_id) from forums_posts where topic_id = '$topic_id'"));
        list($post_id) = mysql_fetch_row(mysql_query("select post_id from forums_posts where forum_id = '$old' order by post_id desc limit 0,1"));
        list($new) = mysql_fetch_row(mysql_query("select post_id from forums_posts where forum_id = '$forum_id' order by post_id desc limit 0,1"));
        mysql_query("update forums_forums set topics = topics - 1, posts = posts - $count, post_id = '$post_id' where forum_id = '$old'");
        mysql_query("update forums_forums set topics = topics + 1, posts = posts + $count, post_id = '$new' where forum_id = '$forum_id'");
        header("Location: forums.php?forum_id=$_POST[forum_id]&topic_id=$_GET[topic_id]");
    }

    function stickyTopic(){
        list($sticky) = mysql_fetch_row(mysql_query("select sticky from forums_topics where topic_id = '$_GET[topic_id]'"));
        if ($sticky == 1){ mysql_query("update forums_topics set sticky = '0' where topic_id = '$_GET[topic_id]'"); }
        else { mysql_query("update forums_topics set sticky = '1' where topic_id = '$_GET[topic_id]'"); }
        header("Location: forums.php?forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&limit=$_GET[limit]");
    }

    function lockTopic(){
        list($sticky) = mysql_fetch_row(mysql_query("select locked from forums_topics where topic_id = '$_GET[topic_id]'"));
        if ($sticky == 1){ mysql_query("update forums_topics set locked = '0' where topic_id = '$_GET[topic_id]'"); }
        else { mysql_query("update forums_topics set locked = '1' where topic_id = '$_GET[topic_id]'"); }
        header("Location: forums.php?forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&limit=$_GET[limit]");
    }

}

class forumPost {

    var $templates;
    var $textArray;


    function createPost(){
        $secure = $this->security->forumSecure($_GET['forum_id'], 1, 1);
        if ($secure == 2){ $text = $this->core->login(0, "forums.php?action=post&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&comment_id=$_GET[comment_id]"); }
        else {
            list($allow_html, $allow_xcode, $allow_smiles, $allow_polls, $image_tags) = mysql_fetch_row(mysql_query("select allow_html, allow_xcode, allow_smiles, polls, image_tags from forums_config")) or die(mysql_error());

            if ($_POST['confirm'] == 1){
                if (isset($_POST['preview'])){
                    $_POST['subject'] = stripslashes($_POST['subject']);
                    $_POST['post'] = stripslashes($_POST['post']);
                    $subject = stripslashes($this->text->convertText($_POST['subject'], 1, 0));
                    $post = stripslashes($this->text->convertText($_POST['post'], 1, 1));
                    $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
                    $text .= "<tr><td class=medtitle>" . $this->textArray['Preview'] . " " . $this->textArray['Post'] . "</td></tr>";
                    $text .= "<td class=main valign=top style=\"word-wrap: break-word\"><span class=small>Subject: $subject</span><br><hr>$post</td></tr>";
                    $text .= "</table><p>";

                    $_POST['confirm'] = 0;
                    $this->createPost();
                    $this->body = $text . $this->body;
                }
                else {
                    if (!isset($_COOKIE[PXL])){ $user_id = 1; }
                    else { $user_id = $_COOKIE[PXL]; }
                    $subject = $this->text->convertText($_POST['subject'], 1, 0);
                    $post = $this->text->convertText($_POST['post'], 1, 1);
                    $date = $this->core->getTime(time(), 1);

                    $ip = $_SERVER["REMOTE_ADDR"];
                    if ($_GET['topic_id'] == ''){
                        if ($_POST['poll'] != 1){ $poll = 0; }
                        else { $poll = "-1"; }
                        mysql_query("update forums_forums set topics = topics + 1 where forum_id = '$_GET[forum_id]'");
                        mysql_query("insert into forums_topics values ('', '$_GET[forum_id]', '', '$poll', '0', '0', '0', '$date', '0', '', '$_POST[comment_id]')");
                        $topic_id = mysql_insert_id();
                        $reply=0;
                    }
                    else {
                        $topic_id = $_GET['topic_id'];
                        $reply=1;
                    }
                    if ($subject == ''){
                        list($subject) = mysql_fetch_row(mysql_query("select p.title from forums_posts p, forums_topics t where t.post_id = p.post_id and t.topic_id = '$topic_id'"));
                        $subject = "RE: " . $subject;
                    }
                    mysql_query("insert into forums_posts values ('', '$topic_id', '$_GET[forum_id]', '$user_id', '$subject', '$post', '$date', '$ip')");
                    $post_id = mysql_insert_id();
                    if ($_GET['topic_id'] == ''){
                        mysql_query("update forums_topics set post_id = '$post_id', last_post_id = '$post_id' where topic_id = '$topic_id'");
                    }
                    if ($user_id != 1 && $_POST['notify'] == 1){
                        list($count) = mysql_fetch_row(mysql_query("select count(forum_notify_id) from forums_notify where user_id = '$user_id' and topic_id = '$topic_id'"));
                        if ($count == 0){mysql_query("insert into forums_notify (user_id, topic_id) values ('$user_id', '$topic_id')"); }
                    }
                    mysql_query("update forums_forums set posts = posts + 1, post_id = '$post_id' where forum_id = '$_GET[forum_id]'");
                    mysql_query("update forums_topics set posts = posts + 1, last_post_id = '$post_id' where topic_id = '$topic_id'");
                    mysql_query("update users set posts = posts + 1 where user_id = '$user_id'");
                    $this->forumNotify($_GET['forum_id'], $topic_id, $post_id, $user_id, $reply);
                    if ($_POST['poll'] != 1){ header("Location: forums.php?forum_id=$_GET[forum_id]&topic_id=$topic_id&post_id=$post_id#$post_id"); }
                    else { header("Location: forums.php?action=poll&topic_id=$topic_id"); }
                }
            }
            else {

                $siteName = $this->core->dbCall("siteName");


                if (isset($_GET['quote_id']) && $allow_xcode == 1){
                    list($quote_post, $quote_user) = mysql_fetch_row(mysql_query("select p.post, u.username from forums_posts p, users u where p.post_id = '$_GET[quote_id]' and u.user_id = p.user_id"));
                    $quote_post = $this->text->convertText($quote_post, 0, 1);
                    $_POST['post'] = "[quote][i]Reply to " . $quote_user . ":[/i]\r\n[b]" . $quote_post . "[/b][/quote]";
                }

                $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=main align=center>";
                $text .= "<tr>";
                if ($_GET[comment_id] != ''){ $allow_polls = 0; }

                list($comment_type) = mysql_fetch_row(mysql_query("select comment_type from forums_forums where forum_id = '$_GET[forum_id]'"));
                if ($comment_type != 0 && $_GET[comment_id] == '' && $_GET[topic_id] == ''){
                    header("Location: error.php?error=403");
                }

                if (isset($_GET['topic_id']) && $_GET['topic_id'] != ''){
                    list($cat_id, $cat_name, $forum_name, $topic_name) = mysql_fetch_row(mysql_query("select c.cat_id, c.name, f.name, p.title from forums_cat c, forums_forums f, forums_topics t, forums_posts p where p.post_id = t.post_id and t.topic_id = '$_GET[topic_id]' and t.forum_id = '$_GET[forum_id]' and f.forum_id = t.forum_id and f.cat_id = c.cat_id"));
                    $cat_name = $this->text->convertText($cat_name);
                    $forum_name = $this->text->convertText($forum_name);
                    $topic_name = $this->text->convertText($topic_name);
                    $text .= "<td class=main-large><a name=top><a href=forums.php>$siteName " . $this->textArray['Forums'] . "</a> -> <a href=forums.php?cat_id=$cat_id>$cat_name</a> -> <a href=forums.php?forum_id=$_GET[forum_id]>$forum_name</a> -> <a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]>$topic_name</a></td></tr>";
                    $formAction = $this->textArray['Post'] . " " . $this->textArray['Reply'];
                }
                else {
                    list($cat_id, $cat_name, $forum_name) = mysql_fetch_row(mysql_query("select c.cat_id, c.name, f.name from forums_cat c, forums_forums f where f.forum_id = '$_GET[forum_id]' and f.cat_id = c.cat_id"));
                    $cat_name = $this->text->convertText($cat_name);
                    $forum_name = $this->text->convertText($forum_name);
                    $text .= "<td class=main-large><a name=top><a href=forums.php>$siteName " . $this->textArray['Forums'] . "</a> -> <a href=forums.php?cat_id=$cat_id>$cat_name</a> -> <a href=forums.php?forum_id=$_GET[forum_id]>$forum_name</a></td></tr>";
                    $formAction = $this->textArray['Add Topic'];
                }
                $text .= "</table>";
                require("includes/formscript.inc.php");
                $text .= "<table border=0 cellpadding=3 cellspacing=1 class=outline width=95% align=center>";
                $text .= "<tr><td class=medtitle colspan=2>$formAction</td></tr>";

                $text .= "<script language=\"JavaScript\" src=\"includes/post.js\"></script>";
                $text .= "<form method=post action=forums.php?action=post&comment_id=$_GET[comment_id]&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id] name=forum onsubmit=\"return validateForm(this)\">";
                $text .= "<input type=hidden name=confirm value=1>";
                $text .= "<input type=hidden name=topic_id value=$_GET[topic_id]>";
                $text .= "<input type=hidden name=forum_id value=$_GET[forum_id]>";
                $text .= "<input type=hidden name=comment_id value=$_GET[comment_id]>";


                if ($allow_smiles == 1){ $smiles = $this->createSmileForm(); }
                $text .= "<tr><td class=mainbold>" . $this->textArray['Subject'] . ": </td><td class=main><input type=text value='$_POST[subject]' size=45 name=subject></td></tr>";
                if ($allow_xcode == 1){ $xcode = $this->createXCodeForm($image_tags); $xdesc = "<span style='font-size:9px;'><a href=forums.php?action=xcode target=new>XCode</a> " . $this->textArray['is On'] . "</span><br>"; }
                else { $xcode = ''; $xdesc = "<span style='font-size:9px;'><a href=forums.php?action=xcode>XCode</a> " . $this->textArray['is Off'] . "</span><br>"; }
                if ($allow_html == 1){ $html = "<span style='font-size:9px;'>HTML " . $this->textArray['is On'] . "</span><br>"; }
                else { $html = "<span style='font-size:9px;'>HTML " . $this->textArray['is Off'] . "</span><br>"; }
                if ($image_tags == 1){ $images = "<span style='font-size:9px;'>[img] " . $this->textArray['is On'] . "</span>"; }
                else { $images = "<span style='font-size:9px;'>[img] " . $this->textArray['is Off'] . "</span>"; }
                $text .= "<tr><td class=main-alt valign=top><b>Xcode:</b> <br>$xdesc $html $images</td><td class=main-alt>$xcode</td></tr>";

                $text .= "<tr><td class=main-alt valign=top><b>" . $this->textArray['Post'] . " :<br><br>";
                $text .= $smiles;

                $text .= "</td><td class=main-alt><textarea name=post cols=75% rows=15 wrap=virtual onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);' onFocus=setCaretAtEnd(this)>$_POST[post]</textarea></td></tr>";

                if ((!isset($_GET['topic_id']) || $_GET['topic_id'] == '') && $allow_polls == 1){
                    $text .= "<tr><td class=mainbold>" . $this->textArray['Create as Poll'] . ": </td><td class=main><input type=radio name=poll value=0 checked>" . $this->textArray['No'] . " ";
                    $text .= "<input type=radio name=poll value=1>" . $this->textArray['Yes'] . "</td></tr>";
                }
                $text .= "<tr><td class=mainbold>" . $this->textArray['Notify of Replies'] . ": </td><td class=main><input type=radio name=notify value=0>" . $this->textArray['No'] . " ";
                $text .= "<input type=radio name=notify value=1 checked>" . $this->textArray['Yes'] . "</td></tr>";
                $text .= "<tr><td class=main align=center colspan=2>";

                $text .= "<input type=submit name=submit value='" . $this->textArray['Submit'] . " " . $this->textArray['Post'] . "' class=submit> ";
                $text .= "<input type=submit name=preview value='" . $this->textArray['Preview'] . " " . $this->textArray['Post'] . "' class=submit></form></td></tr>";
                $text .= "</table>";
                $text .= "<script Language=JavaScript>";
                $text .= "function validateForm(theForm)";
                $text .= "{";
                $text .= "    if (!validRequired(theForm.post,'" . $this->textArray['Post'] . "'))";
                $text .= "        return false;";
                if (!isset($_GET['topic_id']) || $_GET['topic_id'] == ''){
                    $text .= "    if (!validRequired(theForm.subject,'" . $this->textArray['Subject'] . "'))";
                    $text .= "        return false;";
                }
                $text .= "    return true;";
                $text .= "}";
                $text .= "</script>";
                if (isset($_GET[topic_id]) && $_GET[topic_id] != ''){

                    $view = new viewForumClass();
                    $view->core = $this->core;
                    $view->security = $this->security;
                    $view->text = $this->text;
                    $view->templates = $this->templates;
                    $view->textArray = $this->textArray;
                    $view->noShow = 1;

                    $view->viewPost();
                    $text .= $view->body;

                }
                else if ($comment_type != 0){
                    $view = new viewForumClass();
                    $view->core = $this->core;
                    $view->security = $this->security;
                    $view->text = $this->text;
                    $view->templates = $this->templates;
                    $view->textArray = $this->textArray;
                    $view->noShow = 1;

                    $text .= "<br>" . $view->createTopicHeader($_GET[comment_id]);
                    //$text .= $view->body;

                }
                $this->body = $text;
            }
        }
    }

    function editPost(){
        $secure = $this->security->forumAdmin($_GET['forum_id']);
        $allow_edit = $this->core->forumCall("edit_post");
        list($allow_html, $allow_xcode, $allow_smiles, $allow_polls) = mysql_fetch_row(mysql_query("select allow_html, allow_xcode, allow_smiles, polls from forums_config")) or die(mysql_error());

        if ($_POST['confirm'] == 1){
            if (isset($_POST['preview'])){
                $_POST['subject'] = stripslashes($_POST['subject']);
                $_POST['post'] = stripslashes($_POST['post']);
                list($subject) = mysql_fetch_row(mysql_query("select title from forums_posts where post_id = '$_GET[post_id]'"));
                $subject = $this->text->convertText($subject);
                $post = stripslashes($this->text->convertText($_POST['post'], 1, 1));
                $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
                $text .= "<tr><td class=medtitle>" . $this->textArray['Preview'] . " " . $this->textArray['Post'] . "</td></tr>";
                $text .= "<td class=main valign=top style=\"word-wrap: break-word\"><span class=small>Subject: $subject</span><br><hr>$post</td></tr>";
                $text .= "</table><p>";

                $_POST['confirm'] = 0;
                $this->editPost();
                $this->body = $text . $this->body;
            }
            else {
                $post = $this->text->convertText($_POST['post'], 1, 1);
                $date = $this->core->getTime(time(), 1);
                list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$_COOKIE[PXL]'"));
                $dateFormat = $this->core->dbCall("dateFormat");
                $date = $this->core->getTime($date);
                $add = "<span class=small><i><br><br>[" . $this->textArray['Edited'] . " " . $this->textArray['by'] . " $username on " . date($dateFormat . " H:i", $date) . "]<br></i></span>";
                $post = $post . $add;
                mysql_query("update forums_posts set post = '$post' where post_id = '$_POST[post_id]'");
                header("Location: forums.php?forum_id=$_GET[forum_id]&topic_id=$_POST[topic_id]&post_id=$_POST[post_id]#$_POST[post_id]");
            }

        }
        else {
            $siteName = $this->core->dbCall("siteName");



            list($user_id, $subject, $post) = mysql_fetch_row(mysql_query("select user_id, title, post from forums_posts where post_id = '$_GET[post_id]'"));
            if ($secure == 1 || ($allow_edit == 1 && $user_id == $_COOKIE["PXL"])){
                require("includes/formscript.inc.php");

                $subject = $this->text->convertText($subject);

                if ($_POST[post] == ''){ $post = $this->text->convertText($post, 0, 1); }
                else { $post = $_POST[post]; }

                $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=main align=center>";
                $text .= "<tr>";
                if (isset($_GET['topic_id']) && $_GET['topic_id'] != ''){
                    list($cat_id, $cat_name, $forum_name, $topic_name) = mysql_fetch_row(mysql_query("select c.cat_id, c.name, f.name, p.title from forums_cat c, forums_forums f, forums_topics t, forums_posts p where p.post_id = t.post_id and t.topic_id = '$_GET[topic_id]' and t.forum_id = '$_GET[forum_id]' and f.forum_id = t.forum_id and f.cat_id = c.cat_id"));
                    $cat_name = $this->text->convertText($cat_name);
                    $forum_name = $this->text->convertText($forum_name);
                    $topic_name = $this->text->convertText($topic_name);
                    $text .= "<td class=main-large><a name=top><a href=forums.php>$siteName " . $this->textArray['Forums'] . "</a> -> <a href=forums.php?cat_id=$cat_id>$cat_name</a> -> <a href=forums.php?forum_id=$_GET[forum_id]>$forum_name</a> -> <a href=forums.php?forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]>$topic_name</a></td></tr>";
                    $formAction = $this->textArray['Edit'] . " " . $this->textArray['Post'];
                }
                /*else {
                    list($cat_id, $cat_name, $forum_name) = mysql_fetch_row(mysql_query("select c.cat_id, c.name, f.name from forums_cat c, forums_forums f where f.forum_id = '$_GET[forum_id]' and f.cat_id = c.cat_id"));
                    $cat_name = $this->text->convertText($cat_name);
                    $forum_name = $this->text->convertText($forum_name);
                    $text .= "<td class=main-large><a name=top><a href=forums.php>$siteName " . $this->textArray['Forums'] . "</a> -> <a href=forums.php?cat_id=$cat_id>$cat_name</a> -> <a href=forums.php?forum_id=$_GET[forum_id]>$forum_name</a></td></tr>";
                    $formAction = $this->textArray['Add Topic'];
                } */
                $text .= "</table>";
                $text .= "<table border=0 cellpadding=3 cellspacing=1 class=outline width=95% align=center>";
                $text .= "<tr><td class=medtitle colspan=2>$formAction</td></tr>";

                $text .= "<script language=\"JavaScript\" src=\"includes/post.js\"></script>";
                $text .= "<form method=post action=forums.php?action=edit&forum_id=$_GET[forum_id]&topic_id=$_GET[topic_id]&post_id=$_GET[post_id] name=forum onsubmit=\"return validateForm(this)\">";
                $text .= "<input type=hidden name=confirm value=1>";
                $text .= "<input type=hidden name=topic_id value=$_GET[topic_id]>";
                $text .= "<input type=hidden name=forum_id value=$_GET[forum_id]>";
                $text .= "<input type=hidden name=post_id value=$_GET[post_id]>";


                if ($allow_smiles == 1){ $smiles = $this->createSmileForm(); }
                $text .= "<tr><td class=mainbold>" . $this->textArray['Subject'] . ": </td><td class=main>$subject</td></tr>";
                if ($allow_xcode == 1){ $xcode = $this->createXCodeForm(); $xdesc = "<span style='font-size:9px;'><a href=forums.php?action=xcode>XCode</a> " . $this->textArray['is On'] . "</span><br>"; }
                else { $xcode = ''; $xdesc = "<span style='font-size:9px;'><a href=forums.php?action=xcode>XCode</a> " . $this->textArray['is Off'] . "</span><br>"; }
                if ($allow_html == 1){ $html = "<span style='font-size:9px;'>HTML " . $this->textArray['is On'] . "</span>"; }
                else { $html = "<span style='font-size:9px;'>HTML " . $this->textArray['is Off'] . "</span>"; }
                $text .= "<tr><td class=main-alt valign=top><b>Xcode:</b> <br>$xdesc $html</td><td class=main-alt>$xcode</td></tr>";

                $text .= "<tr><td class=main-alt valign=top><b>" . $this->textArray['Post'] . " :<br><br>";
                $text .= $smiles;

                $text .= "</td><td class=main-alt><textarea name=post cols=75% rows=15 wrap=virtual onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);' onFocus=setCaretAtEnd(this)>$post</textarea></td></tr>";

                $text .= "<tr><td class=main colspan=2 align=center>";
                $text .= "<input type=submit name=submit value='" . $this->textArray['Submit'] . " " . $this->textArray['Post'] . "' class=submit> ";
                $text .= "<input type=submit name=preview value='" . $this->textArray['Preview'] . " " . $this->textArray['Post'] . "' class=submit></form></td></tr>";
                $text .= "</table>";
                $text .= "<script Language=JavaScript>";
                $text .= "function validateForm(theForm)";
                $text .= "{";
                $text .= "    if (!validRequired(theForm.post,'" . $this->textArray['Post'] . "'))";
                $text .= "        return false;";
                $text .= "    return true;";
                $text .= "}";
                $text .= "</script>";
                $this->body = $text;
            }
            else {  header("Location: error.php?error=403"); }
        }

    }


    function createPoll(){
        if ($_POST['confirm'] == 1){

            $text = $this->text->convertText($_POST[text]);
            $topic_id = $_POST[topic_id];
            list($forum_id) = mysql_fetch_row(mysql_query("select forum_id from forums_topics where topic_id = '$topic_id'"));
            mysql_query("insert into forums_polls values ('', '$topic_id', '$text', '0')");
            $poll_id = mysql_insert_id();

            $i=1;
            foreach($_POST[option] as $op){
                if ($op != ''){
                    $op = $this->text->convertText($op);
                    mysql_query("insert into forums_poll_results values ('', '$poll_id', '$i', '$op', '0')");
                    $i++;
                }
            }
            mysql_query("update forums_topics set forum_poll_id = '$poll_id' where topic_id = '$topic_id'");
            header("Location: forums.php?forum_id=$forum_id&topic_id=$topic_id");
        }
        else {
            require("includes/formscript.inc.php");
            $max_poll = $this->core->forumCall("max_poll") + 1;
            $text = "<table border=0 cellpadding=3 cellspacing=1 class=outline width=95% align=center>";
            $text .= "<tr><td class=medtitle colspan=2>" . $this->textArray['Create'] . " " . $this->textArray['Forum'] . " " . $this->textArray['Survey'] . "</td></tr>";
            $text .= "<form method=post action=forums.php?action=poll&forum_id=$forum_id&topic_id=$_GET[topic_id] name=forum onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<input type=hidden name=topic_id value=$_GET[topic_id]>";
            $text .= "<tr><td class=mainbold-alt>" . $this->textArray['Survey'] . " " . $this->textArray['Question'] . " : </td><td class=main-alt><input type=text value='' name=text size=40></td></tr>";
            for($i=1;$i<$max_poll;$i++){
                if ($i%2){ $class="main"; $bold="mainbold"; }
                else { $class="main-alt"; $bold="mainbold-alt"; }

                $text .= "<tr><td class=$bold>" . $this->textArray['Option'] . " $i : </td><td class=$class><input type=text size=40 name=option[] value=''></td></tr>  ";

            }
            $text .= "<tr><td class=main align=center colspan=2><input type=submit value='Enter' class=submit></td></tr></form>";
            $text .= "</table>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.text,'" . $this->textArray['Question'] . "'))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $text;
        }

    }

    function forumNotify($forum_id, $topic_id, $post_id, $user_id, $reply){

        list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$user_id'"));
        $siteName = $this->core->dbCall("siteName");
        $siteURL = $this->core->dbCall("siteURL");
        $webmasterEmail = $this->core->dbCall("webmasterEmail");
        list($title) = mysql_fetch_row(mysql_query("select p.title from forums_posts p, forums_topics t where t.topic_id = '$topic_id' and t.post_id = p.post_id"));
        $title = stripslashes($title);
        list($forum_name) = mysql_fetch_row(mysql_query("select name from forums_forums where forum_id = '$forum_id'"));
        $forum_name = stripslashes($forum_name);
        $headers = "From: $siteName " . $this->textArray['Forums'] . " <$webmasterEmail> \r\n";
        $headers .= "X-Sender: <$webmasterEmail>\r\n";
        $headers .= "X-Mailer: PHP\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= "Reply-To: $webmasterEmail\r\n";
        if ($reply == 1){
            $subject = "[$siteName " . $this->textArray['Forums'] . "] $username " . $this->textArray['has replied to'] . " $title";
            $message = "$username " . $this->textArray['has replied to'] . " $title\r\n";
        }
        else {
            $subject = "[$siteName " . $this->textArray['Forums'] . "] $username " . $this->textArray['has posted a new topic'] . " : $forum_name";
            $message = "$username " . $this->textArray['has posted a new topic'] . "\r\n";
        }
        $x=0;
        $message .= $this->textArray['click here to view'] . ": $siteURL" . "/forums.php?forum_id=$forum_id&topic_id=$topic_id&post_id=$post_id#$post_id \r\n\r\n";
        $message .= $this->textArray['Do not reply to this message'];
        if ($reply == 1){
            $result = mysql_query("select u.email, u.user_id from users u, forums_notify f where f.topic_id = '$topic_id' and u.user_id = f.user_id and f.user_id != '$user_id'");
            while(list($email, $check) = mysql_fetch_row($result)){
                list($c) = mysql_fetch_row(mysql_query("select count(user_id) from forums_ignore where user_id = '$check' and ignore_user_id = '$user_id'"));
                if ($c == 0){
                    mail($email, $subject, $message, $headers);
                }
            }
        }
        else {
            $result = mysql_query("select u.email, u.user_id from users u, forums_notify f where f.user_id = u.user_id and f.forum_id = '$forum_id' and f.user_id != '$user_id'");
            while(list($email, $check) = mysql_fetch_row($result)){
                list($c) = mysql_fetch_row(mysql_query("select count(user_id) from forums_ignore where user_id = '$check' and ignore_user_id = '$user_id'"));
                if ($c == 0){
                    mail($email, $subject, $message, $headers);
                }
            }
        }
    }

    function createSmileForm(){

        $result = mysql_query("select code, image from forums_smiles");
        $text = "<table border=0 cellpadding=2 cellspacing=1 width=150 align=center class=outline>";
        $text .= "<tr><td class=medtitle align=center>" . $this->textArray["Smilies"] . "</td></tr>";
        $text .= "<tr><td class=main-alt><table border=0 cellpadding=2 cellspacing=0 width=100% align=center>";
        $count=1;
        while(list($code, $file) = mysql_fetch_row($result)){

            if ($count == 1){ $text .= "<tr height=25>"; }

            $image = "<img src=images/smiles/";
            $image .= "$file alt=$file border=0>";

            $text .= "<td align=center valign=center class=main-alt width=20%><a href='javascript:void(0)' onClick=javascript:icon('$code')>$image</a></td>";
            if ($count == 4){ $text .= "</tr>"; $count=1; }
            else { $count++; }

        }
        $text .= "</tr></table></td></tr></table>";
        return $text;
    }

    function createXCodeForm($images=''){
        $fontSize = array("11" => $this->textArray['normal'], "8" => $this->textArray['tiny'], "18" => $this->textArray['big'], "24" => $this->textArray['huge']);
        $flip = array_flip($fontSize);
        $fontColor = array("black", "red", "orange", "brown", "cyan", "blue", "darkblue", "violet", "green", "yellow", "white");
        $fontFace = array("arial", "verdana", "helvetica", "sans-serif", "times", "garamond", "futura", "courier", "geneva");
        sort($fontColor);
        sort($fontFace);
        $text = "<input type=button value=B onclick='setXCode(0); return false;' style=\"font-weight:bold; width: 25px\" class=submit> ";
        $text .= "<input type=button value=I onclick='setXCode(2); return false;' style=\"font-style:italic; width: 25px\" class=submit> ";
        $text .= "<input type=button value=U onclick='setXCode(4); return false;' style=\"text-decoration:underline; width: 25px\" class=submit> ";
        $text .= "<input type=button value=" . $this->textArray['left'] . " onclick='setXCode(6); return false;' class=submit> ";
        $text .= "<input type=button value=" . $this->textArray['center'] . " onclick='setXCode(10); return false;' class=submit> ";
        $text .= "<input type=button value=" . $this->textArray['right'] . " onclick='setXCode(8); return false;' class=submit> ";
        $text .= "<input type=button value=" . $this->textArray['block'] . " onclick='setXCode(12); return false;' class=submit> ";
        $text .= "<input type=button value=" . $this->textArray['line'] . " onclick='createLine(); return false;' class=submit> ";
        $text .= "<input type=button value=" . $this->textArray['list'] . " onclick='setXCode(14); return false;' class=submit> ";
        if ($images == 1){ $text .= "<input type=button value=" . $this->textArray['image'] . " onclick='setXCode(16); return false;' class=submit> "; }
        $text .= "<input type=button value=" . $this->textArray['code'] . " onclick='setXCode(18); return false;' class=submit> ";
        $text .= "<input type=button value=" . $this->textArray['url'] . " onclick='setXURLCode(); return false;' class=submit> ";
        $text .= "<br><br>";
        $text .= "<select name=fontColor onChange='changeFontColor(this.form)'>";
        foreach($fontColor as $c){
            $text .= "<option value=$c style='color:$c;'>$c</option>";
        }
        $text .= "</select> ";

        $text .= "<select name=fontType onChange='changeFontType(this.form)'>";
        foreach($fontFace as $c){
            $text .= "<option value=$c>$c</option>";
        }
        $text .= "</select> ";

        $text .= "<select name=fontSize onChange='changeFontSize(this.form)'>";
        foreach($fontSize as $c){
            $text .= "<option value=$flip[$c]>$c</option>";
        }
        $text .= "</select> ";
        return $text;
    }


    function forumVote(){
        list($topic_id, $forum_id) = mysql_fetch_row(mysql_query("select topic_id, forum_id from forums_topics where forum_poll_id = '$_POST[forum_poll_id]'"));
        if ($_POST[public_poll] == 1){
            $anon = $this->core->forumCall("anon_vote");
            $unlim = $this->core->forumCall("unlimited_vote");
            if (!isset($_COOKIE[PXL]) && $anon != 1){ header("Location: forums.php?forum_id=$forum_id&topic_id=$topic_id"); }
            else {
                list($count) = mysql_fetch_row(mysql_query("select count(forum_poll_user_id) from forums_polls_users where user_id = '$_COOKIE[PXL]' and forum_poll_id = '$_POST[forum_poll_id]'"));
                if ($count > 0 && $unlim != 1){ header("Location: forums.php?forum_id=$forum_id&topic_id=$topic_id"); }
                else {
                    mysql_query("insert into forums_polls_users values ('', '$_POST[forum_poll_id]', '$_COOKIE[PXL]')");
                    mysql_query("update forums_polls set total = total + 1 where forum_poll_id = '$_POST[forum_poll_id]'");
                    mysql_query("update forums_poll_results set option_results = option_results + 1 where forum_poll_id = '$_POST[forum_poll_id]' and option_id = '$_POST[options]'");
                    header("Location: forums.php?forum_id=$forum_id&topic_id=$topic_id");
                }
            }
        }

        else {
            if (!isset($_COOKIE[PXL])){ header("Location: forums.php?forum_id=$forum_id&topic_id=$topic_id"); }
            else {
                list($count) = mysql_fetch_row(mysql_query("select count(forum_poll_user_id) from forums_polls_users where user_id = '$_COOKIE[PXL]' and forum_poll_id = '$_POST[forum_poll_id]'"));
                if ($count > 0){ header("Location: forums.php?forum_id=$forum_id&topic_id=$topic_id"); }
                else {
                    mysql_query("insert into forums_polls_users values ('', '$_POST[forum_poll_id]', '$_COOKIE[PXL]')");
                    mysql_query("update forums_polls set total = total + 1 where forum_poll_id = '$_POST[forum_poll_id]'");
                    mysql_query("update forums_poll_results set option_results = option_results + 1 where forum_poll_id = '$_POST[forum_poll_id]' and option_id = '$_POST[options]'");
                    header("Location: forums.php?forum_id=$forum_id&topic_id=$topic_id");
                }
            }
        }
    }
}
