<?php
/////////////////////////////////////////////////////////////////
//                                                             //
//                                                             //
//                                                             //
//                                                             //
//                         wimpy                               //
//                                                             //
//             by Mike Gieson <info@gieson.com>                //
//            available at http://www.gieson.com               //
//                     ©2002-2004 plaino                       //
//                                                             //
//                                                             //
//                                                             //
/////////////////////////////////////////////////////////////////
//                                                             //
//                                                             //
//                           v 2.0.33                          //
//                                                             //
//                                                             //
/////////////////////////////////////////////////////////////////
//                                                             //
//                       INSTALLATION:                         //
//                                                             //
/////////////////////////////////////////////////////////////////
// 
// Upload wimpy.php and wimpy.swf to the folder that 
// contains your mp3's.
// 
// USE AT YOUR OWN RISK.
// 
// www.wimpyplayer.com
// www.gieson.com
// info@gieson.com
// 
/////////////////////////////////////////////////////////////////
//                                                             //
//                     DIRECTORY CONFIGURATION                 //
//                                                             //
//     Use this to prevent specific folders                    //
//     from displaying within Wimpy.                           //
//                                                             //
/////////////////////////////////////////////////////////////////
//
// Hide folders:
// Folders to hide: seperate each folder name with a comma
$hide_folders = "_private,_private,_vti_bin,_vti_cnf,_vti_pvt,_vti_txt,cgi-bin";
$media_types = "xml,mp3";
$defaultPlaylistFilename = "playlist.xml";
// 
// 
// 
// 
/////////////////////////////////////////////////////////////////
//                                                             //
//                     CONFIGURATION                           //
//     Use thse configuration options to alter the             //
//     behaviour ONLY IF you do not use a seperate             //
//     HTML page to display Wimpy. These options only          //
//     control the "default" page that is displayed            //
//     when you access this file (wimpy.php).                  //
//                                                             //
/////////////////////////////////////////////////////////////////
//                                                             //
//                 HTML PAGE PRESNETATION:                     //
//                                                             //
//
// Wimpy Flash filename
// the filename of the wimpy flash movie:
$wimpySWFfilename = "wimpy.swf";
//
// Background Color:
// background color for the HTML page that the default wimpy is loaded into)
//$background_color = "6A7A95";
// IMPORTANT: do not use a # in your HEX color... just put the value!
$background_color = 'FFFFFF';
// 
// HTML page title 
// The title that appears on the top of a users browser wimdow.
$wimpyHTMLpageTitle = "wimpy";
$playlisterOutputDirName = "playlister_output";
//
//                                                             //
//                     PLAYER BEHAVIOUR:                       //
//                                                             //
//
// Download Button:
// Should wimpy show the download arrow on the right hand side?
$displayDownloadButton = "yes";
//
// Info display Speed:
// how fast should the currently playing song switch between the artist and the title of the song:
// (lower numbers will switch faster)
$infoDisplayTime = 3;
//
// Random Selection on Launch:
// upon launch should wimpy select the first song randomly? (remaining songs will play in order).
// $defaultPlayRandom = "no";
$defaultPlayRandom = "yes";
//
// Start Playing Immediately
//$startPlayingOnload = "no";
$startPlayingOnload = "yes";
//
// Show pop up help by default
// $popUpHelp = "no";
$popUpHelp = "yes";
//
// Display embedded id3 info or file name
// if you want to sho id3 info set this to "yes"
// If you just want to show a psuedo filename 
// (same name, but no ".mp3") set this to "no"
// $getMyid3info = "yes"
if(isset($_REQUEST['getMyid3info'])){
	$getMyid3info = $_REQUEST['getMyid3info'];
} else {
	$getMyid3info = "no";
}
//
// Startup volume. Set this to the percentage of volume you would like wimpy to start up with.
// Example: 100 would mean full volume
//          50 would mean half volume
//          0 would mean "mute"
$currentVolume = "100";
//
// Random Playback
// set the default button position for the random playback button:
$randomPlayback = "yes";
//
/////////////////////////////////////////////////////////////////
//                                                             //
//                        MySQL SETUP:                         //
//                                                             //
/////////////////////////////////////////////////////////////////
//$host = "localhost";
//$db = "mp3s";
//$table = "wimpy";
//$user = "username";
//$pwd = "1234";
//
$host = "localhost";
$db = "plaino2_wimpy";
$table = "mp3s";
$user = "plaino2_bob";
$pwd = "1234";
//
//
// The data set up:
// For those of you who are using wimpy to build a new mp3 
// table in your database, a new item "id" is appended to the 
// datasetup below and used in the table that wimpy creates.
// 
// Each item listed in the datasetup below is a reference to 
// the column names in your database. You can change the dataset 
// up list below, HOWEVER, the column named "filename" must exist 
// for wimpy to work.
//
// For each item, wimpy sends in all the data for each row. If you would like ot display data from a column inside of wimpy, you need to set up (or edit) the text filed name that corresponds to the column name.  
// item below inside of the file named wimpylink_update.php

