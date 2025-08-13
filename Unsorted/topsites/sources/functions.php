<?php
//=================================================================\\
// Aardvark Topsites PHP 4.1.0                                     \\
//-----------------------------------------------------------------\\
// Copyright (C) 2003 Jeremy Scheff - http://www.aardvarkind.com/  \\
//-----------------------------------------------------------------\\
// This program is free software; you can redistribute it and/or   \\
// modify it under the terms of the GNU General Public License     \\
// as published by the Free Software Foundation; either version 2  \\
// of the License, or (at your option) any later version.          \\
//                                                                 \\
// This program is distributed in the hope that it will be useful, \\
// but WITHOUT ANY WARRANTY; without even the implied warranty of  \\
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the   \\
// GNU General Public License for more details.                    \\
//=================================================================\\

function error($message, $killscript, $writetolog) {
  global $TMPL;
  $TMPL['error'] = $message;
  $TMPL['content'] .= do_template("error");
  if ($killscript) {
    build_template_stuff();
    echo do_template("template");
    exit;
  }
  return 1;
}

function parse_form () {
  global $HTTP_GET_VARS, $HTTP_POST_VARS;
  $return = array();
  if(is_array($HTTP_GET_VARS)) {
    while(list($key, $value) = each($HTTP_GET_VARS)) {
      if(is_array($HTTP_GET_VARS[$key])) {
        while(list($key2, $value2) = each($HTTP_GET_VARS)) {
          $return[$key][$key2] = strip($value2);
        }
      }
      else {
        $return[$key] = strip($value);
      }
    }
  }
  if(is_array($HTTP_POST_VARS)) {
    while(list($key, $value) = each($HTTP_POST_VARS)) {
      if(is_array($HTTP_POST_VARS[$key])) {
        while(list($key2, $value2) = each($HTTP_POST_VARS)) {
          $return[$key][$key2] = strip($value2);
        }
      }
      else {
        $return[$key] = strip($value);
      }
    }
  }
  return $return;
}

function strip ($value) {
  $value = str_replace("&#032;", " ", $value );
  $value = str_replace("&", "&amp;", $value );
  $value = str_replace("<!--", "&#60;&#33;--", $value );
  $value = str_replace("-->", "--&#62;", $value );
  $value = preg_replace("/<script/i", "&#60;script", $value );
  $value = str_replace(">", "&gt;", $value );
  $value = str_replace("<", "&lt;", $value );
  $value = str_replace("\"", "&quot;", $value );
  $value = preg_replace("/\|/", "&#124;", $value );
  $value = preg_replace("/\n/", "<br />", $value );
  $value = preg_replace("/\\\$/", "&#036;", $value );
  $value = preg_replace("/\r/", "", $value );
  $value = str_replace("!", "&#33;", $value );
  $value = str_replace("'", "&#39;", $value );
  $value = stripslashes($value);
  $value = preg_replace("/\\\/", "&#092;", $value );
  return $value;
}
?>