<?php
/*********************************************************************/
/* Program Name         : phpProMembers                              */
/* Home Page            : http://www.gacybertech.com                 */
/* Retail Price         : $149.99 United States Dollars              */
/* WebForum Price       : $000.00 Always 100% Free                   */
/* xCGI Price           : $000.00 Always 100% Free                   */
/* Supplied by          : South [WTN]                                */
/* Nullified by         : CyKuH [WTN]                                */
/* Distribution         : via WebForum and Forums File Dumps         */
/*********************************************************************/
$page_account = "phpProMembers";
require "include.php";
if ($include_template == "1") {
	include "$template_directory/header.php";	
}

$sql = "SELECT * FROM tbl_Files ";
$sql .= "ORDER BY filename ASC";
$result = mysql_query($sql, $db);
$rows = mysql_num_rows($result);

echo "Click On The Download Link To Download The File!";
echo "<br><br>";

echo "<table>";

for ($i = 0; $i < $rows; $i++) {
  $data = mysql_fetch_object($result);
  // since our script is very small, i'm not going to escape out to html mode here
  echo " <tr>\n";
  echo "  <td>$data->filename&nbsp;&nbsp;</td>\n";
  echo "  <td>$data->filesize&nbsp;bytes&nbsp;&nbsp;</td>\n";
  echo "  <td>( <a href='download.php?id=$data->id_files'>Download</a> )</td>\n";
  echo " </tr>\n";
}

echo "</table>";

if ($include_template == "1") {
	include "$template_directory/footer.php";	
}
?>
