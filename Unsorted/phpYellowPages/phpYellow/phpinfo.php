<?php require_once("adminOnly.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>

	<script language="Javascript" src="yellow.js"></script>
	<script language="Javascript">loadCSSFile();</script>


    <?php echo "<TITLE>phpYellow - " . YOURPHPYELLOWNAME . " - phpInfo - " . COMPANY . "</TITLE>"; ?>

	<META NAME="keywords" CONTENT="phpYellow, php, Yellow, Search, for, add, change, or, delete, your, product, or, service, listing(s), on, our, Yellow, Pages, It's, fast, free.">
	<META NAME="description" CONTENT="Search for, add, change or delete your product or service listing(s) on phpYellow. It's fast and free.">
	<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com Email: richardc@dreamriver.com">
	<META NAME="GENERATOR" CONTENT="Blood, Sweat & Tears">

	</HEAD>

<BODY>

<?php 
include("header.php");
echo"<h1>" . YOURPHPYELLOWNAME . "</h1>";
echo "<h2>Results of Function phpinfo()</h2>";
phpinfo();
include("footer.php");
?>
