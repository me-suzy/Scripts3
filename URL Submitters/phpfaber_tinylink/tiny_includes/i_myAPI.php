<?php

// -----------------------------------------------------------------------------
//
// phpFaber TinyLink v.1.0
// Copyright(C), phpFaber LLC, 2004-2005, All Rights Reserved.
// E-mail: products@phpfaber.com
//
// All forms of reproduction, including, but not limited to, internet posting,
// printing, e-mailing, faxing and recording are strictly prohibited.
// One license required per site running phpFaber TinyLink.
// To obtain a license for using phpFaber TinyLink, please register at
// http://www.phpfaber.com/i/products/tinylink/
//
// 19:59 28.07.2005
//
// -----------------------------------------------------------------------------

function js_alert($str){
  echo "<script>alert('$str');</script>";
}

function js_redirect($page){
  echo "<script>window.location.href='$page'</script>";
}

function js_goback(){
  echo "<script>history.go(-1)</script>";
}

function make_seed(){
  list($usec,$sec) = explode(" ", microtime());
  return ((float)$sec+(float)$usec) * 100000;
}

function randomPassword($length){
  mt_srand(make_seed());
  $possible = '0123456789!@#$%^&*()_+' .
        'abcdefghjiklmnopqrstuvwxyz' .
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str = "";
  while (strlen($str) < $length) {
    $str .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
  }
  return($str);
}

function randomLink($length){
  mt_srand(make_seed());
  $possible = '0123456789' .
        'abcdefghjiklmnopqrstuvwxyz' .
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str = "";
  while(strlen($str) < $length){
    $str .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
  }
  return($str);
}

?>