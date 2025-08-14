<?php

//////////////////////////////////////////////////////////////////////////
// Script:        template.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Template extends Base
{
 var $CodeError;
 var $Fun;
 var $Conf;

 function Template ( $fun, $conf, $name)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           if(empty($name)) $this->BaseFile[0]='';
           if($this->Conf->Version>=2)
                  {
                   $this->Base($fun, $conf,'v2/template/'.$name);
                  }
           else
                  {
                   $this->Base($fun, $conf,'template/'.$name);
                  }
          }

 function TemplateInit ()
          {
           $this->CodeError=0;
           $this->BaseInit(0);
           $this->BaseFile[0]='';
          }

 function ParseTemplate ($tvar)
          { global $ENameCoF;
           $this->CodeError=0;
           reset($this->BaseFile);
           while($elem=each($this->BaseFile))
                 {
                  $telem=$elem[1];
                  while(eregi("%%([a-z0-9A-Z]+)%%",$telem,$rez))
                        {
                         if(!isset($tvar[$rez[1]])) {echo $rez[1];$this->CodeError=3;$ENameCoF="class Template";return;}
                         if(is_array($tvar[$rez[1]]))
                                {
                                 $newelem=strstr($telem,"%%$rez[1]%%");
                                 $len=strlen($telem)-strlen($newelem);
                                 $arr[]=substr($telem,0,$len);
                                 $telem=substr($newelem,strlen("%%$rez[1]%%"));
                                 reset($tvar[$rez[1]]);
                                 $elemtmpl=each($tvar[$rez[1]]);
                                 while($elemtmpl=each($tvar[$rez[1]]))
                                         $arr[]=$elemtmpl[1];
                                }
                         else
                                 $telem=str_replace("%%$rez[1]%%",$tvar[$rez[1]],$telem);
                        }
                  $arr[]=$telem;
                 }
           $this->BaseFile=$arr;
          }

 function AddTemplate ($tmpl)
          {
           $this->CodeError=0;
           reset($tmpl);
           while($elem=each($tmpl))
                 {
                  $this->BaseFile[]=$elem[1];
                 }
          }

 function GetTemplate ()
          {
           $this->CodeError=0;
           return $this->BaseFile;
          }

 function OutTemplate ()
          {
           $this->CodeError=0;
           reset($this->BaseFile);
           while($elem=each($this->BaseFile)) echo $elem[1]."\r\n";
          }

}

?>