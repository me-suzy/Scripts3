<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Main settings                  //
	//                                 //
	/////////////////////////////////////
		session_start();
		while (list($key, $val) = @each($HTTP_GET_VARS)) {$GLOBALS[$key] = ($key!="moderr"?@htmlspecialchars($val):$val);}
		while (list($key, $val) = @each($HTTP_POST_VARS)) {$GLOBALS[$key] = @htmlspecialchars($val);}
		while (list($key, $val) = @each($HTTP_POST_FILES)) {$GLOBALS[$key] = @htmlspecialchars($val);}
		while (list($key, $val) = @each($HTTP_SESSION_VARS)) {$GLOBALS[$key] = @htmlspecialchars($val);}
		
		require($INC."config/site_url.php");
		require($INC."config/mysql_config.php");
		require($INC."functions/functions_main.php");
		require($INC."functions/external_results.php");
		
		$TIME_DIFF = 86400; //24 hours in seconds 
		
		$REVENUE_MEMBER_ID = 0; //If you are a revenue member - change it to your real Revenue member's ID
		$REVENUE_FAMILY_FILTER = "off";//values may be 'on' or 'off'
		
		//dirs:
		
		
		$TEMPLATES_DIR = $INC."templates/";
		$DATA_DIR = $INC."data/";
		$BANNERS_DIR = $DATA_DIR."banners/";
		$LOGOS_DIR = $DATA_DIR."logos/";

		$BANNERS_PATH = $SITE_URL."data/banners/";
		$LOGOS_PATH = $SITE_URL."data/logos/";

		//end of dirs

		//Templates files:
		$ERROR_TMP = $TEMPLATES_DIR."error.html";
		//end of templates files
		
		//EMAIL TEMPLATES
		$EMAIL_MEMBER_REGISTRATION = $TEMPLATES_DIR."email_member_reg.txt";
		$EMAIL_MEMBER_REGISTRATION_SUBJECT = "Thank You for registering in PPC Search Engine";

		$EMAIL_MEMBER_BLOCK_LF = $TEMPLATES_DIR."email_member_block_lf.txt";
		$EMAIL_MEMBER_BLOCK_LF_SUBJECT = "Warning! Your Account has been blocked because of low balance value!";

		$EMAIL_MEMBER_BLOCK_M = $TEMPLATES_DIR."email_member_block_m.txt";
		$EMAIL_MEMBER_BLOCK_M_SUBJECT = "Warning! Your Account has been blocked by administrator!";
		
		$EMAIL_MEMBER_DELETE_SUBJECT = "Warning! Your Account has been deleted by administrator!";
		$EMAIL_MEMBER_DELETE = $TEMPLATES_DIR."email_member_deleted.txt";
		
		$MAIL_HEADERS = "From: Ettica.com";
		$ADMIN_MAIL = "ppc@ettica.com";
		
		$EMAIL_MEMBER_TRANSFER_COMPLETED_SUBJECT = "Thank You for money transfer!";
		$EMAIL_MEMBER_TRANSFER_COMPLETED = $TEMPLATES_DIR."email_member_transfer_completed.txt";
		
		$EMAIL_A_TRANSFER_COMPLETED = $TEMPLATES_DIR."email_a_transfer_completed.txt";
		$EMAIL_A_TRANSFER_COMPLETED_SUBJECT = "New transfer!";
		
		$LOGO_WIDTH = 120;
?>