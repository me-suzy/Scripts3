<table border="0" width="600" cellspacing="0" cellpadding="0" class="main_bold" style="border: 1 solid #000000">
	<tr>
		<td width="600" align="middle">
		<table width="580" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="580" height="370" class="main_bold" align="center">
				Thank you for submitting your order. Your order details are as follows:
				<br>
				<br>
				<table border="0" width="390" cellspacing="0" cellpadding="0" class="main_bold">
					<tr>
						<td width="390" class="main_bold">
						
						Fullname: <span class="summary_message"><?php echo $firstname ?> <?php echo $surname ?></span>
						<br>
						Address:
						<br>
						<span class="summary_message">
						<?php echo $house ?> <?php echo $street ?>
						<br>
						<?php echo $town ?>
						<br>
						<?php echo $country ?>
						<br>
						<?php echo $postcode ?>
						</span>
						<br>
						Telephone Number: <span class="summary_message"><?php echo $telephone ?></span>
						<br>
						Email Address: <span class="summary_message"><?php echo $email ?></span>
						<br>
						Domain: <span class="summary_message">www.<?php echo $domainname ?><?php echo $domain ?></span>
						<br>
						Hosting Package: <span class="summary_message"><?php echo $package ?></span>
						<br>
						Schedule: <span class="summary_message"><?php echo $schedule ?></span>
						<br>
						Payment Method: <span class="summary_message"><?php echo $payment ?></span>
						<br>
						<br>
						<center>Any required changes can be made via our helpdesk.</center>
						
						</td>
					</tr>
				</table>
				<br>
				We use the services of <?php echo $processor ?>
				for our secure credit card payment processing.
				<br>
				<br>
				Please click <a href="<?php echo $link ?>" class="link">here</a> to make your payment of
				<span class="summary_message"><?php echo $currencytype ?><?php echo $price ?></span> via our secure payment gateway.
				<br>
				<br>
				An order confirmation email has been sent to <span class="summary_message"><?php echo $email ?></span>.
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>