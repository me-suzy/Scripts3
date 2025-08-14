<?php


require("global.php");

switch($action) {

case "faq":

  $navbar = makenavbar("FAQ's");
  @include("static/sub_pages/help_faq_".$pagesetid.".php");

break;

case "qhtml":

  $navbar = makenavbar("Quick HTML");
  @include("static/sub_pages/help_qhtml_".$pagesetid.".php");

break;

case "smilie":

  if ($use_forum) {
    header_redirect($foruminfo[smilie_path],"Smilies");
  }

  unset($smilielist);

  $smiliedata = getsmiliedata();

  while ($smilie = fetch_array($smiliedata)) {
    eval("\$smilielist .= \"".returnpagebit("help_smilie_bit")."\";");
  }

  $navbar = makenavbar("Smilie Help");
  @include("static/sub_pages/help_smilie_".$pagesetid.".php");

break;

default:
  standarderror("invalid_link");
}

/*======================================================================*\
|| ####################################################################
|| # File: help.php
|| ####################################################################
\*======================================================================*/

?>