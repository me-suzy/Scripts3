<?
include "register_fields_inc.php";
if ($usr_1_text)
{
print("<tr>");
print("<td width=\"50%\" valign=\"top\"> $usr_1_text </td>");
print("<td width=\"50%\" valign=\"top\" class=\"star\">");
getfield($usr_1_type,"usr_1",$usr_1_filename,$usr_1_length,$usr_1_mandatory, $usr_1);
if ($usr_1_mandatory) { print " *"; }
print("</td>");
print("</tr>");
}
if ($usr_2_text)
{
print("<tr>");
print("<td width=\"50%\" valign=\"top\"> $usr_2_text </td>");
print("<td width=\"50%\" valign=\"top\" class=\"star\">");
getfield($usr_2_type,"usr_2",$usr_2_filename,$usr_2_length,$usr_2_mandatory, $usr_2);
if ($usr_2_mandatory) { print " *"; }
print("</td>");
print("</tr>");
}
if ($usr_3_text)
{
print("<tr>");
print("<td width=\"50%\" valign=\"top\"> $usr_3_text </td>");
print("<td width=\"50%\" valign=\"top\" class=\"star\">");
getfield($usr_3_type,"usr_3",$usr_3_filename,$usr_3_length,$usr_3_mandatory,$usr_3);
if ($usr_3_mandatory) { print " *"; }
print("</td>");
print("</tr>");
}
if ($usr_4_text)
{
print("<tr>");
print("<td width=\"50%\" valign=\"top\"> $usr_4_text </td>");
print("<td width=\"50%\" valign=\"top\" class=\"star\">");
getfield($usr_4_type,"usr_4",$usr_4_filename,$usr_4_length,$usr_4_mandatory,$usr_4);
if ($usr_4_mandatory) { print " *"; }
print("</td>");
print("</tr>");
}
if ($usr_5_text)
{
print("<tr>");
print("<td width=\"50%\" valign=\"top\"> $usr_5_text</td>");
print("<td width=\"50%\" valign=\"top\" class=\"star\">");
getfield($usr_5_type,"usr_5",$usr_5_filename,$usr_5_length,$usr_5_mandatory,$usr_5);
if ($usr_5_mandatory) { print " *"; }print("</td>");
print("</tr>");
}
?>
