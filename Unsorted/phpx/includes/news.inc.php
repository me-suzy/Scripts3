<?php
#$Id: news.inc.php,v 1.9 2003/10/20 14:59:48 ryan Exp $
require("includes/text.inc.php");
class news {
    var $textArray;
    var $templates;
    var $id;
    var $body;


    function news($id, $textArray, $templates){
        $this->textArray = $textArray;
        $this->id = $id;
        $this->templates = $templates;
        $this->core = new core();
        $this->text = new textFunctions();
        $this->core->textArray = $textArray;
        $this->core->templates = $templates;
        $this->checkForNews();
        $this->limit = $_GET[limit];
        if ($this->limit == ''){ $this->limit = "0"; }
        $this->setnum = $this->core->dbCall("items_per_page");
        switch($_GET[action]){
            case browse:
                $sqlTotal = "select news_id from news";
                $sql = "select news_id, title, post, date, news_cat_id from news where delay = '0' order by date desc limit $this->limit,$this->setnum";
                $this->browseNews($this->limit, $sql, $sqlTotal);
                break;

            case view_cat:

                $sqlTotal = "select news_id from news where news_cat_id = '$_GET[news_cat_id]'";
                $sql = "select news_id, title, post, date, news_cat_id from news where delay = '0' and news_cat_id = '$_GET[news_cat_id]' order by date desc limit $this->limit,$this->setnum";
                $this->browseNews($this->limit, $sql, $sqlTotal);
                break;

            case list_cat:
                $this->listCat();
                break;

            case email:
                $this->emailNews();
                break;

            case submit:
                $this->submitNews();
                break;

            default:
                $this->showNews();
        }
    }

    function listCat(){
         $cat = $this->core->dbCall("news_categories");
         $catImage = $this->core->dbCall("news_images");
         if ($cat == 1){
             $text = "<table border=0 cellpadding=0 cellspacing=1 class=outline width=100%>";
             $text .= "<tr><td class=title>" . $this->textArray['News'] . " " . $this->textArray['Categories'] . "</td></tr>";
             $text .= "</table><br>";
             $text = "<table border=0 cellpadding=8 cellspacing=4 width=100%>";
             $result = mysql_query("select * from news_cat order by news_cat_title");
             $x=1;
             while($row = mysql_fetch_array($result)){
                 if ($x == 1){ $text .= "<tr>"; }
                 $text .= "<td class=main align=center><a href=news.php?action=view_cat&news_cat_id=$row[news_cat_id]>";
                 if ($catImage == 1){ $text .= "<img src=images/news/$row[news_cat_image] alt='$row[news_cat_title]' border=0><br>"; }
                 $text .= $row[news_cat_title] . "</a></td>";
                 if ($x == 5){ $text .= "</tr>"; $x=1; }
                 else { $x++; }
             }
             $text .= "</tr>";
             $text .= "</table><br>";

         }
         else { header("Location: index.php"); }
         $this->body = $text;
    }


