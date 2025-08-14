<?php

//////////////////////////////////////////////////////////////////////////
// Script:        gif.php                                                //
//                                                                        //
// Source:        http://www.actualscripts.com/                                //
//                                                                        //
// Copyright:        (c) 2002 Actual Scripts Company. All rights reserved.        //
//                                                                        //
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.                        //
// SEE LICENSE AGREEMENT FOR MORE DETAILS                                //
//////////////////////////////////////////////////////////////////////////

class GIF
{

 var $Sizecod;
 var $Stream;
 var $Image;
 var $CodeError;
 var $Symbol;
 var $ImageFile;
 var $ColorFile;
 var $ColorTable;
 var $Conf;
 var $Fun;

 function GIF ($conf,$fun,$FileName,$colfile)
     { global $ENameCoF;
      $this->CodeError=0;
      $this->Fun = $fun;
      $this->Conf = $conf;
      Header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
      Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
      Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
      Header("Pragma: no-cache"); // HTTP/1.0
      Header("Content-type: Image/gif");

      $this->Sizecod=3;
      $this->Symbol[0]=array(0,1,1,0,0,1,0,0,1,0,1,0,0,1,0,1,0,0,1,0,1,0,0,1,0,1,0,0,1,0,0,1,1,0,0);
      $this->Symbol[1]=array(0,0,1,0,0,0,1,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,0,0,0,1,1,1,0);
      $this->Symbol[2]=array(1,1,1,0,0,0,0,0,1,0,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,0,1,1,1,1,0);
      $this->Symbol[3]=array(1,1,1,0,0,0,0,0,1,0,0,0,0,1,0,0,1,1,0,0,0,0,0,1,0,0,0,0,1,0,1,1,1,0,0);
      $this->Symbol[4]=array(0,0,0,1,0,0,0,1,1,0,0,1,0,1,0,1,0,0,1,0,1,1,1,1,1,0,0,0,1,0,0,0,0,1,0);
      $this->Symbol[5]=array(1,1,1,1,0,1,0,0,0,0,1,0,0,0,0,1,1,1,0,0,0,0,0,1,0,0,0,0,1,0,1,1,1,0,0);
      $this->Symbol[6]=array(0,1,1,0,0,1,0,0,0,0,1,0,0,0,0,1,1,1,0,0,1,0,0,1,0,1,0,0,1,0,0,1,1,0,0);
      $this->Symbol[7]=array(1,1,1,1,0,0,0,0,1,0,0,0,1,0,0,0,0,1,0,0,0,1,0,0,0,0,1,0,0,0,1,0,0,0,0);
      $this->Symbol[8]=array(0,1,1,0,0,1,0,0,1,0,1,0,0,1,0,0,1,1,0,0,1,0,0,1,0,1,0,0,1,0,0,1,1,0,0);
      $this->Symbol[9]=array(0,1,1,0,0,1,0,0,1,0,1,0,0,1,0,0,1,1,1,0,0,0,0,1,0,0,0,0,1,0,0,1,1,0,0);
      $this->ImageFile=$FileName;
      $this->ColorFile=$colfile;
     }

 function GIFInit ()
     { global $ENameCoF;
      $this->CodeError=0;
      if(!file_exists($this->ImageFile)) {$this->CodeError=1;$ENameCoF=$this->ImageFile;return;}

      $FileSour=fopen($this->ImageFile,"r");
      if(!$FileSour) {$this->CodeError=11;$ENameCoF=$this->ImageFile;return;}

      for($c=0;$c<3432;$c++)
           {
            $sim=(int)fread($FileSour,1);
            if(!isset($sim)) {$this->CodeError=13;$ENameCoF=$this->ImageFile;return;}
            $this->Image[$c]=$sim;
           }

      if(!file_exists($this->ColorFile)) {$this->CodeError=1;$ENameCoF=$this->ColorFile;return;}

      $this->Fun->extfile($this->ColorFile,$this->ColorTable);
      if($this->Fun->CodeError) {$this->CodeError=$this->Fun->CodeError;return;}

      if(!isset($this->ColorTable)) {$this->CodeError=13;$ENameCoF=$this->ColorFile;return;}
     }

 function Coding ()
     { global $ENameCoF;
      $this->CodeError=0;
      //initialize variable
      $clrcod=pow(2,$this->Sizecod);
      $fincod=$clrcod+1;
      $bpersym=$this->Sizecod+1;
      $maxval=pow(2,$bpersym);

      //create table
      for($c=0;$c<=$fincod;$c++) $tablice[]=$c;

      //create byte Stream
      $tbyte=0;
      $bitc=0;
      $tmp=$clrcod*pow(2,$bitc);
      $tbyte+=$tmp;
      $bitc+=$bpersym;
      if($tbyte>255) {
          $this->Stream[]=(int)$tbyte%256;
          $tbyte=(int)$tbyte/256;
          $bitc-=8;
      }

      $tek="";
      reset($this->Image);
      while($elem=each($this->Image)) {
             $tmp=$tek.$elem[1];
             if(isset($tablice[$tmp])) $tek=$tmp;
             else {
                   if(count($tablice)>$maxval) {
                      $bpersym++;
                      $maxval=pow(2,$bpersym);
                   }
                   $tablice[$tmp]=count($tablice);
                   $tmp=$tablice[$tek]*pow(2,$bitc);
                   $tbyte+=$tmp;
                   $bitc+=$bpersym;
                   while($bitc>=8) {
                       $this->Stream[]=(int)$tbyte%256;
                       $tbyte=(int)$tbyte/256;
                       $bitc-=8;
                   }
                   $tek=$elem[1];
             }
      }
      $tmp=$tablice[$tek]*pow(2,$bitc);
      $tbyte+=$tmp;
      $bitc+=$bpersym;
      if($tbyte>255) {
          $this->Stream[]=(int)$tbyte%256;
          $tbyte=(int)$tbyte/256;
          $bitc-=8;
      }
      $tmp=$fincod*pow(2,$bitc);
      $tbyte+=$tmp;
      $bitc+=$bpersym;
      if($tbyte>255) {
          $this->Stream[]=(int)$tbyte%256;
          $tbyte=(int)$tbyte/256;
          $bitc-=8;
      }
      if($tbyte!=0) $this->Stream[]=(int)$tbyte;
     }

