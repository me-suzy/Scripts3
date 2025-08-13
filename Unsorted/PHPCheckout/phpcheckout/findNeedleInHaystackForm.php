<!-- start of findNeedleInHaystackForm.php -->
<?php require_once("functions.php");?>
<script language="JavaScript">
<!--
function check_haystackForm_Needle(form) { // make sure we have a needle value
	var myForm = window.document.haystackForm; // expressly reference the form
	var needle = myForm.needle.value;
	if( needle == 0 ) {
		alert("Enter the needle you wish to find.");
		 myForm.needle.focus();
		return false;
	}
return true;
}
//-->
</script>



<h2>Find Needle in Haystack . . .</h2>



<form name="haystackForm" method="post" action="adminresult.php" onsubmit="return check_haystackForm_Needle(this);">
<input type="hidden" name="goal" value="Find Needle in Haystack . . .">
<?php // initialize or capture values for some variables
	$highlight = !isset($_REQUEST['highlight'])?"none":$_REQUEST['highlight']; // initialize "highlight" or capture its current value
	$ao = !isset($_REQUEST['ao'])?"DESC":$_REQUEST['ao']; // initialize "ao" or capture its current value
	$myLimit = !isset($_REQUEST['myLimit'])?20:$_REQUEST['myLimit']; // initialize "myLimit" or capture its current value
?>


<table cellpadding=12>
<tr><th>Find Needle in Haystack . . .</th></tr>

<tr class="tableRowOne">
<td>
Find needle like 
<input class="input" type=text name="needle" size=20 value="Enter a value" maxlength=160 onfocus='haystackForm.needle.value=""'>
look in 
<?php buildSelectList( TABLECUSTOMER, "haystack");?>
haystack  
</td></tr>

<tr class="tableRowTwo"><td>
hold up to <input type="text" name="myLimit" size=5 value="<?php echo $myLimit;?>">
straws and arrange by <?php buildSelectList(TABLECUSTOMER, "fieldToOrderBy");?> 
<select name="ao" size=2>
<option value="DESC"<?php if($ao=="DESC"){echo" SELECTED";}?>>descending</option>
<option value="ASC"<?php if($ao=="DESC"){echo" SELECTED";}?>>ascending</option>
</select> 
</td></tr>

<tr class="tableRowOne">
	<td>
		<span style="color:lime;background-color:navy;font-weight:bold;">Highlight is</span> 
		<input type="radio" name="highlight" value="none"<?php if(($highlight=="none")||(empty($highlight))){echo" CHECKED";}?>>off 		
		&nbsp;&nbsp;<input type="radio" name="highlight" value="fuzzy"<?php if($highlight=="fuzzy"){echo" CHECKED";}?>>fuzzy 
		&nbsp;&nbsp;<input type="radio" name="highlight" value="near"<?php if($highlight=="near"){echo" CHECKED";}?>>near 
		&nbsp;&nbsp;<input type="radio" name="highlight" value="exact"<?php if($highlight=="exact"){echo" CHECKED";}?>>exact 		
	</td>
</tr>


<tr>
	<th>
		<input class="submit" type="submit" name="submit" value="Find Needle in Haystack">
	</th>
</tr>
</table>
<div class="note">To find recently added customer records:
	<ol>
		<li>in 'Find needle like' enter the value '@' (without the quotes)</li>
		<li>for 'look in' select the value 'email'</li>
		<li>submit</li>
	</ol>
</div>
</form>
<!-- end of findNeedleInHaystackForm.php -->