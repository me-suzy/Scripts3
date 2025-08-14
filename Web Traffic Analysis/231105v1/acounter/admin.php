<?php

//////////////////////////////////////////////////////////////////////////
// Script:        admin.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

require("./config/config.php");
require("./modules/funct.php");
require("./modules/base/base.php");
require("./modules/base/accbase.php");
require("./modules/admin/setting.php");
require("./modules/template/template.php");
require("./modules/image/gif.php");
require("./modules/view/acclist.php");

$ENameCoF='';

$Fun = new Funct();

$Conf = new Config($Fun);
$Conf->ConfigInit();
if($Conf->CodeError) $Fun->Error($Conf->CodeError,1);

if(isset($_GET['picture']))
    {
     if($Conf->Version>=2) $Picture = new GIF($Conf,$Fun,$Conf->FullBasePath.'images/blank.txt',$Conf->FullBasePath.'v2/data/color.dat');
     else $Picture = new GIF($Conf,$Fun,$Conf->FullBasePath.'images/blank.txt',$Conf->FullBasePath.'data/color.dat');
     $Picture->GIFInit();
     if($Picture->CodeError) $Fun->Error($Picture->CodeError,1);
     $Picture->SetStatistic(123456,123,12);
     if($Picture->CodeError) $Fun->Error($Picture->CodeError,1);
     $Picture->Coding ();
     $Picture->Output ($_GET['picture']);
     exit;               
    }

//if($Conf->Version>=2)
//    {
     $Fun->Auth($Conf,'admin.php',0);
     if($Fun->CodeError) $Fun->Error($Fun->CodeError,1);
//    }

if($Conf->Version>=2)
    {
     require("./v2/modules/admin/extset.php");
    }

$Set= new Setting($Fun,$Conf);
$Set->SettingInit();
if($Set->CodeError) $Fun->Error($Set->CodeError,1);
$Set->OutSetting();
if($Set->CodeError) $Fun->Error($Set->CodeError,1);

?>