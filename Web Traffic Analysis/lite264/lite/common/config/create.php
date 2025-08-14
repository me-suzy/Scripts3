<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: create.php
// Source: http://www.actualscripts.com/
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

//===================================================================
function newtotal($link) {
  global $err;
  // Create the table aa_total.
$request=<<< NEWTOTAL
CREATE TABLE aa_total
(
 time TINYINT(1) UNSIGNED NOT NULL,
 id TINYINT(1) UNSIGNED NOT NULL,
 visitors INT(4) UNSIGNED NOT NULL,
 hosts INT(4) UNSIGNED NOT NULL,
 hits INT(4) UNSIGNED NOT NULL,
 INDEX(time,id)
)
NEWTOTAL;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newtotal|the request \'create table aa_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newdays($link) {
  global $err;
  // Create the table aa_days.
$request=<<< NEWDAYS
CREATE TABLE aa_days
(
 time SMALLINT(2) UNSIGNED NOT NULL,
 id TINYINT(1) UNSIGNED NOT NULL,
 visitors_t INT(4) UNSIGNED NOT NULL,
 visitors_m INT(4) UNSIGNED NOT NULL,
 visitors_w INT(4) UNSIGNED NOT NULL,
 hosts INT(4) UNSIGNED NOT NULL,
 hits INT(4) UNSIGNED NOT NULL,
 INDEX(time,id)
)
NEWDAYS;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newdays|the request \'create table aa_days\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newhours($link) {
  global $err;
  // Create the table aa_hours.
$request=<<< NEWHOURS
CREATE TABLE aa_hours
(
 time SMALLINT(2) UNSIGNED NOT NULL,
 id TINYINT(1) UNSIGNED NOT NULL,
 visitors MEDIUMINT(3) UNSIGNED NOT NULL,
 hosts MEDIUMINT(3) UNSIGNED NOT NULL,
 hits MEDIUMINT(3) UNSIGNED NOT NULL,
 INDEX(time,id)
)
NEWHOURS;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newhours|the request \'create table aa_hours\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newpg($link) {
  global $err;
  // Create the table aa_pages.
$request=<<< NEWPG
CREATE TABLE aa_pages
(
 id TINYINT(1) UNSIGNED NOT NULL,
 uid INT(4) UNSIGNED NOT NULL,
 ident CHAR(255) NOT NULL,
 name CHAR(255) NOT NULL,
 url CHAR(255) NOT NULL,
 imgid TINYINT(1) UNSIGNED NOT NULL,
 flags TINYINT(1) UNSIGNED NOT NULL,
 rgb MEDIUMINT(3) UNSIGNED NOT NULL,
 defpg TINYINT(1) UNSIGNED NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 first_t INT(4) UNSIGNED NOT NULL,
 last_t INT(4) UNSIGNED NOT NULL,
 vmin INT(4) UNSIGNED NOT NULL,
 vmax INT(4) UNSIGNED NOT NULL,
 hsmin INT(4) UNSIGNED NOT NULL,
 hsmax INT(4) UNSIGNED NOT NULL,
 htmin INT(4) UNSIGNED NOT NULL,
 htmax INT(4) UNSIGNED NOT NULL,
 rmin INT(4) UNSIGNED NOT NULL,
 rmax INT(4) UNSIGNED NOT NULL,
 PRIMARY KEY(id,uid),
 INDEX(ident(20)),
 INDEX(url(20)),
 INDEX(added)
)
NEWPG;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newpg|the request \'create table aa_pages\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newgr($link) {
  global $err;
  // Create the table aa_groups.
$request=<<< NEWGR
CREATE TABLE aa_groups
(
 id TINYINT(1) UNSIGNED NOT NULL PRIMARY KEY,
 flags1 INT(4) UNSIGNED NOT NULL,
 flags2 INT(4) UNSIGNED NOT NULL,
 flags3 INT(4) UNSIGNED NOT NULL,
 flags4 INT(4) UNSIGNED NOT NULL,
 flags5 INT(4) UNSIGNED NOT NULL,
 flags6 INT(4) UNSIGNED NOT NULL,
 flags7 INT(4) UNSIGNED NOT NULL,
 name CHAR(255) NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 first_t INT(4) UNSIGNED NOT NULL,
 last_t INT(4) UNSIGNED NOT NULL,
 vmin INT(4) UNSIGNED NOT NULL,
 vmax INT(4) UNSIGNED NOT NULL,
 hsmin INT(4) UNSIGNED NOT NULL,
 hsmax INT(4) UNSIGNED NOT NULL,
 htmin INT(4) UNSIGNED NOT NULL,
 htmax INT(4) UNSIGNED NOT NULL,
 rmin INT(4) UNSIGNED NOT NULL,
 rmax INT(4) UNSIGNED NOT NULL,
 INDEX(added)
)
NEWGR;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newgr|the request \'create table aa_groups\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newip($link) {
  global $err;
  // Create the table aa_hosts.
$request =<<< NEWIP
CREATE TABLE aa_hosts
(
 ip INT(4) NOT NULL PRIMARY KEY,
 flags1 INT(4) UNSIGNED NOT NULL,
 flags2 INT(4) UNSIGNED NOT NULL,
 flags3 INT(4) UNSIGNED NOT NULL,
 flags4 INT(4) UNSIGNED NOT NULL,
 flags5 INT(4) UNSIGNED NOT NULL,
 flags6 INT(4) UNSIGNED NOT NULL,
 flags7 INT(4) UNSIGNED NOT NULL
)
NEWIP;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newip|the request \'create table aa_hosts\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newrb($link) {
  global $err;
  // Create the table aa_ref_base.
$request =<<< NEWRB
CREATE TABLE aa_ref_base
(
 refid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 flag TINYINT(1) UNSIGNED NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 count INT(4) UNSIGNED NOT NULL,
 url VARCHAR(255) NOT NULL,
 INDEX (url(40)),
 INDEX (count)
)
NEWRB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newrb|the request \'create table aa_ref_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newdm($link) {
  global $err;
  // Create the table aa_ref_base.
$request =<<< NEWDM
CREATE TABLE aa_domains
(
 domid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 domain CHAR(60) NOT NULL,
 INDEX (domain(10))
)
NEWDM;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newrb|the request \'create table aa_domains\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newtmp($link) {
  global $err;
  // Create the table aa_ref_base.
$request =<<< NEWTMP
CREATE TABLE aa_tmp
(
 refid SMALLINT(2) UNSIGNED NOT NULL,
 visitors INT(4) UNSIGNED NOT NULL,
 hosts INT(4) UNSIGNED NOT NULL,
 hits INT(4) UNSIGNED NOT NULL,
 reloads INT(4) UNSIGNED NOT NULL
)
NEWTMP;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newtmp|the request \'create table aa_tmp\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newrt($link) {
  global $err;
  // Create the table aa_ref_total.
$request=<<< NEWRT
CREATE TABLE aa_ref_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 refid SMALLINT(2) UNSIGNED NOT NULL,
 domid SMALLINT(2) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,refid)
)
NEWRT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newrt|the request \'create table aa_ref_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newrdata($link) {
  global $err;
  // Create the table  aa_rdata.
