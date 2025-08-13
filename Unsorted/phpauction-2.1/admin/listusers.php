<?
	include "loggedin.inc.php";


   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');
   require('../includes/countries.inc.php');
	
	
	//-- Set offset and limit for pagination
	
	$limit = 20;
	if(!$offset) $offset = 0;
	
	
	include "./header.php";
?>
<STYLE type="text/css">
<!--
.unnamed1 {  font: 10pt Tahoma, Arial; color: #000066; text-decoration: none}
-->
</STYLE>

<?    require('../includes/styles.inc.php'); ?>


	
<TABLE WIDTH=650 CELPADDING=0 CELLSPACING=1 BORDER=0 ALIGN="CENTER" CELLPADDING="3">
	<TR> 
		<TD ALIGN=CENTER COLSPAN=5> <BR>
			<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
			<B>
			<? print $MSG_045; ?>
			</B> </font> <BR>
		</TD>
	</TR>
	<?
		$query = "select count(id) as users from PHPAUCTION_users";
		$result = mysql_query($query);
		if(!$result){
			print "$ERR_001<BR>$query<BR>".mysql_error();
			exit;
		}
		$num_usrs = mysql_result($result,0,"users");
		print "<TR><TD COLSPAN=5><FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2><B>
				$num_usrs $MSG_301</B></TD></TR>";
	?>
	<TR BGCOLOR="#999999"> 
		<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
			<B> 
			<? print $MSG_293; ?>
			</B> </FONT> </TD>
		<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
			<B> 
			<? print $MSG_294; ?>
			</B> </FONT> </TD>
		<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
			<B> 
			<? print $MSG_295; ?>
			</B> </FONT> </TD>
		<TD ALIGN=CENTER> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
			<B> 
			<? print $MSG_296; ?>
			</B> </FONT> </TD>
		<TD ALIGN=LEFT> <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR="#000000"> 
			<B> 
			<? print $MSG_297; ?>
			</B> </FONT> </TD>
	<TR> 
		<?
		$query = "select * from PHPAUCTION_users order by nick limit $offset, $limit";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination<BR>$query<BR>".mysql_error();
			exit;
		}
		$num_users = mysql_num_rows($result);
		$i = 0;
		$bgcolor = "#FFFFFF";
		while($i < $num_users){

			if($bgcolor == "#FFFFFF"){
				$bgcolor = "#EEEEEE";
			}else{
				$bgcolor = "#FFFFFF";
			}

			$id = mysql_result($result,$i,"id");
			$nick = mysql_result($result,$i,"nick");
			$name = mysql_result($result,$i,"name");
			$city = mysql_result($result,$i,"country");
			$email = mysql_result($result,$i,"email");
			$suspended = mysql_result($result,$i,"suspended");

			print "<TR BGCOLOR=$bgcolor>
					<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						$nick
						</FONT>
						</TD>
						<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>";
						if($suspended == 1){
							print "<FONT COLOR=red><B>$name</B></FONT>";
						}else{
							print $name;
						}
						print "</FONT>
						</TD>
						<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						".$countries[$city]."
						</FONT>
						</TD>
						<TD>
						<FONT FACE=\"Verdana, Arial, Helvetica, sans-serif\" SIZE=2>
						<A HREF=\"mailto:$email\">$email</A>
						</FONT>
						</TD>
						<TD ALIGN=LEFT>
						<A HREF=\"edituser.php?userid=$id&offset=$offset\" class=\"nounderlined\">$MSG_298</A><BR>
						<A HREF=\"deleteuser.php?id=$id&offset=$offset\" class=\"nounderlined\">$MSG_299</A><BR>
						<A HREF=\"excludeuser.php?id=$id&offset=$offset\" class=\"nounderlined\">";
						if($suspended == 0)
						{
							print $MSG_300;
						}
						else
						{
							print $MSG_310;
						}
						print "</A><BR>
						</TD>
						<TR>";

			$i++;
		}

		print "</TABLE></FONT><BR>";



		//-- Build navigation line
		print "<CENTER>";
		print "<SPAN CLASS=\"navigation\">";
		$num_pages = ceil($num_usrs / $limit);
		$i = 0;
		while($i < $num_pages ){

			$of = ($i * $limit);

			if($of != $offset){
				print "<A HREF=\"listusers.php?offset=$of\" CLASS=\"navigation\">".($i + 1)."</A>";
				if($i != $num_pages) print " | ";
			}else{
				print $i + 1;
				if($i != $num_pages) print " | ";
			}

			$i++;
		}
		print "</SPAN>";



	  ?>
		<BR>
		<BR>
		<CENTER>
			<A HREF="admin.php" CLASS="links">Admin Home</A> 
		</CENTER>
		<BR>
</table> 
  
  <? include "./footer.php"; ?>