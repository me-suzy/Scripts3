<?php

	/*
        
        by Roulaizze <aaaxl@nocive.com> 
  */
    include ("includes/global_vars.php");    
    include ("includes/fotools.php");
        
	session_start();
		
	if (!session_is_registered("s_user")) {
		msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
	

    $displayed_file = fopen ($s_export_file_name, "r");	

    while (!feof($displayed_file)) { 
        $line[] = fgets($displayed_file, 4096) . "<br>"; 
    }
    
	
	  fclose($displayed_file);
    unlink ($s_export_file_name);
    session_unregister("s_export_file_name");
    
    $lab = loadTemplateLabels($s_lang, "export3");
    include ("templates/export3.tmpl");
?>
