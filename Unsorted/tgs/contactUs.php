<?php
session_start();
ob_start();
include("includes/header.php");
include("includes/common.php");
?>
<b>Contact <?php print $sitename; ?></b>
<div align="left"><br>

If you would like to contact us, our email address is

<a href="mailto:<? echo $contactEmail; ?>">
<b><? echo $contactEmail; ?></b></a>, we look forward to answering
your questions.<br><br>

Thank You

</div>
<?php
include("includes/footer.php");
?>