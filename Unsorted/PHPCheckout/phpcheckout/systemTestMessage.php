<?php if ( PROCESSOR == "localhost" ):?>
<table BORDER=1 class="systemMessage" width="50%">
	<tr><th>phpcheckout<sup>TM</sup> System Test</th></tr>
	<tr><td>
	<b>WARNING!!!</b><br>
	You cannot purchase nor download product in this mode.
	<br><br>
	This is a phpcheckout<sup>TM</sup> test. 
	The administrator has set the constant called PROCESSOR 
	found in configure.php to the value of &quot;localhost&quot;. Change this value to 
	&quot;paypal&quot; to enable a real payment to be made.
	All paypal functionality is bypassed in this mode. 
	<br><br>
	 Test Mode allows the site administrator to test a new or 
	 changed phpcheckout<sup>TM</sup> system.
	</td></tr>
</table>
<?php endif;?>