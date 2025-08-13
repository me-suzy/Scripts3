<?php
# $Id: help.inc.php,v 1.1 2003/07/08 18:30:30 ryan Exp $
//! Controls the help scripts

require("includes/functions.inc.php");
require("includes/var.inc.php");

class helpDocs {

    function helpDocs($page){
        $this->action = $_GET['action'];

        if ($page == menu){
            switch($this->action){
                case search:
                    $this->getHelpSearch($search);
                    break;
                default:
                    $this->getHelpMenu();
            }
            $this->stroke(1);
        }
        else {
          $this->getHelp();
          $this->stroke(0);
        }

    }

    function stroke($helpMenu){
        $body = $this->body;
        if ($helpMenu == 1){ include("templates/helpMenu.inc.php"); }
        else { include("templates/help.inc.php"); }
    }



    function getHelpMenu(){
        //! creates help tree menu
        $menu = "var TREE_ITEMS = [";
        $menu .= "['PHPX Help', 'helpbody.php',";
        $result = mysql_query("select help_id, title from help where sub = '0' order by ord");

        while(list($help_id, $title) = mysql_fetch_row($result)){
            $title = addslashes($title);
            $menu .= "['$title', 'helpbody.php?help_id=$help_id', ";
            $result1 = mysql_query("select help_id, title from help where sub = '$help_id' order by ord");

            while(list($help_id, $title) = mysql_fetch_row($result1)){
                $title = addslashes($title);
                $menu .= "['$title', 'helpbody.php?help_id=$help_id', ";

                $result2 = mysql_query("select help_id, title from help where sub = '$help_id' order by ord");
                while(list($help_id, $title) = mysql_fetch_row($result2)){
                    $title = addslashes($title);
                    $menu .= "['$title', 'helpbody.php?help_id=$help_id'], ";
                }
                $menu .= "],";
            }
            $menu .= "],";
        }
        $menu .= "]];";

        $text = "<script language=JavaScript src=templates/helptree.js></script>";
        $text .= "<script language=javascript>";
        $text .= "$menu";
        $text .=  "	new tree (TREE_ITEMS, tree_tpl);";
        $text .= "</script>";
        $this->body = $text;
    }

    function getHelp(){
        //! creates text for help body, also highlights search entries
        $help_id = $_GET['help_id'];
        $search = $_GET['search'];
        if ($help_id != ''){
            $result = mysql_query("select sub, title, text from help where help_id = '$help_id'");
            list($sub, $title, $htext) = mysql_fetch_row($result);
            $name = stripslashes($title);
            $htext = stripslashes($htext);
            if (isset($search)){
                $replace = "<b><span class=highlight>" . ucfirst($search) . "</span></b>";
                $htext = eregi_replace($search, $replace, $htext);
            }

        }
        else {
            $name = "PHPX Help";
            $htext = "PHPX is a web-based application which allows users to create and manage their website including user and file management.";

        }

        $text .= "<table border=0 cellpadding=2 width=100% cellspacing=0>";
        $text .= "<tr><td class=title>&nbsp; $name</td>";
        $text .= "</tr></table></td></tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 width=100% cellspacing=0>";
        $text .= "<tr><td class=main>$htext</td></tr>";
        $text .= "</table></td></tr></table>";
        $this->body = $text;
    }

    function getHelpSearch(){
        //! displays help search box and results
        $search = $_POST['search'];
        if (isset($search)){ $search = stripslashes($search); }
        $text .= "<table border=0 cellpadding=2 width=100% cellspacing=0>";
        $text .= "<form method=post action=helpmenu.php?action=search>";
        $text .= "<tr><td class=mainbold>Search: </td><td class=main><input type=text value='" . $search . "' size=25 name=search></td></tr>";
        $text .= "<tr><td class=main colspan=2 align=center><input type=submit value=Search></td></form></tr>";
        $text .= "</table><br>";

        $text .= "<table border=0 cellpadding=2 width=100% cellspacing=1 class=bg>";
        $text .= "<tr><td class=title>&nbsp; Search Results</td></tr>";
        $text .= "<form name=f>";

        if ($search == '' || $search == null){
            $text .= "<tr><td class=main>Nothing to Display</td></tr>";
        }
        else {
            $search1 = trim($search, "\"");
            $text .= "<script language=javascript>";
            $text .= "function searchHandler(form) {";
            $text .= "var URL = form.page.options[form.page.selectedIndex].value;";
            $text .= "parent.body.location.href = \"helpbody.php?search=$search1&help_id=\" + URL; }";
            $text .= "</script>";
            $version = mysql_get_server_info();
            $version = substr($version, 0, 1);
            if ($version == "4"){
                $result = mysql_query("select help_id, title from help where match(text) against ('$search' in boolean mode) order by title");
            }
            else {
                $search1 = "%" . $search . "%";
                $result = mysql_query("select help_id, title from help where text like '$search1' order by title");
            }
            $count = mysql_num_rows($result);
            if ($count != 0){
                $count++;
                $text .= "<tr><td class=main><select size=$count name=page onClick=searchHandler(this.form)>";
                while(list($help_id, $title) = mysql_fetch_row($result)){
                    $text .= "<option value=$help_id>$title</option>";
                }
                $text .= "</select></td></tr>";
            }
            else {
                $text .= "<tr><td class=main><select size=2 name=page>";
                $text .= "<option value=0>No Results Found</option>";
                $text .= "</select></td></tr>";
            }
        }
        $text .= "</form>";
        $text .= "</table>";
        $this->body = $text;

    }
}


