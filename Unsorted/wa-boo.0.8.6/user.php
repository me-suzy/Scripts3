<?php
    /*
        contacts.php - contacts management
        by Roulaizze <aaaxl@nocive.com> & laGregouille <lagregouille@free.fr>
    */
    include ("includes/global_vars.php");    
    include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("classes/domain_class.php");
    include ("classes/contact_class.php");
    include ("includes/fotools.php");

    
	session_start();
	
/*    
    if ($s_previous_page != "contacts.php" && $s_previous_page != "user.php" && $s_previous_page != "user_email_action.php") {  // URL hacking / error
		msgBox ("1", "", "Sécurité !  <br><br>Impossible d'accéder directement à cette page !<br> Merci de ne pas l'enregistrer dans vos favoris / signets",
            "Sécurité !", "error",
            "<< Login", "index.php");
            die;
    }
*/
    if (!session_is_registered("s_user") || !session_is_registered("s_previous_page")) {  // session hacking / error
        msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
    
    
    $conn =  new Database();
    $conn2 = new Database();
    
    $user_membership = getUserMembership($s_user->getUserId()); // returns array of everything needed to display 

    if ($account_whichaction == "SAVE") {
        if ($passwd != $confirm) {
            msgBox ("1", "", $s_boxLab[20] /* Password and confirmation doesn't match ! */, 
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[5] /* << Back */, "user.php");
                die;
        }
        
        if ($login == "" || $passwd == "" || $email == "") {
            msgBox ("1", "", $s_boxLab[21] /* Login, password and email are required ! */, 
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[5] /* << Back */, "user.php");
                die;
        }
        
        $s_user->setUserEmail($email);
        $s_user->setUserLogin($login);
        $s_user->setUserPasswd($passwd);
        
        if ($s_user->isExistingLogin($conn) == false) { 
            $s_user->saveUserData($conn); 
        } else {
            msgBox ("1", "", $s_boxLab[22] /* This login is already used by someone else. Please choose another one ! */, 
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[5] /* << Back */, "user.php");
                die;  
        }                    
        unset($account_whichaction);     
        redirect("contacts.php");
    }
    
    $lab = loadTemplateLabels($s_lang, "user");
    include ("templates/user_form.tmpl");
    $s_previous_page = "user.php";
?>