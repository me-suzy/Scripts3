<?


#################################################################################################
#
#  project           	: phpListPro
#  filename          	: lang_english.php
#  version           	: 1.71
#  last modified by  	: Erich Fuchs
#  nullified by      	: CyKuH [WTN]          
#  purpose           	: Language File
#  last modified     	: 06/01/2001
#
#################################################################################################





#  Category Settings


#################################################################################################





$category[0]            = "All Entries";                // must be all Entries


$category[1]            = "Category 1";


$category[2]            = "Category 2";


$category[3]            = "Category 3";


$category[4]            = "Category 4";


$category[5]            = "Category 5";


$category[6]            = "Category 6";








#  Language Settings


#################################################################################################





$lang[metatag]        	= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">";





$lang[rank] 		= "#";


$lang[site] 		= "Sites";


$lang[votes]		= "In";


$lang[hits] 		= "Out";


$lang[rate] 		= "Rating";


$lang[random] 		= "Random link";





$lang[in_msg1]		= "You are now entering from";


$lang[in_msg2]		= "Please press the [Vote for this site] button to vote for this site.";


$lang[in_msg3]		= "Enter the $list_name without voting.";


$lang[in_chose]		= "Please, choose your rating";


$lang[in_vote]		= "Vote for this site";





$lang[anti_cheat_msg] 	= "<center><font color=red>You have already voted within the last $vote_timeout hours! Your vote hasn't been logged.</font></center>";


$lang[vote_log_msg] 	= "<center><font color=red>Thanks! Your vote has been counted!</font></center>";


$lang[not_found_msg] 	= "<center><font color=red>ERROR: Site NOT found !!!</font></center>";


$lang[tilt_msg] 		= "<center><font color=red>This site is temporary invisible after TILT-Detection! Try it later!</font></center>";


$lang[nocookie_msg] 	= "<center><font color=red>Your browser is NOT cookie-enabled! Your vote hasn't been logged.</font></center>";


$lang[ban_msg] 		= "<center><font color=red>You're BANNED from voting on this List. Your vote hasn't been logged.</font></center>";





$lang[emailconfirmed]	= "E-Mail confirmed successfully!";


$lang[emailnotconf]		= "E-Mail NOT confirmed !!! PLEASE contact the Webmaster.";


$lang[redirect]		= "Redirect to list, please wait ...";


$lang[confirmation]		= "Confirmation";





$lang[pages]		= "Pages: ";


$lang[useronline]		= "user online";


$lang[usersonline]		= "users online";


$lang[userssatisfied]	= "users satisfied";


$lang[sitesindb]		= "sites in our database";


$lang[inoutresets]		= "In/Out resets every";


$lang[days]		= "days";


$lang[next]		= " next";





$lang[addsite]		= "Add Site";


$lang[addsitedesc]		= "Add a new site";


$lang[editsite]		= "Edit Site";


$lang[editsitedesc]		= "Edit a existing site";


$lang[helpsite]		= "Help";


$lang[helpsitedesc]		= "HTML implementation help";





$lang[error]		= "Error! please go back and fix them!";


$lang[error_duplicate]	= "Duplicate site address found!";


$lang[error_emptysiten]	= "You didn't enter your site's name";


$lang[error_emptysitea]	= "You didn't enter your site's address";


$lang[error_emptypass]	= "You didn't enter a password";


$lang[error_emptyemail]	= "You didn't enter your e-mail address";


$lang[error_emptyname]	= "You didn't enter your name";


$lang[error_emptydesc]	= "You didn't enter your site's description";


$lang[error_bannerw]	= "You didn't enter a valid banner width";


$lang[error_bannerh]	= "You didn't enter a valid banner height";


$lang[error_banners]	= "Your banner filesize exeed's the filesize-limit ";


$lang[error_bannernok]	= "Your banner URL is not valid";


$lang[error_baddesc]	= "Bad word detected in your site's description";


$lang[error_badname]	= "Bad word detected in your site's name";





$lang[back]		= "Back";


$lang[done]		= "Done";


$lang[submit]		= "Submit";


$lang[search]		= "Search";


$lang[getlost]		= "Lost ID and/or password ?";


