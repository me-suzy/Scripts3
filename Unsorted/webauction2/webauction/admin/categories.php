<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	Function ToBeDeleted($index){
		Global $delete, $dbfix;
		
		$i = 0;
		while($i < count($delete)){
			if($delete[$i] == $index) return true;
			
			$i++;
		}
		return false;
	}


   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');

	//--Authentication check
	if(!$authenticated){
		Header("Location: login.php");
	}
	
	
	if($act){
			
		//-- Built new payments array
		
		$rebuilt_categories = array();
		$i = 0;
		while($i < count($categories)){
		
			if(!ToBeDeleted($i)){
				
				$rebuilt_categories[] = $categories[$i];
			}
			$i++;
		}
		
	   //-- Parse the categories array to update
	   
	   $i = 0;
	   while($i < count($categories)){
	   	
	   	if($categories[$i] != $old_categories[$i] || 
			$old_colour[$i] != $colour[$i] || $old_image[$i] != $image[$i] ){
	   		
	   		$query = "update ".$dbfix."_categories set cat_name=\"$categories[$i]\", cat_colour=\"$colour[$i]\", cat_image=\"$image[$i]\" where cat_id=$categories_id[$i]";
	   		$result = mysql_query($query);
	   		if(!$result){
	   			print "Database access error - abnormal termination ".mysql_error();
	   			exit;
	   		}
	
				$updated = TRUE;
	   	}

			$i++;
		}	   	
	   

	   //-- Parse the categories array to delete
	   
	   $i = 0;
	   while($i < count($delete)){
	   	
	   	if($delete[$i]){
	   		
	   		$query = "update ".$dbfix."_categories set delete=1 where cat_id=$delete[$i]";
	   		$query = "delete from ".$dbfix."_categories where cat_id=$delete[$i]";
	   		$result = mysql_query($query);
	   		if(!$result){
	   			print "Database access error - abnormal termination ".mysql_error();
	   			exit;
	   		}

	   		$updated = TRUE;
	   	}
	   	
	   	$i++;
		}	   	
	   
	   //-- Add new category (if present)
	   
	   if($new_category){
	   
	   	if(!$parent) $parent = 0;
	   	$query = "insert into ".$dbfix."_categories (cat_id, parent_id, cat_name, deleted, sub_counter, counter, cat_colour, cat_image) values (NULL, $parent,\"$new_category\", 0,0,0, \"$cat_colour\", \"$cat_image\")";
	   	$result = mysql_query($query);
	   	if(!$result){
	   		print "Database access error - abnormal termination ".mysql_error();
	   		exit;
	   	}

   		$updated = TRUE;
	   	
		}	   	
	   


		//-- If something has been modified or deleted
		//-- some HTML code pieces must be rebuilt.
		
		if($updated){
			Header("Location: ./util_cc1.php?parent=$parent&name=$name");
			exit;
		}

	}		
		
	
	
?>
<HTML>
<HEAD>


<SCRIPT Language=Javascript>

function window_open(pagina,titulo,ancho,largo,x,y){

  var Ventana= 'toolbar=0,location=0,directories=0,scrollbars=1,screenX='+x+',screenY='+y+',status=0,menubar=0,resizable=0,width='+ancho+',height='+largo;
  open(pagina,titulo,Ventana);

}

</SCRIPT>


<TITLE></TITLE>
</HEAD>

<?    require('../includes/styles.inc.php'); ?>
  
<BODY>

