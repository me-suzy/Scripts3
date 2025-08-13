<?php
include("header.php");
if(@mysql_fetch_array(@mysql_query("SELECT cf FROM st_links WHERE cf='$cf'"))) {
@mysql_query("UPDATE st_links set confirm='Y' WHERE cf='$cf'");

// display confirm message
eval("\$pleaseconfirm = \"".fetchtemplate('confirm_message')."\";");
echo "$pleaseconfirm";
eval("\$cpr = \"".fetchtemplate('index_copyright')."\";"); echo "$cpr";

}
else { 
// display error message
eval("\$nopleaseconfirm = \"".fetchtemplate('confirm_no_message')."\";");
echo "$nopleaseconfirm";
eval("\$cpr = \"".fetchtemplate('index_copyright')."\";"); echo "$cpr";

} exit;
?>