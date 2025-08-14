<?php
  /* 
  	Class User
    by Roulaizze and laGregouille
    aaaxl@nocive.com
  */
    
class User {
    
    // private variables
    var $ID = 0;
    var $login = "";
    var $passwd = ""; 
    var $firstname = "";
    var $name = "";
    var $email = "";
    var $godlike = "NO";
    var $categories = array("Home","Pro.","","","","","","","","");
    var $domains = "";
    var $preferences = array("private_chk"    => "priCtOn",
                             "public_chk"     => "pubCtOn",
                             "domain_chk"     => "domCtOn",
                             "searched_field" => "FIELD_ALL",
                             "position_radio" => "POSITION_ANYWHERE",
                             "category_combo" =>  -1,
                             "name_case"      =>  "AS_TYPED_CASE",
                             "page_size"      =>   20,
                             "confirm_del"    =>  "confirmDelOn");
    var $listpreferences = array(0,1,-1,-1);
    var $altpreferences = array(7,10,11,-1,-1,-1);
    var $maxcontacts = "150";
    var $session_preferences = "";
    var $colorpreferences = array("menu"          => "#7B8590",
                                  "button"        => "#BAC2C7",
                                  "stdfontcolor"  => "#3E4860",
                                  "stdfontsize"   => "12",
                                  "listfontcolor" => "#883322",
                                  "listfontsize"  => "12");
    var $language = "en";                                  
    
    /* assessors */
    function getUserId() {
  	    return $this->ID;
    }
    function getUserLogin() {
  	    return $this->login;
    }
    function getUserPasswd() {
  	    return $this->passwd;
    }
    function getUserFirstname() {
  	    return $this->firstname;
    }
    function getUserName() {
  	    return $this->name;
    }
    function getUserEmail() {
  	    return $this->email;
    }    
    function getUserGodlike() {
  	    return $this->godlike;
    }
    function getUserCategories() {
        return $this->categories;
  	}
    function getUserDomains() {
  	    return $this->domains;
    }
    function getUserPreferences() {
        return $this->preferences;
    }
    function getUserListPreferences() {
  	    return $this->listpreferences;
    }
    function getUserAltPreferences() {
  	    return $this->altpreferences;
    }
    function getUserMaxContacts() {
  	    return $this->maxcontacts;
    }
    function getUserColorPreferences() {
  	    return $this->colorpreferences;
    }
    function getUserLanguage() {
  	    return $this->language;
    }
    function getUserSession_preferences() {
  	    return $this->session_preferences;
    }

     /* Modifiors */
    function setUserId($mID) {
  	    $this->ID = $mID;
    }
    function setUserLogin($mLogin) {
  	    $this->login = $mLogin;
    }
    function setUserPasswd($mPasswd) {
  	    $this->passwd = $mPasswd;
    }
    function setUserFirstname($mFirstname) {
  	    $this->firstname = $mFirstname;
    }  
    function setUserName($mName) {
  	    $this->name = $mName;
    } 
    function setUserEmail($mEmail) {
  	    $this->email = $mEmail;
    } 
    function setUserGodlike($mGodlike) {
  	    $this->godlike = $mGodlike;
    }
    function setUserCategories($mCategories) {
  	    $this->categories = $mCategories;
    }
    function setUserDomains($mDomains) {
  	    $this->domains = $mDomains;
    } 
    function setUserPreferences ($mPreferences) {
        $this->preferences = $mPreferences;
    }
    function setUserListPreferences ($mListPreferences) {
        $this->listpreferences = $mListPreferences;
    }
    function setUserAltPreferences ($mAltPreferences) {
        $this->altpreferences = $mAltPreferences;
    }
    function setUserMaxContacts ($mMaxContacts) {
        $this->maxcontacts = $mMaxContacts;
    }
    function setUserColorPreferences ($mColorPreferences) {
        $this->colorpreferences = $mColorPreferences;
    }
    function setUserSession_preferences ($mSession_preferences) {
        $this->session_preferences = $mSession_preferences;
    }
    function setUserLanguage ($mLanguage) {
        $this->language = $mLanguage;
    }    
    
