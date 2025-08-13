<?php echo"\n\n\n";?>
<!-- adminOneRecord.php -->
<table width="100%">
   <tr>
      <td width="85%%">
<span style="font-size:14px;">
<a href="<?php echo $yfurl;?>" target="_blank"><img src="appimage/iconBook.png" width="16" height="16" border=0 alt="<?php echo"Visit $yfcompany";?>"> <?php if($rank > "0"){echo"<b style=\"font-size:18;\">";}?><?php echo $yfcompany;echo"</b>";?></a>
<br>Category <b><?php echo $yfcategory;?></b><br>
<?php echo"<i>$description</i><br>";?>
<?php echo $yffirstname;?> <?php echo $yflastname;?><br>
<?php echo $yfaddress;?><br>
<?php echo $yfcity;?> <?php echo $yfstateprov;?> <?php echo $yfcountry;?> <?php echo $yfpostalcode;?><br>
<br>
<?php if($yfphone):?><img src="appimage/iconPhone.png" width="13" height="9" border=0 alt="Phone!"> Tel <?php if($yfareacode){echo"$yfareacode";}?> <?php echo $yfphone;?> <?php if($yfcell){echo"Cellular $yfcell";}?> <?php if($yffax){echo"Fax $yffax";}?><br><?php endif;?>
<a href="mailto:<?php echo $yfemail;?>"><img src="appimage/iconEmail.png" width="13" height="10" border=0 alt="Email!"> <?php echo $yfemail;?></a><br>
<a href="<?php echo $yfurl;?>"><img src="appimage/iconEarth.png" width="12" height="12" border=0 alt="Browse!"> <?php echo $yfurl;?></a><br>




<?php 
if(defined("PAIDLISTINGS")) {	
	if(PAIDLISTINGS == "yes") {
		switch($rank) {
			case 0:
				include("premiumGoButton.php");
			   echo"<b>Password: $yfpassword</b>";
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
}
echo"<p style=\"font-size:10px;color:gray;\">On correspondence quote ckey#$ckey yps#$yfps Expires $expires </p>";?>
</span>
</td>



<?php 
if(defined("USELOGOS")):
	if( USELOGOS == "yes" ): // show the logo if applicable
		echo"\n\n<td width=\"15%\" align=\"left\" valign=\"middle\">";
			if(!empty($yflogo)) {
				// show the logo
				echo"<a href=\"$yfurl\"><img src=\"" . IMAGESFOLDER . "/" . "$yflogo\" border=\"0\" alt=\"Click here to visit this website!\"></a>";
			}
		echo"\n\n</td>";	
	endif;
endif;?>


</tr></table>
<?php include("adminGoalControls.php");?>
<br><hr>
<!-- end of adminOneRecord.php -->
<?php echo"\n\n\n";?>