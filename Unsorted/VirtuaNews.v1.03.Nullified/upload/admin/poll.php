<?php

/*======================================================================*\
|| #################################################################### ||
||  Program Name         : VirtuaNews Pro                                 
||  Release Version      : 1.0.3                                          
||  Program Author       : VirtuaSystems                                  
||  Supplied by          : Ravish                                         
||  Nullified by         : WTN Team                                       
||  Distribution         : via WebForum, ForumRU and associated file dumps
|| #################################################################### ||
\*======================================================================*/

if (preg_match("/(admin\/poll.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

updateadminlog(iif($id,"id = $id",""));

switch ($action) {

case "poll":

  echohtmlheader();
  echotableheader("Edit Polls",1);
  echotabledescription("Using this section you can add edit and delete polls that show on $sitename",1);
  echotabledescription(returnlinkcode("Add Poll","admin.php?action=poll_add"),1);

  $tablerows = returnminitablerow("<b>Question</b>","<b>Total Votes</b>","<b>Options</b>");

  $getdata = query("SELECT id,question,totalvotes FROM news_poll ORDER BY id DESC");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow($data_arr[question],"$data_arr[totalvotes]&nbsp;",returnlinkcode("Edit","admin.php?action=poll_edit&id=$data_arr[id]")." |".returnlinkcode("Delete","admin.php?action=poll_delete&id=$data_arr[id]"));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ",1);
  echotablefooter();
  echohtmlfooter();

break;

case "poll_add":

  echohtmlheader();
  echoformheader("poll_new","Add a poll");
  echotabledescription("Below you can add a poll to be displayed on the site, to continue fill in all the details with the appropriate values and press submit");
  echotabledescription("NOTE At least 2 options must be entered for it to be a valid poll");
  echoinputcode("Question:","question","",60);
  echoinputcode("Option 1:","option[1]");
  echoinputcode("Option 2:","option[2]");

  for ($i=3;$i<11;$i++) {
    echoinputcode("Option $i:","option[$i]","",40,1);
  }

  foreach ($cat_arr as $key => $val) {
    $checkboxes .= returncheckboxcode("showcategories[$key]",1,$val[name],iif($key == $defaultcategory,1,0));
  }

  echotablerow("Show in which news categories?<br /><span class=\"red\">(you may select multiple ones)</span>","\n$checkboxes    ");
  echoformfooter();
  echohtmlfooter();

break;

case "poll_new":

  if ($question == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  $lastoption = 0;
  foreach ($option AS $temp) {
    $votes .= iif($temp != "",iif($votes != "",",0",0),"");
    if ($temp != "") {
      $option_new[$lastoption++] = $temp;
    }
  }

  if (countchar($votes,",") < 1) {
    adminerror("Not Enough Options","At least 2 options must be entered for it to be a valid poll");
  }

  foreach ($cat_arr AS $key => $val) {
    $categorydisplay .= iif($showcategories[$key],iif($categorydisplay,",$key",$key),"");
  }

  for ($i=0;$i<10;$i++) {
    $optionsql .= ",'$option_new[$i]'";
  }

  query("INSERT INTO news_poll VALUES (NULL,'$question'$optionsql,'0','$votes','$categorydisplay')");

  $pollid = getlastinsert();

  query("UPDATE news_category SET pollid = $pollid WHERE id IN ($categorydisplay)");

  writeallpages();

  echoadminredirect("admin.php?action=poll");

break;

case "poll_edit":

  verifyid("news_poll",$id);

  $data_arr = query_first("SELECT question,option1,option2,option3,option4,option5,option6,option7,option8,option9,option10,totalvotes,display FROM news_poll WHERE id = $id");
  $cat_display = explode(",",$data_arr[display]);
  foreach ($cat_display AS $temp) {
    $display[$temp] = 1;
  }

  echohtmlheader();
  echoformheader("poll_update","Edit poll on $sitename");
  updatehiddenvar("id",$id);
  echotabledescription("Below you can edit a poll that is displayed on the site, to continue fill in all the details with the appropriate values and press submit");
  echotabledescription("NOTE At least 2 options must be entered for it to be a valid poll");
  echoinputcode("Question:","question",$data_arr[question],60);

  for ($i=1;$i<11;$i++) {
    echoinputcode("Option $i:","option[$i]",$data_arr[option.$i]);
  }

  foreach ($cat_arr as $key => $val) {
    $checkboxes .= returncheckboxcode("showcategories[$key]",1,$val[name],iif($display[$key],1,0));
  }

  echotablerow("Show in which site sections?<br /><span class=\"red\">(you may select multiple ones)</span>","\n$checkboxes    ");
  echoformfooter();
  echohtmlfooter();

break;

case "poll_update":

  if ($question == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  foreach ($option AS $temp) {
    if ($temp != "") {
      $validoption ++;
    }
  }

  if ($validoption < 2) {
    adminerror("Not Enough Options","At least 2 options must be entered for it to be a valid poll");
  }

  $old_data = query_first("SELECT votes,totalvotes,display FROM news_poll WHERE id = $id");

  $splitvotes = explode(",",$old_data[votes]);

  $lastoption = 0;
  $count = 0;
  foreach ($option AS $temp) {
    if ($temp != "") {
      $option_new[$lastoption++] = $temp;
      $votes_new[$lastoption] = $splitvotes[$count];
      query("UPDATE news_pollvote SET voteoption = '$lastoption' WHERE (voteoption = '".($count+1)."') AND (pollid = $id)");
    } else {
      query("DELETE FROM news_pollvote WHERE (voteoption = '".($count+1)."') AND (pollid = $id)");
    }
    $count++;
  }

  $totalvotes = array_sum($votes_new);
  $votes = join(",",$votes_new);

  foreach ($cat_arr AS $key => $val) {
    $categorydisplay .= iif($showcategories[$key],iif($categorydisplay,",$key",$key),"");
  }

  for ($i=0;$i<10;$i++) {
    $optionsql .= "    option".($i+1)." = '$option_new[$i]',\n";
  }

  query("
    UPDATE news_poll SET
    question = '$question',
$optionsql    display = '$categorydisplay',
    votes = '$votes',
    totalvotes = '$totalvotes'
    WHERE id = $id
  ");

  $newdisplay = (strlen($categorydisplay) > 0) ? (explode(',', $categorydisplay)) : (array());
  $olddisplay = (strlen($old_data[display]) > 0) ? (explode(',', $old_data[display])) : (array());

  $update = array();

  foreach ($newdisplay AS $val) {
    if (!in_array($val, $olddisplay)) {
      $update[] = $val;
    }
  }

  foreach ($olddisplay AS $val) {
    if (!in_array($val, $newdisplay) && !in_array($val, $update)) {
      $update[] = $val;
    }
  }

  if (count($update) > 0) {
    foreach ($update AS $val) {

      $next = query_first("SELECT id FROM news_poll WHERE (display LIKE '$val,%') OR (display LIKE '%,$val,%') OR (display LIKE '%,$val%') OR (display = '$val') ORDER BY id DESC LIMIT 1");

      if (empty($next)) {
        $next[id] = 0;
      }

      query("UPDATE news_category SET pollid = $next[id] WHERE id = $val");

    }

  }

  writeallpages();

  echoadminredirect("admin.php?action=poll");

break;


case "poll_delete":

  verifyid("news_poll",$id);
  echodeleteconfirm("poll","poll_kill",$id);

break;

case "poll_kill":

  verifyid("news_poll",$id);

  $old_data = query_first("SELECT display FROM news_poll WHERE id = $id");

  $olddisplay = (strlen($old_data[display]) > 0) ? (explode(',', $old_data[display])) : (array());

  query("DELETE FROM news_poll WHERE id = $id");
  query("DELETE FROM news_pollvote WHERE pollid = $id");

  if (count($olddisplay) > 0) {
    foreach ($olddisplay AS $val) {

      $next = query_first("SELECT id FROM news_poll WHERE (display LIKE '$val,%') OR (display LIKE '%,$val,%') OR (display LIKE '%,$val%') OR (display = '$val') ORDER BY id DESC LIMIT 1");

      if (empty($next)) {
        $next[id] = 0;
      }

      query("UPDATE news_category SET pollid = $next[id] WHERE id = $val");

    }

  }

  writeallpages();

  echoadminredirect("admin.php?action=poll");

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/poll.php
|| ####################################################################
\*======================================================================*/

?>