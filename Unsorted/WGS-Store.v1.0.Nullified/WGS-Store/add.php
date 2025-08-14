<link rel="stylesheet" href="style.css" type="text/css">
<body class="bigfont">
<?
if (isset($work)) {

// ADD NEW PRODUCT TO DB
	move_uploaded_file ($picture, "images/$picture_name");
	$query = "INSERT INTO products VALUES('', '$available', '$manufacturer', '$modelnumber', '$litdescr', '$bigdescr', '$picture_name', '$html', '$orprice', '$ouprice', '$makeopt', '$hiddenopt')";
	$result = mysql_query($query);
	if($result)
	echo "product was added";


} else {
?>

<FORM ENCTYPE="multipart/form-data" ACTION="admin.php" METHOD=POST>
  
  <br>
  <br>


<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="bigfont" width="25%">Qty. Available</td>
    <td width="75%"> 
      <input type=text name=available>
    </td>
  </tr>
  <tr> 
    <td class="bigfont" width="25%">Manufacturer (Index Field)</td>
    <td width="75%"> 
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
    <td class="bigfont" width="25%">Model Number</td>
    <td width="75%"> 
      <input type=text name=modelnumber>
    </td>
  </tr>
  <tr> 
    <td class="bigfont" width="25%">One line Description</td>
    <td width="75%"> 
      <input type=text name=litdescr>
    </td>
  </tr>
  <tr> 
    <td class="bigfont" width="25%">Detailed Description</td>
    <td width="75%"> 
      <textarea name="bigdescr" rows="5" cols="50"></textarea>
    </td>
  </tr>
  <tr> 
    <td class="bigfont" width="25%">Picture of the product</td>
    <td width="75%"> 
      <input name="picture" type="file">
    </td>
  </tr>
  <tr> 
    <td class="bigfont" width="25%">Html Field (For specs. on detailed page)</td>
    <td width="75%"> 
      <textarea name="html" rows="10" cols="50"></textarea>
    </td>
  </tr>
  <tr> 
    <td class="bigfont" width="25%">Original Price</td>
    <td width="75%"> 
      <input type=text name=orprice>
    </td>
  </tr>
  <tr> 
    <td class="bigfont" width="25%">Our Price</td>
    <td width="75%"> 
      <input type=text name=ouprice>
    </td>
  </tr>
  <tr> 
    <td class="bigfont" width="25%">Make Offer Option (on or off)</td>
    <td width="75%"> 
      <select name="makeopt">
        <option value="on">on</option>
        <option value="off">off</option>
      </select>
    </td>
  </tr>
  <tr> 
    <td class="bigfont" width="25%">Hidden Price Option (on or off)</td>
    <td width="75%"> 
      <select name="hiddenopt">
        <option value="on">on</option>
        <option value="off">off</option>
      </select>
    </td>
  </tr>
</table>
<br>



<input type="hidden" name="oper" value="add">
<input type="submit" name="work" value="add">
</form>

<?
}
?>