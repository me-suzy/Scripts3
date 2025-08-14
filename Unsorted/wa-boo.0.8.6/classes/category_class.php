<?php
    /* 
  	    Class User
        by Roulaizze and laGregouille
        aaaxl@nocive.com
    */
  
class Category {
  
    // private variables
    var $ID = 0;
    var $userId = "";
    var $cat0 = "";
    var $cat1 = "";
    var $cat2 = "";  
    var $cat3 = "";
    var $zip = "";  
    var $city = "";
    var $country = "";
    var $email = "";
    var $instantmsg = "";
    var $www = "";
    var $phone = "";
    var $phonepro = "";
    var $cell = "";
    var $fax = "";
    var $misc = "";
    var $privacy = "";
    var $domains = "";
  
    /* assessors */
    function getContactId() {
  	    return $this->ID;
    }
    function getContactFirstname() {
  	    return $this->firstname;
    }
    function getContactName() {
  	    return $this->name;
    }
    function getContactAddr1() {
  	    return $this->addr1;
    }
    function getContactAddr2() {
  	    return $this->addr2;
    }
    function getContactZip() {
  	    return $this->zip;
    }
    function getContactCity() {
  	    return $this->city;
    }
    function getContactCountry() {
  	    return $this->country;
    }
    function getContactEmail() {
  	    return $this->email;
    }
    function getContactInstantmsg() {
  	    return $this->instantmsg;
    }
    function getContactWww() {
  	    return $this->www;
    }
    function getContactPhone() {
  	    return $this->phone;
    }
    function getContactCell() {
  	    return $this->cell;
    }
    function getContactPhonepro() {
  	    return $this->phonepro;
    }
    function getContactFax() {
  	    return $this->fax;
    }
    function getContactMisc() {
  	    return $this->misc;
    }
    function getContactPrivacy() {
  	    return $this->privacy;
    }
    function getContactUserId() {
  	    return $this->userId;
    }
		function getContactDomains() {
				return $this->domains;
		}

     /* Modifiors */
    function setContactId($mID) {
  	    $this->ID = $mID; 
  	}
    function setContactFirstname($mFirstname) {
  	    return $this->firstname = $mFirstname;
    }
    function setContactName($mName) {
  	    return $this->name = $mName;
    }
    function setContactAddr1($mAddr1) {
  	    return $this->addr1 = $mAddr1;
    }
    function setContactAddr2($mAddr2) {
  	    return $this->addr2 = $mAddr2;
    }
    function setContactZip($mZip) {
  	    return $this->zip = $mZip;
    }
    function setContactCity($mCity) {
  	    return $this->city = $mCity;
    }
    function setContactCountry($mCountry) {
  	    return $this->country = $mCountry;
    }
    function setContactEmail($mEmail) {
  	    return $this->email = $mEmail;
    }
    function setContactWww($mWww) {
  	    return $this->www = $mWww;
    }
    function setContactInstantmsg($minstantmsg) {
  	    return $this->instantmsg = $minstantmsg;
    }
    function setContactPhone($mPhone) {
  	    return $this->phone = $mPhone;
    }
    function setContactCell($mCell) {
  	    return $this->cell = $mCell;
    }
    function setContactPhonepro($mPhonepro) {
  	    return $this->phonepro = $mPhonepro;
    }
    function setContactFax($mFax) {
  	    return $this->fax = $mFax;
    }
    function setContactMisc($mMisc) {
  	    return $this->misc = $mMisc;
    }
    function setContactPrivacy($mPrivacy) {
  	    return $this->privacy = $mPrivacy;
    }
    function setContactUserId($mUserID) {
  	    return $this->userId = $mUserID;
    }
 		function setContactDomains($mDomains) {
  	    return $this->domains = $mDomains;
    }
 		
    /* constructor */
    function Contact($cID) {    
        if ($cID != "") {
            $this->setContactId($cID); 
        }
    }
    
    /* publics methods */
    
    function loadContactData($conn) {
        $sql  = "SELECT * ";
        $sql .= "FROM aboo_contact LEFT JOIN aboo_contact_domain ";
        $sql .= "ON cID = cID_FK ";
        $sql .= "WHERE cID = " . $this->getContactId();
        $conn->execQuery($sql);
        $array_domains = array();
        $i = 0;
        while ($field = $conn->getNextRecord()) {
            $this->setContactFirstname($field["cfirstname"]);
            $this->setContactName($field["cname"]);
            $this->setContactAddr1($field["caddr1"]);
            $this->setContactAddr2($field["caddr2"]);
            $this->setContactZip($field["czip"]);
            $this->setContactCity($field["ccity"]);
            $this->setContactCountry($field["ccountry"]);
            $this->setContactEmail($field["cemail"]);
            $this->setContactWww($field["cwww"]);
            $this->setContactInstantmsg($field["cinstantmsg"]);
            $this->setContactPhone($field["cphone"]);
            $this->setContactCell($field["ccell"]);
            $this->setContactPhonepro($field["cphonepro"]);
            $this->setContactFax($field["cfax"]);
            $this->setContactPrivacy($field["cprivacy"]);
            $this->setContactMisc($field["cmisc"]);
            $this->setContactUserId($field["uID_FK"]);
        		if ($field["dID_FK"] != "") {
                $array_domains[$i] = $field["dID_FK"];
            }
        		$i++;
        }
        $this->setContactDomains($array_domains);
        $conn->freeResult();
    }
   
