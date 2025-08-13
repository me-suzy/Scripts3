<?php
#$Id: forums.inc.php,v 1.13 2003/10/20 14:59:47 ryan Exp $

class forumModule {

    function forumModule($userinfo){
        $this->userinfo = $userinfo;
        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();
        $this->forms = new forumForm();
        $this->action = $_GET['action'];

        switch($this->action){

            case addForum:
                $this->createForum();
                break;

            case modifyForum:
                $this->modifyForum();
                break;

            case addCat:
                $this->createCat();
                break;

            case modifyCat:
                $this->modifyCat();
                break;

            case deleteForum:
                $this->deleteForum();
                break;

            case deleteCat:
                $this->deleteCat();
                break;

            case config:
                $this->forumConfig();
                break;

            case order:
                $this->manageForumOrder();
                break;

            case prune:
                $this->pruneForum();
                break;

            case words:
                $words = new forumWords($this->userinfo);
                $this->body = $words->body;
                break;

            case avatars:
                $avatars = new forumAvatars($this->userinfo);
                $this->body = $avatars->body;
                break;

            case smile:
                $smiles = new forumSmiles($this->userinfo);
                $this->body = $smiles->body;
                break;

            case ranks:
                $ranks = new forumRanks($this->userinfo);
                $this->body = $ranks->body;
                break;

            case xcode:
                $xcode = new forumXcode($this->userinfo);
                $this->body = $xcode->body;
                break;

            case flag:
                $flag = new forumFlags($this->userinfo);
                $this->body = $flag->body;
                break;

            default:
                $this->listForums();
                $this->viewForumStats();
                break;
        }
        $this->stroke();

    }

    function stroke(){
        new adminPage($this->body);
    }

    function createForum(){

        if ($_POST['confirm'] == 1){
            $user_id = $_COOKIE[PXL];
            $date = time();
            $name = $this->textFun->convertText($_POST['name'], 1);
            $desc = $this->textFun->convertText($_POST['fdescription'], 1);
            list($ord) = mysql_fetch_row(mysql_query("select ord from forums_forums order by ord desc limit 0,1"));
            $ord++;
            mysql_query("insert into forums_forums values ('', '" . $_POST['cat_id'] . "', '$name', '$desc', '" . $_POST['level'] . "', '0', '0', '0', '$ord', '0')");
            $forum_id = mysql_insert_id();
            mysql_query("insert into forums_topics values ('', '$forum_id', '', '', '0', '0', '0', '$date', '0', '', '0')");
            $topic_id = mysql_insert_id();
            mysql_query("insert into forums_posts values ('', '$topic_id', '$forum_id', '$user_id', 'First Post', 'This is the first post in a new forum', '$date', '1.1.1.1')");
            $post_id = mysql_insert_id();
            mysql_query("update forums_topics set post_id = '$post_id', posts = '1', last_post_id = '$post_id' where topic_id = '$topic_id'");
            mysql_query("update forums_forums set posts = '1', topics = '1', post_id = '$post_id' where forum_id = '$forum_id'");
            mysql_query("insert into forums_notify values ('', '$user_id', '', '$forum_id')");
            $list3 = explode(",", $_POST['list3']);
            foreach($list3 as $l){
                mysql_query("insert into forums_mod values ('', '$l', '$forum_id')");
            }
            $this->core->addLog("Forum Created", $this->userinfo[1], $forum_id);
            header("Location: forums.php");
        }
        else {
            $header = $this->forms->createFormHeader("Create Forum");
            $footer = $this->forms->createFormFooter("Enter");
            $cat = $this->forms->createDropDown("cat", "cat_id", "Category");
            $level = $this->forms->createDropDown("level", "level", "Access Level");

            $body = "<form method=post action=forums.php?action=addForum onsubmit=\"return validateForm(this)\" name=f>";
            $body .= "<input type=hidden name=confirm value=1>";
            $body .= "<tr><td class=mainbold>Forum Name: </td><td class=main><input type=text value='' name=name size=25></td></tr>";
            $body .= "<tr><td class=mainbold>Forum Description: </td><td class=main><input type=text value='' name=fdescription size=40></td></tr>";
            $body .= $cat;
            $body .= $level;
            $body .= $this->getForumModForm();
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    selectAllOptions(document.f.list2);";
            $text .= "    document.f.list3.value = getSelectedValues(document.f.list2);";
            $text .= "    if (!validRequired(theForm.name,\"Forum Name\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.fdescription,\"Forum Description\"))";
            $text .= "        return false;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $body . $text . $footer;
        }
    }

    function modifyForum(){

        if ($_POST['confirm'] == 1){
            $forum_id = $_POST['forum_id'];
            $list3 = explode(",", $_POST['list3']);
            $hold = array();
            $result = mysql_query("select user_id from forums_mod where forum_id = '$forum_id'");
            while(list($id) = mysql_fetch_row($result)){
                if (!in_array($id, $list3)){ mysql_query("delete from forums_mod where user_id = '$id' and forum_id = '$forum_id'"); }
                else { $hold[] = $id; }
            }
            foreach($list3 as $l){
                if (!in_array($l, $hold)){ mysql_query("insert into forums_mod values ('', '$l', '$forum_id')"); }
            }

            $name = $this->textFun->convertText($_POST['name'], 1);
            $desc = $this->textFun->convertText($_POST['fdescription'], 1);

            mysql_query("update forums_forums set cat_id = '" . $_POST['cat_id'] . "', name = '" . $_POST['name'] . "', fdescription = '" . $_POST['fdescription'] . "', level = '" . $_POST['level'] . "' where forum_id = '$forum_id'") or die(mysql_error());
            $this->core->addLog("Forum Modified", $this->userinfo[1], $forum_id);
            header("Location: forums.php");
        }
        else {
            $forum_id = $_GET['forum_id'];
            $result = mysql_query("select * from forums_forums where forum_id = '$forum_id'");
            $row = mysql_fetch_array($result);
            $header = $this->forms->createFormHeader("Modify Forum");
            $footer = $this->forms->createFormFooter("Enter", 1, "forums.php?action=deleteForum&forum_id=$forum_id");
            $cat = $this->forms->createDropDown("cat", "cat_id", "Category", $row['cat_id']);
            $level = $this->forms->createDropDown("level", "level", "Access Level", $row['level']);

            $name = $this->textFun->convertText($row['name'], 0);
            $desc = $this->textFun->convertText($row['fdescription'], 0);

            $body = "<form method=post action=forums.php?action=modifyForum onsubmit=\"return validateForm(this)\" name=f>";
            $body .= "<input type=hidden name=confirm value=1><input type=hidden name=forum_id value=$forum_id>";
            $body .= "<tr><td class=mainbold>Forum Name: </td><td class=main><input type=text value='$name' name=name size=25></td></tr>";
            $body .= "<tr><td class=mainbold>Forum Description: </td><td class=main><input type=text value='$desc' name=fdescription size=40></td></tr>";
            $body .= $cat;
            $body .= $level;
            $body .= $this->getForumModForm($forum_id);
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    selectAllOptions(document.f.list2);";
            $text .= "    document.f.list3.value = getSelectedValues(document.f.list2);";
            $text .= "    if (!validRequired(theForm.name,\"Forum Name\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.fdescription,\"Forum Description\"))";
            $text .= "        return false;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $body . $text . $footer;
        }
    }

