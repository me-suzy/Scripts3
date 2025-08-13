<?php echo"\n\n\n";?>
<!-- start of highlightRecord.php -->
<table width="100%" class="yellow"><tr>
<td>
<span style="font-size:14px;">
<a href="<?php echo $yfurl;?>" target="_blank"><b style="font-size:18;"><?php echo $yfcompany;echo"</b>";?></a>
<br>Category <b><?php echo $yfcategory;?></b><br>
<?php echo"<i>$yfdescription</i><br>";?>
<?php echo $yffirstname;?> <?php echo $yflastname;?><br>
<br>
<?php echo $yfaddress;?><br>
<?php echo $yfcity;?> <?php echo $yfstateprov;?> <?php echo $yfcountry;?> <?php echo $yfpostalcode;?><br>
Tel <?php if($yfareacode){echo"( $yfareacode )";}?> <?php echo $yfphone;?> Cellular <?php echo $yfcell;?> Fax <?php echo $yffax;?><br>
Email <a href="mailto:<?php echo $yfemail;?>"><?php echo $yfemail;?></a><br>
Website <a href="<?php echo $yfurl;?>"><?php echo $yfurl;?></a><br>


<?php if(PAIDLISTINGS == "yes") {
	// echo"Position ";
	switch($yfrank) {
		case 0:
			//include("premiumGoButton.php");
			break;
		case 1:
			echo"<span style=\"color:orange;font-size:16px;font-family:serif;\">$yfcompany is a Preferred customer!</span> ";
			break;
		case 2:
			echo"<br><span style=\"color:gold;background-color:black;font-size:20px;font-family:serif;\">First Page Customer!</span> ";
			break;
		default:
			echo" <b>No rank set.</b> ";
	}
}
echo"<p style=\"font-size:10px;color:gray;\">On correspondence with " . COMPANY . " quote ckey#$yfckey Expires $yfexpires </p>";?>
</span>
</td>



<?php if( USELOGOS == "yes" ): // show the logo if applicable
	echo"<td width=\"30%\" align=\"left\" valign=\"middle\">";
	//if( $yfstatus == "approved" && $yfexpires > $todaysDate && $yfrank > 0 ) {
		if(!empty($yflogo)) {
			// show the logo
			echo"<a href=\"$yfurl\"><img src=\"" . IMAGESFOLDER . "/$yflogo\" border=\"0\" alt=\"Click here to visit!\"></a>";
         //echo"yflogo is: " . $yflogo;
		}
	//}
	echo"</td>";	
endif;?>


</tr></table>
<!-- end of highlightRecord.php -->
<?php echo"\n\n\n";?>