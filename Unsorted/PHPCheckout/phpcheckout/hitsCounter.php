<!-- start of hitsCounter.php -->
<?php 
if(!isset($hits)) { // if  a local scope variable is not available
	$hits = !isset($_REQUEST['hits']) ? "0" : $_REQUEST['hits'];
}
?>
<table border="1" bgcolor="black" width="100">
	<tr>
		<td align="center">
			<b><font color="lime" size="4"><?php echo $hits;?></font></b>
		</td>
	</tr>
	<tr bgcolor="darkgray">
		<td style="font-size:xx-small;font-weight:normal;">
			<i><a href="docs/help.php#hitsthisproduct">Hits this Product</a></i>
		</td>
	</tr>
</table>
<!-- end of Product Hits counter: hitsCounter.php -->