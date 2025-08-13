<? 
require "data.inc.php";
require "global.php";

if(!getUser_stat($user_id,$user_password)) {
	header("LOCATION: ../index.php");
	exit;
} 

eval ("\$headinclude = \"".gettemplate("headinclude")."\";");	

if($action == "welcome") {
	eval("dooutput(\"".gettemplate("welcome")."\");");
}

if($action == "top") {
                eval("dooutput(\"".gettemplate("headbanner")."\");");
}

if($action == "menu"){
                eval("dooutput(\"".gettemplate("menu")."\");");
}

if($action == "special_deal_add") {
	if($send == "send") {
		 $db_connect->query("INSERT INTO db_deals VALUES ('$newid', '$dealname', '$dealdescription', '$amount')");
		header("Location: admin.php?action=special_deal_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_deals");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("special_deal_add")."\");");
}

if($action == "special_deal_del") {
	if($send == "send" && count($id)) {
		for($i = 0; $i < count($id); $i++) $db_connect->query("DELETE FROM db_deals WHERE id = '$id[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_deals ORDER BY id ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$special_deal_delbit .= \"".gettemplate("special_deal_delbit")."\";");
	eval("dooutput(\"".gettemplate("special_deal_del")."\");");
}

if($action == "announcements_add") {
	if($send == "send") {
		if(!$message) eval ("\$error = \"".gettemplate("error")."\";");
		else $db_connect->query("INSERT INTO db_announcements VALUES ('$newid', '$adminname', '$message')");
		header("Location: admin.php?action=announcements_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_announcements");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("announcements_add")."\");");
}

if($action == "announcements_del") {
	if($send == "send" && count($id)) {
		for($i = 0; $i < count($id); $i++) $db_connect->query("DELETE FROM db_announcements WHERE id = '$id[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_announcements ORDER BY id ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$announcements_delbit .= \"".gettemplate("announcements_delbit")."\";");
	eval("dooutput(\"".gettemplate("announcements_del")."\");");
}

if($action == "levels") {
      if($send == send) {
            $db_connect->query("UPDATE db_levels SET expneeded='$level1' WHERE level = '1'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level2' WHERE level = '2'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level3' WHERE level = '3'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level4' WHERE level = '4'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level5' WHERE level = '5'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level6' WHERE level = '6'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level7' WHERE level = '7'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level8' WHERE level = '8'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level9' WHERE level = '9'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level10' WHERE level = '10'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level11' WHERE level = '11'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level12' WHERE level = '12'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level13' WHERE level = '13'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level14' WHERE level = '14'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level15' WHERE level = '15'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level16' WHERE level = '16'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level17' WHERE level = '17'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level18' WHERE level = '18'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level19' WHERE level = '19'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level20' WHERE level = '20'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level21' WHERE level = '21'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level22' WHERE level = '22'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level23' WHERE level = '23'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level24' WHERE level = '24'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level25' WHERE level = '25'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level26' WHERE level = '26'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level27' WHERE level = '27'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level28' WHERE level = '28'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level29' WHERE level = '29'");
            $db_connect->query("UPDATE db_levels SET expneeded='$level30' WHERE level = '30'");
      }
$level1_stat = $db_connect->query_first("select expneeded from db_levels where level='1'");
$level1 = $level1_stat[expneeded];
$level2_stat = $db_connect->query_first("select expneeded from db_levels where level='2'");
$level2 = $level2_stat[expneeded];
$level3_stat = $db_connect->query_first("select expneeded from db_levels where level='3'");
$level3 = $level3_stat[expneeded];
$level4_stat = $db_connect->query_first("select expneeded from db_levels where level='4'");
$level4 = $level4_stat[expneeded];
$level5_stat = $db_connect->query_first("select expneeded from db_levels where level='5'");
$level5 = $level5_stat[expneeded];
$level6_stat = $db_connect->query_first("select expneeded from db_levels where level='6'");
$level6 = $level6_stat[expneeded];
$level7_stat = $db_connect->query_first("select expneeded from db_levels where level='7'");
$level7 = $level7_stat[expneeded];
$level8_stat = $db_connect->query_first("select expneeded from db_levels where level='8'");
$level8 = $level8_stat[expneeded];
$level9_stat = $db_connect->query_first("select expneeded from db_levels where level='9'");
$level9 = $level9_stat[expneeded];
$level10_stat = $db_connect->query_first("select expneeded from db_levels where level='10'");
$level10 = $level10_stat[expneeded];
$level11_stat = $db_connect->query_first("select expneeded from db_levels where level='11'");
$level11 = $level11_stat[expneeded];
$level12_stat = $db_connect->query_first("select expneeded from db_levels where level='12'");
$level12 = $level12_stat[expneeded];
$level13_stat = $db_connect->query_first("select expneeded from db_levels where level='13'");
$level13 = $level13_stat[expneeded];
$level14_stat = $db_connect->query_first("select expneeded from db_levels where level='14'");
$level14 = $level14_stat[expneeded];
$level15_stat = $db_connect->query_first("select expneeded from db_levels where level='15'");
$level15 = $level15_stat[expneeded];
$level16_stat = $db_connect->query_first("select expneeded from db_levels where level='16'");
$level16 = $level16_stat[expneeded];
$level17_stat = $db_connect->query_first("select expneeded from db_levels where level='17'");
$level17 = $level17_stat[expneeded];
$level18_stat = $db_connect->query_first("select expneeded from db_levels where level='18'");
$level18 = $level18_stat[expneeded];
$level19_stat = $db_connect->query_first("select expneeded from db_levels where level='19'");
$level19 = $level19_stat[expneeded];
$level20_stat = $db_connect->query_first("select expneeded from db_levels where level='20'");
$level20 = $level20_stat[expneeded];
$level21_stat = $db_connect->query_first("select expneeded from db_levels where level='21'");
$level21 = $level21_stat[expneeded];
$level22_stat = $db_connect->query_first("select expneeded from db_levels where level='22'");
$level22 = $level22_stat[expneeded];
$level23_stat = $db_connect->query_first("select expneeded from db_levels where level='23'");
$level23 = $level23_stat[expneeded];
$level24_stat = $db_connect->query_first("select expneeded from db_levels where level='24'");
$level24 = $level24_stat[expneeded];
$level25_stat = $db_connect->query_first("select expneeded from db_levels where level='25'");
$level25 = $level25_stat[expneeded];
$level26_stat = $db_connect->query_first("select expneeded from db_levels where level='26'");
$level26 = $level26_stat[expneeded];
$level27_stat = $db_connect->query_first("select expneeded from db_levels where level='27'");
$level27 = $level27_stat[expneeded];
$level28_stat = $db_connect->query_first("select expneeded from db_levels where level='28'");
$level28 = $level28_stat[expneeded];
$level29_stat = $db_connect->query_first("select expneeded from db_levels where level='29'");
$level29 = $level29_stat[expneeded];
$level30_stat = $db_connect->query_first("select expneeded from db_levels where level='30'");
$level30 = $level30_stat[expneeded];

                eval("dooutput(\"".gettemplate("levels")."\");");
}

if($action == "config") {
                if($send == send) {
                $db_connect->query("UPDATE db_configuration SET php_path = '$path', master_board_name = '$rpgname', master_email = '$masteremail' WHERE register = '1'");
                }

	$rpgname_stat = $db_connect->query_first("select master_board_name from db_configuration where register='1'");
                $rpgname2 = $rpgname_stat[master_board_name];
                $path_stat = $db_connect->query_first("select php_path from db_configuration where register='1'");
                $path2 = $path_stat[php_path];
                $masteremail_stat = $db_connect->query_first("select master_email from db_configuration where register='1'");
                $masteremail2 = $masteremail_stat[master_email];

	eval("dooutput(\"".gettemplate("config")."\");");
}

if($action == "swords_del") {
	if($send == "send" && count($swordid)) {
		for($i = 0; $i < count($swordid); $i++) $db_connect->query("DELETE FROM db_shop_swords WHERE id = '$swordid[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_shop_swords ORDER BY money ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$swords_delbit .= \"".gettemplate("swords_delbit")."\";");
	eval("dooutput(\"".gettemplate("swords_del")."\");");
}

if($action == "swords_add") {
	if($send == "send") {
		if(!$swordname) eval ("\$error = \"".gettemplate("error")."\";");
		else $db_connect->query("INSERT INTO db_shop_swords VALUES ('$newid', '$swordname', '$damage', '$price')");
		header("Location: admin.php?action=swords_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_shop_swords");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("swords_add")."\");");
}

if($action == "shields_del") {
	if($send == "send" && count($shieldid)) {
		for($i = 0; $i < count($shieldid); $i++) $db_connect->query("DELETE FROM db_shop_shields WHERE id = '$shieldid[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_shop_shields ORDER BY money ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$shields_delbit .= \"".gettemplate("shields_delbit")."\";");
	eval("dooutput(\"".gettemplate("shields_del")."\");");
}

if($action == "shields_add") {
	if($send == "send") {
		if(!$shieldname) eval ("\$error = \"".gettemplate("error")."\";");
		else $db_connect->query("INSERT INTO db_shop_shields VALUES ('$newid', '$shieldname', '$deffense', '$price')");
		header("Location: admin.php?action=shields_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_shop_shields");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("shields_add")."\");");
}

if($action == "armor_del") {
	if($send == "send" && count($armourid)) {
		for($i = 0; $i < count($armourid); $i++) $db_connect->query("DELETE FROM db_shop_armour WHERE id = '$armourid[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_shop_armour ORDER BY money ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$armor_delbit .= \"".gettemplate("armor_delbit")."\";");
	eval("dooutput(\"".gettemplate("armor_del")."\");");
}

if($action == "armor_add") {
	if($send == "send") {
		if(!$armourname) eval ("\$error = \"".gettemplate("error")."\";");
		else $db_connect->query("INSERT INTO db_shop_armour VALUES ('$newid', '$armourname', '$deffense', '$price')");
		header("Location: admin.php?action=armor_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_shop_armour");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("armor_add")."\");");
}

if($action == "helmet_del") {
	if($send == "send" && count($helmetid)) {
		for($i = 0; $i < count($helmetid); $i++) $db_connect->query("DELETE FROM db_shop_helmet WHERE id = '$helmetid[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_shop_helmet ORDER BY money ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$helmet_delbit .= \"".gettemplate("helmet_delbit")."\";");
	eval("dooutput(\"".gettemplate("helmet_del")."\");");
}

if($action == "helmet_add") {
	if($send == "send") {
		if(!$helmetname) eval ("\$error = \"".gettemplate("error")."\";");
		else $db_connect->query("INSERT INTO db_shop_helmet VALUES ('$newid', '$helmetname', '$deffense', '$price')");
		header("Location: admin.php?action=helmet_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_shop_helmet");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("helmet_add")."\");");
}

if($action == "boots_del") {
	if($send == "send" && count($bootsid)) {
		for($i = 0; $i < count($bootsid); $i++) $db_connect->query("DELETE FROM db_shop_boots WHERE id = '$bootsid[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_shop_boots ORDER BY money ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$boots_delbit .= \"".gettemplate("boots_delbit")."\";");
	eval("dooutput(\"".gettemplate("boots_del")."\");");
}

if($action == "boots_add") {
	if($send == "send") {
		if(!$bootsname) eval ("\$error = \"".gettemplate("error")."\";");
		else $db_connect->query("INSERT INTO db_shop_boots VALUES ('$newid', '$bootsname', '$deffense', '$price')");
		header("Location: admin.php?action=boots_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_shop_boots");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("boots_add")."\");");
}

if($action == "gloves_del") {
	if($send == "send" && count($glovesid)) {
		for($i = 0; $i < count($glovesid); $i++) $db_connect->query("DELETE FROM db_shop_gloves WHERE id = '$glovesid[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_shop_gloves ORDER BY money ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$gloves_delbit .= \"".gettemplate("gloves_delbit")."\";");
	eval("dooutput(\"".gettemplate("gloves_del")."\");");
}

if($action == "gloves_add") {
	if($send == "send") {
		if(!$glovename) eval ("\$error = \"".gettemplate("error")."\";");
		else $db_connect->query("INSERT INTO db_shop_gloves VALUES ('$newid', '$glovename', '$deffense', '$price')");
		header("Location: admin.php?action=gloves_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_shop_gloves");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("gloves_add")."\");");
}

if($action == "specialitem_del") {
	if($send == "send" && count($specialitemid)) {
		for($i = 0; $i < count($specialitemid); $i++) $db_connect->query("DELETE FROM db_shop_specialitem WHERE id = '$specialitemid[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_shop_specialitem ORDER BY money ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$specialitem_delbit .= \"".gettemplate("specialitem_delbit")."\";");
	eval("dooutput(\"".gettemplate("specialitem_del")."\");");
}

if($action == "specialitem_add") {
	if($send == "send") {
		if(!$specialitemname) eval ("\$error = \"".gettemplate("error")."\";");
		else $db_connect->query("INSERT INTO db_shop_specialitem VALUES ('$newid', '$specialitemname', '$damage', '$price')");
		header("Location: admin.php?action=specialitem_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_shop_specialitem");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("specialitem_add")."\");");
}

if($action == "smallitem_del") {
	if($send == "send" && count($smallitemid)) {
		for($i = 0; $i < count($smallitemid); $i++) $db_connect->query("DELETE FROM db_shop_smallitems WHERE id = '$smallitemid[$i]'");
	}
	
	$result = $db_connect->query("SELECT * FROM db_shop_smallitems ORDER BY money ASC");
	while($row = $db_connect->fetch_array($result)) eval ("\$smallitem_delbit .= \"".gettemplate("smallitem_delbit")."\";");
	eval("dooutput(\"".gettemplate("smallitem_del")."\");");
}

if($action == "smallitem_add") {
	if($send == "send") {
		if(!$smallitemname) eval ("\$error = \"".gettemplate("error")."\";");
		else $db_connect->query("INSERT INTO db_shop_smallitems VALUES ('$newid', '$smallitemname', '$hpaddon', '$mpaddon', '$price')");
		header("Location: admin.php?action=smallitem_del$session");
	}
	$maxid = $db_connect->query_first("SELECT MAX(id) AS maxid FROM db_shop_smallitems");
	$newid = ($maxid[maxid]+1);
	eval("dooutput(\"".gettemplate("smallitem_add")."\");");
}
	eval("dooutput(\"".gettemplate("frameset")."\");");
?>