    function saveContactData($conn) {
        $sql = "SELECT cID FROM aboo_contact WHERE cID = " . $this->getContactId();
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) { // contact exists - let's update it
            $conn->freeResult();
            $sql  = "UPDATE aboo_contact SET ";
            $sql .= "cfirstname = '" . $this->getContactFirstname() . "', cname = '" .$this->getContactName() . "', ";
            $sql .= "caddr1 = '" . $this->getContactAddr1() . "', caddr2 = '" . $this->getContactAddr2() . "', ";
            $sql .= "czip = '" . $this->getContactZip() . "', ccity = '" . $this->getContactCity() . "', ";
            $sql .= "ccountry = '" . $this->getContactCountry() . "', cemail = '" . $this->getContactEmail() . "', ";            
            $sql .= "cwww = '" . $this->getContactWww() . "', cinstantmsg = '" . $this->getContactInstantmsg() . "', ";
            $sql .= "cphone = '" . $this->getContactPhone() . "', ccell = '" . $this->getContactCell() . "', ";
            $sql .= "cphonepro = '" . $this->getContactPhonepro() . "', cfax = '" . $this->getContactFax() . "', ";
            $sql .= "cprivacy = '" . $this->getContactPrivacy() . "', cmisc = '" . $this->getContactMisc() . "', ";
            $sql .= "uID_FK = " . $this->getContactUserId() . " ";
            $sql .= "WHERE cID = " . $this->getContactId(); 
        		$conn->execQuery($sql);
        		$this->setCurrentContactDomains($conn, "YES");
        } else {                            // contact doesn't exist - let's create it
            $conn->freeResult();
            $sql  = "INSERT INTO aboo_contact values ('', ";
            $sql .= $this->getContactUserId() . ", '" . $this->getContactFirstname() . "', '";
            $sql .= $this->getContactName() . "', '" . $this->getContactAddr1() . "', '";
            $sql .= $this->getContactAddr2() . "', '" . $this->getContactZip() . "', '";
            $sql .= $this->getContactCity() . "', '" . $this->getContactCountry() . "', '";
            $sql .= $this->getContactEmail() . "', '" . $this->getContactInstantmsg() . "', '";
            $sql .= $this->getContactWww() . "', '" . $this->getContactPhone() . "', '";
            $sql .= $this->getContactCell() . "', '" . $this->getContactPhonepro() . "', '";
            $sql .= $this->getContactFax() . "', '" . $this->getContactMisc() . "','";
            $sql .= $this->getContactPrivacy() . "')";
            //echo $sql;
            $conn->execQuery($sql);
        	$this->setCurrentContactDomains($conn, "NO");
        }
    }

    function deleteContact($conn) {
        $sql  = "SELECT cID ";
        $sql .= "FROM aboo_contact ";
        $sql .= "WHERE cID = " . $this->getContactId() . " ";
        $sql .= "AND uID_FK = " . $this->getContactUserId();
        $conn->execQuery($sql);
        if ($field = $conn->getNextRecord()) {
            $conn->freeResult();
            $sql = "DELETE FROM aboo_contact_domain WHERE cID_FK = " . $this->getContactId();
            $conn->execQuery($sql);
            $sql = "DELETE FROM aboo_contact WHERE cID = " . $this->getContactId();
            $conn->execQuery($sql);   
        }
    }

    function setCurrentContactDomains($conn, $del) {
    		if ($del == "YES") {
        		$sql = "DELETE FROM aboo_contact_domain WHERE cID_FK = ". $this->getContactId();            
            $conn->execQuery($sql); 
        }
        if (is_array($this->getContactDomains())) {
            $temp_array = $this->getContactDomains();
				    while (list ($key, $value) = each ($temp_array)) {
		  		      $sql = "INSERT INTO aboo_contact_domain VALUES (" . $this->getContactId(). ", " . $value . ")";
		  		      //echo $sql."<br>";
		  		      $conn->execQuery($sql);
		  		  }
  			}
  	}
}
?>