<?php
#$Id: functions.inc.php,v 1.25 2003/10/15 17:21:57 ryan Exp $

class coreFunctions {

    function connectdb() {
        global $dbserver;
        global $dbuser;
        global $dbpass;
        global $database;
        mysql_connect("$dbserver", "$dbuser", "$dbpass") or die("MySQL Connection Failed");
        mysql_select_db("$database") or die("Could Not Select Database");
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

    function dbCall($field){
        //! grabs a specific field from the config table.
        list($result) = mysql_fetch_row(mysql_query("select $field from config where config_id = '1'")) or die(mysql_error());
        return $result;
    }

    function logout($userinfo, $code="l"){

        $core = new coreFunctions();
        $ssl = $core->dbCall("secure");
        setcookie("PXL", '', time() - 60, '', '', $ssl);
        $core->addLog("Logged Out", $userinfo[1], $userinfo[0]);
        header("Location: login.php?code=$code");

    }

    function addLog($action, $user, $id){
        $date = time();
        $date = date("M-d-Y H:i", $date);
        $writefile = "logs/event.log";
        if (file_exists($writefile)){ $fp = fopen( $writefile, 'a'); }
        else { touch($writefile); $fp = fopen( $writefile, 'w' ); }
        $message .= "$date :: $action :: $user :: $id\r\n";
        fwrite( $fp, $message);
        fclose($fp);
    }

    function findUserInfo($user_id){

        list($username, $first_name, $last_name, $email, $admin, $suspend) = mysql_fetch_row(mysql_query("select username, first_name, last_name, email, admin, suspend from users where user_id = '$user_id'"));
        $userinfo[] = $user_id;    //0
        $userinfo[] = $username;   //1
        $userinfo[] = $first_name; //2
        $userinfo[] = $last_name;  //3
        $userinfo[] = $email;      //4
        $userinfo[] = $admin;      //5
        $userinfo[] = $suspend;    //6
        return $userinfo;
    }

    function createProgressPage(){

        $text .= "<html>";
        $text .= "<head>";
        $text .= "<title>Working</title>";
        $text .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"templates/style.css\"></link>";
        $text .= "</head>";
        $text .= "<body marginwidth=0 marginheight=0 topmargin=0 leftmargin=0>";
        $text .= "<br><br><br><br><br><br><br>";
        $text .= "<table border=0 width=400 align=center cellpadding=0 cellspacing=1 class=outline>";
        $text .= "<tr><td class=title align=center>Working</td></tr>";
        $text .= "<tr><td class=main><table border=0 width=100% align=center cellpadding=2 cellspacing=0>";
        $text .= "<tr><td class=main align=center><b>Working.  Please wait.</b></td></tr>";
        $text .= "<tr><td class=main align=center><img src=images/searching.gif alt='Working' border=0></td></tr>";
        $text .= "<tr><td class=main align=center>This page will automatically forward when complete.</td></tr></table>";
        $text .= "</td></tr></table></td></tr></table>";
        $text .= "</body>";
        $text .= "</html>";
        print($text);
        flush();
    }

    function pageTab($limit, $number, $url, $setnum){
        //! creates the page tabs for major result sets

        $show = $limit + $setnum;
        if ($show > $number){ $show = $number; }
        if ($limit == '0'){ $limit1 = 1; }
        else { $limit1 = $limit; }
        $showing = "Showing $limit1 - $show ($number Total)";
        $more = "Page: ";
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
            $more .= "<a class=links href=" . $url . "&limit=$new>Next Page</a>";
            $more .= "<a class=links href=" . $url . "&limit=$end>>></a>";
        }
        $more = $showing . " " . $more;
        return $more;
    }

