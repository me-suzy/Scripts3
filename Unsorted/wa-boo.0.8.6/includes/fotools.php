<?php
    /* 
        fotools.php - Front Office tools
        by Roulaizze [aaaxl@nocive.com] 
    */
    
    
   function redirect($page) {
        header("Location: $page");
        exit;
    }

    function isColor($testColor) {
        $values_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");
        $testColor = strtoupper($testColor);
        
        if (strlen($testColor) != 7) {
            return false;
            break;
        }
        if (substr($testColor, 0, 1) != "#") {
            return false;
            break;
        }
        for ($i = 1 ; $i < 7 ; $i++) {
            $temp = substr($testColor, $i, 1);
            //echo $temp . "<br>";
            //if (in_array(substr($testColor, $i, 1), $values_array)) {
            if (!in_array($temp, $values_array)) {
                //echo $testColor . "<br>";
                return false;
                break;
            } 
        }
        //echo "fini";
        return true;
    }
        
    function buildColorTable() {
        $color_array = array();
        $temp_array = array();
        $red = array("00", "33", "66", "99", "CC", "FF");
        $green = array("00", "33", "66", "99", "CC", "FF");
        $blue = array("00", "33", "66", "99", "CC", "FF");
    
        while (list($key_r, $val_r) = each($red)) {
            reset($green);
            while (list($key_g, $val_g) = each ($green)) {
                reset($blue);
                while (list($key_b, $val_b) = each ($blue)) {
                    $currentColor = "#" . $val_r . $val_g . $val_b;
                    if ($fontcolor = buttonFontColor($currentColor)) {
                        $temp_array["bgcolor"]   = $currentColor;
                        $temp_array["fontcolor"] = $fontcolor;
                        $color_array[] = $temp_array;
                    } else {
                        msgBox ("1", "", $s_boxLab[39] /* Error in color table */,
                            $s_boxLab[4] /* Security ! */, "error",
                            $s_boxLab[5] /* << Back */, "index.php");
                            die;
                    }
                }
            }
        }
        $color_array = array_reverse($color_array);
        return $color_array;
    }
    
    function buttonFontColor($bgcolor) {
        $values_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");
        
        if (strlen($bgcolor) != 7) {
            return false;
            break;
        }
        if (substr($bgcolor, 0, 1) != "#") {
            return false;
            break;
        }
        //$cpt = 0;
        $total = 0;
        for ($i = 1 ; $i < 7 ; $i++) {
            $mySubStr = strtoupper(substr($bgcolor, $i /*+ $cpt*/, 1));
            if (!in_array($mySubStr, $values_array)) {
                return false;
                break;
            } elseif ($i == 1) { // red
                if     ($mySubStr == "A") {$total += 10;}
                elseif ($mySubStr == "B") {$total += 11;}
                elseif ($mySubStr == "C") {$total += 12;}
                elseif ($mySubStr == "D") {$total += 13;}
                elseif ($mySubStr == "E") {$total += 14;}
                elseif ($mySubStr == "F") {$total += 15;}
                else {
                    $total += $mySubStr;
                }
            } elseif ($i == 3 ) { // green big ponderation
                $pond = 9;
                if     ($mySubStr == "A") {$total += (10 );}
                elseif ($mySubStr == "B") {$total += (11 );}
                elseif ($mySubStr == "C") {$total += (12 + ($pond * 2));}
                elseif ($mySubStr == "D") {$total += (13 + ($pond * 2));}
                elseif ($mySubStr == "E") {$total += (14 + ($pond * 4));}
                elseif ($mySubStr == "F") {$total += (15 + ($pond * 4));}
                else {
                    $total += $mySubStr;
                }
            } elseif ($i == 5) { // blue medium ponderation
                $pond = 5;
                if     ($mySubStr == "A") {$total += (10 );}
                elseif ($mySubStr == "B") {$total += (11 );}
                elseif ($mySubStr == "C") {$total += (12 + $pond);}
                elseif ($mySubStr == "D") {$total += (13 + $pond);}
                elseif ($mySubStr == "E") {$total += (14 + $pond);}
                elseif ($mySubStr == "F") {$total += (15 + $pond);}
                else {
                    $total += $mySubStr;
                }                                
            }
        }
        if ($total < 36) {
            $fontColor = "#FFFFFF";
        } else {
            $fontColor = "#000000";
        } 
        return $fontColor;
    }
    
    // 3 functions to use for session string that have to be displayed in fields (i.e. : email messages and subjects, contacts fields)
    function HTML_cr($myString) {
        return str_replace(chr(10), "<br>", $myString);
    }

    function HTML_Quotes($myString) { // depends of PHP server configuration
        return (str_replace("\"", "&quot;", $myString));
    }
    
    function NoHTML_Quotes($myString) { // depends of PHP server configuration
        return (str_replace("&quot;", "\"", $myString));
    }
    function NoHTML_Spaces($myString) { // depends of PHP server configuration
        return (str_replace("&nbsp;", " ", $myString));
    }
    
    function gpc_conditionnal_addslashes($myString) { // depends of PHP server configuration
        if (get_magic_quotes_gpc()) {
            return $myString;
        } else {
            return addslashes($myString);
        }
    }    
        function gpc_conditionnal_stripslashes($myString) { // depends of PHP server configuration
        if (get_magic_quotes_gpc()) {
            return stripslashes($myString);
        } else {
            return $myString;
        }
    }    
    
    function convertStringToDB($pString) { // only trim the string or addslashes if get_magic_quotes_gpc()(server config) is off (false)
        if (get_magic_quotes_gpc()) {
            //echo "magic ".$pString;
            return trim($pString);
        } else {
            //echo "no magic ".$pString;
            return trim(addslashes($pString));
        }
    }
    
    function convertStringFromDB($pString) { // htmlentities is for not to execute HTML while displaying (doing nothing on quotes ' and ", str replace to allow the " car in inputs
        return trim(str_replace("\"", "&quot;",htmlentities($pString, ENT_NOQUOTES)));
    }
    
    function convertToUpperStringFromDB($pString) { 
        return trim(htmlentities(strtoupper(str_replace("\"", "&quot;", $pString)),ENT_NOQUOTES));
    }    
    
    function loadTemplateLabels($language, $page) {
        $language;
        $separator = "|";
        $page_labels = array();
        $filename = "lang/". $language ."/". $page . "_" . $language . ".lang.php";
        $cpt = 0;
        $fd = fopen ($filename , "r");
        while (!feof($fd)) {
            $temp = fgets($fd, 1024);
            $temp = substr($temp, 0, strlen($temp)-1); // remove the chr(10) 'CR' char
            if (substr($temp, strlen($temp)-1, 1) != $separator) {  // check the end char "|" is the last one
                echo "Wrong file separator in " . $filename . " in line # " . $cpt;
                fclose ($fd);
                die;
            } else { // working case : remove last char ('|');
                $temp = substr($temp, 0, strlen($temp)-1);
            }
            $temp = str_replace (" ", "&nbsp;", $temp);
            $temp = strrchr($temp, $separator); 
            $page_labels[] = substr($temp, 1); // begin after the separator    
            $cpt++;        
        }  
        fclose ($fd);
        /*
        for ($i = 0 ; $i < count($page_labels) ; $i++) {
            echo $page_labels[$i] . "<br>";
        }
        */
        
        return $page_labels;
    }
    
    function loadComboAndFields($language) {
        global $G_nb_of_DB_fields;
        global $s_combo_fields;
        $separator = "|";
        $filename = "lang/" . $language . "/ctfields_" . $language . ".php";
        $fd = fopen ($filename , "r");
        $i = 0; // because fields array begins with 0
        $nb_line_elements = 3; // 3 elements in each line to explode in array
        while ($i < $G_nb_of_DB_fields) {
            unset($cleanline);
            unset($cleanline_elements);
            $buffer = fgets($fd, 1024);
            $buffer = substr($buffer, 0, strlen($buffer) - 1); // remove the chr(10) 'CR' char

            if (substr($buffer, strlen($buffer)-1, 1) != $separator) {  // check the end char "|" is the last one
                echo "Wrong file separator in " . $filename . " in line # " . $i;
                fclose ($fd);
                die;
            } else { // working case : remove last char ('|');
                $buffer = substr($buffer, 0, strlen($buffer)-1);
            }
            $buffer = str_replace (" ", "&nbsp;", $buffer);
            
            $cleanline_elements = explode ($separator, $buffer , $nb_line_elements);

            $s_combo_fields[$i]["id"]         = $i;
            $s_combo_fields[$i]["fieldname"]  = $cleanline_elements[0];
            $s_combo_fields[$i]["fieldsize"]  = $cleanline_elements[1];
            $s_combo_fields[$i]["label"]      = $cleanline_elements[2];

            $i++;
        }
        fclose ($fd);
        session_register("s_combo_fields"); // now this array will be available everywhere in the application
    }
    
    function getFieldName($position) { 
        global $s_combo_fields;                                   // this session variable is already set
        return $s_combo_fields[$position]["fieldname"];           // the function returns field name in DB
    }

    function getFieldLabel($position) {                           // the function returns field label defined in language file
        global $s_combo_fields;
        return $s_combo_fields[$position]["label"];
    }
    
    function getFieldNumber($position) {                           // the function returns field label defined in language file
        global $s_combo_fields;
        return $s_combo_fields[$position]["id"];
    }
    
    function getFieldSize($position) {                           // the function returns field label defined in language file
        global $s_combo_fields;
        return $s_combo_fields[$position]["fieldsize"];
    } 
    
    function getUserMembership($uID) { // returns an array with everithing needed to display user membership
        $conn  = new Database();
        $conn2 = new Database();
        $testUser = new User($uID, "", "", "");
        $testUser->loadUserData($conn, $conn2);
        $all_domains = $testUser->getUserDomains();
        $all_domains[0];
        $i = 0;
        while (list($key, $value) = each($all_domains)) {
            $temp_domain = new Domain($value["id"]);
            $temp_domain->loadDomainData($conn);
            $temp_admins = $temp_domain->getDomainAdmins();
            $temp_users = $temp_domain->getDomainUsers();
            $list[$i]["domainID"] = $temp_domain->getDomainId();
            $list[$i]["domainName"] = $temp_domain->getDomainName();
            $list[$i]["misc"] = $temp_domain->getDomainMisc();
            $list[$i]["admins"] = $temp_admins;
            $list[$i]["users"] = $temp_users;
            for ($j = 0 ; $j < count ($temp_admins) ; $j++) {
                $temp_mailto_all .= $list[$i]["admins"][$j]["email"] . ", "; // admin email concatenation
            }
            for ($j = 0 ; $j < count ($temp_users) ; $j++) {
                $temp_mailto_all .= $list[$i]["users"][$j]["email"] . ", "; // users email concatenation
            }            
            $list[$i]["users"]["mailtoall"] = substr($temp_mailto_all, 0, (strlen($temp_mailto_all)-2)); // mailtoall build
            unset($temp_mailto_all);
            $i++;
        }
        return $list;
    }
    
    function isVisibleContact($userID, $contactID) {
        $conn  = new Database();
        $conn2 = new Database();
        if (!isset($contactID) || $contactID =="") {
            return false;
            exit();
        } else {    // $contactID looks correct
            $testContact = New Contact($contactID);
            $testContact->loadContactData($conn);
            if ($testContact->getContactPrivacy() == "PUBLIC") {
                return true;
                exit();
            } else {    // DOMAIN or PRIVATE
                if (isset($userID) && $userID != "" && $userID != "PUBLIC_USER") {
                    $testUser = new User($userID, "", "", "");
                    $testUser->loadUserData($conn, $conn2);
                    if ($testContact->getContactPrivacy() == "DOMAIN") {
                        $testUserDomains = $testUser->getUserDomains();
                        $testContactDomains = $testContact->getContactDomains();
                        for ($i = 0 ; $i < count($testUserDomains) ; $i++) {    //create a simple array for the each function below
                            $uDomains[$i] = $testUserDomains[$i]["id"];
                        }
                        while (list ($key, $value) = each ($uDomains)) {
                            if (in_array($value, $testContactDomains)) {
                                return true;
                                exit();
                            }
                         }
                         return false;
                         exit();
                    } elseif ($testContact->getContactPrivacy() == "PRIVATE") {
                        if ($testUser->getUserId() != $testContact->getContactUserId()) {
                            return false;
                            exit();
                        } else {
                            return true;
                            exit();
                        }
                    }
                } else { // bad $userID (not set or "" or "PUBLIC_USER" Hacking)
                    return false;
                    exit();
                }
            }
        } 
    }
    
    //function isEditableContact($contactID, $userID)
    
     

    
    function buildQuery ($mode, $userType, $userId, $fields, $filterPrivate, $filterCategory, $filterPublic, $checkedDomains, $searchString, $searchPosition, $searchField, $searchLetter, $rsPosition, $pageSize) {
        if ($userType == "REGISTERED_USER" && $userId) {
            $sql = "SELECT";
            if ($mode == "RETURN") {
                 $sql .= " DISTINCT cID, uID_FK, $fields"; // the "RETURN" Mode is building the main query
            } elseif ($mode == "COUNT") {
                $sql .= " COUNT(cID) as howmany";          // the "COUNT" Mode is counting how many contacts that are visible for curent user for each letter 
            } elseif ($mode == "EXPORT") {
                $sql .= " DISTINCT cfirstname, cname, caddr1, caddr2, czip, ccity, ccountry, cemail, cinstantmsg, cwww, cphone, ccell, cphonepro, cfax, cfirm, cposition, cmisc, cprivacy, ccategory, uID_FK";         // the "EXPORT" Mode is like the RETURN mode but select all fields
            }
            
            $sql .= " FROM aboo_contact LEFT JOIN aboo_contact_domain ON cID_FK = cID";
 
    		if (($filterPrivate == "priCtOn") || ($filterPublic == "pubCtOn") || isset($checkedDomains) ) { // other parameter to use (same forCOUNT and RETURN mode)
    		    $first_item = "YES";
    		    $sql_cond .= " WHERE (";
        		if ($filterPrivate == "priCtOn") {
        		    $sql_cond .= "(cprivacy = 'PRIVATE' AND uID_FK = " . $userId;
        		    if ($filterCategory != -1) {        // Only one Type selected
        				$sql_cond .= " AND ccategory=" . $filterCategory;
        			}
        			$sql_cond .= ") ";
        			$first_item = "NO";
        		}       		
        		
        		if ($filterPublic == "pubCtOn") {
        		    if ($first_item == "NO") {
        		        $sql_cond .= " OR";
        		    }
        		    $sql_cond .= " (cprivacy = 'PUBLIC')";
        		    $first_item = "NO";
        		}
        		
        		if ($checkedDomains != "") {
        			if ($first_item == "NO") {
        		        $sql_cond .= " OR";
        		    }
        		    $sql_cond .= " (dID_FK in (" . $checkedDomains . ") and cprivacy = 'DOMAIN')";
        		}
        		$sql_cond .= ") ";
        		
            } else { // nothing to return, exec a "0-row-returned-query" ;-)
                $sql_cond .= " WHERE 1 = 2"; // this Were condition is accepted by MySQL but returns nothing ;-) Used for both COUNT and RETURN modes
            }
            
            if ($mode == "COUNT") {     // used by the count request type to show corrects highlighted letters
                if (isset($searchLetter) && trim($searchLetter != "ALL")) {
                    $sql_cond .= " AND UPPER(cname) like '" . $searchLetter . "%'";
                }
            }

    	} else if ($userType == "PUBLIC_USER") { // PUBLIC_USER
    	    if ($mode == "RETURN") {
    			$sql .= "SELECT DISTINCT cID, uID_FK, " . $fields . " FROM aboo_contact WHERE cprivacy = 'PUBLIC' ";
    		} elseif ($mode == "COUNT") {
    		    $sql .= "SELECT COUNT(cID) as howmany FROM aboo_contact WHERE cprivacy = 'PUBLIC'"; 
    		    if (isset($searchLetter) && trim($searchLetter != "ALL")) {
                        $sql_cond .= " AND UPPER(cname) like '" . $searchLetter . "%'";
                }
    		}
        } else {
            die ("Error of user type in buildQuery function");
        }

        $sql .= $sql_cond;
        
        	    
    	// ----- search fields conditions
        if (isset($searchString) && (trim($searchString)!="")) {  // Restriction à la recherche
            if ($searchPosition == "POSITION_START")  {   
                $search_str = $searchString . "%";       // case begin with
    		} elseif ($searchPosition == "POSITION_ANYWHERE") {                           
    			$search_str = "%" . $searchString . "%";   // Case content
    		}
    		if ($searchField == "FIELD_NAME") { // Recherche sur le nom
    			$sql .= " and cname like '".$search_str."'";
    		} elseif ($searchField == "FIELD_ALL") {   // Recherche sur tous les champs
    			$sql .= " AND (cname like '" . $search_str . "' OR cfirstname like '" . $search_str . "'";
    			$sql .= " OR caddr1 like '" . $search_str . "' OR caddr2 like '" . $search_str . "'";
    			$sql .= " OR ccity like '" . $search_str . "' OR ccountry like '" . $search_str . "'";
    			$sql .= " OR czip like '" . $search_str . "' OR cemail like '" . $search_str . "'";
    			$sql .= " OR cinstantmsg like '". $search_str . "' OR cwww like '" . $search_str . "'";
    			$sql .= " OR cphone like '" . $search_str . "' OR ccell like '" . $search_str . "'";
    			$sql .= " OR cphonepro like '" . $search_str . "' OR cfax like '" . $search_str . "'";
    			$sql .= " OR cfirm like '" . $search_str . "' OR cposition like '" . $search_str . "'";
    			$sql .= " OR cmisc like '" . $search_str . "'";
    			$sql .= ")";
    		}
    	// Or the user clicks an alphabet letter
    	} elseif (isset($searchLetter) && trim($searchLetter != "ALL")) { //une lettre cliquée et pas all ("")
    		$sql .= " AND UPPER(cname) LIKE '".$searchLetter."%'";
    	}
    	$sql .= " ORDER BY cname";
    	
    	/*if (isset($current_position) && $current_position != "" && isset($page_size) && $page_size != "") {
        	$sql .= " LIMIT $current_position . ", " . $page_size;
        }*/
        
        // because the "COUNT" mode is searching for only 1 row, we initialize below values if nothing is send
    	if (!isset($rsPosition) || $rsPosition == "") {
    	    $rsPosition = 0;
    	}
    	if (!isset($pageSize) || $pageSize == "") {
    	    $pageSize = 1;
    	}
    	$sql .= " LIMIT " . $rsPosition . ", " . $pageSize;

        return $sql;
    }
    
    function printModifyContactIcon($ID, $FK) {  // returns modification's contact icon if the user have rights
        if ($ID == $FK) { 
            $answer = "<img src=\"images/modify_contact.gif\" border=\"0\" alt=\"Modifier\">";
        } else {
            $answer = false;
        }
        return $answer;
    }
    
    function printDeleteContactIcon($ID, $FK) {  // returns deletion's contact icon if the user have rights
        if ($ID == $FK) { 
            $answer = "<img src=\"images/delete.gif\" border=\"0\" alt=\"Supprimer\">";
        } else {
            $answer = false;
        }
        return $answer;
    }
    
    function checkEmail($email) {
        $email_regular_expression = "^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~ ])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~ ]+\\.)+[a-zA-Z]{2,4}\$";
        //return(eregi($email_regular_expression,$email)!=0);   
        //echo $myemail . "<br>"; 
        return (eregi($email_regular_expression,$email));
    }
    
    function tuned_email($email, $subject, $message, $fromfield) { // send a message to one ore an array of email addresses
        global $G_email_function;
        //echo "email dans function : " . $G_email_function;
        $email_array = array();
        if (!is_array($email)) { // transform into array of one item if single address sended
            $email_array[0] = $email;
        } else {
            $email_array = $email;
        }
            
        for ($i = 0 ; $i < count($email_array) ; $i++) {
            if ($G_email_function == "email") {
                if ($result = email("wa-boo", $email_array[$i], $subject, $message)) { // expéditeur sans domaine, rajouté par proxad
                } else {
                    $result = false;
                }
            } else if ($G_email_function == "mail") {
                $result = mail($email_array[$i], $subject, $message, "From: ". $fromfield ."\nReply-To: " . $fromfield);
                //echo  "la " . $email_array[$i]. " ". $subject ."<br>";
            }
        }
        return $result;
    }

    function antiHack($tested_field,$type) {
        //echo "tested_field : " . $tested_field;
        //echo "<br>strval(intval(tested_field : " . strval(intval($tested_field));
        if (strval(intval($tested_field))!= $tested_field) { //new test by roulaizze
            return "error";
        }
		if (isset($tested_field)) {
			if ($type == "integer") {
				for ($i = 0; $i<strlen($tested_field); $i++) {
					if ($tested_field[$i] < "0" || $tested_field[$i] > "9") {
						return "error";
					}
				}
			}
			if (stristr($tested_field," "))
				return "error";
			if (stristr($tested_field,"**"))
				return "error";
		}	
		return "ok";
	}

