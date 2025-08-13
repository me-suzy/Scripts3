<?

#################################################################################################
#
#  project           	: phpBazar
#  filename          	: variables.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose	     	: English Language File
#  version           	: 1.59
#
#################################################################################################



$lang		="English";

$menusep	=" || ";

$lang_metatag	="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">"; // Added in V1.55



$gender[m]	="Male"; // Added in V1.55

$gender[f]	="Female"; // Added in V1.55

$gender[a]	="Couple M/F"; // Added in V1.55

$gender[b]	="Couple F/F"; // Added in V1.55

$gender[c]	="Couple M/M"; // Added in V1.55



$userlevel[0]	="Junior Member";

$userlevel[1]	="Member";

$userlevel[2]	="Senior Member";

$userlevel[3]	="Paying Member";

$userlevel[4]	="Paying Member";

$userlevel[5]	="Paying Member";

$userlevel[6]	="Paying Member";

$userlevel[7]	="Paying Member";

$userlevel[8]	="Senior Moderator";

$userlevel[9]	="Administrator";



$mail_msg[0] 	="$bazar_name Registration";

$mail_msg[1] 	="Welcome ";

$mail_msg[2] 	="Thank you for registering with $bazar_name!\nHere is the information we recieved :\n\nUsername : ";

$mail_msg[3] 	="Password : ";

$mail_msg[4] 	="E-Mail   : ";

$mail_msg[5] 	="Gender   : ";

$mail_msg[6] 	="You need to confirm the account by pointing your browser at: ";

$mail_msg[7] 	="Your Webmaster\n$url_to_start\n\nPS: If you did not apply for the account please ignore this message.";

$mail_msg[8] 	="Hi Webmaster\n\n There is a new Registration at $bazar_name :\n\nUsername : ";

$mail_msg[9] 	="$bazar_name Registration Confirmation";

$mail_msg[10]	="Hello ";

$mail_msg[11]	="Thank you for confirming your Account!\n\nNow you can login with your Username and your Password.\n\nYour Webmaster\n$url_to_start";

$mail_msg[12]	="Account Information";

$mail_msg[13]	="Hi ";

$mail_msg[14]	="As per your request here is your account information: \n\nUsername : ";

$mail_msg[15] 	="Your Webmaster\n$url_to_start";

$mail_msg[16]	="E-Mail Change Confirmation";

$mail_msg[17]	="Dear User\n\nYou have requested an email change in our database.\nTo ensure authenticity of the email we expect you to go to:";

$mail_msg[18]	="Thank You\n\nYour Webmaster\n$url_to_start";

$mail_msg[19]	="Ad Expiry Notification";

$mail_msg[20]	="Dear User\n\nThe following Ad will expire in $timeoutnotify days:\n";

$mail_msg[21]	="To extend the ad duration within $timeoutconfirm days, go to:\n\n";

$mail_msg[22]	="Thank You\n\nYour Webmaster\n$url_to_start";

$mail_msg[23]	="Notification about new Mail"; // Added in V1.50

$mail_msg[24]	="Hello $toname\n\nYou have new Mail in your WebMail-Inbox at $bazar_name!\n\nLogin to $url_to_start and check your Mail.\n\nYour Webmaster\n$url_to_start"; // Added in V1.50



$status_msg[0]	="Logout OK";

$status_msg[1]	="Login OK";

$status_msg[2]	="Not Found";

$status_msg[3]	="Empty Field";

$status_msg[4]	="Sent";

$status_msg[5]	="Update OK";

$status_msg[6]	="Error";

$status_msg[7]	="Deleted";

$status_msg[8]	="Empty Votes";

$status_msg[9]	="Vote Error";

$status_msg[10]	="Voting OK";

$status_msg[11]	="GB Update Error";

$status_msg[12]	="GB Update OK";

$status_msg[13]	="Stored OK";



$error[0] 	="Passwords do not match each other";

$error[1] 	="Username is too short. Minimum is 3 valid characters.";

$error[2] 	="Username is too long. Maximum is 11 valid characters.";

$error[3] 	="Username contains invalid characters.";

$error[4] 	="Email address format is invalid.";

$error[5] 	="Password is too short. Minimum is 3 valid characters.";

$error[6] 	="Password is too long. Maximum is 20 valid characters.";

$error[7] 	="Password contains invalid characters.";

$error[8] 	="First or Last Name contains invalid characters.";

