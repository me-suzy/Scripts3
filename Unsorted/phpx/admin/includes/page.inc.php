<?php
#$Id: page.inc.php,v 1.17 2003/10/29 18:51:45 ryan Exp $

class webPage {

    var $body;

    function webPage($userinfo){
        $this->userinfo = $userinfo;
        $this->page_id = $_GET['page_id'];

        if ($this->page_id == ''){ $this->page_id = $_POST['page_id']; }

        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();

        switch($_GET['action']){
            case create:
                $this->createWebPage();
                break;
            case modify:
                $this->modifyWebPage();
                break;
            case delete:
                $this->deleteWebPage();
                break;
            case insert:
                $this->pageInsert();
                break;
            default:
                $this->pageListing();
        }
        $this->stroke();
    }

    function stroke(){
        new adminPage($this->body);
    }


    function createWebPage(){

        $time = time();
        mysql_query("insert into pages (title) values ('New Page $time')");
        $page_id = mysql_insert_id();
        $this->core->addLog("Page Created", $this->userinfo[1], $page_id);
        header("Location: page.php?action=modify&page_id=$page_id&create=1");

    }

    function modifyWebPage(){
        $page_id = $this->page_id;

        if ($_POST['confirm'] == 1){
            if ($_FILES['file']['name'] != '' && $_FILES['file']['name'] != "none"){
                $fileFlag=1;
                $body = '';
                $title = '';
                $text = file($_FILES['file']['tmp_name']);
                foreach($text as $record){
                    $body .= $record;
                }
                $start_pos = strpos($body, "<title>");
                $end_pos = strpos($body, "</title>");
                $title = substr($body, $start_pos, $end_pos);
                $body = substr($body, $end_pos);

                $title = strip_tags($title);
                $body = strip_tags($body, '<blink><script><center><div><a><b><i><u><ul><ol><hr><table><td><tr><th><h1><h2><h3><font><li><br><p><img><blockquote><strong>');
            }
            else {

                $title = $_POST['title'];
                $body = $_POST['text'];
                $body = str_replace("size=huge", "size=+3", $body);
                $body = str_replace("size=large", "size=+2", $body);
                $body = str_replace("size=small", "size=-1", $body);
            }

            $body = $this->textFun->convertText($body, 1);
            $title = $this->textFun->convertText($title, 1);
            $link = $_POST['link'];
            $menu_id = $_POST['cat_id'];
            $menu_sub_id = $_POST['cat_id_sub'];

            $link = "index.php?id=" . $page_id;
            mysql_query("update menu set menu_name = '$title' where link = '$link'");
            /*
            list($old, $old_sub) = mysql_fetch_row(mysql_query("select menu_id, sub from menu where link = '$link'"));
            if ($menu_sub_id != ''){ $menu_id = $menu_sub_id; }
            list($ord) = mysql_fetch_row(mysql_query("select ord from menu where sub = '$menu_id'"));
            $ord++;
            if ($old == '' && $_GET[create] == 1){ mysql_query("insert into menu values ('', '$title', '$link', '0', '$menu_id', '$ord')"); }
            else if ($old_sub != $menu_id){ mysql_query("update menu set ord = '$ord', sub = '$menu_id' where menu_id = '$old'"); }
            */
            mysql_query("update pages set title = '$title', body = '$body', parse_php = '$_POST[parse]' where page_id = '$page_id'") or die(mysql_error());



            if ($_POST['page_index'] == on){
                mysql_query("update pages set page_index = '0'");
                mysql_query("update pages set page_index = '1' where page_id = '$page_id'");
            }

            $this->core->addLog("Page Modified", $this->userinfo[1], $page_id);
            if (!isset($fileFlag)){ header("HTTP/1.0 204 No Content"); }
            else { header("Location: page.php?action=modify&page_id=$page_id"); }
        }
        else {

            $result = mysql_query("select body, title, page_index, parse_php from pages where page_id = '$page_id'");
            list($body, $title, $page_index, $parse) = mysql_fetch_row($result);

            $check = "index.php?id=" . $page_id;
            list($menu_id) = mysql_fetch_row(mysql_query("select menu_id from menu where link = '$check' limit 0,1"));
            $body = $this->textFun->convertText($body, 0);
            $title = $this->textFun->convertText($title, 0);
            $contingut_inicial=$body;
            $fontForm = $this->textFun->createFontForm();
            $insertForm = $this->textFun->createInsertBox();
            $featureForm = $this->textFun->createFeatureBox();
            $subForm = $this->textFun->createSubFeatureBox();
            $pollForm = $this->textFun->createPollBox();

            if ($parse == 1){ $c = "checked"; }
            else { $c = ''; }
            $parseForm = "Parse as PHP: <input type=checkbox value=1 name=parse $c>";

            //$menu = $this->textFun->createMenuSelectForm($menu_id);
            if ($page_index == 0){ $pageIndex = "Default Page <input type=checkbox name=page_index>"; }
            else { $pageIndex = "Default Page <input type=checkbox name=page_index checked>"; }
            $text .= "<script language=\"JavaScript\" src=\"templates/page-edit.js\"></script>";
            $text .= "<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>";
            $text .= "<tr>";
            $text .= "<td class=title><img src=images/_layout.gif border=0>Web Pages</td>";
            $text .= "</tr>";
            $text .= "<tr>";
            $text .= "<td class=main>";
            $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
            $text .= "<form method=post name=doc_html enctype='multipart/form-data'>";
            $text .= "<input type=hidden name=page_id value=$page_id>";
            $text .= "<input type=hidden name=preview value=1>";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=main>Title <input type=text value='$title' size=25 name=title> &nbsp; &nbsp; $menu &nbsp; &nbsp;  $pollForm &nbsp; &nbsp; $subForm</td></tr>";
            $text .= "<tr><td class=main>$fontForm &nbsp; &nbsp; $pageIndex $insertForm &nbsp; $featureForm</td></tr>";
            $text .= "<tr><td class=main>Import Page: <input type=file size=25 name=file> &nbsp; $parseForm</td></tr>";
            $text .= "<tr><td class=main><div class=attn id=actionResult style=\"display:none\">Page Saved</div></td></tr>";
            $text .= $this->textFun->createEditBox("page", "modify", $body);
            $text .= "</td></tr></table>";
            $this->body = $text;
        }
    }

