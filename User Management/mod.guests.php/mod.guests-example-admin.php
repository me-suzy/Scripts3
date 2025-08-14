<?
/* set this to prevent this page from being cached */
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
?>

<html>
<head>
  <style type="text/css">
    body, th, td         { font-family: arial; font-size: 8pt; }
    a:link, a:visited    { color: #000000; text-decoration: none; }
    a:active, a:hover    { color: #000000; text-decoration: underline; }
  </style>
</head>
<body bgcolor=#d4b488 text=#000000>
<div align=center>


<br>
<?
require("mod.guests.php");
$guests = new guests();

if (isset($id) && isset($status))
  $guests->set_status($id, $status, "admin");
else
  $guests->show_list("admin");
?>


</div>
</body>
</html>