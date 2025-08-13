<? 
$req_level = 2;
include "inc_t.php";
print "<span class='articlebody'>";
print "<h3>$la_art $name ($email)</h3>";
$way = htmlspecialchars($way);
$order = htmlspecialchars($order);
$validate = htmlspecialchars($validate);
$topnews = htmlspecialchars($topnews);
$frontpage = htmlspecialchars($frontpage);
$delete = htmlspecialchars($delete);
$confirm = htmlspecialchars($confirm);

 
$urln = "&datef=$datef&statsf=$statsf&way=$way&order=$order";

print "$la_num_art_page: <a href='list.php?limit=10$urln'>10</a> | <a href='list.php?limit=20$urln'>20</a> | <a href='list.php?limit=30$urln'>30</a><br>";
print "$la_list_opt: <a href='list.php?$urln&datef=1&statsf=$statsf&limit=$limit'>$la_date</a> | <a href='list.php?$urln&datef=$datef&statsf=1&limit=$limit'>$la_stat</a>";
print "</span>";


print "<p><table border='0' cellpadding='2' width='100%' bgcolor='#E5E5E5' class='articlebody'>";
print "  <tr>";
print "    <td valign='top'>";
     
     if ($validate AND $level==1)
     {
        $sql = "update article_news set validated = 1 WHERE id = $validate";
        $result = mysql_query($sql);
        if ($result)
        {
        	print "$la_art_approved<br>";
        }

     }
     
     if ($topnews AND $level==1)
     {
        $sql = "update article_news set  topnews = $pri WHERE id = $topnews";
        $result = mysql_query($sql);
        if ($result)
        {
        	print "$la_art_upg<br>";
        }

     }
     
      if ($frontpage AND $level==1 AND $add)
     {
        $sql = "update article_news set frontpage = 1 WHERE id = $frontpage";
        $result = mysql_query($sql);
        if ($result)
        {
        	print "$la_art_upg_fp<br>";
        }

     }
     
     if ($frontpage AND $level==1 AND $remove)
     {
        $sql = "update article_news set frontpage = 0 WHERE id = $frontpage";
        $result = mysql_query($sql);
        if ($result)
        {
        	print "$la_art_dwn_fp<br>";
        }

     }

     if ($delete)
     {
        
        if ($delete AND !$confirm)
        {
        	print "$la_sure <b><a href=\"?delete=$delete&confirm=1\">$la_y</a></b> | <a href=\"list.php\">$la_n</a><br>";
        }
        // Superuser want to delete news
        if ($delete AND $confirm AND $level == 1)
        {
        	$sql = "delete from article_news WHERE id = $delete";
        	$result = mysql_query($sql);
        	
        	if ($result)
        	{
        		print "$la_delete_conf<br>";
        	}
       	}
       	// Normal user want to delete news
       	elseif ($delete AND $confirm AND $level <> 1)
       	{
       		$sql = "delete from article_news WHERE id = $delete AND authorid=$userid";
        	$result = mysql_query($sql);
       		
        	if ($result)
       		{
        		print "$la_delete_conf<br>";
       		}
       	}
       	

     }
    

print "    </td>";
print "  </tr>";
print "</table><br>";

if ($way == "desc")
{
	$way = "asc";	
}
else 
{
	$way = "desc";
}


print "<table border='1' cellpadding='2' width='100%' bgcolor='#E5E5E5' bordercolorlight='#FFFFFF' bordercolordark='#E5E5E5' class='articlebody'>\n";
print "<tr>\n";
// Kolonne 1
print "<td valign='top' bgcolor='#C0C0C0'><b>$la_article <a href='list.php?order=title&way=$way&datef=$datef&statsf=$statsf&limit=$limit'>(Sort)</a></b></td>\n";
// Kolonne 2
if ($datef) {
print "<td valign='top' bgcolor='#C0C0C0'><b>$la_first_pub <a href='list.php?order=date&way=$way&datef=$datef&statsf=$statsf&limit=$limit'>(Sort)</a></b></td>\n"; }
// Kolonne 3
print "<td valign=top bgcolor=#C0C0C0><b>$la_cat <a href='list.php?order=catname&way=$way&datef=$datef&statsf=$statsf&limit=$limit'>(Sort)</a></b></td>\n";
if ($author)  { 
	print "<td valign='top' bgcolor='#C0C0C0'><b>author_news <a href='list.php?order=fname&way=$way&datef=$datef&statsf=$statsf&limit=$limit'>(Sort)</a></b></td>\n"; }
if ($statsf) { 
	print "<td valign='top' bgcolor='#C0C0C0'><b>$la_list_feedb</b></td>\n"; }
