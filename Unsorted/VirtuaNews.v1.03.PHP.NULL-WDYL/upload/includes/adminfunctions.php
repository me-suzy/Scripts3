<?php


function adminerror($title,$error) {
  global $pagetitle;

  $GLOBALS[errorcode] = urlencode($title);

  echohtmlheader();
  echotableheader("Error - $title",1);
  echotabledescription("<b>Sorry, $error</b>",1,1);
  echotabledescription("<b>".returnlinkcode("Back","javascript:history.back(1)")."</b>",1,1);
  echotablefooter();
  echohtmlfooter();
  exit;

}

function cleanlocation() {

  $location = getenv("REQUEST_URI");
  $location .= iif(!getenv("QUERY_STRING"),"?");

  if (substr_count($location,"menu=")) {

    $location = preg_replace("/([^\"]*)menu=([^\"]*)&([^\"]*)/i","\\1&\\3",$location);
    $location = preg_replace("/([^\"]*)menu=([^\"]*)$/i","\\1",$location);
    $location = preg_replace("/([^\"]*)&&([^\"]*)/i","\\1&\\2",$location);

    if (substr($location,-1) == "&") {
      $location = substr($location,0,-1);
    }
  }

  if (substr_count($location,"useframes=")) {

    $location = preg_replace("/([^\"]*)useframes=([^\"]*)&([^\"]*)/i","\\1&\\3",$location);
    $location = preg_replace("/([^\"]*)useframes=([^\"]*)$/i","\\1",$location);
    $location = preg_replace("/([^\"]*)&&([^\"]*)/i","\\1&\\2",$location);

    if (substr($location,-1) == "&") {
      $location = substr($location,0,-1);
    }
  }

  return $location;
}

function echoadminredirect($redirect="") {

  if ($redirect) {
    $meta = "<meta http-equiv=\"refresh\" content=\"1; url=$redirect\">";
  }

  echohtmlheader($meta);
  echotableheader("Changes Saved",1);
  echotabledescription("Your changes have now been saved.",1);
  echotabledescription("If your browser does not automatically forward you, please click <a href=\"$redirect\">here</a>",1);
  echotablefooter();
  echohtmlfooter();

}

function echodatecode($title,$prefix,$usedate=0,$width1="",$note="") {

  if ($width1) {
    $width2 = 100 - $width1;
    $width1 = " style=\"width:$width1%\"";
    $width2 = " style=\"width:$width2%\"";
  }
  echo "  <tr>\n    <td$width1>$title</td>\n    <td$width2>\n";

  $monthnames = array(1=> "January",  "February",  "March", "April",  "May",  "June",  "July",  "August", "September",  "October",  "November",  "December");

  echo "      <select name=\"".$prefix."month\" class=\"form\">\n";

  foreach ($monthnames AS $key => $val) {
    echo "        <option value=\"$key\"".iif(intval(date("m",$usedate)) == $key," selected=\"selected\"","").">  $val  </option>\n";
  }

  echo "      </select>\n";

  echo "      <select name=\"".$prefix."day\" class=\"form\">\n";

  for ($i=1;$i<=31;$i++) {
    echo "        <option value=\"$i\"".iif(intval(date("d",$usedate)) == $i," selected=\"selected\"","").">  $i  </option>\n";
  }

  echo "      </select>\n";

  echo "      <select name=\"".$prefix."year\" class=\"form\">\n";

  $startyear = date("Y",$usedate);
  for($i=$startyear-5;$i<=$startyear+5;$i++) {
    echo "        <option value=\"$i\"".iif(intval(date("Y",$usedate)) == $i," selected=\"selected\"","").">  $i  </option>\n";
  }

  echo "      </select>\n";
  echo iif($note,"    $note","");

  echo "    </td>\n  </tr>\n";

}

function echodeleteconfirm($what,$action,$id,$extratext="",$extraurl="",$do="delete") {

  echohtmlheader();
  echotableheader(ucwords($do)." ".ucwords($what),1);
  echotabledescription("<b>Are you sure you want to continue and $do this $what? $extratext</b>",1);
  echotabledescription("<b><a href=\"admin.php?action=$action&id=$id$extraurl\">Yes</a> / <a href=\"javascript:history.back(1)\">No</a></b>",1,1);
  echotablefooter();
  echohtmlfooter();

}

