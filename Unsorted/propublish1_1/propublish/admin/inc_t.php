<?
session_start();

require ("set_inc.php"); 
include_once("language/$lang");	 

if ($override <> 1)
{
include "../header.php";
include "menu_inc.php";
}

include "db.php";
include "auth.php";


?>
