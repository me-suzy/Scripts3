<?php
/************************************************************************/
/* BCB Spider Tracker: Simple Search Engine Bot Tracking                */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2004 by www.bluecollarbrain.com                        */
/* http://bluecollarbrain.com                                           */
/*                                                                      */
/* This program is free software. You may use it as you wish.           */
/*   This File: spider_agent_graph.php                                  */ 
/*   PHP treats this as an image file. It it used by the report page    */
/*   called unique_spider_agents.php.                                   */
/*   You shouldn't have to modify this unless you've changed the table  */
/*   name from the default bcb_spider.                                                                   */
/************************************************************************/

// Set header to image/png
	header("Content-type: image/png");
// Start database section ===============================================================================================================
	require 'config/spider_config.php';
	$connection = mysql_connect($db_host, $username, $pass);
	mysql_select_db($db, $connection);

// Query to pull in graph data
	$query = "SELECT DISTINCT agent AS agent, COUNT(*) AS count FROM $tablename GROUP BY agent ORDER BY count DESC"; 
	$result = mysql_query($query, $connection);
	$totalrows = mysql_num_rows($result); // Number of distinct spiders
// Build an array of values
	while ($row = mysql_fetch_assoc($result)){
		//$graphLabels[] = $row['agent']; // Graph labels - not usable yet
		$graphValues[] = $row['count'];
		}
	$query2 = "SELECT COUNT(*) AS count FROM $tablename";
	$result2 = mysql_query($query2);
	$total = mysql_result($result2, 0); // Total number of hits - all combined.
//======================================================================================================================================
// Start image creation ================================================================================================================
// Start figuring out the size of the image to be created - $max is highest bar in graph
	$max = max($graphValues);
	$imgWidth=$totalrows * 50; // make width = number of distinct spiders * 50 pixels 
	$imgHeight = (ceil($max/25) * 25 ) + 25; // make height round up # of total hits/25 to nearest mult of 25, then + 25 px

// Build the background	first
 	// Create the php bg image
	$bgimage = imagecreatefrompng("includes/graph_bg.png");
	//Get the background image size
	$bgimgsize = getimagesize("includes/graph_bg.png");


// Initialize a blank new image of the proper size for the graph
	$image = imagecreatetruecolor($imgWidth,$imgHeight);
// Resize the bg image to the graph size and output it as $image
	imagecopyresampled($image, $bgimage,0,0,0,0,$imgWidth,$imgHeight,$bgimgsize[0],$bgimgsize[1]);
// Destroy $bgimage, we're done with it.
	imagedestroy($bgimage);

// Define colors to use
	$colorGrey=imagecolorallocate($image, 204, 204, 204);
	$colorWhite=imagecolorallocate($image, 255, 255, 255);
	$colorGrey=imagecolorallocate($image, 204, 204, 204);
	$colorDarkBlue=imagecolorallocate($image, 0, 51, 102);
	$colorOrange=imagecolorallocate($image, 255, 153, 0);

// Create grid
for ($i=1; $i<$totalrows; $i++){
	imageline($image, $i*50, 0, $i*50, $imgHeight-1, $colorGrey); // Vertical grid lines
	}
for ($i=1; $i<($imgHeight/25)*2; $i++){
	imageline($image, 0, $i*25, $imgWidth-1, $i*25, $colorGrey); // Horizontal grid lines
	}

// Create bar charts
for ($i=0; $i<$totalrows; $i++){
	// set text positioning for graph textual values
	$textXstart = ($i*50)+2;
	if ($graphValues[$i]<20){
		$textYstart = ($imgHeight-($graphValues[$i])-14);
		} else {
		$textYstart = ($imgHeight-($graphValues[$i])+4);
		}
	imagefilledrectangle($image, ($i*50)+2, ($imgHeight-($graphValues[$i])+2), (($i+1)*50)-2, $imgHeight-2, $colorDarkBlue);
	imagefilledrectangle($image, ($i*50)+1, ($imgHeight-$graphValues[$i])+1, (($i+1)*50)-5, $imgHeight-2, $colorOrange);
	imagestring ($image, 3, $textXstart, $textYstart, $graphValues[$i], $colorDarkBlue);
	imagestring ($image, 3, $textXstart+20, 4, $i+1, $colorOrange);
	}
// Create border around image
	imageline($image, 0, 0, 0, $imgHeight, $colorDarkBlue); // left border
	imageline($image, 0, 0, $imgWidth, 0, $colorDarkBlue); // top border
	imageline($image, $imgWidth-1, 0, $imgWidth-1, $imgHeight-1, $colorDarkBlue); // right border
	imageline($image, 0, $imgHeight-1, $imgWidth-1, $imgHeight-1, $colorDarkBlue); // bottom border
// Output graph and clear image from memory
	imagepng($image);
	imagedestroy($image);
?>