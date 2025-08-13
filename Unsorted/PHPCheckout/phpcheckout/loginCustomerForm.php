<?php echo"\n\n\n";?>
<!-- start of loginCustomerForm.php -->
<form name="loginCustomerForm" method="post" action="login.php"></a>
<input type="hidden" name="task" value="<?php echo $task;?>">
<input type="hidden" name="customerid" value="<?php echo $customerid;?>">

<table class="favcolor" border=0 cellpadding=3>
<tr><th colspan=2>
<?php echo $task . " - CustomerID #$customerid";?></th></tr>





<tr><td class="required">
*Password</td>
<td>
<input type="password" name="password" size=15 maxlength=15 value="<?php echo $password;?>"> 
</td></tr>


<tr><td class="required">
*Email</td>
<td> 
<input type="text" name="email" size=30 maxlength=80 value="<?php echo $email;?>"> 
</td></tr>


<tr>
<td>Privacy</td>
<?php if(empty($privacy)){$privacy="medium";}?>
<td><input type="radio" name="privacy" value="low"<?php if($privacy=="low"){echo" CHECKED";}?>> low 
    <input type="radio" name="privacy" value="medium"<?php if($privacy=="medium"){echo" CHECKED";}?>> medium 
    <input type="radio" name="privacy" value="high"<?php if($privacy=="high"){echo" CHECKED";}?>> high 
</td></tr> 


<tr><td>
Newsletter</td>
<td><input type="checkbox" name="news" value="yes"<?php if($news=="yes"){echo" CHECKED";}?>> OK send infrequent <?php echo ORGANIZATION;?> news
</td></tr>



<tr><td>
First Name</td>
<td>
<input type="text" name="firstname" size=30 maxlength=35 value="<?php echo $firstname;?>">
</td></tr>

<tr><td>
Last Name
</td>
<td>
<input type="text" name="lastname" size=30 maxlength=45 value="<?php echo $lastname;?>">
</td></tr>


<tr>
<td>Role</td>
<td><?php include("role.php");?></td>
</tr>


<tr><td>
Organization</td>
<td>
<input type="text" name="organization" size=30 maxlength=45 value="<?php echo $organization;?>">
</td></tr>


<tr><td>
Address</td>
<td>
<input type="text" name="address" size=30 maxlength=70 value="<?php echo $address;?>">
</td></tr>

 
<tr><td>
City</td>
<td>
<input type="text" name="city" size=30 maxlength=25 value="<?php echo $city;?>"> 
</td></tr>


<tr><td>
State</td>
<td>
<input type="text" name="stateprov" size=30 maxlength=25 value="<?php echo $stateprov;?>"> 
</td></tr>


<tr><td class="required">
*Country</td>
<td><?php include("countries.php");?></td></tr>

<tr><td> 
Postal Code</td>
<td>
<input type="text" name="postalcode" size=10 maxlength=10 value="<?php echo $postalcode;?>">
</td></tr>

 
<tr><td>
Area Code
(&nbsp;<input type="text" name="areacode" size=3 maxlength=3 value="<?php echo $areacode;?>">&nbsp;) 
</td><td>
Phone&nbsp;&nbsp;
<input type="text" name="phone" size=20 maxlength=20 value="<?php echo $phone;?>">
</td></tr>

<tr><td>&nbsp;</td>
<td>
Fax&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" name="fax" size=20 maxlength=20 value="<?php echo $fax;?>">
</td></tr>

<tr><td>&nbsp;</td>
<td>
Cellular
<input type="text" name="cellphone" size=20 maxlength=20 value="<?php echo $cellphone;?>">
</td></tr>


<tr><td>
Website</td>
<td>
<input type="text" name="website" size=25 maxlength=140 value="<?php echo $website;?>"><br>
<span style="font-size:x-small;">Example: http://www.yourDomain.com</span>
</td></tr>




<!-- start of conditional execution -->
<?php if($task != "Insert Customer Record"): // show only if a returning customer ?>
<tr><td colspan=2>
Customer Since:  
<?php echo $customersince;?>&nbsp;
Visits:  
<?php echo $visits;?>
</td></tr>

<tr><td colspan=2>
<?php echo "<span style=\"font-size: xx-small;\">Last Updated on: $lastupdate</span>";?>
</td></tr>
<?php endif;?>
<!-- end of conditional execution -->



<?php if(!isset($myCheckOutPage)): // this variable should be set on the checkout.php page only ?>
<tr><td colspan=2>
<input class="input" type="submit" name="submit" value="<?php echo $task;?>">
</td></tr></table>
</form>
<?php endif;?>
</table>
<!-- end of loginCustomerForm.php -->