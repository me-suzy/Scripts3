<?php die("Access restricted");?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td colspan=5>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr height=15>
      <td align=center width=130>
        <font size=2 color="#000000"><SPAN class="%%class%%">Page's name</SPAN></font>
      </td>
      <td align=left width=160><SPAN class="f12"><input type="text" size=20 maxlength=20 name=apagename value="%%apagename%%">
      <td>
		<font size=2 color="#000066"><SPAN class="%%class%%">(example: My Home Page)</SPAN></font>
	  </td>
  </tr>
    </table>
  <td></tr>
  <tr height=30>
      <td valign=bottom align=right height=40 colspan=5><SPAN class="f12"><input type="text" size=8 maxlength=6 name=abgcolor value="%%abgcolor%%">
      <input type="submit" class=b44 value="Select your background color"></SPAN></td>
  </tr>
  <tr height=60 bgcolor="#%%abgcolor%%" align=center>
      <td height=60><input type="image" border=0 name="aimgset1" src="%%url%%admin.php?picture=1" onclick="jump_fun(this.form)"></td>
      <td><input type="image" border=0 name="aimgset2" src="%%url%%admin.php?picture=2" onclick="jump_fun(this.form)"></td>
      <td><input type="image" border=0 name="aimgset3" src="%%url%%admin.php?picture=3" onclick="jump_fun(this.form)"></td>
      <td><input type="image" border=0 name="aimgset4" src="%%url%%admin.php?picture=4" onclick="jump_fun(this.form)"></td>
      <td><input type="image" border=0 name="aimgset5" src="%%url%%admin.php?picture=5" onclick="jump_fun(this.form)"></td>
  <tr height=10 align=center>
      <td height=10><input type="radio" border=0 name="aimgset" value="1" onclick="jump_fun(this.form)"%%i1%%></td>
      <td><input type="radio" border=0 name="aimgset" value="2" onclick="jump_fun(this.form)"%%i2%%></td>
      <td><input type="radio" border=0 name="aimgset" value="3" onclick="jump_fun(this.form)"%%i3%%></td>
      <td><input type="radio" border=0 name="aimgset" value="4" onclick="jump_fun(this.form)"%%i4%%></td>
      <td><input type="radio" border=0 name="aimgset" value="5" onclick="jump_fun(this.form)"%%i5%%></td>
  </tr>
  <tr height=40>
      <td height=40 align=right valign=bottom colspan=5><input name="asavethis" type="submit" class=b55 value="Save the parameters"></SPAN></td>
  </tr>
</table>