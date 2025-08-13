<?php
#$Id: functions.inc.php,v 1.42 2003/11/03 13:27:02 ryan Exp $
require('admin/includes/config.inc.php');
require('admin/includes/var.inc.php');
require('admin/includes/moduleVar.inc.php');

class website {
    var $templates;

    function website(){
        $this->id = $_GET['id'];
        $this->action = $_GET['action'];
        $this->page = str_replace(".php", '', substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/") + 1));
        if ($this->page == "index"){ $this->page = ''; }
        $this->core = new core();
        $this->core->connectdb();
        $this->http_secure();
        $this->getPageId();
        $this->insertStats();
        $this->core->forumTracker();
        $this->themeLang();
        $this->createPage();
        $this->stroke();

    }

    function http_secure(){
        $ssl = $this->core->dbCall("secure");
        $close = $this->core->dbCall("close_site");
        if ($close == 1){ die($this->textArray['site closed temporarily.  please check back soon']); }
        $check = $this->core->dbCall("http_secure");
        $this->security = new security();
        $this->security->banList();
        $siteName = $this->core->dbCall("siteName");
        $authType = $this->core->dbCall("database_secure");
        if ($check == 1){

            if (!isset($_SERVER["PHP_AUTH_USER"]) || $_SERVER["PHP_AUTH_USER"] == '') {
                header("WWW-Authenticate: Basic realm=\"$siteName\"");
                header("HTTP/1.0 401 Unauthorized");
                die("Unauthorized");
            }
            else {
            	$user = $_SERVER['PHP_AUTH_USER'];
            	$password = $_SERVER['PHP_AUTH_PW'];
                if ($authType == 0){
                    dl("smbauth.so");
                    list($server1, $server2, $domain) = mysql_fetch_row(mysql_query("select http_server1, http_server2, http_domain from config"));
                    $here = smbauth($user, $password, $server1, $server2, $domain);

                }
                else {
                    $password = md5($password);
                    $result = mysql_query("select user_id from users where username = '$user' and password = '$password' and suspend != '1' and actkey is null");
                    $here = mysql_num_rows($result);
                }
                if ($here != "Success" && $authType == 0){
                    header("WWW-Authenticate: Basic realm=\"$siteName\"");
                    header("HTTP/1.0 401 Unauthorized");
                    die("Unauthorized");
                }
                else if ($here != "1" && $authType == 1){
                    header("WWW-Authenticate: Basic realm=\"$siteName\"");
                    header("HTTP/1.0 401 Unauthorized");
                    die("Unauthorized");
                }
                else {
                    list($user_id) = mysql_fetch_row($result);
                    setcookie("PXL", $user_id, 0, '', '', $ssl);
                }
            }
        }
    }


    function getPageId(){
        $id = $this->id;
        if ($id == ''){
            $result = mysql_query("select page_id from pages where page_index = '1'");
            list($id) = mysql_fetch_row($result);
        }

        if (!isset($id) || $id == '' || $id == 0){ header("Location: error.php?error=500"); }
        list($c) = mysql_fetch_row(mysql_query("select count(*) from pages where page_id = '$id'"));
        if ($c != 1){ header("Location: error.php?error=404"); }
        $this->id = $id;
    }

    function themeLang(){

        $theme_id = $this->core->dbCall("theme_id");
        list($dir) = mysql_fetch_row(mysql_query("select theme_dir from theme where theme_id = '$theme_id'"));
        $this->templates = "templates/" . $dir;
        $langFile = "lang/" . $this->core->dbCall("lang") . ".inc.php";
        require($langFile);
        $this->textArray = $textArray;
        $this->menuType = $this->core->dbCall("menuType");
        $this->menu = $this->createMenu();
    }

    function stroke(){

        $id = $this->id;
        $menu = $this->menu;
        $footer = $this->core->dbCall("footer");
        $siteName = $this->core->dbCall("siteName");
        $keywords = $this->core->dbCall("keywords");
        $description = $this->core->dbCall("description");
        $splash = $this->core->dbCall("splash_page");
        $body = $this->body;
        if ($this->title == ''){
            list($title, $page_index) = mysql_fetch_row(mysql_query("select title, page_index from pages where page_id = '$id'"));
            $title = stripslashes($title);
        }
        else {
            list($page_index) = mysql_fetch_row(mysql_query("select page_index from pages where page_id = '$id'"));
            $title = $this->title;
        }
        $template = $this->templates;
        if ($page_index == 1 && $splash == 1 && !isset($this->pageFlag) && $_POST[preview] != "1"){ $templateFile = $this->templates . "/splash.inc.php"; }
        else { $templateFile = $this->templates . "/main.inc.php"; }
        require($templateFile);

    }

    function sortAction(){

        switch($this->action){
            case search:
                include_once("includes/page.inc.php");
                $this->page = new page($this->id, $this->textArray, $this->templates);
                $this->body = $this->page->siteSearch();
                break;

            case login:
                $this->body = $this->core->login();
                break;
        }
    }


    function createPage(){
        if (isset($this->id)){
            $sql = "select title, body, parse_php from pages where page_id = '" . $this->id . "'";
            list($title, $body, $parse_php) = mysql_fetch_row(mysql_query($sql));
            $this->body = stripslashes($body);
            $this->title = stripslashes($title);
        }

        if ($_POST['preview'] == 1){ $this->body = $_POST['fieldValue']; }
        //if (isset($_GET['news_id'])){ $this->body = ":NEWS:"; }

        if ($this->page != '' && $this->page != "index"){

            $this->pageFlag = 1;
            switch($this->page){
                case news:
                    $this->body = ":NEWS:";
                    include_once("includes/news.inc.php");
                    $news = new news($this->id, $this->textArray, $this->templates);
                    $this->body = str_replace(":SEARCH:", $news->body, $this->body);
                    $this->body = str_replace(":NEWS:", $news->body, $this->body);
                    if (substr_count($this->body, "SEARCHHERE") > 0){
                        include_once("includes/page.inc.php");
                        $this->page = new page($this->id, $this->textArray, $this->templates);
                        $search = $this->page->siteSearch();
                        $this->body = str_replace("SEARCHHERE", $search, $this->body);
                    }
                    break;
                case users:
                    include_once("includes/users.inc.php");
                    $users = new userModule($this->id, $this->textArray, $this->templates);
                    $this->body = $users->body;
                    $this->title = "Users";
                    break;
                case forums:
                    include_once("includes/forums.inc.php");
                    $forums = new forumModule($this->textArray, $this->templates);
                    $this->body = $forums->body;
                    $this->title = "Forums";
                    break;
                case faq:
                    include_once("includes/faq.inc.php");
                    $faq = new faqModule($this->textArray, $this->templates);
                    $this->body = $faq->body;
                    $this->title = "FAQ";
                    break;
                case status:
                    $this->statusModule();
                    $this->title = $this->textArray['Results'];
                    break;

                default:
                    include("includes/modules.inc.php");
                    //**MODULE INSERTION POINT**//
                    //This is where you should include your modules, so they dont get overwritten
            }
        }

        else if ($this->action == "search" || $this->action == "login"){
            $this->sortAction();
        }
        else {
            if (substr_count($this->body, ":CONTACT:") > 0){
                $mainFlag=1;
                include_once("includes/page.inc.php");
                $this->page = new page($this->id, $this->textArray, $this->templates);
                $result = $this->page->contact();
                $this->body = str_replace(":CONTACT:", $result, $this->body);
            }
            else if (substr_count($this->body, ":STATS:") > 0){
                $mainFlag=1;
                include_once("includes/page.inc.php");
                $this->page = new page($this->id, $this->textArray, $this->templates);
                $result = $this->page->publicstats();
                $this->body = str_replace(":STATS:", $result, $this->body);
            }

            else if (substr_count($this->body, ":NEWS:") > 0){
                include_once("includes/news.inc.php");
                $mainFlag=1;
                $news = new news($this->id, $this->textArray, $this->templates);
                $this->body = str_replace(":SEARCH:", $news->body, $this->body);
                $this->body = str_replace(":NEWS:", $news->body, $this->body);
                if (substr_count($this->body, "SEARCHHERE") > 0){
                    include_once("includes/page.inc.php");
                    $this->page = new page($this->id, $this->textArray, $this->templates);
                    $search = $this->page->siteSearch();
                    $this->body = str_replace("SEARCHHERE", $search, $this->body);
                }
            }
            include("includes/modulesInsert.inc.php");
            //**MODULE INSERTION POINT**//
            //This is where you should include your modules, so they dont get overwritten

            if (!$mainFlag){
                if (substr_count($this->body, ":POLL:") > 0){
                    include_once("includes/sub.inc.php");
                    $subFun = new subFunctions($this->id, $this->textArray, $this->templates, $this->core);
                    $this->body = $subFun->poll($this->body);
                }
                if (substr_count($this->body, ":SEARCH:") > 0){
                    include_once("includes/page.inc.php");
                    $this->page = new page($this->id, $this->textArray, $this->templates);
                    $result = $this->page->siteSearch();
                    $this->body = str_replace(":SEARCH:", $result, $this->body);
                }

                if (substr_count($this->body, ":ONLINE:") > 0){
                    include_once("includes/sub.inc.php");
                    $subFun = new subFunctions($this->id, $this->textArray, $this->templates, $this->core);
                    $this->body = $subFun->whoIsOnline($this->body);
                }

                if ($parse_php == 1){
                    $this->body = $this->parsePHP($this->body);
                }
            }
        }

    }

    function statusModule(){

        switch($_GET[status]){
            case 1:
                $statusURL = "<a href=news.php?news_id=$_GET[news_id]>" . $this->textArray['Back to'] . " " . $this->textArray['News Story'] . "</a>";
                $statusText = $this->textArray['Your email has been sent.'];
                break;

            case 2:
                $statusURL = "<a href=news.php>" . $this->textArray['Back to'] . " " . $this->textArray['News'] . "</a>";
                $statusText = $this->textArray['Your Story has been Submitted'];
                break;

        }

        $text = "<table width=500 cellspacing=1 cellpadding=0 border=0 align=center>";
        $text .= "<tr><td align=center class=attn>$statusText</td></tr>";
        $text .= "<tr><td align=center class=main>$statusURL</td></tr>";
        $text .= "</table>";
        $this->body = $text;
    }

    function parsePHP($string) {
    	//Majority of code by nathan vonnahme.
        //Contributed by maduyb
        //cleaned up and optimized by Xnuiem

    	$string = str_replace('&#40;', '(', $string);
	    $string = str_replace('&#39;', '\'', $string);
        $string = str_replace('&#41;', ')', $string);
        $string = ' ' . $string;
	    $pos = 0;

	    while (($pos = strpos($string, '<?php')) != FALSE){
	        $pos2 = strpos($string, '?>', $pos + 2);
            ob_start();
	        eval(substr($string, $pos + 5, $pos2 - $pos - 2));
	        $value = ob_get_contents();
	        ob_end_clean();
	        $string = substr($string, 0, $pos) . $value . substr($string, $pos2 + 2);
        }

	    return $string;
    }

    function createMenu(){

        if ($this->menuType == 0){
            $text = "<script language=\"JavaScript\" src=\"menu_files/menu.js\"></script>";
            $text .= "<script language=\"JavaScript\" src=\"menu_files/menu_tpl.js\"></script>";
            $text .= "<script language=Javascript>";
            $text .= $this->createMenuItems();
            $text .= "new menu (MENU_ITEMS0, MENU_POS0);";
            $text .= "</script>";
        }
        else if ($this->menuType == 1){
            $text = "<script language=\"JavaScript\" src=\"menu_files/menu.js\"></script>";
            $text .= "<script language=\"JavaScript\" src=\"menu_files/menu_tpl1.js\"></script>";
            $text .= "<script language=Javascript>";
            $text .= $this->createMenuItems();
            $text .= "new menu (MENU_ITEMS0, MENU_POS0);";
            $text .= "</script>";
        }
        else if ($this->menuType == 2){
            $text = "<script language=\"JavaScript\" src=\"menu_files/tree.js\"></script>";
            $text .= "<script language=\"JavaScript\" src=\"menu_files/tree_tpl.js\"></script>";
            $text .= "<script language=Javascript>";

            $text .= $this->createMenuItems();
            $text .= "new tree (MENU_ITEMS0, TREE_TPL);";
            $text .= "</script>";
        }
        else if ($this->menuType == 3){
            $text = $this->createTextMenu();
        }
        return $text;
    }

    function createTextMenu(){

        list($page_id) = mysql_fetch_row(mysql_query("select page_id from pages where page_index = '1'"));
        $menu = "<a href=index.php?id=$page_id>Home</a><br><br>";

        $result = mysql_query("select menu_id, menu_name, link from menu where sub = '0' order by ord");
        while(list($menu_id, $menu_name, $link) = mysql_fetch_row($result)){
            if ($link != ''){ $menu .= "<a href=$link>$menu_name</a><br>"; }
            else { $menu .= "$menu_name<br>"; }
            $result2 = mysql_query("select menu_id, menu_name, link from  menu where sub = '$menu_id' order by ord");
            $count = mysql_num_rows($result2);
            if ($count > 0){
                while(list($id2, $name2, $link2) = mysql_fetch_row($result2)){
                    if ($link2 != ''){ $menu .= "&nbsp;&nbsp;&nbsp;<a href=$link2>$name2</a><br>"; }
                    else { $menu .= "&nbsp;&nbsp;&nbsp;$name2<br>"; }

                    $result3 = mysql_query("select menu_name, link from  menu where sub = '$id2' order by ord");
                    $count1 = mysql_num_rows($result3);
                    if ($count1 > 0){
                        while(list($name3, $link3) = mysql_fetch_row($result3)){
                            $menu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=$link3>$name3</a><br>";
                        }
                    }
                }
            }
        }
        return $menu;
    }

    function createMenuItems(){
        $menu = "MENU_ITEMS0 = [";
        if ($this->menuType == 2){
            list($page_id) = mysql_fetch_row(mysql_query("select page_id from pages where page_index = '1'"));
            $menu .= "['Home', 'index.php?id=$page_id', 'null', ";
        }
        $result = mysql_query("select menu_id, menu_name, link, target from menu where sub = '0' order by ord");
        while(list($menu_id, $menu_name, $link, $target) = mysql_fetch_row($result)){
            if ($link == ''){ $link = "null"; }
            else { $link = "'$link'"; }
            $menu .= "[";
            $menu .= "'$menu_name'";
            $menu .= ", $link,";
            $menu .= " { 'sb' : '$menu_name' ";

            if ($target != 0){ $menu .= ",'tw' : '_blank' "; }
            $menu .= " },";

            $result2 = mysql_query("select menu_id, menu_name, link, target from  menu where sub = '$menu_id' order by ord");
            $count = mysql_num_rows($result2);
            if ($count > 0){
                while(list($id2, $name2, $link2, $target) = mysql_fetch_row($result2)){
                    if ($link2 == ''){ $link2 = "null"; }
                    else { $link2 = "'$link2'"; }
                    $menu .= "[";
                    $menu .= "'$name2'";
                    $menu .= ", $link2,";
                    $menu .= " { 'sb' : '$name2' ";

                    if ($target != 0){ $menu .= ",'tw' : '_blank' "; }
                    $menu .= "},";

                    $result3 = mysql_query("select menu_name, link, target from  menu where sub = '$id2' order by ord");
                    $count1 = mysql_num_rows($result3);
                    if ($count1 > 0){
                        while(list($name3, $link3, $target) = mysql_fetch_row($result3)){
                            if ($link3 == ''){ $link3 = "null"; }
                            else { $link3 = "'$link3'"; }
                            $menu .= "[";
                            $menu .= "'$name3'";
                            $menu .= ", $link3,";
                            $menu .= " { 'sb' : '$name3' ";

                            if ($target != 0){ $menu .= ",'tw' : '_blank' "; }
                            $menu .= "},";
                            $menu .= "],";
                        }
                        $menu = substr($menu, 0, strlen($menu)-1);
                        $menu .= "],";
                    }
                    else {
                        $menu = substr($menu, 0, strlen($menu)-1);
                        $menu .= "],";
                    }
                }
            }
            $menu = substr($menu, 0, strlen($menu)-1);
            $menu .= "],";
        }
        $menu = substr($menu, 0, strlen($menu)-1);
        if ($this->menuType == 2){ $menu .= "]"; }
        $menu .= "];";
        //print "$menu";
        return $menu;
    }

    function insertStats(){
        $agent = $_SERVER["HTTP_USER_AGENT"];
        $ref = $_SERVER["HTTP_REFERER"];
        $ip = $_SERVER["REMOTE_ADDR"];
        $host = $_SERVER["HTTP_HOST"];

        if (ereg("MSIE", $agent)){ $browser = "MSIE"; }
        else if (ereg("Nav", $agent) || ereg("Gold", $agent) || ereg("X11", $agent) || ereg("Mozilla", $agent) || ereg("Netscape", $agent) && (!ereg("MSIE", $agent)) && (!ereg("Konqueror", $agent))){ $browser = "Netscape"; }
        else if (ereg("Lynx", $agent)){ $browser = "Lynx"; }
        else if (ereg("Opera", $agent)){ $browser = "Opera"; }
        else if (ereg("WebTV", $agent)){ $browser = "WebTV"; }
        else if (ereg("Konqueror", $agent)){ $browser = "Konqueror"; }
        else if ((eregi("bot", $agent)) || (ereg("Google", $agent)) || (ereg("Slurp", $agent)) || (ereg("Scooter", $agent)) || (eregi("Spider", $agent)) || (eregi("Infoseek", $agent))){ $browser = "Bot"; }
        else { $browser = "Other"; }

        if(ereg("Win", $agent)){ $os = "Windows"; }
        else if((ereg("Mac", $agent)) || (ereg("PPC", $agent))){ $os = "Mac"; }
        else if(ereg("Linux", $agent)){ $os = "Linux"; }
        else if(ereg("FreeBSD", $agent)){ $os = "FreeBSD"; }
        else if(ereg("SunOS", $agent)){ $os = "SunOS"; }
        else { $os = "Other"; }

        mysql_query("update stats set count=count+1 where (type = '4' and name = 'pageviews') or (name = '$browser' and type = '3') or (name = '$os' and type = '0') or (name = '$id' and type = '1')") or die(mysql_error());
        $refCount = substr_count($ref, $host);
        if ($ref != '' && $refCount == 0){
            $ref = str_replace("http://", '', $ref);
            $result = mysql_query("select count(*) from stats where type = '2' and name = '$ref'");
            list($c) = mysql_fetch_row($result);
            if ($c > 0){ mysql_query("update stats set count=count+1 where type = '2' and name = '$ref'"); }
            else { mysql_query("insert into stats values ('', '2', '$ref', '1')"); }
        }

    }
}

class security {
    //0 - "Guest Post"
    //1 - "Guest Read Only"
    //2 - "Members Post"
    //3 - "Members Read Only"
    //4 - "Moderator Post"
    //5  - "Moderator Read Only"
    //6 - "Admin Only"
    function forumSecure($forum_id, $action='0', $redirect='0'){
        //aciton if == '' or 0 View only
        //1 = post
        //2 = admin/moderator only
        //if redirect == 1, send to error!
        //if (!isset($_COOKIE['VALID'])){ header("Location: error.php?error=403"); }
        $admin=0;
        list($level) = mysql_fetch_row(mysql_query("select level from forums_forums where forum_id = '$forum_id'"));
        if (!isset($_COOKIE[PXL])){


            switch($level){
                case 0:
                    $admin = 1;
                    break;
                case 1:
                    if ($action != 0){ $admin = 0; }
                    else { $admin = 1; }

                    break;
                default:
                    $admin = 0;
                    break;
            }
        }
        else {
            list($userAdmin) = mysql_fetch_row(mysql_query("select admin from users where user_id = '$_COOKIE[PXL]'"));
            list($modCount) = mysql_fetch_row(mysql_query("select count(forum_mod_id) from forums_mod where user_id = '$_COOKIE[PXL]' and forum_id = '$forum_id'"));
            switch ($level){
                case 0:
                    $admin = 1;
                    break;
                case 1:
                    $admin = 1;
                    break;
                case 2:
                    $admin = 1;
                    break;
                case 3:
                    if ($action != 0){
                        if ($userAdmin == 0 && $modCount == 0){ $admin = 0; }
                        else { $admin = 1; }
                    }
                    else { $admin = 1; }
                    break;
                case 4:
                    if ($userAdmin == 0 && $modCount == 0){ $admin = 0; }
                    else { $admin = 1; }
                    break;
                case 5:
                    if ($modAcount == 0 && $userAdmin == 0){ $admin = 0; }
                    else if ($action != 0){
                        if ($userAdmin == 0){ $admin = 0; }
                        else { $admin = 1; }
                    }
                    else { $admin = 1; }
                    break;
                case 6:
                    if ($userAdmin == 0){ $admin = 0; }
                    else { $admin = 1; }
                    break;
            }
        }
        if ($admin == 1){ return $admin; }
        else if ($redirect == 1 && isset($_COOKIE[PXL])){ header("Location: error.php?error=403"); }
        else if ($redirect == 1 && !isset($_COOKIE[PXL])){ $admin = 2; return $admin; }
        else { return $admin; }
    }

