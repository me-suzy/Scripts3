<p><a href="#question">change 'short' survey question</a></p>

<h2><a name="survey">Short Survey</a></h2>





<h3>On or off</h3>
<p>
Surveys are defined in configure.php to be on. You can turn them off by opening configure.php 
and setting the OFFERSURVEY constant from a value of 'yes' to a value of 'no'. Save the changed file and 
upload it back to your web server.
</p>
<p>
Taking a survey is always optional- the visitor is given a choice. This tends to 
improve the overall results.
</p>


<h3>Short or User</h3>

You may choose a 'short' or 'user' survey. You can decide which one to use by opening 
configure.php and setting the SURVEYNAME constant value to either 'short' or 'user'. 
The short survey is answered with a short 'yes' or 'no' answer. You set the question:



<h4>Short Survey</h4>
<p>You can ask any question in the short survey, as long as it can be answered with 
&quot;yes&quot; or &quot;no&quot;. 

<b>Examples:</b>
<ul>
<li>Is this site easy to navigate?</li>
<li>Did you find what you wanted on this website?</li>
<li>Was your visit here a satisfying experience?</li>
<li>Do you plan to purchase software online this year?</li>
<li>Would you wait longer than 8 seconds for an average page to load?</li>
<li>Do you normally use the browser scroll bar to see the bottom of a page?</li>
<li>Would you pay a purchase price of $99 for software?</li>
</ul>


<h3><a name="question">How to Change the Short Survey Question</a></h3>

<ol>
	<li>open the file surveyQuestion.php in an html editor</li>
	<li>enter your new question</li>
	<li>save this modified file as 'surveyQuestion.php' on your web server in the /phpcheckout folder</li>
	<li>
		<form name="resetSurveyResultsForm" method="post" action="adminresult.php" onsubmit="popup()" target="popup">
			<input class="submit" type="submit" name="submit" value="Reset Short Survey Results to ZERO"></p>
			<input type="hidden" name="goal" value="Reset Short Survey Results to ZERO">
		</form>
	</li>
</ol>


<div class="favcolor">
<h4>Reset the short survey results back to ZERO</h4>
<p>
When you change the 'short' survey question you will already have existing 'yes' and 'no' answers 
- to some other question! You need to remove these existing answers. This is resetting the survey results 
back to zero. Click the button to reset.
</p>
<p>
When the results are reset it is only for the short survey. The 'user' survey may contain  
significant data which by default is not removed.
</p>
</div>







<h2>User Survey</h2>

<h3>What is it?</h3>
<p>
The 'user' survey allows you to query your visitors about their software and 
hardware preferences. It leans towards software environment questions, as it was initially 
devised for DreamRiver.com
<p>
<h3>How to use</h3>
<p>
	To use the 'user' survey open configure.php and set the SURVEYNAME 
	constant value to 'user', save and upload to web server.
</p>

<h3>Improving user survey results</h3>

<p>To improve results from the user survey you are given control over when to 
allow the data to be inserted into your database. This control is exercised based on the number of questions the 
user has answered. If the visitor has answered MINIMUMSURVEYANSWERS then 
their responses are accepted and inserted into the database. If the visitor has answered 
LESS than MINIMUMSURVEYANSWERS their data is ignored. The current value for MINIMUMSURVEYANSWERS 
( the minimum questions to answer ) is: <?php echo MINIMUMSURVEYANSWERS;?>. You may 
change this value in configure.php.</p>



<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>