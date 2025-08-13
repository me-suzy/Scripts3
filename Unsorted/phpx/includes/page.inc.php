<?php
#$Id: page.inc.php,v 1.8 2003/11/20 17:13:42 ryan Exp $
class page {

    var $templates;
    var $textArray;
    var $id;

    function page($id, $textArray, $templates){
        $this->core = new core();
        $this->id = $id;
        $this->textArray = $textArray;
        $this->templates = $templates;
    }

    function contact(){
        $id = $this->id;
        if ($_POST['confirm'] == 1){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $to = $this->core->dbCall("webmasterEmail");

            $headers = "From: $name <$email>\r\n";
            $headers .= "X-Sender: <$email>\r\n";
            $headers .= "X-Mailer: PHP\r\n";
            $headers .= "X-Priority: 3\r\n";
            $headers .= "Reply-To: $email\r\n";
            $message = $_POST['comments'];
            mail($to, $this->core->dbCall("siteName") . " " . $this->textArray['Contact Form Submission'], $message, $headers);
            $text = $this->textArray['Your email has been sent.  Thank you for contacting'] . " " . $this->core->dbCall("siteName");
        }
        else {
            require("includes/formscript.inc.php");
            require("$this->templates/files/contact.tpl.php");
        }
        return $text;

    }


    function siteSearch($loop=''){

        $siteName = $this->core->dbCall("siteName");
        if ($_POST['confirm'] == 1 && $loop != 1){

            $version = mysql_get_server_info();
            $version = substr($version, 0, 1);
            $search = $this->siteSearch(1);

            if ($version == "4"){
                $result = mysql_query("select page_id, title, body from pages where match(title, body) against ('$_POST[keywords]' in boolean mode)");
                $result2 = mysql_query("select news_id, title, post, date from news where match(title, post) against ('$_POST[keywords]' in boolean mode) and delay = '0'");
            }
            else {
                $keywords = "%" . $_POST['keywords'] . "%";
                $result = mysql_query("select page_id, title, body from pages where match(title, body) against ('$keywords')");
                $result2 = mysql_query("select news_id, title, post, date from news where match(title, post) against ('$keywords') and delay = '0'");
            }
            $count = mysql_num_rows($result);
            $count1 = mysql_num_rows($result2);

            $text .= "<table border=0 cellpadding=1 cellspacing=1 width=95% align=center class=bg>";
            $text .= "<tr><td class=title>" . $this->textArray['Search Results'] . "</td></tr></table>";
            $text .= "<table border=0 cellpadding=1 cellspacing=1 width=95% align=center>";
            $text .= "<tr><td class=main colspan=2>$count " . $this->textArray['Pages'] . " " . $this->textArray['and'] . " $count1 " . $this->textArray['News Stories'] . " " . $this->textArray['found'] . "</td></tr>";
            $text .= "<tr><td class=main>";

            if ($count == 0 && $count1 == 0){
                $text .= "<table border=0 cellpadding=0 cellspacing=0 width=100% align=center><tr><td class=main>" . $this->textArray['No Results Found'] . "</td></tr></table>";
            }
            else {
                $text .= "<table border=0 cellpadding=0 cellspacing=0 width=95% align=center>";
                while(list($page_id, $title, $body) = mysql_fetch_row($result)){
                    $body = stripslashes(strip_tags($body));
                    $title = stripslashes($title);
                    $body = substr($body, 0, 50) . "...";
                    $text .= "<tr><td class=main><b>" . $this->textArray['Page']  . " : <a href=index.php?id=$page_id>$title</a></b><br>$body</td></tr>";
                }
                $text .= "<tr><td class=main><br><br></td></tr>";
                list($news_page_id) = mysql_fetch_row(mysql_query("select page_id from pages where body = ':NEWS:'"));
                while(list($news_id, $title, $body, $date) = mysql_fetch_row($result2)){
                    $body = stripslashes(strip_tags($body));
                    $title = stripslashes($title);
                    $body = substr($body, 0, 50) . "...";
                    $date = date($this->core->dbCall("dateFormat"), $date);
                    $text .= "<tr><td class=main><b>" . $this->textArray['News Story'] . ": <a href=index.php?id=$news_page_id&news_id=$news_id>$title</a></b> <font size=1>$date</font><br>$body</td></tr>";
                }
                $text .= "</table>";
            }
            $text .= "</td><td class=main valign=top>$search</td></tr>";
            //$text .= "<tr><td class=main colspan=2>$showing $more</td></tr>";
            $text .= "</table>";
        }
        else {
            $id = $this->id;
            $keywords = $_POST['keywords'];
            require("$this->templates/files/search.tpl.php");
        }
        return $text;
    }

