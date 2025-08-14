<?php
$centerblocks_modules[links] = array(
	'title' => 'links',
	'func_display' => 'centerblocks_links_block',
	'text_type' => 'links',
	'text_type_long' => 'links',
	'text_content' => 'links',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function centerblocks_links_block($row) {
    global $prefix,$DB_site;
    OpenTable();
    echo "<center><font size=\"4\"><b>"._LINKSMAINCAT."</b></font></center><br>";
    echo "<table border=\"0\" cellspacing=\"10\" cellpadding=\"0\" align=\"center\"><tr>";
    $result=$DB_site->query("select cid, title, cdescription from $prefix"._links_categories." order by title");
    $count = 0;
    while(list($cid, $title, $cdescription) = mysql_fetch_row($result)) {
	$cresult = $DB_site->query("select * from $prefix"._links_links." where cid=$cid");
	$cnumrows = mysql_num_rows($cresult);
	echo "<td><font size=\"3\"><strong><big>&middot;</big></strong> <a href=\"links.php?op=viewlink&amp;cid=$cid\"><b>$title</b></a> ($cnumrows)</font>";
   	if ($description) {
	    echo "<font size=\"2\">$cdescription</font><br>";
	} else {
	    echo "<br>";
	}
	$result2 = $DB_site->query("select sid, title from $prefix"._links_subcategories." where cid=$cid order by title limit 0,3");
	$space = 0;
	while(list($sid, $stitle) = mysql_fetch_row($result2)) {
    	    if ($space>0) {
		echo ",&nbsp;";
	    }
	    echo "<font size=\"2\"><a href=\"links.php?op=viewslink&amp;sid=$sid\">$stitle</a></font>";
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
    $result=$DB_site->query("select * from $prefix"._links_links."");
    $numrows = mysql_num_rows($result);
    $result=$DB_site->query("select * from $prefix"._links_categories."");
    $catnum1 = mysql_num_rows($result);
    $result=$DB_site->query("select * from $prefix"._links_subcategories."");
    $catnum2 = mysql_num_rows($result);
    $catnum = $catnum1+$catnum2;
    echo "<br><br><center><font size=\"2\">"._THEREARE." <b>$numrows</b> "._LINKS." "._AND." <b>$catnum</b> "._CATEGORIES." "._INDB."</font></center>";
    CloseTable();
    echo "<br>";
}

?>