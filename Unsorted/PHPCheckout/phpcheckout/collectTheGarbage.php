<?php include("configure.php"); // for masthead constants ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>

      <?php echo "<TITLE>Collect the Garbage - phpcheckout - " . ORGANIZATION . "</TITLE>"; ?>
	</HEAD>

	<BODY>
<?php
/* 
The download module generates a new random named download file 
for every download. It is a security mechanism. The files collect 
over time, take up unnecessary space,  and periodically you need to 'collect the garbage'.
This file automatically collects the garbage in conjuction with using 
Windows task scheduler to launch a browser.
*/
function delete($file) {
	@chmod($file,0777); // the delete may be a success, even if the chmod fails, thus the @ to suppress warnings
	if (is_dir($file)) {
		$handle = opendir($file); 
		while($filename = readdir($handle)) {
			if ($filename != "." && $filename != "..") {
				delete($file."/".$filename);
			}
		}
		closedir($handle);
		@rmdir($file);
	}else{
		@unlink($file);
	}
} // end of function delete($file)
delete("temp");
include("header.php");
echo"<blockquote>";
if (!is_dir("temp")) {
	echo"<h3>Garbage Collected</h3>";
}else{
	echo"<h3>Problem Collecting Garbage!</h3>";
	echo"<p>Normally this happens when a user is still downloading a file.</p>";
}
echo"</blockquote>";
?>