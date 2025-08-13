<?
// SigX Remote Image Generater (Basic Background)
// Version 1.0, 
// Notes: you can use any image, JPG PNG or GIF (gif untested), any size and dimention. just make sure its big enough to fit your biggest text
// this code does not resize the background image.
// my email: i@yuriy.net


// Settings, Edit
// This is the only part you need to edit.
// Username Setting
$un = "jbond03";
// background image location
$backIMG = "sigxmonster2.png"; // you can use URL for the image here too, JPG

// where the text goes on the image, offset to blank area on the image, in pixels.
$Xoffset=90;
$Yoffset=50;


////////////////////////////////////////////////////
// No need to go further.
/////////////////////////////////////////////////// 
// Code, Only edit if you know what you are doing!!
//////////////////////////////////////////////////

$alldata = getSomeData($un); // We get signal. It is you. How are you gentleman? Make your time now.

if (empty($alldata)) { die("cant contact server to get any data"); }

$fc = GetMid($alldata,"<fc="," \fc>"); // font color
$bc = GetMid($alldata,"<bc="," \bc>"); // background color (not used if background image set)
$fz = GetMid($alldata,"<fz="," \fz>"); // font size
$trans = GetMid($alldata,"<trans="," \\trans>"); // the \\t so we can type letter t. if background image is not set (transparencys not work if using imagecreatetruecolor() which i am.)
$data = GetMid($alldata,"<data="," \data>"); // the data from sigx.yuriy.net server, what we got to work with.

//<fc=0,0,0 \fc>
//<bc=255,255,255 \bc>
//<fz=2 \fz>
//<trans=1 \trans>
//<data=Visit http://sigx.yuriy.net \data>

if( $data ){ // if we get the data ok
	drawIt($data, $fc, $bc, $fz, $trans, $Xoffset, $Yoffset, $backIMG);
	//echo "$data, $fc, $bc, $fz, $trans, $backIMG";
}


//
////
// cool functions
////

