<? require("admheader.php"); ?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
                <td bgcolor="lightgrey">
                                 
                                &nbsp; Field Editor 
                </td>
</tr>

<tr bgcolor="white">
                <td width="100%">
                               
<?
if ($add)
{
         print "<form action='extra.php' method='post'>";
         print "<input type='hidden' name='add' value='1' />";
         print "<input type='text' name='new_name' />";
         print "<input type='submit' name='add_submit' value='Add tpl' />";
         print "</form>";
         if ($add_submit)
         {
              $sql = "insert into template (name) values ('$new_name')";
              $result = mysql_query ($sql);

              print "  Category is now added. You can now the <a href='?edit_tpl=1&amp;tplname=$new_name'>edit it</a>. ";
         }
}


if ($del)
{
          print "<form action='extra.php' method='post'>";
          print "<input type='hidden' name='del' value='1' />";
          print "<select name='tplname'>";
          $sql = "select * from template order by name";
          $result = mysql_query ($sql);
          while ($row = mysql_fetch_array($result))
          {
                  $name = $row[name];
                  print "<option value='$name'>$name</option>";
          }
         print "</select>";
         print "<input type='submit' name='del_submit' value='Remove tpl' />";
         print "</form>";
         if ($del_submit)
         {
                  $sql = "delete from template where name = '$tplname'";
                  $result = mysql_query ($sql);
                  print "  Deleted the template $tplname! ";
         }
}



if (!$edit_field AND !$add AND !$del)
{
// ----------------
print "<b>Select template to edit</b><br />";
print "<form action='extra.php' method='post'>";
print "<select name='tplname'>";
$sql = "select * from template order by tplid";
$result = mysql_query ($sql);
 while ($row = mysql_fetch_array($result)) {
         $name = $row[name];
         print "<option value='$name'>$name</option>";
}
print "</select>";
print "<button type='submit' name='edit_tpl'>Edit template</button>";
print "</form>";
print "<p />  <a href='extra.php?add=1'>Add template</a> | <a href='extra.php?del=1'>Delete template</a> ";
}
// -------------



if ($edit_tpl)
{
// ----------------
print "<p /><b>Select fieldnumber to edit</b><br />";
print "<form action='extra.php' method='post'>";
print "<input type='hidden' name='tplname' value='$tplname' />";
print "<select name='field'>";
$sql = "select * from template order by id";
$result = mysql_query ($sql);
while ($teller < 15) {
        $teller = $teller +1;
        print "<option value='$teller'>$teller</option>";
}
print "</select>";
print "<button type='submit' name='edit_field'>Show editable field</button>";
print "</form>";
}
// -------------






if ($edit_field)
{
$tmpfield1 = "f" . $field . "_caption";
$tmpfield2 = "f" . $field . "_type";
$tmpfield3 = "f" . $field . "_mandatory";
$tmpfield4 = "f" . $field . "_length";
$tmpfield5 = "f" . $field . "_filename";



$string = "select * from template where name = '$tplname'";
$result = mysql_query ($string);
$row = mysql_fetch_array($result);
$caption = $row["$tmpfield1"];
$type = $row["$tmpfield2"];
$mandatory = $row["$tmpfield3"];
$length = $row["$tmpfield4"];
$filen = $row["$tmpfield5"];

print "<b>Now editing Template $tplname, field number $field</b><br />";
print "<form action='extra.php' method='post'>";
print "<input type='hidden' name='field' value='$field' />";
print "<input type='hidden' name='tplname' value='$tplname' />";
print "<table width='100%' cellspacing='2'>";

print "<tr>";
print "<td> Caption </td>";
print "<td><input type='text' name='caption' value='$caption' /></td>";
print "<td>  (Shown on form) </td>";
print "</tr>";

print "<tr>";
print "<td> Type </td>";
print "<td><select name='type'><option selected='selected'>$type</option><option>Checkbox</option><option>Option</option><option>Dropdown</option><option>Text</option><option>URL</option><option>Textarea</option></select></td>";
print "<td>  (type of options) </td>";
print "</tr>";

print "<tr>";
print "<td> Mandatory </td>";
print "<td><input type='checkbox' name='mandatory' ";
if ($mandatory)
{
 print "checked";
}
print " /></td>";
print "<td>  (errormsg if not filled out) </td>";
print "</tr>";

print "<tr>";
print "<td> Field length </td>";
print "<td><input type='text' name='length' value='";
if (!$length)
{
print "29";
}
print "' /></td>";
print "<td>  (Only form length) </td>";
print "</tr>";

print "<tr>";
print "<td> Filename </td>";
print "<td><input type='text' name='filen' value='$filen' /></td>";
print "<td>  (File with options) </td>";
print "</tr>";

print "<tr>";
print "<td colspan=3><button type='submit' name='save'>Save</button></td>";
print "</tr>";
print "<tr>";
print "<td colspan=3> <i>Note: You can only use one option when using a checkbox. If you have several option i a checkbox .txt file, only the last checked checkbox in the form is inserted. If you need several options, use Option or Select box instead.<p />Also, when using a Textarea field, you will need to do an Alter statement on your database, and make the selected field a Text field,  in order to save the entire content.</i> </td>";
print "</tr>";
print "</table>";



}


// --- SAVE ---
if ($save)
{
 print "<ol>";
 if (($type == 'Option' || $type == 'Checkbox' || $type == 'Dropdown') AND !$filen)
 {
                print "<li> <b>Error:</b><br />";
                print "In order to use Option, Checkbox or Dropdown list, ";
                print "you must supply options (labels). This is done by ";
                print "filling out a filename in the previous form.";
                print "The text file must exist, and be placed in the /options dir.";
                print " You have NOT gived a filename, and therefore no options.";
                print " Use the back button in the browser! </li>";
                $stop = 1;
 }


 print "</ol>";

 if (!$stop)
 {
 $fieldname1 = "f" . $field . "_caption";
 $fieldname2 = "f" . $field . "_type";
 $fieldname3 = "f" . $field . "_mandatory";
 $fieldname4 = "f" . $field . "_length";
 $fieldname5 = "f" . $field . "_filename";
 $sql_start = "update template set $fieldname1 = '$caption', $fieldname2 = '$type', $fieldname3 = '$mandatory', $fieldname4 = '$length', $fieldname5 = '$filen'";
 $sql_end = " where name = '$tplname'";
 $string = $sql_start . $sql_end;
 $result = mysql_query ($string);
 //print "$string";

 if (!$caption)
 {
               print " <b>Success, but NOTE:</b><br />";
               print "This field is no longer used on add_ad page, however ";
               print "if you have used this field earlier, and users";
               print " have added ads, these will still show a value without description on detail.php!";
               print " ";

 }
 else
 {
  print "<p /><b>$tplname, field $field is now Saved!</b>";
 }
}
}
?>
        <p />
         </td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
