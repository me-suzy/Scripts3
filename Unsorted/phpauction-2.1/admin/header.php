<?
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

   session_name("PHPAUCTIONADMIN");
   session_start();

   include "../includes/dates.inc.php";
	

?>
<TITLE>::PHPAUCTION ADMINISTRATION BACK-END::</TITLE>
</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#08428C" LINK="#08428C" VLINK="#08428C" ALINK="#08428C" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#EEEEEE" ALIGN="CENTER">
	<TR BGCOLOR="#336699"> 
		<TD WIDTH="54%" VALIGN=MIDDLE BGCOLOR="#336699"><FONT SIZE="6" COLOR="#CCCCCC" FACE="Tahoma, Verdana"><B>PHPAuction 
			2.1 </B></FONT> </TD>
		<TD WIDTH="46%" VALIGN="BOTTOM" ALIGN=center> 
			<DIV ALIGN="RIGHT"><FONT FACE="Verdana,Arial,Helvetica" SIZE=4 COLOR="#000066"> 
				<B><FONT COLOR="#CCCCCC" SIZE="2">ADMINISTRATION BACK-END</FONT></B></FONT> 
			</DIV>
		</TD>
	</TR>
	<TR BGCOLOR="#C8D6E6"> 
		<TD VALIGN=TOP HEIGHT="17"><FONT FACE=Verdana SIZE=2 COLOR=#000000> 
			<?=$MSG_592?>
			<B> 
			<?=$HTTP_SESSION_VARS[PHPAUCTION_ADMIN_USER]?>
			</B></FONT></TD>
		<TD VALIGN=TOP HEIGHT="17" ALIGN=right><A HREF="admin.php"><FONT FACE="Tahoma, Verdana" SIZE="2">Home</FONT></A><FONT FACE="Tahoma, Verdana" SIZE="2"> 
			| <A HREF="logout.php">Logout</A></FONT></TD>
	</TR>
</TABLE>