$error[9] 	="Field contains invalid characters.";

$error[10] 	="ICQ contains invalid characters.";

$error[11] 	="Gender can not be empty."; // Changed in V1.55

$error[12] 	="Username already exists.";

$error[13] 	="A Member with that email already exists.";

$error[14] 	="Some fields were left empty.";

$error[15] 	="Temporary ID and Username combination incorrect, or account purged.";

$error[16] 	="Unknown database failure, please try later.";

$error[17] 	="Your login information was entered but due to an unknown error other details could not be filled in. Please login to your account and remove it immediately and then re-register.";

$error[18] 	="The flushing process was unsuccessful.";

$error[19] 	="No username corresponds to that email.";

$error[20] 	="Your registration details could not be updated due to a database fault.";

$error[21] 	="Your password could not be updated due to a database fault.";

$error[22] 	="Your emails do not match.";

$error[23] 	="I think your current email and the email you entered for modification are the same so I can't change anything.";

$error[24] 	="Your vote could not be updated due to a database fault";

$error[25] 	="You already voted so your vote could not be counted !!!";

$error[26] 	="ID and Username combination incorrect, or account purged.";

$error[27]	="I am sorry but you're banned - please contact the Webmaster !!!";

$error[28]	="Sorry. Only Members can vote !!!";

$error[29]	="No AD found !!!<br> Enter the Ad-Number !!!";

$error[30]	="No AD found !!!<br> Try other Queries !!!";



$text_msg[0]    ="Thank you for your Ad !";

$text_msg[1]    ="Thank you for your Ad. Please wait for Webmaster approval";

$text_msg[2]    ="E-Mail confirmation sent to your NEW E-Mail-Address";

$text_msg[3]    ="E-Mail confirmation sent to your E-Mail-Address";



#########################################################

# WARNING!! The $menu_link_desc variables below provide javascript messages for the

# browser status bar. DO NOT use any apostrophes in these text messages

# or they may cause an error in the browser !!!. Tip: Instead of trying to write

# "Reply to this member's ad" why not try "Reply to the ad of this member"

#  or even just "Reply to ad".

##########################################################



$menu_link1	="Home";

$menu_link1desc	="Back Home";

$menu_link1url	="main.php";

$menu_link2	="Bazar";

$menu_link2desc	="Go To The Classified Ads";

$menu_link2url	="classified.php";

$menu_link3	="Pictures";

$menu_link3desc	="Pictures, Pictures, Pictures, ...";

$menu_link3url	="picturelib.php"; // "picturelib.php" for PicLib Option else use "pictures.php"

$menu_link4	="Links";

$menu_link4desc	="Other Selected WebLinks";

$menu_link4url	="links.php";

$menu_link5	="Forum";

$menu_link5desc	="Go To The Forum";

$menu_link5url	="forum.php";

$menu_link6	="Chat";

$menu_link6desc	="Chat With Other Members";

$menu_link6url	="chat.php";

$menu_link7	="WebMail"; // Changed in V1.50

$menu_link7desc	="Check Your Mail"; // Changed in V1.50

$menu_link7url	="webmail.php"; // Changed in V1.50

$menu_link8	="Members"; // Changed in V1.55

$menu_link8desc	="Members, View/Change Your Personal Profile"; // Changed in V1.55

$menu_link8url	="members.php";

$menu_link9	="Guestbook";

$menu_link9desc	="Sign Our Guestbook";

$menu_link9url	="guestbook.php";

$menu_link10	="About";

$menu_link10desc="About Us ...";

$menu_link10url	="contact.php";



$main_head	="Home";

$classified_head="Bazar";

$classadd_head	="Create Ad";

$classedit_head	="Edit Ad";

$classseek_head	="Search Ads";

$classmy_head	="My Ads";

$classfav_head	="Favorites";

$classnot_head	="Auto Notify";

$forum_head	="Forum";

$stories_head	="Stories";

$pictures_head	="Pictures";

$links_head	="Links";

$members_head	="Members"; // Changed in V1.55

$guestbook_head	="Guestbook";

$contact_head	="Contact";

$status_header	="Status";

$useronl_head	="Users Online"; // Added in V1.50

$newmemb_head	="New Member"; // Added in V1.50

$webmail_head	="WebMail"; // Added in V1.50

$classtop_head	="Top"; // Added in V1.55



$lostpw_header	="Lost Password";