    function banList(){
        if (isset($_COOKIE[PXL])){
            list($count) = mysql_fetch_row(mysql_query("select count(user_id) from users_ban where user_id = '$_COOKIE[PXL]'"));
            if ($count != 0){ header("Location: error.php?error=403"); }

        }
        $ip = $_SERVER["REMOTE_ADDR"];
        list($count) = mysql_fetch_row(mysql_query("select count(ip_addr) from users_ban where ip_addr = '$ip'"));
        if ($count != 0){ header("Location: error.php?error=403"); }

    }

    function forumAdmin($forum_id){

        list($admin) = mysql_fetch_row(mysql_query("select admin from users where user_id = '$_COOKIE[PXL]'"));

        if ($admin == 0){
            list($check) = mysql_fetch_row(mysql_query("select count(forum_mod_id) from forums_mod where user_id = '$_COOKIE[PXL]' and forum_id = '$forum_id'"));
            if ($check > 0){ $admin = 1; }
        }
        return $admin;
    }
}


class core {
    var $textArray;
    var $templates;
    var $words;
    var $xcode;
    var $html;
    var $smiles;

    function connectdb() {
        //! Connets to the database
        global $dbserver;
        global $dbuser;
        global $dbpass;
        global $database;

        mysql_connect("$dbserver", "$dbuser", "$dbpass") or die("MySQL Connect Failed");
        mysql_select_db("$database") or die("Can't Connect to Database");
    }

