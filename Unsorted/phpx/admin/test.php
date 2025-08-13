<?php
#$Id: test.php,v 1.4 2003/07/14 20:33:04 ryan Exp $
?>
<html>
<form name=previewPage action="../index.php" method=post>
<input type=hidden name=preview value=1>
<script language=javascript>
var pageId = '<input type=hidden name=id value=' + opener.doc_html.page_id.value + '>';
var fieldValue = '<input type=hidden name=fieldValue value="' + opener.doc_html.text.value + '">';

document.write(pageId);
document.write(fieldValue);

</script>
</form>

<body onLoad='document.previewPage.submit();'>

















