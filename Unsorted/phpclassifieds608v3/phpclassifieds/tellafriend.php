<?
session_start();
include_once("admin/inc.php");
?>

<html
<head>
<link rel="stylesheet" href="style.css" type="text/css" />
<title><? echo $la_tell_a_friend ?></title>
</head>
<body topmargin="10" leftmargin="10" bgcolor="#FFFFFF" marginwidth="10" marginheight="10">
<?
$referers_list[0] = $ref1;
$referers_list[1] = $ref2;

$temp = explode("/",getenv("HTTP_REFERER"));
$referer = $temp[2];

if ($submit)
{


        if ($avsender_epost AND $mottaker_epost)
        {
        	
             	// Referer check, and then send
 				for ($y=0; $y < count($referers_list); $y++) 
				{
					if (eregi ($referers_list[$y], $referer)) 
					{
        				if (!$legal)
        				{    
							$legal = 1;
        	

?>

<table border="0" width="100%" cellspacing="1" cellpadding="0" bgcolor="#C8D3E5">
  <tr>
    <td width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="8">
        <tr>
          <td width="100%"><? print("$la_message_sent $mottaker_epost !"); ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="8" bgcolor="#FFFFFF">
        <tr>
          <td width="100%" align="right"><a href="javascript:window.close();"><img src="images/noimage.gif" width="16" height="15" border="0" align="absmiddle"><? echo $la_close ?></a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>


<?

//        				    print("<p />$la_message_sent $mottaker_epost !<p />");
				            $melding = strip_tags($kommentar);
				            $sendto = $mottaker_epost;
				            $from = $avsender_epost;
				            $subject = "$la_message_subject";
				            $message = "$avsender_epost $la_message_msg:\n http://$url/detail.php?siteid=$id\n\n $la_message_comment:\n ------------\n$melding\n\n\n$la_sent_from: http://$url";
				            $headers = "From: $from\r\n";
				            // send e-mail
				            mail($sendto, $subject, $message, $headers);
            			}
    				}
    				else 
    				{
    					
    				}
    	
				}
				
				if (!$legal)	
				{
    						print "<p /> <b>Error:</b><br />The domain is not in my allowed referer list! ";
				}
				
				// Refereer check end
            
            
        }

        else
        {
			print("<b>$la_tell_error</b>");
        }


}
else
{

?>

<form method="post" action="<?php echo $PHP_SELF?>">
<table border="0" width="100%" cellspacing="1" cellpadding="0" bgcolor="#C8D3E5">
  <tr>
    <td width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="8">
        <tr>
          <td width="100%"><b><? echo $la_tell_a_friend ?>&nbsp;<? print "$adtitle";  ?> </b>(<? echo $id ?>)
<br /><? echo $la_tell_welcome ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="8" bgcolor="#FFFFFF">
        <tr>
          <td width="100%"> <u><? echo $la_reciever ?><br />
    </u>
    <input type="text" name="mottaker_epost" size="30">
          </td>
        </tr>
        <tr>
          <td width="100%"><u><? echo $la_sender ?><br />
    </u>
    <input type="text" name="avsender_epost" size="30">
          </td>
        </tr>
        <tr>
          <td width="100%"><u><? echo $la_message_comment ?><br />
    </u>
    <textarea rows="6" name="kommentar" cols="50"></textarea>
          </td>
        </tr>
        <tr>
          <td width="100%"><input type="submit" value="Send" name="submit">
          </td>
        </tr>
        <tr>
          <td width="100%" align="right">
 <a href="javascript:window.close();"><img src="images/noimage.gif" width="16" height="15" border="0" align="absmiddle"><? echo $la_close ?></a>
<?

}

?>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<input type="hidden" name="id" value="<? echo $id ?>">
</form>





</body>
</html>
