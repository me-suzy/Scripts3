<?php 
if (!$redirurl){
	if ($dbfield){
	$str = "mod.$dbfield.php";
	$redirurl = strtolower($str);
	}else{die;}
}
?>

<html>
<head>
<meta http-equiv="refresh" content="0;url=<?php echo $redirurl; ?>">
</head>
<body>
</body>
</html>