    /* constructor */                                       
    function User($cID, $cLogin, $cPasswd, $cEmail){    
        if (trim($cLogin) != "" && trim($cPasswd) != ""){   // Login and passwd are given -> filling object login & passwd
  		    $this->setUserLogin($cLogin);
			$this->setUserPasswd($cPasswd);
  	    } else { 
  	        if ($cEmail != "") {
  	            $this->setUserEmail($cEmail);
  	        } else {                                         
  		        if ($cID != "") {                               //  ID exists -> filling object ID
                    $this->setUserId($cID); 
                }
            }   
  	    }  
    }
    
    /* public methods */
    
    function loadUserData($conn, $conn2) {
        $sql  = "SELECT ulogin, upasswd, ufirstname, uname, uemail, ugodlike, ucategories, upreferences, ulist_preferences, ualt_preferences, umaxcontacts, ucolor_preferences, ulanguage, dID_FK, uadminright";
        $sql .= " FROM aboo_user LEFT JOIN aboo_user_domain";
        $sql .= " ON uID = uID_FK";
        $sql .= " WHERE uID = " . $this->getUserId();
        //echo $sql."<hr>";
        $conn->execQuery($sql);
        $i = 0;
        $array_domains = array();
        while ($field = $conn->getNextRecord()) {
            if ($i == 0) {                              // only the first time, the others are for domains
                $this->setUserLogin(convertStringFromDB($field["ulogin"]));
                $this->setUserPasswd(convertStringFromDB($field["upasswd"]));
                $this->setUserFirstname(convertStringFromDB($field["ufirstname"]));
                $this->setUserName(convertStringFromDB($field["uname"]));
                $this->setUserEmail(convertStringFromDB($field["uemail"]));
                $this->setUserCategories(explode(",",$field["ucategories"])); 
                $this->setUserGodlike($field["ugodlike"]);
                $this->setUserLanguage($field["ulanguage"]);
                               
                // explode field categories before setting them
                $temp = explode(",",$field["upreferences"]);
                
                // create the array for preferences
                $clean_pref["private_chk"]     = $temp[0];       
                $clean_pref["public_chk"]      = $temp[1];      
                $clean_pref["domain_chk"]      = $temp[2];      
                $clean_pref["searched_field"]  = $temp[3];      
                $clean_pref["position_radio"]  = $temp[4];      
                $clean_pref["category_combo"]  = $temp[5];      
                $clean_pref["name_case"]       = $temp[6];      
                $clean_pref["page_size"]       = $temp[7]; 
                $clean_pref["confirm_del"]     = $temp[8];     
                                                   
                $this->setUserPreferences($clean_pref);
                $this->setUserListPreferences(explode(",",$field["ulist_preferences"]));
                $this->setUserAltPreferences(explode(",",$field["ualt_preferences"]));
                
                
                // explode field colorprefs before setting them
                unset($temp);
                $temp = explode(",",$field["ucolor_preferences"]);
                $field["ucolor_preferences"]."|";
                
                // create array for color prefs
                $clean_color["menu"]          = $temp[0];
                $clean_color["button"]        = $temp[1];
                $clean_color["stdfontcolor"]  = $temp[2];
                $clean_color["stdfontsize"]   = $temp[3];
                $clean_color["listfontcolor"] = $temp[4];
                $clean_color["listfontsize"]  = $temp[5];
                
                $this->setUserColorPreferences($clean_color);
                
                $this->setUserMaxContacts($field["umaxcontacts"]);
                
            }
            if ($field["dID_FK"] != "" && $field["uadminright"] != "") {
                $sql2  = "SELECT dID, dname, dmisc, uID_FK, uadminright FROM aboo_domain, aboo_user_domain ";
                $sql2 .= "WHERE dID = dID_FK AND uID_FK = " . $this->getUserId() . " AND dID = " . $field["dID_FK"];
                //echo $sql2."<hr>";
                $conn2->execQuery($sql2);
                $field2 = $conn2->getNextRecord();
                $array_domains[$i]["id"] = $field2["dID"];
                $array_domains[$i]["name"] = $field2["dname"];
                $array_domains[$i]["misc"] = $field2["dmisc"];
                $array_domains[$i]["right"] = $field2["uadminright"];
                $conn2->freeResult();
            }
            $i++;
        }
        
        $this->setUserDomains($array_domains);
        $conn->freeResult();
    }
    

    
    function saveUserData($conn) {
        $sql = "SELECT uID FROM aboo_user WHERE uID = ".$this->getUserId();
        $conn->execQuery($sql);
        //echo $sql . "<br>";
        // contatenation before database storage
        $concat_categories  = concatenate($this->getUserCategories()); // concatenate function is defined in fotools.php
        $concat_prefs       = concatenate($this->getUserPreferences());
        $concat_list_prefs  = concatenate($this->getUserListPreferences());
        $concat_alt_prefs   = concatenate($this->getUserAltPreferences());
        $concat_color_prefs = concatenate($this->getUserColorPreferences());
 
        
        if ($field = $conn->getnextRecord()) { // user exists - we update it
            //echo $this->getUserFirstname();
            //echo convertStringToDB($this->getUserFirstname());
            //die;
            $conn->freeResult();
            $sql  = "UPDATE aboo_user SET ulogin = '" . convertStringToDB($this->getUserLogin()) . "', upasswd = '" . convertStringToDB($this->getUserPasswd()) . "', ";
            $sql .= "ufirstname = '" . convertStringToDB($this->getUserFirstname()) . "', uname = '" . convertStringToDB($this->getUserName()) . "', ";
            $sql .= "uemail = '" . convertStringToDB($this->getUserEmail()) . "', ugodlike = '" . $this->getUserGodlike()."', ";   
            $sql .= "ucategories = '" . $concat_categories ."', upreferences = '" . $concat_prefs . "', ";  
            $sql .= "ulist_preferences = '" . $concat_list_prefs . "', ualt_preferences = '" . $concat_alt_prefs . "',";  
            $sql .= "umaxcontacts = " . $this->getUserMaxContacts() . ", ucolor_preferences = '" . $concat_color_prefs . "', ulanguage = '" . $this->getUserLanguage() ."' ";
            $sql .= "WHERE uID = " . $this->getUserId(); 
            //echo $sql . "<br>";
            $conn->execQuery($sql);
            $this->setCurrentUserDomains($conn, "YES");  // because we update user, we first delete its old domain values
        } else {                               // user doesn't exists - we create it
            $conn->freeResult();
            $sql  = "INSERT INTO aboo_user VALUES ('', '" . convertStringToDB($this->getUserLogin()). "', '";
            $sql .= convertStringToDB($this->getUserPasswd()) . "', '" . convertStringToDB($this->getUserFirstname()) . "', '". convertStringToDB($this->getUserName()). "', '";
            $sql .= convertStringToDB($this->getUserEmail()) . "', '". $this->getUserGodlike() . "','". $concat_categories . "', '";
            $sql .= $concat_prefs . "', '". $concat_list_prefs . "','". $concat_alt_prefs . "',". $this->getUserMaxContacts() . ", '";
            $sql .= $concat_color_prefs . "', '" . $this->getUserLanguage() . "')";
            //echo $sql;
            $conn->execQuery($sql);
            $this->setUserId($conn->getAutoIncrementId()); //get the uID created by mySQL through autoincrement mechanism
            $this->setCurrentUserDomains($conn, "NO");   // because we create user, don't need its domains deletion
        }
    }

