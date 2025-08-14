<?php /* ***** Orca Ringmaker - Head File ********************* */

/* ***************************************************************
* Orca Ringmaker v2.3d
*  A comprehensive web ring creation and managment script
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
*************************************************************** */

/* ***** User Variables *************************************** */
/* ************************************************************ */
$rData['admin'] = "admin";
$rData['password'] = "password";

$dData['hostname'] = "hostname";
$dData['username'] = "username";
$dData['password'] = "password";
$dData['database'] = "database";
$dData['tablename'] = "orcaringmaker";

$rData['PHPMailer'] = "orca/phpmailer.php";
$rData['templateDir'] = "orca/";


/* ***** Functions ******************************************** */
/* ************************************************************ */
function set_vData($field, $input) {
  global $dData, $vData;

  mysql_query("UPDATE `{$dData['tablevar']}` SET `$field`='".strFunx($input, "0110")."';");
  $vData[$field] = $input;
}

function set_uData($id, $field, $input) {
  global $dData, $uData;

  mysql_query("UPDATE `{$dData['tablename']}` SET `$field`='".strFunx($input, "0110")."' WHERE `id`='$id';");
  if (isset($uData['id']) && $id == $uData['id']) $uData[$field] = $input;
}

function strFunx($input, $command = "0000", $fix = true) {
  $input = trim($input);
  if ($command{0}) $input = strip_tags($input);
  if ($command{1}) $input = stripslashes($input);
  if ($command{2}) $input = addslashes($input);
  if ($command{3}) {
    $input = htmlspecialchars($input);
    if ($fix) $input = preg_replace("/&amp;(#\d{2,4}|[a-z]{2,5});/i", "&$1;", $input);
  }
  return $input;
}

function gatekeeper($user, $pass) {
  global $rData, $dData;

  if ($user == $rData['admin'] && $pass == $rData['password']) return 3;

  $urow = mysql_query("SELECT `password`, `admin` FROM `{$dData['tablename']}` WHERE `username`='".addslashes($user)."';");
  if (mysql_num_rows($urow) && crypt($pass, mysql_result($urow, 0, "password")) == mysql_result($urow, 0, "password"))
    return (mysql_result($urow, 0, "admin")) ? 2 : 1;

  return 0;
}

function gen_conf($length) {
  $build = "";

  while (strlen($build) < $length) {
    $randChar = chr(mt_rand(48, 122));
    if (preg_match("/[\w\d]/i", $randChar)) $build .= $randChar;
  }
  return $build;
}

function navverify($id) {
  global $rData, $dData;

  $id = (int)$id;

  $grabUser = mysql_query("SELECT `URI` FROM `{$dData['tablename']}` WHERE `id`='$id';");
  $grabText = @fopen(mysql_result($grabUser, 0, "URI"), "rb");

  if ($grabText) {
    ini_set("auto_detect_line_endings", "On");

    while (!feof($grabText)) {
      $resultStr = fgets($grabText, 4096);

      if (strpos($resultStr, "<script") !== false) {
        while (!feof($grabText) && strlen($resultStr) < 512) $resultStr .= fgets($grabText, 4096);
        if (preg_match('/<script.*?src="'.preg_quote($rData['thisURI'], "/").'\?Nav&(amp;)?[\d]+?".*?>[\s]*?<\/script>/is', $resultStr)) {
          fclose($grabText);
          return "found";
        }
      }
    }
    fclose($grabText);
    return "not found";
  } else return "error";
}

function ring_mail($to, $subject, $message) {
  global $rData, $vData, $lang;

  require_once($rData['PHPMailer']);

  $mail = new PHPMailer();

  $mail->From = $vData['ringemail'];
  $mail->FromName = $vData['ringname'];
  if ($vData['smtp']) {
    $mail->Mailer = "smtp";
    $mail->Host = $vData['smtp'];
  } else $mail->Mailer   = "mail";
  $mail->CharSet = $lang['charset'];

  $message .= "\n\n-- \n";
  $message .= $rData['thisURI']."\n";
  $message .= $vData['ringname']."\n";

  $mail->Body     = $message;
  $mail->Subject  = $subject;

  if (!is_array($to[0])) $to = array($to);

  foreach ($to as $recips) {
    $mail->AddAddress($recips[1], $recips[0]);

    if(!$mail->Send()) {
      $eData['error'][] = $mail->ErrorInfo;
      $eData['error'][] = sprintf($lang['er_c'], $recips[1]);
    }
    $mail->ClearAddresses();
  }
}

function loadTemplate($filebit) {
  global $_ORMPG, $rData;

  $template = implode("", preg_grep("/^[^#]/", file($rData['templateDir']."orm_template_$filebit.txt")));

  do {
    $md5 = md5($template);

    preg_match_all("/!\{(.+?)\}!/", $template, $matches);
    while (list($key, $value) = each($matches[1])) $matches[2][$key] = (!$_ORMPG[$value]) ? "~!true!~" : "~!false!~";
    if (isset($matches[2])) $template = str_replace($matches[0], $matches[2], $template);

    preg_match_all("/&\{(.+?)\}&/", $template, $matches);
    while (list($key, $value) = each($matches[1])) $matches[2][$key] = ($_ORMPG[$value]) ? "~&true&~" : "~&false&~";
    if (isset($matches[2])) $template = str_replace($matches[0], $matches[2], $template);

    preg_match_all("/=\{(.+?)\}=/", $template, $matches);
    while (list($key, $value) = each($matches[1])) {
      $expression = explode(":", $value);
      $matches[2][$key] = "~=false=~";
      if (count($expression >= 3)) {
        if (count($expression) > 3) $expression[2] = implode("", array_slice($expression, 2));
        $expression = array_map("trim", $expression);
        if (isset($_ORMPG[$expression[0]])) {
          if (($expression[1] == "=" && $_ORMPG[$expression[0]] == $expression[2]) ||
              ($expression[1] == "!" && $_ORMPG[$expression[0]] != $expression[2]) ||
              ($expression[1] == ">" && $_ORMPG[$expression[0]] > $expression[2]) ||
              ($expression[1] == "<" && $_ORMPG[$expression[0]] < $expression[2]))
            $matches[2][$key] = "~=true=~";
        }
      }
    }
    if (isset($matches[2])) $template = str_replace($matches[0], $matches[2], $template);
  } while ($md5 != md5($template));  

  do {
    $md5 = md5($template);

    $cmatch = array(array(), array());
    $ch = array("!", "&", "=");
    $ty = array("false", "true");

    for ($x = 0; $x < 3; $x++) {
      for ($y = 0; $y < 2; $y++) {
        preg_match_all("/~{$ch[$x]}{$ty[$y]}{$ch[$x]}~(.*?){$ch[$x]}\{\}{$ch[$x]}/s", $template, $matches);
        while (list($key, $value) = each($matches[1])) {
          if (strpos($value, "~{$ch[$x]}false{$ch[$x]}~") === false && strpos($value, "~{$ch[$x]}true{$ch[$x]}~") === false) {
            $cmatch[0][] = $matches[0][$key];
            $cmatch[1][] = ($y) ? $value : "";
          } else {
            preg_match("/^{$ch[$x]}\}\{{$ch[$x]}(.*?)~{$ch[$x]}(eurt|eslaf){$ch[$x]}~/s", strrev($matches[0][$key]), $match);
            if (count($match)) {
              $match = array_map("strrev", $match);
              $cmatch[0][] = $match[0];
              $cmatch[1][] = ($match[0]{2} == "f") ? "" : $match[1];
            }
          }
        }
      }
    }

    $template = str_replace($cmatch[0], $cmatch[1], $template);
  } while ($md5 != md5($template));

  do {
    $md5 = md5($template);

    $template = preg_replace("/%\{(.+?)\}%/e", "\$_ORMPG['$1'];", $template);
  } while ($md5 != md5($template));

  return $template;
}


/* ************************************************************ */
/* ***** Begin Program Execution ****************************** */
error_reporting(E_ALL);
$eData = array("success" => array(), "error" => array());
$dData['now'] = array_sum(explode(" ", microtime()));


/* ***** Magic Quotes Fix ************************************* */
if (get_magic_quotes_gpc()) {
  $fsmq = create_function('&$mData, $fnSelf', 'if (is_array($mData)) foreach ($mData as $mKey=>$mValue) $fnSelf($mData[$mKey], $fnSelf); else $mData = strFunx($mData, "0100");');
  $fsmq($_POST, $fsmq);
  $fsmq($_GET, $fsmq);
  $fsmq($_ENV, $fsmq);
  $fsmq($_SERVER, $fsmq);
  $fsmq($_COOKIE, $fsmq);
}
set_magic_quotes_runtime(0);


/* ************************************************************ */
/* ***** MySQL Setup ****************************************** */
$dData['online'] = false;

$db = @mysql_connect($dData['hostname'], $dData['username'], $dData['password']) or $eData['error'][] = mysql_errno().": ".mysql_error();
if (!count($eData['error'])) @mysql_select_db($dData['database'], $db) or $eData['error'][] = mysql_errno().": ".mysql_error();

