<?php
    /* 
    domains.php - users administration
    by Roulaizze [aaaxl@nocive.com] & laGregouille [lagregouille@free.fr]
    */
    include ("../includes/global_vars.php");
    include ("../includes/fotools.php");
    include ("includes/admtools.php");
    include ("../classes/database_class.php");
    include ("../classes/user_class.php");
    include ("../classes/domain_class.php");
    include ("../classes/paging_class.php");
    
    
    
    session_start();
    
    ?>
    
    <html>
        <head>
            <style><? include ("../includes/css.php"); ?></style> 
        </head>
    <body>
    
    <?
    
    // === AntiHack check ===
    if (!session_is_registered("s_previous_page") || !session_is_registered("s_user") || !session_is_registered("s_user_type")) {
    	msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    }   
    if ($s_user_type != "GODLIKE" && $s_user_type != "ADMIN") { // should never happened
    	msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    }
    if ($s_previous_page != "adm_index.php" && $s_previous_page != "adm_users.php" && $s_previous_page != "adm_users_action.php") { // should never happened
    	msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    }
    if ($dID != "" && antiHack($dID,"integer") == "error") {	// session hacking / error coming for the first time
    	msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    }
    
    // === END of AntiHack check ===
    
    $conn  = new Database();  
    $conn2 = new Database();  
    
    if (isset($dID)) {  // first time, have to set $s_admin_domain
        $s_admin_domain = new Domain($dID);
        $s_admin_domain->loadDomainData($conn);
    }
    
     
    if (!$s_user->isDomainAdminUser($conn, $s_admin_domain->getDomainId())) {
    	msgBox ("1", "../", "You don't have admin right on this group ",
            "Security", "error",
            "<< Back", "../index.php");
            die;
    }
    
    // Query and paging step 
    $whole_users = getFullNonAdminList($conn, $conn2); // returns all non GODLIKE users
    
    // init of paging system
    if( !isset( $current_position ) || $current_position == 0 ){ // init of position in paging system
      $current_position = 0;
    }
    if ($s_user_context["page_size"] == 0) { // Number of contacts to display on the page, according to user preferences. If 0, means to show all in one page ($size = $count_contact)
        if (count($whole_users) != 0) {
            $page_size = count($whole_users); // Number of contacts to display on the page, according to user preferences. If 0, means to show all in one page ($size = $count_contact)
        } else {
            $page_size = 1; // if no user in database, put to one to avoid paging class to divide by zero
        }
    } else {
        $page_size = $s_user_context["page_size"];
    }
 
    // paging system. Build 2 arrays for display and links
    $paging = new Paging(count($whole_users), $current_position, $page_size, $no_extra); // extra_arg not used
    $array_paging = $paging->getPagingArray();  // Load up the 2 array in order to display result in the .tmpl file
    $array_row_paging = $paging->getPagingRowArray();
    
    
    
    if ($usr_action == "EDIT") {
        $current_user = new User($uID, "", "", "");
        $current_user->loadUserData($conn, $conn2);
        
        
        if (!isUpdatableUser($conn, $conn2, $s_user->getUserId(), $current_user->getUserId()) ) {   // trying to EDIT an existing contact by an admin not authorized
    	msgBox ("1", "../", "You can't modify this contact !",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
        }
        
        //$current_user->loadUserData($conn, $conn2);             // settin' temp variables for display   
        $i_usr_id = $current_user->getUserId();
        $i_usr_firstname = $current_user->getUserFirstname();     
        $i_usr_name = $current_user->getUserName();
        $i_usr_login = $current_user->getUserLogin();
        $i_usr_passwd = $current_user->getUserPasswd();
        $i_usr_email = $current_user->getUserEmail();
        
        if ($current_user->isDomainUser($s_admin_domain->getDomainId()) == "USER") {
            $i_usr_domain = $s_admin_domain->getDomainId();
        }
            
        unset($usr_action);
    }

    // cleaning $whole_users list for correct display
    
    
        
        
    for ($i = 0 ; $i < count($whole_users) ; $i++) {
        $is_member = "NO";
        for ( $j = 0 ; $j < count($whole_users[$i]["domain"]) ; $j++ ) {
            if ($s_admin_domain->getDomainId() == $whole_users[$i]["domain"][$j]["dID"]) {    // have to know if the users belongs to $s_admin_domain domain
                $is_member = "YES";
            }
        }
        $whole_users[$i]["member"] = $is_member;
    }

    $s_previous_page = "adm_users.php";



    include ("templates/adm_users.tmpl");
    
    $conn->close();
    $conn2->close();

?>