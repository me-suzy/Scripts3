<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.9
// Supplied by          : CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
require("global.php");

if ($step == "") {

	print("
	<div align=\"center\"><table width=\"80%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
    <tr>
	<td><div align=\"center\"><img src=\"images/turnkeywebtools.gif\" width=\"236\" height=\"100\" border=\"0\" alt=\"\"></div><br><br>
	Run this upgrade <b>ONLY</b> if you are upgrading from version 2.7  Nullified by WTN Team or 2.8 Nullified by WTN Team. <b>DO NOT</b> run this to upgrade from
	any other version. Hit continue only if you are sure you are currently running version 2.7 Nullified by WTN Team or 2.8 Nullified by WTN Team.<br><br>
	If you have not installed any version yet, just delete this script and install using the install.php located in the
	admin directory. To continue please read and agree to the software license terms below.<br><br>
	<div class=\"td_ident_type\">
	    <strong>Software License Agreement</strong><br><br>

   </div>
   </div></strong>
  The user assumes the entire risk of using the program.
<br>
Enjoy!
<br>Copyright &copy WTN Team `2002
	<div align=\"center\">[ <a href=\"upgrade.php?step=2\">I AGGREE</a> ] [ <a href=\"http://www.disney.com\">I DISAGREE</a> ]</div><!--CyKuH [WTN]--></td>
	</tr>
	</table></div>
	");

} elseif ($step == 2) {
	
	//$DB_site->query("DROP TABLE IF EXISTS ".$dbprefix2."discounts, ".$dbprefix2."templates, ".$dbprefix2."itemfields, ".$dbprefix2."itemoptions");
	
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
	
	$filesize=filesize('admin/sunshop.templates');
	$fp=fopen('admin/sunshop.templates','r');
	$templatesfile=fread($fp,$filesize);
	fclose($fp);
	$templates = explode("|->SS_TEMPLATE_FILE<-|", $templatesfile);
	while (list($key,$val) = each($templates)) {
		$template = explode("|->SS_TEMPLATE<-|", $val);
		$template[1] = addslashes($template[1]);
		$DB_site->query("INSERT INTO ".$dbprefix."templates VALUES ('', '".addslashes($template[0])."', '".addslashes($template[1])."', '".addslashes($template[2])."')");
	}
	$DB_site->query("DELETE FROM ".$dbprefix."templates WHERE title=''");
	
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."items");
	while($row=$DB_site->fetch_array($temp)) {
		if ($row[option1] != "") {
		    $temp1 = addslashes(stripslashes(stripslashes($row[option12])));
			$DB_site->query("INSERT INTO ".$dbprefix."itemoptions VALUES ('' , '$row[itemid]' , '$row[option1]' , '".str_replace("-", "->", $temp1)."' , '".str_replace("-", "->", $row[payop])."' , '1')");
		}
		if ($row[option2] != "") {
		    $temp1 = addslashes(stripslashes(stripslashes($row[option22])));
			$DB_site->query("INSERT INTO ".$dbprefix."itemoptions VALUES ('' , '$row[itemid]' , '$row[option2]' , '".str_replace("-", "->", $temp1)."' , '' , '2')");
		}
		if ($row[option3] != "") {
		    $temp1 = addslashes(stripslashes(stripslashes($row[option32])));
			$DB_site->query("INSERT INTO ".$dbprefix."itemoptions VALUES ('' , '$row[itemid]' , '$row[option3]' , '".str_replace("-", "->", $temp1)."' , '' , '3')");
		}
	}
	
	$temp=$DB_site->query("SELECT * from ".$dbprefix."options LIMIT 0,1");
	$row=$DB_site->fetch_array($temp);
	
	$DB_site->query("UPDATE ".$dbprefix."templates set template='".addslashes(addslashes(stripslashes(stripslashes(stripslashes(stripslashes("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n<HTML>\n<HEAD>\n".$row[header1]))))))."' where title='header'");
	$DB_site->query("UPDATE ".$dbprefix."templates set template='".addslashes(addslashes(stripslashes(stripslashes(stripslashes(stripslashes($row[footer]))))))."' where title='footer'");
	$DB_site->query("UPDATE ".$dbprefix."templates set template='".addslashes(addslashes(stripslashes(stripslashes(stripslashes(stripslashes("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n<HTML>\n<HEAD>\n".$row[secureheader]))))))."' where title='secure_header'");
	$DB_site->query("UPDATE ".$dbprefix."templates set template='".addslashes(addslashes(stripslashes(stripslashes(stripslashes(stripslashes($row[securefooter]))))))."' where title='secure_footer'");
	$DB_site->query("UPDATE ".$dbprefix."templates set template='".addslashes(addslashes(stripslashes(stripslashes(stripslashes(stripslashes($row[css]))))))."' where title='style_sheet'");
	$DB_site->query("UPDATE ".$dbprefix."templates set template='".addslashes(addslashes(stripslashes(stripslashes(stripslashes(stripslashes($row[shopintro]))))))."' where title='welcome_message'");
	
	$DB_site->query("ALTER TABLE ".$dbprefix."transaction ADD prices TEXT AFTER options, ADD ofields TEXT AFTER prices");
	
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."transaction");
	while($row=$DB_site->fetch_array($temp)) {
	    $temp1 = addslashes(stripslashes(stripslashes($row[options])));
		$DB_site->query("UPDATE ".$dbprefix."transaction set items='".str_replace("-", "->", $row[items])."',  itemquantity='".str_replace("-", "->", $row[itemquantity])."', options='".str_replace("-", "->", $temp1)."' where orderid='$row[orderid]'");
	}
	
	$DB_site->query("ALTER TABLE ".$dbprefix."options DROP header1, DROP footer, DROP css, DROP shopintro, DROP securefooter, DROP secureheader");
	$DB_site->query("ALTER TABLE ".$dbprefix."items DROP option1, DROP option12, DROP option2, DROP option22, DROP option3, DROP option32, DROP payop");
	$DB_site->query("ALTER TABLE ".$dbprefix."options ADD catsdisplay VARCHAR(10), ADD allwidth VARCHAR(10), ADD centerwidth VARCHAR(10), ADD tablewidth VARCHAR(10)");
	
	$DB_site->query("UPDATE ".$dbprefix."options set catsdisplay='0', allwidth='750', centerwidth='440', tablewidth='150'");
	
	$DB_site->query("INSERT INTO ".$dbprefix."transaction VALUES ('99999', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY')");
	$DB_site->query("DELETE from ".$dbprefix."transaction where orderid='99999'");
	
	$DB_site->query("INSERT INTO ".$dbprefix."user VALUES ('99999', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY', 'EMPTY')");
	$DB_site->query("DELETE from ".$dbprefix."user where userid='99999'");
	
	print("
	<div align=\"center\"><img src=\"images/turnkeywebtools.gif\" width=\"236\" height=\"100\" border=\"0\" alt=\"\"><br><br>
	Upgrade was successfull. Please delete this file from the server. </div>");
}

?>