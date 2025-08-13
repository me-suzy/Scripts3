<?php
#$Id: user.inc.php,v 1.14 2003/11/20 17:13:42 ryan Exp $

class userModule {

    function userModule($userinfo){
        $this->userinfo = $userinfo;
        $this->user_id = $_GET['user_id'];
        if ($this->user_id == ''){ $this->user_id = $_POST['user_id']; }
        $this->action = $_GET['action'];

        $this->core = new coreFunctions();
        $this->ssl = $this->core->dbCall("secure");
        if ($userinfo[5] != 1){
            $this->action = "modify";
            $this->user_id = $userinfo[0];
        }

        switch($this->action){
            case add:
                $this->addUserInfo();
                break;
            case delete:
                $this->deleteUserInfo();
                break;
            case modify:
                $this->modifyUserInfo();
                break;
            case detail:
                $this->userDetail();
                break;
            case search:
                $this->searchUsers();
                break;
            case ban:
                $this->banUsers();
                break;
            case email:
                $this->emailUsers();
                break;
            case activate:
                $this->activateUser();
                break;
            default:
                $this->listUsers();
        }
        $this->stroke();
    }

    function stroke(){
        new adminPage($this->body);
    }

    function activateUser(){
        mysql_query("update users set actkey = null where user_id = '$this->user_id'");
        $this->core->addLog("User Activated", $this->userinfo[1], $user_id);
        header("Location: user.php?action=modify&user_id=$this->user_id");
    }

    function createForm($user_id='', $username='', $first_name='', $last_name='', $email='', $ac='', $sc='', $rank='', $a=''){
        $rankForm = $this->createRankForm($rank);
        $text = "<form method=post action=user.php?action=$this->action onsubmit=\"return validateForm(this)\">";
        $text .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=500 align=center>";
        $text .= "<tr>";
        $text .= "<td class=title colspan=2><img src=images/users.gif border=0> Manage User</td>";
        $text .= "</tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        $text .= "<input type=hidden name=user_id value='$user_id'><input type=hidden name=confirm value=1>";
        if ($_GET[code] == 5){ $text .= "<tr><td class=attn colspan=2>Configuration Saved.  Now change your password.</td></tr>"; }
        $text .= "<tr><td class=mainbold align=right width=100>Username: </td><td class=main><input type=text name=username value='$username'></td></tr>";
        $text .= "<tr><td class=mainbold align=right width=100>First Name: </td><td class=main><input type=text name=first_name value='$first_name'></td></tr>";
        $text .= "<tr><td class=mainbold align=right width=100>Last Name: </td><td class=main><input type=text name=last_name value='$last_name'></td></tr>";
        $text .= "<tr><td class=mainbold align=right width=100>Email Address: </td><td class=main><input type=text name=email value='$email'></td></tr>";
        $text .= "<tr><td class=mainbold align=right width=100>Password: </td><td class=main><input type=password name=password></td></tr>";
        $text .= $rankForm;
        if ($this->userinfo[5] == 1){
            $text .= "<tr><td class=mainbold align=right width=100>Admin: </td><td class=main><input type=checkbox name=admin value=1 $ac> If checked, user has permission to change all user information.</td></tr>";
            $text .= "<tr><td class=mainbold align=right width=100>Suspend: </td><td class=main><input type=checkbox name=suspend value=1 $sc> If checked, user has been suspended.</td></tr>";
        }
        $text .= "<tr><td class=main align=center colspan=2>";
        if ($this->action == "add"){ $text .= "<input type=submit name=submit value='Enter'>"; }
        else if ($this->action == "modify") { $text .= "<input type=submit name=submit value='Modify User'>"; }
        if ($this->action == "modify" && $a != ''){ $text .= " <input type=button value='Activate User' onclick=goToURL('user.php?action=activate&user_id=$user_id')>"; }
        $text .= "<script Language=JavaScript>";
        $text .= "function validateForm(theForm)";
        $text .= "{";
        $text .= "    if (!validRequired(theForm.username,'Username'))";
        $text .= "        return false;";
        $text .= "    if (!validRequired(theForm.first_name,'First Name'))";
        $text .= "        return false;";
        $text .= "    if (!validRequired(theForm.last_name,'Last Name'))";
        $text .= "        return false;";
        if ($this->action == "add"){
            $text .= "    if (!validRequired(theForm.password,'Password'))";
            $text .= "        return false;";
        }
        $text .= "    if (!validEmail(theForm.email,'Email',true))";
        $text .= "        return false;";
        $text .= "    return true;";
        $text .= "}";
        $text .= "</script>";
        $text .= "</td></form></tr></table></td></tr></table>";
        $this->body = $text;
    }

