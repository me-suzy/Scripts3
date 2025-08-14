--
-- Definition of the tables that are ne4cessary for IpUtilities
--

CREATE TABLE IpUtilities_csv (
 start_ip CHAR(15) NOT NULL,
 end_ip CHAR(15) NOT NULL,
  start INT UNSIGNED NOT NULL,
  end INT UNSIGNED NOT NULL,
  cc CHAR(2) NOT NULL,
  cn VARCHAR(50) NOT NULL,
  last_update timestamp DEFAULT now()
  );

CREATE TABLE  IpUtilities_CountryCodes (
  ci TINYINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  cc CHAR(3) NOT NULL,
  cn VARCHAR(255) NOT NULL
  );

CREATE TABLE  IpUtilities_IpRanges (
  start INT UNSIGNED NOT NULL,
  end INT UNSIGNED NOT NULL,
  ci TINYINT UNSIGNED NOT NULL
  );