$myDataSetup = "filename|artist|album|title|track|comments|genre|seconds|filesize|bitrate|visual|url";
/////////////////////////////////////////////////////////////////
//                                                             //
//                     TROUBLE SHOOTING:                       //
//                                                             //
/////////////////////////////////////////////////////////////////
// 
// this one will try and find "argv" from the defined variables
$troubleshoot1 = false;
//
//
/////////////////////////////////////////////////////////////////
//                                                             //
//         do not edit anything below here unless              //
//          you really know what you are doing!                //
//                                                             //
/////////////////////////////////////////////////////////////////
// 
$ts = array();
$ts[0] = false;
strstr( PHP_OS, "WIN") ? $v480 = "\\" : $v480 = "/";
if(!@getcwd ()){
$v165['path']['physical'] = dirname(__FILE__);
} else {
$v165['path']['physical'] = getcwd ();
}
function f111(&$v175, $id, $var){
$v496 = array($var => $id);
$v175 = array_merge ($v175, $v496);
}
if($_SERVER['PHP_SELF']){
$v377 = FALSE;
$v528 = strtolower ($_SERVER["HTTP_USER_AGENT"]);
} else {
$v377 = TRUE;
if($troubleshoot1){
$_REQUEST = array();
$v472 = get_defined_vars();
$v19 = explode("&", $v472['argv'][0]);
for($i=0;$i<sizeof($v19);$i++){
$v20 = explode("=", $v19[$i]);
f111($_REQUEST, $v20[1], $v20[0]);
}
} else {
$v472 = get_defined_vars();
$_REQUEST = $v472;
}
$v528 = strtolower ($_REQUEST["HTTP_USER_AGENT"]);
}
if($v377){
$v431 = $HTTP_SERVER_VARS['PHP_SELF'];
} else {
$v431 = $_SERVER['PHP_SELF'];
}
$v32 = explode("/", $v431);
$v405 = array_pop($v32);
$v430 = implode("/", $v32);
if($v377){
$v165['path']['www'] = "http://".$HTTP_SERVER_VARS['HTTP_HOST'].$v430;
} else {
$v165['path']['www'] = "http://".$_SERVER['HTTP_HOST'].$v430;
}
$v471 = "";
define('MPEG_VALID_CHECK_FRAMES', 35);
function f130(&$fd, &$v152) {
f131($fd, $v152, $v152['avdataoffset']);
if (isset($v152['mpeg']['audio']['bitrate_mode'])) {
$v152['audio']['bitrate_mode'] = strtolower($v152['mpeg']['audio']['bitrate_mode']);
}
if (((isset($v152['id3v2']) && ($v152['avdataoffset'] > $v152['id3v2']['headerlength'])) || (!isset($v152['id3v2']) && ($v152['avdataoffset'] > 0)))) {
$v152['warning'] .= "\n".'Unknown data before synch ';
if (isset($v152['id3v2']['headerlength'])) {
$v152['warning'] .= '(ID3v2 header ends at '.$v152['id3v2']['headerlength'].', ';
} else {
$v152['warning'] .= '(should be at beginning of file, ';
}
$v152['warning'] .= 'synch detected at '.$v152['avdataoffset'].')';
if ($v152['audio']['bitrate_mode'] == 'cbr') {
if (!empty($v152['id3v2']['headerlength']) && (($v152['avdataoffset'] - $v152['id3v2']['headerlength']) == $v152['mpeg']['audio']['framelength'])) {
$v152['warning'] .= '. This is a known problem with some versions of LAME (3.91, 3.92) DLL in CBR mode.';
$v152['audio']['codec'] = 'LAME';
} elseif (empty($v152['id3v2']['headerlength']) && ($v152['avdataoffset'] == $v152['mpeg']['audio']['framelength'])) {
$v152['warning'] .= '. This is a known problem with some versions of LAME (3.91, 3.92) DLL in CBR mode.';
$v152['audio']['codec'] = 'LAME';
}
}
}
if (isset($v152['mpeg']['audio']['layer']) && ($v152['mpeg']['audio']['layer'] == 'II')) {
$v152['audio']['dataformat'] = 'mp2';
} elseif (isset($v152['mpeg']['audio']['layer']) && ($v152['mpeg']['audio']['layer'] == 'I')) {
$v152['audio']['dataformat'] = 'mp1';
}
if ($v152['fileformat'] == 'mp3') {
switch ($v152['audio']['dataformat']) {
case 'mp1':
case 'mp2':
case 'mp3':
$v152['fileformat'] = $v152['audio']['dataformat'];
break;
default:
$v152['warning'] .= "\n".'Expecting [audio][dataformat] to be mp1/mp2/mp3 when fileformat == mp3, [audio][dataformat] actually "'.$v152['audio']['dataformat'].'"';
break;
}
}
if (empty($v152['fileformat'])) {
$v152['error'] .= "\n".'Synch not found';
unset($v152['fileformat']);
unset($v152['audio']['bitrate_mode']);
unset($v152['avdataoffset']);
unset($v152['avdataend']);
return false;
}
$v152['mime_type']         = 'audio/mpeg';
$v152['audio']['lossless'] = false;
if (!isset($v152['playtime_seconds']) && isset($v152['audio']['bitrate']) && ($v152['audio']['bitrate'] > 0)) {
$v152['playtime_seconds'] = ($v152['avdataend'] - $v152['avdataoffset']) * 8 / $v152['audio']['bitrate'];
}
if (isset($v152['mpeg']['audio']['LAME'])) {
$v152['audio']['codec'] = 'LAME';
if (!empty($v152['mpeg']['audio']['LAME']['short_version'])) {
$v152['audio']['encoder'] = trim($v152['mpeg']['audio']['LAME']['short_version']);
}
}
return true;
}
function f123($filename) {
$fd = fopen($filename, 'rb');
$v457 = getID3Filepointer($fd);
fclose($fd);
return $v457;
}
function f118($fd, $v423, &$v152, $v452=true, $v143=false, $v61=false) {
static $v121;
static $v117;
static $v107;
static $v113;
static $v109;
static $v119;
static $v111;
if (empty($v121)) {
$v121       = f87();
$v117         = f85();
$v107       = f79();
$v113     = f82();
$v109   = f80();
$v119 = f86();
$v111      = f81();
}
if ($v423 >= $v152['avdataend']) {
$v152['error'] .= "\n".'end of file encounter looking for MPEG synch';
return false;
}
fseek($fd, $v423, SEEK_SET);
$v340 = fread($fd, 192);
$v336 = substr($v340, 0, 4);
static $v114 = array();
if (isset($v114[$v336])) {
$v122 = $v114[$v336];
} else {
$v122 = f83($v336);
$v114[$v336] = $v122;
}
static $v115 = array();
if (!isset($v115[$v336])) {
$v115[$v336] = f84($v122);
}
if ($v115[$v336]) {
$v152['mpeg']['audio']['raw'] = $v122;
} else {
$v152['error'] .= "\n".'Invalid MPEG audio header at offset '.$v423;
return false;
}
if (!$v61) {
$v152['mpeg']['audio']['version']       = $v121[$v152['mpeg']['audio']['raw']['version']];
$v152['mpeg']['audio']['layer']         = $v117[$v152['mpeg']['audio']['raw']['layer']];
$v152['mpeg']['audio']['channelmode']   = $v109[$v152['mpeg']['audio']['raw']['channelmode']];
$v152['mpeg']['audio']['channels']      = (($v152['mpeg']['audio']['channelmode'] == 'mono') ? 1 : 2);
$v152['mpeg']['audio']['sample_rate']   = $v113[$v152['mpeg']['audio']['version']][$v152['mpeg']['audio']['raw']['sample_rate']];
$v152['mpeg']['audio']['protection']    = !$v152['mpeg']['audio']['raw']['protection'];
$v152['mpeg']['audio']['private']       = (bool) $v152['mpeg']['audio']['raw']['private'];
$v152['mpeg']['audio']['modeextension'] = $v119[$v152['mpeg']['audio']['layer']][$v152['mpeg']['audio']['raw']['modeextension']];
$v152['mpeg']['audio']['copyright']     = (bool) $v152['mpeg']['audio']['raw']['copyright'];
$v152['mpeg']['audio']['original']      = (bool) $v152['mpeg']['audio']['raw']['original'];
$v152['mpeg']['audio']['emphasis']      = $v111[$v152['mpeg']['audio']['raw']['emphasis']];
$v152['audio']['channels']  = $v152['mpeg']['audio']['channels'];
$v152['audio']['sample_rate'] = $v152['mpeg']['audio']['sample_rate'];
if ($v152['mpeg']['audio']['protection']) {
$v152['mpeg']['audio']['crc'] = f9(substr($v340, 4, 2));
}
}
$v152['mpeg']['audio']['padding'] = (bool) $v152['mpeg']['audio']['raw']['padding'];
$v152['mpeg']['audio']['bitrate'] = $v107[$v152['mpeg']['audio']['version']][$v152['mpeg']['audio']['layer']][$v152['mpeg']['audio']['raw']['bitrate']];
if (!$v61 && ($v152['mpeg']['audio']['layer'] == 'II')) {
$v152['audio']['dataformat'] = 'mp2';
switch ($v152['mpeg']['audio']['channelmode']) {
case 'mono':
if (($v152['mpeg']['audio']['bitrate'] == 'free') || ($v152['mpeg']['audio']['bitrate'] <= 192)) {
} else {
$v152['error'] .= "\n".$v152['mpeg']['audio']['bitrate'].'kbps not allowed in Layer II, '.$v152['mpeg']['audio']['channelmode'].'.';
return false;
}
break;
case 'stereo':
case 'joint stereo':
case 'dual channel':
if (($v152['mpeg']['audio']['bitrate'] == 'free') || ($v152['mpeg']['audio']['bitrate'] == 64) || ($v152['mpeg']['audio']['bitrate'] >= 96)) {
} else {
$v152['error'] .= "\n".$v152['mpeg']['audio']['bitrate'].'kbps not allowed in Layer II, '.$v152['mpeg']['audio']['channelmode'].'.';
return false;
}
break;
}
}
if ($v152['mpeg']['audio']['bitrate'] != 'free') {
if ($v152['mpeg']['audio']['version'] == '1') {
if ($v152['mpeg']['audio']['layer'] == 'I') {
$v67 = 48;
$v68     = ($v152['mpeg']['audio']['padding'] ? 4 : 0); 
} else { 
$v67 = 144;
$v68     = ($v152['mpeg']['audio']['padding'] ? 1 : 0); 
}
} else { 
if ($v152['mpeg']['audio']['layer'] == 'I') {
$v67 = 24;
$v68     = ($v152['mpeg']['audio']['padding'] ? 4 : 0); 
} else { 
$v67 = 72;
$v68     = ($v152['mpeg']['audio']['padding'] ? 1 : 0); 
}
}
if ($v152['audio']['sample_rate'] > 0) {
$v152['mpeg']['audio']['framelength'] = (int) floor(($v67 * 1000 * $v152['mpeg']['audio']['bitrate']) / $v152['audio']['sample_rate']) + $v68;
}
}
$v152['audio']['bitrate'] = 1000 * $v152['mpeg']['audio']['bitrate'];
if (isset($v152['mpeg']['audio']['framelength'])) {
$v415 = $v423 + $v152['mpeg']['audio']['framelength'];
} else {
$v152['error'] .= "\n".'Frame at offset('.$v423.') is has an invalid frame length.';
return false;
}
if (substr($v340, 4 + 32, 4) == 'VBRI') {
$v152['mpeg']['audio']['bitrate_mode'] = 'vbr';
$v152['mpeg']['audio']['VBR_method']  = 'Fraunhofer';
$v152['audio']['codec'] = 'Fraunhofer';
$v146 = substr($v340, 4 + 2, 32);
$v72 = 4 + 32 + strlen('VBRI');
$v75 = substr($v340, $v72, 2);
$v72 += 2;
$v152['mpeg']['audio']['VBR_encoder_version'] = f9($v75);
$v74 = substr($v340, $v72, 2);
$v72 += 2;
$v152['mpeg']['audio']['VBR_encoder_delay'] = f9($v74);
$v80 = substr($v340, $v72, 2);
$v72 += 2;
$v152['mpeg']['audio']['VBR_quality'] = f9($v80);
$v73 = substr($v340, $v72, 4);
$v72 += 4;
$v152['mpeg']['audio']['VBR_bytes'] = f9($v73);
$v76 = substr($v340, $v72, 4);
$v72 += 4;
$v152['mpeg']['audio']['VBR_frames'] = f9($v76);
$v79 = substr($v340, $v72, 2);
$v72 += 2;
$v152['mpeg']['audio']['VBR_seek_offsets'] = f9($v79);
$v72 += 4; 
$v78 = substr($v340, $v72, 2);
$v72 += 2;
$v152['mpeg']['audio']['VBR_seek_offsets_stride'] = f9($v78);
$v441 = $v423;
for ($i = 0; $i < $v152['mpeg']['audio']['VBR_seek_offsets']; $i++) {
$v77 = f9(substr($v340, $v72, 2));
$v72 += 2;
$v152['mpeg']['audio']['VBR_offsets_relative'][$i] = $v77;
$v152['mpeg']['audio']['VBR_offsets_absolute'][$i] = $v77 + $v441;
$v441 += $v77;
}
} else {
if ($v152['mpeg']['audio']['version'] == '1') {
if ($v152['mpeg']['audio']['channelmode'] == 'mono') {
$v160  = 4 + 17; 
$v146 = substr($v340, 4 + 2, 17);
} else {
$v160 = 4 + 32; 
$v146 = substr($v340, 4 + 2, 32);
}
} else { 
if ($v152['mpeg']['audio']['channelmode'] == 'mono') {
$v160 = 4 + 9;  
$v146 = substr($v340, 4 + 2, 9);
} else {
$v160 = 4 + 17; 
$v146 = substr($v340, 4 + 2, 17);
}
}
if ((substr($v340, $v160, strlen('Xing')) == 'Xing') || (substr($v340, $v160, strlen('Info')) == 'Info')) {
$v152['mpeg']['audio']['bitrate_mode'] = 'vbr';
$v152['mpeg']['audio']['VBR_method']  = 'Xing';
$v167 = $v160 + strlen('Xing');
$v152['mpeg']['audio']['xing_flags_raw'] = substr($v340, $v167, 4);
$v167 += 4;
$v166 = f7(substr($v152['mpeg']['audio']['xing_flags_raw'], 3, 1));
$v152['mpeg']['audio']['xing_flags']['frames']    = (bool) $v166{4};
$v152['mpeg']['audio']['xing_flags']['bytes']     = (bool) $v166{5};
$v152['mpeg']['audio']['xing_flags']['toc']       = (bool) $v166{6};
$v152['mpeg']['audio']['xing_flags']['vbr_scale'] = (bool) $v166{7};
if ($v152['mpeg']['audio']['xing_flags']['frames']) {
$v152['mpeg']['audio']['VBR_frames'] = f9(substr($v340, $v167, 4));
$v167 += 4;
}
if ($v152['mpeg']['audio']['xing_flags']['bytes']) {
$v152['mpeg']['audio']['VBR_bytes'] = f9(substr($v340, $v167, 4));
$v167 += 4;
}
if ($v152['mpeg']['audio']['xing_flags']['toc']) {
$v97 = substr($v340, $v167, 100);
$v167 += 100;
for ($i = 0; $i < 100; $i++) {
$v152['mpeg']['audio']['toc'][$i] = ord($v97{$i});
}
}
if ($v152['mpeg']['audio']['xing_flags']['vbr_scale']) {
$v152['mpeg']['audio']['VBR_scale'] = f9(substr($v340, $v167, 4));
$v167 += 4;
}
if (substr($v340, $v167, 4) == 'LAME') {
$v152['mpeg']['audio']['LAME']['short_version']     = substr($v340, $v167, 9);
$v167 += 9;
$v96 = f9(substr($v340, $v167, 1));
$v167 += 1;
$v152['mpeg']['audio']['LAME']['tag_revision']      = ($v96 & 0xF0) >> 4;
$v152['mpeg']['audio']['LAME']['vbr_method_raw']    = $v96 & 0x0F;
$v152['mpeg']['audio']['LAME']['vbr_method']        = f69($v152['mpeg']['audio']['LAME']['vbr_method_raw']);
$v152['mpeg']['audio']['LAME']['lowpass_frequency'] = 100 * f9(substr($v340, $v167, 1));
$v167 += 1;
$v152['mpeg']['audio']['LAME']['RGAD']['peak_amplitude'] = f8(substr($v340, $v167, 4));
$v167 += 4;
$v134 = f9(substr($v340, $v167, 2));
$v167 += 4;
$v138   = ($v134 & 0xE000) >> 13;
$v139 = '';
switch ($v138) {
case 1:
$v139 = 'radio';
break;
case 2:
$v139 = 'audiophile';
break;
case 0:  
default: 
break;
}
if ($v139) {
$v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['raw']['name']        = ($v134 & 0xE000) >> 13;
$v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['raw']['originator']  = ($v134 & 0x1C00) >> 10;
$v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['raw']['sign_bit']    = ($v134 & 0x0200) >> 9;
$v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['raw']['gain_adjust'] = $v134 & 0x01FF;
$v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['name']       = RGADnameLookup($v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['raw']['name']);
$v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['originator'] = RGADoriginatorLookup($v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['raw']['originator']);
$v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['gain_db']    = RGADadjustmentLookup($v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['raw']['gain_adjust'], $v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['raw']['sign_bit']);
$v152['replay_gain']["$v139"]['peak']       = $v152['mpeg']['audio']['LAME']['RGAD']['peak_amplitude'];
$v152['replay_gain']["$v139"]['originator'] = $v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['originator'];
$v152['replay_gain']["$v139"]['adjustment'] = $v152['mpeg']['audio']['LAME']['RGAD']["$v139"]['gain_db'];
}
$v58 = f9(substr($v340, $v167, 1));
$v167 += 1;
$v152['mpeg']['audio']['LAME']['encoding_flags']['nspsytune']   = (bool) ($v58 & 0x10);
$v152['mpeg']['audio']['LAME']['encoding_flags']['nssafejoint'] = (bool) ($v58 & 0x20);
$v152['mpeg']['audio']['LAME']['encoding_flags']['nogap_next']  = (bool) ($v58 & 0x40);
$v152['mpeg']['audio']['LAME']['encoding_flags']['nogap_prev']  = (bool) ($v58 & 0x80);
$v152['mpeg']['audio']['LAME']['ath_type'] = $v58 & 0x0F;
$v4 = f9(substr($v340, $v167, 1));
$v167 += 1;
if ($v152['mpeg']['audio']['LAME']['vbr_method_raw'] == 2) { 
$v152['mpeg']['audio']['LAME']['bitrate_abr'] = $v4;
} elseif ($v4 > 0) { 
$v152['mpeg']['audio']['LAME']['bitrate_min'] = $v4;
}
$v57 = f9(substr($v340, $v167, 3));
$v167 += 3;
$v152['mpeg']['audio']['LAME']['encoder_delay'] = ($v57 & 0xFFF000) >> 12;
$v152['mpeg']['audio']['LAME']['end_padding']   = $v57 & 0x000FFF;
$v125 = f9(substr($v340, $v167, 1));
$v167 += 1;
$v152['mpeg']['audio']['LAME']['noise_shaping_raw']       = $v58 & 0x03;
$v152['mpeg']['audio']['LAME']['stereo_mode_raw']         = ($v58 & 0x1C) >> 2;
$v152['mpeg']['audio']['LAME']['not_optimal_quality_raw'] = ($v58 & 0x20) >> 5;
$v152['mpeg']['audio']['LAME']['source_sample_freq_raw']  = ($v58 & 0xC0) >> 6;
$v152['mpeg']['audio']['LAME']['noise_shaping']       = $v152['mpeg']['audio']['LAME']['noise_shaping_raw'];
$v152['mpeg']['audio']['LAME']['stereo_mode']         = f68($v152['mpeg']['audio']['LAME']['stereo_mode_raw']);
$v152['mpeg']['audio']['LAME']['not_optimal_quality'] = (bool) $v152['mpeg']['audio']['LAME']['not_optimal_quality_raw'];
$v152['mpeg']['audio']['LAME']['source_sample_freq']  = f67($v152['mpeg']['audio']['LAME']['source_sample_freq_raw']);
$v152['mpeg']['audio']['LAME']['mp3_gain_raw'] = f9(substr($v340, $v167, 1), false, true);
$v167 += 1;
$v152['mpeg']['audio']['LAME']['mp3_gain'] = 1.5 * $v152['mpeg']['audio']['LAME']['mp3_gain_raw'];
$v140 = f9(substr($v340, $v167, 2));
$v167 += 2;
$v152['mpeg']['audio']['LAME']['audio_bytes']  = f9(substr($v340, $v167, 4));
$v167 += 4;
if ($v152['mpeg']['audio']['LAME']['audio_bytes'] > ($v152['avdataend'] - $v152['avdataoffset'])) {
$v152['warning'] .= "\n".'Probable truncated file: expecting '.$v152['mpeg']['audio']['LAME']['audio_bytes'].' bytes of audio data, only found '.($v152['avdataend'] - $v152['avdataoffset']);
}
$v152['mpeg']['audio']['LAME']['music_crc']    = f9(substr($v340, $v167, 2));
$v167 += 2;
$v152['mpeg']['audio']['LAME']['lame_tag_crc'] = f9(substr($v340, $v167, 2));
$v167 += 2;
if ($v152['mpeg']['audio']['LAME']['vbr_method_raw'] == 1) {
$v152['mpeg']['audio']['bitrate_mode'] = 'cbr';
if (empty($v152['mpeg']['audio']['bitrate']) || ($v152['mpeg']['audio']['LAME']['bitrate_min'] != 255)) {
$v152['mpeg']['audio']['bitrate'] = $v152['mpeg']['audio']['LAME']['bitrate_min'];
}
}
}
} else {
$v152['mpeg']['audio']['bitrate_mode'] = 'cbr';
if ($v452) {
$v152['mpeg']['audio']['bitrate_mode'] = 'vbr';
if (f100($fd, $v152, $v423, $v415, true)) {
$v452 = false;
$v152['mpeg']['audio']['bitrate_mode'] = 'cbr';
}
if ($v152['mpeg']['audio']['bitrate_mode'] == 'vbr') {
$v152['warning'] .= "\n".'VBR file with no VBR header. Bitrate values calculated from actual frame bitrates.';
}
}
}
}
if (($v152['mpeg']['audio']['bitrate_mode'] == 'vbr') && isset($v152['mpeg']['audio']['VBR_frames']) && ($v152['mpeg']['audio']['VBR_frames'] > 1)) {
$v152['mpeg']['audio']['VBR_frames']--; 
if (($v152['mpeg']['audio']['version'] == '1') && ($v152['mpeg']['audio']['layer'] == 'I')) {
$v152['mpeg']['audio']['VBR_bitrate'] = ((($v152['mpeg']['audio']['VBR_bytes'] / $v152['mpeg']['audio']['VBR_frames']) * 8) * ($v152['audio']['sample_rate'] / 384)) / 1000;
} elseif ((($v152['mpeg']['audio']['version'] == '2') || ($v152['mpeg']['audio']['version'] == '2.5')) && ($v152['mpeg']['audio']['layer'] == 'III')) {
$v152['mpeg']['audio']['VBR_bitrate'] = ((($v152['mpeg']['audio']['VBR_bytes'] / $v152['mpeg']['audio']['VBR_frames']) * 8) * ($v152['audio']['sample_rate'] / 576)) / 1000;
} else {
$v152['mpeg']['audio']['VBR_bitrate'] = ((($v152['mpeg']['audio']['VBR_bytes'] / $v152['mpeg']['audio']['VBR_frames']) * 8) * ($v152['audio']['sample_rate'] / 1152)) / 1000;
}
if ($v152['mpeg']['audio']['VBR_bitrate'] > 0) {
$v152['audio']['bitrate']         = 1000 * $v152['mpeg']['audio']['VBR_bitrate'];
$v152['mpeg']['audio']['bitrate'] = $v152['mpeg']['audio']['VBR_bitrate']; 
}
}
if ($v452) {
if (!f100($fd, $v152, $v423, $v415, $v143)) {
return false;
}
}
if (false) {
$v145 = f7($v146);
$v147 = 0;
if ($v152['mpeg']['audio']['version'] == '1') {
if ($v152['mpeg']['audio']['channelmode'] == 'mono') {
$v152['mpeg']['audio']['side_info']['main_data_begin'] = substr($v145, $v147, 9);
$v147 += 9;
$v147 += 5;
} else {
$v152['mpeg']['audio']['side_info']['main_data_begin'] = substr($v145, $v147, 9);
$v147 += 9;
$v147 += 3;
}
} else { 
if ($v152['mpeg']['audio']['channelmode'] == 'mono') {
$v152['mpeg']['audio']['side_info']['main_data_begin'] = substr($v145, $v147, 8);
$v147 += 8;
$v147 += 1;
} else {
$v152['mpeg']['audio']['side_info']['main_data_begin'] = substr($v145, $v147, 8);
$v147 += 8;
$v147 += 2;
}
}
if ($v152['mpeg']['audio']['version'] == '1') {
for ($v211 = 0; $v211 < $v152['audio']['channels']; $v211++) {
for ($v470 = 0; $v470 < 4; $v470++) {
$v152['mpeg']['audio']['scfsi'][$v211][$v470] = substr($v145, $v147, 1);
$v147 += 2;
}
}
}
for ($v334 = 0; $v334 < (($v152['mpeg']['audio']['version'] == '1') ? 2 : 1); $v334++) {
for ($v211 = 0; $v211 < $v152['audio']['channels']; $v211++) {
$v152['mpeg']['audio']['part2_3_length'][$v334][$v211] = substr($v145, $v147, 12);
$v147 += 12;
$v152['mpeg']['audio']['big_values'][$v334][$v211] = substr($v145, $v147, 9);
$v147 += 9;
$v152['mpeg']['audio']['global_gain'][$v334][$v211] = substr($v145, $v147, 8);
$v147 += 8;
if ($v152['mpeg']['audio']['version'] == '1') {
$v152['mpeg']['audio']['scalefac_compress'][$v334][$v211] = substr($v145, $v147, 4);
$v147 += 4;
} else {
$v152['mpeg']['audio']['scalefac_compress'][$v334][$v211] = substr($v145, $v147, 9);
$v147 += 9;
}
$v152['mpeg']['audio']['window_switching_flag'][$v334][$v211] = substr($v145, $v147, 1);
$v147 += 1;
if ($v152['mpeg']['audio']['window_switching_flag'][$v334][$v211] == '1') {
$v152['mpeg']['audio']['block_type'][$v334][$v211] = substr($v145, $v147, 2);
$v147 += 2;
$v152['mpeg']['audio']['mixed_block_flag'][$v334][$v211] = substr($v145, $v147, 1);
$v147 += 1;
for ($v453 = 0; $v453 < 2; $v453++) {
$v152['mpeg']['audio']['table_select'][$v334][$v211][$v453] = substr($v145, $v147, 5);
$v147 += 5;
}
$v152['mpeg']['audio']['table_select'][$v334][$v211][2] = 0;
for ($v542 = 0; $v542 < 3; $v542++) {
$v152['mpeg']['audio']['subblock_gain'][$v334][$v211][$v542] = substr($v145, $v147, 3);
$v147 += 3;
}
} else {
for ($v453 = 0; $v453 < 3; $v453++) {
$v152['mpeg']['audio']['table_select'][$v334][$v211][$v453] = substr($v145, $v147, 5);
$v147 += 5;
}
$v152['mpeg']['audio']['region0_count'][$v334][$v211] = substr($v145, $v147, 4);
$v147 += 4;
$v152['mpeg']['audio']['region1_count'][$v334][$v211] = substr($v145, $v147, 3);
$v147 += 3;
$v152['mpeg']['audio']['block_type'][$v334][$v211] = 0;
}
if ($v152['mpeg']['audio']['version'] == '1') {
$v152['mpeg']['audio']['preflag'][$v334][$v211] = substr($v145, $v147, 1);
$v147 += 1;
}
$v152['mpeg']['audio']['scalefac_scale'][$v334][$v211] = substr($v145, $v147, 1);
$v147 += 1;
$v152['mpeg']['audio']['count1table_select'][$v334][$v211] = substr($v145, $v147, 1);
$v147 += 1;
}
}
}
return true;
}
function f100(&$fd, &$v152, &$v423, &$v415, $v143) {
for ($i = 0; $i < MPEG_VALID_CHECK_FRAMES; $i++) {
if (($v415 + 4) >= $v152['avdataend']) {
return true;
}
$v414 = array('error'=>'', 'warning'=>'', 'avdataend'=>$v152['avdataend'], 'avdataoffset'=>$v152['avdataoffset']);
if (f118($fd, $v415, $v414, false)) {
if ($v143) {
if (!isset($v414['mpeg']['audio']['bitrate']) || !isset($v152['mpeg']['audio']['bitrate']) || ($v414['mpeg']['audio']['bitrate'] != $v152['mpeg']['audio']['bitrate'])) {
return false;
}
}
if (isset($v414['mpeg']['audio']['framelength']) && ($v414['mpeg']['audio']['framelength'] > 0)) {
$v415 += $v414['mpeg']['audio']['framelength'];
} else {
$v152['error'] .= "\n".'Frame at offset ('.$v423.') is has an invalid frame length.';
return false;
}
} else {
$v152['error'] .= "\n".'Frame at offset ('.$v423.') is valid, but the next one at ('.$v415.') is not.';
return false;
}
}
return true;
}
function f131($fd, &$v152, $v184, $v45=false) {
fseek($fd, $v184, SEEK_SET);
$v337 = '';
$v150 = 0;
if (!defined('CONST_FF')) {
define('CONST_FF', chr(0xFF));
define('CONST_E0', chr(0xE0));
}
static $v121;
static $v117;
static $v107;
if (empty($v121)) {
$v121 = f87();
$v117   = f85();
$v107 = f79();
}
$v338 = strlen($v337) - round(FREAD_BUFFER_SIZE / 2);
while (true) {
if (($v150 > $v338) && (($v184 + $v150)  < $v152['avdataend']) && !feof($fd)) {
if ($v150 > 65536) {
$v152['error'] .= "\n".'could not find valid MPEG synch within the first 65536 bytes';
if (isset($v152['audio']['bitrate'])) {
unset($v152['audio']['bitrate']);
}
if (isset($v152['mpeg']['audio'])) {
unset($v152['mpeg']['audio']);
}
if (isset($v152['mpeg']) && (!is_array($v152['mpeg']) || (count($v152['mpeg']) == 0))) {
unset($v152['mpeg']);
}
return false;
} elseif ($v337 .= fread($fd, FREAD_BUFFER_SIZE)) {
$v338 = strlen($v337) - round(FREAD_BUFFER_SIZE / 2);
} else {
$v152['error'] .= "\n".'could not find valid MPEG synch before end of file';
if (isset($v152['audio']['bitrate'])) {
unset($v152['audio']['bitrate']);
}
if (isset($v152['mpeg']['audio'])) {
unset($v152['mpeg']['audio']);
}
if (isset($v152['mpeg']) && (!is_array($v152['mpeg']) || (count($v152['mpeg']) == 0))) {
unset($v152['mpeg']);
}
return false;
}
}
if (($v337{$v150} == CONST_FF) && ($v337{($v150 + 1)} > CONST_E0)) { 
if (!isset($v64) && !isset($v152['mpeg']['audio'])) {
$v64 = $v152;
$v63 = $v184 + $v150;
if (!f118($fd, $v184 + $v150, $v64, false)) {
unset($v64);
}
}
$v238 = $v152; 
if (f118($fd, $v184 + $v150, $v238, true)) {
$v152 = $v238;
$v152['avdataoffset'] = $v184 + $v150;
switch ($v152['fileformat']) {
case '':
case 'id3':
case 'ape':
case 'mp3':
$v152['fileformat']               = 'mp3';
$v152['audio']['dataformat']      = 'mp3';
}
if (isset($v64['mpeg']['audio']['bitrate_mode']) && ($v64['mpeg']['audio']['bitrate_mode'] == 'vbr')) {
if (!f23($v152['audio']['bitrate'], $v64['audio']['bitrate'], 1)) {
$v152 = $v64;
$v152['avdataoffset']        = $v63;
$v152['fileformat']          = 'mp3';
$v152['audio']['dataformat'] = 'mp3';
$v238                               = $v152;
$v84 = $v63 + $v64['mpeg']['audio']['framelength'];
$v83   = $v184 + $v150;
if (f118($fd, $v83, $v238, true, true)) {
$v152 = $v238;
$v152['warning'] .= "\n".'apparently-valid VBR header not used because could not find '.MPEG_VALID_CHECK_FRAMES.' consecutive MPEG-audio frames immediately after VBR header (garbage data for '.($v83 - $v84).' bytes between '.$v84.' and '.$v83.'), but did find valid CBR stream starting at '.$v83;
$v152['avdataoffset'] = $v83;
} else {
$v152['warning'] .= "\n".'using data from VBR header even though could not find '.MPEG_VALID_CHECK_FRAMES.' consecutive MPEG-audio frames immediately after VBR header (garbage data for '.($v83 - $v84).' bytes between '.$v84.' and '.$v83.')';
}
}
}
if (isset($v152['mpeg']['audio']['bitrate_mode']) && ($v152['mpeg']['audio']['bitrate_mode'] == 'vbr') && !isset($v152['mpeg']['audio']['VBR_method'])) {
$v45 = true;
}
if ($v45) {
$v152['mpeg']['audio']['stereo_distribution'] = array('stereo'=>0, 'joint stereo'=>0, 'dual channel'=>0, 'mono'=>0);
if ($v152['mpeg']['audio']['version'] == '1') {
if ($v152['mpeg']['audio']['layer'] == 'III') {
$v152['mpeg']['audio']['bitrate_distribution'] = array('free'=>0, 32=>0, 40=>0, 48=>0, 56=>0, 64=>0, 80=>0, 96=>0, 112=>0, 128=>0, 160=>0, 192=>0, 224=>0, 256=>0, 320=>0);
} elseif ($v152['mpeg']['audio']['layer'] == 'II') {
$v152['mpeg']['audio']['bitrate_distribution'] = array('free'=>0, 32=>0, 48=>0, 56=>0, 64=>0, 80=>0, 96=>0, 112=>0, 128=>0, 160=>0, 192=>0, 224=>0, 256=>0, 320=>0, 384=>0);
} elseif ($v152['mpeg']['audio']['layer'] == 'I') {
$v152['mpeg']['audio']['bitrate_distribution'] = array('free'=>0, 32=>0, 64=>0, 96=>0, 128=>0, 160=>0, 192=>0, 224=>0, 256=>0, 288=>0, 320=>0, 352=>0, 384=>0, 416=>0, 448=>0);
}
} elseif ($v152['mpeg']['audio']['layer'] == 'I') {
$v152['mpeg']['audio']['bitrate_distribution'] = array('free'=>0, 32=>0, 48=>0, 56=>0, 64=>0, 80=>0, 96=>0, 112=>0, 128=>0, 144=>0, 160=>0, 176=>0, 192=>0, 224=>0, 256=>0);
} else {
$v152['mpeg']['audio']['bitrate_distribution'] = array('free'=>0, 8=>0, 16=>0, 24=>0, 32=>0, 40=>0, 48=>0, 56=>0, 64=>0, 80=>0, 96=>0, 112=>0, 128=>0, 144=>0, 160=>0);
}
$v238 = array('error'=>$v152['error'], 'warning'=>$v152['warning'], 'avdataend'=>$v152['avdataend'], 'avdataoffset'=>$v152['avdataoffset']);
$v491 = $v152['avdataoffset'];
$v62 = false;
while (f118($fd, $v491, $v238, false, false, $v62)) {
$v62 = true;
$v513 = $v107[$v121[$v238['mpeg']['audio']['raw']['version']]][$v117[$v238['mpeg']['audio']['raw']['layer']]][$v238['mpeg']['audio']['raw']['bitrate']];
$v152['mpeg']['audio']['bitrate_distribution'][$v513]++;
$v152['mpeg']['audio']['stereo_distribution'][$v238['mpeg']['audio']['channelmode']]++;
if (!isset($v238['mpeg']['audio']['framelength'])) {
$v152['error'] .= "\n".'Invalid/missing framelength in histogram analysis - aborting';
return false;
}
$v491 += $v238['mpeg']['audio']['framelength'];
}
$v202     = 0;
$v326 = 0;
foreach ($v152['mpeg']['audio']['bitrate_distribution'] as $v200 => $v199) {
$v326 += $v199;
if ($v200 != 'free') {
$v202 += ($v200 * $v199);
}
}
if ($v326 == 0) {
$v152['error'] .= "\n".'Corrupt MP3 file: framecounter == zero';
return false;
}
$v152['mpeg']['audio']['frame_count'] = $v326;
$v152['mpeg']['audio']['bitrate']     = 1000 * ($v202 / $v326);
$v152['audio']['bitrate'] = $v152['mpeg']['audio']['bitrate'];
$v237 = 0;
foreach ($v152['mpeg']['audio']['bitrate_distribution'] as $v198 => $v197) {
if ($v197 > 0) {
$v237++;
}
}
if ($v237 > 1) {
$v152['mpeg']['audio']['bitrate_mode'] = 'vbr';
} else {
$v152['mpeg']['audio']['bitrate_mode'] = 'cbr';
}
$v152['audio']['bitrate_mode'] = $v152['mpeg']['audio']['bitrate_mode'];
}
break; 
}
}
$v150++;
if (($v184 + $v150) >= $v152['avdataend']) {
if (empty($v152['mpeg']['audio'])) {
$v152['error'] .= "\n".'could not find valid MPEG synch before end of file';
if (isset($v152['audio']['bitrate'])) {
unset($v152['audio']['bitrate']);
}
if (isset($v152['mpeg']['audio'])) {
unset($v152['mpeg']['audio']);
}
if (isset($v152['mpeg']) && (!is_array($v152['mpeg']) || empty($v152['mpeg']))) {
unset($v152['mpeg']);
}
return false;
}
break;
}
}
$v152['audio']['bits_per_sample'] = 16;
$v152['audio']['channels']        = $v152['mpeg']['audio']['channels'];
$v152['audio']['channelmode']     = $v152['mpeg']['audio']['channelmode'];
$v152['audio']['sample_rate']     = $v152['mpeg']['audio']['sample_rate'];
return true;
}
function f87() {
static $v120 = array('2.5', false, '2', '1');
return $v120;
}
function f85() {
static $v116 = array(false, 'III', 'II', 'I');
return $v116;
}
function f79() {
static $v106;
if (empty($v106)) {
$v106['1']['I']     = array('free', 32, 64, 96, 128, 160, 192, 224, 256, 288, 320, 352, 384, 416, 448);
$v106['1']['II']    = array('free', 32, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320, 384);
$v106['1']['III']   = array('free', 32, 40, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320);
$v106['2']['I']     = array('free', 32, 48, 56, 64, 80, 96, 112, 128, 144, 160, 176, 192, 224, 256);
$v106['2.5']['I']   = $v106['2']['I'];
$v106['2']['II']    = array('free', 8, 16, 24, 32, 40, 48, 56, 64, 80, 96, 112, 128, 144, 160);
$v106['2']['III']   = $v106['2']['II'];
$v106['2.5']['II']  = $v106['2']['II'];
$v106['2.5']['III'] = $v106['2']['II'];
}
return $v106;
}
function f82() {
static $v112;
if (empty($v112)) {
$v112['1']   = array(44100, 48000, 32000);
$v112['2']   = array(22050, 24000, 16000);
$v112['2.5'] = array(11025, 12000,  8000);
}
return $v112;
}
function f80() {
static $v108 = array('stereo', 'joint stereo', 'dual channel', 'mono');
return $v108;
}
function f86() {
static $v118;
if (empty($v118)) {
$v118['I']   = array('4-31', '8-31', '12-31', '16-31');
$v118['II']  = array('4-31', '8-31', '12-31', '16-31');
$v118['III'] = array('', 'IS', 'MS', 'IS+MS');
}
return $v118;
}
function f81() {
static $v110 = array('none', '50/15ms', false, 'CCIT J.17');
return $v110;
}
function f84($v447, $v240=false) {
if (($v447['synch'] & 0x0FFE) != 0x0FFE) {
return false;
}
static $v121;
static $v117;
static $v107;
static $v113;
static $v109;
static $v119;
static $v111;
if (empty($v121)) {
$v121       = f87();
$v117         = f85();
$v107       = f79();
$v113     = f82();
$v109   = f80();
$v119 = f86();
$v111      = f81();
}
if (isset($v121[$v447['version']])) {
$v229 = $v121[$v447['version']];
} else {
if ($v240) {
echo "\n".'invalid Version ('.$v447['version'].')';
}
return false;
}
if (isset($v117[$v447['layer']])) {
$v228 = $v117[$v447['layer']];
} else {
if ($v240) {
echo "\n".'invalid Layer ('.$v447['layer'].')';
}
return false;
}
if (!isset($v107[$v229][$v228][$v447['bitrate']])) {
if ($v240) {
echo "\n".'invalid Bitrate ('.$v447['bitrate'].')';
}
return false;
}
if (!isset($v113[$v229][$v447['sample_rate']])) {
if ($v240) {
echo "\n".'invalid Frequency ('.$v447['sample_rate'].')';
}
return false;
}
if (!isset($v109[$v447['channelmode']])) {
if ($v240) {
echo "\n".'invalid ChannelMode ('.$v447['channelmode'].')';
}
return false;
}
if (!isset($v119[$v228][$v447['modeextension']])) {
if ($v240) {
echo "\n".'invalid Mode Extension ('.$v447['modeextension'].')';
}
return false;
}
if (!isset($v111[$v447['emphasis']])) {
if ($v240) {
echo "\n".'invalid Emphasis ('.$v447['emphasis'].')';
}
return false;
}
return true;
}
function f83($v88) {
if (strlen($v88) != 4) {
return false;
}
$v123['synch']         = (f9(substr($v88, 0, 2)) & 0xFFE0) >> 4;
$v123['version']       = (ord($v88{1}) & 0x18) >> 3; 
$v123['layer']         = (ord($v88{1}) & 0x06) >> 1; 
$v123['protection']    = (ord($v88{1}) & 0x01);      
$v123['bitrate']       = (ord($v88{2}) & 0xF0) >> 4; 
$v123['sample_rate']   = (ord($v88{2}) & 0x0C) >> 2; 
$v123['padding']       = (ord($v88{2}) & 0x02) >> 1; 
$v123['private']       = (ord($v88{2}) & 0x01);      
$v123['channelmode']   = (ord($v88{3}) & 0xC0) >> 6; 
$v123['modeextension'] = (ord($v88{3}) & 0x30) >> 4; 
$v123['copyright']     = (ord($v88{3}) & 0x08) >> 3; 
$v123['original']      = (ord($v88{3}) & 0x04) >> 2; 
$v123['emphasis']      = (ord($v88{3}) & 0x03);      
return $v123;
}
function f69($v161) {
static $v98 = array();
if (empty($v98)) {
$v98[0x00] = 'unknown';
$v98[0x01] = 'cbr';
$v98[0x02] = 'abr';
$v98[0x03] = 'vbr-old / vbr-rh';
$v98[0x04] = 'vbr-mtrh';
$v98[0x05] = 'vbr-new / vbr-mt';
}
return (isset($v98[$v161]) ? $v98[$v161] : '');
}
function f68($v149) {
static $v95 = array();
if (empty($v95)) {
$v95[0] = 'mono';
$v95[1] = 'stereo';
$v95[2] = 'dual';
$v95[3] = 'joint';
$v95[4] = 'forced';
$v95[5] = 'auto';
$v95[6] = 'intensity';
$v95[7] = 'other';
}
return (isset($v95[$v149]) ? $v95[$v149] : '');
}
function f67($v148) {
static $v94 = array();
if (empty($v94)) {
$v94[0] = '<= 32 kHz';
$v94[1] = '44.1 kHz';
$v94[2] = '48 kHz';
$v94[3] = '> 48kHz';
}
return (isset($v95[$v148]) ? $v95[$v148] : '');
}
function f92(&$fd, &$v423, $v397) {
fseek($fd, $v423, SEEK_SET);
while ($v215 = fread($fd, 4)) {
if ($v215{0} === chr(0)) {
$v215 = substr($v215, 1, 3).fread($fd, 1);
}
$v216 = f73(fread($fd, 4));
if ($v216 <= 0) {
break;
}
switch ($v215) {
case 'RIFF':
case 'SDSS': 
case 'LIST':
$v381 = fread($fd, 4);
$v423 = ftell($fd);
if ($v423 >= $v397) {
$v131 = array_merge_recursive($v131, f92($fd, $v423, $v423 + $v216));
} else {
$v131["$v381"] = f92($fd, $v423, $v423 + $v216);
}
$v423 = ftell($fd) + $v216;
fseek($fd, $v423, SEEK_CUR);
break;
default:
if (isset($v131["$v215"]) && is_array($v131["$v215"])) {
$v514 = count($v131["$v215"]);
} else {
$v514 = 0;
}
$v131["$v215"]["$v514"]['size'] = $v216;
if ($v216 <= 128) {
$v131["$v215"]["$v514"]['data'] = fread($fd, $v216);
} else {
fseek($fd, $v216, SEEK_CUR);
}
$v423 = ftell($fd);
break;
}
}
if (isset($v131)) {
return $v131;
} else {
return FALSE;
}
}
function f98($v539) {
static $v132 = array();
if (count($v132) < 1) {
$v132[0x0000] = 'Unknown';
$v132[0x0001] = 'Microsoft Pulse Code Modulation (PCM)';
$v132[0x0002] = 'Microsoft ADPCM';
$v132[0x0005] = 'IBM CVSD';
$v132[0x0006] = 'Microsoft A-Law';
$v132[0x0007] = 'Microsoft mu-Law';
$v132[0x0010] = 'OKI ADPCM';
$v132[0x0011] = 'Intel DVI/IMA ADPCM';
$v132[0x0012] = 'Videologic Mediaspace ADPCM';
$v132[0x0013] = 'Sierra Semiconductor ADPCM';
$v132[0x0014] = 'Antex Electronics G723 ADPCM';
$v132[0x0015] = 'DSP Solutions DigiSTD';
$v132[0x0016] = 'DSP Solutions DigiFIX';
$v132[0x0017] = 'Dialogic OKI ADPCM';
$v132[0x0020] = 'Yamaha ADPCM';
$v132[0x0021] = 'Speech Compression Sonarc';
$v132[0x0022] = 'DSP Group Truespeech';
$v132[0x0023] = 'Echo Speech EchoSC1';
$v132[0x0024] = 'Audiofile AF36';
$v132[0x0025] = 'Audio Processing Technology APTX';
$v132[0x0026] = 'Audiofile AF10';
$v132[0x0030] = 'Dolby AC2';
$v132[0x0031] = 'Microsoft GSM 6.10';
$v132[0x0033] = 'Antex Electronics ADPCME';
$v132[0x0034] = 'Control Resources VQLPC';
$v132[0x0035] = 'DSP Solutions DigiREAL';
$v132[0x0036] = 'DSP Solutions DigiADPCM';
$v132[0x0037] = 'Control Resources CR10';
$v132[0x0038] = 'Natural MicroSystems VBXADPCM';
$v132[0x0039] = 'Crystal Semiconductor IMA ADPCM';
$v132[0x0040] = 'Antex Electronics GS721 ADPCM';
$v132[0x0050] = 'Microsoft MPEG';
$v132[0x0055] = 'Microsoft ACM: LAME MP3 encoder (ACM)';
$v132[0x0101] = 'IBM mu-law';
$v132[0x0102] = 'IBM A-law';
$v132[0x0103] = 'IBM AVC Adaptive Differential Pulse Code Modulation (ADPCM)';
$v132[0x0161] = 'Microsoft ACM: DivX ;-) Audio';
$v132[0x0200] = 'Creative Labs ADPCM';
$v132[0x0202] = 'Creative Labs Fastspeech8';
$v132[0x0203] = 'Creative Labs Fastspeech10';
$v132[0x0300] = 'Fujitsu FM Towns Snd';
$v132[0x1000] = 'Olivetti GSM';
$v132[0x1001] = 'Olivetti ADPCM';
$v132[0x1002] = 'Olivetti CELP';
$v132[0x1003] = 'Olivetti SBC';
$v132[0x1004] = 'Olivetti OPR';
$v132[0xFFFF] = 'development';
}
return (isset($v132["$v539"]) ? $v132["$v539"] : '');
}
function f132(&$fd, &$v105) {
$v105['fileformat']   = 'riff';
$v105['bitrate_mode'] = 'cbr';
$v423 = 0;
rewind($fd);
$v105['RIFF'] = f92($fd, $v423, $v105['filesize']);
$v482 = 0;
if (!is_array($v105['RIFF'])) {
$v105['error'] .= "\n".'Cannot parse RIFF (this is maybe not a RIFF / WAV / AVI file?)';
unset($v105['RIFF']);
unset($v105['fileformat']);
return FALSE;
}
$v179 = array_keys($v105['RIFF']);
switch ($v179[0]) {
case 'WAVE':
$v105['fileformat'] = 'wav';
if (isset($v105['RIFF']['WAVE']['fmt '][0]['data'])) {
$v274 = $v105['RIFF']['WAVE']['fmt '][0]['data'];
$v105['RIFF']['raw']['fmt ']['wFormatTag']      = f73(substr($v274,  0, 2));
$v105['RIFF']['raw']['fmt ']['nChannels']       = f73(substr($v274,  2, 2));
$v105['RIFF']['raw']['fmt ']['nSamplesPerSec']  = f73(substr($v274,  4, 4));
$v105['RIFF']['raw']['fmt ']['nAvgBytesPerSec'] = f73(substr($v274,  8, 4));
$v105['RIFF']['raw']['fmt ']['nBlockAlign']     = f73(substr($v274, 12, 2));
$v105['RIFF']['raw']['fmt ']['nBitsPerSample']  = f73(substr($v274, 14, 2));
$v105['RIFF']['audio']["$v482"]['format']        = f98($v105['RIFF']['raw']['fmt ']['wFormatTag']);
$v105['RIFF']['audio']["$v482"]['channels']      = $v105['RIFF']['raw']['fmt ']['nChannels'];
$v105['RIFF']['audio']["$v482"]['channelmode']   = (($v105['RIFF']['audio']["$v482"]['channels'] == 1) ? 'mono' : 'stereo');
$v105['RIFF']['audio']["$v482"]['frequency']     = $v105['RIFF']['raw']['fmt ']['nSamplesPerSec'];
$v105['RIFF']['audio']["$v482"]['bitrate']       = $v105['RIFF']['raw']['fmt ']['nAvgBytesPerSec'] * 8;
$v105['RIFF']['audio']["$v482"]['bitspersample'] = $v105['RIFF']['raw']['fmt ']['nBitsPerSample'];
if (!isset($v105['frequency'])) {
$v105['frequency'] = $v105['RIFF']['audio']["$v482"]['frequency'];
}
if (!isset($v105['channels'])) {
$v105['channels']  = $v105['RIFF']['audio']["$v482"]['channels'];
}
if (!isset($v105['bitrate_audio']) && isset($v105['RIFF']['audio']["$v482"]['bitrate']) && isset($v105['audiobytes'])) {
$v105['bitrate_audio']    = $v105['RIFF']['audio']["$v482"]['bitrate'];
$v105['playtime_seconds'] = (float) (($v105['audiobytes'] * 8) / $v105['bitrate_audio']);
}
}
if (isset($v105['RIFF']['WAVE']['rgad'][0]['data'])) {
$v463 = $v105['RIFF']['WAVE']['rgad'][0]['data'];
$v105['RIFF']['raw']['rgad']['fPeakAmplitude']      = f72(substr($v463, 0, 4));
$v105['RIFF']['raw']['rgad']['nRadioRgAdjust']      = f73(substr($v463, 4, 2));
$v105['RIFF']['raw']['rgad']['nAudiophileRgAdjust'] = f73(substr($v463, 6, 2));
$v408      = str_pad(f30($v105['RIFF']['raw']['rgad']['nRadioRgAdjust']), 16, '0', STR_PAD_LEFT);
$v407 = str_pad(f30($v105['RIFF']['raw']['rgad']['nAudiophileRgAdjust']), 16, '0', STR_PAD_LEFT);
$v105['RIFF']['raw']['rgad']['radio']['name']       = f11(substr($v408, 0, 3));
$v105['RIFF']['raw']['rgad']['radio']['originator'] = f11(substr($v408, 3, 3));
$v105['RIFF']['raw']['rgad']['radio']['signbit']    = f11(substr($v408, 6, 1));
$v105['RIFF']['raw']['rgad']['radio']['adjustment'] = f11(substr($v408, 7, 9));
$v105['RIFF']['raw']['rgad']['audiophile']['name']       = f11(substr($v407, 0, 3));
$v105['RIFF']['raw']['rgad']['audiophile']['originator'] = f11(substr($v407, 3, 3));
$v105['RIFF']['raw']['rgad']['audiophile']['signbit']    = f11(substr($v407, 6, 1));
$v105['RIFF']['raw']['rgad']['audiophile']['adjustment'] = f11(substr($v407, 7, 9));
$v105['RIFF']['rgad']['peakamplitude'] = $v105['RIFF']['raw']['rgad']['fPeakAmplitude'];
if (($v105['RIFF']['raw']['rgad']['radio']['name'] != 0) && ($v105['RIFF']['raw']['rgad']['radio']['originator'] != 0)) {
$v105['RIFF']['rgad']['radio']['name']            = RGADnameLookup($v105['RIFF']['raw']['rgad']['radio']['name']);
$v105['RIFF']['rgad']['radio']['originator']      = RGADoriginatorLookup($v105['RIFF']['raw']['rgad']['radio']['originator']);
$v105['RIFF']['rgad']['radio']['adjustment']      = RGADadjustmentLookup($v105['RIFF']['raw']['rgad']['radio']['adjustment'], $v105['RIFF']['raw']['rgad']['radio']['signbit']);
}
if (($v105['RIFF']['raw']['rgad']['audiophile']['name'] != 0) && ($v105['RIFF']['raw']['rgad']['audiophile']['originator'] != 0)) {
$v105['RIFF']['rgad']['audiophile']['name']       = RGADnameLookup($v105['RIFF']['raw']['rgad']['audiophile']['name']);
$v105['RIFF']['rgad']['audiophile']['originator'] = RGADoriginatorLookup($v105['RIFF']['raw']['rgad']['audiophile']['originator']);
$v105['RIFF']['rgad']['audiophile']['adjustment'] = RGADadjustmentLookup($v105['RIFF']['raw']['rgad']['audiophile']['adjustment'], $v105['RIFF']['raw']['rgad']['audiophile']['signbit']);
}
}
if (isset($v105['RIFF']['WAVE']['fact'][0]['data'])) {
$v105['RIFF']['raw']['fact']['NumberOfSamples'] = f73(substr($v105['RIFF']['WAVE']['fact'][0]['data'], 0, 4));
if (isset($v105['RIFF']['raw']['fmt ']['nSamplesPerSec']) && $v105['RIFF']['raw']['fmt ']['nSamplesPerSec']) {
$v105['playtime_seconds'] = (float) $v105['RIFF']['raw']['fact']['NumberOfSamples'] / $v105['RIFF']['raw']['fmt ']['nSamplesPerSec'];
}
if (isset($v105['RIFF']['raw']['fmt ']['nAvgBytesPerSec']) && $v105['RIFF']['raw']['fmt ']['nAvgBytesPerSec']) {
$v105['audiobytes']    = f20(round($v105['playtime_seconds'] * $v105['RIFF']['raw']['fmt ']['nAvgBytesPerSec']));
$v105['bitrate_audio'] = f20($v105['RIFF']['raw']['fmt ']['nAvgBytesPerSec'] * 8);
}
}
if (!isset($v105['audiobytes']) && isset($v105['RIFF']['WAVE']['data'][0]['size'])) {
$v105['audiobytes'] = $v105['RIFF']['WAVE']['data'][0]['size'];
}
if (!isset($v105['bitrate_audio']) && isset($v105['RIFF']['audio']["$v482"]['bitrate']) && isset($v105['audiobytes'])) {
$v105['bitrate_audio']    = $v105['RIFF']['audio']["$v482"]['bitrate'];
$v105['playtime_seconds'] = (float) (($v105['audiobytes'] * 8) / $v105['bitrate_audio']);
}
break;
case 'AVI ':
$v105['fileformat'] = 'avi';
if (isset($v105['RIFF']['AVI ']['hdrl']['avih']["$v482"]['data'])) {
$v185 = $v105['RIFF']['AVI ']['hdrl']['avih']["$v482"]['data'];
$v105['RIFF']['raw']['avih']['dwMicroSecPerFrame']    = f73(substr($v185,  0, 4)); 
$v105['RIFF']['raw']['avih']['dwMaxBytesPerSec']      = f73(substr($v185,  4, 4)); 
$v105['RIFF']['raw']['avih']['dwPaddingGranularity']  = f73(substr($v185,  8, 4)); 
$v105['RIFF']['raw']['avih']['dwFlags']               = f73(substr($v185, 12, 4)); 
$v105['RIFF']['raw']['avih']['dwTotalFrames']         = f73(substr($v185, 16, 4)); 
$v105['RIFF']['raw']['avih']['dwInitialFrames']       = f73(substr($v185, 20, 4));
$v105['RIFF']['raw']['avih']['dwStreams']             = f73(substr($v185, 24, 4));
$v105['RIFF']['raw']['avih']['dwSuggestedBufferSize'] = f73(substr($v185, 28, 4));
$v105['RIFF']['raw']['avih']['dwWidth']               = f73(substr($v185, 32, 4));
$v105['RIFF']['raw']['avih']['dwHeight']              = f73(substr($v185, 36, 4));
$v105['RIFF']['raw']['avih']['dwScale']               = f73(substr($v185, 40, 4));
$v105['RIFF']['raw']['avih']['dwRate']                = f73(substr($v185, 44, 4));
$v105['RIFF']['raw']['avih']['dwStart']               = f73(substr($v185, 48, 4));
$v105['RIFF']['raw']['avih']['dwLength']              = f73(substr($v185, 52, 4));
$v105['RIFF']['raw']['avih']['flags']['hasindex']     = (bool) ($v105['RIFF']['raw']['avih']['dwFlags'] & 0x00000010);
$v105['RIFF']['raw']['avih']['flags']['mustuseindex'] = (bool) ($v105['RIFF']['raw']['avih']['dwFlags'] & 0x00000020);
$v105['RIFF']['raw']['avih']['flags']['interleaved']  = (bool) ($v105['RIFF']['raw']['avih']['dwFlags'] & 0x00000100);
$v105['RIFF']['raw']['avih']['flags']['trustcktype']  = (bool) ($v105['RIFF']['raw']['avih']['dwFlags'] & 0x00000800);
$v105['RIFF']['raw']['avih']['flags']['capturedfile'] = (bool) ($v105['RIFF']['raw']['avih']['dwFlags'] & 0x00010000);
$v105['RIFF']['raw']['avih']['flags']['copyrighted']  = (bool) ($v105['RIFF']['raw']['avih']['dwFlags'] & 0x00020010);
$v105['RIFF']['video']["$v482"]['frame_width']  = $v105['RIFF']['raw']['avih']['dwWidth'];
$v105['RIFF']['video']["$v482"]['frame_height'] = $v105['RIFF']['raw']['avih']['dwHeight'];
$v105['RIFF']['video']["$v482"]['frame_rate']   = round(1000000 / $v105['RIFF']['raw']['avih']['dwMicroSecPerFrame'], 3);
if (!isset($v105['resolution_x'])) {
$v105['resolution_x'] = $v105['RIFF']['video']["$v482"]['frame_width'];
}
if (!isset($v105['resolution_y'])) {
$v105['resolution_y'] = $v105['RIFF']['video']["$v482"]['frame_height'];
}
}
if (isset($v105['RIFF']['AVI ']['hdrl']['strl']['strh'][0]['data'])) {
if (is_array($v105['RIFF']['AVI ']['hdrl']['strl']['strh'])) {
for ($i=0;$i<count($v105['RIFF']['AVI ']['hdrl']['strl']['strh']);$i++) {
if (isset($v105['RIFF']['AVI ']['hdrl']['strl']['strh']["$i"]['data'])) {
$v484 = $v105['RIFF']['AVI ']['hdrl']['strl']['strh']["$i"]['data'];
$v105['RIFF']['raw']['strh']["$i"]['fccType']               = substr($v484,  0, 4);
$v105['RIFF']['raw']['strh']["$i"]['fccHandler']            = substr($v484,  4, 4);
$v105['RIFF']['raw']['strh']["$i"]['dwFlags']               = f73(substr($v484,  8, 4)); 
$v105['RIFF']['raw']['strh']["$i"]['wPriority']             = f73(substr($v484, 12, 2));
$v105['RIFF']['raw']['strh']["$i"]['wLanguage']             = f73(substr($v484, 14, 2));
$v105['RIFF']['raw']['strh']["$i"]['dwInitialFrames']       = f73(substr($v484, 16, 4));
$v105['RIFF']['raw']['strh']["$i"]['dwScale']               = f73(substr($v484, 20, 4));
$v105['RIFF']['raw']['strh']["$i"]['dwRate']                = f73(substr($v484, 24, 4));
$v105['RIFF']['raw']['strh']["$i"]['dwStart']               = f73(substr($v484, 28, 4));
$v105['RIFF']['raw']['strh']["$i"]['dwLength']              = f73(substr($v484, 32, 4));
$v105['RIFF']['raw']['strh']["$i"]['dwSuggestedBufferSize'] = f73(substr($v484, 36, 4));
$v105['RIFF']['raw']['strh']["$i"]['dwQuality']             = f73(substr($v484, 40, 4));
$v105['RIFF']['raw']['strh']["$i"]['dwSampleSize']          = f73(substr($v484, 44, 4));
$v105['RIFF']['raw']['strh']["$i"]['rcFrame']               = f73(substr($v484, 48, 4));
if (isset($v105['RIFF']['AVI ']['hdrl']['strl']['strf']["$i"]['data'])) {
$v483 = $v105['RIFF']['AVI ']['hdrl']['strl']['strf']["$i"]['data'];
switch ($v105['RIFF']['raw']['strh']["$i"]['fccType']) {
case 'auds':
if (isset($v105['RIFF']['audio']) && is_array($v105['RIFF']['audio'])) {
$v482 = count($v105['RIFF']['audio']);
}
$v105['RIFF']['raw']['strf']['auds']["$v482"]['wFormatTag']      = f73(substr($v483,  0, 2));
$v105['RIFF']['raw']['strf']['auds']["$v482"]['nChannels']       = f73(substr($v483,  2, 2));
$v105['RIFF']['raw']['strf']['auds']["$v482"]['nSamplesPerSec']  = f73(substr($v483,  4, 4));
$v105['RIFF']['raw']['strf']['auds']["$v482"]['nAvgBytesPerSec'] = f73(substr($v483,  8, 4));
$v105['RIFF']['raw']['strf']['auds']["$v482"]['nBlockAlign']     = f73(substr($v483, 12, 2));
$v105['RIFF']['raw']['strf']['auds']["$v482"]['nBitsPerSample']  = f73(substr($v483, 14, 2));
$v105['RIFF']['audio']["$v482"]['format']        = f98($v105['RIFF']['raw']['strf']['auds']["$v482"]['wFormatTag']);
$v105['RIFF']['audio']["$v482"]['channels']      = $v105['RIFF']['raw']['strf']['auds']["$v482"]['nChannels'];
$v105['RIFF']['audio']["$v482"]['channelmode']   = (($v105['RIFF']['audio']["$v482"]['channels'] == 1) ? 'mono' : 'stereo');
$v105['RIFF']['audio']["$v482"]['frequency']     = $v105['RIFF']['raw']['strf']['auds']["$v482"]['nSamplesPerSec'];
$v105['RIFF']['audio']["$v482"]['bitrate']       = $v105['RIFF']['raw']['strf']['auds']["$v482"]['nAvgBytesPerSec'] * 8;
$v105['RIFF']['audio']["$v482"]['bitspersample'] = $v105['RIFF']['raw']['strf']['auds']["$v482"]['nBitsPerSample'];
if (!isset($v105['frequency'])) {
$v105['frequency'] = $v105['RIFF']['audio']["$v482"]['frequency'];
}
if (!isset($v105['channels'])) {
$v105['channels']  = $v105['RIFF']['audio']["$v482"]['channels'];
}
if (!isset($v105['bitrate_audio']) && isset($v105['RIFF']['audio']["$v482"]['bitrate'])) {
$v105['bitrate_audio'] = $v105['RIFF']['audio']["$v482"]['bitrate'];
}
break;
case 'vids':
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biSize']          = f73(substr($v483,  0, 4)); 
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biWidth']         = f73(substr($v483,  4, 4)); 
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biHeight']        = f73(substr($v483,  8, 4)); 
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biPlanes']        = f73(substr($v483, 12, 2)); 
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biBitCount']      = f73(substr($v483, 14, 2)); 
$v105['RIFF']['raw']['strf']['vids']["$v482"]['fourcc']          = substr($v483, 16, 4);
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biSizeImage']     = f73(substr($v483, 20, 4)); 
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biXPelsPerMeter'] = f73(substr($v483, 24, 4)); 
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biYPelsPerMeter'] = f73(substr($v483, 28, 4)); 
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biClrUsed']       = f73(substr($v483, 32, 4)); 
$v105['RIFF']['raw']['strf']['vids']["$v482"]['biClrImportant']  = f73(substr($v483, 36, 4)); 
$v105['RIFF']['video']["$v482"]['codec'] = RIFFfourccLookup($v105['RIFF']['raw']['strh']["$i"]['fccHandler']);
if (!$v105['RIFF']['video']["$v482"]['codec'] && RIFFfourccLookup($v105['RIFF']['raw']['strf']['vids']["$v482"]['fourcc'])) {
RIFFfourccLookup($v105['RIFF']['raw']['strf']['vids']["$v482"]['fourcc']);
}
break;
}
}
}
}
}
}
break;
default:
unset($v105['fileformat']);
break;
}
if (isset($v105['RIFF']['WAVE']['INFO']) && is_array($v105['RIFF']['WAVE']['INFO'])) {
$v105['RIFF']['title']              = trim(substr($v105['RIFF']['WAVE']['INFO']['DISP'][count($v105['RIFF']['WAVE']['INFO']['DISP']) - 1]['data'], 4));
$v105['RIFF']['artist']             = trim($v105['RIFF']['WAVE']['INFO']['IART'][count($v105['RIFF']['WAVE']['INFO']['IART']) - 1]['data']);
$v105['RIFF']['genre']              = trim($v105['RIFF']['WAVE']['INFO']['IGNR'][count($v105['RIFF']['WAVE']['INFO']['IGNR']) - 1]['data']);
$v105['RIFF']['comment']            = trim($v105['RIFF']['WAVE']['INFO']['ICMT'][count($v105['RIFF']['WAVE']['INFO']['ICMT']) - 1]['data']);
$v105['RIFF']['copyright']          = trim($v105['RIFF']['WAVE']['INFO']['ICOP'][count($v105['RIFF']['WAVE']['INFO']['ICOP']) - 1]['data']);
$v105['RIFF']['engineers']          = trim($v105['RIFF']['WAVE']['INFO']['IENG'][count($v105['RIFF']['WAVE']['INFO']['IENG']) - 1]['data']);
$v105['RIFF']['keywords']           = trim($v105['RIFF']['WAVE']['INFO']['IKEY'][count($v105['RIFF']['WAVE']['INFO']['IKEY']) - 1]['data']);
$v105['RIFF']['originalmedium']     = trim($v105['RIFF']['WAVE']['INFO']['IMED'][count($v105['RIFF']['WAVE']['INFO']['IMED']) - 1]['data']);
$v105['RIFF']['name']               = trim($v105['RIFF']['WAVE']['INFO']['INAM'][count($v105['RIFF']['WAVE']['INFO']['INAM']) - 1]['data']);
$v105['RIFF']['sourcesupplier']     = trim($v105['RIFF']['WAVE']['INFO']['ISRC'][count($v105['RIFF']['WAVE']['INFO']['ISRC']) - 1]['data']);
$v105['RIFF']['digitizer']          = trim($v105['RIFF']['WAVE']['INFO']['ITCH'][count($v105['RIFF']['WAVE']['INFO']['ITCH']) - 1]['data']);
$v105['RIFF']['subject']            = trim($v105['RIFF']['WAVE']['INFO']['ISBJ'][count($v105['RIFF']['WAVE']['INFO']['ISBJ']) - 1]['data']);
$v105['RIFF']['digitizationsource'] = trim($v105['RIFF']['WAVE']['INFO']['ISRF'][count($v105['RIFF']['WAVE']['INFO']['ISRF']) - 1]['data']);
}
foreach ($v105['RIFF'] as $key => $v531) {
if (!is_array($v531) && !$v531) {
unset($v105['RIFF']["$key"]);
}
}
if (!isset($v105['playtime_seconds']) && isset($v105['RIFF']['raw']['avih']['dwTotalFrames']) && isset($v105['RIFF']['raw']['avih']['dwMicroSecPerFrame'])) {
$v105['playtime_seconds'] = $v105['RIFF']['raw']['avih']['dwTotalFrames'] * ($v105['RIFF']['raw']['avih']['dwMicroSecPerFrame'] / 1000000);
}
if (isset($v105['RIFF']['video']) && isset($v105['bitrate_audio']) && ($v105['bitrate_audio'] > 0)) {
$v105['bitrate_video']    = (($v105['filesize'] / $v105['playtime_seconds']) * 8) - $v105['bitrate_audio'];
if ($v105['bitrate_video'] <= 0) {
unset($v105['bitrate_video']);
}
}
return TRUE;
}
function f126(&$fd, &$v152) {
rewind($fd);
$v337 = fread($fd, 10);
if (substr($v337, 0, 3) == 'ID3') {
$v152['id3v2']['header'] = true;
$v152['id3v2']['majorversion'] = ord($v337{3});
$v152['id3v2']['minorversion'] = ord($v337{4});
} else {
return false;
}
if ($v152['id3v2']['majorversion'] > 4) { 
$v152['error'] .= "\n".'this script only parses up to ID3v2.4.x - this tag is ID3v2.'.$v152['id3v2']['majorversion'].'.'.$v152['id3v2']['minorversion'];
return false;
}
$v345 = f7($v337{5});
switch ($v152['id3v2']['majorversion']) {
case 2:
$v152['id3v2']['flags']['unsynch']     = $v345{0}; 
$v152['id3v2']['flags']['compression'] = $v345{1}; 
break;
case 3:
$v152['id3v2']['flags']['unsynch']     = $v345{0}; 
$v152['id3v2']['flags']['exthead']     = $v345{1}; 
$v152['id3v2']['flags']['experim']     = $v345{2}; 
break;
case 4:
$v152['id3v2']['flags']['unsynch']     = $v345{0}; 
$v152['id3v2']['flags']['exthead']     = $v345{1}; 
$v152['id3v2']['flags']['experim']     = $v345{2}; 
$v152['id3v2']['flags']['isfooter']    = $v345{3}; 
break;
}
$v152['id3v2']['headerlength'] = f9(substr($v337, 6, 4), 1) + f60($v152['id3v2']['majorversion']);
if (isset($v152['id3v2']['flags']['exthead']) && $v152['id3v2']['flags']['exthead']) {
$v252 = fread ($fd, 4);
$v152['id3v2']['extheaderlength'] = f9($v252, 1);
$v253 = fread ($fd, 1);
$v254     = fread ($fd, $v253);
$v344 = f7(substr($v337, 5, 1));
$v152['id3v2']['exthead_flags']['update']       = substr($v344, 1, 1);
$v152['id3v2']['exthead_flags']['CRC']          = substr($v344, 2, 1);
if ($v152['id3v2']['exthead_flags']['CRC']) {
$v255 = fread ($fd, 5);
$v152['id3v2']['exthead_flags']['CRC'] = f9($v255, 1);
}
$v152['id3v2']['exthead_flags']['restrictions'] = substr($v344, 3, 1);
if ($v152['id3v2']['exthead_flags']['restrictions']) {
$v256 = fread ($fd, 1);
$v152['id3v2']['exthead_flags']['restrictions_tagsize']  = (bindec('11000000') & ord($v256)) >> 6; 
$v152['id3v2']['exthead_flags']['restrictions_textenc']  = (bindec('00100000') & ord($v256)) >> 5; 
$v152['id3v2']['exthead_flags']['restrictions_textsize'] = (bindec('00011000') & ord($v256)) >> 3; 
$v152['id3v2']['exthead_flags']['restrictions_imgenc']   = (bindec('00000100') & ord($v256)) >> 2; 
$v152['id3v2']['exthead_flags']['restrictions_imgsize']  = (bindec('00000011') & ord($v256)) >> 0; 
}
} 
$v477 = $v152['id3v2']['headerlength'] - f60($v152['id3v2']['majorversion']);
if (isset($v152['id3v2']['extheaderlength'])) {
$v477 -= $v152['id3v2']['extheaderlength'];
}
if (isset($v152['id3v2']['flags']['isfooter']) && $v152['id3v2']['flags']['isfooter']) {
$v477 -= 10; 
}
if ($v477 > 0) {
$v327 = fread($fd, $v477); 
if (isset($v152['id3v2']['flags']['unsynch']) && $v152['id3v2']['flags']['unsynch'] && ($v152['id3v2']['majorversion'] <= 3)) {
$v327 = f29($v327);
}
$v328 = 10; 
while (isset($v327) && (strlen($v327) > 0)) { 
if ($v152['id3v2']['majorversion'] == 2) {
$v299 = substr($v327, 0, 6); 
$v327    = substr($v327, 6);    
$v308   = substr($v299, 0, 3);
$v320   = f9(substr($v299, 3, 3), 0);
$v296  = ''; 
} elseif ($v152['id3v2']['majorversion'] > 2) {
$v299 = substr($v327, 0, 10); 
$v327    = substr($v327, 10);    
$v308 = substr($v299, 0, 4);
if ($v152['id3v2']['majorversion'] == 3) {
$v320 = f9(substr($v299, 4, 4), 0); 
} else { 
$v320 = f9(substr($v299, 4, 4), 1); 
}
if ($v320 < (strlen($v327) + 4)) {
$v412 = substr($v327, $v320, 4);
if (f66($v412, $v152['id3v2']['majorversion'])) {
} elseif (($v308 == chr(0).'MP3') || ($v308 == chr(0).chr(0).'MP') || ($v308 == ' MP3') || ($v308 == 'MP3e')) {
} elseif (($v152['id3v2']['majorversion'] == 4) && (f66(substr($v327, f9(substr($v299, 4, 4), 0), 4), 3))) {
$v152['warning'] .= "\n".'ID3v2 tag written as ID3v2.4, but with non-synchsafe integers (ID3v2.3 style). Older versions of Helium2 (www.helium2.com) is a known culprit of this. Tag has been parsed as ID3v2.3';
$v152['id3v2']['majorversion'] = 3;
$v320 = f9(substr($v299, 4, 4), 0); 
}
}
$v296 = f7(substr($v299, 8, 2));
}
if ((($v152['id3v2']['majorversion'] == 2) && ($v308 == chr(0).chr(0).chr(0))) || ($v308 == chr(0).chr(0).chr(0).chr(0))) {
$v152['id3v2']['padding']['start']  = $v328;
$v152['id3v2']['padding']['length'] = strlen($v327);
$v152['id3v2']['padding']['valid']  = true;
for ($i = 0; $i < $v152['id3v2']['padding']['length']; $i++) {
if (substr($v327, $i, 1) != chr(0)) {
$v152['id3v2']['padding']['valid'] = false;
$v152['id3v2']['padding']['errorpos'] = $v152['id3v2']['padding']['start'] + $i;
break;
}
}
break; 
}
if ($v308 == 'COM ') {
$v152['warning'] .= "\n".'error parsing "'.$v308.'" ('.$v328.' bytes into the ID3v2.'.$v152['id3v2']['majorversion'].' tag). (ERROR: !f66("'.str_replace(chr(0), ' ', $v308).'", '.$v152['id3v2']['majorversion'].'))). [Note: this particular error has been known to happen with tags edited by iTunes (versions "X v2.0.3", "v3.0.1" are known-guilty, probably others too)]';
$v308 = 'COMM';
}
if (($v320 <= strlen($v327)) && (f66($v308, $v152['id3v2']['majorversion']))) {
$v152['id3v2']["$v308"]['data']       = substr($v327, 0, $v320);
$v152['id3v2']["$v308"]['datalength'] = f20($v320);
$v152['id3v2']["$v308"]['dataoffset'] = $v328;
$v327 = substr($v327, $v320);
f59($v308, $v296, $v152);
} else { 
if ($v320 <= strlen($v327)) {
if (f66(substr($v327, $v320, 4), $v152['id3v2']['majorversion'])) {
$v327 = substr($v327, $v320);
$v91 = 'warning';
$v90 = ' Next frame is valid, skipping current frame.';
} else {
unset($v327);
$v91 = 'error';
$v90 = ' Next frame is also invalid, aborting processing.';
}
} elseif ($v320 == strlen($v327)) {
$v91 = 'warning';
$v90 = ' This was the last frame.';
} else {
unset($v327);
$v91 = 'error';
$v90 = ' Invalid frame size, aborting.';
}
if (!f66($v308, $v152['id3v2']['majorversion'])) {
switch ($v308) {
case chr(0).chr(0).'MP':
case chr(0).'MP3':
case ' MP3':
case 'MP3e':
case chr(0).'MP':
case ' MP':
case 'MP3':
$v91 = 'warning';
$v152["$v91"] .= "\n".'error parsing "'.$v308.'" ('.$v328.' bytes into the ID3v2.'.$v152['id3v2']['majorversion'].' tag). (ERROR: !f66("'.str_replace(chr(0), ' ', $v308).'", '.$v152['id3v2']['majorversion'].'))). [Note: this particular error has been known to happen with tags edited by "MP3ext (www.mutschler.de/mp3ext/)"]';
break;
default:
$v152["$v91"] .= "\n".'error parsing "'.$v308.'" ('.$v328.' bytes into the ID3v2.'.$v152['id3v2']['majorversion'].' tag). (ERROR: !f66("'.str_replace(chr(0), ' ', $v308).'", '.$v152['id3v2']['majorversion'].'))).';
break;
}
} elseif ($v320 > strlen($v327)){
$v152["$v91"] .= "\n".'error parsing "'.$v308.'" ('.$v328.' bytes into the ID3v2.'.$v152['id3v2']['majorversion'].' tag). (ERROR: $v320 ('.$v320.') > strlen($v327) ('.strlen($v327).')).';
} else {
$v152["$v91"] .= "\n".'error parsing "'.$v308.'" ('.$v328.' bytes into the ID3v2.'.$v152['id3v2']['majorversion'].' tag).';
}
$v152["$v91"] .= $v90;
}
$v328 += ($v320 + f60($v152['id3v2']['majorversion']));
}
}
if (isset($v152['id3v2']['flags']['isfooter']) && $v152['id3v2']['flags']['isfooter']) {
$v275 = fread ($fd, 10);
if (substr($v275, 0, 3) == '3DI') {
$v152['id3v2']['footer'] = true;
$v152['id3v2']['majorversion_footer'] = ord(substr($v275, 3, 1));
$v152['id3v2']['minorversion_footer'] = ord(substr($v275, 4, 1));
}
if ($v152['id3v2']['majorversion_footer'] <= 4) {
$v345 = f7(substr($v275, 5, 1));
$v152['id3v2']['flags']['unsynch_footer']  = substr($v345, 0, 1);
$v152['id3v2']['flags']['extfoot_footer']  = substr($v345, 1, 1);
$v152['id3v2']['flags']['experim_footer']  = substr($v345, 2, 1);
$v152['id3v2']['flags']['isfooter_footer'] = substr($v345, 3, 1);
$v152['id3v2']['footerlength'] = f9(substr($v275, 6, 4), 1);
}
} 
if (isset($v152['id3v2']['comments']['genre'])) {
foreach ($v152['id3v2']['comments']['genre'] as $key => $v531) {
unset($v152['id3v2']['comments']['genre'][$key]);
$v152['id3v2']['comments'] = f114($v152['id3v2']['comments'], f91($v531));
}
}
if (isset($v152['id3v2']['comments']['track'])) {
foreach ($v152['id3v2']['comments']['track'] as $key => $v531) {
if (strstr($v531, '/')) {
list($v152['id3v2']['comments']['track'][$key], $v152['id3v2']['comments']['totaltracks'][$key]) = explode('/', $v152['id3v2']['comments']['track'][$key]);
}
$v152['id3v2']['comments']['track'][$key] = intval($v152['id3v2']['comments']['track'][$key]);
}
}
return true;
}
function f91($v333) {
$v458 = null;
if (strpos($v333, chr(0)) !== false) {
$v524 = trim($v333); 
$v333 = '';
while (strpos($v524, chr(0)) !== false) {
$v246 = strpos($v524, chr(0));
$v333 .= '('.substr($v524, 0, $v246).')';
$v524 = substr($v524, $v246 + 1);
}
unset($v524);
}
while (strpos($v333, '(') !== false) {
$v481 = strpos($v333, '(');
$v246   = strpos($v333, ')');
if (substr($v333, $v481 + 1, 1) == '(') {
$v333 = substr($v333, 0, $v481).substr($v333, $v481 + 1);
$v246--;
}
$v241     = substr($v333, $v481 + 1, $v246 - ($v481 + 1));
$v333 = substr($v333, 0, $v481).substr($v333, $v246 + 1);
if (f76($v241) !== '') { 
if (!is_array($v458['genre']) || !in_array(f76($v241), $v458['genre'])) { 
if (($v241 == 'CR') && ($v241 == 'RX')) {
$v458['genreid'][] = $v241;
} else {
$v458['genreid'][] = (int) $v241;
}
$v458['genre'][]   = f76($v241);
}
} else {
if (!is_array($v458['genre']) || !in_array($v241, $v458['genre'])) { 
$v458['genreid'][] = '';
$v458['genre'][]   = $v241;
}
}
}
if ($v333) {
if (!is_array($v458['genre']) || !in_array($v333, $v458['genre'])) { 
$v458['genreid'][] = '';
$v458['genre'][]   = $v333;
}
}
return $v458;
}
function f125(&$fd, &$v152) {
fseek($fd, -128, SEEK_END);
$v347 = fread($fd, 128);
if (substr($v347, 0, 3) == 'TAG') {
$v152['id3v1']['title']   = trim(substr($v347,   3, 30));
$v152['id3v1']['artist']  = trim(substr($v347,  33, 30));
$v152['id3v1']['album']   = trim(substr($v347,  63, 30));
$v152['id3v1']['year']    = trim(substr($v347,  93,  4));
$v152['id3v1']['comment'] =      substr($v347,  97, 30); 
$v152['id3v1']['genreid'] =  ord(substr($v347, 127,  1));
if ((substr($v152['id3v1']['comment'], 28, 1) === chr(0)) && (substr($v152['id3v1']['comment'], 29, 1) !== chr(0))) {
$v152['id3v1']['track'] = ord(substr($v152['id3v1']['comment'], 29, 1));
$v152['id3v1']['comment'] = substr($v152['id3v1']['comment'], 0, 28);
}
$v152['id3v1']['comment'] = trim($v152['id3v1']['comment']);
$v152['id3v1']['genre'] = f76($v152['id3v1']['genreid']);
return true;
}
return false;
}
if (!function_exists('f96')) {
function f96($v485) {
$v460 = '';
for ($i = 0; $i < strlen($v485); $i++) {
$v460 .= str_pad(dechex(ord(substr($v485, $i, 1))), 2, '0', STR_PAD_LEFT).' ';
}
return $v460;
}
}
if (!function_exists('f97')) {
function f97($v485) {
$v460 = '';
for ($i = 0; $i < strlen($v485); $i++) {
if (ord(substr($v485, $i, 1)) <= 31) {
$v460 .= '   ';
} else {
$v460 .= ' '.substr($v485, $i, 1).' ';
}
}
return $v460;
}
}
if (!function_exists('f36')) {
function f36($v500) {
return mysql_escape_string($v500);
}
}
if (!function_exists('f37')) {
function f37($v500) {
$v500 = f104($v500);
$v500 = str_replace('\'', '&#39;', $v500);
$v500 = str_replace('"', '&quot;', $v500);
return $v500;
}
}
if (!function_exists('f104')) {
function f104($v500) {
if (get_magic_quotes_gpc()) {
return stripslashes($v500);
}
return $v500;
}
}
define('GIF_SIG',     chr(0x47).chr(0x49).chr(0x46));  
define('PNG_SIG',     chr(0x89).chr(0x50).chr(0x4E).chr(0x47).chr(0x0D).chr(0x0A).chr(0x1A).chr(0x0A));
define('JPG_SIG',     chr(0xFF).chr(0xD8).chr(0xFF));
define('JPG_SOS',     chr(0xDA)); 
define('JPG_SOF0',    chr(0xC0)); 
define('JPG_SOF1',    chr(0xC1)); 
define('JPG_SOF2',    chr(0xC2)); 
define('JPG_SOF3',    chr(0xC3));
define('JPG_SOF5',    chr(0xC5));
define('JPG_SOF6',    chr(0xC6));
define('JPG_SOF7',    chr(0xC7));
define('JPG_SOF9',    chr(0xC9));
define('JPG_SOF10',   chr(0xCA));
define('JPG_SOF11',   chr(0xCB));
define('JPG_SOF13',   chr(0xCD));
define('JPG_SOF14',   chr(0xCE));
define('JPG_SOF15',   chr(0xCF));
define('JPG_EOI',     chr(0xD9)); 
function f54($v526) {
if ($fd = @fopen($v526, 'rb')){
$v357 = fread($fd, filesize($v526));
fclose($fd);
return f50($v357);
} else {
return array('', '', '');
}
}
function f50($v357) {
$v341 = '';
$v540  = '';
$v523   = '';
if ((substr($v357, 0, 3) == GIF_SIG) && (strlen($v357) > 10)) {
$dim = unpack('v2dim', substr($v357, 6, 4));
$v540  = $dim['dim1'];
$v341 = $dim['dim2'];
$v523 = 1;
} else if ((substr($v357, 0, 8) == PNG_SIG) && (strlen($v357) > 24)) {
$dim = unpack('N2dim', substr($v357, 16, 8));
$v540  = $dim['dim1'];
$v341 = $dim['dim2'];
$v523 = 3;
} else if ((substr($v357, 0, 3) == JPG_SIG) && (strlen($v357) > 4)) {
$v358 = 2;
$v523 = 2;
$v204 = strlen($v357) - 2;
while ($v358 < strlen($v357)) {
$v358 = strpos($v357, 0xFF, $v358) + 1;
$v394 = $v357[$v358];
do {
$v394 = ord($v357[$v358++]);
} while ($v394 == 255);
switch (chr($v394)) {
case JPG_SOF0:
case JPG_SOF1:
case JPG_SOF2:
case JPG_SOF3:
case JPG_SOF5:
case JPG_SOF6:
case JPG_SOF7:
case JPG_SOF9:
case JPG_SOF10:
case JPG_SOF11:
case JPG_SOF13:
case JPG_SOF14:
case JPG_SOF15:
$dim = unpack('n2dim', substr($v357, $v358 + 3, 4));
$v341 = $dim['dim1'];
$v540  = $dim['dim2'];
break 2; 
case JPG_EOI:
case JPG_SOS:
return FALSE;   
default:            
$v479 = (ord($v357[$v358++]) << 8) + ord($v357[$v358++]) - 2;
$v204 -= $v479;
if ($v204 < 512) { 
return FALSE; 
}
$v358 += $v479;
break;
} 
} 
} 
return array($v540, $v341, $v523);
} 
if (!function_exists('f145')) {
function f145($v536) {
$v460 = '';
switch (gettype($v536)) {
case 'array':
$v460 .= '<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">';
foreach ($v536 as $key => $v531) {
$v460 .= '<TR><TD VALIGN="TOP"><B>'.str_replace(chr(0), ' ', $key).'</B></TD>';
$v460 .= '<TD VALIGN="TOP">'.gettype($v531);
if (is_array($v531)) {
$v460 .= '&nbsp;('.count($v531).')';
} elseif (is_string($v531)) {
$v460 .= '&nbsp;('.strlen($v531).')';
}
if (($key == 'data') && isset($v536['image_mime']) && isset($v536['dataoffset'])) {
$v353 = @f50($v531);
$v56 = $_REQUEST['filename'].'.'.$v536['dataoffset'].'.'.ImageTypesLookup($v353[2]);
if ($v498 = fopen($v56, 'wb')) {
fwrite($v498, $v531);
fclose($v498);
}
$v460 .= '</TD><TD><IMG SRC="'.$v56.'" WIDTH="'.$v353[0].'" HEIGHT="'.$v353[1].'"></TD></TR>';
} else {
$v460 .= '</TD><TD>'.f145($v531).'</TD></TR>';
}
}
$v460 .= '</TABLE>';
break;
case 'boolean':
$v460 .= ($v536 ? 'TRUE' : 'FALSE');
break;
case 'integer':
case 'double':
case 'float':
$v460 .= $v536;
break;
case 'object':
case 'null':
$v460 .= f144($v536);
break;
case 'string':
$v536 = str_replace(chr(0), ' ', $v536);
$v537 = strlen($v536);
for ($i = 0; $i < $v537; $i++) {
if (ereg('['.chr(0x0A).chr(0x0D).' -;A-z]', $v536{$i})) {
$v460 .= $v536{$i};
} else {
$v460 .= '&#'.str_pad(ord($v536{$i}), 3, '0', STR_PAD_LEFT).';';
}
}
$v460 = nl2br($v460);
break;
default:
$v353 = f50(substr($v536, 0, FREAD_BUFFER_SIZE));
if (($v353[2] >= 1) && ($v353[2] <= 3)) {
$v460 .= '<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">';
$v460 .= '<TR><TD><B>type</B></TD><TD>'.ImageTypesLookup($v353[2]).'</TD></TR>';
$v460 .= '<TR><TD><B>width</B></TD><TD>'.number_format($v353[0]).' px</TD></TR>';
$v460 .= '<TR><TD><B>height</B></TD><TD>'.number_format($v353[1]).' px</TD></TR>';
$v460 .= '<TR><TD><B>size</B></TD><TD>'.number_format(strlen($v536)).' bytes</TD></TR></TABLE>';
} else {
$v460 .= nl2br(htmlspecialchars(str_replace(chr(0), ' ', $v536)));
}
break;
}
return $v460;
}
}
if (!function_exists('f144')) {
function f144($v536) {
ob_start();
var_dump($v536);
$v239 = ob_get_contents();
ob_end_clean();
return $v239;
}
}
if (!function_exists('f119')) {
function f119($filename, $v422=1) {
if (strstr($filename, '.')) {
$v462 = strrev($filename);
$v423 = 0;
for ($i = 0; $i < $v422; $i++) {
$v423 = strpos($v462, '.', $v423 + 1);
if ($v423 === false) {
return '';
}
}
return strrev(substr($v462, 0, $v423));
}
return '';
}
}
if (!function_exists('f101')) {
function f101($v485) {
return strtr($v485, '¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ', 'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
}
}
if (!function_exists('f88')) {
function f88($ar1, $ar2) {
if ($ar1 === $ar2) {
return 0;
}
$v378     = strlen($ar1);
$v379     = strlen($ar2);
$v473 = min($v378, $v379);
if (substr($ar1, 0, $v473) === substr($ar2, 0, $v473)) {
if ($v378 < $v379) {
return -1;
} elseif ($v378 > $v379) {
return 1;
}
return 0;
}
$ar1 = f101(strtolower(trim($ar1)));
$ar2 = f101(strtolower(trim($ar2)));
$v521 = array('\''=>'', '"'=>'', '_'=>' ', '('=>'', ')'=>'', '-'=>' ', '  '=>' ', '.'=>'', ','=>'');
foreach ($v521 as $key => $val) {
$ar1 = str_replace($key, $val, $ar1);
$ar2 = str_replace($key, $val, $ar2);
}
if ($ar1 < $ar2) {
return -1;
} elseif ($ar1 > $ar2) {
return 1;
}
return 0;
}
}
if (!function_exists('f146')) {
function f146($v271) {
if ($v271 >= 1) {
$v522 = floor($v271);
} elseif ($v271 <= -1) {
$v522 = ceil($v271);
} else {
$v522 = 0;
}
if ($v522 <= pow(2, 30)) {
$v522 = (int) $v522;
}
return $v522;
}
}
if (!function_exists('f20')) {
function f20($v270) {
$v270 = (float) $v270;
if (f146($v270) == $v270) {
if ($v270 <= pow(2, 30)) {
$v270 = (int) $v270;
}
}
return $v270;
}
}
if (!function_exists('f133')) {
function f133() {
list($v527, $sec) = explode(' ', microtime());
return ((float) $v527 + (float) $sec);
}
}
if (!function_exists('f31')) {
function f31($v188) {
$v421   = f11($v188);
$v233 = f11(str_repeat('1', strlen($v188)));
return ($v421 / $v233);
}
}
if (!function_exists('f90')) {
function f90($v189, $v396=52) {
if (strpos($v189, '.') === false) {
$v189 = '0.'.$v189;
} elseif ($v189{0} == '.') {
$v189 = '0'.$v189;
}
$v248 = 0;
while (($v189{0} != '1') || (substr($v189, 1, 1) != '.')) {
if (substr($v189, 1, 1) == '.') {
$v248--;
$v189 = substr($v189, 2, 1).'.'.substr($v189, 3);
} else {
$v439 = strpos($v189, '.');
$v248 += ($v439 - 1);
$v189 = str_replace('.', '', $v189);
$v189 = $v189{0}.'.'.substr($v189, 1);
}
}
$v189 = str_pad(substr($v189, 0, $v396 + 2), $v396 + 2, '0', STR_PAD_RIGHT);
return array('normalized'=>$v189, 'exponent'=>(int) $v248);
}
}
if (!function_exists('f41')) {
function f41($v273) {
$v396 = 128; 
$v363   = f146($v273);
$v272 = abs($v273 - $v363);
$v438 = '';
while (($v272 != 0) && (strlen($v438) < $v396)) {
$v272 *= 2;
$v438 .= (string) f146($v272);
$v272 -= f146($v272);
}
$v189 = decbin($v363).'.'.$v438;
return $v189;
}
}
if (!function_exists('f42')) {
function f42($v273, $v201) {
switch ($v201) {
case 32:
$v249 = 8;
$v280 = 23;
break;
case 64:
$v249 = 11;
$v280 = 52;
break;
default:
return false;
break;
}
if ($v273 >= 0) {
$v474 = '0';
} else {
$v474 = '1';
}
$v416  = f90(f41($v273), $v280);
$v187    = pow(2, $v249 - 1) - 1 + $v416['exponent']; 
$v250 = str_pad(decbin($v187), $v249, '0', STR_PAD_LEFT);
$v281 = str_pad(substr($v416['normalized'], 2), $v280, '0', STR_PAD_RIGHT);
return f10(f11($v474.$v250.$v281), $v201 % 8, false);
}
}
if (!function_exists('f72')) {
function f72($v208) {
return f8(strrev($v208));
}
}
if (!function_exists('f8')) {
function f8($v208) {
$v203 = f7($v208);
$v474 = $v203{0};
switch (strlen($v208) * 8) {
case 32:
$v249 = 8;
$v280 = 23;
break;
case 64:
$v249 = 11;
$v280 = 52;
break;
case 80:
$v249 = 16;
$v280 = 64;
break;
default:
return false;
break;
}
$v251 = substr($v203, 1, $v249 - 1);
$v282 = substr($v203, $v249, $v280);
$v248 = f11($v251);
$v279 = f11($v282);
if (($v249 == 16) && ($v280 == 64)) {
return pow(2, ($v248  - 16382)) * f31($v282);
}
if (($v248 == (pow(2, $v249) - 1)) && ($v279 != 0)) {
$v273 = false;
} elseif (($v248 == (pow(2, $v249) - 1)) && ($v279 == 0)) {
if ($v474 == '1') {
$v273 = '-infinity';
} else {
$v273 = '+infinity';
}
} elseif (($v248 == 0) && ($v279 == 0)) {
if ($v474 == '1') {
$v273 = -0;
} else {
$v273 = 0;
}
$v273 = ($v474 ? 0 : -0);
} elseif (($v248 == 0) && ($v279 != 0)) {
$v273 = pow(2, (-1 * (pow(2, $v249 - 1) - 2))) * f31($v282);
if ($v474 == '1') {
$v273 *= -1;
}
} elseif ($v248 != 0) {
$v273 = pow(2, ($v248 - (pow(2, $v249 - 1) - 1))) * (1 + f31($v282));
if ($v474 == '1') {
$v273 *= -1;
}
}
return (float) $v273;
}
}
if (!function_exists('f9')) {
function f9($v208, $v490=false, $v475=false) {
$v365 = 0;
$v209 = strlen($v208);
for ($i = 0; $i < $v209; $i++) {
if ($v490) { 
$v365 = $v365 | (ord($v208{$i}) & 0x7F) << (($v209 - 1 - $i) * 7);
} else {
$v365 += ord($v208{$i}) * pow(256, ($v209 - 1 - $i));
}
}
if ($v475 && !$v490) {
switch ($v209) {
case 1:
case 2:
case 3:
case 4:
$v476 = 0x80 << (8 * ($v209 - 1));
if ($v365 & $v476) {
$v365 = 0 - ($v365 & ($v476 - 1));
}
break;
default:
die('ERROR: Cannot have signed integers larger than 32-bits in f9()');
break;
}
}
return f20($v365);
}
}
if (!function_exists('f73')) {
function f73($v208, $v475=false) {
return f9(strrev($v208), false, $v475);
}
}
if (!function_exists('f7')) {
function f7($v208) {
$v194 = '';
$v209 = strlen($v208);
for ($i = 0; $i < $v209; $i++) {
$v194 .= str_pad(decbin(ord($v208{$i})), 8, '0', STR_PAD_LEFT);
}
return $v194;
}
}
if (!function_exists('f10')) {
function f10($v419, $v399=1, $v490=false, $v475=false) {
if ($v419 < 0) {
return false;
}
$v395 = (($v490 || $v475) ? 0x7F : 0xFF);
$v364 = '';
if ($v475) {
if ($v399 > 4) {
die('ERROR: Cannot have signed integers larger than 32-bits in f10()');
}
$v419 = $v419 & (0x80 << (8 * ($v399 - 1)));
}
while ($v419 != 0) {
$v445 = ($v419 / ($v395 + 1));
$v364 = chr(ceil(($v445 - floor($v445)) * $v395)).$v364;
$v419 = floor($v445);
}
return str_pad($v364, $v399, chr(0), STR_PAD_LEFT);
}
}
if (!function_exists('f30')) {
function f30($v419) {
while ($v419 >= 256) {
$v206[] = (($v419 / 256) - (floor($v419 / 256))) * 256;
$v419 = floor($v419 / 256);
}
$v206[] = $v419;
$v192 = '';
for ($i = 0; $i < count($v206); $i++) {
$v192 = (($i == count($v206) - 1) ? decbin($v206[$i]) : str_pad(decbin($v206[$i]), 8, '0', STR_PAD_LEFT)).$v192;
}
return $v192;
}
}
if (!function_exists('f11')) {
function f11($v192) {
$v232 = 0;
for ($i = 0; $i < strlen($v192); $i++) {
$v232 += ((int) substr($v192, strlen($v192) - $i - 1, 1)) * pow(2, $i);
}
return f20($v232);
}
}
if (!function_exists('f12')) {
function f12($v192) {
$v485 = '';
$v193 = strrev($v192);
for ($i = 0; $i < strlen($v193); $i += 8) {
$v485 = chr(f11(strrev(substr($v193, $i, 8)))).$v485;
}
return $v485;
}
}
if (!function_exists('f74')) {
function f74($v419, $v399=1, $v490=false) {
$v364 = '';
while ($v419 > 0) {
if ($v490) {
$v364 = $v364.chr($v419 & 127);
$v419 >>= 7;
} else {
$v364 = $v364.chr($v419 & 255);
$v419 >>= 8;
}
}
return str_pad($v364, $v399, chr(0), STR_PAD_RIGHT);
}
}
if (!function_exists('f15')) {
function f15($v365) {
return ($v365 ? '1' : '0');
}
}
if (!function_exists('f62')) {
function f62($v213) {
if ($v213 == '1') {
return true;
} elseif ($v213 == '0') {
return false;
}
return null;
}
}
if (!function_exists('f63')) {
function f63($v531) {
return ($v531 ? false : true);
}
}
if (!function_exists('f29')) {
function f29($v224) {
return str_replace(chr(0xFF).chr(0x00), chr(0xFF), $v224);
}
}
if (!function_exists('f108')) {
function f108($v224) {
$v224 = str_replace(chr(0xFF).chr(0x00), chr(0xFF).chr(0x00).chr(0x00), $v224);
$v525 = '';
for ($i = 0; $i < strlen($v224); $i++) {
$v512 = $v224{$i};
$v525 .= $v512;
if ($v512 == chr(255)) {
$v413 = ord(substr($v224, $i + 1, 1));
if (($v413 | 0xE0) == 0xE0) {
$v525 .= chr(0);
}
}
}
return $v525;
}
}
if (!function_exists('f135')) {
function f135($var) {
if (is_array($var)) {
$v375 = array_keys($var);
$v169 = true;
for ($i = 0; $i < count($v375); $i++) {
if (is_string($v375[$i])) {
return true;
}
}
}
return false;
}
}
if (!function_exists('f112')) {
function f112($v173, $v174) {
if (is_array($v173) && is_array($v174)) {
$v410 = array();
if (f135($v173) && f135($v174)) {
$v375 = array_merge(array_keys($v173), array_keys($v174));
foreach ($v375 as $key) {
$v410[$key] = f112($v173[$key], $v174[$key]);
}
} else {
$v410 = array_reverse(array_unique(array_reverse(array_merge($v173,$v174))));
}
return $v410;
} else {
return $v174 ? $v174 : $v173;
}
}
}
function f113($v176, $v177) {
if (!is_array($v176) || !is_array($v177)) {
return false;
}
$v411 = $v176;
foreach ($v177 as $key => $val) {
if (is_array($val) && isset($v411[$key]) && is_array($v411[$key])) {
$v411[$key] = f113($v411[$key], $val);
} else {
$v411[$key] = $val;
}
}
return $v411;
}
function f114($v176, $v177) {
if (!is_array($v176) || !is_array($v177)) {
return false;
}
$v411 = $v176;
foreach ($v177 as $key => $val) {
if (is_array($val) && isset($v411[$key]) && is_array($v411[$key])) {
$v411[$key] = f114($v411[$key], $val);
} elseif (!isset($v411[$key])) {
$v411[$key] = $val;
}
}
return $v411;
}
if (!function_exists('f102')) {
function f102($v448, $v323) {
$v499 = '';
switch ($v323) {
case 0: 
$v181 = $v448;
break;
case 1: 
$v181 = $v448;
if (substr($v181, 0, 2) == chr(0xFF).chr(0xFE)) {
$v181 = substr($v181, 2);
}
if (substr($v181, strlen($v181) - 2, 2) == chr(0).chr(0)) {
$v181 = substr($v181, 0, strlen($v181) - 2); 
}
for ($i = 0; $i < strlen($v181); $i += 2) {
if ((ord($v181{$i}) <= 0x7F) || (ord($v181{$i}) >= 0xA0)) {
$v499 .= $v181{$i};
} else {
$v499 .= '?';
}
}
$v181 = $v499;
break;
case 2: 
$v181 = $v448;
if (substr($v181, strlen($v181) - 2, 2) == chr(0).chr(0)) {
$v181 = substr($v181, 0, strlen($v181) - 2); 
}
for ($i = 0; $i < strlen($v181); $i += 2) {
if ((ord($v181{$i}) <= 0x7F) || (ord($v181{$i}) >= 0xA0)) {
$v499 .= $v181{$i};
} else {
$v499 .= '?';
}
}
$v181 = $v499;
break;
case 3: 
$v181 = f147($v448);
break;
case 255: 
$v181 = $v448;
if (substr($v181, strlen($v181) - 2, 2) == chr(0).chr(0)) {
$v181 = substr($v181, 0, strlen($v181) - 2); 
}
for ($i = 0; ($i + 1) < strlen($v181); $i += 2) {
if ((ord($v181{($i + 1)}) <= 0x7F) || (ord($v181{($i + 1)}) >= 0xA0)) {
$v499 .= $v181{($i + 1)};
} else {
$v499 .= '?';
}
}
$v181 = $v499;
break;
default:
$v181 = $v448;
break;
}
if (substr($v181, strlen($v181) - 1, 1) == chr(0)) {
$v181 = f89($v181);
}
return $v181;
}
}
if (!function_exists('f94')) {
function f94($v437) {
$v221 = round((($v437 / 60) - floor($v437 / 60)) * 60);
$v220 = floor($v437 / 60);
if ($v221 >= 60) {
$v221 -= 60;
$v220++;
}
return number_format($v220).':'.str_pad($v221, 2, 0, STR_PAD_LEFT);
}
}
if (!function_exists('f23')) {
function f23($v532, $v533, $v519) {
return (abs($v532 - $v533) <= $v519);
}
}
if (!function_exists('f58')) {
function f58($v346, $v349) {
$v456 = array('title', 'artist', 'album', 'year', 'genre', 'comment');
foreach ($v456 as $v455) {
if (!isset($v346["$v455"])) {
$v346["$v455"] = '';
}
if (!isset($v349["$v455"])) {
$v349["$v455"] = '';
}
}
if (trim($v346['title']) != trim(substr($v349['title'], 0, 30))) {
return false;
}
if (trim($v346['artist']) != trim(substr($v349['artist'], 0, 30))) {
return false;
}
if (trim($v346['album']) != trim(substr($v349['album'], 0, 30))) {
return false;
}
if (trim($v346['year']) != trim(substr($v349['year'], 0, 4))) {
return false;
}
if (trim($v346['genre']) != trim($v349['genre'])) {
return false;
}
if (isset($v346['track'])) {
if (!isset($v346['track']) || (trim($v346['track']) != trim($v349['track']))) {
return false;
}
if (trim($v346['comment']) != trim(substr($v349['comment'], 0, 28))) {
return false;
}
} else {
if (trim($v346['comment']) != trim(substr($v349['comment'], 0, 30))) {
return false;
}
}
return true;
}
}
if (!function_exists('f34')) {
function f34($v60, $v464=true) {
if ($v464) {
return round(($v60 - 116444736000000000) / 10000000);
}
return ($v60 - 116444736000000000) / 10000000;
}
}
if (!function_exists('f45')) {
function f45($v82) {
$v343  = chr(hexdec(substr($v82,  6, 2)));
$v343 .= chr(hexdec(substr($v82,  4, 2)));
$v343 .= chr(hexdec(substr($v82,  2, 2)));
$v343 .= chr(hexdec(substr($v82,  0, 2)));
$v343 .= chr(hexdec(substr($v82, 11, 2)));
$v343 .= chr(hexdec(substr($v82,  9, 2)));
$v343 .= chr(hexdec(substr($v82, 16, 2)));
$v343 .= chr(hexdec(substr($v82, 14, 2)));
$v343 .= chr(hexdec(substr($v82, 19, 2)));
$v343 .= chr(hexdec(substr($v82, 21, 2)));
$v343 .= chr(hexdec(substr($v82, 24, 2)));
$v343 .= chr(hexdec(substr($v82, 26, 2)));
$v343 .= chr(hexdec(substr($v82, 28, 2)));
$v343 .= chr(hexdec(substr($v82, 30, 2)));
$v343 .= chr(hexdec(substr($v82, 32, 2)));
$v343 .= chr(hexdec(substr($v82, 34, 2)));
return $v343;
}
}
if (!function_exists('f16')) {
function f16($v47) {
$v82  = str_pad(dechex(ord($v47{3})),  2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{2})),  2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{1})),  2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{0})),  2, '0', STR_PAD_LEFT);
$v82 .= '-';
$v82 .= str_pad(dechex(ord($v47{5})),  2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{4})),  2, '0', STR_PAD_LEFT);
$v82 .= '-';
$v82 .= str_pad(dechex(ord($v47{7})),  2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{6})),  2, '0', STR_PAD_LEFT);
$v82 .= '-';
$v82 .= str_pad(dechex(ord($v47{8})),  2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{9})),  2, '0', STR_PAD_LEFT);
$v82 .= '-';
$v82 .= str_pad(dechex(ord($v47{10})), 2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{11})), 2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{12})), 2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{13})), 2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{14})), 2, '0', STR_PAD_LEFT);
$v82 .= str_pad(dechex(ord($v47{15})), 2, '0', STR_PAD_LEFT);
return strtoupper($v82);
}
}
if (!function_exists('f13')) {
function f13($v196) {
$v196 /= 3; 
$v196--;    
$v196 = max($v196, 0);
$v196 = min($v196, 255);
$v135 = max(255 - ($v196 * 2), 0);
$v85 = max(($v196 * 2) - 255, 0);
if ($v196 > 127) {
$v41 = max((255 - $v196) * 2, 0);
} else {
$v41 = max($v196 * 2, 0);
}
return str_pad(dechex($v135), 2, '0', STR_PAD_LEFT).str_pad(dechex($v85), 2, '0', STR_PAD_LEFT).str_pad(dechex($v41), 2, '0', STR_PAD_LEFT);
}
}
if (!function_exists('f14')) {
function f14($v196) {
return '<SPAN STYLE="color: #'.f13($v196).'">'.round($v196).' kbps</SPAN>';
}
}
if (!function_exists('f134')) {
function f134($v356) {
static $v351 = array();
if (empty($v351)) {
$v351[1]  = 'image/gif';                     
$v351[2]  = 'image/jpeg';                    
$v351[3]  = 'image/png';                     
$v351[4]  = 'application/x-shockwave-flash'; 
$v351[5]  = 'image/psd';                     
$v351[6]  = 'image/bmp';                     
$v351[7]  = 'image/tiff';                    
$v351[8]  = 'image/tiff';                    
$v351[13] = 'application/x-shockwave-flash'; 
$v351[14] = 'image/iff';                     
}
return (isset($v351[$v356]) ? $v351[$v356] : 'application/octet-stream');
}
}
if (!function_exists('f147')) {
function f147($v530) {
$v529 = strlen($v530);
$v231 = '';
for ($i = 0; $i < $v529; $i++) {
if ((ord($v530{$i}) & 0x80) == 0) {
$v231 .= $v530{$i};
} elseif ((ord($v530{$i}) & 0xF0) == 0xF0) {
$v231 .= '?';
$i += 3;
} elseif ((ord($v530{$i}) & 0xE0) == 0xE0) {
$v231 .= '?';
$i += 2;
} elseif ((ord($v530{$i}) & 0xC0) == 0xC0) {
$v230 = f11(substr(f30(ord($v530{$i})), 3, 5).substr(f30(ord($v530{($i + 1)})), 2, 6));
if ($v230 <= 255) {
$v231 .= chr($v230);
} else {
$v231 .= '?';
}
$i += 1;
}
}
return $v231;
}
}
if (!function_exists('f28')) {
function f28($v391) {
return f20($v391 - 2082844800);
}
}
if (!function_exists('f40')) {
function f40($v448) {
return f9(substr($v448, 0, 1)) + (float) (f9(substr($v448, 1, 1)) / pow(2, 8));
}
}
if (!function_exists('f38')) {
function f38($v448) {
return f9(substr($v448, 0, 2)) + (float) (f9(substr($v448, 2, 2)) / pow(2, 16));
}
}
if (!function_exists('f39')) {
function f39($v448) {
$v190 = f7($v448);
return f11(substr($v190, 0, 2)) + (float) (f11(substr($v190, 2, 30)) / pow(2, 30));
}
}
if (!function_exists('f93')) {
function f93($v428) {
return substr($v428, 1);
}
}
if (!function_exists('f89')) {
function f89($v418) {
if (substr($v418, strlen($v418) - 1, 1) === chr(0)) {
return substr($v418, 0, strlen($v418) - 1);
}
return $v418;
}
}
if (!function_exists('f35')) {
function f35($v265, $v440=2) {
if ($v265 < 1000) {
$v478  = 'bytes';
$v440 = 0;
} else {
$v265 /= 1024;
$v478 = 'kB';
}
if ($v265 >= 1000) {
$v265 /= 1024;
$v478 = 'MB';
}
if ($v265 >= 1000) {
$v265 /= 1024;
$v478 = 'GB';
}
return number_format($v265, $v440).' '.$v478;
}
}
if (!function_exists('f27')) {
function f27($v52, $v53) {
$v154    =  ($v52 & 0x001F);
$v157  = (($v52 & 0x01E0) >> 5);
$v159   = (($v52 & 0xFE00) >> 9) + 1980;
$v158 =  ($v53 & 0x001F) * 2;
$v156 = (($v53 & 0x07E0) >> 5);
$v155   = (($v53 & 0xF800) >> 11);
return mktime($v155, $v156, $v158, $v157, $v154, $v159);
}
}
if (!function_exists('f26')) {
function f26($v34, $v144, $v162) {
if (($pos = strpos($v34, $v144)) !== false) {
$v175[substr($v34, 0, $pos)] = f26(substr($v34, $pos + 1), $v144, $v162);
} else {
$v175["$v34"] = $v162;
}
return $v175;
}
}
if (!function_exists('f140')) {
function f140($v260) {
if (substr(php_uname(), 0, 7) == 'Windows') {
die('PHP 4.2.0 or newer required for f140()');
}
$v260 = str_replace('`', '\\`', $v260);
if (ereg("^([0-9a-f]{32})[ \t\n\r]", `md5sum "$v260"`, $r)) {
return $r[1];
}
return false;
}
}
if (!function_exists('f139')) {
function f139($v260, $v423, $end, $v366=false) {
if (($v225 = tempnam('.', eregi_replace('[^[:alnum:]]', '', dirname($v260)))) === false) {
$v225 = tempnam('/tmp', 'f123');
}
if ($v104 = fopen($v225, 'wb')) {
if ($fp = fopen($v260, 'rb')) {
if ($v366) {
                $v331 = '';
                $to   = '';
for ($i = 0; $i < 128; $i++) {
$v331 .= chr($i);
$to   .= chr($i + 128);
}
for ($i = 128; $i < 256; $i++) {
$v331 .= chr($i);
$to   .= chr($i - 128);
}
}
fseek($fp, $v423, SEEK_SET);
$v207 = $end - $v423;
while (($v207 > 0) && ($v204 = fread($fp, FREAD_BUFFER_SIZE))) {
if ($v366) {
$v204 = strtr($v204, $v331, $to);
}
$v207 -= fwrite($v104, $v204, $v207);
}
fclose($fp);
} else {
return false;
}
fclose($v104);
$md5 = f140($v225);
unlink($v225);
return $md5;
}
return false;
}
}
if (!function_exists('f107')) {
function f107($v42) {
if ($v42 & 0x80) {
return (0 - ((~$v42 & 0xFF) + 1));
} else {
return $v42;
}
}
}
if (!function_exists('f71')) {
function f71($v126) {
if (!is_array($v126)) {
return false;
}
if (empty($v126)) {
return null;
}
foreach ($v126 as $key => $v531) {
}
return $v531;
}
}
if (!function_exists('f143')) {
function f143(&$v536, $v360=1) {
if (isset($v536)) {
$v536 += $v360;
} else {
$v536 = $v360;
}
return true;
}
}
if (!function_exists('f19')) {
function f19(&$v152) {
if (empty($v152['video'])) {
return false;
}
if (empty($v152['video']['resolution_x']) || empty($v152['video']['resolution_y'])) {
return false;
}
if (empty($v152['video']['bits_per_sample'])) {
return false;
}
switch ($v152['video']['dataformat']) {
case 'bmp':
case 'gif':
case 'jpeg':
case 'jpg':
case 'png':
case 'tiff':
$v71 = 1;
$v129 = 1;
$v44 = $v152['filesize'] * 8;
break;
default:
if (!empty($v152['video']['frame_rate'])) {
$v71 = $v152['video']['frame_rate'];
} else {
return false;
}
if (!empty($v152['playtime_seconds'])) {
$v129 = $v152['playtime_seconds'];
} else {
return false;
}
if (!empty($v152['video']['bitrate'])) {
$v44 = $v152['video']['bitrate'];
} else {
return false;
}
break;
}
$v46 = $v152['video']['resolution_x'] * $v152['video']['resolution_y'] * $v152['video']['bits_per_sample'] * $v71;
$v152['video']['compression_ratio'] = $v44 / $v46;
return true;
}
}
if (!function_exists('f18')) {
function f18(&$v152) {
if (empty($v152['audio']['bitrate']) || empty($v152['audio']['channels']) || empty($v152['audio']['sample_rate']) || empty($v152['audio']['bits_per_sample'])) {
return false;
}
$v152['audio']['compression_ratio'] = $v152['audio']['bitrate'] / ($v152['audio']['channels'] * $v152['audio']['sample_rate'] * $v152['audio']['bits_per_sample']);
return true;
}
}
function f59($v308, $v296, &$v152) {
$v284 = count($v152['id3v2']["$v308"]); 
if (isset($v152['id3v2']["$v308"]['data'])) {
$v284--;
}
if (isset($v152['id3v2']["$v308"]['datalength'])) {
$v284--;
}
if (isset($v152['id3v2']["$v308"]['dataoffset'])) {
$v284--;
}
if (isset($v152['id3v2']["$v308"]['flags'])) {
$v284--;
}
if (isset($v152['id3v2']["$v308"]['timestampformat'])) {
$v284--;
}
if ($v152['id3v2']['majorversion'] >= 3) { 
if ($v152['id3v2']['majorversion'] == 3) {
$v152['id3v2']["$v308"]['flags']['TagAlterPreservation']  = (bool) substr($v296,  0, 1); 
$v152['id3v2']["$v308"]['flags']['FileAlterPreservation'] = (bool) substr($v296,  1, 1); 
$v152['id3v2']["$v308"]['flags']['ReadOnly']              = (bool) substr($v296,  2, 1); 
$v152['id3v2']["$v308"]['flags']['compression']           = (bool) substr($v296,  8, 1); 
$v152['id3v2']["$v308"]['flags']['Encryption']            = (bool) substr($v296,  9, 1); 
$v152['id3v2']["$v308"]['flags']['GroupingIdentity']      = (bool) substr($v296, 10, 1); 
} elseif ($v152['id3v2']['majorversion'] == 4) {
$v152['id3v2']["$v308"]['flags']['TagAlterPreservation']  = (bool) substr($v296,  1, 1); 
$v152['id3v2']["$v308"]['flags']['FileAlterPreservation'] = (bool) substr($v296,  2, 1); 
$v152['id3v2']["$v308"]['flags']['ReadOnly']              = (bool) substr($v296,  3, 1); 
$v152['id3v2']["$v308"]['flags']['GroupingIdentity']      = (bool) substr($v296,  9, 1); 
$v152['id3v2']["$v308"]['flags']['compression']           = (bool) substr($v296, 12, 1); 
$v152['id3v2']["$v308"]['flags']['Encryption']            = (bool) substr($v296, 13, 1); 
$v152['id3v2']["$v308"]['flags']['Unsynchronisation']     = (bool) substr($v296, 14, 1); 
$v152['id3v2']["$v308"]['flags']['DataLengthIndicator']   = (bool) substr($v296, 15, 1); 
}
if (isset($v152['id3v2']["$v308"]['flags']['Unsynchronisation'])) {
$v152['id3v2']["$v308"]['data'] = f29($v152['id3v2']["$v308"]['data']);
}
if (isset($v152['id3v2']["$v308"]['flags']['compression'])) {
}
}
if (isset($v152['id3v2']["$v308"]['datalength']) && ($v152['id3v2']["$v308"]['datalength'] == 0)) {
$v152['warning'] .= "\n".'Frame "'.$v308.'" at offset '.$v152['id3v2']["$v308"]['dataoffset'].' has no data portion';
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'UFID')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'UFI'))) {  
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0));
$v300 = substr($v152['id3v2']["$v308"]['data'], 0, $v321);
$v152['id3v2']["$v308"][$v284]['ownerid'] = $v300;
$v152['id3v2']["$v308"][$v284]['data'] = substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(chr(0)));
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags'] = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'TXXX')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'TXX'))) {     
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v292 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v292) === 0) {
$v292 = '';
}
$v152['id3v2']["$v308"][$v284]['encodingid']  = $v323;
$v152['id3v2']["$v308"][$v284]['encoding']    = f105('encoding', $v323);
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags']   = $v152['id3v2']["$v308"]['flags'];
}
$v152['id3v2']["$v308"][$v284]['description'] = $v292;
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['asciidescription'] = f102($v292, $v323);
}
$v152['id3v2']["$v308"][$v284]['data'] = substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)));
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['asciidata'] = f102($v152['id3v2']["$v308"][$v284]['data'], $v323);
}
if ($v152['id3v2']['majorversion'] >= 3) {
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
if (f44($v308) && $v152['id3v2']["$v308"][$v284]['asciidata']) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"][$v284]['asciidata'];
}
} elseif ($v308{0} == 'T') { 
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
if ($v321) {
$v152['id3v2']["$v308"]['data'] = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
} else {
$v152['id3v2']["$v308"]['data'] = substr($v152['id3v2']["$v308"]['data'], $v309);
}
if (!isset($v152['id3v2']["$v308"]['flags']['compression']) || !$v152['id3v2']["$v308"]['flags']['compression']) {
$v152['id3v2']["$v308"]['asciidata'] = f102($v152['id3v2']["$v308"]['data'], $v323);
}
$v152['id3v2']["$v308"]['encodingid']    = $v323;
$v152['id3v2']["$v308"]['encoding']      = f105('encoding', $v323);
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
if (f44($v308) && !empty($v152['id3v2']["$v308"]['asciidata'])) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"]['asciidata'];
}
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'WXXX')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'WXX'))) {     
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v292 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v292) === 0) {
$v292 = '';
}
$v152['id3v2']["$v308"]['data'] = substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)));
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323));
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
if ($v321) {
$v325 = substr($v152['id3v2']["$v308"]['data'], 0, $v321);
} else {
$v325 = $v152['id3v2']["$v308"]['data'];
}
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags']   = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['encodingid']  = $v323;
$v152['id3v2']["$v308"][$v284]['encoding']    = f105('encoding', $v323);
$v152['id3v2']["$v308"][$v284]['url']         = $v325;
$v152['id3v2']["$v308"][$v284]['description'] = $v292;
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['asciidescription'] = f102($v292, $v323);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
if (f44($v308) && $v152['id3v2']["$v308"][$v284]['url']) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"][$v284]['url'];
}
} elseif ($v308{0} == 'W') { 
$v152['id3v2']["$v308"][$v284]['url'] = trim($v152['id3v2']["$v308"]['data']);
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags'] = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
if (f44($v308) && $v152['id3v2']["$v308"][$v284]['url']) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"][$v284]['url'];
}
} elseif ((($v152['id3v2']['majorversion'] == 3) && ($v308 == 'IPLS')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'IPL'))) {     
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v152['id3v2']["$v308"]['encodingid']    = $v323;
$v152['id3v2']["$v308"]['encoding']      = f105('encoding', $v152['id3v2']["$v308"]['encodingid']);
$v152['id3v2']["$v308"]['data']          = substr($v152['id3v2']["$v308"]['data'], $v309);
$v152['id3v2']["$v308"]['asciidata']     = f102($v152['id3v2']["$v308"]['data'], $v323);
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
if (f44($v308) && $v152['id3v2']["$v308"]['asciidata']) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"]['asciidata'];
}
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'MCDI')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'MCI'))) {     
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
if (f44($v308) && $v152['id3v2']["$v308"]['data']) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"]['data'];
}
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'ETCO')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'ETC'))) {     
$v309 = 0;
$v152['id3v2']["$v308"]['timestampformat'] = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
while ($v309 < strlen($v152['id3v2']["$v308"]['data'])) {
$v152['id3v2']["$v308"][$v284]['typeid']    = substr($v152['id3v2']["$v308"]['data'], $v309++, 1);
$v152['id3v2']["$v308"][$v284]['type']      = f33($v152['id3v2']["$v308"][$v284]['typeid']);
$v152['id3v2']["$v308"][$v284]['timestamp'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 4));
$v309 += 4;
}
if ($v152['id3v2']['majorversion'] >= 3) {
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'MLLT')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'MLL'))) {     
$v309 = 0;
$v152['id3v2']["$v308"]['framesbetweenreferences'] = f9(substr($v152['id3v2']["$v308"]['data'], 0, 2));
$v152['id3v2']["$v308"]['bytesbetweenreferences']  = f9(substr($v152['id3v2']["$v308"]['data'], 2, 3));
$v152['id3v2']["$v308"]['msbetweenreferences']     = f9(substr($v152['id3v2']["$v308"]['data'], 5, 3));
$v152['id3v2']["$v308"]['bitsforbytesdeviation']   = f9(substr($v152['id3v2']["$v308"]['data'], 8, 1));
$v152['id3v2']["$v308"]['bitsformsdeviation']      = f9(substr($v152['id3v2']["$v308"]['data'], 9, 1));
$v152['id3v2']["$v308"]['data'] = substr($v152['id3v2']["$v308"]['data'], 10);
while ($v309 < strlen($v152['id3v2']["$v308"]['data'])) {
$v235 .= f7(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
}
while (strlen($v235)) {
$v152['id3v2']["$v308"][$v284]['bytedeviation'] = bindec(substr($v235, 0, $v152['id3v2']["$v308"]['bitsforbytesdeviation']));
$v152['id3v2']["$v308"][$v284]['msdeviation']   = bindec(substr($v235, $v152['id3v2']["$v308"]['bitsforbytesdeviation'], $v152['id3v2']["$v308"]['bitsformsdeviation']));
$v235 = substr($v235, $v152['id3v2']["$v308"]['bitsforbytesdeviation'] + $v152['id3v2']["$v308"]['bitsformsdeviation']);
$v284++;
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'SYTC')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'STC'))) {     
$v309 = 0;
$v152['id3v2']["$v308"]['timestampformat'] = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
while ($v309 < strlen($v152['id3v2']["$v308"]['data'])) {
$v152['id3v2']["$v308"][$v284]['tempo'] = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
if ($v152['id3v2']["$v308"][$v284]['tempo'] == 255) {
$v152['id3v2']["$v308"][$v284]['tempo'] += ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
}
$v152['id3v2']["$v308"][$v284]['timestamp'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 4));
$v309 += 4;
$v284++;
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'USLT')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'ULT'))) {     
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v306 = substr($v152['id3v2']["$v308"]['data'], $v309, 3);
$v309 += 3;
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v292 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v292) === 0) {
$v292 = '';
}
$v152['id3v2']["$v308"]['data'] = substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)));
$v152['id3v2']["$v308"][$v284]['encodingid']   = $v323;
$v152['id3v2']["$v308"][$v284]['encoding']     = f105('encoding', $v323);
$v152['id3v2']["$v308"][$v284]['data']         = $v152['id3v2']["$v308"]['data'];
$v152['id3v2']["$v308"][$v284]['language']     = $v306;
$v152['id3v2']["$v308"][$v284]['languagename'] = f70($v306, false);
$v152['id3v2']["$v308"][$v284]['description']  = $v292;
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['asciidescription'] = f102($v152['id3v2']["$v308"][$v284]['description'], $v323);
}
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags']    = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['asciidata'] = f102($v152['id3v2']["$v308"][$v284]['data'], $v323);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
if (f44($v308) && $v152['id3v2']["$v308"][$v284]['asciidata']) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"][$v284]['asciidata'];
}
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'SYLT')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'SLT'))) {     
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v306 = substr($v152['id3v2']["$v308"]['data'], $v309, 3);
$v309 += 3;
$v152['id3v2']["$v308"][$v284]['timestampformat'] = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"][$v284]['contenttypeid']   = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"][$v284]['contenttype']     = f103($v152['id3v2']["$v308"][$v284]['contenttypeid']);
$v152['id3v2']["$v308"][$v284]['encodingid']      = $v323;
$v152['id3v2']["$v308"][$v284]['encoding']        = f105('encoding', $v323);
$v152['id3v2']["$v308"][$v284]['language']        = $v306;
$v152['id3v2']["$v308"][$v284]['languagename']    = f70($v306, false);
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags']       = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
$v517 = 0;
$v317 = substr($v152['id3v2']["$v308"]['data'], $v309);
while (strlen($v317)) {
$v309 = 0;
$v321 = strpos($v317, f105('terminator', $v323));
if ($v321 === false) {
$v317 = '';
} else {
if (ord(substr($v317, $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v152['id3v2']["$v308"][$v284]['data'][$v517]['data'] = substr($v317, $v309, $v321 - $v309);
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['data'][$v517]['asciidata'] = f102($v152['id3v2']["$v308"][$v284]['data'][$v517]['data'], $v323);
}
$v317 = substr($v317, $v321 + strlen(f105('terminator', $v323)));
if (($v517 == 0) && (ord($v317{0}) != 0)) {
} else {
$v152['id3v2']["$v308"][$v284]['data'][$v517]['timestamp'] = f9(substr($v317, 0, 4));
$v317 = substr($v317, 4);
}
$v517++;
}
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'COMM')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'COM'))) {     
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v306 = substr($v152['id3v2']["$v308"]['data'], $v309, 3);
$v309 += 3;
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v292 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v292) === 0) {
$v292 = '';
}
$v322 = substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)));
$v152['id3v2']["$v308"][$v284]['encodingid']   = $v323;
$v152['id3v2']["$v308"][$v284]['encoding']     = f105('encoding', $v323);
$v152['id3v2']["$v308"][$v284]['language']     = $v306;
$v152['id3v2']["$v308"][$v284]['languagename'] = f70($v306, false);
$v152['id3v2']["$v308"][$v284]['description']  = $v292;
$v152['id3v2']["$v308"][$v284]['data']         = $v322;
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags']    = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['asciidescription'] = f102($v292, $v323);
}
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['asciidata']        = f102($v322, $v323);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
if (f44($v308) && $v152['id3v2']["$v308"][$v284]['asciidata']) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"][$v284]['asciidata'];
}
} elseif (($v152['id3v2']['majorversion'] >= 4) && ($v308 == 'RVA2')) { 
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0));
$v300 = substr($v152['id3v2']["$v308"]['data'], 0, $v321);
if (ord($v300) === 0) {
$v300 = '';
}
$v317 = substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(chr(0)));
$v152['id3v2']["$v308"][$v284]['description'] = $v300;
while (strlen($v317)) {
$v309 = 0;
$v288 = ord(substr($v317, $v309++, 1));
$v152['id3v2']["$v308"][$v284][$v288]['channeltypeid']  = $v288;
$v152['id3v2']["$v308"][$v284][$v288]['channeltype']    = f99($v288);
$v152['id3v2']["$v308"][$v284][$v288]['volumeadjust']   = f9(substr($v317, $v309, 2), false, true); 
$v309 += 2;
$v152['id3v2']["$v308"][$v284][$v288]['bitspeakvolume'] = ord(substr($v317, $v309++, 1));
$v285 = ceil($v152['id3v2']["$v308"][$v288]['bitspeakvolume'] / 8);
$v152['id3v2']["$v308"][$v284][$v288]['peakvolume']     = f9(substr($v317, $v309, $v285));
$v317 = substr($v317, $v309 + $v285);
}
$v152['id3v2']["$v308"][$v284][$v288]['flags'] = $v152['id3v2']["$v308"]['flags'];
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['flags']);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] == 3) && ($v308 == 'RVAD')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'RVA'))) {     
$v309 = 0;
$v303 = f7(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['incdec']['right'] = (bool) substr($v303, 6, 1);
$v152['id3v2']["$v308"]['incdec']['left']  = (bool) substr($v303, 7, 1);
$v152['id3v2']["$v308"]['bitsvolume'] = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v287 = ceil($v152['id3v2']["$v308"]['bitsvolume'] / 8);
$v152['id3v2']["$v308"]['volumechange']['right'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
if ($v152['id3v2']["$v308"]['incdec']['right'] === false) {
$v152['id3v2']["$v308"]['volumechange']['right'] *= -1;
}
$v309 += $v287;
$v152['id3v2']["$v308"]['volumechange']['left'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
if ($v152['id3v2']["$v308"]['incdec']['left'] === false) {
$v152['id3v2']["$v308"]['volumechange']['left'] *= -1;
}
$v309 += $v287;
$v152['id3v2']["$v308"]['peakvolume']['right'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
$v309 += $v287;
$v152['id3v2']["$v308"]['peakvolume']['left']  = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
$v309 += $v287;
if ($v152['id3v2']['majorversion'] == 3) {
$v152['id3v2']["$v308"]['data'] = substr($v152['id3v2']["$v308"]['data'], $v309);
if (strlen($v152['id3v2']["$v308"]['data']) > 0) {
$v152['id3v2']["$v308"]['incdec']['rightrear'] = (bool) substr($v303, 4, 1);
$v152['id3v2']["$v308"]['incdec']['leftrear']  = (bool) substr($v303, 5, 1);
$v152['id3v2']["$v308"]['volumechange']['rightrear'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
if ($v152['id3v2']["$v308"]['incdec']['rightrear'] === false) {
$v152['id3v2']["$v308"]['volumechange']['rightrear'] *= -1;
}
$v309 += $v287;
$v152['id3v2']["$v308"]['volumechange']['leftrear'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
if ($v152['id3v2']["$v308"]['incdec']['leftrear'] === false) {
$v152['id3v2']["$v308"]['volumechange']['leftrear'] *= -1;
}
$v309 += $v287;
$v152['id3v2']["$v308"]['peakvolume']['rightrear'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
$v309 += $v287;
$v152['id3v2']["$v308"]['peakvolume']['leftrear']  = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
$v309 += $v287;
}
$v152['id3v2']["$v308"]['data'] = substr($v152['id3v2']["$v308"]['data'], $v309);
if (strlen($v152['id3v2']["$v308"]['data']) > 0) {
$v152['id3v2']["$v308"]['incdec']['center'] = (bool) substr($v303, 3, 1);
$v152['id3v2']["$v308"]['volumechange']['center'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
if ($v152['id3v2']["$v308"]['incdec']['center'] === false) {
$v152['id3v2']["$v308"]['volumechange']['center'] *= -1;
}
$v309 += $v287;
$v152['id3v2']["$v308"]['peakvolume']['center'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
$v309 += $v287;
}
$v152['id3v2']["$v308"]['data'] = substr($v152['id3v2']["$v308"]['data'], $v309);
if (strlen($v152['id3v2']["$v308"]['data']) > 0) {
$v152['id3v2']["$v308"]['incdec']['bass'] = (bool) substr($v303, 2, 1);
$v152['id3v2']["$v308"]['volumechange']['bass'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
if ($v152['id3v2']["$v308"]['incdec']['bass'] === false) {
$v152['id3v2']["$v308"]['volumechange']['bass'] *= -1;
}
$v309 += $v287;
$v152['id3v2']["$v308"]['peakvolume']['bass'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v287));
$v309 += $v287;
}
}
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
} elseif (($v152['id3v2']['majorversion'] >= 4) && ($v308 == 'EQU2')) { 
$v309 = 0;
$v305 = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v300 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v300) === 0) {
$v300 = '';
}
$v152['id3v2']["$v308"][$v284]['description'] = $v300;
$v317 = substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(chr(0)));
while (strlen($v317)) {
$v297 = f9(substr($v317, 0, 2)) / 2;
$v152['id3v2']["$v308"][$v284]['data'][$v297] = f9(substr($v317, 2, 2), false, true);
$v317 = substr($v317, 4);
}
$v152['id3v2']["$v308"][$v284]['interpolationmethod'] = $v305;
$v152['id3v2']["$v308"][$v284]['flags'] = $v152['id3v2']["$v308"]['flags'];
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['flags']);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] == 3) && ($v308 == 'EQUA')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'EQU'))) {     
$v309 = 0;
$v152['id3v2']["$v308"]['adjustmentbits'] = substr($v152['id3v2']["$v308"]['data'], $v309++, 1);
$v283 = ceil($v152['id3v2']["$v308"]['adjustmentbits'] / 8);
$v317 = substr($v152['id3v2']["$v308"]['data'], $v309);
while (strlen($v317)) {
$v298 = f7(substr($v317, 0, 2));
$v302    = (bool) substr($v298, 0, 1);
$v297 = bindec(substr($v298, 1, 15));
$v152['id3v2']["$v308"][$v297]['incdec'] = $v302;
$v152['id3v2']["$v308"][$v297]['adjustment'] = f9(substr($v317, 2, $v283));
if ($v152['id3v2']["$v308"][$v297]['incdec'] === false) {
$v152['id3v2']["$v308"][$v297]['adjustment'] *= -1;
}
$v317 = substr($v317, 2 + $v283);
}
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'RVRB')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'REV'))) {     
$v309 = 0;
$v152['id3v2']["$v308"]['left']  = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 2));
$v309 += 2;
$v152['id3v2']["$v308"]['right'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 2));
$v309 += 2;
$v152['id3v2']["$v308"]['bouncesL']      = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['bouncesR']      = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['feedbackLL']    = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['feedbackLR']    = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['feedbackRR']    = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['feedbackRL']    = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['premixLR']      = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['premixRL']      = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'APIC')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'PIC'))) {     
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
if ($v152['id3v2']['majorversion'] == 2) {
$v301 = substr($v152['id3v2']["$v308"]['data'], $v309, 3);
if (strtolower($v301) == 'ima') {
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v307 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v307) === 0) {
$v307 = '';
}
$v301 = strtoupper(str_replace('image/', '', strtolower($v307)));
if ($v301 == 'JPEG') {
$v301 = 'JPG';
}
$v309 = $v321 + strlen(chr(0));
} else {
$v309 += 3;
}
}
if ($v152['id3v2']['majorversion'] > 2) {
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v307 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v307) === 0) {
$v307 = '';
}
$v309 = $v321 + strlen(chr(0));
}
$v311 = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v292 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v292) === 0) {
$v292 = '';
}
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags']        = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['encodingid']       = $v323;
$v152['id3v2']["$v308"][$v284]['encoding']         = f105('encoding', $v323);
if ($v152['id3v2']['majorversion'] == 2) {
$v152['id3v2']["$v308"][$v284]['imagetype']    = $v301;
} else {
$v152['id3v2']["$v308"][$v284]['mime']         = $v307;
}
$v152['id3v2']["$v308"][$v284]['picturetypeid']    = $v311;
$v152['id3v2']["$v308"][$v284]['picturetype']      = f5($v311);
$v152['id3v2']["$v308"][$v284]['description']      = $v292;
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['asciidescription'] = f102($v292, $v323);
}
$v152['id3v2']["$v308"][$v284]['data']             = substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)));


