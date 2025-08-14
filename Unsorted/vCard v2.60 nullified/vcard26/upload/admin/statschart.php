<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
// Header("Content-type: image/gif"); GD lib VS compuserv GIF copyright
@header("Content-type: image/jpeg");    //if your php support JPEG
@header("Pragma: no-cache");
//header("Cache-Control: no-cache, must-revalidate"); 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$title = $HTTP_GET_VARS['title'];
$sval = $HTTP_GET_VARS['sval'];

$img_width	= 350;
$img_height	= 200;
$title_font	= 5;
$im 		= imagecreate($img_width,$img_height);

$background 	= imageColorAllocate($im,80,80,80);
$textcolor	= imageColorAllocate($im,255,207,95);
$barbground	= imageColorAllocate($im,239,239,239);
$barcolor	= imageColorAllocate($im,144,144,255);

ImageString($im,$title_font,($img_width-ImageFontWidth($title_font)*strlen($title))/2,0,$title,$textcolor);
#Draw rectangle which contain bars
imagefilledrectangle($im,25,20,$img_width-10,$img_height-10,$barbground);

# Manage data
$valeurs 	= explode(";",$sval);
$img_width_barre = (int)(($img_width-22)/(1.5*sizeof($valeurs)+0.5));
# $max = max($valeurs);

# Max function does not work correctly in php < 4
for ($i=0; $i<sizeof($valeurs); $i++)
{
	if ($valeurs[$i] > $max)
	{
   		$max = $valeurs[$i];
	}
}


#Draw bars
for ($i=0; $i<sizeof($valeurs); $i++)
{
	$x = 25+(int)($img_width_barre*(0.5+$i*1.5));
	$img_height_barre = (int)(($valeurs[$i]*($img_height-40))/$max);
	imagefilledrectangle($im,$x,$img_height-15-$img_height_barre,$x+$img_width_barre,$img_height-15,$barcolor);
	if ($valeurs[$i] == $max)
	{
		ImageString($im,2,4,($img_height-15-$img_height_barre)-(ImageFontHeight(2)/2),$valeurs[$i],$textcolor);
		imageline($im,20,$img_height-15-$img_height_barre,$img_width-2,$img_height-15-$img_height_barre,$background);
 	}
 	# write x axis every 2 hours
 	if (($i % 2)==0)
	{
		ImageString($im,1,$x,$img_height-9,$i,$textcolor);
	}
}

ImageString($im,2,3,0,"hits",$textcolor);
ImageString($im,-10,320,191,"Hour",$textcolor);
//  ImageGIF($im);
//ImageJPEG($im);
ImagePng($im);
ImageDestroy($im);

?>