    function deleteWebPage(){
        $page_id = $this->page_id;
        $link = "index.php?id=$page_id";
        mysql_query("delete from menu where link = '$link'");
        mysql_query("delete from pages where page_id = '$page_id'");
        $this->core->addLog("Page Deleted", $this->userinfo[1], $page_id);
        header("Location: page.php");
    }

    function pageListing(){

        $text .= "<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>";
        $text .= "<tr>";
        $text .= "<td class=title><img src=images/_layout.gif border=0><b> Web Pages </b></td>";
        $text .= "</tr>";
        $text .= "<tr>";
        $text .= "<td class=main>";
        $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
        $text .= "<tr><td class=mainbold>Page Title</td><td class=mainbold>Menu Placement</td><td class=mainbold>Actions</td></tr>";
        $result = mysql_query("select page_id, title, page_index from pages order by title");
        while(list($page_id, $title, $page_index) = mysql_fetch_row($result)){
            $menu = '';
            $link = "index.php?id=$page_id";
            $title = stripslashes($title);
            list($menu_id, $menu_name, $sub) = mysql_fetch_row(mysql_query("select menu_id, menu_name, sub from menu where link = '$link'"));
            if ($sub != '' && $sub != 0){
                list($menu_name1, $sub1) = mysql_fetch_row(mysql_query("select menu_name, sub from menu where menu_id = '$sub'"));
                if ($sub1 != '' && $sub1 != 0){
                    list($menu_name2) = mysql_fetch_row(mysql_query("select menu_name from menu where menu_id = '$sub1'"));
                    $menu = "$menu_name2 : ";
                }
                $menu = $menu . "$menu_name1 : ";
            }
            $menu = $menu . "$menu_name";
            $menu = stripslashes($menu);
            $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC';\" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
            $text .= "<td class=hover>$title</td><td class=hover>$menu</td>";
            $text .= "<td class=hover><a href=../index.php?id=$page_id target=new><img src=images/_page.gif border=0 alt='View'></a> <a href=page.php?action=modify&page_id=$page_id><img src=images/bedit.gif alt='Modify' border=0></a> <a href=javascript:confirmDelete('page.php?action=delete&page_id=$page_id')><img src=images/bdelete.gif alt='Delete' border=0></a></td>";
            $text .= "</tr>";
        }

        $text .= "</table>";
        $text .= "</td></tr></table>";
        $this->body = $text;
    }

