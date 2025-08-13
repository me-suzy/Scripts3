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

// ------------------------
// Send them a page 
  $html -> send_header ("Edit theme settings",$Cat,0,$user);
  $html -> admin_table_header("Edit theme settings");
  $html -> open_admin_table();

// ------------------------------------------------------------------------
// We need to define the base set of keys that we need for the operation of
// the program
   $knownkeys = "-stylesheet-availablestyles-cellspacing-cellpadding-admincolor-modcolor-tablewidth-postlist-threaded-sort-postsperpage-flatposts-PictureView-PicturePosts-TextCols-TextRows-timeformat-PictureWidth-PictureHeight-";

// -------------------------------------------------------------------------
// Now we need to check for any new keys that have been introduced since the
// current version being ran
   $keycheck = split("-",$knownkeys);
   $keysize  = sizeof($keycheck);
   for ( $i=0; $i<$keysize;$i++) {
      if (!isset($theme[$keycheck[$i]])) {
         $new[$keycheck[$i]] = "class = standouttext";
      }
   }

// Basic width and height of pictures
   if (!$theme['PictureWidth']) {
      $theme['PictureWidth'] = "65";
   }
   if (!$theme['PictureHeight']) {
      $theme['PictureHeight'] = "75";
   }

   if ($theme['postlist'] == "flat") {
      $postlistflat = "SELECTED";
   }
   else {
      $postlistthreaded = "SELECTED";
   }

   if ($theme['threaded'] == "expanded") {
      $threadedexpanded = "SELECTED";
   }
   else {
      $threadedcollapsed = "SELECTED";
   }

   if ($theme['sort'] == 1) {
      $sortds = "SELECTED";
   }elseif ($theme['sort'] == 2) {
      $sortas = "SELECTED";
   }elseif ($theme['sort'] == 3) {
      $sortdp = "SELECTED";
   }elseif ($theme['sort'] == 4) {
      $sortap = "SELECTED";
   }elseif ($theme['sort'] == 5) {
      $sortdd = "SELECTED";
   }elseif ($theme['sort'] == 6) {
      $sortad = "SELECTED";
   }

   if ($theme['PictureView']) {
      $PictureView = "SELECTED";
   }
   else {
      $NoPictureView = "SELECTED";
   }

   if ($theme['PicturePosts']) {
      $PicturePosts = "SELECTED";
   }
   else {
      $NoPicturePosts = "SELECTED";
   }

   if ($theme['timeformat'] == "long") {
      $long = "SELECTED";
   }
   elseif ($theme['timeformat'] == "short1") {
      $short1 = "SELECTED";
   }
   elseif ($theme['timeformat'] == "short2") {
      $short2 = "SELECTED";
   }
   elseif ($theme['timeformat'] == "short3") {
      $short3 = "SELECTED";
   }
   elseif ($theme['timeformat'] == "short4") {
      $short4 = "SELECTED";
   }
   elseif ($theme['timeformat'] == "short5") {
      $short5 = "SELECTED";
   }

   if (!is_writeable("{$config['path']}/theme.inc.php")) {
       echo "<b>{$config['path']}/theme.inc.php is not writeable, so you will not be able to use this tool until the permissions are changed.</b><p>";
   }

   echo "
    <TR><TD class=\"lighttable\">
    You can edit any of the information below.  <p {$new['stylesheet']}>

    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doedittheme.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>

    Default stylesheet to use (this can be changed on a per forum basis when creating/editing a forum):<br>
    <select name=\"stylesheet\" class=formboxes>
  ";

// ------------------------
// List out the stylesheets
   $StyleSheet = $theme['stylesheet'];
   $stylenames = split(",",$theme['availablestyles']);
   $stylesize = sizeof($stylenames);
   for ( $i=0;$i<$stylesize;$i++) {
      list($style,$desc) = split(":",$stylenames[$i]);
      $style = preg_replace("/^\s+/","",$style);
      $stylenames[$style] = $desc;
      $extra = "";
      if ($StyleSheet == $style) {
         $extra = "selected";
      }
      echo "<option value=\"$style\" $extra>$desc";
   }
   echo "</select>";

   echo "  
      <p {$new['availablestyles']}>
      The following stylesheets were found in your stylesheet directory.  Place a checkmark by those you want to make available to your users.  Provide a name for those you want to make available:<br>
   ";

   $dir = opendir($config['stylepath']);
   $i=0;
   while ($file = readdir($dir)) {
      if ($file == "." || $file == "..") { continue; }
      $i++;
      $styles[$i] = $file;
   }
   sort ($styles);
   closedir($dir);
   $stylesize = sizeof($styles);

