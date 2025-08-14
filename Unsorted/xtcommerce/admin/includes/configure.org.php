<?php
/* --------------------------------------------------------------
  ### Be careful, this is the backup of your original configuration data ###

  XT-Commerce - community made shopping
  http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
  --------------------------------------------------------------
  based on:
  (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(configure.php,v 1.14 2003/02/21); www.oscommerce.com

  Released under the GNU General Public License
  --------------------------------------------------------------*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://localhost'); // eg, http://localhost or - https://localhost should not be empty for productive servers
  define('HTTP_CATALOG_SERVER', 'http://localhost');
  define('HTTPS_CATALOG_SERVER', 'https://localhost');
  define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT', ''); // where the pages are located on the server
  define('DIR_WS_ADMIN', ''); // absolute path required
  define('DIR_FS_ADMIN', ''); // absolute pate required
  define('DIR_WS_CATALOG', ''); // absolute path required
  define('DIR_FS_CATALOG', ''); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_ORIGINAL_IMAGES', DIR_FS_CATALOG_IMAGES .'original/');
  define('DIR_FS_CATALOG_THUMBNAILS', DIR_FS_CATALOG_IMAGES .'thumbnails/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_FS_CATALOG. 'lang/admin/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'lang/catalog/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'lang/catalog/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
  define('CURRENT_TEMPLATE','xtc');

// define our database connection
  define('DB_SERVER', ''); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', '');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', '');
  define('USE_PCONNECT', 'false'); // use persisstent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'

?>