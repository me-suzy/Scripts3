<? //MS SQL Server installation - tables definition

	db_query("CREATE TABLE ".CUSTOMERS_TABLE." (Login VARCHAR(30) PRIMARY KEY, cust_password VARCHAR(20), Email VARCHAR(50), Country VARCHAR(40), City VARCHAR(40), Address VARCHAR(255), Phone VARCHAR(30), first_name VARCHAR(30), default_currency INT, subscribed4news INT, ZIP VARCHAR(10), State VARCHAR(20), last_name VARCHAR(30), otch VARCHAR(30))") or die (db_error());
	db_query("CREATE TABLE ".ORDERS_TABLE." (orderID INT PRIMARY KEY IDENTITY(1, 1), customer_login VARCHAR(30), payment_type VARCHAR(30), customers_comment VARCHAR(255), order_time datetime, Done INT, shipping_type VARCHAR(30), final_shipping_address VARCHAR(255), shipping_cost FLOAT, calculate_tax INT, tax FLOAT)") or die (db_error());
	db_query("CREATE TABLE ".ORDERED_CARTS_TABLE." (productID INT NOT NULL, orderID INT NOT NULL, name VARCHAR(255), Price FLOAT, Quantity INT, PRIMARY KEY (productID, orderID))") or die (db_error()); 
	db_query("CREATE TABLE ".PRODUCTS_TABLE." (productID INT PRIMARY KEY IDENTITY(1, 1), categoryID INT, name VARCHAR(255), description TEXT, customers_rating FLOAT, Price FLOAT, picture VARCHAR(30), in_stock INT, thumbnail VARCHAR(30), customer_votes INT, items_sold INT, show_as_special_offer INT, big_picture VARCHAR(30), enabled INT, brief_description TEXT, list_price FLOAT, product_code VARCHAR(25))") or die (db_error());
	db_query("CREATE TABLE ".CATEGORIES_TABLE." (categoryID INT PRIMARY KEY IDENTITY(1, 1), name VARCHAR(255), parent INT, products_count INT, description TEXT, picture VARCHAR(30), products_count_admin INT)") or die (db_error()); 
	db_query("CREATE TABLE ".SHOPPING_CARTS_TABLE." (customer_login VARCHAR(20) NOT NULL, productID INT NOT NULL, Quantity INT, PRIMARY KEY (customer_login, productID))") or die (db_error());
	db_query("CREATE TABLE ".NEWS_TABLE." (NID INT PRIMARY KEY IDENTITY(1, 1), add_date VARCHAR(30), Body TEXT, add_stamp INT)") or die (db_error());
	db_query("CREATE TABLE ".DISCUSSIONS_TABLE." (DID INT PRIMARY KEY IDENTITY(1, 1), productID INT, Author VARCHAR(40), Body TEXT, add_time datetime, Topic VARCHAR(255))") or die (db_error());
	db_query("CREATE TABLE ".MAILING_LIST_TABLE." (MID INT PRIMARY KEY IDENTITY(1, 1), Email VARCHAR(50))") or die (db_error());
	db_query("CREATE TABLE ".RELATED_PRODUCTS_TABLE." (productID INT NOT NULL, Owner INT NOT NULL, PRIMARY KEY (productID, Owner))") or die (db_error());
	db_query("CREATE TABLE ".PRODUCT_OPTIONS_TABLE." (optionID INT PRIMARY KEY IDENTITY(1, 1), name VARCHAR(50))") or die (db_error());
	db_query("CREATE TABLE ".PRODUCT_OPTIONS_VALUES_TABLE." (optionID INT NOT NULL, productID INT NOT NULL, option_value VARCHAR(255), PRIMARY KEY (optionID, productID))") or die (db_error());
	db_query("CREATE TABLE ".SHIPPING_METHODS_TABLE." (SID INT PRIMARY KEY IDENTITY(1, 1), Name VARCHAR(30), description VARCHAR(255), lump_sum FLOAT, percent_value FLOAT, Enabled INT)") or die (db_error());
	db_query("CREATE TABLE ".PAYMENT_TYPES_TABLE." (PID INT PRIMARY KEY IDENTITY(1, 1), Name VARCHAR(30), description VARCHAR(255), Enabled INT, calculate_tax INT)") or die (db_error()); 

?>