<?php

    require('lib/session.inc');
    session_start();

    if(!$HTTP_SESSION_VARS["login"]) {
       header("Location: index.php");
       exit;
    }

?>
