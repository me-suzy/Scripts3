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
 
	//--Authentication check
	if(!$authenticated){
		Header("Location: login.php");
	}
	
	
	//--Check email
	
	if($submited && !$AdminPasswd){
		$ERR = "ERR_162";}
			
	if($submited && !$$ERR){
		
		if ($new_admin_pwd == $new_admin_pwd2){ //falls die beiden neuen Passwörter identisch sind:
		if ($apw_crypt == "yes"){ $old_admin_pwd = md5($old_admin_pwd);}
		if ($old_admin_pwd == $AdminPasswd) {	// das Adminpasswort war falsch!
		//-- Update passwd.inc.php file
		
		$buffer = file("../includes/passwd.inc.php");
		$fp = fopen("../includes/passwd.inc.php", "w+");

		$i = 0;
		
		$ERR= "ERR_160";
		while($i < count($buffer)){
			if(strpos($buffer[$i],"apw_crypt")){  // falls akt. zeile $apw_crypt
				$buffer[$i]="\$apw_crypt = \"yes\";\n";
				$apw_crypt="yes";
				}
			if(strpos($buffer[$i],"AdminPasswd")){
				if ($apw_crypt != "yes"){
					fputs($fp,"\$apw_crypt = \"yes\";\n");
					}
				$buffer[$i] = str_replace($AdminPasswd,md5($new_admin_pwd),$buffer[$i]);
				$ERR = "ERR_161";
				$pw_changed="yes";					
			}
			
			fputs($fp,$buffer[$i]);	
			$i++;
		}
		fclose($fp);
	}else{$ERR = "ERR_164";}   // das Adminpasswort war falsch!
	}else{$ERR = "ERR_163";} // falls die beiden neuen nicht identisch sind
	
	}
	
?>
<HTML>
<HEAD>
<TITLE>Admin-Passwort ändern</TITLE>

<SCRIPT language="JavaScript">
function check_data(){
var neupass1 = document.conf.new_admin_pwd.value;
var neupass2 = document.conf.new_admin_pwd2.value;
var checked = true;

if (neupass1 != neupass2) {
	alert ("<? echo $ERR_163 ?>\n<? echo $MSG_464 ?>");
	document.conf.new_admin_pwd.value = "";
	document.conf.new_admin_pwd2.value = "";
	checked = false;
	}

if (checked) {
	document.conf.method = "post";
	document.conf.action = "<? echo $PHP_SELF ?>";
	document.conf.submit();
	}
return (checked);
}
</SCRIPT>

</HEAD>

<?    require('../includes/styles.inc.php'); ?>
  
<BODY>

<? require("./header.php"); ?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_463; /* Passwort ändern */ ?>  
		
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
	
	
          <table WIDTH="100%" border="0">
            <TD> <div align="center">
              <? 
			print $MSG_462;  // Ändere passwort MSG_055; 
			if($$ERR){
				print "<FONT COLOR=red><BR>".$$ERR;
			}else{
				if($$MSG){
					print "<FONT COLOR=red><BR>".$$MSG;
				}else{
					print "<BR><BR>";
				}
			}
		?>
              </div>
            </TD>
          </table>
          <?if (!$pw_changed){ ?>
          <FORM NAME=conf onsubmit="return check_data()">
          <TABLE WIDTH=400 CELLPADDING=2>
            <TR> 
              <TD>
                <div align="right"><? echo $MSG_465 ?>&nbsp;</div> 
              </TD>
              <TD> 
                
                <INPUT TYPE=password NAME=old_admin_pwd VALUE="<? print $value; ?>" SIZE=30>
              </TD>
            </TR>
            <TR> 
              <TD>
                <div align="right"><? echo $MSG_466 ?>&nbsp;</div>
              </TD>
              <TD>
                <input type=password name=new_admin_pwd value="<? print $value; ?>" size=30>
              </TD>
            </TR>
            <TR>
              <TD>
                <div align="right"><? echo $MSG_467 ?>&nbsp;</div>
              </TD>
              <TD>
                <input type=password  name=new_admin_pwd2 value="<? print $value; ?>" size=30>
              </TD>
            </TR>
          </TABLE>
          <table WIDTH="100%" border="0" align="center">
            <TR>
	
	          <TD> 
                <div align="center">
		  <INPUT TYPE=hidden NAME=submited VALUE="yes">
                  <INPUT TYPE=submit NAME=act VALUE="<? print $MSG_461; ?>">
                </div>
              </TD>
	</TR>
</table>
	</FORM>
	<? } ?>
	<BR><BR>

	<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
	<A HREF="admin.php" CLASS="links">Admin Home</A>
	</FONT>
	<BR><BR>
	</center>
</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
</TD>
</TR>
</TABLE>

<? require("./footer.php"); ?>
</BODY>
</HTML>
