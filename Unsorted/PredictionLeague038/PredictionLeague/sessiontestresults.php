<?php
///////////////////////////////////////////////////////////////////
//Author: John Astill (c) 2002
//Desc  : Write a values for the User session data
//      : and test that it still has data in the next page.
///////////////////////////////////////////////////////////////////
  require "SystemVars.php";

  session_start();
?>
<html>
<head>
<title>
Session Test Results
</title>
</head>
<body>
<p>
<a href="AdminMenu.html">back</a>
</p>
<?php
 if ($User->userid != "TestSession") {
   echo "<br><b>It appears that there is a problem with session support on your server</b><br>";
 } else {
   echo "<br><b>Session support working, safe to continue with install</b><br>";
 }
?>
<p>
</body>
</html>
