<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

/*
require('includes/messages.inc.php');
require('includes/config.inc.php');
*/
$dbtb="aufrufzaehler"; 
mysql_connect($DbHost, $DbUser, $DbPassword);
mysql_selectdb($DbDatabase);
//header ("Content-type:image/gif";
if(!isset($id)) {
/*
$im = @ImageCreate (200, 35)
or die ("Kann keinen neuen GD-Bild-Stream erzeugen";
$background_color = ImageColorAllocate ($im, 255, 255, 255);
$text_color = ImageColorAllocate ($im, 233, 14, 91);
ImageString ($im, 5, 5, 8, "keine ID angegeben!", $text_color);
ImageGIF ($im);
*/
echo "keine ID angegeben";
exit;
}
$result=mysql_query("SELECT *FROM $dbtb WHERE auktionsid = \"$id\"";
if(mysql_num_rows($result)==0) {
$result=mysql_query("INSERT INTO $dbtb VALUES(\"$id\", 1)";
$zugriff=1;
}
else {
$result=mysql_fetch_array($result);
$zugriff=$result["zugriff"];
$result=mysql_query("UPDATE $dbtb SET zugriff=zugriff+1 WHERE auktionsid=\"$id\"";
}
/*
$im = @ImageCreate (24, 21)
or die ("Kann keinen neuen GD-Bild-Stream erzeugen";
$background_color = ImageColorAllocate ($im, 255, 255, 255);
$text_color = ImageColorAllocate ($im, 0, 0, 0);
ImageString ($im, 5, 5, 8, "$zugriff", $text_color);
Imagegif ($im);
*/
echo "$zugriff";
?>      