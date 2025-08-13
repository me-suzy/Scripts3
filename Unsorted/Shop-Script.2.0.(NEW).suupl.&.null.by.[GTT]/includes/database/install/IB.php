<? //Interbase installation - tables definition

	db_query("CREATE TABLE ".CUSTOMERS_TABLE." (Login VARCHAR(30) NOT NULL PRIMARY KEY, cust_password VARCHAR(20), Email VARCHAR(50), Country VARCHAR(40), City VARCHAR(40), Address VARCHAR(255), Phone VARCHAR(30), first_name VARCHAR(30), default_currency INTEGER, subscribed4news INTEGER, ZIP VARCHAR(10), State VARCHAR(20), last_name VARCHAR(30), otch VARCHAR(30))") or die (db_error());

	db_query("CREATE GENERATOR ORDERS_GEN"); 
	db_query("CREATE TABLE ".ORDERS_TABLE." (orderID INTEGER NOT NULL PRIMARY KEY, customer_login VARCHAR(30), payment_type VARCHAR(30), customers_comment VARCHAR(255), order_time TIMESTAMP, Done INTEGER, shipping_type VARCHAR(30), final_shipping_address VARCHAR(255), shipping_cost double precision, calculate_tax INTEGER, tax double precision)") or die (db_error()); 

	db_query("CREATE TRIGGER ORDERS_NEW FOR ".ORDERS_TABLE." ACTIVE BEFORE INSERT POSITION 0 AS begin if (new.orderID is null) then new.orderID = gen_id(ORDERS_GEN, 1); end" );

	db_query("CREATE TABLE ".ORDERED_CARTS_TABLE." (productID INTEGER NOT NULL, orderID INTEGER NOT NULL, name VARCHAR(255), Price double precision, Quantity INTEGER, PRIMARY KEY (productID, orderID))") or die (db_error()); 

	db_query("CREATE TABLE ".PRODUCTS_TABLE." (productID INTEGER NOT NULL PRIMARY KEY, categoryID INTEGER, name VARCHAR(255), description VARCHAR(8192), customers_rating double precision, Price double precision, picture VARCHAR(30), in_stock INTEGER, thumbnail VARCHAR(30), customer_votes INTEGER, items_sold INTEGER, show_as_special_offer INTEGER, big_picture VARCHAR(30), enabled INTEGER, brief_description VARCHAR(8192), list_price FLOAT, product_code VARCHAR(25))") or die (db_error()); 
	db_query("CREATE GENERATOR PRODUCTS_GEN");  
	db_query( "CREATE TRIGGER PRODUCTS_NEW FOR ".PRODUCTS_TABLE." ACTIVE BEFORE INSERT POSITION 0 AS begin if (new.productID is null) then new.productID = gen_id(PRODUCTS_GEN, 1); end" ); 

	db_query("CREATE TABLE ".CATEGORIES_TABLE." (categoryID INTEGER NOT NULL PRIMARY KEY, name VARCHAR(255), parent INTEGER, products_count INTEGER, description VARCHAR(8192), picture VARCHAR(30), products_count_admin INTEGER)") or die (db_error()); 
	db_query("CREATE GENERATOR CATEGORIES_GEN");  
	db_query( "CREATE TRIGGER CATEGORIES_NEW FOR ".CATEGORIES_TABLE." ACTIVE BEFORE INSERT POSITION 0 AS begin if (new.categoryID is null) then new.categoryID = gen_id(CATEGORIES_GEN, 1); end" );


	db_query("CREATE TABLE ".SHOPPING_CARTS_TABLE." (customer_login VARCHAR(20) NOT NULL, productID INTEGER NOT NULL, Quantity INTEGER, PRIMARY KEY (customer_login, productID))") or die (db_error());

	db_query("CREATE TABLE ".NEWS_TABLE." (NID INTEGER NOT NULL PRIMARY KEY, add_date VARCHAR(30), Body VARCHAR(8192), add_stamp INTEGER)") or die (db_error()); 
	db_query("CREATE GENERATOR NEWS_GEN");  
	db_query( "CREATE TRIGGER NEWS_NEW FOR ".NEWS_TABLE." ACTIVE BEFORE INSERT POSITION 0 AS begin if (new.NID is null) then new.NID = gen_id(NEWS_GEN, 1); end" ); 

	db_query("CREATE TABLE ".DISCUSSIONS_TABLE." (DID INTEGER NOT NULL PRIMARY KEY, productID INTEGER, Author VARCHAR(40), Body VARCHAR(8192), add_time TIMESTAMP, Topic VARCHAR(255))") or die (db_error()); 
	db_query("CREATE GENERATOR DISCUSSIONS_GEN");
	db_query( "CREATE TRIGGER DISCUSSIONS_NEW FOR ".DISCUSSIONS_TABLE." ACTIVE BEFORE INSERT POSITION 0 AS begin if (new.DID is null) then new.DID = gen_id(DISCUSSIONS_GEN, 1); end" ); 

	db_query("CREATE TABLE ".MAILING_LIST_TABLE." (MID INTEGER NOT NULL PRIMARY KEY, Email VARCHAR(50))") or die (db_error());
	db_query("CREATE GENERATOR MAILING_GEN");  
	db_query( "CREATE TRIGGER MAILING_NEW FOR ".MAILING_LIST_TABLE." ACTIVE BEFORE INSERT POSITION 0 AS begin if (new.MID is null) then new.MID = gen_id(MAILING_GEN, 1); end" );  

	db_query("CREATE TABLE ".RELATED_PRODUCTS_TABLE." (productID INTEGER NOT NULL, Owner INTEGER NOT NULL, PRIMARY KEY (productID, Owner))") or die (db_error()); 
	db_query("CREATE TABLE ".PRODUCT_OPTIONS_TABLE." (optionID INTEGER NOT NULL PRIMARY KEY, name VARCHAR(50))") or die (db_error());
	db_query("CREATE GENERATOR PRODUCT_OPTIONS_GEN");  
	db_query( "CREATE TRIGGER PRODUCT_OPTIONS_NEW FOR ".PRODUCT_OPTIONS_TABLE." ACTIVE BEFORE INSERT POSITION 0 AS begin if (new.optionID is null) then new.optionID = gen_id(PRODUCT_OPTIONS_GEN, 1); end" );  

	db_query("CREATE TABLE ".PRODUCT_OPTIONS_VALUES_TABLE." (optionID INTEGER NOT NULL, productID INTEGER NOT NULL, option_value VARCHAR(255), PRIMARY KEY (optionID, productID))") or die (db_error());
	db_query("CREATE TABLE ".SHIPPING_METHODS_TABLE." (SID INTEGER NOT NULL PRIMARY KEY, Name VARCHAR(30), description VARCHAR(255), lump_sum FLOAT, percent_value FLOAT, Enabled INTEGER)") or die (db_error()); 

	db_query("CREATE GENERATOR SHIPPING_METHODSS_GEN");  
	db_query( "CREATE TRIGGER SHIPPING_METHODS_NEW FOR ".SHIPPING_METHODS_TABLE." ACTIVE BEFORE INSERT POSITION 0 AS begin if (new.SID is null) then new.SID = gen_id(SHIPPING_METHODSS_GEN, 1); end" );    


	db_query("CREATE TABLE ".PAYMENT_TYPES_TABLE." (PID INTEGER NOT NULL PRIMARY KEY, Name VARCHAR(30), description VARCHAR(255), Enabled INTEGER, calculate_tax INTEGER)") or die (db_error());

	db_query("CREATE GENERATOR PAYMENT_TYPES_GEN");  
	db_query( "CREATE TRIGGER PAYMENT_TYPES_NEW FOR ".PAYMENT_TYPES_TABLE." ACTIVE BEFORE INSERT POSITION 0 AS begin if (new.PID is null) then new.PID = gen_id(PAYMENT_TYPES_GEN, 1); end" );  

?>