    function dbCall($field){
        //! grabs a specific field from the config table.
        list($result) = mysql_fetch_row(mysql_query("select $field from config where config_id = '1'"));
        return $result;
    }

    function forumCall($field){
        //! grabs a specific field from the forums_config table.
        list($result) = mysql_fetch_row(mysql_query("select $field from forums_config"));
        return $result;
    }

    function getTime($date, $flag=''){
        if ($flag == 1 || !isset($_COOKIE[PXL])){ //incoming, use General
            $time = $this->dbCall("timeZone");
        }
        else {
            list($time) = mysql_fetch_row(mysql_query("select timeZone from users where user_id = '$_COOKIE[PXL]'"));
        }
        if ($flag == 1){ $date = $date - ($time*3600); }
        else { $date = $date + ($time*3600); }
        return $date;
    }

    function forumTracker(){

        if (!isset($_COOKIE[PXLF])){
            $last = time();
            $last = $this->getTime($last, 1);
            if (isset($_COOKIE[PXL])){
                $result = mysql_query("select sess_id from forums_session where user_id = '$_COOKIE[PXL]'");
                $count = mysql_num_rows($result);
                if ($count != 0){
                    list($cookie) = mysql_fetch_row($result);
                }
                else {
                    mysql_query("insert into forums_session (user_id, last) VALUES ('$_COOKIE[PXL]', '$last')");
                    $cookie = mysql_insert_id();
                }
            }
            else {
                mysql_query("insert into forums_session (user_id, last) VALUES ('0', '$last')");
                $cookie = mysql_insert_id();
            }
            setcookie("PXLF", $cookie, time() + 315360000, '', '', $this->ssl);
        }
        else {
            list($count) = mysql_fetch_row(mysql_query("select count(sess_id) from forums_session where sess_id = '$_COOKIE[PXLF]'"));
            if ($count == 1){
                $time = $this->getTime(time(), 1);
                list($last, $temp) = mysql_fetch_row(mysql_query("select last, temp from forums_session where sess_id = '$_COOKIE[PXLF]'"));
                if ($temp < $time){
                    $timeplus = $time + 900;
                    mysql_query("update forums_session set templast = '$last', temp = '$timeplus' where sess_id = '$_COOKIE[PXLF]'");
                }
                $last = $this->getTime($last, 1);
                mysql_query("update forums_session set last = '$time', user_id = '$_COOKIE[PXL]' where sess_id = '$_COOKIE[PXLF]'");
            }
            else {
                setcookie("PXLF", '', time() - 900, '', '', $this->ssl);
            }
        }
        if (isset($_GET['topic_id'])){ mysql_query("update forums_topics set reads = reads + 1 where topic_id = '$_GET[topic_id]'"); }
    }

