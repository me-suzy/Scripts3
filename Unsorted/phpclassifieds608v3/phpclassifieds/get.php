<?php
require("admin/db.php");
include_once("admin/inc.php");
$query_get = "select * from $pic_tbl where id=$id";
$result = @MYSQL_QUERY($query_get);
$data = @MYSQL_RESULT($result,0, "bin_data");
$type = @MYSQL_RESULT($result,0, "filetype");
Header("Content-type: $type");
echo $data;
?>