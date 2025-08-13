<?php
#$Id: menu.inc.php,v 1.8 2003/10/29 18:51:45 ryan Exp $

class menuModule {

    var $body;

    function menuModule($userinfo){
        $this->userinfo = $userinfo;
        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();
        $this->action = $_GET['action'];
        $this->menu_id = $_GET['menu_id'];
        if ($this->menu_id == ''){ $this->menu_id = $_POST['menu_id']; }

        switch($this->action){

            case create:
                $this->createMenu1();
                break;
            case modify:
                $this->editMenu();
                break;
            case delete:
                $this->deleteMenu();
                break;
            case order:
                $this->orderMenu();
                break;
            default:
                $this->showMenu();
        }
        $this->stroke();
    }

    function stroke(){
        new adminPage($this->body);
    }

    function showMenu(){
        $cat = "<form method=post action=menu.php?action=order name=f>";
        $cat .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=100%>";
        $cat .= "<tr>";
        $cat .= "<td class=title colspan=2>Order Menu Items</td>";
        $cat .= "</tr>";
        $cat .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        $cat .= "<script language=javascript src=templates/list.js></script>";
        $cat .= "<script language=javascript>";
        $cat .= "var listB = new DynamicOptionList(\"cat_id_sub\",\"cat_id\");";
        $cat .= "var listC = new DynamicOptionList(\"cat_id_sub_sub\",\"cat_id\",\"cat_id_sub\");";
        $result = mysql_query("select menu_id, menu_name from menu where sub = '0' order by ord");
        while(list($menu_id, $menu_name) = mysql_fetch_row($result)){
            $result55 = mysql_query("select menu_id, menu_name from menu where sub = '$menu_id' order by ord");
            $count = mysql_num_rows($result55);
            if ($count != 0){
                $js .= "listB.addOptions(\"$menu_id\"";
                while(list($menu_id2, $menu_name2) = mysql_fetch_row($result55)){
                    $js .= ",\"$menu_name2\", \"$menu_id2\"";

                    $result66 = mysql_query("select menu_id, menu_name from menu where sub = '$menu_id2' order by ord");
                    $count = mysql_num_rows($result66);
                    if ($count != 0){
                        $cjs .= "listC.addOptions(\"$menu_id|$menu_id2\"";
                        while(list($menu_id3, $menu_name3) = mysql_fetch_row($result66)){
                            $cjs .= ",\"$menu_name3\", \"$menu_id3\"";
                        }
                        $cjs .= ");";
                    }

                }
                $js .= ");";
            }
            $catList .= "<option value=$menu_id $selected>$menu_name</option>";
        }
        $cat .= $js;
        $cat .= $cjs;
        $cat .= "</script>";
        $cat .= "<tr>";
        $cat .= "<td class=main align=center width=33%><select size=25 multiple name=cat_id onChange=\"listB.populate();listC.populate();\" ondblclick=\"menuEdit(1)\">$catList</select>";
        $cat .= "<br><img src=images/up.gif alt='Move Up' onClick=\"moveOptionUp(document.forms[0].cat_id)\">&nbsp;";
        $cat .= "<img src=images/down.gif alt='Move Down' onClick=\"moveOptionDown(document.forms[0].cat_id)\"><br>";
        $cat .= "<input type=button onClick=submitMenuForm1() value='Save This Form'></td>";
        $cat .= "<td class=main align=center width=33%><select size=25 multiple name=cat_id_sub onChange=\"listC.populate();\" onDblClick=\"menuEdit(2)\"><script language=JavaScript>listB.printOptions();</script></select>";
        $cat .= "<br><img src=images/up.gif alt='Move Up' onClick=\"moveOptionUp(document.f.cat_id_sub)\">&nbsp;";
        $cat .= "<img src=images/down.gif alt='Move Down' onClick=\"moveOptionDown(document.f.cat_id_sub)\"><br>";
        $cat .= "<input type=button onClick=submitMenuForm2() value='Save This Form'></td>";
        $cat .= "<td class=main align=center width=33%><select size=25 multiple name=cat_id_sub_sub onDblClick=\"menuEdit(3)\"><script language=JavaScript>listC.printOptions();</script></select>";
        $cat .= "<br><img src=images/up.gif alt='Move Up' onClick=\"moveOptionUp(document.f.cat_id_sub_sub)\">&nbsp;";
        $cat .= "<img src=images/down.gif alt='Move Down' onClick=\"moveOptionDown(document.f.cat_id_sub_sub)\"><br>";
        $cat .= "<input type=button onClick=submitMenuForm3() value='Save This Form'></td></tr>";
        $cat .= "<script language=javascript>listB.init(document.forms[0]);</script>";
        $cat .= "<script language=javascript>listC.init(document.forms[0]);</script>";
        $cat .= "<input type=hidden name=list1>";
        $cat .= "</form></table></td></tr></table>";
        $this->core->addLog("View Menu", $this->userinfo[1], '');
        $this->body = $cat;
    }

