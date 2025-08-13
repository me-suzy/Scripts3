<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
if (!$special_mode) { include("navigation.php"); }

//{ print("$menu_ordinary"); }
//require ("link_title.php");

$referers_list[0] = $ref1;
$referers_list[1] = $ref2;

$temp = explode("/",getenv("HTTP_REFERER"));
$referer = $temp[2];


if ($submit)
{
 $sql = "select ad_username from $ads_tbl where siteid = $siteid";
 $sql_resultads = mysql_query ($sql);
 $num_ads =  mysql_num_rows($sql_resultads);

 for ($i=0; $i<$num_ads; $i++)
 {
      $row = mysql_fetch_array($sql_resultads);
      $email = $row["ad_username"];
 }
 // setup variables
 if ($epost)
 {
	
 	// Referer check, and then send
 	for ($y=0; $y < count($referers_list); $y++) 
	{
		
		if (eregi ($referers_list[$y], $referer)) 
		{
        	if (!$legal)
        	{    
				$legal = 1;
 	
 				$sendto = "$email";
				$from = "$epost";
				$subject = "$la_email";
				$message = "$la_email_body";
				$headers = "From: $from\r\n";
				// send e-mail
				mail($sendto, $subject, $message, $headers);
				print "<p /> <b>$la_sent_message</b> ";
			}
    	}
    	
    	
	}
	
	if (!$legal)	
	{
    			$wrong = 1;
    			print "<p /> <b>Error:</b><br />The domain is not in my allowed referer list! ";
    }
	// Refereer check end
			
 }
 else
 {
 	print "<p /> <b>$la_contact_mail</b> ";	
 }

}
else
{
?>

<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr><td>

<form method="post" action="contact.php">

<?
  $sql = "select ad_username from $ads_tbl where siteid = $siteid";
  $sql_resultads = mysql_query ($sql);
  $num_ads =  mysql_num_rows($sql_resultads);

  for ($i=0; $i<$num_ads; $i++)
  {
      $row = mysql_fetch_array($sql_resultads);
      $ad_username = $row["ad_username"];
  }

        $sql_eier = "select name from $usr_tbl where email = '$ad_username'";
        $result = mysql_query ($sql_eier);



        while ($row = mysql_fetch_array($result))
        {
                $name_own = $row["name"];



?>
<input type="hidden" name="name" value="<? echo $name_own ?>">
<input type="hidden" name="siteid" value="<? echo $siteid ?>">

<table border="0" cellspacing="1" width="80%">
  <tr>
    <td width="100%"><span class="title"><? echo $la_kontakt ?></span><br><br>
       <? print($la_main_message); ?> <b><? echo $name_own ?></b>.<br />
      &nbsp; 
      <table border="0" cellspacing="1" width="100%">
        <tr>
          <td width="50%" valign="top"> <? echo $add_user_name ?> </td>
          <td width="50%" valign="top"> <input type="text" name="navn" size="42" style="font-size: 8pt; font-family: Verdana"> </td>
        </tr>
        <tr>
          <td width="50%" valign="top"> <? echo $add_user_email ?> </td>
          <td width="50%" valign="top"> <input type="text" name="epost" size="42" style="font-size: 8pt; font-family: Verdana"> </td>
        </tr>
        <tr>
          <td width="50%" valign="top"> <? echo $la_contact_msg ?> </td>
          <td width="50%" valign="top"><textarea rows="5" name="beskjed" cols="32"></textarea></td>
        </tr>
      </table>
      <p> <input type="submit" value="<? echo $la_kontakt ?>" name="submit" style="font-size: 8pt; font-family: Verdana" /> </td>
  </tr>
</table>
</form>

    </td>
  </tr>
</table>

<?
};
}
require("admin/config/footer.inc.php");
?>
