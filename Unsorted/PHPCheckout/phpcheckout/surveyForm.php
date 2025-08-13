<?php echo"\n\n\n";?>
<!-- START OF surveyForm.php -->
<FORM NAME="surveyForm" ACTION="runner.php" METHOD="post">
<p>
<?php include("surveyQuestion.php");?>


<input type="radio" name="response" value="unused" CHECKED> no answer 
<input type="radio" name="response" value="yes"> yes 
<input type="radio" name="response" value="no"> no 
<input class="input" type="submit" name="submit" value="Next Step">
</p>
<!-- END of customer survey -->