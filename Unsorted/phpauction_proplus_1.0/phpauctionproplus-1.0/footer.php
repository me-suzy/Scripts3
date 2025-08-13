<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


?>



    <TABLE WIDTH="764" BORDER=0 ALIGN=LEFT BGCOLOR=<?=$FONTCOLOR[$SETTINGS[bordercolor]]?>>

    <TR><TD>

    <CENTER>

        <? print $footer_font; ?>

        <A HREF="./index.php?">

        <? print $footer_font.$MSG_501; ?></FONT></A>

        

        | <A HREF="./sell.php?">

  		<? print $footer_font.$MSG_236; ?></FONT></A>

		<?
	   	if($HTTP_SESSION_VARS["PHPAUCTION_LOGGED_IN"])
	   	{
		/* user is logged in, give link to edit data or log out */
		?>

        | <A HREF="./user_menu.php?">

   		<? print $footer_font.$MSG_622; ?></FONT></A>

        | <A HREF="./logout.php?">

   		<? print $footer_font.$MSG_245; ?></FONT></A>

		<?
		} else {
		/* user not logged in, give link to register or login */
		?>

        | <A HREF="./register.php?">

   		<? print $footer_font.$MSG_235; ?></FONT></A>

        | <A HREF="./user_login.php?">

   		<? print $footer_font.$MSG_259; ?></FONT></A>

		<?
		}
		?>

        | <A HREF="./help.php?">

   		<? print $footer_font.$MSG_164; ?></FONT></A>

       
        <BR><BR>    

        <? print $footer_font.$MSG_260; ?><BR><BR>
	<a href="mailto:<? print $SETTINGS[adminmail]; ?>"><? print $footer_font; ?>Webmaster</a></FONT><BR><BR>

        </FONT>

    </CENTER>

    </TD></TR>

    </TABLE>


</TD></TR>

</TABLE>

</BODY>

</HTML>
