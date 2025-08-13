<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	if ( isset( $HTTP_SESSION_VARS['session_setup'] ) ) { $session_setup = $HTTP_SESSION_VARS['session_setup'] ; } else { HEADER( "location: index.php" ) ; exit ; }
	if ( !file_exists( "../web/$session_setup[login]/$session_setup[login]-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: index.php" ) ;
		exit ;
	}
	include_once("../web/conf-init.php");
	include_once("../web/$session_setup[login]/$session_setup[login]-conf-init.php") ;
	include_once("../lang_packs/$LANG_PACK.php") ;
	include_once("../system.php") ;
	include_once("../API/sql.php") ;
	include_once("../API/Users/get.php") ;
?>
<?
	// initialize
	$action = "" ;
	$deptid = 0 ;

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$textbox_width = "85" ;
	else
		$textbox_width = "55" ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> Generate Live Support HTML Code </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A">
<table cellspacing=0 cellpadding=0 border=0 width="98%">
<tr>
	<td valign="top"><span class="basetxt">
		<?
			if ( file_exists( "../web/$session_setup[login]/$LOGO" ) && $LOGO )
				$logo = "../web/$session_setup[login]/$LOGO" ;
			else if ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP )
				$logo = "../web/$LOGO_ASP" ;
			else
				$logo = "../pics/phplive_logo.gif" ;
		?>
		<img src="<? echo $logo ?>">
		<br>
		<b><big>Generate Live Support HTML Code</big></b> - <a href="JavaScript:history.go(-1)">previous</a>
		<br>
		<br>
		<img src="../pics/icons/world.gif" width="32" height="32" border=0 alt="" align="left"> Generate your Support HTML Code to place your website.  You can generate a code that displays ALL your created departments OR you can generate code JUST for a particular department.  If you generate code for a specific department, the user will not see the department drop-down menu.  They will only be prompted for their name.
		<p>
		<ul>

			<? if ( $action == "generate" ): ?>
			<li> Simply copy the below code into your HTML.  <b>Below code is for your HTML pages.</b><br>
			<font color="#FF0000">
			* please keep it EXACTLY like the way it shows and make sure your edit program does not<br>
			break up the long lines, or you WILL get errors or image will not show.</font>
			<br>
<form>
<textarea cols="<? echo $textbox_width ?>" rows="8" class="input" wrap="virtual">

<!-- BEGIN PHP Live! Code, copyright 2001-2002 OSI Codes -->
<script language="JavaScript" src="<? echo $BASE_URL ?>/js/status_image.php?base_url=<? echo $BASE_URL ?>&l=<? echo $session_setup['login'] ?>&x=<? echo $session_setup['aspID'] ?>&deptid=<? echo $deptid ?>&btn=1"></script><a href="http://www.phplivesupport.com"></a>
<!-- END PHP Live! Code, copyright 2001-2002 OSI Codes -->

</textarea>
</form>
		<br>

The above code will produce the below status icon and link.<br>

<!-- END PHP Live! Code, copyright 2001-2002 OSI Codes -->
<script language="JavaScript" src="<? echo $BASE_URL ?>/js/status_image.php?base_url=<? echo $BASE_URL ?>&l=<? echo $session_setup['login'] ?>&x=<? echo $session_setup['aspID'] ?>&deptid=<? echo $deptid ?>&btn=1"></script><a href="http://www.phplivesupport.com"></a>
<!-- BEGIN PHP Live! Code, copyright 2001-2002 OSI Codes -->

		<hr>
		
		<li> Below code can be placed inside your <b>HTML RICH EMAILS</b>.
		<form>
		<textarea cols="<? echo $textbox_width ?>" rows="5" class="input" wrap="virtual">
<a href="<? echo $BASE_URL ?>/request_email.php?l=<? echo $session_setup['login'] ?>&x=<? echo $session_setup['aspID'] ?>&deptid=<? echo $deptid ?>" target="new"><img src="<? echo $BASE_URL ?>/image.php?l=<? echo $session_setup['login'] ?>&x=<? echo $session_setup['aspID'] ?>&deptid=<? echo $deptid ?>" border=0 alt="Click for Live Support!"></a>
		</textarea>
		</form>




		<?
			else:
			$departments = AdminUsers_get_AllDepartments( $dbh, $session_setup['aspID'] ) ;
		?>
		<li> <a href="code.php?action=generate&dept=&phplive_notally&">Generate HTML to display ALL departments.</a>
		<li> Generate HTML for ONLY the specified department below.
			<ul>
				<?
					for ( $c = 0; $c < count( $departments ); ++$c )
					{
						$department = $departments[$c] ;
						print "<li> <a href=\"code.php?action=generate&deptid=$department[deptID]&phplive_notally&\">$department[name]</a>\n" ;
					}
				?>
			</ul>


		<? endif ;?>

		</ul>
	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>