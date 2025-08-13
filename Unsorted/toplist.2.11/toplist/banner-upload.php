<?
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";


echo "<br><br><center>" . $lang['uploadtxt'] . "<br><br>

<form enctype='multipart/form-data' method='post' action='upload.php'>

<input type='hidden' name='action' value='upload'>

<table frame=box rules=none border=0 cellpadding=2

       cellspacing=0 align='center'>

   <tr>

      <td>Banner:</td>

      <td><input type='file' name='userfile'></td>

   </tr>

      <tr>

      <td>" . $lang['siteurlwohttp'] . ":</td>

      <td><input type='input' name='sitename'></td>

   </tr>

   <tr>

      <td></td>

      <td><input type='submit' name ='upload'

                 value='Upload'></td>

   <tr>

</table>

</form>";
include "footer.php";
?>
