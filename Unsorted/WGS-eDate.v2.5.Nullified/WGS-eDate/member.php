<?php
require "settings.php";
require "lib/mysql.lib";
$r = q("select status, rdate from members where id='$auth' and pswd='$pass'");
if(e($r)) header("Location: login.php");
q("update profiles set ldate='".strtotime(date("d M Y H:i:s"))."' where id='$auth'");

$tm0=f(q("select * from members where id='$auth'"));
$tp0=f(q("select * from profiles where id='$auth'"));

if (!$tp0[type]) $tp0[type]=0;
$tm0[type]=$tp0[type];

$tm0pics=$mem_free_pics;
$tm0message=$mem_free_message;
$tm0chat=$mem_free_chat;
$tm0web=0;
$tm0emails=0;
$tm0phone=0;

if ($tm0[type]>=1000) 
{
$tm0pics=$mem_silver_pics;
$tm0message=1;
$tm0chat=1;
$tm0web=$mem_silver_web;
$tm0emails=0;
$tm0phone=0;
};

if ($tm0[type]>=2000) 
{
$tm0pics=$mem_gold_pics;
$tm0message=1;
$tm0chat=1;
$tm0web=1;
$tm0emails=$mem_gold_emails;
$tm0phone=0;
};

if ($tm0[type]>=3000) 
{
$tm0pics=$mem_platinum_pics;
$tm0message=1;
$tm0chat=1;
$tm0web=1;
$tm0emails=1;
$tm0phone=$mem_platinum_phone;
};

require "_header.php";
?>