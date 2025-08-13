<? if (!$skip) { include "header.php"; } ?>


<?
include ("admin/db.php");

if ($catid)
{
	$sql = "SELECT article_news.id,article_news.catid,article_news.title,article_news.date,article_news.authorid,article_news.intro,article_news.body,article_news.date,cat_news.catname FROM article_news,cat_news,author_news where article_news.authorid=author_news.userid AND article_news.catid=cat_news.id AND article_news.catid=$catid order by article_news.catid desc";
}	
else
{
	$sql = "SELECT * FROM cat_news order by id desc";
}
$result = mysql_query($sql);
$num_res = mysql_num_rows($result);

print "<span class='articlebody'>";
print "<A href='cat.php'><*></a> / <A href='cat.php?catid=$catid&catname=$catname'>$catname</a><p>";
$catname_large = strtoupper($catname);
print "<h1>$catname_large</h1>";
print "<ul type='square'>";

if ($catid)
{
	for ($i=0; $i<$num_res; $i++)
	{
              $myrow = mysql_fetch_array($result);
              $idnb = $myrow["id"];
              $catid = $myrow["catid"];
              $title = $myrow["title"];
              $date = $myrow["date"];
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
              $catname = $myrow["catname"];
			  $year=substr($myrow[date],0,4);
	      	  $month=substr($myrow[date],4,2);
	          $day=substr($myrow[date],6,2);
              $date = "$day.$month.$year";
              
              
	              
              if ($skip) { $filename_skip = "index.php"; }
              else  { $filename_skip = "art.php"; }
              
              $fname = "cat.php";
              
              	print "<!-- TEMPLATE FILE -->";
				$filename = "admin/templates/cat.html";
				$fd = fopen ($filename, "r");
				$file= fread ($fd, filesize ($filename));
				$file = str_replace("{FILENAME}", "$filename_skip", $file);
				$file = str_replace("{ID}", "$idnb", $file);
				$file = str_replace("{TITLE}", "$title", $file);
				$file = str_replace("{DATE}", "$date", $file);
				$file = str_replace("{INTRO}", "$intro", $file);
				echo $file;
				print "<!-- // TEMPLATE FILE -->";
  
	}
	print "</ul>";


	if ($num_res < 1)
	{
		print "Ingen nyheter publisert eller godkjent per dags dato.";
	}
}

// If no categoryid
else
{
		for ($i=0; $i<$num_res; $i++)
	{
              $myrow = mysql_fetch_array($result);
              $idnb = $myrow["id"];
              $catname = $myrow["catname"];
         
              
    		  $sql_art = "SELECT * FROM article_news where catid = $idnb AND validated = 1";
			  $result_art = mysql_query($sql_art);
			  $num_art = mysql_num_rows($result_art);
				
			  if ($skip) { $filename = "index.php"; }
              else  { $filename = "cat.php"; }
              
              print " <a href='$filename?catid=$idnb&catname=$catname'>$catname</a> ($num_art)<br>";
     }
}

print "</span>";

if ($skip) { include ("webber_footer.php"); };
?>

 
<? if (!$skip) { include "footer.php"; } ?>