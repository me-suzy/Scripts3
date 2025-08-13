<?php
#$Id: news.inc.php,v 1.19 2003/11/20 17:13:42 ryan Exp $

class newsModule{

    function newsModule($userinfo){
        $this->action = $_GET['action'];
        $this->news_id = $_GET['news_id'];

        $this->userinfo = $userinfo;
        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();
        $this->newsCat = $this->core->dbCall("news_categories");
        if ($this->news_id == ''){ $this->news_id = $_POST['news_id']; }

        switch($this->action){
            case create:
                $this->createNews();
                break;
            case delete:
                $this->deleteNews();
                break;
            case modify:
                $this->modifyNews();
                break;
            case cat:
                $this->newsCat();
                break;
            case sub:
                $this->newsApprove();
                break;
            default:
                $this->listNews();
        }
        $this->stroke();

    }

    function stroke(){
        new adminPage($this->body);
    }

    function createNews(){

        if ($_POST['confirm'] == 1){

            $time = $this->core->dbCall("timeZone");
            if ($_POST[date] == ''){ $date = time() - ($time*3600); $delay=0;}
            else {
                $ex = explode("/", $_POST[date]);
                $date = mktime($_POST[hour],$_POST[minute],"00", $ex[0],$ex[1],$ex[2]) - ($time*3600); $delay=1;
                $now = time() - ($time*3600);
                if ($date < $now){ $date = time() - ($time*3600); $delay=0; }
            }
            $body = $_POST['text'];
            $size = strlen($body);
            if (substr_count($body, "===SPLIT===") > 0){
                $pos = strpos($body, "\r\n===SPLIT===\r\n");
                $more = substr($body, $pos);
                $body = substr($body, 0, $pos);
                $more = str_replace("\r\n===SPLIT===\r\n", '', $more);
                $more = $this->textFun->convertText($more, 1);
            }

            $body = $this->textFun->convertText($body, 1);
            $title = $this->textFun->convertText($_POST['title'],1);
            $user = $this->userinfo[0];
            mysql_query("insert into news values ('', '$title', '$body', '$date', '$_POST[news_cat_id]', '$user', '$size', '$more', '$delay')");
            $news_id = mysql_insert_id();
            $this->newsNotify($news_id);
            $this->core->addLog("News Created", $this->userinfo[1], $news_id);
            header("Location: news.php?action=modify&news_id=$news_id");

        }
        else {
            if ($this->newsCat == 1){ $catForm = $this->newsCatSelect(); }
            $fontForm = $this->textFun->createFontForm();
            $text .= "<script language=\"JavaScript\" src=\"templates/page-edit.js\"></script>";
            $text .= "<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>";
            $text .= "<tr>";
            $text .= "<td class=title><img src=images/_page.gif border=0>Create News</td>";
            $text .= "</tr>";
            $text .= "<tr>";
            $text .= "<td class=main>";
            $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
            $text .= "<form method=post name=doc_html>";
            $text .= "<input type=hidden name=confirm value=1>";
            //$text .= "<input type=hidden name=page_id value=$page_id>";
            //$text .= "<input type=hidden name=preview value=1>";
            $text .= "<tr><td class=main>Title <input type=text value='$title' size=25 name=title> &nbsp; $catForm";
            $text .= "Release Date (leave blank if posting now): <input type=text size=10 name=date value='$date'> <a href='javascript:cal5.popup();' onMouseOver=\"window.status='Show Calendar';return true\" onMouseOut=\"window.status='';return true\"><img src=images/cal.gif width=16 height=16 border=0 alt='Calendar'></a>";
            $text .= " " . $this->createTime();
            $text .= "<script language=JavaScript src=templates/cal.js></script>";
            $text .= "<script language=JavaScript>";
            $text .= "var cal5 = new calendar2(document.forms['doc_html'].elements['date']);";
            $text .= "cal5.year_scroll = true; cal5.time_comp = false; </script>";
            $text .= "</td></tr>";
            $text .= "<tr><td class=main>$fontForm &nbsp; &nbsp; <input type=checkbox name=replace value=1 checked>Preserve Line Breaks";
            $text .= " &nbsp; <input type=button value='Split Article' onclick=insertMoreSplit()></td></tr>";
            $text .= $this->textFun->createEditBox("news", "create", '');
            $text .= "</td></tr></table>";
            $this->body = $text;
        }

    }

