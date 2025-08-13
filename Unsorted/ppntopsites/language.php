<?php

//Index Page
$ppn_notice["index_link"] = "Top Sites";
$ppn_notice["join"] = "Add Your Site";
$ppn_notice["edit"] = "Edit Your Site";
$ppn_notice["lost_id"] = "Lost ID";
$ppn_notice["lost_code"] = "Lost Code";
$ppn_notice["report"] = "Report Cheater";
$ppn_notice["File_Error"] = "We could not find the files :";
$ppn_notice["go"] = "Go";
$ppn_notice["rank"] = "Rank";
$ppn_notice["site_info"] = "Site Information";
$ppn_notice["average"] = "Average";
$ppn_notice["hits_today"] = "Hits<br>Today";
$ppn_notice["your_site"] = "Your Site";
$ppn_notice["time_generate1"] = "Page generated in";
$ppn_notice["time_generate2"] = "seconds.";
//Join Page
$ppn_notice["join_msg"] = "Fill out the form to join";
$ppn_notice["html_warning"] = "Absolutely No HTML Codes Allowed";
$ppn_notice["join_topsites"] = "Web Site Name";
$ppn_notice["join_url"] = "Web Site URL";
$ppn_notice["join_email"] = "Your Email address";
$ppn_notice["join_button"] = "URL for your button";
$ppn_notice["join_pass"] = "Password"; 
$ppn_notice["join_des"] = "Site Description";
$ppn_notice["join_but"] = "Join";
$ppn_notice["error_email"] = "You have not entered a valid Email";
$ppn_notice["error_url"] = "You have not entered a valid url";
$ppn_notice["error_button"] = "You have not entered a valid banner url";
$ppn_notice["error_des"] = "Please push the back button on your browser to correct these errors.";
$ppn_notice["join_confirm"] = "You are now signed up, Insert this code on your site.";
//Edit Account Page
$ppn_notice["edit_msg"] = "Edit Account on";
$ppn_notice["edit_but"] = "Edit";
$ppn_notice["edit_confirm"] = "Details Successfully Changed!";
$ppn_notice["edit_error"] = "Your ID and Password do not Match.";
//LodeCode Page
$ppn_notice["lostcode_msg"] = "Please Enter your ID Number";
$ppn_notice["lostcode_but"] = "Generate";
$ppn_notice["lostcode_confirm"] = "The Code for your ID is:";
//LostId Page
$ppn_notice["lostid_msg"] = "Please Enter your URL";
$ppn_notice["lostid_but"] = "Find";
$ppn_notice["lostid_confirm"] = "Your id number is:";
$ppn_notice["lostid_error"] = "Unable to find your ID!";
//Report
$ppn_notice["report_name"] = "Your Name";
$ppn_notice["report_email"] = "Your Email address";
$ppn_notice["report_site"] = "Offending Site";
$ppn_notice["report_reason"] = "Reason";
$ppn_notice["report_but"] = "Send";
$ppn_notice["report_confirm"] = "Thanks for submitting this report!";
$ppn_notice["report_error"] = "Please Fill out all parts of the Form";

$top = <<< TOP
<html>
<head>
<title>$tname - $title</title>
<style>
a:link       { color: $lcolour; text-decoration: none }
a:visited    { color: $vcolour; text-decoration: none }
a:hover      { color: $hcolour; text-decoration: none }
a:active     { color: $acolour; text-decoration: none }
</style>
</head>
<body bgcolor="$bgcolour">
$header
<center>
<center><font face="$font" size="2" color="$fontcolour"><b><a href="index.php">$ppn_notice[index_link]</a> | <a href="join.php">$ppn_notice[join]</a> | <a href="change.php">$ppn_notice[edit]</a> | <a href="lostid.php">$ppn_notice[lost_id]</a> | <a href="lostcode.php">$ppn_notice[lost_code]</a> | <a href="report.php">$ppn_notice[report]</a></b></font></center>
<br><br>
TOP;

?>