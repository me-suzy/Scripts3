<?php // initialize or capture
$productnumber = !isset($_POST["productnumber"])? NULL: $_POST["productnumber"];
$task = !isset($_POST["task"])? NULL: $_POST['task'];
$status = !isset($_POST["status"])? "Offline": $_POST["status"];
$productname = !isset($_POST["productname"])? NULL: $_POST["productname"];
$resource = !isset($_POST["resource"])? "none": $_POST["resource"];
$shortname = !isset($_POST["shortname"])? NULL: $_POST["shortname"];
$version = !isset($_POST["version"])? NULL: $_POST["version"];
$released = !isset($_POST["released"])? $todaysDate: $_POST["released"]; // $todaysDate is from configure.php
$availability = !isset($_POST["availability"])? "Retail": $_POST["availability"];
$baseprice = !isset($_POST["baseprice"])? NULL: $_POST["baseprice"];
$license = !isset($_POST["license"])? NULL: $_POST["license"];
$os = !isset($_POST["os"])? NULL: $_POST["os"];
$language = !isset($_POST["language"])? NULL: $_POST["language"];
$benefit = !isset($_POST["benefit"])? NULL: $_POST["benefit"];
$overview = !isset($_POST["overview"])? NULL: $_POST["overview"];
$description = !isset($_POST["description"])? NULL: $_POST["description"];
$filesize = !isset($_POST["filesize"])? NULL: $_POST["filesize"];
$logo = !isset($_POST["logo"])? NULL: $_POST["logo"];
$logourl = !isset($_POST["logourl"])? NULL: $_POST["logourl"];
$author = !isset($_POST["author"])? NULL: $_POST["author"];
$companyurl = !isset($_POST["companyurl"])? NULL: $_POST["companyurl"];
$companyemail = !isset($_POST["companyemail"])? NULL: $_POST["companyemail"];
$category = !isset($_POST["category"])? NULL: $_POST["category"];
$hits = !isset($_POST["hits"])? 0: $_POST["hits"];
$position = !isset($_POST["position"])? NULL: $_POST["position"];
$endorsement = !isset($_POST["endorsement"])? NULL: $_POST["endorsement"];
$via = !isset($_POST["via"])? "HTTP": $_POST["via"];
$special = !isset($_POST["special"])? NULL: $_POST["special"];
$url = !isset($_POST["url"])? NULL: $_POST["url"];
$requirements = !isset($_POST["requirements"])? "Requires ": $_POST["requirements"];
/*
$ = !isset($_POST[""])? NULL: $_POST[""];
*/
?>
<br><br><a href="#upload">upload a new customer file for this item</a>
<br><a name="top"><!-- START ITEM FORM --></a>
<form name="itemForm" method="post" enctype="multipart/form-data" action="workWithItem.php">
	<!-- get ready to post back variable values not included in the regular form -->
	<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE;?>">
	<input type="hidden" name="task" value="<?php echo $task;?>">
	<input type="hidden" name="productnumber" value="<?php echo $productnumber;?>">
<?php 
switch($status) { // set the color of the status 
	case"Online":
		$formStatusColor="Honeydew";
		break;
	case"Offline":
		$formStatusColor="#ffcccc";
		break;
	case"Unused":
		$formStatusColor="#e9e9e9";
		break;
	default:
		$formStatusColor="PeachPuff";
}?>
<table bgcolor="<?php echo $formStatusColor;?>">
	<tr>
		<th colspan=2>
			<?php echo"$task - $productname - Status: $status";?>
		</th>
	</tr>
		
	<tr>
		<td colspan=2 class="required">
			* means data is required
		</td>
	</tr>

	<tr>
		<td class="required">
			*Resource Name
		</td>
		<td>
			<input type="text" name="productname" value="<?php echo $productname;?>" maxlength=80 size=50>
		</td>
	</tr>

	<tr>
		<td class="required">
			*Benefit
		</td>
		<td>
			<input type="text" name="benefit" value="<?php echo $benefit;?>" maxlength=60 size=50>
		</td>
	</tr>

	<tr>
		<td class="required">
			*Type
		</td>
		<td>
			<?php include("resourceTypes.php");?>
		</td>
	</tr>

	<tr>
		<td class="required">
			*Short Name
		</td>
		<td>
			<input type="text" name="shortname" value="<?php echo $shortname;?>" maxlength=20 size=20>
			<br>
			<span class="note">Shortname (20 characters) is used as part of the download file name</span>
		</td>
	</tr>