$lostpw_email 	="E-Mail";

$lostpw_button	="Get it";



$login_header	="Login";

$login_username	="Username";

$login_password	="Password";

$login_member	="Member#";



$user_online	="User online"; // Added in V1.50

$users_online	="Users online"; // Added in V1.50

$useronl_uname	="Username"; // Added in V1.50

$useronl_ip	="IP-Address"; // Added in V1.50

$useronl_time	="Time"; // Added in V1.50

$useronl_page	="Page"; // Added in V1.50

$useronl_guest	="Guest"; // Added in V1.50



$nav_prev	="Go to previous Page"; // Added in V1.50

$nav_next	="Go to next Page"; // Added in V1.50

$nav_gopage	="Go to this Page"; // Added in V1.50

$nav_actpage	="This Page"; // Added in V1.50



$memf_username	="Username (Nickname)"; // Added in V1.50

$memf_email	="E-Mail"; // Added in V1.50

$memf_level	="Level"; // Added in V1.50

$memf_votes	="Votes"; // Added in V1.50

$memf_lastvote	="Last Vote Date"; // Added in V1.50

$memf_ads	="Ads"; // Added in V1.50

$memf_lastad	="Last Ad Date"; // Added in V1.50

$memf_password	="Password"; // Added in V1.50

$memf_password2	="Confirm Password"; // Added in V1.50

$memf_sex	="Gender"; // Added in V1.50

$memf_newsletter="Newsletter"; // Added in V1.50

$memf_firstname ="Firstname"; // Added in V1.50

$memf_lastname	="Lastname"; // Added in V1.50

$memf_address	="Address"; // Added in V1.50

$memf_zip	="Zip"; // Added in V1.50

$memf_city	="City"; // Added in V1.50

$memf_state	="State"; // Added in V1.50

$memf_country	="Country"; // Added in V1.50

$memf_phone	="Phone"; // Added in V1.50

$memf_cellphone	="Cellphone"; // Added in V1.50

$memf_icq	="ICQ"; // Added in V1.50

$memf_homepage	="Homepage"; // Added in V1.50

$memf_hobbys	="Hobbies"; // Added in V1.50

$memf_field1	="Field1"; // Added in V1.50

$memf_field2	="Field2"; // Added in V1.50

$memf_field3	="Field3"; // Added in V1.50

$memf_field4	="Field4"; // Added in V1.50

$memf_field5	="Field5"; // Added in V1.50

$memf_field6	="Field6"; // Added in V1.50

$memf_field7	="Field7"; // Added in V1.50

$memf_field8	="Field8"; // Added in V1.50

$memf_field9	="Field9"; // Added in V1.50

$memf_field10	="Field10"; // Added in V1.50



$webmail_inbox	="Inbox"; // Added in V1.50

$webmail_sent	="Sent"; // Added in V1.50

$webmail_trash	="Trash"; // Added in V1.50

$webmail_from	="From"; // Added in V1.50

$webmail_to	="To"; // Added in V1.50

$webmail_date	="Date"; // Added in V1.50

$webmail_subject="Subject"; // Added in V1.50

$webmail_message="Message"; // Added in V1.50

$webmail_attach	="Attachments"; // Added in V1.50

$webmail_reply	="Reply to this Message"; // Added in V1.50

$webmail_del	="Delete Message (Trash)"; // Added in V1.50

$webmail_tdel	="Delete Message from Trash"; // Added in V1.50

$webmail_tundel	="Undelete Message from Trash"; // Added in V1.50

$webmail_sdel	="Delete Message from Sent"; // Added in V1.55



#########################################################

# WARNING!! The $xxxx_link_desc variables below provide javascript messages for the

# browser status bar. DO NOT use any apostrophes in these text messages

# or they may cause an error in the browser !!!. Tip: Instead of trying to write

# "Reply to this member's ad" why not try "Reply to the ad of this member"

#  or even just "Reply to ad".

##########################################################



$logi_link1	="Lost Password";

$logi_link1desc	="Get lost Password by EMail";

$logi_link2	="New Member";

$logi_link2desc	="Register as a new Member. IT IS FREE !!!";



$gb_link1	="Add Entry";

$gb_link1desc	="Add your Guestbook Entry";

$gb_link1head	="Add Guestbook Entry";

$gb_pages	="Pages:";

$gb_name	="Name";

$gb_comments	="Comments";

$gb_location	="Location: ";