    function createRankForm($default=''){
        $text = "<tr><td class=mainbold>Special User Rank</td><td class=main><select name=rank>";
        $text .= "<option value=0></option>";
        $result = mysql_query("select rank_id, rank_name from forums_ranks where rank_spec = '1' order by rank_name");

        while(list($rank_id, $rank_name) = mysql_fetch_row($result)){
            if ($rank_id == $default){ $s = "selected"; }
            else { $s = ''; }
            $text .= "<option value=$rank_id $s>$rank_name</option>";
        }
        $text .= "</select></td></tr>";
        return $text;
    }


    function addUserInfo(){

        if ($_POST['confirm'] == "1"){
            $date = time();
            $password = md5($_POST['password']);
            $sql = "insert into users (username, first_name, last_name, email, password, suspend, admin, rank, regdate) values ('" . $_POST['username'] . "','" . $_POST['first_name'] . "','" . $_POST['last_name'] . "','" . $_POST['email'] . "','$password', '" . $_POST['suspend'] . "', '" . $_POST['admin'] . "', '$_POST[rank]', '$date')";
            mysql_query($sql) or die(mysql_error());
            $user_id = mysql_insert_id();
            $this->core->addLog("User Created", $this->userinfo[1], $user_id);
            header("Location: user.php?action=detail&user_id=$user_id");
        }
        else {
            $this->createForm();
        }
    }


    function modifyUserInfo(){

        if ($_POST['confirm'] == "1"){
            if ($_POST['password'] != ''){ $password = md5($_POST['password']); mysql_query("update users set password = '$password' where user_id = '$this->user_id'"); }
            $sql = "update users set username='" . $_POST['username'] . "', first_name='" . $_POST['first_name'] . "', last_name='" . $_POST['last_name'] . "', email='" . $_POST['email'] . "', admin='" . $_POST['admin'] . "', suspend='" . $_POST['suspend'] . "', rank = '$_POST[rank]' where user_id='$this->user_id'";
            mysql_query($sql) or die(mysql_error());
            $this->core->addLog("User Modified", $this->userinfo[1], $this->user_id);
            if (isset($_COOKIE[FIRST])){ setcookie("FIRST", '', time() - 60, '', $_SERVER["HTTP_HOST"], $this->ssl); }
            if ($this->userinfo[5] == 1){ header("Location: user.php?action=detail&user_id=$this->user_id"); }
            else { $text .= "<script language=javascript>window.close();</script>"; }
        }
        else {
            list($username, $first_name, $last_name, $email, $admin, $suspend, $rank, $a) = mysql_fetch_row(mysql_query("select username, first_name, last_name, email, admin, suspend, rank, actkey from users where user_id = '$this->user_id'"));
            if ($admin == 1){ $ac = "checked"; }
            if ($suspend == 1){ $sc = "checked"; }
            $this->createForm($this->user_id, $username, $first_name, $last_name, $email, $ac, $sc, $rank, $a);
        }
    }

    function deleteUserInfo(){
        mysql_query("delete from users where user_id = '$this->user_id'");
        $this->core->addLog("User Deleted", $userinfo[1], $this->user_id);
        header("Location: user.php?action=list");
    }