function echoformfooter($colspan=2,$value="Submit",$showpreview=0,$extra="") {

  global $formhiddenfields;

  echo "  <tr>
    <td colspan=\"$colspan\" align=\"center\">
$formhiddenfields      <input type=\"submit\" name=\"submit\" value=\"$value\" class=\"form\" />
$extra      <input type=\"reset\" name=\"reset\" value=\"Reset\" class=\"form\" />\n";

  if ($showpreview) {
    echo "      <input type=\"submit\" name=\"preview\" value=\"Preview\" class=\"form\" />\n";
  }

  echo "    </td>
  </tr>
</table>
</form>\n";

}

function echoformheader($target,$title,$colspan=2,$uploadform=0,$formname="form") {

  global $action,$errorcode;

  if ($uploadform) {
    $enctype = " enctype=\"multipart/form-data\"";
  }

  $GLOBALS[formhiddenfields] = "      <input type=\"hidden\" name=\"action\" value=\"$target\" />\n";

  echo "<form action=\"admin.php\" method=\"post\" name=\"$formname\" id=\"$formname\"$enctype>

<table cellspacing=\"0\" cellpadding=\"2\" class=\"header\">
  <tr>
    <td class=\"header\">$title</td>
    <td class=\"help\"><!--[WTN]&[WDYL]--></td>
  </tr>
</table>
<table cellpadding=\"2\" cellspacing=\"2\" class=\"main\">\n";

}

function echohtmlfooter() {

  global $debug,$version,$action,$loggedin,$gzipoutput,$gziplevel,$adminnav;

  if ($loggedin & ($adminnav == "page")) {
    echo "\n    </td>\n  </tr>\n</table>\n";
  }

  if ($action != "misc_menu") {
    echo "<table width=\"100%\">
  <tr>
    <td align=\"center\">VirtuaNews Admin Panel Version $version</td>
  </tr>\n";

    if ($debug > 0) {

      global $pagestarttime,$db_query_count;

      $pageendtime = microtime();

      $starttime = explode(" ",$pagestarttime);
      $endtime = explode(" ",$pageendtime);

      $totaltime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];

      settype($db_query_count,"integer");

      echo "  <tr>
    <td align=\"center\">Page generated in ".round($totaltime,4)." seconds with $db_query_count database queries</td>
  </tr>\n";
    }

    if ($debug == 3) {
      global $db_query_arr;

      if (!empty($db_query_arr)) {

        echo "  <tr>\n    <td>\n      SQL Queries:<br />\n      <pre>\n";

        foreach ($db_query_arr AS $val) {
          $val = htmlspecialchars($val);
          $val = preg_replace("/^SELECT/i","<font style=\"color:red;font-weight:bold\">SELECT</font>",$val);
          $val = preg_replace("/^UPDATE/i","<font style=\"color:blue;font-weight:bold\">UPDATE</font>",$val);
          $val = preg_replace("/^DELETE/i","<font style=\"color:orange;font-weight:bold\">DELETE</font>",$val);
          $val = preg_replace("/^INSERT/i","<font style=\"color:green;font-weight:bold\">INSERT</font>",$val);
          echo "$val\n";
        }

        echo "      </pre>\n    </td>\n  </tr>\n";
      }
    }

    echo "</table>\n";
  }

  echo "\n</div>\n</body>
</html>";

  if ($gzipoutput) {
    $page = ob_get_contents();
    ob_end_clean();
    $page = gzipoutput($page,$gziplevel);
    header("Content-Length: ".strlen($page));
    echo $page;
  }
}

