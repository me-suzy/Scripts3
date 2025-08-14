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
    
    // clean export_files directory from files that $s_user should have generated before
    $dir_handle = opendir("./export_files/");
    while ($file_name = readdir($dir_handle)) {
        $minus_pos = strpos($file_name, "-");
        $expected_id = substr($file_name, 0, $minus_pos);
        if ($expected_id == $s_user->getUserId()) {
            unlink("./export_files/" . $file_name);
        }
    }
    closedir($dir_handle);
	
	session_unregister("s_export_file_name");
	
	
	$lab = loadTemplateLabels($s_lang, "export1");
	include ("templates/export1.tmpl");
	
?>
