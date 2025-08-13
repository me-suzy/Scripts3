<?#//v.1.0.0?>
<html>
<head>
<title>Phpauction Upgrade Script</title>
</head>
<body BGCOLOR="brown1" TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<TABLE ALIGN=CENTER CELLPADDING=1 CELLSPACING=0 BORDER=0 BGCOLOR=white WIDTH=700>

<TR>
	<TD>
		<TABLE WIDTH=100% CELLPADDING=4 CELLSPACING=0 BORDER=0 BGCOLOR=white>
		<TR>
			<TD>
			<IMG SRC=images/logo.gif>
			&nbsp;&nbsp;
			<H1>Phpauction ProPlus Upgrade Script</H1>
			<BR>
			<BR>

<? 
function footer()
	{
	echo "<BR><BR><BR></TD></TR></TABLE></TD></TR></TABLE></body></html>";
	}


if ($step=="")
	{
		#// Get current directory
		$NEW_PATH = getcwd();
		$NEW_PATH = str_replace("/admin","",$NEW_PATH);
?>
			<FONT FACE=Helvetica SIZE=3>
			The present upgrade script will upgrade your Phpauction installation from version Pro to ProPlus 1.0.
			<BR><BR>
			Before using the upgrade script, unpack the Phpauction ProPlus 1.0 distribution file you downloaded in 
			a directory on your server under the www root (NOTE: do not overwrite you current Phpauction Pro
			installaion).
			<BR>
			<BR>
			Be sure to give <FONT FACE=Courier>read</FONT> permissions to the Phpauction ProPlus directory and <FONT FACE=Courier>write</FONT>
			permissions to the following files:
			<UL>
			<LI><FONT FACE=Courier>includes/passwd.inc.php</FONT>
			<LI><FONT FACE=Courier>includes/config.inc.php</FONT>
			</UL>
			<BR>
			<B>Note:</B> The upgrade script will populate the new database with the content of your Phpauction Pro installation.
			<BR>The script will not upgrade your PhpAdsNew tables. 
			<BR><BR>
			If you don't have Phpauction Pro installed on your server, use the <A HREF=install.php>installation script</A>.
			<BR><BR><BR>
			<FORM ACTION=upgrade.php?step=1 method=post>
			<INPUT TYPE=HIDDEN NAME=NEW_PATH VALUE=<? print $NEW_PATH; ?>>
			<INPUT TYPE=radio NAME=DB VALUE=current>&nbsp;<b>Use your Phpauction Pro existing database</b><BR>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=text size=40 name=PRO_PATH value=<? print $NEW_PATH; ?>> (Correct this path to your Pro version)<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No ending / needed.<br><br>
			<INPUT TYPE=radio NAME=DB VALUE=new>&nbsp;<b>Create a new database </b>
			<center>
			<BR><BR>
			<INPUT TYPE=submit VALUE="Start Upgrade Script >>"><BR>
			<BR>
			Copyright &copy; 2002, Phpauction.org
			</center>
			</FORM>
			

<?	}
if ($step=="1")
	{

	#// Copy config.inc.php to new location
	$proplus_conf = getcwd();
	$proplus_conf = str_replace("admin","",$proplus_conf);
	$proplus_conf = $proplus_conf."includes/config.inc.php";
	$pro_conf = "$PRO_PATH/includes/config.inc.php";
	copy($pro_conf,$proplus_conf);
	echo "$pro_conf<br>$proplus_conf";

	if ($DB=="new")
		{ 
?>
Enter data needed for creating database for your PhPAuction ProPlus script :<br><br>
<table width=50%>
<form action=upgrade.php?step=2 method=post>
<tr><td>Database host :</td><td> <INPUT TYPE=text size=20 name=DB_HOST value=<? print $DB_HOST; ?>></td></tr>
<tr><td>Database name :</td><td> <INPUT TYPE=text size=20 name=DB_NAME value=<? print $DB_NAME; ?>></td></tr>
<tr><td>Database username :</td><td> <INPUT TYPE=text size=20 name=DB_USER value=<? print $DB_USER; ?>></td></tr>
<tr><td>Database password :</td><td> <INPUT TYPE=text size=20 name=DB_PASS value=<? print $DB_PASS; ?>></td></tr>
<tr><td></td><td><INPUT TYPE=hidden name=DB value=new> </td></tr>
<tr><td></td><td><INPUT TYPE=submit value=Continue> </td></tr>
<form>
</table>

<?		}
	if ($DB=="current")
		{

	@include ("$PRO_PATH/includes/passwd.inc.php");

	if (empty($PRO_PATH) || empty($DbHost) || empty($DbDatabase) || empty($DbUser) || empty($DbPassword))
		{ $ERR = "<font color=red size=3>Path incorrect or file does not exist!</font>";
		}
			?><FONT FACE=Helvetica SIZE=3>
			<FORM ACTION=upgrade.php?step=2 METHOD=POST>
			<INPUT TYPE=HIDDEN NAME=DB VALUE=current>
			<INPUT TYPE=HIDDEN NAME=PRO_PATH VALUE=<? print $PRO_PATH; ?>>
			<INPUT TYPE=HIDDEN NAME=DB_HOST VALUE=<? print $DbHost; ?>>
			<INPUT TYPE=HIDDEN NAME=DB_NAME VALUE=<? print $DbDatabase; ?>>
			<INPUT TYPE=HIDDEN NAME=DB_USER VALUE=<? print $DbUser; ?>>
			<INPUT TYPE=HIDDEN NAME=DB_PASS VALUE=<? print $DbPassword; ?>>
			<BR><? print $ERR; ?><BR>
			<FONT FACE=Helvetica SIZE=3>

		<? if ($ERR!="") { footer(); exit; } ?>

			<B>Current Phpauction Pro installation information:</B>
			<UL>
			<LI>Root directory: <FONT FACE=Courier COLOR=brown1><? print $PRO_PATH; ?></FONT>
			<LI>Database Host: <FONT FACE=Courier COLOR=brown1><? print $DbHost; ?></FONT>
			<LI>Database Name: <FONT FACE=Courier COLOR=brown1><? print $DbDatabase; ?></FONT>
			<LI>Database User: <FONT FACE=Courier COLOR=brown1><? print $DbUser; ?></FONT>
			<LI>Database Password: <FONT FACE=Courier COLOR=brown1><? print $DbPassword; ?></FONT><BR>
			</UL>
			<BR><BR>If these settings are correct then go to next step, otherwice go back and correct your data.<BR><BR>
			<CENTER><INPUT TYPE=SUBMIT VALUE=Continue >
			</FORM>

<?
		}
	}
