<? 
session_start(); 
header("Cache-control: private"); //IE 6 Fix 
$_SESSION["valid_id"] = 1; 
$_SESSION["valid_user"] = 1234; 
$_SESSION["valid_title"] = "Manager"; 
$_SESSION["valid_time"] = time(); 
$_SESSION["dep"] = "IT Dep"; 
$_SESSION["fname"] = "John"; 
$_SESSION["lname"] = "Doe"; 
?>
<form action="session.asp" method="post"> 
<input type="hidden" name="valid_user" value="<?=$_SESSION["valid_user"];?>"> 
<input type="hidden" name="valid_id" value="<?=$_SESSION["valid_id"];?>"> 
<input type="hidden" name="valid_title" value="<?=$_SESSION["valid_title"];?>"> 
<input type="hidden" name="dep" value="<?=$_SESSION["dep"];?>"> 
<input type="hidden" name="fname" value="<?=$_SESSION["fname"];?>"> 
<input type="hidden" name="lname" value="<?=$_SESSION["lname"];?>"> 
<input type="submit" name="submit" value="Open ASP Session"> 
</form> 
