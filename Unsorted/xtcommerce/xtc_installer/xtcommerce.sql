# -----------------------------------------------------------------------------------------
#  $Id: xtcommerce.sql,v 1.76 2003/08/25 09:53:14 fanta2k Exp $
#
#  XT-Commerce - community made shopping
#  http://www.xt-commerce.com
#
#  Copyright (c) 2003 XT-Commerce
#  -----------------------------------------------------------------------------------------
#  Third Party Contributions:
#  Customers status v3.x (c) 2002-2003 Elari elari@free.fr
#  Download area : www.unlockgsm.com/dload-osc/
#  CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist
#  --------------------------------------------------------------
#  based on: 
#  (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
#  (c) 2002-2003 osCommerce (oscommerce.sql,v 1.83); www.oscommerce.com 
#  (c) 2003	 nextcommerce (nextcommerce.sql,v 1.76 2003/08/25); www.nextcommerce.org
#
#  Released under the GNU General Public License 
#
#  --------------------------------------------------------------
# NOTE: * Please make any modifications to this file by hand!
#       * DO NOT use a mysqldump created file for new changes!
#       * Please take note of the table structure, and use this
#         structure as a standard for future modifications!
#       * Any tables you add here should be added in admin/backup.php
#         and in catalog/install/includes/functions/database.php
#       * To see the 'diff'erence between MySQL databases, use
#         the mysqldiff perl script located in the extras
#         directory of the 'catalog' module.
#       * Comments should be like these, full line comments.
#         (don't use inline comments)
#  --------------------------------------------------------------



DROP TABLE IF EXISTS address_book;
CREATE TABLE address_book (
   address_book_id int NOT NULL auto_increment,
   customers_id int NOT NULL,
   entry_gender char(1) NOT NULL,
   entry_company varchar(32),
   entry_firstname varchar(32) NOT NULL,
   entry_lastname varchar(32) NOT NULL,
   entry_street_address varchar(64) NOT NULL,
   entry_suburb varchar(32),
   entry_postcode varchar(10) NOT NULL,
   entry_city varchar(32) NOT NULL,
   entry_state varchar(32),
   entry_country_id int DEFAULT '0' NOT NULL,
   entry_zone_id int DEFAULT '0' NOT NULL,
   PRIMARY KEY (address_book_id),
   KEY idx_address_book_customers_id (customers_id)
);

DROP TABLE IF EXISTS customers_memo;
CREATE TABLE customers_memo (
  memo_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL default '0',
  memo_date date NOT NULL default '0000-00-00',
  memo_title text NOT NULL,
  memo_text text NOT NULL,
  poster_id int(11) NOT NULL default '0',
  PRIMARY KEY  (memo_id)
);

DROP TABLE IF EXISTS products_xsell; 
CREATE TABLE products_xsell ( 
ID int(10) NOT NULL auto_increment, 
products_id int(10) unsigned NOT NULL default '1', 
xsell_id int(10) unsigned NOT NULL default '1', 
sort_order int(10) unsigned NOT NULL default '1', 
PRIMARY KEY  (ID) 
);

DROP TABLE IF EXISTS address_format;
CREATE TABLE address_format (
  address_format_id int NOT NULL auto_increment,
  address_format varchar(128) NOT NULL,
  address_summary varchar(48) NOT NULL,
  PRIMARY KEY (address_format_id)
);

DROP TABLE IF EXISTS admin_access;
CREATE TABLE admin_access (
  customers_id int(11) NOT NULL default '0',
  configuration int(1) NOT NULL default '0',
  modules int(1) NOT NULL default '0',
  countries int(1) NOT NULL default '0',
  currencies int(1) NOT NULL default '0',
  zones int(1) NOT NULL default '0',
  geo_zones int(1) NOT NULL default '0',
  tax_classes int(1) NOT NULL default '0',
  tax_rates int(1) NOT NULL default '0',
  customers int(1) NOT NULL default '0', 
  create_account int(1) NOT NULL default '0',
  accounting int(1) NOT NULL default '0',
  customers_status int(1) NOT NULL default '0',
  orders int(1) NOT NULL default '0',
  categories int(1) NOT NULL default '0',
  new_attributes int(1) NOT NULL default '0',
  products_attributes int(1) NOT NULL default '0',
  manufacturers int(1) NOT NULL default '0',
  reviews int(1) NOT NULL default '0',
  xsell_products int(1) NOT NULL default '0',
  specials int(1) NOT NULL default '0',
  stats_products_expected int(1) NOT NULL default '0',
  stats_products_viewed int(1) NOT NULL default '0',
  stats_products_purchased int(1) NOT NULL default '0',
  stats_customers int(1) NOT NULL default '0',
  backup int(1) NOT NULL default '0',
  banner_manager int(1) NOT NULL default '0',
  cache int(1) NOT NULL default '0',
  define_language int(1) NOT NULL default '0',
  file_manager int(1) NOT NULL default '0',
  mail int(1) NOT NULL default '0',
  newsletters int(1) NOT NULL default '0',
  server_info int(1) NOT NULL default '0',
  whos_online int(1) NOT NULL default '0',
  templates_boxes int(1) NOT NULL default '0',
  invoice int(1) NOT NULL default '0',
  packingslip int(1) NOT NULL default '0',
  languages int(1) NOT NULL default '0',
  start int(1) NOT NULL default '1',
  print_order int(1) NOT NULL default '1',
  content_manager int(1) NOT NULL default '0',
  content_preview int(1) NOT NULL default '1',	
  credits int(1) NOT NULL default '1',
  print_packingslip int(1) NOT NULL default '1',	
  PRIMARY KEY  (customers_id)
);


# only for bugfix with non installed banktransfer
# if calling orders.php at the admin-tool
# ---------- should be deleted asap

DROP TABLE IF EXISTS banktransfer;
CREATE TABLE banktransfer (
  orders_id int(11) NOT NULL default '0',
  banktransfer_owner varchar(64) default NULL,
  banktransfer_number varchar(24) default NULL,
  banktransfer_bankname varchar(255) default NULL,
  banktransfer_blz varchar(8) default NULL,
  banktransfer_status int(11) default NULL,
  banktransfer_prz char(2) default NULL,
  banktransfer_fax char(2) default NULL,
  KEY orders_id(orders_id)
);

# --------------------------------------------------

DROP TABLE IF EXISTS banners;
CREATE TABLE banners (
  banners_id int NOT NULL auto_increment,
  banners_title varchar(64) NOT NULL,
  banners_url varchar(255) NOT NULL,
  banners_image varchar(64) NOT NULL,
  banners_group varchar(10) NOT NULL,
  banners_html_text text,
  expires_impressions int(7) DEFAULT '0',
  expires_date datetime DEFAULT NULL,
  date_scheduled datetime DEFAULT NULL,
  date_added datetime NOT NULL,
  date_status_change datetime DEFAULT NULL,
  status int(1) DEFAULT '1' NOT NULL,
  PRIMARY KEY  (banners_id)
);

DROP TABLE IF EXISTS banners_history;
CREATE TABLE banners_history (
  banners_history_id int NOT NULL auto_increment,
  banners_id int NOT NULL,
  banners_shown int(5) NOT NULL DEFAULT '0',
  banners_clicked int(5) NOT NULL DEFAULT '0',
  banners_history_date datetime NOT NULL,
  PRIMARY KEY  (banners_history_id)
);

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
   categories_id int NOT NULL auto_increment,
   categories_image varchar(64),
   parent_id int DEFAULT '0' NOT NULL,
   categories_status TINYint (1)  UNSIGNED DEFAULT "1" NOT NULL,
   sort_order int(3),
   date_added datetime,
   last_modified datetime,
   PRIMARY KEY (categories_id),
   KEY idx_categories_parent_id (parent_id)
);

DROP TABLE IF EXISTS categories_description;
CREATE TABLE categories_description (
   categories_id int DEFAULT '0' NOT NULL,
   language_id int DEFAULT '1' NOT NULL,
   categories_name varchar(32) NOT NULL,
   PRIMARY KEY (categories_id, language_id),
   KEY idx_categories_name (categories_name)
);

DROP TABLE IF EXISTS configuration;
CREATE TABLE configuration (
  configuration_id int NOT NULL auto_increment,
  configuration_key varchar(64) NOT NULL,
  configuration_value varchar(255) NOT NULL,
  configuration_group_id int NOT NULL,
  sort_order int(5) NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  use_function varchar(255) NULL,
  set_function varchar(255) NULL,
  PRIMARY KEY (configuration_id),
  KEY idx_configuration_group_id (configuration_group_id)
);

DROP TABLE IF EXISTS configuration_group;
CREATE TABLE configuration_group (
  configuration_group_id int NOT NULL auto_increment,
  configuration_group_title varchar(64) NOT NULL,
  configuration_group_description varchar(255) NOT NULL,
  sort_order int(5) NULL,
  visible int(1) DEFAULT '1' NULL,
  PRIMARY KEY (configuration_group_id)
);

DROP TABLE IF EXISTS counter;
CREATE TABLE counter (
  startdate char(8),
  counter int(12)
);

DROP TABLE IF EXISTS counter_history;
CREATE TABLE counter_history (
  month char(8),
  counter int(12)
);

DROP TABLE IF EXISTS countries;
CREATE TABLE countries (
  countries_id int NOT NULL auto_increment,
  countries_name varchar(64) NOT NULL,
  countries_iso_code_2 char(2) NOT NULL,
  countries_iso_code_3 char(3) NOT NULL,
  address_format_id int NOT NULL,
  PRIMARY KEY (countries_id),
  KEY IDX_COUNTRIES_NAME (countries_name)
);

DROP TABLE IF EXISTS currencies;
CREATE TABLE currencies (
  currencies_id int NOT NULL auto_increment,
  title varchar(32) NOT NULL,
  code char(3) NOT NULL,
  symbol_left varchar(12),
  symbol_right varchar(12),
  decimal_point char(1),
  thousands_point char(1),
  decimal_places char(1),
  value float(13,8),
  last_updated datetime NULL,
  PRIMARY KEY (currencies_id)
);

DROP TABLE IF EXISTS customers;
CREATE TABLE customers (
   customers_id int NOT NULL auto_increment,
   customers_status int(5) DEFAULT '1' NOT NULL, 
   customers_gender char(1) NOT NULL,
   customers_firstname varchar(32) NOT NULL,
   customers_lastname varchar(32) NOT NULL,
   customers_dob datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
   customers_email_address varchar(96) NOT NULL,
   customers_default_address_id int NOT NULL,
   customers_telephone varchar(32) NOT NULL,
   customers_fax varchar(32),
   customers_password varchar(40) NOT NULL,
   customers_newsletter char(1),
   customers_newsletter_mode char( 1 ) DEFAULT '0' NOT NULL,
   member_flag char(1) DEFAULT '0' NOT NULL,
   delete_user char(1) DEFAULT '1' NOT NULL,
   PRIMARY KEY (customers_id)
);

DROP TABLE IF EXISTS customers_basket;
CREATE TABLE customers_basket (
  customers_basket_id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  products_id tinytext NOT NULL,
  customers_basket_quantity int(2) NOT NULL,
  final_price decimal(15,4) NOT NULL,
  customers_basket_date_added char(8),
  PRIMARY KEY (customers_basket_id)
);

DROP TABLE IF EXISTS customers_basket_attributes;
CREATE TABLE customers_basket_attributes (
  customers_basket_attributes_id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  products_id tinytext NOT NULL,
  products_options_id int NOT NULL,
  products_options_value_id int NOT NULL,
  PRIMARY KEY (customers_basket_attributes_id)
);

DROP TABLE IF EXISTS customers_info;
CREATE TABLE customers_info (
  customers_info_id int NOT NULL,
  customers_info_date_of_last_logon datetime,
  customers_info_number_of_logons int(5),
  customers_info_date_account_created datetime,
  customers_info_date_account_last_modified datetime,
  global_product_notifications int(1) DEFAULT '0',
  PRIMARY KEY (customers_info_id)
);

DROP TABLE IF EXISTS customers_ip;
CREATE TABLE customers_ip (
  customers_ip_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL default '0',
  customers_ip varchar(15) NOT NULL default '',
  customers_ip_date datetime NOT NULL default '0000-00-00 00:00:00',
  customers_host varchar(255) NOT NULL default '',
  customers_advertiser varchar(30) default NULL,
  customers_referer_url varchar(255) default NULL,
  PRIMARY KEY  (customers_ip_id),
  KEY customers_id (customers_id)
);

DROP TABLE IF EXISTS customers_status;
CREATE TABLE customers_status (
  customers_status_id int(11) NOT NULL default '0',
  language_id int(11) NOT NULL DEFAULT '1',
  customers_status_name VARCHAR(32) NOT NULL DEFAULT '',
  customers_status_public int(1) NOT NULL DEFAULT '1',
  customers_status_image varchar(64) DEFAULT NULL,
  customers_status_discount decimal(4,2) DEFAULT '0',
  customers_status_ot_discount_flag char(1) NOT NULL DEFAULT '0',
  customers_status_ot_discount decimal(4,2) DEFAULT '0',
  customers_status_graduated_prices varchar(1) NOT NULL DEFAULT '0',
  customers_status_show_price int(1) NOT NULL DEFAULT '1',
  customers_status_show_price_tax int(1) NOT NULL DEFAULT '1',
  customers_status_add_tax_ot  int(1) NOT NULL DEFAULT '0',
  customers_status_payment_unallowed varchar(255) NOT NULL,
  customers_status_shipping_unallowed varchar(255) NOT NULL,
  customers_status_discount_attributes  int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (customers_status_id,language_id),
  KEY idx_orders_status_name (customers_status_name)
);

DROP TABLE IF EXISTS customers_status_history;
CREATE TABLE customers_status_history (
  customers_status_history_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL default '0',
  new_value int(5) NOT NULL default '0',
  old_value int(5) default NULL,
  date_added datetime NOT NULL default '0000-00-00 00:00:00',
  customer_notified int(1) default '0',
  PRIMARY KEY  (customers_status_history_id)
);

DROP TABLE IF EXISTS languages;
CREATE TABLE languages (
  languages_id int NOT NULL auto_increment,
  name varchar(32)  NOT NULL,
  code char(2) NOT NULL,
  image varchar(64),
  directory varchar(32),
  sort_order int(3),
  language_charset text NOT NULL,
  PRIMARY KEY (languages_id),
  KEY IDX_LANGUAGES_NAME (name)
);


DROP TABLE IF EXISTS manufacturers;
CREATE TABLE manufacturers (
  manufacturers_id int NOT NULL auto_increment,
  manufacturers_name varchar(32) NOT NULL,
  manufacturers_image varchar(64),
  date_added datetime NULL,
  last_modified datetime NULL,
  PRIMARY KEY (manufacturers_id),
  KEY IDX_MANUFACTURERS_NAME (manufacturers_name)
);

DROP TABLE IF EXISTS manufacturers_info;
CREATE TABLE manufacturers_info (
  manufacturers_id int NOT NULL,
  languages_id int NOT NULL,
  manufacturers_url varchar(255) NOT NULL,
  url_clicked int(5) NOT NULL default '0',
  date_last_click datetime NULL,
  PRIMARY KEY (manufacturers_id, languages_id)
);

DROP TABLE IF EXISTS newsletters;
CREATE TABLE newsletters (
  newsletters_id int NOT NULL auto_increment,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  module varchar(255) NOT NULL,
  date_added datetime NOT NULL,
  date_sent datetime,
  status int(1),
  locked int(1) DEFAULT '0',
  PRIMARY KEY (newsletters_id)
);

DROP TABLE IF EXISTS newsletters_history;
CREATE TABLE newsletters_history (
  news_hist_id int(11) NOT NULL default '0',
  news_hist_cs int(11) NOT NULL default '0',
  news_hist_cs_date_sent date default NULL,
  PRIMARY KEY  (news_hist_id)
);

DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
  orders_id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  customers_status int(11),
  customers_status_name varchar(32) NOT NULL,
  customers_status_image varchar (64),
  customers_status_discount decimal (4,2),
  customers_name varchar(64) NOT NULL,
  customers_company varchar(32),
  customers_street_address varchar(64) NOT NULL,
  customers_suburb varchar(32),
  customers_city varchar(32) NOT NULL,
  customers_postcode varchar(10) NOT NULL,
  customers_state varchar(32),
  customers_country varchar(32) NOT NULL,
  customers_telephone varchar(32) NOT NULL,
  customers_email_address varchar(96) NOT NULL,
  customers_address_format_id int(5) NOT NULL,
  delivery_name varchar(64) NOT NULL,
  delivery_company varchar(32),
  delivery_street_address varchar(64) NOT NULL,
  delivery_suburb varchar(32),
  delivery_city varchar(32) NOT NULL,
  delivery_postcode varchar(10) NOT NULL,
  delivery_state varchar(32),
  delivery_country varchar(32) NOT NULL,
  delivery_address_format_id int(5) NOT NULL,
  billing_name varchar(64) NOT NULL,
  billing_company varchar(32),
  billing_street_address varchar(64) NOT NULL,
  billing_suburb varchar(32),
  billing_city varchar(32) NOT NULL,
  billing_postcode varchar(10) NOT NULL,
  billing_state varchar(32),
  billing_country varchar(32) NOT NULL,
  billing_address_format_id int(5) NOT NULL,
  payment_method varchar(32) NOT NULL,
  cc_type varchar(20),
  cc_owner varchar(64),
  cc_number varchar(32),
  cc_expires varchar(4),
  comments varchar (255),
  last_modified datetime,
  date_purchased datetime,
  orders_status int(5) NOT NULL,
  orders_date_finished datetime,
  currency char(3),
  currency_value decimal(14,6),
  PRIMARY KEY (orders_id)
);

