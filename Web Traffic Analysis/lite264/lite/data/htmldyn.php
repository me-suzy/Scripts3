<?php

$code=<<<CODE
<!-- ActualAnalyzer %%SERIES%%(%%VERSION%%) -->
<!-- YOU DO NOT NEED TO EDIT ANYTHING IN THIS HTML CODE -->
<script language="Javascript"><!--
aw=window; aatd=aw.top.document; aasd=aw.self.document;
aar=escape(aasd.referrer); aap=escape(aw.self.location.href);
if(self!=top) {aafr=escape(aw.top.location.href);if(aafr==aar) aar=escape(aatd.referrer)}
document.write("<img border=0 alt='ActualAnalyzer' src='%%URL%%aa.php?anident=%%IDENT%%&anr=" + aar + "&anp="+aap + "'>");
//--></script><noscript>
<img border=0 alt='ActualAnalyzer' src='%%URL%%aa.php?anident=%%IDENT%%'>
</noscript>
<!-- ActualAnalyzer -->
CODE;

?>