function echohtmlheader($javascript="") {

  global $sitename,$loggedin,$gzipoutput,$action,$adminnav,$admindirectory;
  global $canpostnews,$userinfo,$use_forum,$newsallowlogoup,$adminmenu,$adminmenu,$browser,$location,$adminnav;

  if ($gzipoutput) {
    ob_start();
  }

  if ($javascript == "qhtmlcode") {
    $javascript = "<script type=\"text/javascript\" src=\"$admindirectory/qhtmlcode.js\"></script>";
  } elseif ($javascript == "adminjs") {
    $javascript = "<script type=\"text/javascript\" src=\"$admindirectory/adminjs.js\"></script>";
  }

  echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head>
  <title>$sitename Admin Panel</title>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
  <link rel=\"stylesheet\" type=\"text/css\" href=\"$admindirectory/style.css\" />
  $javascript
  <script type=\"text/javascript\">
  <!--
  function newwindow(windowurl) {
    window.open(windowurl);
  }
  -->
  </script>
";

  $GLOBALS[useragent] = getenv("HTTP_USER_AGENT");
  $GLOBALS[browser] = getbrowser();

  if ((($GLOBALS[browser] == "MSIE") & ($adminnav == "page") & $loggedin) | ($action == "misc_menu")) {
    echo "<script type=\"text/javascript\" src=\"$admindirectory/toggle.js\"></script>\n";
  }

  echo "</head>\n\n<body>\n<div>\n";

  if ((($adminnav == "page") | ($action == "misc_menu")) & $loggedin) {

    if ($adminnav == "page") {
      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n  <tr valign=\"top\">\n    <td style=\"width:200px\">\n";
    }

    echo "<!-- Start Menu -->\n<table width=\"".iif($adminnav == "frames","100%","190")."\" cellpadding=\"2\" cellspacing=\"0\">\n <tr>\n   <td class=\"header\">Site Options</td>\n </tr>\n <tr>\n   <td>\n";$columns=echotablerestart();

    $rows = returnoptionrow(returnlinkcode("Admin main","admin.php?action=misc_index"),returnlinkcode("Main page","index.php",1));
    $rows .= returnoptionrow(returnlinkcode("Log Out","admin.php?action=misc_logout"),iif($browser == "MSIE",returnlinkcode(iif($adminmenu == "open","Contract All","Expand All"),iif($adminnav == "page","$location&menu=".iif($adminmenu == "open","close","open"),"javascript:window.parent.frames['menu'].location.replace('admin.php?action=misc_menu&menu=".iif($adminmenu == "open","close","open")."')"))),iif($browser == "MSIE",0,1));
    echooptiontable("Main",$rows,"misc");

    unset($rows);
    if ($canpostnews) {
      $rows = returnoptionrow(returnlinkcode("Add","admin.php?action=news_add"),returnlinkcode("Edit / Delete","admin.php?action=news_list"),0,1);
      $rows .= returnoptionrow(returnlinkcode("View Archive","admin.php?action=news_archive"),iif($newsallowlogoup,returnlinkcode("Upload Logo","admin.php?action=newslogo_upload"),""),iif($newsallowlogoup,0,1));
    }

    if ($userinfo[candeletelogos]) {
      $rows .= returnoptionrow(returnlinkcode("Delete Logos","admin.php?action=newslogo"),0,1);
    }

    if ($rows) {
      echooptiontable("News Posts",$rows,"news");
    }

    if ($userinfo[caneditcategories]) {
      $rows = returnoptionrow(returnlinkcode("Add","admin.php?action=category_add"),returnlinkcode("Edit / Delete","admin.php?action=category"),0,1);
      echooptiontable("News Categories",$rows,"category",$columns);
    }

    if ($userinfo[caneditarticles]) {
      $rows = returnoptionrow(returnlinkcode("Add","admin.php?action=article_add"),returnlinkcode("Edit / Delete","admin.php?action=article"),0,1);
      $rows .= returnoptionrow(returnlinkcode("Add Category","admin.php?action=article_cat_add"),"",1);
      echooptiontable("Articles",$rows,"article");
    }

    if ($userinfo[caneditpolls]) {
      $rows = returnoptionrow(returnlinkcode("Add","admin.php?action=poll_add"),returnlinkcode("Edit / Delete","admin.php?action=poll"),0,1);
      echooptiontable("Polls",$rows,"poll");
    }

    if ($userinfo[caneditstaff]) {
      $rows = returnoptionrow(returnlinkcode("Add","admin.php?action=staff_search_form"),returnlinkcode("Edit / Delete","admin.php?action=staff"),0,1);
      echooptiontable("Staff",$rows,"staff");
    }

    if ($userinfo[caneditusers] & !$use_forum) {
      $rows = returnoptionrow(returnlinkcode("Add","admin.php?action=user_add"),returnlinkcode("Edit / Delete","admin.php?action=user"),0,1);
      $rows .= returnoptionrow(returnlinkcode("Email Users","admin.php?action=user_email"),returnlinkcode("Edit PM's","admin.php?action=user_pm"));
      echooptiontable("Users",$rows,"user");
    }

    if ($userinfo[caneditprofilefields] & !$use_forum) {
      $rows = returnoptionrow(returnlinkcode("Add","admin.php?action=profilefield_add"),returnlinkcode("Edit / Delete","admin.php?action=profilefield"),0,1);
      echooptiontable("Profile Fields",$rows,"profilefield");
    }

    if ($userinfo[caneditsmilies] & !$use_forum) {
      $rows = returnoptionrow(returnlinkcode("Add","admin.php?action=smilie_add"),returnlinkcode("Edit / Delete","admin.php?action=smilie"),0,1);
      echooptiontable("Smilies",$rows,"smilie");
    }

    $moddata = getmoddata();
    unset($modlinks);
    while ($mod_arr = fetch_array($moddata)) {
      if ($userinfo[caneditmod_.$mod_arr[name]]) {
        $modlinks .= returnoptionrow(returnlinkcode("Edit $mod_arr[text]","admin.php?action=$mod_arr[name]"),"",1);
      }
    }

    if ($userinfo[caneditmodules]) {
      $modlinks .= returnoptionrow(returnlinkcode("Add","admin.php?action=module_add"),returnlinkcode("Edit / Delete","admin.php?action=module"),0,1);
    }

    if ($modlinks) {
      echooptiontable("Modules",$modlinks,"module");
    }

    if ($userinfo[caneditthemes]) {
      $rows = returnoptionrow(returnlinkcode("Add","admin.php?action=theme_add"),returnlinkcode("Edit / Delete","admin.php?action=theme"),0,1);
      echooptiontable("Themes / Pages",$rows,"theme");
    }

    if ($userinfo[caneditoptions]) {
      $rows = returnoptionrow(returnlinkcode("General","admin.php?action=option&set=general"),returnlinkcode("Statistics","admin.php?action=option&set=stats"));
      $rows .= returnoptionrow(returnlinkcode("Output","admin.php?action=option&set=output"),returnlinkcode("Article","admin.php?action=option&set=article"));
      $rows .= returnoptionrow(returnlinkcode("Comments","admin.php?action=option&set=comment"),returnlinkcode("Recent Post","admin.php?action=option&set=recentpost"));
      $rows .= returnoptionrow(returnlinkcode("News Post","admin.php?action=option&set=news"),returnlinkcode("Site Jump","admin.php?action=option&set=sitejump"));
      $rows .= returnoptionrow(returnlinkcode("Polls","admin.php?action=option&set=polls"),returnlinkcode("Search","admin.php?action=option&set=search"));
      $rows .= returnoptionrow(returnlinkcode("Users","admin.php?action=option&set=user"),returnlinkcode("IP Banning","admin.php?action=option&set=ipbanning"));
      $rows .= returnoptionrow(returnlinkcode("Date and Time","admin.php?action=option&set=datetime"),"",1,0,0);
      $rows .= returnoptionrow(returnlinkcode("Private Messaging","admin.php?action=option&set=pm"),"",1,0,0);
      $rows .= returnoptionrow(returnlinkcode("Message Formatting","admin.php?action=option&set=format"),"",1,0,0);
      $rows .= returnoptionrow(returnlinkcode("Comment Posting","admin.php?action=option&set=commentpost"),"",1,0,0);
      $rows .= returnoptionrow(returnlinkcode("Comment Editing","admin.php?action=option&set=commentedit"),"",1,0,0);
      echooptiontable("Options",$rows,"option");
    }

    if ($userinfo[canmaintaindb]) {
      $rows = returnoptionrow(returnlinkcode("Backup Database","admin.php?action=maintain_backup"),"",1,0,0);
      $rows .= returnoptionrow(returnlinkcode("Prune Comments","admin.php?action=maintain_comments"),"",1,0,0);
      $rows .= returnoptionrow(returnlinkcode("Prune News","admin.php?action=maintain_news"),"",1,0,0);
      $rows .= returnoptionrow(returnlinkcode("Import systems","admin.php?action=maintain_import"),"",1,0,0);
      echooptiontable("Maintain Database",$rows,"maintain");
    }

    if ($userinfo[canviewlog]) {
      $rows = returnoptionrow(returnlinkcode("View Admin Log","admin.php?action=log"),"",1,0,0);
      $rows .= returnoptionrow(returnlinkcode("Server Stats","admin.php?action=log_server"),"",1,0,0);
      $rows .= returnoptionrow(returnlinkcode("Post Stats","admin.php?action=log_post"),"",1,0,0);
      echooptiontable("Admin Log &amp; Stats",$rows,"log");
    }

    echo "   </td>\n  </tr>\n</table>\n<!-- End Menu -->\n";

    if ($adminnav == "page") {
      echo "    </td>\n    <td>\n";
    }
  }
}

