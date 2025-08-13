
		// table string (for future use)
		$phpauction_adminusers = "PHPAUCTION_adminusers";
		$phpauction_auctions = "PHPAUCTION_auctions";
		$phpauction_bids = "PHPAUCTION_bids";
		$phpauction_categories = "PHPAUCTION_categories";
		$phpauction_categories_plain = "PHPAUCTION_categories_plain";
		$phpauction_counters = "PHPAUCTION_counters";
		$phpauction_countries = "PHPAUCTION_countries";
		$phpauction_durations = "PHPAUCTION_durations";
		$phpauction_feedbacks = "PHPAUCTION_feedbacks";
		$phpauction_help = "PHPAUCTION_help";
		$phpauction_increments = "PHPAUCTION_increments";
		$phpauction_news = "PHPAUCTION_news";
		$phpauction_payments = "PHPAUCTION_payments";
		$phpauction_request = "PHPAUCTION_request";
		$phpauction_sessions = "PHPAUCTION_sessions";
		$phpauction_settings = "PHPAUCTION_settings";
		$phpauction_users = "PHPAUCTION_users";

  /*======================================================================
   *																							  *																	
   * Don't edit the code below unless you really know what you are doing  *
   *																							  *          															
   ======================================================================*/	

	//--
  if(strpos($PHP_SELF,"admin/")){
  	$password_file = "../".$include_path."passwd.inc.php";
  }else{
  	$password_file = $include_path."passwd.inc.php";
  }
  	
  	
  

  include($password_file);

  //-- Database connection

  if(!mysql_connect($DbHost,$DbUser,$DbPassword))
  {
  	$NOTCONNECTED = TRUE;
  }
  if(!mysql_select_db($DbDatabase))
  {
  	$NOTCONNECTED = TRUE;
  }
  
  #// RETRIEVE SETTINGS AND CREATE SESSION VARIABLES FOR THEM
	if(strpos($PHP_SELF,"admin/"))
	{
		include "../includes/fonts.inc.php";
		include "../includes/fontcolor.inc.php";
		include "../includes/fontsize.inc.php";
	}
	else
	{
		include "includes/fonts.inc.php";
		include "includes/fontcolor.inc.php";
		include "includes/fontsize.inc.php";
	}
  	
  	$query = "select * from PHPAUCTION_settings";
  	$RES = @mysql_query($query);
  	if($RES)
  	{
  		$SETTINGS = mysql_fetch_array($RES);

  		$std_font = "<FONT FACE=".$FONTS[substr($SETTINGS[std_font],0,1)]." 
  					 SIZE=".$FONTSIZE[substr($SETTINGS[std_font],1,1)]." 
  					 COLOR=".$FONTCOLOR[substr($SETTINGS[std_font],2,1)].">";
  		if(substr($SETTINGS[std_font],3,1) == 1)
  		{
  			$std_font .= "<B>";
  		}
  		if(substr($SETTINGS[std_font],4,1) == 1)
  		{
  			$std_font .= "<I>";
  		}

  		$nav_font = "<FONT FACE=".$FONTS[substr($SETTINGS[nav_font],0,1)]." 
  					 SIZE=".$FONTSIZE[substr($SETTINGS[nav_font],1,1)]." 
  					 COLOR=".$FONTCOLOR[substr($SETTINGS[nav_font],2,1)].">";
  		if(substr($SETTINGS[nav_font],3,1) == 1)
  		{
  			$nav_font .= "<B>";
  		}
  		if(substr($SETTINGS[nav_font],4,1) == 1)
  		{
  			$nav_font .= "<I>";
  		}

  		$footer_font = "<FONT FACE=".$FONTS[substr($SETTINGS[footer_font],0,1)]." 
  					 SIZE=".$FONTSIZE[substr($SETTINGS[footer_font],1,1)]." 
  					 COLOR=".$FONTCOLOR[substr($SETTINGS[footer_font],2,1)].">";
  		if(substr($SETTINGS[footer_font],3,1) == 1)
  		{
  			$footer_font .= "<B>";
  		}
  		if(substr($SETTINGS[footer_font],4,1) == 1)
  		{
  			$footer_font .= "<I>";
  		}

  		$tlt_font = "<FONT FACE=".$FONTS[substr($SETTINGS[tlt_font],0,1)]." 
  					 SIZE=".$FONTSIZE[substr($SETTINGS[tlt_font],1,1)]." 
  					 COLOR=".$FONTCOLOR[substr($SETTINGS[tlt_font],2,1)].">";
  		if(substr($SETTINGS[tlt_font],3,1) == 1)
  		{
  			$tlt_font .= "<B>";
  		}
  		if(substr($SETTINGS[tlt_font],4,1) == 1)
  		{
  			$tlt_font .= "<I>";
  		}

  		$err_font = "<FONT FACE=".$FONTS[substr($SETTINGS[err_font],0,1)]." 
  					 SIZE=".$FONTSIZE[substr($SETTINGS[err_font],1,1)]." 
  					 COLOR=".$FONTCOLOR[substr($SETTINGS[err_font],2,1)].">";
  		if(substr($SETTINGS[err_font],3,1) == 1)
  		{
  			$err_font .= "<B>";
  		}
  		if(substr($SETTINGS[err_font],4,1) == 1)
  		{
  			$err_font .= "<I>";
  		}

  		$sml_font = "<FONT FACE=".$FONTS[substr($SETTINGS[sml_font],0,1)]." 
  					 SIZE=".$FONTSIZE[substr($SETTINGS[sml_font],1,1)]." 
  					 COLOR=".$FONTCOLOR[substr($SETTINGS[sml_font],2,1)].">";
  		if(substr($SETTINGS[sml_font],3,1) == 1)
  		{
  			$sml_font .= "<B>";
  		}
  		if(substr($SETTINGS[sml_font],4,1) == 1)
  		{
  			$sml_font .= "<I>";
  		}

  		session_name($SESSION_NAME);
  		session_register("SETTINGS","std_font");
  	}
  	
  	
  	#// PhpAdsNew includes
	if(strpos($PHP_SELF,"admin/"))
	{
		require("../phpAdsNew/config.inc.php"); 
 		require("../phpAdsNew/view.inc.php"); 
 		require("../phpAdsNew/acl.inc.php");  
 	}
 	else
 	{
		require("./phpAdsNew/config.inc.php"); 
 		require("./phpAdsNew/view.inc.php"); 
 		require("./phpAdsNew/acl.inc.php");  
 	}


  if(strpos($PHP_SELF,"admin/"))
  {
  	include("../includes/currency.inc.php");
  }
  else
  {
  	include("./includes/currency.inc.php");
  }

?>
