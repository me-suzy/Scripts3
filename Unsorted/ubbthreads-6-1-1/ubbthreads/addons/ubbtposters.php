<?

// -------------------------------------------------
// Variables Section - Edit these to suit your needs

  // Path to your main.inc.php script
   $path = ""; 

  // Set this variable to the number of top posts you want to include
   $topposters = 	'10';

  // If they view a user's profile from this list, where should the return
  // link send them to?
   $returnpage = "http://www.wwwthreads.com";
  
  // If they view a user's profile from this list, what should the return
  // link say?
   $returntext = "Back to this page";

  // You may need to change the unshift line to the path of your UBB.threads
  // installation

// -----------------------------
// End of the variables section
   //require ("$path/main.inc.php");

// ------------------------------
// Can we use the limit function?
   $limit;
   if ($config['dbtype'] == "postgres") {
      $limit = "LIMIT $topposters,0";
   }
   elseif ($config['dbtype'] == "mysql") {
      $limit = "LIMIT 0,$topposters";
   }
   else {
      $limit = "";
   }

// --------------------------
// Let's grab the news items
   $query = "
    SELECT U_Username,U_Totalposts
    FROM   {$config['tbprefix']}Users
    ORDER BY U_Totalposts DESC
    $limit
   ";
   $sth = $dbh -> do_query($query);

// ---------------------------
// echo out the starting html
   $html = new html;

// -----------------------
// Cycle through the posts
   echo "
    <TABLE BORDER=0>
      <TR><TD COLSPAN=2>
        Top $topposters posters
      </TD></TR>
   ";

   while ( list($Username,$Totalposts) = $dbh -> fetch_array($sth)) {

      $EUsername = rawurlencode($Username);
      $returnpage = rawurlencode($returnpage);
      $returntext = rawurlencode($returntext); 
      echo "<TR><TD><a href=\"{$config['phpurl']}/showprofile.php?what=addon&returnpage=$returnpage&returntext=$returntext&User=$EUsername\">$Username</a></TD><TD>$Totalposts</TD></TR>";
   }

   echo "</TABLE>";
   $dbh -> finish_sth($sth);

?>
