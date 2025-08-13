<?php
#$Id: faq.inc.php,v 1.3 2003/09/22 14:46:42 ryan Exp $

class faqModule {

    function faqModule($userinfo){
        $this->action = $_GET['action'];
        $this->sub = $_GET['subaction'];
        $this->userinfo = $userinfo;
        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();

        switch($this->action){
            case create:
                $this->createFAQ();
                break;
            case delete:
                $this->deleteFAQ();
                break;
            case modify:
                $this->modifyFAQ();
                break;
            case order:
                $this->orderFAQ();
                break;
            default:
                $this->listFAQ();
        }
        $this->stroke();
    }

    function stroke(){
        new adminPage($this->body);
    }

    function createFAQ(){
        if ($_POST[confirm] == 1){
            if ($this->sub == "cat"){
                $title = $this->textFun->convertText($_POST[title], 1);
                list($ord) = mysql_fetch_row(mysql_query("select ord from faq_cat order by ord desc limit 1,0"));
                $ord++;
                mysql_query("insert into faq_cat values ('', '$title', '$ord')");
                $faq_cat_id = mysql_insert_id();
                $this->core->addLog("FAQ Cat Created", $this->userinfo[1], $faq_cat_id);
                header("Location: faq.php");
            }
            else {
                $question = $this->textFun->convertText($_POST[question], 1);
                $answer = $this->textFun->convertText($_POST[answer], 1);
                list($ord) = mysql_fetch_row(mysql_query("select ord from faq where faq_cat_id = '$_POST[faq_cat_id]' order by ord desc limit 1,0"));
                $ord++;
                mysql_query("insert into faq values ('', '$_POST[faq_cat_id]', '$question', '$answer', '$ord')");
                $faq_id = mysql_insert_id();
                $this->core->addLog("FAQ Created", $this->userinfo[1], $faq_id);
                header("Location: faq.php");
            }
        }
        else {
            if ($this->sub == "cat"){
                $text = "<table border=0 cellpadding=2 cellspacing=1 width=400 align=center class=outline>";
                $text .= "<tr>";
                $text .= "<td class=title>Create FAQ Category</td>";
                $text .= "</tr>";
                $text .= "<tr><td class=main><table border=0 cellpadding=0 cellspacing=0>";
                $text .= "<form method=post action=faq.php?action=create&subaction=cat>";
                $text .= "<input type=hidden name=confirm value=1>";
                $text .= "<tr><td class=mainbold>Category Title: </td><td class=main> <input type=text value='' name=title size=40></td></tr>";
                $text .= "<tr><td class=main colspan=2 align=center><input type=submit value='Enter'></td></form></tr>";
                $text .= "</table></td></tr></table>";
            }
            else {
                $text = "<table border=0 cellpadding=2 cellspacing=1 width=400 align=center class=outline>";
                $text .= "<tr>";
                $text .= "<td class=title>Create FAQ</td>";
                $text .= "</tr>";
                $text .= "<tr><td class=main><table border=0 cellpadding=0 cellspacing=0>";
                $text .= "<form method=post action=faq.php?action=create&subaction=faq>";
                $text .= "<input type=hidden name=confirm value=1>";
                $text .= $this->createCatSelect();
                $text .= "<tr><td class=mainbold valign=top>Question: </td><td class=main> <textarea name=question cols=40 rows=10></textarea></td></tr>";
                $text .= "<tr><td class=mainbold valign=top>Answer: </td><td class=main> <textarea name=answer cols=40 rows=10></textarea></td></tr>";
                $text .= "<tr><td class=main colspan=2 align=center><input type=submit value='Enter'></td></form></tr>";
                $text .= "</table></td></tr></table>";
            }
            $this->body = $text;
        }
    }

    function createCatSelect($old=''){
        $text = "<tr><td class=mainbold>Category: </td><td class=main><select name=faq_cat_id>";
        $result = mysql_query("select faq_cat_id, title from faq_cat order by title");
        while(list($faq_cat_id, $title) = mysql_fetch_row($result)){
            $title = stripslashes($title);
            if ($faq_cat_id == $old){ $s = "selected"; }
            else { $s = ''; }
            $text .= "<option value=$faq_cat_id>$title</option>";
        }
        $text .= "</select></td></tr>";
        return $text;
    }