function echoinputcode($title,$name,$value="",$size=40,$optional=0,$width1="",$note="") {

  $htmlval = htmlspecialchars($value);
  if ($width1) {
    $width2 = 100 - $width1;
    $width1 = " style=\"width:$width1%\"";
    $width2 = " style=\"width:$width2%\"";
  }
  echo "  <tr>\n    <td$width1>$title".iif($optional," <span class=\"red\">(Optional)</span>","")."</td>\n    <td$width2><input type=\"text\" size=\"$size\" name=\"$name\" value=\"$htmlval\" class=\"form\" />$note</td>\n  </tr>\n";

}

function echonewscatselect($title,$name,$value=0,$showall=0,$alloption=0) {

  global $userinfo,$cat_arr;
  static $getdata;
  unset($code);

  echo "  <tr>\n    <td>$title</td>\n    <td>\n      <select name=\"$name\" class=\"form\">\n";

  if ($alloption) {
    echo "        <option value=\"0\">---------All---------</option>\n";
  }

  if (!empty($cat_arr)) {
    foreach ($cat_arr as $key => $cat) {
      $cat[id] = $key;
      echo returncatoption($cat,0,$value,1,$showall);
    }
  }

  echo "      </select>\n    </td>\n  </tr>\n";

}

function echooptiontable($title,$rows,$name="",$extra="") {

  global $action,$modname,$adminmenu,$browser,$adminmenu,$admindirectory;

  $script = explode("_",$action);
  $script = $script[0];

  if ($script == "comment") {
    $script = "misc";
  }

  if ($script == "newslogo") {
    $script = "news";
  }

  if ($modname) {
    $script = "module";
  }

  if ($adminmenu == "open") {
    $script = $name;
  }

  echo "      <table cellspacing=\"0\" cellpadding=\"2\" class=\"menu_header\">
        <tr>
          <td class=\"menu_header\"".iif($browser == "MSIE"," style=\"cursor:hand\" onmouseover=\"this.style.background='#000066'\" onmouseout=\"this.style.background='#5A77B2'\" onclick=\"menutoggle(table_$name,image_$name)\"><img src=\"$admindirectory/icons/".iif($script == $name,"minus","plus").".gif\" id=\"image_$name\" alt=\"\" />","><img src=\"$admindirectory/icons/item.gif\" alt=\"\" />")."&nbsp;$title</td>
        </tr>
      </table>
      <table cellspacing=\"2\" cellpadding=\"2\" class=\"menu\" id=\"table_$name\"".iif($browser == "MSIE"," style=\"display:".iif($script == $name,"","none")."\"","").">
$rows      </table>
      <table>
        <tr>
          <td class=\"menu_spacer\">$extra</td>
        </tr>
      </table>
";

}

