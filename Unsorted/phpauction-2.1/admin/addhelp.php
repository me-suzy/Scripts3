<?
	include "loggedin.inc.php";

   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');
  
	
	//-- Set offset and limit for pagination
	
	$limit = 20;
	if(!$offset) $offset = 0;
	
	if ($act == "save") {
	   $sqlstr = "INSERT INTO PHPAUCTION_help (topic,helptext) VALUES ('";
           $sqlstr .= $topic . "','" . $helptext . "');";
	   $result = mysql_query($sqlstr);
                  if (!$result) {
			$TPL_error = $ERR_001;
		   } else {

		      	   Header("Location: listhelp.php");
		   }
	}

	if ($act == "update") {
	   $sqlstr = "UPDATE PHPAUCTION_help set helptext = '";
           $sqlstr .= $helptext . "' WHERE topic = '";
           $sqlstr .= $topic . "';";
	   $result = mysql_query($sqlstr);
                  if (!$result) {
			$TPL_error = $ERR_001;
		   } else {

		      	   Header("Location: listhelp.php");
		   }
	}
	include "./header.php";
	require('../includes/styles.inc.php');
?>
<STYLE type="text/css">
<!--
.unnamed1 {  font: 10pt Tahoma, Arial; color: #000066; text-decoration: none}
-->
</STYLE>

	
<TABLE WIDTH=650 CELPADDING=0 CELLSPACING=0 BORDER=0 ALIGN="CENTER">
	<TR>
	 <TD ALIGN=CENTER COLSPAN=5>
		<BR>
		<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4">
		<B><? print $MSG_912; ?></B>
		</FONT>
		<BR>
	 </TD>
	</TR>	
	<?
		$query = "select count(topic) as topics from PHPAUCTION_help";
		$result = mysql_query($query);
		if(!$result){
			print "$ERR_001<BR>$query<BR>".mysql_error();
			exit;
		}
		if (mysql_num_rows($result)) {
			$num_usrs = mysql_result($result,0,"topics");
		} else {
			$num_usrs = 0;
		}
		print "<TR><TD COLSPAN=3><FONT FACE=\"Verdana, Arial,Helvetica, sans-serif\" SIZE=2><B>
				$num_usrs $MSG_913</B></TD></TR>";
	?>

	  <?
			
?>
<TR BGCOLOR="#EEEEEE">
<TD>
 <BR>
<?
	if ($TPL_error) {
		print $err_font . $TPL_error . "</font>";
 		include "./footer.php";
		exit;
	}
        if ($act == "edit") {
		$query = "select topic,helptext from PHPAUCTION_help where topic='" . $topic . "';";
		$result = mysql_query($query);
		if(!$result){
			print "$ERR_001<BR>$query<BR>".mysql_error();
	 		include "./footer.php";
			exit;
		}
                $helptext = mysql_result($result,0,"helptext");

?>
	   <FORM ACTION="addhelp.php" METHOD="POST">	
           <INPUT TYPE="hidden" NAME="act" VALUE="update"> 
<?
	} else { 
           $topic = "";
           $helptext = "";
?>
	   <FORM ACTION="addhelp.php" METHOD="POST">	
           <INPUT TYPE="hidden" NAME="act" VALUE="save"> 
<?
	}
?>
 <FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE=2>
 <CENTER><B><? print $MSG_914; ?></B> <INPUT TYPE="text" NAME="topic" VALUE="<? print $topic ?>"></CENTER><br>
 <CENTER>
				<B>
				<? print $MSG_915; ?>
				</B> <BR>
				<TEXTAREA NAME="helptext" ROWS=20 COLS=50><? print $helptext ?></TEXTAREA></CENTER>
 <CENTER><INPUT type="submit" name="submit"></CENTER>
 </FONT>
 <BR>
</TD>
</TR>
<?
		print "</TABLE></FONT><BR>";
	  ?>
	  <CENTER>
		<A HREF="admin.php" CLASS="links">Admin Home</A>
		</CENTER>
	  <BR>

</TABLE>

  
  
  <? include "./footer.php"; ?>