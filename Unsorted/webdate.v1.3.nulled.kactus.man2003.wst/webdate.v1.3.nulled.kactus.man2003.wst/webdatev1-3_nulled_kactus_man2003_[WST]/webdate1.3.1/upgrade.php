<?
require "engine/load_configuration.pml";
$db = c();
q("ALTER TABLE `dt_photos` ADD `filename_6` VARCHAR(255) NOT NULL"); 
q("ALTER TABLE `dt_members` CHANGE `email` `email` VARCHAR(255) DEFAULT NULL");
q("ALTER TABLE `dt_prepared_members` CHANGE `email` `email` VARCHAR(255) DEFAULT NULL");
d($db);
?>

Database updated successfully.
