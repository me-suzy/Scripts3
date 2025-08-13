<br>
<font color="#000000"><b>Account Management</b></font><br>
<?php
	if($sessionUser == "")
	{
		print "»<A href=\"login.php\"> <b>Login</b></font></a><br>";
		print "»<A href=\"register.php\"> <b>Register</b></a><br><br>";
	}
	else
	{
                print "» <A href=\"sites.php\"><b>Add/Delete Site</b></a><br>";
                print "» <A href=\"changeInfo.php\"><b>Change Password</b></a><br>";
                print "» <A href=\"referralUrl.php\"><b>Get the Code</b></a><br><br>";
                print "» <A href=\"logout.php\"><b>Logout</b></a><br><br>";

                print "<font color=\"#000000\"><b>View Stats</b></font><br>";
                print "» <A href=\"selectSite.php\"><b>Site Statistics</b></a><br><br>";

                print "<font color=\"#000000\"><b>Hit Statistics</b></font><br>";
                print "» <A href=\"dailyVisitors.php\"><b>Daily Visitors</b></a><br>";
                print "» <A href=\"monthlyVisitors.php\"><b>Monthly Visitors</b></a><br>";
                print "» <A href=\"totalVisitors.php\"><b>Total Visitors</b></a><br><br>";

                print "<font color=\"#000000\"><b>Browsers/Systems</b></font><br>";

                print "» <A href=\"browser.php\"><b>Browser</b></a><br>";
                print "» <A href=\"colordepth.php\"><b>Color Depth</b></a><br>";
                print "» <A href=\"javaandjavascript.php\"><b>Java & Javascript</b></a><br>";
                print "» <A href=\"language.php\"><b>Language</b></a><br>";
                print "» <A href=\"operatingsystem.php\"><b>Operating System</b></a><br>";
                print "» <A href=\"screenresolution.php\"><b>Screen Resolution</b></a><br><br>";
	}
?>

<font color="#000000"><b>Our Site</b></font><br>
» <A href="faqs.php"><b>FAQ's</b></a><br>
» <A href="contactUs.php"><b>Contact Us</b></a><br><br>
