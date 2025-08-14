<?php
    /* 
        contacts.php - contacts management
        by Roulaizze <aaaxl@nocive.com> & laGregouille <lagregouille@free.fr>
    */
    include ("includes/global_vars.php");
    include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("classes/contact_class.php");
    include ("includes/fotools.php");
    
	  session_start();
	
	  global $G_default_langage;
	
    /* AntiHack Protection */
    if (!session_is_registered("s_user") || !session_is_registered("s_previous_page")) {  // session hacking / error
		msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
    if (($s_previous_page != "contacts.php") && ($s_previous_page != "user_properties.php") && ($s_previous_page != "user_color.php")) {  // session hacking / error
		msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
	/* End of AntiHack protection */
	
    $conn = new Database();
    $conn2 = new Database();
    
    // registering temp array color for next save
    if (!session_is_registered ("s_temp_color")) {
        session_register("s_temp_color");
    }
    // display of modified color prefs
    $s_temp_color["menu"]          = ($menubgcolor      != "" ? "#" . $menubgcolor    : $s_user_context["color"]["menu"]);  
    $s_temp_color["button"]        = ($buttonbgcolor    != "" ? "#" . $buttonbgcolor  : $s_user_context["color"]["button"]);
    $s_temp_color["stdfontcolor"]  = ($stdfontcolor     != "" ? "#" . $stdfontcolor   : $s_user_context["color"]["stdfontcolor"]);
    $s_temp_color["stdfontsize"]   = ($stdfontsize      != "" ? "#" . $stdfontsize    : $s_user_context["color"]["stdfontsize"]);
    $s_temp_color["listfontcolor"] = ($listfontcolor    != "" ? "#" . $listfontcolor  : $s_user_context["color"]["listfontcolor"]); 
    $s_temp_color["listfontsize"]  = ($listfontsize     != "" ? "#" . $listfontsize   : $s_user_context["color"]["listfontsize"]);         

    
    if (!isset($properties_whichaction) || $properties_whichaction == "" ) {   // all cases below are cases with action, means not arriving vith the clean url users.php
        $properties_whichaction = "EDIT";
    }
    
    if ($properties_whichaction == "EDIT") {
        
        $user_context = $s_user->getUserSession_preferences();
        //for ($i = 0; $i < count($user_context) ; $i++ ) { echo "<br> user_context[".$i."] : " . $user_context[$i] ;}
        
        $user_list_prefs =    Array();
        $user_alt_prefs =     Array();   
        $i_user_cat =         $s_user->getUserCategories();
        $user_prefs =         $s_user->getUserPreferences();
        $user_list_prefs =    $s_user->getUserListPreferences();
        $user_alt_prefs =     $s_user->getUserAltPreferences();
        $i_chkPriCt =        ($user_prefs["private_chk"]    == "priCtOn" ? "priCtOn" : "priCtOff");
        $i_chkPubCt =        ($user_prefs["public_chk"]     == "pubCtOn" ? "pubCtOn" : "pubCtOff");
        $i_chkDomCt =        ($user_prefs["domain_chk"]     == "domCtOn" ? "domCtOn" : "domCtOff");
        $i_search_field =    ($user_prefs["searched_field"] == "FIELD_NAME" ? "FIELD_NAME" : "FIELD_ALL");
        $i_search_position = ($user_prefs["position_radio"] == "POSITION_START" ? "POSITION_START" : "POSITION_ANYWHERE");
        $i_search_category =  $user_prefs["category_combo"];                    
        $i_name_case =       ($user_prefs["name_case"]      == "UPPER_CASE" ? "UPPER_CASE" : "AS_TYPED_CASE");
        $i_page_size =        $user_prefs["page_size"]     ;
        $i_confirm_del =     ($user_prefs["confirm_del"]    == "confirmDelOn"  ? "confirmDelOn"  : "confirmDelOff");
        $i_language =         $s_user->getUserLanguage();

        
    } else if ($properties_whichaction == "SAVE") {  

        if (antiHack($i_page_size,"integer") == "error" || $i_page_size > 99) {  // session hacking / error
    		msgBox ("1", "", $i_page_size . $s_boxLab[29] /* is not matching with<br>'number of contacts per page' field format ! */,
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[54] /* << Modify */, "user_properties.php");
                die;
        }
        if (antiHack($i_std_font_size,"integer") == "error" || $i_std_font_size > 99) {  // session hacking / error
    		msgBox ("1", "", $i_std_font_size . $s_boxLab[30] /* is not matching with<br>Main font Size field format ! */,
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[54] /* << Modify */, "user_properties.php");
                die;
        }
        if (antiHack($i_list_font_size,"integer") == "error" || $i_list_font_size > 99) {  // session hacking / error
    		msgBox ("1", "", $i_list_font_size . $s_boxLab[31] /* is not matching with<br>Alt font Size field format ! */,
                $s_boxLab[3] /* Error ! */, "error",
                $s_boxLab[54] /* << Modify */, "user_properties.php");
                die;
        }                
        
        if (!isColor($i_menu_color) || !isColor($i_button_color) || !isColor($i_std_font_color) || !isColor($i_list_font_color)) {
    		msgBox ("1", "", $s_boxLab[32] /* Invalid Color format. Please follow '#XXXXXX' format, <br>where X is a figure or a letter of set {A,B,C,D,E;F}<br><br>Exemple : #03A7F8 */,
            $s_boxLab[3] /* Error ! */, "error",
            $s_boxLab[5] /* << Back */, "user_properties.php");
            die;
    }
        
        // setting up user preferences for data save
        $user_prefs["private_chk"]     = ($i_chkPriCt        == "priCtOn"             ? "priCtOn"        : "priCtOff");
        $user_prefs["public_chk"]      = ($i_chkPubCt        == "pubCtOn"             ? "pubCtOn"        : "pubCtOff");
        $user_prefs["domain_chk"]      = ($i_chkDomCt        == "domCtOn"             ? "domCtOn"        : "domCtOff");
        $user_prefs["searched_field"]  = ($i_search_field    == "FIELD_NAME"          ? "FIELD_NAME"     : "FIELD_ALL");
        $user_prefs["position_radio"]  = ($i_search_position == "POSITION_START"      ? "POSITION_START" : "POSITION_ANYWHERE");
        $user_prefs["category_combo"]  =  $i_search_category;
        $user_prefs["name_case"]       = ($i_name_case       == "UPPER_CASE"          ? "UPPER_CASE"     : "AS_TYPED_CASE");
        $user_prefs["page_size"]       =  $i_page_size;
        $user_prefs["confirm_del"]     = ($i_confirm_del    == "confirmDelOn"         ? "confirmDelOn"  : "confirmDelOff");
        
        $color_user_pref["menu"]          =  $i_menu_color;
        $color_user_pref["button"]        =  $i_button_color;
        $color_user_pref["stdfontcolor"]  =  $i_std_font_color;
        $color_user_pref["stdfontsize"]   =  $i_std_font_size;
        $color_user_pref["listfontcolor"] =  $i_list_font_color;
        $color_user_pref["listfontsize"]  =  $i_list_font_size;
        
        
        
        // setting changes in user object, and save it
        $s_user->setUserPreferences($user_prefs);
        $s_user->setUserCategories($i_user_cat);
        $s_user->setUserListPreferences($i_list_field);
        $s_user->setUserAltPreferences($i_alt_field);
        $s_user->setUserLanguage($i_language);
        $s_user->setUserColorPreferences($color_user_pref);

        $s_user->saveUserData($conn);
        
        $s_user_context["name_case"]   = $user_prefs["name_case"];    // affected directy in context because we want to use it now (before next login) (dirty but usefull :)
        $s_user_context["page_size"]   = $user_prefs["page_size"];    // affected directy in context because we want to use it now (before next login) (dirty but usefull :)
        $s_user_context["confirm_del"] = $user_prefs["confirm_del"];  // affected directy in context because we want to use it now (before next login) (dirty but usefull :)
        
        $s_lang = $s_user->getUserLanguage();
        $s_boxLab = loadTemplateLabels($s_lang, "msgbox"); // load msg box labels in correct language
        
        $temp = $s_user->getUserColorPreferences();
        $s_user_context["color"]["menu"]          = $temp["menu"];
        $s_user_context["color"]["button"]        = $temp["button"];
        $s_user_context["color"]["stdfontcolor"]  = $temp["stdfontcolor"];
        $s_user_context["color"]["stdfontsize"]   = $temp["stdfontsize"];
        $s_user_context["color"]["listfontcolor"] = $temp["listfontcolor"];
        $s_user_context["color"]["listfontsize"]  = $temp["listfontsize"];        
        session_unregister("s_temp_color");
        
        //$s_user->loadUserData($conn, $conn2); // after saving, reload
        // 3 lines below are old work, when domains were not keeped..
        // $s_user->setUserSession_preferences($s_user->getUserPreferences());
        // load user preferences & categories into context array
        // $s_user_context = $s_user->getUserSession_preferences();
        //echo "yop" . $s_lang;
        redirect("contacts.php"); 

    } 
    
    $lab = loadTemplateLabels($s_lang, "user_properties");
    
    include ("templates/user_properties.tmpl");
    $s_previous_page = "user_properties.php";
?>