    function pageTab($limit, $number, $url, $setnum){
        //! creates the page tabs for major result sets

        $show = $limit + $setnum;
        if ($show > $number){ $show = $number; }
        if ($limit == '0'){ $limit1 = 1; }
        else { $limit1 = $limit; }
        $showing = $this->textArray['Showing'] . " $limit1 - $show ($number " . $this->textArray['Total'] . ")";
        $more = $this->textArray['Page'] . ": ";
        if ($limit != '0'){
            $new = $limit - $setnum;
            if ($new < 0){ $new = 0; }
            $more .= "<a class=links href=" . $url . "&limit=0><<</a> ";
            $more .= "<a class=links href=" . $url . "&limit=$new>" . $this->textArray['Previous'] . " " . $this->textArray['Page'] . "</a>";
        }
        $y=0;
        for($x=0; $x < $number; $x += $setnum){
            if ($x == $limit){
                $save = $y;
                break;
            }
            $y++;
        }

        $a=1;
        $stopFlag=0;
        for($x=0; $x < $number; $x += $setnum){
            if ($save < 5){
                $st = 0;
                $en = 10;
            }
            else {
                $st = $save - 4;
                $en = $save + 6;
            }
            if ($limit == $x){
                $more .= " $a ";
                $stopFlag=1;
            }
            else if ($x > ($number - $setnum) && $stopFlag == 0){
                $more .= " $a";
            }
            else if ($a > $st && $a < $en){
                $more .= " <a class=links href=" . $url . "&limit=$x>$a</a> ";
            }
            $a++;
        }
        if ($number > ($limit + $setnum)){



            $new = $limit + $setnum;
            $check = $number/$setnum;
            if (substr_count($check, ".") > 0){
                $pages = substr($check, 0, strpos($check, ".")) + 1;
            }
            $end = $pages*$setnum - $setnum;
            $more .= "<a class=links href=" . $url . "&limit=$new>" . $this->textArray['Next'] . " " . $this->textArray['Page'] . "</a>";
            $more .= "<a class=links href=" . $url . "&limit=$end>>></a>";
        }
        $more = $showing . " " . $more;
        return $more;
    }