$request=<<<NEWRDATA
CREATE TABLE  aa_rdata
(
 id INT(4) UNSIGNED NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 num TINYINT(1) UNSIGNED NOT NULL,
 name CHAR(255) NOT NULL,
 addpar CHAR(255) NOT NULL,
 vi INT(4) NOT NULL,
 vp FLOAT(10,2) NOT NULL,
 v INT(4) UNSIGNED NOT NULL,
 hsi INT(4) NOT NULL,
 hsp FLOAT(10,2) NOT NULL,
 hs INT(4) UNSIGNED NOT NULL,
 ri INT(4) NOT NULL,
 rp FLOAT(10,2) NOT NULL,
 r INT(4) UNSIGNED NOT NULL,
 hti INT(4) NOT NULL,
 htp FLOAT(10,2) NOT NULL,
 ht INT(4) UNSIGNED NOT NULL,
 INDEX(added,id)
)
NEWRDATA;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newrdata|the request \'create table aa_rdata\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newconfdb($link) {
  global $err;
  // Create the table  aa_confdb.
$request=<<<NEWCONFDB
CREATE TABLE  aa_confdb
(
 var CHAR(25) NOT NULL,
 val CHAR(255) NOT NULL
)
NEWCONFDB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newconfdb|the request \'create table aa_confdb\' has failed -- '.mysql_error());return;}
}
//===================================================================

?>