<?php die("Access restricted");?>
<table width="570" bgcolor="#CCCCCC" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align=center valign="top" width="100%">

      <table width="560" bgcolor="#666666" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td width="30" height="25" align="center"><b><font size=3 color="#FFFFFF">
             <SPAN class="%%class%%">N</SPAN>
          </font></b></td>
          <td width="350" align="center"><b><font size=3 color="#FFFFFF">
             <SPAN class="%%class%%">%%hname%%</SPAN>
          </font></b></td>
          <td width="80" align="center"><b><font size=3 color="#FFFFFF">
             <SPAN class="%%class%%">%%hvalue%%</SPAN>
          </font></b></td>
          <td width="100" align="center"><b><font size=3 color="#FFFFFF">
             <SPAN class="%%class%%">%</SPAN>
          </font></b></td>
        </tr>
          %%rows%%
        <tr>
          <td height="25" align="center" colspan="2"><b><font size=3 color="#FFFFFF">
             <SPAN class="%%class%%">%%val2%%</SPAN>
          </font></b></td>
          <td width="80" align="center"><b><font size=3 color="#FFFFFF">
             <SPAN class="%%class%%">%%val3%%</SPAN>
          </font></b></td>
          <td width="100" align="center"><b><font size=3 color="#FFFFFF">
             <SPAN class="%%class%%">%%val4%%</SPAN>
          </font></b></td>
        </tr>
      </table>

    </td>
  </tr>
  <tr>
    <td align="center">
      <table width="560" bgcolor=#000066 height=24 border="0" cellspacing="0" cellpadding="1">
        <tr bgcolor=#000066>
          <td><input type="image" border=0 name="vbegin" width="23" src="%%url%%images/scroll/vbegin.gif"></td>
          <td><input type="image" border=0 name="vup" width="23" src="%%url%%images/scroll/vup.gif"></td>
          <td width="100%" align=center>
      <table width="100%" bgcolor=#FFFFFF height=20 border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align=center>
            <input type="hidden" name="vbeginnum" value="%%beginnum%%">
            <input type="hidden" name="vmaxnum" value="%%maxnum%%">
            <font size=3 color="#000066"><b>
               <SPAN class="%%class%%">%%range%%%%maxnum%%</SPAN>
            </b></font>
            </SPAN>
          </td>
        </tr>
      </table>
          </td>
          <td><input type="image" border=0 name="vdown" width="23" src="%%url%%images/scroll/vdown.gif"></td>
          <td><input type="image" border=0 name="vend" width="23" src="%%url%%images/scroll/vend.gif"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width=560 height=10>
    </td>
  </tr>
</table>