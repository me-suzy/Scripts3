<?php
#$Id: users.inc.php,v 1.23 2003/11/20 17:13:42 ryan Exp $
require_once("includes/text.inc.php");
class userModule {

    function userModule($id, $textArray, $templates){
        $this->action = $_GET['action'];
        $this->core = new core();
        $this->core->textArray = $textArray;
        $this->core->templates = $templates;
        $this->ssl = $this->core->dbCall("secure");
        if ($_SERVER["SERVER_PORT"] != 443 && $this->ssl == 1){
            $head = "Location: https://" . $_SERVER["HTTP_HOST"] .  $_SERVER["REQUEST_URI"];
            header($head);
        }
        $this->id = $id;
        $this->textArray = $textArray;
        $this->templates = $templates;

        $this->textFun = new textFunctions();
        list($words, $html, $xcode, $smiles) = mysql_fetch_row(mysql_query("select censor_words, allow_html, allow_xcode, allow_smiles from forums_config"));
        $this->textFun->words = $words;
        $this->textFun->html = $html;
        $this->textFun->xcode = $xcode;
        $this->textFun->smiles = $smiles;

        switch($this->action){
            case signup:
                $this->signupUser();
                break;
            case modify:
                $this->modifyUser();
                break;
            case email:
                $this->emailUser();
                break;
            case view:
                $this->viewUser();
                break;
            case activate:
                $this->activateUser();
                break;
            case password:
                $this->passwordLookup();
                break;
            case login:
                $this->body = $this->core->login();
                break;
            case avatar:
                $this->viewAvatars();
                break;
            default:
                $this->listUsers();
        }
    }

    function signupUser($code=''){
        if ($_POST['confirm'] == 1 && $code == ''){
            $username = strip_tags(str_replace("'", '', $_POST['username']));
            $user = strtolower($username);
            $password = md5(strip_tags(str_replace("'", '', $_POST['password'])));
            list($count) = mysql_fetch_row(mysql_query("select count(user_id) from users where email = '$_POST[email]'"));
            if ($count != 0){ $this->signupUser(1); }
            else {
                list($count) = mysql_fetch_row(mysql_query("select count(user_id) from users where lower(username) = '$user'"));
                if ($count != 0){ $this->signupUser(2); }
                else {
                    $time = $this->core->getTime(time(), 1);
                    mysql_query("insert into users (username, password, email, regdate) values ('$username', '$password', '$_POST[email]', '$time')");
                    $user_id = mysql_insert_id();
                    $act = $this->core->forumCall("account_activate");
                    $siteURL = $this->core->dbCall("siteURL");
                    $siteName = $this->core->dbCall("siteName");
                    $webmasterEmail = $this->core->dbCall("webmasterEmail");

                    $headers = "From: $siteName " . $this->textArray['Mailer'] . " <$webmasterEmail>\r\n";
                    $headers .= "X-Sender: <$webmasterEmail>\r\n";
                    $headers .= "X-Mailer: PHP\r\n";
                    $headers .= "X-Priority: 3\r\n";
                    $headers .= "Reply-To: $webmasterEmail\r\n";
                    $subject = "$siteName " . $this->textArray['Mailer'] . "";
                    $message = $this->textArray['Thank you for Registering at'] . " $siteName.\r\n\r\n";
                    $message .= $this->textArray['Username'] . ": $username\r\n" . $this->textArray['Password'] . ": $_POST[password]\r\n\r\n";
                    $message .= $textArray['You may login at'] . ": $siteURL/users.php?action=login\r\n\r\n";

                    $messageFooter = $textArray['Do not reply to this message.'];

                    if ($act == 1){
                        $chars = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G");
                        $max_elements = count($chars) - 1;
                        srand((double)microtime()*1000000);
                        $pw = time() . $chars[rand(0,$max_elements)] . $chars[rand(0,$max_elements)];
                        $actkey = md5($pw);
                        mysql_query("update users set actkey = '$actkey' where user_id = '$user_id'") or die(mysql_error());
                        $subject .= " - " . $this->textArray['Activation Required'] . "";
                        $message .= $this->textArray['In order to activate your account, please go to the following URL'] . ":\r\n\r\n";
                        $message .= $siteURL . "/users.php?action=activate&actkey=$actkey&user_id=$user_id \r\n\r\n";
                        $message .= $messageFooter;
                        mail($_POST['email'], $subject, $message, $headers);
                        $text .= "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
                        $text .= "<tr><td class=title align=center>" . $this->textArray['Register'] . "</td></tr>";
                        $text .= "<tr><td class=main align=center>" . $this->textArray['You will receive an email with further instructions shortly.'] . "</td></tr></table>";
                        $this->body = $text;
                    }
                    else {
                        mail($_POST['email'], $subject, $message, $headers);
                        setcookie("PXL", $user_id, 0, '', '', $this->ssl);
                        header("Location: users.php?action=modify");
                    }
                }
            }
        }
        else {
            $agreement = $this->core->forumCall("agreement");
            $agreement = $this->textFun->convertText($agreement, 0);

            require("includes/formscript.inc.php");
            $text .= "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
            $text .= "<tr><td class=title align=center>" . $this->textArray['Register'] . "</td></tr>";
            $text .= "</table><table border=0 cellpadding=4 cellspacing=1 align=center width=95% class=outline>";
            $text .= "<form method=post action=users.php?action=signup onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>" . $this->textArray['Username'] . ": </td><td class=main><input type=text size=25 name=username value='$_POST[username]'>";
            if ($code == 2){ $text .= "<br><div class=attn>" . strtolower($this->textArray['Username']) . " " . $this->textArray['already in use'] . "</div>"; }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold-alt>" . $this->textArray['Password'] . ": </td><td class=main-alt><input type=text size=25 name=password></td></tr>";
            $text .= "<tr><td class=mainbold>" . $this->textArray['Email'] . ": </td><td class=main><input type=text size=50 name=email value='$_POST[email]'>";
            if ($code == 1){ $text .= "<br><div class=attn>" . strtolower($this->textArray['Email']) . " " . $this->textArray['already in use'] . "</div>"; }
            $text .= "</td></tr>";
            $text .= "<tr><td class=main align=center colspan=2><input type=submit class=submit value='" . $this->textArray['Register'] . "'></td></tr></form></table>";
            $text .= "<script language=javascript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.username,\"" . $this->textArray['Username'] . "\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.password,\"" . $this->textArray['Password'] . "\"))";
            $text .= "        return false;";
            $text .= "    if (!validEmail(theForm.email,\"" . $this->textArray['Email'] . "\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}</script>";
            $text .= "<p><table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
            $text .= "<tr><td class=title align=center>" . $this->textArray['Terms of Service'] . "</td></tr>";
            $text .= "<tr><td class=main>$agreement</td></tr></table>";
            $this->body = $text;
        }

    }

