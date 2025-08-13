<table border="0" cellpadding="10" cellspacing="0">
<tr valign="top">
		<td align="right">
			
			<!-- LATEST ARTICLES  -->
			<table width="160" border="0" cellpadding="2" cellspacing="1" bgcolor="#000000">
			<tr>
				<td bgcolor="#C6C6F7" align="center">New!<br><img src="spacer.gif" border=0 width=1 height=1><br></td>
			</tr>
				
			<tr>
				<td bgcolor="#F3F3FF">
					<?
					$result = mysql_query ("SELECT id,title FROM article_news where validated=1 order by article_news.id desc limit 9");
					
					while ($row = mysql_fetch_array($result)) 
					{
						$catid = $row["catid"];
						$id = $row["id"];
						$title = $row["title"];
					
						$count = $count +1 ;
						
						$t = substr($title,0,20);
						print("<font color='#000000' size='1' face='Arial'>$count. <a href='art.php?artid=$id'>$t</a></font><br>");
					}
					
					?>			
				</td>
			</tr>
			</table>
			<!-- // LATEST ARTICLES -->
			
			
		</td>
</tr>
<tr>
    	<td align="right">
			
    		<!-- MOST VIEWED ARTICLES -->
    		<table width="160" border="0" cellpadding="2" cellspacing="1" bgcolor="#000000">
			<tr>
				<td bgcolor="#C6C6F7" align="center">HOT<br><img src="spacer.gif" border=0 width=1 height=1><br></td>
			</tr>
			
			<tr>
				<td bgcolor="#F3F3FF">
					<?
					$count = 0;
					
					$result = mysql_query ("SELECT id,title FROM article_news where validated=1 order by article_news.count desc limit 9");
					while ($row = mysql_fetch_array($result)) 
					
					{
						$catid = $row["catid"];
						$id = $row["id"];
						$title = $row["title"];
					
						$count = $count +1 ;
						
						$t = substr($title,0,20);
					
					
					 print("<font color='#000000' size='1' face='Arial'>$count. <a href='art.php?artid=$id'>$t</a></font><br>");
					}
					?>
				</td>
			</tr>
			</table>
			<!-- // MOST VIEWED ARTICLES -->
	</td>
</tr>
</table>