$gb_posted	="Posted: ";



$gbadd_name	="Name :";

$gbadd_location	="Location :";

$gbadd_email	="E-Mail :";

$gbadd_url	="URL :";

$gbadd_icq	="ICQ :";

$gbadd_msg	="Message :";



$vote_vote	="Vote";

$vote_answer	="Answer";

$vote_button	="Vote";



$memb_link1	="Change E-Mail";

$memb_link1desc	="Change your E-Mail Address";

$memb_link2	="Change Password";

$memb_link2desc	="Change your Password";

$memb_link3	="Delete";

$memb_link3desc	="Delete your Account";



$memb_newvalid	="(must be valid)"; // Added in V1.50

$memb_newterms	="I have read the Terms of Use and accept them !"; // Added in V1.50

$memb_newsubmit	="Register"; // Added in V1.50

$memb_newpublic	="(public viewable)"; // Added in V1.55



$memb_detdeleted="deleted"; // Added in V1.55

$memb_detdetails="Details"; // Added in V1.55

$memb_detuser   ="Username"; // Added in V1.55

$memb_detonl    ="Username"; // Added in V1.55

$memb_detads    ="Ads"; // Added in V1.55

$memb_detvot    ="Vot"; // Added in V1.55

$memb_detmail   ="Mail"; // Added in V1.55

$memb_deticq    ="ICQ"; // Added in V1.55

$memb_deturl    ="URL"; // Added in V1.55



$members_link	="Members"; // Added in V1.55

$members_link_desc="Back to Members Menu"; // Added in V1.55

$members_link1	="Search"; // Added in V1.55

$members_link1desc="Search for Members"; // Added in V1.55

$members_link2	="My Profile"; // Added in V1.55

$members_link2desc="Review, Manage & Edit your Profile"; // Added in V1.55



$myprofile_head	="My Profile"; // Added in V1.55

$memberseek_head="Search Members"; // Added in V1.55

$memberdet_head	="Member Details"; // Added in V1.55

$memberads_head	="Member Ads"; // Added in V1.55



$class_link1	="Create Ad";

$class_link1desc="Create a Classified Ad";

$class_link2	="My Ads";

$class_link2desc="Review, Manage & Edit your Ad Details";

$class_link3	="Search";

$class_link3desc="Search the Bazar Database";

$class_link4	="Favorites";

$class_link4desc="See Ads you placed in My Favorites";

$class_link5	="Auto Notify";

$class_link5desc="See Categories you have selected for Auto Notify";

$class_link	="Bazar";

$class_link_desc="Back to Bazar Main menu";



$ad_pages	="Pages:";

$ad_from	="Posted by:";

$ad_date	="on";

$ad_home	="Bazar";

$ad_sendemail	="Send an E-Mail to this Member";

$ad_sendlink	="Send this Ad (Link) to a Friend";

$ad_icq		="Send an ICQ-Mail to this  Member";

$ad_location	="Location: ";

$ad_noloc	="None";

$ad_text	="Text";

$ad_picwin	="Large Picture";

$ad_enlarge	="Enlarge Picture";

$ad_print	="Print this Ad";

$ad_favorits	="Put this Ad in My Favorites";

$ad_nr		="Ad Number: ";

$ad_gotourl	="View Homepage of Member";

$ad_stat	="Statistics: views/answers";

$ad_att		="Attached Document(s)"; // Added in V1.50

$ad_new		="New ad within last $show_newicon days"; // Added in V1.50

$ad_rating	="Ad Rating "; // Added in V1.50

$ad_yes		="Yes"; // Added in V1.50

$ad_no		="No"; // Added in V1.50

$ad_member	="Member Details"; // Added in V1.55



$adadd_submit	="Submit";

$adadd_user	="Username :";

$adadd_ip  	="IP-Address :";

$adadd_cat 	="Category :";

$adadd_subcat	="Subcategory :";

$adadd_dur	="Duration :";

$adadd_durweeks	="Weeks";

$adadd_durdays	="Days";

$adadd_loc	="(* Required) Location :";

$adadd_head	="(* Required) Title :";

$adadd_text	="(* Required) Ad Text :";

$adadd_selicon	="Select :";

$adadd_fieldend =" :";

$adadd_pic	="Pictures :";

$adadd_picnos	="no special Characters";

$adadd_submitone="[click only once]";

$adadd_att	="Attachments :"; // Added in V1.50

