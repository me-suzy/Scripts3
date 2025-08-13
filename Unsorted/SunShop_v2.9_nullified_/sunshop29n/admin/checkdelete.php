<?PHP 
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.9
// Supplied by          : CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
if ($action == "viewtransactions") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete transaction number " + num + "?\n You may want to print this transaction before you delete it.")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } if ($action == "viewoptions") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete option number " + num + "?")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } if ($action == "viewitems") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete item number " + num + "?")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } if ($action == "viewspecials") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete special number " + num + "?")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } if ($action == "viewdiscounts") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete discount " + num + "?")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } if ($action == "viewfields") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete custom field " + num + "?")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } if ($action == "viewcoupons") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete coupon " + num + "?")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } if ($action == "viewusers") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete user number " + num + "?")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } if ($action == "viewtransaction") { ?>
	<SCRIPT Language="Javascript">
	function printit(){  
		if (NS) {
		    window.print() ;  
		} else {
		    var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
	    	document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
		    WebBrowser1.ExecWB(6, 2);//Use a 1 vs. a 2 for a prompting dialog box    WebBrowser1.outerHTML = "";  
		}
	}
	</script>
<?PHP } if ($action == "viewcat") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete category number " + num + "?\nRemember you will delete ALL items and subcategories in that category!")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } if ($action == "viewsubcat") { ?>
	<script language="JavaScript">
	<!--
	function jsconfirm(num) {
	   if (confirm("Are you sure you want to delete subcategory number " + num + "?\nRemember you will delete ALL items in that subcategory!")) {
	       return true;
	      } else {
	       return false;
	      }
	}
	// -->
	</script>
<?PHP } ?>