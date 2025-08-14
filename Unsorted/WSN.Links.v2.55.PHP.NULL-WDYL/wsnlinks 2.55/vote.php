<?php

require 'start.php';
$area = $language->title_divider . $language->title_vote;
// access with vote.php?action=vote
if ($action=='vote')
{
 $thislink = new onelink('dummy', $id);
 // check to be sure they haven't already voted
 if ( strstr($HTTP_COOKIE_VARS[votes], " $id ") || strstr($thislink->voterips, $HTTP_SERVER_VARS['REMOTE_ADDR']) || (($thismember->id > 0) && (strstr($thislink->voterids, ' '. $thismember->id .' '))))
 {
  if (!$template) $template = new template('redirect.tpl');
  $template->replace('{MESSAGE}', $language->vote_alreadyvoted);
  if ($returnto == '') $returnto = 'index.php';
  $template->replace('{DESTINATION}', $returnto); 
 }
 else
 {
  if ($thismember->canvote())
  {
   if ($votevalue > $settings->maxvote) $votevalue = $settings->maxvote; // prevent cheating
   if ($votevalue < $settings->minvote) $votevalue = $settings->minvote;
   // find out existing vote data for this link
   $thislink->voterips .= ' '. $HTTP_SERVER_VARS['REMOTE_ADDR'] .' ';
   if ($thismember->id > 0) $thislink->voterids .= ' '. $thismember->id .' ';
   $thislink->sumofvotes += $votevalue;
   $thislink->votes = $thislink->votes + 1; // incriment the vote counter
   $rating = ($thislink->sumofvotes)/($thislink->votes);
   $thislink->rating = $rating;
   // update the link in the database with the new vote info
   $result = $thislink->update('votes,sumofvotes,rating,voterips');
  
   // handle custom rating fields
   $customratings = trimleft($customratings, 1);
   $listem = explode(',', $customratings);
   $n = sizeof($listem);
   for ($a=0; $a<$n; $a++)
   {
    if ($$listem[$a] != '')
    {
     $old = explode('[,]', $thislink->$listem[$a]);
     if ($old[0] == '') $old[0] = 0; 
     if ($old[1] == '') $old[1] = 0; 
     $votes = $old[0] + 1;
     if ($$listem[$a] > $settings->maxvote) $listem[$a] = $settings->maxvote;
     if ($$listem[$a] < $settings->minvote) $listem[$a] = $settings->minvote;
     $sumofvotes = $old[1] + $$listem[$a];
     $thislink->$listem[$a] = $votes .'[,]'. $sumofvotes;
     $thislink->update($listem[$a]);
    }
   }
   
   $aliases = $db->select($settings->linkfields, 'linkstable', 'alias='. $thislink->id, '', '');
   $n = $db->numrows($aliases);
   for ($x=0; $x<$n; $x++)
   {
    $alias = new onelink('row', $db->row($aliases));
    $alias->votes = $thislink->votes;
    $alias->sumofvotes = $thislink->sumofvotes;
    $alias->rating = $thislink->rating;
    $alias->voterips = $thislink->voterips;
    $alias->update('votes,sumofvotes,rating,voterips');
    // handle custom rating fields
    $customratings = trimleft($customratings, 1);
    $listem = explode(',', $customratings);
    $n = sizeof($listem);
    for ($a=0; $a<$n; $a++)
    {
     if ($$listem[$a] != '')
     {
      $old = explode('[,]', $thislink->$listem[$a]);
      if ($old[0] == '') $old[0] = 0; 
      if ($old[1] == '') $old[1] = 0; 
      $votes = $old[0] + 1;
      if ($$listem[$a] > $settings->maxvote) $listem[$a] = $settings->maxvote;
      if ($$listem[$a] < $settings->minvote) $listem[$a] = $settings->minvote;
      $sumofvotes = $old[1] + $$listem[$a];
      $alias->$listem[$a] = $votes .'[,]'. $sumofvotes;
      $alias->update($listem[$a]);
     }
    }
   }

   // set cookie to prevent re-voting on same link again
   $idstring = ' '. $HTTP_COOKIE_VARS[votes] .' '. $id .' ';
   setcookie("votes", $idstring, time()+1000000);

   // thank you and good night
   if (!$template) $template = new template('redirect.tpl');
   $template->replace('{MESSAGE}', $language->vote_thanks);
   if ($returnto == '') $returnto = 'index.php';
   $template->replace('{DESTINATION}', $returnto);
  }
  else
  {
   if (!$template) $template = new template('redirect.tpl');
   $template->replace('{MESSAGE}', $language->vote_cannotvote);
   $template->replace('{DESTINATION}', $returnto);
  }
 }
}
else
{
 //display prompt asking for vote
 if (!$template) $template = new template('vote.tpl');
 $thislink = new onelink('dummy', $id);
 $template->text = linkreplacements($template->text, $thislink);
 $ourcategory = new category('dummy', $thislink->catid);
 $template->replace('{NAVIGATION}', shownav($ourcategory));
}

include 'end.php';
?>