    function deleteNews(){
        $sql = "delete from news where news_id = '" . $this->news_id . "'";
        mysql_query($sql);
        $this->core->addLog("News Deleted", $this->$userinfo[1], $news_id);
        header("Location: news.php");
    }

    function modifyNews(){

        if ($_POST['confirm'] == 1){

            if ($_POST[date] != ''){
                $date = strtotime($_POST[date]);
                if ($date > time()){
                     $time = $this->core->dbCall("timeZone");
                     $ex = explode("/", $_POST[date]);
                     $date = mktime($_POST[hour],$_POST[minute],"00", $ex[0],$ex[1],$ex[2]) - ($time*3600); $delay=1;
                }
                else { $delay = 0; }
            }
            else { $date = $_POST[old_date]; }



            $body = $_POST['text'];
            $size = strlen($body);
            if (substr_count($body, "===SPLIT===") > 0){
                $pos = strpos($body, "\r\n===SPLIT===\r\n");
                $more = substr($body, $pos);
                $body = substr($body, 0, $pos);
                $more = str_replace("\r\n===SPLIT===\r\n", '', $more);
                $more = $this->textFun->convertText($more, 1);
            }

            $body = $this->textFun->convertText($body, 1);
            $title = $this->textFun->convertText($_POST['title'],1);
            $sql = "update news set title = '$title', post = '$body', news_cat_id = '$_POST[news_cat_id]', delay = '$delay', more = '$more', size = '$size', date = '$date' where news_id = '" . $this->news_id . "'";

            mysql_query($sql) or die(mysql_error());
            $this->core->addLog("News Modified", $userinfo[1], $this->news_id);
            header("HTTP/1.0 204 No Content");
        }
        else {
            $news_id = $this->news_id;
            list($body, $title, $date, $more, $cat_id, $delay) = mysql_fetch_row(mysql_query("select post, title, date, more, news_cat_id, delay from news where news_id = '$news_id'"));

            $body = $this->textFun->convertText($body, 0, 3);
            if ($more != ''){
                $more = $this->textFun->convertText($more, 0, 3);
                $body = $body . "\r\n===SPLIT===\r\n" . $more;
            }

            if ($delay == 1){
                $time = $this->core->dbCall("timeZone");
                $date = $date + ($time*3600);
                $date = date("m/d/y::H:i", $date);
                $ex = explode("::", $date);
                $date = $ex[0];
                $ex = explode(":", $ex[1]);
                $hour = $ex[0]; $min = $ex[1];
                $date = "<input type=hidden name=old_date value=$date>Release Date (leave blank if posting now): <input type=text size=10 name=date value='$date'> <a href='javascript:cal5.popup();' onMouseOver=\"window.status='Show Calendar';return true\" onMouseOut=\"window.status='';return true\"><img src=images/cal.gif width=16 height=16 border=0 alt='Calendar'></a>";
                $date .= " " . $this->createTime($hour, $min);
                $date .= "<script language=JavaScript src=templates/cal.js></script>";
                $date .= "<script language=JavaScript>";
                $date .= "var cal5 = new calendar2(document.forms['doc_html'].elements['date']);";
                $date .= "cal5.year_scroll = true; cal5.time_comp = false; </script>";
            }
            else { $date = "<input type=hidden name=old_date value=$date>"; }
            $title = $this->textFun->convertText($title,0);
            if ($this->newsCat == 1){ $catForm = $this->newsCatSelect($cat_id); }
            $fontForm = $this->textFun->createFontForm();
            $text .= "<script language=\"JavaScript\" src=\"templates/page-edit.js\"></script>";
            $text .= "<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>";
            $text .= "<tr>";
            $text .= "<td class=title><img src=images/_page.gif border=0>Modify News</td>";
            $text .= "</tr>";
            $text .= "<tr>";
            $text .= "<td class=main>";
            $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
            $text .= "<form method=post name=doc_html>";
            $text .= "<input type=hidden name=news_id value=$news_id>";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=main>Title <input type=text value='$title' size=25 name=title> &nbsp; $catForm &nbsp;&nbsp; $date";

            $text .= "</td></tr>";
            $text .= "<tr><td class=main>$fontForm &nbsp; &nbsp; <input type=checkbox name=replace value=1 checked>Preserve Line Breaks";
            $text .= " &nbsp; <input type=button value='Split Article' onclick=insertMoreSplit()></td></tr>";
            $text .= "<tr><td class=main><div class=attn id=actionResult style=\"display:none\">News Saved</div></td></tr>";
            $text .= $this->textFun->createEditBox("news", "modify", $body);
            $text .= "</td></tr></table>";
            $this->body = $text;
        }

    }

