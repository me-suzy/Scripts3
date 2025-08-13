<?
/*

   Copyright (c), 1999, 2000, 2001 - phpauction.org                  
   
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



include "../includes/config.inc.php";

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

		static $indent;

		reset($parent_id_hash);

		while ( list($key, $val) = each( $parent_id_hash ) )
		{
				if ($val == $id)
				{
					if ( $c == 1)
					{
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
		$query = "select cat_id,cat_name,parent_id from PHPAUCTION_categories where deleted=0 order by cat_name";
		$result = mysql_query($query);
		if ( !$result )
		{
			print $ERR_001."$query<BR> ".mysql_error();
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

		dump_children(0,1,$categoriesL);
		
		Header("Location: ./categories.php?MSG=MSG_184&parent=$parent&name=$name");
		exit;

?>
