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
$id_files = $GLOBALS["id"];
$page_account = "phpProMembers";
require "include.php";
if ($id_files) {
  
     
  $sql = "SELECT bin_data, filetype, filename, filesize FROM tbl_Files WHERE id_files=$id_files";
  $result = @mysql_query($sql, $db);
  $data = @mysql_result($result, 0, "bin_data");
  $name = @mysql_result($result, 0, "filename");
  $size = @mysql_result($result, 0, "filesize");
  $type = @mysql_result($result, 0, "filetype");
	
  header("Content-type: $type");
  header("Content-length: $size");
  header("Content-Disposition: attachment; filename=$name");
  header("Content-Description: PHP Generated Data");
  echo $data;

}
?>
