<?
    include( "config.php" );
	include("usersession.inc");			
    include( "dblink.inc" );		

	$TryLogin = false;
	$SignInSuccess = false;
	
	if ( isset($MemberID) || isset($Password ) )
	{
		if ( ($MemberID != "") && ($Password != "") )
		{
			$TryLogin = true;

			$MemberID = addslashes($MemberID);
			$Password = addslashes($Password);
	
			$query = "SELECT * FROM UserAccounts WHERE MemberID='$MemberID' AND Password='$Password'";
			$result = mysql_query( $query, $link );		
	
			if ( mysql_numrows( $result ) != 0 )
			{			
				$SignInSuccess = true;

				if ( $SavePwd == "save" )
					NewLogin($MemberID, true);
				else
					NewLogin($MemberID, false);
			}
			else
			{
				$SignInSuccess = false;
			}
		}
	}
?>

<html>
<head>
<title>Sign In</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<style type="text/css">
<!--
.inputbox
{
    BORDER-RIGHT: #FFCC00 1px solid;
    BORDER-TOP: #FFCC00 1px solid;
    BORDER-LEFT: #FFCC00 1px solid;
    BORDER-BOTTOM: #FFCC00 1px solid;
    FONT-WEIGHT: normal;
    FONT-SIZE: 12px;
    COLOR: #333333;
    BACKGROUND-COLOR: #FCE9B3
}
-->
</style>
</head>
<script type="text/javascript">
	var focused=false;

	if (navigator.appName.indexOf('Netscape')!=-1) 
		document.captureEvents(Event.KEYDOWN);

	document.onkeydown=function(e) 
	{
		if (!focused) 
			return;

		if (!e && event) 
			e = {which:event.keyCode};

		if (e.which == 13) 
			document.SignIn.submit();
	}
</script>
<body bgcolor="#FFFFFF" <? if (!$SignInSuccess) print( "onload=\"document.SignIn.MemberID.focus();\"" ); ?>>
<?
	include( "header.inc" );
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="12%" bgcolor="#FCE9B3">&nbsp;</td>
    <td width="88%" valign="top"> 
      <p>&nbsp;</p>
      <blockquote>
        <p><font face="Arial, Helvetica, sans-serif" size="2">Don't have an account? 
          <a href="register.php">Click here to register for your free account.</a></font></p>
        <hr>
      </blockquote>
      <blockquote> <?
	if ( !$SignInSuccess )
	{
		print( "<p><font face=\"Arial, Helvetica, sans-serif\" size=\"3\"><b><font size=\"4\" color=\"#003399\">Sign!</font></b></font></p>" );
	}

	if ( isset($MemberID) || isset($Password ) )
	{
		if ( ($MemberID == "") || ($Password == "") )
		{
			print("<p><font color=\"#FF0033\">Please enter both the <b>Member ID</b> and the <b>Password</b>.</font></p>\n");
		}
		else if ( !$SignInSuccess && $TryLogin )
		{
			print("<p><font color=\"#FF0033\">Incorrect <b>Member ID</b> and/or <b>Password</b>.</font></p>\n");
		}
	}

	if ($SignInSuccess)
	{
		print("<p><font face=\"Arial, Helvetica, sans-serif\" size=\"3\" color=\"#006600\"><b>Welcome $MemberID!</b></font></p>
        <p><font face=\"Arial, Helvetica, sans-serif\" size=\"3\"><a href=\"/MyAccount/MyAccount.php\">Click 
          here to go to \"My Account\".</a><br><br><a href=\"/\">Or click 
          here to continue.</a></font></p> " );
		print( "</blockquote></td></tr></table>\n" );
		print( "</body></html>\n" );
		exit;
	}
?> 
        <form action="signin.php" method="post" name="SignIn">
          <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr> 
              <td width="18%">&nbsp;</td>
              <td width="82%"><font face="Arial, Helvetica, sans-serif" size="2">Please 
                enter in the Member ID and Password for your account.</font></td>
            </tr>
            <tr> 
              <td width="18%"><b><font face="Arial, Helvetica, sans-serif">Member 
                ID:</font></b></td>
              <td width="82%"> 
                <input type="text" name="MemberID" class="inputbox" size="10" maxlength="10" <? print( "value=\"$MemberID\"" ); ?> >
              </td>
            </tr>
            <tr> 
              <td width="18%"><b><font face="Arial, Helvetica, sans-serif">Password:</font></b></td>
              <td width="82%"> 
                <input type="password" name="Password" class="inputbox" size="10" maxlength="10" onfocus="focused=true" onblur="focused=false" />
              </td>
            </tr>
            <tr>
              <td width="18%">&nbsp;</td>
              <td width="82%"> 
                <input type="checkbox" name="SavePwd" value="save">
                <font face="Arial, Helvetica, sans-serif" size="2">Save password 
                on my computer.</font></td>
            </tr>
            <tr> 
              <td width="18%"><b></b></td>
              <td width="82%"><a href="javascript:document.SignIn.submit()"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Sign 
                in!</b></font> </a></td>
            </tr>
          </table>
        </form>
        <p>&nbsp;</p>
      </blockquote>
    </td>
  </tr>
</table>
<?
	include( "footer.inc" );
?>
</body>
</html>
