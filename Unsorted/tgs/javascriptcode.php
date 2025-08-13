<?php
session_start();
ob_start();
include("includes/header.php");
include("includes/messages.php");
include("includes/validate_member.php");
ini_set("include_path", "./");
?>
<p align="center"><span class="error"><b>Javascript Code</b></span></p>
<p align="left"><b>Usage simply cut and paste the code shown below on to your web pages.</b></p>
<p align="left">
  &lt;SCRIPT LANGUAGE=&quot;JavaScript&quot;&gt;jsver = &quot;1.0&quot;;&lt;/SCRIPT&gt;
  &lt;SCRIPT LANGUAGE=&quot;JavaScript1.1&quot;&gt;jsver = &quot;1.1&quot;;&lt;/SCRIPT&gt;&lt;SCRIPT
  Language=&quot;JavaScript1.2&quot;&gt;jsver = &quot;1.2&quot;;&lt;/SCRIPT&gt;
  &lt;SCRIPT Language=&quot;JavaScript1.3&quot;&gt;jsver = &quot;1.3&quot;;&lt;/SCRIPT&gt;&lt;SCRIPT
  Language=&quot;JavaScript1.4&quot;&gt;jsver = &quot;1.4&quot;;&lt;/SCRIPT&gt;
  &lt;script language=&quot;javascript&quot;&gt; data = &quot;agent=&quot; + escape(navigator.userAgent) + &quot;&appname=&quot; + escape(navigator.appName) + &quot;&language=&quot; +
escape(navigator.systemLanguage) + &quot;&os=&quot; + escape(navigator.platform) + &quot;&appversion=&quot; +
escape(navigator.appVersion) + &quot;&referrer=&quot; + escape(document.referrer) + &quot;&site=<?=$site?>&quot; + &quot;&jsver=&quot; + escape(jsver);
if ((navigator.appVersion).substring(0,1)&gt;'3') { data = data + &quot;&extra=1&colordepth=&quot; + escape(screen.colorDepth) + &quot;&screenwidth=&quot; + escape(screen.width) + &quot;&screenheight=&quot; + escape(screen.height) + &quot;&javaenabled=&quot; + escape(navigator.javaEnabled()); } document.write(&quot;&lt;a href='<?=$script_url?>/totalVisitors.php?site=<?=$site?>'&gt;&lt;img
  src='<?=$script_url?>/logger.php?&quot;+data+&quot;' width=0; height=0; border=0&gt;&lt;/a&gt;&quot;); &lt;/script&gt;
</p>
<?php
include("includes/footer.php");
?>