    function syncForums(){


        $result = mysql_query("select forum_id, cat_id from forums_forums");
        while(list($forum_id, $cat_id) = mysql_fetch_row($result)){
            list($count) = mysql_fetch_row(mysql_query("select count(*) from forums_cat where cat_id = '$cat_id'"));
            if ($count == 0){ mysql_query("delete from forums_forums where forum_id = '$forum_id'"); }
            else {
                list($topics) = mysql_fetch_row(mysql_query("select count(topic_id) from forums_topics where forum_id = '$forum_id'"));
                list($posts, $last) = mysql_fetch_row(mysql_query("select count(post_id), max(post_id) from forums_posts where forum_id = '$forum_id'"));
                mysql_query("update forums_forums set posts = '$posts', post_id = '$last', topics = '$topics' where forum_id = '$forum_id'");
            }
        }
        mysql_query("delete from forums_mod where user_id = '0'");
        list($auto, $days) = mysql_fetch_row(mysql_query("select auto_prune, prune_number from forums_config"));
        if ($auto == 1){
            $result = mysql_query("select topic_id from forums_topics where forum_poll_id = '0' and sticky = '0'");
            while(list($topic_id) = mysql_fetch_row($result)){
                list($count, $date) = mysql_fetch_row(mysql_query("select count(post_id), date from forums_posts where topic_id = '$topic_id'"));
                $check = $date + $days;
                if ($count == 1 && $check < time()){
                    mysql_query("delete from forums_topics where topic_id = '$topic_id'");
                    mysql_query("delete from forums_posts where topic_id = '$topic_id'");
                    $this->core->addLog("Topic Pruned", $this->userinfo[1], $topic_id);
                }
            }
        }
        $result = mysql_query("select topic_id from forums_topics where post_id = '0'");
        while(list($topic_id) = mysql_fetch_row($result)){
            list($post_id) = mysql_fetch_row(mysql_query("select post_id from forums_posts where topic_id = '$topic_id' order by post_id asc limit 0,1"));
            if ($post_id != ''){ mysql_query("update forums_topics set post_id = '$post_id' where topic_id = '$topic_id'"); }
            else { mysql_query("delete from forums_topics where topic_id = '$topic_id'"); $flag=1; }
        }
        if (isset($flag)){ $this->syncForums(); }

        $result = mysql_query("select post_id, topic_id from forums_posts where title = ''");
        while(list($post_id, $topic_id) = mysql_fetch_row($result)){
            list($topic) = mysql_fetch_row(mysql_query("select title from forums_posts where topic_id = '$topic_id' order by post_id asc limit 0,1"));
            $topic = addslashes($topic);
            mysql_query("update forums_posts set title = '$topic' where post_id = '$post_id'");
        }

        $result = mysql_query("select user_id from users");
        while(list($user_id) = mysql_fetch_row($result)){
            list($count) = mysql_fetch_row(mysql_query("select count(post_id) from forums_posts where user_id = '$user_id'"));
            mysql_query("update users set posts = '$count' where user_id = '$user_id'");
        }

        $result = mysql_query("select topic_id from forums_topics");
        while(list($topic_id) = mysql_fetch_row($result)){
            list($posts) = mysql_fetch_row(mysql_query("select count(post_id) from forums_posts where topic_id = '$topic_id'"));
            list($last_post_id) = mysql_fetch_row(mysql_query("select post_id from forums_posts where topic_id = '$topic_id' order by post_id desc limit 0,1"));
            mysql_query("update forums_topics set last_post_id = '$last_post_id', posts = '$posts' where topic_id = '$topic_id'");
        }

        $result = mysql_query("select forum_poll_id from forums_polls");
        while(list($poll_id) = mysql_fetch_row($result)){
            $total = 0;
            $result1 = mysql_query("select option_results from forums_poll_results where forum_poll_id = '$poll_id'") or die(mysql_error());
            while(list($r) = mysql_fetch_row($result1)){
                $total += $r;
            }
            mysql_query("update forums_polls set total = '$total' where forum_poll_id = '$poll_id'");
        }

        $hold = array();
        mysql_query("delete from forums_notify where forum_id = '0' and topic_id = '0'");
        mysql_query("delete from forums_notify where user_id = '1'");
        //$result = mysql_query("select forum_id from forums_forums");
        //while(list($forum_id) = mysql_fetch_row($result)){
        //    $result1 = mysql_query("select forum_notify_id, user_id from forums_notify where forum_id = '$forum_id'");
        //    while(list($id, $user_id) = mysql_fetch_row($result1)){
        //        if (!in_array($user_id, $hold)){ $hold[] = $user_id; }
        //        else { mysql_query("delete from forums_notify where forum_notify_id = '$id'"); }
        //    }
        //}

        //$hold = array();
        //$result = mysql_query("select topic_id from forums_topics");
        //while(list($topic_id) = mysql_fetch_row($result)){
        //    $result1 = mysql_query("select forum_notify_id, user_id from forums_notify where topic_id = '$topic_id'");
        //    while(list($id, $user_id) = mysql_fetch_row($result1)){
        //        if (!in_array($user_id, $hold)){ $hold[] = $user_id; }
        //        else { mysql_query("delete from forums_notify where forum_notify_id = '$id'"); }
        //    }
        //}

        $x=1;
        $result = mysql_query("select cat_id from forums_cat order by ord");
        while(list($cat_id) = mysql_fetch_row($result)){
            mysql_query("update forums_cat set ord = '$x' where cat_id = '$cat_id'");
            $x++;
            $j=1;
            $result1 = mysql_query("select forum_id from forums_forums where cat_id = '$cat_id'");
            while(list($forum_id) = mysql_fetch_row($result1)){
                mysql_query("update forums_forums set ord = '$j' where forum_id = '$forum_id'");
                $j++;
            }
        }
        mysql_query("delete from forums_notify where forums_id = '0' and topic_id = '0'");
        mysql_query("delete from forums_pm where deleted = '1'");
    }
}