print "<td valign='top' bgcolor='#C0C0C0'><b>$la_list_change</b></td>\n";
print "</tr>\n";
  
  	
	// If no order is spesificed
	if (!$order)
    {
        	$order = "article_news.id";
    }
    
    if (!$offset) 
    {
    		$offset = 0;	
    }
    
	if (!$limit)
	{
    	$limit = 15;
    	$n = $limit;
	}
    $n = $limit;
     
	// If superadmin
    if ($level == 1)
	{  
		$numresults = mysql_query("SELECT article_news.grade, article_news.topnews, article_news.votes, article_news.frontpage, article_news.id,article_news.catid,article_news.title,article_news.date,article_news.authorid,article_news.intro,article_news.body,article_news.validated,article_news.date,cat_news.catname,author_news.fname,author_news.lname FROM article_news,cat_news,author_news where article_news.authorid=author_news.userid AND article_news.catid=cat_news.id");
        $numrows=mysql_num_rows($numresults);
		
        $sql = "SELECT article_news.grade, article_news.topnews, article_news.votes, article_news.frontpage, article_news.id,article_news.catid,article_news.title,article_news.date,article_news.authorid,article_news.intro,article_news.body,article_news.validated,article_news.date,cat_news.catname,author_news.fname,author_news.lname FROM article_news,cat_news,author_news where article_news.authorid=author_news.userid AND article_news.catid=cat_news.id order by $order $way limit $offset,$limit";
	}
	// Everyone else..
	else
	{
		$numresults = mysql_query("SELECT article_news.grade, article_news.topnews, article_news.votes, article_news.frontpage, article_news.id,article_news.catid,article_news.title,article_news.date,article_news.authorid,article_news.intro,article_news.body,article_news.validated,article_news.date,cat_news.catname,author_news.fname,author_news.lname FROM article_news,cat_news,author_news where article_news.authorid=author_news.userid AND author_news.userid=$userid AND article_news.catid=cat_news.id");
        $numrows=mysql_num_rows($numresults);
		$sql = "SELECT article_news.grade, article_news.topnews, article_news.votes, article_news.frontpage, article_news.id,article_news.catid,article_news.title,article_news.date,article_news.authorid,article_news.intro,article_news.body,article_news.validated,article_news.date,cat_news.catname,author_news.fname,author_news.lname FROM article_news,cat_news,author_news where article_news.authorid=author_news.userid AND author_news.userid=$userid AND article_news.catid=cat_news.id order by $order $way limit $offset,$limit";
		   
	}

		$result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $id = $myrow["id"];
              $catid = $myrow["catid"];
              $title = $myrow["title"];
              $authorid = $myrow["authorid"];
              $intro = $myrow["intro"];
              $body = $myrow["body"];
              $status = $myrow["status"];
              $validated = $myrow["validated"];
              $grade = $myrow["grade"];
              $votes = $myrow["votes"];
              $frontpage = $myrow["frontpage"];
              $topnews = $myrow["topnews"];
              $year=substr($myrow[date],0,4);
	      	  $month=substr($myrow[date],4,2);
	      	  $day=substr($myrow[date],6,2);
	          $tid = "$day.$month.$year";
              
              $catname = $myrow["catname"];
              $fname = $myrow["fname"];
              $lname = $myrow["lname"];
              
              $title = substr($title,0,20);
              
              if ($votes)
              {
              	$grade = round($grade / $votes,1);
              }
              
              if ($skip)
              {
              	$fname = "index.php";
              	$catfname = "index.php";
              }
              else 
              {
              	$fname = "art.php";
              	$catfname = "cat.php";
              }
              
              print "<tr>";
              print "<td valign=\"top\"><a href=\"../$fname?artid=$id\" target=\"new\">$title</a></td>";
			  if ($datef) { print "<td valign=\"top\">$tid</td>"; }
			  print "<td valign=\"top\"><a href=\"../$catfname?catid=$catid&catname=$catname\" target=\"new\">$catname</a></td>";
			  if ($author) { print "<td valign=\"top\">$fname $lname</td>"; }
			  if ($statsf) { print "<td valign=\"top\">$grade (# $votes)</td>"; }
              print "<td valign=\"top\"><small><a href=\"editor.php?id=$id\">$la_list_change</a>";
              print " | <a href=\"editor_html.php?id=$id\">$la_list_change html</a>";
              print " | <a href=\"list.php?detail=$id\">$la_details</a>";
              print " | <a href=\"list.php?delete=$id\">$la_del</a>";
  			  if ($level==1) 
  			  { 
  			  	print " | ";
  			  	
  			  	if ($topnews == 1)
  			  	{
  			  		print "<b>1</b> <a href=\"list.php?topnews=$id&pri=2\">2</a>  <a href=\"list.php?topnews=$id&pri=3\">3</a>  <a href=\"list.php?topnews=$id&pri=0\">0</a>"; 
  			  	
  			  	}
  			  	
  			  	if ($topnews == 2)
  			  	{
  			  		print "<a href=\"list.php?topnews=$id&pri=1\">1</a>  <b>2</b> | <a href=\"list.php?topnews=$id&pri=3\">3</a>  <a href=\"list.php?topnews=$id&pri=0\">0</a>"; 
  			  	
  			  	}
  			  	
  			  	if ($topnews == 3)
  			  	{
  			  		print "<a href=\"list.php?topnews=$id&pri=1\">1</a>  <a href=\"list.php?topnews=$id&pri=2\">2</a>  <b>3</b>  <a href=\"list.php?topnews=$id&pri=0\">0</a>"; 
  			  	
  			  	}
  			  	if (!$topnews)
  			  	{
  			  		print "<a href=\"list.php?topnews=$id&pri=1\">1</a>  <a href=\"list.php?topnews=$id&pri=2\">2</a>  <a href=\"list.php?topnews=$id&pri=3\">3</a>  <a href=\"list.php?topnews=$id&pri=0\">0</a>"; 
  			  	
  			  	}
  			  	
  			  	if (!$frontpage)
  			  	{
  			  		print " | <a href=\"list.php?frontpage=$id&add=1\">$la_fp</a>"; 
  			  	
  			  	}
  			  	else 
  			  	{
  			  		print " | <a href=\"list.php?frontpage=$id&remove=1\">$la_nfp</a>"; 	
  			  	}
  			  	
  			  		
  			  	if (!$validated)
	            {
	            	print " | <a href=\"list.php?validate=$id\">$la_approve</a>";
	            }
  			  }

              print "</small></td></tr>";
      }




   ?>
