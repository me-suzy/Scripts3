<?php
#$Id: main.inc.php,v 1.25 2003/11/20 17:13:42 ryan Exp $

class generalConfig {
    var $body;
    var $action;
    var $userinfo;
    var $row;

    function generalConfig($userinfo){
        $this->userinfo = $userinfo;
        $this->action = $_GET['action'];
        $result = mysql_query("select * from config where config_id = '1'");
        $this->row = mysql_fetch_array($result);
        switch($this->action){
            case database:
                $data = new databaseMaint($userinfo);
                $this->body = $data->body;
                break;
            case page:
                $this->themeSelect = new configSelect($this->row["theme_id"], "Theme Selection", "theme", 0);
                $this->barSelect = new configSelect($this->row["barColor"], "Stats Bar Color", "bar", 0);
                $this->pageConfig();
                break;
            case menu:
                $this->menuSelect = new configSelect($this->row["menuType"], "Page Menu Type", "menu", 0);
                $this->menuConfig();
                break;
            case news:
                $this->headlineSelect = new configSelect($this->row["headlines"], "Show Headlines", "headlines", 0);
                $this->newsSelect = new configSelect($this->row["numberOfNews"], "Number of News to Show", "numberOfNews", 0);
                $this->itemSelect = new configSelect($this->row["items_per_page"], "Number of Items to Show", "items_per_page", 0);
                $this->newsConfig();
                break;
            case security:
                $this->securityConfig();
                break;
            case faq:
                $this->faqConfig();
                break;
            default:
                $this->dateSelect = new configSelect($this->row["dateFormat"], "Date Format", "dateFormat", 0);
                $this->langSelect = new configSelect($this->row["lang"], "Language", "lang", 0);
                $this->timeSelect = new configSelect($this->row["timeZone"], "Time Zone", "timeZone", 0);
                $this->general();

        }
        $this->stroke();
    }

    function stroke(){

        new adminPage($this->body);

    }

    function finish(){

        $logs = new coreFunctions();
        $this->action = ucfirst($this->action) . " Config Updated";
        $logs->addLog($this->action, $this->userinfo[1], 1);
        if (isset($_COOKIE[FIRST])){ header("Location: user.php?action=modify&user_id=2&code=5"); }
        else { header("HTTP/1.0 204 No Content"); }

    }

    function pageConfig(){

        if ($_POST['confirm'] == 1){
            $sql = "update config set barColor = '" . $_POST['bar'] . "', theme_id = '" . $_POST['theme'] . "', splash_page = '$_POST[splash_page]' where config_id = '1'";
            mysql_query($sql) or die(mysql_error());
            $this->finish();
        }
        else {

            $text = $this->createTableHeader();
            $text .= $this->barSelect->stroke();
            $text .= $this->themeSelect->stroke();
            $text .= "<tr><td class=mainbold align=right>Use Splash Page: </td><td class=main>";
            if ($this->row[splash_page] == 1){
                $text .= "<input type=radio value=1 name=splash_page checked>Yes <input type=radio name=splash_page value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=splash_page>Yes <input type=radio name=splash_page value=0 checked>No";
            }
            $text .= "</td></tr>";
           	$text .= $this->createTableFooter();
            $this->body = $text;
        }

    }