function drawIt($data, $fontcolor="0,0,0", $backcolor="255,255,255", $font="2", $transperancy=1, $Xoffset=2, $Yoffset=2, $backIMG=''){
	
	// max amount of lines
	$maxElements = 10;
	
	if( $data ) {
		// if we pass our data dynamic array string
		$data = explode("*<*",$data);
	}

	
	if ( file_exists($backIMG) ){
		
		# reading image
		$image = @getImageSize($backIMG, $info); # $info, only to handle problems with earlier php versions...
		switch($image[2]) {
		case 1:
			# GIF image
			$bkIMG = @imageCreateFromGIF($backIMG);
			break;
		case 2:
			# JPEG image
			$bkIMG = @imageCreateFromJPEG($backIMG);
			break;
		case 3:
			# PNG image
			$bkIMG = @imageCreateFromPNG($backIMG);
			break;
		default:
			# PNG
			$bkIMG = @imageCreateFromPNG($backIMG);
			break;
		}
		
		//$bkIMG = @imageCreateFromPNG($backIMG); // create the background image from a PNG file
		$bkIMG_width=imageSX($bkIMG);  // get width and height of the background image
		$bkIMG_height=imageSY($bkIMG); 		
		// we set the width and height based on the background image provided.
		$width = $bkIMG_width; 
		$height = $bkIMG_height;
	}
		
	if ( !$bkIMG ) { // if no background image is set
		if( is_array($data) ){ // if we have the data
			
			$data = array_slice($data, 0, $maxElements); // cut off the limitation of lines
			
			for($x = 0; $x <= count($data); $x++){ // find the widest line, and we have our width
				$curWidth =  ImageFontWidth($font) * strlen($data[$x]) + $Xoffset+2;
				if($curWidth > $defaultWidth){
					$defaultWidth = $curWidth;
				}
			}
			if($defaultWidth > 500) { $defaultWidth = 500; }	
			$width  = $defaultWidth;
			
			// lets do the same for height
			$curHeight = ImageFontHeight($font) * count($data) + $Yoffset + 2;		
			
			if ($curHeight > $defaultHeight){ $defaultHeight = $curHeight; }
			$height = $defaultHeight;		
				
		} else { // if its just an offline msg or single element
			
			$curWidth =  ImageFontWidth($font) * strlen($data) + $Xoffset+2;
						
			if($curWidth > $defaultWidth){
				$defaultWidth = $curWidth;
			}
	
			if($defaultWidth > 500) { $defaultWidth = 500; }
			$width  = $defaultWidth;
			
			$curHeight = ImageFontHeight($font) + $Yoffset+2;			
			if ($curHeight > $defaultHeight){ 
				$defaultHeight = $curHeight; 
			}
					
			$height = $defaultHeight;
		}
	}
	
	
	$im = @imagecreatetruecolor ($width,$height); // create the image
    
	$fColor = explode(",",$fontcolor); // font color
	$bColor = explode(",",$backcolor); // back color
	
	$bgc = imagecolorallocate ($im, $bColor[0], $bColor[1], $bColor[2]); // default white
	imagefilledrectangle($im, 0, 0, $width, $height, $bgc);

	//mergePix(&$sourcefile, $insertfile, $pos=1,$transition=100) 
	if ($bkIMG){ // if we found a background image
		@mergePix($im, $bkIMG, 1); // put our background image on top left
	}

	// if transperent (....)
	// note: if using imagecreatetruecolor transperancy is not working. but if your using a img it makes no difference
	//if ( $transperancy == 1) { // only used if no backgrouns image set, only works for PNG, maybe GIF
		//ImageColorTransparent($im, $bgc);
	//}
	
	
	// lets allocate some colors
	$fontColor = imagecolorallocate ($im, $fColor[0], $fColor[1], $fColor[2]);
	$black = imagecolorallocate ($im, 0, 0, 0);
	
	if( is_array($data) ){
	// start outputing the info, line by line
		for($x = 0; $x<= count($data); $x++){
		imagestring ($im, $font, $Xoffset, ImageFontHeight($font) * $x + $Yoffset,  $data[$x], $fontColor);			
		}
	} else {
		imagestring ($im, $font, $Xoffset, $Yoffset,  $data, $fontColor);
	}


	# output
	switch($image[2]) {
		case 1:
		# GIF image
		header("Content-type: image/gif");
		imageGIF($im);
		break;
	case 2:
		# JPEG image
		header("Content-type: image/jpeg");
		imageJPEG($im);
		break;
	case 3:
		# PNG image
		header("Content-type: image/png");
		imagePNG($im);
		break;
	default:
		# PNG image
		header("Content-type: image/png");
		imagePNG($im);
		break;
	}
	
	# cleaning cache
	imageDestroy($im);	
}



// Functions, belong to their respective owners.
function ImageStringWrap($image, $font, $x, $y, $text, $color, $maxwidth)
{
   $fontwidth = ImageFontWidth($font);
  $fontheight = ImageFontHeight($font);

   if ($maxwidth != NULL) {
       $maxcharsperline = floor($maxwidth / $fontwidth);
      $text = wordwrap($text, $maxcharsperline, "\n", 1);
    }

   $lines = explode("\n", $text);
   while (list($numl, $line) = each($lines)) {
       ImageString($image, $font, $x, $y, $line, $color);
       $y += $fontheight;
    }
}

// Align string on image
// $x and $y values
// 0 align left or top
// 1 align center
// 2 align right or bottom
function ImageStringAlign(&$image, $font, $x, $y, $s, $col) {
 switch ($x) {
   case 0: $x = 2; break;
   case 1: $x = floor((ImageSX($image) / 2) - ((ImageFontWidth($font) * strlen($s)) / 2)); break;
   case 2: $x = (ImageSX($image) - (ImageFontWidth($font) * strlen($s))) - 2; break;
 }
 switch ($y) {
   case 0: $y = 2; break;
   case 1: $y = floor((ImageSY($image) / 2) - (ImageFontHeight($font) / 2)); break;
   case 2: $y = (ImageSY($image) - ImageFontHeight($font)); break;
 }
 ImageString($image, $font, $x, $y, $s, $col);
}

