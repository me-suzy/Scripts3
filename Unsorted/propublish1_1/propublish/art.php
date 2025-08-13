<? if (!$skip) { include "header.php"; } ?>

<?
include ("admin/db.php");
include ("admin/set_inc.php");
include ("admin/language/$lang");
	
$sql = "SELECT * FROM article_news,author_news where author_news.userid = article_news.authorid AND id = '$artid'";
		
$result = mysql_query($sql);
$num_res = mysql_num_rows($result);

for ($i=0; $i<$num_res; $i++)
{
    $myrow = mysql_fetch_array($result);
    $id = $myrow["id"];
    $catid = $myrow["catid"];
    $title = $myrow["title"];
    $date = $myrow["date"];
    $date_changed = $myrow["date"];
    $authorid = $myrow["authorid"];
    $intro = $myrow["intro"];
    $body = $myrow["body"];
    $status = $myrow["status"];
    $count = $myrow["count"];
    $votes = $myrow["votes"];
    $grade = $myrow["grade"];
    $fname = $myrow["fname"];
    $lname = $myrow["lname"];
    $userid = $myrow["userid"];
    $email = $myrow["email"];
    $show_html = $myrow["show_html"];
      
    $year=substr($myrow[date],0,4);
  	$month=substr($myrow[date],4,2);
    $day=substr($myrow[date],6,2);
    $tid1 = "$day.$month.$year";
   
    $year2=substr($myrow[date_changed],0,4);
  	$month2=substr($myrow[date_changed],4,2);
    $day2=substr($myrow[date_changed],6,2);
    $tid2 = "$day2.$month2.$year2";

    
               
    $code_table_start = "<table class=\"code\" borderColorDark=\"#000000\" 
cellPadding=\"2\" width=\"100%\" bgColor=\"#e4e4fc\" borderColorLight=\"#ffffff\" 
border=\"1\"><tr><td width=\"100%\">";
	$code_table_end = "</td></tr></table>";

	// Syntax
	// $body = str_replcace("LOOK FOR","REPLACE_WITH_THIS",$body);
	// By default, only html/php code activated here is allowed
	// Next 2 lines disables all php and html code
	

	if ($show_html)
	{
		
	    $body = str_replace("<", "&lt;", $body);
		$body = str_replace(">", "&gt;", $body);
	}	
		// Code tags
		$body = str_replace("&lt;code&gt;", "$code_table_start", $body);
		$body = str_replace("&lt;/code&gt;", "$code_table_end", $body);
			
		$body = str_replace("[code]", "$code_table_start", $body);
		$body = str_replace("[/code]", "$code_table_end", $body);
		
	if ($show_html)
	{	
		// Convert img to visible images html code
		$body = str_replace("<img", "&lt;img", $body);
	}	
		
		// Image tags
		$body = str_replace("&lt;image&gt;", "<img src='get.php?id=", $body);
		$body = str_replace("&lt;/image&gt;", "'>", $body);
		
		$body = str_replace("&lt;image_left&gt;", "<img align='left' src='get.php?id=", $body);
		$body = str_replace("&lt;/image&gt;", "'>", $body);
		
		$body = str_replace("&lt;image_right&gt;", "<img align='right' src='get.php?id=", $body);
		$body = str_replace("&lt;/image&gt;", "'>", $body);
		
	
	if ($show_html)
	{
		$body = str_replace("[img]", "<img align='left' src='", $body);
		$body = str_replace("[/img]", "'>", $body);
	}	
		
		// Ingress
		$body = str_replace("[i]", "<i>", $body);
		$body = str_replace("[/i]", "</i>", $body);
		
		// Bold tags
		$body = str_replace("[b]", "<b>", $body);
		$body = str_replace("[/b]", "</b>", $body);
		
		// Underline
		$body = str_replace("[u]", "<u>", $body);
		$body = str_replace("[/u]", "</u>", $body);
		
		// Font size
		$body = str_replace("[h1]", "<h1>", $body);
		$body = str_replace("[/h1]", "</h1>", $body);
		$body = str_replace("[h2]", "<h2>", $body);
		$body = str_replace("[/h2]", "</h2>", $body);
		$body = str_replace("[h3]", "<h3>", $body);
		$body = str_replace("[/h3]", "</h3>", $body);
		$body = str_replace("[h4]", "<h4>", $body);
		$body = str_replace("[/h4]", "</h4>", $body);
		$body = str_replace("[h5]", "<h5>", $body);
		$body = str_replace("[/h5]", "</h5>", $body);
		$body = str_replace("[small]", "<small>", $body);
		$body = str_replace("[/small]", "</small>", $body);
		
		
		// Blockquote
		$body = str_replace("[bq]", "<blockquote>", $body);
		$body = str_replace("[/bq]", "</blockquote>", $body);
		
	if ($show_html)
	{
		// Convert a tags to visiible a href html code
		$body = str_replace("<a", "&lt;a", $body);
		$body = str_replace("</a>", "&lt;/a&gt;", $body);
		$body = str_replace("\"&gt;", "\">", $body);
	}	
		$body = str_replace("[url]", "<a href='", $body);
		$body = str_replace("[/url]", "'>Link</a>", $body);
		
		// List tags
		$body = str_replace("[ol]", "<ol>", $body);
		$body = str_replace("[/ol]", "</ol>", $body);
		$body = str_replace("[li]", "<li>", $body);
		$body = str_replace("[/li]", "</li>", $body);
		$body = str_replace("[ul]", "<ul type=square>", $body);
		$body = str_replace("[/ul]", "</ul>", $body);
		
	           
	    $body = str_replace("&lt;br /&gt;", "<br>", $body);
		
	    $intro = nl2br($intro);
	
	      
	}

	$sql = "SELECT * FROM cat_news where id = $catid";
	$result = mysql_query($sql);
    $num_res = mysql_num_rows($result);

    for ($i=0; $i<$num_res; $i++)
    {
          $myrow = mysql_fetch_array($result);
          $catname = $myrow["catname"];
          $id = $myrow["id"];
	}

	print "<!-- TEMPLATE FILE -->";
	$filename = "admin/templates/art.html";
	$fd = fopen ($filename, "r");
	$file= fread ($fd, filesize ($filename));
	$file = str_replace("{ID}", "$id", $file);
	$file = str_replace("{CATNAME}", "$catname", $file);
	$file = str_replace("{TITLE}", "$title", $file);
	$file = str_replace("{EMAIL}", "$email", $file);
	$file = str_replace("{FNAME}", "$fname", $file);
	$file = str_replace("{LNAME}", "$lname", $file);
	$file = str_replace("{DATE_CHANGED}", "$date_changed", $file);
	$file = str_replace("{ARTID}", "$artid", $file);
	$file = str_replace("{INTRO}", "$intro", $file);
	$file = str_replace("{TID1}", "$tid1", $file);
	$file = str_replace("{TID2}", "$tid2", $file);
	$file = str_replace("{BODY}", "$body", $file);
	$file = str_replace("{LA_BY}", "$la_by", $file);
	$file = str_replace("{LA_CHANGED}", "$la_changed", $file);
	$file = str_replace("{LA_NEWS}", "$la_news", $file);
	$file = str_replace("{LA_PRINT}", "$la_print", $file);
	$file = str_replace("{LA_RATE_THIS}", "$la_rate_this", $file);
	$file = str_replace("{LA_TELLAFRIEND}", "$la_tellafriend", $file);
	echo $file;

	print "<!-- // TEMPLATE FILE -->";
	$sql = "UPDATE article_news set count = count+1 where id = $artid;";
	$result = mysql_query($sql);

?>

<? if (!$skip) { include "footer.php"; } ?>