    function userDetail(){

        $result = mysql_query("select user_id, username, first_name, last_name, email, admin, suspend from users where user_id = '$this->user_id'");
        list($user_id, $username, $first_name, $last_name, $email, $admin, $suspend) = mysql_fetch_row($result);
        if ($admin == 1){ $admin = "Yes"; }
        else { $admin = "No"; }
        if ($suspend == 1){ $suspend = "<font color=red>Yes</font>"; }
        else { $suspend = "No"; }

        $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=500 align=center>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        $text .= "<tr>";
        $text .= "<td class=title><img src=images/users.gif border=0> User Detail</td>";
        $text .= "<td class=title align=right><a href=user.php?action=modify&user_id=$user_id><img src=images/_workflow.gif alt='Modify User' border=0></a> <a href=javascript:confirmDelete('user.php?action=delete&user_id=$user_id')><img src=images/bdelete.gif alt='Delete User' border=0></a></td>";
        $text .= "</tr></table></td></tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        $text .= "<tr><td class=mainbold width=100 align=right>User ID: </td><td class=main>$user_id</td></tr>";
        $text .= "<tr><td class=mainbold width=100 align=right>Username: </td><td class=main>$username</td></tr>";
        $text .= "<tr><td class=mainbold width=100 align=right>Name: </td><td class=main>$first_name $last_name</td></tr>";
        $text .= "<tr><td class=mainbold width=100 align=right>Email: </td><td class=main>$email</td></tr>";
        $text .= "<tr><td class=mainbold width=100 align=right>Admin: </td><td class=main>$admin</td></tr>";
        $text .= "<tr><td class=mainbold width=100 align=right>Suspend: </td><td class=main>$suspend</td></tr>";
        $text .= "</table></td></tr></table>";
        $this->body = $text;
    }

    function listUsers(){

        if ($_GET['limit'] == ''){ $limit = 0; }
        else { $limit = $_GET['limit']; }

        $setnum = 50;

        $result = mysql_query("select user_id, username, first_name, last_name, email from users order by user_id asc limit $limit, $setnum");
        list($number) = mysql_fetch_row(mysql_query("select count(user_id) from users"));
        $more = $this->core->pageTab($limit, $number, "user.php?action=users", $setnum);
        $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=100%>";
        $text .= "<tr>";
        $text .= "<td class=title colspan=2><img src=images/users.gif border=0> List Users</td>";
        $text .= "</tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";

        $text .= "<tr><td class=main colspan=5>$more</td></tr>";
        $text .= "<tr><td class=mainbold>User ID</td><td class=mainbold>Username</td><td class=mainbold>Name</td><td class=mainbold>Email</td><td class=mainbold>Actions</td></tr>";
        while(list($user_id, $username, $first_name, $last_name, $email)=mysql_fetch_row($result)){
            $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC';\" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
            $text .= "<td class=hover>$user_id</td><td class=hover>$username</td>";
            $text .= "<td class=hover>$first_name $last_name</td><td class=hover>$email</td>";
            $text .= "<td class=hover><a href=user.php?action=detail&user_id=$user_id><img src=images/_page.gif border=0 alt='View'></a> <a href=user.php?action=modify&user_id=$user_id><img src=images/bedit.gif alt='Modify' border=0></a> <a href=javascript:confirmDelete('user.php?action=delete&user_id=$user_id')><img src=images/bdelete.gif alt='Delete' border=0></a></td>";
            $text .= "</tr>";
        }
        $text .= "<tr><td class=main colspan=5>$more</td></tr>";
        $text .= "</table></td></tr></table>";
        $this->core->addLog("List Users", $this->userinfo[1], '');
        $this->body = $text;
    }

