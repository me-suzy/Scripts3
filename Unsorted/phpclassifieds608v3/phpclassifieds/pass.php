<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
// print("<h3>$name_of_site</h3>");

include_once("member_header.php");
check_valid_user();
?>
<form method="post" action="pass.php">
<table border="0" cellspacing="1" width="100%">
<tr>
    <td width="100%" valign="top">
     
    <b><? echo $la_new_pass1; ?></b> <p />

<?
if ($submit)
{
          if ($n_pass1 == $n_pass2)
         {
                        $result = mysql_query ("update $usr_tbl set password_enc = password('$n_pass1') where email = '$valid_user'");
                        if ($result)
                        {
                                  print " $la_pass_success ";
                                  
                        }
         }
         else
         {                // Due to late fix, if not found in language file, use english..
                          if (!$la_pass_not_match)
                          {
                              print " The passwords didnt match! ";
                          }
                          else
                          {
                              print " $la_pass_not_match ";
                          }

         }
}
?>


<table>
<tr>
 <td> <? echo $la_new_pass1 ?> </td>
 <td><input type="text" class="txt" name="n_pass1" /></td>
</tr>

<tr>
 <td> <? echo $la_new_pass2 ?> </td>
 <td><input type="text" class="txt" name="n_pass2" /></td>
</tr>

<tr>
 <td colspan="2"><br /><br />
  <input type="submit" name="submit" value="<? echo $la_change_pass ?>" />
 </td>
</tr>

</table>

<?


print "</td></tr></table></form>";
include_once("member_footer.php");
include_once("admin/config/footer.inc.php");
?>
