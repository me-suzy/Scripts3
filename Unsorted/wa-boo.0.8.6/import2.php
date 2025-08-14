<?php

	/*
        import2.php - contacts importing
        by Roulaizze <aaaxl@nocive.com> 
  */

	include ("includes/global_vars.php");
	include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("classes/domain_class.php");
    include ("classes/contact_class.php");
    include ("includes/fotools.php");

	session_start();


	if (!session_is_registered("s_user")) {
		msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }

    if ($importfile == none) {
        msgBox ("1", "", $s_boxLab[17] /* Unable to open file */,
            $s_boxLab[3] /* Error ! */, "error",
            $s_boxLab[5] /* << Back */, "import1.php");
            die;
    }

    if ($i_separator == "") {
        msgBox ("1", "", $s_boxLab[53] /* Please choose a separator */,
            $s_boxLab[3] /* Error ! */, "error",
            $s_boxLab[5] /* << Back */, "import1.php");
            die;
    }

    if ($i_separator == "t") {
        $i_separator = "\t";
    }

    $conn = new Database();

	$s_array_import = array(); // the main array buit from file
	$current_row = 0;
	$uploaded_file = fopen ($importfile, "r");
	while ($data = fgetcsv ($uploaded_file, 4096, $i_separator)) {
		$num_of_columns = count ($data);
		for ($current_column=0; $current_column < $num_of_columns; $current_column++) {
			$s_array_import[$current_row][$current_column] = $data[$current_column];
		}
		$current_row++;
	}
	fclose ($uploaded_file);
	
	$s_import_params["nbrows"] = $current_row;
	$s_import_params["nbcolumns"] = $num_of_columns;
	
	// duplicates rules
	if ($G_control_duplicates == "YES" && $s_user->allowDuplicatesImport($conn)) {// wa-boo is setup to look after duplicates && there is no duplicates in the actual base
	    $s_import_params["duplicates"] = true;
	} else {
	    $s_import_params["duplicates"] = false;
	}
	    
	
	session_register("s_array_import", "s_import_params"); // now whe have in session the array and parameters to work

    
    $conn = new Database();
    
    // simplify values used below
    $max    = $s_user->getUserMaxContacts();
    $temp = $s_user->haveDependentContacts($conn);
    $temp == "" ? $actual = 0 : $actual = $temp;
    $rows   = $s_import_params["nbrows"];
    $cols   = $s_import_params["nbcolumns"];
    
    $lab = loadTemplateLabels($s_lang, "import2");
    include ("templates/import2.tmpl");
?>
