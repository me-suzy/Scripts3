<?php


require("global.php");

switch($action) {

case "":

  settype($perpage,"integer");
  settype($pagenum,"integer");
  if ((isset($perpage) == 0) | ($perpage == 0)) {
    $perpage = $pollsperpage;
  }

  if ((isset($pagenum) == 0) | ($pagenum == 0)) {
    $pagenum = 1;
  }

  $offset = ($pagenum - 1) * $perpage;

  $numrecords = countrows(query("SELECT id FROM news_poll"));
  $pagenav = pagenav($perpage,$pagenum,"polls.php?",$numrecords);

  $getdata = query("SELECT id,question,option1,option2,option3,option4,option5,option6,option7,option8,option9,option10,totalvotes,votes FROM news_poll ORDER BY id DESC LIMIT $offset,$perpage");

  while ($data_arr = fetch_array($getdata)) {

    $totalvotes = $data_arr[totalvotes];

    $splitvotes = explode(",",$data_arr[votes]);

    $results = "";

    for ($i=1;$i<=10;$i++) {
      if ($data_arr[option.$i]) {
        $option = $data_arr[option.$i];
        $optioncount = $i;
        @$barwidth = round(($splitvotes[$i-1]/$totalvotes)*50);
        $no_of_votes = $splitvotes[$i-1];
        eval("\$results .= \"".returnpagebit("poll_view_results_option")."\";");
      }

    }

    $question = $data_arr[question];

    eval("\$poll_results .= \"".returnpagebit("poll_view_results_table")."\";");
  }

  $navbar = makenavbar("Polls");
  include("static/sub_pages/poll_results_".$pagesetid.".php");

break;

case "pollvote":

  verifyid("news_poll",$pollid);

  if ((isset($votedpoll[$pollid]) == 1) | (($loggedin == 1) & ($uservoteinfo = query_first("SELECT userid FROM news_pollvote WHERE (userid = $userid) AND (pollid = $pollid)")))) {
    standarderror("already_voted");
  }

  if (!$loggedin & !$pollanonvote) {
    standarderror("no_anon_voting");
  }

  if (!$loggedin) {
    updatecookie("votedpoll[$pollid]","$pollvote");
  }

  $poll_data = query_first("SELECT votes FROM news_poll WHERE id = $pollid LIMIT 1");

  $splitvotes = explode(",",$poll_data[votes]);

  $splitvotes[$pollvote-1]++;

  $newvotes = implode(",",$splitvotes);

  query("UPDATE news_poll SET totalvotes = totalvotes+1 , votes = '$newvotes' WHERE id = $pollid");

  query("INSERT INTO news_pollvote VALUES(NULL,'$pollid','$userid','".time()."','$pollvote')");

  include("includes/writefunctions.php");
  include("includes/adminfunctions.php");
  writeallpages();

  standardredirect("voted",iif($pollredirect,$pollredirect,"index.php"));

break;

default:
  standarderror("invalid_link");
}

/*======================================================================*\
|| ####################################################################
|| # File: polls.php
|| ####################################################################
\*======================================================================*/

?>