class textFunctions {

    function convertText($text, $flag, $html=''){
        //! Converts text to text or to HTML
        //! flag is set to 1 for incoming
        if ($flag == 1){
            $text = str_replace("(", "&#40;", $text);
            $text = str_replace("'", "&#39;", $text);
            $text = str_replace(")", "&#41;", $text);
            $text = addslashes($text);
            if ($html == 1){ $text = htmlentities($text); }
            $ret = " " . $text;
            $ret = preg_replace("#([\n ])([a-z]+?)://([^, \n\r]+)#i", "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $ret);
            $ret = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \n\r]*)?)#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $ret);
            $ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([^, \n\r]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
            $text = substr($ret, 1);
            if ($_POST['replace'] == 1){ $text = str_replace("\r\n", "<br>", $text); }
            $text = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $text);
            if ($html == 2){ $text = strip_tags($text); }
        }
        else {
            if ($html == 1){
                $trans = get_html_translation_table(HTML_ENTITIES);
                $trans = array_flip($trans);
                $text = strtr($text, $trans);
            }
            if ($html == 3){ $text = str_replace("<br>", "\r\n", $text); }
            $text = str_replace("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "\t", $text);
            $text = stripslashes($text);
        }
        return $text;

    }

    function createFontForm(){

        $fontSize = array("11" => "Normal", "8" => "tiny", "18" => "big", "24" => "Huge");
        $flip = array_flip($fontSize);
        $fontColor = array("black", "red", "orange", "brown", "cyan", "blue", "darkblue", "violet", "green", "yellow", "white");
        $fontFace = array("arial", "verdana", "helvetica", "sans-serif", "times", "garamond", "futura", "courier", "geneva");

        sort($fontColor);
        sort($fontFace);

        $text = "Font: <select name=fontColor onChange='changeFontColor(this.form)'>";
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

    function createEditBox($page, $action, $body){
        if ($page == "news"){
            if ($action == create){ $text1 = "<td class=main width=100><a href='' onclick='createNews(); return false;'><img src='images/save_2.gif' alt='Save' border=0 align=absmiddle ></a> "; }
            else if ($_GET[action] == "sub"){ $text1 = "<td class=main width=100><a href='' onclick='submitNews(); return false;'><img src='images/save_2.gif' alt='Save' border=0 align=absmiddle ></a> "; }
            else { $text1 = "<td class=main width=100><a href='' onclick='modifyNews(); return false;'><img src='images/save_2.gif' alt='Save' border=0 align=absmiddle ></a> ";}
            $text1 .= "<a href='' onclick='newsPreview(); return false;'><img src='images/preview_2.gif' alt='Preview' border=0 ></a></td>";
        }
        else {
            //print_r($_GET);
            $parm = $_GET[create];
            $text1 = "<td class=main width=100><a href='' onclick='SubForm($parm); return false;'><img src='images/save_2.gif' alt='Save' border=0 align=absmiddle ></a> ";
            $text1 .= "<a href='' onclick=confirmDelete('page.php?action=delete&page_id=$page_id')><img src='images/delete.gif' alt='Delete' border=0 align=absmiddle ></a>";
            $text1 .= "<a href='' onclick='pagePreview(); return false;'><img src='images/preview_2.gif' alt='Preview' border=0 ></a></td>";
        }
        require("includes/webedit.inc.php");
        return $text;
    }

    function createMenuSelectForm($menu_id1){

        $cat = "<script language=javascript src=templates/list.js></script>";
        $cat .= "<script language=javascript>";
        $catList = "<option value=0></option>";
        $cat .= "var listB = new DynamicOptionList(\"cat_id_sub\",\"cat_id\");";
        $result = mysql_query("select menu_id, menu_name from menu where sub = '0' order by ord");
        while(list($menu_id, $menu_name) = mysql_fetch_row($result)){
            $selected = '';
            if ($menu_id == $menu_id1){ $selected = "selected"; }
            $result55 = mysql_query("select menu_id, menu_name from menu where sub = '$menu_id' order by ord");
            $count = mysql_num_rows($result55);
            if ($count != 0){

                $js .= "listB.addOptions(\"$menu_id\", \" \", \"0\"";
                while(list($menu_id2, $menu_name2) = mysql_fetch_row($result55)){
                    $x=0;
                    list($checkCount) = mysql_fetch_row(mysql_query("select count(*) from menu where sub = '$menu_id2' and menu_id = '$menu_id1'"));
                    if ($checkCount == 1){ $selected = "selected"; $x=1; }
                    if ($menu_id2 != $menu_id1){ $js .= ",\"$menu_name2\", \"$menu_id2\""; }
                    else { $selected = "selected"; }
                    if ($x==1){ $djs .= "listB.setDefaultOption(\"$menu_id\", \"$menu_id2\");";}
                }
                $js .= ");";
            }
            if ($menu_id != $menu_id1){ $catList .= "<option value=$menu_id $selected>$menu_name</option>"; }
        }
        $cat .= $js;
        $cat .= $djs;
        $cat .= "</script>";
        $cat .= "<select name=cat_id onChange=\"listB.populate()\">$catList</select>";
        $cat .= "<select name=cat_id_sub><script language=JavaScript>listB.printOptions();</script></select>";
        $cat .= "<script language=javascript>listB.init(document.forms[0]);</script>";
        return $cat;
    }

    function createInsertBox(){
        $js = "<script language=javascript>var insertArray = new Array();";
        $text = "Text Insertion: <select name=insert>";
        $result = mysql_query("select page_insert_id, page_insert_title, page_insert_text from page_insert order by page_insert_title");
        while(list($id, $title, $insert) = mysql_fetch_row($result)){
            $title = stripslashes($title);
            $insert = stripslashes($insert);
            $text .= "<option value=$id>$title</option>";
            $js .= "insertArray[$id] = '$insert';";
        }
        $text .= "</select> <input type=button value=Insert onclick=insertInsertText()>";
        $js .= "</script>";
        $text = $text . $js;
        return $text;
    }

    function createFeatureBox(){
        global $featureInsert;
        asort($featureInsert);
        $keys = array_keys($featureInsert);

        $text = "Feature Insertion: <select name=feature>";
        foreach($keys as $k){
            $text .= "<option value=$featureInsert[$k]>$k</option>";
        }
        $text .= "</select> <input type=button value=Insert onclick=setFeature()>";
        return $text;
    }

    function createSubFeatureBox(){
        global $subFeatureInsert;
        asort($subFeatureInsert);
        $keys = array_keys($subFeatureInsert);

        $text = "Sub-Feature Insertion: <select name=subfeature>";
        foreach($keys as $k){
            $text .= "<option value=$subFeatureInsert[$k]>$k</option>";
        }
        $text .= "</select> <input type=button value=Insert onclick=setSubFeature()>";
        return $text;
    }

    function createPollBox(){
        $result = mysql_query("select p.forum_poll_id, p.forum_poll_text from forums_polls p, forums_topics t, users u, forums_posts po where t.locked != '1' and u.admin = '1' and p.topic_id = t.topic_id and t.post_id = po.post_id and po.user_id = u.user_id order by t.topic_id desc") or die(mysql_error());
        $text = "Poll Insertion: <select name=poll>";
        while(list($poll_id, $poll_text) = mysql_fetch_row($result)){
            $poll_text = stripslashes($poll_text);
            $text .= "<option value=$poll_id>$poll_text</option>";
        }
        $text .= "</select> <input type=button value=Insert onclick=setPoll()>";
        return $text;
    }
}

class adminPage {

    function adminPage($body, $sub=''){
        global $progTitle;
        $core = new coreFunctions();
        $progVersion = $core->dbCall("version");

        if ($sub != 1){ include("templates/templateMain.inc.php"); }
        else { include("templates/template.inc.php"); }
    }


}


function userSelect(){

    $text .= "<tr><form><td class=main>View User: <select name=site onChange=formHandler(this.form)>";
    $text .= "<option value=''></option>";
    $result = mysql_query("select user_id, first_name, last_name from users order by first_name");
    while(list($user_id, $first_name, $last_name) = mysql_fetch_row($result)){
        $text .= "<option value=user.php?action=detail&user_id=$user_id>$first_name $last_name</option>";
    }
    $text .= "</select></td></form></tr>";
    return $text;
}

function getIndex($userinfo){

    require("includes/page.inc.php");
    $page = new webPage($userinfo);
}


?>
