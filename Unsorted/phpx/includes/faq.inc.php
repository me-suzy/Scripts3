<?php
#$Id: faq.inc.php,v 1.5 2003/09/29 14:29:44 ryan Exp $
require_once("includes/text.inc.php");

class faqModule {

    function faqModule($textArray, $templates){
        $this->textArray = $textArray;
        $this->templates = $templates;
        $this->textFun = new textFunctions();
        $this->core = new core();
        $active = $this->core->dbCall("faq");
        $this->comments = $this->core->dbCall("faq_comments");
        if ($active != 1){ $this->body = $textArray['FAQ is currently offline']; }
        else {
            if (isset($_GET[faq_cat_id])){ $this->showFaq(); }
            else { $this->showFaqHeader(); }
        }
    }

    function showFaqHeader(){

        $result = mysql_query("select faq_cat_id, title from faq_cat order by ord");
        $text = "<table width=90% align=center cellspacing=1 cellpadding=2 border=0 class=outline>";
        $text .= "<tr><td class=title><a name=top>" . $this->textArray['FAQ'] . "</td></tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=0 cellspacing=0 width=100% align=center>";
        while(list($cat_id, $title) = mysql_fetch_row($result)){
            $title = $this->textFun->convertText($title, 2);
            $text .= "<tr><td class=main><table border=0 cellpadding=3 cellspacing=1 width=100% align=center class=outline>";
            $text .= "<tr><td class=title><a href=faq.php?faq_cat_id=$cat_id>$title</a></td></tr>";
            $resultf = mysql_query("select faq_id, question from faq where faq_cat_id = '$cat_id' order by ord");
            while(list($faq_id, $question) = mysql_fetch_row($resultf)){
               $question = $this->textFun->convertText($question, 2);
               $text .= "<tr><td class=main>&nbsp;&nbsp; <a href=faq.php?faq_cat_id=$cat_id#$faq_id>$question</a></td></tr>";
            }
            $text .= "</table><br>";
        }
        $text .= "</table></td></tr></table>";
        $this->body = $text;

    }

    function showFaq(){

        $faq_comments = $this->core->dbCall("faq_comments");
        list($catName) = mysql_fetch_row(mysql_query("select title from faq_cat where faq_cat_id = '$_GET[faq_cat_id]'"));
        $catName = $this->textFun->convertText($catName, 2);

        $text .= "<p><font face=arial size=2><center><b>$catName</b></center></font><br>";
        $result = mysql_query("select faq_id, answer, question from faq where faq_cat_id = '$_GET[faq_cat_id]'");
        while(list($faq_id, $answer, $question) = mysql_fetch_row($result)){
            list($forum_id) = mysql_fetch_row(mysql_query("select f.forum_id from forums_forums f where f.comment_type = '7'"));
            list($topic_id, $comments) = mysql_fetch_row(mysql_query("select topic_id, posts from forums_topics where forum_id = '$forum_id' and comment_id = '$faq_id'"));
            if ($comments == ''){ $comments = 0; $link = "forums.php?action=post&forum_id=$forum_id&comment_id=$faq_id"; }
            else { $link = "forums.php?forum_id=$forum_id&topic_id=$topic_id"; }
            $question = $this->textFun->convertText($question, 2);
            $answer = $this->textFun->convertText($answer, 2);
            $questions .= "<a href=#$faq_id>$question</a><br>";
            $answers .= "<tr><td class=main><table border=0 cellpadding=3 cellspacing=1 width=100% align=center class=outline>";
            $answers .= "<tr><td class=title><a name=$faq_id>$question</td></tr>";
            $answers .= "<tr><td class=main>$answer</td></tr>";
            $answers .= "<tr><td class=main><span class=small><a href=#top>" . $this->textArray['Back to Top'] . "</a>";
            if ($faq_comments == 1){ $answers .= "&nbsp;&nbsp;<a href=$link>Comments ($comments)";}
            $answers .= "</span></td></tr>";
            $answers .= "</table><br></td></tr>";
        }
        $text = "<table width=90% align=center cellspacing=1 cellpadding=2 border=0 class=outline>";
        $text .= "<tr><td class=title><a name=top>" . $this->textArray['FAQ'] . "</td></tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=0 cellspacing=0 width=100% align=center>";
        $text .= "<tr><td class=main><table border=0 cellpadding=3 cellspacing=1 width=100% align=center class=outline>";
        $text .= "<tr><td class=title>$catName</td></tr>";
        $text .= "<tr><td class=main>$questions</td></tr></table><br><br>";
        $text .= $answers;
        $text .= "</table>";
        $text .= "</td></tr></table>";

        $this->body = $text;

    }
}
