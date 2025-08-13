<?
  	include( "config.php" );

	$WelcomeMember = GetSessionMemberID();	
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="287"><? print( "<img src=\"$HomeInstall/logo.gif\" width=\"287\" height=\"62\">"); ?></td>
    <td width="*"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="62">          
        <tr align="right" valign="top"> 
          <td> 
<?
if ( isset($WelcomeMember) && $WelcomeMember != "" )
	print( "<font face=\"Arial, Helvetica, sans-serif\" size=\"2\">Welcome <a href=\"$HomeInstall/MyAccount/MyAccount.php\">$WelcomeMember</a>!</font>\n" );
else
	print( "&nbsp;" );
?>
          </td>
        </tr>          
        <tr valign="bottom" align="right"> 
          <td><font face="Arial, Helvetica, sans-serif" size="2"><b>
		<? print("<a href=\"$HomeInstall/index.php\">"); ?>Home</a> | 
		<? print("<a href=\"$HomeInstall/MyAccount/MyAccount.php\">"); ?>My Account</a> | 
		<? print("<a href=\"$HomeInstall/search.php\">"); ?>Search</a> | 
		<? print("<a href=\"$HomeInstall/register.php\">"); ?>Register</a></b></font></td>
          </tr>
        </table>       
    </td>
  </tr>
</table>
<hr noshade>