    function deleteFAQ(){
        if ($this->sub == "cat"){
            mysql_query("delete from faq where faq_cat_id = '$_GET[faq_cat_id]'");
            mysql_query("delete from faq_cat where faq_cat_id = '$_GET[faq_cat_id]'");
            $this->core->addLog("FAQ Cat Deleted", $this->userinfo[1], $_GET[faq_cat_id]);
            header("Location: faq.php");
        }
        else {
            mysql_query("delete from faq where faq_id = '$_GET[faq_id]'");
            $this->core->addLog("FAQ Deleted", $this->userinfo[1], $_GET[faq_id]);
            header("Location: faq.php");
        }
    }

    function modifyFAQ(){
        if ($_POST[confirm] == 1){
            if ($this->sub == "cat"){
                $title = $this->textFun->convertText($_POST[title], 1);
                mysql_query("update faq_cat set title = '$title' where faq_cat_id = '$_POST[faq_cat_id]'");
                $this->core->addLog("FAQ Cat Modified", $this->userinfo[1], $faq_cat_id);
                header("Location: faq.php");
            }
            else {
                $question = $this->textFun->convertText($_POST[question], 1);
                $answer = $this->textFun->convertText($_POST[answer], 1);
                mysql_query("update faq set question = '$question', answer = '$answer', faq_cat_id = '$_POST[faq_cat_id]' where faq_id = '$_POST[faq_id]'");
                $this->core->addLog("FAQ Modified", $this->userinfo[1], $faq_id);
                header("Location: faq.php");
            }
        }
        else {
            if ($this->sub == "cat"){
                list($title) = mysql_fetch_row(mysql_query("select title from faq_cat where faq_cat_id = '$_GET[faq_cat_id]'"));
                $title = $this->textFun->convertText($title, 0);
                $text = "<table border=0 cellpadding=2 cellspacing=1 width=400 align=center class=outline>";
                $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
                $text .= "<tr>";
                $text .= "<td class=title>Modify FAQ Category</td><td class=title align=right><a href=javascript:confirmDelete('faq.php?action=delete&subaction=cat&faq_cat_id=$_GET[faq_cat_id]')><img src=images/bdelete.gif alt='Delete' border=0></a></td>";
                $text .= "</tr></table></td></tr>";
                $text .= "<tr><td class=main><table border=0 cellpadding=0 cellspacing=0>";
                $text .= "<form method=post action=faq.php?action=modify&subaction=cat>";
                $text .= "<input type=hidden name=confirm value=1><input type=hidden name=faq_cat_id value=$_GET[faq_cat_id]>";
                $text .= "<tr><td class=mainbold>Category Title: </td><td class=main> <input type=text value='$title' name=title size=40></td></tr>";
                $text .= "<tr><td class=main colspan=2 align=center><input type=submit value='Enter'></td></form></tr>";
                $text .= "</table></td></tr></table>";
            }
            else {
                list($question, $answer, $faq_cat_id) = mysql_fetch_row(mysql_query("select question, answer, faq_cat_id from faq where faq_id = '$_GET[faq_id]'"));
                $question = $this->textFun->convertText($question, 0);
                $answer = $this->textFun->convertText($answer, 0);
                $text = "<table border=0 cellpadding=2 cellspacing=1 width=400 align=center class=outline>";
                $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
                $text .= "<tr>";
                $text .= "<td class=title>Modify FAQ</td><td class=title align=right><a href=javascript:confirmDelete('faq.php?action=delete&subaction=faq&faq_id=$_GET[faq_id]')><img src=images/bdelete.gif alt='Delete' border=0></a></td>";
                $text .= "</tr></table></td></tr>";
                $text .= "<tr><td class=main><table border=0 cellpadding=0 cellspacing=0>";
                $text .= "<form method=post action=faq.php?action=modify&subaction=faq>";
                $text .= "<input type=hidden name=confirm value=1><input type=hidden name=faq_id value=$_GET[faq_id]>";
                $text .= $this->createCatSelect($faq_cat_id);
                $text .= "<tr><td class=mainbold valign=top>Question: </td><td class=main> <textarea name=question cols=40 rows=10>$question</textarea></td></tr>";
                $text .= "<tr><td class=mainbold valign=top>Answer: </td><td class=main> <textarea name=answer cols=40 rows=10>$answer</textarea></td></tr>";
                $text .= "<tr><td class=main colspan=2 align=center><input type=submit value='Enter'></td></form></tr>";
                $text .= "</table></td></tr></table>";
            }
            $this->body = $text;
        }
    }