    function setCurrentUserDomains($conn, $del) {
        if ($del == "YES") {            // user's update : first delete all domains...
            $sql = "DELETE FROM aboo_user_domain WHERE uID_FK = ". $this->getUserId();
            //echo $sql."<br>";
            $conn->execQuery($sql); 
        }
        
        $temp_array = $this->getUserDomains();
        if (count($temp_array)) {   // new user or user's update, domains insertion
            for ($i = 0 ; $i < count($temp_array); $i++) {
                //echo "avant  ";
                $sql = "INSERT INTO aboo_user_domain VALUES (" . $this->getUserId() . ", " . $temp_array[$i]["id"] . ", '" . $temp_array[$i]["right"] . "')";
  		        //echo $sql."<br>";
  		        $conn->execQuery($sql);
            }
        }
      
    }        
  

    function deleteUser($conn) {
               
		$sql  = "SELECT DISTINCT cID FROM aboo_contact WHERE uID_FK = " . $this->getUserID();
		$conn->execQuery($sql);
	    $i = 0;
        $array_contacts = array();
        while ($field = $conn->getNextRecord()) {
            $array_contacts[$i] = $field["cID"];
            $i++;
        }
	    while (list ($key, $value) = each ($array_contacts)) {
            $formated_user_contacts .=  $value . ",";
        }
        if (!empty($formated_user_contacts)) {
            $formated_user_contacts = substr($formated_user_contacts, 0, strlen($formated_user_contacts) - 1);     
		    $sql = "DELETE FROM aboo_contact_domain WHERE cID_FK IN (" . $formated_user_contacts . ")";
		    $conn->execQuery($sql);
        }
        
        $sql = "DELETE FROM aboo_contact WHERE uID_FK = ". $this->getUserId();
        $conn->execQuery($sql);
    
        $sql = "DELETE FROM aboo_user_domain WHERE uID_FK = ". $this->getUserId();
        $conn->execQuery($sql);

        $sql = "DELETE FROM aboo_user WHERE uID = ". $this->getUserId();
        $conn->execQuery($sql);
    }
 
