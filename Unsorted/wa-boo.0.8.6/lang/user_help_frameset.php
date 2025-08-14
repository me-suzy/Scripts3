<?
    include ("../includes/global_vars.php");
    include ("../includes/fotools.php");

    session_start();

?>
<html>
<head>
<title>:::: wa-boo HELP ::::: </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <style><? include ("../includes/css.php"); ?></style> 
</head>



<frameset cols="150,*" frameborder="NO" border="0" framespacing="0">
  <frame name="leftFrame" scrolling="NO" noresize src="<? echo $s_lang ?>/user_help/menu.php">
  <frame name="mainFrame" SRC="<? echo $s_lang ?>/user_help/welcome.php">
  
</frameset>
<noframes><body bgcolor="#FFFFFF" text="#000000">

</body></noframes>
</html>
