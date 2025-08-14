<?php
/*******************************************************************\
*   File Name weather.php                                           *
*   Date 15-11-2005                                                 *
*   For WAP4Joomla! WAP Site Builder                                *
*   Writen By Tony Skilton admin@media-finder.co.uk                 *
*   Version 1.1                                                     *
*   Copyright (C) 2005 Media Finder http://www.media-finder.co.uk   *
*   Distributed under the terms of the GNU General Public License   *
*   Please do not remove any of the information above               *
\*******************************************************************/
header("Content-Type: text/vnd.wap.wml");
echo"<?xml version=\"1.0\"?>"; ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN"
			"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
<? require("config.php"); ?>
<card id="weather" title="<? echo $wap_title ?>">
<do type="prev" label="Back"><prev/></do>
<p align="center"><small>weatheronline.co.uk</small></p>
<p mode="nowrap" align="left"><small>
<a href="http://wap.wetteronline.de/wap/focus?L=en&amp;S=1">Great Britain</a><br/>
<a href="http://wap.wetteronline.de/wap/feature?L=en">Outlook</a><br/>
<a href="http://wap.wetteronline.de/wap/sail?L=en">Marine Weather</a><br/>
<a href="http://wap.wetteronline.de/wap/spezial?L=en&amp;T=spezial">Special</a><br/>
<a href="http://wap.wetteronline.de/wap/welt?L=en">World Weather</a><br/>
<a href="http://wap.WetterOnline.de/wap/list?L=en&amp;S=1">more...</a><br/>
</small></p>
</card>
</wml>