    function listNews(){


        $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=100%>";
        $text .= "<tr>";
        $text .= "<td class=title colspan=2><img src=images/_page.gif border=0>List News Items</td>";
        $text .= "</tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        $text .= "<tr><td class=mainbold width=100>Title</td><td class=mainbold>Date</td><td class=mainbold>Category</td><td class=mainbold>Actions</td></tr>";
        list($page_id) = mysql_fetch_row(mysql_query("select page_id from pages where body = ':NEWS:'"));
        $result = mysql_query("select news_id, title, date, news_cat_id, delay from news order by date desc");
        while(list($news_id, $title, $date, $cat_id, $delay) = mysql_fetch_row($result)){
            if ($this->core->dbCall("news_categories") == 1){
                list($cat) = mysql_fetch_row(mysql_query("select news_cat_title from news_cat where news_cat_id = '$cat_id'"));
            }
            $date = $this->core->getTime($date);
            $date = date($this->core->dbCall("dateFormat") . " H:i", $date);
            if ($delay == 1){ $date = "<b>$date</b>"; }
            else { $date = $date; }
            $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC';\" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
            $text .= "<td class=hover>$title</td><td class=hover>$date</td><td class=hover>$cat</td>";
            $text .= "<td class=hover><a href=../index.php?id=$page_id target=new><img src=images/_page.gif border=0 alt='View'></a> <a href=news.php?action=modify&news_id=$news_id><img src=images/bedit.gif alt='Modify' border=0></a> <a href=javascript:confirmDelete('news.php?action=delete&news_id=$news_id')><img src=images/bdelete.gif alt='Delete' border=0></a></td>";
            $text .= "</tr>";
        }
        $text .= "</table></td></tr></table>";
        $this->body = $text;
    }

    function newsCatSelect($cat=''){
        $text = "&nbsp; Category: <select name=news_cat_id>";
        $result = mysql_query("select news_cat_id, news_cat_title from news_cat order by news_cat_title");
        while(list($id, $title) = mysql_fetch_row($result)){
            if ($id == $cat){ $s = "selected"; }
            else { $s = ''; }
            $title = stripslashes($title);
            $text .= "<option value=$id $s>$title</option>";
        }
        $text .= "</select> &nbsp;";
        return $text;

    }

