<?php
/****************************************************************/
/*                         phpStatus                            */
/*                    functions.php file                        */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                  support@hintondesign.org                    */
/*                                                              */
/* This program is free software. You can redistrabute it and/or*/
/* modify it under the terms of the GNU General Public Licence  */
/* as published by the Free Software Foundation; either version */
/* 2 of the license.                                            */
/*                                                              */
/****************************************************************/
if(eregi("functions.php", $HTTP_SERVER_VARS['PHP_SELF'])) {
   header("Location: ../index.php");
   exit();
}

function hinton_real_path($path)
{
   global $hinton_real_path;
   return (!@function_exists('realpath') || !@realpath($hinton_real_path . 'includes/functions.php')) ? $path : @realpath($path);
}

function replace_quotes($text) {
   $replace = str_replace("'", "\'", $text);
   return $replace;
}   

?>