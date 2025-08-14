<?
/*
 * directories and files
 */
$spooldir="spool";
$imgdir="img";
$file_groups="groups.txt";
$file_groupaccess="grpaccess.txt";
$file_newsportal="newsportal.php";
$file_index="index.php";
$file_thread="thread.php";
$file_article="article.php";
//$file_article="article-flat.php";
//$file_article_full="article.php";
$file_rawarticle="article-raw.php";
$file_attachment="attachment.php";
$file_post="post.php";
$file_cancel="cancel.php";
$file_xface="xface.php?preview=1";
$file_language="lang/english.lang";
$file_footer="";


/* 
 * newsserver setup
 */
$server="news.newsportal.one.pl";
$port=119;
//$server_auth_user="";
//$server_auth_pass="";
//$post_server="";
//$post_port=119;
//$post_server_auth_user="";
//$post_server_auth_pass="";
$maxfetch=0; // depricated
$initialfetch=0;  // depricated
//$server_auth_http=true;

/*
 * grouplist-layout
 */
$gl_age=false; //show colors in index.php, but it works slow

/*
 * Thread layout
 */
$thread_treestyle=7;
$thread_show["date"]=true;
$thread_show["subject"]=true;
$thread_show["author"]=true;
$thread_show["authorlink"]=true;
$thread_show["replies"]=true;
$thread_show["lastdate"]=false; // makes only sense with $thread_show["replies"]=false
$thread_show["threadsize"]=false;
$thread_maxSubject=100;
$maxarticles=500;
$maxarticles_extra=100;
$age_count=3;
$age_time[1]=86400;  //24 hours
$age_color[1]="#f00";
$age_time[2]=259200; //3 days
$age_color[2]="#cc00e0";
$age_time[3]=604800; //7 days
$age_color[3]="#00b";
$thread_sort_order=-1;
$thread_sort_type="article";
$articles_per_page=100;
$startpage="first";

/* 
 * article layout 
 */
$article_show["Subject"]=false;
$article_show["From"]=true;
$article_show["From_link"]=true;
$article_show["From_rewrite"]=array('@','&#0064;'); //uses PCRE
$article_show["Newsgroups"]=true;
$article_show["Followup"]=true;
$article_show["Organization"]=true;
$article_show["Date"]=true;
$article_show["Message-ID"]=true;
$article_show["References"]=true;
$article_show["User-Agent"]=false;
$article_showthread=true;
$article_graphicquotes=true;

/*
 * settings for the article flat view, if used
 */
$articleflat_articles_per_page=10;
$articleflat_chars_per_articles=500;

/*
 * Message posting
 */
$send_poster_host=true;
$readonly=false;
$testgroup=true;
$validate_email=1;
$organization="TR Newsportal [http://www.newsportal.one.pl]";
$setcookies=true;
// $anonym_address="set_this@to_something_valid";
$msgid_generate="md5";
$msgid_fqdn=$_SERVER["HTTP_HOST"];
$post_autoquote=true;

/* 
 * Attachments
 */
$attachment_show=true;
$attachment_delete_alternative=true; // delete non-text mutipart/alternative
$attachment_uudecode=true;  // Decodes UUEncoded attachments

/*
 * Security settings
 */
$block_xnoarchive=false;
$rawview=true;

/*
 * Cache
 */
$cache_articles=false;  // article cache, experimental!
$cache_index=3600; // cache the group index for one hour before reloading
$cache_thread=60; // cache the thread for one minute reloading

/*
 * Misc 
 */
$title="TR Newsportal WebNewsReader";
$cutsignature=true;
$compress_spoolfiles=false;

  // website and posted articles charset, "koi8-r" for example
$www_charset="iso-8859-2";
  // Use the iconv extension for improved charset conversions
$iconv_enable=true;
  // timezone relative to GMT, +1 for CET
$timezone=+1;
  // experimental (you must have compface installed, see extras/xface/xface.php)
$show_xface=false;

/*
 * Group specific config
 * Put there only changed values
 */
//$group_config=array(
//  '^de\.alt\.fan\.aldi$' => "aldi.inc",
//  '^de\.' => "german.inc"
//);

/*
 * Do not edit anything below this line
 */
// Load group specifig config files
if((isset($group)) && (isset($group_config))) {
  foreach ($group_config as $key => $value) {
    if (ereg($key,$group)) {
      include $value;
      break;
    }
  }
}

// check the settings
include "lib/check.php";

// load the english language definitions first because some of the other
// definitions are incomplete
include("lang/english.lang"); 
include($file_language);
?>
