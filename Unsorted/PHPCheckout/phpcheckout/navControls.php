<?php echo"\n\n\n";?>
<!-- start of navControls.php -->
<?php if(FPSTATUS=='Online'):?>
<a href="<?php echo PHPCHECKOUTPAGE;?>">Home |</a> 
<a href="showcaseIndex.php">Products |</a> 
<a href="services.php">Services |</a> 
<a href="members.php">Members |</a> 
<a href="company.php">Company</a>
<?php else:?>
<span style="color:red;font-size:16px;font-weight:bold;"><BLINK>O F F L I N E</BLINK></span>
<?php endif;?>
<!-- end of navControls.php -->