    function listFAQ(){
       $cat = "<form method=post action=faq.php?action=order name=f>";
        $cat .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=750 align=center>";
        $cat .= "<tr>";
        $cat .= "<td class=title colspan=2>Order FAQ Items</td>";
        $cat .= "</tr>";
        $cat .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        $cat .= "<script language=javascript src=templates/list.js></script>";
        $cat .= "<script language=javascript>";
        $cat .= "var listB = new DynamicOptionList(\"faq_id\",\"faq_cat_id\");";
        $result = mysql_query("select faq_cat_id, title from faq_cat order by ord");
        while(list($menu_id, $menu_name) = mysql_fetch_row($result)){
            $menu_name = stripslashes($menu_name);
            $result55 = mysql_query("select faq_id, question from faq where faq_cat_id = '$menu_id' order by ord");
            $count = mysql_num_rows($result55);
            if ($count != 0){
                $js .= "listB.addOptions(\"$menu_id\"";
                while(list($menu_id2, $menu_name2) = mysql_fetch_row($result55)){
                    $menu_name2 = stripslashes($menu_name2);
                    $js .= ",\"$menu_name2\", \"$menu_id2\"";
                }
                $js .= ");";
            }
            $catList .= "<option value=$menu_id $selected>$menu_name</option>";
        }
        $cat .= $js;
        $cat .= "</script>";
        $cat .= "<tr>";
        $cat .= "<td class=main align=center width=33%><select size=25 multiple name=faq_cat_id onChange=\"listB.populate();\" onDblClick=\"faqEdit(1)\">$catList</select>";
        $cat .= "<br><img src=images/up.gif alt='Move Up' onClick=\"moveOptionUp(document.forms[0].faq_cat_id)\">&nbsp;";
        $cat .= "<img src=images/down.gif alt='Move Down' onClick=\"moveOptionDown(document.forms[0].faq_cat_id)\"><br>";
        $cat .= "<input type=button onClick=submitFaqForm1() name=save1 value='Save This Form'></td>";
        $cat .= "<td class=main align=center width=33%><select size=25 multiple name=faq_id onDblClick=\"faqEdit(2)\"><script language=JavaScript>listB.printOptions();</script></select>";
        $cat .= "<br><img src=images/up.gif alt='Move Up' onClick=\"moveOptionUp(document.f.faq_id)\">&nbsp;";
        $cat .= "<img src=images/down.gif alt='Move Down' onClick=\"moveOptionDown(document.f.faq_id)\"><br>";
        $cat .= "<input type=button onClick=submitFaqForm2() name=save2 value='Save This Form'></td></tr>";
        $cat .= "<script language=javascript>listB.init(document.forms[0]);</script>";
        $cat .= "<input type=hidden name=list1><input type=hidden name=save>";
        $cat .= "</form></table></td></tr></table>";
        $this->core->addLog("View FAQ", $this->userinfo[1], '');
        $this->body = $cat;
    }

    function orderFAQ(){
        $list = explode(",", $_POST['list1']);
        $x=1;
        foreach($list as $l){
            if ($_POST[save] == 1){
                mysql_query("update faq_cat set ord = '$x' where faq_cat_id = '$l'");
            }
            else {
                mysql_query("update faq set ord = '$x' where faq_id = '$l'");
            }
            $x++;
        }
        $this->core->addLog("Re-Order FAQ", $this->userinfo[1], '');
        header("HTTP/1.0 204 No Content");
    }
}


