<?php
require("../script_ext.inc");
require("admin_global" . $script_ext);

?>

<html>
<head>
<title>TriviaShock <?php echo TS_VERSION;?> - Control Panel</title>

<frameset cols="175,*"  framespacing=0 border=0 frameborder=0 frameborder=no border=0>
<frame src="<?php echo "$TS_SCRIPTS[MAIN]?fn=nav";?>" name="nav" scrolling=auto frameborder=0 marginwidth=0 marginheight=0 border=no noresize>

<frameset rows="15, *, 15" framespacing=0 border=0 frameborder=0 frameborder=no border=0>
<frame src="<?php echo "$TS_SCRIPTS[MAIN]?fn=top";?>" name="main1" scrolling=no frameborder=0 marginwidth=10 marginheight=10 border=no noresize>
<frame src="<?php echo "$TS_SCRIPTS[MAIN]?fn=index";?>" name="main" scrolling=auto frameborder=0 marginwidth=10 marginheight=10 border=no noresize>
<frame src="<?php echo "$TS_SCRIPTS[MAIN]?fn=bottom";?>" name="main2" scrolling=no frameborder=0 marginwidth=10 marginheight=10 border=no noresize>
</frameset>

</frameset>

</head>
</html>
