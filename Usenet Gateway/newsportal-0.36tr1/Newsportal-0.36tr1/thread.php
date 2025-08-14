<? @header("Expires: ".gmdate("D, d M Y H:i:s",time()+7200)." GMT");

  // register parameters
  $group=$_REQUEST["group"];
  if(isset($_REQUEST["first"]))
    $first=intval($_REQUEST["first"]);
  if(isset($_REQUEST["last"]))
    $last=intval($_REQUEST["last"]);

  include "config.inc.php";
  include "auth.inc";
  $title.= ' - '.$group;
  include "head.inc";
  include($file_newsportal);
?>

<h1 id="top" class="np_thread_headline"><?php echo $group; ?></h1>

<div class="np_buttonbar">
<? if (testGroupAccess($group)) 
  echo '<span class="np_button"><a class="np_button" href="'.
       $file_post.'?newsgroups='.urlencode($group).'&amp;type=new">'.
       $text_thread["button_write"]."</a></span>\n";
echo '<span class="np_button"><a class="np_button" href="'.
     $file_index.'">'.$text_thread["button_grouplist"]."</a></span>\n";
?>


<? 
//$ns=nntp_open($server,$port);
flush();
$headers = thread_load($group);
$article_count=count($headers);
if ($articles_per_page != 0) { 
  if ((!isset($first)) || (!isset($last))) {
    if ($startpage=="first") {
      $first=1;
      $last=$articles_per_page;
    } else {
      $first=$article_count - (($article_count -1) % $articles_per_page);
      $last=$article_count;
    }
  }
  echo '<span class="np_pages">';
  // Show the replies to an article in the thread view?
  if($thread_show["replies"]) {
    // yes, so the counting of the shown articles is very easy
    $pagecount=count($headers);
  } else {
    // oh no, the replies will not be shown, this makes life hard...
    $pagecount=0;
    foreach($headers as $h) {
      if($h->isAnswer==false)
        $pagecount++;
    }
  }

  thread_pageselect($group,$pagecount,$first);
  echo '</span>';
} else {
  $first=0;
  $last=$article_count;
}
echo "</div>\n";
thread_show($headers,$group,$first,$last);
//nntp_close($ns);
?>

<p align="right"><a href="#top"><? echo $text_thread["button_top"];?></a></p>

<? include "tail.inc"; ?>