<? require("./header.php"); ?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_078; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
	<CENTER>
	<FORM NAME=conf ACTION=categories.php METHOD=POST>
	<TABLE WIDTH=600 CELLPADDING=2>
	<TR>
	<TD WIDTH=50></TD>
	<TD COLSPAN=2>
		<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
		<? 
			print $MSG_161; 
			if($$ERR){
				print "<FONT COLOR=red><BR><BR>".$$ERR;
			}else{
				if($$MSG){
					print "<FONT COLOR=red><BR><BR>".$$MSG;
				}else{
					print "<BR><BR>";
				}
			}
		?>
	</TD>
	</TR>

			
	 <TR>
	 <TD WIDTH=50></TD>
	 <TD COLSPAN=2>
	 	<? 
	 		if($parent > 0)
	 		{
	 			$father = $parent;
	 			$navigation = "";
	 			$counter = 0;
	 			do
	 			{
	 				$query = "select cat_id,cat_name,parent_id from ".$dbfix."_categories where cat_id=$father";
	 				$result = mysql_query($query);
	 				
	 				$id 			= mysql_result($result,0,"cat_id");
	 				$descr 		= mysql_result($result,0,"cat_name");
	 				$granfather = mysql_result($result,0,"parent_id");
	 				
	 				
	 				if($counter == 0)
	 				{
	 					$navigation = $std_font."$descr ";
	 				}
	 				else
	 				{
	 					if($parent != $father)
	 					{
	 						$navigation = "<A HREF=\"categories.php?parent=$id&name=$descr\">
	 											$std_font
	 											$descr</A>"." > ".$navigation;
	 					}
	 				}
	 				$counter++;
	 				
	 				$father = $granfather;
	 				
	 			}while($father > 0);

				$navigation = "$std_font<A HREF=\"categories.php\">Categories:</A> ".$navigation;
 				print $navigation;
										
	 		}
	 	?>
	 </TD>
	 </TR>
	 <TR>
	 <TD WIDTH=50></TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_087; ?> </B>
	 </TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <!-- Category colour -->
	 <B><? print $MSG_328; ?></B>
	 </TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <!-- Image location -->
	 <B><? print $MSG_329; ?></B>
	 </TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_088; ?> </B>
	 </TD>
	 </TR>
	
	<?
	
		//-- Get first level categories

		$query = "select * from ".$dbfix."_categories where parent_id=\"$parent\" and deleted=0 order by cat_name";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error - abnormal termination".mysql_error();
			exit;
		}
		
		$num_cats = mysql_num_rows($result);
		
			
		$i = 0;
		while($i < $num_cats ){
			
			//-- Get category's data
			
			$cat_id = mysql_result($result,$i,"cat_id");
			$cat_name = mysql_result($result,$i,"cat_name");
			$counter = mysql_result($result,$i,"counter");
			$sub_counter = mysql_result($result,$i,"sub_counter");
			$cat_colour = mysql_result($result, $i, "cat_colour");
			$cat_image = mysql_result($result, $i, "cat_image");
			
			//-- Check if this category has sub_categories
			
			$query = "select count(cat_id) as subcats from ".$dbfix."_categories where parent_id=$cat_id and deleted=0";
			$resultsub = mysql_query($query);
			if(!$resultsub){
				print "Database access error - abnormal termination ".mysql_error();
				exit;
			}
			if(mysql_result($resultsub,0,"subcats") > 0) {
				$hassubs = 1;
			} else {
				$hassubs = 0;		
			}
			print "<TR>
					 <TD WIDTH=50 ALIGN=RIGHT>
					 <A HREF=\"categories.php?parent=$cat_id&name=".urlencode($cat_name)."\">
					 <IMG SRC=\"../images/plus.gif\" BORDER=0 ALT=\"Browse Subcategories\">
					 </A>
					 </TD>
					 <TD>
					 <INPUT TYPE=hidden NAME=categories_id[] VALUE=\"$cat_id\">
					 <INPUT TYPE=hidden NAME=old_categories[] VALUE=\"$cat_name\">
					 <INPUT TYPE=text NAME=categories[] VALUE=\"$cat_name\" SIZE=25>
					 </TD>
					 <TD>
					 <INPUT TYPE=hidden NAME=old_colour[] VALUE=\"$cat_colour\">
					 <INPUT TYPE=text NAME=colour[] VALUE=\"$cat_colour\" SIZE=25>
					 </TD>
					 <TD>
					 <INPUT TYPE=hidden NAME=old_image[] VALUE=\"$cat_image\">
					 <INPUT TYPE=text NAME=image[] VALUE=\"$cat_image\" SIZE=25>
					 </TD>
					 <TD>";
					 
					 if (counter == 0 && $sub_counter == 0 && $hassubs == 0) 
					 {
					 	print "<INPUT TYPE=checkbox NAME=delete[] VALUE=$cat_id>";
					 }
					 else
					 {
					 	print "<IMG SRC=\"../images/nodelete.gif\" ALT=\"You cannot delete this category\">";
					 }
					 print "</TD>
					 </TR>";
			$i++;
		}
			print "<TR>
					 <TD WIDTH=50>
					 $std_font Add
					 </TD>
					 <TD>
					 <INPUT TYPE=hidden NAME=parent VALUE=\"$parent\">
					 <INPUT TYPE=hidden NAME=name VALUE=\"$name\">
					 <INPUT TYPE=text NAME=new_category SIZE=25>
					 </TD>
					 <TD>
					 <INPUT TYPE=text NAME=cat_colour SIZE=25>
					 </TD>
					 <TD>
					 <INPUT TYPE=text NAME=cat_image SIZE=25>
					 </TD>
					 <TD>
					 </TD>
					 </TR>";
		
	?>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_089; ?>">
	</TD>
	</TR>
	<TR>
	<TD WIDTH=50></TD>
	<TD>

	</TD>
	</TR>
	</TABLE>
	</FORM>
	<BR><BR>

	<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
	<A HREF="admin.php" CLASS="links">Admin Home</A>
	</FONT>
	<BR><BR>

</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
</TD>
</TR>
</TABLE>

<? require("./footer.php"); ?>
</BODY>
</HTML>
