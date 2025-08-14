<?php
$forumblocks_modules[showbirthdays] = array(
	'title' => 'Todays Birthdays',
	'func_display' => 'forumblocks_showbirthdays_block',
	'text_type' => 'Birthdays',
	'text_type_long' => 'Todays Birthdays',
	'text_content' => 'Todays Birthdays',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function forumblocks_showbirthdays_block($row) {
// Display Today's Birthday's //
global $DB_site,$bburl,$userid,$themesidebox,$block_content;
$today = vbdate("m-d",time());
$datetoday = vbdate("m-d-Y",time());
$bdays = $DB_site->query("SELECT userid, username, birthday FROM user WHERE birthday LIKE '%-$today'");
      $num_rows = mysql_num_rows($bdays);
	if ($num_rows < 1) {
  		$P_nobdays = "None Found";
            $todaysbirthdays .="<li><smallfont color=\"{calpubliccolor}\">$P_nobdays</smallfont>&nbsp; </li>\n";
      }else{

	while ($bday = $DB_site->fetch_array($bdays)) {
	    $P_bd_user = $bday[username];
		$P_bd_userid = $bday[userid];
		$datebits = explode("-", $bday[birthday]);
		$bdage = date("Y") - $datebits[0];
		if ($bdage > 0) $P_age = "($bdage)";
	    $todaysbirthdays .="<li><a href=\"$bburl/member.php?s=$session[sessionhash]&action=getinfo&userid=$P_bd_userid\"><smallfont color=\"{calpubliccolor}\">$P_bd_user </smallfont></a><smallfont color=\"{calpubliccolor}\"> $P_age</smallfont>&nbsp; </li>\n";
     }
    }
 $block_title = $row[title];
 $block_content = $todaysbirthdays;
 eval("\$themesidebox .= \"".gettemplate("P_themesidebox_left")."\";");
}
?>