<tr bgcolor="Coral">
	<td class="label"><blink>*Status</blink></td>
	<td>
		<select name="status" size=3>
		<option value="Offline"<?php if($status=='Offline'){echo" SELECTED";}?>>Offline</option>
		<option value="Online"<?php if($status=='Online'){echo" SELECTED";}?>>Online</option>
		<option value="Unused"<?php if($status=='Unused'){echo" SELECTED";}?>>Unused</option>
		</select>
	</td>
</tr>



<tr><td class="label">Version</td> 
<td><input type="text" name="version" value="<?php echo $version;?>" maxlength=20 size=20></td></tr>


<tr><td class="label">Released</td>
<td>
<select name="released" size=2>
<option value="<?php echo $released;?>" SELECTED><?php echo $released;?></option>
<option value="<?php echo $todaysDate;?>"><?php echo $todaysDate;?></option>
</select>
<br><span class="note">yyyy-mm-dd</span>
</td></tr>


<tr>
	<td class="label">Availability</td>
	<td><select name="availability" size=3>
		<option value="Free"<?php if($availability=='Free'){echo" SELECTED";}?>>Free</option>
		<?php if(file_exists("proBuy.php")):?>
		<option value="Retail"<?php if($availability=='Retail'){echo" SELECTED";}?>>Retail</option>
		<?php endif;?>
		<option value="Other"<?php if($availability=='Other'){echo" SELECTED";}?>>Other</option>
		</select> 
		<span class="note">Is the product free, for retail or other?</span>
		
	</td>
</tr>


<?php if(file_exists("proBuy.php")):?>
<tr><td class="label">Base Price</td>
<td><?php echo CURRENCYSYMBOL;?><input type="text" name="baseprice" value="<?php echo $baseprice;?>" maxlength=7 size=7> 
<span class="note">Example 123.45 &nbsp;&nbsp;( Optionally change CURRENCYSYMBOL in configure.php )</span>
</td></tr>
<?php else:?>
	<?php $_POST['baseprice'] = "0.00";?>
<?php endif;?>

<tr><td class="label">License</td>
<td><?php include("licenseTypes.php");?></td></tr>





<tr><td class="label">Operating System</td>
<td><?php include("os.php");?></td></tr>



<tr><td class="label">Language</td>   
<td><?php include("language.php");?></td></tr>



<tr bgcolor="#b3b3b3"><td class="label">Overview</td> 
<td><textarea rows=4 cols=60 name="overview"><?php echo $overview;?></textarea>
<br><span class="note">Maximum 255 characters</span></td></tr>
     
	 

<tr><td class="label">Description</td> 
<td><textarea rows=15 cols=60 name="description"><?php echo $description;?></textarea>
<br><span class="note">Take as much room as you like! HTML is ok.</span></td></tr> 
  
   
   
<tr><td class="label">File Size</td>
<td><input type="text" name="filesize" value="<?php echo $filesize;?>" maxlength=50 size=50></td></tr>


<tr bgcolor="#b3b3b3"><td class="label">Logo</td>
<td>
<SELECT NAME="logo" SIZE=3>
	<OPTION VALUE="<?php echo $logo;?>" SELECTED><?php echo $logo;?></OPTION>
	<OPTION VALUE="yes">yes</OPTION>
	<OPTION VALUE="no">no</OPTION>
</SELECT>
</td></tr>
<tr bgcolor="#b3b3b3"><td class="label">Logo URL</td>   
<td><input type="text" name="logourl" value="<?php echo $logourl;?>" maxlength=120 size=50></td></tr>
 
  

