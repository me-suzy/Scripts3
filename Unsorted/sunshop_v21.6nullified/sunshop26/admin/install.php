<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.6
// Supplied by          : Stive [WTN], CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
$software = "SunShop";
$version = "2.6";

$steps = 8;
$step_w[0] = "Confirm Installation";
$step_w[1] = "Confirm Configuration";
$step_w[2] = "Connect To Database";
$step_w[3] = "Creating Tables";
$step_w[4] = "Populating Tables";
$step_w[5] = "Edit Settings";
$step_w[6] = "Add Administrator";
$step_w[7] = "Setup Successful";

if (!$step) { $step = 1; }

if ($step>=3) {	
	include("config.php");
}

if ($step>=4) {
  include("db_mysql.php");
  $DB_site=new DB_Sql_vb;
  $DB_site->appname=$software." Installer";
  $DB_site->appshortname=$software;
  $DB_site->database=$dbname;
  $DB_site->server=$servername;
  $DB_site->user=$dbusername;
  $DB_site->password=$dbpassword;
  $DB_site->connect();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?PHP echo $software; ?> Installation - Step <?PHP echo $step; ?></title>
	<style type="text/css">
		body            { font-family : Arial, Verdana, Sans-Serif; color : #000000; font-size : 12px; background-color : #FFFFFF; }
		td              { font-family : Arial, Verdana, Sans-Serif; color : #000000; font-size : 12px; }
		.td_ident_type  { font-family : Courier; color : #000000; font-size : 12px; }
		a               { font-family : Arial, Verdana, Sans-Serif; color : #0000FF; font-size : 12px; text-decoration : none;}
		a:hover         { font-family : Arial, Verdana, Sans-Serif; color : #FF0000; font-size : 12px; text-decoration : underline; }
		strong          { font-family : Arial, Verdana, Sans-Serif; font-size : 12px; font-weight : bold; }
		.small          { font-family : Arial, Verdana, Sans-Serif; font-size : 10px; text-decoration : none;}
		a:hover.small   { font-family : Arial, Verdana, Sans-Serif; color : #333333; font-size : 10px; text-decoration : underline; }
	</style>
</head>
<body>

<div align="center">
<table width="750" border="0" cellpadding="0">
	<tr>
		<td><div align="center"><img src="../images/turnkeysolutions.gif" width="236" height="100" border="0" alt=""></div></td>
	</tr>
</table>

<br>
<table width="750" border="0" cellpadding="0">
	<tr>
		<td width="180" valign="top">
			<table width="180" border="1" cellspacing="0" cellpadding="2" bordercolor="#C0C0C0">
		    <?PHP
			for ($i=0; $i<$steps; $i++) {
			    $on = $i+1;
				if ($on == $step) {
					print("<tr><td><b>Step$on: $step_w[$i]</b></td></tr>");
				} else {
				    print("<tr><td>Step$on: $step_w[$i]</td></tr>");
				}
			}
			?>
		    </table>
		</td>
		<td width="20" valign="top">&nbsp;</td>
	    <td width="550" valign="top">
			<?PHP if ($step == 1) { ?>
				   You are about to install <?PHP echo $software ?> version <?PHP echo $version ?>. Running this script will do a clean 
				   install of <?PHP echo $software ?> onto your server. To continue please hit the continue button below.<br><br>
				   <div class="td_ident_type">
				   <b>SunShop License Agreement</b><br><br>

					SunShop is provided to you under license. This license agreement defines the ways in 
					
					which you can use SunShop the graphics and source code it contains.<br><br>
					
					SunShop software (herein after referred to as SOFTWARE) IS A PROPRIETARY PRODUCT OF 
					
					Turnkey Solutions (herein after referred to as "TS") AND IS PROTECTED BY COPYRIGHT LAWS. 
					
					USE OF THIS SOFTWARE IS GOVERNED BY THIS LICENSE AGREEMENT AND APPLICABLE LAW INCLUDING 
					
					COPYRIGHT LAW.<br><br>
					
					<b>Grant of License</b><br>
					TS grants you a license for the use of the Software as follows.<br><br>
					
					a) You may run one instance (a single installation) of the software on one web server and 
					
					one web site for each license purchased. Each license may power one instance of the 
					
					software on one domain.<br><br>
					
					b) Software source code may be altered (at the owner's risk), but the software (altered or 
					
					otherwise) may not be distributed or resold to entities beyond the license holder without 
					
					the explicit written permission of TS.<br><br>
					
					c) All software copyright notices within design templates must remain unchanged (and 
					
					visible) at all times.<br><br>
					
					c) If any terms are violated, Turnkey Solutions reserves the right to revoke the license at 
					
					any time. No license refunds will be granted for revoked licenses.<br><br>
					
					d) License fees are non-refundable. Please verify that your server supports MySQL and PHP 
					
					before purchasing. For complete system requirements, please visit the requirements page for 
					
					the software.<br><br>
					
					<b>Copy Restrictions</b><br>
					Ownership of the legal rights contained in the Software remain solely with TS. These may 
					
					include trade secret, trademark, copyright, patent, international treaty and other rights 
					
					as applicable. You may not sublicense, rent, lease, disassemble, create derivative works, 
					
					or include portions of the Software in other Software Programs.<br><br>
					
					<b>Use of Images</b><br>
					You may not modify, publish and distribute the button images; create presentations which 
					
					incorporate the button images; use the button images in developing pages for the World Wide 
					
					Web and Intranet systems.<br><br>
					
					<b>Warranty</b><br>
					TS MAKES NO WARRANTIES, EXPRESS OR IMPLIED, REGARDING THE FITNESS OF THE SOFTWARE FOR ANY 
					
					PARTICULAR PURPOSE. TS CLAIMS NO LIABILITY FOR DATA LOSS OR OTHER PROBLEMS CAUSED DIRECTLY 
					
					OR INDIRECTLY BY THE SOFTWARE. TS IS NOT LIABLE FOR THE CONTENT OF ANY WEBSITES POWERED BY 
					
					OUR SOFTWARE.<br><br>
					
					From time to time, TS may inspect your registration integrity. Information verified will be 
					
					your license number and the domain on which the software is run. Should we discover logical 
					
					discrepancies in the software usage, be aware that you may lose your license and may face 
					
					legal actions for software piracy.<br><br>
				   </div>
				   </div>
				   <div align="center">[ <a href="http://www.disney.com">I Disagree</a> ] &nbsp;&nbsp;&nbsp;&nbsp;[ <a href="install.php?step=2">I Agree</a> ]</div>
			<?PHP } elseif ($step == 2) { 
				if (file_exists("config.php")==0) {
					?>
					Cannot find config.php file.<br>
					<br>Make sure that you have uploaded it and that it is in the admin directory. It should look 
					something like this:<br><br>
					&lt;?<br>
					// hostname or ip of server<br>
					$servername="localhost";<br>
					// username and password to log onto db server<br>
					$dbusername="root";<br>
					$dbpassword="";<br>
					// name of database<br>
					$dbname="sunshop";<br>
					// Table prefixes. Do not change the value after the install has been finished!<br>
                    $dbprefix="ss_";<br>
					?&gt;
					<br><br>
				    <div align="center">[ <a href="install.php?step=1">Go Back</a> ]</div>
					<?PHP
				} else {
				    include("config.php");
				    ?>
					Please confirm the details below:<br><br>
					<b>Database server hostname / IP address:</b> <?PHP echo $servername ?><br>
					<b>Database username:</b> <?PHP echo $dbusername ?><br>
					<b>Database password:</b> <?PHP echo $dbpassword ?><br>
					<b>Database name:</b> <?PHP echo $dbname ?><br>
					<b>Table prefix:</b> <?PHP echo $dbprefix ?><br><br>
					Only continue to the next step if those details are correct. If they are not, please edit 
					your config.php file and reupload it.<br><br>
					<div align="center">[ <a href="install.php?step=3">Continue To Next Step</a> ]</div>
					<?PHP
				}
			} elseif ($step == 3) {
				include("db_mysql.php");
				$DB_site=new DB_Sql_vb;
				
				$DB_site->appname=$software." Installer";
				$DB_site->appshortname=$software;
				$DB_site->database=$dbname;
				$DB_site->server=$servername;
				$DB_site->user=$dbusername;
				$DB_site->password=$dbpassword;
				
				$DB_site->reporterror=0;
				$DB_site->connect();
			    $errno=$DB_site->errno;
				
				if ($DB_site->link_id!=0) {
					if ($errno!=0) {
						if ($errno==1049) {
							echo "You have specified a non existent database. Trying to create one now...<br>";
							$DB_site->query("CREATE DATABASE $dbname");
							echo "<br>Trying to connect again...<br>";
							$DB_site->select_db($dbname);
							
							$errno=$DB_site->geterrno();
							
							if ($errno==0) {
								echo "Connection Complete!<br>";
								echo "<br><div align=\"center\">[ <a href=\"install.php?step=4\">Continue To Next Step</a> ]</div><br>";
							} else {
								echo "<br>Connect failed again! Please ensure that the database and server is correctly configured and try again.<br>";
							}
						} else {
							echo "Connect Failed: Unexpected error from the database.<br>";
							echo "<br>Error number: ".$DB_site->errno."<br>";
							echo "<br>Error description: ".$DB_site->errdesc."<br>";
							echo "<br>Please ensure that the database and server is correctly configured and try again.<br>";
						}
			        } else {
						echo "Connection Complete! <br>The database already exists.<br>";
						echo "<br>[ <a href=\"install.php?step=4\">Continue To Next Step</a> ] <br>If an error is generated the tables may already exist. If this occurs select \"Continue To Next Step And Reset Database\"<br>";
						echo "<br>[ <a href=\"install.php?step=4&reset=1\">Continue To Next Step And Reset Database</a> ] <br>Click here to continue and reset the database to an empty one<br>";
					}
				}
			} elseif ($step == 4) {
				if ($reset==1) {
					echo "Reseting database...<br><br>";
					$DB_site->query("DROP DATABASE IF EXISTS $dbname");
					$DB_site->query("CREATE DATABASE $dbname");
					$DB_site->select_db($dbname);
					echo "Reseting Complete<br>";
				}
				
				//Insert Table Definitions
				
				// ###################### creating user table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."user (
				 userid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 username VARCHAR(50),
				 password VARCHAR(50),
				 name VARCHAR(100),
				 address_line1 VARCHAR(50),
				 address_line2 VARCHAR(50),
				 city VARCHAR(20),
				 state VARCHAR(20),
				 zip VARCHAR(30),
				 country VARCHAR(20),
				 baddress_line1 VARCHAR(50),
				 baddress_line2 VARCHAR(50),
				 bcity VARCHAR(20),
				 bstate VARCHAR(20),
				 bzip VARCHAR(30),
				 bcountry VARCHAR(20),
				 phone VARCHAR(30),
				 email VARCHAR(50),
				 lastvisit VARCHAR(30),
				 PRIMARY KEY(userid)
				)");
				
				// ###################### creating category table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."category (
				 categoryid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 title VARCHAR(100),
				 stitle VARCHAR(20),
				 displayorder SMALLINT,
				 PRIMARY KEY(categoryid)
				)");
				
				// ###################### creating subcategory table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."subcategory (
				 subcategoryid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 categoryid INT,
				 title VARCHAR(100),
				 stitle VARCHAR(20),
				 displayorder SMALLINT,
				 PRIMARY KEY(subcategoryid)
				)");
				
				// ###################### creating template table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."template (
				 templateid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 name VARCHAR(100),
				 temp TEXT,
				 PRIMARY KEY(templateid)
				)");
				
				// ###################### creating items table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."items (
				 itemid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 categoryid INT,
				 subcategoryid INT,
				 title VARCHAR(100),
				 imagename VARCHAR(250),
				 thumb VARCHAR(250),
				 poverview TEXT,
				 pdetails TEXT,
				 quantity INT,
				 sold INT,
				 price VARCHAR(20),
				 weight VARCHAR(20),
				 viewable VARCHAR(10),
				 option1 VARCHAR(250),
				 option12 VARCHAR(250),
				 option2 VARCHAR(250),
				 option22 VARCHAR(250),
				 option3 VARCHAR(250),
				 option32 VARCHAR(250),
				 payop VARCHAR(150),
				 sku VARCHAR(150), 
				 PRIMARY KEY(itemid)
				)");
				
				// ###################### creating specials table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."specials (
				 specialid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 itemid INT,
				 sdescription TEXT,
				 sprice VARCHAR(20),
				 PRIMARY KEY(specialid)
				)");
				
				// ###################### creating coupons table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."coupons (
				 id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 couponid VARCHAR(50),
				 discount VARCHAR(10),
				 type VARCHAR(5),
				 sold VARCHAR(10),
				 PRIMARY KEY(id)
				)");
				
				// ###################### creating transaction table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."transaction (
				 orderid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 userid VARCHAR(20),
				 items VARCHAR(250),
				 itemquantity VARCHAR(250),
				 tdate VARCHAR(20),
				 shipmethod VARCHAR(30),
				 shipprice VARCHAR(20),
				 total VARCHAR(20),
				 method VARCHAR(100),
				 name_on_card VARCHAR(100),
				 card_no VARCHAR(30),
				 expir_date VARCHAR(20),
				 card_type VARCHAR(30),
				 cvv2 VARCHAR(10),
				 ccstatus VARCHAR(30),
				 status VARCHAR(100),
				 options VARCHAR(250),
				 comments TEXT,
				 coupon VARCHAR(200),
				 PRIMARY KEY(orderid)
				)");  
				
				// ###################### creating options table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."options (
				 title VARCHAR(100),
				 hometitle VARCHAR(100),
				 shopurl VARCHAR(100),
				 homeurl VARCHAR(100),
				 securepath VARCHAR(100),
				 header1 TEXT,
				 footer TEXT,
				 companyname VARCHAR(100),
				 address TEXT,
				 city VARCHAR(30),
				 state VARCHAR(20),
				 zip VARCHAR(20),
				 country VARCHAR(30),
				 phone VARCHAR(20),
				 faxnumber VARCHAR(20),
				 contactemail VARCHAR(100),
				 taxrate VARCHAR(10),
				 shipups VARCHAR(5),
				 grnd VARCHAR(5),
				 nextdayair VARCHAR(5),
				 seconddayair VARCHAR(5),
				 threeday VARCHAR(5),
				 canada VARCHAR(5),
				 worldwidex VARCHAR(5),
				 worldwidexplus VARCHAR(5),
				 fixedshipping VARCHAR(5),
				 method VARCHAR(15),
				 rate VARCHAR(20),
				 productpath VARCHAR(50),
				 catimage VARCHAR(50),
				 catopen VARCHAR(50),
				 viewcartimage VARCHAR(50),
				 viewaccountimage VARCHAR(50),
				 checkoutimage VARCHAR(50),
				 helpimage VARCHAR(50),
				 cartimage VARCHAR(50),
				 tablehead VARCHAR(50),
				 tableheadtext VARCHAR(50),
				 tableborder VARCHAR(50),
				 tablebg VARCHAR(50),
				 shipchart VARCHAR(50),
				 ship1p1 VARCHAR(50),
				 ship1us VARCHAR(50),
				 ship1ca VARCHAR(50),
				 ship2 VARCHAR(50),
				 ship2p1 VARCHAR(50),
				 ship2p2 VARCHAR(50),
				 ship2us VARCHAR(50),
				 ship2ca VARCHAR(50),
				 ship3 VARCHAR(50),
				 ship3p1 VARCHAR(50),
				 ship3p2 VARCHAR(50),
				 ship3us VARCHAR(50),
				 ship3ca VARCHAR(50),
				 ship4p1 VARCHAR(50),
				 ship4us VARCHAR(50),
				 ship4ca VARCHAR(50),
				 visa VARCHAR(50),
				 mastercard VARCHAR(50),
				 discover VARCHAR(50),
				 amex VARCHAR(50),
				 check VARCHAR(50),
				 fax VARCHAR(50),
				 moneyorder VARCHAR(50),
				 cc VARCHAR(50),
				 payable VARCHAR(100),
				 paypal VARCHAR(50),
				 paypalemail VARCHAR(100),
				 shopimage VARCHAR(100),
				 centerborder VARCHAR(50),
				 centerheader VARCHAR(50),
				 centercolor VARCHAR(50),
				 centerfont VARCHAR(50),
				 centerbg VARCHAR(50),
				 useheader VARCHAR(50),
				 usefooter VARCHAR(50),
				 myheader VARCHAR(50),
				 myfooter VARCHAR(50),
				 thumbheight VARCHAR(50),
				 thumbwidth VARCHAR(50),
				 picheight VARCHAR(50),
				 picwidth VARCHAR(50),
				 showstock VARCHAR(50),
				 css TEXT,
				 showitem VARCHAR(50),
				 showintro VARCHAR(50),
				 shopintro TEXT,
				 orderby VARCHAR(50),
				 outofstock VARCHAR(50),
				 cs VARCHAR(5),
				 showprice VARCHAR(5),
				 po VARCHAR(5),
				 license VARCHAR(50),
				 handling VARCHAR(20),
				 imagel VARCHAR(1)
				)");
				
				//End Table Definitions
				
				if ($DB_site->errno!=0) {
					echo "<br>The script reported errors in the installation of the tables. Only continue if you are sure that they are not serious.<br>";
					echo "<br>The errors were:<br>";
					echo "<br>Error number: ".$DB_site->errno."<br>";
					echo "<br>Error description: ".$DB_site->errdesc."<br>";
				} else {
					echo "<br>Tables set up successfully.<br>";
				}
			
				echo "<br><div align=\"center\"><br>[ <a href=\"install.php?step=5\">Continue To Next Step</a> ]</div>";
			} elseif ($step == 5) {
			    echo "Populating tables...<br>";
				
				//Insert Table Populations
				
				$DB_site->query("INSERT INTO ".$dbprefix."template (templateid,name,temp) VALUES (NULL,'checkout','To remove items from your cart set the amount to 0 and click the \"Update Cart\" button.')");
				$DB_site->query("INSERT INTO ".$dbprefix."template (templateid,name,temp) VALUES (NULL,'signup','Please enter all shipping information as it appears on your credit card. You may be held responsible for all items returned due to an invalid address.')");
				$DB_site->query("INSERT INTO ".$dbprefix."template (templateid,name,temp) VALUES (NULL,'shipmethod','Please select your shipping method and make sure your shipping address is correct. Select the \"Secure Area\" button to proceed to the secure area. Please remember that your shipping address must be the same as the address on your credit card.')");
				$DB_site->query("INSERT INTO ".$dbprefix."template (templateid,name,temp) VALUES (NULL,'shipaddressok','Please make sure your shipping address is correct. Select the \"Secure Area\" button to proceed to the secure area. Please remember that your shipping address must be the same as the address on your credit card.')");
				
				//End Table Populations
				
				echo "<br>Populating complete<br>";
				echo "<br>All tables and templates successfully populated.<br>";
				echo "<br><div align=\"center\"><br>[ <a href=\"install.php?step=6\">Continue To Next Step</a> ]</div>";
			} elseif ($step == 6) {
			    ?>
			    <form action="install.php" method="post">
				<input type="hidden" name="step" value="7">
				<div align="center">All information is needed for smooth usage.</div><br>
					<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="navy">
						<tr>
							<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="4">
							<tr><td colspan="2" bgcolor="navy">
							<div align="center"><b><font color="white">General Settings</font></b></div></td></tr>
							
							<tr>
							<td width="110" bgcolor="#e9e9e9"><b>Shop Title:</b></td>
							<td bgcolor="#e9e9e9"><input size="35" name="title" value="Your Shop"></td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Title of your shopping cart.<br></td></tr>
							
							<tr>
							<td width="110" bgcolor="#c0c0c0"><b>Home Title:</b></td>
							<td bgcolor="#c0c0c0"><input size="35" name="hometitle" value="Your Homepage"></td>
							</tr>
							<tr><td bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0">Name of your main site.<br></td></tr>
							
							<tr>
							<td width="110" bgcolor="#e9e9e9"><b>Shop URL:</b></td>
							<td bgcolor="#e9e9e9"><input size="35" name="shopurl" value="http://www.yourdomain.com/sunshop/" ></td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">URL&nbsp;of the shopping cart. (Add final "/")<br></td></tr>
					
							<tr>
							<td width="110" bgcolor="#c0c0c0"><b>Home URL:</b></td>
							<td bgcolor="#c0c0c0"><input size="35" name="homeurl" value="http://www.yourdomain.com" ></td>
							</tr>
							<tr><td bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0">URL of your home main site. (Add final "/")<br></td></tr>
							
							<tr>
							<td width="110" bgcolor="#e9e9e9"><b>Secure URL:</b></td>
							<td bgcolor="#e9e9e9"><input size="35" name="securepath" value="https://www.yourdomain.com/sunshop/" ></td>
							</tr>
							<tr>
							<td width="110" bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Secure connection URL. (Add
							final "/"). Read documentation for additional important information.<br></td></tr>
							</table>
							</td>
						</tr>
				    </table><br>
			        
					
					<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="navy">
						<tr>
							<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="4">
							<tr><td colspan="2" bgcolor="navy">
							<div align="center"><b><font color="white">Company Information</font></b></div></td></tr>
								<tr>
								<td width="110" bgcolor="#e9e9e9"><b>Company Name:</b></td>
								<td bgcolor="#e9e9e9"><input size="35" name="companyname" value="Your Company"></td>
								</tr>
								
								<tr>
								<td width="110" bgcolor="#c0c0c0"><b>Address:</b></td>
								<td bgcolor="#c0c0c0"><input size="35" name="address" value="Your Address"></td>
								</tr>
								
								<tr>
								<td width="110" bgcolor="#e9e9e9"><b>City:</b></td>
								<td bgcolor="#e9e9e9"><input size="35" name="city" value="Your City"></td>
								</tr>
								
								<tr>
								<td width="110" bgcolor="#c0c0c0"><b>State/Province:</b></td>
								<td bgcolor="#c0c0c0"><select NAME="state">
								<option VALUE="AL" selected>Alabama
								<option VALUE="AK">Alaska
								<option VALUE="AZ">Arizona
								<option VALUE="AR">Arkansas
								<option VALUE="CA">California
								<option VALUE="CO">Colorado
								<option VALUE="CT">Connecticut
								<option VALUE="DE">Delaware
								<option VALUE="FL">Florida
								<option VALUE="GA">Georgia
								<option VALUE="HI">Hawaii
								<option VALUE="ID">Idaho
								<option VALUE="IL">Illinois
								<option VALUE="IN">Indiana
								<option VALUE="IA">Iowa
								<option VALUE="KS">Kansas
								<option VALUE="KY">Kentucky
								<option VALUE="LA">Louisiana
								<option VALUE="ME">Maine
								<option VALUE="MD">Maryland
								<option VALUE="MA">Massachusetts
								<option VALUE="MI">Michigan
								<option VALUE="MN">Minnesota
								<option VALUE="MS">Mississippi
								<option VALUE="MO">Missouri
								<option VALUE="MT">Montana
								<option VALUE="NE">Nebraska
								<option VALUE="NV">Nevada
								<option VALUE="NH">New Hampshire
								<option VALUE="NJ">New Jersey
								<option VALUE="NM">New Mexico
								<option VALUE="NY">New York
								<option VALUE="NC">North Carolina
								<option VALUE="ND">North Dakota
								<option VALUE="OH">Ohio
								<option VALUE="OK">Oklahoma
								<option VALUE="OR">Oregon
								<option VALUE="PA">Pennsylvania
								<option VALUE="RI">Rhode Island
								<option VALUE="SC">South Carolina
								<option VALUE="SD">South Dakota
								<option VALUE="TN">Tennessee
								<option VALUE="TX">Texas
								<option VALUE="UT">Utah
								<option VALUE="VT">Vermont
								<option VALUE="VA">Virginia
								<option VALUE="WA">Washington
								<option VALUE="DC">Washington Dc
								<option VALUE="WV">West Virginia
								<option VALUE="WI">Wisconsin
								<option VALUE="WY">Wyoming
								<option VALUE="">
								<option VALUE="AB">Canada: Alberta
								<option VALUE="BC">Canada: Brit. Columbia
								<option VALUE="MB">Canada: Manitoba
								<option VALUE="NB">Canada: Nw. Brunswick
								<option VALUE="NF">Canada: Newfoundland
								<option VALUE="NS">Canada: Nova Scotia
								<option VALUE="NWT">Canada: Nw. Territories
								<option VALUE="ON">Canada: Ontario
								<option VALUE="PEI">Canada: Pr. Edward Isl.
								<option VALUE="QC">Canada: Quebec
								<option VALUE="SK">Canada: Saskatchewan
								<option VALUE="YT">Canada: Yukon</option> 
								</select>
								</td>
								</tr>
								
								<tr>
								<td width="110" bgcolor="#e9e9e9"><b>Zip/Postal Code:</b></td>
								<td bgcolor="#e9e9e9"><input size="35" name="zip" value="Your Zip"></td>
								</tr>
								
								<tr>
								<td width="110" bgcolor="#c0c0c0"><b>Country:</b></td>
								<td bgcolor="#c0c0c0"><select NAME="country">
								<option VALUE="US" selected>UNITED STATES
								<option VALUE="CA">CANADA
								<OPTION value="AR">ARGENTINA</OPTION>
								<OPTION value="AU">AUSTRALIA</OPTION>
								<OPTION value="AT">AUSTRIA</OPTION>
								<OPTION value="BS">BAHAMAS</OPTION>
								<OPTION value="BE">BELGIUM</OPTION>
								<OPTION value="BR">BRAZIL</OPTION>
								<OPTION value="CL">CHILE</OPTION>
								<OPTION value="CR">COSTA RICA</OPTION>
								<OPTION value="DK">DENMARK</OPTION>
								<OPTION value="DO">DOMINICAN REPUBLIC</OPTION>
								<OPTION value="FI">FINLAND</OPTION>
								<OPTION value="FR">FRANCE</OPTION>
								<OPTION value="DE">GERMANY</OPTION>
								<OPTION value="GB">GREAT BRITAIN</OPTION>
								<OPTION value="GR">GREECE</OPTION>
								<OPTION value="GT">GUATEMALA</OPTION>
								<OPTION value="HK">HONG KONG</OPTION>
								<OPTION value="IL">ISRAEL</OPTION>
								<OPTION value="IT">ITALY</OPTION>
								<OPTION value="IE">REPUBLIC OF IRELAND</OPTION>
								<OPTION value="MY">MALAYSIA</OPTION>
								<OPTION value="MX">MEXICO</OPTION>
								<OPTION value="NL">NETHERLANDS</OPTION>
								<OPTION value="NZ">NEW ZEALAND</OPTION>
								<OPTION value="NO">NORWAY</OPTION>
								<OPTION value="PA">PANAMA</OPTION>
								<OPTION value="PT">PORTUGAL</OPTION>
								<OPTION value="PR">PUERTO RICO</OPTION>
								<OPTION value="SG">SINGAPORE</OPTION>
								<OPTION value="ES">SPAIN</OPTION>
								<OPTION value="SE">SWEDEN</OPTION>
								<OPTION value="CH">SWITZERLAND</OPTION>
								<OPTION value="TW">TAIWAN</OPTION>
								<OPTION value="TH">THAILAND</OPTION>
								</select></td>
								</tr>
								
								<tr>
								<td width="110" bgcolor="#e9e9e9"><b>Phone Number:</b></td>
								<td bgcolor="#e9e9e9"><input size="35" name="phone" value="Your Phone"></td>
								</tr>
								
								<tr>
								<td width="110" bgcolor="#c0c0c0"><b>Fax Number:</b></td>
								<td bgcolor="#c0c0c0"><input size="35" name="faxnumber" value="Your Fax"></td>
								</tr>
								
								<tr>
								<td width="110" bgcolor="#e9e9e9"><b>Contact Email Address:</b></td>
								<td bgcolor="#e9e9e9"><input size="35" name="contactemail" value="contact@yourdomain.com" ></td>
								</tr>
					        </table>
							</td>
						</tr>
				    </table><br>
					
					
					<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="navy">
						<tr>
							<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="4">
							<tr><td colspan="2" bgcolor="navy">
							<div align="center"><b><font color="white">Shipping Settings</font></b></div></td></tr>
							<tr>
							<td align="left" valign="top" bgcolor="Silver"><b>Handling Charge:</b></td>
							<td bgcolor="#C0C0C0"><input type="text" name="handling" value="0.00"><br>Will be charged in addition to calculated shipping charge.</td>
							</tr>
							<tr>
							<td width="250" bgcolor="#e9e9e9"><b>UPS Shipping Calculator: </b><br>(Best Method)</td>
							<td bgcolor="#e9e9e9"><input type="radio" name="shipups" value="Yes" checked>Yes&nbsp;<input type="radio" name="shipups" value="No">No</td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td align="left" bgcolor="#e9e9e9">
								<table border="1" cellspacing="0" cellpadding="0">
				                    <tr>
									<td align="middle"><b>Offered Shipping Methods</b></td>
									</tr>
									<tr>
									<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="2">
										<tr>
											<td>Ground:</td>
											<td><input type="radio" name="grnd" value="Yes" checked>Yes&nbsp;<input type="radio" name="grnd" value="No">No</td>
										</tr>
										<tr>
											<td>Next Day Air:</td>
											<td><input type="radio" name="nextdayair" value="Yes" checked>Yes&nbsp;<input type="radio" name="nextdayair" value="No">No</td>
										</tr>
										<tr>
											<td>Second Day Air:</td>
											<td><input type="radio" name="seconddayair" value="Yes" checked>Yes&nbsp;<input type="radio" name="seconddayair" value="No">No</td>
										</tr>
										<tr>
											<td>Third Day Select:</td>
											<td><input type="radio" name="threeday" value="Yes" checked>Yes&nbsp;<input type="radio" name="threeday" value="No">No</td>
										</tr>
										<tr>
											<td>Canada Standard:</td>
											<td><input type="radio" name="canada" value="Yes" checked>Yes&nbsp;<input type="radio" name="canada" value="No">No</td>
										</tr>
										<tr>
											<td>Worldwide Express:</td>
											<td><input type="radio" name="worldwidex" value="Yes" checked>Yes&nbsp;<input type="radio" name="worldwidex" value="No">No</td>
										</tr>
										<tr>
											<td>Worldwide Express Plus:</td>
											<td><input type="radio" name="worldwidexplus" value="Yes" checked>Yes&nbsp;<input type="radio" name="worldwidexplus" value="No">No</td>
										</tr>

									</table>
									</td>
									</tr>
								</table>
			
							
							</td>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">  UPS realtime shipping calculator. If this is set to "Yes", all
							others will automatically be turned off.<br><br></td></tr>
							<tr>
						    
							<td width="250" bgcolor="#c0c0c0"><b>Shipping Table:</b></td>
							<td bgcolor="#c0c0c0"><input type="radio" name="shipchart" value="Yes">Yes&nbsp;<input type="radio" name="shipchart" value="No" checked>No</td>
							</tr>
							<tr>
							<td align="right" bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0"><table border="1" cellspacing="0" cellpadding="4">
								<tr>
									<td colspan="4" align="middle"><font size="3" color="red"><b>Shipping Charges:</b></font></td>
								</tr>
								<tr>
									<td align="middle"><b>Enable</b></td>
									<td align="middle"><font size="3" color="red">Orders Totaling</font></td>
									<td align="middle"><b>Shipping<br>to US</b></td>
									<td align="middle"><b>Shipping<br>to Canada</b></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>Between $0.00 and $<input name="ship1p1" size="4" value=""></td>
									<td>$<input name="ship1us" size="4" value=""></td>
									<td>$<input name="ship1ca" size="4" value=""></td>
								</tr>
								<tr>
									<td><input type="checkbox" name="ship2" value="Yes"></td>
									<td>Between $<input name="ship2p1" size="4" value=""> and $<input name="ship2p2" size="4" value=""></td>
									<td>$<input name="ship2us" size="4" value=""></td>
									<td>$<input name="ship2ca" size="4" value=""></td>
								</tr>
								<tr>
									<td><input type="checkbox" name="ship3" value="Yes"></td>
									<td>Between $<input name="ship3p1" size="4" value=""> and $<input name="ship3p2" size="4" value=""></td>
									<td>$<input name="ship3us" size="4" value=""></td>
									<td>$<input name="ship3ca" size="4" value=""></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>$<input name="ship4p1" size="4" value=""> and up</td>
									<td>$<input name="ship4us" size="4" value=""></td>
									<td>$<input name="ship4ca" size="4" value=""></td>
								</tr>
							</table>
								</td>
							</tr>
							<tr><td bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0">      
						        Shipping chart.<br><br></td></tr>
							
							<tr>
							<td bgcolor="#e9e9e9"><b>Fixed Shipping:</b></td>
							<td bgcolor="#e9e9e9"><input type="radio" name="fixedshipping" value="Yes">Yes&nbsp;<input type="radio" name="fixedshipping" value="No" checked>No</td>
							</tr>
							<tr>
							<td align="right" bgcolor="#e9e9e9"><b>Method:<br>Rate:</b></td>
							<td bgcolor="#e9e9e9">
							<input type="radio" name="method" value="perorder" checked>Per Order&nbsp;<input type="radio" name="method" value="perpound">Per Pound&nbsp;<input type="radio" name="method" value="peritem">Per Item<br>
							<input name="rate" value="0.00" size="4" >
							</td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Fixed shipping cost.<br><br></td></tr>
					        </table>
							</td>
						</tr>
				    </table><br>
					
					<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="navy">
						<tr>
							<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="4">
							<tr><td colspan="2" bgcolor="navy">
							<div align="center"><b><font color="white">Payment Options</font></b></div></td></tr>
								<tr>
								  <td bgcolor="#c0c0c0"><b>Currency Symbol:</b></td>
								  <td bgcolor="#c0c0c0"><input type="text" name="currency" value="$" size="3"></td>
								</tr>
								<tr>
								  <td bgcolor="#e9e9e9"><b>Credit Cards:</b></td>
								  <td bgcolor="#e9e9e9"><INPUT name="cc" type="radio" value="Yes" checked>Yes&nbsp;<INPUT name="cc" type="radio" value="No">No</td>
								</tr>
								<tr>
								  <td bgcolor="#c0c0c0"><b>Visa:</b></td>
								  <td bgcolor="#c0c0c0"><INPUT name="visa" type="radio" value="Yes" checked>Yes&nbsp;<INPUT name="visa" type="radio" value="No">No</td>
								</tr>
								<tr>
								  <td bgcolor="#e9e9e9"><b>Master Card:</b></td><td bgcolor="#e9e9e9"><INPUT name="mastercard" type="radio" value="Yes" checked>Yes&nbsp;<INPUT name="mastercard" type="radio" value="No">No</td>
								<tr>
								  <td bgcolor="#c0c0c0"><b>Discover:</b></td>
								  <td bgcolor="#c0c0c0"><input type="radio" name="discover" value="Yes">Yes&nbsp;<input type="radio" name="discover" value="No" checked>No</td>
								</tr>
								<tr>
								  <td bgcolor="#e9e9e9"><b>American Express:</b></td>
								  <td bgcolor="#e9e9e9"><input type="radio" name="amex" value="Yes" checked>Yes&nbsp;<input type="radio" name="amex" value="No">No</td>
								</tr>
								<tr>
								  <td valign="top" bgcolor="#c0c0c0"><b>Personal Check:</b></td>
								  <td bgcolor="#c0c0c0"><input type="radio" name="check" value="Yes" checked>Yes&nbsp;<input type="radio" name="check" value="No">No
								      <br>*Payable To: <input name="payable" value="Your Name"></td>
								</tr>
								<tr>
								  <td bgcolor="#e9e9e9"><b>Money Order:</b></td>
								  <td bgcolor="#e9e9e9"><input type="radio" name="moneyorder" value="Yes" checked>Yes&nbsp;<input type="radio" name="moneyorder" value="No">No</td>
								</tr>
								<tr>
								  <td bgcolor="#c0c0c0"><b>Fax:</b></td>
								  <td bgcolor="#c0c0c0"><input type="radio" name="fax" value="Yes" checked>Yes&nbsp;<input type="radio" name="fax" value="No">No</td>
								</tr>
								<tr>
								  <td valign="top" bgcolor="#e9e9e9"><b>Paypal:</b></td>
								  <td bgcolor="#e9e9e9"><input type="radio" name="paypal" value="Yes" checked>Yes&nbsp;<input type="radio" name="paypal" value="No">No<br>
								      *PayPal Email Address: <input name="paypalemail" value="Paypal Email"></td>
								</tr>
								<tr>
								  <td bgcolor="#c0c0c0"><b>Purchase Order:</b></td>
								  <td bgcolor="#c0c0c0"><input type="radio" name="po" value="Yes" checked>Yes&nbsp;<input type="radio" name="po" value="No">No</td>
								</tr>
								<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Acceptable payment methods.<br></td></tr>
					        </table>
							</td>
						</tr>
				    </table><br>
					
					<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="navy">
						<tr>
							<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="4">
							<tr><td colspan="2" bgcolor="navy">
							<div align="center"><b><font color="white">Tax Settings:</font></b></div></td></tr>
							<tr>
							<td bgcolor="#e9e9e9"><b>Tax Rate</b></td>
							<td bgcolor="#e9e9e9"><input size="6" name="taxrate" value=".0750"></td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Tax rate for your state converted to decimal. (i.e. 7.5% to decimal is .0750)<br></td></tr>
							<tr></tr>
					        </table>
							</td>
						</tr>
				    </table><br>
					
					<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="navy">
						<tr>
							<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="4">
							<tr><td colspan="2" bgcolor="navy">
							<div align="center"><b><font color="white">Item Stock Settings:</font></b></div></td></tr>
							<tr>
							<td bgcolor="#e9e9e9"><b>Show Stock:</b></td>
							<td bgcolor="#e9e9e9"><input type="radio" name="showstock" value="Yes" checked>Yes&nbsp;&nbsp;<input type="radio" name="showstock" value="No">No</td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Select whether or not you would like the number of items in stock to be viewed by customers.<br></td></tr>
							<tr>
							<tr>
							<td bgcolor="#c0c0c0"><b>Block Out Of Stock Items:</b></td>
							<td bgcolor="#c0c0c0"><input type="radio" name="outofstock" value="Yes" checked>Yes&nbsp;<input type="radio" name="outofstock" value="No">No</td>
							</tr>
							<tr><td bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0">When items are out of stock do you want people to be able to add them to their cart?<br></td></tr>
					        </table>
							</td>
						</tr>
				    </table><br>
					
					<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="navy">
						<tr>
							<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="4">
							
							<tr><td colspan="2" bgcolor="navy">
							<div align="center"><b><font color="white">Shop Design</font></b></div></td></tr>			
							<tr>
							<td bgcolor="#e9e9e9"><b>Item Order:</b></td>
							<td bgcolor="#e9e9e9"><input type="radio" name="orderby" value="title" checked>Alphabetically&nbsp;<input type="radio" name="orderby" value="itemid">By Item ID&nbsp;<input type="radio" name="orderby" value="price">By Price</td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">How would you like items to be displayed within the cart. (Setting to Item ID will display in the order they are entered)<br></td></tr>
							
							<tr>
							<td bgcolor="#C0C0C0"><b>Item Listing Image:</b></td>
							<td bgcolor="#C0C0C0"><input type="radio" name="imagel" value="1" checked>Right Of Price&nbsp;<input type="radio" name="imagel" value="2">Centered Above Price</td>
							</tr>
							<tr><td bgcolor="#C0C0C0">&nbsp;</td>
							<td bgcolor="#C0C0C0">When a customer is viewing item details, where would you like the image to be placed?<br></td></tr>
							
							
							<tr>
							<td bgcolor="#e9e9e9" valign="top"><b>Show item pictures or list them?</b></td>
							<td bgcolor="#e9e9e9"><input type="radio" name="showitem" value="picture" checked>Pictures&nbsp;<input type="radio" name="showitem" value="list">List Them</td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Whould you like to show the items with their pictures or just list them with text only?<br></td></tr>
							
							<tr>
							<td bgcolor="#c0c0c0" valign="top"><b>Display a welcome message or show bestsellers?</b></td>
							<td bgcolor="#c0c0c0"><input type="radio" name="showintro" value="No" checked>Bestsellers&nbsp;<input type="radio" name="showintro" value="Yes">Welcome Message<br><br>
							Message:<br><TEXTAREA name=shopintro rows=10 cols=50></TEXTAREA><br>
							(Use HTML for better results)
							</td>
							</tr>
							<tr><td bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0">Whould you like to display a welcome message or show bestsellers on the main page?<br></td></tr>
							
							<tr>
							<td valign="top" bgcolor="#e9e9e9"><b>Style Sheet:</b></td>
							<td bgcolor="#e9e9e9"><TEXTAREA name=css rows=10 wrap=off cols=50>&lt;style type="text/css"&gt;
/* Body Settings */
body       { font-family : Arial, Verdana, Sans-Serif; color : #000000; font-size : 12px; background-color : #FFFFFF; }
/* Table Settings */
td         { font-family : Arial, Verdana, Sans-Serif; color : #000000; font-size : 12px; }
/* Link Settings */
a          { font-family : Arial, Verdana, Sans-Serif; color : #0000FF; font-size : 12px; }
/* Link Hover Settings */
a:hover    { font-family : Arial, Verdana, Sans-Serif; color : #333333; font-size : 12px; text-decoration : underline; }
/* Strong Settings */
strong     { font-family : Arial, Verdana, Sans-Serif; font-size : 12px; font-weight : bold; }
/* Input Box Settings */
.input_box { border: 1 solid #000000; background-color: #FFFFCC; color: #000000; font-family: Arial, Verdana, Sans-Serif; 
			 font-size: 12px; line-height: 13px; font-weight:normal; }
/* Submit Button Settings */
.submit_button  { font-size : xx-small; font-family : Arial, Verdana, Sans-Serif; background-color : #CCCCCC; 
			      color : #000000; font-weight : bold; border-width : thin; }
/* You will not need to change below */
.small          { font-family : Arial, Verdana, Sans-Serif; font-size : 10px; }
a:hover.small   { font-family : Arial, Verdana, Sans-Serif; color : #333333; font-size : 10px; text-decoration : underline; }
&lt;/style&gt;</TEXTAREA></td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Style sheet settings. (Optional)<br></td></tr>
							
							<tr>
							<td valign="top" bgcolor="#c0c0c0"><b>Header:</b></td>
							
							<td bgcolor="#c0c0c0"><input type="radio" name="useheader" value="Yes" checked><TEXTAREA name=header1 rows=10 wrap=off cols=50>&lt;!--//Displays info for current page. Dont not remove these variables //--&gt;
&lt;!--//unless you want all pages the same.//--&gt;
&lt;TITLE&gt;$title - $action&lt;/TITLE&gt;
$css
&lt;/HEAD&gt;
&lt;BODY&gt;
&lt;div align=center&gt;&lt;img src=$shopimage border=0&gt;&lt;/div&gt;&lt;br&gt;</TEXTAREA><br>
		                    <input type="radio" name="useheader" value="No"> Header File: <input name="myheader">
							</td>
							</tr>
							<tr><td bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0">Common header. Appears at the top of every page. You may use the built in or define your own file.<br></td></tr>
							
							<tr>
							<td valign="top" bgcolor="#e9e9e9"><b>Footer:</b></td>
							<td bgcolor="#e9e9e9"><input type="radio" name="usefooter" value="Yes" checked><TEXTAREA name=footer rows=10 wrap=off cols=50>&lt;br&gt;
&lt;div align=center&gt;&lt;hr size=1 width=60%&gt;
&lt;a href=$homeurl&gt;$hometitle&lt;/a&gt; | &lt;a href=$shopurl&gt;$title&lt;/a&gt; | &lt;a href=index.php?action=viewcart&gt;View Cart&lt;/a&gt; | &lt;a href=index.php?action=account&gt;Your Account&lt;/a&gt; | &lt;a href=index.php?action=help&gt;Help&lt;/a&gt;&lt;/div&gt;&lt;br&gt;</TEXTAREA><br>
							<input type="radio" name="usefooter" value="No"> Footer File: <input name="myfooter">
							</td>
							</tr>
							<tr><td bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Common header. Appears at the bottom of every page. You may use the built in or define your own file.<br></td></tr>
							
							<tr>
							<td bgcolor="#c0c0c0"><b>Product Path:</b></td>
							<td bgcolor="#c0c0c0"><input size="35" name="productpath" value="images/products/" ></td>
							</tr>
							<tr><td bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0">Path to product pictures. SunShop will look for product images in this directory.<br></td></tr>
							
							<tr>
							<td valign="top" bgcolor="#e9e9e9"><b>Image Settings:</b></td>
							<td bgcolor="#e9e9e9">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								    <tr>
									<td bgcolor="#e9e9e9" width="230"><b>Thumbnail Sizes:</b></td>
									<td bgcolor="#e9e9e9">Width: <input size="3" name="thumbwidth" value="90" >&nbsp;&nbsp;Height: <input size="3" name="thumbheight" value="70" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#c0c0c0" width="230"><b>Picture Sizes:</b></td>
									<td bgcolor="#c0c0c0">Width: <input size="3" name="picwidth" value="150" >&nbsp;&nbsp;Height: <input size="3" name="picheight" value="120" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#e9e9e9" width="230"><b>Logo Image:</b></td>
									<td bgcolor="#e9e9e9"><input size="35" name="shopimage" value="images/sunshop.gif" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#c0c0c0" width="230"><b>Category Image:</b></td>
									<td bgcolor="#c0c0c0"><input size="35" name="catimage" value="images/category.gif" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#e9e9e9" width="230"><b>Category Open Image:</b></td>
									<td bgcolor="#e9e9e9"><input size="35" name="catopen" value="images/catopen.gif" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#c0c0c0" width="230"><b>View Cart Image:</b></td>
									<td bgcolor="#c0c0c0"><input size="35" name="viewcartimage" value="images/viewcart.gif" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#e9e9e9" width="230"><b>View Account Image:</b></td>
									<td bgcolor="#e9e9e9"><input size="35" name="viewaccountimage" value="images/viewaccount.gif" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#c0c0c0" width="230"><b>Checkout Image:</b></td>
									<td bgcolor="#c0c0c0"><input size="35" name="checkoutimage" value="images/checkout.gif" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#e9e9e9" width="230"><b>Add To Cart Image:</b></td>
									<td bgcolor="#e9e9e9"><input size="35" name="cartimage" value="images/addtocart.gif" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#c0c0c0" width="230"><b>Help Image</b></td>
									<td bgcolor="#c0c0c0"><input size="35" name="helpimage" value="images/help.gif" ></td>
									</tr>
								</table>
							</td>
							</tr>
							<tr>
							<td valign="top" bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">Specify the location of the images you wish to use. Defaults are included with SunShop and additional are available via the client login.</td>
							</tr>
							
							<tr>
							<td valign="top" bgcolor="#c0c0c0"><b>Side Table Settings:</b></td>
							<td bgcolor="#c0c0c0">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								    <tr>
									<td bgcolor="#c0c0c0" width="230"><b>Heading Background Color:</b></td>
									<td bgcolor="#c0c0c0"><input size="15" name="tablehead" value="#C0C0C0" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#e9e9e9" width="230"><b>Heading Text Color:</b></td>
									<td bgcolor="#e9e9e9"><input size="15" name="tableheadtext" value="#FFFFFF" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#c0c0c0" width="230"><b>Border Color:</b></td>
									<td bgcolor="#c0c0c0"><input size="15" name="tableborder" value="#C0C0C0" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#e9e9e9" width="230"><b>Table Background Color:</b></td>
									<td bgcolor="#e9e9e9"><input size="15" name="tablebg" value="#FFFFFF" ></td>
									</tr>
								</table>
							</td>
							</tr>
							<tr>
							<td valign="top" bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0">The side tables are the tables displayed on the left and right of the screen ("Category Index" and "Account Login"). Specify the properties for those tables.</td>
							</tr>
							
							<tr>
							<td valign="top" bgcolor="#e9e9e9"><b>Center Table Settings:</b></td>
							<td bgcolor="#e9e9e9">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								    <tr>
									<td bgcolor="#e9e9e9" width="230"><b>Heading Background Color:</b></td>
									<td bgcolor="#e9e9e9"><input size="15" name="centerheader" value="#C0C0C0" ></td>
									</tr>
			
									<tr>
									<td bgcolor="#c0c0c0" width="230"><b>Heading Text Color:</b></td>
									<td bgcolor="#c0c0c0"><input size="15" name="centerfont" value="#FFFFFF" ></td>
									</tr>
										
									<tr>
									<td bgcolor="#e9e9e9" width="230"><b>Border Color:</b></td>
									<td bgcolor="#e9e9e9"><input size="15" name="centercolor" value="#C0C0C0" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#c0c0c0" width="230"><b>Table Background Color:</b></td>
									<td bgcolor="#c0c0c0"><input size="15" name="centerbg" value="#FFFFFF" ></td>
									</tr>
									
									<tr>
									<td bgcolor="#e9e9e9" width="230"><b>Border Size:</b></td>
									<td bgcolor="#e9e9e9"><input size="15" name="centerborder" value="1" ></td>
									</tr>
			
								</table>
							</td>
							</tr>
							<tr>
							<td valign="top" bgcolor="#e9e9e9">&nbsp;</td>
							<td bgcolor="#e9e9e9">The center table is the main table displayed on the screen ("Products" display and others). Specify the properties for this table.</td>
							</tr>
							
					        <tr>
							<td bgcolor="#c0c0c0"><b>Product Path:</b></td>
							<td bgcolor="#c0c0c0"><input type="radio" name="showprice" value="No">No&nbsp;<input type="radio" name="showprice" value="Yes" checked>Yes</td>
							</tr>
							<tr><td bgcolor="#c0c0c0">&nbsp;</td>
							<td bgcolor="#c0c0c0">Would you like to show the item price next to the product name when displayed in category areas.<br></td></tr>
							
					        </table>
							</td>
						</tr>
				</table><br>
				<input type=submit value="Submit Options and Continue to next step">
			    <?PHP
			} elseif ($step == 7) {
				
				//Store Settings
				
				$title = addslashes($title);
				$hometitle = addslashes($hometitle);
				$shopurl = addslashes($shopurl);
				$homeurl = addslashes($homeurl);
				$securepath = addslashes($securepath);
				$companyname = addslashes($companyname);
				$address = addslashes($address);
				$city = addslashes($city);
				$state = addslashes($state);
				$zip = addslashes($zip);
				$country = addslashes($country);
				$phone = addslashes($phone);
				$faxnumber = addslashes($faxnumber);
				$contactemail = addslashes($contactemail);
				$taxrate = addslashes($taxrate);
				$shipups = addslashes($shipups);
				$grnd = addslashes($grnd);
				$nextdayair = addslashes($nextdayair);
				$seconddayair = addslashes($seconddayair);
				$threeday = addslashes($threeday);
				$canada = addslashes($canada);
				$worldwidex = addslashes($worldwidex);
				$worldwidexplus = addslashes($worldwidexplus);
				$fixedshipping = addslashes($fixedshipping);
				$method = addslashes($method);
				$rate = addslashes($rate);
				$productpath = addslashes($productpath);
				$catimage = addslashes($catimage);
				$catopen = addslashes($catopen);
				$viewcartimage = addslashes($viewcartimage);
				$viewaccountimage = addslashes($viewaccountimage);
				$checkoutimage = addslashes($checkoutimage);
				$helpimage = addslashes($helpimage);
				$cartimage = addslashes($cartimage);
				$tablehead = addslashes($tablehead);
				$tableheadtext = addslashes($tableheadtext);
				$tableborder = addslashes($tableborder);
				$tablebg = addslashes($tablebg);
				$shipchart = addslashes($shipchart);
				$ship1p1 = addslashes($ship1p1);
				$ship1us = addslashes($ship1us);
				$ship1ca = addslashes($ship1ca);
				$ship2 = addslashes($ship2);
				$ship2p1 = addslashes($ship2p1);
				$ship2p2 = addslashes($ship2p2);
				$ship2us = addslashes($ship2us);
				$ship2ca = addslashes($ship2ca);
				$ship3 = addslashes($ship2);
				$ship3p1 = addslashes($ship3p1);
				$ship3p2 = addslashes($ship3p2);
				$ship3us = addslashes($ship3us);
				$ship3ca = addslashes($ship3ca);
				$ship4p1 = addslashes($ship4p1);
				$ship4us = addslashes($ship4us);
				$ship4ca = addslashes($ship4ca);
				$visa = addslashes($visa);
				$mastercard = addslashes($mastercard);
				$discover = addslashes($discover);
				$amex = addslashes($amex);
				$check = addslashes($check);
				$fax = addslashes($fax);
				$moneyorder = addslashes($moneyorder);
				$cc = addslashes($cc);
				$payable = addslashes($payable);
				$paypal = addslashes($paypal);
				$paypalemail = addslashes($paypalemail);
				$shopimage = addslashes($shopimage);
				$centercolor = addslashes($centercolor);
				$centerborder = addslashes($centerborder);
				$centerheader = addslashes($centerheader);
				$centerfont = addslashes($centerfont);
				$centerbg = addslashes($centerbg);
				$myheader = addslashes($myheader);
				$myfooter = addslashes($myfooter);
				$useheader = addslashes($useheader);
				$usefooter = addslashes($usefooter);
				$css = addslashes($css);
				$thumbwidth = addslashes($thumbwidth);
				$thumbheight = addslashes($thumbheight);
				$picwidth = addslashes($picwidth);
				$picheight = addslashes($picheight);
				$showstock = addslashes($showstock);
				$showitem = addslashes($showitem);
				$showintro = addslashes($showintro);
				$shopintro = addslashes($shopintro);
				$orderby = addslashes($orderby);
				$outofstock = addslashes($outofstock);
				$cs = addslashes($currency);
				$showprice = addslashes($showprice);
				$po = addslashes($po);
                $handling = addslashes($handling);
		        $imagel = addslashes($imagel);
				
				$DB_site->query("INSERT INTO ".$dbprefix."options VALUES ('$title','$hometitle','$shopurl', '$homeurl', '$securepath', '$header1', '$footer', '$companyname', '$address', '$city', '$state', '$zip', '$country', '$phone', '$faxnumber', '$contactemail', '$taxrate', '$shipups', '$grnd', '$nextdayair', '$seconddayair', '$threeday', '$canada', '$worldwidex', '$worldwidexplus', '$fixedshipping', '$method', '$rate', '$productpath', '$catimage', '$catopen', '$viewcartimage', '$viewaccountimage', '$checkoutimage', '$helpimage', '$cartimage', '$tablehead', '$tableheadtext', '$tableborder', '$tablebg', '$shipchart', '$ship1p1', '$ship1us', '$ship1ca', '$ship2', '$ship2p1', '$ship2p2', '$ship2us', '$ship2ca', '$ship3', '$ship3p1', '$ship3p2', '$ship3us', '$ship3ca', '$ship4p1', '$ship4us', '$ship4ca', '$visa', '$mastercard', '$discover', '$amex', '$check', '$fax', '$moneyorder', '$cc', '$payable', '$paypal', '$paypalemail', '$shopimage', '$centerborder', '$centerheader', '$centercolor', '$centerfont', '$centerbg', '$useheader', '$usefooter', '$myheader', '$myfooter', '$thumbheight', '$thumbwidth', '$picheight', '$picwidth', '$showstock', '$css', '$showitem', '$showintro', '$shopintro', '$orderby', '$outofstock', '$cs', '$showprice', '$po', '', '$handling', '$imagel')");
				
				//Store Settings
				
				echo "Options added and set successfully.<br><br>";
				?>
				Please fill in the form below to set yourself up as an administrator...<br><br>
	                        <style>
	                        input {color:blue; font-weight : bold;}
	                        </style>
				<form action="install.php" method="post"><!--CyKuH-->
				<input type="hidden" name="step" value="8">
				<table border="0" cellspacing="0" cellpadding="2">
				<tr>
				<td><?PHP echo $software ?> License:</td>
				<td><input type="hidden" size="35" name="license" value="1234567890"><font color=blue><center><b>on WTN Team `2002</td>
				</tr>
				<tr>
				<td>User Name:</td>
				<td><input type="text" size="35" name="username"></td>
				</tr>
				<tr>
				<td>Password:</td>
				<td><input type="text" size="35" name="password"></td>
				</tr>
				<tr>
				<td>Email Address:</td>
				<td><input type="text" size="35" name="email"></td>
				</tr>
				</table><br>
				<input type=submit value="Continue To Next Step">
				</form>
				
				<?PHP
			} elseif ($step == 8) {
			    $DB_site->query("UPDATE ".$dbprefix."options set license='$license'");
                $DB_site->query("INSERT INTO ".$dbprefix."user (userid,username,password,name,address_line1,address_line2,city,state,zip,country,phone,email,lastvisit) VALUES (NULL,'".addslashes($username)."','".addslashes($password)."','Admin_Account','NULL','NULL','NULL','NULL','NULL','NULL','NULL','".addslashes($email)."','NULL')");
			    $PATH_TO = str_replace("admin/install.php", "", $HTTP_REFERER);
				?>
				You have completed the setup of <?PHP echo $software ?> version <?PHP echo $version ?>. It is important that you
				delete this installation file before you continue to the control panel.<br><br>
			    An email has been sent to the email address you associated with the administrator account to confirm
				installation.<br>
				<br><div align="center"><br>[ <a href="index.php">Continue To Control Panel</a> ]</div>
				<?PHP
			}
			?>
	    </td>
	</tr>
</table>
</div>
</body>
</html>