function getSomeData($username=""){
	// this allows us to get the remote data
	
	$alldata = '';
	$username = urlencode($username); // encode in case name has spaces
	
	$fd=fopen("http://sigx.yuriy.net/remote/textout.php?username=$username","r");
	while ($line=fgets($fd,1000))
	{
	 	$alldata.=$line;
	}
	fclose ($fd);
	return $alldata;
}

function GetMid($TheStr, $sLeft, $sRight){
	$pleft = strpos($TheStr, $sLeft, 0);	
	if ($pleft !== false){
		$pright = strpos($TheStr, $sRight, $pleft + strlen($sLeft));			
		If ($pright !== false) {
			return (substr($TheStr, $pleft + strlen($sLeft), ($pright - ($pleft + strlen($sLeft)))));
		}
	}
	return '';
}

//$sourcefile = Filename of the picture into that $insertfile will be inserted. 
//$insertfile = Filename of the picture that is to be inserted into $sourcefile. 
//$targetfile = Filename of the modified picture. 
//$transition = Intensity of the transition (in percent) 
//$pos          = Position where $insertfile will be inserted in $sourcefile 
//                0 = middle 
//                1 = top left 
//                2 = top right 
//                3 = bottom right 
//                4 = bottom left 
//                5 = top middle 
//                6 = middle right 
//                7 = bottom middle 
//                8 = middle left 
// 
// 
function mergePix(&$sourcefile, &$insertfile, $pos=1,$transition=100) 
{ 
    
//Get the resource id´s of the pictures 
    //$insertfile_id = imageCreateFromPNG($insertfile); 
    //$sourcefile_id = imageCreateFromPNG($sourcefile); 
	$insertfile_id = $insertfile;
	$sourcefile_id = $sourcefile;

//Get the sizes of both pix    
    $sourcefile_width=imageSX($sourcefile_id); 
    $sourcefile_height=imageSY($sourcefile_id); 
    $insertfile_width=imageSX($insertfile_id); 
    $insertfile_height=imageSY($insertfile_id); 

//middle 
    if( $pos == 0 ) 
    { 
        $dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 ); 
        $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    } 

//top left 
    if( $pos == 1 ) 
    { 
        $dest_x = 0; 
        $dest_y = 0; 
    } 

//top right 
    if( $pos == 2 ) 
    { 
        $dest_x = $sourcefile_width - $insertfile_width; 
        $dest_y = 0; 
    } 

//bottom right 
    if( $pos == 3 ) 
    { 
        $dest_x = $sourcefile_width - $insertfile_width; 
        $dest_y = $sourcefile_height - $insertfile_height; 
    } 

//bottom left    
    if( $pos == 4 ) 
    { 
        $dest_x = 0; 
        $dest_y = $sourcefile_height - $insertfile_height; 
    } 

//top middle 
    if( $pos == 5 ) 
    { 
        $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
        $dest_y = 0; 
    } 

//middle right 
    if( $pos == 6 ) 
    { 
        $dest_x = $sourcefile_width - $insertfile_width; 
        $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    } 
        
//bottom middle    
    if( $pos == 7 ) 
    { 
        $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
        $dest_y = $sourcefile_height - $insertfile_height; 
    } 

//middle left 
    if( $pos == 8 ) 
    { 
        $dest_x = 0; 
        $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    } 
    
//The main thing : merge the two pix    
    imageCopyMerge($sourcefile_id, $insertfile_id,$dest_x,$dest_y,0,0,$insertfile_width,$insertfile_height,$transition); 

return $sourcefile_id;
}

?>