    function searchUsers($skip=''){
        $key = $_POST['keyword'];
        if ($_POST['confirm'] == 1 && $skip != 1){
            $text = $this->searchUsers(1);
            $text .= "<br><br>";

            $text .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=100%>";
            $text .= "<tr>";
            $text .= "<td class=title colspan=2><img src=images/users.gif border=0> List Users</td>";
            $text .= "</tr>";
            $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
            $version = mysql_get_server_info();
            $version = substr($version, 0, 1);
            if ($version == "4"){ $result = mysql_query("select user_id, username, first_name, last_name, email from users where match(username, first_name, last_name, email) against ('$key' in boolean mode) order by user_id asc"); }
            else {
                 $key1 = "%" . $key . "%";
                 $result = mysql_query("select user_id, username, first_name, last_name, email from users where match(username, first_name, last_name, email) against ('$key1') order by user_id asc");
            }
            $text .= "<tr><td class=mainbold>User ID</td><td class=mainbold>Username</td><td class=mainbold>Name</td><td class=mainbold>Email</td><td class=mainbold>Actions</td></tr>";
            while(list($user_id, $username, $first_name, $last_name, $email)=mysql_fetch_row($result)){
                $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC';\" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
                $text .= "<td class=hover>$user_id</td><td class=hover>$username</td>";
                $text .= "<td class=hover>$first_name $last_name</td><td class=hover>$email</td>";
                $text .= "<td class=hover><a href=user.php?action=detail&user_id=$user_id><img src=images/_page.gif border=0 alt='View'></a> <a href=user.php?action=modify&user_id=$user_id><img src=images/bedit.gif alt='Modify' border=0></a> <a href=javascript:confirmDelete('user.php?action=delete&user_id=$user_id')><img src=images/bdelete.gif alt='Delete' border=0></a></td>";
                $text .= "</tr>";
            }
            $text .= "</table></td></tr></table>";
            $this->core->addLog("User Search", $this->userinfo[1], '');
            $this->body = $text;


        }
        else {

            $text = "<form method=post action=user.php?action=search onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=250 align=center>";
            $text .= "<tr>";
            $text .= "<td class=title colspan=2><img src=images/users.gif border=0> Search Users</td>";
            $text .= "</tr>";
            $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
            $text .= "<tr><td class=mainbold>Name/Email/Username: </td><td class=main><input type=text value='$key' size=25 name=keyword></td></tr>";
            $text .= "<tr><td class=main align=center colspan=2><input type=submit value='Search'></td></tr>";
            $text .= "</table></td></tr></table>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.keyword,'Searh Parameter'))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            if ($skip == 1){ return $text; }
            else { $this->body = $text; }

        }
    }

    function banUsers(){
        if ($_POST['confirm'] == 1){

            $list3 = explode(",", $_POST['list3']);
            $hold = array();

            foreach($list3 as $l){
                $hold[] = $l;
                list($count) = mysql_fetch_row(mysql_query("select count(user_id) from users_ban where user_id = '$l'"));
                if ($count == 0){
                    mysql_query("insert into users_ban (user_id) values ('$l')");
                }
            }
            $result = mysql_query("select user_id from users_ban");
            while(list($user_id) = mysql_fetch_row($result)){
                if (!in_array($user_id, $hold)){ mysql_query("delete from users_ban where user_id = '$user_id'"); }
            }

            $list6 = explode(",", $_POST['list6']);
            $hold = array();

            foreach($list6 as $l){
                $hold[] = $l;
                list($count) = mysql_fetch_row(mysql_query("select count(ip_addr) from users_ban where ip_addr = '$l'"));
                if ($count == 0){
                    mysql_query("insert into users_ban (ip_addr) values ('$l')");
                }
            }
            $result = mysql_query("select ip_addr from users_ban");
            while(list($user_id) = mysql_fetch_row($result)){
                if (!in_array($user_id, $hold)){ mysql_query("delete from users_ban where ip_addr = '$user_id'"); }
            }

            $list7 = explode(",", $_POST['list7']);
            $hold = array();

            $this->core->addLog("User Ban List Modified", $this->userinfo[1], '');
            header("HTTP/1.0 204 No Content");
        }
        else {
            $text = "<form method=post action=user.php?action=ban onsubmit=\"banUserForm()\" name=f>";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=500 align=center>";
            $text .= "<tr>";
            $text .= "<td class=title colspan=2><img src=images/users.gif border=0> Ban Users/Emails/IP's</td>";
            $text .= "</tr>";
            $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
            $text .= "<tr><td class=main colspan=2><div class=attn id=actionResult style=\"display:none\">Ban List(s) Saved</div></td></tr>";
            $text .= "<tr><td class=title colspan=2>Ban Username</td></tr>";
            $hold = array();
            $result = mysql_query("select u.user_id, u.username from users u, users_ban b where u.user_id = b.user_id order by u.username");
            while(list($user_id, $username) = mysql_fetch_row($result)){
                $current .= "<option value=$user_id>$username</option>";
                $hold[] = $user_id;
            }

            $result = mysql_query("select user_id, username from users where suspend != '1' and admin != '1' order by username");
            while(list($user_id, $username) = mysql_fetch_row($result)){
                if (!in_array($user_id, $hold)){ $poss .= "<option value=$user_id>$username</option>"; }
            }

            $result = mysql_query("select ip_addr from users_ban where ip_addr != ''");
            while(list($ip_addr) = mysql_fetch_row($result)){
                $ip .= "<option value=$ip_addr>$ip_addr</option>";
            }


            $text .= "<tr><td class=main colspan=2>Search: <input type=text name=searchForm size=25 onKeyUp=\"autoComplete(this,this.form.list1,'text',true)\"> $searchInfo</td></tr>";
            $text .= "<tr><td class=main width=50%>";
            $text .= "<select multiple size=8 name=list1 onDblClick=\"moveSelectedOptions(this.form.list1,this.form.list2,false)\">";
            $text .= $poss;
            $text .= "</select></td>";
            $text .= "<td class=main width=50%>";
            $text .= "<select multiple size=8 name=list2 onDblClick=\"moveSelectedOptions(this.form.list2,this.form.list1,false)\">";
            $text .= $current;
            $text .= "</select></td></tr>";
            $text .= "<input type=hidden name=list3><input type=hidden name=list6>";
            $text .= "<script src=templates/list2.js language=javascript></script>";
            $text .= "<tr><td class=main colspan=2>&nbsp;</td></tr>";
            $text .= "<tr><td class=title colspan=2>Ban IP Addresses</td></tr>";
            $text .= "<tr><td class=mainbold valign=top>IP Address: <br>";
            $text .= "<!--<font size=1>use * for wildcards</font><br>--><input type=text name=ips size=40></td>";
            $text .= "<td class=main><select size=8 name=list4 onDblClick=\"removeIP(this.form.list4)\">$ip</select></td></tr>";
            $text .= "<tr><td class=main align=center colspan=2><input type=submit value='Enter'></td></tr>";
            $text .= "</table></td></tr></table>";
            $this->body = $text;
        }
    }