    function getForumModForm($forum_id=''){

        $hold = array();
        if (isset($forum_id)){
            $result = mysql_query("select u.user_id, u.username from users u, forums_mod m where m.user_id = u.user_id and m.forum_id = '$forum_id' order by u.username");
            while(list($user_id, $username) = mysql_fetch_row($result)){
                $current .= "<option value=$user_id>$username</option>";
                $hold[] = $user_id;
            }
        }
        $result = mysql_query("select user_id, username from users where suspend != '1' and admin != '1' order by username");
        while(list($user_id, $username) = mysql_fetch_row($result)){
            if (!in_array($user_id, $hold)){ $poss .= "<option value=$user_id>$username</option>"; }
        }

        $text = "<tr><td class=bg colspan=2>Forum Moderation Selection</td></tr>";
        $text .= "<tr><td class=main colspan=2>Search: <input type=text name=searchForm size=25 onKeyUp=\"autoComplete(this,this.form.list1,'text',true)\"> $searchInfo</td></tr>";
        $text .= "<tr><td class=main width=50%>";
        $text .= "<select multiple size=15 name=list1 onDblClick=\"moveSelectedOptions(this.form.list1,this.form.list2,false)\">";
        $text .= $poss;
        $text .= "</select></td>";
        $text .= "<td class=main width=50%>";
        $text .= "<select multiple size=15 name=list2 onDblClick=\"moveSelectedOptions(this.form.list2,this.form.list1,false)\">";
        $text .= $current;
        $text .= "</select></td></tr>";
        $text .= "<input type=hidden name=list3>";
        $text .= "<script src=templates/list2.js language=javascript></script>";
        return $text;
    }

    function deleteForum(){
        $forum_id = $_GET['forum_id'];
        mysql_query("delete from forums_forums where forum_id = '$forum_id'");
        mysql_query("delete from forums_posts where forum_id = '$forum_id'");
        mysql_query("delete from forums_topics where forum_id = '$forum_id'");
        mysql_query("delete from forums_mod where forum_id = '$forum_id'");
        $i=1;
        $result = mysql_query("select forum_id from forums_forums order by ord asc");
        while(list($_id) = mysql_fetch_row($result)){
            mysql_query("update forums_forums set ord = '$i' where forum_id = '$_id'");
            $i++;
        }
        $this->core->addLog("Forum Deleted", $this->userinfo[1], $forum_id);
        $this->core->syncForums();
        header("Location: forums.php");
    }