    function forumPostTab($limit, $number, $url, $setnum){
        //! creates the page tabs for major result sets

        if ($limit != '0'){
            $new = $limit - $setnum;
            if ($new < 0){ $new = 0; }
            $more .= "<a class=links href=" . $url . "&limit=0><<</a> ";
            $more .= "<a class=links href=" . $url . "&limit=$new>Previous Page</a>";
        }
        $y=0;
        for($x=0; $x < $number; $x += $setnum){
            if ($x == $limit){
                $save = $y;
                break;
            }
            $y++;
        }

        $a=1;
        $stopFlag=0;
        for($x=0; $x < $number; $x += $setnum){
            if ($save < 4){
                $st = 0;
                $en = 4;
            }
            else {
                $st = $save - 3;
                $en = $save + 4;
            }
            if ($limit == $x){
                $more .= " $a ";
                $stopFlag=1;
            }
            else if ($x > ($number - $setnum) && $stopFlag == 0){
                $more .= " $a";
            }
            else if ($a > $st && $a < $en){
                $more .= " <a class=links href=" . $url . "&limit=$x>$a</a> ";
            }
            $a++;
        }
        if ($number > ($limit + $setnum)){



            $new = $limit + $setnum;
            $check = $number/$setnum;
            if (substr_count($check, ".") > 0){
                $pages = substr($check, 0, strpos($check, ".")) + 1;
            }
            $end = $pages*$setnum - $setnum;
            $more .= "<a class=links href=" . $url . "&limit=$new>Next Page</a>";
            $more .= "<a class=links href=" . $url . "&limit=$end>>></a>";
        }
        $more = $showing . " " . $more;
        return $more;
    }

