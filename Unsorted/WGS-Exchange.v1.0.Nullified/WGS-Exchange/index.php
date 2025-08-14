<?


error_reporting(7);

if (!defined('directory')) {
	define('directory', '');
}

include(directory . "var.php");

head("Welcome to our Exit-Popup Exchange");

$ratio1 = $ratio*10;
$ratio2 = (10-$ratio)*10;

include(directory . "tpl/index.tpl");

footer();

?>