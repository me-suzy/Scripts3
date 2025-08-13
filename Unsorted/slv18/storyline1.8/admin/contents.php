<? 
session_start();

include("../base/define.inc.php");
include("../base/main.inc.php");

$dl = new TheDB();	
$dl->connect() or die($dl->getError());
$dl->debug=false;

$cl = new TheCleaner();

if( !$_SESSION['sladmin'] ) {
	print "You do not have permission to access this area";

} else {

?>
<html>
<head>
<link rel='stylesheet' href='<?=SL_ROOT_URL?>/base/html/Default/style.css'>
</head>
<body leftmargin='0' topmargin='0' rightmargin='0' bottomargin='0' marginheight='0' marginwidth='0'>
<img src='logo.gif'>
<table border='0' width='100%' class='cleardis'>
	<tr>
		<td class='heavydis'>General</td>
	</tr><tr>
		<td class='small'>
			&#8226; <a href='main.php?config' target='main'>Configuration</a><br>
			&#8226; <a href='main.php?language' target='main'>Language</a>
		</td>
	</tr><tr>
		<td class='heavydis'>Manager</td>
	</tr><tr>
		<td class='small'>
			&#8226; <a href='main.php?mancat' target='main'>Category</a><br>
			&#8226; <a href='main.php?mansub' target='main'>Sub-Category</a><br>
			&#8226; <a href='main.php?manstory' target='main'>Story</a>
		</td>
	</tr><tr>
		<td class='heavydis'>Cosmetics</td>
	</tr><tr>
		<td class='small'>
			&#8226; <a href='main.php?style' target='main'>Edit StyleSheet</a><br>
			&#8226; <a href='main.php?layout' target='main'>Edit Layout</a><br>
			&#8226; <a href='main.php?file' target='main'>Edit File</a>	
		</td>
	</tr><tr>
		<td class='heavydis'>News</td>
	</tr><tr>
		<td class='small'>
			&#8226; <a href='main.php?newsadd' target='main'>New news post</a><br>
			&#8226; <a href='main.php?newsedit' target='main'>Edit news post</a><br>
			&#8226; <a href='main.php?newsdelete' target='main'>Delete news post</a>			
		</td>
	</tr>
</table>
</body>
</html>

<?
}
?>