    function login($code='', $url='', $username=''){
        $ssl = $this->dbCall("secure");
        if ($_SERVER["SERVER_PORT"] != 443 && $ssl == 1){
            $head = "Location: https://" . $_SERVER["HTTP_HOST"] .  $_SERVER["REQUEST_URI"];
            header($head);
        }
        if ($_POST['confirm'] == 1 && $code == ''){

            if (isset($_COOKIE['PXL'])){
                setcookie("PXL", '', time() - 100, '', '', $ssl);
                setcookie("PXLF", '', time() - 100, '', '', $ssl);
                if (isset($_POST['url'])){ header("Location: $_POST[url]"); }
                else { header("Location: users.php?action=login&code=3"); }
            }
            else {
                if ($_POST[remember] == "on"){ $time = time() + 4492800; }
                else { $time = 0; }
                $password = md5($_POST['password']);
                $username = strtolower($_POST['username']);
                $result = mysql_query("select user_id, suspend, actkey from users where lower(username) = '$username' and password = '$password'");
                $r = mysql_num_rows($result);
                if ($r == "0"){ $text = $this->login(1, $_POST['url'], $_POST['username']);  }
                else {
                    list($user_id, $suspend, $a) = mysql_fetch_row($result);
                    list($ban) = mysql_fetch_row(mysql_query("select count(user_ban_id) from users_ban where user_id = '$user_id'"));
                    if ($suspend == 1 || $ban > 0){ $text = $this->login(2, $_POST['url'], $_POST['username']); }
                    else if ($a != ''){ $text = $this->login(4, $_POST['url'], $_POST['username']); }
                    else {
                        $result = mysql_query("select sess_id from forums_session where user_id = '$user_id'");
                        $count = mysql_num_rows($result);
                        if ($count > 0){
                            list($sess) = mysql_fetch_row($result);
                            setcookie("PXLF", $sess, time() + 315360000, '', '', $ssl);
                        }
                        setcookie("PXL", $user_id, $time, '', '', $ssl);

                        header("Location: $_POST[url]");
                    }
                }
                return $text;
            }
        }
        else {
            if (isset($_COOKIE['PXL'])){
                require("$this->templates/files/logout.tpl.php");
                return $text;
            }
            else {

                if (isset($_GET['code'])){ $code = $_GET['code']; }
                $loginCodes[0] = $this->textArray['You must be Logged in to Perform this Action'];
                $loginCodes[1] = $this->textArray['Invalid Login'];
                $loginCodes[2] = $this->textArray['Account Suspended/Banned'];
                $loginCodes[3] = $this->textArray['Logged Out'];
                $loginCodes[4] = $this->textArray['Account not Activated'];
                if ($code != '' || $code == "0"){ $code = $loginCodes[$code]; }

                require("includes/formscript.inc.php");
                require("$this->templates/files/login.tpl.php");
                return $text;

            }
        }
    }
}

class printPage {