$v353 = f50($v152['id3v2']["$v308"][$v284]['data']);
if (($v353[2] >= 1) && ($v353[2] <= 3)) {
if ($v353[0]) {
$v152['id3v2']["$v308"][$v284]['image_width']  = $v353[0];
}
if ($v353[1]) {
$v152['id3v2']["$v308"][$v284]['image_height'] = $v353[1];
}
$v152['id3v2']["$v308"][$v284]['image_bytes']      = strlen($v152['id3v2']["$v308"][$v284]['data']);
}
$v152['id3v2']["$v308"][$v284]['framenamelong']    = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
if (isset($v152['id3v2']["$v308"]['datalength'])) {
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
}
if (isset($v152['id3v2']["$v308"]['dataoffset'])) {
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
}
} else if ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'GEOB')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'GEO'))) {     
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v307 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v307) === 0) {
$v307 = '';
}
$v309 = $v321 + strlen(chr(0));
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v295 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v295) === 0) {
$v295 = '';
}
$v309 = $v321 + strlen(f105('terminator', $v323));
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v292 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v292) === 0) {
$v292 = '';
}
$v309 = $v321 + strlen(f105('terminator', $v323));
$v152['id3v2']["$v308"][$v284]['objectdata']       = substr($v152['id3v2']["$v308"]['data'], $v309);
$v152['id3v2']["$v308"][$v284]['encodingid']       = $v323;
$v152['id3v2']["$v308"][$v284]['encoding']         = f105('encoding', $v323);
$v152['id3v2']["$v308"][$v284]['mime']             = $v307;
$v152['id3v2']["$v308"][$v284]['filename']         = $v295;
$v152['id3v2']["$v308"][$v284]['description']      = $v292;
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags']        = $v152['id3v2']["$v308"]['flags'];
if (!isset($v152['id3v2']["$v308"][$v284]['flags']['compression']) || ($v152['id3v2']["$v308"][$v284]['flags']['compression'] === false)) {
$v152['id3v2']["$v308"][$v284]['asciidescription'] = f102($v292, $v323);
}
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'PCNT')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'CNT'))) {     
$v152['id3v2']["$v308"]['data']          = f9($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'POPM')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'POP'))) {     
$v309 = 0;
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v293 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v293) === 0) {
$v293 = '';
}
$v309 = $v321 + strlen(chr(0));
$v314 = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['data'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309));
$v152['id3v2']["$v308"][$v284]['email']  = $v293;
$v152['id3v2']["$v308"][$v284]['rating'] = $v314;
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags'] = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'RBUF')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'BUF'))) {     
$v309 = 0;
$v152['id3v2']["$v308"]['buffersize'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 3));
$v309 += 3;
$v294 = f7(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['flags']['embededinfo'] = (bool) substr($v294, 7, 1);
$v152['id3v2']["$v308"]['nexttagoffset'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 4));
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
} elseif (($v152['id3v2']['majorversion'] == 2) && ($v308 == 'CRM')) { 
$v309 = 0;
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v310 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v310) === 0) {
$v310 = count($v152['id3v2']["$v308"]) - 1;
}
$v309 = $v321 + strlen(chr(0));
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v292 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v292) === 0) {
$v292 = '';
}
$v309 = $v321 + strlen(chr(0));
$v152['id3v2']["$v308"][$v284]['ownerid']       = $v310;
$v152['id3v2']["$v308"][$v284]['data']          = substr($v152['id3v2']["$v308"]['data'], $v309);
$v152['id3v2']["$v308"][$v284]['description']   = $v292;
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'AENC')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'CRA'))) {     
$v309 = 0;
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v310 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v310) === 0) {
$v310 == '';
}
$v309 = $v321 + strlen(chr(0));
$v152['id3v2']["$v308"][$v284]['ownerid'] = $v310;
$v152['id3v2']["$v308"][$v284]['previewstart'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 2));
$v309 += 2;
$v152['id3v2']["$v308"][$v284]['previewlength'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 2));
$v309 += 2;
$v152['id3v2']["$v308"][$v284]['encryptioninfo'] = substr($v152['id3v2']["$v308"]['data'], $v309);
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"]["$v310"]['flags'] = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif ((($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'LINK')) || 
(($v152['id3v2']['majorversion'] == 2) && ($v308 == 'LNK'))) {     
$v309 = 0;
if ($v152['id3v2']['majorversion'] == 2) {
$v152['id3v2']["$v308"][$v284]['frameid'] = substr($v152['id3v2']["$v308"]['data'], $v309, 3);
$v309 += 3;
} else {
$v152['id3v2']["$v308"][$v284]['frameid'] = substr($v152['id3v2']["$v308"]['data'], $v309, 4);
$v309 += 4;
}
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v324 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v324) === 0) {
$v324 = '';
}
$v309 = $v321 + strlen(chr(0));
$v152['id3v2']["$v308"][$v284]['url'] = $v324;
$v152['id3v2']["$v308"][$v284]['additionaldata'] = substr($v152['id3v2']["$v308"]['data'], $v309);
if ($v152['id3v2']['majorversion'] >= 3) {
$v152['id3v2']["$v308"][$v284]['flags'] = $v152['id3v2']["$v308"]['flags'];
unset($v152['id3v2']["$v308"]['flags']);
}
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
if (f44($v308) && $v152['id3v2']["$v308"][$v284]['url']) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"][$v284]['url'];
}
} elseif (($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'POSS')) { 
$v309 = 0;
$v152['id3v2']["$v308"]['timestampformat'] = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"]['position']        = f9(substr($v152['id3v2']["$v308"]['data'], $v309));
$v152['id3v2']["$v308"]['framenamelong']   = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
} elseif (($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'USER')) { 
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v306 = substr($v152['id3v2']["$v308"]['data'], $v309, 3);
$v309 += 3;
$v152['id3v2']["$v308"]["$v306"]['language']      = $v306;
$v152['id3v2']["$v308"]["$v306"]['languagename']  = f70($v306, false);
$v152['id3v2']["$v308"]["$v306"]['encodingid']    = $v323;
$v152['id3v2']["$v308"]["$v306"]['encoding']      = f105('encoding', $v323);
$v152['id3v2']["$v308"]["$v306"]['data']          = substr($v152['id3v2']["$v308"]['data'], $v309);
if (!$v152['id3v2']["$v308"]['flags']['compression']) {
$v152['id3v2']["$v308"]["$v306"]['asciidata'] = f102($v152['id3v2']["$v308"]["$v306"]['data'], $v323);
}
$v152['id3v2']["$v308"]["$v306"]['flags']         = $v152['id3v2']["$v308"]['flags'];
$v152['id3v2']["$v308"]["$v306"]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['flags']);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"]["$v306"]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"]["$v306"]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
if (f44($v308) && $v152['id3v2']["$v308"]["$v306"]['asciidata']) {
$v152['id3v2']['comments'][f44($v308)][] = $v152['id3v2']["$v308"]["$v306"]['asciidata'];
}
} elseif (($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'OWNE')) { 
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v152['id3v2']["$v308"]['encodingid'] = $v323;
$v152['id3v2']["$v308"]['encoding']   = f105('encoding', $v323);
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v312 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
$v309 = $v321 + strlen(chr(0));
$v152['id3v2']["$v308"]['pricepaid']['currencyid'] = substr($v312, 0, 3);
$v152['id3v2']["$v308"]['pricepaid']['currency']   = f75($v152['id3v2']["$v308"]['pricepaid']['currencyid'], 'units');
$v152['id3v2']["$v308"]['pricepaid']['value']      = substr($v312, 3);
$v152['id3v2']["$v308"]['purchasedate'] = substr($v152['id3v2']["$v308"]['data'], $v309, 8);
if (!f65($v152['id3v2']["$v308"]['purchasedate'])) {
$v152['id3v2']["$v308"]['purchasedateunix'] = mktime (0, 0, 0, substr($v152['id3v2']["$v308"]['purchasedate'], 4, 2), substr($v152['id3v2']["$v308"]['purchasedate'], 6, 2), substr($v152['id3v2']["$v308"]['purchasedate'], 0, 4));
}
$v309 += 8;
$v152['id3v2']["$v308"]['seller']        = substr($v152['id3v2']["$v308"]['data'], $v309);
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
} elseif (($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'COMR')) { 
$v309 = 0;
$v323 = f106(ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1)), $v152, $v308);
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v313 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
$v309 = $v321 + strlen(chr(0));
$v315 = explode('/', $v313);
foreach ($v315 as $key => $val) {
$v290 = substr($val, 0, 3);
$v152['id3v2']["$v308"][$v284]['price']["$v290"]['currency'] = f75($v290, 'units');
$v152['id3v2']["$v308"][$v284]['price']["$v290"]['value']    = substr($val, 3);
}
$v291 = substr($v152['id3v2']["$v308"]['data'], $v309, 8);
$v309 += 8;
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v289 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
$v309 = $v321 + strlen(chr(0));
$v316 = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v319 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v319) === 0) {
$v319 = '';
}
$v309 = $v321 + strlen(f105('terminator', $v323));
$v321 = strpos($v152['id3v2']["$v308"]['data'], f105('terminator', $v323), $v309);
if (ord(substr($v152['id3v2']["$v308"]['data'], $v321 + strlen(f105('terminator', $v323)), 1)) === 0) {
$v321++; 
}
$v292 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v292) === 0) {
$v292 = '';
}
$v309 = $v321 + strlen(f105('terminator', $v323));
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v307 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
$v309 = $v321 + strlen(chr(0));
$v318 = substr($v152['id3v2']["$v308"]['data'], $v309);
$v152['id3v2']["$v308"][$v284]['encodingid']        = $v323;
$v152['id3v2']["$v308"][$v284]['encoding']          = f105('encoding', $v323);
$v152['id3v2']["$v308"][$v284]['pricevaliduntil']   = $v291;
$v152['id3v2']["$v308"][$v284]['contacturl']        = $v289;
$v152['id3v2']["$v308"][$v284]['receivedasid']      = $v316;
$v152['id3v2']["$v308"][$v284]['receivedas']        = f17($v316);
$v152['id3v2']["$v308"][$v284]['sellername']        = $v319;
$v152['id3v2']["$v308"][$v284]['asciisellername']   = f102($v319, $v323);
$v152['id3v2']["$v308"][$v284]['description']       = $v292;
$v152['id3v2']["$v308"][$v284]['asciidescription']  = f102($v292, $v323);
$v152['id3v2']["$v308"][$v284]['mime']              = $v307;
$v152['id3v2']["$v308"][$v284]['logo']              = $v318;
$v152['id3v2']["$v308"][$v284]['flags']             = $v152['id3v2']["$v308"]['flags'];
$v152['id3v2']["$v308"][$v284]['framenamelong']     = f43($v308);
unset($v152['id3v2']["$v308"]['flags']);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif (($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'ENCR')) { 
$v309 = 0;
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v310 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v310) === 0) {
$v310 = '';
}
$v309 = $v321 + strlen(chr(0));
$v152['id3v2']["$v308"][$v284]['ownerid']       = $v310;
$v152['id3v2']["$v308"][$v284]['methodsymbol']  = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"][$v284]['data']          = substr($v152['id3v2']["$v308"]['data'], $v309);
$v152['id3v2']["$v308"][$v284]['flags']         = $v152['id3v2']["$v308"]['flags'];
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['flags']);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif (($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'GRID')) { 
$v309 = 0;
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v310 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v310) === 0) {
$v310 = '';
}
$v309 = $v321 + strlen(chr(0));
$v152['id3v2']["$v308"][$v284]['ownerid']       = $v310;
$v152['id3v2']["$v308"][$v284]['groupsymbol']   = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"][$v284]['data']          = substr($v152['id3v2']["$v308"]['data'], $v309);
$v152['id3v2']["$v308"][$v284]['flags']         = $v152['id3v2']["$v308"]['flags'];
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['flags']);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif (($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'PRIV')) { 
$v309 = 0;
$v321 = strpos($v152['id3v2']["$v308"]['data'], chr(0), $v309);
$v310 = substr($v152['id3v2']["$v308"]['data'], $v309, $v321 - $v309);
if (ord($v310) === 0) {
$v310 = '';
}
$v309 = $v321 + strlen(chr(0));
$v152['id3v2']["$v308"][$v284]['ownerid']       = $v310;
$v152['id3v2']["$v308"][$v284]['data']          = substr($v152['id3v2']["$v308"]['data'], $v309);
$v152['id3v2']["$v308"][$v284]['flags']         = $v152['id3v2']["$v308"]['flags'];
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['flags']);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif (($v152['id3v2']['majorversion'] >= 4) && ($v308 == 'SIGN')) { 
$v309 = 0;
$v152['id3v2']["$v308"][$v284]['groupsymbol']   = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v152['id3v2']["$v308"][$v284]['data']          = substr($v152['id3v2']["$v308"]['data'], $v309);
$v152['id3v2']["$v308"][$v284]['flags']         = $v152['id3v2']["$v308"]['flags'];
$v152['id3v2']["$v308"][$v284]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['flags']);
unset($v152['id3v2']["$v308"]['data']);
$v152['id3v2']["$v308"][$v284]['datalength'] = $v152['id3v2']["$v308"]['datalength'];
unset($v152['id3v2']["$v308"]['datalength']);
$v152['id3v2']["$v308"][$v284]['dataoffset'] = $v152['id3v2']["$v308"]['dataoffset'];
unset($v152['id3v2']["$v308"]['dataoffset']);
} elseif (($v152['id3v2']['majorversion'] >= 4) && ($v308 == 'SEEK')) { 
$v309 = 0;
$v152['id3v2']["$v308"]['data']          = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 4));
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
} elseif (($v152['id3v2']['majorversion'] >= 4) && ($v308 == 'ASPI')) { 
$v309 = 0;
$v152['id3v2']["$v308"]['datastart'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 4));
$v309 += 4;
$v152['id3v2']["$v308"]['indexeddatalength'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 4));
$v309 += 4;
$v152['id3v2']["$v308"]['indexpoints'] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, 2));
$v309 += 2;
$v152['id3v2']["$v308"]['bitsperpoint'] = ord(substr($v152['id3v2']["$v308"]['data'], $v309++, 1));
$v286 = ceil($v152['id3v2']["$v308"]['bitsperpoint'] / 8);
for ($i = 0; $i < $v304; $i++) {
$v152['id3v2']["$v308"]['indexes'][$i] = f9(substr($v152['id3v2']["$v308"]['data'], $v309, $v286));
$v309 += $v286;
}
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
} elseif (($v152['id3v2']['majorversion'] >= 3) && ($v308 == 'RGAD')) { 
$v309 = 0;
$v152['id3v2']["$v308"]['peakamplitude'] = f8(substr($v152['id3v2']["$v308"]['data'], $v309, 4));
$v309 += 4;
$v446 = f30(substr($v152['id3v2']["$v308"]['data'], $v309, 2));
$v309 += 2;
$v183 = f30(substr($v152['id3v2']["$v308"]['data'], $v309, 2));
$v309 += 2;
$v152['id3v2']["$v308"]['raw']['radio']['name']            = f11(substr($v446, 0, 3));
$v152['id3v2']["$v308"]['raw']['radio']['originator']      = f11(substr($v446, 3, 3));
$v152['id3v2']["$v308"]['raw']['radio']['signbit']         = f11(substr($v446, 6, 1));
$v152['id3v2']["$v308"]['raw']['radio']['adjustment']      = f11(substr($v446, 7, 9));
$v152['id3v2']["$v308"]['raw']['audiophile']['name']       = f11(substr($v183, 0, 3));
$v152['id3v2']["$v308"]['raw']['audiophile']['originator'] = f11(substr($v183, 3, 3));
$v152['id3v2']["$v308"]['raw']['audiophile']['signbit']    = f11(substr($v183, 6, 1));
$v152['id3v2']["$v308"]['raw']['audiophile']['adjustment'] = f11(substr($v183, 7, 9));
$v152['id3v2']["$v308"]['radio']['name']       = RGADnameLookup($v152['id3v2']["$v308"]['raw']['radio']['name']);
$v152['id3v2']["$v308"]['radio']['originator'] = RGADoriginatorLookup($v152['id3v2']["$v308"]['raw']['radio']['originator']);
$v152['id3v2']["$v308"]['radio']['adjustment'] = RGADadjustmentLookup($v152['id3v2']["$v308"]['raw']['radio']['adjustment'], $v152['id3v2']["$v308"]['raw']['radio']['signbit']);
$v152['id3v2']["$v308"]['audiophile']['name']       = RGADnameLookup($v152['id3v2']["$v308"]['raw']['audiophile']['name']);
$v152['id3v2']["$v308"]['audiophile']['originator'] = RGADoriginatorLookup($v152['id3v2']["$v308"]['raw']['audiophile']['originator']);
$v152['id3v2']["$v308"]['audiophile']['adjustment'] = RGADadjustmentLookup($v152['id3v2']["$v308"]['raw']['audiophile']['adjustment'], $v152['id3v2']["$v308"]['raw']['audiophile']['signbit']);
$v152['replay_gain']['radio']['peak']            = $v152['id3v2']["$v308"]['peakamplitude'];
$v152['replay_gain']['radio']['originator']      = $v152['id3v2']["$v308"]['radio']['originator'];
$v152['replay_gain']['radio']['adjustment']      = $v152['id3v2']["$v308"]['radio']['adjustment'];
$v152['replay_gain']['audiophile']['originator'] = $v152['id3v2']["$v308"]['audiophile']['originator'];
$v152['replay_gain']['audiophile']['adjustment'] = $v152['id3v2']["$v308"]['audiophile']['adjustment'];
$v152['id3v2']["$v308"]['framenamelong'] = f43($v308);
unset($v152['id3v2']["$v308"]['data']);
}
return true;
}
function f75($v223, $v368) {
static $v51 = array();
if (empty($v51)) {
$v51['AED']['country'] = 'United Arab Emirates';
$v51['AFA']['country'] = 'Afghanistan';
$v51['ALL']['country'] = 'Albania';
$v51['AMD']['country'] = 'Armenia';
$v51['ANG']['country'] = 'Netherlands Antilles';
$v51['AOA']['country'] = 'Angola';
$v51['ARS']['country'] = 'Argentina';
$v51['ATS']['country'] = 'Austria';
$v51['AUD']['country'] = 'Australia';
$v51['AWG']['country'] = 'Aruba';
$v51['AZM']['country'] = 'Azerbaijan';
$v51['BAM']['country'] = 'Bosnia and Herzegovina';
$v51['BBD']['country'] = 'Barbados';
$v51['BDT']['country'] = 'Bangladesh';
$v51['BEF']['country'] = 'Belgium';
$v51['BGL']['country'] = 'Bulgaria';
$v51['BHD']['country'] = 'Bahrain';
$v51['BIF']['country'] = 'Burundi';
$v51['BMD']['country'] = 'Bermuda';
$v51['BND']['country'] = 'Brunei Darussalam';
$v51['BOB']['country'] = 'Bolivia';
$v51['BRL']['country'] = 'Brazil';
$v51['BSD']['country'] = 'Bahamas';
$v51['BTN']['country'] = 'Bhutan';
$v51['BWP']['country'] = 'Botswana';
$v51['BYR']['country'] = 'Belarus';
$v51['BZD']['country'] = 'Belize';
$v51['CAD']['country'] = 'Canada';
$v51['CDF']['country'] = 'Congo/Kinshasa';
$v51['CHF']['country'] = 'Switzerland';
$v51['CLP']['country'] = 'Chile';
$v51['CNY']['country'] = 'China';
$v51['COP']['country'] = 'Colombia';
$v51['CRC']['country'] = 'Costa Rica';
$v51['CUP']['country'] = 'Cuba';
$v51['CVE']['country'] = 'Cape Verde';
$v51['CYP']['country'] = 'Cyprus';
$v51['CZK']['country'] = 'Czech Republic';
$v51['DEM']['country'] = 'Germany';
$v51['DJF']['country'] = 'Djibouti';
$v51['DKK']['country'] = 'Denmark';
$v51['DOP']['country'] = 'Dominican Republic';
$v51['DZD']['country'] = 'Algeria';
$v51['EEK']['country'] = 'Estonia';
$v51['EGP']['country'] = 'Egypt';
$v51['ERN']['country'] = 'Eritrea';
$v51['ESP']['country'] = 'Spain';
$v51['ETB']['country'] = 'Ethiopia';
$v51['EUR']['country'] = 'Euro Member Countries';
$v51['FIM']['country'] = 'Finland';
$v51['FJD']['country'] = 'Fiji';
$v51['FKP']['country'] = 'Falkland Islands (Malvinas)';
$v51['FRF']['country'] = 'France';
$v51['GBP']['country'] = 'United Kingdom';
$v51['GEL']['country'] = 'Georgia';
$v51['GGP']['country'] = 'Guernsey';
$v51['GHC']['country'] = 'Ghana';
$v51['GIP']['country'] = 'Gibraltar';
$v51['GMD']['country'] = 'Gambia';
$v51['GNF']['country'] = 'Guinea';
$v51['GRD']['country'] = 'Greece';
$v51['GTQ']['country'] = 'Guatemala';
$v51['GYD']['country'] = 'Guyana';
$v51['HKD']['country'] = 'Hong Kong';
$v51['HNL']['country'] = 'Honduras';
$v51['HRK']['country'] = 'Croatia';
$v51['HTG']['country'] = 'Haiti';
$v51['HUF']['country'] = 'Hungary';
$v51['IDR']['country'] = 'Indonesia';
$v51['IEP']['country'] = 'Ireland (Eire)';
$v51['ILS']['country'] = 'Israel';
$v51['IMP']['country'] = 'Isle of Man';
$v51['INR']['country'] = 'India';
$v51['IQD']['country'] = 'Iraq';
$v51['IRR']['country'] = 'Iran';
$v51['ISK']['country'] = 'Iceland';
$v51['ITL']['country'] = 'Italy';
$v51['JEP']['country'] = 'Jersey';
$v51['JMD']['country'] = 'Jamaica';
$v51['JOD']['country'] = 'Jordan';
$v51['JPY']['country'] = 'Japan';
$v51['KES']['country'] = 'Kenya';
$v51['KGS']['country'] = 'Kyrgyzstan';
$v51['KHR']['country'] = 'Cambodia';
$v51['KMF']['country'] = 'Comoros';
$v51['KPW']['country'] = 'Korea';
$v51['KWD']['country'] = 'Kuwait';
$v51['KYD']['country'] = 'Cayman Islands';
$v51['KZT']['country'] = 'Kazakstan';
$v51['LAK']['country'] = 'Laos';
$v51['LBP']['country'] = 'Lebanon';
$v51['LKR']['country'] = 'Sri Lanka';
$v51['LRD']['country'] = 'Liberia';
$v51['LSL']['country'] = 'Lesotho';
$v51['LTL']['country'] = 'Lithuania';
$v51['LUF']['country'] = 'Luxembourg';
$v51['LVL']['country'] = 'Latvia';
$v51['LYD']['country'] = 'Libya';
$v51['MAD']['country'] = 'Morocco';
$v51['MDL']['country'] = 'Moldova';
$v51['MGF']['country'] = 'Madagascar';
$v51['MKD']['country'] = 'Macedonia';
$v51['MMK']['country'] = 'Myanmar (Burma)';
$v51['MNT']['country'] = 'Mongolia';
$v51['MOP']['country'] = 'Macau';
$v51['MRO']['country'] = 'Mauritania';
$v51['MTL']['country'] = 'Malta';
$v51['MUR']['country'] = 'Mauritius';
$v51['MVR']['country'] = 'Maldives (Maldive Islands)';
$v51['MWK']['country'] = 'Malawi';
$v51['MXN']['country'] = 'Mexico';
$v51['MYR']['country'] = 'Malaysia';
$v51['MZM']['country'] = 'Mozambique';
$v51['NAD']['country'] = 'Namibia';
$v51['NGN']['country'] = 'Nigeria';
$v51['NIO']['country'] = 'Nicaragua';
$v51['NLG']['country'] = 'Netherlands (Holland)';
$v51['NOK']['country'] = 'Norway';
$v51['NPR']['country'] = 'Nepal';
$v51['NZD']['country'] = 'New Zealand';
$v51['OMR']['country'] = 'Oman';
$v51['PAB']['country'] = 'Panama';
$v51['PEN']['country'] = 'Peru';
$v51['PGK']['country'] = 'Papua New Guinea';
$v51['PHP']['country'] = 'Philippines';
$v51['PKR']['country'] = 'Pakistan';
$v51['PLN']['country'] = 'Poland';
$v51['PTE']['country'] = 'Portugal';
$v51['PYG']['country'] = 'Paraguay';
$v51['QAR']['country'] = 'Qatar';
$v51['ROL']['country'] = 'Romania';
$v51['RUR']['country'] = 'Russia';
$v51['RWF']['country'] = 'Rwanda';
$v51['SAR']['country'] = 'Saudi Arabia';
$v51['SBD']['country'] = 'Solomon Islands';
$v51['SCR']['country'] = 'Seychelles';
$v51['SDD']['country'] = 'Sudan';
$v51['SEK']['country'] = 'Sweden';
$v51['SGD']['country'] = 'Singapore';
$v51['SHP']['country'] = 'Saint Helena';
$v51['SIT']['country'] = 'Slovenia';
$v51['SKK']['country'] = 'Slovakia';
$v51['SLL']['country'] = 'Sierra Leone';
$v51['SOS']['country'] = 'Somalia';
$v51['SPL']['country'] = 'Seborga';
$v51['SRG']['country'] = 'Suriname';
$v51['STD']['country'] = 'São Tome and Principe';
$v51['SVC']['country'] = 'El Salvador';
$v51['SYP']['country'] = 'Syria';
$v51['SZL']['country'] = 'Swaziland';
$v51['THB']['country'] = 'Thailand';
$v51['TJR']['country'] = 'Tajikistan';
$v51['TMM']['country'] = 'Turkmenistan';
$v51['TND']['country'] = 'Tunisia';
$v51['TOP']['country'] = 'Tonga';
$v51['TRL']['country'] = 'Turkey';
$v51['TTD']['country'] = 'Trinidad and Tobago';
$v51['TVD']['country'] = 'Tuvalu';
$v51['TWD']['country'] = 'Taiwan';
$v51['TZS']['country'] = 'Tanzania';
$v51['UAH']['country'] = 'Ukraine';
$v51['UGX']['country'] = 'Uganda';
$v51['USD']['country'] = 'United States of America';
$v51['UYU']['country'] = 'Uruguay';
$v51['UZS']['country'] = 'Uzbekistan';
$v51['VAL']['country'] = 'Vatican City';
$v51['VEB']['country'] = 'Venezuela';
$v51['VND']['country'] = 'Viet Nam';
$v51['VUV']['country'] = 'Vanuatu';
$v51['WST']['country'] = 'Samoa';
$v51['XAF']['country'] = 'Communauté Financière Africaine';
$v51['XAG']['country'] = 'Silver';
$v51['XAU']['country'] = 'Gold';
$v51['XCD']['country'] = 'East Caribbean';
$v51['XDR']['country'] = 'International Monetary Fund';
$v51['XPD']['country'] = 'Palladium';
$v51['XPF']['country'] = 'Comptoirs Français du Pacifique';
$v51['XPT']['country'] = 'Platinum';
$v51['YER']['country'] = 'Yemen';
$v51['YUM']['country'] = 'Yugoslavia';
$v51['ZAR']['country'] = 'South Africa';
$v51['ZMK']['country'] = 'Zambia';
$v51['ZWD']['country'] = 'Zimbabwe';
$v51['AED']['units']   = 'Dirhams';
$v51['AFA']['units']   = 'Afghanis';
$v51['ALL']['units']   = 'Leke';
$v51['AMD']['units']   = 'Drams';
$v51['ANG']['units']   = 'Guilders';
$v51['AOA']['units']   = 'Kwanza';
$v51['ARS']['units']   = 'Pesos';
$v51['ATS']['units']   = 'Schillings';
$v51['AUD']['units']   = 'Dollars';
$v51['AWG']['units']   = 'Guilders';
$v51['AZM']['units']   = 'Manats';
$v51['BAM']['units']   = 'Convertible Marka';
$v51['BBD']['units']   = 'Dollars';
$v51['BDT']['units']   = 'Taka';
$v51['BEF']['units']   = 'Francs';
$v51['BGL']['units']   = 'Leva';
$v51['BHD']['units']   = 'Dinars';
$v51['BIF']['units']   = 'Francs';
$v51['BMD']['units']   = 'Dollars';
$v51['BND']['units']   = 'Dollars';
$v51['BOB']['units']   = 'Bolivianos';
$v51['BRL']['units']   = 'Brazil Real';
$v51['BSD']['units']   = 'Dollars';
$v51['BTN']['units']   = 'Ngultrum';
$v51['BWP']['units']   = 'Pulas';
$v51['BYR']['units']   = 'Rubles';
$v51['BZD']['units']   = 'Dollars';
$v51['CAD']['units']   = 'Dollars';
$v51['CDF']['units']   = 'Congolese Francs';
$v51['CHF']['units']   = 'Francs';
$v51['CLP']['units']   = 'Pesos';
$v51['CNY']['units']   = 'Yuan Renminbi';
$v51['COP']['units']   = 'Pesos';
$v51['CRC']['units']   = 'Colones';
$v51['CUP']['units']   = 'Pesos';
$v51['CVE']['units']   = 'Escudos';
$v51['CYP']['units']   = 'Pounds';
$v51['CZK']['units']   = 'Koruny';
$v51['DEM']['units']   = 'Deutsche Marks';
$v51['DJF']['units']   = 'Francs';
$v51['DKK']['units']   = 'Kroner';
$v51['DOP']['units']   = 'Pesos';
$v51['DZD']['units']   = 'Algeria Dinars';
$v51['EEK']['units']   = 'Krooni';
$v51['EGP']['units']   = 'Pounds';
$v51['ERN']['units']   = 'Nakfa';
$v51['ESP']['units']   = 'Pesetas';
$v51['ETB']['units']   = 'Birr';
$v51['EUR']['units']   = 'Euro';
$v51['FIM']['units']   = 'Markkaa';
$v51['FJD']['units']   = 'Dollars';
$v51['FKP']['units']   = 'Pounds';
$v51['FRF']['units']   = 'Francs';
$v51['GBP']['units']   = 'Pounds';
$v51['GEL']['units']   = 'Lari';
$v51['GGP']['units']   = 'Pounds';
$v51['GHC']['units']   = 'Cedis';
$v51['GIP']['units']   = 'Pounds';
$v51['GMD']['units']   = 'Dalasi';
$v51['GNF']['units']   = 'Francs';
$v51['GRD']['units']   = 'Drachmae';
$v51['GTQ']['units']   = 'Quetzales';
$v51['GYD']['units']   = 'Dollars';
$v51['HKD']['units']   = 'Dollars';
$v51['HNL']['units']   = 'Lempiras';
$v51['HRK']['units']   = 'Kuna';
$v51['HTG']['units']   = 'Gourdes';
$v51['HUF']['units']   = 'Forints';
$v51['IDR']['units']   = 'Rupiahs';
$v51['IEP']['units']   = 'Pounds';
$v51['ILS']['units']   = 'New Shekels';
$v51['IMP']['units']   = 'Pounds';
$v51['INR']['units']   = 'Rupees';
$v51['IQD']['units']   = 'Dinars';
$v51['IRR']['units']   = 'Rials';
$v51['ISK']['units']   = 'Kronur';
$v51['ITL']['units']   = 'Lire';
$v51['JEP']['units']   = 'Pounds';
$v51['JMD']['units']   = 'Dollars';
$v51['JOD']['units']   = 'Dinars';
$v51['JPY']['units']   = 'Yen';
$v51['KES']['units']   = 'Shillings';
$v51['KGS']['units']   = 'Soms';
$v51['KHR']['units']   = 'Riels';
$v51['KMF']['units']   = 'Francs';
$v51['KPW']['units']   = 'Won';
$v51['KWD']['units']   = 'Dinars';
$v51['KYD']['units']   = 'Dollars';
$v51['KZT']['units']   = 'Tenge';
$v51['LAK']['units']   = 'Kips';
$v51['LBP']['units']   = 'Pounds';
$v51['LKR']['units']   = 'Rupees';
$v51['LRD']['units']   = 'Dollars';
$v51['LSL']['units']   = 'Maloti';
$v51['LTL']['units']   = 'Litai';
$v51['LUF']['units']   = 'Francs';
$v51['LVL']['units']   = 'Lati';
$v51['LYD']['units']   = 'Dinars';
$v51['MAD']['units']   = 'Dirhams';
$v51['MDL']['units']   = 'Lei';
$v51['MGF']['units']   = 'Malagasy Francs';
$v51['MKD']['units']   = 'Denars';
$v51['MMK']['units']   = 'Kyats';
$v51['MNT']['units']   = 'Tugriks';
$v51['MOP']['units']   = 'Patacas';
$v51['MRO']['units']   = 'Ouguiyas';
$v51['MTL']['units']   = 'Liri';
$v51['MUR']['units']   = 'Rupees';
$v51['MVR']['units']   = 'Rufiyaa';
$v51['MWK']['units']   = 'Kwachas';
$v51['MXN']['units']   = 'Pesos';
$v51['MYR']['units']   = 'Ringgits';
$v51['MZM']['units']   = 'Meticais';
$v51['NAD']['units']   = 'Dollars';
$v51['NGN']['units']   = 'Nairas';
$v51['NIO']['units']   = 'Gold Cordobas';
$v51['NLG']['units']   = 'Guilders';
$v51['NOK']['units']   = 'Krone';
$v51['NPR']['units']   = 'Nepal Rupees';
$v51['NZD']['units']   = 'Dollars';
$v51['OMR']['units']   = 'Rials';
$v51['PAB']['units']   = 'Balboa';
$v51['PEN']['units']   = 'Nuevos Soles';
$v51['PGK']['units']   = 'Kina';
$v51['PHP']['units']   = 'Pesos';
$v51['PKR']['units']   = 'Rupees';
$v51['PLN']['units']   = 'Zlotych';
$v51['PTE']['units']   = 'Escudos';
$v51['PYG']['units']   = 'Guarani';
$v51['QAR']['units']   = 'Rials';
$v51['ROL']['units']   = 'Lei';
$v51['RUR']['units']   = 'Rubles';
$v51['RWF']['units']   = 'Rwanda Francs';
$v51['SAR']['units']   = 'Riyals';
$v51['SBD']['units']   = 'Dollars';
$v51['SCR']['units']   = 'Rupees';
$v51['SDD']['units']   = 'Dinars';
$v51['SEK']['units']   = 'Kronor';
$v51['SGD']['units']   = 'Dollars';
$v51['SHP']['units']   = 'Pounds';
$v51['SIT']['units']   = 'Tolars';
$v51['SKK']['units']   = 'Koruny';
$v51['SLL']['units']   = 'Leones';
$v51['SOS']['units']   = 'Shillings';
$v51['SPL']['units']   = 'Luigini';
$v51['SRG']['units']   = 'Guilders';
$v51['STD']['units']   = 'Dobras';
$v51['SVC']['units']   = 'Colones';
$v51['SYP']['units']   = 'Pounds';
$v51['SZL']['units']   = 'Emalangeni';
$v51['THB']['units']   = 'Baht';
$v51['TJR']['units']   = 'Rubles';
$v51['TMM']['units']   = 'Manats';
$v51['TND']['units']   = 'Dinars';
$v51['TOP']['units']   = 'Pa\'anga';
$v51['TRL']['units']   = 'Liras';
$v51['TTD']['units']   = 'Dollars';
$v51['TVD']['units']   = 'Tuvalu Dollars';
$v51['TWD']['units']   = 'New Dollars';
$v51['TZS']['units']   = 'Shillings';
$v51['UAH']['units']   = 'Hryvnia';
$v51['UGX']['units']   = 'Shillings';
$v51['USD']['units']   = 'Dollars';
$v51['UYU']['units']   = 'Pesos';
$v51['UZS']['units']   = 'Sums';
$v51['VAL']['units']   = 'Lire';
$v51['VEB']['units']   = 'Bolivares';
$v51['VND']['units']   = 'Dong';
$v51['VUV']['units']   = 'Vatu';
$v51['WST']['units']   = 'Tala';
$v51['XAF']['units']   = 'Francs';
$v51['XAG']['units']   = 'Ounces';
$v51['XAU']['units']   = 'Ounces';
$v51['XCD']['units']   = 'Dollars';
$v51['XDR']['units']   = 'Special Drawing Rights';
$v51['XPD']['units']   = 'Ounces';
$v51['XPF']['units']   = 'Francs';
$v51['XPT']['units']   = 'Ounces';
$v51['YER']['units']   = 'Rials';
$v51['YUM']['units']   = 'New Dinars';
$v51['ZAR']['units']   = 'Rand';
$v51['ZMK']['units']   = 'Kwacha';
$v51['ZWD']['units']   = 'Zimbabwe Dollars';
}
return (isset($v51["$v223"]["$v368"]) ? $v51["$v223"]["$v368"] : '');
}
function f70($v376, $v210=false) {
if ($v376 == 'XXX') {
return 'unknown';
}
if (!$v210) {
$v376 = strtolower($v376);
}
static $v99 = array();
if (empty($v99)) {
$v99['aar'] = 'Afar';
$v99['abk'] = 'Abkhazian';
$v99['ace'] = 'Achinese';
$v99['ach'] = 'Acoli';
$v99['ada'] = 'Adangme';
$v99['afa'] = 'Afro-Asiatic (Other)';
$v99['afh'] = 'Afrihili';
$v99['afr'] = 'Afrikaans';
$v99['aka'] = 'Akan';
$v99['akk'] = 'Akkadian';
$v99['alb'] = 'Albanian';
$v99['ale'] = 'Aleut';
$v99['alg'] = 'Algonquian Languages';
$v99['amh'] = 'Amharic';
$v99['ang'] = 'English, Old (ca. 450-1100)';
$v99['apa'] = 'Apache Languages';
$v99['ara'] = 'Arabic';
$v99['arc'] = 'Aramaic';
$v99['arm'] = 'Armenian';
$v99['arn'] = 'Araucanian';
$v99['arp'] = 'Arapaho';
$v99['art'] = 'Artificial (Other)';
$v99['arw'] = 'Arawak';
$v99['asm'] = 'Assamese';
$v99['ath'] = 'Athapascan Languages';
$v99['ava'] = 'Avaric';
$v99['ave'] = 'Avestan';
$v99['awa'] = 'Awadhi';
$v99['aym'] = 'Aymara';
$v99['aze'] = 'Azerbaijani';
$v99['bad'] = 'Banda';
$v99['bai'] = 'Bamileke Languages';
$v99['bak'] = 'Bashkir';
$v99['bal'] = 'Baluchi';
$v99['bam'] = 'Bambara';
$v99['ban'] = 'Balinese';
$v99['baq'] = 'Basque';
$v99['bas'] = 'Basa';
$v99['bat'] = 'Baltic (Other)';
$v99['bej'] = 'Beja';
$v99['bel'] = 'Byelorussian';
$v99['bem'] = 'Bemba';
$v99['ben'] = 'Bengali';
$v99['ber'] = 'Berber (Other)';
$v99['bho'] = 'Bhojpuri';
$v99['bih'] = 'Bihari';
$v99['bik'] = 'Bikol';
$v99['bin'] = 'Bini';
$v99['bis'] = 'Bislama';
$v99['bla'] = 'Siksika';
$v99['bnt'] = 'Bantu (Other)';
$v99['bod'] = 'Tibetan';
$v99['bra'] = 'Braj';
$v99['bre'] = 'Breton';
$v99['bua'] = 'Buriat';
$v99['bug'] = 'Buginese';
$v99['bul'] = 'Bulgarian';
$v99['bur'] = 'Burmese';
$v99['cad'] = 'Caddo';
$v99['cai'] = 'Central American Indian (Other)';
$v99['car'] = 'Carib';
$v99['cat'] = 'Catalan';
$v99['cau'] = 'Caucasian (Other)';
$v99['ceb'] = 'Cebuano';
$v99['cel'] = 'Celtic (Other)';
$v99['ces'] = 'Czech';
$v99['cha'] = 'Chamorro';
$v99['chb'] = 'Chibcha';
$v99['che'] = 'Chechen';
$v99['chg'] = 'Chagatai';
$v99['chi'] = 'Chinese';
$v99['chm'] = 'Mari';
$v99['chn'] = 'Chinook jargon';
$v99['cho'] = 'Choctaw';
$v99['chr'] = 'Cherokee';
$v99['chu'] = 'Church Slavic';
$v99['chv'] = 'Chuvash';
$v99['chy'] = 'Cheyenne';
$v99['cop'] = 'Coptic';
$v99['cor'] = 'Cornish';
$v99['cos'] = 'Corsican';
$v99['cpe'] = 'Creoles and Pidgins, English-based (Other)';
$v99['cpf'] = 'Creoles and Pidgins, French-based (Other)';
$v99['cpp'] = 'Creoles and Pidgins, Portuguese-based (Other)';
$v99['cre'] = 'Cree';
$v99['crp'] = 'Creoles and Pidgins (Other)';
$v99['cus'] = 'Cushitic (Other)';
$v99['cym'] = 'Welsh';
$v99['cze'] = 'Czech';
$v99['dak'] = 'Dakota';
$v99['dan'] = 'Danish';
$v99['del'] = 'Delaware';
$v99['deu'] = 'German';
$v99['din'] = 'Dinka';
$v99['div'] = 'Divehi';
$v99['doi'] = 'Dogri';
$v99['dra'] = 'Dravidian (Other)';
$v99['dua'] = 'Duala';
$v99['dum'] = 'Dutch, Middle (ca. 1050-1350)';
$v99['dut'] = 'Dutch';
$v99['dyu'] = 'Dyula';
$v99['dzo'] = 'Dzongkha';
$v99['efi'] = 'Efik';
$v99['egy'] = 'Egyptian (Ancient)';
$v99['eka'] = 'Ekajuk';
$v99['ell'] = 'Greek, Modern (1453-)';
$v99['elx'] = 'Elamite';
$v99['eng'] = 'English';
$v99['enm'] = 'English, Middle (ca. 1100-1500)';
$v99['epo'] = 'Esperanto';
$v99['esk'] = 'Eskimo (Other)';
$v99['esl'] = 'Spanish';
$v99['est'] = 'Estonian';
$v99['eus'] = 'Basque';
$v99['ewe'] = 'Ewe';
$v99['ewo'] = 'Ewondo';
$v99['fan'] = 'Fang';
$v99['fao'] = 'Faroese';
$v99['fas'] = 'Persian';
$v99['fat'] = 'Fanti';
$v99['fij'] = 'Fijian';
$v99['fin'] = 'Finnish';
$v99['fiu'] = 'Finno-Ugrian (Other)';
$v99['fon'] = 'Fon';
$v99['fra'] = 'French';
$v99['fre'] = 'French';
$v99['frm'] = 'French, Middle (ca. 1400-1600)';
$v99['fro'] = 'French, Old (842- ca. 1400)';
$v99['fry'] = 'Frisian';
$v99['ful'] = 'Fulah';
$v99['gaa'] = 'Ga';
$v99['gae'] = 'Gaelic (Scots)';
$v99['gai'] = 'Irish';
$v99['gay'] = 'Gayo';
$v99['gdh'] = 'Gaelic (Scots)';
$v99['gem'] = 'Germanic (Other)';
$v99['geo'] = 'Georgian';
$v99['ger'] = 'German';
$v99['gez'] = 'Geez';
$v99['gil'] = 'Gilbertese';
$v99['glg'] = 'Gallegan';
$v99['gmh'] = 'German, Middle High (ca. 1050-1500)';
$v99['goh'] = 'German, Old High (ca. 750-1050)';
$v99['gon'] = 'Gondi';
$v99['got'] = 'Gothic';
$v99['grb'] = 'Grebo';
$v99['grc'] = 'Greek, Ancient (to 1453)';
$v99['gre'] = 'Greek, Modern (1453-)';
$v99['grn'] = 'Guarani';
$v99['guj'] = 'Gujarati';
$v99['hai'] = 'Haida';
$v99['hau'] = 'Hausa';
$v99['haw'] = 'Hawaiian';
$v99['heb'] = 'Hebrew';
$v99['her'] = 'Herero';
$v99['hil'] = 'Hiligaynon';
$v99['him'] = 'Himachali';
$v99['hin'] = 'Hindi';
$v99['hmo'] = 'Hiri Motu';
$v99['hun'] = 'Hungarian';
$v99['hup'] = 'Hupa';
$v99['hye'] = 'Armenian';
$v99['iba'] = 'Iban';
$v99['ibo'] = 'Igbo';
$v99['ice'] = 'Icelandic';
$v99['ijo'] = 'Ijo';
$v99['iku'] = 'Inuktitut';
$v99['ilo'] = 'Iloko';
$v99['ina'] = 'Interlingua (International Auxiliary language Association)';
$v99['inc'] = 'Indic (Other)';
$v99['ind'] = 'Indonesian';
$v99['ine'] = 'Indo-European (Other)';
$v99['ine'] = 'Interlingue';
$v99['ipk'] = 'Inupiak';
$v99['ira'] = 'Iranian (Other)';
$v99['iri'] = 'Irish';
$v99['iro'] = 'Iroquoian uages';
$v99['isl'] = 'Icelandic';
$v99['ita'] = 'Italian';
$v99['jav'] = 'Javanese';
$v99['jaw'] = 'Javanese';
$v99['jpn'] = 'Japanese';
$v99['jpr'] = 'Judeo-Persian';
$v99['jrb'] = 'Judeo-Arabic';
$v99['kaa'] = 'Kara-Kalpak';
$v99['kab'] = 'Kabyle';
$v99['kac'] = 'Kachin';
$v99['kal'] = 'Greenlandic';
$v99['kam'] = 'Kamba';
$v99['kan'] = 'Kannada';
$v99['kar'] = 'Karen';
$v99['kas'] = 'Kashmiri';
$v99['kat'] = 'Georgian';
$v99['kau'] = 'Kanuri';
$v99['kaw'] = 'Kawi';
$v99['kaz'] = 'Kazakh';
$v99['kha'] = 'Khasi';
$v99['khi'] = 'Khoisan (Other)';
$v99['khm'] = 'Khmer';
$v99['kho'] = 'Khotanese';
$v99['kik'] = 'Kikuyu';
$v99['kin'] = 'Kinyarwanda';
$v99['kir'] = 'Kirghiz';
$v99['kok'] = 'Konkani';
$v99['kom'] = 'Komi';
$v99['kon'] = 'Kongo';
$v99['kor'] = 'Korean';
$v99['kpe'] = 'Kpelle';
$v99['kro'] = 'Kru';
$v99['kru'] = 'Kurukh';
$v99['kua'] = 'Kuanyama';
$v99['kum'] = 'Kumyk';
$v99['kur'] = 'Kurdish';
$v99['kus'] = 'Kusaie';
$v99['kut'] = 'Kutenai';
$v99['lad'] = 'Ladino';
$v99['lah'] = 'Lahnda';
$v99['lam'] = 'Lamba';
$v99['lao'] = 'Lao';
$v99['lat'] = 'Latin';
$v99['lav'] = 'Latvian';
$v99['lez'] = 'Lezghian';
$v99['lin'] = 'Lingala';
$v99['lit'] = 'Lithuanian';
$v99['lol'] = 'Mongo';
$v99['loz'] = 'Lozi';
$v99['ltz'] = 'Letzeburgesch';
$v99['lub'] = 'Luba-Katanga';
$v99['lug'] = 'Ganda';
$v99['lui'] = 'Luiseno';
$v99['lun'] = 'Lunda';
$v99['luo'] = 'Luo (Kenya and Tanzania)';
$v99['mac'] = 'Macedonian';
$v99['mad'] = 'Madurese';
$v99['mag'] = 'Magahi';
$v99['mah'] = 'Marshall';
$v99['mai'] = 'Maithili';
$v99['mak'] = 'Macedonian';
$v99['mak'] = 'Makasar';
$v99['mal'] = 'Malayalam';
$v99['man'] = 'Mandingo';
$v99['mao'] = 'Maori';
$v99['map'] = 'Austronesian (Other)';
$v99['mar'] = 'Marathi';
$v99['mas'] = 'Masai';
$v99['max'] = 'Manx';
$v99['may'] = 'Malay';
$v99['men'] = 'Mende';
$v99['mga'] = 'Irish, Middle (900 - 1200)';
$v99['mic'] = 'Micmac';
$v99['min'] = 'Minangkabau';
$v99['mis'] = 'Miscellaneous (Other)';
$v99['mkh'] = 'Mon-Kmer (Other)';
$v99['mlg'] = 'Malagasy';
$v99['mlt'] = 'Maltese';
$v99['mni'] = 'Manipuri';
$v99['mno'] = 'Manobo Languages';
$v99['moh'] = 'Mohawk';
$v99['mol'] = 'Moldavian';
$v99['mon'] = 'Mongolian';
$v99['mos'] = 'Mossi';
$v99['mri'] = 'Maori';
$v99['msa'] = 'Malay';
$v99['mul'] = 'Multiple Languages';
$v99['mun'] = 'Munda Languages';
$v99['mus'] = 'Creek';
$v99['mwr'] = 'Marwari';
$v99['mya'] = 'Burmese';
$v99['myn'] = 'Mayan Languages';
$v99['nah'] = 'Aztec';
$v99['nai'] = 'North American Indian (Other)';
$v99['nau'] = 'Nauru';
$v99['nav'] = 'Navajo';
$v99['nbl'] = 'Ndebele, South';
$v99['nde'] = 'Ndebele, North';
$v99['ndo'] = 'Ndongo';
$v99['nep'] = 'Nepali';
$v99['new'] = 'Newari';
$v99['nic'] = 'Niger-Kordofanian (Other)';
$v99['niu'] = 'Niuean';
$v99['nla'] = 'Dutch';
$v99['nno'] = 'Norwegian (Nynorsk)';
$v99['non'] = 'Norse, Old';
$v99['nor'] = 'Norwegian';
$v99['nso'] = 'Sotho, Northern';
$v99['nub'] = 'Nubian Languages';
$v99['nya'] = 'Nyanja';
$v99['nym'] = 'Nyamwezi';
$v99['nyn'] = 'Nyankole';
$v99['nyo'] = 'Nyoro';
$v99['nzi'] = 'Nzima';
$v99['oci'] = 'Langue d\'Oc (post 1500)';
$v99['oji'] = 'Ojibwa';
$v99['ori'] = 'Oriya';
$v99['orm'] = 'Oromo';
$v99['osa'] = 'Osage';
$v99['oss'] = 'Ossetic';
$v99['ota'] = 'Turkish, Ottoman (1500 - 1928)';
$v99['oto'] = 'Otomian Languages';
$v99['paa'] = 'Papuan-Australian (Other)';
$v99['pag'] = 'Pangasinan';
$v99['pal'] = 'Pahlavi';
$v99['pam'] = 'Pampanga';
$v99['pan'] = 'Panjabi';
$v99['pap'] = 'Papiamento';
$v99['pau'] = 'Palauan';
$v99['peo'] = 'Persian, Old (ca 600 - 400 B.C.)';
$v99['per'] = 'Persian';
$v99['phn'] = 'Phoenician';
$v99['pli'] = 'Pali';
$v99['pol'] = 'Polish';
$v99['pon'] = 'Ponape';
$v99['por'] = 'Portuguese';
$v99['pra'] = 'Prakrit uages';
$v99['pro'] = 'Provencal, Old (to 1500)';
$v99['pus'] = 'Pushto';
$v99['que'] = 'Quechua';
$v99['raj'] = 'Rajasthani';
$v99['rar'] = 'Rarotongan';
$v99['roa'] = 'Romance (Other)';
$v99['roh'] = 'Rhaeto-Romance';
$v99['rom'] = 'Romany';
$v99['ron'] = 'Romanian';
$v99['rum'] = 'Romanian';
$v99['run'] = 'Rundi';
$v99['rus'] = 'Russian';
$v99['sad'] = 'Sandawe';
$v99['sag'] = 'Sango';
$v99['sah'] = 'Yakut';
$v99['sai'] = 'South American Indian (Other)';
$v99['sal'] = 'Salishan Languages';
$v99['sam'] = 'Samaritan Aramaic';
$v99['san'] = 'Sanskrit';
$v99['sco'] = 'Scots';
$v99['scr'] = 'Serbo-Croatian';
$v99['sel'] = 'Selkup';
$v99['sem'] = 'Semitic (Other)';
$v99['sga'] = 'Irish, Old (to 900)';
$v99['shn'] = 'Shan';
$v99['sid'] = 'Sidamo';
$v99['sin'] = 'Singhalese';
$v99['sio'] = 'Siouan Languages';
$v99['sit'] = 'Sino-Tibetan (Other)';
$v99['sla'] = 'Slavic (Other)';
$v99['slk'] = 'Slovak';
$v99['slo'] = 'Slovak';
$v99['slv'] = 'Slovenian';
$v99['smi'] = 'Sami Languages';
$v99['smo'] = 'Samoan';
$v99['sna'] = 'Shona';
$v99['snd'] = 'Sindhi';
$v99['sog'] = 'Sogdian';
$v99['som'] = 'Somali';
$v99['son'] = 'Songhai';
$v99['sot'] = 'Sotho, Southern';
$v99['spa'] = 'Spanish';
$v99['sqi'] = 'Albanian';
$v99['srd'] = 'Sardinian';
$v99['srr'] = 'Serer';
$v99['ssa'] = 'Nilo-Saharan (Other)';
$v99['ssw'] = 'Siswant';
$v99['ssw'] = 'Swazi';
$v99['suk'] = 'Sukuma';
$v99['sun'] = 'Sudanese';
$v99['sus'] = 'Susu';
$v99['sux'] = 'Sumerian';
$v99['sve'] = 'Swedish';
$v99['swa'] = 'Swahili';
$v99['swe'] = 'Swedish';
$v99['syr'] = 'Syriac';
$v99['tah'] = 'Tahitian';
$v99['tam'] = 'Tamil';
$v99['tat'] = 'Tatar';
$v99['tel'] = 'Telugu';
$v99['tem'] = 'Timne';
$v99['ter'] = 'Tereno';
$v99['tgk'] = 'Tajik';
$v99['tgl'] = 'Tagalog';
$v99['tha'] = 'Thai';
$v99['tib'] = 'Tibetan';
$v99['tig'] = 'Tigre';
$v99['tir'] = 'Tigrinya';
$v99['tiv'] = 'Tivi';
$v99['tli'] = 'Tlingit';
$v99['tmh'] = 'Tamashek';
$v99['tog'] = 'Tonga (Nyasa)';
$v99['ton'] = 'Tonga (Tonga Islands)';
$v99['tru'] = 'Truk';
$v99['tsi'] = 'Tsimshian';
$v99['tsn'] = 'Tswana';
$v99['tso'] = 'Tsonga';
$v99['tuk'] = 'Turkmen';
$v99['tum'] = 'Tumbuka';
$v99['tur'] = 'Turkish';
$v99['tut'] = 'Altaic (Other)';
$v99['twi'] = 'Twi';
$v99['tyv'] = 'Tuvinian';
$v99['uga'] = 'Ugaritic';
$v99['uig'] = 'Uighur';
$v99['ukr'] = 'Ukrainian';
$v99['umb'] = 'Umbundu';
$v99['und'] = 'Undetermined';
$v99['urd'] = 'Urdu';
$v99['uzb'] = 'Uzbek';
$v99['vai'] = 'Vai';
$v99['ven'] = 'Venda';
$v99['vie'] = 'Vietnamese';
$v99['vol'] = 'Volapük';
$v99['vot'] = 'Votic';
$v99['wak'] = 'Wakashan Languages';
$v99['wal'] = 'Walamo';
$v99['war'] = 'Waray';
$v99['was'] = 'Washo';
$v99['wel'] = 'Welsh';
$v99['wen'] = 'Sorbian Languages';
$v99['wol'] = 'Wolof';
$v99['xho'] = 'Xhosa';
$v99['yao'] = 'Yao';
$v99['yap'] = 'Yap';
$v99['yid'] = 'Yiddish';
$v99['yor'] = 'Yoruba';
$v99['zap'] = 'Zapotec';
$v99['zen'] = 'Zenaga';
$v99['zha'] = 'Zhuang';
$v99['zho'] = 'Chinese';
$v99['zul'] = 'Zulu';
$v99['zun'] = 'Zuni';
}
return (isset($v99["$v376"]) ? $v99["$v376"] : '');
}
function f33($v361) {
static $v59 = array();
if (empty($v59)) {
$v59[0x00] = 'padding (has no meaning)';
$v59[0x01] = 'end of initial silence';
$v59[0x02] = 'intro start';
$v59[0x03] = 'main part start';
$v59[0x04] = 'outro start';
$v59[0x05] = 'outro end';
$v59[0x06] = 'verse start';
$v59[0x07] = 'refrain start';
$v59[0x08] = 'interlude start';
$v59[0x09] = 'theme start';
$v59[0x0A] = 'variation start';
$v59[0x0B] = 'key change';
$v59[0x0C] = 'time change';
$v59[0x0D] = 'momentary unwanted noise (Snap, Crackle & Pop)';
$v59[0x0E] = 'sustained noise';
$v59[0x0F] = 'sustained noise end';
$v59[0x10] = 'intro end';
$v59[0x11] = 'main part end';
$v59[0x12] = 'verse end';
$v59[0x13] = 'refrain end';
$v59[0x14] = 'theme end';
$v59[0x15] = 'profanity';
$v59[0x16] = 'profanity end';
for ($i = 0x17; $i <= 0xDF; $i++) {
$v59[$i] = 'reserved for future use';
}
for ($i = 0xE0; $i <= 0xEF; $i++) {
$v59[$i] = 'not predefined synch 0-F';
}
for ($i = 0xF0; $i <= 0xFC; $i++) {
$v59[$i] = 'reserved for future use';
}
$v59[0xFD] = 'audio end (start of silence)';
$v59[0xFE] = 'audio file ends';
$v59[0xFF] = 'one more byte of events follows';
}
return (isset($v59[$v361]) ? $v59[$v361] : '');
}
function f103($v361) {
static $v142 = array();
if (empty($v142)) {
$v142[0x00] = 'other';
$v142[0x01] = 'lyrics';
$v142[0x02] = 'text transcription';
$v142[0x03] = 'movement/part name'; 
$v142[0x04] = 'events';             
$v142[0x05] = 'chord';              
$v142[0x06] = 'trivia/\'pop up\' information';
$v142[0x07] = 'URLs to webpages';
$v142[0x08] = 'URLs to images';
}
return (isset($v142[$v361]) ? $v142[$v361] : '');
}
function f5($v361) {
static $v18 = array();
if (empty($v18)) {
$v18[0x00] = 'Other';
$v18[0x01] = '32x32 pixels \'file icon\' (PNG only)';
$v18[0x02] = 'Other file icon';
$v18[0x03] = 'Cover (front)';
$v18[0x04] = 'Cover (back)';
$v18[0x05] = 'Leaflet page';
$v18[0x06] = 'Media (e.g. label side of CD)';
$v18[0x07] = 'Lead artist/lead performer/soloist';
$v18[0x08] = 'Artist/performer';
$v18[0x09] = 'Conductor';
$v18[0x0A] = 'Band/Orchestra';
$v18[0x0B] = 'Composer';
$v18[0x0C] = 'Lyricist/text writer';
$v18[0x0D] = 'Recording Location';
$v18[0x0E] = 'During recording';
$v18[0x0F] = 'During performance';
$v18[0x10] = 'Movie/video screen capture';
$v18[0x11] = 'A bright coloured fish';
$v18[0x12] = 'Illustration';
$v18[0x13] = 'Band/artist logotype';
$v18[0x14] = 'Publisher/Studio logotype';
}
return (isset($v18[$v361]) ? $v18[$v361] : '');
}
function f17($v361) {
static $v48 = array();
if (empty($v48)) {
$v48[0x00] = 'Other';
$v48[0x01] = 'Standard CD album with other songs';
$v48[0x02] = 'Compressed audio on CD';
$v48[0x03] = 'File over the Internet';
$v48[0x04] = 'Stream over the Internet';
$v48[0x05] = 'As note sheets';
$v48[0x06] = 'As note sheets in a book with other sheets';
$v48[0x07] = 'Music on other media';
$v48[0x08] = 'Non-musical merchandise';
}
return (isset($v48[$v361]) ? $v48[$v361] : '');
}
function f99($v361) {
static $v133 = array();
if (empty($v133)) {
$v133[0x00] = 'Other';
$v133[0x01] = 'Master volume';
$v133[0x02] = 'Front right';
$v133[0x03] = 'Front left';
$v133[0x04] = 'Back right';
$v133[0x05] = 'Back left';
$v133[0x06] = 'Front centre';
$v133[0x07] = 'Back centre';
$v133[0x08] = 'Subwoofer';
}
return (isset($v133[$v361]) ? $v133[$v361] : '');
}
function f43($v329) {
static $v69 = array();
if (empty($v69)) {
$v69['AENC'] = 'Audio encryption';
$v69['APIC'] = 'Attached picture';
$v69['ASPI'] = 'Audio seek point index';
$v69['BUF']  = 'Recommended buffer size';
$v69['CNT']  = 'Play counter';
$v69['COM']  = 'Comments';
$v69['COMM'] = 'Comments';
$v69['COMR'] = 'Commercial frame';
$v69['CRA']  = 'Audio encryption';
$v69['CRM']  = 'Encrypted meta frame';
$v69['ENCR'] = 'Encryption method registration';
$v69['EQU']  = 'Equalization';
$v69['EQU2'] = 'Equalisation (2)';
$v69['EQUA'] = 'Equalization';
$v69['ETC']  = 'Event timing codes';
$v69['ETCO'] = 'Event timing codes';
$v69['GEO']  = 'General encapsulated object';
$v69['GEOB'] = 'General encapsulated object';
$v69['GRID'] = 'Group identification registration';
$v69['IPL']  = 'Involved people list';
$v69['IPLS'] = 'Involved people list';
$v69['LINK'] = 'Linked information';
$v69['LNK']  = 'Linked information';
$v69['MCDI'] = 'Music CD identifier';
$v69['MCI']  = 'Music CD Identifier';
$v69['MLL']  = 'MPEG location lookup table';
$v69['MLLT'] = 'MPEG location lookup table';
$v69['OWNE'] = 'Ownership frame';
$v69['PCNT'] = 'Play counter';
$v69['PIC']  = 'Attached picture';
$v69['POP']  = 'Popularimeter';
$v69['POPM'] = 'Popularimeter';
$v69['POSS'] = 'Position synchronisation frame';
$v69['PRIV'] = 'Private frame';
$v69['RBUF'] = 'Recommended buffer size';
$v69['REV']  = 'Reverb';
$v69['RVA']  = 'Relative volume adjustment';
$v69['RVA2'] = 'Relative volume adjustment (2)';
$v69['RVAD'] = 'Relative volume adjustment';
$v69['RVRB'] = 'Reverb';
$v69['SEEK'] = 'Seek frame';
$v69['SIGN'] = 'Signature frame';
$v69['SLT']  = 'Synchronized lyric/text';
$v69['STC']  = 'Synced tempo codes';
$v69['SYLT'] = 'Synchronised lyric/text';
$v69['SYTC'] = 'Synchronised tempo codes';
$v69['TAL']  = 'Album/Movie/Show title';
$v69['TALB'] = 'Album/Movie/Show title';
$v69['TBP']  = 'BPM (Beats Per Minute)';
$v69['TBPM'] = 'BPM (beats per minute)';
$v69['TCM']  = 'Composer';
$v69['TCO']  = 'Content type';
$v69['TCOM'] = 'Composer';
$v69['TCON'] = 'Content type';
$v69['TCOP'] = 'Copyright message';
$v69['TCR']  = 'Copyright message';
$v69['TDA']  = 'Date';
$v69['TDAT'] = 'Date';
$v69['TDEN'] = 'Encoding time';
$v69['TDLY'] = 'Playlist delay';
$v69['TDOR'] = 'Original release time';
$v69['TDRC'] = 'Recording time';
$v69['TDRL'] = 'Release time';
$v69['TDTG'] = 'Tagging time';
$v69['TDY']  = 'Playlist delay';
$v69['TEN']  = 'Encoded by';
$v69['TENC'] = 'Encoded by';
$v69['TEXT'] = 'Lyricist/Text writer';
$v69['TFLT'] = 'File type';
$v69['TFT']  = 'File type';
$v69['TIM']  = 'Time';
$v69['TIME'] = 'Time';
$v69['TIPL'] = 'Involved people list';
$v69['TIT1'] = 'Content group description';
$v69['TIT2'] = 'Title/songname/content description';
$v69['TIT3'] = 'Subtitle/Description refinement';
$v69['TKE']  = 'Initial key';
$v69['TKEY'] = 'Initial key';
$v69['TLA']  = 'Language(s)';
$v69['TLAN'] = 'Language(s)';
$v69['TLE']  = 'Length';
$v69['TLEN'] = 'Length';
$v69['TMCL'] = 'Musician credits list';
$v69['TMED'] = 'Media type';
$v69['TMOO'] = 'Mood';
$v69['TMT']  = 'Media type';
$v69['TOA']  = 'Original artist(s)/performer(s)';
$v69['TOAL'] = 'Original album/movie/show title';
$v69['TOF']  = 'Original filename';
$v69['TOFN'] = 'Original filename';
$v69['TOL']  = 'Original Lyricist(s)/text writer(s)';
$v69['TOLY'] = 'Original lyricist(s)/text writer(s)';
$v69['TOPE'] = 'Original artist(s)/performer(s)';
$v69['TOR']  = 'Original release year';
$v69['TORY'] = 'Original release year';
$v69['TOT']  = 'Original album/Movie/Show title';
$v69['TOWN'] = 'File owner/licensee';
$v69['TP1']  = 'Lead artist(s)/Lead performer(s)/Soloist(s)/Performing group';
$v69['TP2']  = 'Band/Orchestra/Accompaniment';
$v69['TP3']  = 'Conductor/Performer refinement';
$v69['TP4']  = 'Interpreted, remixed, or otherwise modified by';
$v69['TPA']  = 'Part of a set';
$v69['TPB']  = 'Publisher';
$v69['TPE1'] = 'Lead performer(s)/Soloist(s)';
$v69['TPE2'] = 'Band/orchestra/accompaniment';
$v69['TPE3'] = 'Conductor/performer refinement';
$v69['TPE4'] = 'Interpreted, remixed, or otherwise modified by';
$v69['TPOS'] = 'Part of a set';
$v69['TPRO'] = 'Produced notice';
$v69['TPUB'] = 'Publisher';
$v69['TRC']  = 'ISRC (International Standard Recording Code)';
$v69['TRCK'] = 'Track number/Position in set';
$v69['TRD']  = 'Recording dates';
$v69['TRDA'] = 'Recording dates';
$v69['TRK']  = 'Track number/Position in set';
$v69['TRSN'] = 'Internet radio station name';
$v69['TRSO'] = 'Internet radio station owner';
$v69['TSI']  = 'Size';
$v69['TSIZ'] = 'Size';
$v69['TSOA'] = 'Album sort order';
$v69['TSOP'] = 'Performer sort order';
$v69['TSOT'] = 'Title sort order';
$v69['TSRC'] = 'ISRC (international standard recording code)';
$v69['TSS']  = 'Software/hardware and settings used for encoding';
$v69['TSSE'] = 'Software/Hardware and settings used for encoding';
$v69['TSST'] = 'Set subtitle';
$v69['TT1']  = 'Content group description';
$v69['TT2']  = 'Title/Songname/Content description';
$v69['TT3']  = 'Subtitle/Description refinement';
$v69['TXT']  = 'Lyricist/text writer';
$v69['TXX']  = 'User defined text information frame';
$v69['TXXX'] = 'User defined text information frame';
$v69['TYE']  = 'Year';
$v69['TYER'] = 'Year';
$v69['UFI']  = 'Unique file identifier';
$v69['UFID'] = 'Unique file identifier';
$v69['ULT']  = 'Unsychronized lyric/text transcription';
$v69['USER'] = 'Terms of use';
$v69['USLT'] = 'Unsynchronised lyric/text transcription';
$v69['WAF']  = 'Official audio file webpage';
$v69['WAR']  = 'Official artist/performer webpage';
$v69['WAS']  = 'Official audio source webpage';
$v69['WCM']  = 'Commercial information';
$v69['WCOM'] = 'Commercial information';
$v69['WCOP'] = 'Copyright/Legal information';
$v69['WCP']  = 'Copyright/Legal information';
$v69['WOAF'] = 'Official audio file webpage';
$v69['WOAR'] = 'Official artist/performer webpage';
$v69['WOAS'] = 'Official audio source webpage';
$v69['WORS'] = 'Official Internet radio station homepage';
$v69['WPAY'] = 'Payment';
$v69['WPB']  = 'Publishers official webpage';
$v69['WPUB'] = 'Publishers official webpage';
$v69['WXX']  = 'User defined URL link frame';
$v69['WXXX'] = 'User defined URL link frame';
$v69['TFEA'] = 'Featured Artist';        
$v69['TSTU'] = 'Recording Studio';       
$v69['rgad'] = 'Replay Gain Adjustment'; 
}
return (isset($v69["$v329"]) ? $v69["$v329"] : '');
}
function f44($v329) {
static $v70 = array();
if (empty($v70)) {
$v70['COM']  = 'comments';
$v70['COMM'] = 'comments';
$v70['TAL']  = 'album';
$v70['TALB'] = 'album';
$v70['TBP']  = 'bpm';
$v70['TBPM'] = 'bpm';
$v70['TCM']  = 'composer';
$v70['TCO']  = 'genre';
$v70['TCOM'] = 'composer';
$v70['TCON'] = 'genre';
$v70['TCOP'] = 'copyright';
$v70['TCR']  = 'copyright';
$v70['TEN']  = 'encoded_by';
$v70['TENC'] = 'encoded_by';
$v70['TEXT'] = 'lyricist';
$v70['TIT1'] = 'description';
$v70['TIT2'] = 'title';
$v70['TIT3'] = 'subtitle';
$v70['TLA']  = 'language';
$v70['TLAN'] = 'language';
$v70['TLE']  = 'length';
$v70['TLEN'] = 'length';
$v70['TMOO'] = 'mood';
$v70['TOA']  = 'original_artist';
$v70['TOAL'] = 'original_album';
$v70['TOF']  = 'original_filename';
$v70['TOFN'] = 'original_filename';
$v70['TOL']  = 'original_lyricist';
$v70['TOLY'] = 'original_lyricist';
$v70['TOPE'] = 'original_artist';
$v70['TOT']  = 'original_album';
$v70['TP1']  = 'artist';
$v70['TP2']  = 'band';
$v70['TP3']  = 'conductor';
$v70['TP4']  = 'remixer';
$v70['TPB']  = 'publisher';
$v70['TPE1'] = 'artist';
$v70['TPE2'] = 'band';
$v70['TPE3'] = 'conductor';
$v70['TPE4'] = 'remixer';
$v70['TPUB'] = 'publisher';
$v70['TRC']  = 'isrc';
$v70['TRCK'] = 'track';
$v70['TRK']  = 'track';
$v70['TSI']  = 'size';
$v70['TSIZ'] = 'size';
$v70['TSRC'] = 'isrc';
$v70['TSS']  = 'encoder_settings';
$v70['TSSE'] = 'encoder_settings';
$v70['TSST'] = 'subtitle';
$v70['TT1']  = 'description';
$v70['TT2']  = 'title';
$v70['TT3']  = 'subtitle';
$v70['TXT']  = 'lyricist';
$v70['TXX']  = 'text';
$v70['TXXX'] = 'text';
$v70['TYE']  = 'year';
$v70['TYER'] = 'year';
$v70['UFI']  = 'unique_file_identifier';
$v70['UFID'] = 'unique_file_identifier';
$v70['ULT']  = 'unsychronized_lyric';
$v70['USER'] = 'terms_of_use';
$v70['USLT'] = 'unsynchronised lyric';
$v70['WAF']  = 'url_file';
$v70['WAR']  = 'url_artist';
$v70['WAS']  = 'url_source';
$v70['WCOP'] = 'copyright';
$v70['WCP']  = 'copyright';
$v70['WOAF'] = 'url_file';
$v70['WOAR'] = 'url_artist';
$v70['WOAS'] = 'url_souce';
$v70['WORS'] = 'url_station';
$v70['WPB']  = 'url_publisher';
$v70['WPUB'] = 'url_publisher';
$v70['WXX']  = 'url_user';
$v70['WXXX'] = 'url_user';
$v70['TFEA'] = 'featured_artist';
$v70['TSTU'] = 'studio';
}
return (isset($v70["$v329"]) ? $v70["$v329"] : '');
}
function f105($v523, $v243) {
$v151['encoding']   = array('ISO-8859-1', 'UTF-16', 'UTF-16BE', 'UTF-8');
$v151['terminator'] = array(chr(0), chr(0).chr(0), chr(0).chr(0), chr(0));
return (isset($v151["$v523"][$v243]) ? $v151["$v523"][$v243] : '');
}
function f106($v501, &$v152, $v308) {
switch ($v501) {
case 0:
case 1:
case 2:
case 3:
return $v501;
break;
default:
$v152['warning'] .= "\n".'Invalid text encoding byte ('.$v501.') in frame "'.$v308.'", defaulting to ASCII encoding';
return 0;
break;
}
}
function f66($v329, $v350) {
switch ($v350) {
case 2:
return ereg('[A-Z][A-Z0-9]{2}', $v329);
break;
case 3:
case 4:
return ereg('[A-Z][A-Z0-9]{3}', $v329);
break;
}
return false;
}
function f64($v420, $v170=false, $v171=false) {
for ($i = 0; $i < strlen($v420); $i++) {
if ((chr($v420{$i}) < chr('0')) || (chr($v420{$i}) > chr('9'))) {
if (($v420{$i} == '.') && $v170) {
} elseif (($v420{$i} == '-') && $v171 && ($i == 0)) {
} else {
return false;
}
}
}
return true;
}
function f65($v226) {
if (strlen($v226) != 8) {
return false;
}
if (!f64($v226, false)) {
return false;
}
$v545  = substr($v226, 0, 4);
$v400 = substr($v226, 4, 2);
$day   = substr($v226, 6, 2);
if (($v545 == 0) || ($v400 == 0) || ($day == 0)) {
return false;
}
if ($v400 > 12) {
return false;
}
if ($day > 31) {
return false;
}
if (($day > 30) && (($v400 == 4) || ($v400 == 6) || ($v400 == 9) || ($v400 == 11))) {
return false;
}
if (($day > 29) && ($v400 == 2)) {
return false;
}
return true;
}
function f60($v392) {
if ($v392 == 2) {
return 6;
} else {
return 10;
}
}
function f122(&$fd, &$v152) {
$v348     = 128;
$v172 = 32;
@fseek($fd, 0 - $v348 - $v172, SEEK_END);
$v7 = @fread($fd, $v348 + $v172);
if ((substr($v7, 0, strlen('APETAGEX')) == 'APETAGEX') && (substr($v7, $v172, strlen('TAG')) == 'TAG')) {
$v6 = substr($v7, 0, $v172);
$v8 = 0 - $v172 - $v348;
} elseif (substr($v7, $v348, strlen('APETAGEX')) == 'APETAGEX') {
$v6 = substr($v7, $v348, $v172);
$v8 = 0 - $v172;
} else {
return false;
}
$v152['ape']['tag_offset_end'] = $v152['filesize'] - ($v8 + $v172);
if (empty($v152['fileformat'])) {
$v152['fileformat'] = 'ape';
}
if (!($v152['ape']['footer'] = f141($v6))) {
$v152['error'] .= "\n".'Error parsing APE footer at offset '.$v152['ape']['tag_offset_end'];
return false;
}
if (isset($v152['ape']['footer']['flags']['header']) && $v152['ape']['footer']['flags']['header']) {
fseek($fd, $v8 - $v152['ape']['footer']['raw']['tagsize'] + $v172 - $v172, SEEK_END);
$v152['ape']['tag_offset_start'] = ftell($fd);
$v14 = fread($fd, $v152['ape']['footer']['raw']['tagsize'] + $v172);
} else {
fseek($fd, $v8 - $v152['ape']['footer']['raw']['tagsize'] + $v172, SEEK_END);
$v152['ape']['tag_offset_start'] = ftell($fd);
$v14 = fread($fd, $v152['ape']['footer']['raw']['tagsize']);
}
$v423 = 0;
if (isset($v152['ape']['footer']['flags']['header']) && $v152['ape']['footer']['flags']['header']) {
if ($v152['ape']['header'] = f141(substr($v14, 0, $v172))) {
$v423 += $v172;
} else {
$v152['error'] .= "\n".'Error parsing APE header at offset '.$v152['ape']['tag_offset_start'];
return false;
}
}
for ($i = 0; $i < $v152['ape']['footer']['raw']['tag_items']; $i++) {
$v534 = f73(substr($v14, $v423, 4));
$v423 += 4;
$v369 = f73(substr($v14, $v423, 4));
$v423 += 4;
if (strstr(substr($v14, $v423), chr(0)) === false) {
$v152['error'] .= "\n".'Cannot find null-byte (0x00) seperator between ItemKey #'.$i.' and value. ItemKey starts '.$v423.' bytes into the APE tag, at file offset '.($v152['ape']['tag_offset_start'] + $v423);
return false;
}
$v92 = strpos($v14, chr(0), $v423) - $v423;
$v370      = strtolower(substr($v14, $v423, $v92));
$v423 += $v92 + 1; 
$v152['ape']['items']["$v370"]['data'] = substr($v14, $v423, $v534);
$v423 += $v534;
if ($v152['ape']['footer']['tag_version'] >= 2) {
$v152['ape']['items']["$v370"]['flags'] = f142($v369);
}
switch ($v152['ape']['items']["$v370"]['flags']['item_contents_raw']) {
case 0: 
case 3: 
$v152['ape']['items']["$v370"]['data'] = explode(chr(0), $v152['ape']['items']["$v370"]['data']);
foreach ($v152['ape']['items']["$v370"]['data'] as $key => $v531) {
$v152['ape']['items']["$v370"]['data_ascii'][$key]    = f102($v531, 3);
}
break;
default: 
break;
}
switch ($v370) {
case 'replaygain_track_gain':
$v152['replay_gain']['radio']['adjustment']      = (float) $v152['ape']['items']["$v370"]['data_ascii'];
$v152['replay_gain']['radio']['originator']      = 'unspecified';
break;
case 'replaygain_track_peak':
$v152['replay_gain']['radio']['peak']            = (float) $v152['ape']['items']["$v370"]['data_ascii'];
$v152['replay_gain']['radio']['originator']      = 'unspecified';
break;
case 'replaygain_album_gain':
$v152['replay_gain']['audiophile']['adjustment'] = (float) $v152['ape']['items']["$v370"]['data_ascii'];
$v152['replay_gain']['audiophile']['originator'] = 'unspecified';
break;
case 'replaygain_album_peak':
$v152['replay_gain']['audiophile']['peak']       = (float) $v152['ape']['items']["$v370"]['data_ascii'];
$v152['replay_gain']['audiophile']['originator'] = 'unspecified';
break;
default:
foreach ($v152['ape']['items']["$v370"]['data_ascii'] as $v218) {
$v152['ape']['comments'][strtolower($v370)][] = $v218;
}
break;
}
}
if (isset($v152['ape']['comments'])) {
f25($v152['ape']['comments'], $v152, true, true, true);
}
return true;
}
function f141($v11) {
$v339['raw']['footer_tag']   =                  substr($v11,  0, 8);
if ($v339['raw']['footer_tag'] != 'APETAGEX') {
return false;
}
$v339['raw']['version']      = f73(substr($v11,  8, 4));
$v339['raw']['tagsize']      = f73(substr($v11, 12, 4));
$v339['raw']['tag_items']    = f73(substr($v11, 16, 4));
$v339['raw']['global_flags'] = f73(substr($v11, 20, 4));
$v339['raw']['reserved']     =                  substr($v11, 24, 8);
$v339['tag_version']         = $v339['raw']['version'] / 1000;
if ($v339['tag_version'] >= 2) {
$v339['flags'] = f142($v339['raw']['global_flags']);
}
return $v339;
}
function f142($v449) {
$v269['header']            = (bool) ($v449 & 0x80000000);
$v269['footer']            = (bool) ($v449 & 0x40000000);
$v269['this_is_header']    = (bool) ($v449 & 0x20000000);
$v269['item_contents_raw'] =        ($v449 & 0x00000006) >> 1;
$v269['read_only']         = (bool) ($v449 & 0x00000001);
$v269['item_contents']     = f3($v269['item_contents_raw']);
return $v269;
}
function f3($v222) {
static $v5 = array();
if (empty($v5)) {
$v5[0]  = 'utf-8';
$v5[1]  = 'binary';
$v5[2]  = 'external';
$v5[3]  = 'reserved';
}
return (isset($v5[$v222]) ? $v5[$v222] : 'invalid');
}
function f4($v372) {
static $v16 = array();
if (empty($v16)) {
$v16[]  = 'title';
$v16[]  = 'subtitle';
$v16[]  = 'artist';
$v16[]  = 'album';
$v16[]  = 'debut album';
$v16[]  = 'publisher';
$v16[]  = 'conductor';
$v16[]  = 'track';
$v16[]  = 'composer';
$v16[]  = 'comment';
$v16[]  = 'copyright';
$v16[]  = 'publicationright';
$v16[]  = 'file';
$v16[]  = 'year';
$v16[]  = 'record date';
$v16[]  = 'record location';
$v16[]  = 'genre';
$v16[]  = 'media';
$v16[]  = 'related';
$v16[]  = 'isrc';
$v16[]  = 'abstract';
$v16[]  = 'language';
$v16[]  = 'bibliography';
}
return in_array(strtolower($v372), $v16);
}
function f109($filename, $v224, $v544=false) {
if ($v13 = f46($v224)) {
if ($fp = @fopen($filename, 'a+b')) {
$v426 = ignore_user_abort(true);
flock($fp, LOCK_EX);
$v17 = 0;
fseek($fp, -128, SEEK_END);
$v89 = fread($fp, 128);
if (substr($v89, 0, 3) == 'TAG') {
$v17 -= 128;
} else {
if ($v544) {
$v89 = 'TAG'.str_repeat(chr(0), 124).chr(255);
} else {
unset($v89);
}
}
fseek($fp, $v17 - 32, SEEK_END);
$v9 = fread($fp, 32);
if (substr($v9, 0, 8) == 'APETAGEX') {
$v128 = f73(substr($v9,  8, 4)) / 1000;
$v127    = f73(substr($v9, 12, 4));
switch ($v128) {
case 1:
$v17 -= $v127;
break;
case 2:
fseek($fp, $v17 - $v127 - 32, SEEK_END);
$v12 = fread($fp, 32);
if (substr($v12, 0, 8) == 'APETAGEX') {
$v17 -= ($v127 + 32);
} else {
$v17 -= $v127;
}
break;
default:
flock($fp, LOCK_UN);
fclose($fp);
ignore_user_abort($v426);
return false;
break;
}
} else {
}
fseek($fp, $v17, SEEK_END);
ftruncate($fp, ftell($fp));
fwrite($fp, $v13, strlen($v13));
if (!empty($v89)) {
fwrite($fp, $v89, 128);
}
flock($fp, LOCK_UN);
fclose($fp);
ignore_user_abort($v426);
return true;
}
return false;
}
return false;
}
function f46($v224) {
$v373 = array();
if (!is_array($v224)) {
return false;
}
foreach ($v224 as $key => $v180) {
if (!is_array($v180)) {
return false;
}
$v535 = '';
foreach ($v180 as $v531) {
$v535 .= str_replace(chr(0), '', $v180);
}
$v494  = f74(strlen($v535), 4);
$v494 .= "\x00\x00\x00\x00";
$v494 .= f21($key)."\x00";
$v494 .= $v535;
$v373[] = $v494;
}
return f48($v373, true).implode('', $v373).f48($v373, false);
}
function f48(&$v373, $v367=false) {
$v493 = 0;
foreach ($v373 as $v371) {
$v493 += strlen($v371);
}
$v10  = 'APETAGEX';
$v10 .= f74(2000, 4);
$v10 .= f74(32 + $v493, 4);
$v10 .= f74(count($v373), 4);
$v10 .= f47(true, true, $v367, 0, false);
$v10 .= str_repeat(chr(0), 8);
return $v10;
}
function f47($v337=true, $v275=true, $v367=false, $v244=0, $v451=false) {
$v15 = array_fill(0, 4, 0);
if ($v337) {
$v15[0] |= 0x80; 
}
if (!$v275) {
$v15[0] |= 0x40; 
}
if ($v367) {
$v15[0] |= 0x20; 
}
$v15[3] |= ($v244 << 1);
if ($v451) {
$v15[3] |= 0x01; 
}
return chr($v15[3]).chr($v15[2]).chr($v15[1]).chr($v15[0]);
}
function f21($v372) {
$v372 = eregi_replace("[^\x20-\x7E]", '', $v372);
switch ($v372) {
case 'EAN/UPC':
case 'ISBN':
case 'LC':
case 'ISRC':
$v372 = strtoupper($v372);
break;
default:
$v372 = ucwords($v372);
break;
}
return $v372;
}
define('FREAD_BUFFER_SIZE', 16384); 
$v359 = get_included_files();
foreach ($v359 as $key => $val) {
if (basename($val) == 'getid3.php') {
if (substr(php_uname(), 0, 7) == 'Windows') {
define('GETID3_INCLUDEPATH', str_replace('\\', '/', dirname($val)).'/');
} else {
define('GETID3_INCLUDEPATH', dirname($val).'/');
}
}
}
if (!defined('GETID3_INCLUDEPATH')) {
define('GETID3_INCLUDEPATH', '');
}
function f49($filename, $v182='mp3', $v103=false, $v101=false, $v102=false) {
$v152     = array();
$v382 = null;
if (!f61($filename, $v382, $v152)) {
f22($v152);
f24($v382);
return $v152;
}
f56($v382, $v152);
rewind($v382);
$v278 = fread($v382, 32774); 
if (f57($v382, $v152)) {
fseek($v382, $v152['avdataoffset'], SEEK_SET);
$v278 = fread($v382, 32774); 
}
f55($v382, $v152);
if (($v152['avdataend'] - $v152['avdataoffset']) > 0) {
if ($v55 = f52($v278)) {
if (!$v55['allowtags'] && ($v152['avdataoffset'] > 0) && ($v152['avdataend'] != $v152['filesize'])) {
$v152['error'] .= "\n".'Illegal ID3 and/or APE tag found on non multimedia file.';
break;
}
$v152['mime_type'] = $v55['mimetype'];
$v163 = $v55['function'];
$v163($v382, $v152);
} elseif ((($v182 == 'mp3') || (($v182 == '') && ((substr($v278, 0, 3) == 'ID3') || (substr(f7(substr($v278, 0, 2)), 0, 11) == '11111111111'))))) {
$v238 = $v152;
if (f121($v382, $v238)) {
$v152 = $v238;
} else {
f130($v382, $v152);
}
} else {
}
}
if (isset($v152['fileformat'])) {
$v49  = 0;
$v49 += (isset($v152['audio']['bitrate']) ? $v152['audio']['bitrate'] : 0);
$v49 += (isset($v152['video']['bitrate']) ? $v152['video']['bitrate'] : 0);
if (($v49 > 0) && !isset($v152['bitrate'])) {
$v152['bitrate'] = $v49;
}
if (!empty($v152['playtime_seconds']) && empty($v152['playtime_string'])) {
$v152['playtime_string'] = f94($v152['playtime_seconds']);
}
if (!empty($v152['audio']['channels'])) {
switch ($v152['audio']['channels']) {
case 1:
$v152['audio']['channelmode'] = 'mono';
break;
case 2:
$v152['audio']['channelmode'] = 'stereo';
break;
default:
break;
}
}
}
if ($v103 && empty($v152['f140'])) {
set_time_limit(max($v152['filesize'] / 10000000, 30));
$v152['f140'] = f140($filename);
}
if ($v101 && empty($v152['f139'])) {
if ($v102 || empty($v152['md5_data_source'])) {
f129($v152);
}
}
f19($v152);
f18($v152);
$v152['tags'] = array_unique($v152['tags']);
sort($v152['tags']);
f22($v152);
f24($v382);
return $v152;
}
function f53($filename) {
$v152     = array();
$v382 = null;
if (!f61($filename, $v382, $v152)) {
f22($v152);
f24($v382);
return $v152;
}
f55($v382, $v152);
f57($v382, $v152);
f56($v382, $v152);
$v152['tags'] = array_unique($v152['tags']);
sort($v152['tags']);
f22($v152);
f24($v382);
return $v152;
}
function f61($filename, &$v382, &$v152) {
$v152['fileformat']          = '';            
$v152['audio']['dataformat'] = '';            
$v152['video']['dataformat'] = '';            
$v152['tags']                = array();       
$v152['error']               = '';            
$v152['warning']             = '';            
$v152['comments']            = array();       
$v152['exist']               = false;
if (eregi('^(ht|f)tp://', $filename)) {
$v152['filename'] = $filename;
$v152['error'] .= "\n".'Remote files are not supported in this version of f123() - please copy the file locally first';
return false;
} else {
$v152['filename']     = basename($filename);
$v152['filepath']     = str_replace('\\', '/', realpath(dirname($filename)));
$v152['filenamepath'] = $v152['filepath'].'/'.$v152['filename'];
ob_start();
if ($v382 = fopen($filename, 'rb')) {
$v152['exist'] = true;
fseek($v382, 0, SEEK_END);
$v152['filesize'] = ftell($v382);
ob_end_clean();
if ($v152['filesize'] == 0) {
if (filesize($v152['filenamepath']) != 0) {
unset($v152['filesize']);
$v152['error'] .= "\n".'File is most likely larger than 2GB and is not supported by PHP';
return false;
}
}
$v152['avdataoffset'] = 0;
$v152['avdataend']    = $v152['filesize'];
} else {
$v152['error'] .= "\n".'Error opening file: '.trim(strip_tags(ob_get_contents()));
ob_end_clean();
return false;
}
}
return true;
}
function f22(&$v152) {
if (empty($v152['fileformat'])) {
unset($v152['fileformat']);
unset($v152['audio']['dataformat']);
unset($v152['video']['dataformat']);
unset($v152['avdataoffset']);
unset($v152['avdataend']);
}
$v130 = array('dataformat', 'bitrate_mode');
foreach (array('audio', 'video') as $key) {
if (isset($v152[$key])) {
$v136 = true;
foreach ($v152[$key] as $v486 => $v489) {
if (!in_array($v486, $v130)) {
$v136 = false;
break;
}
}
if ($v136) {
unset($v152[$key]);
}
}
}
$v130 = array('comments', 'error', 'warning', 'audio', 'video');
foreach ($v130 as $v374) {
if (empty($v152["$v374"])) {
unset($v152["$v374"]);
}
}
return true;
}
function f24(&$v264) {
if (isset($v382) && is_resource($v382) && (get_resource_type($v382) == 'file')) {
fclose($v382);
if (isset($v382)) {
unset($v382);
}
}
return true;
}
function f25($v219, &$v152, $v93=array('title'=>'title', 'artist'=>'artist', 'album'=>'album', 'year'=>'year', 'genre'=>'genre', 'comment'=>'comment', 'track'=>'track'), $v137=false, $v33=false) {
if ($v137) {
$v152['comments'] = array();
}
if (!is_array($v93)) {
$v93 = array();
foreach (array_keys($v219) as $key => $v531) {
$v93[$v531] = $v531;
}
}
foreach ($v93 as $v81 => $v153) {
$v153 = strtolower($v153);
if (!empty($v219["$v81"])) {
if (is_array($v219["$v81"])) {
foreach ($v219["$v81"] as $v50) {
if ((empty($v152['comments']["$v153"])) || ($v33 && !in_array($v50, $v152['comments']["$v153"], false))) {
$v152['comments']["$v153"][] = $v50;
}
}
} else {
if ((empty($v152['comments']["$v153"])) || ($v33 && !in_array($v219["$v81"], $v152['comments']["$v153"], false))) {
$v152['comments']["$v153"][] = $v219["$v81"];
}
}
}
}
return true;
}
function f52(&$v261) {
static $v276 = array();
if (empty($v276)) {
$v276 = array(
'aac'  => array('^ADIF',  'getid3.aac.php', 'f120', true, 'application/octet-stream'),
'au'   => array('^\.snd', 'getid3.au.php', 'getAUheaderFilepointer', true, 'audio/basic'),
'flac' => array('^fLaC', 'getid3.flac.php', 'getFLACHeaderFilepointer', true, 'audio/x-flac'),
'la'   => array('^LA0[23]', 'getid3.la.php', 'getLAHeaderFilepointer', true, 'application/octet-stream'),
'lpac' => array('^LPAC', 'getid3.lpac.php', 'getLPACHeaderFilepointer', true, 'application/octet-stream'),
'midi' => array('^MThd', 'getid3.midi.php', 'getMIDIHeaderFilepointer', true, 'audio/midi'),
'mac'  => array('^MAC ', 'getid3.monkey.php', 'getMonkeysAudioHeaderFilepointer', true, 'application/octet-stream'),
'mod'  => array('^.{1080}(M.K.|[5-9]CHN|[1-3][0-9]CH)', 'getid3.mod.php', 'getMODheaderFilepointer', true, 'audio/mod'),
'it'   => array('^IMPM', 'getid3.mod.php', 'getITheaderFilepointer', true, 'audio/it'),
'xm'   => array('^Extended Module', 'getid3.mod.php', 'getXMheaderFilepointer', true, 'audio/xm'),
's3m'  => array('^.{44}SCRM', 'getid3.mod.php', 'getS3MheaderFilepointer', true, 'audio/s3m'),
'mpc'  => array('^MP\+', 'getid3.mpc.php', 'getMPCHeaderFilepointer', true, 'application/octet-stream'),
'ofr'  => array('^(\*RIFF|OFR)', 'getid3.optimfrog.php', 'getOptimFROGHeaderFilepointer', true, 'application/octet-stream'),
'voc'  => array('^Creative Voice File', 'getid3.voc.php', 'getVOCheaderFilepointer', true, 'audio/voc'),
'vqf'  => array('^TWIN', 'getid3.vqf.php', 'getVQFHeaderFilepointer', true, 'application/octet-stream'),
'asf'  => array('^\x30\x26\xB2\x75\x8E\x66\xCF\x11\xA6\xD9\x00\xAA\x00\x62\xCE\x6C', 'getid3.asf.php', 'getASFHeaderFilepointer', true, 'video/x-ms-asf'),
'mpeg' => array('^\x00\x00\x01\xBA', 'getid3.mpeg.php', 'getMPEGHeaderFilepointer', true, 'video/mpeg'),
'nsv'  => array('^NSV[sf]', 'getid3.nsv.php', 'getNSVHeaderFilepointer', true, 'application/octet-stream'),
'ogg'  => array('^OggS', 'getid3.ogg.php', 'getOggHeaderFilepointer', true, 'application/x-ogg'),
'quicktime' => array('^.{4}(cmov|free|ftyp|mdat|moov|pnot|skip|wide)', 'getid3.quicktime.php', 'getQuicktimeHeaderFilepointer', true, 'video/quicktime'),
'riff' => array('^(RIFF|SDSS|FORM)', 'getid3.riff.php', 'f132', true, 'audio/x-wave'),
'real' => array('^(\.RMF|.ra)', 'getid3.real.php', 'getRealHeaderFilepointer', true, 'audio/x-realaudio'),
'bmp'  => array('^BM', 'getid3.bmp.php', 'getBMPHeaderFilepointer', false, 'image/bmp'),
'gif'  => array('^GIF', 'getid3.gif.php', 'getGIFHeaderFilepointer', false, 'image/gif'),
'jpg'  => array('^\xFF\xD8\xFF', 'getid3.jpg.php', 'getJPGHeaderFilepointer', false, 'image/jpg'),
'png'  => array('^\x89\x50\x4E\x47\x0D\x0A\x1A\x0A', 'getid3.png.php', 'getPNGHeaderFilepointer', false, 'image/png'),
'iso'  => array('^.{32769}CD001', 'getid3.iso.php', 'getISOHeaderFilepointer', false, 'application/octet-stream'),
'zip'  => array('^PK\x03\x04', 'getid3.zip.php', 'getZIPHeaderFilepointer', false, 'application/zip'),
);
}
foreach ($v276 as $v277 => $v362) {
if (preg_match('/'.$v362[0].'/s', $v261)) {
$v65['format']    = $v277;
$v65['include']   = $v362[1];
$v65['function']  = $v362[2];
$v65['allowtags'] = $v362[3];
$v65['mimetype']  = $v362[4];
return $v65;
}
}
return false;
}
function f55(&$fd, &$v152) {
f122($fd, $v152);
if (isset($v152['ape']['header']['raw']['tagsize'])) {
$v152['avdataend'] -= $v152['ape']['header']['raw']['tagsize'] + 32;
if (!empty($v152['ape']['comments'])) {
f25($v152['ape']['comments'], $v152, true, true, true);
$v152['tags'][] = 'ape';
}
}
return true;
}
function f128(&$fd, &$v152) {
@fseek($fd, (0 - 128 - 9 - 6), SEEK_END); 
$v384 = @fread($fd, (128 + 9 + 6));
$v386    = substr($v384,  0,   6); 
$v385    = substr($v384,  6,   9); 
$v347      = substr($v384, 15, 128); 
if ($v385 == 'LYRICSEND') {
$v388    = 5100;
$v387  = $v152['filesize'] - 128 - $v388;
$v389 = 1;
} elseif ($v385 == 'LYRICS200') {
$v388    = $v386 + 6 + strlen('LYRICS200');
$v387  = $v152['filesize'] - 128 - $v388;
$v389 = 2;
} elseif (substr(strrev($v384), 0, 9) == strrev('LYRICSEND')) {
$v388    = 5100;
$v387  = $v152['filesize'] - $v388;
$v389 = 1;
$v387  = $v152['filesize'] - $v388;
} elseif (substr(strrev($v384), 0, 9) == strrev('LYRICS200')) {
$v388    = strrev(substr(strrev($v384), 9, 6)) + 6 + strlen('LYRICS200'); 
$v387  = $v152['filesize'] - $v388;
$v389 = 2;
} else {
if (!isset($v152['ape'])) {
f122($fd, $v152);
}
if (isset($v152['ape']['tag_offset_start']) && ($v152['ape']['tag_offset_start'] > 15)) {
fseek($fd, $v152['ape']['tag_offset_start'] - 15, SEEK_SET);
$v386 = fread($fd, 6);
$v385 = fread($fd, 9);
if ($v385 == 'LYRICSEND') {
$v388    = 5100;
$v387  = $v152['ape']['tag_offset_start'] - $v388;
$v152['avdataend'] = $v387;
$v389 = 1;
$v152['warning'] .= "\n".'APE tag located after Lyrics3, will probably break Lyrics3 compatability';
} elseif ($v385 == 'LYRICS200') {
$v388    = $v386 + 6 + strlen('LYRICS200'); 
$v387  = $v152['ape']['tag_offset_start'] - $v388;
$v389 = 2;
$v152['warning'] .= "\n".'APE tag located before Lyrics3, will probably break Lyrics3 compatability';
}
}
}
if (isset($v387)) {
$v152['avdataend'] = $v387;
f127($v152, $fd, $v387, $v389, $v388);
if (!isset($v152['ape'])) {
f122($fd, $v152, $v152['lyrics3']['tag_offset_start']);
}
}
return true;
}
function f127(&$v152, &$fd, $v245, $v538, $v380) {
fseek($fd, $v245, SEEK_SET);
$v448 = fread($fd, $v380);
if (substr($v448, 0, 11) != 'LYRICSBEGIN') {
if (strpos($v448, 'LYRICSBEGIN') !== false) {
$v152['warning'] .= "\n".'"LYRICSBEGIN" expected at '.$v245.' but actually found at '.($v245 + strpos($v448, 'LYRICSBEGIN')).' - this is invalid for Lyrics3 v'.$v538;
$v152['avdataend'] = $v245 + strpos($v448, 'LYRICSBEGIN');
$v152['lyrics3']['tag_offset_start'] = $v152['avdataend'];
$v448 = substr($v448, strpos($v448, 'LYRICSBEGIN'));
$v380 = strlen($v448);
} else {
$v152['error'] .= "\n".'"LYRICSBEGIN" expected at '.$v245.' but found "'.substr($v448, 0, 11).'" instead';
return false;
}
}
$v152['lyrics3']['raw']['lyrics3version'] = $v538;
$v152['lyrics3']['raw']['lyrics3tagsize'] = $v380;
$v152['lyrics3']['tag_offset_start']      = $v245;
$v152['lyrics3']['tag_offset_end']        = $v245 + $v380;
switch ($v538) {
case 1:
$v152['tags'][] = 'lyrics3';
if (substr($v448, strlen($v448) - 9, 9) == 'LYRICSEND') {
$v152['lyrics3']['raw']['LYR'] = trim(substr($v448, 11, strlen($v448) - 11 - 9));
f77($v152);
} else {
$v152['error'] .= "\n".'"LYRICSEND" expected at '.(ftell($fd) - 11 + $v380 - 9).' but found "'.substr($v448, strlen($v448) - 9, 9).'" instead';
}
break;
case 2:
$v152['tags'][] = 'lyrics3';
if (substr($v448, strlen($v448) - 9, 9) == 'LYRICS200') {
$v152['lyrics3']['raw']['unparsed'] = substr($v448, 11, strlen($v448) - 11 - 9 - 6); 
$v448 = $v152['lyrics3']['raw']['unparsed'];
while (strlen($v448) > 0) {
$v257 = substr($v448, 0, 3);
$v259 = (int) substr($v448, 3, 5);
$v152['lyrics3']['raw']["$v257"] = substr($v448, 8, $v259);
$v448 = substr($v448, 3 + 5 + $v259);
}
if (isset($v152['lyrics3']['raw']['IND'])) {
$i = 0;
$v268 = array('lyrics', 'timestamps', 'inhibitrandom');
foreach ($v268 as $v267) {
if (strlen($v152['lyrics3']['raw']['IND']) > ++$i) {
$v152['lyrics3']['flags']["$v267"] = f62(substr($v152['lyrics3']['raw']['IND'], $i, 1));
}
}
}
$v258 = array('ETT'=>'title', 'EAR'=>'artist', 'EAL'=>'album', 'INF'=>'comment', 'AUT'=>'author');
foreach ($v258 as $key => $v531) {
if (isset($v152['lyrics3']['raw']["$key"])) {
$v152['lyrics3']['comments']["$v531"][] = trim($v152['lyrics3']['raw']["$key"]);
}
}
if (isset($v152['lyrics3']['raw']['IMG'])) {
$v355 = explode("\r\n", $v152['lyrics3']['raw']['IMG']);
foreach ($v355 as $key => $v354) {
if (strpos($v354, '||') !== false) {
$v352 = explode('||', $v354);
$v152['lyrics3']['images']["$key"]['filename']     = $v352[0];
$v152['lyrics3']['images']["$key"]['description']  = $v352[1];
$v152['lyrics3']['images']["$key"]['timestamp']    = f78($v352[2]);
}
}
}
if (isset($v152['lyrics3']['raw']['LYR'])) {
f77($v152);
}
} else {
$v152['error'] .= "\n".'"LYRICS200" expected at '.(ftell($fd) - 11 + $v380 - 9).' but found "'.substr($v448, strlen($v448) - 9, 9).'" instead';
}
break;
default:
$v152['error'] .= "\n".'Cannot process Lyrics3 version '.$v538.' (only v1 and v2)';
break;
}
return true;
}
function f78($v450) {
if (ereg('^\\[([0-9]{2}):([0-9]{2})\\]$', $v450, $v454)) {
return (int) (($v454[1] * 60) + $v454[2]);
}
return false;
}
function f77(&$v152) {
$v390 = explode("\r\n", $v152['lyrics3']['raw']['LYR']);
foreach ($v390 as $key => $v383) {
$v454 = array();
unset($v515);
while (ereg('^(\\[[0-9]{2}:[0-9]{2}\\])', $v383, $v454)) {
$v515[] = f78($v454[0]);
$v383 = str_replace($v454[0], '', $v383);
}
$v417["$key"] = $v383;
if (isset($v515) && is_array($v515)) {
sort($v515);
foreach ($v515 as $v518 => $v516) {
if (isset($v152['lyrics3']['synchedlyrics'][$v516])) {
$v152['lyrics3']['synchedlyrics'][$v516] .= "\r\n".$v383;
} else {
$v152['lyrics3']['synchedlyrics'][$v516] = $v383;
}
}
}
}
$v152['lyrics3']['unsynchedlyrics'] = implode("\r\n", $v417);
if (isset($v152['lyrics3']['synchedlyrics']) && is_array($v152['lyrics3']['synchedlyrics'])) {
ksort($v152['lyrics3']['synchedlyrics']);
}
return true;
}
function f32($filename) {
$v152 = f49($filename);
if (isset($v152['lyrics3']['tag_offset_start']) && isset($v152['lyrics3']['tag_offset_end'])) {
if ($fp = @fopen($filename, 'a+b')) {
flock($fp, LOCK_EX);
$v426 = ignore_user_abort(true);
fseek($fp, $v152['lyrics3']['tag_offset_end'], SEEK_SET);
$v54 = fread($fp, $v152['filesize'] - $v152['lyrics3']['tag_offset_end']);
ftruncate($fp, $v152['lyrics3']['tag_offset_start']);
fseek($fp, $v152['lyrics3']['tag_offset_start'], SEEK_SET);
fwrite($fp, $v54, strlen($v54));
flock($fp, LOCK_UN);
fclose($fp);
ignore_user_abort($v426);
return true;
}
}
return false;
}
function f56(&$fd, &$v152) {
fseek($fd, (0 - 128 - 9 - 6), SEEK_END); 
$v384 = fread($fd, (128 + 9 + 6));
$v386    = substr($v384,  0,   6); 
$v385    = substr($v384,  6,   9); 
$v347      = substr($v384, 15, 128); 
if ($v385 == 'LYRICSEND') {
$v388 = 5100;
$v152['avdataend'] -= $v388;
f128($v152, $fd, 0 - 128 - $v388, 1, $v388);
} elseif ($v385 == 'LYRICS200') {
$v388 = $v386 + 6 + strlen('LYRICS200'); 
$v152['avdataend'] -= $v388;
f128($v152, $fd, -128 - $v388, 2, $v388);
} elseif (substr($v384, strlen($v384) - 1 - 9, 9) == 'LYRICSEND') {
$v388 = 5100;
$v152['avdataend'] -= $v388;
f128($v152, $fd, 0 - $v388, 1, $v388);
} elseif (substr($v384, strlen($v384) - 1 - 9, 9) == 'LYRICS200') {
$v388 = $v386 + 6 + strlen('LYRICS200'); 
$v152['avdataend'] -= $v388;
f128($v152, $fd, 0 - $v388, 2, $v388);
}
if (substr($v347, 0, 3) == 'TAG') {
$v152['avdataend'] -= 128;
f125($fd, $v152);
if (empty($v152['fileformat'])) {
$v152['fileformat'] = 'id3';
}
$v152['tags'][] = 'id3v1';
if (isset($v152['id3v1'])) {
f25($v152['id3v1'], $v152, true, false, false);
}
}
return true;
}
function f57(&$fd, &$v152) {
f126($fd, $v152);
if (isset($v152['id3v2']['header'])) {
if (empty($v152['fileformat'])) {
$v152['fileformat'] = 'id3';
}
$v152['avdataoffset'] = $v152['id3v2']['headerlength'];
if (isset($v152['id3v2']['footer'])) {
$v152['avdataoffset'] += 10;
}
$v152['tags'][] = 'id3v2';
if (isset($v152['id3v2']['comments'])) {
f25($v152['id3v2']['comments'], $v152, true, false, false);
}
}
return true;
}
function f129(&$v152) {
if ((@$v152['fileformat'] == 'ogg') && (@$v152['audio']['dataformat'] == 'vorbis')) {
if ((bool) ini_get('safe_mode')) {
$v152['warning'] .= "\n".'Failed making system call to vorbiscomment.exe - f139 is incorrect - error returned: PHP running in Safe Mode (backtick operator not available)';
$v152['f139'] = false;
} else {
$v425 = ignore_user_abort(true);
$v242 = tempnam('/tmp', 'f123');
touch($v242);
$v495 = tempnam('/tmp', 'f123');
$v260 = $v152['filenamepath'];
if (substr(php_uname(), 0, 7) == 'Windows') {
if (file_exists(GETID3_INCLUDEPATH.'vorbiscomment.exe')) {
$v164 = `vorbiscomment.exe -w -c "$v242" "$v260" "$v495"`;
} else {
$v164 = 'vorbiscomment.exe not found in '.GETID3_INCLUDEPATH;
}
} else {
$v164 = `vorbiscomment -w -c "$v242" "$v260" "$v495" 2>&1`;
}
if (!empty($v164)) {
$v152['warning'] .= "\n".''.$v164;
$v152['f139'] = false;
} else {
$v152['f139'] = f140($v495);
}
unlink($v242);
unlink($v495);
ignore_user_abort($v425);
}
} else {
if (!empty($v152['avdataoffset']) || (isset($v152['avdataend']) && ($v152['avdataend'] < $v152['filesize']))) {
if (($v152['audio']['dataformat'] == 'wav') && ($v152['audio']['bits_per_sample'] == 8)) {
$v152['f139'] = f139($v152['filenamepath'], $v152['avdataoffset'], $v152['avdataend'], true);
} else {
$v152['f139'] = f139($v152['filenamepath'], $v152['avdataoffset'], $v152['avdataend']);
}
} else {
if (empty($v152['f140'])) {
$v152['f140'] = f140($v152['filenamepath']);
}
$v152['f139'] = $v152['f140'];
}
}
return true;
}
function f120(&$fd, &$v152) {
$v152['fileformat']          = 'aac';
$v152['audio']['dataformat'] = 'aac';
$v152['audio']['lossless']   = false;
fseek($fd, $v152['avdataoffset'], SEEK_SET);
$v0 = fread($fd, 1024);
$v423    = 0;
if (substr($v0, 0, 4) == 'ADIF') {
$v1 = f7($v0);
$v195          = 0;
$v152['aac']['header_type']                   = 'ADIF';
$v195 += 32;
$v152['aac']['header']['mpeg_version']        = 4;
$v152['aac']['header']['copyright']           = (bool) (substr($v1, $v195, 1) == '1');
$v195 += 1;
if ($v152['aac']['header']['copyright']) {
$v152['aac']['header']['copyright_id']    = f12(substr($v1, $v195, 72));
$v195 += 72;
}
$v152['aac']['header']['original_copy']       = (bool) (substr($v1, $v195, 1) == '1');
$v195 += 1;
$v152['aac']['header']['home']                = (bool) (substr($v1, $v195, 1) == '1');
$v195 += 1;
$v152['aac']['header']['is_vbr']              = (bool) (substr($v1, $v195, 1) == '1');
$v195 += 1;
if ($v152['aac']['header']['is_vbr']) {
$v152['audio']['bitrate_mode']            = 'vbr';
$v152['aac']['header']['bitrate_max']     = f11(substr($v1, $v195, 23));
$v195 += 23;
} else {
$v152['audio']['bitrate_mode']            = 'cbr';
$v152['aac']['header']['bitrate']         = f11(substr($v1, $v195, 23));
$v195 += 23;
$v152['audio']['bitrate']                 = $v152['aac']['header']['bitrate'];
}
if ($v152['audio']['bitrate'] == 0) {
$v152['error'] .= "\n".'Corrupt AAC file: bitrate_audio == zero';
return false;
}
$v152['aac']['header']['num_program_configs'] = 1 + f11(substr($v1, $v195, 4));
$v195 += 4;
for ($i = 0; $i < $v152['aac']['header']['num_program_configs']; $i++) {
if (!$v152['aac']['header']['is_vbr']) {
$v152['aac']['program_configs'][$i]['buffer_fullness']        = f11(substr($v1, $v195, 20));
$v195 += 20;
}
$v152['aac']['program_configs'][$i]['element_instance_tag']       = f11(substr($v1, $v195, 4));
$v195 += 4;
$v152['aac']['program_configs'][$i]['object_type']                = f11(substr($v1, $v195, 2));
$v195 += 2;
$v152['aac']['program_configs'][$i]['sampling_frequency_index']   = f11(substr($v1, $v195, 4));
$v195 += 4;
$v152['aac']['program_configs'][$i]['num_front_channel_elements'] = f11(substr($v1, $v195, 4));
$v195 += 4;
$v152['aac']['program_configs'][$i]['num_side_channel_elements']  = f11(substr($v1, $v195, 4));
$v195 += 4;
$v152['aac']['program_configs'][$i]['num_back_channel_elements']  = f11(substr($v1, $v195, 4));
$v195 += 4;
$v152['aac']['program_configs'][$i]['num_lfe_channel_elements']   = f11(substr($v1, $v195, 2));
$v195 += 2;
$v152['aac']['program_configs'][$i]['num_assoc_data_elements']    = f11(substr($v1, $v195, 3));
$v195 += 3;
$v152['aac']['program_configs'][$i]['num_valid_cc_elements']      = f11(substr($v1, $v195, 4));
$v195 += 4;
$v152['aac']['program_configs'][$i]['mono_mixdown_present']       = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
if ($v152['aac']['program_configs'][$i]['mono_mixdown_present']) {
$v152['aac']['program_configs'][$i]['mono_mixdown_element_number']    = f11(substr($v1, $v195, 4));
$v195 += 4;
}
$v152['aac']['program_configs'][$i]['stereo_mixdown_present']             = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
if ($v152['aac']['program_configs'][$i]['stereo_mixdown_present']) {
$v152['aac']['program_configs'][$i]['stereo_mixdown_element_number']  = f11(substr($v1, $v195, 4));
$v195 += 4;
}
$v152['aac']['program_configs'][$i]['matrix_mixdown_idx_present']         = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
if ($v152['aac']['program_configs'][$i]['matrix_mixdown_idx_present']) {
$v152['aac']['program_configs'][$i]['matrix_mixdown_idx']             = f11(substr($v1, $v195, 2));
$v195 += 2;
$v152['aac']['program_configs'][$i]['pseudo_surround_enable']         = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
}
for ($j = 0; $j < $v152['aac']['program_configs'][$i]['num_front_channel_elements']; $j++) {
$v152['aac']['program_configs'][$i]['front_element_is_cpe'][$j]     = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
$v152['aac']['program_configs'][$i]['front_element_tag_select'][$j] = f11(substr($v1, $v195, 4));
$v195 += 4;
}
for ($j = 0; $j < $v152['aac']['program_configs'][$i]['num_side_channel_elements']; $j++) {
$v152['aac']['program_configs'][$i]['side_element_is_cpe'][$j]     = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
$v152['aac']['program_configs'][$i]['side_element_tag_select'][$j] = f11(substr($v1, $v195, 4));
$v195 += 4;
}
for ($j = 0; $j < $v152['aac']['program_configs'][$i]['num_back_channel_elements']; $j++) {
$v152['aac']['program_configs'][$i]['back_element_is_cpe'][$j]     = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
$v152['aac']['program_configs'][$i]['back_element_tag_select'][$j] = f11(substr($v1, $v195, 4));
$v195 += 4;
}
for ($j = 0; $j < $v152['aac']['program_configs'][$i]['num_lfe_channel_elements']; $j++) {
$v152['aac']['program_configs'][$i]['lfe_element_tag_select'][$j] = f11(substr($v1, $v195, 4));
$v195 += 4;
}
for ($j = 0; $j < $v152['aac']['program_configs'][$i]['num_assoc_data_elements']; $j++) {
$v152['aac']['program_configs'][$i]['assoc_data_element_tag_select'][$j] = f11(substr($v1, $v195, 4));
$v195 += 4;
}
for ($j = 0; $j < $v152['aac']['program_configs'][$i]['num_valid_cc_elements']; $j++) {
$v152['aac']['program_configs'][$i]['cc_element_is_ind_sw'][$j]          = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
$v152['aac']['program_configs'][$i]['valid_cc_element_tag_select'][$j]   = f11(substr($v1, $v195, 4));
$v195 += 4;
}
$v195 = ceil($v195 / 8) * 8;
$v152['aac']['program_configs'][$i]['comment_field_bytes'] = f11(substr($v1, $v195, 8));
$v195 += 8;
$v152['aac']['program_configs'][$i]['comment_field']       = f12(substr($v1, $v195, 8 * $v152['aac']['program_configs'][$i]['comment_field_bytes']));
$v195 += 8 * $v152['aac']['program_configs'][$i]['comment_field_bytes'];
$v152['aac']['header']['profile_text']                      = f1($v152['aac']['program_configs'][$i]['object_type'], $v152['aac']['header']['mpeg_version']);
$v152['aac']['program_configs'][$i]['sampling_frequency']   = f2($v152['aac']['program_configs'][$i]['sampling_frequency_index']);
$v152['audio']['sample_rate']                               = $v152['aac']['program_configs'][$i]['sampling_frequency'];
$v152['audio']['channels']                                  = f0($v152['aac']['program_configs'][$i]);
if ($v152['aac']['program_configs'][$i]['comment_field']) {
$v152['comments']['comment'][]                   = $v152['aac']['program_configs'][$i]['comment_field'];
}
}
$v152['playtime_seconds'] = (($v152['avdataend'] - $v152['avdataoffset']) * 8) / $v152['audio']['bitrate'];
return true;
} else {
unset($v152['fileformat']);
unset($v152['aac']);
$v152['error'] .= "\n".'AAC-ADIF synch not found (expected "ADIF", found "'.substr($v0, 0, 4).'" instead)';
return false;
}
}
function f121(&$fd, &$v152, $v124=1000000, $v141=false) {
$v205  = 0;
$v330 = 0;
static $v227 = array();
for ($i = 0; $i < 256; $i++) {
$v227[chr($i)] = str_pad(decbin($i), 8, '0', STR_PAD_LEFT);
}
static $v43 = array();
while (true) {
fseek($fd, $v205, SEEK_SET);
$v487 = fread($fd, 10);
$v488 = strlen($v487);
if ($v488 != 10) {
$v152['error'] .= "\n".'Failed to read 10 bytes at offset '.(ftell($fd) - $v488).' (only read '.$v488.' bytes)';
return false;
}
$v1 = '';
for ($i = 0; $i < 10; $i++) {
$v1 .= $v227[$v487{$i}];
}
$v195 = 0;
$v492 = bindec(substr($v1, $v195, 12));
$v195 += 12;
if ($v492 != 0x0FFF) {
$v152['error'] .= "\n".'Synch pattern (0x0FFF) not found at offset '.(ftell($fd) - 10).' (found 0x0'.strtoupper(dechex($v492)).' instead)';
if ($v152['fileformat'] == 'aac') {
return true;
}
return false;
}
if ($v330 > 0) {
if (!$v1[$v195]) {
$v195 += 20;
} else {
$v195 += 18;
}
} else {
$v152['aac']['header_type']                      = 'ADTS';
$v152['aac']['header']['synch']                  = $v492;
$v152['fileformat']                              = 'aac';
$v152['audio']['dataformat']                     = 'aac';
$v152['aac']['header']['mpeg_version']           = ((substr($v1, $v195, 1) == '0') ? 4 : 2);
$v195 += 1;
$v152['aac']['header']['layer']                  = f11(substr($v1, $v195, 2));
$v195 += 2;
if ($v152['aac']['header']['layer'] != 0) {
$v152['error'] .= "\n".'Layer error - expected 0x00, found 0x'.dechex($v152['aac']['header']['layer']).' instead';
return false;
}
$v152['aac']['header']['crc_present']            = ((substr($v1, $v195, 1) == '0') ? true : false);
$v195 += 1;
$v152['aac']['header']['profile_id']             = f11(substr($v1, $v195, 2));
$v195 += 2;
$v152['aac']['header']['profile_text']           = f1($v152['aac']['header']['profile_id'], $v152['aac']['header']['mpeg_version']);
$v152['aac']['header']['sample_frequency_index'] = f11(substr($v1, $v195, 4));
$v195 += 4;
$v152['aac']['header']['sample_frequency']       = f2($v152['aac']['header']['sample_frequency_index']);
if ($v152['aac']['header']['sample_frequency'] == 0) {
$v152['error'] .= "\n".'Corrupt AAC file: sample_frequency == zero';
return false;
}
$v152['audio']['sample_rate']                    = $v152['aac']['header']['sample_frequency'];
$v152['aac']['header']['private']                = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
$v152['aac']['header']['channel_configuration']  = f11(substr($v1, $v195, 3));
$v195 += 3;
$v152['audio']['channels']                       = $v152['aac']['header']['channel_configuration'];
$v152['aac']['header']['original']               = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
$v152['aac']['header']['home']                   = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
if ($v152['aac']['header']['mpeg_version'] == 4) {
$v152['aac']['header']['emphasis']           = f11(substr($v1, $v195, 2));
$v195 += 2;
}
if ($v141) {
$v152['aac'][$v330]['copyright_id_bit']   = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
$v152['aac'][$v330]['copyright_id_start'] = (bool) f11(substr($v1, $v195, 1));
$v195 += 1;
} else {
$v195 += 2;
}
}
$v66 = bindec(substr($v1, $v195, 13));
if (!isset($v43[$v66])) {
$v43[$v66] = ($v152['aac']['header']['sample_frequency'] / 1024) * $v66 * 8;
}
f143($v152['aac']['bitrate_distribution'][$v43[$v66]]);
$v152['aac'][$v330]['aac_frame_length']     = $v66;
$v195 += 13;
$v152['aac'][$v330]['adts_buffer_fullness'] = bindec(substr($v1, $v195, 11));
$v195 += 11;
if ($v152['aac'][$v330]['adts_buffer_fullness'] == 0x07FF) {
$v152['audio']['bitrate_mode'] = 'vbr';
} else {
$v152['audio']['bitrate_mode'] = 'cbr';
}
$v152['aac'][$v330]['num_raw_data_blocks']  = bindec(substr($v1, $v195, 2));
$v195 += 2;
if ($v152['aac']['header']['crc_present']) {
$v195 += 16;
}
if (!$v141) {
unset($v152['aac'][$v330]);
}
$v205 += $v66;
if ((++$v330 < $v124) && (($v205 + 10) < $v152['avdataend'])) {
} else {
$v152['aac']['frames']    = $v330;
$v152['playtime_seconds'] = ($v152['avdataend'] / $v205) * (($v330 * 1024) / $v152['aac']['header']['sample_frequency']);  
if ($v152['playtime_seconds'] == 0) {
$v152['error'] .= "\n".'Corrupt AAC file: playtime_seconds == zero';
return false;
}
$v152['audio']['bitrate']    = (($v152['avdataend'] - $v152['avdataoffset']) * 8) / $v152['playtime_seconds'];
ksort($v152['aac']['bitrate_distribution']);
return true;
}
}
}
function f2($v467) {
static $v3 = array();
if (empty($v3)) {
$v3[0]  = 96000;
$v3[1]  = 88200;
$v3[2]  = 64000;
$v3[3]  = 48000;
$v3[4]  = 44100;
$v3[5]  = 32000;
$v3[6]  = 24000;
$v3[7]  = 22050;
$v3[8]  = 16000;
$v3[9]  = 12000;
$v3[10] = 11025;
$v3[11] = 8000;
$v3[12] = 0;
$v3[13] = 0;
$v3[14] = 0;
$v3[15] = 0;
}
return (isset($v3[$v467]) ? $v3[$v467] : 'invalid');
}
function f1($v442, $v402) {
static $v2 = array();
if (empty($v2)) {
$v2[2][0]  = 'Main profile';
$v2[2][1]  = 'Low Complexity profile (LC)';
$v2[2][2]  = 'Scalable Sample Rate profile (SSR)';
$v2[2][3]  = '(reserved)';
$v2[4][0]  = 'AAC_MAIN';
$v2[4][1]  = 'AAC_LC';
$v2[4][2]  = 'AAC_SSR';
$v2[4][3]  = 'AAC_LTP';
}
return (isset($v2[$v402][$v442]) ? $v2[$v402][$v442] : 'invalid');
}
function f0($v443) {
$v212 = 0;
for ($i = 0; $i < $v443['num_front_channel_elements']; $i++) {
$v212++;
if ($v443['front_element_is_cpe'][$i]) {
$v212++;
}
}
for ($i = 0; $i < $v443['num_side_channel_elements']; $i++) {
$v212++;
if ($v443['side_element_is_cpe'][$i]) {
$v212++;
}
}
for ($i = 0; $i < $v443['num_back_channel_elements']; $i++) {
$v212++;
if ($v443['back_element_is_cpe'][$i]) {
$v212++;
}
}
for ($i = 0; $i < $v443['num_lfe_channel_elements']; $i++) {
$v212++;
}
return $v212;
}
function f6() {
static $v86 = array();
if (empty($v86)) {
$v86[0]    = 'Blues';
$v86[1]    = 'Classic Rock';
$v86[2]    = 'Country';
$v86[3]    = 'Dance';
$v86[4]    = 'Disco';
$v86[5]    = 'Funk';
$v86[6]    = 'Grunge';
$v86[7]    = 'Hip-Hop';
$v86[8]    = 'Jazz';
$v86[9]    = 'Metal';
$v86[10]   = 'New Age';
$v86[11]   = 'Oldies';
$v86[12]   = 'Other';
$v86[13]   = 'Pop';
$v86[14]   = 'R&B';
$v86[15]   = 'Rap';
$v86[16]   = 'Reggae';
$v86[17]   = 'Rock';
$v86[18]   = 'Techno';
$v86[19]   = 'Industrial';
$v86[20]   = 'Alternative';
$v86[21]   = 'Ska';
$v86[22]   = 'Death Metal';
$v86[23]   = 'Pranks';
$v86[24]   = 'Soundtrack';
$v86[25]   = 'Euro-Techno';
$v86[26]   = 'Ambient';
$v86[27]   = 'Trip-Hop';
$v86[28]   = 'Vocal';
$v86[29]   = 'Jazz+Funk';
$v86[30]   = 'Fusion';
$v86[31]   = 'Trance';
$v86[32]   = 'Classical';
$v86[33]   = 'Instrumental';
$v86[34]   = 'Acid';
$v86[35]   = 'House';
$v86[36]   = 'Game';
$v86[37]   = 'Sound Clip';
$v86[38]   = 'Gospel';
$v86[39]   = 'Noise';
$v86[40]   = 'Alt. Rock';
$v86[41]   = 'Bass';
$v86[42]   = 'Soul';
$v86[43]   = 'Punk';
$v86[44]   = 'Space';
$v86[45]   = 'Meditative';
$v86[46]   = 'Instrumental Pop';
$v86[47]   = 'Instrumental Rock';
$v86[48]   = 'Ethnic';
$v86[49]   = 'Gothic';
$v86[50]   = 'Darkwave';
$v86[51]   = 'Techno-Industrial';
$v86[52]   = 'Electronic';
$v86[53]   = 'Folk/Pop';
$v86[54]   = 'Eurodance';
$v86[55]   = 'Dream';
$v86[56]   = 'Southern Rock';
$v86[57]   = 'Comedy';
$v86[58]   = 'Cult';
$v86[59]   = 'Gangsta';
$v86[60]   = 'Top 40';
$v86[61]   = 'Christian Rap';
$v86[62]   = 'Pop/Funk';
$v86[63]   = 'Jungle';
$v86[64]   = 'Native American';
$v86[65]   = 'Cabaret';
$v86[66]   = 'New Wave';
$v86[67]   = 'Psychadelic';
$v86[68]   = 'Rave';
$v86[69]   = 'Showtunes';
$v86[70]   = 'Trailer';
$v86[71]   = 'Lo-Fi';
$v86[72]   = 'Tribal';
$v86[73]   = 'Acid Punk';
$v86[74]   = 'Acid Jazz';
$v86[75]   = 'Polka';
$v86[76]   = 'Retro';
$v86[77]   = 'Musical';
$v86[78]   = 'Rock & Roll';
$v86[79]   = 'Hard Rock';
$v86[80]   = 'Folk';
$v86[81]   = 'Folk/Rock';
$v86[82]   = 'National Folk';
$v86[83]   = 'Swing';
$v86[84]   = 'Fast-Fusion';
$v86[85]   = 'Bebob';
$v86[86]   = 'Latin';
$v86[87]   = 'Revival';
$v86[88]   = 'Celtic';
$v86[89]   = 'Bluegrass';
$v86[90]   = 'Avantgarde';
$v86[91]   = 'Gothic Rock';
$v86[92]   = 'Progressive Rock';
$v86[93]   = 'Psychedelic Rock';
$v86[94]   = 'Symphonic Rock';
$v86[95]   = 'Slow Rock';
$v86[96]   = 'Big Band';
$v86[97]   = 'Chorus';
$v86[98]   = 'Easy Listening';
$v86[99]   = 'Acoustic';
$v86[100]  = 'Humour';
$v86[101]  = 'Speech';
$v86[102]  = 'Chanson';
$v86[103]  = 'Opera';
$v86[104]  = 'Chamber Music';
$v86[105]  = 'Sonata';
$v86[106]  = 'Symphony';
$v86[107]  = 'Booty Bass';
$v86[108]  = 'Primus';
$v86[109]  = 'Porn Groove';
$v86[110]  = 'Satire';
$v86[111]  = 'Slow Jam';
$v86[112]  = 'Club';
$v86[113]  = 'Tango';
$v86[114]  = 'Samba';
$v86[115]  = 'Folklore';
$v86[116]  = 'Ballad';
$v86[117]  = 'Power Ballad';
$v86[118]  = 'Rhythmic Soul';
$v86[119]  = 'Freestyle';
$v86[120]  = 'Duet';
$v86[121]  = 'Punk Rock';
$v86[122]  = 'Drum Solo';
$v86[123]  = 'A Cappella';
$v86[124]  = 'Euro-House';
$v86[125]  = 'Dance Hall';
$v86[126]  = 'Goa';
$v86[127]  = 'Drum & Bass';
$v86[128]  = 'Club-House';
$v86[129]  = 'Hardcore';
$v86[130]  = 'Terror';
$v86[131]  = 'Indie';
$v86[132]  = 'BritPop';
$v86[133]  = 'Negerpunk';
$v86[134]  = 'Polsk Punk';
$v86[135]  = 'Beat';
$v86[136]  = 'Christian Gangsta Rap';
$v86[137]  = 'Heavy Metal';
$v86[138]  = 'Black Metal';
$v86[139]  = 'Crossover';
$v86[140]  = 'Contemporary Christian';
$v86[141]  = 'Christian Rock';
$v86[142]  = 'Merengue';
$v86[143]  = 'Salsa';
$v86[144]  = 'Trash Metal';
$v86[145]  = 'Anime';
$v86[146]  = 'Jpop';
$v86[147]  = 'Synthpop';
$v86[255]  = 'Unknown';
$v86['CR'] = 'Cover';
$v86['RX'] = 'Remix';
}
return $v86;
}
function f76($v332, $v459=false) {
if (($v332 != 'RX') && ($v332 === 'CR')) {
$v332 = (int) $v332; 
}
$v86 = f6();
if ($v459) {
$v100 = strtolower(str_replace(' ', '', $v332));
foreach ($v86 as $key => $v531) {
if (strtolower(str_replace(' ', '', $v531)) == $v100) {
return $key;
}
}
return '';
} else {
return (isset($v86[$v332]) ? $v86[$v332] : '');
}
}
function f124($v507){
global $v165, $v480;
$v217 = urldecode($v507);
$v362 = f53($v165['path']['physical'].$v480.$v217);
$v461 = array();
if($v362['exist']){
$v461[0]=@$v362['comments']['artist'][0];
$v461[1]=@$v362['comments']['album'][0];
$v461[2]=@$v362['comments']['title'][0];
$v461[3]=@$v362['comments']['track'][0];
$v461[4]=@$v362['comments']['comments'][0];
$v461[5]=@$v362['comments']['genre'][0];
$v401 = f49($v165['path']['physical'].$v480.$v217, "mp3", false, false, false);
$v404 = array();
$v461[6]=@$v401['playtime_seconds'];
$v461[7]=@round($v401['filesize']/1000000, 1);
$v461[8]=@floor($v401['bitrate']/1000);
} else {
return 0;
break;
}
return $v461;
}
function f148($v429){
global $v165;
if ( isset($_ENV['OS']) && preg_match('/window/i', $_ENV['OS']) ){
$v429 = $v165['path']['physical']."\\".preg_replace('/\//', '\\', $v429);
echo "$v429<p>";
}
return $v429;
}
function f51($v465){
global $v165,$v471,$v480,$v430,$ts,$hide_folders,$media_types,$v398,$myDataSetup;
if($v465 == $v165['path']['physical']){
$v466 = true;
} else {
$v466 = false;
}
$v335=opendir($v465);
$v23 = array ();
$v27 = array ();
$v30 = array ();
$v29 = array();
$v29 = explode(",",$hide_folders);
$v31 = explode(",",$media_types);
$v236 = 0;
$v186 = $v165['path']['www'];
while (false !== ($v260 = readdir($v335))){
$v262 = cleanForFlash($v260);
$ext=explode('.',$v260);
if($v260 != '.' && $v260 != '..' && @sizeof($ext)>1 && in_array($ext[strtolower(sizeof($ext)-1)],$v31)){
if($ts[0]){
echo "$v260<br>";
}
$v27[count($v27)]=cleanForFlash($v260);
} else {
if($v260 != '.' && $v260 != '..'){
if(!in_array($v260,$v29)){
if (false !== ($v214 = @opendir($v465.$v480.$v260))){
if($ts[0]){
echo "$v260<br>";
}
$v23[count($v23)] = cleanForFlash($v260);
}
@closedir($v465.$v480.$v260);
}
}
}
}
closedir($v335); 
natcasesort($v23);
natcasesort($v27);
$v24 = array_values($v23);
$v28 = array_values($v27);
for($i=0;$i<sizeof($v24);$i++){
$v497 = $v24[$i];
if($v466){
$v24[$i]="$v497"."|d|||";
} else {
$v24[$i]="$v465/$v497"."|d|||";
}
$v236++;
}
for($i=0;$i<sizeof($v28);$i++){
$v497 = $v28[$i];
if($v466){
$v28[$i]=$v165['path']['www']."/$v497|".f138 ($v497, "full");
} else {
$v28[$i]=$v165['path']['www']."/$v465/$v497|".f138 ($v465.$v480.$v497, "full");
}
}
if($v398 == "mysql"){
if(sizeof($v28)){
for($i=0;$i<sizeof($v28); $i++){
array_push ($v30, f116($v28[$i]));
}
}
return $v30;
} else {
if(sizeof($v24)){
for($i=0;$i<sizeof($v24); $i++){
array_push ($v30, $v24[$i]);
}
}
if(sizeof($v28)){
for($i=0;$i<sizeof($v28); $i++){
array_push ($v30, $v28[$i]);
}
}
for($i=0;$i<sizeof($v30);$i++){
$v471 .= "&item".$i."=".f116($v30[$i]);
}
$v520 = sizeof ($v30);
$v471 .= "&totalitems=$v520";
$v471 .= "&datasetup=$myDataSetup";
if($ts[0]){
echo "<br><br>$v471";
exit;
}
return $v471;
}
}
function cleanForFlash($v511){
$v461 = $v511;
$v461 = str_replace(" ", "%20", $v461);
$v461 = str_replace("+", "%2B", $v461);
$v461 = str_replace("'", "%27", $v461);
$v461 = str_replace('"', "%22", $v461);
$v461 = str_replace("", "", $v461);
$v461 = preg_replace("/(\r\n|\n|\r)/", "", $v461);
return $v461;
}
function f116($v511){
$v461 = $v511;
$v461 = str_replace("&", "%26", $v461);
$v461 = str_replace(" ", "%20", $v461);
$v461 = str_replace('?', "%3F", $v461);
return $v461;
}
function f138($v507, $v523){
global $v165,$v480,$v178,$ts,$getMyid3info;
if ($getMyid3info=="yes"){
$v461 = f124($v507);
} else {
$v461 = array();
$v461[0]="";
$v461[1]="";
$v461[2]="";
$v461[3]="";
$v461[4]="";
$v461[5]="";
$v461[6]="";
$v461[7]="";
$v461[8]="";
}
for($i=0;$i<sizeof($v461);$i++){
$v461[$i] = quoted_printable_decode ($v461[$i]);
}
$v35 = explode($v480,$v507);
$v504 = $v35[sizeof($v35)-1];
$v37=explode('.',$v504);
$v505 = $v37[sizeof($v37)-2];
if($v461[0]=="" || $v461[0]==null){
$v461[0] = $v505;
}
if($v461[2]=="" || $v461[2]==null){
$v461[2] = $v505;
}
return (implode ("|", $v461));
}
function f149($v506, $v503, $v427='w+'){
global $v165;
if($v503=="" || is_null($v503)){
$v247 = " ";
} else {
$v247 = $v503;
}
$v461 = TRUE;
if (!$fp = fopen($v506, $v427)) {
$v461 = FALSE;
}
if($v461){
if (!$v266 = fwrite($fp, stripslashes($v247))) {
$v461 = FALSE;
exit;
} else {
$v461 = TRUE;
}
}
@fclose($fp);
return $v461;
@chmod ($v506, 0777);
}
function f117($v424, $v409, $v503){
copy($v424, $v409) && chmod($v409, 0777);
return f149($v409, $v503, $v427='w+');
}
function f136($v263, $v541){
global $v165, $v480, $playlisterOutputDirName;
$wimpySWFfilename = $v165['path']['www']."/".$_REQUEST['wimpySWFfilename'];
$background_color = $_REQUEST['background_color'];
$v433 = $_REQUEST['playerSize_value'];
$v435 = $_REQUEST['playerW'];
$v432 = $_REQUEST['playerH'];
if($v433 == "percent"){
$v435 = $v435."%";
$v432 = $v432."%";
}
$v444 = "";
$v444 .= "wimpyApp=".$v165['path']['www']."/".$v541."&";
$v444 .= "background_color=".$background_color."&";
$v444 .= "wimpyHTMLpageTitle=".$_REQUEST['wimpyHTMLpageTitle']."&";
$v444 .= "displayDownloadButton=".$_REQUEST['displayDownloadButton']."&";
$v444 .= "infoDisplayTime=".$_REQUEST['infoDisplayTime']."&";
$v444 .= "defaultPlayRandom=".$_REQUEST['defaultPlayRandom']."&";
$v444 .= "startPlayingOnload=".$_REQUEST['startPlayingOnload']."&";
$v444 .= "popUpHelp=".$_REQUEST['popUpHelp'];
$v87 = '<HTML>'."\n";
$v87 .= '<HEAD>'."\n";
$v87 .= '<meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">'."\n";
$v87 .= '<TITLE>'.$v541.'</TITLE>'."\n";
$v87 .= '</HEAD>'."\n";
$v87 .= '<BODY bgcolor="#'.$background_color.'" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">'."\n";
$v87 .= '<!-- Wimpy Player Code -->'."\n";
$v87 .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,47,0" width="'.$v435.'" height="'.$v432.'">'."\n";
$v87 .= '<param name="movie" value="'.$wimpySWFfilename.'?'.$v444.'">'."\n";
$v87 .= '<param name="quality" value="high">'."\n";
$v87 .= '<param name="bgcolor" value=#'.$background_color.'>'."\n";
$v87 .= '<embed src="'.$wimpySWFfilename.'?'.$v444.'" width="'.$v435.'" height="'.$v432.'" quality="high" bgcolor=#'.$background_color.' pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed></object>'."\n";
$v87 .= '<!-- End Wimpy Player Code -->'."\n";
$v87 .= '</BODY>'."\n";
$v87 .= '</HTML>'."\n";
$v469 = f149($v165['path']['physical'].$v480.$v263, $v87, 'w+');
return $v469;
}
$v21 = array(
"action", 
"theFile", 
"filename",
"dir",
"getMyid3info",
"useMysql",
"queryValue",
"queryWhere"
);
for($i=0;$i<sizeof($v21);$i++){
$var = $v21[$i];
if(!isset($_REQUEST[$var])){
if(!isset($$var)){
$$var = "";
}
} else {
$$var = $_REQUEST[$var];
}
}
if($useMysql=="yes"){
$action = "getmysql";
}
function f137($v263){
global $v165, $v480, $playlisterOutputDirName,$_REQUEST;
$v30 = explode("^", $_REQUEST['items']);
$v22 = explode("|", $_REQUEST['datasetup']);
$v543 = "";
$v26 = array();
array_push($v26, '<'.urldecode("%3F").'xml version="1.0"'.urldecode("%3F").'>');
array_push($v26, '<playlist>');
for ($i=0; $i<sizeof($v30); $i++) {
array_push($v26, "\t".'<item>');
$v25 = explode("|", $v30[$i]);
for ($j=0; $j<sizeof($v22); $j++) {
$v224 = str_replace(" ", "%20", $v25[$j]);
$v224 = str_replace("'", "%27", $v224);
$v224 = str_replace('"', "%22", $v224);
$v224 = str_replace('#', "%23", $v224);
array_push($v26, "\t"."\t".'<'.$v22[$j].'>'.$v224.'</'.$v22[$j].'>');
}
array_push($v26, "\t".'</item>');
}
array_push($v26, '</playlist>');
$v468 = implode("\r\n", $v26);
$v469 = f149 ($v165['path']['physical'].$v480.$v263, $v468, 'w+');

return $v469;
}
if($action=="getmysql"){
require ("wimpy_mysql_get.php");
exit;
} else if($action == "updateMySQL"){
$v398 = "mysql";
$getMyid3info = "yes";
$Asendback = f51($v165['path']['physical']);
require ("wimpy_mysql_update.php");
exit;
} else if($action == "makeplaylist"){
$v39 = explode(".", urldecode($_REQUEST['destination']));
$v436 = $v165['path']['physical'].$v480.$playlisterOutputDirName;
if (!is_dir($playlisterOutputDirName)){
if (!$v393 = @mkdir($v436, 0755)) {
$v168 = 0;
$v461 = "&retval=error&filename=$v436";
} else {
$v168 = 1;
}
} else {
$v168 = 1;
}
if($v168 == 1){
$v509 = $v39[0].".xml";
$v508 = $v39[0].".html";
$v461 = "ok";
if($v406 = f137($playlisterOutputDirName.$v480.$v509)){
$v461 = "ok";
} else {
$v461 = "error";
}
if($v461 == "ok"){
if($v403 = f136($playlisterOutputDirName.$v480.$v508, $playlisterOutputDirName."/".$v509)){
$v461 = "&retval=ok&filename=".$v165['path']['www']."/".$playlisterOutputDirName."/".$v508;
} else {
$v461 = "&retval=error&filename=$v508";
}
} else {
$v461 = "&retval=error&filename=$v508";
}
}
print $v461;
exit;
} else if ($action=="downloadfile"){
$v36 = explode ("/", $theFile);
$v506 = array_pop($v36);
$v40 = explode ("/", $v165['path']['www']);
$v38 = array_values (array_diff ($v36, $v40));
if($v38){
$v502 = $v165['path']['physical'].$v480.implode($v480, $v38).$v480.$v506;
} else {
$v502 = $v165['path']['physical'].implode($v480, $v38).$v480.$v506;
}
header("Pragma: public");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: private");
header("Content-Type: audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
if ((is_integer (strpos($v528, "msie"))) && (is_integer (strpos($v528, "win")))) {
   header( "Content-Disposition: attachment; filename=".basename($v502).";" );
} else {
   header( "Content-Disposition: attachment; filename=".basename($v502).";" );
}
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($v502));
readfile("$filename");
exit;
} else if($action=="getstartupdirlist"){
$v471 = f51($v165['path']['physical']);
echo (cleanForFlash($v471));
exit;
} else if ($action=="dir"){
$v471 = f51(stripslashes($dir));
echo (cleanForFlash ($v471));
exit;
} else if ($action == "info"){
$v510 = f138($theFile, "full");
echo (cleanForFlash($v471));
exit;
} else if ($action=="phpinfo"){
$v461 = phpinfo();
echo "$v461";
exit;
} else {
$v444 = "";
$v444 .= "wimpyApp=".$v405."&";
$v444 .= "background_color=".$background_color."&";
$v444 .= "displayDownloadButton=".$displayDownloadButton."&";
$v444 .= "infoDisplayTime=".$infoDisplayTime."&";
$v444 .= "defaultPlayRandom=".$defaultPlayRandom."&";
$v444 .= "startPlayingOnload=".$startPlayingOnload."&";
$v444 .= "popUpHelp=".$popUpHelp;
$getMyid3info .= "getMyid3info=".$getMyid3info;
$currentVolume .= "currentVolume=".$currentVolume;
if($randomPlayback == "yes"){
$currentVolume .= "randomPlayback=".$randomPlayback; 
}
$wimpySWFfilename = $wimpySWFfilename."?".$v444;
$v471 .= '<HTML>'."\n";
$v471 .= '<HEAD>'."\n";
$v471 .= '<meta http-equiv=Content-Type content="text/html; charset=iso-8859-1">'."\n";
$v471 .= '<TITLE>'.$wimpyHTMLpageTitle.'</TITLE>'."\n";
$v471 .= '</HEAD>'."\n";
$v471 .= '<BODY bgcolor=#'.$background_color.' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">'."\n";
$v471 .= '<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'."\n";
$v471 .= ' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,47,0"'."\n";
$v471 .= ' WIDTH="100%" HEIGHT="100%" id="wimpy" ALIGN="center">'."\n";
$v471 .= ' <PARAM NAME=movie VALUE="'.$wimpySWFfilename.'">'."\n";
$v471 .= ' <PARAM NAME=quality VALUE=high>'."\n";
$v471 .= ' <PARAM NAME=bgcolor VALUE=#'.$background_color.'>'."\n";
$v471 .= ' <EMBED src="'.$wimpySWFfilename.'" quality=high bgcolor=#'.$background_color.'  WIDTH="100%" HEIGHT="100%" id="wimpy" ALIGN="center" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>'."\n";
$v471 .= '</OBJECT>'."\n";
$v471 .= '</BODY>'."\n";
$v471 .= '</HTML>'."\n";
echo ($v471);
}
?>