    function activateUser(){
        $result = mysql_query("select user_id from users where actkey = '$_GET[actkey]' and user_id = '$_GET[user_id]'") or die(mysql_error());
        $count = mysql_num_rows($result);
        if ($count == 0){
            $text .= "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
            $text .= "<tr><td class=title align=center>" . $this->textArray['Register'] . "</td></tr>";
            $text .= "<tr><td class=main align=center>" . $this->textArray['An Error Occured.  Account Not Active.'] . "</td></tr></table>";
            $this->body = $text;
        }
        else {
            mysql_query("update users set actkey = null where user_id = '$_GET[user_id]'");
            setcookie("PXL", $_GET[user_id], 0, '', '', $this->ssl);
            header("Location: users.php?action=modify");
        }
    }

    function passwordLookup($code=''){
        if ($_POST[confirm] == 1 && $code == ''){
            $result = mysql_query("select user_id, username from users where email = '$_POST[email]'");
            $count = mysql_num_rows($result);
            if ($count == 0){ $this->passwordLookup(1); }
            else {
                $siteURL = $this->core->dbCall("siteURL");
                $siteName = $this->core->dbCall("siteName");
                $webmasterEmail = $this->core->dbCall("webmasterEmail");

                $chars = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J", "k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9","0");
                $max_elements = count($chars) - 1;
                srand((double)microtime()*1000000);
                $newpw = $chars[rand(0,$max_elements)];
                $newpw .= $chars[rand(0,$max_elements)];
                $newpw .= $chars[rand(0,$max_elements)];
                $newpw .= $chars[rand(0,$max_elements)];
                $newpw .= $chars[rand(0,$max_elements)];
                $newpw .= $chars[rand(0,$max_elements)];
                $newpw .= $chars[rand(0,$max_elements)];
                $newpw .= $chars[rand(0,$max_elements)];
                $newpwe = md5($newpw);
                list($user_id, $username) = mysql_fetch_row($result);
                mysql_query("update users set password = '$newpwe' where user_id = '$user_id'");

                $headers = "From: $siteName " . $this->textArray['Mailer'] . " <$_POST[email]>\r\n";
                $headers .= "X-Sender: <$_POST[email]>\r\n";
                $headers .= "X-Mailer: PHP\r\n";
                $headers .= "X-Priority: 3\r\n";
                $headers .= "Reply-To: $_POST[email]\r\n";
                $subject = "$siteName " . $this->textArray['Mailer'];
                $message = $this->textArray['Your login information is as follows'] . ":\r\n\r\n";
                $message .= $this->textArray['Username'] . ": $username\r\n" . $this->textArray['Password'] . ": $newpw\r\n";
                $message .= $this->textArray['You may login at'] . " $siteURL/users.php?action=login\r\n\r\n";
                $message .= $this->textArray['Do not reply to this message.'];
                mail($_POST[email], $subject, $message, $headers);
                $text .= "<table border=0 cellpadding=0 cellspacing=1 width=250 class=outline align=center>";
                $text .= "<tr><td class=title align=center>" . $this->textArray['Lost Password'] . "</td></tr>";
                $text .= "<tr><td class=main align=center>" . $this->textArray['Your password has been emailed to you.'] . "</td></tr></table>";
                $this->body = $text;

            }
        }
        else {

            require("includes/formscript.inc.php");
            $text .= "<table border=0 cellpadding=0 cellspacing=1 width=250 class=outline align=center>";
            $text .= "<tr><td class=title align=center>" . $this->textArray['Lost Password'] . "</td></tr>";
            $text .= "</td></tr><tr><td class=main><table border=0 cellpadding=4 cellspacing=1 align=center width=95%>";
            $text .= "<form method=post action=users.php?action=password  onsubmit=\"return validateForm(this)\"><input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>" . $this->textArray['Email'] . ": </td><td class=main><input type=text name=email size=35 value='$_POST[email]'>";
            if ($code == 1){ $text .= "<div class=attn>" . strtolower($this->textArray['Email']) . " " . $this->textArray['not valid'] . "</div>"; }
            $text .= "</td></tr>";
            $text .= "<tr><td class=main align=center colspan=2><input type=submit class=submit value='" . $this->textArray['Enter'] . "'></td></tr></form></table></td></tr></table>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validEmail(theForm.email,\"" . $this->textArray['Email'] . "\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $text;

        }
    }

    function viewAvatars(){
        if ($_POST['confirm'] == 1){
            mysql_query("update users set av_id = '$_POST[av_id]' where user_id = '$_COOKIE[PXL]'");
            header("Location: users.php?action=modify&tab=avatar");
        }
        else {
            $text .= "<table border=0 cellpadding=0 cellspacing=1 width=500 class=outline align=center>";
            $text .= "<tr><td class=title align=center>" . $this->textArray['Avatar'] . "</td></tr>";
            $text .= "</td></tr><tr><td class=main><table border=0 cellpadding=8 cellspacing=8 align=center width=95%>";
            $text .= "<form name=avatar method=post action=users.php?action=avatar><input type=hidden name=av_id value=''><input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=subtitle colspan=5>" . $this->textArray['this page may be slow to load'] . " ... " . $this->textArray['click image to select'] . "</td></tr>";
            $result = mysql_query("select av_id, file from forums_avatars where user_id = '0'");
            $x=1;
            while(list($av_id, $file) = mysql_fetch_row($result)){
                if ($x == 1){ $text .= "<tr>"; }
                $text .= "<td class=main align=center><a href=javascript:submitAvatar('$av_id')><img src=images/avatars/$file alt='" . $this->textArray['Avatar'] . "' border=0></td>";
                $x++;
                if ($x == 6){ $text .= "</tr>"; $x=1; }
            }
            $text .= "</tr></table></td></form></tr></table>";
        }
        $this->body = $text;

    }



