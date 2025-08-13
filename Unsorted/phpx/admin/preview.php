<?php
#$Id: preview.php,v 1.3 2003/11/20 17:13:41 ryan Exp $
require("includes/auth.inc.php");
$page_title = "News Preview";

class newsPreview {


    function newsPreview(){
        global $core;
        global $userinfo;

        $this->userinfo = $userinfo;
        $this->core = $core;

        $theme_id = $this->core->dbCall("theme_id");
        list($dir) = mysql_fetch_row(mysql_query("select theme_dir from theme where theme_id = '$theme_id'"));
        $this->templates = "../templates/" . $dir;
        $template = $this->templates;

        $post = "<script language=javascript>";
        $post .= "document.write(opener.doc_html.text.value);";
        $post .= "</script>";

        $title = "<script language=javascript>";
        $title .= "document.write(opener.doc_html.title.value);";
        $title .= "</script>";

        $this->showNews($post, $title);



        $body = $this->body;
        include("$this->templates/main.inc.php");
    }

    function showNews($post, $title, $cat_id){

        $date = $this->core->getTime(time(), 1);
        $user_id = $this->userinfo[0];
        $dateFormat = $this->core->dbCall("dateFormat");


        list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$user_id'"));

        $title = stripslashes($title);
        $post = stripslashes($post);

        $date = $this->core->getTime($date);
        $date = date($dateFormat . " H:i", $date);

        require("$this->templates/files/news.tpl.php");
        $this->body = $text;
    }
}

new newsPreview();














?>

