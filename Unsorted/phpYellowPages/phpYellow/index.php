<?php 
/* Start of CMS Bridge
This section incorporate CMS Bridge middleware. See
http://portal.dbserve.net/codeshare/cms_bridge.htm
for instructions and information.
*/
if (isset($cms_bridge)) {
setcookie("cms_bridge_cc","$cms_bridge");
include "cms_bridge/sync_user.php";
} // End of CMS Bridge ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>

<HEAD>
	<script language="Javascript" src="yellow.js"></script>
	<script language="Javascript">loadCSSFile();</script>

    <?php require_once("util.php");?>

    <?php echo "<TITLE>phpYellow Home - " . YOURPHPYELLOWNAME . " - " . COMPANY . " Easily create Yellow Page business directories for B2B sales! phpYellow Pages from Dreamriver.com - Build a Directory - A Dreamriver.com Software Product - php software mysql database software download apps</TITLE>"; ?>

	<META NAME="keywords" CONTENT="phpYellow, Pages, php, Yellow, directory, build, Search, for, add, change, or, delete, your, product, or, service, listing(s), on, our, Yellow, Pages, It's, fast, free.">
	<META NAME="description" CONTENT="Home Page for <?php echo COMPANY;?>. Easily create Yellow Page business directories for your website! Search the total database listings. View by category. Add, change or delete your product or service listing(s) on phpYellow Pages. While you're here take a look at our Feature Listing and the Advanced Search. phpYellow Pages is the best way to showcase your product and service listings. Free to download.">
	<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com Email: richardc@dreamriver.com">
	<META NAME="GENERATOR" CONTENT="Blood, Sweat & Tears">
	</HEAD>

<BODY>

<?php if(SHOWAD == "yes"): // conditionally display the Premium Ad ?>
	<!--green grapes berries black yellow pages ... the natural choice -->
	<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" WIDTH=468 HEIGHT=60>
		<PARAM NAME=movie VALUE="appimage/yellowPagesBanner.swf"> <PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF> 
		<EMBED src="appimage/yellowPagesBanner.swf" quality=high bgcolor=#FFFFFF  WIDTH=468 HEIGHT=60 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></EMBED>
	</OBJECT>
<?php endif;?>

<?php include("header.php");?>



<h1><?php echo YOURPHPYELLOWNAME;?></h1>


<table width="100%" class="yellow">
	<tr>
		<!-- optionally show the premiumFeatureListing.php file  -->
			<?php 
			if(defined("USEFEATURELISTING")) {
				if(USEFEATURELISTING == "yes") {
					if(defined("FEATURELISTINGNUMBER")) {
						if(FEATURELISTINGNUMBER != NULL) {
							echo"<td valign=\"top\">";
							include("premiumFeatureListing.php"); // show feature listing
						} // !null
					} // defined
				} // use featurelisting
			}else{
				echo"<td>&nbsp;"; // for the Standard Edition
			}?>
		</td>
	</tr>




	<tr width="100%">
		<!-- display the index of listings -->
		<td width="100%" align="left" valign="top">
			<?php 
			if( USECLICKBYCATEGORY == "yes" ) {
				include("clickByCategoryForm.php"); // includes a static list of categories
			}else{
				include("indexDynamicListings.php"); // creates a dynamic index of listings
			}?>
		</td>
	</tr>



	
	<tr>
		<!-- put the search form in a table row -->
		<td valign="middle">
			<?php 
				if(defined("SETRANK")) {
					if (file_exists("premiumSearchForm.php")) {
						include("premiumSearchForm.php");
					}
				}else{
					include("searchForm.php");
				}
			?>
		</td>
	</tr>




	<!-- put the footer in a table row -->
	<tr>
		<td>
			<?php include("footer.php");?>
		</td>
	</tr>
</table>