    function modifyUser($code=''){

        if ($_POST['confirm'] == 1 && $code == '' && isset($_COOKIE[PXL])){
            if (isset($_POST[email])){
                $result = mysql_query("select count(user_id) from users where email = '$_POST[email]' and user_id != '$_COOKIE[PXL]'");
                list($count) = mysql_fetch_row($result);
            }
            else { $count = 0; }
            if ($count != 0){ $this->modifyUser(1); }
            else {

                $flip = array_keys($_POST);

                foreach($flip as $f){
                    if ($f == "pass1"){
                        if ($_POST[$f] != ''){
                            if ($_POST[$f] != $_POST['pass2']){
                                $pass=1;
                            }
                            else {
                                $password = md5($_POST[$f]);
                                mysql_query("update users set password = '$password' where user_id = '$_COOKIE[PXL]'");
                                $pass=2;
                            }
                         }

                    }

                    else if ($f != "confirm" && $f != "av_id" && $f != "disable_notify" && $f != "rank" && $f != "list2" && $f != "pass2" && $f != "list3"){
                        if ($f == "sig"){ $flag = 1; }
                        else { $flag = 0; }
                        $_POST[$f] = $this->textFun->convertText($_POST[$f], 1, $flag);
                        $sql = "update users set $f = '$_POST[$f]' where user_id = '$_COOKIE[PXL]'";
                        mysql_query($sql);
                        if ($f == "remote_avatar" && $_POST[$f] != ''){
                            mysql_query("update users set av_id = '0' where user_id = '$_COOKIE[PXL]'");
                        }
                    }
                    else if ($f == "av_id" && $_POST[$f] != ''){

                            list($_POST[$f]) = mysql_fetch_row(mysql_query("select av_id from forums_avatars where file = '$_POST[$f]'"));
                            mysql_query("update users set $f = '$_POST[$f]' where user_id = '$_COOKIE[PXL]'");
                    }

                    else if ($f == "disable_notify" && $_POST[$f] == '1'){
                        mysql_query("delete from forums_notify where user_id = '$_COOKIE[PXL]'");
                    }

                    else if ($f == "rank"){
                        if ($_POST[$f] != ''){
                            $rank = $this->textFun->convertText($_POST[$f]);
                            list($rank_id) = mysql_fetch_row(mysql_query("select rank_id from forums_ranks where rank_name = '$rank' and rank_spec = '1'"));
                            if ($rank_id == ''){
                                mysql_query("insert into forums_ranks (rank_name, rank_spec) values ('$rank', '1')");
                                $rank_id = mysql_insert_id();
                            }
                        }
                        else { $rank_id = 0; }
                        mysql_query("update users set rank = '$rank_id' where user_id = '$_COOKIE[PXL]'");
                    }

                    else if ($f == "list2"){
                        $list2 = explode(",", $_POST[$f]);
                        $result = mysql_query("select ignore_user_id from forums_ignore where user_id = '$_COOKIE[PXL]'");
                        while(list($id) = mysql_fetch_row($result)){
                            if (!in_array($id, $list2)){ mysql_query("delete from forums_ignore where ignore_user_id = '$id' and user_id = '$_COOKIE[PXL]'"); }
                        }
                    }
                    else if ($f == "list3"){
                        mysql_query("delete from user_news_view where user_id = '$_COOKIE[PXL]'");
                        foreach($_POST[$f] as $l){
                            mysql_query("insert into user_news_view values ('$_COOKIE[PXL]', '$l')");
                        }
                    }
                }
                $check = $_FILES['avatar_upload'][tmp_name];
                if ($check != ''){
                    global $image_types;
                    $ext = array_flip($image_types);
                    $file_type = $_FILES['avatar_upload'][type];
                    if (in_array($file_type, $image_types)){
                        list($old) = mysql_fetch_row(mysql_query("select file from forums_avatars where user_id = '$_COOKIE[PXL]'"));
                        if ($old != ''){

                            $old = "images/avatars/" . $old;
                            unlink($old);
                            $old=1;
                        }
                        $newName = time() . $ext[$_FILES[avatar_upload][type]];
                        $filesend =  "images/avatars/" . $newName;
                        copy($check, $filesend);
                        if ($old == 1){
                            mysql_query("update forums_avatars set file = '$newName', name = '$newName' where user_id = '$_COOKIE[PXL]'");
                            list($av_id) = mysql_fetch_row(mysql_query("select av_id from forums_avatars where user_id = '$_COOKIE[PXL]'"));

                        }
                        else {
                            mysql_query("insert into forums_avatars values ('', '$_COOKIE[PXL]', '$newName', '$newName')");
                            $av_id = mysql_insert_id();
                        }
                        mysql_query("update users set av_id = '$av_id' where user_id = '$_COOKIE[PXL]'");
                    }
                }
                header("Location: users.php?action=modify&code=2&pass=$pass&tab=$_GET[tab]");
            }
        }
        else {
            if (!isset($_COOKIE[PXL])){ $text = $this->core->login(0, "users.php?action=modify"); $this->body = $text;}
            else {
                $max_sig_length = $this->core->forumCall("max_sig_length");
                if ($code == ''){ $code = $_GET['code']; }
                $passwordArray = array("1"=>"<br>" . $this->textArray['Passwords do not match'], "2"=>"<br>" . $this->textArray['Password Changed']);
                if ($_GET['pass'] != ''){ $cc = $_GET['pass']; $pass = $passwordArray[$cc]; }
                require("includes/formscript.inc.php");
                list($allow_ranks, $allow_avatars, $remote_avatars, $avatar_upload, $max_sig, $allow_flags) = mysql_fetch_row(mysql_query("select allow_ranks, allow_avatars, remote_avatars, avatar_upload, max_sig_length, allow_flags from forums_config"));

                $row = mysql_fetch_array(mysql_query("select * from users where user_id = '$_COOKIE[PXL]'"));

                $keys = array_keys($row);

                foreach($keys as $k){
                    if ($row[$k] != '' && $k != "sig"){
                        $row[$k] = $this->textFun->convertText($row[$k], 0, 0);
                    }
                    else if ($k == "sig"){ $row[$k] = $this->textFun->convertText($row[$k], 0, 1); }
                }

                if ($code == 1){ $row[email] = $_POST[email]; }
                $text .= "<link rel=\"stylesheet\" href=\"includes/master-style.css\">";
                $text .= "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center>";
                $text .= "<tr><td class=title align=center>" . $this->textArray['User Profile'] . " " . $this->textArray['for'] . " $row[username]</td></tr></table>";
                $text .= "<table border=0 cellpadding=4 cellspacing=1 width=95% class=outline align=center>";
                if ($code == 2){ $text .= "<tr><td class=main colspan=2><div class=attn><b>Profile Saved</b>$pass</div></td></tr>"; }
                $text .= "<form method=post name=f action=users.php?action=modify onsubmit=\"return validateForm(this)\" enctype='multipart/form-data'><input type=hidden name=confirm value=1>";
                $text .= "<table border=0 cellpadding=0 cellspacing=0 class=outline align=center width=80%>";
                $text .= "<tr><td class=main><br><table border=0 cellpadding=0 cellspacing=0>";
                $text .= "<tr>";
                $text .= "<td id=iProfile class=tab width=75 align=center onMouseOver=\"style.cursor='pointer';\" onClick=showSub('Profile')>" . $this->textArray['Profile'] . "</td>";
                $text .= "<td class=main width=20>&nbsp;</td>";
                $text .= "<td id=iOptions class=tab width=75 align=center onMouseOver=\"style.cursor='pointer';\" onClick=showSub('Options')>" . $this->textArray['Options'] . "</td>";
                $text .= "<td class=main width=20>&nbsp;</td>";
                if ($allow_avatars == 1){ $text .= "<td id=iAvatars class=tab width=75 align=center onMouseOver=\"style.cursor='pointer';\" onClick=showSub('Avatars')>" . $this->textArray['Avatars'] . "</td>"; }


                $text .= "</tr></table></td></tr></table>";

                $text .= "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
                $text .= "<tr><td class=main>";

                $text .= "<div style=\"display:none\" id=Profile>";
                $text .= "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
                $text .= "<tr><td class=subtitle colspan=2 align=center>" . $this->textArray['User Profile'] . "</td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['first_name'] . ": </td><td class=main><input type=text value='$row[first_name]' name=first_name size=35></td></tr>";
                $text .= "<tr><td class=mainbold-alt>" . $this->textArray['last_name'] . ": </td><td class=main-alt><input type=text value='$row[last_name]' name=last_name size=35></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['Email'] . ": </td><td class=main><input type=text value='$row[email]' name=email size=35>";
                if ($code == 1){ $text .= "<div class=attn>" . strtolower($this->textArray['Email']) . " " . $this->textArray['already in use'] . "</div>"; }
                $text .= "</td></tr>";
                $text .= "<tr><td class=mainbold-alt>" . $this->textArray['Password'] . ": </td><td class=main-alt><input type=password name=pass1 size=16> <span class=small>" . $this->textArray['only required if changing'] . "</span></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['Confirm'] . " " . $this->textArray['Password'] . ": </td><td class=main><input type=password name=pass2 size=16></td></tr>";

                $text .= "<tr><td class=mainbold-alt>" . $this->textArray['Website'] . ": </td><td class=main-alt><input type=text value='$row[website]' name=website size=35></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['loc'] . ": </td><td class=main><input type=text value='$row[loc]' name=loc size=35></td></tr>";
                $text .= "<tr><td class=mainbold-alt>" . $this->textArray['occ'] . ": </td><td class=main-alt><input type=text value='$row[occ]' name=occ size=35></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['interest'] . ": </td><td class=main><input type=text value='$row[interest]' name=interest size=35></td></tr>";
                $text .= "<tr><td class=mainbold-alt>" . $this->textArray['aim'] . ": </td><td class=main-alt><input type=text value='$row[aim]' name=aim size=35></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['yim'] . ": </td><td class=main><input type=text value='$row[yim]' name=yim size=35></td></tr>";
                $text .= "<tr><td class=mainbold-alt>" . $this->textArray['msn'] . ": </td><td class=main-alt><input type=text value='$row[msn]' name=msn size=35></td></tr>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['icq'] . ": </td><td class=main><input type=text value='$row[icq]' name=icq size=35></td></tr>";
                $text .= "<tr><td class=mainbold-alt>" . $this->textArray['imood'] . ": </td><td class=main-alt><input type=text value='$row[imood]' name=imood size=35></td></tr>";
                $text .= "<tr><td class=mainbold valign=top>" . $this->textArray['sig'] . ": </td><td class=main><textarea cols=50 rows=8 name=sig onKeyDown=\"textCounter(this.form.sig,this.form.remLen,$max_sig_length);\" onKeyUp=\"textCounter(this.form.sig,this.form.remLen,$max_sig_length);\">$row[sig]</textarea>";
                $len = $max_sig_length - strlen($row[sig]);
                $text .= " <input readonly type=text name=remLen size=4 maxlength=4 value=$len><span class=small> " . $this->textArray['character limit'] . "</span></td></tr>";
                $text .= "<tr><td class=mainbold-alt>" . $this->textArray['Time Zone'] . ": </td><td class=main-alt><select name=timeZone>";
                $text .= $this->timeSelect($row[timeZone]);
                $text .= "</select></td></tr>";
                if ($allow_flags == 1){
                    $text .= "<tr><td class=mainbold>" . $this->textArray['Country'] . ": </td><td class=main><select name=flag_id>";
                    $text .= $this->flagSelect($row[flag_id]);
                    $text .= "</select>";
                    if ($row[flag_id] != '' && $row[flag_id] != 0){
                        list($flag_file, $flag_name) = mysql_fetch_row(mysql_query("select flag_file, flag_name from forums_flags where flag_id = '$row[flag_id]'"));
                        $text .= " <img src=images/flags/$flag_file border=0 alt='$flag_name'>";
                    }
                    $text .= "</td></tr>";
                }

                if ($allow_ranks == 1){
                    if ($row[rank] == 0){ $row[rank] = ''; }
                    else {
                        list($rank_name) = mysql_fetch_row(mysql_query("select rank_name from forums_ranks where rank_spec = '1' and rank_id = '$row[rank]'"));
                        $row[rank] = $rank_name;
                    }
                    $text .= "<tr><td class=mainbold-alt>" . $this->textArray['rank1'] . ": </td><td class=main-alt><input type=text value='$row[rank]' name=rank size=35></td></tr>";

                }
                $text .= "</table>";
                $text .= "</div>";
                $text .= "<div id=Options>";
                $text .= "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
                $text .= "<tr><td class=subtitle colspan=2 align=center>" . $this->textArray['User Options'] . "</td></tr>";
                $text .= $this->createRadio("attach_sig", $this->textArray['attach_sig'], $row[attach_sig], 0);
                $text .= $this->createRadio("view_email", $this->textArray['view_email'], $row[view_email], 1);
                $text .= $this->createRadio("view_online", $this->textArray['view_online'], $row[view_online], 0);
                $text .= $this->createRadio("disable_notify", $this->textArray['Disable All Notifies'], '', 1);
                $text .= $this->createRadio("pm_email", $this->textArray['Private Message Notification by Email'], $row[pm_email], 0);
                $text .= $this->createRadio("news_notify", $this->textArray['News Notification by Email'], $row[news_notify], 1);
                $text .= $this->getUserIgnoreForm($class, $bold);
                $text .= $this->getNewsCatForm();
                $text .= "</table></div>";
                if ($allow_avatars == 1){
                    $text .= "<div style=\"display:none\" id=Avatars>";
                    $result = mysql_query("select av_id, file from forums_avatars where user_id = '0' order by file");
                    $options = "<option value=''></option>";
                    while(list($av_id, $file) = mysql_fetch_row($result)){
                        if ($av_id == $row[av_id]){ $s = "selected"; }
                        else { $s = ''; }
                        $options .= "<option value=$file $s>$file</option>";
                    }

                    if ($row[av_id] != 0){
                        list($file) = mysql_fetch_row(mysql_query("select file from forums_avatars where av_id = '$row[av_id]'"));
                        $current = "<img src=images/avatars/$file name=avatarImage border=0 alt='" . $this->textArray['Current'] . " " . $this->textArray['Avatar'] . "' width=45 height=45>";
                    }
                    else if ($row[remote_avatar] != ''){
                        $current = "<img src=$row[remote_avatar] name=avatarImage border=0 alt='" . $this->textArray['Current'] . " " . $this->textArray['Avatar'] . "' width=45 height=45>";
                    }
                    else { $current = "<img src=images/nofile.gif name=avatarImage border=0 alt='" . $this->textArray['Current'] . " " . $this->textArray['Avatar'] . "' width=45 height=45>"; }

                    $text .= "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
                    $text .= "<tr><td class=subtitle colspan=2 align=center>" . $this->textArray['Avatar'] . "</td></tr>";
                    $text .= "<tr><td class=main colspan=2><table border=0 cellpadding=4 cellspacing=0 width=100%>";
                    $text .= "<tr><td class=mainbold>" . $this->textArray['Avatar'] . ": </td><td class=main><select name=av_id onChange=\"changeAvatarImage(this.form)\">$options</select><br><a href=users.php?action=avatar>" . $this->textArray['view all'] . "</a></td>";
                    $text .= "<td class=main valign=center align=center rowspan=3>$current</td></tr>";
                    if ($remote_avatars == 1){
                        $text .= "<tr><td class=mainbold>" . $this->textArray['Remote'] . " " . $this->textArray['Avatar'] . ": </td><td class=main><input type=text value='$row[remote_avatar]' name=remote_avatar size=35></td></tr>";
                    }
                    if ($avatar_upload == 1){
                        $text .= "<tr><td class=mainbold>" . $this->textArray['Upload'] . " " . $this->textArray['Avatar'] . ": </td><td class=main><input type=file value='' name=avatar_upload size=35></td></tr>";
                    }
                    $text .= "</table>";
                    $text .= "</div>";
                }
                $text .= "</td></tr></table>";
                $text .= "<tr><td class=main align=center colspan=2><br><input class=submit type=submit value='" . $this->textArray['Enter'] . "'>";
                $text .= "</td></tr></form></table>";
                $text .= "<script language=\"JavaScript\" src=\"includes/post.js\"></script>";
                $text .= "<script language=javascript>onLoadShowSub();</script>";
                $text .= "<script Language=JavaScript>";
                $text .= "function validateForm(theForm)";
                $text .= "{";
                $text .= "    if (document.f.list1){ ";
                $text .= "        selectAllOptions(document.f.list1);";
                $text .= "        document.f.list2.value = getSelectedValues(document.f.list1);";
                $text .= "    } ";
                $text .= "    return true;";
                $text .= "}";
                $text .= "</script>";
                $this->body = $text;
            }
        }
    }

