<a href="index.php">Summary</a><br><br>
<a href="index.php?page=pages">Page Rankings</a><br><br>
<a href="index.php?page=averages">Averages</a><br><br>
<a href="index.php?page=latest">Latest Visitors</a><br><br>
<a href="index.php?page=browsers">Browsers and OS</a><br><br>
<a href="index.php?page=search">Search Engines</a><br><br>
<a href="index.php?page=reffer">Refferers</a><br><br>
<a href="index.php?page=trends">Trends</a><br><br>
<?if(preg_match('/trends/', $_GET['page'])){?>
<img src="shim.gif" width="15" height="5"><a href="index.php?page=trends">Today</a><br><br>
<img src="shim.gif" width="15" height="5"><a href="index.php?page=trends_month">This Month</a><br><br>
<img src="shim.gif" width="15" height="5"><a href="index.php?page=trends_year">This Year</a><br><br>
<img src="shim.gif" width="15" height="5"><a href="index.php?page=trends_all">All Years</a><br><br>
<?}?>
<a href="http://www.matt-toigo.com/mec/index.php?s=support">Support</a><br><br>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
</form>