    function emailUsers(){
        if ($_POST[confirm] == 1){
            $this->core->createProgressPage();
            $siteName = $this->core->dbCall("siteName");
            $headers = "From: $this->userinfo[1] via $siteName Admin Mailer <$this->userinfo[4]>\r\n";
            $headers .= "X-Sender: <$this->userinfo[4]>\r\n";
            $headers .= "X-Mailer: PHP\r\n";
            $headers .= "X-Priority: 3\r\n";
            $headers .= "Reply-To: $this->userinfo[4]\r\n";

            if ($_POST['group'] == "admin"){ $result = mysql_query("select email from users where admin = '1' and suspend != '1'"); }
            else if ($_POST['group'] == "mod"){ $result = mysql_query("select distinct(u.email) from users u, forums_mod m where m.user_id = u.user_id"); }
            else { $result = mysql_query("select email from users"); }

            while(list($to) = mysql_fetch_row($result)){
                mail($to, $_POST[subject], $_POST[body], $headers);
            }
            $this->core->addLog("Email Users", $this->userinfo[1], '');
            print "<script language=javascript>window.location = 'user.php?action=email&code=1';</script>";
        }
        else {
                $text = "<table border=0 cellpadding=0 cellspacing=1 width=95% class=outline align=center >";
                $text .= "<tr><td class=title align=center>Email Users</td></tr>";
                $text .= "<tr><td width=100% class=main><table border=0 cellpadding=2 cellspacing=1 align=center width=100%>";
                if ($_GET[code] == 1){ $text .= "<tr><td class=attn colspan=2>Email Sent</td></tr>"; }
                $text .= "<form method=post action=user.php?action=email>";
                $text .= "<input type=hidden name=confirm value=1>";
                $text .= "<tr><td class=mainbold>Subject: </td><td class=main><input type=text name=subject size=40></td></tr>";
                $text .= "<tr><td class=mainbold>To: </td><td class=main><select name=group><option value=admin>Administrators</option><option name=mod>Forum Moderators</option><option value=all>All Users</option></select></td></tr>";
                $text .= "<tr><td class=mainbold valign=top>Body: </td><td class=main><textarea name=body cols=43 rows=15></textarea></td></tr>";
                $text .= "<tr><td class=main align=center colspan=2><input type=submit value='Send Email'></td></tr>";
                $text .= "</table></td></tr></table>";


        }
        $this->body = $text;
    }
}
?>
