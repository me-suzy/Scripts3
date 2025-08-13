<?

#################################################################################################

#

#  project           	: phpListPro

#  filename          	: header.inc

#  last modified by  	: Erich Fuchs

#  e-mail            	: office@smartisoft.com

#  purpose           	: Header File

#

#################################################################################################



#  Processing Time Start

#################################################################################################

$proctime_start=microtime();



#  HTML Header Start

#################################################################################################

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">



<html>

 <head>

  <title><?echo $list_name;?> - rated TopList</title>

  <meta name="publisher" content="NetonE GmbH">

  <meta name="publisher-email" content="office@smartisoft.com">

  <meta name="robots" content="index, follow">

  <meta name="revisit-after" content="20 days">

<?

echo "

  $lang[metatag]

  <link rel=\"stylesheet\" type=\"text/css\" href=\"$style\">

";



if ($exiturl) {

echo"

  <script language=\"Javascript\">

  var exit=true;

  function exitwin() {

    if (exit) {

      open(\"$exiturl\", \"_blank\");

      self.focus();

    }

  }

  </script>



  <script>

    if (parent.frames.length > 0) {

      parent.location.href = self.document.location

    }

  </script>



 </head>



<body onUnload=exitwin()>

";

} else {

echo"

  <script>

    if (parent.frames.length > 0) {

      parent.location.href = self.document.location

    }

  </script>



 </head>



<body>

";

}



?>



<!-- BEGIN Your header stuff here -->



 <center>

 <table border="0" cellpadding="0" cellspacing="0" width="710" height="60">

  <tr>

   <td width="10">

    <img src=images/advertising.gif border="0">

   </td>

   <td width="504">

    <a href="mailto:<?echo $admin_email;?>"><img src=images/advertise.jpg border="0"></a>

   </td>

   <td width="202">

    <a href="http://rated100.com" target="_blank"><img src="images/rt_logo01.gif" border="0" width="234" height="60"></a>

   </td>

  </tr>

 </table>

 <br>



<!-- END Your header stuff here -->
