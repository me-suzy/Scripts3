<?php
include "var_user.php";

if($rd == "10") {
print('<HTML><HEAD></HEAD>
<BODY ONLOAD="document.stats1.submit()" STYLE="background:#EEEEEE;font:11px arial">
<H3 ALIGN=CENTER>Loading...</H3>
<FORM NAME="stats1" ACTION="stats.php" METHOD="post">
<INPUT TYPE="hidden" NAME="row" VALUE="'.$row.'">
<INPUT TYPE="hidden" NAME="rd" VALUE="'.$rd.'">
<INPUT TYPE="hidden" NAME="pass" VALUE="'.$pw.'">
</FORM>
</BODY></HTML>');
exit;
}
else if($rd == "20") {
print('<HTML><HEAD></HEAD>
<BODY ONLOAD="document.stats2.submit()" STYLE="background:#EEEEEE;font:11px arial">
<H3 ALIGN=CENTER>Loading...</H3>
<FORM NAME="stats2" ACTION="stats.php" METHOD="post">
<INPUT TYPE="hidden" NAME="visitor" VALUE="detail">
<INPUT TYPE="hidden" NAME="row" VALUE="'.$row.'">
<INPUT TYPE="hidden" NAME="rd" VALUE="'.$rd.'">
<INPUT TYPE="hidden" NAME="pass" VALUE="'.$pw.'">
</FORM>
</BODY></HTML>');
exit;
}
else if($rd == "30") {
print('<HTML><HEAD></HEAD>
<BODY ONLOAD="document.pages.submit()" STYLE="background:#EEEEEE;font:11px arial">
<H3 ALIGN=CENTER>Loading...</H3>
<FORM NAME="pages" ACTION="pages.php" METHOD="post">
<INPUT TYPE="hidden" NAME="pass" VALUE="'.$pw.'">
</FORM>
</BODY></HTML>');
exit;
}
else if($rd == "40") {
print('<HTML><HEAD></HEAD>
<BODY ONLOAD="document.referral.submit()" STYLE="background:#EEEEEE;font:11px arial">
<H3 ALIGN=CENTER>Loading...</H3>
<FORM NAME="referral" ACTION="referral.php" METHOD="post">
<INPUT TYPE="hidden" NAME="pass" VALUE="'.$pw.'">
</FORM>
</TD></TR></TABLE></BODY></HTML>');
exit;
}
else if($rd == "50") {
print('<HTML><HEAD></HEAD>
<BODY ONLOAD="document.referral_search.submit()" STYLE="background:#EEEEEE;font:11px arial">
<H3 ALIGN=CENTER>Loading...</H3>
<FORM NAME="referral_search" ACTION="referral.php" METHOD="post">
<INPUT TYPE="hidden" NAME="pass" VALUE="'.$pw.'">
<INPUT TYPE="hidden" NAME="keyword" VALUE="'.$keyword.'">
</FORM>
</BODY></HTML>');
exit;
}

?>