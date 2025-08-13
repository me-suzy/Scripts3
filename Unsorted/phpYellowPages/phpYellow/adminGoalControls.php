<?php echo"\n\n\n";?>
<!-- START OF adminGoalControls.php -->
<br>
<table bgcolor="silver" border="5" class="form"><tr>
<form name="goAdmin" method="post" action="<?php echo ADMINHOME;?>">
<td>
<input type="hidden" name="formuser" value="<?php echo $formuser;?>">
<input type="hidden" name="formpassword" value="<?php echo $formpassword;?>">
<input class="input" type="submit" name="submit" value="To Admin">
</td>
</form>
<form method="post" name="deleteForm" action="adminresult.php" onsubmit="popup()" target="popup">
<td align="center" bgcolor="red">
<input type="submit" name="submit" value="Delete" class="input">
<input type="hidden" name="goal" value="Delete">
<input type="hidden" name="ckey" value="<?php echo $ckey;?>">
<input type="hidden" name="yps" value="<?php echo $yfps;?>">
<input type="hidden" name="formuser" value="<?php echo $formuser;?>">
<input type="hidden" name="formpassword" value="<?php echo $formpassword;?>">
</td>
</form>
<?php
if (defined("SETRANK")):?>
<form name="setRankForm" method="post" action="adminresult.php" onsubmit="popup()" target="popup">
<td bgcolor="yellow">
<select name="rank">
<option value="<?php echo $rank;?>" SELECTED><?php echo $rank;?></option>
<option value="2">First Page</option>
<option value="1">Preferred</option>
<option value="0">Basic</option>
</select>
<input type="submit" name="submit" value="Set-Rank" class="input">
<input type="hidden" name="goal" value="Set-Rank">
<input type="hidden" name="ckey" value="<?php echo $ckey;?>">
<input type="hidden" name="category" value="<?php echo $category;?>">
<input type="hidden" name="formuser" value="<?php echo $formuser;?>">
<input type="hidden" name="formpassword" value="<?php echo $formpassword;?>">
</td>
</form>
<?php endif;?>
<form name="updateStartForm" method="post" action="yellowgoal.php" target="_blank">
<td bgcolor="Green">
<input class="input" type="submit" name="submit" value="Edit" class="input">
<input type="hidden" name="goal" value="Edit">
<input type="hidden" name="yemail" value="<?php echo $yfemail;?>">
<input type="hidden" name="ypassword" value="<?php echo $yfpassword;?>">
<input type="hidden" name="category" value="<?php echo $category;?>">
<input type="hidden" name="ckey" value="<?php echo $ckey;?>">
<input type="hidden" name="yps" value="<?php echo $yfps;?>">
<input type="hidden" name="status" value="<?php echo $status;?>">
<input type="hidden" name="rank" value="<?php echo $rank;?>">
</td>
</form>
</tr></table>
<!-- END OF adminGoalControls.php -->
<?php echo"\n\n\n";?>