function echopasswordcode($title,$name,$value="",$size=40) {

  $htmlval = htmlspecialchars($value);
  echo "  <tr>\n    <td>$title</td>\n    <td><input type=\"password\" size=\"$size\" name=\"$name\" value=\"$htmlval\" class=\"form\" /></td>\n  </tr>\n";

}

function echopermissionselect($title,$name,$value) {

  echo "  <tr>
    <td>$title</td>
    <td>
      <input type=\"radio\" name=\"$name\" value=\"0\"".iif($value == 0," checked=\"checked\"","")." /> No One<br />
      <input type=\"radio\" name=\"$name\" value=\"1\"".iif($value == 1," checked=\"checked\"","")." /> Staff Only<br />
      <input type=\"radio\" name=\"$name\" value=\"2\"".iif($value == 2," checked=\"checked\"","")." /> Registered Users Only<br />
      <input type=\"radio\" name=\"$name\" value=\"3\"".iif($value == 3," checked=\"checked\"","")." /> All Users
    </td>
  </tr>
";

}

function echoqhtmlhelp() {

  echo "  <tr>
    <td>Quick HTML:</td>
    <td>
      <a href=\"javascript:void(0)\" onclick=\"inserttag(document.form,'b')\" title=\"Add bold text\">Bold</a>
      | <a href=\"javascript:void(0)\" onclick=\"inserttag(document.form,'i')\" title=\"Add italic text\">Italic</a>
      | <a href=\"javascript:void(0)\" onclick=\"inserttag(document.form,'u')\" title=\"Add underlined text\">Underline</a>
      | <a href=\"javascript:void(0)\" onclick=\"insertlink(document.form,'url')\" title=\"Add a web address\">URL</a>
      | <a href=\"javascript:void(0)\" onclick=\"insertlink(document.form,'email')\" title=\"Add an email address\">Email</a>
      | <a href=\"javascript:void(0)\" onclick=\"inserttag(document.form,'img')\" title=\"Add an image\">Image</a>
      | <a href=\"javascript:void(0)\" onclick=\"dolist(document.form)\" title=\"Add a bulleted list\">List</a>
      | <a href=\"javascript:void(0)\" onclick=\"inserttag(document.form,'quote')\" title=\"Add a quote by someone\">Quote</a>
    </td>
  </tr>\n";

}