if (!count($eData['error'])) {
  $dData['online'] = true;

  $dData['tablevar'] = $dData['tablename']."var";
  $dData['tablelog'] = $dData['tablename']."log";
  $dData['tablemail'] = $dData['tablename']."mail";
  $dData['tableauth'] = $dData['tablename']."auth";
  $dData['tablestat'] = $dData['tablename']."stat";
  $dData['tabletemp'] = $dData['tablename']."temp";

  $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablename']}` (
    `id` int(11),
    `order` int(11),
    `title` text,
    `description` text,
    `URI` text,
    `owner` text,
    `joindate` int(11),
    `username` text,
    `password` text,
    `status` tinytext,
    `navstatus` tinytext,
    `navtime` int(11),
    `email` text,
    `admin` tinytext
  ) TYPE=MyISAM;");

  $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablevar']}` (
    `ringname` text,
    `ringemail` text,
    `smtp` text,
    `sitelimit` tinyint(4),
    `statlimit` tinyint(4),
    `announcement` text,
    `navscript` text,
    `navhtml` text,
    `statcachetype` text,
    `statdate` int(11),
    `statkey` text,
    `statcache` longtext,
    `statlock` text,
    `helpflag` tinyint(4),
    `helpfile` text,
    `authimage` tinyint(4),
    `timezone` text,
    `tzoffset` tinyint(4),
    `staticon` text,
    `joindate` int(11)
  ) TYPE=MyISAM;");

  if (!mysql_num_rows(mysql_query("SELECT `ringname` FROM `{$dData['tablevar']}`;")))
    mysql_query("INSERT INTO `{$dData['tablevar']}` VALUES ('{$lang['db4']}', '{$lang['db5']}', NULL, 10, 20, '{$lang['db6']}', NULL, NULL, 'none', 0, NULL, '', 'false', 0, 'orca/orm_help_en.txt', 0, 'EST', -5, 'orca/o_stats.png', 0);");

  @mysql_query("DELETE FROM `{$dData['tablelog']}` WHERE `time` < '".(time() - 1800)."';");
  list($logrows) = @mysql_fetch_array(@mysql_query("SELECT COUNT(*) FROM `{$dData['tablelog']}`;"));
  if (!$logrows) @mysql_query("DROP TABLE `{$dData['tablelog']}`;");

  @mysql_query("DELETE FROM `{$dData['tablemail']}` WHERE `time` < '".(time() - 1800)."';");
  list($mailrows) = @mysql_fetch_array(@mysql_query("SELECT COUNT(*) FROM `{$dData['tablemail']}`;"));
  if (!$mailrows) @mysql_query("DROP TABLE `{$dData['tablemail']}`;");

  $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablestat']}` (
    `from` int(11),
    `aim` int(11),
    `to` int(11),
    `time` int(11),
    `type` tinytext,
    `ua` text
  ) TYPE=MyISAM;");

  $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tabletemp']}` SELECT * FROM `{$dData['tablestat']}` WHERE 1=2;");


  /* ***** Removing this Update Section after installation **** */
  /* *****  may improve performance *************************** */
  /* ********************************************************** */
  /* ***** Update to 2.1 ************************************** */
  $show = mysql_query("SHOW COLUMNS FROM `{$dData['tablename']}`;");
  for ($x = 0, $dData['fields'] = array(); $x < mysql_num_rows($show); $x++) $dData['fields'][] = mysql_result($show, $x, "Field");

  if (in_array("hitsgen", $dData['fields'])) {
    function stat_align($input) {
      global $rData;

      $shifts = gmdate("z", time() + $vData['tzoffset'] * 3600) - $input[0];
      if ($shifts < 0) $shifts = gmdate("z", time() + $vData['tzoffset'] * 3600) + gmdate("z", mktime(0, 0, 0, 12, 31, gmdate("Y", time() + $vData['tzoffset'] * 3600) - 1) + $vData['tzoffset'] * 3600) - $input[0];
      if ($shifts > 0) {
        for ($i = 0; $i < $shifts; $i++) {
          for ($j = 56; $j > 1; $j--) $input[$j] = $input[$j - 1];
          $input[1] = 0;
        } $input[0] = gmdate("z", time() + $vData['tzoffset'] * 3600);
      } return $input;
    }

    // Misc updates
    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `admin`=LOWER(`admin`);");
    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `status`=LOWER(`status`);");
    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `navstatus`=LOWER(`navstatus`);");

    // Import old stats to new system
    $select = mysql_query("SELECT `id`, `hitsgen`, `hitsrec` FROM `{$dData['tablename']}`;");
    for ($x = 0, $ids = array(); $x < mysql_num_rows($select); $x++) $ids[] = mysql_result($select, $x, "id");

    $y = 0;
    $hits = array();
    for ($x = 0; $x < mysql_num_rows($select); $x++) {
      $hits[$y][0] = stat_align(explode(" ", mysql_result($select, $x, "hitsrec")));
      $hits[$y][1] = stat_align(explode(" ", mysql_result($select, $x, "hitsgen")));
      $hits[$y++]['id'] = mysql_result($select, $x, "id");
    }

    for ($x = 0; $x < count($hits); $x++) {
      for ($y = 0; $y < count($hits[$x]) - 1; $y++) {
        for ($z = 1; $z < count($hits[$x][$y]); $z++) {
          for ($a = 0; $a < $hits[$x][$y][$z]; $a++) {
            $b = ($y) ? 0 : 1;
            for ($c = $x + 1; $c < count($hits); $c++) {
              if ($hits[$c][$b][$z]) {
                $hits[$c][$b][$z]--;
                $from = ($y) ? $hits[$x]['id'] : $hits[$c]['id'];
                $to = ($y) ? $hits[$c]['id'] : $hits[$x]['id'];
                $insert = mysql_query("INSERT INTO `{$dData['tablestat']}` VALUES ('$from', '$to', '$to', '".(time() - (mt_rand(0, 86400) + ($z - 1) * 86400))."', '', '');");
                break;
              } else if ($c == count($hits) - 1) {
                $from = ($y) ? $hits[$x]['id'] : 0;
                $to = ($y) ? 0 : $hits[$x]['id'];
                $insert = mysql_query("INSERT INTO `{$dData['tablestat']}` VALUES ('$from', '$to', '$to', '".(time() - (mt_rand(0, 86400) + ($z - 1) * 86400))."', '', '');");
              }
            }
          }
        }
      }
    }

    $alter = mysql_query("ALTER TABLE `{$dData['tablename']}` DROP `hitsgen`, DROP `hitsrec`;");
    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `admin`='1' WHERE `admin`='admin' || `admin`='yes';");
    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `admin`='0' WHERE `admin`='member' || `admin`='no';");
  }

  if (!in_array("order", $dData['fields'])) {
    $alter = mysql_query("ALTER TABLE `{$dData['tablename']}` ADD `order` INT AFTER `id`;");
    $ids = mysql_query("SELECT `id` FROM `{$dData['tablename']}` ORDER BY `id`;");

    $x = 1;
    while ($row = mysql_fetch_assoc($ids))
      $update = mysql_query("UPDATE `{$dData['tablename']}` SET `order`='".($x++)."' WHERE `id`='{$row['id']}' LIMIT 1;");
  }

  /* ***** Update to 2.2 ************************************** */
  $show = mysql_query("SHOW COLUMNS FROM `{$dData['tablevar']}`;");
  for ($x = 0, $dData['fields'] = array(); $x < mysql_num_rows($show); $x++) $dData['fields'][] = mysql_result($show, $x, "Field");

  if (!in_array("statcache", $dData['fields'])) {
    $alter = mysql_query("ALTER TABLE `{$dData['tablevar']}` ADD `statcachetype` TEXT, ADD `statdate` INT, ADD `statkey` TEXT, ADD `statcache` LONGTEXT, ADD `statlock` TEXT;");
    $update = mysql_query("UPDATE `{$dData['tablevar']}` SET `statcachetype`='none', `statdate`='0', `statcache`='', `statlock`='false';");
  }

  /* ***** Update to 2.2a ************************************* */
  if (!in_array("helpflag", $dData['fields'])) {
    $alter = mysql_query("ALTER TABLE `{$dData['tablevar']}` ADD `helpflag` TINYINT, ADD `helpfile` TEXT, ADD `authimage` TINYINT, ADD `timezone` TEXT, ADD `tzoffset` TINYINT, ADD `staticon` TEXT;");
    $update = mysql_query("UPDATE `{$dData['tablevar']}` SET `helpflag`='0', `helpfile`='orca/orm_help_en.txt', `authimage`='0', `timezone`='EST', `tzoffset`='-5', `staticon`='orca/o_stats.png';");
  }

  /* ***** Update to 2.3 ************************************** */
  $show = mysql_query("SHOW COLUMNS FROM `{$dData['tablestat']}`;");
  for ($x = 0, $dData['fields'] = array(); $x < mysql_num_rows($show); $x++) $dData['fields'][] = mysql_result($show, $x, "Field");

  if (in_array("ip", $dData['fields'])) {
    $alter = mysql_query("ALTER TABLE `{$dData['tablestat']}` DROP `ip`;");
    $alter = @mysql_query("ALTER TABLE `{$dData['tabletemp']}` DROP `ip`;");
  }

  $show = mysql_query("SHOW COLUMNS FROM `{$dData['tablename']}`;");
  for ($x = 0, $dData['fields'] = array(); $x < mysql_num_rows($show); $x++) $dData['fields'][] = mysql_result($show, $x, "Field");

  if (!in_array("joindate", $dData['fields'])) {
    $alter = mysql_query("ALTER TABLE `{$dData['tablename']}` ADD `joindate` INT AFTER `owner`;");
    $alter = mysql_query("ALTER TABLE `{$dData['tablevar']}` ADD `joindate` INT;");

    $now = time();
    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `joindate`='$now';");
    $update = mysql_query("UPDATE `{$dData['tablevar']}` SET `joindate`='$now';");

    $update = mysql_query("UPDATE `{$dData['tablename']}` SET `status`='inactive' WHERE `status`='unchecked';");
  }

  /* ********************************************************** */
  /* ***** End of Update Section ****************************** */


  /* ********************************************************** */
  /* ***** Prepare Ring Data ********************************** */
  $_SERVER['PHP_SELF'] = preg_replace("/\?.*$/i", "", $_SERVER['REQUEST_URI']);

  $rData['UA'] = "Orca Ringmaker v2.3d";
  $rData['autocheck'] = (ini_get("allow_url_fopen") || ini_set("allow_url_fopen", "1") !== false) ? true : false;
  $rData['thisURI'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

  list($rData['allcount']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}`;"));
  list($rData['actcount']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `status`='active';"));

  $lData['unchecked'] = $lang['db8'];
  $lData['found'] = $lang['db1'];
  $lData['not found'] = $lang['db2'];
  $lData['error'] = $lang['db3'];

  $lData['active'] = $lang['db9'];
  $lData['inactive'] = $lang['db7'];
  $lData['suspended'] = $lang['dbc'];
  $lData['hibernating'] = $lang['dba'];

  $rData['success'] = "";
  $rData['event'] = "";
  $rData['docache'] = false;

  $rData['coderep'] = array("--scriptURL--", "--id--", "--ringname--", "--ringemail--");

  $vData = mysql_fetch_assoc(mysql_query("SELECT * FROM `{$dData['tablevar']}`;"));

  if (isset($_POST['event'])) {
    $rData['event'] = $_POST['event'];
  } else if (isset($_SERVER['QUERY_STRING'])) {
    $rData['qsarr'] = explode("&", $_SERVER['QUERY_STRING']);
    $rData['event'] = $rData['qsarr'][0];
  }

  $rData['admins'] = mysql_query("SELECT `owner`, `email` FROM `{$dData['tablename']}` WHERE `admin`='1';");
  $rData['adminmail'][] = array($lang['term2'], $vData['ringemail']);
  for ($x = 0; $x < mysql_num_rows($rData['admins']); $x++)
    $rData['adminmail'][] = array(mysql_result($rData['admins'], $x, "owner"), mysql_result($rData['admins'], $x, "email"));

  if ($rData['event'] == "Help")
    $rData['event'] = ($vData['helpflag'] && @file_exists($vData['helpfile'])) ? "Help" : "";


  /* ***** User Data (Login) ********************************** */
  if (isset($_COOKIE['user'])) {
    $x = explode(" :: ", base64_decode($_COOKIE['user']));
    $uData['username'] = $x[0];
    $uData['password'] = $x[1];
    $uData['logged'] = gatekeeper($x[0], $x[1]);
  } else $uData['logged'] = 0;

  if ($rData['event'] == "Login" && !isset($_POST['forget'])) {
    if ($uData['logged'] = gatekeeper($_POST['user'], $_POST['pass'])) {
      setcookie("user", base64_encode("{$_POST['user']} :: {$_POST['pass']}"), time() + 43200, $_SERVER['PHP_SELF']);
      $uData['username'] = $_POST['user'];
      $uData['password'] = $_POST['pass'];
    } else $eData['error'][] = $lang['err1'];
  }

  if ($uData['logged'] && $rData['event'] != "Logout") {
    if ($uData['username'] != $rData['admin']) {
      $uData = array_merge(mysql_fetch_assoc(mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `username`='".addslashes($uData['username'])."';")), $uData);
    } else $uData['owner'] = $lang['term2'];
  }


  /* ********************************************************** */
  /* ***** Interpret Incoming Events ************************** */
  switch ($rData['event']) {

    /* ***** Output Authorization Image *********************** */
    case "Auth":
      if ($vData['authimage']) {

        $update = mysql_query("DELETE FROM `{$dData['tableauth']}` WHERE `date`<".(time() - 7200).";");
        $select = mysql_query("SELECT `code` FROM `{$dData['tableauth']}` WHERE `md5`='".str_replace("Auth&", "", addslashes($_SERVER['QUERY_STRING']))."';");

        if (mysql_num_rows($select)) {
          header("Content-type: image/png");

          $num = mysql_result($select, 0, "code");
          $img = imagecreate(100, 30);

          $bcol = imagecolorallocate($img, 255, 255, 255);
          $tcol = imagecolorallocate($img, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));
          $lcol = imagecolorallocate($img, mt_rand(100, 255), mt_rand(100, 255), mt_rand(100, 255));
                  imagefill($img, 1, 1, $bcol);

          for ($x = 0; $x < strlen($num); $x++) {
            imagestring($img, min(max($vData['authimage'], 1), 5), mt_rand(0, 9) + 17 * $x, mt_rand(1, 15),  $num{$x}, $tcol);
            imageline($img, mt_rand(0, 100), mt_rand(0, 30), mt_rand(0, 100), mt_rand(0, 30), $lcol);
          }

          imagepng($img);
          imagedestroy($img);
        }
      }
      exit();


    /* ***** Send Navbar Code ********************************* */
    case "Nav":
      $from = explode("&", $_SERVER['QUERY_STRING']);

      $navbar = preg_replace("/^\*/", "", $vData['navhtml']);
      $rep = array($rData['thisURI'], $from[1], $vData['ringname'], $vData['ringemail']);
      $navbar = str_replace($rData['coderep'], $rep, $navbar);
      $navbar = str_replace(array('"', "\n", "\r"), array('\"', '\n', ""), $navbar);

      header("Content-type: application/x-javascript"); ?> 
      document.write("<?php echo $navbar; ?>");
      <?php exit();


    /* ***** Go to a Ring Link ******************************** */
    case "Go":
      $go = explode("&", $_SERVER['QUERY_STRING']);
      $go[6] = "";

      if ($rData['actcount']) {
        if (in_array($go[1], array("Rand", "Next", "Prev", "Site"))) {

          $go[5] = $go[1];

          $select = mysql_query("SELECT `order` FROM `{$dData['tablename']}` WHERE `id`='".($go[2] = (int)$go[2])."';");
          if (mysql_num_rows($select)) {
            if ($go[1] == "Site") $go[2] = "-";
            $go[3] = mysql_result($select, 0, "order");
          } else {
            $go[2] = $go[3] = "0";
            if ($go[1] != "Site") $go[1] = "Rand";
          }

          $bbad = "";
          $marktime = time();

          do {
            switch ($go[1]) {
              case "Site":
                if (!$go[3]) break;
                $getURL = mysql_query("SELECT `URI`, `id`, `order` FROM `{$dData['tablename']}` WHERE `order`='{$go[3]}' AND `status`='active' LIMIT 1;");
                break;

              case "Next":
                $getURL = mysql_query("SELECT `URI`, `id`, `order` FROM `{$dData['tablename']}` WHERE `order`>'{$go[3]}'$bbad AND `status`='active' ORDER BY `order` LIMIT 1;");
                if (mysql_num_rows($getURL) == 0) $getURL = mysql_query("SELECT `URI`, `id`, `order` FROM `{$dData['tablename']}` WHERE `status`='active'$bbad ORDER BY `order` LIMIT 1;");
                break;

              case "Prev":
                $getURL = mysql_query("SELECT `URI`, `id`, `order` FROM `{$dData['tablename']}` WHERE `order`<'{$go[3]}'$bbad AND `status`='active' ORDER BY `order` DESC LIMIT 1;");
                if (mysql_num_rows($getURL) == 0) $getURL = mysql_query("SELECT `URI`, `id`, `order` FROM `{$dData['tablename']}` WHERE `status`='active'$bbad ORDER BY `order` DESC LIMIT 1;");
                break;

              case "Rand":
                $getURL = mysql_query("SELECT `URI`, `id`, `order` FROM `{$dData['tablename']}` WHERE `order`!='{$go[3]}'$bbad AND `status`='active' ORDER BY RAND() LIMIT 0, 1;");
                break;
            }

            if (isset($getURL) && mysql_num_rows($getURL)) {
              $URL = mysql_result($getURL, 0, "URI");
              $tries = 0;
              if (!isset($go[6])) $go[6] = mysql_result($getURL, 0, "id");

              if (ini_get("allow_url_fopen") == "1" || ini_set("allow_url_fopen", "1") !== false) {
                do {
                  $crack = parse_url($URL);
                  if (isset($crack['query'])) $crack['path'] .= "?{$crack['query']}";
                  unset($URL);

                  if ($st = @fsockopen($crack['host'], 80, $erstr, $errno, 3)) {
                    if (!isset($crack['path'])) $crack['path'] = "/";
                    @fwrite($st, "HEAD {$crack['path']} HTTP/1.0\r\nHost: {$crack['host']}\r\nUser-Agent: {$rData['UA']}\r\n\r\n");

                    while (!feof($st)) {
                      $data = fgets($st, 1024);
                      if (preg_match("/^HTTP\/1\.\d 2\d\d/i", $data, $code)) $goURL = mysql_result($getURL, 0, "URI");
                      if (preg_match("/^HTTP\/1\.\d [45]\d\d/i", $data, $code)) break;
                      if (preg_match("/^Location:\s([^\r\n]*?)[\r\n]/i", $data, $location)) $URL = $location[1];
                    }
                    fclose($st);
                  }
                } while (isset($URL) && !isset($goURL) && ++$tries < 10);

              } else $goURL = $URL;

              if (!isset($goURL)) {
                if ($go[1] == "Site" || $marktime + 7 < time()) {
                  $goURL = $_SERVER['PHP_SELF'];
                } else $bbad .= " AND `order`!='".mysql_result($getURL, 0, "order")."'";
              }

            } else $goURL = $_SERVER['PHP_SELF'];
          } while (!isset($goURL));
        } else $goURL = $_SERVER['PHP_SELF'];

        $go[4] = ($goURL != $_SERVER['PHP_SELF']) ? mysql_result($getURL, 0, "id") : 0;

        $insert = mysql_query("INSERT INTO `{$dData['tablestat']}` VALUES ('{$go[2]}', '{$go[6]}', '{$go[4]}', '".time()."', '{$go[5]}', '".addslashes($_SERVER['HTTP_USER_AGENT'])."');");

      } else $goURL = $_SERVER['PHP_SELF'];

      if ($goURL != $_SERVER['PHP_SELF']) {
        header("Location: ".$goURL);
        exit();
      } else $eData['error'][] = $lang['errv'];
      break;

    /* ***** Login ******************************************** */
    case "Login":
      if ($uData['logged'] == 0) $rData['success'] = "Login-Forget";

      if (isset($_POST['forget'])) {
        $find = mysql_query("SELECT `owner`, `email` FROM `{$dData['tablename']}` WHERE `username`='".strFunx($_POST['forget'], "0110")."';");

        if (mysql_num_rows($find)) {
          $change = gen_conf(8);
          mysql_query("UPDATE `{$dData['tablename']}` SET `password`='".crypt(strFunx($change, "0010"))."' WHERE `username`='".strFunx($_POST['forget'], "0110")."';");
          ring_mail(array(mysql_result($find, 0, "owner"), mysql_result($find, 0, "email")), $lang['email1']['subject'], sprintf($lang['email1']['message'], $_POST['forget'], $change));
        }
        $eData['success'][] = $lang['suc1'];
        $rData['event'] = "Blank";
        $rData['success'] = "";
      }
      break;


    /* ***** Logout ******************************************* */
    case "Logout":
      setcookie("user", "", time() - 100000, $_SERVER['PHP_SELF']);
      unset($uData['username']);
      unset($uData['password']);
      $uData['logged'] = 0;
      break;


    /* ******************************************************** */
    /* ***** Add Site ***************************************** */
    case "Add":
      if ($uData['logged'] > 0) {
        $eData['error'][] = $lang['err2'];
        $rData['event'] = "Blank";

      } else if (isset($_GET['rand'])) {
        $getlog = @mysql_query("SELECT * FROM `{$dData['tablelog']}` WHERE `confirm`='".addslashes($_GET['rand'])."';");

        if (!@mysql_num_rows($getlog)) {
          $eData['error'][] = $lang['err3'];
          $rData['event'] = "Blank";

        } else {
          $logrow = mysql_fetch_assoc($getlog);

          $top = mysql_query("SELECT `id` FROM `{$dData['tablename']}` ORDER BY `id`;");
          for ($x = 1; $x <= mysql_num_rows($top); $x++) if ($x < mysql_result($top, $x - 1, "id")) break;
          $rData['openid'] = $x;

          $top = mysql_query("SELECT `order` FROM `{$dData['tablename']}` ORDER BY `order`;");
          for ($x = 1; $x <= mysql_num_rows($top); $x++) if ($x < mysql_result($top, $x - 1, "order")) break;
          $rData['openorder'] = $x;

          $add = mysql_query("INSERT INTO `{$dData['tablename']}` VALUES ('{$rData['openid']}',
                                                                          '{$rData['openorder']}',
                                                                          '".strFunx($logrow['title'], "0010")."',
                                                                          '".strFunx($logrow['description'], "0010")."',
                                                                          '".strFunx($logrow['URI'], "0010")."',
                                                                          '".strFunx($logrow['owner'], "0010")."',
                                                                          '".time()."',
                                                                          '".strFunx($logrow['username'], "0010")."',
                                                                          '".strFunx($logrow['password'], "0010")."',
                                                                          'inactive',
                                                                          'unchecked',
                                                                          '".time()."',
                                                                          '".strFunx($logrow['email'], "0010")."',
                                                                          '0');");

          $eData['success'][] = $lang['suc2'];
          $rData['success'] = "Add-Complete";

          $message = sprintf($lang['email2']['message'], $logrow['owner'], $logrow['title'], $vData['ringname'], $logrow['URI']);

          ring_mail($rData['adminmail'], $lang['email2']['subject'], $message);

          $delete = mysql_query("DELETE FROM `{$dData['tablelog']}` WHERE `confirm`='".addslashes($_GET['rand'])."';");
        }

      } else if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (!$vData['authimage'] || @mysql_num_rows(@mysql_query("SELECT * FROM `{$dData['tableauth']}` WHERE `code`='".addslashes($_POST['auth'])."' && `md5`='".addslashes($_POST['authcheck'])."';"))) {

          if ($_POST['user'] = strFunx($_POST['user'], "1100")) {
            if (preg_match("/\W/i", $_POST['user'])) {
              $eData['error'][] = $lang['err4'];
            } else if (strlen($_POST['user']) > 32) {
              $eData['error'][] = $lang['err5'];
            } else if (strlen($_POST['user']) < 4) {
              $eData['error'][] = $lang['err6'];
            } else {
              list($duprows) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `username`='".addslashes($_POST['user'])."';"));
              if ($duprows || $_POST['user'] == $rData['admin']) $eData['error'][] = $lang['err7'];
            }
          } else $eData['error'][] = $lang['err8'];

          if ($_POST['pass1'] = strFunx($_POST['pass1'], "1100")) {
            if ($_POST['pass2'] = strFunx($_POST['pass2'], "1100")) {
              if (preg_match("/\W/i", $_POST['pass1'])) {
                $eData['error'][] = $lang['err9'];
              } else if (strlen($_POST['pass1']) > 32) {
                $eData['error'][] = $lang['erra'];
              } else if (strlen($_POST['pass1']) < 4) {
                $eData['error'][] = $lang['errb'];
              } else if ($_POST['pass1'] != $_POST['pass2'])
                $eData['error'][] = $lang['errc'];
            } else $eData['error'][] = $lang['errd'];
          } else $eData['error'][] = $lang['erre'];

          if ($_POST['email'] = strFunx($_POST['email'], "1100")) {
            if (!preg_match("/^(([^<>()[\]\\\\.,;:\s@\"]+(\.[^<>()[\]\\\\.,;:\s@\"]+)*)|(\"([^\"\\\\\r]|(\\\\[\w\W]))*\"))@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([a-z\-0-9]+\.)+[a-z]{2,}))$/i", $_POST['email']))
              $eData['error'][] = $lang['errf'];
          } else $eData['error'][] = $lang['errg'];

          if ($_POST['owner'] = strFunx($_POST['owner'], "1100")) {
            if (strlen($_POST['owner']) > 32)
              $eData['error'][] = $lang['errh'];
          } else $eData['error'][] = $lang['errj'];

          if ($_POST['title'] = strFunx($_POST['title'], "1100")) {
            if (strlen($_POST['title']) > 32) {
              $eData['error'][] = $lang['errk'];
            } else {
              list($duprows) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `title`='".strFunx($_POST['title'], "0110")."';"));
              if ($duprows) $eData['error'][] = $lang['errl'];
            }
          } else $eData['error'][] = $lang['errm'];

          if (($_POST['URI'] = strFunx($_POST['URI'], "1100")) && $_POST['URI'] != "http://") {
            if (!preg_match("/^http:\/\//", $_POST['URI']))
              $_POST['URI'] = "http://".$_POST['URI'];
          } else $eData['error'][] = $lang['errn'];

          if ($_POST['description'] = strFunx($_POST['description'], "1100")) {
            if (strlen($_POST['description']) > 500)
              $eData['error'][] = $lang['erro'];
          }

          if (count($eData['error'])) break;

          $confirm = gen_conf(mt_rand(8, 16));

          $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablelog']}` (
            `time` int(11),
            `confirm` text,
            `title` text,
            `description` text,
            `URI` text,
            `owner` text,
            `username` text,
            `password` text,
            `email` text
          ) TYPE=MyISAM;");

          $addlog = mysql_query("INSERT INTO `{$dData['tablelog']}` VALUES ('".time()."',
                                                                            '$confirm',
                                                                            '".strFunx($_POST['title'], "0010")."',
                                                                            '".strFunx($_POST['description'], "0010")."',
                                                                            '".strFunx($_POST['URI'], "0010")."',
                                                                            '".strFunx($_POST['owner'], "0010")."',
                                                                            '".strFunx($_POST['user'], "0010")."',
                                                                            '".crypt(strFunx($_POST['pass1'], "0010"))."',
                                                                            '".strFunx($_POST['email'], "0010")."');");

          $message = sprintf($lang['email3']['message'], $vData['ringname'], $rData['thisURI'], $confirm);
          ring_mail(array($_POST['owner'], $_POST['email']), $lang['email3']['subject'], $message);

          $eData['success'][] = sprintf($lang['suc3'], $_POST['email']);
          $rData['success'] = "Add-Verify";

        } else {

          $eData['error'][] = $lang['auth1'];
          $rData['event'] = "Blank";
        }

      }
      break;


    /* ***** Ring Setup *************************************** */
    case "Setup":
      if ($uData['logged'] < 3) {
        $eData['error'][] = $lang['errt'];
        $rData['event'] = "Blank";

      } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
        switch ($_POST['setup']) {

          case "Change":
            if (isset($_POST['ringname']) && ($_POST['ringname'] = strFunx($_POST['ringname'], "1100")) && $vData['ringname'] != $_POST['ringname']) {
              if (strlen($_POST['ringname']) > 48) {
                $eData['error'][] = $lang['erru'];
              } else {
                set_vData("ringname", $_POST['ringname']);
                $eData['success'][] = $lang['sucd'];
              }
            }

            if (isset($_POST['ringemail']) && ($_POST['ringemail'] = strFunx($_POST['ringemail'], "1100")) && $vData['ringemail'] != $_POST['ringemail']) {
              if (!preg_match("/[\w\d]@[\w\d]/i", $_POST['ringemail'])) {
                $eData['error'][] = $lang['errf'];
              } else {
                set_vData("ringemail", $_POST['ringemail']);
                $eData['success'][] = $lang['suc4'];
              }
            }

            if (isset($_POST['smtp']) && $vData['smtp'] != $_POST['smtp']) {
              set_vData("smtp", strFunx($_POST['smtp'], "1100"));
              $eData['success'][] = $lang['suce'];
            }

            if (isset($_POST['timezone']) && ($_POST['timezone'] = strFunx($_POST['timezone'], "1100")) && $vData['timezone'] != $_POST['timezone']) {
              set_vData("timezone", substr($_POST['timezone'], 0, 5));
              $eData['success'][] = $lang['suct'];
            }

            if (isset($_POST['tzoffset']) && (($_POST['tzoffset'] = (int)$_POST['tzoffset']) || $_POST['tzoffset'] === 0) && $vData['tzoffset'] != $_POST['tzoffset']) {
              if ($_POST['tzoffset'] < -12) $_POST['tzoffset'] += 24;
              if ($_POST['tzoffset'] > 12) $_POST['tzoffset'] -= 24;
              set_vData("tzoffset", $_POST['tzoffset']);
              $eData['success'][] = $lang['sucu'];
            }

            if (isset($_POST['helpflag']) && (($_POST['helpflag'] = (int)$_POST['helpflag']) || $_POST['helpflag'] === 0) && $vData['helpflag'] != $_POST['helpflag']) {
              set_vData("helpflag", $_POST['helpflag']);
              $eData['success'][] = ($_POST['helpflag']) ? $lang['sucv'] : $lang['sucw'];
            }

            if ($vData['helpflag'] && isset($_POST['helpfile']) && ($_POST['helpfile'] = strFunx($_POST['helpfile'], "1100")) && $vData['helpfile'] != $_POST['helpfile']) {
              set_vData("helpfile", $_POST['helpfile']);
              $eData['success'][] = $lang['sucx'];
            }

            if (isset($_POST['authimage']) && (($_POST['authimage'] = min(max((int)$_POST['authimage'], 0), 5)) || $_POST['authimage'] === 0) && $vData['authimage'] != $_POST['authimage']) {
              set_vData("authimage", $_POST['authimage']);
              $eData['success'][] = $lang['sucy'];
            }

            if (isset($_POST['sitelimit']) && ($_POST['smtp'] = strFunx($_POST['sitelimit'], "1100")) && $vData['sitelimit'] != $_POST['sitelimit']) {
              if ((int)$_POST['sitelimit'] > 50) {
                set_vData('sitelimit', 50);
                $eData['error'][] = $lang['errw'];
              } else if ((int)$_POST['sitelimit'] < 5) {
                set_vData('sitelimit', 5);
                $eData['error'][] = $lang['errx'];
              } else {
                set_vData('sitelimit', (int)$_POST['sitelimit']);
                $eData['success'][] = $lang['sucf'];
              }
            }

            if (isset($_POST['statlimit']) && ($_POST['smtp'] = strFunx($_POST['statlimit'], "1100")) && $vData['statlimit'] != $_POST['statlimit']) {
              if ((int)$_POST['statlimit'] > 50) {
                set_vData('statlimit', 50);
                $eData['error'][] = $lang['erry'];
              } else if ((int)$_POST['statlimit'] < 5) {
                set_vData('statlimit', 5);
                $eData['error'][] = $lang['erry'];
              } else {
                set_vData('statlimit', (int)$_POST['statlimit']);
                $eData['success'][] = $lang['errz'];
              }
            }

            if (isset($_POST['statcachetype']) && $vData['statcachetype'] != $_POST['statcachetype']) {
              set_vData("statcachetype", $_POST['statcachetype']);
              // if ($vData['statcachetype'] != "none") $interval = ($vData['statcachetype'] == "hourly") ? 3600 : 86400;

              $eData['success'][] = $lang['sucq'];
            }

            if (isset($_POST['staticon']) && ($_POST['staticon'] = strFunx($_POST['staticon'], "1100")) && $vData['staticon'] != $_POST['staticon']) {
              if (!@file_exists($_POST['staticon'])) {
                $eData['error'][] = $lang['er_a'];
              } else {
                set_vData("staticon", $_POST['staticon']);
                $eData['success'][] = $lang['sucz'];
              }
            }

            if (isset($_POST['announcement']) && ($_POST['announcement'] = strFunx($_POST['announcement'], "0100")) && $vData['announcement'] != $_POST['announcement']) {
              if (strlen(strFunx($_POST['announcement'], "1000")) > 500) {
                $eData['error'][] = $lang['er_1'];
              } else {
                set_vData("announcement", $_POST['announcement']);
                $eData['success'][] = $lang['sucg'];
              }
            }

            break;

          case "Toggle Status":
            if ($_POST['member'] != "NULL") {
              $old = mysql_query("SELECT `owner`, `title`, `admin` FROM `{$dData['tablename']}` WHERE `id`='{$_POST['member']}';");
              $new = (mysql_result($old, 0, "admin")) ? "0" : "1";

              set_uData($_POST['member'], "admin", $new);

              $eData['success'][] = sprintf(($new) ? $lang['such'] : $lang['suci'], mysql_result($old, 0, "owner"), mysql_result($old, 0, "title"));
            }

            break;

          case "Reorder":
            if (isset($_POST['Randomize'])) $method = "Randomize";
            if (isset($_POST['SiteID'])) $method = "SiteID";
            if (isset($_POST['SiteTitle'])) $method = "SiteTitle";

            if (isset($method)) {
              $select = mysql_query("SELECT `id` FROM `{$dData['tablename']}` ORDER BY `".(($method == "SiteTitle") ? "title" : "id")."`;");

              if (mysql_num_rows($select) >= 3 || (mysql_num_rows($select) > 1 && $method != "Randomize")) {

                while ($row = mysql_fetch_assoc($select)) $ids[] = $row['id'];
                $ods = range(1, count($ids));
                if ($method == "Randomize") shuffle($ods);
                for ($x = 0; $x < count($ods); $x++) $neworder[$ids[$x]] = $ods[$x];

                reset($neworder);
                while (list($key, $value) = each($neworder)) $update = mysql_query("UPDATE `{$dData['tablename']}` SET `order`='$value' WHERE `id`='$key';");

                switch ($method) {
                  case "Randomize": $eData['success'][] = $lang['suco']; break;
                  case "SiteID": $eData['success'][] = $lang['sucp']; break;
                  case "SiteTitle": $eData['success'][] = $lang['sucs']; break;
                }
              } else $eData['error'][] = $lang['er_9'];
            }
            break;

          case "Submit":
            set_vData("navhtml", str_replace(chr(13), "", strFunx($_POST['navhtml'])));
            set_vData("navscript", str_replace(chr(13), "", strFunx($_POST['navscript'])));

            break;

        }
      }

      break;


    /* ***** Administration *********************************** */
    case "Admin":
      if ($uData['logged'] < 2) {
        $eData['error'][] = $lang['errt'];
        $rData['event'] = "Blank";

      } else {
        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['admin'])) {
          $rData['admin'] = $_GET['admin'];
          $_POST = $_GET;
        } else if ($_SERVER['REQUEST_METHOD'] == "POST") $rData['admin'] = $_POST['admin'];

        if (isset($rData['admin'])) {
          switch ($rData['admin']) {

            case "Remove":
              $aData['row'] = mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `id`='{$_POST['id']}';");

              if (isset($_POST['confirm'])) {
                if (mysql_num_rows($aData['row'])) {
                  $delete = mysql_query("DELETE FROM `{$dData['tablename']}` WHERE `id`='{$_POST['id']}';");
                  $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `from`='-1' WHERE `from`='{$_POST['id']}';");
                  $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `aim`='-1' WHERE `aim`='{$_POST['id']}';");
                  $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `to`='-1' WHERE `to`='{$_POST['id']}';");

                  $reason = ($_POST['reason']) ? sprintf($lang['email6']['reason'], $_POST['reason']) : "";
                  $message = sprintf($lang['email6']['message'], mysql_result($aData['row'], 0, "owner"), mysql_result($aData['row'], 0, "title"), $vData['ringname'], $reason);

                  ring_mail(array(mysql_result($aData['row'], 0, "owner"), mysql_result($aData['row'], 0, "email")), $lang['email6']['subject'], $message);
                  $eData['success'][] = sprintf($lang['sucl'], mysql_result($aData['row'], 0, "title"));
                  $rData['admin'] = "";

                } else {
                  $eData['error'][] = $lang['er_2'];
                  $rData['admin'] = "";
                }
              }
              break;

            case "Edit":
              if (!isset($_POST['id'])) {
                $_POST['id'] = (isset($_POST['idina']) && $_POST['idina'] != "NULL") ? $_POST['idina'] : "NULL";
                $_POST['id'] = (isset($_POST['idact']) && $_POST['idact'] != "NULL") ? $_POST['idact'] : $_POST['id'];
                $_POST['id'] = (isset($_POST['idsus']) && $_POST['idsus'] != "NULL") ? $_POST['idsus'] : $_POST['id'];
                $_POST['id'] = (isset($_POST['idhib']) && $_POST['idhib'] != "NULL") ? $_POST['idhib'] : $_POST['id'];
              }

              if ($_POST['id'] == "NULL") {
                $eData['error'][] = $lang['er_3'];

              } else if (isset($uData['id']) && $_POST['id'] == $uData['id']) {
                $rData['event'] = "Edit";

              } else { 
                $aData['row'] = mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `id`='{$_POST['id']}';");

                if (!mysql_num_rows($aData['row'])) {
                  $eData['error'][] = $lang['er_4'];
                  $rData['admin'] = "";
                  break;

                } else if (isset($_POST['confirm'])) {

                  if (isset($_POST['owner']) && ($_POST['owner'] = strFunx($_POST['owner'], "1100")) && $_POST['owner'] != mysql_result($aData['row'], 0, "owner")) {
                    if (strlen($_POST['owner']) > 32) {
                      $eData['error'][] = $lang['errh'];
                    } else {
                      set_uData($_POST['id'], "owner", $_POST['owner']);
                      $eData['success'][] = $lang['suc8'];
                    }
                  }

                  if (isset($_POST['title']) && ($_POST['title'] = strFunx($_POST['title'], "1100")) && $_POST['title'] != mysql_result($aData['row'], 0, "title")) {
                    if (strlen($_POST['title']) > 32) {
                      $eData['error'][] = $lang['errk'];
                    } else {
                      list($duprows) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `title`='".strFunx($_POST['title'], "0110")."';"));
                      if ($duprows) {
                        $eData['error'][] = $lang['errl'];
                      } else {
                        set_uData($_POST['id'], "title", $_POST['title']);
                        $eData['success'][] = $lang['suc9'];
                      }
                    }
                  }

                  if (isset($_POST['description'])) {
                    $_POST['description'] = strFunx($_POST['description'], "1100");
                    if ($_POST['description'] != mysql_result($aData['row'], 0, "description")) {
                      if (strlen($_POST['description']) > 500) {
                        $eData['error'][] = $lang['erro'];
                      } else {
                        set_uData($_POST['id'], "description", $_POST['description']);
                        $eData['success'][] = $lang['sucb'];
                      }
                    }
                  }

                  if (isset($_POST['URI']) && ($_POST['URI'] = strFunx($_POST['URI'], "1100")) && $_POST['URI'] != mysql_result($aData['row'], 0, "URI")) {
                    if (!preg_match("/^http:\/\//", $_POST['URI'])) $_POST['URI'] = "http://".$_POST['URI'];
                    set_uData($_POST['id'], "URI", $_POST['URI']);
                    set_uData($_POST['id'], "navstatus", "unchecked");
                    set_uData($_POST['id'], "navtime", time());
                    $eData['success'][] = $lang['suca'];
                  }

                  if (isset($_POST['status']) && $_POST['status'] != mysql_result($aData['row'], 0, "status")) {
                    set_uData($_POST['id'], "status", $_POST['status']);
                    $eData['success'][] = sprintf($lang['sucm'], $lData[$_POST['status']]);

                    $aData['row'] = mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `id`='{$_POST['id']}';");

                    if ($_POST['status'] == "active") {
                      $message = sprintf($lang['email5']['message'], mysql_result($aData['row'], 0, "title"), $vData['ringname']);
                      ring_mail(array(mysql_result($aData['row'], 0, "owner"), mysql_result($aData['row'], 0, "email")), $lang['email5']['subject'], $message);

                    } else {
                      $reason = ($_POST['reason']) ? sprintf($lang['email7']['reason'] , $_POST['reason']) : "";
                      $message = sprintf($lang['email7']['message'], mysql_result($aData['row'], 0, "title"), $vData['ringname'], $lData[mysql_result($aData['row'], 0, "status")], $reason);
                      ring_mail(array(mysql_result($aData['row'], 0, "owner"), mysql_result($aData['row'], 0, "email")), $lang['email7']['subject'], $message);
                    }
                  }
                }
                $aData['row'] = mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `id`='{$_POST['id']}';");
              }
              break;          

            case "Check":
              if ($rData['autocheck']) {
                set_uData($_POST['id'], "navstatus", navverify($_POST['id']));
                set_uData($_POST['id'], "navtime", time());
              }
              $aData['row'] = mysql_query("SELECT * FROM `{$dData['tablename']}` WHERE `id`='{$_POST['id']}';");

              $eData['success'][] = sprintf($lang['sucj'], mysql_result($aData['row'], 0, "title"), $lData[mysql_result($aData['row'], 0, "navstatus")]);
              $rData['admin'] = ($_POST['check'] == "page") ? "Edit" : "";

              break;

          }
        }
      }
      break;


    /* ***** Email Ring Members ******************************* */
    case "Email":
      if ($uData['logged'] < 2) {
        $eData['error'][] = $lang['errt'];
        $rData['event'] = "Blank";

      } else if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_POST['subject'])) $_POST['subject'] = strFunx($_POST['subject'], "1100");
        if (!$_POST['subject']) $eData['error'][] = $lang['er_5'];

        if (isset($_POST['message'])) $_POST['message'] = strFunx($_POST['message'], "1100");
        if (!$_POST['message']) $eData['error'][] = $lang['er_6'];

        if (count($eData['error'])) break;

        switch ($_POST['recipients']) {
          case "all":
            $getter = $lang['dbb'];
            $mailbag = mysql_query("SELECT `owner`, `email` FROM `{$dData['tablename']}` WHERE `admin`='0';");
            break;

          case "active":
          case "inactive":
          case "hibernating":
          case "suspended":
            $getter = $lData[$_POST['recipients']];
            $mailbag = mysql_query("SELECT `owner`, `email` FROM `{$dData['tablename']}` WHERE `status`='{$_POST['recipients']}' AND `admin`='0';");
            break;

          case "selected":
            if (isset($_POST['id']) && is_array($_POST['id'])) {
              $build = "";
              foreach($_POST['id'] as $id) if ($id != "NULL") $build .= "`id`='$id' || ";
              $build = preg_replace("/\s\|\|\s$/i", "", $build);
              $mailbag = mysql_query("SELECT `owner`, `email` FROM `{$dData['tablename']}` WHERE $build AND `admin`='0';");
            } else {
              $eData['error'][] = $lang['er_b'];
              break 2;
            }
            break;

        }

        if (!@mysql_num_rows($mailbag)) {
          $eData['error'][] = $lang['er_7'];

        } else if (mysql_num_rows($mailbag) == 1) {
          ring_mail(array(mysql_result($mailbag, 0, "owner"), mysql_result($mailbag, 0, "email")), $_POST['subject'], $_POST['message']);
          ring_mail($rData['adminmail'], $_POST['subject'], sprintf($lang['email']['admin1'], mysql_result($mailbag, 0, "owner")." <".mysql_result($mailbag, 0, "email").">").$_POST['message']);
          $eData['success'][] = $lang['sucn'];

        } else {
          for ($x = 0, $build1 = array(), $build2 = ""; $x < mysql_num_rows($mailbag); $x++) {
            $build1[] = array(mysql_result($mailbag, $x, "owner"), mysql_result($mailbag, $x, "email"));
            if ($_POST['recipients'] == "selected") $build2 .= " - ".implode(" - ", $build1[count($build1) - 1])."\n";
          }
          $prefix = ($_POST['recipients'] == "selected") ? sprintf($lang['email']['admin1'], $build2) : sprintf($lang['email']['admin2'], $getter);

          ring_mail($build1, $_POST['subject'], $_POST['message']);
          ring_mail($rData['adminmail'], $_POST['subject'], $prefix."\n".$_POST['message']);
          $eData['success'][] = $lang['sucn'];
        }

      }
      break;

    /* ***** Edit Details ************************************* */
    case "Edit":
      if ($uData['logged'] == 0) {
        $eData['error'][] = $lang['errp'];
        $rData['event'] = "Blank";

      } else if (isset($_GET['rand'])) {
        $getmail = mysql_query("SELECT `email` FROM `{$dData['tablemail']}` WHERE `confirm`='{$_GET['rand']}';");
        if (mysql_num_rows($getmail)) {

          set_uData($uData['id'], "email", mysql_result($getmail, 0, "email"));
          mysql_query("DELETE FROM `{$dData['tablemail']}` WHERE `confirm`='{$_GET['rand']}';");

          $eData['success'][] = $lang['suc4'];

        } else $eData['error'][] = $lang['err3'];

      } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
        switch ($_POST['edit']) {

          case "Change":
            if (($_POST['email'] = strFunx($_POST['email'], "1100"))  && $_POST['email'] != $uData['email']) {
              if (!preg_match("/[\w\d]@[\w\d]/i", $_POST['email'])) {
                $eData['error'][] = $lang['errf'];
                break;
              }
            } else break;

            $confirm = gen_conf(mt_rand(8, 16));
            
            $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablemail']}` (
              `time` int(11),
              `confirm` text,
              `id` int(11),
              `email` text
            ) TYPE=MyISAM;");

            $addmail = mysql_query("INSERT INTO `{$dData['tablemail']}` VALUES ('".time()."', '$confirm', '{$uData['id']}', '{$_POST['email']}');");

            $message = sprintf($lang['email4']['message'], $vData['ringname'], $uData['owner'], $rData['thisURI'], $confirm);

            ring_mail(array($uData['owner'], $_POST['email']), $lang['email4']['subject'], $message);
            $eData['success'][] = sprintf($lang['suc5'], $_POST['email']);
            $rData['success'] = "Edit-Verify";

            break;

          case "Check":
            if ($rData['autocheck']) {
              set_uData($uData['id'], "navstatus", navverify($uData['id']));
              set_uData($uData['id'], "navtime", time());
            }
            break;

          case "Leave":
            if (isset($_POST['confirm'])) {
              $delete = mysql_query("DELETE FROM `{$dData['tablename']}` WHERE `id`='{$uData['id']}';");
              $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `from`='-1' WHERE `from`='{$uData['id']}';");
              $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `aim`='-1' WHERE `aim`='{$uData['id']}';");
              $update = mysql_query("UPDATE `{$dData['tablestat']}` SET `to`='-1' WHERE `to`='{$uData['id']}';");

              setcookie("user", "", time() - 100000, $_SERVER['PHP_SELF']);
              unset($uData['username']);
              unset($uData['password']);
              $uData['logged'] = 0;
              $rData['event'] = "Blank";

              $eData['success'][] = sprintf($lang['suc6'], $uData['owner']);
            } else $rData['success'] = "Edit-Leave";
            break;

          case "Edit":
            if ($_POST['passold']) {
              if (gatekeeper($uData['username'], $_POST['passold'])) {
                if ($_POST['pass1'] = strFunx($_POST['pass1'], "1100")) {
                  if ($_POST['pass2'] = strFunx($_POST['pass2'], "1100")) {
                    if (preg_match("/\W/i", $_POST['pass1'])) {
                      $eData['error'][] = $lang['err9'];
                    } else if (strlen($_POST['pass1']) > 32) {
                      $eData['error'][] = $lang['erra'];
                    } else if (strlen($_POST['pass1']) < 4) {
                      $eData['error'][] = $lang['errb'];
                    } else if ($_POST['pass1'] != $_POST['pass2']) {
                      $eData['error'][] = $lang['errc'];
                    } else {
                      set_uData($uData['id'], "password", crypt($_POST['pass1']));
                      setcookie("user", base64_encode("{$uData['username']} :: {$_POST['pass1']}"), time() + 43200, $_SERVER['PHP_SELF']);
                      $eData['success'][] = $lang['suc7'];
                    }
                  } else $eData['error'][] = $lang['errd'];
                } else $eData['error'][] = $lang['errq'];
              } else $eData['error'][] = $lang['errr'];
            }

            if (($_POST['owner'] = strFunx($_POST['owner'], "1100")) && $_POST['owner'] != $uData['owner']) {
              if (strlen($_POST['owner']) > 32) {
                $eData['error'][] = $lang['errh'];
              } else {
                set_uData($uData['id'], "owner", $_POST['owner']);
                $eData['success'][] = $lang['suc8'];
              }
            }

            if (($_POST['title'] = strFunx($_POST['title'], "1100")) && $_POST['title'] != $uData['title']) {
              if (strlen($_POST['title']) > 32) {
                $eData['error'][] = $lang['errk'];
              } else {
                list($duprows) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `title`='".strFunx($_POST['title'], "0110")."';"));
                if ($duprows) {
                  $eData['error'][] = $lang['errl'];
                } else {
                  set_uData($uData['id'], "title", $_POST['title']);
                  $eData['success'][] = $lang['suc9'];
                }
              }
            }

            if (($_POST['URI'] = strFunx($_POST['URI'], "1100")) && $_POST['URI'] != $uData['URI']) {
              if (!preg_match("/^http:\/\//", $_POST['URI'])) $_POST['URI'] = "http://".$_POST['URI'];
              set_uData($uData['id'], "URI", $_POST['URI']);
              set_uData($uData['id'], "navstatus", "unchecked");
              set_uData($uData['id'], "navtime", time());
              $eData['success'][] = $lang['suca'];
            }

            $_POST['description'] = strFunx($_POST['description'], "1100");
            if ($_POST['description'] != $uData['description']) {
              if (strlen($_POST['description']) > 500) {
                $eData['error'][] = $lang['erro'];
              } else {
                set_uData($uData['id'], "description", $_POST['description']);
                $eData['success'][] = $lang['sucb'];
              }
            }

            if (isset($_POST['status']) && $uData['status'] != $_POST['status']) {
              if ($uData['logged'] == 2) {
                set_uData($uData['id'], "status", $_POST['status']);
                $eData['success'][] = sprintf($lang['succ'], $lData[$uData['status']]);

              } else {
                if ($uData['status'] == "active" || $uData['status'] == "hibernating") {
                  $uData['status'] = ($uData['status'] == "active") ? "hibernating" : "active";
                  set_uData($uData['id'], "status", $uData['status']);
                  $eData['success'][] = sprintf($lang['succ'], $lData[$uData['status']]);
                } else $eData['error'][] = sprintf($lang['errs'], $lData[$_POST['status']]);
              }
            }

        }
      }
      break;
  }


  /* ********************************************************** */
  /* ***** Statistics Part Deux ******************************* */
  function statList($spacer, $indent, $title, $ringtotal, $input1, $input2, $percent) { 
    global $lang, $xData;
    static $bkg = 1; 

    if ($spacer) { ?> 
      <tr<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>><td colspan="4">&nbsp;</td></tr>
    <?php } else { ?> 
      <tr<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
        <th><?php if ($indent) echo " &nbsp;  &nbsp; "; ?><strong><?php echo $title; ?></strong><?php if ($ringtotal) echo " / {$lang['pa__c']}"; ?></th><?php
        foreach ($xData['dtypes'] as $dtypes) { ?> 
          <td><strong><?php echo $input1[$dtypes]; ?></strong> / <?php echo $input2[$dtypes]; ?></td><?php 
        } ?> 
      </tr>
      <?php if ($percent) { ?> 
        <tr<?php if ($bkg++ % 2) echo " class=\"orm_drow\""; ?>>
          <th> &nbsp;  &nbsp; <?php echo $lang['pa__d']; ?></th><?php
          foreach ($xData['dtypes'] as $dtypes) { ?> 
            <td><?php ($input2[$dtypes]) ? printf("%1.2f", 100 * $input1[$dtypes] / $input2[$dtypes]) : print("0.00"); ?></td><?php
          } ?> 
        </tr>
      <?php }
    }
  }

  class statAbacus {
    var $hits = array();
    var $clks = array();
    var $errr = array();

    function __sleep() {
      while (list($key, $value) = each($this->hits)) $this->hits[$key] = implode(":", $value);
      while (list($key, $value) = each($this->clks)) $this->clks[$key] = implode(":", $value);
      if (isset($this->errr)) $this->errr = implode(":", $this->errr);
      return array("hits", "clks", "errr");
    }

    function __wakeup() {
      while (list($key, $value) = each($this->hits)) $this->hits[$key] = explode(":", $value);
      while (list($key, $value) = each($this->clks)) $this->clks[$key] = explode(":", $value);
      if (isset($this->errr)) $this->errr = explode(":", $this->errr);
    }

    function statAbacus($slots) {
      $this->hits['total'] = array_fill(0, $slots, 0);
      $this->clks['total'] = array_fill(0, $slots, 0);
      if ($slots < 100) {
        $this->hits['prev'] = array_fill(0, $slots, 0);
        $this->hits['next'] = array_fill(0, $slots, 0);
        $this->hits['rand'] = array_fill(0, $slots, 0);
        $this->hits['site'] = array_fill(0, $slots, 0);
        $this->clks['prev'] = array_fill(0, $slots, 0);
        $this->clks['next'] = array_fill(0, $slots, 0);
        $this->clks['rand'] = array_fill(0, $slots, 0);
        $this->errr = array_fill(0, $slots, 0);
      }
    }
  }

  class ringStat {
    var $ring;
    var $site = array();
    var $ids = array();
    var $now = 0;

    function ringStat() {
      global $dData, $rData, $vData;

      $now = time();
      $this->now = $now + $vData['tzoffset'] * 3600;

      $this->ring = array("days" => new statAbacus(56), "hours" => new statAbacus(1344), "browsers" => array());
      $this->ring['browsers']['Opera'] = array("opera", 0);
      $this->ring['browsers']['Firefox'] = array("firefox", 0);
      $this->ring['browsers']['Safari'] = array("safari", 0);
      $this->ring['browsers']['Googlebot'] = array("googlebot", 0);
      $this->ring['browsers']['MSNBot'] = array("msnbot", 0);
      $this->ring['browsers']['Netscape'] = array("netscape", 0);
      $this->ring['browsers']['Mozilla'] = array("gecko", 0);
      $this->ring['browsers']['Lynx'] = array("lynx", 0);
      $this->ring['browsers']['Konqueror'] = array("konqueror", 0);
      $this->ring['browsers']['Internet Explorer'] = array("msie", 0);
      $this->ring['browsers']['Other'] = array(" ", 0);
      $this->ring['browsers']['Unknown'] = array("", 0);

      $delete = mysql_query("DELETE FROM `{$dData['tablestat']}` WHERE `time`<'".($now - 4838400)."';");
      $optimize = mysql_query("OPTIMIZE TABLE `{$dData['tablestat']}`;");
      list($statRows) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablestat']}`;"));

      $truncate = mysql_query("TRUNCATE TABLE `{$dData['tabletemp']}`;");
      do {
        $insert = mysql_query("INSERT INTO `{$dData['tabletemp']}` SELECT * FROM `{$dData['tablestat']}`;");
        list($tempRows) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tabletemp']}`;"));
      } while ($tempRows != $statRows);

      $select = mysql_query("SELECT `id`, `title` FROM `{$dData['tablename']}` WHERE `status`='active';");
      while ($row = mysql_fetch_assoc($select))
        $this->site[$this->ids[] = $row['id']] = array("title" => $row['title'], "days" => new statAbacus(56));
      sort($this->ids);

      $adjDays = (24 - gmdate("G", $this->now)) * 3600 + (60 - gmdate("i", $this->now)) * 60 + (60 - gmdate("s", $this->now));
      $adjHours = (60 - gmdate("i", $this->now)) * 60 + (60 - gmdate("s", $this->now));

      $all = mysql_unbuffered_query("SELECT * FROM `{$dData['tabletemp']}` ORDER BY `time` DESC;");

      while ($row = mysql_fetch_assoc($all)) {
        $day = floor(($now - $row['time'] + $adjDays) / 86400);
        $hour = floor(($now - $row['time'] + $adjHours) / 3600);

        if ($day >= 56) break;

        reset($this->ring['browsers']);
        while (list($key, $value) = each($this->ring['browsers'])) {
          if ($key != "Unknown") {
            if (stristr($row['ua'], $value[0]) !== false) {
              $this->ring['browsers'][$key][1]++;
              break;
            }
          } else $this->ring['browsers'][$key][1]++;
        }

        if ($row['to'] && isset($this->site[$row['to']])) {
          if ($day < 56) {
            $this->ring['days']->hits['total'][$day]++;
            $this->site[$row['to']]['days']->hits['total'][$day]++;
            switch ($row['type']) {
              case "Next":
                $this->ring['days']->hits['next'][$day]++;
                $this->site[$row['to']]['days']->hits['next'][$day]++;
                break;
              case "Prev":
                $this->ring['days']->hits['prev'][$day]++;
                $this->site[$row['to']]['days']->hits['prev'][$day]++;
                break;
              case "Rand":
                $this->ring['days']->hits['rand'][$day]++;
                $this->site[$row['to']]['days']->hits['rand'][$day]++;
                break;
              case "Site":
                $this->ring['days']->hits['site'][$day]++;
                $this->site[$row['to']]['days']->hits['site'][$day]++;
            }
          }
          if ($hour < 1344) $this->ring['hours']->hits['total'][$hour]++;
        }
        if ($row['from'] && isset($this->site[$row['from']])) {
          if ($day < 56) {
            $this->ring['days']->clks['total'][$day]++;
            $this->site[$row['from']]['days']->clks['total'][$day]++;
            switch ($row['type']) {
              case "Next":
                $this->ring['days']->clks['next'][$day]++;
                $this->site[$row['from']]['days']->clks['next'][$day]++;
                break;
              case "Prev":
                $this->ring['days']->clks['prev'][$day]++;
                $this->site[$row['from']]['days']->clks['prev'][$day]++;
                break;
              case "Rand":
                $this->ring['days']->clks['rand'][$day]++;
                $this->site[$row['from']]['days']->clks['rand'][$day]++;
            }
          }
          if ($hour < 1344) $this->ring['hours']->clks['total'][$hour]++;
        }
        if (isset($this->site[$row['aim']])) {
          if ($day < 56) {
            if (($row['aim'] != "-1" && $row['aim'] != $row['to']) || ($row['aim'] == "-1" && $row['to'] == "0")) {
              $this->ring['days']->errr[$day]++;
              if ($row['aim'] != "-1") $this->site[$row['aim']]['days']->errr[$day]++;
            }
          }
        }
        if (time() % 20 == 19) @set_time_limit(30);
      }

      uasort($this->ring['browsers'], create_function('$a, $b', 'if ($a[1] == $b[1]) return 0; return ($a[1] > $b[1]) ? -1 : 1;'));
    }
  }

  class orcaGraph {
    var $id;
    var $arr = array();
    var $max = array();
    var $min = array();
    var $limit;
    var $k;
    var $title;
    var $digits = 0;

    function orcaGraph($id, $rec, $gen, $k, $kmod, $jk, $title, $digits, $base, $width, $barts) {
      $this->id = "orca_".$id;
      $this->title = $title;
      $this->digits = $digits;
      $this->k = $k;
      $this->kmod = $kmod;
      $this->jk = $jk;
      $this->arr[0] = $rec;
      $this->max[0] = max($rec);
      $this->min[0] = min($rec);
      $this->arr[1] = $gen;
      $this->max[1] = max($gen);
      $this->min[1] = min($gen);
      $this->limit = count($rec);
      $this->base = $base;
      $this->width = $width;
      $this->barts = $barts;
    }
  }

  function cacheError($errno, $errstr, $errfile, $errline) {
    set_vData("statlock", "false");
    exit();
  }

  if ($vData['statlock'] == "true" && time() - $vData['statdate'] > 900) set_vData("statlock", "false");

  if (isset($_GET['key']) && $_GET['key'] == $vData['statkey'] && $vData['statlock'] == "false") {
    set_error_handler("cacheError");

    set_vData("statlock", "true");
    set_vData("statcache", serialize($cData = new ringStat()));
    $optimize = mysql_query("OPTIMIZE TABLE `{$dData['tablevar']}`;");
    set_vData("statdate", time());
    set_vData("statlock", "false");
    exit();
  }

  if ($vData['statcachetype'] != "none") {
    if (!$vData['statcache']) {
      if ($vData['statlock'] == "false") {
        set_vData("statkey", $key = md5(mt_rand(1, 9999999)));
        $st = pfsockopen($_SERVER['HTTP_HOST'], 80, $erstr, $errno, 5);
        @fwrite($st, "GET {$_SERVER['PHP_SELF']}?key=$key HTTP/1.0\r\nHost: {$_SERVER['HTTP_HOST']}\r\n\r\n");
      }

    } else {
      $cData = unserialize($vData['statcache']);

      $interval = ($vData['statcachetype'] == "hourly") ? 3600 : 86400;
      if (time() - $interval > $vData['statdate'] && $vData['statlock'] == "false") {
        set_vData("statkey", $key = md5(mt_rand(1, 9999999)));
        $st = pfsockopen($_SERVER['HTTP_HOST'], 80, $erstr, $errno, 5);
        @fwrite($st, "GET {$_SERVER['PHP_SELF']}?key=$key HTTP/1.0\r\nHost: {$_SERVER['HTTP_HOST']}\r\n\r\n");
      }
    }
  } else $cData = new ringStat();

  if (isset($cData) && is_object($cData)) {

    $xData['dtypes'] = array(3, 14, 56);
    $xData['idok'] = (isset($_GET['id']) && $_GET['id'] && in_array($_GET['id'], $cData->ids)) ? true : false;
    if ($xData['idok']) {
      $xData['this'] = $_GET['id'];
      $xData['thisURI'] = mysql_query("SELECT `joindate`, `URI` FROM `{$dData['tablename']}` WHERE `id`='{$xData['this']}' LIMIT 1;");
    }

    foreach ($xData['dtypes'] as $dtypes) {
      $xData['ring']['days']['hits']['total'][$dtypes] = array_sum(array_slice($cData->ring['days']->hits['total'], 0, $dtypes));
      if ($rData['event'] == "Stats") {
        $xData['ring']['days']['hits']['prev'][$dtypes] = array_sum(array_slice($cData->ring['days']->hits['prev'], 0, $dtypes));
        $xData['ring']['days']['hits']['next'][$dtypes] = array_sum(array_slice($cData->ring['days']->hits['next'], 0, $dtypes));
        $xData['ring']['days']['hits']['rand'][$dtypes] = array_sum(array_slice($cData->ring['days']->hits['rand'], 0, $dtypes));
        $xData['ring']['days']['hits']['site'][$dtypes] = array_sum(array_slice($cData->ring['days']->hits['site'], 0, $dtypes));

        $xData['ring']['days']['clks']['total'][$dtypes] = array_sum(array_slice($cData->ring['days']->clks['total'], 0, $dtypes));
        $xData['ring']['days']['clks']['prev'][$dtypes] = array_sum(array_slice($cData->ring['days']->clks['prev'], 0, $dtypes));
        $xData['ring']['days']['clks']['next'][$dtypes] = array_sum(array_slice($cData->ring['days']->clks['next'], 0, $dtypes));
        $xData['ring']['days']['clks']['rand'][$dtypes] = array_sum(array_slice($cData->ring['days']->clks['rand'], 0, $dtypes));

        foreach ($cData->ids as $id) {
          $xData['site'][$id]['days']['hits']['total'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->hits['total'], 0, $dtypes));
          $xData['site'][$id]['days']['hits']['prev'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->hits['prev'], 0, $dtypes));
          $xData['site'][$id]['days']['hits']['next'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->hits['next'], 0, $dtypes));
          $xData['site'][$id]['days']['hits']['rand'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->hits['rand'], 0, $dtypes));
          $xData['site'][$id]['days']['hits']['site'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->hits['site'], 0, $dtypes));

          $xData['site'][$id]['days']['clks']['total'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->clks['total'], 0, $dtypes));
          $xData['site'][$id]['days']['clks']['prev'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->clks['prev'], 0, $dtypes));
          $xData['site'][$id]['days']['clks']['next'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->clks['next'], 0, $dtypes));
          $xData['site'][$id]['days']['clks']['rand'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->clks['rand'], 0, $dtypes));
        }
      }

      $xData['ring']['days']['errr'][$dtypes] = array_sum(array_slice($cData->ring['days']->errr, 0, $dtypes));
      foreach ($cData->ids as $id)
        $xData['site'][$id]['days']['errr'][$dtypes] = array_sum(array_slice($cData->site[$id]['days']->errr, 0, $dtypes));
    }

    for ($x = 7, $xData['ring']['days']['avg']['hits'] = $xData['ring']['days']['avg']['clks'] = array_fill(0, 7, 0), $hStart = $cStart = 1; $x >= 0; $x--) {
      for ($y = 6; $y >= 0; $y--) {
        if ($cData->ring['days']->hits['total'][$x * 7 + $y] && $hStart == 1) $hStart = $x + 1;
        $xData['ring']['days']['avg']['hits'][$y] += $cData->ring['days']->hits['total'][$x * 7 + $y];
        if ($cData->ring['days']->clks['total'][$x * 7 + $y] && $cStart == 1) $cStart = $x + 1;
        $xData['ring']['days']['avg']['clks'][$y] += $cData->ring['days']->clks['total'][$x * 7 + $y];
      }
    }
    for ($y = 0; $y < 7; $y++) {
      $xData['ring']['days']['avg']['hits'][$y] /= $hStart;
      $xData['ring']['days']['avg']['clks'][$y] /= $cStart;
    }

    for ($x = 55, $xData['ring']['hours']['avg']['hits'] = $xData['ring']['hours']['avg']['clks'] = array_fill(0, 24, 0), $hStart = $cStart = 1; $x >= 0; $x--) {
      for ($y = 23; $y >= 0; $y--) {
        if ($cData->ring['hours']->hits['total'][$x * 24 + $y] && $hStart == 1) $hStart = $x + 1;
        $xData['ring']['hours']['avg']['hits'][$y] += $cData->ring['hours']->hits['total'][$x * 24 + $y];
        if ($cData->ring['hours']->clks['total'][$x * 24 + $y] && $cStart == 1) $cStart = $x + 1;
        $xData['ring']['hours']['avg']['clks'][$y] += $cData->ring['hours']->clks['total'][$x * 24 + $y];
      }
    }
    for ($y = 0; $y < 24; $y++) {
      $xData['ring']['hours']['avg']['hits'][$y] /= $hStart;
      $xData['ring']['hours']['avg']['clks'][$y] /= $cStart;
    }

    $sData['toplimit'] = (count($cData->ids) < $vData['statlimit']) ? count($cData->ids) : $vData['statlimit'];
    $sData['day'] = array($lang['pa__u'], $lang['pa__v'], $lang['pa__w'], $lang['pa__x'], $lang['pa__y'], $lang['pa__z'], $lang['p___1']);

  } else if ($rData['event'] == "Stats") {
    unset($cData);

    $rData['event'] = "Blank";
    $eData['success'][] = $lang['sucr'];
  }

  list($rData['allcount']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}`;"));
  list($rData['actcount']) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `status`='active';"));


  /* ********************************************************** */
  /* ***** Initial Variable Data ****************************** */
  if (!$vData['navhtml']) {
    $navbar = <<<ORCA
<table border="0" cellpadding="0" cellspacing="0" style="border:2px outset #a0b0c0;margin:0px auto;font:normal 12px Arial,sans-serif !important;background-color:#d0d7df !important;color:#000000 !important;">
  <tr>
    <th style="padding:2px;text-align:left;font-size:140%;">--ringname--</th>
  </tr>
  <tr>
    <td style="padding:0px 4px 4px 4px;text-align:center;">
      [ <a href="--scriptURL--?Add" style="color:#000080;" target="_top">{$lang['dbe']}</a>
      | <a href="--scriptURL--" style="color:#000080;" target="_top">{$lang['dbf']}</a>
      | <a href="--scriptURL--?Go&amp;Rand&amp;--id--" style="color:#000080;" target="_top">{$lang['dbg']}</a>
      | <a href="--scriptURL--?Go&amp;Prev&amp;--id--" style="color:#000080;" target="_top">{$lang['dbh']}</a>
      | <a href="--scriptURL--?Go&amp;Next&amp;--id--" style="color:#000080;" target="_top">{$lang['dbi']}</a> ]
    </td>
  </tr>
</table>
ORCA;

    set_vData("navhtml", $navbar);
  }

  if (!$vData['navscript']) {
    $navblk = sprintf($lang['dbj'], '<a href="--scriptURL--">--ringname--</a>');
    set_vData("navscript", $navblk);
  }

  $vData['wrap'] = <<<ORCA
<script src="--scriptURL--?Nav&amp;--id--" type="text/javascript"></script>
<noscript>%s</noscript>
ORCA;


  /* ********************************************************** */
  /* ***** Template Setup Data ******************************** */
  $_ORMPG['php.self'] = $_SERVER['PHP_SELF'];

  $_ORMPG['udata.logged'] = $uData['logged'];
  $_ORMPG['udata.id'] = (isset($uData['id'])) ? $uData['id'] : NULL;

  $_ORMPG['menu.title'] = $lang['page1'];
  $_ORMPG['menu.announcement'] = $vData['announcement'];
  $_ORMPG['menu.hublink.title'] = strFunx($lang['page2'], "0101");
  $_ORMPG['menu.hublink.text'] = $lang['dbf'];
  $_ORMPG['menu.addlink.title'] = strFunx($lang['page4'], "0101");
  $_ORMPG['menu.addlink.text'] = $lang['page3'];
  $_ORMPG['menu.rndlink'] = (isset($cData) && count($cData->ids) > 2) ? true : false;
  $_ORMPG['menu.rndlink.title'] = strFunx($lang['page6'], "0101");
  $_ORMPG['menu.rndlink.text'] = $lang['page5'];
  $_ORMPG['menu.hlplink'] = ($vData['helpflag'] && @file_exists($vData['helpfile'])) ? true : false;
  $_ORMPG['menu.hlplink.title'] = strFunx($lang['p___u'], "0101");
  $_ORMPG['menu.hlplink.text'] = $lang['p___t'];
  $_ORMPG['menu.stslink.title'] = strFunx($lang['page8'], "0101");
  $_ORMPG['menu.stslink.text'] = $lang['page7'];
  $_ORMPG['menu.stsitem.sites.text'] = $lang['page9'];
  $_ORMPG['menu.stsitem.sites.data'] = $rData['actcount'];
  $_ORMPG['menu.stsitem.2week.text'] = $lang['pagea'];
  $_ORMPG['menu.stsitem.2week.data'] = (!isset($cData)) ? "---" : $xData['ring']['days']['hits']['total'][14];
  $_ORMPG['menu.stsitem.8week.text'] = $lang['pageb'];
  $_ORMPG['menu.stsitem.8week.data'] = (!isset($cData)) ? "---" : $xData['ring']['days']['hits']['total'][56];
  $_ORMPG['menu.login.username'] = $lang['termh'];
  $_ORMPG['menu.login.password'] = $lang['termi'];
  $_ORMPG['menu.login.submit'] = $lang['term3'];
  $_ORMPG['menu.login.logout'] = $lang['term4'];
  $_ORMPG['menu.login.owner'] = (isset($uData['owner'])) ? sprintf($lang['pagec'], strFunx($uData['owner'], "1100")) : "";
  $_ORMPG['menu.opt.setup.title'] = strFunx($lang['pagek'], "0101");
  $_ORMPG['menu.opt.setup.text'] = $lang['pagej'];
  $_ORMPG['menu.opt.edit.title'] = strFunx($lang['pagee'], "0101");
  $_ORMPG['menu.opt.edit.text'] = $lang['paged'];
  $_ORMPG['menu.opt.admin.title'] = strFunx($lang['pageg'], "0101");
  $_ORMPG['menu.opt.admin.text'] = $lang['pagef'];
  $_ORMPG['menu.opt.email.title'] = strFunx($lang['pagei'], "0101");
  $_ORMPG['menu.opt.email.text'] = $lang['pageh'];

  switch($rData['event']) {
    case "Add":
      switch ($rData['success']) {
        case "Add-Complete":
          $_ORMPG['add.complete.message'] = preg_replace("/[\n\r]{2,}/", "</p>\n<p>", $lang['html1']);
          $code = (strpos($vData['navhtml'], "*") !== 0) ? sprintf($vData['wrap'], $vData['navscript']) : $vData['navscript'];
          $rep = array($rData['thisURI'], $rData['openid'], $vData['ringname'], $vData['ringemail']);
          $_ORMPG['add.complete.code'] = strFunx(str_replace($rData['coderep'], $rep, $code), "0101", false);
          break;
        case "Add-Verify":
          $_ORMPG['add.verify.message'] = preg_replace("/[\n\r]{2,}/", "</p>\n<p>", $lang['html2']);
          break;
        default:
          $_ORMPG['add.form.header'] = $lang['page3'];
          $_ORMPG['add.form.username.title'] = strFunx($lang['pagel'], "0101");
          $_ORMPG['add.form.username.text'] = $lang['termh'];
          $_ORMPG['add.form.username.post'] = (isset($_POST['user'])) ? strFunx($_POST['user'], "0101") : "";
          if ($vData['authimage']) {
            $create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tableauth']}` (
              `md5` text,
              `code` text,
              `date` int(11)
            ) TYPE=MyISAM;");
            $md5 = md5(uniqid(mt_rand(), true));
            $insert = mysql_query("INSERT INTO `{$dData['tableauth']}` VALUES ('$md5', '".mt_rand(10000, 99999)."', '".time()."');");
          }
      }
      break;
    case "Setup": break;
    case "Admin":
      if ($rData['allcount']) {
        switch ($rData['admin']) {
          case "Edit": break;
          case "Remove": break;
        }
        class adminType {
          var $nick;
          var $full;
          var $rows;
          var $title;

          function adminType($nick, $full, $title) {
            global $dData;

            $this->nick = $nick;
            $this->full = $full;
            $this->rows = mysql_query("SELECT `owner`, `title`, `id`, `URI` FROM `{$dData['tablename']}` WHERE `status`='{$full}' ORDER BY `title`;");
            $this->title = $title;
          }

          function nonTypes() {
            global $rData;

            $build = "";
            reset($rData['adminls']);
            while (list($key, $value) = each($rData['adminls'])) if ($this->nick != $value->nick) $build .= $value->nick." ";
            return strFunx($build);
          }

          function anyType() {
            global $rData;

            foreach ($rData['adminls'] as $adminls) if (mysql_num_rows($adminls->rows)) return true;
            return false;
          }
        }

        $rData['adminls'] = array();
        $rData['adminls'][] = new adminType("ina", "inactive", $lang['pag_y']);
        $rData['adminls'][] = new adminType("act", "active", $lang['db9']);
        $rData['adminls'][] = new adminType("sus", "suspended", $lang['dbc']);
        $rData['adminls'][] = new adminType("hib", "hibernating", $lang['dba']);
      }
      break;

    case "Email":
      if ($rData['allcount']) {
        $y = array("active", "inactive", "hibernating", "suspended");
        foreach ($y as $rowType) list($emailRows[$rowType]) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `status`='$rowType';"));
        $rtyp = array("disabled=\"disabled\"", "onfocus=\"document.getElementById('orm_bid').selectedIndex=-1;\"");
      } 
      break;

    case "Edit": break;
    case "Stats": break;
    case "Help": break;
    case "Blank": break;
    default:
      if ($rData['success'] == "Login-Forget") {

      } else {
        list($hubCount) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `status`='active';"));
        if ($hubCount) {
          $pData['start'] = (isset($_GET['start'])) ? (((int)$_GET['start'] > $hubCount) ? $hubCount : (int)$_GET['start']) : mt_rand(0, $hubCount - 1);
          $pData['end'] = ($vData['sitelimit'] > $hubCount) ? $hubCount : $vData['sitelimit'];
          $listed = 0;
          $list = mysql_query("SELECT `id`, `title`, `description` FROM `{$dData['tablename']}` WHERE `status`='active' ORDER BY `order` LIMIT {$pData['start']}, {$pData['end']};");
        }
      }
  }

} else $rData['event'] = "Blank";


/* ************************************************************ */
/* ***** Do not cache this page ******************************* */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); ?>