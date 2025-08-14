<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');

   // comes back from an page without access (probably no cookies enabled)
   if($loginfail){
      $ERR = "ERR_044";                 //cookies müssen ...
   }

   if($submited=="yes") {                       //send by this form (Submitted)?
                 //sets cookie Autolog for
    if ($chk_autolog=="on"){

      $autolog_valid_time = time()+604800;  //604800 seconds (7Days)
      }
    else {
      $autolog_valid_time = "0"; //destroy after session
      }
    setcookie ("autolog",$chk_autolog,$autolog_valid_time);



    if ($apw_crypt == "yes") {$passwd = md5($passwd);}
    if(($passwd == $AdminPasswd) or ($autolog=="on" and ($HTTP_COOKIE_VARS["authenticated"]=="1")) ) {
         setcookie("authenticated","1",$autolog_valid_time);
         Header("Location: admin.php");
      }else{
         $ERR = "ERR_026";  // "passwort Falsch"
      }
   }
   else { // if not submitted
   $chk_autolog = $autolog;
}
// $autolog = $chk_autolog;
?>
<HTML>
<HEAD>
<TITLE>PHPAuktion-Login</TITLE>
<SCRIPT language = "JavaScript">
function crypt_msg(){
	alert ("<? echo $MSG_469 ?>");
	return (true);
	}
</script>
</HEAD>


<BODY <? if ($apw_crypt != "yes") {?>onunload="crypt_msg()"<?}?>>

<? require("./header.php"); ?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
    <TD> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
      <br>
      <center>
        <B> 
        <? print $MSG_052;  /* "Login" */  ?>
        </B>
      </center>
      </font> <BR>
      <BR>

   <? /* if(!$action || ($action && $ERR)) :    ****************************/?>

      <FORM NAME=login ACTION=login.php METHOD=POST>
        <TABLE WIDTH=50% CELLPADDING=0 align="center">
          <TR> 
            <TD> 
              <div align="center"><font face="Verdana, Verdana, Arial, Helvetica, sans-serif" size="2" color="red"><b> 
                <? print $$ERR; ?>
                </b></font> </div>
            </TD>
          </TR>
          <TR>
            <TD>&nbsp;</TD>
          </TR>
        </TABLE>


        <TABLE WIDTH=50% CELLPADDING=0 align="center">
          <? if (!($chk_autolog=="on" and $HTTP_COOKIE_VARS["authenticated"]=="1")) { ?>
          <TR>
            <TD ALIGN=right height="46"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
              <?print $MSG_004;  /*  Passwort  */?>
            </TD>
            <TD height="46"> 
              <INPUT TYPE=password NAME=passwd SIZE=20>
            </TD>
          </TR>
        <? } ?>
          <TR>
            <TD ALIGN=right> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
              <? echo $MSG_468 ?>
            </TD>
            <TD width="50%">
              <input type="checkbox" name="chk_autolog" value="on" <? if ($chk_autolog=="on") print "checked"; ?>>

            </TD>
          </TR>
          <TR>
          </TR>
        </TABLE>
		<table width="50%" align="center">
          <tr> 
            <td height="36"> 
              <div align="center"> 
                <INPUT TYPE=hidden NAME=submited VALUE="yes">
                <INPUT TYPE=submit NAME=action VALUE=<? print $MSG_052;   /* "Login" */ ?>>
              </div>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
      </FORM>
     <? /* endif;         *************************************************************/?>
 <br><br>
      </FONT> </TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
</TD>
</TR>
</TABLE>


<? require("./footer.php"); ?>
</BODY>
</HTML>