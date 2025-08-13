<?#//v.1.0.1

		require('./includes/messages.inc.php');
	require('./includes/config.inc.php');
include "header.php"


?> 
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td> 
      <table width="100%" border="0" cellspacing="0" cellpadding="8" height="296" bgcolor="#FFFFFF">
        <tr> 
          <td height="292" valign="top"> 
            <p>&nbsp;</p>
            <p><? print $std_font; ?><? print $MSG_1025; ?>	<a href="mailto:<? print $SETTINGS[adminmail]; ?>"><b><? print $MSG_1026; ?></a> 
              </b></font></p>
            <p>&nbsp;</p>
            <table width="91%" border="0" cellspacing="0" cellpadding="6" align="center">
              <tr>
                <td align="center" bgcolor="<?=$FONTCOLOR[$SETTINGS[headercolor]]?>" height="44"><b><? print $std_font; ?><? print $MSG_1027; ?></b></font></td>
              </tr>
            </table>
            
            <p>&nbsp;</p><p>
              
            </p>
          </td>
        </tr>
      </table>
      
</td>
  </tr>
</table>
<? include "footer.php"?>

