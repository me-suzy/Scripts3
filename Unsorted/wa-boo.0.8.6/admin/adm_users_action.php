<?php
    /* 
    domains.php - users administration
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
    if (!session_is_registered("s_previous_page") || !session_is_registered("s_user") || !session_is_registered("s_user_type") || !session_is_registered("s_admin_domain")) {
        msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    }   
    if ($s_user_type != "ADMIN") { // should never happened
    	msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    }
   
    if ($dID != "" && antiHack($uID,"integer") == "error") {	// session hacking / error 
		msgBox ("1", "../", "Session error",
            "Security !", "error",
            "<< Back", "../index.php");
            die;
    }
    
    if (!$s_user->isDomainAdminUser($conn, $s_admin_domain->getDomainId())) {
        msgBox ("1", "../", "You don't have admin right on this group ",
            "Security", "error",
            "<< Back", "../index.php");
            die;
    }
    
    // === END of AntiHack check ===
 
    
    $conn  = new Database();  
    $conn2 = new Database(); 
    $s_previous_page = "adm_users_action.php"; 

    if (isset($usr_action)) {
        $current_user = new User($uID, "", "", "");
        $current_user->loadUserData($conn, $conn2); //necessary to have user domains.. updated below
        
        //echo $current_user->getUserFirstname();
        
        
        if ($usr_action == "DEL") { 
            unset($usr_action);
            if (!isUpdatableUser($conn, $conn2, $s_user->getUserId(), $uID)) {
                msgBox ("1", "../", "You can't delete this user !",
                    "Error", "error",
                    "OK", "../index.php");
                    die;
            } else {
                if ($i_confirm != "YES") {
                    if ($nb_attached_contacts = $current_user->haveDependentContacts($conn)) {
                        $more_info_str = "<br><br>Warning !! This user have " . $nb_attached_contacts . " contacts that will be deleted with him !!";
                    }
                    $msgBox_msg = "Do you really want to delete this user ? :<br>" . $current_user->getUserFirstname() . " " . $current_user->getUserName() . " ?"
                        . $more_info_str;
                    msgBox ("2", "../", $msgBox_msg,
                        "Confirm", "question",
                        "Delete", "adm_users_action.php?uID=" . $uID. "&usr_action=DEL&i_confirm=YES",
                        "Cancel", "adm_users.php");
                        die;
                  
                } elseif ($i_confirm == "YES") { 
                    $current_user->deleteUser($conn);                 // ask method to do the bad job ;-)
                    msgBox ("1", "../" , "User deleted successfully !",
                        "Delete", "exclamation",
                        "OK", "adm_users.php");
                        die; 
                }
            }          
        


        } elseif ($usr_action == "SAVE") { 
            if (isset($uID) && $uID != "") {
                if (!isUpdatableUser($conn, $conn2, $s_user->getUserId(), $uID) ) {   // trying to SAVE an existing contact by an admin not authorized
                    msgBox ("1", "../", "You can't delete this user !",
                    "Error !", "error",
                    "OK", "../index.php");
                    die;
                }
            }
            
            if ($i_usr_firstname == "" || $i_usr_name == "" || $i_usr_login == "" || $i_usr_passwd == "" || $i_usr_confirm_passwd == "" || $i_usr_email == "" ) {
            	msgBox ("1", "../", "All fields are required",
                "Error !", "error",
                "<< Back", "adm_users.php");
                die;
            }
            
            if ($i_usr_passwd != $i_usr_confirm_passwd) {
            	msgBox ("1", "../", "password and its confirmation are not matching",
                "Error !", "error",
                "<< Back", "adm_users.php");
                die;
            }
            
            if (!checkEmail($i_usr_email)) {
            	msgBox ("1", "../", "Invalid email address",
                "Error !", "error",
                "<< Back", "adm_users.php");
                die;
            }
            
            // seting properties
            $current_user->setUserFirstname($i_usr_firstname);
            $current_user->setUserName($i_usr_name);
            $current_user->setUserLogin($i_usr_login);
            $current_user->setUserPasswd($i_usr_passwd);
            $current_user->setUserEmail($i_usr_email);

            if ($current_user->isExistingLogin($conn)) { 
                msgBox ("1", "../", "This Login already exists<br><br>Please choose another one !", 
                        "Error !", "error", 
                        "<< Back", "adm_users.php");
                        die;  
            }            
            
            // update userDomains. Have to give ID, Name, Misc form current domain && boolean (checkbox state or action)
            $current_user->updateOneUserDomain($s_admin_domain->getDomainId(), $s_admin_domain->getDomainName(), $s_admin_domain->getDomainMisc(), isset($i_usr_domain));
            $current_user->saveUserData($conn);
              
            unset($usr_action);
            redirect("adm_users.php?current_position=" . $current_position);
        
    } elseif ($usr_action == "CHECK" || $usr_action == "UNCHECK") { 
            
            if (isset($uID) && $uID != "") {
                if (!isCheckableUser($conn, $conn2, $s_user->getUserId(), $uID, $s_admin_domain->getDomainId())) {
                msgBox ("1", "../", "Impossible to link / unlink this user !",
                    "Error !", "error",
                    "OK", "../index.php");
                    die;
                }
            }
            
            if ($usr_action == "UNCHECK" ) {
                if ($i_confirm != "YES") {
                    if ($nb_attached_contacts = $current_user->haveDependentContactsInDomain($conn, $s_admin_domain->getDomainId())) {
                        $more_info_str = "<br><br>Warning : all " . $nb_attached_contacts . " contacts,  currently visible by all members of group '" . $s_admin_domain->getDomainName() . "'<br> and created by " . $current_user->getUserFirstname() . " " . $current_user->getUserName() . " won't be visible anymore by the group's member  !!";
                    }
                    $msgBox_msg = "Do you really want to exclude user " . $current_user->getUserFirstname() . " " . $current_user->getUserName() . "<br>from group '" .$s_admin_domain->getDomainName() ."' ?" . $more_info_str;
                    msgBox ("2", "../", $msgBox_msg,
                        "Confirm", "question",
                        "Yes I Want", "adm_users_action.php?uID=" . $uID. "&usr_action=UNCHECK&i_confirm=YES&current_position=" . $current_position,
                        "<< Back", "adm_users.php");
                        die; 
                }  
            }
            
            if ($usr_action == "CHECK" ) {
                $s_admin_domain->addUserInDomain($conn, $current_user->getUserId());
            } else if ($usr_action == "UNCHECK") {
                $s_admin_domain->removeUserFromDomain($conn, $current_user->getUserId());
            }    
            $s_admin_domain->loadDomainData($conn); // refresh all domain data
            
                        
            /* // previous version.... Using user update.
                  current version is using domain update (better)    
            if ($usr_action == "CHECK" ) {
                $temp_select  = true;
            } else if ($usr_action == "UNCHECK") {
                $temp_select  = false;
            }       
            // update userDomains. Have to give ID, Name, Misc form current domain && boolean (checkbox state or action)
            
            // $current_user->updateOneUserDomain($s_admin_domain->getDomainId(), $s_admin_domain->getDomainName(), $s_admin_domain->getDomainMisc(), $temp_select);
            
            // HERE IS NOT VERY GOOD ;) WORKS BUT HAVE TO DO BETTER
            // because the loadDataUser method is mainly used for display and editing, and is doing a htmlentities() conversion
            // ---> we must : 1) convert user info to non HTML format
            //                2) save user Info
            //                3) reload user Info to be again in the main mode (display-edit to browser, means HTML)
            //                4) point should not seems to be required because $current_user is not in session and page is over (means a clean load will be done in next page...
            
            // tool for HTML->normal char conversion
            $transform = get_html_translation_table(HTML_ENTITIES);
            $transform = array_flip($transform);
            
            // 1) convert user properties to non HTML
            $current_user->setUserFirstname(strtr($current_user->getUserFirstname(), $transform));
            $current_user->setUserName(strtr($current_user->getUserName(), $transform));
            $current_user->setUserLogin(strtr($current_user->getUserLogin(), $transform));
            $current_user->setUserPasswd(strtr($current_user->getUserPasswd(), $transform));
            $current_user->setUserEmail(strtr($current_user->getUserEmail(), $transform));

            // 2) save User data
            $current_user->saveUserData($conn);
            
            // 3) reload // comment it ??
            $current_user->LoadUserData($conn, $conn2);
            
            */
            unset($usr_action);
            
            redirect("adm_users.php?current_position=" . $current_position);
        }
    } 
?>