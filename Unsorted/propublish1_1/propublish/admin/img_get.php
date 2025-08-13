<?php
require("db.php");
$id = htmlspecialchars($id);

$query = "select * from newspicture_news where id=$id";
$result = @MYSQL_QUERY($query);

$data = @MYSQL_RESULT($result,0, "bin_data");
$type = @MYSQL_RESULT($result,0, "filetype");

Header("Content-type: $type");
echo $data;


?>