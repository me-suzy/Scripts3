
CREATE TABLE ibs_customers (
  id blob,
  name blob,
  email blob,
  phone blob,
  address blob,
  password blob,
  comments blob
) TYPE=MyISAM;


CREATE TABLE ibs_custsessions (
  sessionid blob,
  customerid blob,
  timedate blob,
  basic blob
) TYPE=MyISAM;

CREATE TABLE ibs_invoices (
  id blob,
  customer_id blob,
  terms blob,
  agent blob,
  status blob,
  date blob,
  customer_class blob,
  products blob,
  taxes blob,
  shipping blob,
  servicefee blob,
  misc blob
) TYPE=MyISAM;


CREATE TABLE ibs_products (
  id blob,
  name blob,
  price blob,
  taxstatus blob,
  info blob
) TYPE=MyISAM;

CREATE TABLE ibs_taxes (
  id blob,
  name blob,
  rate blob
) TYPE=MyISAM;


CREATE TABLE listing (
  id blob,
  invoice blob,
  paid blob
) TYPE=MyISAM;
