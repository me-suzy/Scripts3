This update adds the much wanted "Re-send Activation Email" feature :o)

To install:

1) Add the following code to your language files:

$activationemail_password = "Your chosen password";
$activationemail_sent = "Activation email sent";

2) Replace "yourphpmanagerfolder/admin/clientmanager/modify.php" with the new modify.php file

3) Add the following code to your "yourphpmanagerfolder/templates/admin/clientmanager/viewclients.inc" file:

<button onclick="window.location='modify.php?id=<?php echo $id ?>&activationemail=yes'" class="formfield">Re-Send Activation Email</button>