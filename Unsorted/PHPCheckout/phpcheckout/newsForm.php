<!-- START of newsForm.php -->
<?php $goal = !isset($_GET["goal"])?$_POST["goal"]:$_GET["goal"]; // initialize or capture ?>


<table>
<tr>
	<td>
<form name="newsForm" method="post" action="newsResult.php">
<table class="form" align="left" cellpadding=10 border=0>
	<tr>
		<th>
			<?php echo "<b>" . ORGANIZATION . " Newsletter</b><br>";?>
		</th>
	</tr>

	<tr>
		<td>
			<span style="color:lime;font-size:x-small;">
				<input onfocus="newsForm.email.value=''" type="text" name="email" value="your email address here" size="25" maxlength="150">
				<br><input type="radio" name="goal" value="Subscribe"<?php if($goal=="Subscribe"){echo" CHECKED";}?>> Subscribe 
				<br><input type="radio" name="goal" value="Unsubscribe"<?php if($goal=="Unsubscribe"){echo" CHECKED";}?>> Unsubscribe
			</span>
			<br><input class="submit" type="submit" name="submit" value=" Submit ">
			<br><span class="note"><a href="privacyPolicy.php">Privacy Policy</a></span>
		</td>
	</tr>
</table>
</form>	
	</td>
</tr>
</table>
<!-- END of newsForm.php -->

