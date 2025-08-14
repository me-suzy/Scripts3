<?php die("Access restricted");?>
    <td align=center vAlign=bottom>
<table width=30 border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><font size="2" color="#000000">
      <SPAN class="%%class%%">%%value%%</SPAN>
    </font>
      <input type="hidden" name="vdcol%%num%%" value="%%inttim%%">
    </td>
  </tr>
  <tr>
    <td align=center><img border=0 width="30" src="%%url%%images/diagram/vertical/%%color%%top.gif" onclick = "col_select(%%num%%);"></td>
  </tr>
  <tr>
    <td align=center><img border=0 height="%%height%%" width="30" src="%%url%%images/diagram/vertical/%%color%%center.gif" onclick = "col_select(%%num%%);"></td>
  </tr>
  <tr>
    <td align=center><img border=0 width="30" src="%%url%%images/diagram/vertical/%%color%%bottom.gif" onclick = "col_select(%%num%%);"></td>
  </tr>
</table>
    </td>