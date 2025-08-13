<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


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
						$query = "INSERT INTO PHPAUCTIONPROPLUS_categories_plain VALUES (NULL,$key,'".addslashes($sval)."')";
						@mysql_query($query);

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
		$query = "select cat_id,cat_name,parent_id from PHPAUCTIONPROPLUS_categories where deleted=0 order by cat_name";
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

		mysql_query ( "DELETE FROM PHPAUCTIONPROPLUS_categories_plain" );

		dump_children(0,1,$categoriesL);

?>