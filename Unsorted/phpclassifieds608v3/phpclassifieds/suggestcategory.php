<?
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
          <td width="100%"><? print("$la_suggestion_sent !"); ?></td>
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
				            $sendto =  $from_adress_mail;
				            $from = $from_adress_mail;
				            $subject = "Suggested category";
				            $message = "Suggested category: $kommentar";
				            $headers = "From: $from\r\n";
				            // send e-mail
				            mail($sendto, $subject, $message, $headers);
            			}
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

?>

<form method="post" action="suggestcategory.php">
<table border="0" width="100%" cellspacing="1" cellpadding="0" bgcolor="#C8D3E5">
  <tr>
    <td width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="8">
        <tr>
          <td width="100%"><b><? echo $la_suggest ?></b></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="8" bgcolor="#FFFFFF">
        <tr>
          <td width="100%"><u><br>
            </u><? echo $la_suggest ?><u><br>
            <br />
            </u>
    <textarea rows="6" name="kommentar" cols="50"></textarea>
            <br>
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