    function showNews(){
        $dateFormat = $this->core->dbCall("dateFormat");
        $numberOfNews = $this->core->dbCall("numberOfNews");
        $headlines = $this->core->dbCall("headlines");
        $news_comments = $this->core->dbCall("news_comments");
        $news_categories = $this->core->dbCall("news_categories");
        $news_images = $this->core->dbCall("news_images");
        $news_align = $this->core->dbCall("news_image_align");
        if ($news_align == 1){ $news_align = "left"; }
        else { $news_align = "right"; }

        if (isset($_COOKIE[PXL])){
            $result = mysql_query("select news_cat_id from user_news_view where user_id = '$_COOKIE[PXL]'");
            $c = mysql_num_rows($result);
            if ($c > 0){
                $mess = $this->textArray['Your Preferences will affect stories shown'];
                $limitCat = "and news_cat_id in (";
                while(list($cid) = mysql_fetch_row($result)){
                    $limitCat .= "'$cid', ";
                }
                $limitCat = substr($limitCat,0,-2) . ") ";

            }
        }


        if (isset($_GET[news_id])){
            $sql = "select news_id, title, post, date, news_cat_id, more, user_id, size from news where delay = '0' and news_id = '$_GET[news_id]'";
            $headlines = 0;
        }

        else { $sql = "select news_id, title, post, date, news_cat_id, more, user_id, size from news where delay = '0' $limitCat order by date desc limit 0," . $numberOfNews; }
        if ($headlines == 1){ $text = "<b>" . $this->textArray['News Stories'] . "</b><br><br>"; }
        $result = mysql_query($sql);
        while(list($news_id, $title, $post, $date, $cat_id, $more, $user_id, $size) = mysql_fetch_row($result)){
            list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$user_id'"));
            $readMore = '';
            if ($news_categories == 1){
                list($cat_name, $cat_image) = mysql_fetch_row(mysql_query("select news_cat_title, news_cat_image from news_cat where news_cat_id = '$cat_id'"));
                $cat_name = stripslashes($cat_name);
                if ($news_images == 1){ $cat_image = "<img src=images/news/$cat_image alt='$cat_name' border=0 align=$news_align>"; }
                $category = " <a href=news.php?news_cat_id=$cat_id&action=view_cat>$cat_name</a> : ";
            }

            if ($news_comments == 1){
                list($forum_id) = mysql_fetch_row(mysql_query("select f.forum_id from forums_forums f where f.comment_type = '1'"));
                list($topic_id, $comments) = mysql_fetch_row(mysql_query("select topic_id, posts from forums_topics where forum_id = '$forum_id' and comment_id = '$news_id'"));
                if ($comments == ''){ $comments = 0; $link = "forums.php?action=post&forum_id=$forum_id&comment_id=$news_id"; }
                else { $link = "forums.php?forum_id=$forum_id&topic_id=$topic_id"; }
            }
            $title = stripslashes($title);
            $post = stripslashes($post);
            $more = "<br><br>" . stripslashes($more);
            $readMore .= "<a href=news.php?news_id=$news_id>" . $size . " " . $this->textArray['bytes in body'] . "</a> ";
            if ($more != "<br><br>" && !isset($_GET[news_id])){
                $readMore .= " | <a href=news.php?news_id=$news_id>" . $this->textArray['Read More'] . "</a> ";
                $more = '';
            }

            if ($news_comments == 1){ $readMore .= "| <a href=$link>" . $this->textArray['Comments'] . " ($comments)</a>"; }

            if (isset($_COOKIE[PXLF])){
                list($temp) = mysql_fetch_row(mysql_query("select templast from forums_session where sess_id = '$_COOKIE[PXLF]'"));
                if ($temp < $date){
                    $alt = $this->textArray['New'];
                    $new = "<img src=images/new.gif alt=$alt border=0>";
                }
                else { $new = ''; }
            }
            else {
                $alt = $this->textArray['New'];
                $new = "<img src=images/new.gif alt=$alt border=0>";
            }

            $date = $this->core->getTime($date);
            $date = date($dateFormat . " H:i", $date);

            if ($_GET[news_id] != ''){
                $printEmail = "<tr><td class=main>";
                $printEmail .= "<a href=print.php?action=news&news_id=$_GET[news_id] target=new2>" . $this->textArray['Printer Friendly Version'] . "</a> | ";
                $printEmail .= "<a href=news.php?action=email&news_id=$_GET[news_id]>" . $this->textArray['Email'] . " " . $this->textArray['News Story'] . "</a>";
                $printEmail .= "</td></tr>";
            }

            if ($headlines == 0){ require("$this->templates/files/news.tpl.php"); }
            else { require("$this->templates/files/newsHeadline.tpl.php"); }
            $text .= "<p>";
        }
        $text .= "<p>";
        $text .= "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td class=main>$mess</td><td class=main align=right><a href=news.php?action=browse>" . $this->textArray['older news stories'] . "...</a></td></tr></table>";

        $this->body = $text;
    }

