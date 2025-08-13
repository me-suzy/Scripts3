<?php
setcookie("logged_in","", time()-31536000);
Header("Location: index.php");
?>
