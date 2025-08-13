<?
/*

   Copyright (c), 1999, 2000 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
                                                                        
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
   
*/



include "includes/config.inc.php";

$output = "";

/* global variable */
   $category_id_hash[]   = array();
   $category_name_hash[] = array();
   $parent_id_hash[]     = array();
   $children_hash[]      = array();
   $num_categories       = 0;


		/* view categories */
		function dump_children($id,$c,$ct)
		{
			global $category_id_hash;
			global $category_name_hash;
			global $parent_id_hash;
			global $children_hash;
			global $TPL_categories_list;
			global $TPL_categories;
			global $output;

			static $indent;

			reset($parent_id_hash);

			while ( list($key, $val) = each( $parent_id_hash ) )
			{
					if ($val == $id)
					{
						if ( $c == 1)
						{
							$output .= "<OPTION VALUE=\"$key\"";
							$output .= ">$indent$category_name_hash[$key]</OPTION>\n";

							$sval = $indent.$category_name_hash[$key];

							mysql_query ("INSERT INTO PHPAUCTION_categories_plain VALUES (NULL,$key,'".$sval."')");
						 }
						 $indent .= '&nbsp; &nbsp;';

						 if ($children_hash[$key]) 
						 {
							dump_children($key,$c,$ct);
							reset($parent_id_hash);
							while ( key($parent_id_hash) != $key )
							{
								next($parent_id_hash);
							}
							next($parent_id_hash);
						  }
								$indent = substr($indent,0,-13);
					}
			}
		}

		/* Categories */
		$query = "select cat_id,cat_name,parent_id from PHPAUCTION_categories order by cat_id";
		$result = mysql_query($query);
		if ( !$result )
		{
			print $ERR_001;
			exit;
		}
		$num_rows       = mysql_num_rows($result);
		$num_categories = $num_rows;

		$i=0;
		while ( $i < $num_rows )
		{
				$category_id                      = mysql_result($result,$i,"cat_id");
				$category_name_hash[$category_id] = mysql_result($result,$i,"cat_name");
				$parent_id_hash[$category_id]     = mysql_result($result,$i,"parent_id");
				$children_hash[$parent_id_hash[$category_id]]++;
				$i++;
		}
		//      $cat = $categories;

		mysql_query ( "DELETE FROM PHPAUCTION_categories_plain" );

		$output = "<SELECT NAME=categoriesL>";
		dump_children(0,1,$categoriesL);
		$output .= "</SELECT>";

		$handle = fopen("categories_list_all.", "w" );
		fputs ($handle,$output);
		fclose($handle);
?>

<H2>PHPauction - Populate Categories Script</H2>
<BR>
<BR>
categories_list_all. created.
<BR><BR>
You can now go back to the <A HREF="admin/admin.php">Admin HomePage</A>.

