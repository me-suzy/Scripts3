<?php
    /* 
  	    Class User
        by Roulaizze and laGregouille
        aaaxl@nocive.com
    */
  
class Contact {
  
    // private variables
    var $ID = 0;
    var $userId = "";
    var $firstname = "";
    var $name = "";
    var $addr1 = "";  
    var $addr2 = "";
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
    var $firm;
    var $position;
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
    function getContactFirm() {
  	    return $this->firm;
    }
    function getContactPosition() {
  	    return $this->position;
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
	 function getContactCategory() {
        return $this->category;
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
    function setContactFirm($mFirm) {
  	    return $this->firm = $mFirm;
    }
    function setContactPosition($mPosition) {
  	    return $this->position = $mPosition;
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
    function setContactCategory($mCategory) {
  	    return $this->category = $mCategory;
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
        //echo $sql;
        $conn->execQuery($sql);
        $array_domains = array();
        $i = 0;
        while ($field = $conn->getNextRecord()) {
            $this->setContactFirstname(convertStringFromDB($field["cfirstname"]));
            $this->setContactName(convertStringFromDB($field["cname"]));
            $this->setContactAddr1(convertStringFromDB($field["caddr1"]));
            $this->setContactAddr2(convertStringFromDB($field["caddr2"]));
            $this->setContactZip(convertStringFromDB($field["czip"]));
            $this->setContactCity(convertStringFromDB($field["ccity"]));
            $this->setContactCountry(convertStringFromDB($field["ccountry"]));
            $this->setContactEmail(convertStringFromDB($field["cemail"]));
            $this->setContactWww(convertStringFromDB($field["cwww"]));
            $this->setContactInstantmsg(convertStringFromDB($field["cinstantmsg"]));
            $this->setContactPhone(convertStringFromDB($field["cphone"]));
            $this->setContactCell(convertStringFromDB($field["ccell"]));
            $this->setContactPhonepro(convertStringFromDB($field["cphonepro"]));
            $this->setContactFax(convertStringFromDB($field["cfax"]));
            $this->setContactFirm(convertStringFromDB($field["cfirm"]));
            $this->setContactPosition(convertStringFromDB($field["cposition"]));
            $this->setContactPrivacy(convertStringFromDB($field["cprivacy"]));
            $this->setContactMisc(convertStringFromDB($field["cmisc"]));
            $this->setContactCategory($field["ccategory"]);
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
        //echo $sql . "<br>";
        $conn->execQuery($sql);
        
        if ($field = $conn->getNextRecord()) { // contact exists - let's update it
            $conn->freeResult();
            $sql  = "UPDATE aboo_contact SET ";
            $sql .= "cfirstname = '" . convertStringToDB($this->getContactFirstname()) . "', cname = '" . convertStringToDB($this->getContactName()) . "', ";
            $sql .= "caddr1 = '"     . convertStringToDB($this->getContactAddr1()) . "', caddr2 = '"    . convertStringToDB($this->getContactAddr2()) . "', ";
            $sql .= "czip = '"       . convertStringToDB($this->getContactZip()) . "', ccity = '"       . convertStringToDB($this->getContactCity()) . "', ";
            $sql .= "ccountry = '"   . convertStringToDB($this->getContactCountry()) . "', cemail = '"  . convertStringToDB($this->getContactEmail()) . "', ";            
            $sql .= "cwww = '"       . convertStringToDB($this->getContactWww()) . "', cinstantmsg = '" . convertStringToDB($this->getContactInstantmsg()) . "', ";
            $sql .= "cphone = '"     . convertStringToDB($this->getContactPhone()) . "', ccell = '"     . convertStringToDB($this->getContactCell()) . "', ";
            $sql .= "cphonepro = '"  . convertStringToDB($this->getContactPhonepro()) . "', cfax = '"   . convertStringToDB($this->getContactFax()) . "', ";
            $sql .= "cfirm = '"      . convertStringToDB($this->getContactFirm()) . "', cposition = '"  . convertStringToDB($this->getContactPosition()) . "', ";
            $sql .= "cprivacy = '"   . convertStringToDB($this->getContactPrivacy()) . "', cmisc = '"   . convertStringToDB($this->getContactMisc()) . "', ";
            $sql .= "uID_FK = "      . $this->getContactUserId()                    . ", ccategory = "  . $this->getContactCategory() ." ";
            $sql .= "WHERE cID = "   . $this->getContactId(); 
        	//echo $sql . "<br>";
        	$conn->execQuery($sql);
        	$this->setCurrentContactDomains($conn, "YES");
        } else {                            // contact doesn't exist - let's create it
            $conn->freeResult();
            $sql  = "INSERT INTO aboo_contact VALUES ('', ";
            $sql .= $this->getContactUserId() . ", '" . $this->getContactFirstname() . "', '";
            $sql .= convertStringToDB($this->getContactName())     . "', '" . convertStringToDB($this->getContactAddr1())      . "', '";
            $sql .= convertStringToDB($this->getContactAddr2())    . "', '" . convertStringToDB($this->getContactZip())        . "', '";
            $sql .= convertStringToDB($this->getContactCity())     . "', '" . convertStringToDB($this->getContactCountry())    . "', '";
            $sql .= convertStringToDB($this->getContactEmail())    . "', '" . convertStringToDB($this->getContactInstantmsg()) . "', '";
            $sql .= convertStringToDB($this->getContactWww())      . "', '" . convertStringToDB($this->getContactPhone())      . "', '";
            $sql .= convertStringToDB($this->getContactCell())     . "', '" . convertStringToDB($this->getContactPhonepro())   . "', '";
            $sql .= convertStringToDB($this->getContactFax())      . "', '" . convertStringToDB($this->getContactFirm())       . "', '";
            $sql .= convertStringToDB($this->getContactPosition()) . "', '" . convertStringToDB($this->getContactMisc())       . "','";
            $sql .= convertStringToDB($this->getContactPrivacy())  . "', "  . $this->getContactCategory()                      . ")";
            //echo $sql . "<br>";
            $conn->execQuery($sql);
            $this->setContactId($conn->getAutoIncrementId());
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