    function deletePublicContactsUser($conn) {
        $exec_delete = false; // init of delete action
        $sql = "SELECT COUNT(cID) as howmany FROM aboo_contact WHERE uID_FK = ". $this->getUserId() . " AND cprivacy ='PUBLIC'";
        $conn->execQuery($sql);
        $field = $conn->getNextRecord();
        $conn->freeResult();
        
        if ($field["howmany"] != 0) {
            $sql = "DELETE FROM aboo_contact WHERE uID_FK = ". $this->getUserId() . " AND cprivacy ='PUBLIC'";
            $conn->execQuery($sql);
            $exec_delete = true;
        }
        return $exec_delete;
    }
       
    function deletePrivateContactsUser($conn, $category) {
        $exec_delete = false; // init of delete action
        $cat_condition = "";
        
        if ($category != -1){
            $cat_condition = " AND ccategory = " . $category;
        }
        
        $sql = "SELECT COUNT(cID) as howmany FROM aboo_contact WHERE uID_FK = ". $this->getUserId() . " AND cprivacy ='PRIVATE' " . $cat_condition;
        $conn->execQuery($sql);
        $field = $conn->getNextRecord();
        $conn->freeResult();
        
        if ($field["howmany"] != 0) {
            if ($category == -1) { // means all categories have been selected
                $sql = "DELETE FROM aboo_contact WHERE uID_FK = ". $this->getUserId() . " AND cprivacy ='PRIVATE'";
            } else {
                $sql = "DELETE FROM aboo_contact WHERE uID_FK = ". $this->getUserId() . " AND cprivacy ='PRIVATE' AND ccategory = " . $category;
            }
            $conn->execQuery($sql);
            $exec_delete = true;
        }
        return $exec_delete;
    }
 
