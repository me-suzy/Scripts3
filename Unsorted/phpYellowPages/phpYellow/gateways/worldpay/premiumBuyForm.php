<?php
// for test data only
$ckey = 423; // set this to a vaid listing ckey number
$customerid = 1; // set this to any number
$monthsGoodFor = 12;
$item = "Preferred";
$x_address = "123 Anywhere Avenue";
$x_city = "Los Angeles";
$x_state = "California";
$x_country = "U.S.A.";
$x_zip = "12345";
$x_first_name = "Justin";
$x_last_name = "Testin";
$x_areacode = 555;
$x_phone = "123 - 4567";
$x_fax = "123-4567";
$email = "test@notAValidDomain.com";
$description = "Product or service description here";
$goal = "Process Payment Response";
$encodedGoal = urlencode($goal);
?>
<!-- concatenate first and last name -->
<?php $combinedFirstAndLastNames = $x_first_name . " " . $x_last_name;?>
<!-- concatenate address and city -->
<?php $address = $x_address . " " . $x_city . " " . $x_state;?>
<!-- add postal code -->
<?php $postcode = $x_zip;?>
<!-- convert country -->
<?php $country = $x_country;?>
<!-- add drop down box for currency -->


<!-- modify customerid to include productname -->
<?php $productAndCustomerid = "phpYellow Pages: " . $customerid;?>


<?php $cartId = $ckey;?>

<!-- concatenate phone area code with phone number -->
<?php $tel = "(" . $x_areacode . ") " . $x_phone;?>
<!-- concatenate areacode with fax -->
<?php $fax = "(" . $x_areacode . ") " . $x_fax;?>


<form action="https://select.worldpay.com/wcc/purchase" method=POST target="_blank">
<input type="hidden" name="goal" value="Process Payment Response">
<input type="hidden" name="ckey" value="<?php echo $ckey;?>">
<input type="hidden" name="monthsGoodFor" value="<?php echo $monthsGoodFor;?>">
<input type=hidden name="instId" value="48863">
<input type=hidden name="cartId" value="<?php echo $cartId;?>">
<input type=hidden name="name" value="<?php echo $combinedFirstAndLastNames;?>">
<input type=hidden name="tel" value="<?php echo $tel;?>">
<input type=hidden name="fax" value="<?php echo $fax;?>">
<input type="hidden" name="country" value="<?php echo $country;?>">
<input type=hidden name="amount" value="99.00">
<input type=hidden name="currency" value="USD">
<input type=hidden name="desc" value="<?php echo $description;?>">
<input type=hidden name="testMode" value="100">
<input type=hidden name="email" value="<?php echo $email;?>">
<input type=hidden name="address" value="<?php echo $address;?>">
<input type=hidden name="postcode" value="<?php echo $postcode;?>">
<input type=submit value="Test World Pay Account">

<!-- Needed by phpYellow Pages Premium Edition -->
<input type=hidden name="MC_goal" value="<?php echo $goal;?>">
<input type=hidden name="MC_ckey" value="<?php echo $ckey;?>">
<input type=hidden name="MC_item" value="<?php echo $item;?>">
<input type=hidden name="MC_monthsGoodFor" value="<?php echo $monthsGoodFor;?>">

</form>

