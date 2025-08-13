<?php echo"\n\n\n";?>
<!-- start of oneRecord.php -->
<table width="100%">
   <tr>
      <td width="75%">
			<span style="font-size:14px;">
				<a href="<?php echo $yfurl;?>" target="_blank"><img src="appimage/iconBook.png" width="16" height="16" border=0 alt="<?php echo"Visit $yfcompany";?>"> <?php if($yfrank > "0"){echo"<b style=\"font-size:18;\">";}?><?php echo $yfcompany;echo"</b>";?></a>
				<br>Category <b><?php echo $yfcategory;?></b>
				<br><?php echo"<i>$yfdescription</i>";?>
				<br><br><?php echo $yffirstname;?> <?php echo $yflastname;?>
				<br><?php echo $yfaddress;?>
				<br><?php echo $yfcity;?> <?php echo $yfstateprov;?> <?php echo $yfcountry;?> <?php echo $yfpostalcode;?>
				
				<br><br><?php if($yfphone):?><img src="appimage/iconPhone.png" width="13" height="9" border=0 alt="Phone!"> Phone <?php if($yfareacode){echo"$yfareacode";}?> <?php echo $yfphone;?> <?php if($yfcell){echo"Cellular $yfcell";}?> <?php if($yffax){echo"Fax $yffax";}?><?php endif;?>
				<br><a href="yellowgoal.php?goal=Comment&yps=<?php echo $yfps;?>" target="_blank"><img src="appimage/iconEmail.png" width="13" height="10" border=0 alt="Send Email!"> Send Email</a>
				<br><a href="<?php echo $yfurl;?>"><img src="appimage/iconEarth.png" width="12" height="12" border="0" alt="Browse!"> Visit website: <?php echo $yfurl;?></a>
				<?php echo"<p style=\"font-size:10px;color:gray;\"></a>This listing is ckey# $yfckey. It runs until $yfexpires. <a href=\"mailto:" . WEBMASTER . "\">Contact " . COMPANY . " about Premium listings.</a></p>";?>
			</span>
		</td>
	</tr>
</table>
<!-- end of oneRecord.php -->
<?php echo"\n\n\n";?>