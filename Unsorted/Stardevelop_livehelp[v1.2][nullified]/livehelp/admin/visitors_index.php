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

if (!isset($_GET['RECORD'])){ $_GET['RECORD'] = 0; }

$current_record = $_GET['RECORD'];

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

$query_guests_online = "SELECT count(request_id) FROM " . $table_prefix . "requests WHERE (UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(last_refresh)) < 30";
$row_guests_online = $SQLDISPLAY->selectquery($query_guests_online);
$total_records = $row_guests_online['count(request_id)'];

?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../include/styles.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.newstable {
	border: thin dashed #6DB4D3;
}
.newscellborder {
	border: 1px solid #F3F3F3;
}
.newscelltop {
	border-top-width: 3px;
	border-top-style: solid;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	border-top-color: #F3F3F3;
}
-->
</style>
</head>
<body> 
<div align="center"> 
  <table width="425" border="0"> 
    <tr> 
      <td width="25"><div align="center"><strong><img src="../icons/visitors_sm.gif" alt="<?php echo($language['online_visitors']); ?>" width="22" height="22" border="0"></strong></div></td> 
      <td width="400" colspan="2"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['online_visitors']); ?> :: </em></font></td> 
    </tr> 
    <tr> 
      <td width="25">&nbsp;</td> 
      <td width="400"> <div align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($language['current_record']); ?>:
          <?php if ($total_records == 0) { echo('0'); } else { echo($current_record + 1); } ?> 
          / <?php echo($total_records); ?></em></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> </font> </div></td> 
      <td width="400"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['current_online_visitors']); ?>:
          <?php
	  if (is_array($row_guests_online)) {
	  	echo($total_records);
	  }
	  ?> 
          </font></div></td> 
    </tr> 
    <tr> 
      <td width="25">&nbsp;</td> 
      <td width="400" colspan="2"> <?php
	  $query_requests = "SELECT * FROM " . $table_prefix . "requests WHERE (UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(last_refresh)) < 30 ORDER BY last_request DESC LIMIT $current_record, 1";
	  $rows_requests = $SQLDISPLAY->selectall($query_requests);
	  if (is_array($rows_requests)) {
	  	foreach ($rows_requests as $row_request) {
			if (is_array($row_request)) {
	  ?> 
        <table width="400" border="0" align="center" cellpadding="2" cellspacing="2" class="newstable"> 
          <tr> 
            <td width="5" rowspan="3" bgcolor="#F3F3F3">&nbsp;</td> 
            <td colspan="3" bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="0"> 
                <tr> 
                  <td width="25"><div align="center"><img src="../icons/identity.gif" alt="Live Help News" width="22" height="21"></div></td> 
                  <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em>&nbsp;<?php echo($row_request['session_id']); ?></em></font></td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td colspan="3" bgcolor="#FFFFFF"><table width="400" border="0" cellspacing="2" cellpadding="2"> 
                <tr> 
                  <td width="175" valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['request_id']); ?>:</font></td> 
                  <td width="250"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($row_request['request_id']); ?></em></font></td> 
                </tr> 
                <tr> 
                  <td width="175" valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['session_id']); ?>:</font></td> 
                  <td width="250"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($row_request['session_id']); ?></em></font></td> 
                </tr> 
                <tr> 
                  <td width="175" valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['hostname']); ?>:</font></td> 
                  <td width="250"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($row_request['ip_address']); ?></em></font></td> 
                </tr> 
                <tr> 
                  <td width="175" valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['last_request']); ?>:</font></td> 
                  <td width="250"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($row_request['last_request']); ?></em></font></td> 
                </tr> 
                <tr> 
                  <td width="175" valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['user_agent']); ?>:</font></td> 
                  <td width="250"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($row_request['user_agent']); ?></em></font></td> 
                </tr> 
                <tr> 
                  <td width="175" valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['current_url']); ?>:</font></td> 
                  <td width="250"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><a href="<?php echo($row_request['current_page']); ?>" target="_blank" class="normlink"><?php echo($row_request['current_page']); ?></a></em></font></td> 
                </tr> 
                <tr> 
                  <td valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['current_page_title']); ?>:</font></td> 
                  <td valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($row_request['current_page_title']); ?></em></font></td> 
                </tr> 
                <tr> 
                  <td width="175" valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['page_path']); ?>:</font></td> 
                  <td width="250" valign="top"><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em> 
                      <textarea name="textarea" cols="45" rows="3" readonly="readonly" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; font-style: italic;" ><?php echo($row_request['page_path']); ?></textarea> 
                      </em></font></p></td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td width="200" bordercolor="#F3F3F3" bgcolor="#FFFFFF" class="newscelltop"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['last_refreshed']); ?>:<br> 
                </font><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em><?php echo($row_request['last_refresh']); ?></em></font></div></td> 
            <td width="100" bgcolor="#FFFFFF">&nbsp;</td> 
            <td width="100" bordercolor="6DB4D3" bgcolor="F0FAFE" class="newscellborder"><div align="center"> 
                <table width="110" border="0" align="center" cellpadding="2" cellspacing="2"> 
                  <tr> 
                    <td><div align="center"><a href="initate_chat.php?SESSION_ID=<?php echo($row_request['session_id']); ?>&SLOGIN_ID=<?php echo($login_id); ?>&<?php echo(SID); ?>"><img src="../icons/chat.gif" width="22" height="22" border="0"></a></div></td> 
                    <td><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> <a href="initate_chat.php?SESSION_ID=<?php echo($row_request['session_id']); ?>&SLOGIN_ID=<?php echo($login_id); ?>&<?php echo(SID); ?>" class="normlink"><?php echo($language['initiate_chat']); ?></a></font></div></td> 
                  </tr> 
                </table> 
              </div></td> 
          </tr> 
        </table> 
        <?php
	  			}
	  		}
	  	}
		
		$SQLDISPLAY->disconnect();
		?> </td> 
    </tr> 
    <tr> 
      <td width="25">&nbsp;</td> 
      <td width="400" colspan="2"><div align="center"> 
          <p> 
          <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <?php
	  if ($total_records == 0) {
	  ?> 
          <table width="290" border="0" cellpadding="0" cellspacing="0"> 
            <tr> 
              <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['notice']); ?>" width="32" height="32"></td> 
              <td><div align="center"> 
                  <p><em><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['notice']); ?></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><strong><br> 
                    </strong></font><?php echo($language['visitors_notice']); ?>:</font></em></p> 
                </div></td> 
            </tr> 
          </table> 
          </font>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['no_visitors_msg']); ?></font> 
            </p> 
          </p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['no_visitors_tracker_msg']); ?> 
            <?php
	  }
	  elseif ($total_records == 1) {
	  ?> 
            <?php echo($language['back_record']); ?> :: <?php echo($language['next_record']); ?> 
            <?php
	  }
	  elseif ($current_record == 0) {
	  ?> 
            <?php echo($language['back_record']); ?> :: <a href="./visitors_index.php?RECORD=<?php echo($current_record + 1)?>" target="displayFrame" class="normlink"><?php echo($language['next_record']); ?></a> 
            <?php
	  }
	  elseif ($current_record == ($total_records - 1)) {
	  ?> 
            <a href="./visitors_index.php?RECORD=<?php echo($current_record - 1)?>" target="displayFrame" class="normlink"><?php echo($language['back_record']); ?></a> :: <?php echo($language['next_record']); ?> 
            <?php
	  }
	  else {
	  ?> 
            <a href="./visitors_index.php?RECORD=<?php echo($current_record - 1)?>" target="displayFrame" class="normlink"><?php echo($language['back_record']); ?></a> :: <a href="./visitors_index.php?RECORD=<?php echo($current_record + 1)?>" target="displayFrame" class="normlink"><?php echo($language['next_record']); ?></a> 
            <?php
	  }
	  ?> 
            </font></p> 
        </div></td> 
    </tr> 
  </table> 
</div> 
</body>
</html>