    function deleteDomainContactsUser($conn, $domain) {
        // delete all user contacts for given $domain
        $exec_delete = false; // init of delete action
        $user_domains = array();
        $selected_contacts_array = array();
        $temp = array();
        
        // step 1 : first select all record of aboo_contact_domain that match $domain and are user property
        $temp = $this->getUserDomains();
        
        for ($i = 0; $i < count($temp) ; $i++) { // create $user_domains with only IDs
            $user_domains[$i] = $temp[$i]["id"];
        }
        
        $sql = "SELECT cID_FK, dID_FK FROM aboo_contact, aboo_contact_domain  WHERE cID = cID_FK AND uID_FK = " . $this->getUserId() . " AND dID_FK = " . $domain;
        //echo $sql;
        $conn->execQuery($sql);
        
        while ($field = $conn->getNextRecord()) {
            if (in_array($field["dID_FK"], $user_domains)) {
                $selected_contacts_array[] = $field["cID_FK"];
            }
            $exec_delete = true;
        }
        $conn->freeResult();
        $selected_contacts_list = concatenate($selected_contacts_array);  // all cID to delete, separated by ','
        
        if ($exec_delete) { // found at least one cID in step 1
            // step 2 : delete records in contacts table
            $sql = "DELETE FROM aboo_contact WHERE uID_FK = ". $this->getUserId() . " AND cprivacy ='DOMAIN' AND cID IN (". $selected_contacts_list . ")";
            $conn->execQuery($sql);
            
            // step 3 : delete records in contact_domain table
            $sql = "DELETE FROM aboo_contact_domain WHERE cID_FK IN (". $selected_contacts_list . ")";
            $conn->execQuery($sql);
        } 
        return $exec_delete;
    }
    
    
    /*   Use this function for ADMIN work (not GODLIKE) */
    // FUNCTION WORKS BUT HAVE TO CLEAN NOT USED PARAMETERS
    /*   $selection is true or false (true for a checked checkbox, etc...) */
    function updateOneUserDomain($domainID, $domainName, $domainMisc, $selection) {  
        $temp_domains = $this->getUserDomains(); 
        $work = "NOTHING";
        reset($temp_domains);
        while (list ($key, $value) = each ($temp_domains)) {
            if ($value["id"] == $domainID) { // $current_user was previously belonging...
                if (!$selection) {    // but have to quit the domain
                    $what = $key;     // store 
                }
                $work = "TODO";    // the 2 ID were matching, work is done
            }
        }
        if ($work == "NOTHING") {  // $current_user was NOT previously belonging...
            if ($selection) {    // have to add domain
                $a = array();               // create new temporary array...
                $a["id"] = $domainID;     
                $a["right"] = "USER";  
                $a["name"] = $domainName; // not used in save method, but clean to do
                $a["misc"] = $domainMisc; // not used in save method, but clean to do
                $temp_domains[] = $a;   //... and merge it as new
            }
        } elseif ($work == "TODO" && isset($what)) { // what to do is defined ;)
            reset($temp_domains);
            $temp_domains = RemoveRowFromArray($temp_domains, $what); // new array 
        }
        $this->setUserDomains($temp_domains);
    }
    
    
    function allowDuplicatesImport($conn) {
        $result = true;

        $name_firstname = array();
        $name_email = array();
        
        $sql  = "SELECT DISTINCT cID, cname, cfirstname, cemail FROM aboo_contact WHERE uID_FK = " . $this->getUserID();
		$conn->execQuery($sql);
		while ($field = $conn->getNextRecord()) {
		    
		    $name = trim(strtolower($field["cname"]));
		    $firstname = trim(strtolower($field["cfirstname"]));
		    $email = trim(strtolower($field["cemail"]));
		    
		    // check if exists a $name . $firstname in base
		    if ($name != "" && $firstname != "") {
    		    if (in_array($name . $firstname, $name_firstname)) {
    		        // echo "NF : " . $name . $firstname . "<br>";
    		        $result = false;   
    		    } else {
    		        $name_firstname[] = $name . $firstname;
    		    }
    		}
    		
    		// check if exists a $name . $email in base
    		if ($name != "" && $email != "") {    
    		    if (in_array($name . $email, $name_email)) {
    		        // echo "NE : " . $name . $email. "<br>";
    		        $result = false;
    		    } else {
    		        $name_email[] = $name . $email;
    		    }    
    		}
		}
		return $result; // returns true if clean, false other case
    }

