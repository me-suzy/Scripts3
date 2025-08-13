<?
require "_functions.php";

$db_zugriff->query("ALTER TABLE bb".$n."_user_table ADD isregisteredrpg varchar(16)  DEFAULT 'no' NOT NULL");

echo "Neccessary alterations are done. Delete this file right away!";
?>