    function printPage(){
        $this->core = new core();
        $this->core->connectdb();

        $theme_id = $this->core->dbCall("theme_id");
        list($dir) = mysql_fetch_row(mysql_query("select theme_dir from theme where theme_id = '$theme_id'"));
        $this->templates = "templates/" . $dir;
        $langFile = "lang/" . $this->core->dbCall("lang") . ".inc.php";
        require($langFile);
        $this->textArray = $textArray;

        switch($_GET[action]){
            case news:
                $this->printNews();
                break;
        }
        print($this->body);
    }

    function printNews(){
        $dateFormat = $this->core->dbCall("dateFormat");
        $news_categories = $this->core->dbCall("news_categories");

        $sql = "select news_id, title, post, date, news_cat_id, more, user_id, size from news where delay = '0' and news_id = '$_GET[news_id]'";
        $result = mysql_query($sql);
        while(list($news_id, $title, $post, $date, $cat_id, $more, $user_id, $size) = mysql_fetch_row($result)){
            list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$user_id'"));

            if ($news_categories == 1){
                list($cat_name) = mysql_fetch_row(mysql_query("select news_cat_title from news_cat where news_cat_id = '$cat_id'"));
                $cat_name = stripslashes($cat_name);
                $category = " <a href=index.php?id=$this->id&news_cat_id=$cat_id&action=view_cat>$cat_name</a> : ";
            }

            $title = stripslashes($title);
            $post = stripslashes($post);
            $more = "<br><br>" . stripslashes($more);

            $date = $this->core->getTime($date);
            $date = date($dateFormat . " H:i", $date);

            $text = "<html><head><title>$title</title>";
            $text .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$this->templates/style.css\">";
            $text .= "</head><body>";
            $text .= "<table width=500 cellspacing=1 cellpadding=0 border=0 align=center class=outline>";
            $text .= "<tr>";
            $text .= "<td align=left class=title>$new $category $title</td></tr>";
            $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=500 align=center>";
            $text .= "<tr><td class=small>";
            $text .= "<b>" . $this->textArray['Posted by'] . " <a href=users.php?action=view&user_id=$user_id>$username</a> " . $this->textArray['on'] . " $date</b>";
            $text .= "</td></tr>";
            $text .= "<tr><td colspan=2 align=left class=main>";
            $text .= "$cat_image $post $more";
            $text .= "</td></tr>";
            $text .= "</table></td></tr>";
            $text .= "</table><br><br>";
            $text .= "</body></html>";
        }
        $this->body = $text;
    }
}



















































