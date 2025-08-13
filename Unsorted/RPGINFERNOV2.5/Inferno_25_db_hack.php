<html>
<head>
<title>RPG Inferno Installler</title>
</head>
<body>
Preparing To Install RPG Inferno v2.5<br>
Starting Install<br>
<?php
// Require the configuration
require "./conf_global.php";

// Connect to the database
$INFO['sql_driver'] = !$INFO['sql_driver'] ? 'mySQL' : $INFO['sql_driver'];

$to_require = "./sources/Drivers/".$INFO['sql_driver'].".php";
require ($to_require);

$DB = new db_driver;

$DB->obj['sql_database']     = $INFO['sql_database'];
$DB->obj['sql_user']         = $INFO['sql_user'];
$DB->obj['sql_pass']         = $INFO['sql_pass'];
$DB->obj['sql_host']         = $INFO['sql_host'];
$DB->obj['sql_tbl_prefix']   = $INFO['sql_tbl_prefix'];

// Connect Now
echo('Connecting To Database<br>');
$DB->connect();
echo('Connection Complete - Update Tables<br>');
$install=Array();


$install[] = "alter table `ibf_members`
add rpgname text NOT NULL,
add rpgrace text NOT NULL,
add rpgav text NOT NULL,
add money int(15) NOT NULL default '0',
add item1 text NOT NULL,
add item2 text NOT NULL,
add item3 text NOT NULL,
add item4 text NOT NULL,
add item5 text NOT NULL,
add hp int(15) NOT NULL default '100',
add hpm int(15) NOT NULL default '100',
add mp int(15) NOT NULL default '50',
add mpm int(15) NOT NULL default '50',
add def int(15) NOT NULL default '30',
add str int(15) NOT NULL default '30',
add exp int(15) NOT NULL default '0',
add level int(15) NOT NULL default '1',
add inbattle int(15) NOT NULL default '0',
add vics int(15) NOT NULL default '0',
add loss int(15) NOT NULL default '0',
add heal1 text NOT NULL,
add heal2 text NOT NULL,
add heal3 text NOT NULL,
add rpgsex text NOT NULL,
add smove text NOT NULL,
add rpgelement text NOT NULL,
add align text NOT NULL,
add bankmoney int(15) NOT NULL default '0',
add lastvisit int(15) NOT NULL default '0',
add inclan text NOT NULL,
add claninv text NOT NULL,
add uitem1 text NOT NULL,
add uitem2 text NOT NULL,
add rage int(15) NOT NULL default '0',
add equip text NOT NULL;";
$install[] = "UPDATE ibf_members SET hp='100',
hpm='100',
mp='50',
mpm='50',
def='30',
str='30';";
echo('Tables Updated - Inserting New Tables<br>');
$install[] = "CREATE TABLE `ibf_infernoshop` (
  `id` int(11) NOT NULL auto_increment,
  `img` text NOT NULL,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `cost` int(11) NOT NULL default '0',
  `hp` int(11) NOT NULL ,
  `mp` int(11) NOT NULL ,
  `def` int(11) NOT NULL ,
  `str` int(11) NOT NULL ,
  `type` text NOT NULL,
  `sold` int(11) NOT NULL default '0',

  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_infernoclan` (
  `id` int(11) NOT NULL auto_increment,
  `img` text NOT NULL,
  `name` text NOT NULL,
  `leader` text NOT NULL,
  `leaderid` text NOT NULL,
  `totalm` text NOT NULL,

  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_infernousershop` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(11) NOT NULL,
  `img` text NOT NULL,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `cost` int(11) NOT NULL default '0',
  `hp` int(11) NOT NULL ,
  `mp` int(11) NOT NULL ,
  `def` int(11) NOT NULL ,
  `str` int(11) NOT NULL ,

  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_infernousershopd` (
  `id` int(11) NOT NULL auto_increment,
  `oid` text NOT NULL,
  `oname` text NOT NULL,
  `logo` text NOT NULL,
  `name` text NOT NULL,
  `desc` text NOT NULL,

  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_usershopop` (
  `id` int(11) NOT NULL auto_increment,
  `cost` text NOT NULL,

  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_races` (
  `id` int(11) NOT NULL auto_increment,
  `race` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_rpgelements` (
  `id` int(11) NOT NULL auto_increment,
  `element` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_rpgoptions` (
  `id` int(11) NOT NULL auto_increment,
  `intrest` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_clanoptions` (
  `id` int(11) NOT NULL auto_increment,
  `cost` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_infernobattle` (
  `id` int(11) NOT NULL auto_increment,
  `type` text NOT NULL,
  `p1` text NOT NULL,
  `p2` text NOT NULL,
  `turn` text NOT NULL ,
  `background` text NOT NULL ,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_infernoscene` (
  `id` int(11) NOT NULL auto_increment,
  `img` text NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_infernoreturn` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_infernoheal` (
  `id` int(11) NOT NULL auto_increment,
  `img` text NOT NULL,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `cost` int(11) NOT NULL default '0',
  `hp` int(11) NOT NULL ,
  `mp` int(11) NOT NULL ,
  `sold` int(11) NOT NULL default '0',

  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] = "CREATE TABLE `ibf_infernosprite` (
  `id` int(11) NOT NULL auto_increment,
  `img` text NOT NULL,

  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";

$install[] = "CREATE TABLE `ibf_infernocat` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";

echo('Tables Inserted - Inserting Values<br>');
$install[] = "insert into ibf_usershopop values (1,'5000');";
$install[] = "insert into ibf_races values ('','Human');";
$install[] = "insert into ibf_races values ('','Warrior');";
$install[] = "insert into ibf_rpgelements values ('','Fire');";
$install[] = "insert into ibf_rpgelements values ('','Ice');";
$install[] = "insert into ibf_rpgelements values ('','Water');";
$install[] = "insert into ibf_rpgelements values ('','Earth');";
$install[] = "insert into ibf_rpgelements values ('','Light');";
$install[] = "insert into ibf_rpgelements values ('','Dark');";
$install[] = "insert into ibf_rpgelements values ('','Wind');";
$install[] = "insert into ibf_rpgelements values ('','Nuclear');";
$install[] = "insert into ibf_rpgelements values ('','Unknown');";
$install[] = "insert into ibf_rpgoptions values ('','10');";
$install[] = "insert into ibf_clanoptions values ('','5000');";

$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (1, 'Light Attack', 'Attack');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (2, 'Rage Rush', 'Attack');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (3, 'Arcane Swing', 'Spell');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (4, 'Arcane Crush', 'Spell');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (5, 'Madness Hero', 'Spell');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (6, 'Double Slash', 'Attack');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (7, 'Corval Punishment', 'Attack');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (8, 'Master Fists', 'Attack');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (9, 'Plosmos Heat', 'Spell');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (10, 'Kai Kick', 'Attack');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (11, 'Vortex Swirl', 'Spell');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (12, 'Grave Digger', 'Spell');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (13, 'Lassion Thrust', 'Attack');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (14, 'Mordel Lapith', 'Spell');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (15, 'Demon Hook', 'Attack');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (16, 'Digital Dust', 'Spell');";
$install[] = "INSERT INTO `ibf_infernoreturn` VALUES (17, 'Fury', 'Attack');";

$install[] = "INSERT INTO `ibf_infernosprite` VALUES ('', 'RageP.gif');";
$install[] = "INSERT INTO `ibf_infernosprite` VALUES ('', 'RageX.gif');";
$install[] = "INSERT INTO `ibf_infernosprite` VALUES ('', 'earthbreaker.gif');";
$install[] = "INSERT INTO `ibf_infernosprite` VALUES ('', 'Bot.gif');";
$install[] = "INSERT INTO `ibf_infernosprite` VALUES ('', 'protofire.gif');";
$install[] = "INSERT INTO `ibf_infernosprite` VALUES ('', 'Player.gif');";

$install[] = "INSERT INTO `ibf_infernoscene` VALUES (1, 'icecave.gif', 'Ice Cave');";
$install[] = "INSERT INTO `ibf_infernoscene` VALUES (2, 'canyon.gif', 'Canyon');";
$install[] = "INSERT INTO `ibf_infernoscene` VALUES (3, 'cave.gif', 'Cave');";
$install[] = "INSERT INTO `ibf_infernoscene` VALUES (4, 'army.gif', 'Army Base');";
$install[] = "INSERT INTO `ibf_infernoscene` VALUES (5, 'arena.gif', 'Arena');";
$install[] = "INSERT INTO `ibf_infernoscene` VALUES (6, 'bfield.gif', 'Snow Field');";

$install[] = "INSERT INTO `ibf_infernocat` VALUES (1, 'Armour', 'Buy Protective Gear For Your Battles');";
$install[] = "INSERT INTO `ibf_infernocat` VALUES (2, 'Items', 'Use spells to help you fight against your opponent');";
$install[] = "INSERT INTO `ibf_infernocat` VALUES (3, 'Weapons', 'Equip yourself with weapons to fight to the end');";


$install[] = "alter table ibf_members
add `equip2` text not null,
add `equip3` text not null,
drop uitem1,
drop uitem2,
drop item1,
drop item2,
drop item3,
drop item4,
drop item5,
add job text NOT NULL,
add last_jpay int(11) NOT NULL,
add `summon` text not null,
add `rpaw` int(11) not null,
add `rpah` int(11) not null;";
$install[] = "alter table ibf_rpgoptions
add `treply` int(11) not null,
add `tnew` int(11) not null,
add `tquote` int(11) not null,
add `tpoll` int(11) not null;";

$install[] ="update ibf_rpgoptions
set treply='5',tquote='5',tnew='10',tpoll='10';";
$install[] ="alter table ibf_rpgoptions
add `itemshopon` text not null,
add `battleon` text not null,
add `bankon` text not null,
add `transferon` text not null,
add `healingon` text not null,
add `clanon` text not null,
add `rpgstatson` text not null,
add `lotteryon` text not null,
add `storeon` text not null,
add `helpeon` text not null,
add `rpaw` int(11) not null,
add `rpah` int(11) not null,
add `rpgjobon` text not null;";
$install[] ="drop table ibf_infernoreturn;";
$install[] ="drop table ibf_infernosprite;";
$install[] ="update ibf_members
set equip='',equip2='',equip3='';";
$install[] ="alter table ibf_infernoshop
add `stock` int(11) not null,
add `lvlre` int(11) not null;";
$install[] ="drop table ibf_infernousershop;";
$install[] ="drop table ibf_infernousershopd;";
$install[] ="update ibf_infernoshop set stock='30';";
$install[] ="alter table ibf_infernobattle
add `verify` text not null,
add `verby` text not null,
add `vs` text not null;";
$install[] ="update ibf_rpgoptions
set rpaw='100',rpah='100';";


echo('Tables Updated - Inserting New Tables<br>');

$install[] ="CREATE TABLE `ibf_battlelog` (
  `id` int(11) NOT NULL auto_increment,
  `bid` int(11) NOT NULL,
  `attacker` text NOT NULL,
  `opponent` text NOT NULL,
  `move` text NOT NULL,
  `hpl` text NOT NULL,
  `mpl` text NOT NULL,
  `dmg` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] ="CREATE TABLE `ibf_lottery` (
  `id` int(11) NOT NULL auto_increment,
  `prize` int(11) NOT NULL,
  `n1` int(11) NOT NULL,
  `n2` int(11) NOT NULL,
  `n3` int(11) NOT NULL,
  `n4` int(11) NOT NULL,
  `n5` int(11) NOT NULL,
  `tcost` int(11) NOT NULL,
  `state` text NOT NULL,
  `name` text NOT NULL,
  `descr` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] ="CREATE TABLE `ibf_tickets` (
  `id` int(11) NOT NULL auto_increment,
  `mid` int(11) NOT NULL,
  `n1` int(11) NOT NULL,
  `n2` int(11) NOT NULL,
  `n3` int(11) NOT NULL,
  `n4` int(11) NOT NULL,
  `n5` int(11) NOT NULL,
  `lname` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] ="CREATE TABLE `ibf_infernostock` (
  `i_id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  PRIMARY KEY  (`i_id`)
) TYPE=MyISAM;";
$install[] ="CREATE TABLE `ibf_infernoreturn` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `img` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] ="CREATE TABLE `ibf_infernostore` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `descr` text NOT NULL,
  `img` text NOT NULL,
  `cost` int(11) NOT NULL,
  `rfile` text NOT NULL,
  `stock` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] ="CREATE TABLE `ibf_infernohelp` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `descr` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] ="CREATE TABLE `ibf_infernologs` (
  `id` int(11) NOT NULL auto_increment,
  `log` text NOT NULL,
  `type` text NOT NULL,
  `time` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] ="CREATE TABLE `ibf_infernosummon` (
  `id` int(11) NOT NULL auto_increment,
  `img` text NOT NULL,
  `name` text NOT NULL,
  `mp` int(11) NOT NULL,
  `lvl` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";
$install[] ="CREATE TABLE `ibf_infernojobs` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `icon` text NOT NULL,
  `desc` text NOT NULL,
  `salary` int(11) NOT NULL default '0',
  `sinterval` int(6) NOT NULL default '0',
  `lastpay` int(11) default '0',
  `lvl` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;";

echo('Tables Inserted - Add In Some Stuff Eh :D<br>');

$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (1, 'Arcane Swing', 'Attack', 'Player.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (2, 'Kick Rush', 'Attack', 'RageX.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (3, 'Lion Output', 'Attack', 'RageP.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (4, 'Earth Breaker', 'Attack', 'earthbreaker.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (5, 'Ex-Blast', 'Spell', 'akari-ex-blast.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (6, 'Wind Slash', 'Spell', 'Bot.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (7, 'Fire Fists', 'Spell', 'kyo-3hit.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (8, 'Roundhouse Kick', 'Attack', 'angel-spinhk.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (9, 'Warp Kick', 'Attack', 'billy-rgkick.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (10, 'Plasma Cannon', 'Spell', 'protofire.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (11, 'Triad Blaster', 'Spell', 'x2zeroani.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (12, 'Brain Buster', 'Spell', 'sf-akuma2.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (13, 'Double Slash', 'Spell', 'Player.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (14, 'Mid-Kick', 'Attack', 'mai-anotherkick.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (15, 'Card Trick', 'Attack', 'cardtrick.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (16, 'Fist Fury', 'Attack', 'fist_fury.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (17, 'X Slash', 'Spell', 'xslash.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (18, 'Royal Flush', 'Attack', 'royalflush.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (19, 'Hyper Kick', 'Attack', 'hyper_kick.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (20, 'Plasma Blast', 'Spell', 'plasma_blast.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (21, 'Card Toss', 'Attack', 'cardtoss.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (22, 'Rock Punch', 'Attack', 'rockpunch.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (23, 'Spinning Cane', 'Attack', 'spinning_cane.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (24, 'Hammer Fist', 'Attack', 'hammer_fist.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (25, 'Pick Axe', 'Attack', 'pick_axe.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (26, 'Duel Stab', 'Spell', 'duel_stab.gif');";
$install[] ="INSERT INTO `ibf_infernoreturn` VALUES (27, 'Head Stomp', 'Attack', 'head_stomp.gif');";

$install[] ="INSERT INTO `ibf_infernojobs` VALUES (36, 'Chemist', 'MChemist.gif', 'Serve Public Medication', 20, 86400, 1071528601, 2);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (37, 'Knight', 'MHolyKnight.gif', 'Protect And Serve', 150, 604800, 1071569961, 8);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (38, 'Captain', 'FCaptain.gif', 'Lead The Town In Peace', 350, 604800, 1071570001, 20);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (39, 'Ninja', 'MNinja.gif', 'Stealthy Missions', 50, 86400, 1071571316, 4);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (40, 'Geomancer', 'MGeomancer.gif', 'High Classed Knight', 450, 604800, 1071571361, 27);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (41, 'Monk', 'FMonk.gif', 'Uphold Religion', 15, 86400, 1071577005, 0);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (42, 'Black Smith', 'MBlackSmith.gif', 'Create hand held weapons', 300, 604800, 1071577198, 10);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (43, 'Dragoon', 'FDragoon.gif', 'Fight Off Demons', 500, 604800, 1071577470, 24);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (45, 'Sorceress&#39;s Knight', 'MCaptain.gif', 'Defend the Sorceress', 100, 86400, 1071579971, 35);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (46, 'DarK lord', 'MSoldier.gif', 'Dreams Of World Domination', 900, 604800, 1071605456, 45);";
$install[] ="INSERT INTO `ibf_infernojobs` VALUES (47, 'Mage', 'MMage.gif', 'A Master Of Magic', 150, 604800, 1071605884, 10);";
$install[] ="INSERT INTO `ibf_infernohelp` VALUES (1, 'Battle Ground Help', 'Run Through Of Battle Ground Features', '----------------<br>Problems<br>----------------<br>Im attacking but its not hurting them or changing turn?<br>Then the opponenet has not verified battle yet, they must do that befoe you are allowed to attack<br><br>What is Verify Battle?<br>Verify Battle is a simple feature to allow the opponent to accept/discard a battle challenge from whoever that maybe<br><br>What is my damage?<br>Depends on your current str, the higher it is, the higher dmg you can do<br>');";
$install[] ="INSERT INTO `ibf_infernohelp` VALUES (2, 'Lottery Help', 'How The Lottery System Works', 'When an admin adds a lottery, he choose&#39;s 5 numbers, between 1 and 40, then when you buy a ticket, you choose 5 numbers, between 1 and 40. (Failure to comply with limits will result in losing)<br>Check numbers is really validating your ticket, to check your numbers, you may only check numbers if lottery state is &#39;drawn&#39;.<br>Uually a date is specified for the lottery, just be around at that time to check your numbers.<br><br>-----------<br>Problems<br>-----------<br>I keep getting 0%?<br>Well that can be of 2 cause&#39;s, one you didn&#39;t buy a ticket for that lottery, or two, you didn&#39; match any numbers, just make sure you input numbers between the limits');";
$install[] ="INSERT INTO `ibf_infernohelp` VALUES (3, 'Healing Center Help', 'How The Healing Center Works', 'Users are able to stock maximum 3 healings, they can use them anytime.<br>But, during battle you cannot buy more healing, only use your current ones, so make sure your stocked up&#33;<br>Also, if you need to be revied, just use a healing of any kind.');";
$install[] ="INSERT INTO `ibf_infernohelp` VALUES (4, 'Summons In Battle', 'Summons Explained', 'Summons are a new way of attacking your opponent, but they use up MP<br>To equip a summon, go to your RPG CP, and choose from what are listed, as your levels increase, more summons become available to choose from, the more MP they require, the more damage they do');";


$install[] ="ALTER TABLE `ibf_infernocat` CHANGE `id` `cid` INT( 11 ) NOT NULL AUTO_INCREMENT";
$install[] ="ALTER TABLE `ibf_infernocat` CHANGE `name` `cname` TEXT NOT NULL";
$install[] ="CREATE TABLE `ibf_infernoequip` (
  `eid` int(11) NOT NULL auto_increment,
  `eitem` int(11) NOT NULL,
  `eowner` int(11) NOT NULL,
  `ecat` int(11) NOT NULL,
  PRIMARY KEY  (`eid`)
) TYPE=MyISAM;";
$install[] ="alter table ibf_members
drop equip,
drop equip2,
drop equip3;";

echo('Inserts Complete<br>');
foreach ($install AS $this_query){
$DB->query($this_query);
}
$DB->close_db();
echo('Install Complete - REMOVE FILE NOW!!! <- Yeh, what he said');
?>
</body>
</html>