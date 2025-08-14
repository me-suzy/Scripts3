<link rel="stylesheet" href="style.css" type="text/css">
<?
if (isset($edit)) {

	if (isset($work)) {

// UPLOAD NEW IMAGE AND CHANGE INFORMATION ABOUT PRODUCT
		if ($picture_name != "") {
			move_uploaded_file ($picture, "images/$picture_name");
			$query = "UPDATE products SET available = '$available', manufacturer = '$manufacturer', modelnumber = '$modelnumber', litdescr = '$litdescr', bigdescr = '$bigdescr',picture = '$picture_name', html = '$html', orprice = '$orprice', ouprice = '$ouprice', makeopt = '$makeopt', hiddenopt = '$hiddenopt' WHERE id = '$id'";
		} else {
			$query = "UPDATE products SET available = '$available', manufacturer = '$manufacturer', modelnumber = '$modelnumber', litdescr = '$litdescr', bigdescr = '$bigdescr', html = '$html', orprice = '$orprice', ouprice = '$ouprice', makeopt = '$makeopt', hiddenopt = '$hiddenopt' WHERE id = '$id'";
		}
		$result = mysql_query($query);
		echo "info about <b>$n[3]</b> was changed";
	} else {





// SELECT CHOUSEN PRODUCT
$query = "SELECT * FROM products WHERE id = '$id'";
$result = mysql_query($query);
$n[0] = mysql_result($result,$i,'available');
$n[1] = mysql_result($result,$i,'manufacturer');
$n[2] = mysql_result($result,$i,'modelnumber');
$n[3] = mysql_result($result,$i,'litdescr');
$n[4] = mysql_result($result,$i,'bigdescr');
$n[5] = mysql_result($result,$i,'picture');
$n[6] = mysql_result($result,$i,'html');
$n[7] = mysql_result($result,$i,'orprice');
$n[8] = mysql_result($result,$i,'ouprice');
$n[9] = mysql_result($result,$i,'makeopt');
$n[10] = mysql_result($result,$i,'hiddenopt');
$n[11] = mysql_result($result,$i,'id');
?>
<form enctype="multipart/form-data" action="admin.php" method=post>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="39%" class="bigfont">Qty. Available</td>
      <td width="61%" class="bigfont"> 
        <input type=text name=available value='<?=$n[0];?>'>
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">Manufacturer (Index Field)</td>
      <td width="61%" class="bigfont"> 
        <select name="manufacturer">
          <?
$query = "SELECT * FROM manufacturer";
$result = mysql_query($query);
if ($result !="")
		{
		$number = mysql_numrows($result);
		}
for ($i=0;$i<$number;$i++) {
	$n[0] = mysql_result($result,$i,'manufacturer');
	echo "<option value='$n[0]'>$n[0]</option>";
}
?>
        </select>
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">Model Number</td>
      <td width="61%" class="bigfont"> 
        <input type=text name=modelnumber value='<?=$n[2];?>'>
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">One line Description</td>
      <td width="61%" class="bigfont"> 
        <input type=text name=litdescr value='<?=$n[3];?>'>
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">Detailed Description</td>
      <td width="61%" class="bigfont"> 
        <textarea name="bigdescr" rows="5" cols="40"><?=$n[4];?></textarea>
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">Picture of the product <br>
        (don't write here nothing if you don't want to change file for this product) 
      </td>
      <td width="61%" class="bigfont"> 
        <input name="picture" type="file">
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">Html Field (For specs. on detailed page)</td>
      <td width="61%" class="bigfont"> 
        <textarea name="html" rows="10" cols="40"><?=$n[6];?></textarea>
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">Original Price</td>
      <td width="61%" class="bigfont"> 
        <input type=text name=orprice value='<?=$n[7];?>'>
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">Our Price</td>
      <td width="61%" class="bigfont"> 
        <input type=text name=ouprice value='<?=$n[8];?>'>
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">Make Offer Option (on or off)</td>
      <td width="61%" class="bigfont"> 
        <select name="makeopt">
          <?
if ($n[9] == 'on') {
	echo "<option value='on' selected>on</option><option value='off'>off</option>";
} else {
	echo "<option value='on'>on</option><option value='off' selected>off</option>";
}
?>
        </select>
      </td>
    </tr>
    <tr> 
      <td width="39%" class="bigfont">Hidden Price Option (on or off)</td>
      <td width="61%" class="bigfont"> 
        <select name="hiddenopt">
          <?
if ($n[10] == 'on') {
	echo "<option value='on' selected>on</option><option value='off'>off</option>";
} else {
	echo "<option value='on'>on</option><option value='off' selected>off</option>";
}
?>
        </select>
      </td>
    </tr>
  </table>

<input type=hidden name=edit value=edit>
<input type=hidden name=id value=<?=$n[11];?>>
<input type="hidden" name="oper" value="edit">
  <input type="submit" name="work" value="modify">
</form>











<?
	}
} elseif (isset($delete)) {

// DELETE CHOUSEN PRODECT AND ITS IMAGE
	$query = "SELECT * FROM products WHERE id = '$id'";
	$result = mysql_query($query);
	$n[6] = mysql_result($result,$i,'html');
	@unlink("images/".$file);
	$query = "DELETE FROM products WHERE id = '$id'";
	$result = mysql_query($query);
	
	echo "it was deleted";
}

echo "<hr height=1 size=1 width='100%'>";

// SHOW ALL PRODUCTS
$query = "SELECT * FROM products";
$result = mysql_query($query);
if ($result !="")
		{
		$number = mysql_numrows($result);
		}

echo "<form action='admin.php' method=post>";
for ($i=0;$i<$number;$i++) {
	$n[0] = mysql_result($result,$i,'available');
	$n[1] = mysql_result($result,$i,'manufacturer');
	$n[2] = mysql_result($result,$i,'modelnumber');
	$n[3] = mysql_result($result,$i,'litdescr');
	$n[4] = mysql_result($result,$i,'bigdescr');
	$n[5] = mysql_result($result,$i,'picture');
	$n[6] = mysql_result($result,$i,'html');
	$n[7] = mysql_result($result,$i,'orprice');
	$n[8] = mysql_result($result,$i,'ouprice');
	$n[9] = mysql_result($result,$i,'makeopt');
	$n[10] = mysql_result($result,$i,'hiddenopt');
	$n[11] = mysql_result($result,$i,'id');
	echo "<input name=id type=radio value='$n[11]'> <font class=bigfont><b>$n[3]</b></font><br>";
}
echo "<br><br><input type=submit name=delete value=delete><input type=hidden name=oper value=edit><input type=submit name=edit value=edit></form>";
?>


















