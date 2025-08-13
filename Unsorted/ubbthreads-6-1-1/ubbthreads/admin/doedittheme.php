<?
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: 06/05/2002

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/

// Require the library
   require ("../main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $html = new html;

// -----------------------------
// Make sure they should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }

// --------------------
// Assign the variables
   $themeextras = str_replace("<br>","\n",$themeextras);

// -----------------------------------------------------------
// Escape all ' before writing to the file, so it won't break
   while (list($key,$value) = each($HTTP_POST_VARS)) {
      $HTTP_POST_VARS{$key} = str_replace("\"","\\'",$value);
   }

// -------------------------------------------------------------
// Need to figure out what stylesheets we want to make available
   $dir = opendir ($config['stylepath']);
   $i = 0;
   while ($file = readdir($dir)) {
      $i++;
      $styles[$i] = $file;
   }
   sort ($styles);
   closedir ($dir);
   $stylesize = sizeof($styles);

   for ($i=0; $i<$stylesize;$i++) {
      if ( (!preg_match("/\.css$/",$styles[$i])) || (preg_match("/$\./",$styles[$i])) ){ continue; }
      $styles[$i] = str_replace(".css","",$styles[$i]);
      $name = "stylename$styles[$i]";
      if ($HTTP_POST_VARS[$name]) {
         $desc = "styledesc$styles[$i]";
         if (!$availablestyles) {
            $availablestyles .= "$styles[$i]:$HTTP_POST_VARS[$desc],";
         }
         else {
            $availablestyles .= "\n				$styles[$i]:$HTTP_POST_VARS[$desc],";
         }
      }
   }
   $availablestyles = preg_replace("/,$/","",$availablestyles);

$newconfig = <<<EOF
<?
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: 06/05/2002

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/

// Theme variables

// Default style sheet
  \$theme['stylesheet'] =	"$stylesheet";

// What style sheets do you want to make available to your users
// Format:	Filename without the .css extension: Description
//     ie:	'stylesheet:The default style sheet,
//   		 stylesheet2:Large font style sheet';
    \$theme['availablestyles'] = "$availablestyles";

// Table cellpadding
  \$theme['cellpadding']=	$cellpadding;

// Table cellspacing
  \$theme['cellspacing'] =	$cellspacing;

// Default color for admin usernames
  \$theme['admincolor']	=	"$admincolor";

// Default color for moderator usernames
  \$theme['modcolor']	=	"$modcolor";

// Table width
// Percentage or integer
  \$theme['tablewidth']	=	"$tablewidth";

// Default listing to flat or threaded mode
  \$theme['postlist']	=	"$postlist";

// Default posts and reply listing to collapsed or expanded
  \$theme['threaded']	=	"$threaded";

// Default sort order
// 1 - Descending Subject
// 2 - Ascending Subject
// 3 - Descdending Poster
// 4 - Ascending Poster
// 5 - Descending Date
// 6 - Ascending Date    
  \$theme['sort']	=	"$sort";

// Default number of topics per page
  \$theme['postsperpage']=	"$postsperpage";

// Defrault number of posts to be shown on a single page when viewing a
// thread in flat mode
  \$theme['flatposts']	=	"$flatposts";

// Allow users to add a picture to their profile
  \$theme['PictureView']=	"$PictureView";

// If allowing users to add pictures, what is the maximum width allowed
  \$theme['PictureWidth'] =	"$PictureWidth";

// If allowing users to add pictures, what is the maximum width allowed
  \$theme['PictureHeight'] =	"$PictureHeight";

// Show user's pictures with their posts
  \$theme['PicturePosts']=	$PicturePosts;

// Default textarea columns
  \$theme['TextCols']	=	"$TextCols";

// Default Textarea rows
  \$theme['TextRows']	=	"$TextRows";

// Which Time format to use
// short1 = 'MM/DD/YY 10:19 AM';
// short2 = 'DD/MM/YY 10:19 AM';
// short3 = 'YY/MM/DD 10:19 AM';
// short4 = 'DD/MM/YYYY 22:43";
// short5 = '22:43 DD/MM/YYYY";
  \$theme['timeformat']	=	"$timeformat";

?>
EOF;

// --------------------
// Write the new config
   echo $newconfig;
   $fd = fopen("{$config['path']}/theme.inc.php","w");
   fwrite($fd,$newconfig);
   fclose($fd);

// ------------------------
// Send them a confirmation
  $html -> send_header ("theme.inc.php updated",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
  $html -> admin_table_header("Configuration has been updated.");
  echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
  echo "The theme file, theme.inc.php, has been updated";
  echo "</span></TD></TR></TABLE>";
  $html -> send_admin_footer();
