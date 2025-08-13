<?#//v.1.0.0
	session_start();
	
	if($HTTP_POST_VARS[prev] == "<< PREV")
	{
		Header("Location: upgrade21_pro10.php");
		exit;
	}
	elseif($HTTP_POST_VARS[next] == "NEXT >>")
	{
		#// 
		$CURRENT_PATH = $HTTP_POST_VARS[CURRENT_PATH];
		$NEW_PATH = $HTTP_POST_VARS[NEW_PATH];
		$DB = $HTTP_POST_VARS[DB]; 
		#//
		if(empty($HTTP_POST_VARS[CURRENT_PATH]) || 
		   empty($HTTP_POST_VARS[NEW_PATH]))
		{
			$ERR = "Please, provide both your current Phpauction 2.1 installation path and you Phpauction Pro installation path";
		}
		else
		{
			#// Are data correct?
			if(!file_exists($CURRENT_PATH) || !is_dir($CURRENT_PATH))
			{
				$ERR = "*ERROR*: Directory <FONT FACE=COURIER>$CURRENT_PATH</FONT> does not exist or is not a directory";
			}
			elseif(!file_exists($NEW_PATH) || !is_dir($NEW_PATH))
			{
				$ERR = "*ERROR*: Directory <FONT FACE=COURIER>$NEW_PATH</FONT> does not exist or is not a directory";
				$DIR = $HTTP_POST_VARS[newpath];
			}
			elseif(!file_exists($CURRENT_PATH."/includes/passwd.inc.php"))
			{
				$ERR = "*ERROR*: file <FONT FACE=COURIER>".$CURRENT_PATH."/includes/passwd.inc.php</FONT> does not exists.<BR>
					    Please check the Phpauction 2.1 path below is correct";
			} 
			elseif(!file_exists($CURRENT_PATH."/includes/config.inc.php"))
			{
				$ERR = "*ERROR*: file <FONT FACE=COURIER>".$CURRENT_PATH."/includes/config.inc.php</FONT> does not exists.<BR>
					    Please check the Phpauction 2.1 path below is correct";
			} 
			elseif(!file_exists($NEW_PATH."/includes/passwd.inc.php"))
			{
				$ERR = "*ERROR*: file <FONT FACE=COURIER>".$NEW_PATH."/includes/passwd.inc.php</FONT> does not exists.<BR>
					    Please check the Phpauction Pro path below is correct";
			} 
			elseif(!file_exists($NEW_PATH."/includes/config.inc.php"))
			{
				$ERR = "*ERROR*: file <FONT FACE=COURIER>".$NEW_PATH."/includes/config.inc.php</FONT> does not exists.<BR>
					    Please check the Phpauction Pro path below is correct";
			} 
			else
			{
				#// Check file permission fir the new installation
				clearstatcache();
				if(!is_writable($NEW_PATH."/includes/passwd.inc.php"))
				{
					$ERR = "*ERROR*: File <FONT FACE=COURIER>$NEW_PATH/includes/passwd.inc.php</FONT> is not writable";
				}
				elseif(!is_writable($NEW_PATH."/includes/config.inc.php"))
				{
					$ERR = $ERR = "*ERROR*: File <FONT FACE=COURIER>$NEW_PATH/includes/config.inc.php</FONT> is not writable";
				}
				else
				{
					#//  Save obtained data in session vars
					session_register(CURRENT_PATH);
					session_register(NEW_PATH);
					session_register(DB);
				
					#// Next Step
					Header("Location: upgrade21_pro10_2.php");
					exit;
				}
			}
		}
		
	}
	
	if(empty($HTTP_POST_VARS[action]))
	{
		#// Get current directory
		$NEW_PATH = getcwd();
		$NEW_PATH = str_replace("/admin","",$NEW_PATH);
	}
	elseif(isset($HTTP_SESSON_VARS[CURRENT_PATH]))
	{
		$NEW_PATH = $HTTP_SESSION_VARS[NEW_PATH];
		$CURRENT_PATH = $HTTP_SESSION_VARS[CURRENT_PATH];
		$DB = $HTTP_SESSION_VARS[DB];
	}
	
?>	
<html>
<head>
<title>Phpauction Upgrade Script</title>
</head>
<body BGCOLOR="brown1" TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<TABLE ALIGN=CENTER CELLPADDING=1 CELLSPACING=0 BORDER=0 BGCOLOR=white WIDTH=700>
<TR>
	<TD>
		<TABLE WIDTH=100% CELLPADDING=4 CELLSPACING=0 BORDER=0 BGCOLOR=white>
		<TR>
			<TD>
			<IMG SRC=images/logo.gif>
			&nbsp;&nbsp;
			<H1>Phpauction Pro Upgrade Script - STEP 1</H1>
			<BR>
			<BR>
			<FONT FACE=Helvetica SIZE=3>
			Please provide the requested information below and press the "Proceed" button
			<BR><BR>
			<?
				if(isset($ERR))
				{
			?>
				<FONT COLOR=RED><B><?=$ERR?></B></FONT>
			<?
				}
			?>
			<FORM ACTION="<?=basename($PHP_SELF)?>" METHOD=post>
			Path to your current (Phpauction 2.1) installation [NOTE: no ending slash (/) required]<BR>
			<INPUT TYPE=text SIZE=40 NAME=CURRENT_PATH VALUE=<?=$CURRENT_PATH?>><BR>
			Path to your new (Phpauction Pro) installation [NOTE: no ending slash (/) required]<BR>
			<INPUT TYPE=text SIZE=40 NAME=NEW_PATH VALUE="<?=$NEW_PATH?>"><BR>
			<BR>Database<BR>
			<INPUT TYPE=radio NAME=DB VALUE=current <?if($DB == "current" || empty($DB)) print " checked";?>>&nbsp;Use your Phpauction 2.1 existing database<BR>
			<INPUT TYPE=radio NAME=DB VALUE=new <?if($DB == "new") print " checked";?>>&nbsp;Create a new database 
			<BR><BR>
			<CENTER>
			<INPUT TYPE=hidden NAME=action VALUE=process>
			<INPUT TYPE=submit NAME=prev VALUE="<< PREV">
			<INPUT TYPE=submit NAME=next VALUE="NEXT >>">
			</FORM>
			<BR>
			Copyright &copy; 2002, Phpauction.org
			</TD>
		</TR>
		</TABLE>
	</TD>
</TR>
</TABLE>
</body>
</html>