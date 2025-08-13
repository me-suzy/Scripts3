<?
function query ($catid,$artid,$nbr)
{	
   	global $skip;    
	global $lang;
	global $teller;
	global $la_read_more;
	    

	   include_once("admin/language/$lang");	    
   
            if ($catid >0)
            {
             $sql = "SELECT * FROM article_news, cat_news where cat_news.id = article_news.catid AND article_news.catid = $catid AND validated=1 AND status = 1 limit 1$nbr";
             
             $result	= mysql_query ($sql);
            }
            elseif ($artid >0)
            {
             $sql = "SELECT article_news.id,cat_news.catname,article_news.catid,title,intro,body,date FROM article_news, cat_news where cat_news.id = article_news.catid AND article_news.id = $artid AND validated=1 AND status=1";
             $result	= mysql_query ($sql);
            }

            while ($row = mysql_fetch_array($result))
             {
				 	$id = $row["id"];
					$catname =	$row["catname"];
					$title	= $row["title"];
					$catid = $row["catid"];
					$intro = $row["intro"];
					$body = $row["body"];
					$date =	$row["date"];
					$tid = $row["date"];
			 }

			$teller = $teller + 1;
			if ($teller <>1)
			{
				$intro = substr($intro,0,200);
			}
			else 
			{
				$intro = substr($intro,0,500);
			}
			?>
			<span class="articlebody"><b><? echo $title ?></b><br>
			<? echo $intro ?>...<br>

<?
			if ($skip) { $fname = "index.php"; $catfname = 
"index.php"; }
else
{
	$fname = "art.php"; $catfname = "cat.php";
}
?>
			<a href="<? echo $fname; ?>?artid=<? echo $id 
?>"><? echo $la_read_more ?></a>  
|
			<a href="<? echo $catfname; ?>?catid=<? echo 
$catid ?>&catname=<? echo $catname ?>"><? 
			echo $catname ?></a></SPAN>
			<p>
			<?
}
?>

