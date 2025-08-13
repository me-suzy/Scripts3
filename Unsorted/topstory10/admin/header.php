<? include("login.php");
$fieldlist=mysql_listtables($tst["db"]["name"]);
	$foundTbl=0;
for($i=0;$i<mysql_numrows($fieldlist);$i++)
{
	if(mysql_tablename($fieldlist,$i)==$tst["tbl"]["articles"]) {
		$foundTbl=1;
	}
	
}
if($foundTbl==0) {
header("location:create_tbl.php");
}
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title> Top Story- Basic Cpanel </title>
<link rel="stylesheet" type="text/css" href="<? echo $tst["url"] ?>/lib/topStory.css">
</head>
<body bgcolor="#FFFFFF">
<table class="text" align="center" width="600" cellpadding="3" cellspacing="1" bgcolor="CCCCCC">
<tr bgcolor="#999999">
	<td><a class="topMenuLink" href="postNews.php"><? echo $tst["lang"]["postNews"] ?></a> | <a class="topMenuLink" href="listAnnc.php"><? echo $tst["lang"]["listManage"] ?></a> | <a class="topMenuLink" href="index.php"><? echo $tst["lang"]["home"] ?></a>
	</td>
	</tr>
	<tr bgcolor="#FFFFFF">
	<td height="300" valign="top">