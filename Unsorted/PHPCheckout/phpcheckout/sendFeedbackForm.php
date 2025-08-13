<!-- sendFeedbackForm.php -->
<form name="sendFeedbackForm" method="post" action="loginResult.php">
	<input type="hidden" name="task" value="Send the Feedback">

<?php // identifiers used will be posted by the registrant ?>
<?php $customerid = !isset($customerid)?NULL:$_REQUEST['customerid'];?>
<?php $password = !isset($_REQUEST['password'])?NULL:$_REQUEST['password'];?>
<?php $email = !isset($_REQUEST['email'])?NULL:$_REQUEST['email'];?>
<?php $privacy = !isset($_REQUEST['privacy'])?NULL:$_REQUEST['privacy'];?>
<?php $news = !isset($_REQUEST['news'])?"no":$_REQUEST['news'];?>
<?php $firstname = !isset($_REQUEST['firstname'])?NULL:$_REQUEST['firstname'];?>
<?php $lastname = !isset($_REQUEST['lastname'])?NULL:$_REQUEST['lastname'];?>
<?php $role = !isset($_REQUEST['role'])?NULL:$_REQUEST['role'];?>
<?php $organization = !isset($_REQUEST['organization'])?NULL:$_REQUEST['organization'];?>
<?php $address = !isset($_REQUEST['address'])?NULL:$_REQUEST['address'];?>
<?php $city = !isset($_REQUEST['city'])?NULL:$_REQUEST['city'];?>
<?php $stateprov = !isset($_REQUEST['stateprov'])?NULL:$_REQUEST['stateprov'];?>
<?php $country = !isset($_REQUEST['country'])?NULL:$_REQUEST['country'];?>
<?php $postalcode = !isset($_REQUEST['postalcode'])?NULL:$_REQUEST['postalcode'];?>
<?php $areacode = !isset($_REQUEST['areacode'])?NULL:$_REQUEST['areacode'];?>
<?php $news = !isset($_REQUEST['news'])?NULL:$_REQUEST['news'];?>
<?php $phone = !isset($_REQUEST['phone'])?NULL:$_REQUEST['phone'];?>
<?php $fax = !isset($_REQUEST['fax'])?NULL:$_REQUEST['fax'];?>
<?php $cellphone = !isset($_REQUEST['cellphone'])?NULL:$_REQUEST['cellphone'];?>
<?php $website = !isset($_REQUEST['website'])?NULL:$_REQUEST['website'];?>
<?php $customersince = !isset($_REQUEST['customersince'])?NULL:$_REQUEST['customersince'];?>
<?php $visits = !isset($_REQUEST['visits'])?NULL:$_REQUEST['visits'];?>
<?php $lastupdate = !isset($_REQUEST['lastupdate'])?NULL:$_REQUEST['lastupdate'];?>
<?php $remember_me = !isset($_REQUEST['remember_me'])?'on':$_REQUEST['remember_me'];?>

		<input type="hidden" name="email" value="<?php echo $email;?>">
		<input type="hidden" name="customerid" value="<?php echo $customerid;?>">
		<input type="hidden" name="password" value="<?php echo $password;?>">
		<input type="hidden" name="email" value="<?php echo $email;?>">
		<input type="hidden" name="organization" value="<?php echo $organization;?>">
		<input type="hidden" name="firstname" value="<?php echo $firstname;?>">
		<input type="hidden" name="lastname" value="<?php echo $lastname;?>">
		<input type="hidden" name="address" value="<?php echo $address;?>">		
		<input type="hidden" name="city" value="<?php echo $city;?>">		
		<input type="hidden" name="stateprov" value="<?php echo $stateprov;?>">
		<input type="hidden" name="country" value="<?php echo $country;?>">		
		<input type="hidden" name="postalcode" value="<?php echo $postalcode;?>">
		<input type="hidden" name="areacode" value="<?php echo $areacode;?>">
		<input type="hidden" name="phone" value="<?php echo $phone;?>">
		<input type="hidden" name="fax" value="<?php echo $fax;?>">
		<input type="hidden" name="cellphone" value="<?php echo $cellphone;?>">		
		<input type="hidden" name="website" value="<?php echo $website;?>">		
		<input type="hidden" name="customersince" value="<?php echo $customersince;?>">
		<input type="hidden" name="visits" value="<?php echo $visits;?>">		
		<input type="hidden" name="role" value="<?php echo $role;?>">
		<input type="hidden" name="privacy" value="<?php echo $privacy;?>">
		<input type="hidden" name="news" value="<?php echo $news;?>">		
		<input type="hidden" name="lastupdate" value="<?php echo $lastupdate;?>">


<b>Comment</b><br>
<textarea name="userComments" rows=12 cols=60></textarea>
<div class="note">[use as much space as needed]</div>

<br><br>


<input type="submit" name="submit" value="Send Feedback" class="submit">
<input type="checkbox" name="cc" CHECKED> also send carbon copy (CC) to me

</form>
