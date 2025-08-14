<?php

	global $_WAIT; $_WAIT = 1;

	echo '
	<script language=javascript>
	var ie4 = (document.all) ? true : false;
	var ns4 = (document.layers) ? true : false;
	var ns6 = (document.getElementById && !document.all) ? true : false;

	function hidelayer(lay) {
		if (ie4) {document.all[lay].style.visibility = "hidden";}
		if (ns4) {document.layers[lay].visibility = "hide";}
		if (ns6) {document.getElementById([lay]).style.display = "none";}
	}
	function showlayer(lay) {
		if (ie4) {document.all[lay].style.visibility = "visible";}
		if (ns4) {document.layers[lay].visibility = "show";}
		if (ns6) {document.getElementById([lay]).style.display = "block";}
	}
	</script>';

	echo '
	<script language="javascript">
	var laywidth  = screen.width/2;
	var layheight = screen.height/2;
	var layl   = (screen.width-laywidth)/2;
  	var layt   = (screen.height-layheight)/2;
	document.write("<div id=\'waitlayer\' align=\'center\' style=\'position:absolute; width:"+laywidth+"px; height:"+layheight+"px; z-index:-1; left:"+layl+"px; top:"+layt+"px; visibility: visible;\'>");
	</script>';

	echo '<center><b>'.'Please Wait'.'</b><br><br>
	<a href="mod.php?mod=whois" target="_self">'.'Click here to return if delay more than a minute'.'</a>
	</div>'."\r\n\r\n";

?>