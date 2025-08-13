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
$page_account = "admin";
require "include.php";
include "templates/header.php";	

if ($action == "upload") {
  // ok, let's get the uploaded data and insert it into the db now

  if (isset($binFile) && $binFile != "none") {
    $data = addslashes(fread(fopen($binFile, "r"), filesize($binFile)));
    $strDescription = addslashes(nl2br($txtDescription));
    $sql = "INSERT INTO tbl_Files ";
    $sql .= "(description, bin_data, filename, filesize, filetype) ";
    $sql .= "VALUES ('$strDescription', '$data', ";
    $sql .= "'$binFile_name', '$binFile_size', '$binFile_type')";
    $result = mysql_query($sql, $db);
    mysql_free_result($result); // it's always nice to clean up!
    echo "Thank you. The new file was successfully added to our database.<br><br>";
    echo "<a href='admin_home.php'>Continue</a>";
  }
  mysql_close();

} else {
?>
<HTML>
<BODY>
<FORM METHOD="post" ACTION="add_download.php" ENCTYPE="multipart/form-data">
 <INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" VALUE="1000000">
 <INPUT TYPE="hidden" NAME="action" VALUE="upload">
 <TABLE BORDER="0">
  <TR>
   <TD valign="top">Description: </TD>
   <TD><TEXTAREA NAME="txtDescription" ROWS="10" COLS="50"></TEXTAREA></TD>
  </TR>
  <TR>
   <TD>File: </TD>
   <TD><INPUT TYPE="file" NAME="binFile"></TD>
  </TR>
  <TR>
   <TD COLSPAN="2"><INPUT TYPE="submit" VALUE="Upload"></TD>
  </TR>
 </TABLE>
</FORM>
</BODY>
</HTML>
<?php
include "templates/footer.php";	
}
?>
