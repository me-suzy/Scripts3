<html>
<head>
<title>SunShop : Turnkey Solutions</title>
<script language="JavaScript">
	function setFocus()	{ document.frmLogin.IN_USER.focus(); }
</script>
</head>

<body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<br>
<div align="center">
  <center>
  <table border="0" width="400">
    <tr>
      <td width="100%">
        <p align="center"><font face="Verdana,Arial" size="2" color="#7B2418"><b>SunShop Administration Login</b></font></td>
    </tr>
    <tr>
      <td width="100%">
        <p align="center"><b><font face="Verdana,Arial" size="1">Developed By <!--CyKuH-->Turnkey Solutions</font></b></td>
    </tr>
    <tr>
      <td width="100%">
        <form name="frmLogin" method="POST" action="index.php">
		<input type="hidden" name="firstlogin" value="1">
          <table border="0" width="100%">
            <tr>
              <td width="100%" colspan="2"><font face="Verdana,Arial" size="2"><b>Administrator Login</b></font> <?PHP if ($invalid) { ?><font color="Red">Invalid Login</font><?PHP } ?></td>
            </tr>
            <tr>
              <td width="28%"><font face="Verdana,Arial" size="2">Login ID: </font></td>
              <td width="72%"><input type="text" name="IN_USER" size="20"></td>
            </tr>
            <tr>
              <td width="28%"><font face="Verdana,Arial" size="2">Password: </font></td>
              <td width="72%"><input type="password" name="IN_PW" size="20"></td>
            </tr>
            <tr>
              <td width="28%">&nbsp;</td>
              <td width="72%"><input type="submit" name="Submit" value="Login"></td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
    <tr>
      <td width="100%">
        <p align="center">&nbsp;</td>
    </tr>
    <tr>
      <td width="100%"><b><font face="Verdana,Arial" size="2" color="#7B2418">Instruction:<br>
        </font></b><font face="Verdana,Arial" size="2" color="#000000">Please login using your login administration information. *IMAPORTANT* When viewing your customers credit card information it is important that you connect securely. If your server supports it you may do this by replacing http:// with https:// in the address.</font><b></b></td>
    </tr>
  </table>
  </center>
</div>
<script language="JavaScript">
	setFocus();
</script>
</body>
</html>