    function createCat(){

        if ($_POST['confirm'] == 1){
            $name = $this->textFun->convertText($_POST['name'], 1);
            list($ord) = mysql_fetch_row(mysql_query("select ord from forums_cat order by ord desc limit 0,1"));
            $ord++;
            mysql_query("insert into forums_cat values ('', '$name', '$ord', '0')");
            $cat_id = mysql_insert_id();
            $this->core->addLog("Forum Category Created", $this->userinfo[1], $cat_id);
            header("Location: forums.php");
        }
        else {
            $header = $this->forms->createFormHeader("Create Forum Category");
            $footer = $this->forms->createFormFooter("Enter");
            $text = "<form method=post action=forums.php?action=addCat onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>Category Name: </td><td class=main><input type=text value='' name=name size=25></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.name,\"Category Name\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }
    function modifyCat(){

        if ($_POST['confirm'] == 1){
            $name = $this->textFun->convertText($_POST['name'], 1);
            mysql_query("update forums_cat set name = '$name' where cat_id = '$cat_id'");
            $this->core->addLog("Forum Category Modified", $this->userinfo[1], $cat_id);
            header("Location: forums.php");
        }
        else {
            $cat_id = $_GET['cat_id'];
            list($name) = mysql_fetch_row(mysql_query("select name from forums_cat where cat_id = '$cat_id'"));
            $name = $this->textFun->convertText($name, 0);
            $header = $this->forms->createFormHeader("Modify Forum Category");
            $footer = $this->forms->createFormFooter("Enter", 1, "forums.php?action=deleteCat&cat_id=$cat_id");
            $text = "<form method=post action=forums.php?action=modifyCat onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1><input type=hidden name=cat_id value=$cat_id>";
            $text .= "<tr><td class=mainbold>Category Name: </td><td class=main><input type=text value='$name' name=name size=25></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.name,\"Category Name\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }
    function deleteCat(){

        $cat_id = $_GET['cat_id'];
        list($count) = mysql_fetch_row(mysql_query("select count(forum_id) from forums_forums where cat_id = '$cat_id'"));

        if ($count != 0){
            $this->body = "<div class=attn align=center>Category still has Forums.  Delete All Forums first.</div>";
        }
        else {
            mysql_query("delete from forums_cat where cat_id = '$cat_id'");

            $i=1;
            $result = mysql_query("select cat_id from forums_cat order by ord asc");
            while(list($_id) = mysql_fetch_row($result)){
                mysql_query("update forums_forums set ord = '$i' where cat_id = '$_id'");
                $i++;
            }
            $this->core->addLog("Forum Category Deleted", $this->userinfo[1], $cat_id);
            header("Location: forums.php");
        }
    }

    function listForums(){
        $text = $this->forms->createFormHeader("Forums");

        $result = mysql_query("select cat_id, name from forums_cat order by ord asc");
        while(list($cat_id, $name) = mysql_fetch_row($result)){
            $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC'; style.cursor='pointer';\" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff onclick=goToURL('forums.php?action=modifyCat&cat_id=$cat_id')>";
            $text .= "<td class=hover><b>$name</b></td></tr>";

            $result1 = mysql_query("select forum_id, name, posts, topics from forums_forums where cat_id = '$cat_id' order by ord asc");
            while(list($forum_id, $name, $posts, $topics) = mysql_fetch_row($result1)){
                $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC'; style.cursor='pointer';\" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff onclick=goToURL('forums.php?action=modifyForum&forum_id=$forum_id')>";
                $text .= "<td class=hover>&nbsp;&nbsp;&nbsp;&nbsp;<b>$name</b>($posts Posts in $topics Topics)</td></tr>";
            }
        }
        $text .= "</table></td></tr></table>";
        $this->core->addLog("List Forums", $this->userinfo[1], '');
        $this->body = $text;
    }

    function forumConfig(){

        if ($_POST['confirm'] == 1){
            $flip = array_keys($_POST);
            foreach($flip as $f){
                if ($f != "confirm" && $f != "forums"){
                    if ($f == "agreement"){ $_POST[$f] = str_replace("\r\n", "<br>", $_POST[$f]); }
                    $sql = "update forums_config set $f = '$_POST[$f]'";
                    mysql_query($sql) or die(mysql_error());
                }
                mysql_query("update config set forums = '$_POST[forums]'");
            }
            $this->core->addLog("Forum Config Updated", $this->userinfo[1], '');
            header("HTTP/1.0 204 No Content");
        }
        else {

            $row = mysql_fetch_array(mysql_query("select * from forums_config limit 0,1"));
            list($forums) = mysql_fetch_row(mysql_query("select forums from config where config_id = '1'"));
            $header = $this->forms->createFormHeader("Edit Forum Configuration");
            $footer = $this->forms->createFormFooter("Enter");

            $agreement = str_replace("<br>", "\r\n", $row[agreement]);

            $text = "<form method=post action=forums.php?action=config onSubmit=\"displaySubs('actionResult');\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=main colspan=2><div class=attn id=actionResult style=\"display:none\">Configuration Saved</div></td></tr>";
            $text .= "<tr><td class=title align=center colspan=2>Activate Forums</td></tr>";
            $text .= $this->forms->createRadio("forums", "Activate Forums", $forums);
            $text .= "<tr><td class=title align=center colspan=2>General Configuration Settings</td></tr>";
            $text .= $this->forms->createDropDown("number", "topics_per_page", "Topics Per Page", $row['topics_per_page']);
            $text .= $this->forms->createDropDown("number", "posts_per_page", "Posts Per Page", $row['posts_per_page']);
            $text .= $this->forms->createDropDown("number", "popular_threshold", "Popular Topic Threshold", $row['popular_threshold']);
            $text .= $this->forms->createDropDown("number", "max_poll", "Max Number of Poll Options", $row['max_poll']);
            $text .= $this->forms->createRadio("auto_prune", "Auto Prune Posts", $row['auto_prune']);
            $text .= "<tr><td class=mainbold>Topic Prune Days: </td><td class=main><input type=text name=prune_number value='$row[prune_number]' size=10></td></tr>";
            $text .= "<tr><td class=title align=center colspan=2>Display Settings</td></tr>";
            $text .= $this->forms->createRadio("allow_html", "Allow HTML in Posts", $row['allow_html']);
            $text .= $this->forms->createRadio("allow_xcode", "Allow XCode", $row['allow_xcode']);
            $text .= $this->forms->createRadio("allow_smiles", "Allow Smilies", $row['allow_smiles']);
            $text .= $this->forms->createRadio("polls", "Allow Polls", $row['polls']);
            $text .= $this->forms->createRadio("censor_words", "Word Censor", $row['censor_words']);
            $text .= $this->forms->createRadio("image_tags", "Allow Images", $row['image_tags']);
            $text .= $this->forms->createRadio("edit_post", "Users can Edit Posts", $row['edit_post']);
            $text .= $this->forms->createRadio("allow_pm", "Users can Private Message", $row['allow_pm']);
            $text .= "<tr><td class=mainbold>PM Inbox Size: </td><td class=main><input type=text name=pm_inbox_size value='$row[pm_inbox_size]' size=10></td></tr>";


            $text .= "<tr><td class=title align=center colspan=2>User Settings</td></tr>";
            $text .= $this->forms->createRadio("allow_avatars", "Allow Avatars", $row['allow_avatars']);
            $text .= $this->forms->createRadio("remote_avatars", "Allow Remote Avatars", $row['remote_avatars']);
            $text .= $this->forms->createRadio("avatar_upload", "Allow Uploaded Avatars", $row['avatar_upload']);
            $text .= $this->forms->createRadio("allow_flags", "Allow Country Flags", $row['allow_flags']);
            $text .= $this->forms->createRadio("allow_imood", "Allow Imood's", $row['allow_imood']);
            $text .= $this->forms->createRadio("allow_sig", "Allow Signatures", $row['allow_sig']);
            $text .= "<tr><td class=mainbold>Max Signature Length: </td><td class=main><input type=text name=max_sig_length value='$row[max_sig_length]' size=10></td></tr>";
            $text .= $this->forms->createRadio("allow_ranks", "Users Set Own Ranks", $row['allow_ranks']);
            $text .= $this->forms->createRadio("account_activate", "Email Account Activation", $row['account_activate']);
            $text .= "<tr><td class=title align=center colspan=2>Poll Settings</td></tr>";
            $text .= $this->forms->createRadio("unlimited_vote", "Unlimited Voting", $row['unlimited_vote']);
            $text .= $this->forms->createRadio("anon_vote", "Anon Voting", $row['anon_vote']);

            $text .= "<tr><td class=title align=center colspan=2>User Agreement</td></tr>";
            $text .= "<tr><td class=mainbold valign=top>User Agreement: </td><td class=main><textarea name=agreement cols=60 rows=15 wrap=virtual>$agreement</textarea></td></tr>";
            $this->body = $header . $text . $footer;
        }
    }
    function manageForumOrder(){

        if ($_POST['confirm'] == 1){
            $list = explode(",", $_POST['list1']);
            $x=1;
            foreach($list as $l){
                if ($_POST[save] == 1){
                    mysql_query("update forums_cat set ord = '$x' where cat_id = '$l'") or die(mysql_error());
                }
                else {
                    mysql_query("update forums_forums set ord = '$x' where forum_id = '$l'");
                }
                $x++;
            }
            $this->core->addLog("Re-Order Forums", $this->userinfo[1], '');
            header("HTTP/1.0 204 No Content");
        }
        else {
            $header = $this->forms->createFormHeader("Manage Forum Order");
            $cat = "<form method=post action=forums.php?action=order name=f>";
            $cat .= "<input type=hidden value=1 name=confirm><input type=hidden name=save>";
            $cat .= "<script language=javascript src=templates/list.js></script>";
            $cat .= "<script language=javascript>";
            $cat .= "var listB = new DynamicOptionList(\"forum_id\",\"cat_id\");";
            $result = mysql_query("select cat_id, name from forums_cat order by ord");
            while(list($cat_id, $cat_name) = mysql_fetch_row($result)){
                $result55 = mysql_query("select forum_id, name from forums_forums where cat_id = '$cat_id' order by ord");
                $count = mysql_num_rows($result55);
                if ($count != 0){
                    $js .= "listB.addOptions(\"$cat_id\"";
                    while(list($forum_id, $forum_name) = mysql_fetch_row($result55)){
                        $js .= ",\"$forum_name\", \"$forum_id\"";
                    }
                    $js .= ");";
                }
                $catList .= "<option value=$cat_id>$cat_name</option>";
            }
            $cat .= $js;
            $cat .= "</script>";
            $cat .= "<tr>";
            $cat .= "<td class=main align=center width=50%><select size=25 multiple name=cat_id onChange=\"listB.populate();\" onDblClick=\"forumEdit(1)\">$catList</select>";
            $cat .= "<br><img src=images/up.gif alt='Move Up' onClick=\"moveOptionUp(document.forms[0].cat_id)\">&nbsp;";
            $cat .= "<img src=images/down.gif alt='Move Down' onClick=\"moveOptionDown(document.forms[0].cat_id)\"><br>";
            $cat .= "<input type=button onClick=submitForumForm1() name=save1 value='Save This Form'></td>";
            $cat .= "<td class=main align=center width=50%><select size=25 multiple name=forum_id onDblClick=\"forumEdit(2)\"><script language=JavaScript>listB.printOptions();</script></select>";
            $cat .= "<br><img src=images/up.gif alt='Move Up' onClick=\"moveOptionUp(document.f.forum_id)\">&nbsp;";
            $cat .= "<img src=images/down.gif alt='Move Down' onClick=\"moveOptionDown(document.f.forum_id)\"><br>";
            $cat .= "<input type=button onClick=submitForumForm2() name=save2 value='Save This Form'></td>";
            $cat .= "<script language=javascript>listB.init(document.forms[0]);</script>";
            $cat .= "<input type=hidden name=list1>";
            $cat .= "</form></table></td></tr></table>";
            $this->core->addLog("View Menu", $this->userinfo[1], '');
            $this->body = $header . $cat;
        }
    }

    function pruneForum(){
        if ($_POST['confirm'] == 1){
            $days = $_POST['days'] * 86400;
            $result = mysql_query("select topic_id from forums_topics where forum_poll_id = '0' and sticky = '0'");
            while(list($topic_id) = mysql_fetch_row($result)){
                list($count, $date) = mysql_fetch_row(mysql_query("select count(post_id), date from forums_posts where topic_id = '$topic_id'"));
                $check = $date + $days;
                if ($count == 1 && $check < time()){
                    mysql_query("delete from forums_topics where topic_id = '$topic_id'");
                    mysql_query("delete from forums_posts where topic_id = '$topic_id'");
                    $this->core->addLog("Topic Pruned", $this->userinfo[1], $topic_id);
                }
            }
            $this->core->syncForums();
            header("Location: forums.php");
        }
        else {
            $header = $this->forms->createFormHeader("Prune Forums");
            $footer = $this->forms->createFormFooter("Enter");
            $text .= "<form method=post action=forums.php?action=prune onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=main colspan=2>Delete topics that have not been responded to in a number of days.<br>";
            $text .= "This will not delete sticky topics, or polls.</td></tr>";
            $text .= "<tr><td class=mainbold>Number of Days: </td><td class=main><input type=text name=days size=5></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.days,\"Number of Days\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $text = $header . $text . $footer;
            $this->body = $text;

        }
    }

    function viewForumStats(){
        $header = $this->forms->createFormHeader("Forum Stats");
        list($cat) = mysql_fetch_row(mysql_query("select count(cat_id) from forums_cat"));
        list($forums) = mysql_fetch_row(mysql_query("select count(forum_id) from forums_forums"));
        list($topics) = mysql_fetch_row(mysql_query("select count(topic_id) from forums_topics"));
        list($posts) = mysql_fetch_row(mysql_query("select count(post_id) from forums_posts"));
        list($users) = mysql_fetch_row(mysql_query("select count(user_id) from users"));
        $text = "<tr><td class=mainbold>Forum Categories: </td><td class=main>$cat</td></tr>";
        $text .= "<tr><td class=mainbold>Forum Forums: </td><td class=main>$forums</td></tr>";
        $text .= "<tr><td class=mainbold>Forum Topics: </td><td class=main>$topics</td></tr>";
        $text .= "<tr><td class=mainbold>Forum Posts: </td><td class=main>$posts</td></tr>";
        $text .= "<tr><td class=mainbold>Forum Users: </td><td class=main>$users</td></tr>";
        $text .= "</table></td></tr></table>";
        $this->body .= "<br>" . $header . $text;
    }
}

class forumWords {

    function forumWords($userinfo){
        $this->userinfo = $userinfo;

        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();
        $this->forms = new forumForm();

        switch($_GET['subaction']){

            case add:
                $this->addWord();
                break;
            case delete:
                $this->deleteWord();
                break;
            case modify:
                $this->modifyWord();
                break;
            default:
                $this->listWords();
            }
    }

    function addWord(){

        if ($_POST['confirm'] == 1){
            $word = $this->textFun->convertText($_POST['word'], 1, 2);
            $replace = $this->textFun->convertText($_POST['replacement'], 1, 2);
            mysql_query("insert into forums_words values ('', '$word', '$replace')");
            $word_id = mysql_insert_id();
            $this->core->addLog("Forum Word Added", $this->userinfo[1], $word_id);
            header("Location: forums.php?action=words");
        }
        else {
            $header = $this->forms->createFormHeader("Add Word to Censor List");
            $footer = $this->forms->createFormFooter("Add Word");

            $text = "<form method=post action=forums.php?action=words&subaction=add onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>Word to Replace: </td><td class=main><input type=text value='' name=word size=25></td></tr>";
            $text .= "<tr><td class=mainbold>Replacement: </td><td class=main><input type=text value='' name=replacement size=25></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.word,\"Word\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.replacement,\"Replacement\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }

    function deleteWord(){
        $word_id = $_GET['word_id'];
        mysql_query("delete from forums_words where word_id = '$word_id'");
        $this->core->addLog("Word Deleted", $this->userinfo[1], $word_id);
        header("Location: forums.php?action=words");
    }

    function modifyWord(){

        if ($_POST['confirm'] == 1){
            $word_id = $_POST['word_id'];
            $word = $this->textFun->convertText($_POST['word'], 1, 2);
            $replace = $this->textFun->convertText($_POST['replacement'], 1, 2);
            mysql_query("update forums_words set word = '$word', replacement = '$replace' where word_id = '$word_id'");
            $this->core->addLog("Forum Word Modified", $this->userinfo[1], $word_id);
            header("Location: forums.php?action=words");
        }
        else {
            $word_id = $_GET['word_id'];
            $header = $this->forms->createFormHeader("Modify Word Censor List");
            $footer = $this->forms->createFormFooter("Modify Word");
            list($word, $replacement) = mysql_fetch_row(mysql_query("select word, replacement from forums_words where word_id = '$word_id'"));

            $text = "<form method=post action=forums.php?action=words&subaction=modify onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1><input type=hidden name=word_id value=$word_id>";
            $text .= "<tr><td class=mainbold>Word to Replace: </td><td class=main><input type=text value='$word' name=word size=25></td></tr>";
            $text .= "<tr><td class=mainbold>Replacement: </td><td class=main><input type=text value='$replacement' name=replacement size=25></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.word,\"Word\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.replacement,\"Replacement\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }

    function listWords(){

        $header = $this->forms->createFormHeader("Censor Word List");
        $text = "<tr><td class=bg>Word</td><td class=bg>Replacement</td><td class=bg>Actions</td></tr>";
        $result = mysql_query("select * from forums_words order by word");
        $count = mysql_num_rows($result);
        if ($count > 0){
            while($row = mysql_fetch_array($result)){
                $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC'; \" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
                $text .= "<td class=hover>$row[word]</td><td class=hover>$row[replacement]</td>";
                $text .= "<td class=hover><a href=forums.php?action=words&subaction=modify&word_id=$row[word_id]><img src=images/bedit.gif alt='Modify Entry' border=0></a> ";
                $text .= "<a href=javascript:confirmDelete('forums.php?action=words&subaction=delete&word_id=$row[word_id]')><img src=images/bdelete.gif border=0 alt='Delete Entry'></a></td></tr>";
            }
        }
        else { $text .= "<tr><td class=main>---</td><td class=main>---</td><td class=main>---</td></tr>"; }
        $text .= "<tr><td class=main colspan=3 align=center><input type=button value='Add New Word' onClick=goToURL('forums.php?action=words&subaction=add')></td></tr>";
        $text .= "</table></td></tr></table>";

        $this->body = $header . $text;

    }
}

class forumXcode {

    function forumXcode($userinfo){
        $this->userinfo = $userinfo;

        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();
        $this->forms = new forumForm();
        $this->trans = array("<" => "&lt;", ">" => "&gt;");
        switch($_GET['subaction']){

            case add:
                $this->addCode();
                break;
            case delete:
                $this->deleteCode();
                break;
            case modify:
                $this->modifyCode();
                break;
            default:
                $this->listCode();
            }
    }

    function addCode(){

        if ($_POST['confirm'] == 1){
            mysql_query("insert into forums_xcode values ('', '$_POST[find]', '$_POST[replace]')");
            $xcode_id = mysql_insert_id();
            $this->core->addLog("Xcode Added", $this->userinfo[1], $xcode_id);
            header("Location: forums.php?action=xcode");
        }
        else {
            $header = $this->forms->createFormHeader("Add Xcode to List");
            $footer = $this->forms->createFormFooter("Add Xcode");

            $text = "<form method=post action=forums.php?action=xcode&subaction=add onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>Find: </td><td class=main><input type=text value='' name=find size=25></td></tr>";
            $text .= "<tr><td class=mainbold>Replace: </td><td class=main><input type=text value='' name=replace size=25></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.find,\"Find\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.replace,\"Replace\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }

    function deleteCode(){
        $xcode_id = $_GET['xcode_id'];
        mysql_query("delete from forums_xcode where xcode_id = '$xcode_id'");
        $this->core->addLog("Xcode Deleted", $this->userinfo[1], $xcode_id);
        header("Location: forums.php?action=xcode");
    }

    function modifyCode(){

        if ($_POST['confirm'] == 1){
            $xcode_id = $_POST['xcode_id'];
            mysql_query("update forums_xcode set xcode_find = '$_POST[find]', xcode_replace = '$_POST[replace]' where xcode_id = '$xcode_id'");
            $this->core->addLog("Xcode Modified", $this->userinfo[1], $xcode_id);
            header("Location: forums.php?action=xcode");
        }
        else {
            $xcode_id = $_GET['xcode_id'];
            $header = $this->forms->createFormHeader("Modify Xcode");
            $footer = $this->forms->createFormFooter("Modify Xcode");
            list($find, $replace) = mysql_fetch_row(mysql_query("select xcode_find, xcode_replace from forums_xcode where xcode_id = '$xcode_id'"));
            $replace = strtr($replace, $this->trans);
            $text = "<form method=post action=forums.php?action=xcode&subaction=modify onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1><input type=hidden name=xcode_id value=$xcode_id>";
            $text .= "<tr><td class=mainbold>Find: </td><td class=main><input type=text value='$find' name=find size=25></td></tr>";
            $text .= "<tr><td class=mainbold>Replace: </td><td class=main><input type=text value='$replace' name=replace size=25></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.find,\"Find\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.replace,\"Replace\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }

    function listCode(){

        $header = $this->forms->createFormHeader("Xcode List");
        $text = "<tr><td class=bg>Find</td><td class=bg>Replace</td><td class=bg>Actions</td></tr>";
        $result = mysql_query("select * from forums_xcode order by xcode_find");
        $count = mysql_num_rows($result);
        if ($count > 0){
            while($row = mysql_fetch_array($result)){
                $row[xcode_replace] = strtr($row[xcode_replace], $this->trans);
                $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC'; \" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
                $text .= "<td class=hover>$row[xcode_find]</td><td class=hover>$row[xcode_replace]</td>";
                $text .= "<td class=hover><a href=forums.php?action=xcode&subaction=modify&xcode_id=$row[xcode_id]><img src=images/bedit.gif alt='Modify Entry' border=0></a> ";
                $text .= "<a href=javascript:confirmDelete('forums.php?action=xcode&subaction=delete&xcode_id=$row[xcode_id]')><img src=images/bdelete.gif border=0 alt='Delete Entry'></a></td></tr>";
            }
        }
        else { $text .= "<tr><td class=main>---</td><td class=main>---</td><td class=main>---</td></tr>"; }
        $text .= "<tr><td class=main colspan=3 align=center><input type=button value='Add New Xcode' onClick=goToURL('forums.php?action=xcode&subaction=add')></td></tr>";
        $text .= "</table></td></tr></table>";

        $this->body = $header . $text;

    }
}

class forumForm {

    function createRadio($selectName, $formName, $default=''){
        $text = "<tr><td class=mainbold>$formName: </td><td class=main>";
        if ($default == 0){ $text .= "<input type=radio name=$selectName value=1>Yes <input type=radio name=$selectName value=0 checked>No"; }
        else { $text .= "<input type=radio name=$selectName value=1 checked>Yes <input type=radio name=$selectName value=0>No"; }
        $text .= "</td></tr>";
        return $text;
    }

    function createFormHeader($formTitle){
        $text = "<table class=outline border=0 cellpadding=2 cellspacing=1 width=650 align=center>";
        $text .= "<tr>";
        $text .= "<td class=title> $formTitle</td>";
        $text .= "</tr>";
        $text .= "<tr>";
        $text .= "<td class=main>";
        $text .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>";
        return $text;
    }

    function createFormFooter($enterButton, $delete='', $url=''){
        $text = "<tr><td class=main align=center colspan=2><input type=submit value='$enterButton'>";
        if ($delete == 1){ $text .= " <input type=button value='Delete' onClick=confirmDelete('$url')>"; }
        $text .= "</td></form></tr>";
        $text .= "</table>";
        $text .= "</td></tr></table>";
        return $text;
    }

    function createDropDown($type, $selectName, $formName, $default=''){

        $start = "<tr><td class=mainbold>$formName: </td><td class=main><select name=$selectName>";
        $end = "</select></td></tr>";

        switch($type){

            case level:
                global $forumLevel;
                $keys = array_flip($forumLevel);

                foreach($forumLevel as $f){
                    if ($keys[$f] == $default && $default != ''){ $s = "selected"; }
                    else { $s = ''; }
                    $text .= "<option value=$keys[$f] $s>$f</option>";
                }
                break;

            case cat:
                $result = mysql_query("select cat_id, name from forums_cat order by ord asc");
                while(list($cat_id, $name) = mysql_fetch_row($result)){
                    if ($cat_id == $default && $default != ''){ $s = "selected"; }
                    else { $s = ''; }
                    $text .= "<option value=$cat_id $s>$name</option>";
                }
                break;

            case number:
                for($i=5; $i<55; $i+=5){
                    if ($i == $default && $default != ''){ $s = "selected"; }
                    else { $s = ''; }
                    $text .= "<option value=$i $s>$i</option>";
                }
                break;
        }

        $text = $start . $text . $end;
        return $text;
    }

}

class forumAvatars{


    function forumAvatars($userinfo){
        $this->action = $_GET['subaction'];
        $this->userinfo = $userinfo;

        $this->core = new coreFunctions();
        $this->forms = new forumForm();

        switch($this->action){
            case sync:
                $this->syncAvatars();
                break;
            default:
                $this->listAvatars();
                break;
        }
    }

    function listAvatars(){

        $header = $this->forms->createFormHeader("Avatar List");
        if ($_GET['limit'] == ''){ $limit = 0; }
        else { $limit = $_GET['limit']; }
        $result = mysql_query("select av_id, file from forums_avatars where user_id = '0' order by av_id asc");
        $number = mysql_num_rows($result);
        $result = mysql_query("select av_id, file from forums_avatars where user_id = '0' order by av_id asc limit $limit, 30") or die(mysql_error());
        if ($number == 0){
            $text .= "<tr><td class=attn align=center>No Avatars Loaded</td></tr>";
        }
        else {
            $tabs = $this->core->pageTab($limit, $number, "forums.php?action=avatars", "30");
            $text .= "<tr><td class=main colspan=5>$tabs</td></tr>";
            $i=0;
            while(list($av_id, $file) = mysql_fetch_row($result)){
                if ($i == 0){ $text .= "<tr>"; }
                $text .= "<td class=main align=center width=20%><img src=../images/avatars/$file alt='$file' border=0 width=45 height=45><br>$file</td>";
                if ($i == 4){ $text .= "</tr>"; $i=0; }
                else { $i++; }
            }
            $text .= "</tr>";
            $text .= "<tr><td class=main colspan=5>$tabs</td></tr>";
        }
        $text .= "<tr><td class=main colspan=5 align=center><input type=button value='Sync Avatars' onClick=goToURL('forums.php?action=avatars&subaction=sync')></td></tr>";
        $text .= "</table></td></tr></table>";
        $this->body = $header . $text;
    }

    function syncAvatars(){
        $hold = array();
        $avatarDir = "../images/avatars";
        $handle=opendir($avatarDir);
        while (false!==($file = readdir($handle))) {
            $checkFile = $avatarDir . "/" . $file;
            if ($file != "." && $file != ".." && is_file($checkFile) && $file != "index.php") {
                $result = mysql_query("select av_id from forums_avatars where file = '$file'");
                $count = mysql_num_rows($result);
                if ($count == 0){
                    mysql_query("insert into forums_avatars values ('', '0', '$file', '$file')");
                    $av_id = mysql_insert_id();
                }
                else {
                    list($av_id) = mysql_fetch_row($result);
                }
                $hold[] = $av_id;
            }
        }
        closedir($handle);

        $result = mysql_query("select av_id from forums_avatars where user_id = '0'");
        while(list($av_id) = mysql_fetch_row($result)){
            if (!in_array($av_id, $hold)){
                mysql_query("delete from forums_avatars where av_id = '$av_id'");
            }
        }
        $this->core->addLog("Avatars Synced", $this->userinfo[1], '');
        header("Location: forums.php?action=avatars");
    }
}

class forumSmiles {

    function forumSmiles($userinfo){
        $this->userinfo = $userinfo;

        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();
        $this->forms = new forumForm();

        switch($_GET['subaction']){

            case add:
                $this->addSmile();
                break;
            case delete:
                $this->deleteSmile();
                break;
            case modify:
                $this->modifySmile();
                break;
            default:
                $this->listSmiles();
            }
    }

    function addSmile($skip=''){

        if ($_POST['confirm'] == 1 && $skip == ''){
            global $image_types;
            $ext = array_flip($image_types);
            $check = $_FILES['image'][tmp_name];
            list($count) = mysql_fetch_row(mysql_query("select count(smile_id) from forums_smiles where code = '$_POST[code]'"));
            if ($count != 0){
                $this->addSmile(3);
            }
            else if ($check != ''){
                $file_type = $_FILES['image'][type];
                if (!in_array($file_type, $image_types)){ $this->addSmile(2); }
                else {
                    $desc = $this->textFun->convertText($_POST['description'], 1, 2);
                    $filesend =  "../images/smiles/" . $_FILES['image']['name'];
                    copy($check, $filesend);
                    mysql_query("insert into forums_smiles values ('', '" . $_POST['code'] . "', '" . $_FILES['image']['name'] . "', '$desc')");
                    $smile_id = mysql_insert_id();
                    $this->core->addLog("Smilie Added", $this->userinfo[1], $smile_id);
                    header("Location: forums.php?action=smile");
                }
            }
            else {
                $this->addSmile(1);
            }
        }
        else {
            $header = $this->forms->createFormHeader("Add Smilie");
            $footer = $this->forms->createFormFooter("Add Smilie");

            $text = "<form method=post action=forums.php?action=smile&subaction=add enctype='multipart/form-data' onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>Smilie Image: </td><td class=main><input type=file name=image size=25></td></tr>";
            if ($skip == 2){ $text .= "<tr><td class=attn2 colspan=2>invalid file type. must be a jpg, gif, or png</td></tr>"; }
            $text .= "<tr><td class=mainbold>Code Used to Call Smilie: </td><td class=main><input type=text value='$_POST[code]' name=code size=10></td></tr>";
            if ($skip == 3){ $text .= "<tr><td class=attn2 colspan=2>code already in use.  must be unique.</td></tr>"; }
            $text .= "<tr><td class=mainbold>Description: </td><td class=main><input type=text value='$_POST[description]' name=description size=40></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.image,\"Smilie Image\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.code,\"Code\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }

    function deleteSmile(){
        $smile_id = $_GET['smile_id'];
        list($file) = mysql_fetch_row(mysql_query("select image from forums_smiles where smile_id = '$smile_id'"));
        $file = "../images/smiles/$file";
        unlink($file);
        mysql_query("delete from forums_smiles where smile_id = '$smile_id'");
        $this->core->addLog("Smilie Deleted", $this->userinfo[1], $smile_id);
        header("Location: forums.php?action=smile");
    }

    function modifySmile($skip=''){

        if ($_POST['confirm'] == 1 && $skip == ''){
            global $image_types;
            $smile_id = $_POST['smile_id'];
            $ext = array_flip($image_types);
            $check = $_FILES['image'][tmp_name];
            list($count) = mysql_fetch_row(mysql_query("select count(smile_id) from forums_smiles where code = '$_POST[code]' and smile_id != '$smile_id'"));
            if ($count != 0){
                $this->modifySmile(3);
            }
            else if ($check != ''){
                $file_type = $_FILES['image'][type];
                if (!in_array($file_type, $image_types)){ $this->modifySmile(2); }
                else {
                    list($image) = mysql_fetch_row(mysql_query("select image from forums_smiles where smile_id = '$smile_id'"));
                    $image = "../images/smiles/$image";
                    unlink($image);
                    $desc = $this->textFun->convertText($_POST['description'], 1, 2);
                    $filesend =  "../images/smiles/" . $_FILES['image']['name'];
                    copy($check, $filesend);
                    mysql_query("update forums_smiles set code = '$_POST[code]', description = '$desc', image = '" . $_FILES['image']['name'] . "' where smile_id = '$smile_id'");
                    $this->core->addLog("Smilie Modified", $this->userinfo[1], $smile_id);
                    header("Location: forums.php?action=smile");
                }
            }
            else {
                $this->modifySmile(1);
            }
        }
        else {
            $smile_id = $_GET['smile_id'];
            list($code, $description, $image) = mysql_fetch_row(mysql_query("select code, description, image from forums_smiles where smile_id = '$smile_id'"));
            $image = "../images/smiles/$image";
            $header = $this->forms->createFormHeader("Modify Smilie");
            $footer = $this->forms->createFormFooter("Modify Smilie");

            $text = "<form method=post action=forums.php?action=smile&subaction=modify enctype='multipart/form-data' onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1><input type=hidden name=smile_id value=$smile_id>";
            $text .= "<tr><td class=mainbold>Smilie Image: </td><td class=main><input type=file name=image size=25>";
            $text .= "<img src=$image alt='Smilie' border=0 align=right>";
            $text .= "</td></tr>";
            if ($skip == 2){ $text .= "<tr><td class=attn2 colspan=2>invalid file type. must be a jpg, gif, or png</td></tr>"; }
            $text .= "<tr><td class=mainbold>Code Used to Call Smilie: </td><td class=main><input type=text value='$code' name=code size=10></td></tr>";
            if ($skip == 3){ $text .= "<tr><td class=attn2 colspan=2>code already in use.  must be unique.</td></tr>"; }
            $text .= "<tr><td class=mainbold>Description: </td><td class=main><input type=text value='$description' name=description size=40></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.image,\"Smilie Image\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.code,\"Code\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }

    function listSmiles(){

        $header = $this->forms->createFormHeader("Forum Smilie List");
        $text = "<tr><td class=bg>Smilie</td><td class=bg>Code</td><td class=bg>Description><td class=bg>Actions</td></tr>";
        $result = mysql_query("select * from forums_smiles order by smile_id");
        $count = mysql_num_rows($result);
        if ($count > 0){
            while($row = mysql_fetch_array($result)){
                $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC'; \" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
                $text .= "<td class=hover><img src=../images/smiles/$row[image] border=0 alt='Smilie'></td><td class=hover>$row[code]</td>";
                $text .= "<td class=hover>$row[description]</td>";
                $text .= "<td class=hover><a href=forums.php?action=smile&subaction=modify&smile_id=$row[smile_id]><img src=images/bedit.gif alt='Modify Smilie' border=0></a> ";
                $text .= "<a href=javascript:confirmDelete('forums.php?action=smile&subaction=delete&smile_id=$row[smile_id]')><img src=images/bdelete.gif border=0 alt='Delete Smilie'></a></td></tr>";
            }
        }
        else { $text .= "<tr><td class=main>---</td><td class=main>---</td><td class=main>---</td></tr>"; }
        $text .= "<tr><td class=main colspan=4 align=center><input type=button value='Add New Smilie' onClick=goToURL('forums.php?action=smile&subaction=add')></td></tr>";
        $text .= "</table></td></tr></table>";

        $this->body = $header . $text;

    }



}


class forumFlags {

    function forumFlags($userinfo){
        $this->userinfo = $userinfo;

        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();
        $this->forms = new forumForm();

        switch($_GET['subaction']){

            case add:
                $this->addFlag();
                break;
            case delete:
                $this->deleteFlag();
                break;
            case modify:
                $this->modifyFlag();
                break;
            default:
                $this->listFlags();
            }
    }

    function addFlag($skip=''){

        if ($_POST['confirm'] == 1 && $skip == ''){
            global $image_types;
            $ext = array_flip($image_types);
            $check = $_FILES['image'][tmp_name];
            list($count) = mysql_fetch_row(mysql_query("select count(flag_id) from forums_flags where flag_name = '$_POST[name]'"));
            if ($count != 0){
                $this->addFlag(3);
            }
            else if ($check != ''){
                $file_type = $_FILES['image'][type];
                if (!in_array($file_type, $image_types)){ $this->addFlag(2); }
                else {
                    $name = $this->textFun->convertText($_POST['name'], 1, 0);
                    $filesend =  "../images/flags/" . $_FILES['image']['name'];
                    copy($check, $filesend);
                    mysql_query("insert into forums_flags values ('', '$name', '" . $_FILES['image']['name'] . "')");
                    $flag_id = mysql_insert_id();
                    $this->core->addLog("Flag Added", $this->userinfo[1], $flag_id);
                    header("Location: forums.php?action=flag");
                }
            }
            else {
                $this->addFlag(1);
            }
        }
        else {
            $header = $this->forms->createFormHeader("Add Flag");
            $footer = $this->forms->createFormFooter("Add Flag");

            $text = "<form method=post action=forums.php?action=flag&subaction=add enctype='multipart/form-data' onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>Flag Image: </td><td class=main><input type=file name=image size=25></td></tr>";
            if ($skip == 2){ $text .= "<tr><td class=attn2 colspan=2>invalid file type. must be a jpg, gif, or png</td></tr>"; }
            $text .= "<tr><td class=mainbold>Country Name: </td><td class=main><input type=text value='$_POST[name]' name=name size=10></td></tr>";
            if ($skip == 3){ $text .= "<tr><td class=attn2 colspan=2>name already in use.  must be unique.</td></tr>"; }
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.image,\"Flag Image\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.name,\"Country Name\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }

    function deleteFlag(){
        $flag_id = $_GET['flag_id'];
        list($file) = mysql_fetch_row(mysql_query("select flag_file from forums_flags where flag_id = '$flag_id'"));
        $file = "../images/flags/$file";
        unlink($file);
        mysql_query("delete from forums_flags where flag_id = '$flag_id'");
        $this->core->addLog("Flag Deleted", $this->userinfo[1], $flag_id);
        header("Location: forums.php?action=flag");
    }

    function modifyFlag($skip=''){

        if ($_POST['confirm'] == 1 && $skip == ''){
            global $image_types;
            $flag_id = $_POST['flag_id'];
            $ext = array_flip($image_types);
            $check = $_FILES['image'][tmp_name];
            list($count) = mysql_fetch_row(mysql_query("select count(flag_id) from forums_flags where flag_name = '$_POST[name]' and flag_id != '$flag_id'"));
            if ($count != 0){
                $this->modifyFlag(3);
            }
            else if ($check != ''){
                $file_type = $_FILES['image'][type];
                if (!in_array($file_type, $image_types)){ $this->modifyFlag(2); }
                else {
                    list($image) = mysql_fetch_row(mysql_query("select flag_file from forums_flags where flag_id = '$flag_id'"));
                    $image = "../images/flags/$image";
                    unlink($image);
                    $name = $this->textFun->convertText($_POST['name'], 1, 0);
                    $filesend =  "../images/flags/" . $_FILES['image']['name'];
                    copy($check, $filesend);
                    mysql_query("update forums_flags set flag_name = '$name', flag_file = '" . $_FILES['image']['name'] . "' where flag_id = '$flag_id'");
                    $this->core->addLog("Flag Modified", $this->userinfo[1], $flag_id);
                    header("Location: forums.php?action=flag");
                }
            }
            else {
                $this->modifyFlag(1);
            }
        }
        else {
            $flag_id = $_GET['flag_id'];
            list($name, $image) = mysql_fetch_row(mysql_query("select flag_name, flag_file from forums_flags where flag_id = '$flag_id'"));
            $image = "../images/flags/$image";
            $header = $this->forms->createFormHeader("Modify Flag");
            $footer = $this->forms->createFormFooter("Modify Flag");

            $text = "<form method=post action=forums.php?action=flag&subaction=modify enctype='multipart/form-data' onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1><input type=hidden name=flag_id value=$flag_id>";
            $text .= "<tr><td class=mainbold>Flag Image: </td><td class=main><input type=file name=image size=25>";
            $text .= "<img src=$image alt='Flag' border=0 align=right>";
            $text .= "</td></tr>";
            if ($skip == 2){ $text .= "<tr><td class=attn2 colspan=2>invalid file type. must be a jpg, gif, or png</td></tr>"; }
            $text .= "<tr><td class=mainbold>Country Name: </td><td class=main><input type=text value='$name' name=name size=10></td></tr>";
            if ($skip == 3){ $text .= "<tr><td class=attn2 colspan=2>country name already in use.  must be unique.</td></tr>"; }

            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.image,\"Flag Image\"))";
            $text .= "        return false;";
            $text .= "    if (!validRequired(theForm.name,\"Country Name\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }

    function listFlags(){

        $header = $this->forms->createFormHeader("Flag List");
        $text = "<tr><td class=bg>Flag</td><td class=bg>Name</td><td class=bg>Actions</td></tr>";
        $result = mysql_query("select * from forums_flags order by flag_name");
        $count = mysql_num_rows($result);
        if ($count > 0){
            while($row = mysql_fetch_array($result)){
                $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC'; \" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
                $text .= "<td class=hover><img src=../images/flags/$row[flag_file] border=0 alt='Flag'></td><td class=hover>$row[flag_name]</td>";
                $text .= "<td class=hover><a href=forums.php?action=flag&subaction=modify&flag_id=$row[flag_id]><img src=images/bedit.gif alt='Modify Flag' border=0></a> ";
                $text .= "<a href=javascript:confirmDelete('forums.php?action=flag&subaction=delete&flag_id=$row[flag_id]')><img src=images/bdelete.gif border=0 alt='Delete Flag'></a></td></tr>";
            }
        }
        else { $text .= "<tr><td class=main>---</td><td class=main>---</td><td class=main>---</td></tr>"; }
        $text .= "<tr><td class=main colspan=4 align=center><input type=button value='Add New Flag' onClick=goToURL('forums.php?action=flag&subaction=add')></td></tr>";
        $text .= "</table></td></tr></table>";

        $this->body = $header . $text;

    }
}

class forumRanks{

    function forumRanks($userinfo){
        $this->userinfo = $userinfo;

        $this->core = new coreFunctions();
        $this->textFun = new textFunctions();
        $this->forms = new forumForm();

        switch($_GET['subaction']){

            case add:
                $this->addRank();
                break;
            case delete:
                $this->deleteRank();
                break;
            case modify:
                $this->modifyRank();
                break;
            default:
                $this->listRanks();
            }
    }

    function addRank($skip=''){
        global $image_types;

        if ($_POST['confirm'] == 1){
            $name = $this->textFun->convertText($_POST['rank_name'], 1, 2);
            mysql_query("insert into forums_ranks values ('', '$name', '$_POST[rank_min]', '', '$_POST[rank_spec]')");
            $rank_id = mysql_insert_id();
            $ext = array_flip($image_types);
            $check = $_FILES['image'][tmp_name];
            if ($check != ''){
                $file_type = $_FILES['image'][type];
                if (in_array($file_type, $image_types)){
                    $filesend =  "../images/other/" . $_FILES['image']['name'];
                    copy($check, $filesend);
                    $file = $_FILES['image']['name'];
                    mysql_query("update forums_ranks set rank_image = '$file' where rank_id = '$rank_id'");
                }
            }
            $this->core->addLog("Forum Rank Added", $this->userinfo[1], $rank_id);
            header("Location: forums.php?action=ranks");
        }
        else {
            $header = $this->forms->createFormHeader("Add Forum Rank");
            $footer = $this->forms->createFormFooter("Add Rank");
            $special = $this->forms->createRadio("rank_spec", "Special Rank");
            $text = "<form method=post action=forums.php?action=ranks&subaction=add enctype='multipart/form-data' onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>Rank Name: </td><td class=main><input type=text value='' name=rank_name size=25></td></tr>";
            $text .= "<tr><td class=mainbold>Minimum Posts: </td><td class=main><input type=text value='' name=rank_min size=5></td></tr>";
            $text .= "<tr><td class=mainbold>Rank Image: </td><td class=main><input type=file value='' name=image size=25></td></tr>";
            $text .= $special;
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.name,\"Rank Name\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";

            $this->body = $header . $text . $footer;
        }
    }



    function deleteRank(){
        $rank_id = $_GET['rank_id'];
        list($image) = mysql_fetch_row(mysql_query("select rank_image from forums_ranks where rank_id = '$rank_id'"));
        if ($image != ''){
            $path = "../images/other/" . $image;
            unlink($path);
        }
        mysql_query("delete from forums_ranks where rank_id = '$rank_id'");
        $this->core->addLog("Forum Rank Deleted", $this->userinfo[1], $rank_id);
        header("Location: forums.php?action=ranks");
    }

    function modifyRank(){
        global $image_types;
        if ($_POST['confirm'] == 1){
            $rank_id = $_POST['rank_id'];
            $name = $this->textFun->convertText($_POST['rank_name'], 1, 2);
            mysql_query("update forums_ranks set rank_name = '$name', rank_min = '$_POST[rank_min]', rank_spec = '$_POST[rank_spec]' where rank_id = '$rank_id'");

            $ext = array_flip($image_types);
            $check = $_FILES['image'][tmp_name];
            if ($check != ''){
                $file_type = $_FILES['image'][type];
                if (in_array($file_type, $image_types)){
                    list($old) = mysql_fetch_row(mysql_query("select rank_image from forums_ranks where rank_id = '$rank_id'"));
                    $old = "../images/other/" . $old;
                    unlink($old);

                    $filesend =  "../images/other/" . $_FILES['image']['name'];
                    copy($check, $filesend);
                    $file = $_FILES['image']['name'];
                    mysql_query("update forums_ranks set rank_image = '$file' where rank_id = '$rank_id'");
                }
            }
            $this->core->addLog("Forum Rank Modified", $this->userinfo[1], $rank_id);
            header("Location: forums.php?action=ranks");
        }
        else {
            $rank_id = $_GET['rank_id'];
            $row = mysql_fetch_array(mysql_query("select * from forums_ranks where rank_id = '$rank_id'"));
            $header = $this->forms->createFormHeader("Modify Forum Rank");
            $footer = $this->forms->createFormFooter("Modify Rank");
            $special = $this->forms->createRadio("rank_spec", "Special Rank", $row[rank_spec]);
            $text = "<form method=post action=forums.php?action=ranks&subaction=modify enctype='multipart/form-data' onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1><input type=hidden name=rank_id value=$rank_id>";
            $text .= "<tr><td class=mainbold>Rank Name: </td><td class=main><input type=text value='$row[rank_name]' name=rank_name size=25></td></tr>";
            $text .= "<tr><td class=mainbold>Minimum Posts: </td><td class=main><input type=text value='$row[rank_min]' name=rank_min size=5></td></tr>";
            if ($row[rank_image] != ''){ $image = "<img src=../images/other/$row[rank_image] alt='Rank Image'>"; }
            $text .= "<tr><td class=mainbold>Rank Image: $image </td><td class=main><input type=file value='' name=image size=25></td></tr>";
            $text .= $special;
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.name,\"Rank Name\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";

            $this->body = $header . $text . $footer;
        }
    }

    function listRanks(){

        $header = $this->forms->createFormHeader("Forum Ranks");
        $text = "<tr><td class=bg>Rank</td><td class=bg>Min. Posts</td><td class=bg>Rank Image</td><td class=bg>Special</td><td class=bg>Actions</td></tr>";
        $result = mysql_query("select * from forums_ranks order by rank_min,rank_spec");
        $count = mysql_num_rows($result);
        if ($count > 0){
            while($row = mysql_fetch_array($result)){
                $text .= "<tr onMouseOver=\"style.backgroundColor='#B5C1DC'; \" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
                $text .= "<td class=hover>$row[rank_name]</td><td class=hover>$row[rank_min]</td>";
                $text .= "<td class=hover>";
                if ($row[rank_image] != ''){ $text .= "<img src=../images/other/$row[rank_image] border=0>"; }
                if ($row[rank_spec] == 1){ $spec = "Yes"; }
                else { $spec = "No"; }
                $text .= "</td><td class=hover>$spec</td>";
                $text .= "<td class=hover><a href=forums.php?action=ranks&subaction=modify&rank_id=$row[rank_id]><img src=images/bedit.gif alt='Modify Entry' border=0></a> ";
                $text .= "<a href=javascript:confirmDelete('forums.php?action=ranks&subaction=delete&rank_id=$row[rank_id]')><img src=images/bdelete.gif border=0 alt='Delete Entry'></a></td></tr>";
            }
        }
        else { $text .= "<tr><td class=main>---</td><td class=main>---</td><td class=main>---</td></tr>"; }
        $text .= "<tr><td class=main colspan=5 align=center><input type=button value='Add Rank' onClick=goToURL('forums.php?action=ranks&subaction=add')></td></tr>";
        $text .= "</table></td></tr></table>";

        $this->body = $header . $text;

    }




}
