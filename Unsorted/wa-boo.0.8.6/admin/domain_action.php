<?php
    /* 
    domain_action.php 
    by Roulaizze [aaaxl@nocive.com] & laGregouille [lagregouille@free.fr]
    */
    
    //include ("includes/queries.inc");
    //include ("includes/admtools.inc");
    include ("../classes/database_class.php");
    include ("../classes/user_class.php");
    include ("../classes/domain_class.php");
    include ("../includes/fotools.php");
    
    session_start();
    
    if (!session_is_registered("s_user") || $s_user_type != "GODLIKE") {
    	msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    } 
    $conn = new Database();
    

    if (isset($action)) {   // all cases below are cases with action, means not arriving vith the clean url users.php
        $current_domain = new Domain($dID);                         
        if ($action == "DEL") {
            $current_domain->deleteDomain($conn);                 // ask method to do the bad job ;-) ($dID != "")
            unset($action);
            redirect("domains.php");
        } /*else if ($action == "INSERT") {
            $current_user->saveDomainData($conn);
            unset($action);
            redirect("domains.php");
        }*/
    }



?>