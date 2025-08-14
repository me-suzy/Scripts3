<?php
// this helps
?>
<!-- Wimpy Player Code -->
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,47,0" width="450" height="185">
<param name="movie" value="wimpy.swf?wimpyApp=wimpy.php&backgroundColor=6A7A95&displayDownloadButton=yes&infoDisplayTime=3&defaultPlayRandom=yes&randomButtonState=off&startPlayingOnload=yes&popUpHelp=yes&currentVolume=100&getMyid3info=yes&useMysql=yes&<?php print ("queryWhere=".$_REQUEST['queryWhere']."&queryValue=".$_REQUEST['queryValue']); ?>">
<param name="quality" value="high">
<param name="bgcolor" value=#6A7A95>
<embed src="wimpy.swf?wimpyApp=wimpy.php&backgroundColor=6A7A95&displayDownloadButton=yes&infoDisplayTime=3&defaultPlayRandom=yes&randomButtonState=off&startPlayingOnload=yes&popUpHelp=yes&currentVolume=100&getMyid3info=yes&useMysql=yes&<?php print ("queryWhere=".$_REQUEST['queryWhere']."&queryValue=".$_REQUEST['queryValue']); ?>" width="450" height="185" quality="high" bgcolor=#6A7A95 pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed></object>
<!-- End Wimpy Player Code -->