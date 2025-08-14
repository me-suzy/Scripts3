<?
	require "lib/mod_xml.lib";
	require "lib/mod_xml_ct.lib";
	require "lib/system.lib";

	require "services/BD3LoadConfiguration.service";
	require "services/BD3LoadDBDevice.service";
	require "services/BD3ForwardRoutines.service";
	$sSystemSource = "services/BD3Login.service";
	$db = c();
	if($action != "login")
	{
	 	setcookie("bd3Auth");

		if($QUERY_STRING != "")
		{
			$QUERY_STRING = base64_decode($QUERY_STRING);
			parse_str($QUERY_STRING);
		}

		//	Handling new actions
		switch($action)
		{
			case change_password:
				//	Now we must to check bd3AuthFlag and compare it with
				//	'user_id' variable value

				if(!sysIsBD3AuthFlagTrue())
				{
					break;
				}
				$sSystemSource = "services/BD3ChangePassword.service";
				break;

			case validate_new_password:
				if(!sysIsBD3AuthFlagTrue())
				{
					break;
				}

				//	Checking input variables - pswd, new_pswd, new_pswd_1

				if($pswd == "" || $new_pswd == "" || $new_pswd != $new_pswd_1 || $pswd == $new_pswd)
				{
					$sError = "Invalid input data!";
				}

				//	Comparing 'pswd' with database's record for user with
				//	current 'user_id'

				$rUser = q("SELECT login FROM webDate_bd_users WHERE id='$user_id' AND pswd='".sysCrypt($pswd)."'");
				if(e($rUser) && $sError == "")
				{
					$sError = "The password you entered is incorrect.";
				}

				if($sError != "")
				{
					$sSystemSource = "services/BD3ChangePassword.service";
					break;
				}

				//	Changing user's password and logging user in
				q("UPDATE webDate_bd_users SET pswd='".sysCrypt($new_pswd)."', status='0' WHERE id='$user_id'");
				$fUser = f($rUser);

				$login = $fUser[ login ];
				$pswd = $new_pswd;

				require "services/BD3LoginRoutines.service";
				break;
		}
	}
	else
	{
		require "services/BD3LoginRoutines.service";
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>webDate Administrative Area</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<!--Start Top-->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td><img src="images/logo.gif" alt="" border="0"></td>
<td background="images/bg_top.gif" width="100%" align="right" style="padding:10px;" valign="bottom"></td>
<td><img src="images/border_top.gif" width="26" height="91" alt="" border="0"></td>
</tr>
</table><br> <br><br>
                  <?
			//	Include system source (this value defined by action's hanlder)
			@include $sSystemSource;
		?>
<br><br><br><br><br>
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
<?

        $db;
?>