function echotabledescription($content,$colspan=2,$center=0,$height=0) {

  if ($center) {
    $center_code = " align=\"center\"";
  }

  if ($height) {
    $height_code = " height=\"$height%\"";
  }

  echo "  <tr>
    <td colspan=\"$colspan\"$center_code$height_code>$content</td>
  </tr>
";
}

function echotablefooter() {

  echo "</table>\n";

}

function echotableheader($title,$colspan=2) {

  global $action,$errorcode;

  echo "<table cellspacing=\"0\" cellpadding=\"2\" class=\"header\">
  <tr>
    <td class=\"header\">$title</td>
    <td class=\"help\"><!--[WTN]&[WDYL]--></td>
  </tr>
</table>
<table cellpadding=\"2\" cellspacing=\"0\" class=\"main\">\n";

}

function echotablerestart($title="",$colspan=2,$addbr=0) {

  global $action,$errorcode;

  if ($addbr) {
    $code = "<br />";
  }

  $code .= "<table cellspacing=\"0\" cellpadding=\"2\" class=\"header\">\n  <tr>\n    <td class=\"header\">$title</td>\n    <td class=\"help\"><!--[WTN]&[WDYL]--></td>\n  </tr>\n</table>\n<table cellpadding=\"2\" cellspacing=\"0\" class=\"main\">\n";  if ($addbr | !$addbr) { $code = ""; }

  return $code;

}

function echotablerow($title,$content,$height="",$width="") {

  if ($height) {
    $style = " style=\"height:$height";
  }

  if ($width) {
    if ($style) {
      $style .= ";width:".$width."%\"";
    } else {
      $style = " style=\"width:$width%\"";
    }
  } elseif ($style) {
    $style .= "\"";
  }

  echo "  <tr>
    <td$style>$title</td>
    <td>$content</td>
  </tr>\n";

}

function echotextareacode($title,$name,$value="",$rows=4,$cols=40,$optional=0,$width="") {

  global $browser;

  $htmlval = htmlspecialchars($value);
  if ($width) {
    $width = " style=\"width:$width%\"";
  }

  echo "  <tr>\n    <td$width>$title".iif($optional," <span class=\"red\">(Optional)</span>")."</td>\n    <td><textarea name=\"$name\" rows=\"$rows\" cols=\"$cols\" class=\"form\">$htmlval</textarea></td>\n  </tr>\n";

}

