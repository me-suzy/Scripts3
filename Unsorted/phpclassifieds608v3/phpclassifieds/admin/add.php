<p>
<?
require("../config/header.inc.php");
require("../config/config.inc.php");

  $dagens_dato = date("d.m.Y");
  $dy =  date("d");
  $mn =  date("m");
  $yr = date("Y");
  $expiredate = strftime("%d.%m.%Y", mktime(0,0,0,$mn,$dy+$delete_after_x_days,$yr));


?>

<form method="post" action="<?php echo $PHP_SELF?>">
<input type="hidden" name="siteid" value="<?php echo $siteid ?>">
<?
if (!$sitetitle)
{
?> 
Her legges selve gjenstandsbeskrivelsen inn. Vennligst fyll ut med en kort, men beskrivende tittel. 
Selve beskrivelsesfeltet bruker du for  beskrive detaljer med gjenstanden din.<br />
For at annonsen din skal komme i riktig kategori, velg Kategori nedenfor. Hvis du ikke
velger dette, vil annonsen din ikke vises.
Oppgi ønsket pris på gjenstanden, og forklar eventuellt mer om prisen i beskrivelsen.
<p>

<?
}
if ("$password")
{
?>
<input type="hidden" name="pass" value="<? echo $password ?>">
<?
}

else
{
?>
<input type="hidden" name="checkpass" value="<? echo $checkpass?>">
<?

}

?>



<input type="hidden" name="userid" value="<?php echo $userid ?>">
<?






$companyregistered = date('d.m.Y');

$sql_select = "select * from $ads_tbl where siteid = '$siteid'";
$sql_insert = "insert into $ads_tbl
               (
                 sitetitle,
                 sitedescription,
                 siteurl,
                 sitedate,
                 expiredate,
                 sitecatid,
                 sitehits,
                 sitevotes,
                 sites_userid,
                 sites_pass,
                 custom_field_1,
                 custom_field_2,
                 custom_field_3,
                 custom_field_4,
                 custom_field_5,
                 custom_field_6,
                 custom_field_7,
                 custom_field_8)

                 values

                 (
                 '$sitetitle',
                 '$sitedescription',
                 '$siteurl',
                 '$dagens_dato',
                 '$expiredate',
                  $sitecatid,
                 '$sitehits',
                 '$sitevotes',
                 '$userid',
                 '$pass',
                 '$custom_field_1',
                 '$custom_field_2',
                 '$custom_field_3',
                 '$custom_field_4',
                 '$custom_field_5',
                 '$custom_field_6',
                 '$custom_field_7',
                 '$custom_field_8')";

$sql_update = "update $ads_tbl set sitetitle='$sitetitle',sitedate='$dagens_dato', expiredate='$expiredate', sitedescription='$sitedescription',siteurl='$siteurl',sitecatid=$sitecatid,sitehits='$sitehits',sitevotes='$sitevotes', custom_field_1='$custom_field_1', custom_field_2='$custom_field_2', custom_field_3='$custom_field_3', custom_field_4='$custom_field_4', custom_field_5='$custom_field_5', custom_field_6='$custom_field_6', custom_field_7='$custom_field_7', custom_field_8='$custom_field_8' where siteid = '$siteid'";
$sql_delete = "delete from $ads_tbl where siteid = $siteid";
if ($delete)
{
           $result = mysql_query ($sql_delete);

           $status = "$deleted";


}
else
{


if ($submit)
{

   if ($siteid)
   {
           $result = mysql_query ($sql_update);
           // print("$sql_update");
           $status = $updated_site;

   }
   else
   {
           $result = mysql_query ($sql_insert);
           // print("$sql_insert");
$status = $registered_site;


           $sisteid = mysql_insert_id();
            require("../config.php");
// setup variables
$sendto = "$from_adress";
$from = "$from_adress";
$subject = "$subjectfield_email";
$message = "$admin_new_ad";



$headers = "From: $from\r\n";
// send e-mail
//mail($sendto, $subject, $message, $headers);



   }


$status2= "
</form>$upload_picture_text<p>
<form method=\"post\" action=\"upload.php\" enctype=\"multipart/form-data\">

    <INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10000\">
     <INPUT TYPE=\"hidden\" name=\"pictures_siteid\" value=\"$sisteid\">
    $filename:<br />
    <input type=\"file\" name=\"form_data\"  size=\"40\">
    <br /><input type=\"submit\" name=\"submit\" value=\"Upload\">
    </form>";
print("</table>");

}
}

