<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : UserClass.php
 * Desc  : Class representing a user/player in the 
 *       : prediction league.
 ********************************************************/
class User {
  // The name the user is identified by.
  var $userid;

  // The users password.
  var $pwd;

  // The email address of the user.
  var $emailaddr;

  // The URL of the icon to be used by the user.
  var $icon;

  // The priveleges of the user.
  var $usertype;

  // The day the user created the record.
  var $createdate;

  // Flag to indicate whether the user is logged in.
  var $loggedIn;

  // Contructor for the user class.
  function User() {
    $this->userid = "";
    $this->pwd = "";
    $this->emailaddr = "";
    $this->icon = "";
    $this->usertype = "0";
    $this->createdate = "";
    $this->loggedIn = FALSE;
  }
}
?>
