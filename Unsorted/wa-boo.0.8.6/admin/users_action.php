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
    if (!session_is_registered("s_user") || !session_is_registered("s_user_type")) {
    		msgBox ("1", "../", "Session error !",
            "Sécurité !", "error",
            "<< Back", "../index.php");
            die;
    }   
    if ($s_user_type != "GODLIKE") { // should never happened
    		msgBox ("1", "../", "Session error !",
            "Sécurité !", "error",
            "<< Back", "../index.php");
            die;
    }

    if ($dID != "" && antiHack($uID,"integer") == "error") {	// session hacking / error 
    		msgBox ("1", "../", "Session error !",
            "Sécurité !", "error",
            "<< Back", "../index.php");
            die;
    }
    
    
    // === END of AntiHack check ===
 
    
    $conn  = new Database();  
    $conn2 = new Database(); 

    if (isset($usr_action)) {
        $current_user = new User($uID, "", "", "");
        $current_user->loadUserData($conn, $conn2); //necessary to have user domains.. updated below
        
        //echo $current_user->getUserFirstname();
        
        
        if ($usr_action == "DEL") { 
            unset($usr_action);
            if($current_user->getUserId() == $s_user->getUserId()) {
                msgBox ("1", "../" , "Sorry but you can't commit sucide &nbsp; &nbsp; &nbsp; :o)",
                    "Serial Killer", "exclamation",
                    "OK", "users.php?current_position=" . $current_position);
                    die; 
            }   

            if ($i_confirm != "YES") {
                if ($nb_attached_contacts = $current_user->haveDependentContacts($conn)) {
                    $more_info_str = "<br><br>Warning : this user have " . $nb_attached_contacts . " contacts which will be DELETED with him !!";
                }
                $msgBox_msg = "Do you really want to DELETE :<br>" . $current_user->getUserFirstname() . " " . $current_user->getUserName() . " ?" . $more_info_str;
                msgBox ("2", "../", $msgBox_msg,
                    "Confirm", "question",
                    "Delete !", "users_action.php?uID=" . $uID. "&usr_action=DEL&i_confirm=YES",
                    "<< Cancel", "users.php?i_confirm=YES&current_position=" . $current_position);
                    die;
              
            } elseif ($i_confirm == "YES") { 
                $first_and_name = $current_user->getUserFirstname() . " " . $current_user->getUserName();
                $current_user->deleteUser($conn);                 // ask method to do the bad job ;-)
                msgBox ("1", "../" , "User '" . $first_and_name . "' and all his stuff have been murdered succesfully :-)",
                    "Serial Killer", "exclamation",
                    "OK", "users.php?current_position=" . $current_position);
                    die; 
            }
       

        } elseif ($usr_action == "SAVE") { 
            
            if ($i_usr_firstname == "" || $i_usr_name == "" || $i_usr_login == "" || $i_usr_passwd == "" || $i_usr_email == "" ) {
            	msgBox ("1", "../", "All fields must be filled !",
                    "Error !", "error",
                    "<< Back", "users.php?current_position=" . $current_position);
                    die;
            }
            
            if (!checkEmail($i_usr_email)) {
            	msgBox ("1", "../", "Invalid email address",
                    "Error !", "error", 
                    "<< Back", "users.php?current_position=" . $current_position);
                    die;
            }
            
            if (antiHack($i_usr_maxct,"integer") == "error" || $i_usr_maxct > 9999) {	// session hacking / error 
            		msgBox ("1", "../", "error in maxcontacts field !",
                    "Error !", "error", 
                    "<< Back", "users.php?current_position=" . $current_position);
                    die;
            }
            
            if ($current_user->isExistingLogin($conn)) { 
                msgBox ("1", "../", "This login already exists. Please choose another one !", 
                    "Error !", "error", 
                    "<< Back", "users.php?current_position=" . $current_position);
                    die;  
            }            
            
            // seting properties
            $current_user->setUserFirstname($i_usr_firstname);
            $current_user->setUserName($i_usr_name);
            $current_user->setUserLogin($i_usr_login);
            $current_user->setUserPasswd($i_usr_passwd);
            $current_user->setUserEmail($i_usr_email);
            
            $current_user->setUserMaxContacts($i_usr_maxct);
            $current_user->setUserLanguage($i_usr_language);
            
            $godlike = ($i_usr_godlike == 1 ? "YES" : "NO");
            $current_user->setUserGodlike($godlike);
            
            $current_user->setUserDomains($nothing); // now all user domains are deleted because $nothing is nothing :)
            
            if (count($i_usr_domains)) {
                while (list ($key, $value) = each ($i_usr_domains)) { // count(i_usr_domains) must equals the number of domains in base
                    if ($value == "ADMIN" || $value == "USER") { // only if something to write in the base (if no user, do not create next row !!!
                        $a = array();
                        $a["id"] = $key;
                        $a["right"] = $value;
                        //echo "|" . $key ."| -> |". $value  ."| <br>";
                        $temp_domains[] = $a;
                    }
                }
            }
            // domains are now ready to be set and saved
            $current_user->setUserDomains($temp_domains); 
            $current_user->saveUserData($conn);
              
            unset($usr_action);
            redirect("users.php?current_position=" . $current_position);
        }
 
    } 
?>