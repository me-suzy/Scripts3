<?
   include( "../config.php" );
   include( "../dblink.inc" );

  if ( !isset($HTTP_RAW_POST_DATA) )
  {
    print("POST variable not set!</body></html>\n");    
    exit;
  }

  $query = "DELETE FROM Categories";
  $result = mysql_query( $query, $link );

  function getIndentLevel( $cat, $link )
  {
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

  function getParent( $cat, $link )
  {
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
      print( "getParent(): No row found for CategoryID = $cat</body></html>\n" );
      exit;
    }
  }

  function insertCategory( $name, $parent, $indent, $link )
  {
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

  $cur = -1;

	$line = strtok($HTTP_RAW_POST_DATA , "\n"); 
  
  do 
  {
    if ( strlen($line) == 0 )
      continue;

    for ( $indent = 0; $line[ $indent ] == "\t"; $indent++ )
    {
    }

    $line = substr( $line, $indent, strlen($line) - $indent + 1 );

    $previndent = getIndentLevel( $cur, $link );

    if ( $indent == $previndent )
    {
      $parent = getParent( $cur, $link );
    }
    elseif ( $indent > $previndent )
    {
      $parent = $cur;
    }
    else
    {
      $levelsUp = $previndent - $indent;
      $parent = getParent( $cur, $link );
      while ( $levelsUp > 0 )
      {
        $parent = getParent( $parent, $link );
        $levelsUp--;
      }
    }

    $cur = insertCategory( $line, $parent, $indent, $link );
  }
  while ( $line = strtok("\n") );

  include( "update_browse_cat.php" );

  include( "update_fullbrowse_cat.php" );
?>