$adadd_delatt	="(delete)"; // Added in V1.50

$adadd_attnos	="only .pdf .doc .txt allowed"; // Added in V1.50

$adadd_forceadd	="<center><br><br>Thank you for logging in.<br><br>

		    Now please place your ad.<br>

		    Select your category below and click 'Submit'.</center>"; // Added in V1.57

$adadd_pretext	="<center><br><br>Please place your ad.<br>

		    Select your category below and click 'Submit'.</center>"; // Added in V1.59



$adseek_adnr	="Ad Number: ";

$adseek_submit	="Submit";

$adseek_cat 	="Category :";

$adseek_subcat	="Subcategory :";

$adseek_all	="All";

$adseek_loc	="Location :";

$adseek_text	="Text Search :";

$adseek_submitone="[click only once]";

$adseek_simple	="Simple Search";

$adseek_adv	="Advanced Search";

$adseek_result	="Search Results";

$adseek_sort    ="Sort Order :";

$adseek_pic	="only w/ pictures :"; // Added in V1.50

$adseek_att	="only w/ attachments :"; // Added in V1.50



$admy_edit	="Edit this Ad Entry";

$admy_delete	="Delete this Ad Entry";

$admy_member	="Member: ";



$admydel_head	="Delete Ad";

$admydel_msg	="Do you really want do delete this ad ???";

$admydel_submit ="Delete";

$admydel_done	="Your Ad is now deleted !!!";



$adfav_delete	="Delete this Ad from My Favorites";

$adnot_delete	="Delete this Category from Auto Notify";



$notifydel_head	="Delete Category from Auto Notify";

$notifydel_msg	="Do you really want to delete this ???";

$notifydel_done	="This Category was deleted from Auto Notify!!!";

$notify_done	="The Category was added to your Auto Notify";

$notify_exist	="The Category is already in your Auto Notify.";

$notify_add	="Add this Category to my Auto Notify";

$notify_head	="Notify";



$sm_mailhead	="Send an E-Mail to this Member";

$sm_linkhead	="Send AD (Link) to a Friend";

$sm_friendhead	="Send our URL (Link) to a Friend";

$sm_friendrefx	="sends the following URL to you: ";

$sm_fromname	="From Name :";

$sm_fromemail	="From E-Mail :";

$sm_toname	="To Name :";

$sm_toemail	="To E-Mail :";

$sm_text	="Message :";

$sm_subject	="Subject :";

$sm_submit	="Send";

$sm_cc		="CC";

$sm_afriend     ="A Friend";

$sm_anonym      ="anonymous";

$sm_friendref	="- Friend Referer";

$sm_answer	="- Reply to AD#";

$sm_systext	="sends you the following AD-Link: ";

$sm_emailheader	="";

$sm_emailfooter	="\n\n-----------------------------\nSent from the $bazar_name @ $HTTP_HOST";



$ar_adid	="Ad Number :"; // Added in V1.50

$ar_rating	="Rating :"; // Added in V1.50

$ar_ratingcount	="Votes :"; // Added in V1.50

$ar_submit	="Rate"; // Added in V1.50

$ar_already	="Sorry, you have already voted for this Ad !!!"; // Added in V1.50



$msghead_error	="Error";

$msghead_message="Message";



$favorits_header="Favorites";

$favorits_done	="The Ad has been placed in your Favorites.";

$favorits_exist	="The Ad is already in your Favorites.";

$favorits_del	="The Ad has been deleted from your Favorites.";



$location_sel	="----- please choose -----";

$back		="Back";

$done		="Done";

$close		="Close";

$submit		="Submit";

$update		="Update"; //Added in V1.50

$smiliehelp	="Smiley Help";



$footer_fav	="Bookmark This Page";

$footer_terms	="Terms of Use";



$cat_new	="New ads within last $show_newicon days"; // Added in V1.55

$cat_pass	="Access only with valid passphrase"; // Added in V1.55

$mess_noentry	="<br><br><br><br><br><center>No database-entries in this view !</center>"; // Added in V1.55

$pass_text	="<br><br><br><br><br><center>This view is only possible with a valid passphrase !</center>"; // Added in V1.55

$memb_notenabled="<br><br><br><br><br><center>This view is not enabled !</center>"; // Added in V1.55

$pass_head	="Passphrase"; // Added in V1.55



#################################################################################################

#

# End

#

#################################################################################################

?>