<?
/*

   Copyright (c), 1999, 2000, 2001 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
                                                                        
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
   
*/
?>
<head>
<TITLE>::PHPAUCTION INSTALLATION::</TITLE>
</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#08428C" LINK="#08428C" VLINK="#08428C" ALINK="#08428C" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">

<? 
        require ("../includes/messages.inc.php");


// Beginning of header ?>
<CENTER>

<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#EEEEEE">
        <TR BGCOLOR="#336699"> 
                <TD WIDTH="54%" VALIGN=MIDDLE BGCOLOR="#336699"><FONT SIZE="6" COLOR="#CCCCCC" FACE="Tahoma, Verdana"><B>PHPAuction 
                        2.0</B></FONT> </TD>
                <TD WIDTH="46%" VALIGN="BOTTOM" ALIGN=center> 
                        <DIV ALIGN="RIGHT"><FONT FACE="Verdana,Arial,Helvetica" SIZE=4 COLOR="#000066"> 
                                <B><FONT COLOR="#CCCCCC" SIZE="2">INSTALLATION ADD-ON</FONT></B></FONT> 
                        </DIV>
                </TD>
        </TR>
        <TR BGCOLOR="#C8D6E6"> 
                <TD VALIGN=TOP HEIGHT="17"><FONT FACE=Verdana SIZE=2 COLOR=#000000>&nbsp;</font></TD>
                <TD VALIGN=TOP HEIGHT="17" ALIGN=right><A HREF="../"><FONT FACE="Verdana,Arial,Helvetica" SIZE=2>Exit</A></TD>
        </TR>
</TABLE>
<BR>




<?      // STEP 1
        
        if ($step=="1") { $ERROR=""; }
        if ($step=="") { $step="1"; }

        // Check that all data is inserted in step 1
        if (($step=="2") && (($database_name=="") || ($database_username=="") || ($database_host=="") || ($admin_email=="") || ($site_name=="") || ($site_address==""))) 
                { $ERROR="<FONT COLOR=\"RED\" SIZE=\"5\" FACE=\"Verdana\">ERROR: Data Missing!"; $step="1"; 
                        if ($database=="use_existing_database") { $selected="selected"; }
                           else { $selected=""; }
                }

        // Check that database exists 
        if (($step=="2") && ($database=="use_existing_database")) 
        {
        			    @mysql_connect("$database_host","$database_username","$database_password");
                        $result = mysql_query("USE $database_name"); 
                        if(!$result) 
                        { $ERROR="<FONT COLOR=\"RED\" SIZE=\"5\" FACE=\"Verdana\">ERROR: Database do not exist!</font>"; 
                           $selected="selected"; 
                           $step="1"; $database_name="";
                        } else { $ERROR =""; }
                } 

        // Check that it's possible to create new database
        if (($step=="2") && ($database=="create_new_database")) 
                { mysql_connect("$database_host","$database_username","$database_password");
                        $result = mysql_query("CREATE DATABASE $database_name"); 
                        if(!$result)
                        { $ERROR="<FONT COLOR=\"RED\" SIZE=\"5\" FACE=\"Verdana\">ERROR: Database creation failed!</font>"; 
                           $step="1";
                        } else { $ERROR =""; }
                } 