    function timeSelect($id){
        for($x='-12'; $x < 13; $x++){
            if ($x == 0){ $s = "GMT"; }
            else if ($x > 0){ $s = "GMT +$x"; }
            else { $s = "GMT $x"; }

            if ($x == $id){ $text .= "<option value=$x selected>$s</option>"; }
            else { $text .= "<option value=$x>$s</option>"; }
        }
        return $text;
    }

    function flagSelect($id){
        if ($id == '' || $id == 0){ $id = 1; }
        $result = mysql_query("select flag_id, flag_name from forums_flags order by flag_name");
        while(list($flag_id, $flag_name) = mysql_fetch_row($result)){
            if ($flag_id == $id){ $s = "selected"; }
            else { $s = ''; }
            $text .= "<option value=$flag_id $s>$flag_name</option>";
        }
        return $text;
    }

    function createRadio($selectName, $formName, $default='', $alt){
        if ($alt == 1){ $text = "<tr><td class=mainbold-alt>$formName: </td><td class=main-alt>"; }
        else { $text = "<tr><td class=mainbold>$formName: </td><td class=main>"; }
        if ($default == 0){ $text .= "<input type=radio name=$selectName value=1>Yes <input type=radio name=$selectName value=0 checked>No"; }
        else { $text .= "<input type=radio name=$selectName value=1 checked>Yes <input type=radio name=$selectName value=0>No"; }
        $text .= "</td></tr>";
        return $text;
    }