    function publicstats(){


        list($total) = mysql_fetch_row(mysql_query("select count from stats where type = '4'"));
        $text = "<table border=0 cellpadding=0 cellspacing=1 width=400 align=center class=bg>";
        $text .= "<tr><td class=title align=center colspan=2>" . $this->core->dbCall("siteName") . " " . $this->textArray["Stats"] . "</td></tr></table><p>";
        $result = mysql_query("select name, count from stats where type = '0'");
        $oscount=0;
        $hold = array();
        while(list($name, $count) = mysql_fetch_row($result)){
            $oscount += $count;
            $hold[] = array($name, $count);
        }
        $text .= "<table border=0 cellpadding=0 cellspacing=1 width=400 align=center class=bg>";
        $text .= "<tr><td class=title align=center colspan=2>" . $this->textArray["Operating System"] . "</td></tr></table><table border=0 cellpadding=1 cellspacing=0 width=400 align=center>";
        $s = $oscount/100;
        foreach($hold as $h){
            $perc = ($h[1] / $s);
            $width = $perc * 2;
            $perc = round($perc, 2);
            $text .= "<tr><td align=left class=main><img src=images/$h[0].gif alt=$h[0]> $h[0]</td><td class=main><img src=images/" . $this->core->dbCall("barColor") . ".gif width=$width alt='$h[0]' height=10> $perc % ($h[1])</td></tr>";
        }
        $text .= "</table><p>";

        $result = mysql_query("select name, count from stats where type = '3'");
        $oscount=0;
        $hold = array();
        while(list($name, $count) = mysql_fetch_row($result)){
            $oscount += $count;
            $hold[] = array($name, $count);
        }

        $text .= "<table border=0 cellpadding=0 cellspacing=1 width=400 align=center class=bg>";
        $text .= "<tr><td class=title align=center colspan=2>" . $this->textArray["Browsers"] . "</td></tr></table><table border=0 cellpadding=1 cellspacing=0 width=400 align=center>";
        $s = $oscount/100;
        foreach($hold as $h){
            $perc = ($h[1] / $s);
            $width = $perc * 2;
            $perc = round($perc, 2);
            $text .= "<tr><td align=left class=main><img src=images/$h[0].gif alt=$h[0]> $h[0]</td><td class=main><img src=images/" . $this->core->dbCall("barColor") . ".gif width=$width alt='$h[0]' height=10> $perc % ($h[1])</td></tr>";
        }
        $text .= "</table><p>";

        $result2 = mysql_query("select count(news_id) from news");
        list($temp2) = mysql_fetch_row($result2);
        $text .= "<table border=0 cellpadding=0 cellspacing=1 width=400 align=center class=bg>";
        $text .= "<tr><td class=title align=center colspan=2>" . $this->textArray["Other Stats"] . "</td></tr></table><table border=0 cellpadding=1 cellspacing=0 width=400 align=center>";
        $text .= "<tr><td align=left class=main>" . $this->textArray["Pageviews"] . "</td>";
        $text .= "<td align=left class=main>$total</td></tr>";
        $text .= "<tr><td align=left class=main>" . $this->textArray["News Published"] . "</td>";
        $text .= "<td align=left class=main>$temp2</td></tr>";
        $text .= "</table>";
        return $text;
    }

}
?>