$lang[getid]		= "Get ID/password";


$lang[editsite]		= "Edit Site";





$lang[enterdata]		= "Enter your data";


$lang[password]		= "Password: ";


$lang[siteid]		= "Site ID: ";


$lang[emailaddr]		= "E-mail address: ";


$lang[yourname]		= "Your name: ";


$lang[sitetitle]		= "Site title: ";


$lang[siteurl]		= "Site URL: ";


$lang[sitedesc]		= "Site description: ";


$lang[bannerurl]		= "Banner URL: ";


$lang[bannerw]		= "Banner width: ";


$lang[bannerh]		= "Banner height: ";





$lang[getlost2]		= "Get lost site ID and Password";


$lang[edityoursite]		= "Edit your site";





$lang[getidok]		= "Site found and information has been sent!";


$lang[getidnok]		= "No site with that e-mail address has been found!";


$lang[editidnok]		= "No site with that data has been found!";


$lang[editsuccess]		= "Site updated successfully!";





$lang[addthanks]		= "Thanks! Your site has been added!";


$lang[addloginid]		= "Your log-in ID is: ";


$lang[addsendmail]		= ", we sent a confimation to your e-mail address.";


$lang[addhint]		= "You will need this ID and your password to edit your entry.";


$lang[addapproval]		= "Please be patient, the Webmaster will check your entry as soon as possible.";


$lang[addplease]		= "Please put one (or more) of the following HTML code on your site so people can vote for you!";





$lang[help]		= "HELP";


$lang[helpreplace1]		= "Replace <b>XXXXXXXX</b> with your ID, we sent to your e-mail address.";


$lang[helpreplace2]		= "Replace <b>www.yourdomain.com</b> with the URL to your VoteImage.";





$lang[addrules]		= "Rules for joining";


$lang[addyoursite]		= "Add your site";





$lang[rules]		= "<ul>


			    <li>Ranking depends only on INCOMING hits


			    <li>NO scripts allowed for incoming hits


			    <li>Only TOP 10 sites displayed with banners


			    <li>The counters will be reset periodically


			    <li>!!! Cheating is NOT allowed !!!


			   </ul>";








#  Help Language Forms


#################################################################################################





function help($id) {


    global $list_name,$list_url,$voteimage_1,$voteimage_2,$voteimage_3;





echo "<hr><b><li>TextLink:</b><br>";


echo "&lta href=\"$list_url/in.php?site=$id\" target=\"_blank\"&gt$list_name&lt/a&gt\n";








if ($voteimage_1) {


  echo "<p><hr><b><li>PictureLink:</b><br>\n";


  echo "&lta href=\"$list_url/in.php?site=$id\" target=\"_blank\"&gt<br>&ltimg src=\"http://www.yourdomain.com/vote_image2.gif\" alt=\"Enter $list_name and Vote for this site !!!\" border=0&gt&lt/a&gt\n";


  echo "<p><center><img src=\"$voteimage_1\"><br>This is the image. Please save it on your server.<br>(Right mouseclick - Save as ...)</center><br>\n";


  }





if ($voteimage_2) {


  echo "<p><hr><b><li>PictureLink 2:</b><br>\n";


  echo "&lta href=\"$list_url/in.php?site=$id\" target=\"_blank\"&gt<br>&ltimg src=\"http://www.yourdomain.com/vote_image2.gif\" alt=\"Enter $list_name and Vote for this site !!!\" border=0&gt&lt/a&gt\n";


  echo "<p><center><img src=\"$voteimage_2\"><br>This is the image. Please save it on your server.<br>(Right mouseclick - Save as ...)</center><br>\n";


  }





if ($voteimage_3) {


  echo "<p><hr><b><li>PictureLink 3:</b><br>\n";


  echo "&lta href=\"$list_url/in.php?site=$id\" target=\"_blank\"&gt<br>&ltimg src=\"http://www.yourdomain.com/vote_image2.gif\" alt=\"Enter $list_name and Vote for this site !!!\" border=0&gt&lt/a&gt\n";


  echo "<p><center><img src=\"$voteimage_3\"><br>This is the image. Please save it on your server.<br>(Right mouseclick - Save as ...)</center><br>\n";


  echo "<p>";


  }


}





