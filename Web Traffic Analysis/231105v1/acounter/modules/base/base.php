<?php

//////////////////////////////////////////////////////////////////////////
// Script:        base.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class Base
{

 var $FullFileName;
 var $BaseFile;
 var $CodeError;
 var $Conf;
 var $Fun;
 var $Size;

 function Base ($fun,$conf,$name)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->Fun = $fun;
           $this->Conf = $conf;
           $this->FullFileName=$this->Conf->FullBasePath.$name;
          }

 function BaseInit ($flag)
          { global $ENameCoF;
           $this->CodeError=0;
           if(!file_exists($this->FullFileName))
                  {
                   if ($flag==0) {$this->CodeError=1;$ENameCoF="$this->FullFileName";return;}
                   $fh=fopen($this->FullFileName,'w');
                   if(!$fh) {$this->CodeError=10;$ENameCoF="$this->FullFileName";return;}
                   chmod($this->FullFileName,0755);
                   fclose($fh);
                  }
           $this->Fun->extfile($this->FullFileName,$this->BaseFile);
           if($this->Fun->CodeError) {$this->CodeError=$this->Fun->CodeError;return;}
           if(!isset($this->BaseFile)) {$this->CodeError=13;$ENameCoF="$this->FullFileName";return;}
           reset($this->BaseFile);
           while($elem=each($this->BaseFile))
                  {
                   $elem[1]=str_replace("\r",'',$elem[1]);
                   $elem[1]=str_replace("\n",'',$elem[1]);
                   $this->BaseFile[$elem[0]]=$elem[1];
                  }
          $this->Size=count($this->BaseFile);
          }

 function GetFieldByID( $number , $id )
          { global $ENameCoF;
           $this->CodeError=0;
           if(!isset($this->BaseFile[$id])) {$this->CodeError=100;$ENameCoF="$this->FullFileName";return;}
           $fields=$this->BaseFile[$id];
           $fields = split( "\|", $this->BaseFile[$id] );
                   if(!isset($fields[$number]))
                      {
                       if($this->Conf->Debug)
                           {
                            $this->Recovery();
                            if($this->CodeError) return;
                           }
                       $this->CodeError=100;
                       $ENameCoF="$this->FullFileName";
                       return;
                      }
           return $fields[$number];
          }

 function GetRecordByID( $id )
          { global $ENameCoF;
           $this->CodeError=0;
           if(!isset($this->BaseFile[$id])) {$this->CodeError=100;$ENameCoF="$this->FullFileName";return;}
           return $this->BaseFile[$id];
          }

 function GetIDByField( $number , $field )
          { global $ENameCoF;
           $this->CodeError=0;
           reset($this->BaseFile);
           while($elem=each($this->BaseFile))
           {
            $fields = split( "\|", $elem[1]);
                   if(!isset($fields[$number]))
                      {
                       if($this->Conf->Debug)
                           {
                            $this->Recovery();
                            if($this->CodeError) return;
                            continue;
                           }
                       else {$this->CodeError=100;$ENameCoF="$this->FullFileName";return;}
                      }
            if(!strcmp($fields[$number],$field)) return $elem[0];
           }
           $this->CodeError=2;
           $ENameCoF="$this->FullFileName";
          }

 function GetRecordByField( $number , $field )
          { global $ENameCoF;
           $this->CodeError=0;
           reset($this->BaseFile);
           while($elem=each($this->BaseFile))
                  {
                   $fields = split( "\|", $elem[1]);
                   if(!isset($fields[$number]))
                      {
                       if($this->Conf->Debug)
                           {
                            $this->Recovery();
                            if($this->CodeError) return;
                            continue;
                           }
                       else {$this->CodeError=100;$ENameCoF="$this->FullFileName";return;}
                      }
                   if(!strcmp($fields[$number],$field)) return $elem[1];
                  }
           $this->CodeError=2;
           $ENameCoF="$this->FullFileName";
          }

 function GetIDByRecord( $record )
          { global $ENameCoF;
           $this->CodeError=0;
           reset($this->BaseFile);
           while($elem=each($this->BaseFile))
                  {
                   if(!strcmp($elem[1],$record)) return $elem[0];
                  }
           $this->CodeError=2;
           $ENameCoF="$this->FullFileName";
          }

 function AddRecord($record)
          { global $ENameCoF;
           $this->CodeError=0;
           $n=count($this->BaseFile);
           $this->BaseFile[]=$record;
           if(file_exists($this->FullFileName)) {
                   $file=fopen($this->FullFileName,'a');
                   if(!$file) {$this->CodeError=12;$ENameCoF="$this->FullFileName";return;}
           }
           else {
                   $file=fopen($this->FullFileName,'w');
                   if(!$file) {$this->CodeError=10;$ENameCoF="$this->FullFileName";return;}
                 chmod($this->FullFileName,0755);
           }
           flock($file,2);
           $nr=fputs($file,$record."\n");
           flock($file,3);
           fclose($file);
           $this->Size=count($this->BaseFile);
           if(!$nr) {$this->CodeError=15;$ENameCoF="$this->FullFileName";return;}
           return $n;
          }

 function Update()
          { global $ENameCoF;
           $this->CodeError=0;
           $n=count($this->BaseFile);
           $file=fopen($this->FullFileName,'w');
           if(!$file) {$this->CodeError=11;$ENameCoF="$this->FullFileName";return;}
           chmod($this->FullFileName,0755);
           flock($file,2);
           $nr=1;
           for($i=0;$i<$n;$i++) {
               $nr1=fputs($file,$this->BaseFile[$i]."\n");
               if(!$nr1) $nr=0;
           }
           flock($file,3);
           fclose($file);
           $this->Size=count($this->BaseFile);
           if(!$nr) {$this->CodeError=15;$ENameCoF="$this->FullFileName";return;}
           return $n;
          }

 function Recovery()
          { global $ENameCoF;
           $this->CodeError=0;
           $n=count($this->BaseFile);
           $file=fopen($this->FullFileName,'w');
           if(!$file) {$this->CodeError=11;$ENameCoF="$this->FullFileName";return;}
           chmod($this->FullFileName,0755);
           flock($file,2);
           reset($this->BaseFile);
           $e=each($this->BaseFile);
           $tmp=$e[0];
           reset($this->BaseFile);
           while($e=each($this->BaseFile))
                  {
                   $k=$e[0];
                   if($k==$tmp) continue;
                   fputs($file,$this->BaseFile[$k]."\n");
                  }
           flock($file,3);
           fclose($file);
           $this->Size=count($this->BaseFile);
           reset($this->BaseFile);
           return $n;
          }

 function SaveRecord($record)
          { global $ENameCoF;
           $this->CodeError=0;
           $this->BaseFile=array();
           $this->BaseFile[]=$record;
           $file=fopen($this->FullFileName,'w');
           if(!$file) {$this->CodeError=11;$ENameCoF="$this->FullFileName";return;}
           chmod($this->FullFileName,0755);
           flock($file,2);
           $nr=fputs($file,$record."\n");
           flock($file,3);
           fclose($file);
           $this->Size=count($this->BaseFile);
           if(!$nr) {$this->CodeError=15;$ENameCoF="$this->FullFileName";return;}
          }

}

?>