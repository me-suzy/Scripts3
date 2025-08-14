<?php

//////////////////////////////////////////////////////////////////////////
// Script:        config.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Config
{

 var $FullBasePath;
 var $FullUrl;
 var $CookieExp;
 var $OnlineTime;
 var $ReturnIntervals;
 var $NameReturnIntervals;
 var $RemoveTime;
 var $Debug;
 var $MainConf;
 var $Version;
 var $CodeError;
 var $Fun;

 function Config ($fun)
          { global $ENameCoF;
           $this->Fun=$fun;
           $this->CodeError=0;
           $this->FullUrl="";
           $this->FullBasePath="./";
           $this->AdminName='admin';
           $this->AdminPassword='admin';
           $this->GuestName='guest';
           $this->GuestPassword='guest';
           $this->CookieExp="86400000";
           $this->OnlineTime="120";
           $this->RemoveTime="864000";
           $this->Version="1.21";
           $this->Debug="0";
           $this->MainConf="aHR0cDovL3d3dy5hY3R1YWxzY3JpcHRzLmNvbS8=";
           $this->FSInterval=10;
           $this->ReturnIntervals=array(60,300,600,1800,3600,7200,18000,86400);
           $this->NameReturnIntervals=array('0 - 1 min.','1 - 5 min.','5 - 10 min.','10 - 30 min.','30 min. - 1 hour',
                                             '1 - 2 hours','2 - 5 hours','5 - 24 hours');
           $this->StatIntervals[0]="5|5 Seconds|12|s|i";
           $this->StatIntervals[1]="60|Minute|10|i|G";
           $this->StatIntervals[2]="600|10 Minutes|12|i|G";
           $this->StatIntervals[3]="7200|2 Hours|12|G|j";
           $this->StatIntervals[4]="86400|Day|7|j|M";
           $this->StatIntervals[5]="604800|Week|5|j|M";
           $this->StatIntervals[6]="1|Month|12|M|Y";
           $this->StatIntervals[7]="1|Year|12|Y|Y";
          }

 function ConfigInit ()
          { global $ENameCoF;
           $this->CodeError=0;

           if(!file_exists('./config/conf_dat.php')) {$this->CodeError=1;return;}
           $this->Fun->extfile('./config/conf_dat.php',$fdata);
           if($this->Fun->CodeError) {$this->CodeError=$this->Fun->CodeError;return;}
           if(!$fdata) {$ENameCoF='./config/conf_dat.php';$this->CodeError=13;return;}

           $this->MainConf=base64_decode($this->MainConf);
           reset($fdata);
           $elem=each($fdata);
           while($elem=each($fdata))
                {
                 $elem[1]=str_replace("\r",'',$elem[1]);
                 $elem[1]=str_replace("\n",'',$elem[1]);
                 $tarray = split("=",$elem[1]);
                 if(count($tarray)==2)
                          {
                           $tname=trim($tarray[0]);
                           $tvalue=trim($tarray[1]);
                           if(isset($this->$tname)) $this->$tname=$tvalue;
                          }
                }
          }

}

?>