    function checkUser($conn) {
        $result = false;
        $sql = "SELECT uID FROM aboo_user WHERE ulogin = '" . $this->getUserLogin() . "' AND upasswd = '" . $this->getUserPasswd()."'";
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) {
			$this->setUserId($field["uID"]);
			$result = true;
		}
        $conn->freeResult();
        return $result;
    }
    
    function checkUserWithEmail($conn) {
        $result = false;
        $sql  = "SELECT uID, ulogin, upasswd, uemail, uname, ufirstname FROM aboo_user WHERE uemail = '". $this->getUserEmail() . "'";
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) {
			$this->setUserId($field["uID"]);
			$this->setUserLogin($field["ulogin"]);
			$this->setUserPasswd($field["upasswd"]);
			$this->setUserName($field["uname"]);
			$this->setUserFirstname($field["ufirstname"]);
		    $result = true;
		}
        $conn->freeResult();
        return $result;
    }
    
    function isExistingLogin($conn) {
        $result = false;
        $sql  = "SELECT uID FROM aboo_user WHERE ulogin = '" . $this->getUserLogin() .  "' ";
        $sql .= "AND uID <>  " . $this->getUserId();
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) {
		    $result = true;
		    //echo $field["uID"];
		}
        $conn->freeResult();
        return $result;
    }
    /* oldy
    function isExistingLogin($conn) {
        $result = false;
        $sql  = "SELECT uID FROM aboo_user WHERE ulogin = '" . $this->getUserLogin() . "' AND upasswd = '". $this->getUserPasswd() . "' ";
        $sql .= "AND uID <>  " . $this->getUserId();
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) {
		    $result = true;
		}
        $conn->freeResult();
        return $result;
    }    
    */
    function isExistingUser($conn) {
        return false;
        $sql  = "SELECT uID FROM aboo_user WHERE uID = '" . $this->getUserId() . "'";
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) {
            $conn->freeResult();
            return true;
        } 
    }

    function getUserRights() {
        if ($this->getUserGodlike() == "YES") {
            return "GODLIKE";
            exit;
        } else {
            $temp_array = $this->getUserDomains();
            while (list ($key, $value) = each ($temp_array)) {
                //echo "key : " . $key . " -- Value[right] : " . $value["right"] ."<br>";
                if ($value["right"] == "ADMIN") {
                    return "ADMIN";
                    exit;
                }
            }
            return "USER";
        }
    }

    function isDomainAdminUser($conn, $domainID) {
        //echo "domain : " . $domainID;
        $temp_array = $this->getUserDomains();
        while (list ($key, $value) = each ($temp_array)) {
            //echo "value : " . $value["id"] . " | " . $value["right"] . "<br>";
            if ($value["id"] == $domainID && $value["right"] == "ADMIN") {
                return true;
                exit;
            }
        }
        return false;
    } 
    
    function isDomainUser($domainID) {
        $temp_array = $this->getUserDomains();
        while (list ($key, $value) = each ($temp_array)) {
            if ($value["id"] == $domainID) {
                if ($value["right"] == "USER" || $value["right"] == "ADMIN") {
                    return $value["right"];
                    exit;
                }
            }
        }
        return "NOTHING";
    } 

/*
    function getUserRights($conn) {
        $sql  = "SELECT uID, uadmin ";
        $sql .= "FROM aboo_user ";
        $sql .= "WHERE uID = " . $this->getUserId();
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) {
            if ($field["uadmin"] == "USER") {
                $conn->freeResult();
                return "USER";
            } elseif ($field["uadmin"] == "ADMIN") {
                $conn->freeResult();
                return "ADMIN";
            } elseif ($field["uadmin"] == "GODLIKE") {
                $conn->freeResult();
                return "GODLIKE";
            } else { // should never happened
                $conn->freeResult();
                return false;
            }
        } else {
            $conn->freeResult();
            return false;
        }
    }

    function isAdminUser($conn) {
        $sql  = "SELECT uID, uadmin ";
        $sql .= "FROM aboo_user ";
        $sql .= "WHERE uID = " . $this->getUserId();
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) {
            if ($field["uadmin"] == "ADMIN") {
                $conn->freeResult();
                return true;
            } 
        }
        $conn->freeResult();
        return false;
    }
 */   
    
    function haveDependentContacts($conn) {
        $sql  = "SELECT cID FROM aboo_contact WHERE uID_FK = " . $this->getUserID();
        $conn->execQuery($sql); 
        $i = 0;
        while ($field = $conn->getNextRecord()) {
            $i++;
        }
        $conn->freeResult();
        //echo $i;
        if ($i > 0) {
            return $i;
        } else {
            return false;
        }
    }

    function haveDependentContactsInDomain($conn, $domain) {
        $sql = "SELECT DISTINCT cID_FK FROM aboo_contact_domain WHERE dID_FK = " . $domain; // all contacts in domain
        $conn->execQuery($sql);
        $temp_cid = "";
        while ($field = $conn->getNextRecord()) {
            $temp_cid .= $field["cID_FK"] . ", ";
        }
        $conn->freeResult();
        
        if ($temp_cid == "") {
            return false;
        } else {
            $temp_cid = "(" . substr($temp_cid, 0, (strlen($temp_cid)-2)) . ")"; // strin for next 'IN' condition
            $sql = "SELECT COUNT(cID) as howmany FROM aboo_contact WHERE cID IN " . $temp_cid . " AND uID_FK = " . $this->getUserID(); // al contacts from domains that are proprerty of user
            $conn->execQuery($sql);
            if ($field = $conn->getNextRecord()) {
                $result = $field["howmany"];
            }
            $conn->freeResult();
            return $result;
        }
    }    

    
}
?>