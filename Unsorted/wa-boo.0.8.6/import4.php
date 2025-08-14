<?php

	/*
        import3.php - contacts importing
        by Roulaizze <aaaxl@nocive.com> 
  */

	include ("includes/global_vars.php");
	include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("classes/domain_class.php");
    include ("classes/contact_class.php");
    include ("includes/fotools.php");

	session_start();
	
	global $s_combo_fields;
	global $G_list_bg_color;
	
	if (!session_is_registered("s_user") || !session_is_registered("s_array_import")) {
		msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }	

    $conn = new Database();
    
    $s_import_params["user_cat"] = $i_import_category ;

	// check we have only one of each selected field and the name field. This implicitly check there is at least on field (the name one)
	
	$checkname = false;
	$toomany   = false;
	while (list($key, $value) = each($column)) {
	    $multi = 0;
	    for ($i = 0; $i < count($column) ; $i++) {       // checks for each combo value if it exists more than on time in the 'column' array
	        if ($value == $column[$i] && $value != -1) { // the -1 value is "not selected", then can be multiple
	            $multi++;   
	        }
	        if ($multi > 1) {   // whe have more than one instance of one field 
	            $toomany = true;
	            break;
	        }
	    }
	    if (getFieldName($value) == "cname") {
            $checkname = true;
        }	        
	}
	if ($toomany) {
        msgBox ("1", "", $s_boxLab[18] /* You can't import the same field more than one time ! */,
            $s_boxLab[3] /* Error ! */, "error",
            $s_boxLab[5] /* << Back */, "import3.php");
            die;
    }    	
	if (!$checkname) {
        msgBox ("1", "", $s_boxLab[19] /* 'Name' field is required. Please select it */,
            $s_boxLab[3] /* Error ! */, "error",
            $s_boxLab[5] /* << Back */, "import3.php");
            die;
    }

	

	$conn = new Database();
	
	$s_column = $column; // put all columns submited in session
	session_register("s_column");
	
	
	// clean delete of firstline if asked with the checkbox
	$clean_import_array = array();
	$cpt1 = 0;
    
	if (isset($i_firstline) && $i_firstline == "1") {
		for ($i = 1 ; $i < count($s_array_import) ; $i++) {
			for ($j = 0 ; $j < $s_import_params["nbcolumns"] ; $j++) {
				$clean_import_array[$cpt1][$j] = $s_array_import[$i][$j];
			}
			$cpt1++;
		}
		$s_array_import = $clean_import_array;
	}

    // eliminate rows that have empty name field
	$clean_import_array = array();
	$cpt2 = 0;
	
    for ($i = 0 ; $i < count($s_array_import) ; $i++) {
        $keep_it = false;
        $temp_array = array();
		for ($j = 0 ; $j < $s_import_params["nbcolumns"] ; $j++) {
		    $temp_array[$j] = $s_array_import[$i][$j]; // we write in a temp array the current row
		    if (getFieldName($column[$j]) == "cname" && $s_array_import[$i][$j] != "" ) { // but we check cname field is not empty
			    $keep_it = true;
			}
		}
		if ($keep_it == true) {
            for ($k = 0 ; $k < $s_import_params["nbcolumns"] ; $k++) {		    
		        $clean_import_array[$cpt2][$k] = $temp_array[$k];
		    }
		    $cpt2++;
	    }
	}
	$s_array_import = $clean_import_array; // end of work, the original array is replaced by the clean one
  
    // inits of duplicate stage are outside the if for import5.php compliance
    $the_name_field = "";
	$the_firstname_field = "";
	$the_email_field = "";
	$duplicate = 0;
	$s_duplicate_array = array();
    session_register("s_duplicate_array"); // the duplicate array is now in session
    
    if ($s_import_params["duplicates"]) {  // wa-boo is setuped to look duplicates if possible
	
        // enter duplicate stage
    	
    	
    	for ($i = 0 ; $i < count($s_column) ; $i++) {
    		if (getFieldName($column[$i]) == "cname")
    			$the_name_field = $i;
    		if (getFieldName($column[$i]) == "cfirstname") // this field is not tested for duplicates. If it exists, it's only displayed to help choosing.
    			$the_firstname_field = $i;
            if (getFieldName($column[$i]) == "cemail")
    			$the_email_field = $i;			
    	}
    	
    	$sql = "SELECT cID, cname, cemail, cfirstname FROM aboo_contact WHERE uID_FK=" . $s_user->getUserId(); // all user contacts
    	$conn->execQuery($sql);
    	
    	// now build the duplicates. Even if we don't use them (state of base with already duplicates). This condition is tested in the .tmpl file
    	while ($field = $conn->getNextRecord()) {
    	    
    		for ($n = 0 ; $n < count($s_array_import) ; $n++) { // check all the array rows
    		    // name simplifications for tests below
    		    unset($db_name );
    		    unset($db_first);
    		    unset($db_email);
    		    unset($fi_name );
    		    unset($fi_first);
    		    unset($fi_email);
    		    $db_name  = trim(strtolower($field["cname"]));
    		    $db_first = trim(strtolower($field["cfirstname"]));
    		    $db_email = trim(strtolower($field["cemail"]));
    		    $fi_name  = trim(strtolower($s_array_import[$n][$the_name_field])); 
    		    $fi_first = trim(strtolower($s_array_import[$n][$the_firstname_field])); 
    		    $fi_email = trim(strtolower($s_array_import[$n][$the_email_field])); 
    		    
    		    
    		    //echo "line array : " . $n . " file : name >" .trim(strtolower($s_array_import[$n][$the_email_field]))."<    email >". trim(strtolower($s_array_import[$n][$the_email_field])) . "<      db : name >"  . trim(strtolower($field["cname"])). "<  email >". trim(strtolower($field["cemail"])) .  "<<hr>";
    		
    		    // Next line check if current file contact is a duplicate. the empty name rows have previously been deleted - no risk to compare '' with '' :)
        		//if ((trim(strtolower($field["cname"])) == trim(strtolower($s_array_import[$n][$the_name_field])))  ||   (trim(strtolower($s_array_import[$n][$the_email_field])) != "" && (trim(strtolower($field["cemail"])) == trim(strtolower($s_array_import[$n][$the_email_field])))) ) {
    	        if ( ($fi_first != "" && $db_name == $fi_name && $db_first == $fi_first) || ($fi_email != "" && $db_name == $fi_name && $db_email == $fi_email)  ) {
    				// Now we build the duplicate array that we will pass in session to next page
    				// Only the id of following array (the duplicates array) will be passed in the form with value "file" or "db" 
    				// to know what to keep at the final stage (stage 5)
    				
    				// file values
    				$s_duplicate_array[$duplicate]["file_id"] = $n; // $duplicate is the postion of current duplicate - $n is the full array line number
    				$s_duplicate_array[$duplicate]["file_name"] = $s_array_import[$n][$the_name_field];
    				$s_duplicate_array[$duplicate]["file_firstname"] = $s_array_import[$n][$the_firstname_field];
    				$s_duplicate_array[$duplicate]["file_email"] = $s_array_import[$n][$the_email_field];
    				
    				// DB values
    				$s_duplicate_array[$duplicate]["db_id"] = $field["cID"]; 
    				$s_duplicate_array[$duplicate]["db_name"] = $field["cname"];
    				$s_duplicate_array[$duplicate]["db_firstname"] = $field["cfirstname"];
    				$s_duplicate_array[$duplicate]["db_email"] = $field["cemail"];
    				
    				$duplicate++;
    				//echo "<br> ** DOUBLON ** ---->   F : '" . $s_array_import[$n][$the_name_field] . "', '" . $s_array_import[$n][$the_email_field]. "' -- DB : '" . $field["cname"]. "' , '" . $field["cemail"] ."'<hr>";
    			}
    		}
    		//echo "<hr>";
    	}
    	/* for ($i = 0; $i < $duplicate ; $i++) {
    	    echo $i. " fichier - id, nom : " . $s_duplicate_array[$i]["file_id"]. ", ". $s_duplicate_array[$i]["file_name"] . " base - id, nom : ". $s_duplicate_array[$i]["db_id"]. ", " . $s_duplicate_array[$i]["db_name"] . "<br>";
    	} */
  	
  	} // end of duplicate stage

    $conn->close();
	
	
	if ($duplicate == 0) {
		redirect("import5.php");
	}
	
	$lab = loadTemplateLabels($s_lang, "import4");
	include ("templates/import4.tmpl");
?>
