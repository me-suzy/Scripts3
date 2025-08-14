<?php
function generate_unique_id($filename, $description = '') {
	global $nuketable;
	$description = addslashes($description);
	mysql_query("INSERT INTO $nuketable[unique] (id, filename, description) VALUES (NULL, '$filename', '$description')");
	return mysql_insert_id();
}
?>