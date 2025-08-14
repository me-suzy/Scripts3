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
    
    $user_cat     = $s_user->getUsercategories();
    $user_domains = $s_user->getUserDomains();
    
    $lab = loadTemplateLabels($s_lang, "cleaning");
	  include ("templates/cleaning.tmpl");
	
?>
