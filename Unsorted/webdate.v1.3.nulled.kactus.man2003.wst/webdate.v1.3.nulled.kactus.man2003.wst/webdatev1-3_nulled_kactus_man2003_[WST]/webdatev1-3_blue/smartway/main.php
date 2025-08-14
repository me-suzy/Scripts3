<?
	header("Pragma: no-cache");

	require "lib/system.lib";
	require "lib/plug-ins.lib";
	require "lib/mod_xml.lib";
	require "lib/mod_xml_ct.lib";
	require "lib/mail.lib";

	require "services/BD3LoadConfiguration.service";
	require "services/BD3LoadDBDevice.service";

	require "services/BD3Auth.service";
	require "services/BD3ProcessEvents.service";
	require "services/BD3PushServiceName.service";
	require "services/BD3PushPluginName.service";
	session_start();

	$db = c();
 

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Administrative Area</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<!--Start Top-->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td><img src="images/logo.gif" alt="" border="0"></td>
    <td background="images/bg_top.gif" width="100%" align="right" style="padding:10px;" valign="bottom"><table width="300" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><div align="right"><font color="#FFFFFF">Logged in as user : <strong><? echo sysGetLoggedUserName(); ?></strong><br>
              You have been logged in on : <? echo date("m/d/y H:i",$bd3AuthDT); ?><br>
              <br>
              </font><img src="images/bullet_arrow02.gif" width="5" height="9" alt="" border="0" align="absmiddle" hspace="5"><a href="main.php?<? echo strtotime(date("d M Y H:i:s")); ?>"><strong><font color="#FFFFFF">Refresh
              Page</font></strong></a><img src="images/bullet_arrow02.gif" width="5" height="9" alt="" border="0" align="absmiddle" hspace="5"><a href="main.php?action=logout"><strong><font color="#FFFFFF">Log
              out from system</font></strong></a>
            </div></td>
        </tr>
      </table>

    </td>
    <td><img src="images/border_top.gif" width="26" height="91" alt="" border="0"></td>
  </tr>
</table>
<!--End Top--><br>

<table width="100%" border="0" cellspacing="4" cellpadding="4">
  <tr>
    <td width="20%" valign="top">
      <?
		if($nLoggedAsRoot)
		{
		 	@include "services/BD3AdminstratorMenu.service";
		 	@include "services/BD3Tools.service";
		}
	?>
      <? @include "services/BD3UsersMenu.service"; ?>
    </td>
    <td valign="top">
      <?

		if($current_service == "default" || ($current_plugin == "" && $current_service == ""))
		{
			@include "modules/kernel/default";
		}

		if($current_service != "" && $current_service != "default")
		{
			include "services/BD3ModuleRoutines.service";
			include "services/BD3ModuleAnalyser.service";
		}
		else
		{
			if($current_plugin != "")
			{
				include "services/BD3PluginAnalyser.service";
			}
		}
	?>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
  <tr>
    <td valign="top" height="106">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="right" valign="top" colspan="2">
            <hr noshade size="1" color=C0C0C0>
          </td>
        </tr>
        <tr>
          <td valign="top">
            <table width="295" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="top" width="31" height="22"><a href="http://www.webscribble.com/support.shtml"><font color="#669966">[<img src="images/p.gif" width="14" height="14" border="0">]</font></a></td>
                <td><a href="http://www.webscribble.com/support.shtml"><font color="#999999">Contact support</font></a></td>
              </tr>
              <tr>
                <td valign="top" width="31">&nbsp;</td>
                <td>
                  <hr noshade size="1" color=F0F0F0 width="211" align="left">
                </td>
              </tr>
              <tr>
           
              </tr>
            </table>
          </td>
          <td align="right" valign="top">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="22" width="30" valign="top"><a href="#top"><font color="#669966">[<img src="images/u.gif" border="0" width="13" height="14">]</font></a></td>
                <td width="80"><a href="#top"><font color="#999999">Go to the
                  top </font></a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td valign="top" colspan="2">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
<?
	$db;
?>
