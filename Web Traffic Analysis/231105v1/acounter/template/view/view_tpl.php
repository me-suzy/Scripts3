<?php die("Access restricted");?>
<html>
<head>
<title>Actual Counter %%version%% - View</title>
<LINK href="%%url%%template/css/main.css" type=text/css rel=stylesheet>
<SCRIPT LANGUAGE="JavaScript">
<!--
function jump_fun(form) {eval(form.submit())}
function GoRef(refname) {eval(window.open(refname))}
function col_select(num) {view.vdcolumn.value=num;eval(view.submit())}
//-->
</SCRIPT>
</head>
<body link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<div align="center">
<form name=view method="post" action="%%url%%view.php">
<input type="hidden" name="vdcolumn" value="50">
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
    <table bgcolor="#999999" width="100%" height="100%" border="0" cellspacing="1" cellpadding="0"><tr><td valign="top">
                <table bgcolor="#CCCCCC" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align=left>%%account%%</td>
                    <td width="100%" align=center>
                    <font size=3 color="#000000">
                      <SPAN class="f11">Today <b>%%today%%</b></SPAN>
                    </font></td>
                    <td align=right>
                      <input class=b3 type="button" name="vhelp_x" value="Help">
                    </td>
                  </tr>
                </table>
    </td></tr></table>
              </td>
            </tr>
            <tr>
              <td height=10 width="100%" colspan=2></td>
            </tr>
            <tr>
              <td colspan=2>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="60">%%type%%</td>
                    <td align=right width="280">%%interval%%</td>
                    <td align="right" width="235">%%time%%</td>
                  </tr>
                </table>
              </td>
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
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="50" align="center">
                      <b><font size=3 color="#000000">
                        <SPAN class="%%class%%">%%descr%%</SPAN>
                        </font></b>
                    </td>
                  </tr>
                  <tr>
                    <td align="center">
                      %%statistic%%
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        <td bgcolor="#333366" width=10 height="100%">&nbsp;%%rep%%</td>
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