function echouploadcode($title,$name,$maxfilesize=1000000,$size=40) {

  echo "  <tr>\n    <td>$title</td>\n    <td>\n      <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$maxfilesize\" />\n      <input type=\"file\" class=\"form\" name=\"$name\" size=\"$size\" />\n    </td>\n  </tr>\n";

}

function echoyesnocode($title,$name,$value=1,$yestext="Yes",$notext="No",$width1="") {

  if ($width1) {
    $width2 = 100 - $width1;
    $width1 = " style=\"width:$width1%\"";
    $width2 = " style=\"width:$width2%\"";
  }

  if ($value) {
    echo "  <tr>\n    <td$width1>$title</td>\n    <td$width2>$yestext <input type=\"radio\" checked=\"checked\" name=\"$name\" value=\"1\" /> $notext <input type=\"radio\" name=\"$name\" value=\"0\" /></td>\n  </tr>\n";
  } else {
    echo "  <tr>\n    <td$width1>$title</td>\n    <td$width2>$yestext <input type=\"radio\" name=\"$name\" value=\"1\" /> $notext <input type=\"radio\" checked=\"checked\" name=\"$name\" value=\"0\" /></td>\n  </tr>\n";
  }
}

function getmoddata() {
  static $moddata;
  if (isset($moddata)) {
    data_seek($moddata,0);
  } else {
    $moddata = query("SELECT id,name,text,options,enable FROM news_module");
  }
  return $moddata;
}

function returncatoption($cat,$parentid=0,$value=0,$level=0,$showall=0) {

  global $userinfo,$cat_arr;

  if (($cat[parentid] == $parentid) & ($showall | $userinfo[canpost_.$cat[topid]])) {
    if ($level == 3) {
      $code = "        <option value=\"$cat[id]\"".iif($value == $cat[id]," selected=\"selected\"","").">---- $cat[name]</option>\n";

    } elseif ($level == 2) {

      $code = "        <option value=\"$cat[id]\"".iif($value == $cat[id]," selected=\"selected\"","").">-- $cat[name]</option>\n";

      $sub_arr = $cat_arr;
      foreach ($sub_arr AS $key => $val) {
        $val[id] = $key;
        $code .= returncatoption($val,$cat[id],$value,3,$showall);
      }
      unset($sub_arr);

    } else {

      $code = "        <option value=\"$cat[id]\"".iif($value == $cat[id]," selected=\"selected\"","").">$cat[name]</option>\n";

      $top_arr = $cat_arr;
      foreach ($top_arr AS $key => $val) {
        $val[id] = $key;
        $code .= returncatoption($val,$cat[id],$value,2,$showall);
      }
      unset($top_arr);
    }
  }
  return $code;
}

function returncheckboxcode($name,$value,$text,$checked=0) {

  if ($checked) {
    $code = "      <input type=\"checkbox\" name=\"$name\" value=\"$value\" checked=\"checked\" /> $text<br />\n";
  } else {
    $code = "      <input type=\"checkbox\" name=\"$name\" value=\"$value\" /> $text<br />\n";
  }
  return $code;
}

function returnlinkcode($text,$url,$newwindow=0) {

  if ($newwindow) {
    $url = "javascript:newwindow('$url')";
  }

  return " <a href=\"".str_replace("&","&amp;",$url)."\">$text</a>";
}

function returnminitable($text,$center=1,$width=50) {
  return "      <table cellpadding=\"2\" cellspacing=\"0\"".iif($center," style=\"text-align:center\"","")." width=\"$width%\">\n$text      </table>\n";
}

function returnminitablerow($column1="",$column2="",$column3="",$column4="",$column5="",$column6="",$column7="",$column8="",$column9="",$column10="") {

  $code = "        <tr>\n";

  $code .= iif($column1,"          <td>$column1</td>\n","");
  $code .= iif($column2,"          <td>$column2</td>\n","");
  $code .= iif($column3,"          <td>$column3</td>\n","");
  $code .= iif($column4,"          <td>$column4</td>\n","");
  $code .= iif($column5,"          <td>$column5</td>\n","");
  $code .= iif($column6,"          <td>$column6</td>\n","");
  $code .= iif($column7,"          <td>$column7</td>\n","");
  $code .= iif($column8,"          <td>$column8</td>\n","");
  $code .= iif($column9,"          <td>$column9</td>\n","");
  $code .= iif($column10,"          <td>$column10</td>\n","");

  $code .= "        </tr>\n";

  return $code;

}

