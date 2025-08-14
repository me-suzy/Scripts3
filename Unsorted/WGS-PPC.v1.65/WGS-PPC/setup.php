<?
	$Path="./config/";
	$INC="./";
	Include($Path.'mysql_config.php');
	Include($Path.'dbstructure.php');
	Include($Path.'db_info.php');
	Include($Path.'site_url.php');
	Include($Path.'admin_pw.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Ettica-PPC Setup Tool</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="main/css/asppc_styles.css" type="text/css">
</head>
<body><center>
    <table class="tborder" cellspacing="0" cellpadding="5" bgcolor="#FFFBF7">
	<tr>
	<td valign="bottom" align="right" colspan="2" bgcolor="#934A09"><img src="main/images/logo.jpg" width="432" height="149"></td>
	</tr>
		<tr>
			<td colspan="2" align="center"><h2>Ettica-PPC Search Engine Setup</h2>
			</td>
		</tr>
      <tr> 
      <td align="center" valign="top">


<?
	
	//  £àã¦ ¥â ¡¨­ à­ë© ä ©«
	Function LoadFile($FileName)
	{
	If(!($fh=@FOpen($FileName,'r')))
		Return False;
	$Res='';
	While(!FEof($fh))
		$Res.=FRead($fh,256*256);
	FClose($fh);
	Return $Res;
	}
	
	// ®åà ­ï¥â â¥ªáâ®¢ë© ä ©«
	Function SaveText($FileName,$Data)
	{
	If(!($fh=@FOpen($FileName,'w')))
		Return False;
	$Res=FWrite($fh,$Data)==StrLen($Data);
	FClose($fh);
	Return $Res;
	}
	
	Function Reg($Name,$Def)
	{
	Global $HTTP_POST_VARS;
	$Res=$Def;
	If(IsSet($HTTP_POST_VARS[$Name]))
		$Res=StripSlashes($HTTP_POST_VARS[$Name]);
	$GLOBALS[$Name]=$Res;
	$GLOBALS['Str'.$Name]=HtmlSpecialChars((String)$Res);
	$GLOBALS['Sl'.$Name]=AddSlashes((String)$Res);
	}
	
	function InitDB(){
		global $INSERT_SETTINGS, $DBSTRUCTURE;
		$error = Array();
		foreach($DBSTRUCTURE as $table=>$struct){
			$Q = "create table $table ( $struct )";
			if(!mysql_query($Q)) $error[] = mysql_error();
		}
		foreach($INSERT_SETTINGS as $table=>$values){
			$Q = "insert into $table values($values)";
			if(!mysql_query($Q)) $error[] = mysql_error();
		}
		return $error;
	}
	
	Reg('Step'     ,0);
	Reg('Host'     ,$DBHost     );
	Reg('Login'    ,$DBLogin    );
	Reg('Password' ,$DBPassword );
	Reg('SiteUrl'  ,$SITE_URL   );
	Reg('Database' ,$DBName     );
	Reg('cbcr'     ,False       );
	Reg('cbcl'     ,False       );
	Reg('APassword' ,$ADMIN_PW   );
	Reg('ALogin'    ,$ADMIN_LOGIN);
	$Error=Array();
	
	If($Step)
		{
		$Res=@MySql_Connect($Host,$Login,$Password);
		If(!LoadFile($SlSiteUrl.'setup.php?Step=0'))
			$Error[]='URL "'.$SlSiteUrl.'" is incorrect';
		If(!$Res)
			$Error[]='Can not connect to host "'.$Host.'"';
		Else
			{
			If($cbcl)
				{
//				If(!$z)
//					$Error[]='Database "'.$Database.'" does not exits';
//				Else
					If(!mysql_drop_db($Database))
						$Error[]='Can not drop database "'.$Database.'"';
				}
			$z=@MySql_SelectDb($Database);
			If($cbcr)
				{
				If($z)
					$Error[]='Database "'.$Database.'" already exits';
				Else
					If(!mysql_create_db($Database))
						$Error[]='Can not create database "'.$Database.'"';
				}
			Else
				{
				If(!$z)
					$Error[]='Database "'.$Database.'" not found';
				}
			}
		If($Error)
			$Step=0;
		}
	If($Step==2)
		{
		$Tmp=InitDB();
		If($Tmp)
			$Error[]=Implode("<br>\n",$Tmp);
		}
	
		SaveText($Path.'db_info.php',
'<?
	$DBLogin    = "'.$SlLogin    .'";
	$DBPassword = "'.$SlPassword .'";
	$DBHost     = "'.$SlHost     .'";
	$DBName     = "'.$SlDatabase .'";
?>');
		chmod ($Path.'db_info.php', 0777);
		SaveText($Path.'site_url.php',
'<? $SITE_URL = "'.$SlSiteUrl.'"; ?>');
		chmod ($Path.'site_url.php', 0777);

		SaveText($Path.'admin_pw.php',
'<?
	$ADMIN_PW    = "'.$SlAPassword.'";
	$ADMIN_LOGIN = "'.$SlALogin   .'";
?>');
		chmod ($Path.'admin_pw.php', 0777);

		chmod ('./data', 0777);
		chmod ('./data/banners', 0777);
		chmod ('./data/logos', 0777);
	
	If($Error)
		{
		$Error=Implode("<br>\n",$Error);
?>
<b>
<font color="#FF0000">
  Errors: <br>
  <?=$Error; ?><br><br>
</font>
</b>
<?
		}
	
	Include('./step'.$Step.'.php');
?>


		</td>
      </tr>
	<tr><td>If you have any questions, comments, bug reports write us at <a href="mailto:ppc@ettica.com">ppc@ettica.com</a><br>or you can also can get support on our site: <a href="http://www.ettica.com">http://www.ettica.com</a></td>
      </tr>
    </table><br>
© 2002 <a href="http://www.ettica.com/" target="_blank">Ettica.com</a>
</center>
</body>
</html>