/* // only for php3 (v0.1 of wa-boo) - now besause of php4, don't need it anymore
    if (!function_exists("array_search")) {  // Searches haystack for needle and returns the key if it is found in the array, FALSE otherwise
        function array_search ($needle, $haystack, $strict = FALSE) {
            foreach($haystack as $key => $value) { 
            if ($strict) { 
                if ($value === $needle) {
                    return $key; 
                } else { 
                    if ($value == $needle){ 
                        return $key; 
                    } 
                } 
                    return FALSE; 
                } 
            }
        }
    }
*/    
    function concatenate($myArray) {    // concatenate with comma non empty element of $myArray
        $tempArray = Array();
        $tempArray = $myArray;
        if (count($myArray) == 0) {
            return false;
        } else {
            while (list ($key, $val) = each ($tempArray)) {
                if (trim($val) != "") {
                    $concat .=  $val . ",";
                }
            }
            $result = substr($concat, 0, strlen($concat) - 1);
            if (trim($result) != "") {
                return $result;
            } else {
                return false;
            }
        }
    }

    function tuneDisplay() { 
        global $G_server_OS;
        global $G_time_to_sleep;
        
        if ($G_server_OS == "win") {
            sleep($G_time_to_sleep);
        }
    }

    function msgBox ($nbbutton="1", $imgs_path="", $message="Error !", $title="", $icon="error", $btn_1="OK", $action_1="javascript:history.go(-1)", $btn_2="Cancel", $action_2="javascript:history.go(-1)") {
        global $s_user_context;
        switch ($icon) {
            case "question" :  
                $icon = "icon_question.gif";
                break;
            case "exclamation" :  
                $icon = "icon_exclamation.gif";
                break;
            case "error" :  
                $icon = "icon_error.gif";
                break; 
        } 
        
        echo "<style><br>";
        include($imgs_path . "includes/css.php" ); 
        echo "</style><br>";           
?>    
        <p>&nbsp;</p> 
        <div align="center">
          <table border="0" cellspacing="0" cellpadding="0" background="<? echo $imgs_path; ?>images/bg1.gif">         
            <tr>                   
              <td background="<? echo $imgs_path; ?>images/left_top_corner.gif" height="7"></td>
              <td background="<? echo $imgs_path; ?>images/h_line_top.gif" colspan="6" height="7"></td>
              <td background="<? echo $imgs_path; ?>images/right_top_corner.gif" height="7"></td>
            </tr>          
            <tr>                       
              <td background="<? echo $imgs_path; ?>images/v_line_left.gif" width="7"></td>
              <td class="stdtitle" colspan="6" bgcolor="<? echo $s_user_context["color"]["menu"] ?>" align="center"><? echo $title; ?></td>
              <td valign="top" background="<? echo $imgs_path; ?>images/v_line_right.gif" width="7"></td>
            </tr>          
            <tr>           
              <td background="<? echo $imgs_path; ?>images/v_line_left.gif" width="7"></td>
              <td colspan="6" height="7" background="<? echo $imgs_path; ?>images/h_line.gif"></td>
              <td valign="top" background="<? echo $imgs_path; ?>images/v_line_right.gif" width="7"></td>
            </tr>          
            <tr>            
              <td background="<? echo $imgs_path; ?>images/v_line_left.gif" width="7"></td>
              <td colspan="6">&nbsp;</td>
              <td valign="top" background="<? echo $imgs_path; ?>images/v_line_right.gif" width="7"></td>
            </tr>          
            <tr>      
              <td background="<? echo $imgs_path; ?>images/v_line_left.gif" width="7"></td>
              <td width="10">&nbsp;</td>
              <td width="34" valign="top"><img src="<? echo $imgs_path; ?>images/<? echo $icon; ?>"></td>
              <td width="10">&nbsp;</td>
              <td colspan="2" class="bolddarkredfont"><? echo $message; ?></td>
              <td width="10">&nbsp;</td>
              <td valign="top" background="<? echo $imgs_path; ?>images/v_line_right.gif" width="7"></td>
            </tr>        
            <tr>      
              <td background="<? echo $imgs_path; ?>images/v_line_left.gif" width="7"></td>
              <td colspan="6">&nbsp;</td>
              <td valign="top" background="<? echo $imgs_path; ?>images/v_line_right.gif" width="7"></td>
            </tr>
            <tr>     
              <td background="<? echo $imgs_path; ?>images/v_line_left.gif" width="7"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="left">  
              <?  if ($nbbutton == 2) { ?>
                <form method="post" action="<? echo $action_2; ?>">    
                  <input type="submit" name="btn2" value="<? echo $btn_2; ?>" class="userbutton">     
                </form>
                <?  } else { ?>
                  &nbsp;
                <?  } ?>   
              </td>
              <td align="right">
                <form method="post" action="<? echo $action_1; ?>">     	    
                  <input type="submit" name="btn1" value="<? echo $btn_1; ?>" class="userbutton">
                </form>
              </td>
              <td>&nbsp;</td>
              <td valign="top" background="<? echo $imgs_path; ?>images/v_line_right.gif" width="7"></td>
            </tr>    
            <tr>              
              <td height="7" background="<? echo $imgs_path; ?>images/left_bottom_corner.gif"></td>
              <td height="7" background="<? echo $imgs_path; ?>images/h_line_bottom.gif" colspan="6"></td>
              <td height="7" background="<? echo $imgs_path; ?>images/right_bottom_corner.gif"></td>
            </tr>  
          </table>
          </div>
    <?
    }
    ?>