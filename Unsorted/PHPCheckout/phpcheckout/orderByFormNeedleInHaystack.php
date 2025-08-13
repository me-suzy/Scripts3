<form name="goWithOrderBy" method="post" action="adminresult.php">
<th>
<input class="orderbybutton" type="submit" name="submit" value="<?php echo(mysql_field_name($result,$i));?>">
<input type="hidden" name="needle" value="<?php echo $needle;?>">
<input type="hidden" name="haystack" value="<?php echo $haystack;?>">
<input type="hidden" name="fieldToOrderBy" value="<?php echo(mysql_field_name($result,$i));?>">
<input type="hidden" name="highlight" value="<?php echo $highlight;?>">
<input type="hidden" name="myLimit" value="<?php echo $myLimit;?>">
<input type="hidden" name="goal" value="<?php echo $goal;?>">
<input type="hidden" name="ao" value="<?php echo $ao; // Ascendancy Order: ascending ASC or descending DESC ?>">
</th>
</form>