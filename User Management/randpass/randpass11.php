<?php
#--------------------------------------------------------------------------------------------------#
# This script was created by Nick Presta (http://nickpresta.ath.cx, http://infinity-stuff.com).    #
# This script is released under the Creative Commons license. You may use this script for personal #
# private use, only. You may make changes to this script but you may not claim this script as your #
# own work under any circumstances without explicit written permission from me (Nick Presta -      #
# nick1presta -at -gmail.com). If you wish to contact me about this script, feel free to email     #
# me.                																			   #
#																								   #
# This script was made to create random passwords for messageboards, user verifiction, etc.        #
# The style is located in the <head> section between the <style type="text/css"> and </style>. It  #
# can be fully edited to suit your needs; even linked externally.								   #
#--------------------------------------------------------------------------------------------------#

# Edit the variables below:

$max_num_length = "50"; // This is the maximum length of the password (characters).
$max_num_num = "50"; // This is the maximum number of passwords that can be generated.

# </end edit>

$version = "1.00"; // Do not edit version number.
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Random Password Generator</title>
	<style type="text/css">
	body { width: 50%; margin: 10px auto; padding: 0; border: 0;}
	h1 { font: bold 75% Verdana, Arial, sans-serif; text-align: center; }
	table { width: 100%; border: 2px solid #000; padding: 5px; background: #069; color: #000; }
	table p { margin: 0; font-size: 65%; text-align: center; }
	table td { background: #A5A5CD; padding: 2px 2px 2px 10px; color: #000; }
	table td + td { text-align: right; }
	</style>
</head>
<body>
<h1>Random Password Generator - Guaranteed 100% unique and random!</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table>
<tr>
	<td>Password Length:</td>
	<td><select name="length">
	<option><?php echo $_POST['length']; ?></option>
		<?php
		for($num = 4; $num <= $max_num_length; $num++) {
			echo "<option>" .$num . "</option>
			";
		} 
		?>
</select></td>
</tr>
<tr>
	<td>Number of Passwords:</td>
	<td><select name="number">
	<option><?php echo $_POST['number']; ?></option>
	<?php
	for($num = 1; $num <= $max_num_num; $num++) {
		echo "<option>" .$num . "</option>
		";
	} 
	?>
</select></td>
</tr>
<tr>
	<td>Upper and Lower Case?</td>
	<td><input type="checkbox" name="checked-upper" <?php if($_POST['checked-upper']) { echo "checked=\"checked\""; } ?> /></td>
</tr>
<tr>
	<td>Symbol Replacement?</td>
	<td><input type="checkbox" name="checked-sym" <?php if($_POST['checked-sym']) { echo "checked=\"checked\""; } ?> /></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" name="submit" value="Generate" /></td>
</tr>
<tr>
<td colspan="2">
<p>Random Password Generation created by <a href="http://nickpresta.ath.cx" title="Nick Presta's Website">Nick Presta</a>. Version: <?php echo $version; ?></p>
</td>
</tr>
</table>
</form>

<?php
	if($_POST['submit']) {
		$length = $_POST['length'];
		$number = $_POST['number'];
		$search1 = array("a", "b", "c", "d", "e", "A", "B", "C", "D", "E");
		$replace1 = array("@", "&gt;", "&lt;", "\\", "#", "@", "&gt;", "&lt;", "\\", "#");

		echo "<p>You have requested " . $number . " password(s) with " . $length . " character(s)"; 
		if($_POST['checked-upper']) { echo " that contains upper and lower case characters"; }
		if($_POST['checked-sym']) { echo " and contains symbols"; }	
		echo ".</p>";
	
		echo "<h2>Passwords</h2>";
		echo "<ol>";
		for($num = 0; $num < $number; $num++) {
			$password = sha1(uniqid(time())) . sha1(uniqid(time())) . sha1(uniqid(time())) . sha1(uniqid(time())) . sha1(uniqid(time())); 
				if($_POST['checked-upper']) {
					for ($i = 0; $i < strlen($password); $i++) {
						$password{$i} = ($i % 2 ? strtoupper($password{$i}) : strtolower($password{$i}));
					}
				} // This will convert every other letter to uppercase/lowercase.
				if($_POST['checked-sym']) {
					for ($f = 0; $f < strlen($password); $f++) {
						$password{$f} = ($f % 2 ? str_replace($search1, $replace1, $password{$f}) : str_replace($search, $replace, $password{$f}));
					}
				} // This will convert specific characters to symbols.				
				if (strlen($password) > $length) { 		
					echo "<li><span style=\"font-family: monospace, courier;\">" . substr($password, 0, $length) . "</span></li>
				"; 				
				} 
				else {
					echo "<li><span style=\"font-family: monospace, courier;\">" . $password . "</span></li>
				";
				}
		}
	echo "</ol>";
	}
?>
</body>
</html>