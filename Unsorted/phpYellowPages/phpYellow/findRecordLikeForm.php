<?php echo"\n\n\n\n";?>
<!-- start of findRecordLikeForm.php -->
<?php $searchfield = !isset($searchfield)?"yemail":$HTTP_POST_VARS['searchfield'];?> 
<?php $stringtofind = !isset($stringtofind)?NULL:$HTTP_POST_VARS['stringtofind'];?> 
<br>
<table class="form">
<tr><th><?php if(!isset($goal)){$goal="Find Record Like ...";}echo $goal;?></th></tr>
<tr><td>
<br>
<a name="findrecordlike"><h3>Find Record Like ... </h3></a>
<form name="findinfolike" method="post" action="adminresult.php">
<input type="hidden" name="goal" value="Find Record Like . . .">
<input type="hidden" name="formuser" value="<?php echo $formuser;?>">
<input type="hidden" name="formpassword" value="<?php echo $formpassword;?>">
Search  
<select name="searchfield" size="5" class="input">
<option value="<?php echo $searchfield;?>" SELECTED><?php echo $searchfield;?></option>
<option value="ckey">ckey</option>
<option value="yemail">email</option>
<option value="ycompany">company</option>
<option value="ylastname">last name</option>
<option value="yphone">phone</option>
<option value="ypassword">password</option>
<option value="yfirstname">first name</option>
<option value="yaddress">address</option>
<option value="ycity">city</option>
<option value="ystateprov">state or province</option>
<option value="ycountry">country</option>
<option value="ypostalcode">postalcode</option>
<option value="yareacode">area code</option>
<option value="yphone">phone</option>
<option value="yfax">fax</option>
<option value="ycell">cell phone</option>
<option value="yurl">url</option>
<option value="ylogo">logo</option>
<option value="yps">Contact Listing ID number - yps</option>
<option value="ypsid">- - - - - - - - - - - - - - - - - </option>
<option value="ypsid">Category Listing ID number - ypsid</option>
<option value="ckey">ckey</option>
<option value="description">description</option>
<option value="rank">rank</option>
<option value="paymentrequired">payment required</option>
<option value="status">status</option>
<option value="expires">expires</option>
</select>
for  
<input type=text name="stringtofind" class="input" size=20 value="<? echo "$stringtofind";?>" length=10 maxlength=160>
<input class="input" type="submit" name="submit" value=" Find It! " class="input"> 
</form>

<p>
Need to find just one record but can't find it? Select the search field 
you know along with its data and click on Find It! Finds up to 100 matched records.
</p>
</div>
</td></tr></table>
<p><br></p>
<!-- end of findRecordLikeForm.php -->