<html>
<head>
</head>
<body>
<?php

$file = file(".");

while (list($key,$val) = each($newUsers)) {
  echo $val;
}



?>
</body>
</html>
