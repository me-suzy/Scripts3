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
  $theme['stylesheet'] =	"infopop";

// What style sheets do you want to make available to your users
// Format:	Filename without the .css extension: Description
//     ie:	'stylesheet:The default style sheet,
//   		 stylesheet2:Large font style sheet';
    $theme['availablestyles'] = "blackandblue:Black and blue,
				infopop:Infopop,
				largetheblues:the blues (large fonts),
				stylesheet:wild,
				stylesheet2:desert,
				stylesheet3:wild (large fonts),
				stylesheet4:desert (large fonts),
				theblues:the blues";

// Table cellpadding
  $theme['cellpadding']=	3;

// Table cellspacing
  $theme['cellspacing'] =	1;

// Default color for admin usernames
  $theme['admincolor']	=	"#FF0000";

// Default color for moderator usernames
  $theme['modcolor']	=	"#00AA00";

// Table width
// Percentage or integer
  $theme['tablewidth']	=	"95%";

// Default listing to flat or threaded mode
  $theme['postlist']	=	"flat";

// Default posts and reply listing to collapsed or expanded
  $theme['threaded']	=	"collapsed";

// Default sort order
// 1 - Descending Subject
// 2 - Ascending Subject
// 3 - Descdending Poster
// 4 - Ascending Poster
// 5 - Descending Date
// 6 - Ascending Date    
  $theme['sort']	=	"5";

// Default number of topics per page
  $theme['postsperpage']=	"10";

// Defrault number of posts to be shown on a single page when viewing a
// thread in flat mode
  $theme['flatposts']	=	"10";

// Allow users to add a picture to their profile
  $theme['PictureView']=	1;

// If allowing users to add pictures, what is the maximum width allowed
  $theme['PictureWidth'] =	80;

// If allowing users to add pictures, what is the maximum width allowed
  $theme['PictureHeight'] =	80;

// Show user's pictures with their posts
  $theme['PicturePosts']=	1;

// Default textarea columns
  $theme['TextCols']	=	"60";

// Default Textarea rows
  $theme['TextRows']	=	"5";

// Which Time format to use
// short1 = 'MM/DD/YY 10:19 AM';
// short2 = 'DD/MM/YY 10:19 AM';
// short3 = 'YY/MM/DD 10:19 AM';
// short4 = 'YYYY/MM/DD 22:43";
  $theme['timeformat']	=	"short1";

?>
