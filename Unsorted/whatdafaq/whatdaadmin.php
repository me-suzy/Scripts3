<?php
include("dbconnect.php");
include("auth.php");
include("header.php");

// Adds slashes if magic_quotes_gpc is disabled.
function myAddSlashes($string) {
  if (get_magic_quotes_gpc()==1) {
    return ( $string );
  } else {
    return ( addslashes ( $string ) );
  }
}

// Moves everything in the ordering down by one to makes way for the new / edited faq article
function shiftorder ($start) {
	 if ( $start != 1 ) {
	 	$start++;
	 }
  	 $pullorders = mysql_query ( "SELECT id FROM whatdafaq WHERE ordering >= '$start' ORDER BY ordering" );
	 $count = $start + 1;
	 while ( $pullorder = mysql_fetch_array ( $pullorders ) ) {
	       $id = $pullorder["id"];
		   $changepos = mysql_query ( "UPDATE whatdafaq SET ordering = '$count' WHERE ID = '$id'" );
		   if ( !$changepos ) {
  	 	   	  echo ( "<tr><td align=\"centre\">Error adding to position: " . mysql_error () . "</td></tr>" );
		   }
		   $count++;
	 }
}

// Makes sure that ordering is consequent and starts at 1, after changes were made. 
function resetorder () {
	 $count = 1;
  	 $pullorders = mysql_query ( "SELECT id, ordering FROM whatdafaq ORDER BY ordering" );
	 while ( $pullorder = mysql_fetch_array ( $pullorders ) ) {
	 	   $oldpos = $pullorder["ordering"];
		   $newpos = $count;
		   $changepos = mysql_query ( "UPDATE whatdafaq SET ordering = '$newpos' WHERE ordering = '$oldpos'" );
		   if ( !$changepos ) {
  	 	   	  echo ( "<tr><td align=\"center\">Error reseting position: " . mysql_error () . "</td></tr>" );
		   }
		   $count ++;
	 }
}

// If we're viewing a FAQ, do this
function viewfaq ( $faqid ) {
		 global $faqcategory;
		 global $catname;		 
?>
	<tr>
		<td align=center><hr size=1><b>View FAQ</b><hr size=1></td>
	</tr>
<?
   $sql = mysql_query ( "SELECT * FROM whatdafaq, whatdafaqcat WHERE whatdafaq.category = '$faqcategory' AND whatdafaq.category = whatdafaqcat.id AND whatdafaq.id = '$faqid'" );   
   if ( $result = mysql_fetch_array ( $sql ) ) {
   	  $question = $result ["question"];
	  $answer = $result ["answer"];
	  $publish = $result ["publish"];	  
	  echo ( "<tr><td class=\"small\" align=right><a href=\"whatdaadmin.php\">Return to Contents</a></td></tr>" );
  	  echo ( "<tr><td align=center><b>$question</b></td></tr>" );
	  if ( $publish == "no" ) {
  	  	 echo ( "<tr><td align=right>[Unpublished]</td></tr>" );
	  } else {
  	  	 echo ( "<tr><td align=right>[Published]</td></tr>" );	  
	  }	  
  	  echo ( "<tr><td>$answer</td></tr>" );
	  echo ( "<tr><td align=right>[<a href=$PHP_SELF?edit=$faqid&faqcategory=$faqcategory>Edit</a>]</td></tr>" );
	  echo ( "<tr><td align=right>[<a href=$PHP_SELF?delete=$faqid&faqcategory=$faqcategory>Delete</a>]</td></tr>" );	  	  
	  echo ( "<tr><td class=\"small\" align=right><a href=\"whatdaadmin.php\">Return to Contents</a></td></tr>" );
   } else {
   	  echo ( "<tr><td align=center>Error fetching document: " . mysql_error () . "</p>" );
   }
?>
	<tr>
		<td align=center><hr size=1><b>End View FAQ</b><hr size=1></td>
	</tr>
<?   	  
}

// List the faqs as contents
function listfaqs () {
		 global $faqcategory;
		 global $catname;		 
?>
	<tr>
		<td align=center><hr size=1><b>Current <?=$catname?> FAQ Page</b><hr size=1></td>
	</tr>
<?
$sql = mysql_query ( "SELECT * FROM whatdafaq WHERE category = '$faqcategory' ORDER BY ordering" );
while ( $result = mysql_fetch_array ( $sql ) ) {
	  	 $faqid = $result ["id"];
	     $question = $result ["question"];
	     $section = $result ["section"];
		 $publish = $result ["publish"];		 
		 if ( $section == "yes" ) {
		 	echo ( "<tr><td><b>$question</b>" );
			if ( $publish == "no" ) {
			   echo ( " [Unpublished] [<a href=$PHP_SELF?edit=$faqid&faqcategory=$faqcategory>Edit</a>] [<a href=$PHP_SELF?delete=$faqid&faqcategory=$faqcategory>Delete</a>]</td></tr>" );
			} else {
			   echo ( " [Published] [<a href=$PHP_SELF?edit=$faqid&faqcategory=$faqcategory>Edit</a>] [<a href=$PHP_SELF?delete=$faqid&faqcategory=$faqcategory>Delete</a>]</td></tr>" );			
			}		 
		 } else {
		 	echo ( "<tr><td align=right><a href=$PHP_SELF?faqid=$faqid&faqcategory=$faqcategory>$question</a>" );
			if ( $publish == "no" ) {
			   echo ( " [Unpublished] [<a href=$PHP_SELF?edit=$faqid&faqcategory=$faqcategory>Edit</a>] [<a href=$PHP_SELF?delete=$faqid&faqcategory=$faqcategory>Delete</a>]</td></tr>" );
			} else {
			   echo ( " [Published] [<a href=$PHP_SELF?edit=$faqid&faqcategory=$faqcategory>Edit</a>] [<a href=$PHP_SELF?delete=$faqid&faqcategory=$faqcategory>Delete</a>]</td></tr>" );			
			}					 
		 }
}
?>
	<tr>
		<td align=center><hr size=1><b>End of <?=$catname?> FAQ Page</b><hr size=1></td>
	</tr>
<?
}

