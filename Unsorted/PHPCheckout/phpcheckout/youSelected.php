<?php echo"\n\n\n";?>
<!-- start of youSelected.php -->
<?php // initialize or capture
$method = !isset($_REQUEST['method'])?NULL:$_REQUEST['method'];
$email = !isset($_REQUEST['email'])?NULL:$_REQUEST['email'];
$baseprice = !isset($_POST['baseprice'])?NULL:$_POST['baseprice']; 
$productname = !isset($_POST['productname'])?NULL:$_POST['productname']; 
$availability = !isset($_POST['availability'])?NULL:$_POST['availability']; 
echo"<p class=\"youselected\">You selected > $productname > $availability ";if($availability=="Retail"){echo"> \$$baseprice ";}echo"> $email > $method</p>";?>
<!-- end of youSelected.php -->
	