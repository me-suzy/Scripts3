<?php
    /* 
    domains.php - domains management
    by Roulaizze [aaaxl@nocive.com] & laGregouille [lagregouille@free.fr]
    */
    include ("../includes/global_vars.php");    
    include ("includes/admtools.php");
    include ("../classes/database_class.php");
    include ("../classes/user_class.php");
    include ("../classes/domain_class.php");
    include ("../includes/fotools.php");
    
    session_start();
    
    
    // === AntiHack check ===
    if (!session_is_registered("s_previous_page") || !session_is_registered("s_user") || !session_is_registered("s_user_type")) {
    	msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    } 
     
    if ($s_user_type != "GODLIKE") { // should never happened
    	msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    }
    
    if ($dID != "" && antiHack($dID,"integer") == "error") {	// session hacking / error 
    	msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    }
    
    // === END of AntiHack check ===
    
    $conn = new Database();  
    
    /*if (!$s_user->isDomainAdminUser($conn, $dID)) {
    	msgBox ("1", "../", "You are not an Administrator of this group !",
            "Error !", "error",
            "<< Back", "../index.php");
            die;
            
    }*/
    
    if (isset($action)) {   // all cases below are cases with action, means cases where not opening the straith url 'gdk_domains.php'
        $current_domain = new Domain($dID);           // $dID is "" or with a value
        if ($action == "INSERT" ) {
            if($name == "" || $misc == "") {
                msgBox ("1", "../", "All fields are required",
                    "Error !", "error",
                    "<< Back", "domains.php");
                    die;
            }    
            $current_domain->setDomainName($name);    // settin' properties
            $current_domain->setDomainMisc($misc);
            $current_domain->saveDomainData($conn);    // $current_domain->saveDomainData() will distinct itself cases create ($dID = "") or modify ($dID != "")
            unset($action);
            redirect("domains.php");
        } else if ($action == "MODIF") {
            $current_domain->loadDomainData($conn);                // lookin' for properties in database ($dID != "")
            $i_domain_name = $current_domain->getDomainName();     // settin' temp variables for display
            $i_domain_misc = $current_domain->getDomainMisc();
            unset($action); 
        } else if ($action == "DEL") {
            $current_domain->loadDomainData($conn);
            $answer = array();
            $answer["users"] = $current_domain->haveDependentUsers($conn);
            $answer["contacts"] = $current_domain->haveDependentContacts($conn);

            $msgBox_msg  = "Warning !!<br><br>Group : '" . $current_domain->getDomainName() . "' is used by : " . $answer["users"] . " users(s) and " . $answer["contacts"] . " contact(s). Do yo really want to delete it ?";
            $msgBox_msg .= "<br><br>Even if NO contact OR user will be deleted, you must know :<br><br>";
            $msgBox_msg .= "-> all group CONTACTS SHARING INFORMATION will be lost !!!<br>&nbsp;&nbsp;&nbsp;&nbsp;(contacts linked to this only group will become private contact of their owner)<br><br>";
            $msgBox_msg .= "-> all group MEMBER LIST will be destroyed forever !!!<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Mr. GODLIKE will have to handly re-build it if needed)<br><br>";  
            $msgBox_msg .= "-> all group ADMIN LIST will be destroyed forever !!!<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Mr. GODLIKE will have to handly re-build it if needed)";  
            msgBox ("2", "../", $msgBox_msg, 
                "Confirm", "exclamation", 
                "Delete group", "domain_action.php?action=DEL&dID=" . $dID, 
                "<< Cancel and Back", "domains.php");
                die;  

        } else if ($action == "showusers") {
            $current_domain->loadDomainUsers($conn); 
            $domain_users = array();
            $domain_users = $current_domain->getDomainUsers($conn); 
            
        }
    }

    $domains_list = array();
    $domains_list = getDomainsList($conn);
    

    include ("templates/domains.tmpl");
?>

<?
    $conn->close();
?>