if ($siteid)
{
        $result = mysql_query ($sql_select);


        while ($row = mysql_fetch_array($result))
        {


        $siteid = $row["siteid"];
        $sitetitle = $row["sitetitle"];
        $sitedescription = $row["sitedescription"];
        $siteurl = $row["siteurl"];
        $sitedate = $row["sitedate"];
        $sitecatid = $row["sitecatid"];
        $sitehits = $row["sitehits"];
        $sitevotes = $row["sitevotes"];
        $custom_field_1 = $row["custom_field_1"];
        $custom_field_2 = $row["custom_field_2"];
        $custom_field_3 = $row["custom_field_3"];
        $custom_field_4 = $row["custom_field_4"];
        $custom_field_5 = $row["custom_field_5"];
        $custom_field_6 = $row["custom_field_6"];
        $custom_field_7 = $row["custom_field_7"];
        $custom_field_8 = $row["custom_field_8"];


        };



   }

?>


<table width=100% border=0 cellpadding=2 cellspacing=1>
<tr><td bordercolor="#FFFFFF">
   <?php print("<h2>$status</h2>");  ?>
   <? require("../config.php");
   $sisteid1 = $sisteid;

 print(" $status2");  ?>



<?

if ($submit)
{

}

else
{


if ($debug)
{

   print("$result");
}
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="50%" valign="top"> <? echo $title ?></td>
    <td width="50%" valign="top"> 
<input type="text" name="sitetitle" size="38" style="background-color: #E6E6E6" value=<?php echo $sitetitle ?>>


       

    </td>
  </tr>



    <tr>
    <td width="50%" valign="top"> <? echo $description ?></td>
    <td width="50%" valign="top"> <textarea rows="4" name="sitedescription" cols="29" style="background-color: #E6E6E6"><?php echo $sitedescription ?></textarea>


       

    </td>
  </tr>


      <tr>
     
<td width="50%" valign="top"> <?php echo $category ?></td>
<td width="50%" valign="top"> 

<?php include("../categories_listbox.php"); ?>



       

</td>


       

  </tr>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_1_text)
{
  print("<tr>");
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_1_text</td>");
  print("<td width=\"50%\" valign=\"top\"> ");
  print("<input type=\"text\" name=\"custom_field_1\" size=\"38\" style=\"background-color: #E6E6E6\" value=\"$custom_field_1\">");
  print(" ");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_2_text)
{
  print("<tr>");
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_2_text</td>");
  print("<td width=\"50%\" valign=\"top\"> ");
  print("<input type=\"text\" name=\"custom_field_2\" size=\"38\" style=\"background-color: #E6E6E6\" value=\"$custom_field_2\">");
  print(" ");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_3_text)
{
  print("<tr>");
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_3_text</td>");
  print("<td width=\"50%\" valign=\"top\"> ");
  print("<input type=\"text\" name=\"custom_field_3\" size=\"38\" style=\"background-color: #E6E6E6\" value=\"$custom_field_3\">");
  print(" ");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_4_text)
{
  print("<tr>");
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_4_text</td>");
  print("<td width=\"50%\" valign=\"top\"> ");
  print("<input type=\"text\" name=\"custom_field_4\" size=\"38\" style=\"background-color: #E6E6E6\" value=\"$custom_field_4\">");
  print(" ");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_5_text)
{
  print("<tr>");
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_5_text</td>");
  print("<td width=\"50%\" valign=\"top\"> ");
  print("<input type=\"text\" name=\"custom_field_5\" size=\"38\" style=\"background-color: #E6E6E6\" value=\"$custom_field_5\">");
  print(" ");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_6_text)
{
  print("<tr>");
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_6_text</td>");
  print("<td width=\"50%\" valign=\"top\"> ");
  print("<input type=\"text\" name=\"custom_field_6\" size=\"38\" style=\"background-color: #E6E6E6\" value=\"$custom_field_6\">");
  print(" ");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_7_text)
{
  print("<tr>");
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_7_text</td>");
  print("<td width=\"50%\" valign=\"top\"> ");
  print("<input type=\"text\" name=\"custom_field_7\" size=\"38\" style=\"background-color: #E6E6E6\" value=\"$custom_field_7\">");
  print(" ");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_8_text)
{
  print("<tr>");
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\">$custom_field_8_text</td>");
  print("<td width=\"50%\" valign=\"top\"> ");
  print("<input type=\"text\" name=\"custom_field_8\" size=\"38\" style=\"background-color: #E6E6E6\" value=\"$custom_field_8\">");
  print(" ");
  print("</td>");
  print("</tr>");
}
?>



  <tr>
    <td width="100%" valign="top" colspan="2"> 


       

    </td>
  </tr>



       

</td></tr>

</table></table>

   <input type="submit" name="submit" value="<?php echo $submit_button ?>"><p>


Dersom du nsker  slette din annonse i ettertid, kan du trykke slett knappen nedenfor: <br />
<input type="submit" name="delete" style="background-color: #FF0000" value="<?php echo $delete_button ?>">

       </form>


<?
}


require("../config/footer.inc.php");

?></p>