    function newsApprove(){
        if ($_POST[confirm] == 1){  //Enter the post
            list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$_POST[user_id]'"));
            if ($_POST[user_id] != 1){ $username = "<a href=users.php?action=view&user_id=$_POST[user_id]>$username</a>"; }

            $time = $this->core->dbCall("timeZone");
            if ($_POST[date] == ''){ $date = time() - ($time*3600); $delay=0;}
            else {
                $date = strtotime($_POST[date])  - ($time*3600); $delay=1;
                $now = time() - ($time*3600);
                if ($date < $now){ $date = time() - ($time*3600); $delay=0; }
            }
            $body = $_POST['text'];
            $size = strlen($body);
            if (substr_count($body, "===EDITOR===") > 0){
                $pos = strpos($body, "\r\n===EDITOR===\r\n");
                $edit = substr($body, $pos);
                $body = substr($body, 0, $pos);
                $edit = str_replace("\r\n===EDITOR===\r\n", '', $edit);
                $edit = "<br>" . $this->textFun->convertText($edit, 1);
            }
            if (substr_count($body, "===SPLIT===") > 0){
                $pos = strpos($body, "\r\n===SPLIT===\r\n");
                $more = substr($body, $pos);
                $body = substr($body, 0, $pos);
                $more = str_replace("\r\n===SPLIT===\r\n", '', $more);
                $more = $this->textFun->convertText($more, 1);
            }

            $body = "$username writes <i>\"" . $this->textFun->convertText($body, 1) . "\"</i>" . $edit;
            $title = $this->textFun->convertText($_POST['title'],1);

            mysql_query("insert into news values ('', '$title', '$body', '$date', '$_POST[news_cat_id]', '$user', '$size', '$more', '$delay')") or die(mysql_error());
            $news_id = mysql_insert_id();
            $this->newsNotify($news_id);
            $this->core->addLog("News Created", $this->userinfo[1], $news_id);
            $this->core->addLog("News Submission Approved", $this->userinfo[1], $_POST[news_sub_id]);
            mysql_query("update news_sub set news_sub_post = '', status_id = '7' where news_sub_id = '$_POST[news_sub_id]'");
            header("Location: news.php?action=sub");



        }
        else if ($_GET[subaction] == "delete"){    //delete the post
            mysql_query("update news_sub set news_sub_post = '', status_id = '6' where news_sub_id = '$_GET[news_sub_id]'");
            $this->core->addLog("Submission Rejected", $this->userinfo[1], $_GET[news_sub_id]);
            header("Location: news.php?action=sub");
        }
        else if ($_GET[subaction] == "view"){

            list($body, $title, $date, $cat_id, $user_id) = mysql_fetch_row(mysql_query("select news_sub_post, news_sub_title, news_sub_date, news_cat_id, user_id from news_sub where news_sub_id = '$_GET[news_sub_id]'"));

            $body = $this->textFun->convertText($body, 0, 3);
            $title = $this->textFun->convertText($title,0);
            if ($this->newsCat == 1){ $catForm = $this->newsCatSelect($cat_id); }
            $fontForm = $this->textFun->createFontForm();
            $text .= "<script language=\"JavaScript\" src=\"templates/page-edit.js\"></script>";
            $text .= "<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>";
            $text .= "<tr>";
            $text .= "<td class=title><img src=images/_page.gif border=0>Modify News</td>";
            $text .= "</tr>";
            $text .= "<tr>";
            $text .= "<td class=main>";
            $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
            $text .= "<form method=post name=doc_html>";
            $text .= "<input type=hidden name=news_id value=$news_id>";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<input type=hidden name=user_id value=$user_id>";
            $text .= "<input type=hidden name=news_sub_id value=$_GET[news_sub_id]>";
            $text .= "<tr><td class=main>Title <input type=text value='$title' size=25 name=title> &nbsp; $catForm &nbsp;&nbsp;&nbsp ";
            $text .= "Release Date (Leave blank if today): <input type=text size=10 name=date value=''> <a href='javascript:cal5.popup();' onMouseOver=\"window.status='Show Calendar';return true\" onMouseOut=\"window.status='';return true\"><img src=images/cal.gif width=16 height=16 border=0 alt='Calendar'></a>";
            $text .= "<script language=JavaScript src=templates/cal.js></script>";
            $text .= "<script language=JavaScript>";
            $text .= "var cal5 = new calendar2(document.forms['doc_html'].elements['date']);";
            $text .= "cal5.year_scroll = true; cal5.time_comp = false; </script>";
            $text .= "</td></tr>";
            $text .= "<tr><td class=main>$fontForm &nbsp; &nbsp; <input type=checkbox name=replace value=1 checked>Preserve Line Breaks";
            $text .= " &nbsp; <input type=button value='Split Article' onclick=insertMoreSplit()> &nbsp; <input type=button value='Insert Editor Comments' onclick=insertEditorSplit()></td></tr>";

            $text .= $this->textFun->createEditBox("news", "modify", $body);
            $text .= "</td></tr></table>";
            $this->body = $text;
        }


        else { //list psots

            $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=100%>";
            $text .= "<tr>";
            $text .= "<td class=title colspan=2><img src=images/_page.gif border=0>News Submissions Awaiting Action</td>";
            $text .= "</tr>";
            $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
            $text .= "<tr><td class=mainbold>Submitted by</td><td class=mainbold>Title</td><td class=mainbold>Date</td><td class=mainbold>Category</td><td class=mainbold>Actions</td></tr>";
            $result = mysql_query("select s.news_sub_id, u.username, s.news_sub_title, s.news_sub_date, s.news_cat_id from news_sub s, users u where s.status_id = '3' and u.user_id = s.user_id order by s.news_sub_date") or die(mysql_error());
            while(list($sub_id, $username, $title, $date, $cat_id) = mysql_fetch_row($result)){
                if ($this->newsCat == 1){
                    list($cat) = mysql_fetch_row(mysql_query("select news_cat_title from news_cat where news_cat_id = '$cat_id'"));
                    $cat = stripslashes($cat);
                }
                else { $cat = "N/A"; }
                $title = stripslashes($title);
                $date = $this->core->getTime($date);
                $date = date($this->core->dbCall("dateFormat") . " H:i", $date);
                $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC';\" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
                $text .= "<td class=hover>$username</td><td class=hover>$title</td><td class=hover>$date</td><td class=hover>$cat</td>";
                $text .= "<td class=hover><a href=news.php?action=sub&subaction=view&news_sub_id=$sub_id><img src=images/bedit.gif alt='View' border=0></a> <a href=javascript:confirmDelete('news.php?action=sub&subaction=delete&news_sub_id=$sub_id')><img src=images/bdelete.gif alt='Delete' border=0></a></td>";
                $text .= "</tr>";
            }
            $text .= "</table></td></tr></table>";
        }
        $this->body = $text;
    }

