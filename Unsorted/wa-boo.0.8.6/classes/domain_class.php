<?php
  /* 
  	Class Domain
    by Roulaizze et laGregouille
    aaaxl@nocive.com
  */
    
class Domain{
  
    // private variables
    var $ID = 0;
    var $name = "";
    var $misc = "";
    var $users = "";
    var $admins = "";
    var $godlikes = "";
    var $members = "";
  
    /* assessors */
    function getDomainId() {
  	    return $this->ID;
    }
    function getDomainName() {
  	    return $this->name;
    }
    function getDomainMisc() {
  	    return $this->misc;
    }
    function getDomainUsers() {
  	    return $this->users;
    }
    function getDomainAdmins() {
  	    return $this->admins;
    }
    function getDomainGodlikes() {
  	    return $this->godlikes;
    }
    function getDomainMembers() {
  	    return $this->members;
    }
 
     /* Modifiors */
    function setDomainId($mID) {
  	    $this->ID = $mID;
    } 
    function setDomainName($mName) {
  	    $this->name = $mName;
    }
    function setDomainMisc($mMisc) {
  	    $this->misc = $mMisc;
    }
    function setDomainUsers($mUsers) {
  	    $this->users = $mUsers;
    }
    function setDomainAdmins($mAdmins) {
  	    $this->admins = $mAdmins;
    }
    function setDomainGodlikes($mGodlikes) {
  	    $this->godlikes = $mGodlikes;
    }
    function setDomainMembers($mMembers) {
  	    $this->members = $mMembers;
    }
    
    /* constructor */
    function Domain($ID){    
        if ($ID != "") {
            $this->setDomainId($ID); 
        }
    }
    
