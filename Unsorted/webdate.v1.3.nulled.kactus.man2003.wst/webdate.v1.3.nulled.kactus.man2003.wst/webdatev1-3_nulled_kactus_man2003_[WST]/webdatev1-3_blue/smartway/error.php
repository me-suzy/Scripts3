<?
	require "lib/mod_xml.lib";
	require "lib/mod_xml_ct.lib";
	require "services/BD3LoadConfiguration.service";

	if($login == "")
	{
	 	$login = "unknown user";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Administrative Area</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<!--kactus_man2003[WST]-->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td><img src="images/logo.gif" alt="" border="0"></td>
<td background="images/bg_top.gif" width="100%" align="right" style="padding:10px;" valign="bottom"></td>
<td><img src="images/border_top.gif" width="26" height="91" alt="" border="0"></td>
</tr>
</table>  <br>
      <table border="0" cellspacing="1" bgcolor="#FFFFFF" align="center" cellpadding="0" width="620" height="364">
        <tr>
          <td>            <table width="100%" border="0" cellspacing="8" cellpadding="8" bgcolor="#FFFFFF">
              <tr>
                <td colspan="2" valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td valign="top" align="right" colspan="2"></td>
                    </tr>
                    <tr>
                      <td valign="top" class="larger"><font color="#666666"><b><br>
                        <br>
                        </b></font><b>Access is denied.</b> Login failed for user:
                        &quot;
                        <? echo $login ?>
                        &quot;</td>
                      <td valign="top" align="right" width="120" class="larger"><br>
                        <br>
                        Date:
                        <? echo date("d.m.y") ?>
                        <br>
                        Time:
                        <? echo date("H:i") ?>
                      </td>
                    </tr>
                  </table>
                  <table width="100%" border="0" height="50" cellpadding="0" cellspacing="0">
                    <tr>
                      <td colspan="2">
                        <hr noshade size="1" color=C0C0C0>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" width="40" height="20"><a href="index.php"><font color="#669966">[<img src="images/l.gif" width="14" height="14" border="0">]</font></a></td>
                      <td><a href="index.php"><font color="#999999">Back to the
                        main page to enter your password again</font></a></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr noshade size="1" color=C0C0C0>
                      </td>
                    </tr>
                  </table>
                  <table border="0" cellpadding="2" cellspacing="2" align="center">
                    <tr>
                      <td width="10" valign="top" class="larger"><font color="#333333">1.</font></td>
                      <td class="larger"><font color="#333333">It may be that
                        you made a mistake while entering your login or password.
                        (3)<br>
                        <br>
                        </font></td>
                    </tr>
                    <tr>
                      <td width="10" valign="top" class="larger"><font color="#333333">2.</font></td>
                      <td class="larger"><font color="#333333">The administrator may have denied access to your account.<br>
                        <br>
                        </font></td>
                    </tr>
                    <tr>
                      <td width="10" valign="top" class="larger"><font color="#333333">3.</font></td>
                      <td class="larger"><font color="#333333">Make sure you typed the username and password correctly.<br>
                        </font></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>  </td></tr></table>
<table width="100%" border="0" height="50">
                    <tr>
                      <td colspan="2">
                        <hr noshade size="1" color=C0C0C0>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" width="35"><a href="http://www.webscribble.com/support.shtml"><font color="#669966">[<img src="images/p.gif" width="14" height="14" border="0">]</font></a></td>
                      <td height="22" align="left"><a href="http://www.webscribble.com/support.shtml"><font color="#999999">Contact us with any questions</font></a></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr noshade size="1" color=C0C0C0>
                      </td>
                    </tr>
                  </table>
                  <br>
                  <table width="100%" border="0">
                    <tr>
                
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>
<br>
</body>
</html>