    function newsCat(){
        //create, delete, modify including images!
        global $image_types;
        if ($_POST[confirm] == 1){
            switch($_GET[subaction]){
                case modify:
                    $title = $this->textFun->convertText($_POST[title], 1);
                    mysql_query("update news_cat set news_cat_title = '$title' where news_cat_id = '$_GET[news_cat_id]'");
                    $check = $_FILES['image'][tmp_name];
                    if ($check != ''){
                        $file_type = $_FILES['image'][type];
                        if (in_array($file_type, $image_types)){
                            $filesend =  "../images/news/" . $_FILES['image']['name'];
                            copy($check, $filesend);
                            $file = $_FILES['image']['name'];
                            mysql_query("update news_cat set news_cat_image = '$file' where news_cat_id = '$_GET[news_cat_id]'");
                        }
                    }
                    $this->core->addLog("News Category Modified", $this->userinfo[1], $_GET[news_cat_id]);
                    header("Location: news.php?action=cat");
                    break;

                case create:
                    $title = $this->textFun->convertText($_POST[title], 1);
                    mysql_query("insert into news_cat values ('', '$title', '')");
                    $news_cat_id = mysql_insert_id();
                    $check = $_FILES['image'][tmp_name];
                    if ($check != ''){
                        $file_type = $_FILES['image'][type];
                        if (in_array($file_type, $image_types)){
                            $filesend =  "../images/news/" . $_FILES['image']['name'];
                            copy($check, $filesend);
                            $file = $_FILES['image']['name'];
                            mysql_query("update news_cat set news_cat_image = '$file' where news_cat_id = '$news_cat_id'");
                        }
                    }
                    $this->core->addLog("News Category Created", $this->userinfo[1], $_GET[news_cat_id]);
                    header("Location: news.php?action=cat");
                    break;
            }

        }
        else {
            switch($_GET[subaction]){
                case delete:

                    mysql_query("delete from news_cat where news_cat_id = '$_GET[news_cat_id]'");
                    list($cat_id) = mysql_fetch_row(mysql_query("select news_cat_id from news_cat limit 0,1"));
                    mysql_query("update news set news_cat_id = '$cat_id' where news_cat_id = '$_GET[news_cat_id]'");
                    $this->core->addLog("News Category Deleted", $this->userinfo[1], $_GET[news_cat_id]);
                    header("Location: news.php?action=cat");
                    break;
                case modify:
                    list($title) = mysql_fetch_row(mysql_query("select news_cat_title, news_cat_image from news_cat where news_cat_id = '$_GET[news_cat_id]'"));
                    $title = $this->textFun->convertText($title,2);
                    if ($image != ''){ $image = "<img src=../images/news/$image>"; }
                    $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=100%>";
                    $text .= "<tr>";
                    $text .= "<td class=title colspan=2><img src=images/_page.gif border=0>Modify News Category</td>";
                    $text .= "</tr>";
                    $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
                    $text .= "<form method=post action=news.php?subaction=modify&action=cat&news_cat_id=$_GET[news_cat_id] enctype='multipart/form-data' onsubmit=\"return validateForm(this)\">";
                    $text .= "<input type=hidden name=confirm value=1>";
                    $text .= "<tr><td class=mainbold>Title: </td><td class=main><input type=text value='$title' name=title size=40></td><td class=main rowspan=2>$image</td></tr>";
                    $text .= "<tr><td class=mainbold>Image: </td><td class=main><input type=file name=image size=30></td></tr>";
                    $text .= "<tr><td class=main align=center colspan=3><input type=submit value='Enter'></td></form></tr></table></td></tr></table>";
                    $text .= "<script Language=JavaScript>";
                    $text .= "function validateForm(theForm)";
                    $text .= "{";
                    $text .= "    if (!validRequired(theForm.title,\"Title\"))";
                    $text .= "        return false;";
                    $text .= "    return true;";
                    $text .= "}";
                    $text .= "</script>";
                    break;

                case create:
                    $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=100%>";
                    $text .= "<tr>";
                    $text .= "<td class=title colspan=2><img src=images/_page.gif border=0>Create News Category</td>";
                    $text .= "</tr>";
                    $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
                    $text .= "<form method=post action=news.php?subaction=create&action=cat enctype='multipart/form-data' onsubmit=\"return validateForm(this)\">";
                    $text .= "<input type=hidden name=confirm value=1>";
                    $text .= "<tr><td class=mainbold>Title: </td><td class=main><input type=text value='$title' name=title size=40></td></tr>";
                    $text .= "<tr><td class=mainbold>Image: </td><td class=main><input type=file name=image size=30></td></tr>";
                    $text .= "<tr><td class=main align=center colspan=2><input type=submit value='Enter'></td></form></tr></table></td></tr></table>";
                    $text .= "<script Language=JavaScript>";
                    $text .= "function validateForm(theForm)";
                    $text .= "{";
                    $text .= "    if (!validRequired(theForm.title,\"Title\"))";
                    $text .= "        return false;";
                    $text .= "    return true;";
                    $text .= "}";
                    $text .= "</script>";
                    break;

                default:
                    $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=100%>";
                    $text .= "<tr>";
                    $text .= "<td class=title colspan=2><img src=images/_page.gif border=0>News Category List</td>";
                    $text .= "</tr>";
                    $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
                    $text .= "<tr><td class=mainbold>Category</td><td class=mainbold>Image</td><td class=mainbold>Actions</td></tr>";

                    $result = mysql_query("select news_cat_id, news_cat_title, news_cat_image from news_cat order by news_cat_title");
                    while(list($id, $title, $image) = mysql_fetch_row($result)){
                        $title = $this->textFun->convertText($title,2);
                        if ($image != ''){ $image = "<img src=../images/news/$image>"; }
                        $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC'; \" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
                        $text .= "<td class=hover>$title</td><td class=hover>$image</td>";
                        $text .= "<td class=hover><a href=news.php?action=cat&subaction=modify&news_cat_id=$id><img src=images/bedit.gif alt='Modify Entry' border=0></a> ";
                        $text .= "<a href=javascript:confirmDelete('news.php?action=cat&subaction=delete&news_cat_id=$id')><img src=images/bdelete.gif border=0 alt='Delete Entry'></a></td></tr>";
                    }
                    $text .= "</table></td></tr></table>";
            }
            $this->body = $text;
        }
    }