    function browseNews($limit, $sql, $sqlTotal){
        $dateFormat = $this->core->dbCall("dateFormat");

        $news_comments = $this->core->dbCall("news_comments");
        $news_categories = $this->core->dbCall("news_categories");
        $result = mysql_query($sqlTotal);
        $number = mysql_num_rows($result);
        $result = mysql_query($sql);
        $url = "news.php?action=browse";
        $more = $this->core->pageTab($this->limit, $number, $url, $this->setnum);
        if ($number != 0){
            $text .= "<table border=0 cellpadding=1 cellspacing=1 width=95% align=center class=bg>";
            $text .= "<tr><td class=title>" . $this->textArray['News Stories'] . "</td></tr></table>";
            $text .= "<table border=0 cellpadding=1 cellspacing=1 width=95% align=center>";
            $text .= "<tr><td class=main colspan=2>$more</td></tr>";
            $text .= "<tr><td class=main>";
            while(list($news_id, $title, $post, $date, $cat_id) = mysql_fetch_row($result)){
                if ($news_categories == 1){
                    list($cat) = mysql_fetch_row(mysql_query("select news_cat_title from news_cat where news_cat_id = '$cat_id'"));
                    $cat = stripslashes($cat);
                    $category = "<tr><td class=small>" . $this->textArray['Category'] . " -> <a href=news.php?action=view_cat&news_cat_id=$cat_id>$cat</a></td></tr>";
                }
                if ($news_comments == 1){
                    list($forum_id) = mysql_fetch_row(mysql_query("select f.forum_id from forums_forums f where f.comment_type = '1'"));
                    list($topic_id, $comments) = mysql_fetch_row(mysql_query("select topic_id, posts from forums_topics where forum_id = '$forum_id' and comment_id = '$news_id'"));
                    if ($comments == ''){ $comments = 0; }
                    $comments = " ($comments " . $this->textArray['Comments'] . ")";
                }

                $title = stripslashes($title);
                $post = stripslashes($post);
                $date = date($dateFormat, $date);
                $post = substr($post, 0, 50) . "...";
                $text .= "<table border=0 cellpadding=0 cellspacing=1 width=100%>";
                $text .= "<tr><td class=main><span style='font-size:14px;'><b><a href=news.php?news_id=$news_id>$title</a></b></span></td></tr>";
                $text .= "<tr><td class=small>$date $comments</td></tr>";
                $text .= $category;
                $text .= "<tr><td class=main>$post</td></tr>";


                $text .= "</table><br>";
            }
            $text .= "</td><td class=main valign=top>SEARCHHERE</td></tr>";
            $text .= "<tr><td class=main colspan=2>$more</td></tr>";
            $text .= "</table>";
        }
        else {
            $text .= "<table border=0 cellpadding=1 cellspacing=1 width=95% align=center class=bg>";
            $text .= "<tr><td class=main>" . $textArray['No Results Found'] . "</td></tr></table>";

        }

        $this->body = $text;
    }

    function checkForNews(){
        $date = $this->core->getTime(time(), 1);
        list($count) = mysql_fetch_row(mysql_query("select count(*) from news where delay = '1' and date < '$date'"));
        if ($count > 0){ mysql_query("update news set delay = '0' where delay = '1' and date < '$date'"); }
    }

