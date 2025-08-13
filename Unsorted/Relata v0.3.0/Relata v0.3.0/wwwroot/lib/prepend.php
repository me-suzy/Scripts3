<?
//******************************************************************************
/*
GPL Copyright Notice

Relata
Copyright (C) 2001-2002 Stratabase, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/
//******************************************************************************

$_PHPLIB["libdir"] = $_PHPLIB["basedir"] . "lib/phplib/";

require($_PHPLIB["basedir"] . "lib/global.inc.php");
require($_PHPLIB["libdir"] . "db_" . $db_type .".inc.php");  /* Change this to match your database. */
require($_PHPLIB["libdir"] . "ct_sql.inc.php");    /* Change this to match your data storage container */
require($_PHPLIB["libdir"] . "session.inc.php");   /* Required for everything below.      */
require($_PHPLIB["libdir"] . "auth.inc.php");      /* Disable this, if you are not using authentication. */

//require($_PHPLIB["libdir"] . "perm.inc.php");      /* Disable this, if you are not using permission checks. */
//require($_PHPLIB["libdir"] . "user.inc.php");      /* Disable this, if you are not using per-user variables. */

/* Additional require statements go below this line */

require($_PHPLIB["libdir"] . "template.inc.php");

/* Additional require statements go before this line */

require($_PHPLIB["libdir"] . "local.inc.php");     /* Required, contains your local configuration. */
require($_PHPLIB["libdir"] . "page.inc.php");      /* Required, contains the page management functions. */

?>