    function viewUser(){
        $dis = array("loc", "occ", "interest", "aim", "yim", "msn", "icq");

        if ($_GET[user_id] == ''){ $_GET[user_id] = $_COOKIE[PXL]; }
        $result = mysql_query("select * from users where user_id = '$_GET[user_id]'");
        $check = mysql_num_rows($result);
        if ($check != "1" || $_GET['user_id'] == 1){
            $text = "<div class=main>" . $this->textArray['No User Found'] . "</div>";
        }
        else {
            $row = mysql_fetch_array($result);

            if ($row[view_email] == 1){ $email = $row[email]; }
            else { $email = $this->textArray[Private]; }

            $dateFormat = $this->core->dbCall("dateFormat");
            $date = $this->core->getTime($row[regdate]);
            $date = date($dateFormat, $date);

            if ($row[rank] == '' || $row[rank] == 0){
                list($rank) = mysql_fetch_row(mysql_query("select rank_name from forums_ranks where rank_min < '$row[posts]' and rank_spec != '1' order by rank_min desc limit 0,1"));
            }
            else { list($rank) = mysql_fetch_row(mysql_query("select rank_name from forums_ranks where rank_id = '$row[rank]'")); }
            $rank = $rank . " (" . $row[posts] . " posts)";
            $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
            $text .= "<script language=javascript>";
            $text .= "MM_preloadImages('images/email_user_over.gif', 'images/pm_over.gif');";
            $text .= "</script>";
            $text .= "<tr><td class=title align=center>" . $this->textArray['User Profile'] . " " . $this->textArray['for'] . " $row[username]</td></tr>";
            $text .= "</table><table border=0 cellpadding=4 cellspacing=1 align=center width=95% class=outline>";
            $text .= "<tr><td class=mainbold width=35%>" . $this->textArray['Contact'] . " " . ucfirst($this->textArray['user']) . ": </td><td class=main width=*>$email";
            $allow_pm = $this->core->forumCall("allow_pm");
            if ($allow_pm == 1 && $_COOKIE[PXL] != $_GET[user_id]){ $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('pm', '', 'images/pm_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('forums.php?action=pm&user_id=$_GET[user_id]')><img src=images/pm.gif name=pm alt='" . $this->textArray['PM'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>"; }
            if ($_COOKIE[PXL] != $_GET[user_id]) { $text .= " <span onMouseOver=\"style.cursor='pointer'; MM_swapImage('email', '', 'images/email_user_over.gif',1)\" onMouseOut=\"MM_swapImgRestore()\" onClick=goToURL('users.php?action=email&user_id=$_GET[user_id]')><img src=images/email_user.gif name=email alt='" . $this->textArray['Email'] . " " . ucfirst($this->textArray['user']) . "' border=0></span>"; }

            $text .= "</td>";
            if ($row[av_id] != 0){
                list($file) = mysql_fetch_row(mysql_query("select file from forums_avatars where av_id = '$row[av_id]'"));
                $avatar = "<img src=images/avatars/$file alt='Avatar' width=45 height=45 border=0>";
            }
            else if ($row[remote_avatar] != ''){ $avatar = "<img src=$row[remove_avatar] alt='Avatar' width=45 height=45 border=0>"; }
            list($lastOnlineDate) = mysql_fetch_row(mysql_query("select last from forums_session where user_id = '$_GET[user_id]'"));
            if ($lastOnlineDate != '' && $lastOnlineDate != "0"){
                $lastOnlineDate = $this->core->getTime($lastOnlineDate);
                $lastOnlineDate = date($dateFormat . " H:i", $lastOnlineDate);
            }
            else { $lastOnlineDate = $this->textArray['unknown']; }

            $row[sig] = $this->textFun->convertText($row[sig]);
            $row[website] = $this->textFun->convertText($row[website]);
            $text .= "<td class=main rowspan=14 valign=top align=right width=25%>$avatar<br>$row[sig]</td></tr>";
            $text .= "<tr><td class=mainbold-alt>" . $this->textArray['Register'] . " " . $this->textArray['Date'] . ": </td><td class=main-alt>$date</td></tr> ";
            $text .= "<tr><td class=mainbold>" . $this->textArray['Last Online'] . ": </td><td class=main>$lastOnlineDate</td></tr>";
            $text .= "<tr><td class=mainbold-alt>" . $this->textArray['Website'] . ": </td><td class=main-alt><a href=$row[website] target=new>$row[website]</a></td></tr>";
            $text .= "<tr><td class=mainbold>" . $this->textArray['rank'] . ": </td><td class=main>$rank</td></tr>";

            $x=1;
            foreach($dis as $d){
                $row[$d] = $this->textFun->convertText($row[$d]);
                if ($x == 1){ $text .= "<tr><td class=mainbold-alt>" . $this->textArray[$d] . ": </td><td class=main-alt>$row[$d]</td></tr>"; $x++; }
                else { $text .= "<tr><td class=mainbold>" . $this->textArray[$d] . ": </td><td class=main>$row[$d]</td></tr>"; $x=1; }
            }
            $allow_flags = $this->core->forumCall("allow_flags");
            if ($allow_flags == 1 && $row[flag_id] != '' && $row[flag_id] != 0 && $row[flag_id] != 1){
                list($flag_file, $flag_name) = mysql_fetch_row(mysql_query("select flag_file, flag_name from forums_flags where flag_id = '$row[flag_id]'"));
                $text .= "<tr><td class=mainbold>" . $this->textArray['Country'] . ": </td><td class=main><img src=images/flags/$flag_file border=0 alt='$flag_name'></td></tr>";
            }
            $allow_imood = $this->core->forumCall("allow_imood");
            if ($allow_imood == 1 && $row[imood] != ''){
                $text .= "<tr><td class=mainbold-alt>Imood: </td><td class=main-alt>" . $this->textArray['I\'m Feeling'] . "...<img src=http://www.imood.com/query.cgi?email=" . $row[imood] . "&type=1&trans=1 alt=Imood border=0></td></tr>";
            }
            $text .= "</td></tr>";
            $text .= "</table><p>";
            $forums = $this->core->dbCall("forums");
            $news_sub = $this->core->dbCall("news_sub");
            if ($_COOKIE[PXL] == $_GET['user_id']){

                $text .= "<center><a href=users.php?action=modify>" . $this->textArray['Modify my Profile'] . "</a>";
                if ($allow_pm == 1){ $text .= " | <a href=forums.php?action=pm&subaction=inbox>" . $this->textArray['View Inbox'] . "</a>"; }
                $text .= "</center><p>";
            }
            if ($forums == 1){ $text .= $this->userForumPosts(1, $row[user_id]); }
            if ($news_sub == 1){ $text .= $this->userNewsSub($row[user_id]); }

        }
        $this->body = $text;
    }

    function userNewsSub($user_id){
        $cat = $this->core->dbCall("news_categories");
        $dateFormat = $this->core->dbCall("dateFormat");
        $text = "<br><table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
        $text .= "<tr><td class=title align=center>" . $this->textArray['News Stories'] . "</td></tr>";
        $text .= "</table><table border=0 cellpadding=2 cellspacing=1 align=center width=95% class=outline>";
        $result = mysql_query("select n.news_sub_title, n.news_sub_date, n.news_cat_id, s.status_name from news_sub n, status s where n.user_id = '$user_id' and s.status_id = n.status_id order by n.news_sub_date desc") or die(mysql_error());
        while(list($title, $date, $cat_id, $status) = mysql_fetch_row($result)){
            $title = stripslashes($title);

            $date = $this->core->getTime($date);
            $date = date($dateFormat, $date);

            if ($cat == 1){
                list($cat1) = mysql_fetch_row(mysql_query("select news_cat_title from news_cat where news_cat_id = '$cat_id'"));
                $cat1 = "<br><span class=small>" . $this->textArray['Category'] . " : <a href=news.php?action=view_cat&news_cat_id=$cat_id>$cat1</a></span>";
            }
            if ($status == "Posted" || $_COOKIE[PXL] == $user_id){ $text .= "<tr><td class=main>$title ($date) - " . $this->textArray[$status] . " $cat1 </td></tr>"; }
        }
        $text .= "</table>";
        return $text;
    }

    function userForumPosts($all, $user_id){
        $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
        $text .= "<tr><td class=title align=center>" . $this->textArray['Forum Posts'] . "</td></tr>";
        $text .= "</table><table border=0 cellpadding=2 cellspacing=1 align=center width=95% class=outline>";
        $text .= "<tr><td class=subtitle>" . $this->textArray['Post'] . "</td><td class=subtitle>" . $this->textArray['Date'] . "</td><td class=subtitle>" . $this->textArray['Forum'] . " >> " . $this->textArray['Topic'] . "</td></tr>";
        if ($all == 1){ $limit = 0; $setnum = 10; }

        $result = mysql_query("select p.post_id, p.topic_id, p.forum_id, p.title, p.date, f.name, t.post_id from forums_posts p, forums_forums f, forums_topics t where p.user_id = '$user_id' and f.forum_id = p.forum_id and p.topic_id = t.topic_id order by p.date desc limit $limit,$setnum") or die(mysql_error());
        if (!$result){ $text .= "<tr><td class=main colspan=3>No Forum Posts</td></tr>"; }
        else {
            $x=1;
            while(list($post_id, $topic_id, $forum_id, $title, $date, $name, $topic_post) = mysql_fetch_row($result)){
                if ($topic_post != $post_id){ list($topic) = mysql_fetch_row(mysql_query("select title from forums_posts where post_id = '$topic_post'")); }
                else { $topic = $title; }
                $dateFormat = $this->core->dbCall("dateFormat");
                $date = $this->core->getTime($date);
                $date = date($dateFormat, $date);

                $title = $this->textFun->convertText($title, 0, 0);
                $name = $this->textFun->convertText($name, 0, 0);
                $topic = $this->textFun->convertText($topic, 0, 0);
                if ($x == 1){ $class = "main"; $x++; }
                else { $class = "main-alt"; $x=1; }
                $text .= "<tr><td class=$class><a href=forums.php?forum_id=$forum_id&topic_id=$topic_id&post_id=$post_id#$post_id>$title</a></td>";
                $text .= "<td class=$class>$date</td><td class=$class>$name &gt;&gt; $topic</td></tr>";
            }
        }
        $text .= "</table>";
        return $text;
    }

    function listUsers(){
        if ($_GET['limit'] == ''){ $limit = 0; }
        else { $limit = $_GET['limit']; }

        $setnum = 50;
        $url = "users.php?action=";
        list($number) = mysql_fetch_row(mysql_query("select count(user_id) from users"));
        $more = $this->core->pageTab($limit, $number, $url, $setnum);
        $result = mysql_query("select * from users  where user_id != '1' order by username limit $limit, $setnum") or die(mysql_error());
        $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
        $text .= "<tr><td class=title align=center>" . $this->textArray['User List'] . "</td></tr></table>";
        $text .= "<table border=0 cellpadding=2 cellspacing=1 align=center width=95% class=outline>";
        $text .= "<tr><td class=main colspan=4>$more</td></tr>";
        $text .= "<tr><td class=subtitle>" . $this->textArray['Username'] . "</td><td class=subtitle>" . $this->textArray['Email'] . "</td>";
        $text .= "<td class=subtitle>" . $this->textArray['Register'] . " " . $this->textArray['Date'] . "</td>";
        $text .= "<td class=subtitle>" . $this->textArray['Website'] . "</td></tr>";
        $x=1;
        while($row = mysql_fetch_array($result)){
            if ($x == 1){ $class = "main"; $x++; }
            else { $class = "main-alt"; $x=1; }
            $text .= "<tr><td class=$class><a href=users.php?action=view&user_id=$row[user_id]>$row[username]</a></td>";
            if ($row[view_email] == 1){ $email = $row[email]; }
            else { $email = $this->textArray[Private]; }
            $text .= "<td class=$class>$email</td>";
            $dateFormat = $this->core->dbCall("dateFormat");
            $date = $this->core->getTime($row[regdate]);
            $date = date($dateFormat, $date);
            $text .= "<td class=$class align=center>$date</td>";
            $text .= "<td class=$class><a href=$row[website] target=new>$row[website]</a></td></tr>";
        }
        $text .= "<tr><td class=main colspan=4>$more</td></tr>";
        $text .= "</table>";

        $this->body = $text;
    }

    function emailUser(){
        if ($_POST['confirm'] == 1){
            if ($_COOKIE['VALID'] != 1){ die("Relay Forbidden"); }
            $siteName = $this->core->dbCall("siteName");
            list($toUsername, $to, $user_id) = mysql_fetch_row(mysql_query("select username, email, user_id from users where user_id = '$_POST[user_id]'"));
            list($username, $email) = mysql_fetch_row(mysql_query("select username, email from users where user_id = '$_COOKIE[PXL]'"));

            $headers = "From: $username <$email>\r\n";
            $headers .= "X-Sender: <$email>\r\n";
            $headers .= "X-Mailer: PHP\r\n";
            $headers .= "X-Priority: 3\r\n";
            $headers .= "Reply-To: $email\r\n";
            $subject = $this->textArray['Message'] . " " . $this->textArray['via'] . " $siteName : $_POST[subject]";
            list($c) = mysql_fetch_row(mysql_query("select count(user_id) from forums_ignore where user_id = '$user_id' and ignore_user_id = '$_COOKIE[PXL]'"));
            if ($c == 0){ mail($to, $subject, $_POST[bodyText], $headers); }
            $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
            $text .= "<tr><td class=title align=center>" . $this->textArray['Email User'] . ": $toUsername</td></tr>";
            $text .= "<tr><td class=main align=center>" . $this->textArray['Your email has been sent.'] . "</td></tr>";
            $this->body = $text;

        }
        else {
            if (!isset($_COOKIE["PXL"])){ $this->body = $this->core->login(0, "users.php?action=email&user_id=$_GET[user_id]"); }
            else {
                setcookie("VALID", 1, 0, '', '', $this->ssl);
                list($username) = mysql_fetch_row(mysql_query("select username from users where user_id = '$_GET[user_id]'"));
                $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
                $text .= "<tr><td class=title align=center>" . $this->textArray['Email User'] . ": $username</td></tr>";
                $text .= "<tr><td width=100% class=main><table border=0 cellpadding=2 cellspacing=1 align=center width=100%>";
                $text .= "<form method=post action=users.php?id=$this->id&action=email>";
                $text .= "<input type=hidden name=confirm value=1><input type=hidden name=user_id value=$_GET[user_id]>";
                $text .= "<tr><td class=mainbold>" . $this->textArray['Subject'] . ": </td><td class=main><input type=text name=subject size=40></td></tr>";
                $text .= "<tr><td class=mainbold valign=top>" . $this->textArray['Body Text'] . ": </td><td class=main><textarea name=bodyText cols=43 rows=15></textarea></td></tr>";
                $text .= "<tr><td class=main align=center colspan=2><input type=submit class=submit value='" . $this->textArray['Send Email'] . "'></td></tr>";
                $text .= "</table></td></tr></table>";
                $this->body = $text;
            }
        }
    }

    function getUserIgnoreForm($class, $bold){

        $hold = array();
        $result = mysql_query("select i.ignore_user_id, u.username from forums_ignore i, users u where i.user_id = '$_COOKIE[PXL]' and i.ignore_user_id = u.user_id");
        while(list($ignore_id, $username) = mysql_fetch_row($result)){
            $current .= "<option value=$ignore_id>$username</option>";
            $hold[] = $ignore_id;
        }
        $text = "<tr><td class=mainbold valign=top>" . $this->textArray['Ignore List'] . ": </td>";
        $text .= "<td class=main>";
        $text .= "<select multiple size=8 name=list1 onDblClick=\"removeSelectedOptions(this.form.list1)\">";
        $text .= $current;
        $text .= "</select></td></tr>";
        $text .= "<input type=hidden name=list2>";
        return $text;
    }

    function getNewsCatForm(){

        $hold = array();
        $result = mysql_query("select news_cat_id from user_news_view where user_id = '$_COOKIE[PXL]'");
        while(list($id) = mysql_fetch_row($result)){
            $hold[] = $id;
        }


        $result = mysql_query("select news_cat_title, news_cat_id from news_cat order by news_cat_title");
        while(list($title, $id) = mysql_fetch_row($result)){
            $title = stripslashes($title);
            if (in_array($id, $hold)){ $s = "selected"; }
            else { $s = ''; }
            $current .= "<option value=$id $s>$title</option>";
        }
        $text = "<tr><td class=mainbold-alt valign=top>" . $this->textArray['View News Categories'] . ": </td>";
        $text .= "<td class=main-alt>";
        $text .= "<select multiple size=8 name=list3[]>";
        $text .= $current;
        $text .= "</select></td></tr>";
        return $text;
    }
}