    function newsConfig(){
        if ($_POST['confirm'] == 1){
            $flip = array_keys($_POST);
            foreach($flip as $f){
                if ($f != "confirm" && $f != "submit" && $f != "action"){
                    $sql = "update config set $f = '$_POST[$f]'";

                    mysql_query($sql) or die(mysql_error());
                }
            }
            if ($_POST[news_comments] == 1){ $this->createCommentsForum(); }
            mysql_query($sql) or die(mysql_error());
            $this->finish();
        }
        else {
            $text = $this->createTableHeader();
            $text .= $this->newsSelect->stroke();
            $text .= $this->headlineSelect->stroke();
            $text .= $this->itemSelect->stroke();
            $text .= "<tr><td class=mainbold align=right>News Comments: </td><td class=main>";
            if ($this->row[news_comments] == 1){
                $text .= "<input type=radio value=1 name=news_comments checked>Yes <input type=radio name=news_comments value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=news_comments>Yes <input type=radio name=news_comments value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>News Category Images: </td><td class=main>";
            if ($this->row[news_images] == 1){
                $text .= "<input type=radio value=1 name=news_images checked>Yes <input type=radio name=news_images value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=news_images>Yes <input type=radio name=news_images value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>News Category Image Align: </td><td class=main>";
            if ($this->row[news_image_align] == 1){
                $text .= "<input type=radio value=1 name=news_image_align checked>Left <input type=radio name=news_image_align value=0>Right";
            }
            else {
                $text .= "<input type=radio value=1 name=news_image_align>Left <input type=radio name=news_image_align value=0 checked>Right";
            }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>News Categories: </td><td class=main>";
            if ($this->row[news_categories] == 1){
                $text .= "<input type=radio value=1 name=news_categories checked>Yes <input type=radio name=news_categories value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=news_categories>Yes <input type=radio name=news_categories value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>News Submissions: </td><td class=main>";
            if ($this->row[news_sub] == 1){
                $text .= "<input type=radio value=1 name=news_sub checked>Yes <input type=radio name=news_sub value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=news_sub>Yes <input type=radio name=news_sub value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>News Anonymous Submissions: </td><td class=main>";
            if ($this->row[news_anon_sub] == 1){
                $text .= "<input type=radio value=1 name=news_anon_sub checked>Yes <input type=radio name=news_anon_sub value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=news_anon_sub>Yes <input type=radio name=news_anon_sub value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= $this->createTableFooter();
            $this->body = $text;
        }
    }

    function faqConfig(){
        if ($_POST['confirm'] == 1){
            mysql_query("update config set faq = '$_POST[faq]', faq_comments = '$_POST[faq_comments]'");
            if ($_POST[faq_comments] == 1){ $this->createCommentsForum(); }
            $this->finish();
        }
        else {
            $text = $this->createTableHeader();
            $text .= "<tr><td class=mainbold align=right>FAQ Active: </td><td class=main>";
            if ($this->row[faq] == 1){
                $text .= "<input type=radio value=1 name=faq checked>Yes <input type=radio name=faq value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=faq>Yes <input type=radio name=faq value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>FAQ Comments: </td><td class=main>";
            if ($this->row[faq_comments] == 1){
                $text .= "<input type=radio value=1 name=faq_comments checked>Yes <input type=radio name=faq_comments value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=faq_comments>Yes <input type=radio name=faq_comments value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= $this->createTableFooter();
            $this->body = $text;
        }
    }

    function menuConfig(){
        if ($_POST['confirm'] == 1){
            $sql = "update config set menuType = '" . $_POST['menu'] . "' where config_id = '1'";
            mysql_query($sql) or die(mysql_error());
            $this->finish();
        }
        else {

            $text = $this->createTableHeader();
            $text .= $this->menuSelect->stroke();
            $text .= $this->createTableFooter();
            $this->body = $text;
        }
    }

    function general(){
        if ($_POST['confirm'] == 1){
            $flip = array_keys($_POST);
            foreach($flip as $f){
                if ($f != "confirm" && $f != "submit"){
                    $sql = "update config set $f = '$_POST[$f]'";

                    mysql_query($sql) or die(mysql_error());
                }
            }
            $this->finish();
        }
        else {
            $text = $this->createTableHeader();
            $text .= "<tr><td class=mainbold align=right width=200>Site Name: </td><td class=main><input size=50 type=text name=siteName value='" . $this->row[siteName] . "'></td></tr>";
            $text .= "<tr><td class=mainbold align=right>Site URL: </td><td class=main><input size=50 type=text name=siteURL value='" . $this->row[siteURL] . "'></td></tr>";
            $text .= "<tr><td class=mainbold align=right>Webmaster Email: </td><td class=main><input size=50 type=text name=webmasterEmail value='" . $this->row[webmasterEmail] . "'></td></tr>";
            $text .= "<tr><td class=mainbold align=right>Header Keywords: </td><td class=main><input size=50 type=text name=keywords value='" . $this->row[keywords] . "'></td></tr>";
            $text .= "<tr><td class=mainbold align=right>Header Description: </td><td class=main><input size=50 type=text name=description value='" . $this->row[description] . "'></td></tr>";

            $text .= $this->dateSelect->stroke();
            $text .= $this->langSelect->stroke();
            $text .= $this->timeSelect->stroke();
            $text .= $this->createTableFooter();
            $this->body = $text;
        }
    }

    function securityConfig(){
        if ($_POST['confirm'] == 1){
            $flip = array_keys($_POST);
            foreach($flip as $f){
                if ($f != "confirm" && $f != "submit"){
                    $sql = "update config set $f = '$_POST[$f]'";

                    mysql_query($sql) or die(mysql_error());
                }
            }
            $this->finish();
        }
        else {
            $text = $this->createTableHeader();
            $text .= "<tr><td class=mainbold align=right>Close Site: </td><td class=main>";
            if ($this->row[close_site] == 1){
                $text .= "<input type=radio value=1 name=close_site checked>Yes <input type=radio name=close_site value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=close_site>Yes <input type=radio name=close_site value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>Use SSL: </td><td class=main>";
            if ($this->row[secure] == 1){
                $text .= "<input type=radio value=1 name=secure checked>Yes <input type=radio name=secure value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=secure>Yes <input type=radio name=secure value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>Password Protect Entire Site: </td><td class=main>";
            if ($this->row[http_secure] == 1){
                $text .= "<input type=radio value=1 name=http_secure checked>Yes <input type=radio name=http_secure value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=http_secure>Yes <input type=radio name=http_secure value=0 checked>No";
            }
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>Use Database for Security: </td><td class=main>";
            if ($this->row[database_secure] == 1){
                $text .= "<input type=radio value=1 name=database_secure checked>Yes <input type=radio name=database_secure value=0>No";
            }
            else {
                $text .= "<input type=radio value=1 name=database_secure>Yes <input type=radio name=database_secure value=0 checked>No";
            }
            $text .= "<br><span class=small>No = Use Domain Security.  Requires smauth.so</span>";
            $text .= "</td></tr>";
            $text .= "<tr><td class=mainbold align=right>Domain Server 1: </td><td class=main><input size=50 type=text name=http_server1 value='" . $this->row[http_server1] . "'></td></tr>";
            $text .= "<tr><td class=mainbold align=right>Domain Server 2: </td><td class=main><input size=50 type=text name=http_server2 value='" . $this->row[http_server2] . "'></td></tr>";
            $text .= "<tr><td class=mainbold align=right>Domain: </td><td class=main><input size=50 type=text name=http_domain value='" . $this->row[http_domain] . "'></td></tr>";
            $text .= $this->createTableFooter();
            $this->body = $text;
        }
    }

    function createTableHeader(){

        $ac = ucfirst($this->action);
        $text = "<form method=post action=config.php?action=" . $this->action . " onSubmit=\"displaySubs('actionResult');\">";
        $text .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=500 align=center>";
        $text .= "<tr>";
        $text .= "<td class=title colspan=2>&nbsp; $ac Configuration</td>";
        $text .= "</tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        $text .= "<tr><td class=main colspan=2><div class=attn id=actionResult style=\"display:none\">Configuration Saved</div></td></tr>";
        $text .= "<input type=hidden name=confirm value=1>";
        return $text;

    }

    function createTableFooter(){

        $text = "<tr><td class=main align=center colspan=2>";
        $text .= "<input type=submit name=submit value='Enter'>";
        $text .= "</td></form></tr></table></td></tr></table>";
        return $text;

    }

    function createCommentsForum(){
        $result = mysql_query("select cat_id from forums_cat where comments = '1'");
        $count = mysql_num_rows($result);
        if ($count != 1){
            list($ord) = mysql_fetch_row(mysql_query("select ord from forums_cat order by ord desc limit 0,1"));
            $ord++;
            mysql_query("insert into forums_cat values ('', 'Comments', '$ord', '1')");
            $cat_id = mysql_insert_id();
            mysql_query("insert into forums_forums values ('', '$cat_id', 'News', 'News Comments', '2', '0', '0', '0', '1', '1')");
            mysql_query("insert into forums_forums values ('', '$cat_id', 'FAQ', 'FAQ Comments', '2', '0', '0', '0', '2', '7')");
        }
    }
}

class configSelect {

    var $langArray = array("english", "dutch", "french", "german", "norwegian", "swedish", "spanish", "portuguese", "elvish");
    var $barColorArray = array("blue", "red", "gold", "yellow", "aqua", "brown", "green", "grey", "orange", "pink", "purple");
    var $dateFormatArray = array("M-d-Y" =>  "Jan-1-2000", "m-d-Y" => "1-1-2000", "Y-m-d" => "2000-1-1");
    var $headlineArray = array("Show News", "Show Headlines");
    var $id, $options;
    var $selectName, $displayName;
    var $null, $type;
    var $arrayName;

    function configSelect($id, $displayName, $selectName, $null){
        $this->id = $id;
        $this->displayName = $displayName;
        $this->selectName = $selectName;
        $this->null = $null;
        global $menuTypeArray;
        switch($selectName){
            case dateFormat:
                $this->arrayName = $this->dateFormatArray;
                $this->dateSelect();
                break;
            case lang:
                $this->arrayName = $this->langArray;
                $this->langSelect();
                break;
            case bar:
                $this->arrayName = $this->barColorArray;
                $this->langSelect();
                break;
            case menu:
                $this->arrayName = $menuTypeArray;
                $this->dateSelect();
                break;
            case theme:
                $this->themeSelect();
                break;
            case headlines:
                $this->arrayName = $this->headlineArray;
                $this->dateSelect();
                break;
            case items_per_page:
                $this->newsSelect();
                break;
            case numberOfNews:
                $this->newsSelect();
                break;
            case timeZone:
                $this->timeSelect();
                break;
        }
    }

    function newsSelect(){
        for($x=5; $x < 26; $x+=5){
            if ($x == $this->id){ $text .= "<option value=$x selected>$x</option>"; }
            else { $text .= "<option value=$x>$x</option>"; }
        }
        $this->options = $text;
    }

    function timeSelect(){
        for($x='-12'; $x < 13; $x++){
            if ($x == 0){ $s = "GMT"; }
            else if ($x > 0){ $s = "GMT +$x"; }
            else { $s = "GMT $x"; }

            if ($x == $this->id){ $text .= "<option value=$x selected>$s</option>"; }
            else { $text .= "<option value=$x>$s</option>"; }
        }
        $this->options = $text;
    }

    function langSelect(){
        asort($this->arrayName);
        foreach($this->arrayName as $k){
            $value = ucfirst($k);
            if ($k == $this->id){ $text .= "<option value='$k' selected>$value</option>"; }
            else { $text .= "<option value='$k'>$value</option>"; }
        }
        $this->options = $text;
    }

    function dateSelect(){
        asort($this->arrayName);
        $keys = array_keys($this->arrayName);
        foreach($keys as $k){
            $value = $this->arrayName[$k];
            if ($k == $this->id){ $text .= "<option value='$k' selected>$value</option>"; }
            else { $text .= "<option value='$k'>$value</option>"; }
        }
        $this->options = $text;
    }

    function themeSelect(){
        $result = mysql_query("select theme_id, theme_name from theme order by theme_name");
        while(list($theme_id, $theme_name) = mysql_fetch_row($result)){
            if ($theme_id == $this->id){ $text .= "<option value='$theme_id' selected>$theme_name</option>"; }
            else { $text .= "<option value='$theme_id'>$theme_name</option>"; }
        }
        $this->options = $text;
    }

    function stroke(){
         if ($null == 1){ $options = "<option value=''></option>" . $this->options; }
         else { $options = $this->options; }
         $text = "<tr><td class=mainbold align=right>" . $this->displayName . ": </td>";
         $text .= "<td class=main><select name=" . $this->selectName . ">$options</select></td></tr>";
         return $text;
    }

}

class databaseMaint {

    function databaseMaint($userinfo){
        $this->action = $_GET['subaction'];
        $this->core = new coreFunctions();
        $this->userinfo = $userinfo;

        switch($this->action){
            case backup:
                $this->createBackup();
                break;
            case restore:
                $this->restoreBackup();
                break;
            case upgrade:
                new xUpgrade();
                break;
            default:
                if ($_GET['flag'] == 1){
                    $header = $this->createHeader();
                    $footer = $this->createFooter();
                    $text = "<tr><td class=main>Database Optimized<br>You should do this once a month to keep your";
                    $text .= " site running in tip top shape.</td></tr>";
                    $this->body = $header . $text . $footer;
                }
                else {
                    $this->core->createProgressPage();
                    $this->cleanForumSearch();
                    $this->fixNews();
                    $this->core->syncForums();
                    $this->optimize();
                    $this->core->addLog("Database Maint", $this->userinfo[1], '');
                    print "<script langauge=javascript>window.location = 'config.php?action=database&flag=1'; </script>";
                }
        }

    }

    function optimize(){
        global $database;
        $tables = mysql_list_tables($database);
        while (list($table_name) = mysql_fetch_array($tables)) {
            mysql_query("optimize table $table_name");
        }
    }

    function createBackup(){
        global $database;
        $tables = mysql_list_tables($database);
        while(list($table_name) = mysql_fetch_array($tables)){
            $text .= $this->getTable($table_name, "\r\n");
            $text .= $this->getTableContent($table_name);
        }
        $this->core->addLog("Database Backup Created", $this->userinfo[1], '');
        $writefile = "logs/database.dmp";
        if (file_exists($writefile)){ unlink($writefile); }
        $fp = fopen($writefile, 'w');
        fwrite( $fp, $text);
        fclose($fp);
        $this->core->addLog("Database Backup Created", $this->userinfo[1], '');
        header("Location: $writefile");


    }

    function restoreBackup(){
        if ($_POST['confirm'] == 1){
            $this->core->createProgressPage();
            $check = $_FILES['file'][tmp_name];
            if (!$check){ header("Location: config.php?action=restore&code=1"); }
            $sql_query='';
            $check = file($check);
            foreach($check as $line){
                $sql_query .= $line;
            }
            $sql_query = $this->removeComments($sql_query);
            $pieces = $this->explodeSQL($sql_query, ";");
            $sql_count = count($pieces);
            for($i = 0; $i < $sql_count; $i++){
                $sql = trim($pieces[$i]);
                //print "$sql<br>";
                if(!empty($sql) and $sql[0] != "#"){ $result = mysql_query($sql) or die(mysql_error()); }
            }
            $this->core->addLog("Database Restored", $this->userinfo[1], '');
            $this->core->syncForums();
            print "<script language=javascript>window.location = 'config.php?action=database&subaction=restore&code=2';</script>";
        }
        else {
            $code = $_GET['code'];
            $header = $this->createHeader();
            $footer = $this->createFooter();
            $text .= "<form method=post action=config.php?action=database&subaction=restore enctype='multipart/form-data' onsubmit=\"return validateForm(this)\" name=restore>";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>SQL File: </td><td class=main><input type=file name=file size=25></td></tr>";
            if ($code == 1){ $text .= "<tr><td class=attn2 colspan=2>file did not upload.  check your php.ini file.</td></tr>"; }
            else if ($code == 2){ $text .= "<tr><td class=attn2 colspan=2>database restore complete.</td></tr>"; }
            $text .= "<tr><td class=main align=center colspan=2><input name=submit type=submit value='Execute SQL'></td></tr>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.file,\"File\"))";
            $text .= "        return false;";
            $text .= "    submitOnce();";
            $text .= "}";
            $text .= "</script>";
            $this->body = $header . $text . $footer;
        }
    }


    function cleanForumSearch(){
        $time = time() - 2592000;
        $result = mysql_query("select search_id from forums_search_control where date < '$time'");
        while(list($search_id) = mysql_fetch_row($result)){
            mysql_query("delete from forums_search_control where search_id = '$search_id'");
            mysql_query("delete from forums_search where search_id = '$search_id'");
        }
    }

    function fixNews(){
        list($user_id) = mysql_fetch_row(mysql_query("select user_id from users where admin = '1' order by user_id desc limit 0,1"));
        mysql_query("update news set user_id = '$user_id' where user_id = '0'");

        $result = mysql_query("select news_id, post, more from news");
        while(list($id, $post, $more) = mysql_fetch_row($result)){
            $len = strlen($post) + strlen($more);
            mysql_query("update news set size = '$len' where news_id = '$id'");
        }
    }

    function createHeader(){

        $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=500 align=center>";
        $text .= "<tr>";
        $text .= "<td class=title colspan=2>Database Functions</td>";
        $text .= "</tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        return $text;
    }

    function createFooter(){
        $text .= "</table></td></tr></table>";
        return $text;
    }

    function getTable($table){

        $crlf = "";
        $schema_create = "";
        $field_query = "SHOW FIELDS FROM $table";
        $key_query = "SHOW KEYS FROM $table";
        $schema_create .= "DROP TABLE IF EXISTS $table;$crlf";
        $schema_create .= "CREATE TABLE $table($crlf";
        $result = mysql_query($field_query);



        while ($row = mysql_fetch_array($result)){
            $schema_create .= '	' . $row['Field'] . ' ' . $row['Type'];
            if(!empty($row['Default'])){ $schema_create .= ' DEFAULT \'' . $row['Default'] . '\''; }
            if($row['Null'] != "YES"){ $schema_create .= ' NOT NULL'; }
    		if($row['Extra'] != ""){ $schema_create .= ' ' . $row['Extra'];	}
    		$schema_create .= ",$crlf";
        }
        $schema_create = ereg_replace(',' . $crlf . '$', "", $schema_create);

        $result = mysql_query($key_query);

        while($row = mysql_fetch_array($result)){
            $kname = $row['Key_name'];
            if(($kname != 'PRIMARY') && ($row['Non_unique'] == 0)){ $kname = "UNIQUE|$kname"; }
            if(!is_array($index[$kname])){ $index[$kname] = array(); }
            $index[$kname][name] = $row['Column_name'];
            $index[$kname][type] = $row['Index_type'];
        }

        while(list($x, $columns) = @each($index)){
            $schema_create .= ",";
            if($x == 'PRIMARY'){ $schema_create .= ' PRIMARY KEY (' . $columns[name] . ')'; }
            else if (substr($x,0,6) == 'UNIQUE'){ $schema_create .= '	UNIQUE ' . substr($x,7) . ' (' . $columns[name] . ')'; }
            else if ($columns[type] == "FULLTEXT"){ $schema_create .= "FULLTEXT KEY $x (" . $columns[name] . ")"; }
            else { $schema_create .= "	KEY $x (" . $columns[name] . ')'; }
        }

        $schema_create .= ");\r\n";
        if(get_magic_quotes_runtime()){ return(stripslashes($schema_create)); }
        else { return($schema_create); }
    }

    function getTableContent($table){
        $result = mysql_query("SELECT * FROM $table");
        if ($row = mysql_fetch_array($result)){
            $field_names = array();
            $num_fields = mysql_num_fields($result);
            $table_list = '(';

            for ($j = 0; $j < $num_fields; $j++){
                $field_names[$j] = mysql_field_name($result, $j);
                $table_list .= (($j > 0) ? ', ' : '') . $field_names[$j];
            }
            $table_list .= ')';

            do {
                $schema_insert = "INSERT INTO $table $table_list VALUES(";
                for ($j = 0; $j < $num_fields; $j++){
                    $schema_insert .= ($j > 0) ? ', ' : '';
                    if(!isset($row[$field_names[$j]])) { $schema_insert .= 'NULL'; }
                    else if ($row[$field_names[$j]] != ''){ $schema_insert .= '\'' . addslashes($row[$field_names[$j]]) . '\''; }
                    else { $schema_insert .= '\'\''; }
                }
                $schema_insert .= ");\r\n";
                $text .= $schema_insert;
            }
            while ($row = mysql_fetch_array($result));
        }
        return $text;
    }

    function explodeSQL($sql, $delimiter){
        $tokens = explode($delimiter, $sql);

        $sql = "";
        $output = array();
        $matches = array();

        $token_count = count($tokens);
        for ($i = 0; $i < $token_count; $i++){
            if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))){
                $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
                $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
                $unescaped_quotes = $total_quotes - $escaped_quotes;
                if (($unescaped_quotes % 2) == 0){
                    $output[] = $tokens[$i];
                    $tokens[$i] = "";
                }
                else {
                    $temp = $tokens[$i] . $delimiter;
                    $tokens[$i] = "";
                    $complete_stmt = false;
                    for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++){
                        $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                        $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
                        $unescaped_quotes = $total_quotes - $escaped_quotes;
                        if (($unescaped_quotes % 2) == 1){
                            $output[] = $temp . $tokens[$j];
                            $tokens[$j] = "";
                            $temp = "";
                            $complete_stmt = true;
                            $i = $j;
                        }
                        else {
                            $temp .= $tokens[$j] . $delimiter;
                            $tokens[$j] = "";
                        }
                    }
                }
            }
        }
        return $output;
    }

    function removeComments($sql){
        $lines = explode("\n", $sql);
        $sql = "";
        $linecount = count($lines);
        $output = "";
        for ($i = 0; $i < $linecount; $i++){
            if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)){
                if ($lines[$i][0] != "#"){ $output .= $lines[$i] . "\n"; }
                else { $output .= "\n"; }
                $lines[$i] = "";
            }
        }
        return $output;
    }
}


class xUpgrade {

    function xUpgrade(){



    }
}





