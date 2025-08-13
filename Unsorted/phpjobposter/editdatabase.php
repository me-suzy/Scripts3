<?

include("config.php");



if ($R1=="add"){
$loc="add.php";
}



if ($R1=="update"){
$loc="update.php";
}



if ($R1=="delete"){
$loc="delete.php";
}
echo $currentjobid;
echo "<html>\n";
echo "<form method=\"POST\" action=\"$loc\">\n";
echo "  <p align=\"center\"><input type=\"hidden\" name=\"title\" value=\"$title\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"title\" value=\"$title\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"company\" value=\"$company\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"location\" value=\"$location\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"description\" value=\"$description\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"contact\" value=\"$contact\" size=\"20\">\n";
echo "  <input type=\"hidden\" name=\"email\" value=\"$email\" size=\"20\">\n";
echo "<input type=\"hidden\" name=\"currentjobid\" value=\"$currentjobid\" size=\"20\">\n";
echo " <input type=\"hidden\" name=\"url\" value=\"$url\" size=\"20\">  \n";
echo "<input type=\"hidden\" name=\"newjobid\" value=\"$newjobid\" size=\"20\">\n";
echo "<input type=\"hidden\" name=\"username\" value=\"$username\" size=\"20\">\n";
echo "<input type=\"hidden\" name=\"password\" value=\"$password\" size=\"20\">\n";
echo "<input type=\"hidden\" name=\"publishdate\" value=\"$publishdate\" size=\"20\">\n";
echo "  <input type=\"submit\" value=\"continue\" name=\"B1\">\n";
echo "</form>\n";
echo "</html>\n";

?>