    function createInsertForm(){

        global $menuArray;
        $m2 = array_flip($menuArray);
        $text = "<tr><td class=mainbold align=right>Insert Link :</td><td class=main><select name=insertItem>";
        $text .= "<script language=\"JavaScript\" src=\"templates/page-edit.js\"></script>";
        foreach($menuArray as $m){
            $text .= "<option value=$m>$m2[$m]</option>";
        }
        $text .= "<option value=''></option>";
        $text .= "<option value=''></option>";
        $text .= "<option value=''></option>";
        $result = mysql_query("select page_id, title from pages order by title");
        while(list($page_id, $title) = mysql_fetch_row($result)){
            $title = stripslashes($title);
            $text .= "<option value=index.php?id=$page_id>$title</option>";
        }
        $text .= "</select> <input type=button value='Insert' onclick=insertMenuItem()></td></tr>";
        return $text;

    }

    function orderMenu(){

        $list = explode(",", $_POST['list1']);
        $x=1;
        foreach($list as $l){
            mysql_query("update menu set ord = '$x' where menu_id = '$l'");
            $x++;
        }
        $this->core->addLog("Re-Order Menu", $this->userinfo[1], '');
        header("HTTP/1.0 204 No Content");
    }

    function createMenu1(){

        if ($_POST['confirm'] == 1){

            if ($_POST['cat_id_sub'] != '' && $_POST['cat_id_sub'] != 0){ $cat_id = $_POST['cat_id_sub']; }
            else { $cat_id = $_POST['cat_id']; }
            $name = addslashes($_POST['name']);
            list($ord) = mysql_fetch_row(mysql_query("select ord from menu where sub = '$cat_id'"));
            $ord++;
            $target = $_POST['target'];
            $link = $_POST['link'];
            mysql_query("insert into menu values ('', '$name', '$link', '$target', '$cat_id', '$ord')");
            $menu_id = mysql_insert_id();
            $this->core->addLog("Menu Item Created", $this->userinfo[1], $menu_id);
            header("Location: menu.php");
        }
        else {
            $selectForm = $this->textFun->createMenuSelectForm('');
            $targetForm = $this->createTargetForm('');
            $text = "<form method=post action=menu.php?action=create name=f>";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=450 align=center>";
            $text .= "<tr>";
            $text .= "<td class=title colspan=2>Create Menu Item</td>";
            $text .= "</tr>";
            $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0>";
            $text .= "<tr><td class=mainbold width=125 align=right>Item Placement: </td><td class=main>$selectForm</td></tr>";
            //$text .= "<tr><td class=mainbold width=125 align=right>Top Level: </td><td class=main><input type=checkbox value=1 name=topLevel></td></tr>";
            $text .= "<tr><td class=mainbold width=125 align=right>Item Name: </td><td class=main><input type=text value='' name=name size=25></td></tr>";
            $text .= "<tr><td class=mainbold width=125 align=right>Link: </td><td class=main><input type=text value='' name=link size=25></td></tr>";
            $text .= "<tr><td class=mainbold width=125 align=right>New Window: </td><td class=main>$targetForm</td></tr>";
            $text .= $this->createInsertForm();
            $text .= "<tr><td class=main align=center colspan=2><input type=submit value='Enter'></td></tr>";
            $text .= "</table></td></tr></table>";
        }
        $this->body = $text;
    }
    function editMenu(){
        $menu_id = $this->menu_id;
        if ($_POST['confirm'] == 1){

            if ($_POST['cat_id_sub'] != '' && $_POST['cat_id_sub'] != 0){ $cat_id = $_POST['cat_id_sub']; }
            else { $cat_id = $_POST['cat_id']; }
            $name = addslashes($_POST['name']);
            list($sub) = mysql_fetch_row(mysql_query("select sub from menu where menu_id = '$menu_id'"));
            //print "$cat_id, $cat_id_sub, $sub, $menu_id";
            if ($cat_id != $sub){
               list($ord) = mysql_fetch_row(mysql_query("select ord from menu where sub = '$cat_id'"));
               $ord++;
               $check=1;
            }
            $target = $_POST['target'];
            $link = $_POST['link'];
            mysql_query("update menu set menu_name = '$name', link = '$link', sub = '$cat_id', ord = '$ord', target = '$target' where menu_id = '$menu_id'");
            if ($check == 1){
               $x=1;
               $result = mysql_query("select menu_id from menu where sub = '$cat_id' order by ord");
               while(list($m) = mysql_fetch_row($result)){
                   mysql_query("update menu set ord = '$x' where menu_id = '$menu_id'");
                   $x++;
               }
            }
            $this->core->addLog("Menu Item Modified", $this->userinfo[1], $this->menu_id);
            header("Location: menu.php");
        }
        else {
            list($menu_name, $link, $target, $sub) = mysql_fetch_row(mysql_query("select menu_name, link, target, sub from menu where menu_id = '$menu_id'"));
            $name = stripslashes($menu_name);
            $selectForm = $this->textFun->createMenuSelectForm($menu_id);
            $targetForm = $this->createTargetForm($target);
            $text = "<form method=post action=menu.php?action=modify&menu_id=$menu_id>";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=450 align=center>";
            $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
            $text .= "<tr>";
            $text .= "<td class=title>Modify Menu Item</td><td class=title align=right><a href=javascript:confirmDelete('menu.php?menu_id=$menu_id&action=delete')><img src=images/bdelete.gif alt='Delete' border=0></a></td>";
            $text .= "</tr></table></td></tr>";
            $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
            $text .= "<tr><td class=mainbold width=125 align=right>Item Placement: </td><td class=main>$selectForm</td></tr>";
            $text .= "<tr><td class=mainbold width=125 align=right>Item Name: </td><td class=main><input type=text value='$name' name=name size=25></td></tr>";
            $text .= "<tr><td class=mainbold width=125 align=right>Link: </td><td class=main><input type=text value='$link' name=link size=25></td></tr>";
            $text .= "<tr><td class=mainbold width=125 align=right>New Window: </td><td class=main>$targetForm</td></tr>";
            $text .= $this->createInsertForm();
            $text .= "<tr><td class=main align=center colspan=2><input type=submit value='Enter'></td></tr>";
            $text .= "</table></td></tr></table>";
        }
        $this->body = $text;
    }


    function deleteMenu(){
        $menu_id = $this->menu_id;
        $result = mysql_query("select menu_id from menu where sub = '$menu_id'");
        while(list($m1) = mysql_fetch_row($result)){
            mysql_query("delete from menu where menu_id = '$m1'");
            mysql_query("delete from menu where sub = '$m1'");
        }
        mysql_query("delete from menu where menu_id = '$menu_id'");
        $this->core->addLog("Menu Deleted", $this->userinfo[1], $this->menu_id);
        header("Location: menu.php");

    }

    function createTargetForm($target){

        if ($target == 1){ $text .= "<input type=radio name=target value=1 checked>Yes <input type=radio value=0 name=target>No"; }
        else { $text .= "<input type=radio name=target value=1>Yes <input type=radio value=0 name=target checked>No"; }
        return $text;
    }
}





