<?php
// *************************************************************************************************
// Title: 		PHP AGTC-IP Ban v1.0a
// Developed by: Andy Greenhalgh
// Email:		andy@agtc.co.uk
// Website:		agtc.co.uk
// Copyright:	2005(C)Andy Greenhalgh - (AGTC)
// Licence:		GPL, You may distribute this software under the terms of this General Public License
// *************************************************************************************************
//
session_start();
$domain = GetHostByName($REMOTE_ADDR); // GET YOUR IP ADDRESS
include "inc.php"; // INCLUDE THIS FILE FOR PASSWORD
// IF LOG OUT IS REQUESTED THEN THE SESSION WILL UNSET BELOW
if ($action == 'logout') { 
session_unset();
echo "Logged out !!";
exit();
}
// CHECK TO SEE IF YOUR LOGGED IN
if ($_POST['Login'] == 'Login') { 
if ($pass != $password) {$msg = "You must type in the correct password";}
if ($pass == $password) 
{$_SESSION['logged'] = 'true';
$action = '';}
} 

if (!$_SESSION['logged'] == 'true') { 
echo $_SESSION['logged'];
echo "<link href='IPstyle.css' rel='stylesheet' type='text/css'>
<div align='center'>
  <p>PHP - AGTC IP Ban v1.0a </p>
  <table width='50%'  border='0'>
  <tr>
    <th scope='col'><form action='' method='post' name='form1'>
  <div align='center'>Enter The Admin Password<br>
    <input name='pass' type='password' size='30' maxlength='30'>
    <input name='Login' type='submit' value='Login'>
  </div>
</form>	</th>
  </tr>
</table></div>"; 
}
// THIS IS WHERE WE CHECK BEFORE WE WRITE NEW BANNED IP TO FILE ip.txt
if($_SESSION['logged']) {
if (isset($_POST['Submit'])) {
$addip = $_POST['ipadd'];
$filename = 'ip.txt';
$content = "$addip\n";

// DOES IT EXIST, IF SO PREPARE TO APPEND
if (is_writable($filename)) {
      if (!$handle = fopen($filename, 'a')) {
         echo "Cannot open file ($filename)";
         exit;
   }

   // WRITE $content TO $ip.txt
   if (fwrite($handle, $content) === FALSE) {
       echo "Cannot write to file ($filename)";
       exit;
   }
   
   $msg = "Success, wrote $content to IP ban database";
   
   fclose($handle);

} else {
   $msg = "The file $filename is not writable";
}
}
?>
<!-- ADMIN PAGE -->
<link href="IPstyle.css" rel="stylesheet" type="text/css">
<div align="center">
  <p>PHP - AGTC IP Ban v1.0a </p>
  <p>Your IP address is <?php echo $domain; ?> </p>
  <table width="50%"  border="0">
  <tr>
    <th scope="col"><form action="" method="post" name="form1">
  <div align="center">Enter the IP address you wish to ban<br>
    <input name="ipadd" type="text" size="30" maxlength="30">
    <input name="Submit" type="submit" value="Submit">
  </div>
</form>	</th>
  </tr>
</table>
  <p><a href="?action=logout">LOG OUT </a></p>
  <p><?php echo $msg; ?></p>
</div>
 
<div align="center">
  <p>&nbsp;</p>
  <table width="30%"  border="0" cellspacing="2" cellpadding="2">
  <tr><th><span class="style1">List of banned IP's</span></th>
  
  </tr>  
      <?php $theFile = file_get_contents('ip.txt');
$lines = array();
$lines = explode("\n", $theFile);
$lineCount = count($lines);
for ($i = 0; $i < $lineCount; $i++){ ?>
<tr> <th scope="col"><div align="left"><span class="style1"><?php echo $lines[$i];}?>  
  </span></div></th>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>
<?php } echo "<center>$copyright</center>"; ?>