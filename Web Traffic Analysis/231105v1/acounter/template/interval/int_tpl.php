<?php die("Access restricted");?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input type="image" border=0 name="vintervaladd" src="%%url%%images/interval/intadd.gif"></td>
    <td><input type="image" border=0 name="vintervalsub" src="%%url%%images/interval/intsub.gif"></td>
    <td width="20"></td>
    <td valign=top>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign=center>
            <select name="vinterval"%%ext%%>
              %%items%%
            </select>
          </td>
          <td valign=center>
            <select name="vtime"%%ext%%>
              %%parts%%
            </select>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>