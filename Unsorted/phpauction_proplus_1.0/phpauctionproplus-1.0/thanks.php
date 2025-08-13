<?#//v.1.0.1
		require('./includes/messages.inc.php');
	require('./includes/config.inc.php');
include "header.php";

?> <br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="360" valign="top"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="8" height="268" bgcolor="#FFFFFF">
        <tr> 
          <td height="333" valign="top"> <br>
             <br>
            <p>&nbsp;</p>
            <table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td><? print $std_font; ?><? print $MSG_1022; ?>	<a href="mailto:<? print $SETTINGS[adminmail]; ?>"><b><? print $MSG_1023; ?></b></a> 
                  </font></td>
              </tr>
            </table>
            <p>&nbsp;</p>
            <table width="85%" border="0" cellspacing="0" cellpadding="0" align="center" height="42">
              <tr> 
                <td bgcolor="<?=$FONTCOLOR[$SETTINGS[headercolor]]?>"height="51" align="center"><b><? print $std_font; ?><? print $MSG_1024; ?></b></font></td>
              </tr>
            </table>
            <p>&nbsp;</p>
          </td>
        </tr>
      </table>
  
    </td>
  </tr>
</table>
<? include "footer.php"?>
