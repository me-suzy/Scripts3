<?php if ( PROCESSOR == "localhost" ):?>
<table class="systemMessage" width="50%">
	<tr><th>phpcheckout<sup>TM</sup> Localhost Test</th></tr>
	<tr><td>This is a phpcheckout<sup>TM</sup> localhost test. 
	<span style="color:red;font-weight:bold;">This test is automatically approved.</span> 
	The administrator has set the constant called PROCESSOR 
	found in configure.php to the value of &quot;localhost&quot;. Change this value to 
	&quot;paypal&quot; to enable a real payment to be made. Localhost Test Mode allows 
	the site administrator to test a new or changed phpcheckout<sup>TM</sup> system. 
	All paypal functionality is bypassed in this mode. Instead, the buyForm is posted 
	direct to processPaypalResponse.php. Also, no email is sent and 
	no purchase table updates are made. This mode lets you test for core phpcheckout
	functionality only.</td></tr>
</table>
<?php endif;?>