#  Mail Language Forms


#################################################################################################





function mail_getidpwd($result) {		// Mail Form for sending lost ID/Password


    global $list_name,$list_url,$admin_email;





mail("$result[email]", "Login data request from $list_name", "Hello $result[name],


this is your site information for your site from $list_name.





Your site:


$result[site_name]


$result[site_addr]





Don't forget these!


Site code: $result[id]


Password: $result[password]





$list_url/list.php





Vote image code:





<a href=\"$list_url/in.php?site=$result[id]\" target=\"_blank\"><img src=\"www.yourdomain.com/vote_image1.gif\" border=\"0\"></a>


", "From: $admin_email");


}














function mail_newnotifyuser($email_address,$webmaster_name,$id,$password,$site_name,$site_address,$site_description) {


    global $list_name,$list_url,$admin_email,$secret,$webmasterapproval,$emailapproval;





if ($webmasterapproval) {$webmastermsg="\nPlease be patient, the Webmaster will check your entry as soon as possible.\n";} else {$webmastermsg="";}


if ($emailapproval) {$emailmsg="\nPlease click this link to confirm your entry:\n$list_url/confirm.php?check=".urlencode(encrypt($id,$secret))."\n\n";} else {$emailmsg="";}





mail("$email_address", "Welcome $webmaster_name to the $list_name", "Thanks $webmaster_name for joining the $list_name.


$webmastermsg$emailmsg


Your site:


$site_name ($site_address)





Don't forget these!


Site code: $id


Password: $password





$list_url/list.php





Vote image code:





<a href=\"$list_url/in.php?site=$id\" target=\"_blank\"><img src=\"http://www.yourdomain.com/$voteimage_1\" alt=\"Enter $list_name and Vote for this Site !!!\" border=0></a>





", "From: $admin_email");


}














function mail_newnotifyadm($email_address,$webmaster_name,$id,$password,$site_name,$site_address,$site_description) {


    global $list_name,$list_url,$admin_email,$notify_email;





mail("$notify_email", "NOTIFY $webmaster_name joined the $list_name", "$webmaster_name joined the $list_name.





Site code: $id


Password: $password


Site name: $site_name


Site address: $site_address





Email: $email_address





Banner address (if any): $banner_address





Description:


$site_description








$list_url/list.php





", "From: $admin_email");


}














function mail_editnotifyadm($email_address,$webmaster_name,$id,$password,$site_name,$site_address,$site_description) {


    global $list_name,$list_url,$admin_email,$notify_email;





mail("$notify_email", "NOTIFY $webmaster_name edited on $list_name", "$webmaster_name edited on $list_name.





Site code: $id


Password: $password


Site name: $site_name


Site address: $site_address





Email: $email_address





Banner address (if any): $banner_address





Description:


$site_description








$list_url/list.php





", "From: $admin_email");


}














function mail_tiltuser($result) {		// Mail Form for sending user on TILT


    global $list_name,$list_url,$admin_email,$tilt_insp_timeout;





mail("$result[email]", "ALERT Cheating-Detection from $result[site_name] on $list_name",


"Hi Webmaster,





our Cheating-Detection at $list_name has detected some strange votings for your site





ID: $result[id]


$result[site_name] ($result[site_addr])





So your site is invisible at $list_name ($list_url) for $tilt_insp_timeout minutes.


New suspect votes, within this time increase the timeout !





sincerely





Webmaster


", "From: $admin_email");


}














function mail_tiltadmin($result) {		// Mail Form for sending lost ID/Password


    global $list_name,$list_url,$admin_email,$notify_email,$tilt_insp_timeout;





mail("$notify_email",  "NOTIFY Cheating-Detection from $result[site_name] on $list_name",


"Hello Master,





the Cheating-Detection at $list_name has detected some strange votings for this site.





ID: $result[id]


$result[site_name] ($result[site_addr])





So this site is invisible at $list_name ($list_url) for $tilt_insp_timeout minutes.





Cheating-Detection (Tilt-Protection)


", "From: $admin_email");


}











#  End Language


#################################################################################################


?>
