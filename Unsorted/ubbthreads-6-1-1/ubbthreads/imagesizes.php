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

// If you wish to use static image size, comment out each call to 
// GetImageSize and replace $size[3] with the width and height of that image
// ie. $images[descend] = "height=\10\" width=\"10\"";

// -----------------------------------------------------
// Get the image sizes and put the results into an array
//   $size = GetImageSize ("{$config['imagepath']}/descend.gif");
//   $images['descend'] = $size['3'];
   $images['descend'] = "width=\"7\" height=\"8\"";

//   $size = GetImageSize ("{$config['imagepath']}/newpost.gif");
//   $images['newpost'] = $size['3'];
   $images['newpost'] = "width=\"13\" height=\"15\"";

//   $size = GetImageSize ("{$config['imagepath']}/previous.gif");
//   $images['previous'] = $size['3'];
   $images['previous'] = "width=\"12\" height=\"15\"";

//   $size = GetImageSize ("{$config['imagepath']}/all.gif");
//   $images['all'] = $size['3'];
   $images['all'] = "width=\"19\" height=\"15\"";

//   $size = GetImageSize ("{$config['imagepath']}/next.gif");
//   $images['next'] = $size['3'];
   $images['next'] = "width=\"14\" height=\"15\"";

//   $size = GetImageSize ("{$config['imagepath']}/expand.gif");
//   $images['expand'] = $size['3'];
   $images['expand'] = "width=\"17\" height=\"15\"";

//   $size = GetImageSize ("{$config['imagepath']}/collapse.gif");
//   $images['collapse'] = $size['3'];
   $images['collapse'] = "width=\"17\" height=\"15\"";

//   $size = GetImageSize ("{$config['imagepath']}/icons/book.gif");
//   $images['icons'] = $size['3'];
   $images['icons'] = "width=\"15\" height=\"15\"";

// We only need to know the height for the blank.gif image
// $size = GetImageSize ("{$config['imagepath']blank.gif");
   $images['blank'] = "height = \"11\"";

?>