if ($step=="2")
	{
	
	if ($DB=="new")
		{
		  if(!mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS))
		  {
		  	$ERR = "<font color=red size=3>Connection Error!</font>";
		  }

		 mysql_query("CREATE DATABASE $DB_NAME");

		  if(!mysql_select_db($DB_NAME))
		  {
		  	$ERR = "<font color=red size=3>Database Error!</font>";
		  }
		echo "$ERR";
		if ($ERR!="") { footer(); exit; }
		if ($ERR=="") { 

	        // Write current parameters into the passwd.inc.php

			$DbHost="DbHost"; 
			$DbDatabase="DbDatabase"; 
			$DbUser="DbUser"; 
			$DbPassword="DbPassword";

			$file = "../includes/passwd.inc.php";
			$to_file = fopen("$file", "w");
			$what ="<?#//v.1.0.0
  
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#///////////////////////////////////////////////////////


//--Set the variables below according to your system configuration

$$DbHost     = \"$DB_HOST\"; // The host where the MySQL server resides
$$DbDatabase = \"$DB_NAME\"; // The database you are going to use
$$DbUser     = \"$DB_USER\"; // Username
$$DbPassword = \"$DB_PASS\"; // Password

?>";
			fwrite($to_file, "$what", 500000);
			fclose($to_file);

			include ("../sql/dump.sql.php"); }
			echo $msg;
echo "	Now all you have to do is insert admin username and password what tou use to access admin area of the PhPAuction.
	<form action=admin.php method=post>
	<input type=hidden name=DB value=new><center>
	<br><br><input type=submit value=Continue>";
		}

	if ($DB=="current")
		{
        // Write current parameters into the passwd.inc.php
		  if(!mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS))
		  {
		  	$ERR = "<font color=red size=3>Connection Error!</font>";
		  }

		  if(!mysql_select_db($DB_NAME))
		  {
		  	$ERR = "<font color=red size=3>Database Error!</font>";
		  }
		echo "$ERR";
		if ($ERR!="") { footer(); exit; }
		if ($ERR=="") { 

	        // Write current parameters into the passwd.inc.php

			$DbHost="DbHost"; 
			$DbDatabase="DbDatabase"; 
			$DbUser="DbUser"; 
			$DbPassword="DbPassword";

			$file = "../includes/passwd.inc.php";
			$to_file = fopen("$file", "w");
			$what ="<?#//v.1.0.0
  
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#///////////////////////////////////////////////////////


//--Set the variables below according to your system configuration

$$DbHost     = \"$DB_HOST\"; // The host where the MySQL server resides
$$DbDatabase = \"$DB_NAME\"; // The database you are going to use
$$DbUser     = \"$DB_USER\"; // Username
$$DbPassword = \"$DB_PASS\"; // Password

?>";
			fwrite($to_file, "$what", 500000);
			fclose($to_file);

		#// Update config.inc.php
		@require ("$PRO_PATH/includes/config.inc.php");
		$MD5_PREFIX_NEW = $MD5_PREFIX;
                $buffer = file("../includes/config.inc.php");
                $fp = fopen("../includes/config.inc.php", "w+");
                $i = 0;
                while($i < count($buffer)){
                        
                        if(strpos($buffer[$i],"$MD5_PREFIX")){
                                $buffer[$i] = str_replace($MD5_PREFIX,$MD5_PREFIX_NEW,$buffer[$i]);
                        }
                        fputs($fp,$buffer[$i]); 
                        $i++;
                }
                fclose($fp);


	#// Tables updateing
	mysql_query("ALTER TABLE PHPAUCTIONPRO_accounts RENAME PHPAUCTIONPROPLUS_accounts");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_adminusers RENAME PHPAUCTIONPROPLUS_adminusers");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_auctions RENAME PHPAUCTIONPROPLUS_auctions");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_bids RENAME PHPAUCTIONPROPLUS_bids");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_categories RENAME PHPAUCTIONPROPLUS_categories");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_categories_plain RENAME PHPAUCTIONPROPLUS_categories_plain");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_counters RENAME PHPAUCTIONPROPLUS_counters");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_countries RENAME PHPAUCTIONPROPLUS_countries");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_durations RENAME PHPAUCTIONPROPLUS_durations");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_feedbacks RENAME PHPAUCTIONPROPLUS_feedbacks");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_help RENAME PHPAUCTIONPROPLUS_help");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_increments RENAME PHPAUCTIONPROPLUS_increments");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_news RENAME PHPAUCTIONPROPLUS_news");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_payments RENAME PHPAUCTIONPROPLUS_payments");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_request RENAME PHPAUCTIONPROPLUS_request");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_settings RENAME PHPAUCTIONPROPLUS_settings");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_users RENAME PHPAUCTIONPROPLUS_users");
	mysql_query("ALTER TABLE PHPAUCTIONPRO_winners RENAME PHPAUCTIONPROPLUS_winners");

	#// Add new columns needed
	mysql_query("ALTER TABLE PHPAUCTIONPROPLUS_auctions add buy_now double(16,4) default NULL after reserve_price");
	mysql_query("ALTER TABLE PHPAUCTIONPROPLUS_settings add picturesgallery int(1) NOT NULL default '0'");
	mysql_query("ALTER TABLE PHPAUCTIONPROPLUS_settings add maxpictures int(11) NOT NULL default '0'");
	mysql_query("ALTER TABLE PHPAUCTIONPROPLUS_settings add maxpicturesize int(11) NOT NULL default '0'");
	mysql_query("ALTER TABLE PHPAUCTIONPROPLUS_settings add picturesgalleryfee int(11) NOT NULL default '0'");
	mysql_query("ALTER TABLE PHPAUCTIONPROPLUS_settings add picturesgalleryvalue double NOT NULL default '0'");
	mysql_query("ALTER TABLE PHPAUCTIONPROPLUS_settings add buy_now int(1) NOT NULL default '1'");

echo "	Database updated.<br>
	Now all you have to do is insert admin username and password what tou use to access admin area of the PhPAuction.
	<form action=admin.php method=post>
	<input type=hidden name=DB value=new><center>
	<br><input type=submit value=Continue><br><br>";

		}

?>



<?
		}
	}


footer();

?>