DROP TABLE IF EXISTS orders_products;
CREATE TABLE orders_products (
  orders_products_id int NOT NULL auto_increment,
  orders_id int NOT NULL,
  products_id int NOT NULL,
  products_model varchar(12),
  products_name varchar(64) NOT NULL,
  products_price decimal(15,4) NOT NULL,
  products_discount_made decimal(4,2) DEFAULT NULL,
  final_price decimal(15,4) NOT NULL,
  products_tax decimal(7,4) NOT NULL,
  products_quantity int(2) NOT NULL,
  allow_tax int(1) NOT NULL,
  PRIMARY KEY (orders_products_id)
);

DROP TABLE IF EXISTS orders_status;
CREATE TABLE orders_status (
   orders_status_id int DEFAULT '0' NOT NULL,
   language_id int DEFAULT '1' NOT NULL,
   orders_status_name varchar(32) NOT NULL,
   PRIMARY KEY (orders_status_id, language_id),
   KEY idx_orders_status_name (orders_status_name)
);

DROP TABLE IF EXISTS orders_status_history;
CREATE TABLE orders_status_history (
   orders_status_history_id int NOT NULL auto_increment,
   orders_id int NOT NULL,
   orders_status_id int(5) NOT NULL,
   date_added datetime NOT NULL,
   customer_notified int(1) DEFAULT '0',
   comments text,
   PRIMARY KEY (orders_status_history_id)
);

DROP TABLE IF EXISTS orders_products_attributes;
CREATE TABLE orders_products_attributes (
  orders_products_attributes_id int NOT NULL auto_increment,
  orders_id int NOT NULL,
  orders_products_id int NOT NULL,
  products_options varchar(32) NOT NULL,
  products_options_values varchar(32) NOT NULL,
  options_values_price decimal(15,4) NOT NULL,
  price_prefix char(1) NOT NULL,
  PRIMARY KEY (orders_products_attributes_id)
);

DROP TABLE IF EXISTS orders_products_download;
CREATE TABLE orders_products_download (
  orders_products_download_id int NOT NULL auto_increment,
  orders_id int NOT NULL default '0',
  orders_products_id int NOT NULL default '0',
  orders_products_filename varchar(255) NOT NULL default '',
  download_maxdays int(2) NOT NULL default '0',
  download_count int(2) NOT NULL default '0',
  PRIMARY KEY  (orders_products_download_id)
);

DROP TABLE IF EXISTS orders_total;
CREATE TABLE orders_total (
  orders_total_id int unsigned NOT NULL auto_increment,
  orders_id int NOT NULL,
  title varchar(255) NOT NULL,
  text varchar(255) NOT NULL,
  value decimal(15,4) NOT NULL,
  class varchar(32) NOT NULL,
  sort_order int NOT NULL,
  PRIMARY KEY (orders_total_id),
  KEY idx_orders_total_orders_id (orders_id)
);

DROP TABLE IF EXISTS products;
CREATE TABLE products (
  products_id int NOT NULL auto_increment,
  products_quantity int(4) NOT NULL,
  products_model varchar(12),
  products_image varchar(64),
  products_price decimal(15,4) NOT NULL,
  products_discount_allowed decimal(3,2) DEFAULT '0' NOT NULL,
  products_date_added datetime NOT NULL,
  products_last_modified datetime,
  products_date_available datetime,
  products_weight decimal(5,2) NOT NULL,
  products_status tinyint(1) NOT NULL,
  products_tax_class_id int NOT NULL,
  manufacturers_id int NULL,
  products_ordered int NOT NULL default '0',
  PRIMARY KEY (products_id),
  KEY idx_products_date_added (products_date_added)
);

DROP TABLE IF EXISTS products_attributes;
CREATE TABLE products_attributes (
  products_attributes_id int NOT NULL auto_increment,
  products_id int NOT NULL,
  options_id int NOT NULL,
  options_values_id int NOT NULL,
  options_values_price decimal(15,4) NOT NULL,
  price_prefix char(1) NOT NULL,
  attributes_model varchar(12) NULL,
  attributes_stock int(4) NULL,
  options_values_weight decimal(15,4) NOT NULL,
  weight_prefix char(1) NOT NULL,
  PRIMARY KEY (products_attributes_id)
);

DROP TABLE IF EXISTS products_attributes_download;
CREATE TABLE products_attributes_download (
  products_attributes_id int NOT NULL,
  products_attributes_filename varchar(255) NOT NULL default '',
  products_attributes_maxdays int(2) default '0',
  products_attributes_maxcount int(2) default '0',
  PRIMARY KEY  (products_attributes_id)
);

DROP TABLE IF EXISTS products_description;
CREATE TABLE products_description (
  products_id int NOT NULL auto_increment,
  language_id int NOT NULL default '1',
  products_name varchar(64) NOT NULL default '',
  products_description text,
  products_short_description text,
  products_url varchar(255) default NULL,
  products_viewed int(5) default '0',
  PRIMARY KEY  (products_id,language_id),
  KEY products_name (products_name)
);

DROP TABLE IF EXISTS products_notifications;
CREATE TABLE products_notifications (
  products_id int NOT NULL,
  customers_id int NOT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (products_id, customers_id)
);

DROP TABLE IF EXISTS products_options;
CREATE TABLE products_options (
  products_options_id int NOT NULL default '0',
  language_id int NOT NULL default '1',
  products_options_name varchar(32) NOT NULL default '',
  PRIMARY KEY  (products_options_id,language_id)
);

DROP TABLE IF EXISTS products_options_values;
CREATE TABLE products_options_values (
  products_options_values_id int NOT NULL default '0',
  language_id int NOT NULL default '1',
  products_options_values_name varchar(64) NOT NULL default '',
  PRIMARY KEY  (products_options_values_id,language_id)
);

DROP TABLE IF EXISTS products_options_values_to_products_options;
CREATE TABLE products_options_values_to_products_options (
  products_options_values_to_products_options_id int NOT NULL auto_increment,
  products_options_id int NOT NULL,
  products_options_values_id int NOT NULL,
  PRIMARY KEY (products_options_values_to_products_options_id)
);

DROP TABLE IF EXISTS products_graduated_prices;
CREATE TABLE products_graduated_prices (
  products_id int(11) NOT NULL default '0',
  quantity int(11) NOT NULL default '0',
  unitprice decimal(15,4) NOT NULL default '0.0000',
  KEY products_id (products_id)
);

DROP TABLE IF EXISTS products_to_categories;
CREATE TABLE products_to_categories (
  products_id int NOT NULL,
  categories_id int NOT NULL,
  PRIMARY KEY (products_id,categories_id)
);

DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
  reviews_id int NOT NULL auto_increment,
  products_id int NOT NULL,
  customers_id int,
  customers_name varchar(64) NOT NULL,
  reviews_rating int(1),
  date_added datetime,
  last_modified datetime,
  reviews_read int(5) NOT NULL default '0',
  PRIMARY KEY (reviews_id)
);

DROP TABLE IF EXISTS reviews_description;
CREATE TABLE reviews_description (
  reviews_id int NOT NULL,
  languages_id int NOT NULL,
  reviews_text text NOT NULL,
  PRIMARY KEY (reviews_id, languages_id)
);

DROP TABLE IF EXISTS sessions;
CREATE TABLE sessions (
  sesskey varchar(32) NOT NULL,
  expiry int(11) unsigned NOT NULL,
  value text NOT NULL,
  PRIMARY KEY (sesskey)
);

DROP TABLE IF EXISTS specials;
CREATE TABLE specials (
  specials_id int NOT NULL auto_increment,
  products_id int NOT NULL,
  specials_new_products_price decimal(15,4) NOT NULL,
  specials_date_added datetime,
  specials_last_modified datetime,
  expires_date datetime,
  date_status_change datetime,
  status int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (specials_id)
);

DROP TABLE IF EXISTS tax_class;
CREATE TABLE tax_class (
  tax_class_id int NOT NULL auto_increment,
  tax_class_title varchar(32) NOT NULL,
  tax_class_description varchar(255) NOT NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (tax_class_id)
);

DROP TABLE IF EXISTS tax_rates;
CREATE TABLE tax_rates (
  tax_rates_id int NOT NULL auto_increment,
  tax_zone_id int NOT NULL,
  tax_class_id int NOT NULL,
  tax_priority int(5) DEFAULT 1,
  tax_rate decimal(7,4) NOT NULL,
  tax_description varchar(255) NOT NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (tax_rates_id)
);

DROP TABLE IF EXISTS geo_zones;
CREATE TABLE geo_zones (
  geo_zone_id int NOT NULL auto_increment,
  geo_zone_name varchar(32) NOT NULL,
  geo_zone_description varchar(255) NOT NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (geo_zone_id)
);

DROP TABLE IF EXISTS whos_online;
CREATE TABLE whos_online (
  customer_id int,
  full_name varchar(64) NOT NULL,
  session_id varchar(128) NOT NULL,
  ip_address varchar(15) NOT NULL,
  time_entry varchar(14) NOT NULL,
  time_last_click varchar(14) NOT NULL,
  last_page_url varchar(64) NOT NULL
);

DROP TABLE IF EXISTS zones;
CREATE TABLE zones (
  zone_id int NOT NULL auto_increment,
  zone_country_id int NOT NULL,
  zone_code varchar(32) NOT NULL,
  zone_name varchar(32) NOT NULL,
  PRIMARY KEY (zone_id)
);

DROP TABLE IF EXISTS zones_to_geo_zones;
CREATE TABLE zones_to_geo_zones (
   association_id int NOT NULL auto_increment,
   zone_country_id int NOT NULL,
   zone_id int NULL,
   geo_zone_id int NULL,
   last_modified datetime NULL,
   date_added datetime NOT NULL,
   PRIMARY KEY (association_id)
);


DROP TABLE IF EXISTS box_align;
CREATE TABLE box_align (
  box_id int(2) NOT NULL auto_increment,
  box_name text NOT NULL,
  box_align text NOT NULL,
  box_visible int(2) NOT NULL default '0',
  box_order int(2) NOT NULL default '0',
  PRIMARY KEY  (box_id)
);


DROP TABLE IF EXISTS content_manager;
CREATE TABLE content_manager (
  content_id int(11) NOT NULL auto_increment,
  categories_id int(11) NOT NULL default '0',
  parent_id int(11) NOT NULL default '0',
  languages_id int(11) NOT NULL default '0',
  content_title varchar(32) NOT NULL default '',
  content_heading varchar(32) NOT NULL default '',
  content_text text NOT NULL,
  file_flag int(1) NOT NULL default '0',
  content_file varchar(64) NOT NULL default '',
  content_status int(1) NOT NULL default '0',
  content_group int(11) NOT NULL,
  content_delete int(1) NOT NULL default '1',
  PRIMARY KEY  (content_id)
);

DROP TABLE IF EXISTS media_content;
CREATE TABLE media_content (
  file_id int(11) NOT NULL auto_increment,
  old_filename text NOT NULL,
  new_filename text NOT NULL,
  file_comment text NOT NULL,
  PRIMARY KEY  (file_id)
);

DROP TABLE IF EXISTS products_content;
CREATE TABLE products_content (
  content_id int(11) NOT NULL auto_increment,
  products_id int(11) NOT NULL default '0',
  content_name varchar(32) NOT NULL default '',
  content_file varchar(64) NOT NULL,
  content_link text NOT NULL,
  languages_id int(11) NOT NULL default '0',
  content_read int(11) NOT NULL default '0',
  file_comment text NOT NULL,
  PRIMARY KEY  (content_id)
);



# data

INSERT INTO content_manager VALUES (1,0,0,1,'Shipping & Returns','Shipping & Returns','Put here your Shipping & Returns information.',1,'',1,1,0);
INSERT INTO content_manager VALUES (2,0,0,1,'Privacy Notice','Privacy Notice','Put here your Privacy Notice information.',1,'',1,2,0);
INSERT INTO content_manager VALUES (3,0,0,1,'Conditions of Use','Conditions of Use','Conditions of Use<br />Put here your Conditions of Use information. <br />1. Validity<br />2. Offers<br />3. Price<br />4. Dispatch and passage of the risk<br />5. Delivery<br />6. Terms of payment<br />7. Retention of title<br />8. Notices of defect, guarantee and compensation<br />9. Fair trading cancelling / non-acceptance<br />10. Place of delivery and area of jurisdiction<br />11. Final clauses',1,'',1,3,0);
INSERT INTO content_manager VALUES (4,0,0,1,'Contact','Contact','Put here your Contact information.',1,'',1,4,0);
INSERT INTO content_manager VALUES (5,0,0,2,'Liefer- und Versandkosten','Liefer- und Versandkosten','Fügen Sie hier Ihre Informationen über Liefer- und Versandkosten ein.',1,'',1,1,0);
INSERT INTO content_manager VALUES (6,0,0,2,'Privatsphäre und Datenschutz','Privatsphäre und Datenschutz','Fügen Sie hier Ihre Informationen über Privatsphäre und Datenschutz ein.',1,'',1,2,0);
INSERT INTO content_manager VALUES (7,0,0,2,'Unsere AGB\'s','Allgemeine Geschäftsbedingungen','<strong>Allgemeine Gesch&auml;ftsbedingungen<br></strong><br>F&uuml;gen Sie hier Ihre allgemeinen Gesch&auml;ftsbedingungen ein.<br>1. Geltung<br>2. Angebote<br>3. Preis<br>4. Versand und Gefahr&uuml;bergang<br>5. Lieferung<br>6. Zahlungsbedingungen<br>7. Eigentumsvorbehalt <br>8. M&auml;ngelr&uuml;gen, Gew&auml;hrleistung und Schadenersatz<br>9. Kulanzr&uuml;cknahme / Annahmeverweigerung<br>10. Erf&uuml;llungsort und Gerichtsstand<br>11. Schlussbestimmungen',1,'',1,3,0);
INSERT INTO content_manager VALUES (8,0,0,2,'Kontakt','Kontakt','Fügen Sie hier Ihre Informationen über Kontakt ein.',1,'',1,4,0);

INSERT INTO box_align VALUES (1, 'loginbox.php', 'left', 1, 6);
INSERT INTO box_align VALUES (2, 'customers_status.php', 'left', 0, 7);
INSERT INTO box_align VALUES (3, 'categories.php', 'left', 1, 2);
INSERT INTO box_align VALUES (4, 'manufacturers.php', 'left', 0, 1);
INSERT INTO box_align VALUES (5, 'add_a_quickie.php', 'left', 1, 4);
INSERT INTO box_align VALUES (6, 'search.php', 'left', 1, 8);
INSERT INTO box_align VALUES (7, 'information.php', 'left', 1, 3);
INSERT INTO box_align VALUES (8, 'shopping_cart.php', 'right', 1, 1);
INSERT INTO box_align VALUES (9, 'manufacturer_info.php', 'right', 1, 6);
INSERT INTO box_align VALUES (10, 'order_history.php', 'right', 1, 5);
INSERT INTO box_align VALUES (11, 'best_sellers.php', 'right', 1, 3);
INSERT INTO box_align VALUES (12, 'product_notifications.php', 'right', 1, 2);
INSERT INTO box_align VALUES (15, 'tell_a_friend.php', 'right', 0, 4);
INSERT INTO box_align VALUES (16, 'specials.php', 'right', 1, 7);
INSERT INTO box_align VALUES (17, 'reviews.php', 'right', 1, 9);
INSERT INTO box_align VALUES (18, 'languages.php', 'left', 1, 5);
INSERT INTO box_align VALUES (19, 'currencies.php', 'right', 1, 8);


# 1 - Default, 2 - USA, 3 - Spain, 4 - Singapore, 5 - Germany
INSERT INTO address_format VALUES (1, '$firstname $lastname$cr$streets$cr$city, $postcode$cr$statecomma$country','$city / $country');
INSERT INTO address_format VALUES (2, '$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country','$city, $state / $country');
INSERT INTO address_format VALUES (3, '$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country','$state / $country');
INSERT INTO address_format VALUES (4, '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country', '$postcode / $country');
INSERT INTO address_format VALUES (5, '$firstname $lastname$cr$streets$cr$postcode $city$cr$country','$city / $country');

INSERT INTO admin_access VALUES (1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);


INSERT INTO banners VALUES (1, 'XT-Commerce', 'http://www.xt-commerce.com', 'banners/xtcommerce.gif', '468x50', '', 0, null, null, now(), null, 1);

INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (1, 'category_hardware.gif', 0, 1, 1, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (2, 'category_software.gif', 0, 1, 2, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (3, 'category_dvd_movies.gif', 0, 1, 3, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (4, 'subcategory_graphic_cards.gif', 1, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (5, 'subcategory_printers.gif', 1, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (6, 'subcategory_monitors.gif', 1, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (7, 'subcategory_speakers.gif', 1, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (8, 'subcategory_keyboards.gif', 1, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (9, 'subcategory_mice.gif', 1, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (10, 'subcategory_action.gif', 3, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (11, 'subcategory_science_fiction.gif', 3, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (12, 'subcategory_comedy.gif', 3, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (13, 'subcategory_cartoons.gif', 3, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (14, 'subcategory_thriller.gif', 3, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (15, 'subcategory_drama.gif', 3, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (16, 'subcategory_memory.gif', 1, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (17, 'subcategory_cdrom_drives.gif', 1, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (18, 'subcategory_simulation.gif', 2, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (19, 'subcategory_action_games.gif', 2, 1, 0, '2003-05-25 05:45:57', NULL);
INSERT INTO categories (categories_id, categories_image, parent_id, categories_status, sort_order, date_added, last_modified) VALUES (20, 'subcategory_strategy.gif', 2, 1, 0, '2003-05-25 05:45:57', NULL);


INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (1, 1, 'Hardware');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (2, 1, 'Software');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (3, 1, 'DVD Movies');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (4, 1, 'Graphics Cards');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (5, 1, 'Printers');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (6, 1, 'Monitors');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (7, 1, 'Speakers');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (8, 1, 'Keyboards');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (9, 1, 'Mice');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (10, 1, 'Action');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (11, 1, 'Science Fiction');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (12, 1, 'Comedy');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (13, 1, 'Cartoons');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (14, 1, 'Thriller');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (15, 1, 'Drama');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (16, 1, 'Memory');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (17, 1, 'CDROM Drives');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (18, 1, 'Simulation');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (19, 1, 'Action');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (20, 1, 'Strategy');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (1, 2, 'Hardware');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (2, 2, 'Software');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (3, 2, 'DVD Filme');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (4, 2, 'Grafikkarten');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (5, 2, 'Drucker');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (6, 2, 'Bildschirme');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (7, 2, 'Lautsprecher');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (8, 2, 'Tastaturen');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (9, 2, 'Mäuse');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (10, 2, 'Action');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (11, 2, 'Science Fiction');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (12, 2, 'Komödie');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (13, 2, 'Zeichentrick');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (14, 2, 'Thriller');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (15, 2, 'Drama');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (16, 2, 'Speicher');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (17, 2, 'CDROM Laufwerke');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (18, 2, 'Simulation');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (19, 2, 'Action');
INSERT INTO categories_description (categories_id, language_id, categories_name) VALUES (20, 2, 'Strategie');


# configuration_group_id 1
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_NAME', 'XT-Commerce',  1, 1, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_OWNER', 'XT-Commerce', 1, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_OWNER_EMAIL_ADDRESS', 'owner@your-shop.com', 1, 3, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_FROM', 'XT-Commerce <owner@your-shop.com>',  1, 4, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_COUNTRY', '81',  1, 6, NULL, '', 'xtc_get_country_name', 'xtc_cfg_pull_down_country_list(');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_ZONE', '', 1, 7, NULL, '', 'xtc_cfg_get_zone_name', 'xtc_cfg_pull_down_zone_list(');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EXPECTED_PRODUCTS_SORT', 'desc',  1, 8, NULL, '', NULL, 'xtc_cfg_select_option(array(\'asc\', \'desc\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EXPECTED_PRODUCTS_FIELD', 'date_expected',  1, 9, NULL, '', NULL, 'xtc_cfg_select_option(array(\'products_name\', \'date_expected\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'USE_DEFAULT_LANGUAGE_CURRENCY', 'false', 1, 10, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SEARCH_ENGINE_FRIENDLY_URLS', 'false',  16, 12, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DISPLAY_CART', 'true',  1, 13, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ALLOW_GUEST_TO_TELL_A_FRIEND', 'false', 1, 14, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and', 1, 15, NULL, '', NULL, 'xtc_cfg_select_option(array(\'and\', \'or\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_NAME_ADDRESS', 'Store Name\nAddress\nCountry\nPhone',  1, 16, NULL, '', NULL, 'xtc_cfg_textarea(');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SHOW_COUNTS', 'true',  1, 17, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DEFAULT_CUSTOMERS_STATUS_ID_ADMIN', '0',  1, 20, NULL, '', 'xtc_get_customers_status_name', 'xtc_cfg_pull_down_customers_status_list(');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DEFAULT_CUSTOMERS_STATUS_ID_GUEST', '1',  1, 21, NULL, '', 'xtc_get_customers_status_name', 'xtc_cfg_pull_down_customers_status_list(');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DEFAULT_CUSTOMERS_STATUS_ID', '2',  1, 23, NULL, '', 'xtc_get_customers_status_name', 'xtc_cfg_pull_down_customers_status_list(');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ALLOW_ADD_TO_CART', 'false',  1, 24, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ACCOUNT_OPTIONS', 'account',  1, 25, NULL, '', NULL, 'xtc_cfg_select_option(array(\'account\', \'guest\', \'both\'),');

# configuration_group_id 2
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_FIRST_NAME_MIN_LENGTH', '2',  2, 1, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_LAST_NAME_MIN_LENGTH', '2',  2, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_DOB_MIN_LENGTH', '10',  2, 3, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_EMAIL_ADDRESS_MIN_LENGTH', '6',  2, 4, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_STREET_ADDRESS_MIN_LENGTH', '5',  2, 5, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_COMPANY_MIN_LENGTH', '2',  2, 6, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_POSTCODE_MIN_LENGTH', '4',  2, 7, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_CITY_MIN_LENGTH', '3',  2, 8, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_STATE_MIN_LENGTH', '2', 2, 9, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_TELEPHONE_MIN_LENGTH', '3',  2, 10, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_PASSWORD_MIN_LENGTH', '5',  2, 11, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CC_OWNER_MIN_LENGTH', '3',  2, 12, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CC_NUMBER_MIN_LENGTH', '10',  2, 13, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'REVIEW_TEXT_MIN_LENGTH', '50',  2, 14, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MIN_DISPLAY_BESTSELLERS', '1',  2, 15, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MIN_DISPLAY_ALSO_PURCHASED', '1', 2, 16, NULL, '', NULL, NULL);

# configuration_group_id 3
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_ADDRESS_BOOK_ENTRIES', '5',  3, 1, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_SEARCH_RESULTS', '20',  3, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_PAGE_LINKS', '5',  3, 3, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_SPECIAL_PRODUCTS', '9', 3, 4, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_NEW_PRODUCTS', '9',  3, 5, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_UPCOMING_PRODUCTS', '10',  3, 6, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_MANUFACTURERS_IN_A_LIST', '0', 3, 7, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_MANUFACTURERS_LIST', '1',  3, 7, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_MANUFACTURER_NAME_LEN', '15',  3, 8, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_NEW_REVIEWS', '6', 3, 9, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_RANDOM_SELECT_REVIEWS', '10',  3, 10, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_RANDOM_SELECT_NEW', '10',  3, 11, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_RANDOM_SELECT_SPECIALS', '10',  3, 12, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_CATEGORIES_PER_ROW', '3',  3, 13, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_PRODUCTS_NEW', '10',  3, 14, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_BESTSELLERS', '10',  3, 15, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_ALSO_PURCHASED', '6',  3, 16, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX', '6',  3, 17, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MAX_DISPLAY_ORDER_HISTORY', '10',  3, 18, NULL, '', NULL, NULL);

# configuration_group_id 4
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SMALL_IMAGE_WIDTH', '100',  4, 1, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SMALL_IMAGE_HEIGHT', '80',  4, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'HEADING_IMAGE_WIDTH', '70',  4, 3, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'HEADING_IMAGE_HEIGHT', '50',  4, 4, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SUBCATEGORY_IMAGE_WIDTH', '100',  4, 5, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SUBCATEGORY_IMAGE_HEIGHT', '57',  4, 6, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CONFIG_CALCULATE_IMAGE_SIZE', 'true',  4, 7, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'IMAGE_REQUIRED', 'true',  4, 8, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');

# configuration_group_id 5
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ACCOUNT_GENDER', 'true',  5, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ACCOUNT_DOB', 'true',  5, 2, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ACCOUNT_COMPANY', 'true',  5, 3, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ACCOUNT_SUBURB', 'true', 5, 4, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ACCOUNT_STATE', 'true',  5, 5, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');

# configuration_group_id 6
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_PAYMENT_INSTALLED', '', 6, 0, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_INSTALLED', 'ot_subtotal.php;ot_shipping.php;ot_tax.php;ot_total.php', 6, 0, '2003-07-18 03:31:55', '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_SHIPPING_INSTALLED', '',  6, 0, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DEFAULT_CURRENCY', 'EUR',  6, 0, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DEFAULT_LANGUAGE', 'de',  6, 0, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DEFAULT_ORDERS_STATUS_ID', '1',  6, 0, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'true',  6, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '3',  6, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING', 'false', 6, 3, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', '50',  6, 4, NULL, '', 'currencies->format', NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION', 'national', 6, 5, NULL, '', NULL, 'xtc_cfg_select_option(array(\'national\', \'international\', \'both\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true',  6, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1',  6, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true',  6, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '5',  6, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true',  6, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '6',  6, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_DISCOUNT_STATUS', 'true',  6, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_DISCOUNT_SORT_ORDER', '2', 6, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_SUBTOTAL_NO_TAX_STATUS', 'true',  6, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'MODULE_ORDER_TOTAL_SUBTOTAL_NO_TAX_SORT_ORDER','4',  6, 2, NULL, '', NULL, NULL);


# configuration_group_id 7
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SHIPPING_ORIGIN_COUNTRY', '81',  7, 1, NULL, '', 'xtc_get_country_name', 'xtc_cfg_pull_down_country_list(');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SHIPPING_ORIGIN_ZIP', '',  7, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SHIPPING_MAX_WEIGHT', '50',  7, 3, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SHIPPING_BOX_WEIGHT', '3',  7, 4, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SHIPPING_BOX_PADDING', '10',  7, 5, NULL, '', NULL, NULL);

# configuration_group_id 8
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'PRODUCT_LIST_FILTER', '1', 8, 1, NULL, '', NULL, NULL);

# configuration_group_id 9
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STOCK_CHECK', 'true',  9, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ATTRIBUTE_STOCK_CHECK', 'true',  9, 2, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STOCK_LIMITED', 'true', 9, 3, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STOCK_ALLOW_CHECKOUT', 'true',  9, 4, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '***',  9, 5, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STOCK_REORDER_LEVEL', '5',  9, 6, NULL, '', NULL, NULL);

# configuration_group_id 10
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_PAGE_PARSE_TIME', 'false',  10, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_PAGE_PARSE_TIME_LOG', '/var/log/www/tep/page_parse_time.log',  10, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S', 10, 3, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DISPLAY_PAGE_PARSE_TIME', 'true',  10, 4, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'STORE_DB_TRANSACTIONS', 'false',  10, 5, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');

# configuration_group_id 11
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'USE_CACHE', 'true',  11, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DIR_FS_CACHE', 'cache',  11, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CACHE_LIFETIME', '3600',  11, 3, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CACHE_CHECK', 'true',  11, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');

# configuration_group_id 12
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_TRANSPORT', 'sendmail',  12, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'sendmail\', \'smtp\', \'mail\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SENDMAIL_PATH', '/usr/sbin/sendmail', 12, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SMTP_MAIN_SERVER', 'localhost', 12, 3, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SMTP_Backup_Server', 'localhost', 12, 4, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SMTP_PORT', '25', 12, 5, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SMTP_USERNAME', 'Please Enter', 12, 6, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SMTP_PASSWORD', 'Please Enter', 12, 7, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SMTP_AUTH', 'false', 12, 8, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_LINEFEED', 'LF',  12, 9, NULL, '', NULL, 'xtc_cfg_select_option(array(\'LF\', \'CRLF\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_USE_HTML', 'false',  12, 10, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'ENTRY_EMAIL_ADDRESS_CHECK', 'false',  12, 11, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SEND_EMAILS', 'true',  12, 12, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');

# Constants for contact_us
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CONTACT_US_EMAIL_ADDRESS', 'contact@your-shop.com', 12, 20, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CONTACT_US_NAME', 'Mail send by Contact_us Form',  12, 21, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CONTACT_US_REPLY_ADDRESS',  '', 12, 22, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CONTACT_US_REPLY_ADDRESS_NAME',  '', 12, 23, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CONTACT_US_EMAIL_SUBJECT',  '', 12, 24, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'CONTACT_US_FORWARDING_STRING',  '', 12, 25, NULL, '', NULL, NULL);

# Constants for support system
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_SUPPORT_ADDRESS', 'support@your-shop.com', 12, 26, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_SUPPORT_NAME', 'Mail send by support systems',  12, 27, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_SUPPORT_REPLY_ADDRESS',  '', 12, 28, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_SUPPORT_REPLY_ADDRESS_NAME',  '', 12, 29, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_SUPPORT_SUBJECT',  '', 12, 30, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_SUPPORT_FORWARDING_STRING',  '', 12, 31, NULL, '', NULL, NULL);

# Constants for billing system
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_BILLING_ADDRESS', 'billing@your-shop.com', 12, 32, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_BILLING_NAME', 'Mail send by billing systems',  12, 33, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_BILLING_REPLY_ADDRESS',  '', 12, 34, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_BILLING_REPLY_ADDRESS_NAME',  '', 12, 35, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_BILLING_SUBJECT',  '', 12, 36, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_BILLING_FORWARDING_STRING',  '', 12, 37, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'EMAIL_BILLING_SUBJECT_ORDER',  'Your order Nr:{$nr} / {$date}', 12, 38, NULL, '', NULL, NULL);

# configuration_group_id 13
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DOWNLOAD_ENABLED', 'false',  13, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DOWNLOAD_BY_REDIRECT', 'false',  13, 2, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DOWNLOAD_MAX_DAYS', '7',  13, 3, NULL, '', NULL, '');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DOWNLOAD_MAX_COUNT', '5',  13, 4, NULL, '', NULL, '');

# configuration_group_id 14
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'GZIP_COMPRESSION', 'false',  14, 1, NULL, '', NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'GZIP_LEVEL', '5',  14, 2, NULL, '', NULL, NULL);

# configuration_group_id 15
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SESSION_WRITE_DIRECTORY', '/tmp',  15, 1, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SESSION_FORCE_COOKIE_USE', 'False',  15, 2, NULL, '', NULL, 'xtc_cfg_select_option(array(\'True\', \'False\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SESSION_CHECK_SSL_SESSION_ID', 'False',  15, 3, NULL, '', NULL, 'xtc_cfg_select_option(array(\'True\', \'False\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SESSION_CHECK_USER_AGENT', 'False',  15, 4, NULL, '', NULL, 'xtc_cfg_select_option(array(\'True\', \'False\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SESSION_CHECK_IP_ADDRESS', 'False',  15, 5, NULL, '', NULL, 'xtc_cfg_select_option(array(\'True\', \'False\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SESSION_BLOCK_SPIDERS', 'False',  15, 6, NULL, '', NULL, 'xtc_cfg_select_option(array(\'True\', \'False\'),');
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'SESSION_RECREATE', 'False',  15, 7, NULL, '', NULL, 'xtc_cfg_select_option(array(\'True\', \'False\'),');

# configuration_group_id 16
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'DISPLAY_CONDITIONS_ON_CHECKOUT', 'true',16, 1, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_MIN_KEYWORD_LENGTH', '6', 16, 2, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_KEYWORDS_NUMBER', '5',  16, 3, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_AUTHOR', '',  16, 4, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_PUBLISHER', '',  16, 5, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_COMPANY', '',  16, 6, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_TOPIC', 'shopping',  16, 7, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_REPLY_TO', 'xx@xx.com',  16, 8, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_REVISIT_AFTER', '14',  16, 9, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_ROBOTS', 'index,follow',  16, 10, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_DESCRIPTION', '',  16, 11, NULL, '', NULL, NULL);
INSERT INTO configuration (configuration_id,  configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES   ('', 'META_KEYWORDS', '',  16, 12, NULL, '', NULL, NULL);



INSERT INTO configuration_group VALUES ('1', 'My Store', 'General information about my store', '1', '1');
INSERT INTO configuration_group VALUES ('2', 'Minimum Values', 'The minimum values for functions / data', '2', '1');
INSERT INTO configuration_group VALUES ('3', 'Maximum Values', 'The maximum values for functions / data', '3', '1');
INSERT INTO configuration_group VALUES ('4', 'Images', 'Image parameters', '4', '1');
INSERT INTO configuration_group VALUES ('5', 'Customer Details', 'Customer account configuration', '5', '1');
INSERT INTO configuration_group VALUES ('6', 'Module Options', 'Hidden from configuration', '6', '0');
INSERT INTO configuration_group VALUES ('7', 'Shipping/Packaging', 'Shipping options available at my store', '7', '1');
INSERT INTO configuration_group VALUES ('8', 'Product Listing', 'Product Listing    configuration options', '8', '1');
INSERT INTO configuration_group VALUES ('9', 'Stock', 'Stock configuration options', '9', '1');
INSERT INTO configuration_group VALUES ('10', 'Logging', 'Logging configuration options', '10', '1');
INSERT INTO configuration_group VALUES ('11', 'Cache', 'Caching configuration options', '11', '1');
INSERT INTO configuration_group VALUES ('12', 'E-Mail Options', 'General setting for E-Mail transport and HTML E-Mails', '12', '1');
INSERT INTO configuration_group VALUES ('13', 'Download', 'Downloadable products options', '13', '1');
INSERT INTO configuration_group VALUES ('14', 'GZip Compression', 'GZip compression options', '14', '1');
INSERT INTO configuration_group VALUES ('15', 'Sessions', 'Session options', '15', '1');
INSERT INTO configuration_group VALUES ('16', 'Meta-Tags/Search engines', 'Meta-tags/Search engines', '16', '1');

INSERT INTO countries VALUES (1,'Afghanistan','AF','AFG','1');
INSERT INTO countries VALUES (2,'Albania','AL','ALB','1');
INSERT INTO countries VALUES (3,'Algeria','DZ','DZA','1');
INSERT INTO countries VALUES (4,'American Samoa','AS','ASM','1');
INSERT INTO countries VALUES (5,'Andorra','AD','AND','1');
INSERT INTO countries VALUES (6,'Angola','AO','AGO','1');
INSERT INTO countries VALUES (7,'Anguilla','AI','AIA','1');
INSERT INTO countries VALUES (8,'Antarctica','AQ','ATA','1');
INSERT INTO countries VALUES (9,'Antigua and Barbuda','AG','ATG','1');
INSERT INTO countries VALUES (10,'Argentina','AR','ARG','1');
INSERT INTO countries VALUES (11,'Armenia','AM','ARM','1');
INSERT INTO countries VALUES (12,'Aruba','AW','ABW','1');
INSERT INTO countries VALUES (13,'Australia','AU','AUS','1');
INSERT INTO countries VALUES (14,'Austria','AT','AUT','5');
INSERT INTO countries VALUES (15,'Azerbaijan','AZ','AZE','1');
INSERT INTO countries VALUES (16,'Bahamas','BS','BHS','1');
INSERT INTO countries VALUES (17,'Bahrain','BH','BHR','1');
INSERT INTO countries VALUES (18,'Bangladesh','BD','BGD','1');
INSERT INTO countries VALUES (19,'Barbados','BB','BRB','1');
INSERT INTO countries VALUES (20,'Belarus','BY','BLR','1');
INSERT INTO countries VALUES (21,'Belgium','BE','BEL','1');
INSERT INTO countries VALUES (22,'Belize','BZ','BLZ','1');
INSERT INTO countries VALUES (23,'Benin','BJ','BEN','1');
INSERT INTO countries VALUES (24,'Bermuda','BM','BMU','1');
INSERT INTO countries VALUES (25,'Bhutan','BT','BTN','1');
INSERT INTO countries VALUES (26,'Bolivia','BO','BOL','1');
INSERT INTO countries VALUES (27,'Bosnia and Herzegowina','BA','BIH','1');
INSERT INTO countries VALUES (28,'Botswana','BW','BWA','1');
INSERT INTO countries VALUES (29,'Bouvet Island','BV','BVT','1');
INSERT INTO countries VALUES (30,'Brazil','BR','BRA','1');
INSERT INTO countries VALUES (31,'British Indian Ocean Territory','IO','IOT','1');
INSERT INTO countries VALUES (32,'Brunei Darussalam','BN','BRN','1');
INSERT INTO countries VALUES (33,'Bulgaria','BG','BGR','1');
INSERT INTO countries VALUES (34,'Burkina Faso','BF','BFA','1');
INSERT INTO countries VALUES (35,'Burundi','BI','BDI','1');
INSERT INTO countries VALUES (36,'Cambodia','KH','KHM','1');
INSERT INTO countries VALUES (37,'Cameroon','CM','CMR','1');
INSERT INTO countries VALUES (38,'Canada','CA','CAN','1');
INSERT INTO countries VALUES (39,'Cape Verde','CV','CPV','1');
INSERT INTO countries VALUES (40,'Cayman Islands','KY','CYM','1');
INSERT INTO countries VALUES (41,'Central African Republic','CF','CAF','1');
INSERT INTO countries VALUES (42,'Chad','TD','TCD','1');
INSERT INTO countries VALUES (43,'Chile','CL','CHL','1');
INSERT INTO countries VALUES (44,'China','CN','CHN','1');
INSERT INTO countries VALUES (45,'Christmas Island','CX','CXR','1');
INSERT INTO countries VALUES (46,'Cocos (Keeling) Islands','CC','CCK','1');
INSERT INTO countries VALUES (47,'Colombia','CO','COL','1');
INSERT INTO countries VALUES (48,'Comoros','KM','COM','1');
INSERT INTO countries VALUES (49,'Congo','CG','COG','1');
INSERT INTO countries VALUES (50,'Cook Islands','CK','COK','1');
INSERT INTO countries VALUES (51,'Costa Rica','CR','CRI','1');
INSERT INTO countries VALUES (52,'Cote D\'Ivoire','CI','CIV','1');
INSERT INTO countries VALUES (53,'Croatia','HR','HRV','1');
INSERT INTO countries VALUES (54,'Cuba','CU','CUB','1');
INSERT INTO countries VALUES (55,'Cyprus','CY','CYP','1');
INSERT INTO countries VALUES (56,'Czech Republic','CZ','CZE','1');
INSERT INTO countries VALUES (57,'Denmark','DK','DNK','1');
INSERT INTO countries VALUES (58,'Djibouti','DJ','DJI','1');
INSERT INTO countries VALUES (59,'Dominica','DM','DMA','1');
INSERT INTO countries VALUES (60,'Dominican Republic','DO','DOM','1');
INSERT INTO countries VALUES (61,'East Timor','TP','TMP','1');
INSERT INTO countries VALUES (62,'Ecuador','EC','ECU','1');
INSERT INTO countries VALUES (63,'Egypt','EG','EGY','1');
INSERT INTO countries VALUES (64,'El Salvador','SV','SLV','1');
INSERT INTO countries VALUES (65,'Equatorial Guinea','GQ','GNQ','1');
INSERT INTO countries VALUES (66,'Eritrea','ER','ERI','1');
INSERT INTO countries VALUES (67,'Estonia','EE','EST','1');
INSERT INTO countries VALUES (68,'Ethiopia','ET','ETH','1');
INSERT INTO countries VALUES (69,'Falkland Islands (Malvinas)','FK','FLK','1');
INSERT INTO countries VALUES (70,'Faroe Islands','FO','FRO','1');
INSERT INTO countries VALUES (71,'Fiji','FJ','FJI','1');
INSERT INTO countries VALUES (72,'Finland','FI','FIN','1');
INSERT INTO countries VALUES (73,'France','FR','FRA','1');
INSERT INTO countries VALUES (74,'France, Metropolitan','FX','FXX','1');
INSERT INTO countries VALUES (75,'French Guiana','GF','GUF','1');
INSERT INTO countries VALUES (76,'French Polynesia','PF','PYF','1');
INSERT INTO countries VALUES (77,'French Southern Territories','TF','ATF','1');
INSERT INTO countries VALUES (78,'Gabon','GA','GAB','1');
INSERT INTO countries VALUES (79,'Gambia','GM','GMB','1');
INSERT INTO countries VALUES (80,'Georgia','GE','GEO','1');
INSERT INTO countries VALUES (81,'Germany','DE','DEU','5');
INSERT INTO countries VALUES (82,'Ghana','GH','GHA','1');
INSERT INTO countries VALUES (83,'Gibraltar','GI','GIB','1');
INSERT INTO countries VALUES (84,'Greece','GR','GRC','1');
INSERT INTO countries VALUES (85,'Greenland','GL','GRL','1');
INSERT INTO countries VALUES (86,'Grenada','GD','GRD','1');
INSERT INTO countries VALUES (87,'Guadeloupe','GP','GLP','1');
INSERT INTO countries VALUES (88,'Guam','GU','GUM','1');
INSERT INTO countries VALUES (89,'Guatemala','GT','GTM','1');
INSERT INTO countries VALUES (90,'Guinea','GN','GIN','1');
INSERT INTO countries VALUES (91,'Guinea-bissau','GW','GNB','1');
INSERT INTO countries VALUES (92,'Guyana','GY','GUY','1');
INSERT INTO countries VALUES (93,'Haiti','HT','HTI','1');
INSERT INTO countries VALUES (94,'Heard and Mc Donald Islands','HM','HMD','1');
INSERT INTO countries VALUES (95,'Honduras','HN','HND','1');
INSERT INTO countries VALUES (96,'Hong Kong','HK','HKG','1');
INSERT INTO countries VALUES (97,'Hungary','HU','HUN','1');
INSERT INTO countries VALUES (98,'Iceland','IS','ISL','1');
INSERT INTO countries VALUES (99,'India','IN','IND','1');
INSERT INTO countries VALUES (100,'Indonesia','ID','IDN','1');
INSERT INTO countries VALUES (101,'Iran (Islamic Republic of)','IR','IRN','1');
INSERT INTO countries VALUES (102,'Iraq','IQ','IRQ','1');
INSERT INTO countries VALUES (103,'Ireland','IE','IRL','1');
INSERT INTO countries VALUES (104,'Israel','IL','ISR','1');
INSERT INTO countries VALUES (105,'Italy','IT','ITA','1');
INSERT INTO countries VALUES (106,'Jamaica','JM','JAM','1');
INSERT INTO countries VALUES (107,'Japan','JP','JPN','1');
INSERT INTO countries VALUES (108,'Jordan','JO','JOR','1');
INSERT INTO countries VALUES (109,'Kazakhstan','KZ','KAZ','1');
INSERT INTO countries VALUES (110,'Kenya','KE','KEN','1');
INSERT INTO countries VALUES (111,'Kiribati','KI','KIR','1');
INSERT INTO countries VALUES (112,'Korea, Democratic People\'s Republic of','KP','PRK','1');
INSERT INTO countries VALUES (113,'Korea, Republic of','KR','KOR','1');
INSERT INTO countries VALUES (114,'Kuwait','KW','KWT','1');
INSERT INTO countries VALUES (115,'Kyrgyzstan','KG','KGZ','1');
INSERT INTO countries VALUES (116,'Lao People\'s Democratic Republic','LA','LAO','1');
INSERT INTO countries VALUES (117,'Latvia','LV','LVA','1');
INSERT INTO countries VALUES (118,'Lebanon','LB','LBN','1');
INSERT INTO countries VALUES (119,'Lesotho','LS','LSO','1');
INSERT INTO countries VALUES (120,'Liberia','LR','LBR','1');
INSERT INTO countries VALUES (121,'Libyan Arab Jamahiriya','LY','LBY','1');
INSERT INTO countries VALUES (122,'Liechtenstein','LI','LIE','1');
INSERT INTO countries VALUES (123,'Lithuania','LT','LTU','1');
INSERT INTO countries VALUES (124,'Luxembourg','LU','LUX','1');
INSERT INTO countries VALUES (125,'Macau','MO','MAC','1');
INSERT INTO countries VALUES (126,'Macedonia, The Former Yugoslav Republic of','MK','MKD','1');
INSERT INTO countries VALUES (127,'Madagascar','MG','MDG','1');
INSERT INTO countries VALUES (128,'Malawi','MW','MWI','1');
INSERT INTO countries VALUES (129,'Malaysia','MY','MYS','1');
INSERT INTO countries VALUES (130,'Maldives','MV','MDV','1');
INSERT INTO countries VALUES (131,'Mali','ML','MLI','1');
INSERT INTO countries VALUES (132,'Malta','MT','MLT','1');
INSERT INTO countries VALUES (133,'Marshall Islands','MH','MHL','1');
INSERT INTO countries VALUES (134,'Martinique','MQ','MTQ','1');
INSERT INTO countries VALUES (135,'Mauritania','MR','MRT','1');
INSERT INTO countries VALUES (136,'Mauritius','MU','MUS','1');
INSERT INTO countries VALUES (137,'Mayotte','YT','MYT','1');
INSERT INTO countries VALUES (138,'Mexico','MX','MEX','1');
INSERT INTO countries VALUES (139,'Micronesia, Federated States of','FM','FSM','1');
INSERT INTO countries VALUES (140,'Moldova, Republic of','MD','MDA','1');
INSERT INTO countries VALUES (141,'Monaco','MC','MCO','1');
INSERT INTO countries VALUES (142,'Mongolia','MN','MNG','1');
INSERT INTO countries VALUES (143,'Montserrat','MS','MSR','1');
INSERT INTO countries VALUES (144,'Morocco','MA','MAR','1');
INSERT INTO countries VALUES (145,'Mozambique','MZ','MOZ','1');
INSERT INTO countries VALUES (146,'Myanmar','MM','MMR','1');
INSERT INTO countries VALUES (147,'Namibia','NA','NAM','1');
INSERT INTO countries VALUES (148,'Nauru','NR','NRU','1');
INSERT INTO countries VALUES (149,'Nepal','NP','NPL','1');
INSERT INTO countries VALUES (150,'Netherlands','NL','NLD','1');
INSERT INTO countries VALUES (151,'Netherlands Antilles','AN','ANT','1');
INSERT INTO countries VALUES (152,'New Caledonia','NC','NCL','1');
INSERT INTO countries VALUES (153,'New Zealand','NZ','NZL','1');
INSERT INTO countries VALUES (154,'Nicaragua','NI','NIC','1');
INSERT INTO countries VALUES (155,'Niger','NE','NER','1');
INSERT INTO countries VALUES (156,'Nigeria','NG','NGA','1');
INSERT INTO countries VALUES (157,'Niue','NU','NIU','1');
INSERT INTO countries VALUES (158,'Norfolk Island','NF','NFK','1');
INSERT INTO countries VALUES (159,'Northern Mariana Islands','MP','MNP','1');
INSERT INTO countries VALUES (160,'Norway','NO','NOR','1');
INSERT INTO countries VALUES (161,'Oman','OM','OMN','1');
INSERT INTO countries VALUES (162,'Pakistan','PK','PAK','1');
INSERT INTO countries VALUES (163,'Palau','PW','PLW','1');
INSERT INTO countries VALUES (164,'Panama','PA','PAN','1');
INSERT INTO countries VALUES (165,'Papua New Guinea','PG','PNG','1');
INSERT INTO countries VALUES (166,'Paraguay','PY','PRY','1');
INSERT INTO countries VALUES (167,'Peru','PE','PER','1');
INSERT INTO countries VALUES (168,'Philippines','PH','PHL','1');
INSERT INTO countries VALUES (169,'Pitcairn','PN','PCN','1');
INSERT INTO countries VALUES (170,'Poland','PL','POL','1');
INSERT INTO countries VALUES (171,'Portugal','PT','PRT','1');
INSERT INTO countries VALUES (172,'Puerto Rico','PR','PRI','1');
INSERT INTO countries VALUES (173,'Qatar','QA','QAT','1');
INSERT INTO countries VALUES (174,'Reunion','RE','REU','1');
INSERT INTO countries VALUES (175,'Romania','RO','ROM','1');
INSERT INTO countries VALUES (176,'Russian Federation','RU','RUS','1');
INSERT INTO countries VALUES (177,'Rwanda','RW','RWA','1');
INSERT INTO countries VALUES (178,'Saint Kitts and Nevis','KN','KNA','1');
INSERT INTO countries VALUES (179,'Saint Lucia','LC','LCA','1');
INSERT INTO countries VALUES (180,'Saint Vincent and the Grenadines','VC','VCT','1');
INSERT INTO countries VALUES (181,'Samoa','WS','WSM','1');
INSERT INTO countries VALUES (182,'San Marino','SM','SMR','1');
INSERT INTO countries VALUES (183,'Sao Tome and Principe','ST','STP','1');
INSERT INTO countries VALUES (184,'Saudi Arabia','SA','SAU','1');
INSERT INTO countries VALUES (185,'Senegal','SN','SEN','1');
INSERT INTO countries VALUES (186,'Seychelles','SC','SYC','1');
INSERT INTO countries VALUES (187,'Sierra Leone','SL','SLE','1');
INSERT INTO countries VALUES (188,'Singapore','SG','SGP', '4');
INSERT INTO countries VALUES (189,'Slovakia (Slovak Republic)','SK','SVK','1');
INSERT INTO countries VALUES (190,'Slovenia','SI','SVN','1');
INSERT INTO countries VALUES (191,'Solomon Islands','SB','SLB','1');
INSERT INTO countries VALUES (192,'Somalia','SO','SOM','1');
INSERT INTO countries VALUES (193,'South Africa','ZA','ZAF','1');
INSERT INTO countries VALUES (194,'South Georgia and the South Sandwich Islands','GS','SGS','1');
INSERT INTO countries VALUES (195,'Spain','ES','ESP','3');
INSERT INTO countries VALUES (196,'Sri Lanka','LK','LKA','1');
INSERT INTO countries VALUES (197,'St. Helena','SH','SHN','1');
INSERT INTO countries VALUES (198,'St. Pierre and Miquelon','PM','SPM','1');
INSERT INTO countries VALUES (199,'Sudan','SD','SDN','1');
INSERT INTO countries VALUES (200,'Suriname','SR','SUR','1');
INSERT INTO countries VALUES (201,'Svalbard and Jan Mayen Islands','SJ','SJM','1');
INSERT INTO countries VALUES (202,'Swaziland','SZ','SWZ','1');
INSERT INTO countries VALUES (203,'Sweden','SE','SWE','1');
INSERT INTO countries VALUES (204,'Switzerland','CH','CHE','1');
INSERT INTO countries VALUES (205,'Syrian Arab Republic','SY','SYR','1');
INSERT INTO countries VALUES (206,'Taiwan','TW','TWN','1');
INSERT INTO countries VALUES (207,'Tajikistan','TJ','TJK','1');
INSERT INTO countries VALUES (208,'Tanzania, United Republic of','TZ','TZA','1');
INSERT INTO countries VALUES (209,'Thailand','TH','THA','1');
INSERT INTO countries VALUES (210,'Togo','TG','TGO','1');
INSERT INTO countries VALUES (211,'Tokelau','TK','TKL','1');
INSERT INTO countries VALUES (212,'Tonga','TO','TON','1');
INSERT INTO countries VALUES (213,'Trinidad and Tobago','TT','TTO','1');
INSERT INTO countries VALUES (214,'Tunisia','TN','TUN','1');
INSERT INTO countries VALUES (215,'Turkey','TR','TUR','1');
INSERT INTO countries VALUES (216,'Turkmenistan','TM','TKM','1');
INSERT INTO countries VALUES (217,'Turks and Caicos Islands','TC','TCA','1');
INSERT INTO countries VALUES (218,'Tuvalu','TV','TUV','1');
INSERT INTO countries VALUES (219,'Uganda','UG','UGA','1');
INSERT INTO countries VALUES (220,'Ukraine','UA','UKR','1');
INSERT INTO countries VALUES (221,'United Arab Emirates','AE','ARE','1');
INSERT INTO countries VALUES (222,'United Kingdom','GB','GBR','1');
INSERT INTO countries VALUES (223,'United States','US','USA', '2');
INSERT INTO countries VALUES (224,'United States Minor Outlying Islands','UM','UMI','1');
INSERT INTO countries VALUES (225,'Uruguay','UY','URY','1');
INSERT INTO countries VALUES (226,'Uzbekistan','UZ','UZB','1');
INSERT INTO countries VALUES (227,'Vanuatu','VU','VUT','1');
INSERT INTO countries VALUES (228,'Vatican City State (Holy See)','VA','VAT','1');
INSERT INTO countries VALUES (229,'Venezuela','VE','VEN','1');
INSERT INTO countries VALUES (230,'Viet Nam','VN','VNM','1');
INSERT INTO countries VALUES (231,'Virgin Islands (British)','VG','VGB','1');
INSERT INTO countries VALUES (232,'Virgin Islands (U.S.)','VI','VIR','1');
INSERT INTO countries VALUES (233,'Wallis and Futuna Islands','WF','WLF','1');
INSERT INTO countries VALUES (234,'Western Sahara','EH','ESH','1');
INSERT INTO countries VALUES (235,'Yemen','YE','YEM','1');
INSERT INTO countries VALUES (236,'Yugoslavia','YU','YUG','1');
INSERT INTO countries VALUES (237,'Zaire','ZR','ZAR','1');
INSERT INTO countries VALUES (238,'Zambia','ZM','ZMB','1');
INSERT INTO countries VALUES (239,'Zimbabwe','ZW','ZWE','1');

INSERT INTO currencies VALUES (1,'Euro','EUR','','EUR','.',',','2','1.0000', now());


INSERT INTO languages VALUES (1,'English','en','icon.gif','english',1,'iso-8859-15');
INSERT INTO languages VALUES (2,'Deutsch','de','icon.gif','german',2,'iso-8859-15');

INSERT INTO manufacturers VALUES (1,'Matrox','manufacturer_matrox.gif', now(), null);
INSERT INTO manufacturers VALUES (2,'Microsoft','manufacturer_microsoft.gif', now(), null);
INSERT INTO manufacturers VALUES (3,'Warner','manufacturer_warner.gif', now(), null);
INSERT INTO manufacturers VALUES (4,'Fox','manufacturer_fox.gif', now(), null);
INSERT INTO manufacturers VALUES (5,'Logitech','manufacturer_logitech.gif', now(), null);
INSERT INTO manufacturers VALUES (6,'Canon','manufacturer_canon.gif', now(), null);
INSERT INTO manufacturers VALUES (7,'Sierra','manufacturer_sierra.gif', now(), null);
INSERT INTO manufacturers VALUES (8,'GT Interactive','manufacturer_gt_interactive.gif', now(), null);
INSERT INTO manufacturers VALUES (9,'Hewlett Packard','manufacturer_hewlett_packard.gif', now(), null);

INSERT INTO manufacturers_info VALUES (1, 1, 'http://www.matrox.com', 0, null);
INSERT INTO manufacturers_info VALUES (1, 2, 'http://www.matrox.de', 0, null);
INSERT INTO manufacturers_info VALUES (2, 1, 'http://www.microsoft.com', 0, null);
INSERT INTO manufacturers_info VALUES (2, 2, 'http://www.microsoft.de', 0, null);
INSERT INTO manufacturers_info VALUES (3, 1, 'http://www.warner.com', 0, null);
INSERT INTO manufacturers_info VALUES (3, 2, 'http://www.warner.de', 0, null);
INSERT INTO manufacturers_info VALUES (4, 1, 'http://www.fox.com', 0, null);
INSERT INTO manufacturers_info VALUES (4, 2, 'http://www.fox.de', 0, null);
INSERT INTO manufacturers_info VALUES (5, 1, 'http://www.logitech.com', 0, null);
INSERT INTO manufacturers_info VALUES (5, 2, 'http://www.logitech.com', 0, null);
INSERT INTO manufacturers_info VALUES (6, 1, 'http://www.canon.com', 0, null);
INSERT INTO manufacturers_info VALUES (6, 2, 'http://www.canon.de', 0, null);
INSERT INTO manufacturers_info VALUES (7, 1, 'http://www.sierra.com', 0, null);
INSERT INTO manufacturers_info VALUES (7, 2, 'http://www.sierra.de', 0, null);
INSERT INTO manufacturers_info VALUES (8, 1, 'http://www.infogrames.com', 0, null);
INSERT INTO manufacturers_info VALUES (8, 2, 'http://www.infogrames.de', 0, null);
INSERT INTO manufacturers_info VALUES (9, 1, 'http://www.hewlettpackard.com', 0, null);
INSERT INTO manufacturers_info VALUES (9, 2, 'http://www.hewlettpackard.de', 0, null);

INSERT INTO orders_status VALUES ( '1', '1', 'Pending');
INSERT INTO orders_status VALUES ( '1', '2', 'Offen');
INSERT INTO orders_status VALUES ( '2', '1', 'Processing');
INSERT INTO orders_status VALUES ( '2', '2', 'In Bearbeitung');
INSERT INTO orders_status VALUES ( '3', '1', 'Delivered');
INSERT INTO orders_status VALUES ( '3', '2', 'Versendet');

INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (1, 32, 'MG200MMS', 'matrox/mg200mms.gif', '299.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '23.00', 1, 1, 1, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (2, 32, 'MG400-32MB', 'matrox/mg400-32mb.gif', '499.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '23.00', 1, 1, 1, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (3, 2, 'MSIMPRO', 'microsoft/msimpro.gif', '49.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (4, 13, 'DVD-RPMK', 'dvd/replacement_killers.gif', '42.0000', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '23.00', 1, 1, 2, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (5, 17, 'DVD-BLDRNDC', 'dvd/blade_runner.gif', '35.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (6, 10, 'DVD-MATR', 'dvd/the_matrix.gif', '39.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (7, 10, 'DVD-YGEM', 'dvd/youve_got_mail.gif', '34.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (8, 10, 'DVD-ABUG', 'dvd/a_bugs_life.gif', '35.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (9, 10, 'DVD-UNSG', 'dvd/under_siege.gif', '29.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (10, 10, 'DVD-UNSG2', 'dvd/under_siege2.gif', '29.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (11, 10, 'DVD-FDBL', 'dvd/fire_down_below.gif', '29.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (12, 10, 'DVD-DHWV', 'dvd/die_hard_3.gif', '39.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 4, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (13, 10, 'DVD-LTWP', 'dvd/lethal_weapon.gif', '34.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (14, 10, 'DVD-REDC', 'dvd/red_corner.gif', '32.0000', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (15, 10, 'DVD-FRAN', 'dvd/frantic.gif', '35.0000', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (16, 10, 'DVD-CUFI', 'dvd/courage_under_fire.gif', '38.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 4, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (17, 10, 'DVD-SPEED', 'dvd/speed.gif', '39.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 4, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (18, 10, 'DVD-SPEED2', 'dvd/speed_2.gif', '42.0000', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 4, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (19, 10, 'DVD-TSAB', 'dvd/theres_something_about_mary.gif', '49.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 4, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (20, 10, 'DVD-BELOVED', 'dvd/beloved.gif', '54.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 3, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (21, 16, 'PC-SWAT3', 'sierra/swat_3.gif', '79.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 7, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (22, 13, 'PC-UNTM', 'gt_interactive/unreal_tournament.gif', '89.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '7.00', 1, 1, 8, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (23, 16, 'PC-TWOF', 'gt_interactive/wheel_of_time.gif', '99.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '10.00', 1, 1, 8, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (24, 17, 'PC-DISC', 'gt_interactive/disciples.gif', '90.0000', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '8.00', 1, 1, 8, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (25, 16, 'MSINTKB', 'microsoft/intkeyboardps2.gif', '69.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '8.00', 1, 1, 2, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (26, 10, 'MSIMEXP', 'microsoft/imexplorer.gif', '64.9500', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '8.00', 1, 1, 2, 0);
INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_discount_allowed, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (27, 8, 'HPLJ1100XI', 'hewlett_packard/lj1100xi.gif', '499.9900', '0.00', '2003-05-25 05:45:57', NULL, '0000-00-00 00:00:00', '45.00', 1, 1, 9, 0);


INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (1, 1, 'Matrox G200 MMS', 'Reinforcing its position as a multi-monitor trailblazer, Matrox Graphics Inc. has once again developed the most flexible and highly advanced solution in the industry. Introducing the new Matrox G200 Multi-Monitor Series; the first graphics card ever to support up to four DVI digital flat panel displays on a single 8&quot; PCI board.<br><br>With continuing demand for digital flat panels in the financial workplace, the Matrox G200 MMS is the ultimate in flexible solutions. The Matrox G200 MMS also supports the new digital video interface (DVI) created by the Digital Display Working Group (DDWG) designed to ease the adoption of digital flat panels. Other configurations include composite video capture ability and onboard TV tuner, making the Matrox G200 MMS the complete solution for business needs.<br><br>Based on the award-winning MGA-G200 graphics chip, the Matrox G200 Multi-Monitor Series provides superior 2D/3D graphics acceleration to meet the demanding needs of business applications such as real-time stock quotes (Versus), live video feeds (Reuters & Bloombergs), multiple windows applications, word processing, spreadsheets and CAD.', NULL, 'www.matrox.com/mga/products/g200_mms/home.cfm', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (2, 1, 'Matrox G400 32MB', '<b>Dramatically Different High Performance Graphics</b><br><br>Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry\'s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC\'s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br><br><b>Key features:</b><ul><li>New Matrox G400 256-bit DualBus graphics chip</li><li>Explosive 3D, 2D and DVD performance</li><li>DualHead Display</li><li>Superior DVD and TV output</li><li>3D Environment-Mapped Bump Mapping</li><li>Vibrant Color Quality rendering </li><li>UltraSharp DAC of up to 360 MHz</li><li>3D Rendering Array Processor</li><li>Support for 16 or 32 MB of memory</li></ul>', NULL, 'www.matrox.com/mga/products/mill_g400/home.htm', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (3, 1, 'Microsoft IntelliMouse Pro', 'Every element of IntelliMouse Pro - from its unique arched shape to the texture of the rubber grip around its base - is the product of extensive customer and ergonomic research. Microsoft\'s popular wheel control, which now allows zooming and universal scrolling functions, gives IntelliMouse Pro outstanding comfort and efficiency.', NULL, 'www.microsoft.com/hardware/mouse/intellimouse.asp', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (4, 1, 'The Replacement Killers', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 80 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, 'www.replacement-killers.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (5, 1, 'Blade Runner - Director\'s Cut', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>Languages: English, Deutsch.<br>Subtitles: English, Deutsch, Spanish.<br>Audio: Dolby Surround 5.1.<br>Picture Format: 16:9 Wide-Screen.<br>Length: (approx) 112 minutes.<br>Other: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, 'www.bladerunner.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (6, 1, 'The Matrix', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch.\r<br>\nAudio: Dolby Surround.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 131 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Making Of.', NULL, 'www.thematrix.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (7, 1, 'You\'ve Got Mail', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch, Spanish.\r<br>\nSubtitles: English, Deutsch, Spanish, French, Nordic, Polish.\r<br>\nAudio: Dolby Digital 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 115 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, 'www.youvegotmail.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (8, 1, 'A Bug\'s Life', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Digital 5.1 / Dobly Surround Stereo.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 91 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, 'www.abugslife.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (9, 1, 'Under Siege', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 98 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (10, 1, 'Under Siege 2 - Dark Territory', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 98 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (11, 1, 'Fire Down Below', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 100 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (12, 1, 'Die Hard With A Vengeance', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 122 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (13, 1, 'Lethal Weapon', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 100 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (14, 1, 'Red Corner', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 117 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (15, 1, 'Frantic', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 115 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (16, 1, 'Courage Under Fire', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 112 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (17, 1, 'Speed', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 112 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (18, 1, 'Speed 2: Cruise Control', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 120 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (19, 1, 'There\'s Something About Mary', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 114 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (20, 1, 'Beloved', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br>\nLanguages: English, Deutsch.\r<br>\nSubtitles: English, Deutsch, Spanish.\r<br>\nAudio: Dolby Surround 5.1.\r<br>\nPicture Format: 16:9 Wide-Screen.\r<br>\nLength: (approx) 164 minutes.\r<br>\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (21, 1, 'SWAT 3: Close Quarters Battle', '<b>Windows 95/98</b><br><br>211 in progress with shots fired. Officer down. Armed suspects with hostages. Respond Code 3! Los Angles, 2005, In the next seven days, representatives from every nation around the world will converge on Las Angles to witness the signing of the United Nations Nuclear Abolishment Treaty. The protection of these dignitaries falls on the shoulders of one organization, LAPD SWAT. As part of this elite tactical organization, you and your team have the weapons and all the training necessary to protect, to serve, and "When needed" to use deadly force to keep the peace. It takes more than weapons to make it through each mission. Your arsenal includes C2 charges, flashbangs, tactical grenades. opti-Wand mini-video cameras, and other devices critical to meeting your objectives and keeping your men free of injury. Uncompromised Duty, Honor and Valor!', NULL, 'www.swat3.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (22, 1, 'Unreal Tournament', 'From the creators of the best-selling Unreal, comes Unreal Tournament. A new kind of single player experience. A ruthless multiplayer revolution.<br><br>This stand-alone game showcases completely new team-based gameplay, groundbreaking multi-faceted single player action or dynamic multi-player mayhem. It\'s a fight to the finish for the title of Unreal Grand Master in the gladiatorial arena. A single player experience like no other! Guide your team of \'bots\' (virtual teamates) against the hardest criminals in the galaxy for the ultimate title - the Unreal Grand Master.', NULL, 'www.unrealtournament.net', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (23, 1, 'The Wheel Of Time', 'The world in which The Wheel of Time takes place is lifted directly out of Jordan\'s pages; it\'s huge and consists of many different environments. How you navigate the world will depend largely on which game - single player or multipayer - you\'re playing. The single player experience, with a few exceptions, will see Elayna traversing the world mainly by foot (with a couple notable exceptions). In the multiplayer experience, your character will have more access to travel via Ter\'angreal, Portal Stones, and the Ways. However you move around, though, you\'ll quickly discover that means of locomotion can easily become the least of the your worries...<br><br>During your travels, you quickly discover that four locations are crucial to your success in the game. Not surprisingly, these locations are the homes of The Wheel of Time\'s main characters. Some of these places are ripped directly from the pages of Jordan\'s books, made flesh with Legend\'s unparalleled pixel-pushing ways. Other places are specific to the game, conceived and executed with the intent of expanding this game world even further. Either way, they provide a backdrop for some of the most intense first person action and strategy you\'ll have this year.', NULL, 'www.wheeloftime.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (24, 1, 'Disciples: Sacred Lands', 'A new age is dawning...<br><br>Enter the realm of the Sacred Lands, where the dawn of a New Age has set in motion the most momentous of wars. As the prophecies long foretold, four races now clash with swords and sorcery in a desperate bid to control the destiny of their gods. Take on the quest as a champion of the Empire, the Mountain Clans, the Legions of the Damned, or the Undead Hordes and test your faith in battles of brute force, spellbinding magic and acts of guile. Slay demons, vanquish giants and combat merciless forces of the dead and undead. But to ensure the salvation of your god, the hero within must evolve.<br><br>The day of reckoning has come... and only the chosen will survive.', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (25, 1, 'Microsoft Internet Keyboard PS/2', 'The Internet Keyboard has 10 Hot Keys on a comfortable standard keyboard design that also includes a detachable palm rest. The Hot Keys allow you to browse the web, or check e-mail directly from your keyboard. The IntelliType Pro software also allows you to customize your hot keys - make the Internet Keyboard work the way you want it to!', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (26, 1, 'Microsoft IntelliMouse Explorer', 'Microsoft introduces its most advanced mouse, the IntelliMouse Explorer! IntelliMouse Explorer features a sleek design, an industrial-silver finish, a glowing red underside and taillight, creating a style and look unlike any other mouse. IntelliMouse Explorer combines the accuracy and reliability of Microsoft IntelliEye optical tracking technology, the convenience of two new customizable function buttons, the efficiency of the scrolling wheel and the comfort of expert ergonomic design. All these great features make this the best mouse for the PC!', NULL, 'www.microsoft.com/hardware/mouse/explorer.asp', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (27, 1, 'Hewlett Packard LaserJet 1100Xi', 'HP has always set the pace in laser printing technology. The new generation HP LaserJet 1100 series sets another impressive pace, delivering a stunning 8 pages per minute print speed. The 600 dpi print resolution with HP\'s Resolution Enhancement technology (REt) makes every document more professional.<br><br>Enhanced print speed and laser quality results are just the beginning. With 2MB standard memory, HP LaserJet 1100xi users will be able to print increasingly complex pages. Memory can be increased to 18MB to tackle even more complex documents with ease. The HP LaserJet 1100xi supports key operating systems including Windows 3.1, 3.11, 95, 98, NT 4.0, OS/2 and DOS. Network compatibility available via the optional HP JetDirect External Print Servers.<br><br>HP LaserJet 1100xi also features The Document Builder for the Web Era from Trellix Corp. (featuring software to create Web documents).', NULL, 'www.pandi.hp.com/pandi-db/prodinfo.main?product=laserjet1100', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (1, 2, 'Matrox G200 MMS', '<b>Unterstützung für zwei bzw. vier analoge oder digitale Monitore</b><br><br>\r\nDie Matrox G200 Multi-Monitor-Serie führt die bewährte Matrox Tradition im Multi-Monitor- Bereich fort und bietet flexible und fortschrittliche Lösungen.Matrox stellt als erstes Unternehmen einen vierfachen digitalen PanelLink® DVI Flachbildschirm Ausgang zur Verfügung. Mit den optional erhältlichen TV Tuner und Video-Capture Möglichkeiten stellt die Matrox G200 MMS eine alles umfassende Mehrschirm-Lösung dar.<br><br>\r\n<b>Leistungsmerkmale:</b>\r\n<ul>\r\n<li>Preisgekrönter Matrox G200 128-Bit Grafikchip</li>\r\n<li>Schneller 8 MB SGRAM-Speicher pro Kanal</li>\r\n<li>Integrierter, leistungsstarker 250 MHz RAMDAC</li>\r\n<li>Unterstützung für bis zu 16 Bildschirme über 4 Quad-Karten (unter Win NT,ab Treiber 4.40)</li>\r\n<li>Unterstützung von 9 Monitoren unter Win 98</li>\r\n<li>2 bzw. 4 analoge oder digitale Ausgabekanäle pro PCI-Karte</li>\r\n<li>Desktop-Darstellung von 1800 x 1440 oder 1920 x 1200 pro Chip</li>\r\n<li>Anschlußmöglichkeit an einen 15-poligen VGA-Monitor oder an einen 30-poligen digitalen DVI-Flachbildschirm plus integriertem Composite-Video-Eingang (bei der TV-Version)</li>\r\n<li>PCI Grafikkarte, kurze Baulänge</li>\r\n<li>Treiberunterstützung: Windows® 2000, Windows NT® und Windows® 98</li>\r\n<li>Anwendungsbereiche: Börsenumgebung mit zeitgleich großem Visualisierungsbedarf, Videoüberwachung, Video-Conferencing</li>\r\n</ul>', NULL, 'www.matrox.com/mga/deutsch/products/g200_mms/home.cfm', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (2, 2, 'Matrox G400 32 MB', '<b>Neu! Matrox G400 &quot;all inclusive&quot; und vieles mehr...</b><br><br>\r\nDie neue Millennium G400-Serie - Hochleistungsgrafik mit dem sensationellen Unterschied. Ausgestattet mit dem neu eingeführten Matrox G400 Grafikchip, bietet die Millennium G400-Serie eine überragende Beschleunigung inklusive bisher nie dagewesener Bildqualitat und enorm flexibler Darstellungsoptionen bei allen Ihren 3D-, 2D- und DVD-Anwendungen.<br><br>\r\n<ul>\r\n<li>DualHead Display und TV-Ausgang</li>\r\n<li>Environment Mapped Bump Mapping</li>\r\n<li>Matrox G400 256-Bit DualBus</li>\r\n<li>3D Rendering Array Prozessor</li>\r\n<li>Vibrant Color Quality² (VCQ²)</li>\r\n<li>UltraSharp DAC</li>\r\n</ul>\r\n<i>&quot;Bleibt abschließend festzustellen, daß die Matrox Millennium G400 Max als Allroundkarte für den Highend-PC derzeit unerreicht ist. Das ergibt den Testsieg und unsere wärmste Empfehlung.&quot;</i><br>\r\n<b>GameStar 8/99 (S.184)</b>', NULL, 'www.matrox.com/mga/deutsch/products/mill_g400/home.cfm', 1);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (3, 2, 'Microsoft IntelliMouse Pro', 'Die IntelliMouse Pro hat mit der IntelliRad-Technologie einen neuen Standard gesetzt. Anwenderfreundliche Handhabung und produktiveres Arbeiten am PC zeichnen die IntelliMouse aus. Die gewölbte Oberseite paßt sich natürlich in die Handfläche ein, die geschwungene Form erleichtert das Bedienen der Maus. Sie ist sowohl für Rechts- wie auch für Linkshänder geeignet. Mit dem Rad der IntelliMouse kann der Anwender einfach und komfortabel durch Dokumente navigieren.<br><br>\r\n<b>Eigenschaften:</b>\r\n<ul>\r\n<li><b>ANSCHLUSS:</b> PS/2</li>\r\n<li><b>FARBE:</b> weiß</li>\r\n<li><b>TECHNIK:</b> Mauskugel</li>\r\n<li><b>TASTEN:</b> 3 (incl. Scrollrad)</li>\r\n<li><b>SCROLLRAD:</b> Ja</li>\r\n</ul>', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (4, 2, 'Die Ersatzkiller', 'Originaltitel: &quot;The Replacement Killers&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 80 minutes.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\n(USA 1998). Til Schweiger schießt auf Hongkong-Star Chow Yun-Fat (&quot;Anna und der König&quot;) ­ ein Fehler ...', NULL, 'www.replacement-killers.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (5, 2, 'Blade Runner - Director\'s Cut', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 112 minutes.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\n<b>Sci-Fi-Klassiker, USA 1983, 112 Min.</b><br><br>\r\nLos Angeles ist im Jahr 2019 ein Hexenkessel. Dauerregen und Smog tauchen den überbevölkerten Moloch in ewige Dämmerung. Polizeigleiter schwirren durch die Luft und überwachen das grelle Ethnogemisch, das sich am Fuße 400stöckiger Stahlbeton-Pyramiden tummelt. Der abgehalfterte Ex-Cop und "Blade Runner" Rick Deckard ist Spezialist für die Beseitigung von Replikanten, künstlichen Menschen, geschaffen für harte Arbeit auf fremden Planeten. Nur ihm kann es gelingen, vier flüchtige, hochintelligente "Nexus 6"-Spezialmodelle zu stellen. Die sind mit ihrem starken und brandgefährlichen Anführer Batty auf der Suche nach ihrem Schöpfer. Er soll ihnen eine längere Lebenszeit schenken. Das muß Rick Deckard verhindern.  Als sich der eiskalte Jäger in Rachel, die Sekretärin seines Auftraggebers, verliebt, gerät sein Weltbild jedoch ins Wanken. Er entdeckt, daß sie - vielleicht wie er selbst - ein Replikant ist ...<br><br>\r\nDie Konfrontation des Menschen mit "Realität" und "Virtualität" und das verstrickte Spiel mit getürkten Erinnerungen zieht sich als roter Faden durch das Werk von Autor Philip K. Dick ("Die totale Erinnerung"). Sein Roman "Träumen Roboter von elektrischen Schafen?" liefert die Vorlage für diesen doppelbödigen Thriller, der den Zuschauer mit faszinierenden\r\nZukunftsvisionen und der gigantischen Kulisse des Großstadtmolochs gefangen nimmt.', NULL, 'www.bladerunner.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (6, 2, 'Matrix', 'Originaltitel: &quot;The Matrix&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 136 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\n(USA 1999) Der Geniestreich der Wachowski-Brüder. In dieser technisch perfekten Utopie kämpft Hacker Keanu Reeves gegen die Diktatur der Maschinen...', NULL, 'www.whatisthematrix.com', 1);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (7, 2, 'e-m@il für Dich', 'Original: &quot;You\'ve got mail&quot;<br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 112 minutes.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\n(USA 1998) von Nora Ephron (&qout;Schlaflos in Seattle&quot;). Meg Ryan und Tom Hanks knüpfen per E-Mail zarte Bande. Dass sie sich schon kennen, ahnen sie nicht ...', NULL, 'www.youvegotmail.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (8, 2, 'Das Große Krabbeln', 'Originaltitel: &quot;A Bug\'s Life&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\n(USA 1998). Ameise Flik zettelt einen Aufstand gegen gefräßige Grashüpfer an ... Eine fantastische Computeranimation des "Toy Story"-Teams. ', NULL, 'www.abugslife.com', 1);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (9, 2, 'Alarmstufe: Rot', 'Originaltitel: &quot;Under Siege&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\n<b>Actionthriller. Smutje Steven Seagal versalzt Schurke Tommy Lee Jones die Suppe</b><br><br>\r\nKatastrophe ahoi: Kurz vor Ausmusterung der "U.S.S. Missouri" kapert die High-tech-Bande von Ex-CIA-Agent Strannix (Tommy Lee Jones) das Schlachtschiff. Strannix will die Nuklearraketen des Kreuzers klauen und verscherbeln. Mit Hilfe des irren Ersten Offiziers Krill (lustig: Gary Busey) killen die Gangster den Käptn und sperren seine Crew unter Deck. Blöd, dass sie dabei Schiffskoch Rybak (Steven Seagal) vergessen. Der Ex-Elitekämpfer knipst einen Schurken nach dem anderen aus. Eine Verbündete findet er in Stripperin Jordan (Ex-"Baywatch"-Biene Erika Eleniak). Die sollte eigentlich aus Käptns Geburtstagstorte hüpfen ... Klar: Seagal ist kein Edelmime. Dafür ist Regisseur Andrew Davis ("Auf der Flucht") ein Könner: Er würzt die Action-Orgie mit Ironie und nutzt die imposante Schiffskulisse voll aus. Für Effekte und Ton gab es 1993 Oscar-Nominierungen. ', NULL, '', 4);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (10, 2, 'Alarmstufe: Rot 2', 'Originaltitel: &quot;Under Siege 2: Dark Territory&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\n(USA 95). Von einem gekaperten Zug aus übernimmt Computerspezi Dane die Kontrolle über einen Kampfsatelliten und erpresst das Pentagon. Aber auch Ex-Offizier Ryback (Steven Seagal) ist im Zug ...\r\n', NULL, '', 1);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (11, 2, 'Fire Down Below', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\nEin mysteriöser Mordfall führt den Bundesmarschall Jack Taggert in eine Kleinstadt im US-Staat Kentucky. Doch bei seinen Ermittlungen stößt er auf eine Mauer des Schweigens. Angst beherrscht die Stadt, und alle Spuren führen zu dem undurchsichtigen Minen-Tycoon Orin Hanner. Offenbar werden in der friedlichen Berglandschaft gigantische Mengen Giftmülls verschoben, mit unkalkulierbaren Risiken. Um eine Katastrophe zu verhindern, räumt Taggert gnadenlos auf ...', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (12, 2, 'Stirb Langsam - Jetzt Erst Recht', 'Originaltitel: &quot;Die Hard With A Vengeance&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\nSo explosiv, so spannend, so rasant wie nie zuvor: Bruce Willis als Detectiv John McClane in einem Action-Thriller der Superlative! Das ist heute nicht McClanes Tag. Seine Frau hat ihn verlassen, sein Boß hat ihn vom Dienst suspendiert und irgendein Verrückter hat ihn gerade zum Gegenspieler in einem teuflischen Spiel erkoren - und der Einsatz ist New York selbst. Ein Kaufhaus ist explodiert, doch das ist nur der Auftakt. Der geniale Superverbrecher Simon droht, die ganze Stadt Stück für Stück in die Luft zu sprengen, wenn McClane und sein Partner wider Willen seine explosiven" Rätsel nicht lösen. Eine mörderische Hetzjagd quer durch New York beginnt - bis McClane merkt, daß der Bombenterror eigentlich nur ein brillantes Ablenkungsmanöver ist...!<br><i>"Perfekt gemacht und stark besetzt. Das Action-Highlight des Jahres!"</i> <b>(Bild)</b>', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (13, 2, 'Zwei stahlharte Profis', 'Originaltitel: &quot;Lethal Weapon&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\nSie sind beide Cops in L.A.. Sie haben beide in Vietnam für eine Spezialeinheit gekämpft. Und sie hassen es beide, mit einem Partner arbeiten zu müssen. Aber sie sind Partner: Martin Riggs, der Mann mit dem Todeswunsch, für wen auch immer. Und Roger Murtaugh, der besonnene Polizist. Gemeinsam enttarnen sie einen gigantischen Heroinschmuggel, hinter dem sich eine Gruppe ehemaliger CIA-Söldner verbirgt. Eine Killerbande gegen zwei Profis ...', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (14, 2, 'Labyrinth ohne Ausweg', 'Originaltitel: &quot;Red Corner&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\nDem Amerikaner Jack Moore wird in China der bestialische Mord an einem Fotomodel angehängt. Brutale Gefängnisschergen versuchen, ihn durch Folter zu einem Geständnis zu zwingen. Vor Gericht fordert die Anklage die Todesstrafe - Moore\'s Schicksal scheint besiegelt. Durch einen Zufall gelingt es ihm, aus der Haft zu fliehen, doch aus der feindseligen chinesischen Hauptstadt gibt es praktisch kein Entkommen ...', NULL, '', 1);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (15, 2, 'Frantic', 'Originaltitel: &quot;Frantic&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\nEin romantischer Urlaub in Paris, der sich in einen Alptraum verwandelt. Ein Mann auf der verzweifelten Suche nach seiner entführten Frau. Ein düster-bedrohliches Paris, in dem nur ein Mensch Licht in die tödliche Affäre bringen kann.', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (16, 2, 'Mut Zur Wahrheit', 'Originaltitel: &quot;Courage Under Fire&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\nLieutenant Colonel Nathaniel Serling (Denzel Washington) lässt während einer Schlacht im Golfkrieg versehentlich auf einen US-Panzer schießen, dessen Mannschaft dabei ums Leben kommt. Ein Jahr nach diesem Vorfall wird Serling, der mittlerweile nach Washington D.C. versetzt wurde, die Aufgabe übertragen, sich um einen Kandidaten zu kümmern, der während des Krieges starb und dem der höchste militärische Orden zuteil werden soll. Allerdings sind sowohl der Fall und als auch der betreffende Soldat ein politisch heißes Eisen -- Captain Karen Walden (Meg Ryan) ist Amerikas erster weiblicher Soldat, der im Kampf getötet wurde.<br><br>\r\nSerling findet schnell heraus, dass es im Fall des im felsigen Gebiet von Kuwait abgestürzten Rettungshubschraubers Diskrepanzen gibt. In Flashbacks werden von unterschiedlichen Personen verschiedene Versionen von Waldens Taktik, die Soldaten zu retten und den Absturz zu überleben, dargestellt (à la Kurosawas Rashomon). Genau wie in Glory erweist sich Regisseur Edward Zwicks Zusammenstellung von bekannten und unbekannten Schauspielern als die richtige Mischung. Waldens Crew ist besonders überzeugend. Matt Damon als der Sanitäter kommt gut als der leichtfertige Typ rüber, als er Washington seine Geschichte erzählt. Im Kampf ist er ein mit Fehlern behafteter, humorvoller Soldat.<br><br>\r\nDie erstaunlichste Arbeit in Mut zur Wahrheit leistet Lou Diamond Phillips (als der Schütze der Gruppe), dessen Karriere sich auf dem Weg in die direkt für den Videomarkt produzierten Filme befand. Und dann ist da noch Ryan. Sie hat sich in dramatischen Filmen in der Vergangenheit gut behauptet (Eine fast perfekte Liebe, Ein blutiges Erbe), es aber nie geschafft, ihrem Image zu entfliehen, das sie in die Ecke der romantischen Komödie steckte. Mit gefärbtem Haar, einem leichten Akzent und der von ihr geforderten Darstellungskunst hat sie endlich einen langlebigen dramatischen Film. Obwohl sie nur halb so oft wie Washington im Film zu sehen ist, macht ihre mutige und beeindruckend nachhaltige Darstellung Mut zur Wahrheit zu einem speziellen Film bis hin zu dessen seltsamer, aber lohnender letzter Szene.', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (17, 2, 'Speed', 'Originaltitel: &quot;Speed&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\nEr ist ein Cop aus der Anti-Terror-Einheit von Los Angeles. Und so ist der Alarm für Jack Traven nichts Ungewöhnliches: Ein Terrorist will drei Millionen Dollar erpressen, oder die zufälligen Geiseln in einem Aufzug fallen 35 Stockwerke in die Tiefe. Doch Jack schafft das Unmögliche - die Geiseln werden gerettet und der Terrorist stirbt an seiner eigenen Bombe. Scheinbar. Denn schon wenig später steht Jack (Keanu Reeves) dem Bombenexperten Payne erneut gegenüber. Diesmal hat sich der Erpresser eine ganz perfide Mordwaffe ausgedacht: Er plaziert eine Bombe in einem öffentlichen Bus. Der Mechanismus der Sprengladung schaltet sich automatisch ein, sobald der Bus schneller als 50 Meilen in der Stunde fährt und detoniert sofort, sobald die Geschwindigkeit sinkt. Plötzlich wird für eine Handvoll ahnungsloser Durchschnittsbürger der Weg zur Arbeit zum Höllentrip - und nur Jack hat ihr Leben in der Hand. Als der Busfahrer verletzt wird, übernimmt Fahrgast Annie (Sandra Bullock) das Steuer. Doch wohin mit einem Bus, der nicht bremsen kann in der Stadt der Staus? Doch es kommt noch schlimmer: Payne (Dennis Hopper) will jetzt nicht nur seine drei Millionen Dollar. Er will Jack. Um jeden Preis.', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (18, 2, 'Speed 2: Cruise Control', 'Originaltitel: &quot;Speed 2 - Cruise Control&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\nHalten Sie ihre Schwimmwesten bereit, denn die actiongeladene Fortsetzung von Speed begibt sich auf Hochseekurs. Erleben Sie Sandra Bullock erneut in ihrer Star-Rolle als Annie Porter. Die Karibik-Kreuzfahrt mit ihrem Freund Alex entwickelt sich unaufhaltsam zur rasenden Todesfahrt, als ein wahnsinniger Computer-Spezialist den Luxusliner in seine Gewalt bringt und auf einen mörderischen Zerstörungskurs programmiert. Eine hochexplosive Reise, bei der kein geringerer als Action-Spezialist Jan De Bont das Ruder in die Hand nimmt. Speed 2: Cruise Controll läßt ihre Adrenalin-Wellen in rasender Geschwindigkeit ganz nach oben schnellen.', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (19, 2, 'Verrückt nach Mary', 'Originaltitel: &quot;There\'s Something About Mary&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\n13 Jahre nachdem Teds Rendezvous mit seiner angebeteten Mary in einem peinlichen Fiasko endete, träumt er immer noch von ihr und engagiert den windigen Privatdetektiv Healy um sie aufzuspüren. Der findet Mary in Florida und verliebt sich auf den ersten Blick in die atemberaubende Traumfrau. Um Ted als Nebenbuhler auszuschalten, tischt er ihm dicke Lügen über Mary auf. Ted läßt sich jedoch nicht abschrecken, eilt nach Miami und versucht nun alles, um Healy die Balztour zu vermasseln. Doch nicht nur Healy ist verrückt nach Mary und Ted bekommt es mit einer ganzen Legion liebeskranker Konkurrenten zu tun ...', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (20, 2, 'Menschenkind', 'Originaltitel: &quot;Beloved&quot;<br><br>\r\nRegional Code: 2 (Japan, Europe, Middle East, South Africa).<br>\r\nSprachen: English, Deutsch.<br>\r\nUntertitel: English, Deutsch, Spanish.<br>\r\nAudio: Dolby Surround 5.1.<br>\r\nBildformat: 16:9 Wide-Screen.<br>\r\nDauer: (approx) 96 minuten.<br>\r\nAußerdem: Interaktive Menus, Kapitelauswahl, Untertitel.<br><br>\r\nDieser vielschichtige Film ist eine Arbeit, die Regisseur Jonathan Demme und dem amerikanischen Talkshow-Star Oprah Winfrey sehr am Herzen lag. Der Film deckt im Verlauf seiner dreistündigen Spielzeit viele Bereiche ab. Menschenkind ist teils Sklavenepos, teils Mutter-Tochter-Drama und teils Geistergeschichte.<br><br>\r\nDer Film fordert vom Publikum höchste Aufmerksamkeit, angefangen bei seiner dramatischen und etwas verwirrenden Eingangssequenz, in der die Bewohner eines Hauses von einem niederträchtigen übersinnlichen Angriff heimgesucht werden. Aber Demme und seine talentierte Besetzung bereiten denen, die dabei bleiben ein unvergessliches Erlebnis. Der Film folgt den Spuren von Sethe (in ihren mittleren Jahren von Oprah Winfrey dargestellt), einer ehemaligen Sklavin, die sich scheinbar ein friedliches und produktives Leben in Ohio aufgebaut hat. Aber durch den erschreckenden und sparsamen Einsatz von Rückblenden deckt Demme, genau wie das literarische Meisterwerk von Toni Morrison, auf dem der Film basiert, langsam die Schrecken von Sethes früherem Leben auf und das schreckliche Ereignis, dass dazu führte, dass Sethes Haus von Geistern heimgesucht wird.<br><br>\r\nWährend die Gräuel der Sklaverei und das blutige Ereignis in Sethes Familie unleugbar tief beeindrucken, ist die Qualität des Film auch in kleineren, genauso befriedigenden Details sichtbar. Die geistlich beeinflusste Musik von Rachel Portman ist gleichzeitig befreiend und bedrückend, und der Einblick in die afro-amerikanische Gemeinschaft nach der Sklaverei -- am Beispiel eines Familienausflugs zu einem Jahrmarkt, oder dem gospelsingenden Nähkränzchen -- machen diesen Film zu einem speziellen Vergnügen sowohl für den Geist als auch für das Herz. Die Schauspieler, besonders Kimberley Elise als Sethes kämpfende Tochter und Thandie Newton als der mysteriöse Titelcharakter, sind sehr rührend. Achten Sie auch auf Danny Glover (Lethal Weapon) als Paul D.', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (21, 2, 'SWAT 3: Elite Edition', '<b>KEINE KOMPROMISSE!</b><br><i>Kämpfen Sie Seite an Seite mit Ihren LAPD SWAT-Kameraden gegen das organisierte Verbrechen!</i><br><br>\r\nEine der realistischsten 3D-Taktiksimulationen der letzten Zeit jetzt mit Multiplayer-Modus, 5 neuen Missionen und jede Menge nützliche Tools!<br><br>\r\nLos Angeles, 2005. In wenigen Tagen steht die Unterzeichnung des Abkommens der Vereinten Nationen zur Atom-Ächtung durch Vertreter aller Nationen der Welt an. Radikale terroristische Vereinigungen machen sich in der ganzen Stadt breit. Verantwortlich für die Sicherheit der Delegierten zeichnet sich eine Eliteeinheit der LAPD: das SWAT-Team. Das Schicksal der Stadt liegt in Ihren Händen.<br><br>\r\n<b>Neue Features:</b>\r\n<ul>\r\n<li>Multiplayer-Modus (Co op-Modus, Deathmatch in den bekannten Variationen)</li>\r\n<li>5 neue Missionen an original Örtlichkeiten Las (U-Bahn, Whitman Airport, etc.)</li>\r\n<li>neue Charakter</li>\r\n<li>neue Skins</li>\r\n<li>neue Waffen</li>\r\n<li>neue Sounds</li>\r\n<li>verbesserte KI</li>\r\n<li>Tools-Package</li>\r\n<li>Scenario-Editor</li>\r\n<li>Level-Editor</li>\r\n</ul>\r\nDie dritte Folge der Bestseller-Reihe im Bereich der 3D-Echtzeit-Simulationen präsentiert sich mit einer neuen Spielengine mit extrem ausgeprägtem Realismusgrad. Der Spieler übernimmt das Kommando über eine der besten Polizei-Spezialeinheiten oder einer der übelsten Terroristen-Gangs der Welt. Er durchläuft - als "Guter" oder "Böser" - eine der härtesten und elitärsten Kampfausbildungen, in der er lernt, mit jeder Art von Krisensituationen umzugehen. Bei diesem Action-Abenteuer geht es um das Leben prominenter Vertreter der Vereinten Nationen und bei 16 Missionen an Originalschauplätzen in LA gibt die "lebensechte" KI den Protagonisten jeder Seite so einige harte Nüsse zu knacken.', NULL, 'www.swat3.com', 1);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (22, 2, 'Unreal Tournament', '2341: Die Gewalt ist eine Lebensweise, die sich ihren Weg durch die dunklen Risse der Gesellschaft bahnt. Sie bedroht die Macht und den Einfluss der regierenden Firmen, die schnellstens ein Mittel finden müssen, die tobenden Massen zu besänftigen - ein profitables Mittel ... Gladiatorenkämpfe sind die Lösung. Sie sollen den Durst der Menschen nach Blut stillen und sind die perfekte Gelegenheit, die Aufständischen, Kriminellen und Aliens zu beseitigen, die die Firmenelite bedrohen.<br><br>\r\nDas Turnier war geboren - ein Kampf auf Leben und Tod. Galaxisweit live und in Farbe! Kämpfen Sie für Freiheit, Ruhm und Ehre. Sie müssen stark, schnell und geschickt sein ... oder Sie bleiben auf der Strecke.<br><br>\r\nKämpfen Sie allein oder kommandieren Sie ein Team gegen Armeen unbarmherziger Krieger, die alle nur ein Ziel vor Augen haben: Die Arenen lebend zu verlassen und sich dem Grand Champion zu stellen ... um ihn dann zu bezwingen!<br><br>\r\n<b>Features:</b>\r\n<ul>\r\n<li>Auf dem PC mehrfach als Spiel des Jahres ausgezeichnet!</li>\r\n<li>Mehr als 50 faszinierende Level - verlassene Raumstationen, gotische Kathedralen und graffitibedeckte Großstädte.</li>\r\n<li>Vier actionreiche Spielmodi - Deathmatch, Capture the Flag, Assault und Domination werden Ihren Adrenalinpegel in die Höhe schnellen lassen.</li>\r\n<li>Dramatische Mehrspieler-Kämpfe mit 2, 3 und 4 Spielern, auch über das Netzwerk</li>\r\n<li>Gnadenlos aggressive Computergegner verlangen Ihnen das Äußerste ab.</li>\r\n<li>Neuartiges Benutzerinterface und verbesserte Steuerung - auch mit USB-Maus und -Tastatur spielbar.</li>\r\n</ul>\r\nDer Nachfolger des Actionhits "Unreal" verspricht ein leichtes, intuitives Interface, um auch Einsteigern schnellen Zugang zu den Duellen gegen die Bots zu ermöglichen. Mit diesen KI-Kriegern kann man auch Teams bilden und im umfangreichen Multiplayermodus ohne Onlinekosten in den Kampf ziehen. 35 komplett neue Arenen und das erweiterte Waffenangebot bilden dazu den würdigen Rahmen.', NULL, 'www.unrealtournament.net', 1);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (23, 2, 'The Wheel Of Time', '<b><i>"Wheel Of Time"(Das Rad der Zeit)</i></b> basiert auf den Fantasy-Romanen von Kultautor Robert Jordan und stellt einen einzigartigen Mix aus Strategie-, Action- und Rollenspielelementen dar. Obwohl die Welt von "Wheel of Time" eng an die literarische Vorlage der Romane angelehnt ist, erzählt das Spiel keine lineare Geschichte. Die Story entwickelt sich abhängig von den Aktionen der Spieler, die jeweils die Rollen der Hauptcharaktere aus dem Roman übernehmen. Jede Figur hat den Oberbefehl über eine große Gefolgschaft, militärische Einheiten und Ländereien. Die Spieler können ihre eigenen Festungen konstruieren, individuell ausbauen, von dort aus das umliegende Land erforschen, magische Gegenstände sammeln oder die gegnerischen Zitadellen erstürmen. Selbstverständlich kann man sich auch über LAN oder Internet gegenseitig Truppen auf den Hals hetzen und die Festungen seiner Mitspieler in Schutt und Asche legen. Dreh- und Anlegepunkt von "Wheel of Time" ist der Kampf um die finstere Macht "The Dark One", die vor langer Zeit die Menschheit beinahe ins Verderben stürzte und nur mit Hilfe gewaltiger magischer Energie verbannt werden konnte. "The Amyrlin Seat" und "The Children of the Night" kämpfen nur gegen "The Forsaken" und "The Hound" um den Besitz des Schlüssels zu "Shayol Ghul" - dem magischen Siegel, mit dessen Hilfe "The Dark One" seinerzeit gebannt werden konnte.<br><br>\r\n<b>Features:</b> \r\n<ul>\r\n<li>Ego-Shooter mit Strategie-Elementen</li>\r\n<li>Spielumgebung in Echtzeit-3D</li>\r\n<li>Konstruktion aud Ausbau der eigenen Festung</li>\r\n<li>Einsatz von über 100 Artefakten und Zaubersprüchen</li>\r\n<li>Single- und Multiplayermodus</li>\r\n</ul>\r\nIm Mittelpunkt steht der Kampf gegen eine finstere Macht namens The Dark One. Deren Schergen müssen mit dem Einsatz von über 100 Artefakten und Zaubereien wie Blitzschlag oder Teleportation aus dem Weg geräumt werden. Die opulente 3D-Grafik verbindet Strategie- und Rollenspielelemente. \r\n\r\n<b>Voraussetzungen</b>\r\nmind. P200, 32MB RAM, 4x CD-Rom, Win95/98, DirectX 5.0 komp.Grafikkarte und Soundkarte. ', NULL, 'www.wheeloftime.com', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (24, 2, 'Disciples: Sacred Land', 'Rundenbasierende Fantasy/RTS-Strategie mit gutem Design (vor allem die Levels, 4 versch. Rassen, tolle Einheiten), fantastischer Atmosphäre und exzellentem Soundtrack. Grafisch leider auf das Niveau von 1990.', NULL, 'www.strategyfirst.com/disciples/welcome.html', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (25, 2, 'Microsoft Internet Tastatur PS/2', '<i>Microsoft Internet Keyboard,Windows-Tastatur mit 10 zusätzl. Tasten,2 selbst programmierbar, abnehmbareHandgelenkauflage. Treiber im Lieferumfang.</i><br><br>\r\nEin-Klick-Zugriff auf das Internet und vieles mehr! Das Internet Keyboard verfügt über 10 zusätzliche Abkürzungstasten auf einer benutzerfreundlichen Standardtastatur, die darüber hinaus eine abnehmbare Handballenauflage aufweist. Über die Abkürzungstasten können Sie durch das Internet surfen oder direkt von der Tastatur aus auf E-Mails zugreifen. Die IntelliType Pro-Software ermöglicht außerdem das individuelle Belegen der Tasten.', NULL, '', 0);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (26, 2, 'Microsof IntelliMouse Explorer', 'Die IntelliMouse Explorer überzeugt durch ihr modernes Design mit silberfarbenem Gehäuse, sowie rot schimmernder Unter- und Rückseite. Die neuartige IntelliEye-Technologie sorgt für eine noch nie dagewesene Präzision, da statt der beweglichen Teile (zum Abtasten der Bewegungsänderungen an der Unterseite der Maus) ein optischer Sensor die Bewegungen der Maus erfaßt. Das Herzstück der Microsoft IntelliEye-Technologie ist ein kleiner Chip, der einen optischen Sensor und einen digitalen Signalprozessor (DSP) enthält.<br><br>\r\nDa auf bewegliche Teile, die Staub, Schmutz und Fett aufnehmen können, verzichtet wurde, muß die IntelliMouse Explorer nicht mehr gereinigt werden. Darüber hinaus arbeitet die IntelliMouse Explorer auf nahezu jeder Arbeitsoberfläche, so daß kein Mauspad mehr erforderlich ist. Mit dem Rad und zwei neuen zusätzlichen Maustasten ermöglicht sie effizientes und komfortables Arbeiten am PC.<br><br>\r\n<b>Eigenschaften:</b>\r\n<ul>\r\n<li><b>ANSCHLUSS:</b> USB (PS/2-Adapter enthalten)</li>\r\n<li><b>FARBE:</b> metallic-grau</li>\r\n<li><b>TECHNIK:</b> Optisch (Akt.: ca. 1500 Bilder/s)</li>\r\n<li><b>TASTEN:</b> 5 (incl. Scrollrad)</li>\r\n<li><b>SCROLLRAD:</b> Ja</li>\r\n</ul>\r\n<i><b>BEMERKUNG:</b><br>Keine Reinigung bewegter Teile mehr notwendig, da statt der Mauskugel ein Fotoempfänger benutzt wird.</i>', NULL, '', 2);
INSERT INTO products_description (products_id, language_id, products_name, products_description, products_short_description, products_url, products_viewed) VALUES (27, 2, 'Hewlett-Packard LaserJet 1100Xi', '<b>HP LaserJet für mehr Produktivität und Flexibilität am Arbeitsplatz</b><br><br>\r\nDer HP LaserJet 1100Xi Drucker verbindet exzellente Laserdruckqualität mit hoher Geschwindigkeit für mehr Effizienz.<br><br>\r\n<b>Zielkunden</b>\r\n<ul><li>Einzelanwender, die Wert auf professionelle Ausdrucke in Laserqualität legen und schnelle Ergebnisse auch bei komplexen Dokumenten erwarten.</li>\r\n<li>Der HP LaserJet 1100 sorgt mit gestochen scharfen Texten und Grafiken für ein professionelles Erscheinungsbild Ihrer Arbeit und Ihres Unternehmens. Selbst bei komplexen Dokumenten liefert er schnelle Ergebnisse. Andere Medien - wie z.B. Transparentfolien und Briefumschläge, etc. - lassen sich problemlos bedrucken. Somit ist der HP LaserJet 1100 ein Multifunktionstalent im Büroalltag.</li>\r\n</ul>\r\n<b>Eigenschaften</b>\r\n<ul>\r\n<li><b>Druckqualität</b> Schwarzweiß: 600 x 600 dpi</li>\r\n<li><b>Monatliche Druckleistung</b> Bis zu 7000 Seiten</li>\r\n<li><b>Speicher</b> 2 MB Standardspeicher, erweiterbar auf 18 MB</li>\r\n<li><b>Schnittstelle/gemeinsame Nutzung</b> Parallel, IEEE 1284-kompatibel</li>\r\n<li><b>Softwarekompatibilität</b> DOS/Win 3.1x/9x/NT 4.0</li>\r\n<li><b>Papierzuführung</b> 125-Blatt-Papierzuführung</li>\r\n<li><b>Druckmedien</b> Normalpapier, Briefumschläge, Transparentfolien, kartoniertes Papier, Postkarten und Etiketten</li>\r\n<li><b>Netzwerkfähig</b> Über HP JetDirect PrintServer</li>\r\n<li><b>Lieferumfang</b> HP LaserJet 1100Xi Drucker (Lieferumfang: Drucker, Tonerkassette, 2 m Parallelkabel, Netzkabel, Kurzbedienungsanleitung, Benutzerhandbuch, CD-ROM, 3,5"-Disketten mit Windows® 3.1x, 9x, NT 4.0 Treibern und DOS-Dienstprogrammen)</li>\r\n<li><b>Gewährleistung</b> Ein Jahr</li>\r\n</ul>\r\n', NULL, 'www.hp.com', 0);

INSERT INTO products_attributes VALUES (1,1,4,1,0.00,'+');
INSERT INTO products_attributes VALUES (2,1,4,2,50.00,'+');
INSERT INTO products_attributes VALUES (3,1,4,3,70.00,'+');
INSERT INTO products_attributes VALUES (4,1,3,5,0.00,'+');
INSERT INTO products_attributes VALUES (5,1,3,6,100.00,'+');
INSERT INTO products_attributes VALUES (6,2,4,3,10.00,'-');
INSERT INTO products_attributes VALUES (7,2,4,4,0.00,'+');
INSERT INTO products_attributes VALUES (8,2,3,6,0.00,'+');
INSERT INTO products_attributes VALUES (9,2,3,7,120.00,'+');
INSERT INTO products_attributes VALUES (10,26,3,8,0.00,'+');
INSERT INTO products_attributes VALUES (11,26,3,9,6.00,'+');
INSERT INTO products_attributes VALUES (26, 22, 5, 10, '0.00', '+');
INSERT INTO products_attributes VALUES (27, 22, 5, 13, '0.00', '+');

INSERT INTO products_attributes_download VALUES (26, 'unreal.zip', 7, 3);

INSERT INTO products_options VALUES (1,1,'Color');
INSERT INTO products_options VALUES (2,1,'Size');
INSERT INTO products_options VALUES (3,1,'Model');
INSERT INTO products_options VALUES (4,1,'Memory');
INSERT INTO products_options VALUES (1,2,'Farbe');
INSERT INTO products_options VALUES (2,2,'Größe');
INSERT INTO products_options VALUES (3,2,'Modell');
INSERT INTO products_options VALUES (4,2,'Speicher');
INSERT INTO products_options VALUES (5, 2, 'Version');
INSERT INTO products_options VALUES (5, 1, 'Version');


INSERT INTO products_options_values VALUES (1,1,'4 mb');
INSERT INTO products_options_values VALUES (2,1,'8 mb');
INSERT INTO products_options_values VALUES (3,1,'16 mb');
INSERT INTO products_options_values VALUES (4,1,'32 mb');
INSERT INTO products_options_values VALUES (5,1,'Value');
INSERT INTO products_options_values VALUES (6,1,'Premium');
INSERT INTO products_options_values VALUES (7,1,'Deluxe');
INSERT INTO products_options_values VALUES (8,1,'PS/2');
INSERT INTO products_options_values VALUES (9,1,'USB');
INSERT INTO products_options_values VALUES (1,2,'4 MB');
INSERT INTO products_options_values VALUES (2,2,'8 MB');
INSERT INTO products_options_values VALUES (3,2,'16 MB');
INSERT INTO products_options_values VALUES (4,2,'32 MB');
INSERT INTO products_options_values VALUES (5,2,'Value Ausgabe');
INSERT INTO products_options_values VALUES (6,2,'Premium Ausgabe');
INSERT INTO products_options_values VALUES (7,2,'Deluxe Ausgabe');
INSERT INTO products_options_values VALUES (8,2,'PS/2 Anschluss');
INSERT INTO products_options_values VALUES (9,2,'USB Anschluss');
INSERT INTO products_options_values VALUES (10, 1, 'Download: Windows - English');
INSERT INTO products_options_values VALUES (10, 2, 'Download: Windows - Englisch');
INSERT INTO products_options_values VALUES (13, 1, 'Box: Windows - English');
INSERT INTO products_options_values VALUES (13, 2, 'Box: Windows - Englisch');


INSERT INTO products_options_values_to_products_options VALUES (1,4,1);
INSERT INTO products_options_values_to_products_options VALUES (2,4,2);
INSERT INTO products_options_values_to_products_options VALUES (3,4,3);
INSERT INTO products_options_values_to_products_options VALUES (4,4,4);
INSERT INTO products_options_values_to_products_options VALUES (5,3,5);
INSERT INTO products_options_values_to_products_options VALUES (6,3,6);
INSERT INTO products_options_values_to_products_options VALUES (7,3,7);
INSERT INTO products_options_values_to_products_options VALUES (8,3,8);
INSERT INTO products_options_values_to_products_options VALUES (9,3,9);
INSERT INTO products_options_values_to_products_options VALUES (10, 5, 10);
INSERT INTO products_options_values_to_products_options VALUES (13, 5, 13);

INSERT INTO products_to_categories (products_id, categories_id) VALUES (1, 4);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (2, 4);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (3, 9);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (4, 10);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (5, 11);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (6, 10);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (7, 12);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (8, 13);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (9, 10);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (10, 10);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (11, 10);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (12, 10);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (13, 10);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (14, 15);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (15, 14);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (16, 15);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (17, 10);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (18, 10);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (19, 12);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (20, 15);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (21, 18);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (22, 19);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (23, 20);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (24, 20);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (25, 8);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (26, 9);
INSERT INTO products_to_categories (products_id, categories_id) VALUES (27, 5);

INSERT INTO reviews VALUES (1,19,1,'A typical User',5, now(),'',0);

INSERT INTO reviews_description VALUES (1,1, 'this has to be one of the funniest movies released for 1999!');

INSERT INTO specials VALUES (1,3, 39.99, now(), '', '', '', '1');
INSERT INTO specials VALUES (2,5, 30.00, now(), '', '', '', '1');
INSERT INTO specials VALUES (3,6, 30.00, now(), '', '', '', '1');
INSERT INTO specials VALUES (4,16, 29.99, now(), '', '', '', '1');


# USA
INSERT INTO zones VALUES (1,223,'AL','Alabama');
INSERT INTO zones VALUES (2,223,'AK','Alaska');
INSERT INTO zones VALUES (3,223,'AS','American Samoa');
INSERT INTO zones VALUES (4,223,'AZ','Arizona');
INSERT INTO zones VALUES (5,223,'AR','Arkansas');
INSERT INTO zones VALUES (6,223,'AF','Armed Forces Africa');
INSERT INTO zones VALUES (7,223,'AA','Armed Forces Americas');
INSERT INTO zones VALUES (8,223,'AC','Armed Forces Canada');
INSERT INTO zones VALUES (9,223,'AE','Armed Forces Europe');
INSERT INTO zones VALUES (10,223,'AM','Armed Forces Middle East');
INSERT INTO zones VALUES (11,223,'AP','Armed Forces Pacific');
INSERT INTO zones VALUES (12,223,'CA','California');
INSERT INTO zones VALUES (13,223,'CO','Colorado');
INSERT INTO zones VALUES (14,223,'CT','Connecticut');
INSERT INTO zones VALUES (15,223,'DE','Delaware');
INSERT INTO zones VALUES (16,223,'DC','District of Columbia');
INSERT INTO zones VALUES (17,223,'FM','Federated States Of Micronesia');
INSERT INTO zones VALUES (18,223,'FL','Florida');
INSERT INTO zones VALUES (19,223,'GA','Georgia');
INSERT INTO zones VALUES (20,223,'GU','Guam');
INSERT INTO zones VALUES (21,223,'HI','Hawaii');
INSERT INTO zones VALUES (22,223,'ID','Idaho');
INSERT INTO zones VALUES (23,223,'IL','Illinois');
INSERT INTO zones VALUES (24,223,'IN','Indiana');
INSERT INTO zones VALUES (25,223,'IA','Iowa');
INSERT INTO zones VALUES (26,223,'KS','Kansas');
INSERT INTO zones VALUES (27,223,'KY','Kentucky');
INSERT INTO zones VALUES (28,223,'LA','Louisiana');
INSERT INTO zones VALUES (29,223,'ME','Maine');
INSERT INTO zones VALUES (30,223,'MH','Marshall Islands');
INSERT INTO zones VALUES (31,223,'MD','Maryland');
INSERT INTO zones VALUES (32,223,'MA','Massachusetts');
INSERT INTO zones VALUES (33,223,'MI','Michigan');
INSERT INTO zones VALUES (34,223,'MN','Minnesota');
INSERT INTO zones VALUES (35,223,'MS','Mississippi');
INSERT INTO zones VALUES (36,223,'MO','Missouri');
INSERT INTO zones VALUES (37,223,'MT','Montana');
INSERT INTO zones VALUES (38,223,'NE','Nebraska');
INSERT INTO zones VALUES (39,223,'NV','Nevada');
INSERT INTO zones VALUES (40,223,'NH','New Hampshire');
INSERT INTO zones VALUES (41,223,'NJ','New Jersey');
INSERT INTO zones VALUES (42,223,'NM','New Mexico');
INSERT INTO zones VALUES (43,223,'NY','New York');
INSERT INTO zones VALUES (44,223,'NC','North Carolina');
INSERT INTO zones VALUES (45,223,'ND','North Dakota');
INSERT INTO zones VALUES (46,223,'MP','Northern Mariana Islands');
INSERT INTO zones VALUES (47,223,'OH','Ohio');
INSERT INTO zones VALUES (48,223,'OK','Oklahoma');
INSERT INTO zones VALUES (49,223,'OR','Oregon');
INSERT INTO zones VALUES (50,223,'PW','Palau');
INSERT INTO zones VALUES (51,223,'PA','Pennsylvania');
INSERT INTO zones VALUES (52,223,'PR','Puerto Rico');
INSERT INTO zones VALUES (53,223,'RI','Rhode Island');
INSERT INTO zones VALUES (54,223,'SC','South Carolina');
INSERT INTO zones VALUES (55,223,'SD','South Dakota');
INSERT INTO zones VALUES (56,223,'TN','Tennessee');
INSERT INTO zones VALUES (57,223,'TX','Texas');
INSERT INTO zones VALUES (58,223,'UT','Utah');
INSERT INTO zones VALUES (59,223,'VT','Vermont');
INSERT INTO zones VALUES (60,223,'VI','Virgin Islands');
INSERT INTO zones VALUES (61,223,'VA','Virginia');
INSERT INTO zones VALUES (62,223,'WA','Washington');
INSERT INTO zones VALUES (63,223,'WV','West Virginia');
INSERT INTO zones VALUES (64,223,'WI','Wisconsin');
INSERT INTO zones VALUES (65,223,'WY','Wyoming');

# Canada
INSERT INTO zones VALUES (66,38,'AB','Alberta');
INSERT INTO zones VALUES (67,38,'BC','British Columbia');
INSERT INTO zones VALUES (68,38,'MB','Manitoba');
INSERT INTO zones VALUES (69,38,'NF','Newfoundland');
INSERT INTO zones VALUES (70,38,'NB','New Brunswick');
INSERT INTO zones VALUES (71,38,'NS','Nova Scotia');
INSERT INTO zones VALUES (72,38,'NT','Northwest Territories');
INSERT INTO zones VALUES (73,38,'NU','Nunavut');
INSERT INTO zones VALUES (74,38,'ON','Ontario');
INSERT INTO zones VALUES (75,38,'PE','Prince Edward Island');
INSERT INTO zones VALUES (76,38,'QC','Quebec');
INSERT INTO zones VALUES (77,38,'SK','Saskatchewan');
INSERT INTO zones VALUES (78,38,'YT','Yukon Territory');

# Germany
INSERT INTO zones VALUES (79,81,'NDS','Niedersachsen');
INSERT INTO zones VALUES (80,81,'BAW','Baden-Württemberg');
INSERT INTO zones VALUES (81,81,'BAY','Bayern');
INSERT INTO zones VALUES (82,81,'BER','Berlin');
INSERT INTO zones VALUES (83,81,'BRG','Brandenburg');
INSERT INTO zones VALUES (84,81,'BRE','Bremen');
INSERT INTO zones VALUES (85,81,'HAM','Hamburg');
INSERT INTO zones VALUES (86,81,'HES','Hessen');
INSERT INTO zones VALUES (87,81,'MEC','Mecklenburg-Vorpommern');
INSERT INTO zones VALUES (88,81,'NRW','Nordrhein-Westfalen');
INSERT INTO zones VALUES (89,81,'RHE','Rheinland-Pfalz');
INSERT INTO zones VALUES (90,81,'SAR','Saarland');
INSERT INTO zones VALUES (91,81,'SAS','Sachsen');
INSERT INTO zones VALUES (92,81,'SAC','Sachsen-Anhalt');
INSERT INTO zones VALUES (93,81,'SCN','Schleswig-Holstein');
INSERT INTO zones VALUES (94,81,'THE','Thüringen');

# Austria
INSERT INTO zones VALUES (95,14,'WI','Wien');
INSERT INTO zones VALUES (96,14,'NO','Niederösterreich');
INSERT INTO zones VALUES (97,14,'OO','Oberösterreich');
INSERT INTO zones VALUES (98,14,'SB','Salzburg');
INSERT INTO zones VALUES (99,14,'KN','Kärnten');
INSERT INTO zones VALUES (100,14,'ST','Steiermark');
INSERT INTO zones VALUES (101,14,'TI','Tirol');
INSERT INTO zones VALUES (102,14,'BL','Burgenland');
INSERT INTO zones VALUES (103,14,'VB','Voralberg');

# Swizterland
INSERT INTO zones VALUES (104,204,'AG','Aargau');
INSERT INTO zones VALUES (105,204,'AI','Appenzell Innerrhoden');
INSERT INTO zones VALUES (106,204,'AR','Appenzell Ausserrhoden');
INSERT INTO zones VALUES (107,204,'BE','Bern');
INSERT INTO zones VALUES (108,204,'BL','Basel-Landschaft');
INSERT INTO zones VALUES (109,204,'BS','Basel-Stadt');
INSERT INTO zones VALUES (110,204,'FR','Freiburg');
INSERT INTO zones VALUES (111,204,'GE','Genf');
INSERT INTO zones VALUES (112,204,'GL','Glarus');
INSERT INTO zones VALUES (113,204,'JU','Graubünden');
INSERT INTO zones VALUES (114,204,'JU','Jura');
INSERT INTO zones VALUES (115,204,'LU','Luzern');
INSERT INTO zones VALUES (116,204,'NE','Neuenburg');
INSERT INTO zones VALUES (117,204,'NW','Nidwalden');
INSERT INTO zones VALUES (118,204,'OW','Obwalden');
INSERT INTO zones VALUES (119,204,'SG','St. Gallen');
INSERT INTO zones VALUES (120,204,'SH','Schaffhausen');
INSERT INTO zones VALUES (121,204,'SO','Solothurn');
INSERT INTO zones VALUES (122,204,'SZ','Schwyz');
INSERT INTO zones VALUES (123,204,'TG','Thurgau');
INSERT INTO zones VALUES (124,204,'TI','Tessin');
INSERT INTO zones VALUES (125,204,'UR','Uri');
INSERT INTO zones VALUES (126,204,'VD','Waadt');
INSERT INTO zones VALUES (127,204,'VS','Wallis');
INSERT INTO zones VALUES (128,204,'ZG','Zug');
INSERT INTO zones VALUES (129,204,'ZH','Zürich');

# Spain
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'A Coruña','A Coruña');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Alava','Alava');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Albacete','Albacete');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Alicante','Alicante');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Almeria','Almeria');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Asturias','Asturias');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Avila','Avila');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Badajoz','Badajoz');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Baleares','Baleares');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Barcelona','Barcelona');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Burgos','Burgos');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Caceres','Caceres');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cadiz','Cadiz');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cantabria','Cantabria');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Castellon','Castellon');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Ceuta','Ceuta');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Ciudad Real','Ciudad Real');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cordoba','Cordoba');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cuenca','Cuenca');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Girona','Girona');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Granada','Granada');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Guadalajara','Guadalajara');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Guipuzcoa','Guipuzcoa');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Huelva','Huelva');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Huesca','Huesca');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Jaen','Jaen');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'La Rioja','La Rioja');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Las Palmas','Las Palmas');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Leon','Leon');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Lleida','Lleida');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Lugo','Lugo');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Madrid','Madrid');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Malaga','Malaga');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Melilla','Melilla');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Murcia','Murcia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Navarra','Navarra');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Ourense','Ourense');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Palencia','Palencia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Pontevedra','Pontevedra');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Salamanca','Salamanca');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Santa Cruz de Tenerife','Santa Cruz de Tenerife');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Segovia','Segovia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Sevilla','Sevilla');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Soria','Soria');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Tarragona','Tarragona');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Teruel','Teruel');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Toledo','Toledo');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Valencia','Valencia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Valladolid','Valladolid');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Vizcaya','Vizcaya');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Zamora','Zamora');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Zaragoza','Zaragoza');
