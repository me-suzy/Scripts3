<!-- start of loginHelp.php -->
<h1>Help</h1>
<p>This help file has basic information about your options in this Login module:</p>


<h3>Retrieve Contact Info</h3>
<ul>
	<li>get your contact data and change it
	<li>set your privacy level
	<?php if ( OFFERNEWSLETTER == "yes" ){echo"<li>subscribe or unsubscribe to the newsletter";}?>
</ul>


<h3>Send Feedback</h3>
<ul>
	<li>send your comments to <?php echo ORGANIZATION;?></li>
	<li>request support from <?php echo ORGANIZATION;?></li>
	<li>provide us with your valued feedback</li>
</ul>


<h3>View My Licenses</h3>
<ul>
	<li>shows you any product purchases you made with <?php echo ORGANIZATION;?></li>
	<li>retrieve the current version of any <?php echo ORGANIZATION;?> retail product for which you have a current license</li>
</ul>
<!-- end of loginHelp.php -->