    function loadDomainData($conn) {
        $sql = "SELECT * FROM aboo_domain WHERE dID = " . $this->getDomainId();
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) {
            $this->setDomainName($field["dname"]);
            $this->setDomainMisc($field["dmisc"]);
        }
        $conn->freeResult();
        $this->loadDomainUsers($conn);
        $this->loadDomainAdmins($conn);
        $this->loadDomainGodlikes($conn);
        $this->loadDomainMembers($conn);
    }
    
     /* returns array of domain users with all properties */
    function loadDomainUsers($conn) {
        $sql  = "SELECT DISTINCT uID, ulogin, ufirstname, uname, uemail, uadminright FROM aboo_user_domain, aboo_user WHERE uID_FK = uID AND dID_FK = ". $this->getDomainId() . " AND uadminright = 'USER' ORDER BY uname";
        //echo $sql ."<br>";
        $conn->execQuery($sql);
        $i = 0;
        $users_list = array();
        while ($field = $conn->getNextRecord()) {      
            $users_list[$i]["id"] = $field["uID"];         
            $users_list[$i]["firstname"] = $field["ufirstname"];
            $users_list[$i]["name"] = $field["uname"];
            $users_list[$i]["login"] = $field["ulogin"]; 
            $users_list[$i]["passwd"] = $field["upasswd"];
            $users_list[$i]["email"] = $field["uemail"];
            $users_list[$i]["right"] = $field["uadminright"];
            $i++;
        }
        $this->setDomainUsers($users_list);
        $conn->freeResult();
    }

    function loadDomainAdmins($conn) {
        $sql  = "SELECT DISTINCT uID, ulogin, ufirstname, uname, uemail, ugodlike, uadminright FROM aboo_user_domain, aboo_user WHERE uID_FK = uID AND dID_FK = ". $this->getDomainId() . " AND uadminright = 'ADMIN' ORDER BY uname";
        //echo $sql ."<br>";
        $conn->execQuery($sql);
        $i = 0;
        $admins_list = array();
        while ($field = $conn->getNextRecord()) {      
            $admins_list[$i]["id"] = $field["uID"];         
            $admins_list[$i]["firstname"] = $field["ufirstname"];
            $admins_list[$i]["name"] = $field["uname"];
            $admins_list[$i]["login"] = $field["ulogin"]; 
            $admins_list[$i]["passwd"] = $field["upasswd"];
            $admins_list[$i]["email"] = $field["uemail"];
            $admins_list[$i]["right"] = $field["uadminright"];
            $i++;
        }
        $this->setDomainAdmins($admins_list);
        $conn->freeResult();
    }
    
    function loadDomainGodlikes($conn) {
        $sql  = "SELECT DISTINCT uID, ulogin, ufirstname, uname, uemail, ugodlike, uadminright FROM aboo_user_domain, aboo_user WHERE uID_FK = uID AND dID_FK = ". $this->getDomainId() . " AND uadminright = 'GODLIKE' ORDER BY uname";
        //echo $sql ."<br>";
        $conn->execQuery($sql);
        $i = 0;
        $godlikes_list = array();
        while ($field = $conn->getNextRecord()) { 
            if ($field["ugodlike"] == 'YES') {    
                $godlikes_list[$i]["id"] = $field["uID"];         
                $godlikes_list[$i]["firstname"] = $field["ufirstname"];
                $godlikes_list[$i]["name"] = $field["uname"];
                $godlikes_list[$i]["login"] = $field["ulogin"]; 
                $godlikes_list[$i]["passwd"] = $field["upasswd"];
                $godlikes_list[$i]["email"] = $field["uemail"];
                $godlikes_list[$i]["right"] = $field["uadminright"];
            }
            $i++;
        }
        $this->setDomainGodlikes($godlikes_list);
        $conn->freeResult();
    }

    function loadDomainMembers($conn) {
        $sql  = "SELECT DISTINCT uID, ulogin, ufirstname, uname, uemail, ugodlike, uadminright FROM aboo_user_domain, aboo_user WHERE uID_FK = uID AND dID_FK = ". $this->getDomainId() . " ORDER BY uname";
        //echo $sql ."<br>";
        $conn->execQuery($sql);
        $i = 0;
        $godlikes_list = array();
        while ($field = $conn->getNextRecord()) { 
            $members_list[$i]["id"] = $field["uID"];         
            $members_list[$i]["firstname"] = $field["ufirstname"];
            $members_list[$i]["name"] = $field["uname"];
            $members_list[$i]["login"] = $field["ulogin"]; 
            $members_list[$i]["passwd"] = $field["upasswd"];
            $members_list[$i]["email"] = $field["uemail"];
            $members_list[$i]["right"] = $field["uadminright"];
            $i++;
        }
        $this->setDomainMembers($members_list);
        $conn->freeResult();
    }
            

    function saveDomainData($conn) {
        $sql = "SELECT dID FROM aboo_domain WHERE dID = " . $this->getDomainId();
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) { // domain exists - let's update it
            $conn->freeResult();
            $sql  = "UPDATE aboo_domain SET dname = '" . $this->getDomainName() . "', dmisc = '" .$this->getDomainMisc() . "' ";
            $sql .= "WHERE dID = " . $this->getDomainId(); 
        } else {                            // domain doesn't exist - let's create it
            $conn->freeResult();
            $sql  = "INSERT INTO aboo_domain values ('', '" . $this->getDomainName(). "', '" . $this->getDomainMisc() . "')";
        }
        $conn->execQuery($sql);
    }
    
    function cleanPrivacyContactErrors($conn) {
        // this function is use to clean contact table : when contacts should be PRIVATE and are flagged DOMAIN
        // There is no need of extra parameter. This method is cleaning table, that's all !!
        // This method is called by 2 next methods : deleteDomain and removeUserFromDomain;
        $sql = "SELECT cID FROM aboo_contact LEFT JOIN aboo_contact_domain ON cID = cID_FK WHERE cID_FK IS NULL AND cprivacy='DOMAIN'";
        $conn->execQuery($sql);
        
        $result = false;
        $id_array = array();
        while ($field = $conn->getNextRecord()) {
            $id_array[] = $field["cID"];
        }
                
        $id_list = concatenate($id_array);
        if ($id_list) {
            $conn->freeResult();
            $sql = "UPDATE aboo_contact SET cprivacy='PRIVATE' WHERE cID IN (" . $id_list . ")";
            //echo $sql;
            $conn->execQuery($sql);
            $result = true;
        }
        return $result; 
    }
    
    function deleteDomain($conn) { 
        // Delete a domain WILL NOT DELETE USERS OR CONTACTS, only their link to the domain.
        // 1) delete all current domain's users
        // 2) delete all current domain's contacts 
        // 3) delete the domain itself
        // 4) Clean contact_domain table with cleanPrivacyContactErrors method
        
        // 1)
        $sql = "DELETE FROM aboo_user_domain where dID_FK =" . $this->getDomainId();
        $conn->execQuery($sql);
        
        // 2)
        $sql = "DELETE FROM aboo_contact_domain where dID_FK =" . $this->getDomainId();
        $conn->execQuery($sql);
        
        // 3)
        $sql = "DELETE FROM aboo_domain where dID = " . $this->getDomainId();
        $conn->execQuery($sql);
        
        // 4)
        $this->cleanPrivacyContactErrors($conn);
    }
    
    function removeUserFromDomain($conn, $userID) {
        // 1) delete user in user_domain table
        // 2) build id list of all contacts belongging to user $userID and which are in the current domain 
        // 3) delete rows from contact_domain table which ids are in list AND in current domain
        // 4) Clean contact_domain table with cleanPrivacyContactErrors method
        
        
        // 1)
        $sql = "DELETE FROM aboo_user_domain where dID_FK = " . $this->getDomainId() . " AND uID_FK = " . $userID;
        $conn->execQuery($sql);
        
        // 2)
        $sql = "SELECT cID FROM aboo_contact, aboo_contact_domain WHERE cID = cID_FK AND dID_FK = " . $this->getDomainId() . " AND uID_FK = " . $userID;
        //echo $sql;
        $conn->execQuery($sql);
        
        $id_array = array();
        while ($field = $conn->getNextRecord()) {
            $id_array[] = $field["cID"];
        }
        $id_list = concatenate($id_array);
        $conn->freeResult();
        
        // 3)
        if ($id_list) {
            $sql = "DELETE FROM aboo_contact_domain WHERE cID_FK IN (" . $id_list . ") AND dID_FK = " . $this->getDomainId();
            //echo $sql;
            $conn->execQuery($sql);
        }
        
        // 4)
        $this->cleanPrivacyContactErrors($conn);
    }
    
    function addUserInDomain($conn, $userID) {
        // Check user is not already in group
        
        $sql = "SELECT uID_FK FROM aboo_user_domain WHERE uID_FK = " . $userID . " AND dID_FK = " . $this->getDomainId();
        $conn->execQuery($sql);
        
        if ($field = $conn->getNextRecord()) { // should never happens
            $conn->freeResult();
        } else {
            // insert user in user_domain table
            $sql = "INSERT INTO aboo_user_domain VALUES (" . $userID . ", " . $this->getDomainId() . ", '" . "USER" . "')";
            //echo $sql;
            $conn->execQuery($sql);
        }
    }

    function haveDependentUsers($conn) {
        $result = false;
        $sql  = "SELECT COUNT(dID_FK) FROM aboo_user_domain WHERE dID_FK = " . $this->getDomainID();
        $conn->execQuery($sql); 
        
        if ($field = $conn->getNextRecord()) {
            $result = $field["COUNT(dID_FK)"];
        }
        return $result;
    }
    
    function haveDependentContacts($conn) {
        $result = false;
        $sql  = "SELECT COUNT(dID_FK) FROM aboo_contact_domain WHERE dID_FK = " . $this->getDomainID();
        $conn->execQuery($sql); 
        
        if ($field = $conn->getNextRecord()) {
            $result = $field["COUNT(dID_FK)"];
        }
        return $result;
    }
    
    
/*    function haveDependentUsersAndContacts($conn) {
        $answer = array();
        
        $sql  = "SELECT COUNT(dID_FK) FROM aboo_user_domain WHERE dID_FK = " . $this->getDomainID();
        $conn->execQuery($sql); 
        
        if ($field = $conn->getNextRecord()) {
            $answer["users"] = $field["COUNT(dID_FK)"];
        }
        
        $sql  = "SELECT COUNT(dID_FK) FROM aboo_contact_domain WHERE dID_FK = " . $this->getDomainID();
        $conn->execQuery($sql); 
        
        if ($field = $conn->getNextRecord()) {
            $answer["contacts"] = $field["COUNT(dID_FK)"];
        }
        return $answer;
    }
*/
}
?>