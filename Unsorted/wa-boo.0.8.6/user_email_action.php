<?php
    /*
    user_action.php
    by Roulaizze [aaaxl@nocive.com] & laGregouille [lagregouille@free.fr]
    */

    include ("includes/global_vars.php");
    include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("classes/domain_class.php");
    include ("classes/contact_class.php");
    include ("includes/fotools.php");

	session_start();
														
    // === AntiHack check ===
	if ($i_email_to_domain !="" && antiHack($i_email_to_domain,"integer")=="error") {
        msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }

    if (!session_is_registered("s_user") || !session_is_registered("s_previous_page")) {  // session hacking / error
        msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }

    if ($s_previous_page != "user.php" && $s_previous_page != "user_email_action.php") {  // URL hacking / error
		msgBox ("1", "", $s_boxLab[8] /* You can't access this page directly ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
    
    if ($usr_whichaction != "SENDMAIL" && $usr_whichaction != "CONFIRM") {
		msgBox ("1", "", $s_boxLab[9] /* Please do not play with URLs */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
	// === End of AntiHack check ===
	
    $conn =            new Database();
    $conn2 =           new Database();
    $s_previous_page = "user_email_action.php";
    
    if ($usr_whichaction == "SENDMAIL") {
        
        $s_email_to_domain = new Domain($i_email_to_domain);
        $s_email_to_domain->loadDomainData($conn);
        
        $s_email_subject = gpc_conditionnal_stripslashes(HTML_Quotes($i_email_subject));
        $s_email_message = gpc_conditionnal_stripslashes(HTML_Quotes($i_email_message));
        
        $display_email_message = gpc_conditionnal_stripslashes(HTML_Quotes(HTML_cr($i_email_message))); 
        $display_email_subject = gpc_conditionnal_stripslashes(HTML_Quotes($i_email_subject));
        
        session_register("s_email_subject", "s_email_message", "s_email_to_domain");
            
        if ($i_email_subject == "" || $i_email_message == "") {
            msgBox ("1", "", $s_boxLab[23] /* 'Subject' and 'Message' fields are both required */,
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[5] /* << Back */, "user.php");
                die;
        }
                    
        if (!$s_user->isDomainUser($i_email_to_domain)) {
            msgBox ("1", "", $s_boxLab[24] /* You can't send email to this group ! */,
                $s_boxLab[4] /* Security ! */, "error",
                $s_boxLab[6] /* OK */, "index.php");
                die;
        }
        
        if (!isset($i_email_to_domain)) {
            msgBox ("1", "", $s_boxLab[25] /* You have to select one group */,
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[5] /* << Back */, "user.php");
                die;
        } 
        
        if (trim($i_email_subject) != "" && trim($i_email_message != "")) {    
            
            $msgBox_msg  = $s_boxLab[26] /* Please confirm message delivery to group */ . $s_email_to_domain->getDomainName();
            $msgBox_msg .= "<br><br><hr>". $s_boxLab[47] /* OBJECT : */ . $display_email_subject . "<hr>" . $s_boxLab[48] /* MESSAGE : */ . $display_email_message;    
            
            msgBox ("2", "", $msgBox_msg,
                $s_boxLab[0] /* Confirm */, "exclamation",
                $s_boxLab[6] /* OK */, "user_email_action.php?usr_whichaction=CONFIRM",
                $s_boxLab[49] /* Cancel / Modify */, "user.php");
                die;            
        } 
    } 
    
    if ($usr_whichaction == "CONFIRM") {    // user has already CONFIRMED the "SENDMAIL" stage
        
        if (!session_is_registered("s_email_subject") || !session_is_registered("s_email_message") || !session_is_registered("s_email_to_domain")) {  // session hacking / error
            msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
                $s_boxLab[4] /* Security ! */, "error",
                $s_boxLab[6] /* OK */, "index.php");
        }    
      
        if (!$s_user->isDomainUser($s_email_to_domain)) {
            msgBox ("1", "", $s_boxLab[24] /* You can't send email to this group ! */,
                $s_boxLab[4] /* Security ! */, "error",
                $s_boxLab[6] /* OK */, "index.php");
                die;
        }            
        
        // have to clean (replace &quot; by \")  the 2 strings. (because we made inverse operation when building this session strings
        $s_email_subject = NoHTML_Quotes($s_email_subject);
        $s_email_message = NoHTML_Quotes($s_email_message);
        
        $s_email_message = $s_boxLab[50] /* From : */ . $s_user->getUserFirstname() . " " . $s_user->getUserName() . " [" . $s_user->getUserEmail() . "]\n\n". $G_email_header . $s_email_message . $G_email_footer;
        $domain_members = $s_email_to_domain->getDomainMembers();

        for ($i = 0 ; $i < count($domain_members) ; $i++) {
            $email_array[$i] = $domain_members[$i]["email"];
        }
        
        if (tuned_email($email_array, $s_email_subject, $s_email_message, $s_user->getUserEmail())) {
            msgBox ("1", "", $s_boxLab[27] /* Message sucessfully sent to group's member */,
                $s_boxLab[0] /* Confirm */, "exclamation",
                $s_boxLab[6] /* OK */, "contacts.php");

        } else {
            msgBox ("1", "", $s_boxLab[28] /* Unable to connect to SMTP server. The email was not sent ! */,
                $s_boxLab[3] /* Error ! */, "exclamation",
                $s_boxLab[5] /* << Back */, "contacts.php");

        }
        session_unregister("s_email_subject");
        session_unregister("s_email_message");
        session_unregister("s_email_to_domain");

        unset($usr_whichaction);
    }
?>