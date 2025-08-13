<?php echo"\n\n\n";?>
<!-- Start of addForm.php used with insert, update and delete modules -->
<?php $ip = !isset($_SERVER['REMOTE_ADDR'])?$HTTP_SERVER_VARS['HTTP_CLIENT_IP']:$_SERVER['REMOTE_ADDR'];?>

<form method="post" action="yellowresult.php" ENCTYPE="multipart/form-data" onSubmit="return validate(this)">
<input type="hidden" name="goal" value="<?php echo $goal;?>">

<table class="form">
<tr>
	<th colspan=2>
		<?php echo $goal;?><?php if ( $goal != "Add" ) { echo" - Listing # $yps";}?>
	</th>
</tr>


<tr>
	<td colspan=2>
		<b><font color="red">required = *</font></b>
	</td>
</tr>


<tr bgcolor="PapayaWhip">
	<td>
		<font color="red">
		Password*
		</font>
	</td>
	<td>	
		<input type="password" name="ypassword" value="<?php echo $ypassword;?>" size=15 maxlength=15 class="input">
	</td>
</tr>


<tr bgcolor="PapayaWhip">
	<td>
		<font color="red">
		Email*
		</font>
	</td>
	<td>
		<input type="text" name="yemail" value="<?php echo $yemail;?>" size=25 maxlength=80 class="input">
	</td>
</tr>


<tr>
	<td>
		Company
	</td>
	<td>
		<input type="text" name="ycompany" value="<?php echo $ycompany;?>" size=45 maxlength=45 class="input">
	</td>
</tr>	


<tr>
	<td colspan=2>
		<b>Contact Person</b>
	</td>
</tr>


<tr>
	<td>
		First Name				
	</td>
	<td>
		<input type="text" name="yfirstname" value="<?php echo $yfirstname;?>" size=20 maxlength=35 class="input"> 
		Last <input type="text" name="ylastname" value="<?php echo $ylastname;?>" size=20 maxlength=45 class="input">
	</td>
</tr>	


<tr>
	<td>
		Address
	</td>
	<td>
		<input type="text" name="yaddress" value="<?php echo $yaddress;?>" size=45 maxlength=70 class="input">
	</td>
</tr>


<tr>
	<td>
		City
	</td>
	<td>
		<input type="text" name="ycity" value="<?php echo $ycity;?>" size=25 maxlength=25 class="input">
	</td>
</tr>


<tr>
	<td>
		State
	</td>
	<td>		
		<input type="text" name="ystateprov" value="<?php echo $ystateprov;?>" maxlength=25 size=25 class="input">
	</td>
</tr>


<tr>
	<td>
		Country 
	</td>
	<td>
		<?php include("countries.php");?>
	</td>
</tr>


<tr>
	<td>
		Postal Code
	</td>
	<td>
		<input type="text" name="ypostalcode" value="<?php echo $ypostalcode;?>" size=10 maxlength=10 class="input">
	</td>
</tr>


<tr>
	<td>
		Phone
	</td>
	<td>
		( <input type="text" name="yareacode" value="<?php echo $yareacode;?>" size=5 maxlength=7 class="input"> ) - <input type="text" name="yphone" value="<?php echo $yphone;?>" size=20 maxlength=20 class="input">
	</td>
</tr>


<tr>
	<td>
		Fax
	</td>
	<td>
		<input type="text" name="yfax" value="<?php echo $yfax;?>" size=20 maxlength=20 class="input">
	</td>
</tr>


<tr>
	<td>
		Cellular
	</td>
	<td>
		<input type="text" name="ycell" value="<?php echo $ycell;?>" size=20 maxlength=20 class="input">
	</td>
</tr>


<tr>
	<td>	
		Website
	</td>
	<td>
		<input type="text" name="yurl" value="<?php echo $yurl;?>" size=45 maxlength=140 class="input"><br>
		Example: http://www.yourdomain.com/yourFolder/yourFile.html
	</td>
</tr>
<?php if(defined("SETRANK")):?>


<?php if ( USELOGOS == "yes" && $goal != "Delete"): // do not show if images are unwanted, or if the record is being deleted ?>
<tr>
	<td>	
		Image
	</td>
	<td>
      Send this file: <INPUT TYPE="file" NAME="userfile" class="input"><br>
		<?php
			if(empty($ip)) {
				$ip = ($HTTP_VIA)?($HTTP_X_FORWARDED_FOR):($REMOTE_ADDR);
				if($ip==""){
					$ip = $HTTP_SERVER_VARS['HTTP_CLIENT_IP'];
				}
				if($ip==""){
					$ip = $REMOTE_ADDR;
				}
			}?>
      <span style="font-size:x-small;color:salmon;">
      Your Remote Address of <?php echo $ip;?> is logged.
      </span>
      <br>

      <span style="font-size:x-small;color:teal;">
      <b>Maximum</b> File-size = <?php echo MAX_FILE_SIZE;?> bytes  
      Width = <?php echo MAX_IMAGE_WIDTH;?> pixels 
      Height = <?php echo MAX_IMAGE_HEIGHT;?> pixels 
      </span>
		<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE;?>">
      <input type="hidden" name="maxImageWidth" value="<?php echo MAX_IMAGE_WIDTH;?>">
      <input type="hidden" name="maxImageHeight" value="<?php echo MAX_IMAGE_HEIGHT;?>">
	</td>
</tr>
<?php endif; // if ( USELOGOS == "yes"?>


<?php endif;?>
<tr>
	<td colspan=2>
		<input type="hidden" name="goal" value="<?php echo $goal;?>">
		<input class="input" type="submit" name="submit" value=<?php echo $goal;?>>
		<input type="hidden" name="yps" value="<?php echo $yps;?>">
	</td>
</tr>
</form>
</table>
<!-- End addForm.php -->
