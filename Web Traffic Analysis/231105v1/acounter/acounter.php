<?php

//////////////////////////////////////////////////////////////////////////
// Script:        acounter.php                                                //
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
require("./modules/base/langbase.php");
require("./modules/base/refbase.php");
require("./modules/base/ipsbase.php");
require("./modules/base/accbase.php");
require("./modules/base/langlog.php");
require("./modules/base/reflog.php");
require("./modules/base/ipslog.php");
require("./modules/base/vislog.php");
require("./modules/base/uniqlog.php");
require("./modules/image/gif.php");
require("./modules/count/visitor.php");

$ENameCoF='';

$Time=time();

ignore_user_abort(1);

$Fun = new Funct();

$Conf = new Config($Fun);
$Conf->ConfigInit();
if($Conf->CodeError) $Fun->Error($Conf->CodeError,0);
if($Conf->Version>=2)
    {
     require("./v2/modules/base/arch.php");
     require("./v2/modules/base/archbr.php");
     require("./v2/modules/base/archos.php");
     require("./v2/modules/base/archres.php");
     require("./v2/modules/base/archdc.php");
     require("./v2/modules/base/archjs.php");
     require("./v2/modules/count/extparam.php");
     require("./v2/modules/base/browbase.php");
     require("./v2/modules/base/browlog.php");
     require("./v2/modules/base/ossbase.php");
     require("./v2/modules/base/osslog.php");
     require("./v2/modules/base/resbase.php");
     require("./v2/modules/base/reslog.php");
     require("./v2/modules/base/dcolbase.php");
     require("./v2/modules/base/dcollog.php");
     require("./v2/modules/base/jsbase.php");
     require("./v2/modules/base/jslog.php");
     require("./v2/modules/base/cookilog.php");
     require("./v2/modules/base/javalog.php");
     require("./v2/modules/base/frambase.php");
     require("./v2/modules/base/framlog.php");
    }

$Vis= new Visitor($Fun,$Conf,$Time);
$Vis->VisitorInit();
if($Vis->CodeError) $Fun->Error($Vis->CodeError,0);
$Vis->GetAccount();
if($Vis->CodeError) $Fun->Error($Vis->CodeError,0);
$Timeout=time();
$LockName=$Conf->FullBasePath.'accounts/'.$Vis->Acc.'/lock';
if(file_exists($LockName)) {
    $lc=fopen($LockName,'a+');
    if(!$lc) {$ENameCoF="$LockName"; $Fun->Error(10,0);}
}
else {
    $lc=fopen($LockName,'a+');
    if(!$lc) {$ENameCoF="$LockName"; $Fun->Error(10,0);}
    chmod($LockName,0755);
}
flock($lc,2);

$Vis->Recount();
if($Vis->CodeError) $Fun->Error($Vis->CodeError,0);
$Vis->GetVisitorID();
if($Vis->CodeError) $Fun->Error($Vis->CodeError,0);
$Vis->GetEnvironments();
if($Vis->CodeError) $Fun->Error($Vis->CodeError,0);
$Vis->SaveEnvironments();
if($Vis->CodeError) $Fun->Error($Vis->CodeError,0);

flock($lc,3);
fclose($lc);

$Vis->OutImage();
$Fun->Error($Vis->CodeError,0);


?>