<?php
session_start();
ob_start();
include("includes/config.php");
include("includes/header.php");

$st = "select * from StatPayPalCharges";
$rs = mysql_query($st) or die(mysql_error());
$row = mysql_fetch_array($rs);
$plana = $row['planA'];
$planb = $row['planB'];

?>
<p align="center"><b><?php print $sitename; ?></b></p>
<p align=justify>
&nbsp;&nbsp;&nbsp;If you are looking for detailed traffic logging tools for your website and do not want to pay the outrageous prices
that counter services are charging, you have come to the right place. <?php print $sitename; ?> is a full featured 
stats tracking service that uses state-of-the-art software to track and log all website activity. With 
<?php print $sitename; ?> you can cancel any time you like, and unlike other services, there is <b>NO</b> long term 
obligations; period.
</p>
<p align=left>
Our traffic analysis service is fast, easy to use, and displays your traffic stats in an easy to read, printable format. 
<?php print $sitename; ?>'s state of the art software collects all the information about your site you will ever need,
plain and simple.
</p><p align=left>
If you have any questions about our service you can view our FAQ page, or feel free to <a href="contactUs.php"><b>Contact
Us</b></a> if the answer to your Question wasn't answered there, Thank You.
</p>
<?php
include("includes/footer.php");
?>