    function pageInsert(){
        //form in page/news  also create help functions

        if ($_GET['subaction'] == "add"){
            if ($_POST[confirm] == 1){
                $title = strip_tags(addslashes($_POST[title]));
                $insert = addslashes(str_replace("\r\n", "==rn==", $_POST[insert]));
                mysql_query("insert into page_insert values ('', '$title', '$insert')");
                $page_insert_id = mysql_insert_id();
                $this->core->addLog("Add Page Insert", $this->userinfo[1], $page_insert_id);
                header("Location: page.php?action=insert");
            }
            else {
                $text = "<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>";
                $text .= "<tr>";
                $text .= "<td class=title><b> Page Insertion </b></td>";
                $text .= "</tr>";
                $text .= "<tr>";
                $text .= "<td class=main>";
                $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
                $text .= "<form method=post action=page.php?action=insert&subaction=$_GET[subaction] onsubmit=\"return validateForm(this)\">";
                $text .= "<input type=hidden name=confirm value=1><input type=hidden name=page_insert_id value=$_GET[page_insert_id]>";
                $text .= "<tr><td class=mainbold>Title: </td><td class=main><input type=text value='$title' name=title size=40></td></tr>";
                $text .= "<tr><td class=mainbold>Text: </td><td class=main><textarea name=insert cols=40 rows=10>$insert</textarea></td></tr>";
                $text .= "<tr><td class=main colspan=2 align=center><input type=submit value='Enter'></td></tr></form>";
                $text .= "<script Language=JavaScript>";
                $text .= "function validateForm(theForm)";
                $text .= "{";
                $text .= "    if (!validRequired(theForm.title,\"Title\"))";
                $text .= "        return false;";
                $text .= "    if (!validRequired(theForm.insert,\"Text\"))";
                $text .= "        return false;";
                $text .= "    return true;";
                $text .= "}";
                $text .= "</script>";
                $text .= "</table></td></tr></table>";
                $this->body = $text;

            }



        }
        else if ($_GET['subaction'] == "modify"){
            if ($_POST[confirm] == 1){
                $title = strip_tags(addslashes($_POST[title]));
                $insert = addslashes(str_replace("\r\n", "==rn==", $_POST[insert]));
                mysql_query("update page_insert set page_insert_title = '$title', page_insert_text = '$insert' where page_insert_id = '$_POST[page_insert_id]'");
                $this->core->addLog("Update Page Insert", $this->userinfo[1], $_POST[page_insert_id]);
                header("Location: page.php?action=insert");
            }
            else {
                list($title, $insert) = mysql_fetch_row(mysql_query("select page_insert_title, page_insert_text from page_insert where page_insert_id = '$_GET[page_insert_id]'"));
                $title = stripslashes($title);
                $insert = stripslashes($insert);
                $insert = addslashes(str_replace("==rn==", "\r\n", $_POST[insert]));
                $text = "<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>";
                $text .= "<tr>";
                $text .= "<td class=title><b> Page Insertion </b></td>";
                $text .= "</tr>";
                $text .= "<tr>";
                $text .= "<td class=main>";
                $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
                $text .= "<form method=post action=page.php?action=insert&subaction=$_GET[subaction] onsubmit=\"return validateForm(this)\">";
                $text .= "<input type=hidden name=confirm value=1><input type=hidden name=page_insert_id value=$_GET[page_insert_id]>";
                $text .= "<tr><td class=mainbold>Title: </td><td class=main><input type=text value='$title' name=title size=40></td></tr>";
                $text .= "<tr><td class=mainbold>Text: </td><td class=main><textarea name=insert cols=40 rows=10>$insert</textarea></td></tr>";
                $text .= "<tr><td class=main colspan=2 align=center><input type=submit value='Enter'></td></tr></form>";
                $text .= "<script Language=JavaScript>";
                $text .= "function validateForm(theForm)";
                $text .= "{";
                $text .= "    if (!validRequired(theForm.title,\"Title\"))";
                $text .= "        return false;";
                $text .= "    if (!validRequired(theForm.insert,\"Text\"))";
                $text .= "        return false;";
                $text .= "    return true;";
                $text .= "}";
                $text .= "</script>";
                $text .= "</table></td></tr></table>";
                $this->body = $text;

            }
        }
        else if ($_GET['subaction'] == "delete"){
            mysql_query("delete from page_insert where page_insert_id = '$_GET[page_insert_id]'");
            $this->core->addLog("Page Insert Deleted", $this->userinfo[1], $_GET[page_insert_id]);
            header("Location: page.php?action=insert");
        }
        else {
            $text = "<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>";
            $text .= "<tr>";
            $text .= "<td class=title><b> Text Insertions </b></td>";
            $text .= "</tr>";
            $text .= "<tr>";
            $text .= "<td class=main>";
            $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
            $text .= "<tr><td class=mainbold>Insert Title</td><td class=mainbold>Actions</td></tr>";
            $result = mysql_query("select page_insert_id, page_insert_title from page_insert order by page_insert_id");
            while(list($id, $title) = mysql_fetch_row($result)){
                $title = stripslashes($title);
                $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC';\" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
                $text .= "<td class=hover>$title</td>";
                $text .= "<td class=hover><a href=page.php?action=insert&subaction=modify&page_insert_id=$id><img src=images/bedit.gif alt='Modify' border=0></a> <a href=javascript:confirmDelete('page.php?action=insert&subaction=delete&page_insert_id=$id')><img src=images/bdelete.gif alt='Delete' border=0></a></td>";
                $text .= "</tr>";

            }
            $text .= "</table>";
            $text .= "</td></tr></table>";
            $this->body = $text;
        }
    }
}

?>