// Main logic starts here
?>	
<table border="0" cellspacing="3" cellpadding="3" width="100%">
	<tr>
		<TD bgcolor=black align=center><font color=white><b>What da FAQ?!? Admin</b></b></font></td>
	</tr>
	<tr><form action="<?=$PHP_SELF?>" method=post>
   	    <td align=right><span class="small"><b>FAQ Category:</b> <select class="small" name="faqcategory" onChange="document.location.href='<?=$PHP_SELF?>?faqcategory=' + this.options[selectedIndex].value">
<?

// If no category is selected, pick the first from whatdafaqcat
if ( !ISSET ( $faqcategory ) ) {
   $query = mysql_query ( "SELECT id, category FROM whatdafaqcat LIMIT 1");
   if ( $result = mysql_fetch_array ( $query ) ) {
   	  $catid = $result["id"];
   	  $category = $result["category"];
	  $faqcategory = $catid;
	  $catname = $category;
   }
}

// Display the drop list for swapping between categories   	   
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
<?

// If we're deleting an item, do this
if ( ISSET ( $delete ) ) {
   $deletesql = mysql_query ( "DELETE FROM whatdafaq WHERE id = '$delete'" );
   if ( !$deletesql ) {
   	   echo ( "<tr><td align=center>FAQ me! Error deleting: " . mysql_error () . "</td></tr>" );
   } else {
   	   echo ( "<tr><td align=center>FAQ deleted successfully.</td></tr>" );   
   }
}   

// If we're adding a new item...
if ( $add == "yes" ) {
   // Shift the ordering
   if ( $position == "top" ) {
   	 shiftorder(1);
   } else if ( is_numeric ( $position ) ) {
	 shiftorder($position);
   }   
   if ( $position == "top" ) {
   	  $position = 1;
   } else {
   	  $position ++;
   }
   $question = myAddSlashes ( $question );
   $answer = myAddSlashes ( $answer );   
   $addsql = mysql_query ( "INSERT INTO whatdafaq SET question='$question', answer = '$answer', category = '$faqcategory', publish='$publish', section = '$section', ordering='$position'" );
   if ( !$addsql ) {
   	   echo ( "<tr><td align=center>Error updating answer: " . mysql_error () . "</td></tr>" );
   }
   echo ( "<tr><td align=center>Item added successfully.</td></tr>" );   
}

// If we're updating an existing item
if ( ISSET  ( $update ) ) {
   	  // Shift the ordering
	  if ( $position == "top" ) {
	   	 shiftorder(1);
		 $changeorder = mysql_query ( "UPDATE whatdafaq SET ordering = '1' WHERE id = '$update'" );
		 if ( !$changeorder ) {
		  	echo ( "<tr><td align=center>Error adding new order: " . mysql_error (). "</td></tr>" );		  
		 }
		 resetorder();
	  } else if ( is_numeric ( $position ) ) {
	     shiftorder($position);
		 $position ++;
		 $changeorder = mysql_query ( "UPDATE whatdafaq SET ordering = '$position' WHERE id = '$update'" );
		 if ( !$changeorder ) {
		   echo ( "<tr><td align=center>Error adding new order: " . mysql_error () . "</td></tr>" );		  
		 }
		 resetorder();
	  }
   	  $question = myAddSlashes ( $question );
   	  $answer = myAddSlashes ( $answer );
	  $updatesql = mysql_query ( "UPDATE whatdafaq SET question='$question', answer = '$answer', category = '$faqcategory', publish='$publish' WHERE id = '$update'" );
	  if ( !$updatesql ) {
	  	 echo ( "<tr><td align=center>Error updating answer: " . mysql_error () . "</td></tr>" );
	  }
	  echo ( "<tr><td align=center>Item updated successfully.</td></tr>" );	  
}	

