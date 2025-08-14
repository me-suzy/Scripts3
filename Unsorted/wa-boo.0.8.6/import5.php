<?php

	/*
        import4.php - contacts importing
        by Roulaizze <aaaxl@nocive.com> 
  */

    include ("includes/global_vars.php");
	include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("classes/domain_class.php");
    include ("classes/contact_class.php");
    include ("includes/fotools.php");

	session_start();

	if (!session_is_registered("s_user") || !session_is_registered("s_array_import")) {
		msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }	

	$conn = new Database();
	$not_inserted = array();
	
	// 1) delete all asked duplicate in base ... 
	$replaced = 0;
	$no_cpt = 0;
	
	for ($i = 0 ; $i < count($i_duplicate_radio) ; $i++) {
	    // if duplicate hunt opened && user selected he want control && radiobuttons are "file" flagged
	    if ($s_import_params["duplicates"] && $action == "CHECK"  && $i_duplicate_radio[$i] == "file") {
	        $deleted_contact = new Contact($s_duplicate_array[$i]["db_id"]);
			$deleted_contact->setContactUserId($s_user->getUserId()); // because deleteContact method needs it ;)
			$deleted_contact->deleteContact($conn);         // class is managing everything, for example if asked to delete no still existing contact (can happens in file multi-duplicates cases)
			$replaced++;    // a counter for template needs 
			//... and 2) build an array to know which rows, flaged "db"; wont be inserted in base in next step
	    } elseif ($s_import_params["duplicates"] && $action == "CHECK"  && $i_duplicate_radio[$i] == "db") { 
	        $not_inserted[$no_cpt] = $s_duplicate_array[$i]["file_id"]; // ["file_id"] returns the line of main array.
	        $no_cpt++;
	    }
	}

    // do the inserts
	for ($i = 0 ; $i < count($s_array_import) ; $i++) {
        //echo "s_duplicate_array[$i]["file_id"] : " . $s_duplicate_array[$i]["file_id"]
        if (!in_array ($i, $not_inserted)) { // do not import lines in duplicates if they are flaged "db" by user radio choice. Note that if action != CHECK, $not_inserted is empty
    		$fields_name = "cID, uID_FK, cprivacy, ccategory";
    		$fields_value = "''," . $s_user->getUserId() . ", 'PRIVATE', " . $s_import_params["user_cat"];      //on import4.php, we stored which was the category where to insert
    		for ($j = 0 ; $j < count($s_column) ; $j++) {
    			if ($s_column[$j] != "-1") {
    				$fields_name .= "," . getFieldName($s_column[$j]);
    				$fields_value .= ",'" . addslashes($s_array_import[$i][$j]) . "'";
    			}
    		}
    		$sql = "INSERT INTO aboo_contact (". $fields_name . ") VALUES (" . $fields_value. ")";
    		//echo 'ligne '.$i.' : '.$sql.'<br>';
    		$conn->execQuery($sql);
		
	    }
	}
	
	if ($action == "CHECK") {
	    $inserted_rows = count($s_array_import) - count($i_duplicate_radio) + $replaced;
	} else {
	    $inserted_rows = count($s_array_import);
	}
	
	
	$conn->close();
	
	session_unregister("s_array_import");
	session_unregister("s_import_params");
	session_unregister("s_array_field");
	session_unregister("s_duplicate_array");
	
	$lab = loadTemplateLabels($s_lang, "import5");
	include ("templates/import5.tmpl");
?>
