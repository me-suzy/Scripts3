<?php

	/*
        
        by Roulaizze <aaaxl@nocive.com> 
  */
    include ("includes/global_vars.php");
    include ("includes/fotools.php");
	include ("classes/database_class.php");
    include ("classes/user_class.php");
        
	session_start();
		
	session_unregister("s_array_import");
	session_unregister("s_import_params");
	session_unregister("s_array_field");
	session_unregister("s_duplicate_array");
	
	if (!session_is_registered("s_user")) {
		msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
	//echo $s_lang;
	$lab = loadTemplateLabels($s_lang, "import1");
	include ("templates/import1.tmpl");
	
?>
