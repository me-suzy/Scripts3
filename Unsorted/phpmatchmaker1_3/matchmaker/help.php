<?
require_once("php_inc.php"); 
session_start();
include("header_inc.php");


if (session_is_registered("valid_user"))
{
 do_html_heading("Userguide");
 member_menu();
 
?>
<p>
<b>Match process</b><br>
The matching process works as follows:<br>
<ol>
<li>The user fills out the Wish profile, and press Submit.</li>
<li>The program will now remember what your dreampartner looks like,
and will look for people with the exact profile you submitted.</li>
<li>If a user like that is found, it is added to your Match list</li>
<li>In addion, the program will compare your interest results (your
personal profile) with those users matching your dream profile.</li>
<li>Your match score will be seen at the rightmost column. The higher
score, the better.</li>
</ol>
<p>
<b>Mailbox</b><br>
Users that is logged in can send messages to eachother. It is a internal
mail system only, other people will never see your email address you
used to sign up.
<br>
But it will send you a notification to that emailaddress if 
someone sends you an internal mail. This email adress is not seen
by the sender.
<p>
<b>Favorites</b><br>
This is the place where you see all your favorite users. The users themselves
is manually selected by clicking the "Add to my favorites" link on the
ad to the user you want to make a bookmark to. 
<p>
<b>Visitors</b><br>
Here is all <i>member</i> visits logged. When a logged in user visits
an ad, this person is added to a list of visitors to that ad. Only the owner
of the ad can see this list. The Visit number on each ad shows
both membervisits and anonymous visits. 
<p>
<b>What is the link that says <u><font color='blue'>1.</font></u> everywhere?</b><br>
If a search or a listing shows more than x results (often 20), like
there is more than 20 persons that match you, then you will also
see something like this: <u><font color='blue'>1.</font> <font color='blue'>2.</font> <font color='blue'>Next</font></u>
<br>
That indicate the pagenumber. If you click link 2, you will se page 2. Click 1, and you will se page 1 of matches/searches.

<?
}
else
{
     // they are not logged in 
     do_html_heading("Problem:");
     echo "You are not logged in.<br>";
     exit;
}
print "<p>";
include("footer_inc.php");
?>
