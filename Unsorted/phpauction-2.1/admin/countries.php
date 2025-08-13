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
	$DBGSTR = "DBGSTR: ". count($delete). "-" . 
				count($old_countries) . " <br><br>\n";

   include "./rebuild_html.php";
   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');



	/*
	 * When the submit button is pressed (below on the page) on
	 * the first call to countires.php it calls countires.php
	 * again but with a form variable named "act" being sent as true
	 * (see the submit input in the HTML below).  This causes the execution
	 * of the below code.
	 */	
	if ($act)
	{

		/*
		 *	For a description of how the arrays (delete[], new_countries[],
		 *	old_countries[]) are set up see the body of the HTML below.
		 */

		// we use a single SQL query to quickly do ALL our deletes
		$sqlstr = "DELETE FROM PHPAUCTION_countries WHERE ";
		/*
		 * Delete anything marked for deletion in the delete[]
		 * array.
		 */
		// if this is the first country being deleted it don't
		// precede it with an " or " in the SQL string
		$first = 1;
		for ($i = 0; $i < count ($delete); $i++)
		{
			$DBGSTR .= "Deleting[" . $i ."] " . $delete[$i] . "<br>\n";
			if ( ! $first )
			{
				$sqlstr .= " or ";
			}
			else
			{
				$first = 0;
			}	

			$sqlstr .= "country = \"" . $old_countries[$delete[$i]] . "\""; 
		}
		$DBGSTR .= $sqlstr;
		// If the delete array is > 0 in size
		if ( count($delete) )
		{
			$result = mysql_query($sqlstr);
			if ( !$result )
			{
				$TPL_info_err = $ERR_001;
			}
			else
			{
				$TPL_info_err = "";
			}
		}


		/*
		 * Now we update all the countries where old_countries
		 * isn't the same as new_countries (saving ourselves a
		 * lot of queries.
		 */
		for ( $i = 0; $i < count($old_countries); $i++)
		{
			if ( "hey" != "hey")
				$DBGSTR .= "hey != hey";
			if ( $old_countries[$i] != $new_countries[$i])
			{
				$sqlstr = "UPDATE PHPAUCTION_countries SET country = \"" . 
							$new_countries[$i] . "\" WHERE country = \"" .
							$old_countries[$i] . "\"";
				$DBGSTR .= "<br>" . $sqlstr;
				$result = mysql_query($sqlstr);
			}
		}

                /* If a new country was added, insert it into database */
                if ( $new_countries[(count($new_countries) - 1)] ) {
  			$sqlstr = "INSERT INTO PHPAUCTION_countries (country) VALUES ('";
                        $sqlstr .= $new_countries[(count($new_countries) - 1)] . "');";
			$result = mysql_query($sqlstr);
                        if (!$result) {
				$TPL_info_err = $ERR_001;
                        }
                }

	        rebuild_html_file("countries");

	}		
        
        include "../includes/countries.inc.php";

	require("./header.php");
	require('../includes/styles.inc.php'); 
?>
<? print $err_font . $TPL_info_err . "</FONT>"?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_083; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
	<CENTER>
	<FORM NAME=conf ACTION=countries.php METHOD=POST>
	<TABLE WIDTH=400 CELLPADDING=2>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
		<? 
			print $MSG_094; 
			if($$ERR){
				print "<FONT COLOR=red><BR><BR>".$$ERR;
			}else{
				if($$MSG){
					print "<FONT COLOR=red><BR><BR>".$$MSG;
				}else{
					print "<BR><BR>";
				}
			}
		?>
	</TD>
	</TR>

	 <TR>
	 <TD WIDTH=50></TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_087; ?> </B>
	 </TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_088; ?> </B>
	 </TD>
	 </TR>
	
	<?

		$i = 0;
		while ($i < count($countries))
		{
			print "<TR>
					 <TD WIDTH=50></TD>
					 <TD>
					 <INPUT TYPE=hidden NAME=old_countries[] VALUE=\"$countries[$i]\" SIZE=25>
					 <INPUT TYPE=text NAME=new_countries[] VALUE=\"$countries[$i]\" SIZE=25>
					 </TD>
					 <TD>
					 <INPUT TYPE=checkbox NAME=delete[] VALUE=\"$i\">
					 </TD>
					 </TR>";
			$i++;
		}
			
		/*
		 *	We set up several different arrays in the form that get
		 *	passed to the next call to countries.php.  The old_countries
		 *	array will hold the unchanged, initial countries (all of them).
		 *	new_countries will also hold every country, but it will hold the
		 *	modified values (if someone writes in the text field).  The
		 *	delete[] array will hold ONLY deleted countries (ie its size
		 *	will be the number of deleted countries).  It is an array of the
		 *	indices of deleted countries.
		while($i < mysql_num_rows($result)) 
		{
			$countries = mysql_fetch_row($result);
			print "<TR>
					 <TD WIDTH=50></TD>
					 <TD>
					 <INPUT TYPE=hidden NAME=old_countries[] VALUE=\"$countries[0]\" SIZE=25>
					 <INPUT TYPE=text NAME=new_countries[] VALUE=\"$countries[0]\" SIZE=25>
					 </TD>
					 <TD>
					 <INPUT TYPE=checkbox NAME=delete[] VALUE=\"$i\">
					 </TD>
					 </TR>";
			$i++;
		}
		*/
			print "<TR>
					 <TD WIDTH=50>
					 $std_font Add
					 </TD>
					 <TD>
					 <INPUT TYPE=text NAME=new_countries[] SIZE=25>
					 </TD>
					 <TD>
					 </TD>
					 </TR>";
		
	?>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_089; ?>">
	</TD>
	</TR>
	<TR>
	<TD WIDTH=50></TD>
	<TD>

	</TD>
	</TR>
	</TABLE>
	</FORM>
	<BR><BR>

	<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
	<A HREF="admin.php" CLASS="links">Admin Home</A>
	</FONT>
	<BR><BR>

</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
</TD>
</TR>
</TABLE>
<? print $err_font . $TPL_info_err . "</FONT>"?>
<? require("./footer.php"); ?>
