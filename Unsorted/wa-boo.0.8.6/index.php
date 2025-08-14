<?php
    include ("includes/global_vars.php");
    include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("includes/fotools.php");
    
    global $G_default_langage;
    global $G_host_URL;
    global $G_email_header;
    global $G_email_footer;
    global $G_robot;    
    
    session_start();   
    session_destroy();
    
    //echo ($s_lang == "" ? "RIEN" : $s_lang);
    $s_lang == "" ? $s_lang = $G_default_langage : "";
    loadComboAndFields($s_lang); // load langage combos fields
    
    $conn  = new Database();
    $conn2 = new Database();

    // initializing the appearance before login
    //if (!session_is_registered ("s_user_context")) {
        $s_user_context["color"]["menu"]          = $G_user_menu_color;
        $s_user_context["color"]["button"]        = $G_user_button_color;
        $s_user_context["color"]["stdfontcolor"]  = $G_user_stdfont_color;
        $s_user_context["color"]["stdfontsize"]   = $G_user_stdfont_size;
        $s_user_context["color"]["listfontcolor"] = $G_user_listfont_color;
        $s_user_context["color"]["listfontsize"]  = $G_user_listfont_size;
    //}
    
    
    if ($OK == "OK") {
        $s_user = new User("",$login,$password,"");
        if ($s_user->checkUser($conn)) { // user exists 
            $s_user->loadUserData($conn, $conn2);
            $s_user->setUserSession_preferences($s_user->getUserPreferences());
            $s_user_context = $s_user->getUserSession_preferences();    // load user preferences & categories into context array
            
            $s_lang = $s_user->getUserLanguage();
            loadComboAndFields($s_lang);
            $s_boxLab = loadTemplateLabels($s_lang, "msgbox"); // load msg box labels in correct language
            
            // setting user color preferences
            $temp = $s_user->getUserColorPreferences();
            $s_user_context["color"]["menu"]          = $temp["menu"];
            $s_user_context["color"]["button"]        = $temp["button"];
            $s_user_context["color"]["stdfontcolor"]  = $temp["stdfontcolor"];
            $s_user_context["color"]["stdfontsize"]   = $temp["stdfontsize"];
            $s_user_context["color"]["listfontcolor"] = $temp["listfontcolor"];
            $s_user_context["color"]["listfontsize"]  = $temp["listfontsize"];
            

            session_register ("s_user", "s_user_context", "s_previous_page", "s_lang", "s_boxLab");
            
            $s_user->getUserRights();
            if ($adminlog == 1) { //admin check
                if ($s_user->getUserRights() == "GODLIKE" || $s_user->getUserRights($conn) == "ADMIN") { // user is Admin or GodLike
                    $s_user_type = $s_user->getUserRights();
                    $s_previous_page = "index.php";
                    session_register("s_user_type");
                    redirect("admin/adm_index.php");
                } 
            } else { // user 
                $s_domain_checkboxes = "INIT";    // just registering
                $s_previous_page = "index.php";
                session_register("s_domain_checkboxes");
                redirect("contacts.php");
           }
           $conn->close(); 
           $conn2->close(); 
           redirect("index.php");
        } else {
            $conn->close(); 
            $conn2->close(); 
            redirect("index.php");
        }
    }
    
    if ($public) {
        $s_user_type = "PUBLIC_USER";
        $s_previous_page = "index.php";
        session_register("s_user_type","s_previous_page");
        redirect("public.php");
    }   
    
    if ($lostpasswd) {
        $lab = loadTemplateLabels($s_lang, "lostpasswd");
        include ("templates/lostpasswd.tmpl");
        die;
    }   
    
    if ($sendpasswd) {
        $lab = loadTemplateLabels($s_lang, "lostpasswd");
        if ($checked_email = checkEmail($email)) {
            $lost_user = new User("","","",$email);
            if ($lost_user->checkUserWithEmail($conn)) {
                $lost_user->loadUserData($conn,$conn2);
                
                $email_subject = NoHTML_Spaces($lab[5] /*Vos login et mot de passe pour le service */ . $G_host_URL);
                $email_message = NoHTML_Spaces($G_email_header . $lab[6] /*Bonjour */ . $lost_user->getUserFirstname() . " " . $lost_user->getUserName() . "\n\n" . $lab[7] /* Voici vos login et mot de passe pour le service */ . $G_host_URL . " :\n\n" .  $lab[8] /* Login : */ . $lost_user->getUserLogin() . "\n" .  $lab[9] /* Mot de passe : */ . $lost_user->getUserPasswd() . "\n\n" . $G_email_footer);
                
                if (tuned_email($email, $email_subject, $email_message, $G_robot)) {  // syntax is $email_addresses_array, $s_email_subject, $s_email_message, from
                    
                    $result_message = $lab[10] /* Votre mot de passe a été envoyé à l'adresse : */ . $email;
                    $message_color = "#006600";
                } else {    
                    $result_message = $lab[11] /*Problème d'envoi de mail*/;
                    $message_color = "#FF0000";
                }  
            } else {
                $result_message  = $email . $lab[12] /*Cette adresse email ne correspond à aucun utilisateur référencé*/ . "<br>";
                //$result_message .= $email . $lab[13] /*est une adresse invalide :-(*/;
                $message_color = "#FF0000";
                include ("templates/lostpasswd.tmpl");
                die;
            }
        } else {
            $result_message .= $email . $lab[13] /*est une adresse invalide :-(*/;
            $message_color = "#FF0000";
            include ("templates/lostpasswd.tmpl");
            die;
        }
    }
    if (!$OK && !$lostpasswd) {
        $lab = loadTemplateLabels($s_lang, "login");
        include ("templates/login.tmpl");
        //include ("mytool.php");
    }
    $conn->close();
    $conn2->close();
?>