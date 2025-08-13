<?#//v.1.0.0
	session_start();
	
	if($HTTP_POST_VARS[prev] == "<< PREV")
	{
		Header("Location: upgrade21_pro10_2.php");
		exit;
	}
	elseif($HTTP_POST_VARS[next] == "UPGRADE")
	{
		Header("Location:upgrade21_pro10_4.php");
		exit;
	}
	
?>	
<html>
<head>
<title>Phpauction Upgrade Script</title>
</head>
<body BGCOLOR="brown1" TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<TABLE ALIGN=CENTER CELLPADDING=1 CELLSPACING=0 BORDER=0 BGCOLOR=white WIDTH=700>
<FORM ACTION="<?=basename($PHP_SELF)?>" METHOD=post>
<TR>
	<TD>
		<TABLE WIDTH=100% CELLPADDING=4 CELLSPACING=0 BORDER=0 BGCOLOR=white>
		<TR>
			<TD>
			<IMG SRC=images/logo.gif>
			&nbsp;&nbsp;
			<H1>Phpauction Pro Upgrade Script - STEP 4
			</H1>
			<BR>
			<BR>
			<?include "upgrade21_pro10_createtables.inc.php";?>
			<?include "upgrade21_pro10_populatetables.inc.php";?>
			<BR><BR>
			Copying remaining files....<BR>
			<? 
				#// Copy files
				exec("cp $HTTP_SESSION_VARS[CURRENT_PATH]/uploaded/*.* $HTTP_SESSION_VARS[NEW_PATH]/uploaded/"); 
				exec("cp $HTTP_SESSION_VARS[CURRENT_PATH]/includescategories_select_box.inc.php/*.* $HTTP_SESSION_VARS[NEW_PATH]/includes/"); 
				
				print "Editing $HTTP_SESSION_VARS[NEW_PATH]/config.inc.php<BR>";
				#// Write into config.inc.php and passwd.inc.php	
				include $HTTP_SESSION_VARS[CURRENT_PATH]."/includes/config.inc.php";
				$FILE = file($HTTP_SESSION_VARS[NEW_PATH]."/includes/config.inc.php");
				$fp = fopen($HTTP_SESSION_VARS[NEW_PATH]."/includes/config.inc.php", "w+");
				$i = 0;
				while($i < count($FILE))
				{
	                if(strpos($FILE[$i],"\$image_upload_path = ") && !strpos($FILE[$i],"#\$image_upload_path = "))
	                {
                         $FILE[$i] = "\t\$image_upload_path = \"".$HTTP_SESSION_VARS[NEW_PATH]."/uploaded/\";\n"; 
                    }
	                if(strpos($FILE[$i],"\$MD5_PREFIX"))
	                {
                         $FILE[$i] = "\t\$MD5_PREFIX = \"$MD5_PREFIX\";\n";
                    }
                    fputs($fp,$FILE[$i]); 
                    $i++;
				}
                fclose($fp);					

				print "Editing $HTTP_SESSION_VARS[NEW_PATH]/passwd.inc.php<BR>";
				include $HTTP_SESSION_VARS[CURRENT_PATH]."/includes/passwd.inc.php";
				$FILE = file($HTTP_SESSION_VARS[NEW_PATH]."/includes/passwd.inc.php");
				$fp = fopen($HTTP_SESSION_VARS[NEW_PATH]."/includes/passwd.inc.php", "w+");

				$LINE = "<?\n";
				fputs($fp,$LINE);
				
				$LINE = "\$DbHost = \"$DB_HOST\";\n";
				fputs($fp,$LINE);
				
				$LINE = "\$DbUser = \"$DB_USER\";\n";
				fputs($fp,$LINE);
				
				$LINE = "\$DbDatabase = \"$DB_NAME\";\n";
				fputs($fp,$LINE);
				
				$LINE = "\$DbPassword = \"$DB_PASSWORD\";\n";
				fputs($fp,$LINE);

				$LINE = "?>\n";
				fputs($fp,$LINE);
				
                fclose($fp);					
			?>
			<BR>
			Upgrade completed.
			<BR>
			You can now access you <A HREF=admin.php>administration back-end</A> to review the new installation's settings.
			<BR>
			Copyright &copy; 2002, Phpauction.org			
			</TD>
		</TR>
		</TABLE>
	</TD>
</TR>
</FORM>
</TABLE>
</body>
</html>