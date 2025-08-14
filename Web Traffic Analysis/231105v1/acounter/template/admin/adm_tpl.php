<?php die("Access restricted");?>
<html>
<head>
<title>Actual Counter %%version%% - Admin</title>
<LINK href="%%url%%template/css/main.css" type=text/css rel=stylesheet>
<SCRIPT LANGUAGE="JavaScript">
<!--
function jump_fun(form) {eval(form.submit())}
function GoRef(refname) {eval(window.open(refname))}
function goWindow(url) {window.location.href=url;}
//-->
</SCRIPT>
</head>
<body link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<div align="center">
<form name=admin method="post" action="%%url%%admin.php">
<input type="hidden" name="ver" value="%%ver1%%">
    <table bgcolor="#666699" border="0" cellspacing="1" cellpadding="0"><tr><td>
    <table width=755 bgcolor="#333366" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height=70 width=170 align=center><a href="http://www.actualscripts.com/acounter/" target="_blank"><img border=0 src="%%url%%images/view/logo.gif"></a></td>
        <td width=575>
          <table bgcolor="#333366" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height=10 width="100%" colspan=2></td>
            </tr>
            <tr>
              <td width="575">
    <table bgcolor="#999999" width="573" height="100%" border="0" cellspacing="1" cellpadding="0"><tr><td valign="top">
                <table bgcolor="#CCCCCC" width="573" height="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align=center>
                    <font size=3 color="#000000">
                      <SPAN class="f11">&nbsp;&nbsp;Today <b>%%today%%</b></SPAN>
                    </font></td>
                    <td width=110 align=right>
                      <input class=b3 type="button" value="View" onclick="goWindow('./view.php')">
                      <input class=b3 type="button" value="Help">
                    </td>
                  </tr>
                </table>
    </td></tr></table>
              </td>
            </tr>
            <tr>
              <td height=35 width="100%" align=center valign=center colspan=2><b><font size=3 color="#CCCCCC">
                        <SPAN class="f10">%%ver2%%</SPAN></font></b></td>
            </tr>
          </table>
        </td>
        <td bgcolor="#333366" width=10>&nbsp;</td>
      </tr>
      <tr>
        <td align="center" valign="top" width=170>%%menu%%</td>
        <td valign="top" height="100%" width="575" bgcolor="#CCCCCC">
          <table height="100%" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr valign="top">
              <td>
                <table width="100%" height="100%" border="1" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="50" align="center">
                      <b><font size=3 color="#000000">
                        <SPAN class="%%class%%">%%descr%%</SPAN>
                        </font></b>
                    </td>
                  </tr>
                  <tr>
                    <td height="100" align="center">
                      %%setting%%
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        <td width=10 height="100%" bgcolor="#333366">&nbsp;</td>
      </tr>
      <tr>
        <td colspan=3 width="100%">
    <table bgcolor="#333366" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
        <td width=2></td>
        <td height=20 width=258 align="left"><font size=2 color="#FFFFFF">
            <SPAN class="f9">Actual Counter %%version%%</SPAN>
        </font></td>
        <td width=490 align="right"><font size=2 color="#FFFFFF">
            <SPAN class="f9">Copyright &copy; 2002 <a href="%%murl%%" target="_blank"><b>Actual Scripts</b></a> Company. All Rights Reserved.</SPAN>
        </font></td>
        <td width=5></td>
    </td></tr></table>
        </td>
      </tr>
    </table>
    </td></tr></table>
</form>
</div>
</body>
</html>