// --------------------------------------------------------------
// If we can't open the directory, use the current theme settings
   $html -> open_admin_table();
   echo "<tr class=darktable><td> </td><td>stylesheet</td><td>name</td></tr>";

   for ( $i=0; $i<$stylesize;$i++) {
      $styles[$i] = ereg_replace(".css","",$styles[$i]);
      $checked = "";
      if ($stylenames[$styles[$i]]) {
         $checked = "CHECKED";
      }
      $stylenames[$styles[$i]] = ereg_replace("\"","\\\"",$stylenames[$styles[$i]]);
      echo "<tr class=darktable><td><input type=checkbox class =\"formboxes\" name=\"stylename$styles[$i]\" $checked></td>";
      echo "<td>$styles[$i]</td>";
      echo "<td><input type=text name=\"styledesc$styles[$i]\" class=\"formboxes\" value=\"". $stylenames[$styles[$i]] ."\"></td></tr>";
   } 
   echo "</table></td></tr></table>";

   echo "
    <p {$new['cellspacing']}>
    Table cellspacing:<br>
    <input type=text size=5 name=cellspacing value={$theme['cellspacing']} class=formboxes>

    <p {$new['cellpadding']}>
    Table cellpadding:<br>
    <input type=text size=5 name=cellpadding value={$theme['cellpadding']} class=formboxes>

    <p {$new['admincolor']}>
    Default color for admin usernames:<br>
    <input type=text name=admincolor value=\"{$theme['admincolor']}\" class=formboxes>

    <p {$new['modcolor']}>
    Default color for moderator usernames:<br>
    <input type=text name=modcolor value=\"{$theme['modcolor']}\" class=formboxes>

    <p {$new['tablewidth']}>
    Table width - all screens.  (Can be a percentage or integer):<br>
    <input type=text name=tablewidth value=\"{$theme['tablewidth']}\" size=5 class=formboxes>

    <p {$new['postlist']}>
    Default post listing - flat or threaded:<br>
    <select name=\"postlist\" class=formboxes>
      <option value=\"flat\" $postlistflat>Flat mode
      <option value=\"threaded\" $postlistthreaded>Threaded mode
    </select>

    <p {$new['threaded']}>
    Default postlist format.  Collapsed (only show main topic with total number of replies.  Expanded (Show topics with a threaded list of all replies):<br>
    <select name=\"threaded\" class=formboxes>
      <option value=\"collapsed\" $threadedcollapsed>Collapsed mode
      <option value=\"expanded\" $threadedexpanded>Expanded mode
    </select>

    <p {$new['sort']}>
    Default sort order:<br>
    <select name=\"sort\" class=formboxes>
      <option value=1 $sortds>Descending Subject
      <option value=2 $sortas>Ascending Subject
      <option value=3 $sortdp>Descending Poster
      <option value=4 $sortap>Ascending Poster
      <option value=5 $sortdd>Descending Date
      <option value=6 $sortad>Ascending Date
    </select>

    <p {$new['postsperpage']}>
    Default number of topics per page on the postlist screen:<br>
    <input type=text name=postsperpage class=formboxes value=\"{$theme['postsperpage']}\">

    <p {$new['flatposts']}>
    Default number of posts to be shown on a page when viewing a topic in flat mode:<br>
    <input type=text name=flatposts class=formboxes value=\"{$theme['flatposts']}\">

    <p {$new['PictureView']}>
    Allow users to add a picture to their profile:<br>
    <select name=\"PictureView\" class=\"formboxes\">
      <option value=0 $NoPictureView>No pictures in users profiles
      <option value=1 $PictureView>Yes, pictures can be added to profiles
    </select>

    <p {$new['PictureWidth']}>
    If allowing users to add pictures, what is the maximum width allowed:<br>
    <input type=text name=PictureWidth value=\"{$theme['PictureWidth']}\" class=formboxes>

    <p {$new['PictureHeight']}>
    If allowing users to add pictures, what is the maximum height allowed:<br>
    <input type=text name=PictureHeight value=\"{$theme['PictureHeight']}\" class=formboxes>

    <p {$new['PicturePosts']}>
    Show user's profile pictures in their posts:<br>
    <select name=\"PicturePosts\" class=\"formboxes\">
      <option value=0 $NoPicturePosts>No, do not show user's picture in their posts
      <option value=1 $PicturePosts>Yes, show user's picture in their posts
    </select>

    <p {$new['TextCols']}>
    Default Textarea columns:<br>
    <input type=text size=5 class=formboxes value=\"{$theme['TextCols']}\" name=TextCols>

    <p {$new['TextRows']}>
    Default Textarea rows:<br>
    <input type=text size=5 class=formboxes value=\"{$theme['TextRows']}\" name=TextRows>

    <p {$new['timeformat']}>
    Time format:<br>
    <select name=timeformat class=formboxes>
      <option value=\"long\" $long>Sun Mar 7 10:19:48 2000
      <option value=\"short1\" $short1>MM/DD/YY 10:19 AM
      <option value=\"short2\" $short2>DD/MM/YY 10:19 AM
      <option value=\"short3\" $short3>YY/MM/DD 10:19 AM
      <option value=\"short4\" $short4>DD/MM/YYYY 10:19
      <option value=\"short5\" $short5>10:19 DD/MM/YYYY
    </select>

    <br><br>
    The following variables are unknown to the base UBB.threads package, but might be used by installed hacks are modifications to the program:<br>
    <textarea name=\"themeextras\" cols=60 rows=5 class=\"formboxes\">";


// ----------------------------------------------------------------------
// Now we need to see if there are any extra key/value pairs in this hash
// that aren't distributed with the program.
   $extras;
   while (list($key,$value) = each($theme)) {
      if (!ereg("-$key-",$knownkeys)) {
         $extras .= "\$theme[$key] = '$theme[$key]'\n";
      }
   }
   $extras = chop ($extras);

   echo "$extras</textarea>
    <br><br>
    <input type=submit value=\"Update theme.inc.php\" class=\"buttons\">
    </form>
  ";
  $html -> close_table();
  $html -> send_admin_footer();


?>
