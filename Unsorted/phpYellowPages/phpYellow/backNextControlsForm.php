<!-- START OF backNextControlsForm.php -->
<form name="backNextControlsForm" method="post" action="<?php if($goal=="Manage Listings"){echo"adminresult.php";}else{echo"yellowresult.php";}?>">
<table border="0">
   <tr>
      <td>
			<?php if ( $rows > 0 ){echo"<input class=\"back\" type=\"button\" name=\"goBack\" value=\"&lt;= Back\" onclick=\"history.back(1)\">";}if ( $displayNextButton == "true" ):?><input class="input" type="submit" name="submit" value="More Results =>"><?php endif;?>
      </td>
   </tr>
</table>
<input type="hidden" name="primaryField" value="<?php echo $primaryField;?>">
<?php if(!isset($textToFind)){$textToFind=NULL;}else{$textToFind = $HTTP_POST_VARS['textToFind'];}?>
<input type="hidden" name="textToFind" value="<?php echo $textToFind;?>">
<input type="hidden" name="category" value="<?php echo $category;?>">
<input type="hidden" name="citytofind" value="<?php echo $citytofind;?>">
<input type="hidden" name="yareacode" value="<?php echo $yareacode;?>">
<input type="hidden" name="ystateprov" value="<?php echo $ystateprov;?>">
<input type="hidden" name="ycountry" value="<?php echo $ycountry;?>">
<input type="hidden" name="offset" value="<?php echo $offset;?>">
<input type="hidden" name="totalmatches" value="<?php echo $totalmatches;?>">
<input type="hidden" name="goal" value="<?php echo $goal;?>">
<input type="hidden" name="formuser" value="<?php print($formuser);?>">
<input type="hidden" name="formpassword" value="<?php print($formpassword);?>">
<input type="hidden" name="recordDisplay" value="<?php print($recordDisplay);?>">
</form>
<!-- END OF backNextControlsForm.php -->