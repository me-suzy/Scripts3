<?php

//////////////////////////////////////////////////////////////////////////
// Script:        view.php                                                //
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
require("./modules/base/ipsbase.php");
require("./modules/base/langbase.php");
require("./modules/base/refbase.php");
require("./modules/base/accbase.php");
require("./modules/base/vislog.php");
require("./modules/base/ipslog.php");
require("./modules/base/langlog.php");
require("./modules/base/reflog.php");
require("./modules/template/template.php");
require("./modules/view/vdiagram.php");
require("./modules/view/hdiagram.php");
require("./modules/view/acclist.php");
require("./modules/view/intlist.php");
require("./modules/view/timelist.php");
require("./modules/view/stattab.php");
require("./modules/view/parttab.php");
require("./modules/view/sumtable.php");
require("./modules/view/logtable.php");
require("./modules/stat/visstat.php");
require("./modules/stat/ipsstat.php");
require("./modules/stat/langstat.php");
require("./modules/stat/refstat.php");
require("./modules/stat/logsstat.php");
require("./modules/stat/stat.php");

$ENameCoF='';

$Fun = new Funct();

$Conf = new Config($Fun);
$Conf->ConfigInit();
if($Conf->CodeError) $Fun->Error($Conf->CodeError,1);

//if($Conf->Version>=2)
//    {
     $Fun->Auth($Conf,'view.php',1);
     if($Fun->CodeError) $Fun->Error($Fun->CodeError,1);
//    }

if($Conf->Version>=2)
    {
     require("./v2/modules/stat/extstat.php");
     require("./v2/modules/base/browbase.php");
     require("./v2/modules/base/ossbase.php");
     require("./v2/modules/base/resbase.php");
     require("./v2/modules/base/dcolbase.php");
     require("./v2/modules/base/jsbase.php");
     require("./v2/modules/base/frambase.php");
     require("./v2/modules/stat/browstat.php");
     require("./v2/modules/stat/ossstat.php");
     require("./v2/modules/stat/resstat.php");
     require("./v2/modules/stat/dcolstat.php");
     require("./v2/modules/stat/jsstat.php");
     require("./v2/modules/stat/framstat.php");
     require("./v2/modules/stat/landstat.php");
     require("./v2/modules/stat/jcstat.php");
     require("./v2/modules/view/hdjc.php");
     require("./v2/modules/view/elogtab.php");
     require("./v2/modules/stat/arstat.php");
     require("./v2/modules/stat/arrfstat.php");
     require("./v2/modules/stat/aripstat.php");
     require("./v2/modules/stat/arlnstat.php");
     require("./v2/modules/stat/arbrstat.php");
     require("./v2/modules/stat/arosstat.php");
     require("./v2/modules/stat/ardcstat.php");
     require("./v2/modules/stat/arrsstat.php");
     require("./v2/modules/stat/arjsstat.php");
     require("./v2/modules/stat/arfrstat.php");
     require("./v2/modules/stat/elogstat.php");
    }

$View= new Stat($Fun,$Conf);
$View->StatInit();
if($View->CodeError) $Fun->Error($View->CodeError,1);
$View->OutStat();
if($View->CodeError) $Fun->Error($View->CodeError,1);

?>