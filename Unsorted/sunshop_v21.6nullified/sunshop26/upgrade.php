<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.6
// Supplied by          : Stive [WTN], CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
require("global.php");

if ($step == "") {

	print("
	<div align=\"center\"><img src=\"images/turnkeysolutions.gif\" width=\"236\" height=\"100\" border=\"0\" alt=\"\"></div><br><br>
	Run this upgrade <b>ONLY</b> if you are upgrading from version 2.5. <b>DO NOT</b> run this to upgrade from
	any other version. Hit continue only if you are sure you are currently running version 2.5.<br><br>
	If you have not installed any version yet, just delete this script and install using the install.php located in the
	admin directory.<br><br>
	<div align=\"center\">[ <a href=\"upgrade.php?step=2\">CONTINUE</a> ]</div>
	");

} elseif ($step == 2) {
	
	$query=$DB_site->query("ALTER TABLE ".$dbprefix."options CHANGE companyname companyname VARCHAR (100)");
	$query=$DB_site->query("ALTER TABLE ".$dbprefix."options CHANGE title title VARCHAR (100)");
	$query=$DB_site->query("ALTER TABLE ".$dbprefix."options CHANGE hometitle hometitle VARCHAR (100)");
	
	print("
	<div align=\"center\"><img src=\"images/turnkeysolutions.gif\" width=\"236\" height=\"100\" border=\"0\" alt=\"\"></div><br><br>
	Upgrade was successfull. Please delete this file from the server.");

}

?>