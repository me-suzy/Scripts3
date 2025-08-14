<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

	include "../includes/config.inc.php";
	include "../includes/messages.inc.php";
	include "../includes/countries.inc.php";
	include "../includes/classes.inc.php";
	
	$username = $name;


	//-- Data check
	if(!$id){
		header("Location: listusers.php");
		exit;
	}
	
	if($action){
	
		if ($name && $nick && $password && $email && $address && $country && $zip && $phone) 
		{

			$datum = new datum;
			$datum->setRawdate($birthdate);
			$birthdate =$datum->db_date;

			if (strlen($nick)<6) 
			{
				$ERR = "ERR_010";
			}
			else if (strlen ($password)<6) 
			{
				$ERR = "ERR_011";
			}
			else if (strlen($email)<5)		//Primitive mail check 
			{
				$ERR = "ERR_110";
			}
			else if ($datum->error) //Birthdate check
			{
				$ERR = "ERR_043";
			}
			else if (strlen($zip)<5) //Primitive zip check
			{
				$ERR = "ERR_616";
			}
			else if (strlen($phone)<3) //Primitive phone check
			{
				$ERR = "ERR_617";
			}
			else 
			{

				$sql="UPDATE ".$dbfix."_users SET name=\"".			AddSlashes($name)
								 ."\", nick=\"".				AddSlashes($nick)
								 ."\", password=\"".			AddSlashes($password)
								 ."\", email=\"".				AddSlashes($email)
								 ."\", address=\"".			AddSlashes($address)
								 ."\", city=\"".				AddSlashes($city)
								 ."\", prov=\"".				AddSlashes($prov)
								 ."\", country=\"".			AddSlashes($country)
								 ."\", zip=\"".				AddSlashes($zip)
								 ."\", phone=\"".				AddSlashes($phone)
								 ."\", birthdate=".			AddSlashes($birthdate)
								 ." WHERE id='".				AddSlashes($id)."'";
				$res=mysql_query ($sql);
				
				$updated = 1;
								

			}
		}
		else 
		{
			$ERR = "ERR_112";
		}	
	
	}
	

	if(!$action || ($action && $updated)){

		$query = "select * from ".$dbfix."_users where id=\"$id\"";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}

		$username = mysql_result($result,0,"name");

		$nick = mysql_result($result,0,"nick");
		$password = mysql_result($result,0,"password");
		$email = mysql_result($result,0,"email");
		$address = mysql_result($result,0,"address");
                $city = mysql_result($result,0,"city");
                $prov = mysql_result($result,0,"prov");
		$suspended = mysql_result($result,0,"suspended");
		
		$country = mysql_result($result,0,"country");
  $country_list="";
		$country_query = "SELECT country_id, country FROM ".$dbfix."_countries ORDER BY country";
                $res_c = mysql_query($country_query);
                if(!$res_c){
                        print "Database access error: abnormal termination".mysql_error();
                        exit;
                }
                for ($i = 0; $i < mysql_num_rows($res_c); $i++)
                {
                        $row = mysql_fetch_row($res_c);
                        // Append to the list
                        $country_list .= "<option value=\"$row[0]\"";
                        if ($row[0] == $country)
                        {
                                $country_list .= " selected ";
                        }
                        $country_list .= ">$row[1]</option>\n";
                };
		
		$prov = mysql_result($result,0,"prov");
		$zip = mysql_result($result,0,"zip");
		
		$datum = new datum;
		$datum->setdbdate(mysql_result($result,0,"birthdate"));
		$birthdate = $datum->de_date;

		$phone = mysql_result($result,0,"phone");

		$rate_num = mysql_result($result,0,"rate_num");
		$rate_sum = mysql_result($result,0,"rate_sum");
		if ($rate_num) 
		{
			$rate = round($rate_sum / $rate_num);
		}
		else 
		{
			$rate=0;
		}

	}

?>

<HTML>
<HEAD>

<?    require('../includes/styles.inc.php'); ?>

<TITLE></TITLE>

</HEAD>

<? include "./header.php"; ?>
  
<BODY bgcolor="#FFFFFF">
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">
<TR>
<TD>
	<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
	<TR>
	 <TD ALIGN=CENTER COLSPAN=5>
		<BR>
		<B><? print $tlt_font.$MSG_511; ?></B>
		<BR>
	 </TD>
	</TR>	

	<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5">

	<?
	if($ERR || $updated){
				print "<TR>
						<TD>
						</TD>
						<TD WIDTH=486>
						<FONT FACE=\"Verdana,Arial, Helvetica\" SIZE=2 COLOR=red>";
						if($$ERR) print $$ERR;
						if($updated) print "Users data updated";					
						print "</TD>
						</TR>";
	}
	?>
<FORM NAME=details ACTION="edituser.php" METHOD="POST">

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<?print $std_font; ?>
		<? print "$MSG_302 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=name SIZE=40 MAXLENGTH=255 VALUE="<? print $username; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_003 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=nick SIZE=20 MAXLENGTH=20  VALUE="<? echo $nick; ?>">
		<? print $std_font; ?>
		</FONT>
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print " $MSG_004 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=password SIZE=20 MAXLENGTH=20 VALUE="<? echo $password; ?>">
		</FONT>
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204"  VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_303 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=email SIZE=50 MAXLENGTH=50 VALUE="<? echo $email; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204"  VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_252 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=birthdate SIZE=10 MAXLENGTH=10 VALUE="<? echo $birthdate; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_009 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=address SIZE=40 MAXLENGTH=255 VALUE="<? echo $address; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_010 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=city SIZE=40 MAXLENGTH=255 VALUE="<?echo $city; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_011 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=prov SIZE=40 MAXLENGTH=255 VALUE="<?echo $prov; ?>">
	  </TD>
	</TR>


	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_014 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<SELECT NAME=country>
		<OPTION VALUE="">	</OPTION>

		<?  echo $country_list; ?>

		</SELECT>
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_012 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=zip SIZE=15 MAXLENGTH=15 VALUE="<? echo $zip; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_013 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=phone SIZE=40 MAXLENGTH=40 VALUE="<? echo $phone; ?>">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_222"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		 <? if(!$rate) $rate=0; ?>
		<IMG src="../images/estrella_<? echo $rate; ?>.gif">
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_300"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<? 
			print $std_font; 
			if($suspended == 0)
				print "$MSG_029";
			else
				print "$MSG_030";

		?>

	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204">&nbsp;</TD>
	  <TD WIDTH="486">
		</FONT>
		<BR><BR>
		 <INPUT TYPE=submit NAME=act VALUE="<? print $MSG_089; ?>">
		</TD>
	</TR>
	</TABLE>
		<INPUT type="hidden" NAME="id" VALUE="<? echo $id; ?>">
		<INPUT type="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
		<INPUT type="hidden" NAME="action" VALUE="update">
	</FORM>
	</center>
		 <BR>
		  <BR>
		  <CENTER>
		  <FONT face="Tahoma, Arial" size="2"><A HREF="admin.php" CLASS="navigation">Admin home</A> | 
		  <FONT face="Tahoma, Arial" size="2"><A HREF="listusers.php?offset=<? print $offset; ?>" CLASS="navigation">Users list</A></FONT>
	  </CENTER>
	</TD>
	</TR>
	</TABLE>


</TD>
</TR>
</TABLE>
  
  <!-- Closing external table (header.php) -->
  </TD>
  </TR>
</TABLE>
  
  
  <? include "./footer.php"; ?>
  
  
  </BODY>
  </HTML>
