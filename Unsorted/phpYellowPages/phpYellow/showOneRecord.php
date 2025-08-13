<?php 
//<!-- START OF showOneRecord.php -->
switch($yfrank) {
	case 2:
		if(file_exists("premiumListingFirstPage.php")) {
			echo "<p>";
			include("premiumListingFirstPage.php");
			echo "<hr></p>";
		}
		break;	
	case 1:
		if(file_exists("premiumListingPreferred.php")) {
			echo "<p>";
			include("premiumListingPreferred.php");
			echo "<hr></p>";
		}
		break;	
	case 0:
		if(defined("SETRANK")) {
			if(SETRANK == "yes") {
				if(file_exists("premiumListingBasic.php")) {
					include("premiumListingBasic.php");
					echo "<hr></p>";
				}
			}
		}else{
			echo "<p>";
			include("oneRecord.php");
			echo "<hr></p>";
		}
		break;	
	default:
		echo"\n\nNo rank defined. Notify the webmaster.\n\n";
// <!-- END OF showOneRecord.php -->
}?>
