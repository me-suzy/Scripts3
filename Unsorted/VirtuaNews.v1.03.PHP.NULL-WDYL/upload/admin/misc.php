<?php


if (preg_match("/(admin\/misc.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

switch ($action) {
case "misc_index":

  updateadminlog();

  echohtmlheader();

  echotableheader("Welcome to the VirtuaNews administration section - Version $version",1);
  echotabledescription("From here you can control aspects of the site",1);
  echotabledescription("[".returnlinkcode(iif($adminnav == "frames","Non Frames View","Frames View"),"javascript:window.parent.location.replace('admin.php?useframes=".iif($adminnav == "frames",0,1)."')")." ]");
  echotabledescription("You can expand the navigation section to the left by clicking on the arrows, then click on the task you wish to go to.",1);
  echotabledescription("For the latest news about VirtuaNews please go to the VirtuaNews homepage".returnlinkcode("here","/",1).".",1);
  echotabledescription("Please log into the VirtuaNews members area".returnlinkcode("here","/",1)." to download the latest versions and modules for your site.",1);
  echotabledescription("If you require help with any part of the administration panel then you should find a link in the top right corner of each table which will provide you with a help page.",1);
  echotabledescription("If you need further help you can visit the VirtuaNews forums".returnlinkcode("here","/",1)." or submit a trouble ticket".returnlinkcode("here","/",1).".",1);

  echo "<!--Nullified by [WTN]&[WDYL]-->";
  if ($serverstats = @exec("uptime")) {
    echotabledescription("<b>Server Loads</b>",1,0,30);
    preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$serverstats,$load);

    $load[1] = iif(intval($load[1]) >= 2,"<span class=\"red\">$load[1]</span>",$load[1]);
    $load[2] = iif(intval($load[2]) >= 2,"<span class=\"red\">$load[2]</span>",$load[2]);
    $load[3] = iif(intval($load[3]) >= 2,"<span class=\"red\">$load[3]</span>",$load[3]);

    echotabledescription("$load[1], $load[2], $load[3]",1);
  }

  echotablefooter();

  if (!$use_forum) {
    $data_arr = query_first("SELECT count(userid) AS count FROM news_user WHERE moderated = 0");

    echo "<br />\n";
    echotableheader("Moderate Users",1);
    echotabledescription("There are currently $data_arr[count] users awaiting moderated.  To moderate them please go <a href=\"admin.php?action=user_mod\">here</a>.",1);
    echotablefooter();
  }

echo '<script type="text/javascript">
if (vn_version > \''.$version.'\') {
  document.writeln(\'<br /><br /><table cellspacing="0" cellpadding="2" class="header"><tr><td class="header">THERE IS A NEW VERSION OF VIRTUANEWS AVAILABLE</td></tr></table><table cellpadding="2" cellspacing="0" class="main"><tr><td><span class="red">There is a newer version of VirtuaNews than the one you are running. <br /><br />You are advised to upgrade this site as soon as possible as the newer version could contain bug fixes which affect your site.</span></td></tr></table>\');
}
</script>';

  echohtmlfooter();

break;

case "misc_logout":

  dologout();

  $loggedin = 0;

  echohtmlheader();
  echoformheader("","Logged Out");
  echotabledescription("You have now been logged out, if you wish to log back in please enter your details below.");
  updatehiddenvar("redirect","admin.php");
  echoinputcode("Username:","username");
  echopasswordcode("Password:","password");
  echoformfooter();
  echohtmlfooter();

break;

case "misc_menu":

  echohtmlheader("<base target=\"main\" />");
  echohtmlfooter();

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/misc.php
|| ####################################################################
\*======================================================================*/


?>