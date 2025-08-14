<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

	

	//-- Function definition section

	

   include "./includes/dates.inc.php";

	



?>

<HTML>

<HEAD>
<TITLE></TITLE>





</HEAD>



<BODY BGCOLOR="#FFFFFF" TEXT="#08428C" LINK="##08428C" VLINK="#08428C" ALINK="#08428C">
<center>
<TABLE width="764" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">

  <TR> 

    <TD>

      <TABLE width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="#ffffff">

        <TR> 

          <TD width="35%" VALIGN=MIDDLE><A href="index.php?SESSION_ID=<SCRIPT Language=PHP> print urlencode($sessionID); </SCRIPT>"><IMG src="images/logo.gif" border="0"></A></TD>

          <TD width="65%" valign="MIDDLE" align=right>

          <A HREF="http://webauction.de.vu" target="_top"><IMG src="./images/banner.gif" width="468" height="60" BORDER=0></A></TD>

        </TR>

      </TABLE>

      <TABLE width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#0066CC">

        <TR> 

          <TD ALIGN=CENTER>

          	<A href="./index.php?SESSION_ID=<? print urlencode($sessionID); ?>"><? print $nav_font.$MSG_274; ?></FONT></A>

          	&nbsp;&nbsp;<font color="#FFFFFF">|&nbsp;&nbsp;</font> 

          	<A href="./einrichten_ver.php?SESSION_ID=<? print urlencode($sessionID); ?>"><? print $nav_font.$MSG_236; ?></FONT></A>

          	&nbsp;&nbsp;<font color="#FFFFFF">|&nbsp;&nbsp;</font>

          	<A href="./anmeldung.php?SESSION_ID=<SCRIPT Language=PHP> print urlencode($sessionID); </SCRIPT>"><? print $nav_font.$MSG_235; ?></FONT></A>

          	&nbsp;&nbsp;<font color="#FFFFFF">|&nbsp;&nbsp;</font>

          	<A href="./my_account_login.php?SESSION_ID=<SCRIPT Language=PHP> print urlencode($sessionID); </SCRIPT>"><? print $nav_font.$MSG_259; ?></FONT></A>

          	&nbsp;&nbsp;<font color="#FFFFFF">|&nbsp;&nbsp;</font>

          	<A href="help.php?SESSION_ID=<? print urlencode($sessionID); ?>"><? print $nav_font.$MSG_164; ?></FONT></A>
           
                &nbsp;&nbsp;<font color="#FFFFFF">|&nbsp;&nbsp;</font>
                
                <a href="suche.php?SESSION_ID=<SCRIPT Language=PHP> print urlencode($sessionID); </SCRIPT>"><? print $nav_font.$MSG_238; ?></FONT></a>

         </TD>

        </TR>

      </TABLE>

      <TABLE bgcolor="#ffffff" border=0 width="100%" cellspacing="0" cellpadding="4">

        <TR width=100%> 

            

          <TD width="35%" valign=top>
            <table border=0 cellspacing="0" cellpadding="0">
            <tr>
            <td><? print $sml_font.$MSG_103 ?></FONT></td>
            <td><FORM name="search" action="search.php" method="GET">
            <INPUT type=HIDDEN name="SESSION_ID" value="<SCRIPT Language=PHP> print urlencode($sessionID); </SCRIPT>">
              
              <INPUT type="text" name="words" size=15 value="<SCRIPT Language=PHP> print htmlspecialchars($q); </SCRIPT>">
              <INPUT TYPE=submit NAME="" VALUE=<? print $MSG_275;?>></td></FORM></td>
              </tr>
              <tr>
              <td></td>
              <td valign=top><A href="./suche.php?SESSION_ID=<SCRIPT Language=PHP> print urlencode($sessionID); </SCRIPT>"><? print $sml_font.$MSG_1000 ?></FONT></A></td>
              </tr>
         </table>
        </TD>

          <TD width="42%" valign=top> 

            <FORM name=browse action="browse.php" method=GET>

            	<INPUT type=HIDDEN name="SESSION_ID" value="<? print urlencode($sessionID); ?>"> 

              	  <? print $sml_font.$MSG_104; ?></FONT>

              	  <? include "./includes/categories_select_box.inc.php"; ?>

              	  <INPUT TYPE=submit NAME="" VALUE="<? print $MSG_275;?>">

              </FORM>

          </TD>

          

          <TD width="23%" HEIGHT=60 VALIGN=top align=RIGHT> <FONT face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#08428C"> 

            <?

			//-- Display current Date/Time

			print ActualDate();

			print "<BR><BR>";

		    ?>

            </FONT>

            </TD>

            </TR>

    

        





       

      </TABLE>

