<?php
#$Id: sub.inc.php,v 1.1 2003/10/14 19:49:16 ryan Exp $
class subFunctions {


    function subFunctions($id, $textArray, $templates, $core){
        $this->id = $id;
        $this->templates = $templates;
        $this->textArray = $textArray;
        $this->core = $core;
    }

    function poll($body){
        $sub = substr($body, strpos($body, ':POLL:') + 6);
        $spos = strpos($sub, ':');
        $forum_poll_id = substr($sub, 0, $spos);

        list($poll_text, $total) = mysql_fetch_row(mysql_query("select forum_poll_text, total from forums_polls where forum_poll_id = '$forum_poll_id'"));
        list($check) = mysql_fetch_row(mysql_query("select count(forum_poll_user_id) from forums_polls_users where forum_poll_id = '$forum_poll_id' and user_id = '$_COOKIE[PXL]'"));

        list($forum_id, $topic_id, $comments) = mysql_fetch_row(mysql_query("select forum_id, topic_id, posts from forums_topics where forum_poll_id = '$forum_poll_id'"));
        list($anon, $unlim) = mysql_fetch_row(mysql_query("select anon_vote, unlimited_vote from forums_config"));

        $result = mysql_query("select option_id, option_text, option_results from forums_poll_results where forum_poll_id = '$forum_poll_id'") or die(mysql_error());
        if ((isset($_COOKIE['PXL']) || $anon == 1) && ($check == 0 || $unlim == 1) && $locked != "1"){
            $pollData .= "<form method=post action=forums.php?action=vote>";
            $pollData .= "<input type=hidden name=forum_poll_id value=$forum_poll_id>";
            $pollData .= "<input type=hidden name=public_poll value=1>";
            while(list($id, $option, $results) = mysql_fetch_row($result)){
                $option = stripslashes($option);
                $pollData .= "<tr><td class=main width=40 align=center><input type=radio value=$id name=options></td><td class=mainbold> $option </td></tr>";
            }
            $pollData .= "<tr><td class=main align=center colspan=2><input type=submit class=submit value='" . $this->textArray['Vote'] . "'></td></tr></form>";

        }
        else {
            $s = $total/100;
            $barColor = $this->core->dbCall("barColor");

            while(list($id, $option, $results) = mysql_fetch_row($result)){
                $option = stripslashes($option);

                if ($s > 0){
                    $perc = ($results / $s);
                    $width = $perc * 2;
                    $perc = round($perc);
                }
                else {
                    $perc = "0";
                    $width = "0";
                }

                $pollData .= "<tr><td class=mainbold>$option : </td>";
                $pollData .= "<td class=main><img src=images/$barColor.gif alt='$results' width=$width height=10> ($perc%) ($results)</td></tr>";
            }
            $pollData .= "<tr><td class=main colspan=2 align=center>" . $this->textArray['Total'] . " " . $this->textArray['Votes'] . ": $total</td></tr>";

        }
        include("$this->templates/files/poll.tpl.php");
        $rep = ":POLL:$forum_poll_id:";
        $body = str_replace($rep, $text, $body);
        return $body;
    }

    function whoIsOnline($body){
        $allow_pm = $this->core->forumCall("allow_pm");


        $time = time() - 300;
        $time = $this->core->getTime($time, 1);

        $result = mysql_query("select user_id from forums_session where last > '$time'");
        $total = mysql_num_rows($result);
        $guests=0;
        $members=0;
        $hidden=0;
        $member_list = ucfirst($this->textArray['members']) . " " . ucfirst($this->textArray['Online']) . ": ";
        while(list($user_id) = mysql_fetch_row($result)){
            if ($user_id == '0'){ $guests++; }
            else {
                list($username, $view_online) = mysql_fetch_row(mysql_query("select username, view_online from users where user_id = '$user_id'"));
                if ($view_online == 1){ $members++; $member_list .= "<a href=users.php?action=view&user_id=$user_id>$username</a>, "; }
                else { $hidden++; }
            }
        }
        if ($members == 0){ $member_list .= $this->textArray['None'] . "  "; }

        if ($total == 1){ $online .= "<tr><td class=main colspan=2>" . $this->textArray['Currently there is'] . " $total " . $this->textArray['user'] . " " . $this->textArray['online'] . "."; }
        else { $online .= "<tr><td class=main colspan=2>" . $this->textArray['Currently there are'] . " $total " . $this->textArray['users'] . " " . $this->textArray['online'] . "."; }
        $online .= " $members " . $this->textArray['members'] . ", $hidden " . $this->textArray['hidden'] . ", $guests " . $this->textArray['guests'] . ".<br>";
        $online .= substr($member_list, 0, -2) . "</td></tr>";


        $online .= "<tr><td class=subtitle colspan=2></td></tr>";
        $online .= "<tr><td class=main>";
        if (isset($_COOKIE[PXL]) && !isset($_COOKIE[DISMISS]) && $allow_pm == 1){
            list($count) = mysql_fetch_row(mysql_query("select count(pm_id) from forums_pm where user_id = '$_COOKIE[PXL]' and view = '0' and deleted = '0'"));
            if ($count != 0){

                $online .= "<div class=small id=pm style=\"display:none\"></div>";
                $online .= "<div class=small id=pm1><img src=images/pm3.gif border=0 alt=" . $this->textArray['New'] . "> " . $this->textArray['You have'] . " $count " . $this->textArray['unread private messages'] . ". &nbsp;<a href=forums.php?action=pm&subaction=inbox>" . $this->textArray['View'] . "</a> | <a href=forums.php?action=dismiss onclick=\"displaySubs('pm'); displaySubs1('pm1')\">" . $this->textArray['Dismiss'] . "</a>";
            }
        }

        $online .= "</td></tr><tr><td class=main>";
        if (isset($_COOKIE[PXL])){
            list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$_COOKIE[PXL]'"));
            $online .= "<script language=javascript>";
            $online .= "function submitLogout(){ ";
            $online .= "document.f.submit(); } </script>";

            $online .= "<form method=post action=users.php?action=login name=f>";
            $online .= "<input type=hidden name=url value='$_SERVER[REQUEST_URI]'><input type=hidden name=confirm value=1>";
            $online .= "" . $this->textArray['Logged in as'] . " <a href=users.php?action=view&user_id=$_COOKIE[PXL]>$username</a> | ";
            if ($allow_pm == 1){ $online .= "<a href=forums.php?action=pm&subaction=inbox>" . $this->textArray['Inbox'] . "</a> |"; }
            $online .= " <a href='javascript:submitLogout()'>" . $this->textArray['Logout'] . "</a></form>";
        }
        else {
            $online .= "<form method=post action=users.php?action=login><input type=hidden name=confirm value=1>";
            $online .= "<input type=hidden name=url value='$_SERVER[REQUEST_URI]'>";
            $online .= "<div class=small>" . $this->textArray['Login with username and password'] . ":<br></div>";
            $online .= "<input type=online value='' name=username size=8> <input type=password name=password value='' size=8> ";
            $online .= "<input type=submit class=submit value='" . $this->textArray['Login'] . "'><br><input type=checkbox name=remember checked>" . $this->textArray['Remember Me'] . "<br>";
            $online .= "<a href=users.php?action=signup>" . $this->textArray['Register Here'] . "</a> | <a href=users.php?action=password>" . $this->textArray['Lost Password'] . "</a>";

        }


        include("$this->templates/files/whoisonline.tpl.php");
        $body = str_replace(":ONLINE:", $text, $body);
        return $body;


    }



}
















