if ($step=="1") { $msg="
<table>
  <tr><td>
        <FONT FACE=\"Verdana\" SIZE=\"3\">
        <CENTER><B>Welcome to PhpAuction Installation add-on.</B></CENTER> <br>
        This wizard will help you to configure the needed parameters for your installation.<br> 
        It will also set-up the  MySQL tables.<br><br>
        <center>$ERROR</center></FONT>
        <hr noshade></font></CENTER>
        <FONT FACE=\"Verdana\" SIZE=\"5\">Step 1</font> </td></tr>
<table>
  <form method=\"POST\" action=\"install.php?step=2\">
    <tr>
      <td><font face=\"Verdana\" size=\"3\">Please select : </td><td></font>
          <select size=\"1\" name=\"database\" style=\"font-family: Verdana; font-size: 10pt\">
          <option value=\"create_new_database\">Create new database</option>
          <option $selected value=\"use_existing_database\">Use existing
          database</option>
          &nbsp;
          </select></td>
    </tr>
    <tr>
      <td>
          <font face=\"Verdana\" size=\"3\">Database host : </td><td><input type=\"text\" name=\"database_host\" size=\"10\" value=\"$database_host\"></font>
      </td>
    </tr>
    <tr>
      <td>
          <font face=\"Verdana\" size=\"3\">Database name : </td><td><input type=\"text\" name=\"database_name\" size=\"10\" value=\"$database_name\"></font>
      </td>
    </tr>
    <tr>
      <td>
          <font face=\"Verdana\" size=\"3\">Database username : </td><td><input type=\"text\" name=\"database_username\" size=\"10\" value=\"$database_username\"></font>
      </td>
    </tr>
    <tr>
      <td>
          <font face=\"Verdana\" size=\"3\">Database password : </td><td><input type=\"password\" name=\"database_password\" size=\"10\"  value=\"$database_password\"></font>
      </td>
    </tr>
    <tr>
      <td>
          <font face=\"Verdana\" size=\"3\">Admin E-Mail : </td><td><input type=\"text\" name=\"admin_email\" size=\"20\" value=\"$admin_email\"></font>
      </td>
    </tr>
    <tr>
      <td>
          <font face=\"Verdana\" size=\"3\">Site name : </td><td><input type=\"text\" name=\"site_name\" size=\"20\" value=\"$site_name\"></font>
      </td>
    </tr>
    <tr>
      <td>
          <font face=\"Verdana\" size=\"3\">Site URL : </td><td><input type=\"text\" name=\"site_address\" size=\"30\" value=\"$site_address\"> Ending / needed</font>
      </td>
    </tr>
    <tr>
      <td>
      </td>
    </tr>
    <tr>
      <td>
        <BR><input type=\"submit\" value=\"Continue\" style=\"font-family: Verdana; font-size: 10pt\">
        <input type=\"reset\" value=\"Reset\" style=\"font-family: Verdana; font-size: 10pt\">
      </td>
    </tr>
    </form>
</table>
"; }

        // STEP 2
if ($step=="2") { 
                echo "<center><input type=hidden name=database_host value=$database_host>\n";
                echo "<input type=hidden name=database_name value=$database_name>\n";
                echo "<input type=hidden name=database_password value=$database_password>\n";
                echo "<input type=hidden name=database_username value=$database_username>\n";
                echo "<input type=hidden name=database value=$database>\n";
                echo "<input type=hidden name=admin_email value=$admin_email>\n";
                echo "<input type=hidden name=site_name value=$site_name>\n";
                echo "<input type=hidden name=site_address value=$site_address>\n";
												
                mysql_connect("$database_host","$database_username","$database_password");
                mysql_select_db("$database_name");


        // Write current parameters into the passwd.inc.php
                // $DbHost $DbDatabase $DbUser $DbPassword

                require ("../includes/passwd.inc.php");
                $buffer = file("../includes/passwd.inc.php");
                $fp = fopen("../includes/passwd.inc.php", "w+");
                $i = 0;
                while($i < count($buffer)){
                        
                        if(strpos($buffer[$i],"$DbHost")){
                                $buffer[$i] = str_replace($DbHost,$database_host,$buffer[$i]);
                        }
                        fputs($fp,$buffer[$i]); 
                        $i++;
                }
                fclose($fp);
                $buffer = file("../includes/passwd.inc.php");
                $fp = fopen("../includes/passwd.inc.php", "w+");
                $i = 0;
                while($i < count($buffer)){
                        
                        if(strpos($buffer[$i],"$DbDatabase")){
                                $buffer[$i] = str_replace($DbDatabase,$database_name,$buffer[$i]);
                        }
                        fputs($fp,$buffer[$i]); 
                        $i++;
                }
                fclose($fp);
                $buffer = file("../includes/passwd.inc.php");
                $fp = fopen("../includes/passwd.inc.php", "w+");
                $i = 0;
                while($i < count($buffer)){
                        
                        if(strpos($buffer[$i],"$DbUser")){
                                $buffer[$i] = str_replace($DbUser,$database_username,$buffer[$i]);
                        }
                        fputs($fp,$buffer[$i]); 
                        $i++;
                }
                fclose($fp);
                $buffer = file("../includes/passwd.inc.php");
                $fp = fopen("../includes/passwd.inc.php", "w+");
                $i = 0;
                while($i < count($buffer)){
                        
                        if(!empty($DbPassword))
                        {
							if(strpos($buffer[$i],"$DbPassword")){
									$buffer[$i] = str_replace($DbPassword,$database_password,$buffer[$i]);
							}
						}
                        fputs($fp,$buffer[$i]); 
                        $i++;
                }
                fclose($fp);

      

$msg.="$ERROR</FONT>
<table width=\"650\">
<br><hr noshade</font></CENTER></CENTER>
        <FONT FACE=\"Verdana\" SIZE=\"5\">Step 2</font>

<form method=\"POST\" action=\"install.php?step=3\">
    <tr>
      <td width=\"100%\">";

    					
        // Creating Tables and dumping data.

        include ("../sql/dump.sql.php");


        // Update settings by changing Admin E-mail 

$result = mysql_query(" UPDATE PHPAUCTION_settings SET sitename='$site_name' WHERE sitename='PHPAUCTION' ");
if (!$result) { $msg.= "Error: couldn't update settings table (sitename not set)<br>"; } else { $msg.= "Updated settings, sitename is set<br>"; }
$result = mysql_query(" UPDATE PHPAUCTION_settings SET adminmail='$admin_email' WHERE sitename='$site_name' ");
if (!$result) { $msg.= "Error: couldn't update settings table (admin mail not set)<br>"; } else { $msg.= "Updated settings, admin mail is set<br>"; }
$result = mysql_query(" UPDATE PHPAUCTION_settings SET siteurl='$site_address' WHERE sitename='$site_name' ");
if (!$result) { $msg.= "Error: couldn't update settings table (site url not set)<br>"; } else { $msg.= "Updated settings, site url is set<br>"; }
$result = mysql_query(" UPDATE PHPAUCTION_settings SET banners='2' WHERE sitename='$site_name' ");

$msg.="<br><input type=\"button\" value=\"Back\" onClick=\"history.go(-1)\" style=\"font-family: Verdana; font-size: 10pt\">
        <input type=\"submit\" value=\"Continue\" style=\"font-family: Verdana; font-size: 10pt\">
      </td>
    </tr>
    </form>
</table>
"; }



        // STEP 3
if ($step=="3") { 


$includepath="./includes/";
$image_uploadpath="/home/user/phpauction/uploaded/";
$uploadedpath="uploaded/";
$max_upload_size="100000";
$md5_prefix="put_here_unpredictable_string";

        $msg="$ERROR</FONT>
<table width=\"650\">
<br><hr noshade</font>
        <FONT FACE=\"Verdana\" SIZE=\"5\">Step 3</font>
<input type=hidden name=adminemail value=adminemail>
  <form method=\"POST\" action=\"install.php?step=4\">
    <tr>
      <td width=\"30%\">
          <font face=\"Verdana\" size=\"3\">Include path : </td><td><input type=\"text\" name=\"includepath\" size=\"30\" value=\"$includepath\"></font><font size=\"2\" face=\"Verdana\"><br> This is the directory where passwd.inc.php file resides - requires ending slash
      </td>
    </tr>
    <tr>
      <td width=\"30%\">
          <font face=\"Verdana\" size=\"3\">Image upload path : </td><td><input type=\"text\" name=\"image_uploadpath\" size=\"30\" value=\"$image_uploadpath\"></font><font size=\"2\" face=\"Verdana\"><br> This is the directory where users pictures will be uploaded - requires ending slash
      </td>
    </tr>
    <tr>
      <td width=\"30%\">
          <font face=\"Verdana\" size=\"3\">Uploaded path : </td><td><input type=\"text\" name=\"uploadedpath\" size=\"30\" value=\"$uploadedpath\"></font><font size=\"2\" face=\"Verdana\"><br> This is path to 'uploaded' directory
      </td>
    </tr>
    <tr>
      <td width=\"30%\">
          <font face=\"Verdana\" size=\"3\">Max upload size : </td><td><input type=\"text\" name=\"max_upload_size\" size=\"30\"  value=\"$max_upload_size\"></font><font size=\"2\" face=\"Verdana\"><br> This is max size of images which are uploaded to server
      </td>
    </tr>
    <tr>
      <td width=\"30%\">
          <font face=\"Verdana\" size=\"3\">MD5 prefix : </td><td><input type=\"text\" name=\"md5_prefix\" size=\"30\"  value=\"$md5_prefix\"></font><font size=\"2\" face=\"Verdana\"><br> This string is added to passwords before generating the MD5 hash. Do not change it later!
      </td>
    </tr>
    <tr>
      <td width=\"30%\">
      </td>
    </tr>
    <tr>
      <td width=\"30%\">
        <BR><input type=\"submit\" value=\"Continue\" style=\"font-family: Verdana; font-size: 10pt\">
        <input type=\"reset\" value=\"Reset\" style=\"font-family: Verdana; font-size: 10pt\">
      </td>
    </tr>
    </form>
</table>
"; }

if ($step=="4") { 

                // Write current parameters into the config.inc.php

$conf_fail = fopen("../includes/config.inc.php", "w");

$SESSION_NAME ="SESSION_NAME";
$include_path ="include_path";
$image_upload_path ="image_upload_path";
$uploaded_path ="uploaded_path";
$MD5_PREFIX ="MD5_PREFIX";
$MAX_UPLOAD_SIZE ="MAX_UPLOAD_SIZE ";
$logFileName ="logFileName";
$cronScriptHTMLOutput ="cronScriptHTMLOutput";
$expireAuction ="expireAuction";

$txt="<?
/*

   Copyright (c), 1999, 2000 - phpauction.org                  
   

   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  

   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         

   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
   

*/

        $$SESSION_NAME = \"PHPAUCTION_SESSION\";
        session_name($SESSION_NAME);
        session_start();

   //-- This is the directory where passwd.inc.php file resides - requires ending slash
        
        $$include_path = \"$includepath\"; 
        #$$include_path = \"C:\\some\\path\\to\\includes\\\"; 


        //-- This is the directory where users pictures will be uploaded - requires ending slash
        //-- Under Windows use something like C:\\path\\to\\you\\uploaddir\\

        $$image_upload_path = \"$image_uploadpath\"; 
        #$$image_upload_path = \"C:\\some\\path\\to\\uploaded\\\"; 
        $$uploaded_path = \"$uploadedpath\"; 
        #$$uploaded_path = \"uploaded\\\"; 
  

        //--
        $$MAX_UPLOAD_SIZE = $max_upload_size;

        
        
        //-- This string is added to passwords before generating the MD5 hash
        //-- Be sure to never change it after the firt set up or 
        //-- your users passwords will not work
        
        $$MD5_PREFIX = \"$md5_prefix\";
        
        
        /*
                This is the log file generated by cron.php - insert the complete
                file name (including the absolute path).
                If you don't want to generate a log file for cron activity simply
                leave this line commented.
        */

        #$$logFileName = \"/var/www/auctions/logs/cron.log\"; 
        #$$logFileName = \"C:\\path\\to\cron.log\"; 

        /*
                Set this to TRUE if you want cron to generates HTML output
                BESIDES the cron file declared above. cron.php cannot generates
                only HTML output.
        */
        $$cronScriptHTMLOutput = FALSE;


        $$expireAuction = 60*60*24*30; // time of auction expiration (in seconds)
        ";


fwrite($conf_fail, "$txt", 100000);
fclose( $conf_fail );

        // Now add new parameters + end into the one file (config.inc.php)
        
$beginning = fopen("../includes/config.inc.php", "r");
$part1 = fread( $beginning, filesize( "../includes/config.inc.php" ) );
fclose( $beginning );

$end = fopen("../includes/config.tmp.php", "r");
$part2 = fread( $end, filesize( "../includes/config.tmp.php" ) );
fclose( $end );

$conf_file = fopen("../includes/config.inc.php", "w");
fwrite($conf_file, "$part1 $part2", 100000);
fclose( $conf_file );

        include ("check_files.php");

$msg.="$std_font <br><br>Go to <a href='admin.php'>Admin</a> back-end and create the admin username and password.<br><br>";

}


echo $msg;






         // Beginning of footer ?>

<BR>
<TABLE WIDTH="650" BORDER=0 ALIGN=CENTER>
<TR><TD>
        <FONT FACE=Verdana,Arial SIZE=1 COLOR="#999999">
        <? print $MSG_260; ?>
        </FONT>
</CENTER>
</TD></TR>
</TABLE>
</BODY>