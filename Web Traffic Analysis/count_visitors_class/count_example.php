<?php
include($_SERVER['DOCUMENT_ROOT']."/classes/count_visitors/count_visitors_class.php"); //classes is the map where the class file is stored

// create a new instance of the count_visitors class.
$my_visitors = new Count_visitors; 

$my_visitors->delay = 1; // how often (in hours) a visitor is registered in the database (default = 1 hour)
$my_visitors->insert_new_visit(); // That's all, the validation is with this method, too.
?>