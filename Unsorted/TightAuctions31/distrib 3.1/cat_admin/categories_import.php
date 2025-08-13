
<?
   include( "../config.php" );
   include( "../dblink.inc" );

  function getIndentLevel( $cat )
  {
    global $link;

    if ( $cat == -1 )
      return 0;

		$query = "SELECT Level FROM Categories WHERE CategoryID = $cat";
		$result = mysql_query( $query, $link );

		if ( $row = mysql_fetch_row( $result ) )
		{
			return $row[0];
		}		
    else
    {
      print( "getIndentLevel(): No row found for CategoryID = $cat\n" );
      exit;
    }
  }

  function getRootItem()
  {
    global $link;

		$query = "SELECT CategoryID FROM Categories WHERE Level=0";
		$result = mysql_query( $query, $link );

    // Just return the first root item found

		if ( $row = mysql_fetch_row( $result ) )
		{
			return $row[0];
		}		
    else
    {
      print( "getRootItem(): No root found.\n" );
      exit;
    }    
  }

  function getNextItem($cat)
  {   
    if ( itemHasChildren( $cat ) )
    {
      return getChildItem( $cat );
    }
    else
    {
      while ( ($sib = getNextSiblingItem($cat)) == -1 )
      {
        if ( ($cat = getParent( $cat )) == -1 )
          return -1;
      }
    }

    return $sib;
  }

  function itemHasChildren( $cat )
  {
    global $link;

    if ($cat == -1)
      return false;

		$query = "SELECT * FROM Categories WHERE ParentCatID = $cat";
		$result = mysql_query( $query, $link );

    if ( mysql_numrows( $result ) == 0 )
      return false;
    else
      return true;
  }

  function getChildItem( $cat )
  {
    global $link;

    if ($cat == -1)
    {
      print( "getChildItem: cat = -1\n" );
      exit;
    }

		$query = "SELECT CategoryID FROM Categories WHERE ParentCatID = $cat";
		$result = mysql_query( $query, $link );
    
		if ( $row = mysql_fetch_row( $result ) )
		{
			return $row[0];
		}		
    else
    {
      print( "getChildItem(): No child item found for cat = $cat\n" );
      exit;
    }
  }

  function getNextSiblingItem( $cat )
  {
    global $link;

    if ($cat == -1)
    {
      print( "getNextSiblingItem: cat = -1\n" );
      exit;
    }

		$query = "SELECT Level, ParentCatID FROM Categories WHERE CategoryID = $cat";
		$result = mysql_query( $query, $link );
    
		if ( $row = mysql_fetch_row( $result ) )
		{
			$Level  = $row[0];
      $Parent = $row[1];
		}		
    else
    {
      print( "getNextSiblingItem(): No item found for cat = $cat\n" );
      exit;
    }

		$query = "SELECT CategoryID FROM Categories WHERE ParentCatID=$Parent AND Level=$Level";
		$result = mysql_query( $query, $link );
    
    $GetNextItem = false;
    $Item = -1;
		while (($row = mysql_fetch_row( $result )) && ($Item == -1) )
    {
      if ( $GetNextItem )
        $Item = $row[0];

      if ( $row[0] == $cat )
        $GetNextItem = true;      
    }

    return $Item;
  }

  function getParent( $cat )
  {
    global $link;

    if ( $cat == -1 )
      return -1;

		$query = "SELECT ParentCatID FROM Categories WHERE CategoryID = $cat";
		$result = mysql_query( $query, $link );

		if ( $row = mysql_fetch_row( $result ) )
		{
			return $row[0];
		}		
    else
    {
      print( "getParent(): No row found for CategoryID = $cat\n" );
      exit;
    }
  }

  function insertCategory( $name, $parent, $indent )
  {
    global $link;

    $name = addslashes( $name );

    print( "insertCategory $name, $parent, $indent\n" );

		$query = "INSERT INTO Categories (ParentCatID, Level, Name) VALUES ($parent, $indent, '$name' )";
		$result = mysql_query( $query, $link );

		if ( $result )
		{
			return mysql_insert_id( $link );
		}		
    else
    {
      print( "insertCategory() failed for $name, $parent, $indent\n" );
      exit;
    }
  }

  function getName( $cat )
  {
    global $link;

		$query = "SELECT Name FROM Categories WHERE CategoryID = $cat";
		$result = mysql_query( $query, $link );

		if ( $row = mysql_fetch_row( $result ) )
		{
			return $row[0];
		}		
    else
    {
      print( "getName(): No row found for CategoryID = $cat\n" );
      exit;
    }
  }

  $cat = getRootItem();
  while ( $cat != -1 )
  {
    $indent = getIndentLevel( $cat );

    while ( $indent > 0 )
    {
      print("\t");
      $indent--;
    }

    $text = getName($cat);
    $text .= "\n";

    print( "$text" );
    $cat = getNextItem($cat);
  }

?>
