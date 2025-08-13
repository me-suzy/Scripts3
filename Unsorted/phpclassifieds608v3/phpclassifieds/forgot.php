<?
session_start( );

require("admin/config/header.inc.php");
require("admin/inc.php");
?>

<?
if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p>"); }
// print("<h2>$name_of_site</h2>");

srand ((double) microtime() * 1000000);
$new_pass = rand(900,9000);

if (ereg("^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email))
{
 $stop = 0;
}
else
{
 print " $la_error_msg21 <br />";
 $stop = 1;
}

$result = mysql_query ("select email from $usr_tbl where email = '$email'");
$count_results = mysql_num_rows($result);

if ($count_results > 0)
{
 $stop = 0;
}
else
{
 $stop = 1;

 print $la_forgot_notf;

}

if ($stop <> 1)
{
 $res = mysql_query ("update $usr_tbl set password_enc = password('$new_pass') where email = '$email'");
 if ($res)
 {
?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?
         echo $la_forgot;
?>
    </td>
  </tr>
</table>

<?         
         $sendto = $email;
         $from = $from_adress;
         $subject = $la_forgot_mail_subject;
         $message = "$la_forgot_mail_msg1 $new_pass. $la_forgot_mail_msg2 $la_forgot_mail_msg3";

         $headers = "From: $from\r\n";
         // send e-mail
         mail($sendto, $subject, $message, $headers);


 }
}


require("admin/config/footer.inc.php");
?>
