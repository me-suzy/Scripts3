<?php
  /* 
  	domains.php - domains management
    by Roulaizze [aaaxl@nocive.com] & laGregouille [lagregouille@free.fr]
  */
  
              
    function RemoveRowFromArray($p_array, $p_rowIdToRemove) { 
      $newArray = array();
      for ($i = 0 ; $i < count($p_array) ; $i++) {
        if ($i != $p_rowIdToRemove) { 
          $newArray[] = $p_array[$i];
        }
      }
      return $newArray; 
    } 
    
    function isCheckableUser($conn, $conn2, $hunterID, $rabbitID, $domainID) { // used to check either a user is checkable OR de-checkable on the given domain ID
        
        //echo "hunter : " . $hunterID . " lapin : "  .$rabbitID ." domaine : " . $domainID;
        
        // first check that $rabbit is alive (I mean an existing in database one :)
        $sql  = "SELECT uID FROM aboo_user WHERE uID = " . $rabbitID;
        $conn->execQuery($sql);
        if (!$field = $conn->getnextRecord()) {
            return false;
            exit;
        }
        
        // then check that $rabbit is not admin in any domains
        $sql  = "SELECT uID, uadminright FROM aboo_user, aboo_user_domain WHERE uID = uID_FK AND uID = " . $rabbitID;
        //echo $sql;
        $conn->execQuery($sql);
        $i = 0;
        while ($field = $conn->getnextRecord()) {
            //echo $field["uadminright"] ."<br>";
            if ($field["uadminright"] != "USER") {
                return false;
                exit;
            } 
            $i++;
        }
        // here result is true ;)     
        
        $rabbit = new User($rabbitID, "", "", "");
        $rabbit->loadUserData($conn, $conn2);
        $rabbit_status = $rabbit->isDomainUser($domainID);
        //echo "<br> rabbit : ". $rabbit_status;
        
        $hunter = new User($hunterID, "", "", "");
        $hunter->loadUserData($conn, $conn2);
        $hunter_status = $hunter->isDomainUser($domainID);
        //echo " hunter : ". $hunter_status;
        
        if ($rabbit_status == "USER" || $rabbit_status == "NOTHING") {
            if ($hunter_status == "ADMIN") {   
                return true;
            }
        } else  {
            return false;
        }    
    }    

    function isUpdatableUser($conn, $conn2, $hunterID, $rabbitID) { // used to check either a user is editable OR deletable
        
        // first check that $rabbit is alive (I mean an existing in database one :)
        $sql  = "SELECT uID FROM aboo_user WHERE uID = " . $rabbitID;
        $conn->execQuery($sql);
        //echo $sql;
        if (!$field = $conn->getnextRecord()) {
            return false;
            exit;
        }
            
        // then check that $rabbit is not admin in any domains
        $sql  = "SELECT uID, uadminright FROM aboo_user, aboo_user_domain WHERE uID = uID_FK AND uID = " . $rabbitID;
        $conn->execQuery($sql);
        $i = 0;
        while ($field = $conn->getnextRecord()) {
            if ($field["uadminright"] != "USER") {
                return false;
                exit;
            } else {
                $result = true;
            }
            $i++;
        }
        // here result is true ;) 
        
        
        // Then have to check that, in all of $rabbit domains $hunter is "ADMIN" or "GODLIKE"
        $rabbit = new User($rabbitID, "", "", "");
        $rabbit->loadUserData($conn, $conn2);
        $rabbit_domains = $rabbit->getUserDomains();
        
        //echo "count(rabbit_domains) : " . count($rabbit_domains) . "<br>";
        
        $hunter = new User($hunterID, "", "", "");
        $hunter->loadUserData($conn, $conn2);
        $hunter_domains = $hunter->getUserDomains();
        //echo "count(hunter_domains) : " . count($hunter_domains) . "<hr>";
                
        //echo "premier test OK<hr>";
        $result = false; // will try to put $result to 'true' again (hard to be killed for a rabbit :)
        $temp_result = 0;
        reset($rabbit_domains);
        while (list ($keyRabbit, $valueRabbit) = each ($rabbit_domains)) { // checking all $rabbit domains's ID
           // echo "rabbit : " .  $valueRabbit["id"] . "<br>";
            $temp_result_1 = 0;
            reset($hunter_domains);
            while (list ($keyHunter, $valueHunter) = each ($hunter_domains)) {
                //echo "hunter : "  . $valueHunter["id"] . "<br>";
                if ($valueHunter["id"] == $valueRabbit["id"]) { // hunter is in $value domain too... 
                    if ($valueHunter["right"] == "ADMIN" || $valueHunter["right"] == "GODLIKE") { // ... and is "ADMIN"  N.B. : --> never should be GODLIKE, GODLIKE is usually checked in ugodlike field of user table
                        $temp_result_1++;
                        //echo "yess";
                    }
                }
            }
            if ($temp_result_1 == 0) { //  found at least one domain where $hunter is "ADMIN" or "GODLIKE"
                return false;
                exit;
            } elseif ($temp_result_1 == 1) {
                //echo " une sous-boucle OK" ;
                $temp_result++;
            }
        }
        //echo " Nb total : " . $temp_result . "<br>";
        if ($temp_result == count($rabbit_domains)) { 
            //echo "c bon !!";
            return true;
            exit;
        } else {
            //echo "c foiré";
            return false;
            exit;
        }
    }  
  
  
    function getDomainsList($conn) {
        $sql  = "SELECT DISTINCT dID, dname, dmisc, IF(dID_FK is null,'not used','used') as used_or_not ";
        $sql .= "FROM aboo_domain LEFT JOIN aboo_user_domain ";
        $sql .= "ON dID = dID_FK";  
        $conn->execQuery($sql);
        $i = 0;
        $domains_list = array();
        while ($field = $conn->getNextRecord()) {
            $domains_list[$i]["id"] = $field["dID"];         
            $domains_list[$i]["name"] = $field["dname"];
            $domains_list[$i]["misc"] = $field["dmisc"];
            $domains_list[$i]["used_or_not"] = $field["used_or_not"];
            $i++;
        }
        $conn->freeResult();
        return $domains_list;
    }


    
    function getFullNonAdminList($conn, $conn2) {
        // retreive admin and godlikes users, to be able to avoid keeping them in the next query
        $sql  = "SELECT DISTINCT uID_FK FROM aboo_user_domain WHERE uadminright != 'USER'";
        $conn->execQuery($sql);
        
        $id_array = array();
        while ($field = $conn->getNextRecord()) {
            $id_array[] = $field["uID_FK"];
        }
        $not_to_keep = concatenate($id_array);
        
        if ($not_to_keep == ""){
            $condition = "";
        } else { 
            $condition = "AND uID NOT IN (". $not_to_keep .")";
        }
        
        /*
        $sql_count = "SELECT DISTINCT COUNT(uID) AS howmany FROM aboo_user WHERE ugodlike != 'YES' " . $condition;
        $conn->execQuery($sql_count);
        */
        $sql  = "SELECT DISTINCT uID, ulogin, upasswd, ufirstname, uname FROM aboo_user WHERE ugodlike != 'YES' " . $condition . " ORDER BY uname /*LIMIT 0,5*/"; // all non godlike
        $conn->execQuery($sql);
        
        $i = 0;
        $array_users = array();
        while ($field = $conn->getNextRecord()) {
            $sql2  = "SELECT uadminright, dID_FK FROM aboo_user,aboo_user_domain WHERE uID = uID_FK AND uID = " . $field["uID"] /*." order by uID "*/;
            $conn2->execQuery($sql2);
            
            $j = 0;
            while ($field2 = $conn2->getNextRecord()) { 
                if ($field2["uadminright"] == "USER") { 
                    $array_users[$i]["domain"][$j]["dID"] = $field2["dID_FK"];
                    $array_users[$i]["domain"][$j]["right"] = 'USER'; // could not be something else
                }
                $j++;
            }
            
            $sql2  = "SELECT COUNT(cID) as howmutch FROM aboo_contact WHERE uID_FK = " . $field["uID"];
            $conn2->execQuery($sql2); 
            $field2 = $conn2->getNextRecord();
            $array_users[$i]["nbcontacts"] = $field2["howmutch"];
            
            $array_users[$i]["id"] = $field["uID"];
            $array_users[$i]["login"] = convertStringFromDB($field["ulogin"]);
            $array_users[$i]["passwd"] = convertStringFromDB($field["upasswd"]);
            $array_users[$i]["firstname"] = convertStringFromDB($field["ufirstname"]);
            $array_users[$i]["name"] = convertStringFromDB($field["uname"]);
            $i++;
            $conn2->freeResult();
        }
        $conn->freeResult();

        return $array_users;
    }
    

    function getEverybodyList($conn, $conn2) {
    // returns an array of all users, admin and godlike info with all domains rights

        $sql  = "SELECT DISTINCT uID, ulogin, upasswd, ufirstname, uname, uemail, umaxcontacts, ugodlike, ulanguage FROM aboo_user";
        //echo $sql;
        $conn->execQuery($sql);
        
        $i = 0;
        $array_users = array();
        while ($field = $conn->getNextRecord()) {
            $array_users[$i]["id"] = $field["uID"];
            $array_users[$i]["login"] = $field["ulogin"];
            $array_users[$i]["passwd"] = $field["upasswd"];
            $array_users[$i]["firstname"] = $field["ufirstname"];
            $array_users[$i]["name"] = $field["uname"];
            $array_users[$i]["email"] = $field["uemail"];
            $array_users[$i]["max"] = $field["umaxcontacts"];
            $array_users[$i]["godlike"] = $field["ugodlike"];
            $array_users[$i]["lang"] = $field["ulanguage"];
            
            
            $sql2  = "SELECT COUNT(cID) as howmutch FROM aboo_contact WHERE uID_FK = " . $field["uID"];
            $conn2->execQuery($sql2); 
            $field2 = $conn2->getNextRecord();
            //echo $field2["howmutch"];
            $array_users[$i]["nbcontacts"] = $field2["howmutch"];
            
            
            $sql2  = "SELECT uadminright, dID_FK FROM aboo_user LEFT JOIN aboo_user_domain ON uID = uID_FK WHERE uID = " . $field["uID"];
            //echo $sql2;
            $conn2->execQuery($sql2);
            
            $j = 0;
            while ($field2 = $conn2->getNextRecord()) {   
                $array_users[$i]["domain"][$j]["dID"] = $field2["dID_FK"];
                $array_users[$i]["domain"][$j]["right"] = $field2["uadminright"];
                $j++;
            }
            $i++;
            $conn2->freeResult();
        }
        $conn->freeResult();
        
        return $array_users;
    }
  
