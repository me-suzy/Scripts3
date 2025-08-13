<?

	include "loggedin.inc.php";

	include "../includes/config.inc.php";
	include "../includes/messages.inc.php";
	include "../includes/countries.inc.php";

	$username = $name;


	//-- Data check
	if(empty($userid))
	{
		header("Location: listusers.php");
		exit;
	}
	
	if($HTTP_POST_VARS[action] == "update")
	{
	
		if ($name && $email && $address && $country && $zip && $phone) 
		{

			$DATE = explode("/",$birthdate);
			$birth_day 		= $DATE[0];
			$birth_month 	= $DATE[1];
			$birth_year 	= $DATE[2];

			if(strlen($birth_year) == 2){
				$birth_year = "19".$birth_year;
			}


			if ($HTTP_POST_VARS[password] != $HTTP_POST_VARS[repeat_password]) 
			{
				$ERR = "ERR_006";
			}
			else if (strlen($email)<5)		//Primitive mail check 
			{
				$ERR = "ERR_110";
			}
			elseif(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$email))
			{
				$ERR = "ERR_008";
			}
			else if (!ereg("^[0-9]{2}/[0-9]{2}/[0-9]{4}$",$birthdate) &&
					 !ereg("^[0-9]{2}/[0-9]{2}/[0-9]{2}$",$birthdate)) //Birthdate check
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

				$birthdate = "$birth_year"."$birth_month"."$birth_day";

				$sql="UPDATE PHPAUCTION_users SET name=\"".			AddSlashes($name)
								 ."\", email=\"".				AddSlashes($email)
								 ."\", address=\"".			AddSlashes($address)
								 ."\", city=\"".				AddSlashes($city)
								 ."\", prov=\"".				AddSlashes($prov)
								 ."\", country=\"".			AddSlashes($country)
								 ."\", zip=\"".				AddSlashes($zip)
								 ."\", phone=\"".				AddSlashes($phone)
								 ."\", birthdate=".			AddSlashes($birthdate);
				if (strlen($HTTP_POST_VARS[password]) > 0) { 
					$sql .=  ", password=\"". md5($MD5_PREFIX.AddSlashes($password)) . "\"";
				}

				$sql .=	" WHERE id='".				AddSlashes($userid)."'";
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

		$query = "select * from PHPAUCTION_users where id=\"$userid\"";
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
		while (list ($code, $descr) = each ($countries))
		{
			$country_list .= "<option value=\"$code\"";
			if ($code == $country)
			{
				$country_list .= " selected";
			}
			$country_list .= ">$descr</option>\n";
		}
		
		session_name($SESSION_NAME);
		session_register("country_list");
		
		$prov = mysql_result($result,0,"prov");
		$zip = mysql_result($result,0,"zip");

		$birthdate = mysql_result($result,0,"birthdate");
		$birth_day = substr($birthdate,6,2);
		$birth_month = substr($birthdate,4,2);
		$birth_year = substr($birthdate,0,4);
		$birthdate = "$birth_day/$birth_month/$birth_year";

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


	require "./header.php";
	require('../includes/styles.inc.php'); 
?> <BR>
<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"> <B>
<center><? print $MSG_511; ?>
</center>
</B> </FONT> <BR>
<TR>
	<td>
		<TR>
			<TD ALIGN=CENTER COLSPAN=5>&nbsp;</TD>
	</TR>	

	    <TABLE WIDTH="650" BORDER="0" CELLPADDING="5" ALIGN="CENTER">
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
		<? print $std_font; ?>
		<B><? echo $nick; ?></B>
		</FONT>
	  </TD>
	</TR>

	<TR BGCOLOR="#EEEEEE">
	  <TD WIDTH="204" VALIGN="top" >&nbsp;
	  	
	  </td>
	  <TD WIDTH="486">
	  	<? print $std_font; ?>
		<? print "$MSG_243" ?>
	  </TD>
	</TR>

	<TR BGCOLOR="#EEEEEE">
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print " $MSG_004 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=password SIZE=20 MAXLENGTH=20>
		</FONT>
	  </TD>
	</TR>

	<TR BGCOLOR="#EEEEEE">
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print " $MSG_004 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<INPUT TYPE=text NAME=repeat_password SIZE=20 MAXLENGTH=20>
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
		<INPUT type="hidden" NAME="userid" VALUE="<?=$userid; ?>">
		<INPUT type="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
		<INPUT type="hidden" NAME="action" VALUE="update">
	</FORM>
	</TABLE>
	

		 <BR>
		  <BR>
		  <CENTER>
		  <FONT face="Tahoma, Arial" size="2"><A HREF="admin.php" CLASS="navigation">Admin home</A> | 
		  <A HREF="listusers.php?offset=<? print $offset; ?>" CLASS="navigation">Users list</A></FONT>
	  </CENTER>




  
  
  <? include "./footer.php"; ?>