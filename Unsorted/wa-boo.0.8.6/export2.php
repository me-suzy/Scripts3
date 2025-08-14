<?php

	/*
        
        by Roulaizze <aaaxl@nocive.com> 
  */
    include ("includes/global_vars.php");  
    include ("includes/fotools.php");
	include ("classes/database_class.php");
    include ("classes/user_class.php");
        
	session_start();
		
	if (!session_is_registered("s_user")) {
		msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
	
	$conn  = new Database();
	
	if ($i_separator == "t") {
        $i_separator = "\t";
    }
	$k = 0;
	
	if ($i_export == "private") {
	    $sql  = "SELECT DISTINCT cfirstname, cname, caddr1, caddr2, czip, ccity, ccountry, cemail, ";
	    $sql .= "cinstantmsg, cwww, cphone, ccell, cphonepro, cfax, cfirm, cposition, cmisc, cprivacy, ccategory ";
	    $sql .= "FROM aboo_contact WHERE (cprivacy = 'PRIVATE' AND uID_FK = " . $s_user->getUserId() .")";
	} else { // the session query
	    $sql = $s_sql;
	}
	//echo $sql;
	$conn->execQuery($sql);

    $s_export_file_name  = "./export_files/" . $s_user->getUserId() . "-" . time();
    $s_export_file_name .= "-" . rand();
    $s_export_file_name .= ".csv";
    session_register("s_export_file_name");
	
	//die($s_export_file_name);
	
	$exportf = fopen ($s_export_file_name, "w");
	
	
	if ($i_firstline) {
    	for ($i = 0 ; $i < count($s_combo_fields) ; $i++)	{    
    	        $temp = fwrite ($exportf, getFieldLabel($i) . $i_separator );
    		}
    	$temp = fwrite ($exportf, "CATEGORY" . $i_separator );
    	$temp = fwrite ($exportf, "OWNER" . $i_separator );
    	$temp = fwrite ($exportf, "PRIVACY" . "\n" );
    }

	while ($field = $conn->getNextRecord()) {

        for ($i = 0 ; $i < count($s_combo_fields) ; $i++)	{ 
            $clean_data = str_replace($i_separator, " ", $field[$i]);  //replace all separators by space
	        $temp = fwrite ($exportf, $clean_data . $i_separator );
		}

    	$temp = fwrite ($exportf, $field["ccategory"] . $i_separator );
    	$temp = fwrite ($exportf, $field["uID_FK"] . $i_separator );
    	$temp = fwrite ($exportf, $field["cprivacy"] . "\n" );


	}
	$conn->freeResult();
	
	
	fclose($exportf);
    
	$lab = loadTemplateLabels($s_lang, "export2");
	include ("templates/export2.tmpl");
	
	
?>
