<?php

/*======================================================================*\
|| #################################################################### ||
||  Program Name         : VirtuaNews Pro                                 
||  Release Version      : 1.0.3                                          
||  Program Author       : VirtuaSystems                                  
||  Supplied by          : Ravish                                         
||  Nullified by         : WTN Team                                       
||  Distribution         : via WebForum, ForumRU and associated file dumps
|| #################################################################### ||
\*======================================================================*/

$ipaddress = getenv("REMOTE_ADDR");
checkipban();

unset($userinfo);
unset($username);
unset($useremail);
unset($foruminfo);
unset($staffid);
unset($loggedin);

$defaultcategory = $defaultcat_loggedout;

if (!empty($use_forum)) {
  include("includes/forum_".strtolower(trim($use_forum)).".php");
} else {
  include("includes/forum_vn.php");
}

if ($userinfo = validateuser($userid,$userpassword)) {

  $username = $userinfo[username];
  if ($userinfo[showemail]) {
    $useremail = $userinfo[email];
  }

  if (!empty($userinfo[id])) {
    $staffid = $userinfo[id];
    $defaultcategory = $defaultcat_staff;
  } else {
    $defaultcategory = $defaultcat_loggedin;
  }

  $loggedin = 1;
} else {
  $loggedin = 0;
  $defaultcategory = $defaultcat_loggedout;
}

settype($catid,"integer");
if (isset($cat_arr[$catid]) == 0) {
  $catid = $defaultcategory;
}

$useragent = getenv("HTTP_USER_AGENT");
$browser = getbrowser();

if ($dositestats & $siteopen) {

  $os = getos();

  $STATS = query_first('SELECT * FROM news_stats');

  $countusers = query_first('SELECT COUNT(*) AS count FROM news_hit WHERE time > ' . (time() - $sessiontimeout));

  $STATS['usersonline'] = $countusers['count'];

  unset($countusers);

  $STATS['uniquestotal'] ++;
  $STATS['usersonline' ] ++;

  if ($STATS['usersonline'] >= $STATS['maxusersonline']) {
    $STATS['maxusersonline'] = $STATS['usersonline'];
  }

  $day   = date('j', time() - $timeoffset);
  $month = date('n', time() - $timeoffset);
  $year  = date('Y', time() - $timeoffset);

  $morntime = mktime(0, 0, 0, $month, $day, $year) + $timeoffset;

  if ($morntime > $STATS['lastupdate']) {
    $STATS['uniquestoday'] = 1;
  } else {
    $STATS['uniquestoday'] ++;
  }

  function vn_shutdown()
  {
    global $sessiontimeout;

    mt_srand(time());

    if (mt_rand(1,100) == '50') {
      query('DELETE FROM news_hit WHERE time < ' . (time() - $sessiontimeout));
    }
  }

  register_shutdown_function('vn_shutdown');

  if ($getsessionid = query_first("SELECT ip FROM news_hit WHERE (ip = '$ipaddress') AND (time >= ".(time()-$sessiontimeout).")")) {

    query("UPDATE news_hit SET time = '".time()."' , location = '$location' WHERE (ip = '$getsessionid[ip]') AND (time >= " . (time() - $sessiontimeout) . ")");

    if ($STATS['usersonline'] == $STATS['maxusersonline']) {
      $STATS['maxusersonline'] --;
    }

    $STATS['usersonline'] --;
    $STATS['uniquestotal'] --;

    if ($morntime < $STATS['lastupdate']) {
      $STATS['uniquestoday'] --;
    }

  } else {

    query("INSERT INTO news_hit VALUES ('".time()."','$ipaddress','$browser','$os','$location')");

    query('UPDATE news_stats SET lastupdate = ' . time() . ' , uniquestotal = ' . $STATS['uniquestotal'] . ' , uniquestoday = ' . $STATS['uniquestoday'] . ' , maxusersonline = ' . $STATS['maxusersonline']);

    query('UPDATE news_useragent SET value = value + 1 WHERE ((name = \'' . $browser . '\') AND (type = \'browser\')) OR ((name = \'' . $os . '\') AND (type = \'os\'))');

    if ($dohttpreferer) {

      if ($HTTP_REFERER) {

        $refererdetails = parse_url($HTTP_REFERER);

        if (($refererdetails['scheme'] == 'http') | ($refererdetails['scheme'] == 'news') | ($refererdetails['scheme'] == 'ftp') | ($refererdetails['scheme'] == 'https')) {

          $homedetails = parse_url($homeurl);

          if ($refererdetails['host'] == $homedetails['host']) {
            query('UPDATE news_referer SET value = value + 1 WHERE name = \'Home\'');
          } else {

            $referer = $refererdetails['scheme'] . '://' . $refererdetails['host'];

            if (query_first('SELECT value FROM news_referer WHERE name = \'' . $referer . '\'')) {
              query('UPDATE news_referer SET value = value + 1 WHERE name = \'' . $referer . '\'');
            } else {
              query('INSERT INTO news_referer VALUES (\'' . $referer . '\',\'1\')');
            }

          }
        } else {
          query('UPDATE news_referer SET value = value + 1 WHERE name = \'None\'');
        }

      } else {
        query('UPDATE news_referer SET value = value + 1 WHERE name = \'None\'');
      }
    }
  }

  if ($sessionlimit > 0) {
    if ($usersonline > $sessionlimit) {
      $servertoobusy = 1;
    }
  }

  extract($STATS, EXTR_SKIP);
}

/*======================================================================*\
|| ####################################################################
|| # File: includes/sessions.php
|| ####################################################################
\*======================================================================*/

?>