<tr><td class="label">Author</td>
<td><input type="text" name="author" value="<?php echo $author;?>" maxlength=80 size=50></td></tr>


<tr><td class="label">Company URL</td>   
<td><input type="text" name="companyurl" value="<?php echo $companyurl;?>" maxlength=120 size=50></td></tr>


<tr><td class="label">Company email</td>
<td><input type="text" name="companyemail" value="<?php echo $companyemail;?>" maxlength=120 size=50></td></tr>




<tr><td class="label">Category</td>
<td><?php include("categories.php");?></td></tr>



<tr><td class="label">Hits</td>
<td><input type="text" name="hits" value="<?php echo $hits;?>" size=10>
</td></tr>




<tr><td class="label">Endorsements</td>
<td><textarea rows=8 cols=60 name="endorsement"><?php echo $endorsement;?></textarea>
<br><span class="note">HTML ok, or use &lt;pre&gt;text&lt;/pre&gt;</span>
</td></tr>


<tr><th colspan=2>Set Download Method</th></tr>


	<tr>
		<td valign="middle" class="required">*Download Via</td> 
		<td>
			<br>
			<input type="radio" name="via" value="HTTP"<?php if($via=="HTTP"){echo" CHECKED";}?>> HTTP - the default download method<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="note">( file is transferred with standard browser HTTP protocol )</span><br> 


			<!--input type="radio" name="via" value="SMTP Attachment"<?php if($via=="SMTP Attachment"){echo" CHECKED";}?> SMTP Attachment - attached to an email note<br> --> 


<div class="favcolor2">

			<input type="radio" name="via" value="CRT"<?php if($via=="CRT"){echo" CHECKED";}?>> Monitor (CRT)<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="note">( file - plain text only - is displayed on the monitor (CRT). Paste below.  )</span><br>


			<input type="radio" name="via" value="Special"<?php if($via=="Special"){echo" CHECKED";}?>> Special<BR>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="note">( a unique method set by the administrator. Example: Phone 1-900-xxx-xxxx. Paste below. )</span><br> 


			<input type="radio" name="via" value="SMTP Body"<?php if($via=="SMTP Body"){echo" CHECKED";}?>> SMTP Body<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="note">( file - plain text only - is sent in the body of an email note. Paste below. )</span><br>

			
			<br>
			<textarea wrap=physical rows=6 cols=60 name="special"><?php echo $special;?></textarea>
			<br><span class="note">Paste into textarea above if you chose <i>Monitor (CRT)</i> or <i>Special</i> or <i>SMTP Body</i></span>

		</div>
		</td>
	</tr>


<tr><th colspan=2><a name="upload">Upload a new Customer File for this Item</a></th></tr>
<td>Choose file</td>
<td>
	<span class="note">Old file is: <?php echo $url;?></span>
	<input type="hidden" name="url" value="<?php echo $url; // used to see if an HTTP file download exists in the database ?>">

<br>
<!-- The MAX_FILE_SIZE hidden field must precede the file input field --> 
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE;?>">
<INPUT TYPE="file" NAME="userfile" size=50><br>
<div class="note">MAX_FILE_SIZE of <?php $myNumber = (MAX_FILE_SIZE/1048576); $myNumber = number_format($myNumber, 2); echo $myNumber;?> MB megabytes may be changed in configure.php</div>
</td></tr>



<tr bgcolor="#b3b3b3"><td class="label">Requirements</td>
<td><textarea rows=4 cols=60 name="requirements"><?php echo $requirements;?></textarea>
<br><span class="note">maximum 255 characters</span></td></tr>




	<tr>
		<td colspan=2>
			<p><br></p>

			<?php if($task != "Insert this item" ) { $task .= " - $productname";}?>
			<input type="submit" name="submit" value="<?php echo $task;?>" class="submit">
		</td>
	</tr>
</table>
</form>
<a href="#top">up to top</a>
<a name="bottom"><!-- END ITEM FORM --></a>

<br>
<img src="appimage/pixel.gif" width="1" height="400" border=0 alt="spacer for the anchor jump">