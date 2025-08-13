<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


error_reporting(E_ALL & ~E_NOTICE);
define('NO_REGISTER_GLOBALS', 1);
define('THIS_SCRIPT', 'adv_index');
define('VBA_PORTAL', true);

// ============================================
// Enter the full path to your forum here
// Example: /home/vbadvanced/public_html/forum
// ============================================

chdir('./forums');

// ============================================
// No Further Editing Necessary!
// ============================================

require_once('./global.php');

print_portal_output($home);

?>