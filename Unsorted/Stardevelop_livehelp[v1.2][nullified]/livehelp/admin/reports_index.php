<?php
/*
stardevelop.com Live Help
International Copyright stardevelop.com

You may not distribute this program in any manner,
modified or otherwise, without the express, written
consent from stardevelop.com

You may make modifications, but only for your own 
use and within the confines of the License Agreement.
All rights reserved.

Selling the code for this program without prior 
written consent is expressly forbidden. Obtain 
permission before redistributing this program over 
the Internet or in any other medium.  In all cases 
copyright and header must remain intact.  
*/
include('../include/config_database.php');
include('../include/class.mysql.php');
include('../include/config.php');
include('../include/auth.php');

$language_file = '../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('../locale/lang_en.php');
}

session_start();
$username = $_SESSION['USERNAME'];
$login_id = $_SESSION['LOGIN_ID'];
session_write_close();

if (!isset($_GET['MONTH'])){ $_GET['MONTH'] = ""; }
if (!isset($_GET['DATE'])){ $_GET['DATE'] = ""; }

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function getReport(td, date){
  var day = td.innerHTML.replace(/<[^>]+>/g,"");
  
  day = day.replace(/ /g,"");
  
  if (day != 0) {
    if (day < 10) {
    day = "0" + day;
    }
    parent.displayFrame.location.href='reports_index.php?<?php echo(SID); ?>&DATE=' + date + '-' + day;
  }
}
//-->
</script>
<link href="../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
  </font> 
  <table width="423" border="0">
    <tr> 
      <td width="22"><strong><img src="../icons/reports.gif" alt="<?php echo($language['daily_reports']); ?>" width="22" height="22" border="0"></strong></td>
      <td colspan="3"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['daily_reports']); ?> :: </em></font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="3"><div align="center"> 
          <table width="270" border="0">
            <tr> 
              <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['notice']); ?>" width="32" height="32"></td>
              <td><div align="center"> 
                  <p><em><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['notice']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><strong><br>
                    </strong></font><em><?php echo($language['reports_notice']); ?>:</em></font></em></p>
                </div></td>
            </tr>
          </table>
          <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> </font></div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="212">&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2"> <?php
	  include('calendar_include.php');
      ?> </td>
      <td>
        <table border="0" cellpadding="2" cellspacing="2">
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"><div align="center"> 
                <p align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em><?php echo($language['date']); ?>: 
                  <?php
			if ($_GET['DATE'] == "") {
			  $num_date = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y')));
 	  
			  $day = date('d', mktime(0,0,0,date('m'),date('d'),date('Y')));
			  $month = date('F', mktime(0,0,0,date('m'),date('d'),date('Y')));
			  $year = date('Y', mktime(0,0,0,date('m'),date('d'),date('Y'))); 
	if ($language_type != 'en') {	  
	switch ($month) { 
		case 'January':
			$month = $language['january']; 
			break;
		case 'February':
			$month = $language['february']; 
			break;
		case 'March':
			$month = $language['march']; 
			break;
		case 'April':
			$month = $language['april']; 
			break;
		case 'May':
			$month = $language['may']; 
			break;
		case 'June':
			$month = $language['june']; 
			break;
		case 'July':
			$month = $language['july']; 
			break;
		case 'August':
			$month = $language['august']; 
			break;
		case 'September':
			$month = $language['september']; 
			break;
		case 'October':
			$month = $language['october']; 
			break;
		case 'November':
			$month = $language['november']; 
			break;
		case 'December':
			$month = $language['december']; 
			break;
	}
	}		  
			  echo($day . ' ' . $month . ' ' . $year);

			}
			else {
			  $num_date= $_GET['DATE'];
			  list($year, $month, $day) = split('[-]', $num_date);
			  $date = date('d F Y', mktime(0,0,0,$month,$day,$year));
			  echo($date);
			}
			?>
                  </em> </strong></font></p>
              </div></td>
          </tr>
          <tr> 
            <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['total_unique']); ?>:</font></div></td>
            <td width="5%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
              <?php
	  $query_unique_visitors = "SELECT count(session_id) FROM " . $table_prefix . "requests WHERE DATE_FORMAT(last_request, '%Y-%m-%d') = '$num_date'";
	  $row_unique_visitors = $SQLDISPLAY->selectquery($query_unique_visitors);
	  if (is_array($row_unique_visitors)) {
	  echo($row_unique_visitors['count(session_id)']);
	  }
	  else {
	  echo('Unavailable');
	  }
	  ?>
              </font></td>
          </tr>
          <tr> 
            <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['total_supported']); ?>:</font></div></td>
            <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
              <?php
	  $query_supported_users = "SELECT count(session_id) FROM " . $table_prefix . "sessions WHERE active > '0' AND DATE_FORMAT(datetime, '%Y-%m-%d') = '$num_date'";
	  $row_supported_users = $SQLDISPLAY->selectquery($query_supported_users);
	  if (is_array($row_supported_users)) {
	  echo($row_supported_users['count(session_id)']);
	  }
	  ?>
              </font></td>
          </tr>
          <tr> 
            <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['total_unsupported']); ?>:</font></div></td>
            <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
              <?php
	  $query_unsupported_users = "SELECT count(session_id) FROM " . $table_prefix . "sessions WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_refresh)) > '" . $connection_timeout . "' AND active = '0' AND DATE_FORMAT(datetime, '%Y-%m-%d') = '$num_date'";
	  $row_unsupported_users = $SQLDISPLAY->selectquery($query_unsupported_users);
	  if (is_array($row_unsupported_users)) {
	  echo($row_unsupported_users['count(session_id)']);
	  }
	  ?>
              </font></td>
          </tr>
          <tr> 
            <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['total_sent_msgs']); ?>:</font></div></td>
            <td> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
              <?php
	  $query_sent_msgs = "SELECT count(message_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "messages AS m  WHERE s.login_id = m.from_login_id AND s.active =  '-1' AND DATE_FORMAT(datetime, '%Y-%m-%d') = '$num_date'";
	  $row_sent_stats = $SQLDISPLAY->selectquery($query_sent_msgs);
	  if (is_array($row_sent_stats)) {
	  echo($row_sent_stats['count(message_id)']);
	  }
	  ?>
              </font> </td>
          </tr>
          <tr> 
            <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['total_received_msgs']); ?>:</font></div></td>
            <td> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
              <?php
	  $query_received_msgs = "SELECT count(message_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "messages AS m  WHERE s.login_id = m.to_login_id AND s.active =  '-1' AND DATE_FORMAT(datetime, '%Y-%m-%d') = '$num_date'";
	  $row_received_stats = $SQLDISPLAY->selectquery($query_received_msgs);
	  if (is_array($row_received_stats)) {
	  echo($row_received_stats['count(message_id)']);
	  }
	  ?>
              </font> </td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
</body>
</html>