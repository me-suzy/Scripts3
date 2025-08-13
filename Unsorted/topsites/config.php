<?php
$TMPL['list_name'] = 'Aardvark Topsites PHP';
$CONFIG['deflanguage'] = 'english';
$CONFIG['path'] = '.';
$CONFIG['list_url'] = 'http://www.you.com/topsites';
$TMPL['list_url'] = $CONFIG['list_url'];
$CONFIG['templates_path'] = './templates';
$CONFIG['templates_url'] = 'http://www.you.com/topsites/templates';
$TMPL['templates_url'] = $CONFIG['templates_url'];
$CONFIG['youremail'] = 'webmaster@you.com';

$CONFIG['sql'] = 'mysql';
$CONFIG['sql_host'] = 'localhost';
$CONFIG['sql_database'] = 'database';
$CONFIG['sql_user'] = 'user';
$CONFIG['sql_pass'] = 'pass';
$CONFIG['sql_prefix'] = 'ats';

$CONFIG['categories'] = array('Category 1', 'Category 2');
$CONFIG['numlist'] = 10;
$CONFIG['daymonth'] = 0;
$CONFIG['rankingmethod'] = 'unq_pv';
$CONFIG['featured'] = 0;
$CONFIG['top'] = 2;
$CONFIG['adbreaks'] = array();

$CONFIG['active_default'] = 1;

$CONFIG['delete_after'] = 14;
$CONFIG['email_admin_on_join'] = 0;
$CONFIG['max_banner_width'] = 0;
$TMPL['max_banner_width'] = $CONFIG['max_banner_width'];
$CONFIG['max_banner_height'] = 0;
$TMPL['max_banner_height'] = $CONFIG['max_banner_height'];
$CONFIG['defbanner'] = 'http://www.you.com/topsites/images/button.png';

$CONFIG['ranks_on_buttons'] = 1;
$CONFIG['button_url'] = 'http://www.you.com/topsites/images/button.png';
$CONFIG['button_dir'] = 'http://www.you.com/topsites/images';
$CONFIG['button_ext'] = 'png';
$CONFIG['button_num'] = 5;

$CONFIG['search_id'] = 50;
$CONFIG['search'] = 1;
$CONFIG['searchresults'] = 10;

$CONFIG['gzip'] = 0;
$CONFIG['timeoffset'] = 4;
$CONFIG['gateway'] = 1;

$CONFIG['version'] = '4.1.0 (2003-07-29)';
$TMPL['version'] = $CONFIG['version'];
?>