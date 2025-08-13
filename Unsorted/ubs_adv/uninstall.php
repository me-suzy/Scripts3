<?
require "functions.php";

$db_connect->query("DROP TABLE `db_arena1`");
$db_connect->query("DROP TABLE `db_arena2`");
$db_connect->query("DROP TABLE `db_arena3`");
$db_connect->query("DROP TABLE `db_arena4`");
$db_connect->query("DROP TABLE `db_arena5`");
$db_connect->query("DROP TABLE `db_arena6`");
$db_connect->query("DROP TABLE `db_arena7`");
$db_connect->query("DROP TABLE `db_arena8`");
$db_connect->query("DROP TABLE `db_arena9`");
$db_connect->query("DROP TABLE `db_arena10`");
$db_connect->query("DROP TABLE `db_battle_classes`");
$db_connect->query("DROP TABLE `db_bbcode`");
$db_connect->query("DROP TABLE `db_configuration`");
$db_connect->query("DROP TABLE `db_groups`");
$db_connect->query("DROP TABLE `db_levels`");
$db_connect->query("DROP TABLE `db_lobby_table`");
$db_connect->query("DROP TABLE `db_object2user`");
$db_connect->query("DROP TABLE `db_shop_armour`");
$db_connect->query("DROP TABLE `db_shop_boots`");
$db_connect->query("DROP TABLE `db_shop_gloves`");
$db_connect->query("DROP TABLE `db_shop_helmet`");
$db_connect->query("DROP TABLE `db_shop_shields`");
$db_connect->query("DROP TABLE `db_shop_smallitems`");
$db_connect->query("DROP TABLE `db_shop_specialitem`");
$db_connect->query("DROP TABLE `db_shop_swords`");
$db_connect->query("DROP TABLE `db_style`");
$db_connect->query("DROP TABLE `db_useronline`");
$db_connect->query("DROP TABLE `db_users`");
$db_connect->query("DROP TABLE `db_announcements`");

echo "Un-installation complete!";
?>