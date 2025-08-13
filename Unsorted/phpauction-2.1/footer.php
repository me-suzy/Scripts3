<?

/*



   Copyright (c), 1999, 2000 - phpauction.org                  

   

   This program is free software; you can redistribute it and/or modify 

   it under the terms of the GNU General Public License as published by 

   the Free Software Foundation (version 2 or later).                                  

                                                                        

   This program is distributed in the hope that it will be useful,      

   but WITHOUT ANY WARRANTY; without even the implied warranty of       

   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        

   GNU General Public License for more details.                         

                                                                        

   You should have received a copy of the GNU General Public License    

   along with this program; if not, write to the Free Software          

   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

   

*/



?>



    <TABLE WIDTH="765" BORDER=0 ALIGN=LEFT BGCOLOR=<?=$FONTCOLOR[$SETTINGS[bordercolor]]?>>

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

        | <A HREF="user_login.php?">

   		<? print $footer_font.$MSG_259; ?></FONT></A>

		<?
		}
		?>

        | <A HREF="./help.php?">

   		<? print $footer_font.$MSG_164; ?></FONT></A>

       
        <BR><BR>    

        <? print $footer_font.$MSG_260; ?></FONT><BR>

        </FONT>

    </CENTER>

    </TD></TR>

    </TABLE>


</TD></TR>

</TABLE>

</BODY>

</HTML>
