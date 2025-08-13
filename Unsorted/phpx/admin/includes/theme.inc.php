<?php
#$Id: theme.inc.php,v 1.7 2003/09/03 18:25:24 ryan Exp $

class themeModule {
    var $body;

    function themeModule($userinfo){
        $this->core = new coreFunctions();
        $this->userinfo = $userinfo;
        $this->action = $_GET['action'];

        switch($this->action){
            case import:
                $this->importThemes();
                break;
            case set:
                $this->setTheme();
                break;
            default:
                $this->listThemes();
        }
        $this->stroke();
    }

    function stroke(){
        new adminPage($this->body);
    }

    function importThemes(){
        $this->core->createProgressPage();
        $hold = array();
        $templates = "../templates";
        $handle = opendir($templates);
        while ($file = readdir($handle)){
            $dirArray[] = $file;
        }
        closedir($handle);
        foreach($dirArray as $file){
            $file = $templates . "/" . $file;
            if (is_dir($file) && substr($file, -1) != "." && substr($file, -2) != ".." && substr($file, -3) != "CVS" ){
                $configFile = $file . "/theme.txt";
                $f = file($configFile);
                $desc = addslashes(trim(rtrim(str_replace("Description = ", '', $f[2]))));
                $name = addslashes(trim(rtrim(str_replace("Name = ", '', $f[0]))));
                $author = addslashes(trim(rtrim(str_replace("Author = ", '', $f[1]))));
                $menu = addslashes(trim(rtrim(str_replace("Menu = ", '', $f[3]))));
                $splash = addslashes(trim(rtrim(str_replace("Splash = ", '', $f[4]))));
                $directory = trim(rtrim(substr($file, strrpos($file, "/"))));
                $directory = trim($directory, "/");
                $result = mysql_query("select theme_id from theme where theme_dir = '$directory'");
                $count = mysql_num_rows($result);
                if ($count == "1"){
                    list($theme_id) = mysql_fetch_row($result);
                }
                else {
                    mysql_query("insert into theme values ('', '$name', '$author', '$desc', '$menu', '$directory', '$splash')");
                    $theme_id = mysql_insert_id();
                }
                $hold[] = $theme_id;

            }
        }

        $result = mysql_query("select theme_id from theme");
        while(list($id) = mysql_fetch_row($result)){
            if (!in_array($id, $hold)){
                mysql_query("delete from theme where theme_id = '$id'");
            }
        }
        $this->core->addLog("Import Theme", $this->userinfo[1], 0);
        print "<script language=javascript>window.location = 'theme.php';</script>";
    }

    function listThemes(){
        global $menuTypeArray;
        $text = "<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>";
        $text .= "<tr>";
        $text .= "<td class=title>Themes</td>";
        $text .= "</tr>";
        $text .= "<tr>";
        $text .= "<td class=main>";
        list($theme_id) = mysql_fetch_row(mysql_query("select theme_id from config where config_id = '1'"));
        $result = mysql_query("select theme_id, theme_name, theme_author, theme_description, theme_dir, theme_menu from theme order by theme_id");
        $x=1;
        while(list($id, $name, $author, $desc, $dir, $menu) = mysql_fetch_row($result)){
            $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
            $text .= "<tr><td class=main valign=top>";
            $text .= "<b>Theme Name:</b> $name<br>";
            $text .= "Author: $author<br>Default Menu: $menuTypeArray[$menu]<br>";
            $text .= "$desc</td><td class=main width=250 align=center valign=top>";
            $text .= "<img src=../templates/$dir/screen.jpg width=200 height=120 alt='Screen Shot'><br><br>";
            if ($theme_id == $id){ $img= "<img src=images/theme_select.gif border=0 alt='Click to Toggle' name=img$x>"; }
            else { $img = "<img src=images/theme_select1.gif border=0 alt='Click To Toggle' name=img$x>"; }
            $text .= "<a href=\"javascript:toggleTheme('theme.php?action=set&theme_id=$id', 'img$x');\">$img</a>";
            $text .= "</td></tr></table>";
            $text .= "<br><br>";
            $x++;
        }
        $text .= "</td></tr></table>";
        $this->body = $text;
    }

    function setTheme(){

        $theme = $_GET['theme_id'];
        list($menu, $splash) = mysql_fetch_row(mysql_query("select theme_menu, theme_splash from theme where theme_id = '$theme'"));
        mysql_query("update config set theme_id = '$theme', menuType = '$menu', splash_page = '$splash' where config_id = '1'");
        $this->core->addLog("Theme Changed", $this->userinfo[1], $theme);
        header("HTTP/1.0 204 No Content");
    }



}
