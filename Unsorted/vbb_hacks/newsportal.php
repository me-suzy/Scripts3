<?
// Simple Newsportal & Threads overview hack
// Copyright by KuraFire, 2002 :)
// With extra credit to PPN for the permissions section

$templatesused = 'newsportal,newsportal_threadbit,newsportal_newsbit';
include('./global.php');

// These variables define your newsforum, limit of newsposts, 
// limit of threads, and forums to exclude in the threadlisting
$newsforum = 5;
$newslimit = 6;
$threadslimit = 70;


/* ### NEWS SECTION ### */
$newsql = $DB_site->query("SELECT thread.threadid as tid, thread.title as ttitle, thread.replycount as treply, thread.postusername as user, thread.postuserid as userid, thread.dateline as dateline, post.pagetext as pagetext FROM thread LEFT JOIN post USING (threadid) WHERE forumid=$newsforum GROUP BY thread.threadid ORDER BY thread.threadid DESC LIMIT $newslimit");

if (!$newsql) {
  // use this only to check whether it works on your board, 
  // after all tests turn successful you can remove it if you want (remove entire IF-statement of course)
  echo "<p>Could not get news records.";
  exit;
}

while ($news=$DB_site->fetch_array($newsql)) {
	$userid=$news['userid'];
	$pagetext=$news['pagetext'];
	$message=bbcodeparse2($pagetext,"1","1","1","1");
	$user=$news['user'];
	$threadid=$news['tid'];
	$title=$news['ttitle'];
	$replycount=$news['treply'];
	$dateline=$news['dateline'];
	$dateposted = date("M.d, Y - g:i A",$dateline);
	if ($replycount == 1){
		$replies = $replycount . " reply";
	} else {
		$replies = $replycount . " replies";
	}

	eval("\$newsbits .= \"".gettemplate('newsportal_newsbit')."\";");
}



/* ### THREADS SECTION ### */

// Permissions section by PPN
//perms

$doperms=$DB_site->query("SELECT canview,forumid FROM forumpermission WHERE usergroupid='$bbuserinfo[usergroupid]'");
while ($doperm = $DB_site->fetch_array($doperms)) {
   $perms["$doperm[forumid]"] = $doperm;
}

$DB_site->free_result($doperms);
unset($doperm);

$forum=$DB_site->query("SELECT forumid FROM forum");
while ($forums=$DB_site->fetch_array($forum)) {
   if($perms["$forums[forumid]"]["canview"] == 1 || !isset($perms["$forums[forumid]"]["canview"])) {
       $forumperms[]=$forums["forumid"];
   }
}

$DB_site->free_result($forum);
unset($forums);

if(!empty($forumperms)) {
  $forumperms='AND forumid='.implode(' OR forumid=',$forumperms);
}

// END permissions section (PPN)

$threadsql = $DB_site->query("SELECT lastposter, threadid, title, replycount FROM thread WHERE open='1' AND open<>10 $forumperms ORDER BY lastpost DESC LIMIT $threadslimit");

if (!$threadsql) {
  // use this only to check whether it works on your board, 
  // after all tests turn successful you can remove it if you want (remove entire IF-statement of course)
  echo "<p>Could not get thread records.";
  exit;
}

while ($thread=$DB_site->fetch_array($threadsql)) {
	$lastposter=$thread['lastposter'];
	$threadid=$thread['threadid'];
	$title=$thread['title'];
	$replycount=$thread['replycount'];
	
	$fulltitle = $title;
	if (strlen($title) > 50) {
		    $title = substr($title, 0, 50);
			$title .= '...';
	}

	eval("\$threadbits .= \"".gettemplate('newsportal_threadbit')."\";");
}

eval("\$news = \"".gettemplate('newsportal_news')."\";");

eval("dooutput(\"".gettemplate("newsportal")."\");");

?>