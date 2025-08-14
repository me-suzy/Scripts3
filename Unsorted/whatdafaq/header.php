<?php

// CSS + Browser Detection
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net

unset ($BROWSER_AGENT);
unset ($BROWSER_VER);
unset ($BROWSER_PLATFORM);

function browser_get_agent () {
    global $BROWSER_AGENT;
    return $BROWSER_AGENT;
}

function browser_get_version() {
    global $BROWSER_VER;
    return $BROWSER_VER;
}

function browser_get_platform() {
    global $BROWSER_PLATFORM;
    return $BROWSER_PLATFORM;
}

function browser_is_mac() {
    if (browser_get_platform()=='Mac') {
        return true;
    } else {
        return false;
    }
}

function browser_is_windows() {
    if (browser_get_platform()=='Win') {
        return true;
    } else {
        return false;
    }
}

function browser_is_ie() {
    if (browser_get_agent()=='IE') {
        return true;
    } else {
        return false;
    }
}

function browser_is_netscape() {
    if (browser_get_agent()=='MOZILLA') {
        return true;
    } else {
        return false;
    }
}


/*
    Determine browser and version
*/


if (ereg( 'MSIE ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version)) {
    $BROWSER_VER=$log_version[1];
    $BROWSER_AGENT='IE';
} elseif (ereg( 'Opera ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version)) {
    $BROWSER_VER=$log_version[1];
    $BROWSER_AGENT='OPERA';
} elseif (ereg( 'Mozilla/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version)) {
    $BROWSER_VER=$log_version[1];
    $BROWSER_AGENT='MOZILLA';
} else {
    $BROWSER_VER=0;
    $BROWSER_AGENT='OTHER';
}

/*
    Determine platform
*/

if (strstr($HTTP_USER_AGENT,'Win')) {
    $BROWSER_PLATFORM='Win';
} else if (strstr($HTTP_USER_AGENT,'Mac')) {
    $BROWSER_PLATFORM='Mac';
} else if (strstr($HTTP_USER_AGENT,'Linux')) {
    $BROWSER_PLATFORM='Linux';
} else if (strstr($HTTP_USER_AGENT,'Unix')) {
    $BROWSER_PLATFORM='Unix';
} else {
    $BROWSER_PLATFORM='Other';
}

function css_site() {

    //determine font for this platform
    if (browser_is_windows() && browser_is_ie()) {

        //ie needs smaller fonts than anyone else
        $font_size='x-small';
        $font_smaller='xx-small';
        $font_smallest='7pt';
		$hover = 'A:hover { text-decoration: none; color: purple; background: #ffa}';
		$inputoption = 'input, option { border-color:purple; border-width:1; font-family: " . $site_fonts ."; font-size: " . $font_size ."; background-color: #FFFFFF; color:#ff1177; }\n    textarea {border-color:purple; border-width:1; font-family: ". $site_fonts . "; font-size: " . $font_size . "; color:#ff1177; background-color:#FFFFFF; }';
		$scrollbar = 'scrollbar-face-color: #f674e6; scrollbar-shadow-color: #d400fa; scrollbar-highlight-color: #68a9f8; scrollbar-3dlight-color: #71ff11; scrollbar-darkshadow-color: #074a8d; scrollbar-track-color: #FFFFFF; scrollbar-arrow-color: #f6748c;'; 
	} else if (browser_is_windows()) {

        //netscape or "other" on wintel
        $font_size='small';
        $font_smaller='x-small';
        $font_smallest='x-small';

    } else if (browser_is_mac()){

        //mac users need bigger fonts
        $font_size='medium';
        $font_smaller='small';
        $font_smallest='x-small';

    } else {

        //linux and other users
        $font_size='small';
        $font_smaller='x-small';
        $font_smallest='x-small';

    }

    $site_fonts='verdana, helvetica, tahoma, arial';

?>
<STYLE TYPE="text/css">
<!--
body  {
     background : #ffffff;
     font-family : <?php echo $site_fonts; ?>;
     font-size : <?php echo $font_size; ?>;
     color : #ff1177;
	 <?=$scrollbar?>
}
     td {
     font-size : <?php echo $font_size; ?>;
     font-family : <?php echo $site_fonts; ?>;
     color : #ff1177;
}
     td.small {
     font-size : <?php echo $font_smaller; ?>;
     font-family : <?php echo $site_fonts; ?>;
     color : #ff1177;
}
     .small {
     font-size : <?php echo $font_smaller; ?>;
     font-family : <?php echo $site_fonts; ?>;
}
     .title {
     font-size : <?php echo $font_size; ?>;
     font-family : <?php echo $site_fonts; ?>;
     font-weight : bold;
     color : #fa002d;
}
     .xsmall {
     font-size : <?php echo $font_smallest; ?>;
     font-family : <?php echo $site_fonts; ?>;
}
     .welcome {
     font-size : <?php echo $font_size; ?>;
     font-family : <?php echo $site_fonts; ?>;
     font-weight : bold;
     color : #fa002d;
}
     li {
     list-style-image : url(/images/wheel.gif);
     list-style-type : square;
     font-size : <?php echo $font_smaller; ?>;
     font-family : <?php echo $site_fonts; ?>;
     color : #ff1177;
}
     h1 {
     font-size : 175%;
     font-family : <?php echo $site_fonts; ?>;
}
     h2 {
     font-size : 150%;
     font-family : <?php echo $site_fonts; ?>;
}
     h3 {
     font-size : 125%;
     font-family : <?php echo $site_fonts; ?>;
}
     h4 {
     font-size : 100%;
     font-family : <?php echo $site_fonts; ?>;
}
     h5 {
     font-size : 75%;
     font-family : <?php echo $site_fonts; ?>;
}
     h6 {
     font-size : 50%;
     font-family : <?php echo $site_fonts; ?>;
}
     pre , tt , code {
     font-family : courier, sans-serif;
     font-size : <?php echo $font_size; ?>;
}
     a {
     color : purple;
}
     a:visited {
     color : navy;
}
     a:active {
     color : fuchsia;
}
     input.submit {
     background-color : fuchsia;
     color : purple;
     font-family : <?php echo $site_fonts; ?>;
     font-weight : bold;
}
<?=$hover?>;  	
<?=$inputoption?>
-->
</STYLE>
<?php
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>WhatDaFaq Admin Demo</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<?=css_site()?>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="2" width="750" height="200">
<tr>
<td align=center class="title" bgcolor="lime">WhatDaFaq Admin Demo</td>
</tr>
<tr>
<td align=center class="small"><a href=/scripts/whatdafaq.zip>Download</a></td>
</tr>
<tr>
<td>	