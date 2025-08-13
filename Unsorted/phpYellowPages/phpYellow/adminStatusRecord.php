<?php echo"\n\n\n";?>
<!-- start of adminStatusRecord.php -->
<?php
switch($yfrank) {
	case 0:
		$listingPosition = "Basic";
		break;
	case 1:
		$listingPosition = "<b style=\"color:orange;font-size:16px;\">Preferred</b>";
		break;
	case 2:
		$listingPosition = "<b style=\"color:red;font-size:18px;\">First Page</b>";
		break;
	default:
		echo" <b>No rank set.</b> ";
}?>
<table width="100%">
<tr>
<!-- Status: membership expiry length Lastname ID's-->
<td colspan=3><?php echo"<b>$status $listingPosition</b> - $yfcategory - <i>Contact Key#$yfps Category Key#$ckey Expires: $yfexpires</i>";?></td>
</tr>



<tr>
<!-- data -->
<td>
<a href="<?php echo $yfurl;?>" target="_blank"><?php if($yfrank > "0"){echo"<b>";}?><?php echo $yfcompany;?><?php if($yfrank > "0"){echo" - Member!</b>";}?></a><br> 
<?php if($yfrank > "0"):?><i><?php echo $yfdescription;?></i><br><?php endif;?>
<?php echo $yffirstname;?> <?php echo $yflastname;?><br>
<?php echo $yfaddress;?><br>
<?php echo $yfcity;?> <?php echo $yfstateprov;?> <?php echo $yfcountry;?> <?php echo $yfpostalcode;?><br>
Tel <?php if($yfareacode){echo"( $yfareacode )";}?> <?php echo $yfphone;?> Cellular <?php echo $yfcell;?><br>
Fax <?php echo $yffax;?><br>
Email <a href="mailto:<?php echo $yfemail;?>"><?php echo $yfemail;?></a><br>
Website <a href="<?php echo $yfurl;?>"><?php echo $yfurl;?></a>
</td>

<!-- image -->
<td><?php if(!empty($yflogo)):?><img src="<?php echo IMAGESFOLDER . "/" . $yflogo;?>" border="0" alt="<?php echo $yflogo;?> - <?php echo $yfdescription;?>"><?php else:?>&nbsp;<?php endif;?></td>

<!-- controls -->
<td align="right" valign="middle">
<?php include("adminSetStatusControls.php");?>
</td></tr>
</table>
<hr>
<!-- end of adminStatusRecord.php -->