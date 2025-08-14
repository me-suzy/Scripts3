<?php
    /*
    user_action.php
    by Roulaizze [aaaxl@nocive.com] & laGregouille [lagregouille@free.fr]
    */
    include ("includes/global_vars.php");
    include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("classes/contact_class.php");
    include ("includes/fotools.php");

	session_start();
	
    // === AntiHack check ===
    if (!session_is_registered("s_user")) {  
        msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
    if (($i_public !="" && antiHack($i_public,"integer") == "error") || ($i_private !="" && antiHack($i_private,"integer") == "error")) {
        msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
    
	// === End of AntiHack check ===
    
    $conn = new Database();
    
    if ($i_password != $s_user->getUserPasswd()) {
        msgBox ("1", "", $s_boxLab[42] /* Invalid password ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[5] /* << Back */, "cleaning.php");
            die;        
    }
    
    if ($i_public == "" && $i_private == "" && !count($i_domain)) {
        msgBox ("1", "", $s_boxLab[16] /* You selected nothing ! */,
            $s_boxLab[3] /* Error ! */, "error",
            $s_boxLab[5] /* << Back */, "cleaning.php");
            die;        
    }            
    
    $user_cat         = $s_user->getUserCategories();
    $user_domains     = $s_user->getUserDomains();                   // getUserDomains returns an array of array
    $id_domains_array = array();
    
    for ($i = 0 ; $i < count($user_domains) ; $i++) {
        $id_domains_array[] = $user_domains[$i]["id"];
    }

    // delete public contacts of current user
    if ($i_public == 1) {
        $result_public = $s_user->deletePublicContactsUser($conn) ."<br>";
    }
    
    // delete private contacts of current user with one or all cathegory
    if ($i_private == 1) {
        //echo "cat : " . $i_category;
        $result_private = $s_user->deletePrivateContactsUser($conn, $i_category) ."<br>";
    }
     
    // check that domains sended are user domains, if OK delete user's domain contacts
    if (count($i_domain)) {
        while (list($key, $value) = each($i_domain)) {  
            if (!in_array($value, $id_domains_array)) {
        		msgBox ("1", "", $s_boxLab[11] /* Error in selected groups ! */,
                    $s_boxLab[4] /* Security ! */, "error",
                    $s_boxLab[6] /* OK */, "index.php");
                    die;
            } else {
                $result_domain = $s_user->deleteDomainContactsUser($conn, $value); // delete all current domain's contacts belongin to user
            }
        }
        
    }
    
    // display if something has been deleted
    if ($result_public == 1 || $result_private == 1 || $result_domain == 1) { //at least one deletion
        $message = $s_boxLab[45] /* Selected contacts have been deleted successfully ! */;
    } else {
        $message = $s_boxLab[46] /* No contact match your selection. <br><br>The database wasn't altered */;
    }
    msgBox ("1", "",  $s_boxLab[43] /* Result : */ . "<br><br>" . $message,
            $s_boxLab[44] /* Success ! */, "exclamation",
            $s_boxLab[6] /* OK */, "contacts.php");
            die;
                       
?>