</table>
<br>

<?

if ($way == "asc")
{
	$way = "desc";	
}
else 
{
	$way = "asc";
}

$URLNEXT = "&order=$order&way=$way&limit=$limit&datef=$datef&statsf=$statsf";

print "<table border='1' cellpadding='2' width='100%' bgcolor='#E5E5E5' bordercolordark='#E5E5E5' class='articleBody'>\n";
print "<tr><td><b>$la_respage</b> ($n $la_perpage): &nbsp;&nbsp;";
if ($offset==1) { // bypass PREV link if offset is 0
    $prevoffset=$offset-$n;
    print "<a href=\"$PHP_SELF?offset=$prevoffset$URLNEXT\">$la_prev</a> \n";
}

// calculate number of pages needing links
$pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division
if ($numrows%$limit) {
    // has remainder so add one page
    $pages++;
}

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"$PHP_SELF?offset=$newoffset$URLNEXT\"><u>$i</u></a> \n";
}

// check to see if last page
if (!(($offset/$limit)==$pages) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"$PHP_SELF?offset=$newoffset$URLNEXT\"><u>$la_next</u></a>\n";
}
print "</td></tr></table>"; 

?>
 
<p>

<?
if ($detail)
{
        $sql = "SELECT * FROM article_news,author_news where author_news.userid = article_news.authorid AND id = '$detail'";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $id = $myrow["id"];
              $catid = $myrow["catid"];
              $title = $myrow["title"];
              $date = $myrow["date"];
              $date_changed = $myrow["date_changed"];
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
              
                      
            $year=substr($myrow[date],0,4);
	      	$month=substr($myrow[date],4,2);
	        $day=substr($myrow[date],6,2);
            $tid = "$day.$month.$year";
           
            $year2=substr($myrow[date_changed],0,4);
	      	$month2=substr($myrow[date_changed],4,2);
	        $day2=substr($myrow[date_changed],6,2);
            $tid2 = "$day2.$month2.$year2";

      }

?>

<p>

<table border="1" cellpadding="2" width="100%" bgcolor="#E5E5E5" bordercolorlight="#FFFFFF" bordercolordark="#E5E5E5">
  <tr>
    <td valign="top" bgcolor="#C0C0C0"><b><font face="Arial" size="2"><? print "$la_stats_header $title"; ?></b></td>
  </tr>
  <tr>
    <td valign="top">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td><font face="Arial" size="2"><? echo $la_author; ?></td>
          <td align="right"><font face="Arial" size="2"><a href="users.php?userid=<? echo $userid ?>"><? echo $fname ?> <? echo $lname ?></a></td>
        </tr>
        <tr>
          <td><font face="Arial" size="2"><? echo $la_art_shown; ?></td>
          <td align="right"><font face="Arial" size="2"><? echo $count ?></td>
        </tr>
        <tr>
          <td><font face="Arial" size="2"><? echo $la_art_grade; ?></td>
          <td align="right"><font face="Arial" size="2"><? echo $grade ?></td>
        </tr>
        <tr>
          <td><font face="Arial" size="2"><? echo $la_art_voters; ?></td>
          <td align="right"><font face="Arial" size="2"><? echo $votes ?></td>
        </tr>
        <tr>
          <td><font face="Arial" size="2"><? echo $la_art_created; ?></td>
          <td align="right"><font face="Arial" size="2"><? echo $tid ?></td>
        </tr>
        <tr>
          <td><font face="Arial" size="2"><? echo $la_art_changed; ?></td>
          <td align="right"><font face="Arial" size="2"><? echo $tid2 ?></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?
 }
?>

<p>&nbsp;</p>

</body>

<? include "inc_b.php"; ?>
