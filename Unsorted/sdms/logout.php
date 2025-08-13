<?php
    require('lib/config.inc');
    require('lib/session.inc');
    session_start();
    session_destroy();

    header("Location: /");
?>
