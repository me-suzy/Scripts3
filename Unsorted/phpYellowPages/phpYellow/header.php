<!-- start of header.php -->
<br>
<p style="font-size:18px;">
<a href="new.php">New!</a> | 
<a href="index.php">Home</a> | 
<a href="login.php">Add-Edit-Delete</a>  
| <a href="password.php">Password</a> |  
<a href="goPremium.php"> Go Premium</a> 
<?php if(defined("SETRANK")){if(file_exists("premiumTellAFriend.php")){print" | <a href=\"premiumTellAFriend.php\">Tell A Friend!</a>";}}?>
</p>
<!-- end of header.php -->