<?php echo"\n\n\n";?>
<!-- START OF CUSTOMER SURVEY-->
<FORM NAME="surveyForm" ACTION="surveyResults.php" METHOD="post">

<p>
<?php include("surveyQuestion.php");?>
<br><br><input type="radio" name="response" value="yes"> yes 
<input type="radio" name="response" value="no"> no 
<br><br><input class="submit" type="submit" name="submit" value="Next Step">
</p>
<INPUT TYPE="Hidden" NAME="goal" VALUE="<?php echo $goal;?>">
<input type="hidden" name="update_survey_results" value="true">
</form>
<!-- END of customer survey -->