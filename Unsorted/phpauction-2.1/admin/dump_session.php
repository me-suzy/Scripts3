<?
	include "loggedin.inc.php";

/*

   Copyright (c), 1999, 2000, 2001 - phpauction.org                  
   
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

	include "includes/config.inc.php";

	if (strtolower($mode)=="clear")
	{
		while(list($key,$val)=each($sessionVars))
		{
			unset($sessionVars[$key]);
		}
		putSessionVars();
		header ( "Location: dump_session.php?SESSION_ID=$sessionIDU" );
	}

	if (strtolower($mode)=="remove")
	{
		unset($sessionVars[$var]);
		putSessionVars();
		header ( "Location: dump_session.php?SESSION_ID=$sessionIDU" );
	}

	if (strtolower($mode)=="edit")
	{
		if ( strlen($val)==0 )
			unset($sessionVars[$key]);
		else
			$sessionVars[$key]=$val;

		putSessionVars();
		header ( "Location: dump_session.php?SESSION_ID=$sessionIDU" );
	}

	print "<B>SESSION ID displayed:</B> $sessionID<BR><BR>";
	print "<B>Session variables:</B> <BR>";
	?>

<TABLE WIDTH="80%" ALIGN=LEFT CELLSPACING=1 CELLPADDING=3 BORDER=1>
	<?
	while(list($key,$val)=each($sessionVars))
	{
		print "<FORM ACTION=\"dump_session.php\" METHOD=\"POST\">";
			print "<TR VALIGN=MIDDLE>";
				print "<TD WIDTH=\"100%\" ALIGN=LEFT>";
					print "$key => $val &nbsp; &nbsp; ";
				print "</TD>";

				print "<TD ALIGN=LEFT>";
					print "&nbsp;<A HREF=\"dump_session.php?&SESSION_ID=$sessionIDU&mode=remove&var=".urlencode($key)."\">Delete</A>&nbsp;";
				print "</TD>";

				print "<TD>";
					print "<INPUT TYPE=TEXT NAME=\"val\" SIZE=10 VALUE=\"".htmlspecialchars($val)."\">";
					print "<INPUT TYPE=SUBMIT VALUE=\"OK\">";
					print "<INPUT TYPE=HIDDEN NAME=\"key\" VALUE=\"".htmlspecialchars($key)."\">";
					print "<INPUT TYPE=HIDDEN NAME=\"mode\" VALUE=\"edit\">";
					print "<INPUT TYPE=HIDDEN NAME=\"SESSION_ID\" VALUE=\"$sessionIDU\">";
				print "</TD>";
			print "</TR>\n";
		print "</FORM>";
	}
	?>

	<TR>
		<TD COLSPAN=3 ALIGN=LEFT>
			<A HREF="dump_session.php?mode=clear&SESSION_ID=<? print $sessionIDU; ?>">Clear this session</A><BR>
		</TD>
	</TR>
</TABLE>

<BR><BR>
