<?php

//Slide gallery variables
$col = 3; //no. of columns in a page
$maxrow = 2; //no. of rows in a page
$dir="."; //directory for this script, no need to change
$thumb = true ; //setting it to TRUE will generate real thumbnails on-the-fly, supports jpg file only and requires GD library. Setting it to FALSE will resize the original file to fit the thumbnail size, long download time. Turn it off if thumbnails don't show properly.
$place = "."; //directory of the slide mount images, no need to change

//Upload/Delete Module variables
$LOGIN = "admin";
$PASSWORD = "admin";
$abpath = "/usr/local/apache/vhosts"; //Absolute path to where images are uploaded. No trailing slash
$sizelim = "no"; //Size limit, yes or no
$size = "2500000"; //Size limit if there is one
$number_of_uploads = 5;  //Maximum number of uploads in one time

?>