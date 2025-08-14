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
    if (!session_is_registered("s_user") || !session_is_registered("s_user_type")) {
    		msgBox ("1", "../", "Sécurité !  <br><br>Une erreur de session est survenue !",
            "Sécurité !", "error",
            "<< Retour", "../index.php");
            die;
    }   
    if ($s_user_type != "GODLIKE") { // should never happened
    		msgBox ("1", "../", "Sécurité !  <br><br>Une erreur de session est survenue !",
            "Sécurité !", "error",
            "<< Retour", "../index.php");
            die;
    }
    
    if ($dID != "" && antiHack($dID,"integer") == "error") {	// session hacking / error coming for the first time
        if (!session_is_registered("s_previous_page")) {
    		msgBox ("1", "../", "Sécurité !  <br><br>Une erreur de session est survenue !",
            "Sécurité !", "error",
            "<< Retour", "../index.php");
            die;
        }
    }
    
    // === END of AntiHack check ===
    
    $conn  = new Database();  
    $conn2 = new Database();  
        
    // Query and paging step 
    $whole_users = getEverybodyList($conn, $conn2); // returns all non GODLIKE users
    
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
    
    $all_domains = array();
    $all_domains = getDomainsList($conn); // all domains have to be displayed even if user is not belonging to ;)
    
    
    if ($usr_action == "EDIT") {
        $current_user = new User($uID, "", "", "");
        $current_user->loadUserData($conn, $conn2);
        
        $usr_id = $current_user->getUserId();
        $usr_firstname = $current_user->getUserFirstname();     
        $usr_name = $current_user->getUserName();
        $usr_login = $current_user->getUserLogin();
        $usr_passwd = $current_user->getUserPasswd();
        $usr_email = $current_user->getUserEmail();
        $usr_maxct = $current_user->getUserMaxContacts();
        $usr_lang = $current_user->getUserLanguage();
        $usr_godlike = $current_user->getUserGodlike();
        
        $usr_domains = array();
        $usr_domains = $current_user->getUserDomains(); // with this information, we have everything to display cleanly domains in user form

        for ($i = 0 ; $i < count($all_domains) ; $i++) {                       // all available domains
            for ($j = 0 ; $j < count($usr_domains) ; $j++) {                   // all user's domains
                if ($all_domains[$i]["id"] == $usr_domains[$j]["id"]) {        // if matching...
                    $all_domains[$i]["display"] = $usr_domains[$j]["right"];   // create a "display" value used for display in tmpl
                }
            }
        }

        reset($all_domains);
        reset($usr_domains);       
                    
        unset($usr_action);
    }

    // cleaning $whole_users list for correct display
    include ("templates/users.tmpl");
    
    $conn->close();
    $conn2->close();
    
    
    
    
    
    
    
  
?>