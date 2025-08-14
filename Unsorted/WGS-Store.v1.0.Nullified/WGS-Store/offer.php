<?
include ('vars.php');

if ($work == "offer") {
?>
<link rel="stylesheet" href="style.css" type="text/css">

<form action="offer.php" method=post>
<input type=hidden name=product value='<?=$product?>'>
  <table width="100%" border="0" cellspacing="4" cellpadding="0">
    <tr> 
      <td width="120" class="bigfont"><font color="#333333">Name:</font></td>
      <td class="bigfont"> <font color="#333333"> 
        <input type=text name=name>
        </font></td>
    </tr>
    <tr> 
      <td width="120" class="bigfont"><font color="#333333">E-mail:</font></td>
      <td class="bigfont"> <font color="#333333"> 
        <input type=text name=email>
        </font></td>
    </tr>
    <tr> 
      <td width="120" class="bigfont"><font color="#333333">Price:</font></td>
      <td class="bigfont"> <font color="#333333"> 
        <input type=text name=price size=4>
        </font></td>
    </tr>
    <tr> 
      <td width="120" class="bigfont"><font color="#333333">Comments:</font></td>
      <td class="bigfont"> <font color="#333333"> 
        <textarea name="comments" cols=30 rows="3"></textarea>
        </font></td>
    </tr>
    <tr> 
      <td colspan="2" class="bigfont"> <font color="#333333"> 
        <input type="submit" name="work" value="Send">
        </font></td>
    </tr>
  </table>
</FORM>

<?
} elseif ($work == "hidden") {
?>
<link rel="stylesheet" href="style.css" type="text/css">
<form action="offer.php" method=post>
<input type=hidden name=product value='<?=$product?>'>
  <table width="100%" border="0" cellspacing="4" cellpadding="0">
    <tr> 
      <td width="120" class="bigfont"><font color="#333333">Name:</font></td>
      <td class="bigfont"> <font color="#333333"> 
        <input type=text name=name>
        </font></td>
    </tr>
    <tr> 
      <td width="120" class="bigfont"><font color="#333333">E-mail:</font></td>
      <td class="bigfont"> <font color="#333333"> 
        <input type=text name=email>
        </font></td>
    </tr>
    <tr> 
      <td width="120" class="bigfont"><font color="#333333">Comments:</font></td>
      <td class="bigfont"> <font color="#333333"> 
        <textarea name="comments" rows="3" cols="30"></textarea>
        </font></td>
    </tr>
    <tr> 
      <td colspan="2" class="bigfont"> <font color="#333333"> 
        <input type="submit" name="work" value="Send">
        </font></td>
    </tr>
  </table>
</FORM>

<?
} elseif ($work == "Send") {

// SEND MAIL
	if ($price == "") {

		mail("$adminmail", "new offer | hidden price", "name - $name\nemail - $email\ncomments - $comments\nproduct - $product", "From: $adminmail");
		echo "your offer was sent";
	} else {

		mail("$adminmail", "new offer", "name - $name\nemail - $email\nprice - $price\ncomments - $comments\nproduct - $product", "From: $adminmail");
		echo "your hidden price was sent";
	}
}
?>


<div align=right><a class="buton bigfont" href="#" OnClick="window.close();"><B>CLOSE</B></a></div>