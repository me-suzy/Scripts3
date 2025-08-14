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
	if ($cID !="" && antiHack($cID,"integer")=="error") {
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

    if ($s_previous_page != "contacts.php" && $s_previous_page != "contact_action.php") {  // URL hacking / error
		msgBox ("1", "", $s_boxLab[8] /* You can't access this page directly ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
    
    if (($ct_whichaction != "NEW") && ($ct_whichaction != "EDIT") && ($ct_whichaction != "DEL") && ($ct_whichaction != "SAVE")) {
		msgBox ("1", "", $s_boxLab[9] /* Please do not play with URLs */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
	// === End of AntiHack check ===
	
	$s_previous_page = "contact_action.php";
	
    $conn =            new Database();
    $conn2 =           new Database();
    
    $current_contact = new Contact($cID);                          // $cID is "" or with a value
    $current_contact->loadContactData($conn);
    
    $user_cat        = $s_user->getUserCategories();
    $user_domains    = $s_user->getUserDomains();                   // getUserDomains returns an array of array
    $user_prefs      = $s_user->getUserPreferences();
    
    if ($ct_whichaction == "DEL") {
        if ($current_contact->getContactUserId() != $s_user->getUserID()) {
            msgBox ("1", "", $s_boxLab[10] /* This contact is not yours. You can't delete it */,
                $s_boxLab[4] /* Security ! */, "error",
                $s_boxLab[6] /* OK */, "index.php");
                die;
        } else {
            if ($user_prefs["confirm_del"] == "confirmDelOn" && $i_confirm != "YES") {
                $msgBox_msg = $s_boxLab[40] /* Do you really want to delete contact */ . "<br><br>" . $current_contact->getContactFirstname() . " " . $current_contact->getContactName();
                msgBox ("2", "", $msgBox_msg,
                    $s_boxLab[0] /* Confirm */, "question",
                    $s_boxLab[51] /* Delete */, "contact_action.php?cID=" . $cID. "&ct_whichaction=DEL&i_confirm=YES",
                    $s_boxLab[52] /* Cancel */, "contacts.php");
                
            } elseif (($user_prefs["confirm_del"] == "confirmDelOn" && $i_confirm == "YES") || $user_prefs["confirm_del"] == "confirmDelOff") { 
                $current_contact->deleteContact($conn);                 // ask method to do the bad job ;-)
                unset($ct_whichaction);
                msgBox ("1", "", $s_boxLab[41] /* Contact sucessfully deleted */,
                    $s_boxLab[43] /* Result : */, "exclamation",
                    $s_boxLab[6] /* OK */, "contacts.php");
                die; 
            }
        }          
    }  
    // keeping all fields in session, to allow back-to-edit-form functionnality
    if ($cID              != "") { $s_contact["id"]             =                         ($cID);              }  // hiddent field
    if ($i_firstname      != "") { $s_contact["firstname"]      = gpc_conditionnal_stripslashes(HTML_Quotes($i_firstname));      }
    if ($i_name           != "") { $s_contact["name"]           = gpc_conditionnal_stripslashes(HTML_Quotes($i_name));           }
    if ($i_addr1          != "") { $s_contact["addr1"]          = gpc_conditionnal_stripslashes(HTML_Quotes($i_addr1));          }
    if ($i_addr2          != "") { $s_contact["addr2"]          = gpc_conditionnal_stripslashes(HTML_Quotes($i_addr2));          }
    if ($i_zip            != "") { $s_contact["zip"]            = gpc_conditionnal_stripslashes(HTML_Quotes($i_zip));            }
    if ($i_city           != "") { $s_contact["city"]           = gpc_conditionnal_stripslashes(HTML_Quotes($i_city));           }
    if ($i_country        != "") { $s_contact["country"]        = gpc_conditionnal_stripslashes(HTML_Quotes($i_country));        }
    if ($i_email          != "") { $s_contact["email"]          = gpc_conditionnal_stripslashes(HTML_Quotes($i_email));          }
    if ($i_www            != "") { $s_contact["www"]            = gpc_conditionnal_stripslashes(HTML_Quotes($i_www));            }
    if ($i_instantmsg     != "") { $s_contact["instantmsg"]     = gpc_conditionnal_stripslashes(HTML_Quotes($i_instantmsg));     }
    if ($i_phone          != "") { $s_contact["phone"]          = gpc_conditionnal_stripslashes(HTML_Quotes($i_phone));          }
    if ($i_cellular       != "") { $s_contact["cellular"]       = gpc_conditionnal_stripslashes(HTML_Quotes($i_cellular));       }
    if ($i_phonepro       != "") { $s_contact["phonepro"]       = gpc_conditionnal_stripslashes(HTML_Quotes($i_phonepro));       }
    if ($i_fax            != "") { $s_contact["fax"]            = gpc_conditionnal_stripslashes(HTML_Quotes($i_fax));            }
    if ($i_firm           != "") { $s_contact["firm"]           = gpc_conditionnal_stripslashes(HTML_Quotes($i_firm));           }
    if ($i_position       != "") { $s_contact["position"]       = gpc_conditionnal_stripslashes(HTML_Quotes($i_position));       }
    if ($i_privacy        != "") { $s_contact["privacy"]        = ($i_privacy);        }
    if ($i_misc           != "") { $s_contact["misc"]           = gpc_conditionnal_stripslashes(HTML_Quotes($i_misc));           }
    if ($i_contact_cat    != "") { $s_contact["cat"]            = ($i_contact_cat);    }
    
    //echo "addrsstripe : " . $s_contact["addr2"];
    
    if ($ct_whichaction != "EDIT") {
        if (!session_is_registered("s_contact")) {  // the first time of form edition    
            session_register ("s_contact");        
        } 
    }        


    if ($ct_whichaction == "EDIT" || $ct_whichaction == "NEW") {
        
        if ($cID != "" && $current_contact->getContactUserId() != $s_user->getUserID()) {   // trying to MODIFY an existing contact by a user who is not the user
            msgBox ("1", "", $s_boxLab[10] /* This contact is not yours. You can't delete it */,
                $s_boxLab[4] /* Security ! */, "error",
                $s_boxLab[6] /* OK */, "index.php");
                die;
        }
        $lab = loadTemplateLabels($s_lang, "contacts");
        include ("templates/contact_form.tmpl");

    } else if ($ct_whichaction == "SAVE") {

        if (($current_contact->getContactUserId() != "") && $current_contact->getContactUserId() != $s_user->getUserID()) { // trying to SAVE an existing contact by a user who is not the user
            msgBox ("1", "", $s_boxLab[10] /* This contact is not yours. You can't delete it */,
                $s_boxLab[4] /* Security ! */, "error",
                $s_boxLab[6] /* OK */, "index.php");
                die;
        }
        if ($i_email !="" && checkEmail($i_email) == false) {                             // check email syntax if exists
            msgBox ("1", "", $s_boxLab[13] /* Invalid email address ! */,
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[5] /* << Back */, "contact_action.php?ct_whichaction=EDIT");
                die;
        }

        if (trim($i_name) == "") {                                                      // name is mandatory
            msgBox ("1", "", $s_boxLab[14] /* Name field is required */,
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[5] /* << Back */, "contact_action.php?ct_whichaction=EDIT");
                die;
        }

        if ($i_privacy == "DOMAIN") {
            
            if (count($i_chkCtDomain) == 0) {       //Privacy = "DOMAIN" with no domains selected
                msgBox ("1", "", $s_boxLab[15] /* Please select at least one group !  */,
                    $s_boxLab[3] /* Error ! */, "error",
                    $s_boxLab[5] /* << Back */, "contact_action.php?ct_whichaction=EDIT");
                    die;
            }
        }
        
        /*
        echo "les domaines : ";
        for ($i = 0 ; $i < count($i_chkCtDomain) ; $i++) {
            echo $i_chkCtDomain[$i] . "<br>";
        }
        */
                
        $current_contact->setContactUserId      ($s_user->getUserId());
        
        $current_contact->setContactFirstname   ($i_firstname);
        $current_contact->setContactName        ($i_name);
        $current_contact->setContactAddr1       ($i_addr1);
        $current_contact->setContactAddr2       ($i_addr2);
        $current_contact->setContactZip         ($i_zip);
        $current_contact->setContactCity        ($i_city);
        $current_contact->setContactCountry     ($i_country);
        $current_contact->setContactEmail       ($i_email);
        $current_contact->setContactWww         ($i_www);
        $current_contact->setContactInstantmsg  ($i_instantmsg);
        $current_contact->setContactPhone       ($i_phone);
        $current_contact->setContactCell        ($i_cellular);
        $current_contact->setContactPhonepro    ($i_phonepro);
        $current_contact->setContactFax         ($i_fax);
        $current_contact->setContactFirm        ($i_firm);
        $current_contact->setContactPosition    ($i_position);
        $current_contact->setContactPrivacy     ($i_privacy);
        $current_contact->setContactMisc        ($i_misc);
        
       // echo "dans les données membres : " . $current_contact->getContactAddr2();
        
        $current_contact->setContactCategory    ($i_contact_cat);
                                                   
        $current_contact->setContactDomains     ($i_chkCtDomain);
        
        $current_contact->saveContactData($conn);

        unset($ct_whichaction);
        redirect("contacts.php");
    }
?>