// Display the editing form
if ( ISSET ( $edit ) ) {
   $sql = mysql_query ( "SELECT * FROM whatdafaq WHERE id = '$edit'" );
   if ( $result = mysql_fetch_array ( $sql ) ) {
   	  $question = htmlentities ( $result ["question"] );
   	  $answer = $result ["answer"];
   	  $publish = $result ["publish"];
   	  $section = $result ["section"];
   	  $position = $result ["ordering"];      
?>
  	<tr>
		<td>
		<p><a href=<?=$PHP_SELF?>>Back to Main Admin Menu</a></p>
		<table align=center width="99%" border=1 bordercolor=black bgcolor="silver">
		<form action=<?=$PHP_SELF?> method=post>
		<tr><td><b>Question:</b></td><td><input type=text size="70" maxlength="254" name="question" value="<?=$question?>"></td></tr>
		<tr><td><b>Section Heading?:</b></td><td><select name=section>
		<?
		if ( $section == "no" ) {
		   echo ( "<option selected value=\"no\">No<option value=\"yes\">Yes" );
		} else {
		   echo ( "<option value=\"no\">No<option selected value=\"yes\">Yes" );		
		}
		?>
		</select></td></tr>
		<tr><td><b>Publish:</b></td><td><select name=publish>
		<?
		if ( $publish == "no" ) {
		   echo ( "<option selected value=no>No<option value=yes>Yes" );
		} else {
		   echo ( "<option value=no>No<option selected value=yes>Yes" );		
		}
		?>		
		</select></td></tr>
		<tr><td><b>Category:</b></td><td><select name=faqcategory>
		<?
		$query = mysql_query ( "SELECT id, category FROM whatdafaqcat");
		while ( $result = mysql_fetch_array ( $query ) ) {
		 	  $category = $result ["category"];
		  	  $catid = $result ["id"]; 
		   	  if ( $catid == $faqcategory ) {
		    	echo ( "<OPTION SELECTED value='$catid'>$category");
			  } else {
				echo ( "<OPTION value='$catid'>$category");								 
			  }
		}
		?>		
		</select> [ Note: if you change category - check Position ]</td></tr>		
		<tr><td><b>Position:</b></td><td><select name=position><option value=same>Same Position<option value=same>--------------------
		<?
		echo ( "<option value=top>At the top" );		
		$sqlposition = mysql_query ( "SELECT question, ordering, id FROM whatdafaq WHERE category = '$faqcategory' ORDER BY ordering" );
		while ( $resultposition = mysql_fetch_array ( $sqlposition ) ) {
			  $positionid = $resultposition ["ordering"];
		  	  $questionposition = $resultposition ["question"];
		 	  $questionposition = substr ( $questionposition, 0, 25 );
		  	  echo ( "<option value=$positionid>After $questionposition" );			  
		}?></select></td></tr>
		<tr><td colspan=2><b>Body:</b> (Type HTML below)<br><textarea name=answer cols=40 rows=10><?=$answer?></textarea></td></tr>
		<tr><td colspan=2 align=right><input type=submit value=" Update "></td></tr>
		<input type=hidden name=update value=<?=$edit?>>
		</form>
		</table>
		</td>
	</tr>
<?
// Append the faq content list below the edit form
listfaqs ();
   } else {
   	 echo ( "<tr><td align=center>Error retrieving document: " . mysql_error () . "</td></tr>" );
   }
} else {
// If not editing, do this
?>	
	<tr>
		<td align=right><a href=#addnew>Add new item</a></td>
	</tr>
<?
if ( ISSET ( $faqid ) ) {
   // If we're looking at a single faq, show it
   viewfaq ( $faqid );
} else {
   // Otherwise show the faq content list
   listfaqs ();
}

// Then show the add new item form
?>
	<tr><td align=center><b><a name="addnew">Add New FAQ Item</a></b></td></tr>
	<tr><td><hr size=1>
		<table align=center width="99%" border=1 bordercolor=black bgcolor="silver">
		<form action=<?=$PHP_SELF?> method=post>
		<tr><td><b>Question:</b></td><td><input type=text size="70" maxlength="254" name="question"></td></tr>
		<tr><td><b>Section Heading?:</b></td><td><select name=section><option value="no">No<option value="yes">Yes</select></td></tr>
		<tr><td><b>Category:</b></td><td><?=$catname?></td></tr>		
		<tr><td><b>Publish:</b></td><td><select name=publish><option value=no>No<option value=yes>Yes</select></td></tr>
		<tr><td><b>Position:</b></td><td><select name=position><option value=top>At the top
		<?
		$sql = mysql_query ( "SELECT question, ordering FROM whatdafaq WHERE category = '$faqcategory' ORDER BY ordering" );
		while ( $result = mysql_fetch_array ( $sql ) ) {
			  $ordering = $result ["ordering"];
		  	  $question = $result ["question"];
		 	  $question = substr ( $question, 0, 25 );
		  	  echo ( "<option value=$ordering>After $question" );
		}?></select></td></tr>
		<tr><td colspan=2><b>Body:</b> (Type HTML below)<br><textarea name=answer cols=40 rows=10></textarea></td></tr>
		<tr><td colspan=2 align=right><input type=submit value=" Create "></td></tr>
		<input type=hidden name=faqcategory value=<?=$faqcategory?>>
		<input type=hidden name=add value=yes>		
		</form>
		</table>
	<hr size=1>
	</td>
	</tr>
<?
}
?>	   
</table>
<?php
include("footer.php");
?>