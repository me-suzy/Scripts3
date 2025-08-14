<?php

$faqimage = "images/pinkfacesmall.gif";

include ( "dbconnect.php" );
include ( "header.php" );

// If no category is set, pick the first from whatdafaqcat

if ( !ISSET ( $faqcategory ) ) {
   $query = mysql_query ( "SELECT id, category FROM whatdafaqcat LIMIT 1");
   if ( $result = mysql_fetch_array ( $query ) ) {
   	  $catid = $result["id"];
   	  $category = $result["category"];
	  $faqcategory = $catid;
	  $catname = $category;
   }
}
?>

<table width="85%" border=0 cellpadding=3 align=center>
	<tr><form action="<?=$PHP_SELF?>" method=post>
   	    <td align=right><span class="small"><b>FAQ Category:</b> <select class="small" name="faqcategory" onChange="document.location.href='<?=$PHP_SELF?>?faqcategory=' + this.options[selectedIndex].value">
<?
// Display the drop list for swapping between categories
// Remove this if you don't want it   	   
$query = mysql_query ( "SELECT id, category FROM whatdafaqcat");
while ( $result = mysql_fetch_array ( $query ) ) {
 $category = $result ["category"];
 $catid = $result ["id"]; 
 if ( $catid == $faqcategory ) {
 	echo ( "<OPTION SELECTED value='$catid'>$category");
	$catname = $category;
	$faqcategory = $catid;	
 } else {
 	echo ( "<OPTION value='$catid'>$category");								 
 }
}
?>					
</select></span></td></form>
	</tr>
	<tr>
	 <td align="right">
	  <form action="<?=$PHP_SELF?>" method="post">
	  <table>
	   <tr>
	    <td class="small"><b>Search:</b></td>
		<td><input type="text" name="searchtext" size="10" maxlength="200" class="small"></td>
	   </tr>
	  </table>
	  <input type="hidden" name="faqcategory" value="<?=$faqcategory?>">
	  </form>
	 </td>
	</tr>
	<tr>
	 <td>
	  <table width="100%" align="center">
<?

// If we're looking at an invidual faq, do this;
if ( ISSET ( $faqid ) ) {
   $sql = mysql_query ( "SELECT * FROM whatdafaq WHERE publish = 'yes' AND id = '$faqid'" );
   if ( $result = mysql_fetch_array ( $sql ) ) {
   	  $question = $result ["question"];
	  $answer = $result ["answer"];
	  echo ( "<tr><td class=\"small\" align=\"right\" colspan=\"2\"><a href=$PHP_SELF?faqcategory=$faqcategory>Return to Contents</a></td></tr>" );
  	  echo ( "<tr valign=\"top\"><td width=\"10%\"><img src=\"" . $faqimage . "\"></td><td><b>$question</b></td></tr>" );
  	  echo ( "<tr valign=\"top\"><td width=\"10%\">&nbsp;</td><td align=\"justify\">$answer</td></tr>" );	  
	  echo ( "<tr><td class=\"small\" align=\"right\" colspan=\"2\"><a href=$PHP_SELF?faqcategory=$faqcategory>Return to Contents</a></td></tr>" );
   } else {
   	  echo ( "<tr><td align=center>Error fetching document: " . mysql_error () . "</p>" );
   }
} else if ( ISSET ( $searchtext ) ) {
   $sql = mysql_query ( "SELECT * FROM whatdafaq WHERE publish = 'yes' AND category = '$faqcategory' AND answer LIKE '%$searchtext%' ORDER BY ordering" );
   $count = mysql_numrows ( $sql ); 
   if ( $count < 1 ) {
	  echo ( "<tr><td class=\"small\" align=\"right\" colspan=\"2\"><a href=$PHP_SELF?faqcategory=$faqcategory>Return to Contents</a></td></tr>" );
  	  echo ( "<tr valign=\"top\"><td colspan=\"2\" align=\"center\">Sorry: there are no FAQs matching your search.</td></tr>" );
	  echo ( "<tr><td class=\"small\" align=\"right\" colspan=\"2\"><a href=$PHP_SELF?faqcategory=$faqcategory>Return to Contents</a></td></tr>" );   
   } else {
   	  echo ( "<tr><td class=\"small\" align=\"right\" colspan=\"2\"><a href=$PHP_SELF?faqcategory=$faqcategory>Return to Contents</a></td></tr>" );
	  echo ( "<tr valign=\"top\"><td colspan=\"2\"><img src=\"" . $faqimage . "\">&nbsp;Your search returned <b>" . $count . "</b> results.</td></tr>" );
   	  while ( $result = mysql_fetch_array ( $sql ) ) {
	  	 $faqid = $result["id"];
	  	 $question = $result["question"];		 
	     echo ( "<tr valign=\"top\"><td align=right><a href=$PHP_SELF?faqcategory=$faqcategory&faqid=$faqid class=\"small\">$question</a></td></tr>" );
	  }
	  echo ( "<tr valign=\"top\"><td colspan=\"2\">&nbsp</td></tr>" );	  
	  echo ( "<tr><td class=\"small\" align=\"right\" colspan=\"2\"><a href=$PHP_SELF?faqcategory=$faqcategory>Return to Contents</a></td></tr>" );
   }   	     	  
} else {

// Otherwise display the contents for this faq category
$sql = mysql_query ( "SELECT * FROM whatdafaq WHERE publish = 'yes' AND category = '$faqcategory' ORDER BY ordering" );
while ( $result = mysql_fetch_array ( $sql ) ) {
	  	 $faqid = $result ["id"];
	     $question = ( $result ["question"] );
	     $answer = $result ["answer"];
	     $section = $result ["section"];		 		 
		 if ( $section == "yes" ) {
		 	echo ( "<tr valign=\"top\"><td><img src=\"" . $faqimage . "\">&nbsp;<b>$question</b></td></tr>" );		 
		 } else {
		 	echo ( "<tr valign=\"top\"><td align=right><a href=$PHP_SELF?faqcategory=$faqcategory&faqid=$faqid class=\"small\">$question</a></td></tr>" );		 
		 }
}
}
?>
	  </table>
	 </td>
	</tr>	 
</table>
<?
// Include your footer here e.g.
include ( "footer.php" );
?>