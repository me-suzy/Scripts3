<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.9
// Supplied by          : CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
$software = "SunShop";
$version = "2.9";

$steps = 6;
$step_w[0] = "Confirm Installation";
$step_w[1] = "Confirm Configuration";
$step_w[2] = "Connect To Database";
$step_w[3] = "Creating Tables";
$step_w[4] = "Add Administrator";
$step_w[5] = "Setup Successful";

$GET  = array();
$GET  = (isset($_GET))  ? $_GET  : $HTTP_GET_VARS;

if (!($GET['step'])) { $GET['step'] = 1; }

if ($GET['step']>=3) {	
	include("config.php");
}

if ($GET['step']>=4) {
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
	<title><?PHP echo $software; ?> Installation - Step <?PHP echo $GET['step']; ?></title>
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
		<td><div align="center"><img src="../images/turnkeywebtools.gif" width="236" height="100" border="0" alt=""></div></td>
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
				if ($on == $GET['step']) {
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
			<?PHP if ($GET['step'] == 1) { ?>
				   You are about to install <?PHP echo $software ?> version <?PHP echo $version ?> Nullified by WTN Team. Running this script will do a clean 
				   install of <?PHP echo $software ?> onto your server. To continue please hit the continue button below.<br><br>
				   <div class="td_ident_type">
				    <strong>Software License Agreement</strong><br><br>
				   </div>
				   </div>
</strong>
  The user assumes the entire risk of using the program.
<br>
Enjoy!
<br>Copyright &copy WTN Team `2002
				   <div align="center">[ <a href="http://www.disney.com">I Disagree</a> ] &nbsp;&nbsp;&nbsp;&nbsp;[ <a href="install.php?step=2">I Agree</a> ]</div>
			<?PHP } elseif ($GET['step'] == 2) { 
				    $success = @include("config.php"); 
					if ($success) { 
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
					} else {
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
					}
					
			} elseif ($GET['step'] == 3) {
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
			} elseif ($GET['step'] == 4) {
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
				 name VARCHAR(150),
				 address_line1 VARCHAR(100),
				 address_line2 VARCHAR(100),
				 city VARCHAR(30),
				 state VARCHAR(30),
				 zip VARCHAR(30),
				 country VARCHAR(30),
				 baddress_line1 VARCHAR(100),
				 baddress_line2 VARCHAR(100),
				 bcity VARCHAR(30),
				 bstate VARCHAR(30),
				 bzip VARCHAR(30),
				 bcountry VARCHAR(30),
				 phone VARCHAR(30),
				 email VARCHAR(100),
				 lastvisit VARCHAR(30),
				 PRIMARY KEY(userid)
				)");
				
				// ###################### creating category table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."category (
				 categoryid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 title VARCHAR(150),
				 stitle VARCHAR(20),
				 displayorder SMALLINT,
				 PRIMARY KEY(categoryid)
				)");
				
				// ###################### creating subcategory table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."subcategory (
				 subcategoryid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 categoryid INT,
				 title VARCHAR(150),
				 stitle VARCHAR(20),
				 displayorder SMALLINT,
				 PRIMARY KEY(subcategoryid)
				)");
				
				// ###################### creating items table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."items (
				 itemid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 categoryid INT,
				 subcategoryid INT,
				 title VARCHAR(200),
				 imagename VARCHAR(250),
				 thumb VARCHAR(250),
				 poverview TEXT,
				 pdetails TEXT,
				 quantity INT,
				 sold INT,
				 price VARCHAR(20),
				 weight VARCHAR(20),
				 viewable VARCHAR(10),
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
				 discount VARCHAR(20),
				 type VARCHAR(5),
				 sold VARCHAR(10),
				 PRIMARY KEY(id)
				)");
				
				// ###################### creating transaction table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."transaction (
				 orderid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 userid VARCHAR(20),
				 items TEXT,
				 itemquantity TEXT,
				 tdate VARCHAR(20),
				 shipmethod VARCHAR(40),
				 shipprice VARCHAR(20),
				 total VARCHAR(20),
				 method VARCHAR(100),
				 name_on_card VARCHAR(150),
				 card_no VARCHAR(50),
				 expir_date VARCHAR(20),
				 card_type VARCHAR(30),
				 cvv2 VARCHAR(10),
				 ccstatus VARCHAR(30),
				 status VARCHAR(200),
				 options TEXT,
				 prices TEXT,
				 ofields TEXT,
				 comments TEXT,
				 coupon VARCHAR(200),
				 PRIMARY KEY(orderid)
				)");  
				
				// ###################### creating tracking table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."tracking (
				 trackid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 tranid VARCHAR(20),
				 number VARCHAR(250),
				 carrier VARCHAR(100),
				 date VARCHAR(10),
				 PRIMARY KEY(trackid)
				)");
				
				// ###################### creating itemoptions table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."itemoptions (
				 optionid INT UNSIGNED NOT NULL AUTO_INCREMENT,
				 productid VARCHAR(20),
				 name VARCHAR(250),
				 items TEXT,
				 increase TEXT,
				 order1 VARCHAR(20),
				 PRIMARY KEY(optionid)
				)");
				
				// ###################### creating discounts table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."discounts (
				  id int(10) unsigned NOT NULL auto_increment,
				  productid varchar(50),
				  discount varchar(10),
				  type char(1),
				  frombuy int(11),
				  tobuy int(11),
				  sold varchar(10),
				  displayit char(1),
				  message varchar(250),
				  PRIMARY KEY  (id)
				)");
				
				// ###################### creating templates table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."templates (
				  id int(10) unsigned NOT NULL auto_increment,
				  title varchar(50),
				  template text,
				  description varchar(250),
				  PRIMARY KEY  (id)
				)");
				
				// ###################### creating itemfields table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."itemfields (
				  id int(10) unsigned NOT NULL auto_increment,
				  productid varchar(20),
				  name varchar(250),
				  type varchar(10),
				  defaultv text,
				  order1 varchar(20),
				  PRIMARY KEY  (id)
				)");
				
				// ###################### creating options table #######################
				$DB_site->query("CREATE TABLE ".$dbprefix."options (
				 title VARCHAR(100),
				 hometitle VARCHAR(100),
				 shopurl VARCHAR(100),
				 homeurl VARCHAR(100),
				 securepath VARCHAR(100),
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
				 showitem VARCHAR(50),
				 showintro VARCHAR(50),
				 orderby VARCHAR(50),
				 outofstock VARCHAR(50),
				 cs VARCHAR(5),
				 po VARCHAR(5),
				 license VARCHAR(50),
				 handling VARCHAR(20),
				 imagel VARCHAR(1),
				 showbestsellers varchar(5),
				 showspecials varchar(5),
				 language varchar(20),
				 showcattotals varchar(5),
				 shipcalc varchar(5),
				 shipusps varchar(5),
				 itemsperpage int(5),
				 usesecurefooter varchar(5),
				 mysecurefooter varchar(50),
				 usesecureheader varchar(5),
				 mysecureheader varchar(50),
				 mustsignup varchar(5),
				 uspsserver varchar(200),
				 uspsuser varchar(100),
				 uspspass varchar(100),
				 catsdisplay VARCHAR(10),
				 allwidth VARCHAR(10),
				 centerwidth VARCHAR(10),
				 tablewidth VARCHAR(10)
				)");
				
				//End Table Definitions
				
				$filesize=filesize('sunshop.templates');
				$fp=fopen('sunshop.templates','r');
				$templatesfile=fread($fp,$filesize);
				fclose($fp);
				$templates = explode("|->SS_TEMPLATE_FILE<-|", $templatesfile);
				while (list($key,$val) = each($templates)) {
					$template = explode("|->SS_TEMPLATE<-|", $val);
					$template[1] = addslashes($template[1]);
					$DB_site->query("INSERT INTO ".$dbprefix."templates VALUES ('', '".addslashes($template[0])."', '".addslashes($template[1])."', '".addslashes($template[2])."')");
				}
				$DB_site->query("DELETE FROM ".$dbprefix."templates WHERE title=''");
				
				if ($DB_site->errno!=0) {
					echo "<br>The script reported errors in the installation of the tables. Only continue if you are sure that they are not serious.<br>";
					echo "<br>The errors were:<br>";
					echo "<br>Error number: ".$DB_site->errno."<br>";
					echo "<br>Error description: ".$DB_site->errdesc."<br>";
				} else {
					echo "<br>Tables set up successfully.<br>";
				}
				
				$DB_site->query("INSERT INTO ".$dbprefix."transaction VALUES ('99999', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY')");
				$DB_site->query("DELETE from ".$dbprefix."transaction where orderid='99999'");
				
				$DB_site->query("INSERT INTO ".$dbprefix."user VALUES ('99999', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY')");
				$DB_site->query("DELETE from ".$dbprefix."user where userid='99999'");
			
				echo "<br><div align=\"center\"><br>[ <a href=\"install.php?step=5\">Continue To Next Step</a> ]</div>";
			} elseif ($GET['step'] == 5) {
				//Store Settings
				$DB_site->query("INSERT INTO ".$dbprefix."options VALUES ('Your Shop','Your Homepage','http://www.yourdomain.com/sunshop/', 'http://www.yourdomain.com/', 'https://www.yourdomain.com/sunshop/', 'Your Company', 'Your Address', 'Your City', '', 'Your Zip', '', 'Your Phone', 'Your Fax', 'contact@yourdomain.com', '.085', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'percentage', '.05', 'images/products/', 'images/category.gif', 'images/catopen.gif', 'images/viewcart.gif', 'images/viewaccount.gif', 'images/checkout.gif', 'images/help.gif', 'images/addtocart.gif', '#C0C0C0', '#FFFFFF', '#C0C0C0', '#FFFFFF', 'No', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Yes', 'Yes', 'No', 'No', 'Yes', 'No', 'Yes', 'Yes', 'Your Name', 'Yes', 'Paypal Email', 'images/sunshop.gif', '1', '#C0C0C0', '#C0C0C0', '#FFFFFF', '#FFFFFF', 'Yes', 'Yes', '', '', '70', '90', '120', '150', 'Yes', 'list', 'Yes', 'title', 'Yes', '$', 'No', '', '0.00', '1', 'Yes', 'Yes', 'lang/lang_eng.php', 'Yes', 'Yes', 'No', '12', 'Yes', '', 'Yes', '', 'Yes', '', '', '', '10', '750', '440', '150')");
				//Store Settings
				
				echo "Options added and set successfully.<br><br>";
				?>
				Please fill in the form below to set yourself up as an administrator...<br><br>
	
				<form action="install.php?step=6" method="post">
				<table border="0" cellspacing="0" cellpadding="2">
				<tr>
				<td><?PHP echo $software ?> License:</td><!-- CyKuH [WTN] -->
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
			} elseif ($GET['step'] == 6) {
			    $DB_site->query("UPDATE ".$dbprefix."options set license='$license'");
                $DB_site->query("INSERT INTO ".$dbprefix."user (userid,username,password,name,address_line1,address_line2,city,state,zip,country,phone,email,lastvisit) VALUES (NULL,'".addslashes($username)."','".addslashes($password)."','Admin_Account','NULL','NULL','NULL','NULL','NULL','NULL','NULL','".addslashes($email)."','NULL')"); $to[0] = $email;
			    $PATH_TO = str_replace("admin/install.php?step=5", "", $HTTP_REFERER);
				$to[1] = $email;
				for ($i=0; $i<2; $i++) {
				  mail($to[$i], $software." ".$version." Setup Successfully", "You have successfully setup ".$software." ".$version." at ".$PATH_TO." with license number ".$license.". Please remember to delete the install script before you attempt to setup your shopping cart.\n\nThank You\nTurnkey Web Tools Team and Nullified WTN Team");
				}
				?>
				You have completed the setup of <?PHP echo $software ?> version <?PHP echo $version ?>. It is important that you
				delete this installation file BEFORE you continue to the control panel.<br><br>
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