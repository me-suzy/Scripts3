
<form name="enterEmailForm" method="post" action="runner.php">

		
<table width=450 class="form">
<tr>
	<th colspan=2>
		Enter Email &amp; Password<br>
		<?php echo "<i style='color:PaleGoldenRod;font-size:x-small;'>&quot;$benefit&quot;</i>";?>	
	</th>
</tr>

<!-- enter email address to ship to -->
<tr>
	<td>	
	<a href="help.php#email" target="_blank">Email</a>
	</td>
	<td>
		<input type="text" name="email" value="<?php echo $email;?>" size=30 maxlength=160">
		<span style="color:red;">Required</span>
	</td>
</tr>	


<!-- enter password for registration -->
<tr>
	<td>	
	<a href="help.php#password" target="_blank">Password</a>
	</td>
	<td>
	<input type="password" name="password" value="<?php echo $password;?>" size=20 maxlength=40>
	<span style="color:red;">Required</span> New? Make one up.
	</td>
</tr>	




<!-- enter password for registration -->
<tr>
	<td>	
	<a href="help.php#privacy" target="_blank">Privacy</a>
	</td>
	<td>
<input type="radio" name="privacy" value="low"> low 
<input type="radio" name="privacy" value="medium" CHECKED> medium 
<input type="radio" name="privacy" value="high"> high 
	</td>
</tr>	



<?php if(OFFERNEWSLETTER=='yes'):?>
<tr>
	<td>	
	<a href="help.php#newsletter" target="_blank">Newsletter?</a>
	</td>
	<td>
<input type="checkbox" name="news" value="yes"<?php if($news=="yes"){echo" CHECKED";}?>> OK send infrequent <?php echo COMPANY;?> news
	</td>
</tr>
<?php endif;?>



<tr>
	<td colspan=2>
	<input class="input" type="submit" name="submit" value=" Next Step "> 
	</td>
</tr>




<tr>
	<td colspan=2 align="right">
	<a href="mailto:<?php echo SYSTEMEMAIL;?>?subject=<?php echo COMPANY;?> - Question"><i><?php echo SYSTEMEMAIL;?></i></a>&nbsp;&nbsp;&nbsp;
	<input class="back" type="button" name="goBack" value=" &lt;-- Back " onClick="history.back(1)">
	</td>
</tr>


</table>
<input type="hidden" name="goal" value="<?php echo $goal;?>">
<input type="hidden" name="shortname" value="<?php echo $shortname;?>">
<input type="hidden" name="x_amount" value="<?php echo $x_amount;?>">
<input type="hidden" name="availability" value="<?php echo $availability;?>">
<input type="hidden" name="productname" value="<?php echo $productname;?>">
<input type="hidden" name="productnumber" value="<?php echo $productnumber;?>">
<input type="hidden" name="benefit" value="<?php echo $benefit;?>">
</form>

</blockquote>


<!-- END of Main Content -->

<p><br></p>
		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>
</table>

