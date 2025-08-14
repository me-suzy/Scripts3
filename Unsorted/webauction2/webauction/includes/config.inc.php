<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

   //* Diese Zeile Ändern:
   
     $SITE_NAME_MANUELL="webAuction.de.vu";          //Name Ihres Auktionshauses
     $auction_main_directory = "/verzeichnis/";      //Hauptverzeichnis der Auktion (ending slash!)


   //* die folgenden Zeilen nur ändern, wenn Ihre Seiten-Struktur vom Standard abweicht


   $SITE_NAME=$HTTP_SERVER_VARS["SERVER_NAME"];                  
   $auction_main_path = $DOCUMENT_ROOT.$auction_main_directory;  

   $SITE_URL  = "http://".$HTTP_SERVER_VARS["HTTP_HOST"].$auction_main_directory;

  $include_path = $auction_main_path."includes/";

  $image_upload_path = $auction_main_path."uploaded/";
  $uploaded_path = "uploaded/";
  $MAX_UPLOAD_SIZE = 100000;
  $password_file = $include_path."passwd.inc.php";

  $logFileName = $auction_main_path."cron.log";


  if(strpos($PHP_SELF,"/admin/")){
     include("../includes/adminmail.inc.php");
  }else{
     include("./includes/adminmail.inc.php");
         }

  $expireAuction = 60*60*24*30;
  $sessionLifeTime = 60*60*24*2;
  $cronScriptHTMLOutput = true;


 if(strpos($PHP_SELF,"/admin/")){
     include("../includes/currency.inc.php");
  }else{
     include("./includes/currency.inc.php");
  }
  include($password_file);

  mysql_connect($DbHost,$DbUser,$DbPassword);
  mysql_select_db($DbDatabase);

  if(strpos($PHP_SELF,"/admin/")){
     include("../includes/sessions.inc.php");
  }else{
     include("./includes/sessions.inc.php");
  }

  $err_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"2\" COLOR=red>";
  $std_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"2\">";
  $sml_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"1\">";
  $tlt_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"4\">";
  $tlt2_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"3\">";
  $usr_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"4\" COLOR=\"#F6AF17\">";
  $nav_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"2\" COLOR=\"#FFFFFF\">";
  if(strpos($PHP_SELF,"/admin/")){
     include("../includes/increments.inc.php");
  }else{
     include("./includes/increments.inc.php");
  }
?>