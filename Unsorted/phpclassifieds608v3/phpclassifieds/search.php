<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
// print("<h2>$name_of_site</h2>");

if (!$searchword AND !$adv)
{

?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?

print "<b>$la_advanced</b>";

?>
    </td>
  </tr>
</table>

<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1"></td>
  </tr>
</table>

<?
 

?>

<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">


<form action="search.php" method="post">
<table cellspacing="3" border="0">
<tr>
	<td><font style="text"><? echo $la_search ?> </td>
	<td><input name="searchword" size="20" /></td>
</tr>

<tr>
	<td><font style="text"><? echo $la_s_category ?> </td>
	<td><? require("list_hovedkat.php"); ?></td>
</tr>

<?
if ($custom_field_1_text)
{
 
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_1_text </td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_1\" size=\"29\" class='txt' value=\"$custom_field_1\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_2_text)
{

  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_2_text </td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_2\" size=\"29\" class='txt' value=\"$custom_field_2\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_3_text)
{

  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_3_text </td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_3\" size=\"29\" class='txt' value=\"$custom_field_3\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_4_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_4_text </td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_4\" size=\"29\" class='txt' value=\"$custom_field_4\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_5_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_5_text </td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_5\" size=\"29\" class='txt' value=\"$custom_field_5\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_6_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_6_text </td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_6\" size=\"29\" class='txt' value=\"$custom_field_6\" />");

  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_7_text)
{
   print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_7_text </td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_7\" size=\"29\" class='txt' value=\"$custom_field_7\" />");
  print("</td>");
  print("</tr>");
}
?>

<?
// If we have defined extra fields in config, print them...
if ($custom_field_8_text)
{
  print("<tr>");
  print("<td width=\"50%\" valign=\"top\"> $custom_field_8_text </td>");
  print("<td width=\"50%\" valign=\"top\">");
  print("<input type=\"text\" name=\"custom_field_8\" size=\"29\" class='txt' value=\"$custom_field_8\" />");
  print("</td>");
  print("</tr>");
}
?>

<tr>
	<td colspan="2"><input type="submit" value="<? echo $la_search ?>" /></td>
</tr>
</table>
</form>

    </td>
  </tr>
</table>




<?
}
if ($searchword || $adv)
{
         $kid=1;

          require("links.php");
}

include_once("admin/config/footer.inc.php");

?>
