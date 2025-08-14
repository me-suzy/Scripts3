<?php
    /*
        contacts.php - contacts management
        by Roulaizze <aaaxl@nocive.com> 
    */
    
    die ("E N&nbsp;&nbsp;&nbsp;&nbsp;C O N S T R U C T I O N");
    include ("includes/global_vars.php");
    include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("classes/contact_class.php");
    include ("classes/domain_class.php");
    include ("includes/fotools.php");
    
	session_start();
   
    // === AntiHack check ===
    if (!session_is_registered("s_previous_page") || !session_is_registered("s_user_type")) {
    		msgBox ("1", "", "Sécurité !  <br><br>Une erreur de session est survenue !",
            "Sécurité !", "error",
            "<< Retour", "index.php");
            die;
    }
    
	if ($cID != "" && antiHack($cID,"integer") == "error") {	// session hacking / error 
		msgBox ("1", "", "Sécurité !  <br><br>Une erreur de session est survenue !",
            "Sécurité !", "error",
            "<< Retour", "index.php");
            die;
    }
	// === End of AntiHack check ===
	
    $conn  = new Database();
    $conn2 = new Database();
    //echo $s_domain_checkboxes;
    

    
    if ($s_previous_page != "contacts.php" || ($s_previous_page == "contacts.php" && $i_using_chk != "YES")) { 
        
        // coming from (index or other pages) or from (contacts.php without use of the search button) , we assign checks from context
        // N.B. $s_user_context is loaded when coming from index.php and is reloaded after validation in user_properties.php
        
        //echo "case 1 : filling checkboxes with context values<br>";
        
        $i_chkPriCt = ($s_user_context[0] == "priCtOn" ? "priCtOn" : "" );  // check private contacts box if needed
        $i_chkPubCt = ($s_user_context[1] == "pubCtOn" ? "pubCtOn" : "" );  // check public contacts box if needed
        
        if ($s_domain_checkboxes == "INIT") { // fist time, coming from index.php 
            unset($s_domain_checkboxes);    // because $s_domain_checkboxes is a mirror of what di user with search engine, mirror nothing
            //echo "first time";
            if (count($s_user->getUserDomains()) && $s_user_context[2] == "domCtOn") { // user have selected check domains, and want them to be checked
                reset ($s_user->getUserDomains());
        	    while (list ($key, $value) = each ($s_user->getUserDomains())) {
        	        $i_chkDomains[$key] = $value["id"];                             // The first time, we set checkboxes with user prefs
        		}
            }
        } else { // other times, we use '$s_domain_checkboxes' info to set checkboxes
            //echo "other time<br>"; 
            if (is_array($s_domain_checkboxes)) {  //do the while only if at least one domain checkbox has been selected by user, to prevent warning of interpreter
                while (list ($key, $value) = each ($s_domain_checkboxes)) {
                    // echo "utilisation du tableau en session pour les checks HTML : " . $key . " , " . $value  . "<br>";
        	        $i_chkDomains[$key] = $value;                           // used to check domain checkboxes
        		}
    		}
        }
        
        $i_search_field = ($s_user_context[3] == "FIELD_NAME" ? "FIELD_NAME" : "FIELD_ALL" );
        $i_search_position = ($s_user_context[4] == "POSITION_START" ? "POSITION_START" : "POSITION_ANYWHERE" );
        $i_search_category = $s_user_context[5];   // prefered category
        
        
    } elseif ($s_previous_page == "contacts.php" && $i_using_chk == "YES") { 
        
        // normal case of form submission
        //echo "case 2 : context update with already checked checkboxes<br>";
        
        // first update context with public and private (i mean all non domain's checkboxes)   
        $s_user_context[0] = $i_chkPriCt;   
        $s_user_context[1] = $i_chkPubCt;
  		$s_user_context[3] = $i_search_field;   // note $s_user_context[2] is for domains, see below...
        $s_user_context[4] = $i_search_position;
        $s_user_context[5] = $i_search_category;
        // others item are not used in filter        
        $s_user->setUserSession_preferences($s_user_context);   // update context    

       // domains checkboxes turn...
        unset($s_domain_checkboxes);    // reseting to prevent bad  memories information
        if (is_array($i_chkDomains)) {   // do the while only if at least one domain checkbox has been selected by user, to prevent warning of interpreter
            while (list ($key, $value) = each ($i_chkDomains)) {
    	        $s_domain_checkboxes[$key] = $value;
  		    }
  		}

                         
    } else {    // impossible case ;)
		msgBox ("1", "", "Sécurité !  <br><br>Une erreur de session est survenue !",
            "Sécurité", "error",
            "<< Retour", "index.php");
            die;
    }
       
    // === Building usable list of required list fields === 
    $user_list_fields = $s_user->getUserListPreferences();
    $num_of_list_fields = 0;
    $cname_position = "NONE";
    for ($i = 0; $i < count($user_list_fields); $i++) {
        $temp = getFieldName($G_fields_labels, $user_list_fields[$i]);   // getFieldName is defined in fotools
        if ($temp != "") {
            if ($temp == "cname") {     // keep in mind position of cname field if exists, for UPPER display if needed ;)
                $cname_position = $i;
            }
            $query_list_fields .=  $temp . ", ";
            $num_of_list_fields++;
        }
        
    }
    $query_list_fields = substr($query_list_fields, 0, strlen($query_list_fields) - 2);

    // === Building usable list of required alt fields === 
    $user_alt_fields = $s_user->getUserAltPreferences();
    $num_of_alt_fields = 0;
    for ($i = 0; $i < count($user_alt_fields); $i++) {
        $temp = getFieldName($G_fields_labels, $user_alt_fields[$i]);  
        if ($temp != "") {
            $query_alt_fields .=  $temp . ", ";
            $num_of_alt_fields++;
        }
    }
    $query_alt_fields = substr($query_alt_fields, 0, strlen($query_alt_fields) - 2);
    $query_fields = $query_list_fields . ", " . $query_alt_fields;
    // === END of usable list of required list & alt fields  === 
    
    if (concatenate($i_chkDomains) != "") { // only set $query_domains if there is at least one domain
        $query_domains = concatenate($i_chkDomains);
    }
    $user_ID = $s_user->getUserId();
        
        
    if ($s_previous_page != "contacts.php") {   // if coming from other page than contacts.php, the first action is to list, not to show details
        $ct_whichaction = "LIST";
    }
     
    if ($ct_whichaction == "SHOWDETAILS") {     // if SHOWDETAILS needed, instanciate the Contact class
        $selected_contact = new Contact($cID);
        $selected_contact->loadContactData($conn);
        $selected_contact->getContactID();
        $user_owner = new User($selected_contact->getContactUserID(),"","","");
        $user_owner->loadUserData($conn, $conn2);
    }
 

    // buildQuery function will create the entire query that fits the needs   
    $sql = buildQuery("RETURN", "REGISTERED_USER", $user_ID, $query_fields, $i_chkPriCt, $i_search_category, $i_chkPubCt, $query_domains, $i_search_string, $i_search_position, $i_search_field, $i_search_letter);
    
	$conn->execQuery($sql);
	
	// === Building the contact array ===  
	$contact = array();
	$k = 0;
	while ($field = $conn->getNextRecord()) {
	    $contact[$k][0] = $field["cID"];
	    $contact[$k][1] = $field["uID_FK"];
        for ($m = 2 ; $m < $num_of_list_fields + $num_of_alt_fields + 2 ; $m++)	{    
		    if ($cname_position == ($m-2) && $s_user_context[6] == "UPPER_CASE") {  // if selected && asked in prefs, we upper the cname field 
		        $contact[$k][$m] = convertToUpperStringFromDB($field[$m]);          // very special cas, doesn't perfectly work if several cname selected in prefs (only the latest is UPPER)
		    } else {
		        $contact[$k][$m] = convertStringFromDB($field[$m]);
		    }
		}
		$k++;
	}
	$conn->freeResult();
	// === END of building the contact array === 
	
    // === Building the letters array ===
    $A_value = ord("A");              //ascii value of A
    $Z_value = ord("Z");
    $cpt = 0;
    for ($i = $A_value ; $i <= $Z_value ; $i++) {
       $letters_array["ascii_val"][$cpt++] = chr($i);    // create an array containing letters A to Z
    }

	for ($i = 0 ; $i < count($letters_array["ascii_val"]) ; $i++) { // the buildQuery is called with the COUNT parameter
        $sql = buildQuery("COUNT", "REGISTERED_USER", $s_user->getUserId(), $query_fields, $i_chkPriCt, $i_search_category, $i_chkPubCt, $query_domains, $i_search_string, $i_search_position, $i_search_field, $letters_array["ascii_val"][$i]);
        $conn->execQuery($sql);
        $field = $conn->getNextRecord();
        $letters_array["display"][$i] = ($field["howmany"] == 0 ? 0 : 1);  // save letters with contacts
        $conn->freeResult();
    }
    // === END of Building the letters array ===
 

/* 
for ($i=0;$i<count($s_user->getUserDomains());$i++) {
    echo "i_chkDomains[".$i."] : " . $i_chkDomains[$i]."<br>";
} 
echo "i_using_chk : " .$i_using_chk;   echo "<hr>";    echo "page précédente : " . $s_previous_page. "<hr>";
*/ 
   
    $conn->close();
    $conn2->close();
    $s_previous_page = "contacts.php";
    
    require ("templates/contacts_main.tmpl");
?>

