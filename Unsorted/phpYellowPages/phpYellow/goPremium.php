<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>

	<script language="Javascript" src="yellow.js"></script>
	<script language="Javascript">loadCSSFile();</script>

    <?php require("util.php"); ?>
    <?php echo "<TITLE>phpYellow - " . YOURPHPYELLOWNAME . " - " . COMPANY . "</TITLE>"; ?>

	<META NAME="keywords" CONTENT="phpYellow, php, Yellow, Premium, Yellow, customers, enjoy, prominent, First, Page, display, results, Yellow, Pages">
	<META NAME="description" CONTENT="Enjoy the advantages of a premium listing and upgrade today! Premium includes: *your listing is positioned higher - it could be the first on the page! *your listing appears with prominent extra formatting *upgrade for one low price per listing *enjoy quick processing of your request *choose from Preferred or First Page positions">
	<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com Email: richardc@dreamriver.com">
	<META NAME="GENERATOR" CONTENT="Blood, Sweat & Tears">

	</HEAD>

<BODY>

<?php include("header.php");?>

<?php include("flags.php"); // display the flags ?>

<h1>Go Premium!</h1>

Enjoy the advantages of a premium listing and upgrade today! Premium includes:


<table><tr>
<td>
<ol>
<li>your listing is positioned higher - it could be the first on the page!
<li>your listing appears with prominent extra formatting
<li>your internet links - email and website - are displayed
<li>your listing is linked to a map
<li>upgrade for one low price per listing
<li>your business image displayed [logo or other picture]
<li>choose from Preferred or First Page positions
</ol>
</td>
<td><?php //include("answerplus.php");?>&nbsp;</td>
</tr></table>



<h2>Choose Preferred or First Page</h2> 
<h3>Preferred</h3>
<p>Preferred Listings always enjoy better page position than 
standard listings. Your Preferred listing appears higher on 
all search results. Why accept a listing at the 
bottom of the page when you can get optimum results which are priced very 
attractively?</p>


<h3>First Page</h3>
<p>First Page Listings always enjoy a first page position. Standard 
 listings could be pages and pages down at the bottom of search 
results. Your First Page listing is your guarantee of optimum exposure 
and optimum results for your listing. Why accept a listing at the 
bottom of the page when you can get optimum results which are priced very 
attractively?</p>



<h1>Frequently Asked Questions</h1>

<h2>Why Would I want a First Page Listing?</h2>
<p class="yellow">
Research indicates that surfers are more likely to click on listings 
which appear on the first, second or third pages. Let's face it, these 
surfers are just plain LAZY! Surfers like things to be made easy for 
them - after all - wouldn't you like things to be easy for you?

<br><br>

<b>For a nominal payment we can make your listing a First Page or Preferred page postion.</b> 
<br><br>


<?php 
if(file_exists("premiumPriceChart.php")):	include("premiumPriceChart.php");?>
<?php else:?>


<!-- PUT YOUR PRICE CHART BELOW HERE -->
<!-- start of manual made price chart -->
<br>
<span style="font-size:28px;color:yellow;background-color:black;font-weight:bold;font-style:italic;">
Place your price chart right here!</span><br>
<span style="font-size:28px;color:green;font-weight:bold;font-style:italic;">
<a href="http://www.dreamriver.com/phpYellow/goPremium.php#pricechart">See DreamRiver for examples</a>
</span><br><br>
<!-- end of manual made price chart -->
<!-- PUT YOUR PRICE CHART ABOVE HERE -->


<?php endif;?>


</p>

<h2>How can I order a Premium Upgraded Listing?</h2>



<?php if(defined("SETRANK")):?>
<ol>
	<li>make a new search
	<li>find your listing
	<li>click on the &quot;Improve My Listing!&quot; button
</ol>
<?php else: echo"<a href=\"mailto:" . WEBMASTER . "\">Contact us by email</a>"; endif;?>



<p><i>It's that e a s y !!!</i></p>





<br><br>

<?php 
if(defined("SETRANK")) {
	if (file_exists("premiumSearchForm.php")) {
		include("premiumSearchForm.php");
	}
}else{
	include("searchForm.php");
}?>

<p></p>


<?php include("footer.php");?>