function returnoptionrow($cell1="",$cell2="",$span=0,$highlight=0,$center=1) {

  $code = "        <tr>\n";
  if ($cell1) {
    $code .= "          <td class=\"".iif($highlight==1,"menu_highlight","menu")."\" ".iif($span,"colspan=\"2\"".iif($center," align=\"center\"",""),"style=\"width:50%\"").">$cell1</td>\n";
  }
  if ($cell2) {
    $code .= "          <td class=\"".iif($highlight==2,"menu_highlight","menu")."\">$cell2</td>\n";
  }
  $code .= "        </tr>\n";

  return $code;
}

function updateadminlog($extra_info="") {

  global $action,$staffid,$modname;

  $temp_action = explode("_",$action);

  if ($modname) {
    $script = $modname;
  } else {
    $script = $temp_action[0];
  }

  unset($temp_action[0]);
  $script_action = join("_",$temp_action);

  $ipaddress = getenv("REMOTE_ADDR");

  query("INSERT INTO news_adminlog VALUES (NULL,'$staffid','".time()."','$script','$script_action','$extra_info','$ipaddress')");
}

function updatehiddenvar($name,$value="") {

  $htmlval = htmlspecialchars($value);
  $GLOBALS[formhiddenfields] .= "      <input type=\"hidden\" name=\"$name\" value=\"$htmlval\" />\n";
}

function writeallpages() {

  global $cat_arr,$timeoffset,$newsdate,$newsperpage,$forumpath,$version,$homeurl,$pagesetid,$themeid,$foruminfo;
  global $sitename,$defaultcategory;
  static $gettitles,$getmods;

  $old_themeid = $themeid;
  $old_pagesetid = $pagesetid;

  $get_tempsets = query("SELECT
    news_theme.id,
    news_pageset.id AS pagesetid
    FROM news_pageset
    LEFT JOIN news_theme ON news_pageset.id = news_theme.pagesetid
    WHERE news_theme.id <> ''
    GROUP BY news_theme.pagesetid");

  while ($set_arr = fetch_array($get_tempsets)) {

    $GLOBALS[themeid] = $set_arr[id];
    $GLOBALS[pagesetid] = $set_arr[pagesetid];

    writeaboutus();
    writearticles();
    writesubpages();

    foreach ($cat_arr AS $key => $val) {
      if ($val[displaymain]) {
        writeindex($key);
      }
      writecomment($key);
      writepolloptions($key);
      writepollresults($key);
    }

    $catid = $defaultcategory;
    $cat_name = $cat_arr[$catid][name];

    $info = getsitebits($catid);
    $announcement = $info[ann];
    $forumoptions = $info[forumoptions];
    $module_links = $info[mod_links];
    $recentpost = $info[recentpost];

    eval("\$menu = \"".returnpagebit("menu_$catid")."\";");
    eval("\$header = \"$info[header]\";");
    eval("\$footer = \"$info[footer]\";");

    $moddata = getmoddata();
    while ($mod_arr = fetch_array($moddata)) {
      $modname = $mod_arr[name];
      $modid = $mod_arr[id];
      if (file_exists("modules/$modname/writepages.php") & $mod_arr[enable]) {
        getmodoptions($modid,"",$mod_arr[options]);
        include("modules/$modname/writepages.php");
      }
    }
  }

  $GLOBALS[themeid] = $old_themeid;
  $GLOBALS[pagesetid] = $old_pagesetid;
}

if (!function_exists("verifyid")) {
  function verifyid($table,$checkid,$fieldname="id") {

    settype($checkid,"integer");

    if (empty($checkid)) {
      adminerror("Invalid ID","You have specified an invalid id");
    }

    $checkid = query_first("SELECT COUNT($fieldname) AS count FROM $table WHERE $fieldname = $checkid");

    if ($checkid[count] == 0) {
      adminerror("Invalid ID","You have specified an invalid id");
    }
  }
}

/*======================================================================*\
|| ####################################################################
|| # File: includes/adminfunctions.php
|| ####################################################################
\*======================================================================*/

?>