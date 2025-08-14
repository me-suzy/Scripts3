<?php
$centerblocks_modules[downloads] = array(
	'title' => 'downloads',
	'func_display' => 'centerblocks_downloads_block',
	'text_type' => 'downloads',
	'text_type_long' => 'downloads',
	'text_content' => 'downloads',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );

function centerblocks_downloads_block($row) {
    global $prefix,$DB_site;
	OpenTable();
    echo "<center><font size=\"4\"><b>"._DOWNLOADSMAINCAT."</b></font></center><br>";
    echo "<table border=\"0\" cellspacing=\"10\" cellpadding=\"0\" align=\"center\"><tr>";
    $result=$DB_site->query("select cid, title, cdescription from $prefix"._downloads_categories." order by title");
    $count = 0;
    while(list($cid, $title, $cdescription) = mysql_fetch_row($result)) {
	$cresult = $DB_site->query("select * from $prefix"._downloads_downloads." where cid=$cid");
	$cnumrows = mysql_num_rows($cresult);
	echo "<td><font size=\"3\"><strong><big>&middot;</big></strong> <a href=\"download.php?op=viewdownload&amp;cid=$cid\"><b>$title</b></a> ($cnumrows)</font>";
 	if ($description) {
	    echo "<font size=\"2\">$cdescription</font><br>";
	} else {
	    echo "<br>";
	}
	$result2 = $DB_site->query("select sid, title from $prefix"._downloads_subcategories." where cid=$cid order by title limit 0,3");
	$space = 0;
	while(list($sid, $stitle) = mysql_fetch_row($result2)) {
    	    if ($space>0) {
		echo ",&nbsp;";
	    }
	    echo "<font size=\"2\"><a href=\"download.php?op=viewsdownload&amp;sid=$sid\">$stitle</a></font>";
	    $space++;
	}
	if ($count<1) {
	    echo "</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
	    $dum = 1;
	}
	$count++;
	if ($count==2) {
	    echo "</td></tr><tr>";
	    $count = 0;
	    $dum = 0;
	}
    }
    if ($dum == 1) {
	echo "</tr></table>";
    } elseif ($dum == 0) {
	echo "<td></td></tr></table>";
    }
    $result=$DB_site->query("select * from $prefix"._downloads_downloads."");
    $numrows = mysql_num_rows($result);
    $result=$DB_site->query("select * from $prefix"._downloads_categories."");
    $catnum1 = mysql_num_rows($result);
    $result=$DB_site->query("select * from $prefix"._downloads_subcategories."");
    $catnum2 = mysql_num_rows($result);
    $catnum = $catnum1+$catnum2;
    echo "<br><br><center><font size=\"2\">"._THEREARE." <b>$numrows</b> "._DOWNLOADS." "._AND." <b>$catnum</b> "._CATEGORIES." "._INDB."</font></center>";
    CloseTable();
	echo "<br>";
    }
	
?>