 function symbol ( $symb , $x , $y , $lcolor , $bcolor )
     { global $ENameCoF;
      $this->CodeError=0;
      if(!isset($symb)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(!isset($x)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(!isset($y)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(!isset($lcolor)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(!isset($bcolor)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(($x>83)||($x<0)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(($y>32)||($y<0)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(($symb>9)||($symb<0)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(($lcolor>9)||($lcolor<0)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(($bcolor>9)||($bcolor<0)) {$this->CodeError=100;$ENameCoF="class GIF";return;}

      $base=$y*88+$x;
      for($yy=0;$yy<7;$yy++)
           for($xx=0;$xx<5;$xx++) {
                $point=$this->Symbol[$symb][$yy*5+$xx];
                if($point==1) $point=$lcolor;
                else $point=$bcolor;
                $iii=$base+$yy*88+$xx;
                $this->Image[$iii]=$point;
           }
     }

 function SetStatistic ($num1,$num2,$num3)
     { global $ENameCoF;
      $this->CodeError=0;
      if(!isset($num1)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(!isset($num2)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      if(!isset($num3)) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      $leftfield=2;
      $upfield=3;
      $betweenfield=1;
      $width=88;
      $height=39;
      // Input big statistic ($num1)
      $len=strlen($num1);
      $offsetX=$width-($len*5+$leftfield);
      if($offsetX<0) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      $offsetY=$upfield;
      for($i=0;$i<$len;$i++)
           {
            $this->symbol(substr($num1,$i,1),$offsetX,$offsetY,6,5);
            if($this->CodeError) return;
            $offsetX+=5;
           }
      // Input medium statistic ($num2)
      $len=strlen($num2);
      $offsetX=$width-($len*5+$leftfield);
      if($offsetX<0) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      $offsetY=$offsetY+7+$betweenfield;
      for($i=0;$i<$len;$i++)
           {
            $this->symbol(substr($num2,$i,1),$offsetX,$offsetY,6,5);
            if($this->CodeError) return;
            $offsetX+=5;
           }
      // Input small statistic ($num3)
      $len=strlen($num3);
      $offsetX=$width-($len*5+$leftfield);
      if($offsetX<0) {$this->CodeError=100;$ENameCoF="class GIF";return;}
      $offsetY=$offsetY+7+$betweenfield;
      for($i=0;$i<$len;$i++)
           {
            $this->symbol(substr($num3,$i,1),$offsetX,$offsetY,6,5);
            if($this->CodeError) return;
            $offsetX+=5;
           }
     }

 function Output ($CColor)
     {
      $this->CodeError=0;
      //create image data
      $pic=array(0x47,0x49,0x46,0x38,0x39,0x61); //GIF89a

      //header
      $pic[]=88; //width
      $pic[]=0;
      $pic[]=39; //height
      $pic[]=0;
      $pic[]=0xA2;
      $pic[]=0xFF;
      $pic[]=0;

      //global color table
      for($i=0;$i<48;$i+=2)
           {
            $tmpcol=substr($this->ColorTable[$CColor-1],$i,2);
            eval("\$pic[]=0x$tmpcol;");
           }

      //make 1 color as transparent
      $pic[]=0x21;
      $pic[]=0xF9;
      $pic[]=4;
      $pic[]=1;
      $pic[]=0;
      $pic[]=0;
      $pic[]=7;
      $pic[]=0;

      //image header
      $pic[]=0x2C;
      $pic[]=0;  //left
      $pic[]=0;
      $pic[]=0;  //top
      $pic[]=0;
      $pic[]=88; //width
      $pic[]=0;
      $pic[]=39; //height
      $pic[]=0;
      $pic[]=0;

      //image data
      $pic[]=$this->Sizecod;

      reset($pic);
      while($elem=each($pic)) {
           $tmp=sprintf("%x",$elem[1]);
           eval("echo \"\\x$tmp\";");
      }

      $curpos=0;
      while(($len=count($this->Stream)-$curpos)>0) {
           if($len>255) $len=255;
           $curpos+=$len;
           $tmp=sprintf("%x",$len);
           eval("echo \"\\x$tmp\";");
           for($c=$curpos-$len;$c<$curpos;$c++) {
                 $tmp=sprintf("%x",$this->Stream[$c]);
                 eval("echo \"\\x$tmp\";");
           }
      }

      //create end
      $pic2=array(0,0x3B);
      reset($pic2);
      while($elem=each($pic2)) {
           $tmp=sprintf("%x",$elem[1]);
           eval("echo \"\\x$tmp\";");
      }
     }

}

?>