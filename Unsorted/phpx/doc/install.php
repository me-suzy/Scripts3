<?php
#$Id: install.php,v 1.12 2003/10/29 19:18:44 ryan Exp $
class install {

    function install(){

        $this->confirm = $_POST['confirm'];
        $this->action = $_POST['action'];

        switch($this->action){
            case install:
                $this->newInstall();
                break;
            case upgrade:
                $this->upgrade();
                break;
            default:
                $this->defaultPage();
        }
        $this->stroke();
    }

    function stroke(){

        $this->createPageHeader();
        print($this->body);
        $this->createPageFooter();


    }

    function connectdb() {
        require_once('../admin/includes/config.inc.php');
        mysql_connect("$dbserver", "$dbuser", "$dbpass") or die("Cannot Connect to Database");
        mysql_select_db("$database") or die("Cannot Select to Database");
    }

    function handleErrors($error){

        print "<font color=red face=arial>An error Occured.</font><font face=arial><br><br>$error</font>";
        die();
    }

    function createPageHeader(){

        $text = "<html><head><title>Install/Upgrade PHPX</title>";
        $text .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"../admin/templates/style.css\">";
        $text .= "</head><body>";
        $text .= "<table class=outline border=0 cellpadding=0 cellspacing=1 width=500 align=center>";
        $text .= "<tr>";
        $text .= "<td class=title colspan=2>Install/Upgrade PHPX</td>";
        $text .= "</tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        print($text);

    }

    function createPageFooter(){

        $text .= "</table></td></tr></table></body></html>";
        print($text);

    }

    function newInstall(){
        if ($this->confirm == 1){
            //test connection
            $this->connectdb();
            $text = "<tr><td class=main>Database connection appears to be working correctly<br><br></td></tr>";
            $phpv = phpversion();
            $text .= "<tr><td class=main><b>PHP Version : $phpv</b><br>";
            if ($phpv < '4.1.2'){ $text .= "<i>You should upgrade to at least version 4.1.2 in order to get the full use and security from this system.</i>"; }
            $text .= "</td></tr>";
            $version = mysql_get_server_info();
            $text .= "<tr><td class=main><b>Mysql Version : $version</b><br>";
            if ($version < '3.23.23'){ $text .= "<i>Your version of MySQL is very out of date.  This install has failed.  Please upgrade to at least version 3.23.23.</i><br><br>"; $this->error=1; }
            if ($version < '4.0.0'){ $text .= "<i>In order to use the boolean search methods, please upgrade MySQL to version 4.0.12 or better.</i><br><br>"; }
            $text .= "</td></tr>";
            if ($this->error != 1){ $text .= $this->createForm($this->action, 2); }
            else { $text .= "<tr><td class=mainbold align=center>INSTALL FAILED</td></tr>"; }
        }
        else {
            $this->connectdb();
            require("install-sql.php");
            foreach($SQL as $q){
                mysql_query($q) or $this->handleErrors(mysql_error());
            }
            $this->sendEmail("install");
            $text = "<tr><td class=main>Install complete.  Please delete this file at once to avoid a security risk<br>You may now login <a href=../admin>HERE</a> using admin as both your password and username.</td></tr>";
        }
        $this->body = $text;
    }

    function upgrade(){
        $this->connectdb();
        require_once("version.php");
        list($version) = mysql_fetch_row(mysql_query("select version from config where config_id = '1'"));
        if ($version < '3.2.0'){ die("You must upgrade to version 3.2.0 then upgrade to this version"); }

        foreach($installArray as $ia){
            if ($version < $ia){
                $fileName = $ia . "-upgrade.php";
                require($fileName);
                foreach($SQL as $q){
                    mysql_query($q) or $this->handleErrors(mysql_error());
                }
            }
        }
        mysql_query("update config set version = '$install_version'");
        $this->sendEmail("upgrade");
        $text = "<tr><td class=main>Upgrade complete.  Please delete this file at once to avoid a security risk</td></tr>";
        $this->body = $text;
    }

    function createForm($action, $confirm){
        $text = "<tr><td align=center class=main><form method=post action=install.php>";
        $text .= "<input type=hidden name=confirm value=$confirm><input type=hidden name=action value=$action>";
        $text .= "<input type=submit value='Continue'></form></td></tr>";
        return $text;
    }

    function defaultPage(){

        $text = "<tr><td class=main>";
        $text .= "Welcome to PHPX.<br><br>This installer will walk you through the process to install PHPX or upgrade, depending on your situation.</td></tr>";

        if (is_file("../admin/logs/event.log")){
            $action = "upgrade";
            $text .= "<tr><td class=main>I have detected that you have a previous version of PHPX installed.  If this is incorrect";
            $text .= ", <b>STOP</b> and contact support at <a href=http://www.phpx.org class=links>www.phpx.org</a> and see what may have gone wrong.<br>";
            $text .= "If you are upgrading, make sure the following directories have been set to 777 permissions (chmod) <li>admin/logs<li>images<li>images/avatars<li>images/other<li>images/smiles<li>images/flags<li>doc<br>";
            $text .= "to add forums and users to your site, you will need to create menu items.  The pages are forums.php for the forums, users.php for users, and users.php?action=login for login.  Login is also on the forums page.<br>";
            $text .= "please procede using the button below.</td></tr>";
        }
        else {
            $action = "install";
            $text .= "<tr><td class=main>I have detected that you are doing a fresh install of PHPX.  If this is incorrect";
            $text .= ", <b>STOP</b> and contact support at <a href=http://www.phpx.org class=links>www.phpx.org</a> and see what may have gone wrong.</td></tr>";
            $text .= "<tr><td class=main>Please follow these steps before continuning to the next page.<br><br>";
            $text .= "<li>Go into admin/includes and open config.inc.php.  Complete this file by changing the variables to what you need them to be.";
            $text .= "<li>You will need to chmod the following directories to 777: <li>admin/logs<li>images<li>images/avatars<li>images/other<li>images/smiles<li>images/flags<li>doc";
            $text .= "<li>in .htaccess, you will probally want to change the path to your local path so the error handlers will work. ";
            $text .= "<li>Create a database and call it whatever you want.  Make sure it is the same database you specified in config.inc.php and make sure the user has full access to it that you specified in config.inc.php.";
            $text .= "<br>Once this is all complete, please continue to the next page by pressing the button below.</td></tr>";
        }
        $text .= $this->createForm($action, 1);
        $this->body = $text;
    }

    function sendEmail($action){
        $headers = "From: PHPX Registration <register@phpx.org> \r\n";
        $headers .= "X-Sender: <register@phpx.org>\r\n";
        $headers .= "X-Mailer: PHP\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= "Reply-To: register@phpx.org\r\n";
        $http = "http://" . $_SERVER["HTTP_HOST"];
        mail("register@phpx.org", "PHPX Registration", "$http \r\n$action", $headers);
    }
}

new install();