    function newsNotify($news_id){

        $result  = mysql_query("select title, news_cat_id from news where news_id = '$news_id'") or die(mysql_error());
        list($title, $cat_id) = mysql_fetch_row($result);
        $title = stripslashes($title);

        $siteName = $this->core->dbCall("siteName");
        $webmasterEmail = $this->core->dbCall("webmasterEmail");
        $siteURL = $this->core->dbCall("siteURL");
        $headers = "From: $siteName <no-reply@no-reply.com> \r\n";
        $headers .= "X-Sender: <no-reply@no-reply.com>\r\n";
        $headers .= "X-Mailer: PHP\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= "Reply-To: no-reply@no-reply.com\r\n";
        $subject = $siteName . " - " . $title;
        $message = "$siteURL/news.php?news_id=$news_id";

        $result = mysql_query("select u.user_id, u.email from users u where u.news_notify = '1'");
        while(list($user_id, $email) = mysql_fetch_row($result)){
            list($total) = mysql_fetch_row(mysql_query("select count(*) from user_news_view where user_id = '$user_id'"));
            list($cat) = mysql_fetch_row(mysql_query("select count(*) from user_news_view where user_id = '$user_id' and news_cat_id = '$cat_id'"));
            if ($total == 0 || $cat > 0){ mail($email, $subject, $message, $headers); }
        }
    }

    function createTime($hour='', $min=''){
        $text = "Time: <select name=hour>";
        for($x=0;$x<25;$x++){
            if ($x == 0){ $x = '00'; }
            if ($x == $hour){ $s = "selected";} else { $s = ''; }
            $text .= "<option value=$x $s>$x</option>";
        }
        $text .= "</select> <select name=minute>";
        for($x=0;$x<59;$x+=5){
            if ($x == 0){ $x = '00'; }
            if ($x == 5){ $x = '05'; }
            if ($x == $min){ $s = "selected";} else { $s = ''; }
            $text .= "<option value=$x $s>$x</option>";
        }
        $text .= "</select>";
        return $text;

    }
}
