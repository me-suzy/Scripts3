drop table if exists thumbs;
drop table if exists settings;
drop table if exists badthumbs;

CREATE TABLE thumbs
(
	thumbID REAL AUTO_INCREMENT NOT NULL,
	numberImages INTEGER,
	fileName BLOB default '',
	galleryURL BLOB,
	categoryID REAL,
	dateAdded INTEGER,
	active INTEGER,
	PRIMARY KEY (thumbID)
);

CREATE TABLE settings
(
	settingID INTEGER AUTO_INCREMENT NOT NULL,
	thumbWidth INTEGER,
	thumbHeight INTEGER,
	thumbQuality INTEGER,
	thumbWidth2 INTEGER,
	thumbHeight2 INTEGER,
	thumbQuality2 INTEGER,
	thumbWidth3 INTEGER,
	thumbHeight3 INTEGER,
	thumbQuality3 INTEGER,
	templateFile1 BLOB,
	templateFile2 BLOB,
	templateFile3 BLOB,
	outputFile1 BLOB,
	outputFile2 BLOB,
	outputFile3 BLOB,
	installDirectory BLOB,
	installURL BLOB,
	thumbsDirectory BLOB,
	thumbsURL BLOB,
	tradeScriptOut BLOB,
	tradeScriptGalleryOut BLOB,
	tradeScriptOut2 BLOB,
	tradeScriptGalleryOut2 BLOB,
	tradeScriptOut3 BLOB,
	tradeScriptGalleryOut3 BLOB,
	PRIMARY KEY (settingID)
);

INSERT INTO settings (thumbwidth) VALUES (0);

CREATE TABLE badthumbs
(
	badThumbID REAL AUTO_INCREMENT NOT NULL,
	galleryURL BLOB,
	PRIMARY KEY (badThumbID)
);