    function submitNews(){
        $news_sub = $this->core->dbCall("news_sub");
        $news_anon_sub = $this->core->dbCall("news_anon_sub");
        $news_cat = $this->core->dbCall("news_categories");

        if ($news_sub != 1 || ($news_anon_sub != 1 && !isset($_COOKIE[PXL]))){ header("Location: error.php?error=403"); }
        if ($_POST[confirm] == 1){
            if (!isset($_COOKIE[PXL])){ $user_id = 1; }
            else { $user_id = $_COOKIE[PXL]; }
            $date = $this->core->getTime(time(), 1);
            $title = $this->text->convertText($_POST[title], 1);
            $body = $this->text->convertText($_POST[body], 1);

            $siteName = $this->core->dbCall("siteName");
            $email = $this->core->dbCall("webmasterEmail");
            $headers = "From: $siteName News Submission Alert <$email> \r\n";
            $headers .= "X-Sender: <$email>\r\n";
            $headers .= "X-Mailer: PHP\r\n";
            $headers .= "X-Priority: 3\r\n";
            $headers .= "Reply-To: $email\r\n";
            $subject = "News Submission";
            $message = "News has been submitted by a user";
            mail($email, $subject, $message, $headers);
            mysql_query("insert into news_sub values ('', '$user_id', '$title', '$body', '$date', '$_SERVER[REMOTE_ADDR]', '$_POST[cat_id]', '3')");
            header("Location: status.php?status=2");
        }
        else {
            if ($news_cat == 1){
                $cat = "<tr><td class=mainbold>Category : </td><td class=main><select name=cat_id>";
                $result = mysql_query("select news_cat_id, news_cat_title from news_cat order by news_cat_title");
                while(list($cat_id, $cat_title) = mysql_fetch_row($result)){
                    $cat_title = stripslashes($cat_title);
                    $cat .= "<option value=$cat_id>$cat_title</option>";
                }
                $cat .= "</select></td></tr>";
            }
            $text = "<table width=500 cellspacing=1 cellpadding=0 border=0 align=center class=outline>";
            $text .= "<tr>";
            $text .= "<td align=left class=title>" . $this->textArray['Submit'] . " " . $this->textArray['News Story'] . "</td></tr>";
            $text .= "<tr><td class=main>";
            $text .= "<table width=500 cellspacing=1 cellpadding=0 border=0 align=center>";
            $text .= "<form method=post name=f action=news.php?action=submit><input type=hidden name=confirm value=1>";
            $text .= $cat;
            $text .= "<tr><td class=mainbold>" . $this->textArray['Subject'] . " : </td><td class=main><input type=text size=30 name=title></td></tr>";
            $text .= "<tr><td class=mainbold>" . $this->textArray['News Story'] . " : </td><td class=main><textarea cols=30 rows=15 name=body></textarea></td></tr>";
            $text .= "<tr><td class=main align=center colspan=2><input class=submit type=submit value='" . $this->textArray['Send'] . " " . $this->textArray['Email'] . "'></td></tr></form></table>";
            $text .= "</td></tr></table>";
            $this->body = $text;


        }
    }

    function emailNews(){
        if (!isset($_COOKIE[PXL])){ $text = $this->core->login(0, "news.php?news_id=$_GET[news_id]&action=email"); }
        else {
            if ($_POST[confirm] == 1){
                list($first_name, $last_name, $email) = mysql_fetch_row(mysql_query("select first_name, last_name, email from users where user_id = '$_COOKIE[PXL]'"));
                $siteName = $this->core->dbCall("siteName");
                $siteURL = $this->core->dbCall("siteURL");
                $headers = "From:  $first_name $last_name <$email> \r\n";
                $headers .= "X-Sender: <$email>\r\n";
                $headers .= "X-Mailer: PHP\r\n";
                $headers .= "X-Priority: 3\r\n";
                $headers .= "Reply-To: $email\r\n";

                $subject = $this->textArray['News Story'] . " " . $this->textArray['From'] . " $siteName";
                $message = $this->textArray['Check out this'] . " " . $this->textArray['News Story'] . " " . $this->textArray['From'] . " $siteName\r\n\r\n";
                $message .= $this->textArray['Click Here to view'] . ": $siteURL/news.php?news_id=$_GET[news_id]";

                foreach($_POST[send] as $s){
                    if ($s != ''){ mail($s,$subject,$message,$headers); }
                }
                header("Location: status.php?status=1&news_id=$_GET[news_id]");
            }
            else {
                $this->showNews($this->id);
                $text = "<table width=500 cellspacing=1 cellpadding=0 border=0 align=center class=outline>";
                $text .= "<tr>";
                $text .= "<td align=left class=title>" . $this->textArray['Email'] . " " . $this->textArray['News Story'] . "</td></tr>";
                $text .= "<tr><td class=main>";
                $text .= "<table width=500 cellspacing=1 cellpadding=0 border=0 align=center>";
                $text .= "<form method=post name=f action=news.php?action=email&news_id=$_GET[news_id]><input type=hidden name=confirm value=1>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['Email'] . " 1: </td><td class=main><input type=text size=30 name=send[]></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['Email'] . " 2: </td><td class=main><input type=text size=30 name=send[]></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['Email'] . " 3: </td><td class=main><input type=text size=30 name=send[]></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['Email'] . " 4: </td><td class=main><input type=text size=30 name=send[]></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['Email'] . " 5: </td><td class=main><input type=text size=30 name=send[]></td></tr>";
                $text .= "<tr><td class=main align=center colspan=2><input class=submit type=submit value='" . $this->textArray['Send'] . " " . $this->textArray['Email'] . "'></td></tr></form></table>";
                $text .= "</td></tr></table>";
                $this->body = $this->body . $text;

            }
        }
    }
}
