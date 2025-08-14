# --------------------------------------------------------
#
# Table structure for table 'a_auctions'
#

CREATE TABLE a_auctions (
   id varchar(30) NOT NULL,
   user varchar(30),
   title tinytext,
   date timestamp(14),
   description text,
   pict_url tinytext,
   category int(11),
   minimum_bid double(16,4),
   reserve_price double(16,4),
   sofortkauf double(16,2),
   auction_type char(1),
   duration char(2),
   location tinytext,
   location_zip varchar(6),
   shipping char(1),
   payment tinytext,
   international char(1),
   ends timestamp(14),
   current_bid double(16,4),
   closed char(1),
   photo_uploaded char(1),
   quantity int(11),
   suspended int(1) DEFAULT '0',
   PRIMARY KEY (id),
   KEY id (id)
);

#
# Dumping data for table 'a_auctions'
#


# --------------------------------------------------------
#
# Table structure for table 'a_aufrufzaehler'
#

CREATE TABLE a_aufrufzaehler (
   auktionsid char(30),
   zugriff int(11) DEFAULT '0'
);

#
# Dumping data for table 'a_aufrufzaehler'
#


# --------------------------------------------------------
#
# Table structure for table 'a_bids'
#

CREATE TABLE a_bids (
   auction varchar(30),
   bidder varchar(30),
   bid double(16,4),
   bidwhen timestamp(14),
   quantity int(11) DEFAULT '0'
);

#
# Dumping data for table 'a_bids'
#



# --------------------------------------------------------
#
# Table structure for table 'a_categories'
#

CREATE TABLE a_categories (
   cat_id int(4) NOT NULL auto_increment,
   parent_id int(4),
   cat_name tinytext,
   deleted int(1),
   sub_counter int(11),
   counter int(11),
   cat_colour tinytext NOT NULL,
   cat_image tinytext NOT NULL,
   PRIMARY KEY (cat_id)
);

#
# Dumping data for table 'a_categories'
#

INSERT INTO a_categories VALUES ( '1', '0', 'Kategorie D', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '2', '0', 'Kategorie B', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '3', '0', 'Kategorie C', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '6', '0', 'Kategorie A', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '7', '0', 'Kategorie E', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '8', '0', 'Kategorie F', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '9', '0', 'Kategorie G', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '10', '0', 'Kategorie H', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '11', '0', 'Kategorie I', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '12', '6', 'Unter-Kategorie A1', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '13', '6', 'Unter-Kategorie A2', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '14', '6', 'Unter-Kategorie A3', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '15', '2', 'Unter-Kategorie B1', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '16', '2', 'Unter-Kategorie B2', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '17', '2', 'Unter-Kategorie B3', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '18', '3', 'Unter-Kategorie C1', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '19', '3', 'Unter-Kategorie C2', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '20', '3', 'Unter-Kategorie C3', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '21', '1', 'Unter-Kategorie D1', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '22', '1', 'Unter-Kategorie D2', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '23', '1', 'Unter-Kategorie D3', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '24', '7', 'Unter-Kategorie E1', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '25', '7', 'Unter-Kategorie E2', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '26', '7', 'Unter-Kategorie E3', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '27', '8', 'Unter-Kategorie F1', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '28', '8', 'Unter-Kategorie F2', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '29', '8', 'Unter-Kategorie F3', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '30', '9', 'Unter-Kategorie G1', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '31', '9', 'Unter-Kategorie G2', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '32', '9', 'Unter-Kategorie G3', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '33', '10', 'Unter-Kategorie H1', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '34', '10', 'Unter-Kategorie H2', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '35', '10', 'Unter-Kategorie H3', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '36', '11', 'Unter-Kategorie I1', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '37', '11', 'Unter-Kategorie I2', '0', '0', '0', '', '');
INSERT INTO a_categories VALUES ( '38', '11', 'Unter-Kategorie I3', '0', '0', '0', '', '');

# --------------------------------------------------------
#
# Table structure for table 'a_categories_plain'
#

CREATE TABLE a_categories_plain (
   id int(11) NOT NULL auto_increment,
   cat_id int(11),
   cat_name tinytext,
   PRIMARY KEY (id)
);

#
# Dumping data for table 'a_categories_plain'
#


# --------------------------------------------------------
#
# Table structure for table 'a_counters'
#

CREATE TABLE a_counters (
   users int(11) DEFAULT '0',
   auctions int(11) DEFAULT '0'
);

#
# Dumping data for table 'a_counters'
#

INSERT INTO a_counters VALUES ( '127', '0');

# --------------------------------------------------------
#
# Table structure for table 'a_countries'
#

CREATE TABLE a_countries (
   country_id int(8) NOT NULL auto_increment,
   domain varchar(8),
   country varchar(40) NOT NULL,
   countries_long varchar(80),
   FIPS_code varchar(8) NOT NULL,
   capital varchar(80),
   logo blob,
   PRIMARY KEY (country_id),
   KEY FIPS_code (FIPS_code)
);

#
# Dumping data for table 'a_countries'
#

INSERT INTO a_countries VALUES ( '195', 'at', 'Österreich', 'Austria', '', 'Wien', NULL);
INSERT INTO a_countries VALUES ( '65', 'de', 'Deutschland', 'Federal Republic of Germany', 'GM', 'Berlin', NULL);
INSERT INTO a_countries VALUES ( '166', 'ch', 'Schweiz', 'Swiss Confederation', 'SZ', 'Bern', NULL);

# --------------------------------------------------------
#
# Table structure for table 'a_durations'
#

CREATE TABLE a_durations (
   days int(2) DEFAULT '0' NOT NULL,
   description varchar(30)
);

#
# Dumping data for table 'a_durations'
#

INSERT INTO a_durations VALUES ( '1', '1 Tag');
INSERT INTO a_durations VALUES ( '3', '3 Tage');
INSERT INTO a_durations VALUES ( '7', '7 Tage');
INSERT INTO a_durations VALUES ( '14', '14 Tage');
INSERT INTO a_durations VALUES ( '30', '30 Tage');

# --------------------------------------------------------
#
# Table structure for table 'a_feedbacks'
#

CREATE TABLE a_feedbacks (
   rated_user_id varchar(30),
   rater_user_nick varchar(25),
   feedback mediumtext,
   rate int(2),
   date timestamp(14)
);

#
# Dumping data for table 'a_feedbacks'
#



# --------------------------------------------------------
#
# Table structure for table 'a_help'
#

CREATE TABLE a_help (
   topic varchar(40),
   helptext text
);

#
# Dumping data for table 'a_help'
#

INSERT INTO a_help VALUES ( 'Allgemeines', 'Willkommen bei unserer Online-Auktion.<P>
<P>
Als registrierter User können Sie hier Artikel ersteigern.
Um sich zu registrieren müßen Sie Ihren Namen, Ihre Anschrift und Ihre eMail-Adresse angeben. Weiterhin müssen Sie mindestens 18 Jahre alt sein.
<P>
Sobald die Auktion endet, wird der Verkäufer per eMail über den Ausgang der Auktion informiert. 
War die Auktion erfolgreich, werden dem Verkäufer und dem Käufer die Daten zur Kontaktaufnahme übersendet.<P>');
INSERT INTO a_help VALUES ( 'Registrieren', 'Wie registriere ich mich?
<P>
Um sich zu registrieren, klicken Sie auf \"Jetzt registrieren\" im oberen Bereich.
Geben Sie Ihre persönlichen Daten ein (Name, Anschrift, eMail-Adresse) und wählen Sie einen Usernamen, sowie ein Passwort.
<P>
Sie müssen mindestens 18 Jahre alt sein.<P>');
INSERT INTO a_help VALUES ( 'Bieten', 'Wie biete ich?
<P>
Um Artikel ersteigern zu können, müssen Sie ein registrierter User sein.
<P>
Um zu bieten, geben Sie einfach Ihr Gebot in den Kasten neben der Artikelbeschreibung ein und klicken Sie auf \"Los!\".
Das Gebot muß mindestens so hoch sein wie das ebenfalls angezeigte Minimumgebot.
<P>
Danach werden Sie gebeten, Ihr Gebot zu bestätigen. Geben Sie hierzu Ihren Usernamen und Ihr Passwort ein und klicken auf \"Submit Query\".<P>');
INSERT INTO a_help VALUES ( 'Verkaufen', 'Wie verkaufe ich?
<P>
Um Artikel versteigern zu können, müssen Sie ein registrierter User sein.
<P>
Klicken Sie auf \"Artikel anbieten\" im oberen Bereich, um eine Auktion zu erstellen.
Geben Sie einen Artikelnamen ein und fügen Sie eine Artikelbeschreibung hinzu.
Zusätzlich können ein Bild wählen, da0 Sie entweder von Ihrer Festplatte hochladen können oder Sie geben die URL/Webadresse Ihres Bildes ein.
<P>
Bestimmen Sie nun noch die Dauer der Auktion, indem sie auswählen, wieviele Tage Ihre Auktion laufen soll.
<P>
Legen Sie einen Startpreis fest und wenn Sie wollen auch einen Minimumpreis, unter dem der Artikel nicht verkauft wird.
Geben Sie weiterhin ein, welche Zahlungsmöglichkeiten und Versandbedingungen Sie akzeptieren wollen. Die Abwicklung der Auktion (Zahlung, Versand) liegt einzig bei Ihnen.
<P>
Zuletzt wählen Sie noch eine Kategorie, in der Ihr Artikel geführt werden soll. Sie können auch eine neue Kategorie vorschlagen, müßen aber trotzdem vorerst eine bereits vorhande Kategorie auswählen.<P>');

# --------------------------------------------------------
#
# Table structure for table 'a_increments'
#

CREATE TABLE a_increments (
   id decimal(3,0),
   low double(16,4),
   high double(16,4),
   increment double(16,4)
);

#
# Dumping data for table 'a_increments'
#

INSERT INTO a_increments VALUES ( '1', '0.0000', '9.9900', '1.0000');
INSERT INTO a_increments VALUES ( '2', '10.0000', '99.9900', '5.0000');
INSERT INTO a_increments VALUES ( '3', '100.0000', '299.9900', '8.0000');
INSERT INTO a_increments VALUES ( '4', '300.0000', '599.9900', '10.0000');
INSERT INTO a_increments VALUES ( '5', '600.0000', '999.9900', '25.0000');
INSERT INTO a_increments VALUES ( '6', '1000.0000', '4999.9900', '50.0000');
INSERT INTO a_increments VALUES ( '7', '5000.0000', '29999.9900', '100.0000');

# --------------------------------------------------------
#
# Table structure for table 'a_news'
#

CREATE TABLE a_news (
   topic varchar(40),
   newstext text
);

#
# Dumping data for table 'a_news'
#



# --------------------------------------------------------
#
# Table structure for table 'a_payments'
#

CREATE TABLE a_payments (
   id int(2),
   description varchar(30)
);

#
# Dumping data for table 'a_payments'
#

INSERT INTO a_payments VALUES ( '1', 'Überweisung');
INSERT INTO a_payments VALUES ( '2', 'Nachname');
INSERT INTO a_payments VALUES ( '3', 'Barzahlung');

# --------------------------------------------------------
#
# Table structure for table 'a_sessions'
#

CREATE TABLE a_sessions (
   id varchar(33),
   vars text,
   created timestamp(14),
   last_visit timestamp(14)
);

#
# Dumping data for table 'a_sessions'
#


# --------------------------------------------------------
#
# Table structure for table 'a_users'
#

CREATE TABLE a_users (
   id varchar(30) NOT NULL,
   nick varchar(25),
   password varchar(20),
   name tinytext,
   address tinytext,
   city varchar(25),
   prov varchar(10),
   country varchar(4),
   zip varchar(6),
   phone varchar(40),
   email varchar(50),
   reg_date timestamp(14),
   rate_sum int(11),
   rate_num int(11),
   birthdate int(8),
   suspended int(1) DEFAULT '0',
   KEY id (id)
);

#
# Dumping data for table 'a_users'
#


# --------------------------------------------------------
#
# Table structure for table 'a_views'
#

CREATE TABLE a_views (
   id varchar(30) NOT NULL,
   auction varchar(30) NOT NULL,
   user varchar(30),
   title tinytext,
   date timestamp(14),
   description text,
   pict_url tinytext,
   category int(11),
   minimum_bid double(16,4),
   reserve_price double(16,4),
   auction_type char(1),
   duration char(2),
   location tinytext,
   location_zip varchar(6),
   shipping char(1),
   payment tinytext,
   international char(1),
   ends timestamp(14),
   current_bid double(16,4),
   closed char(1),
   photo_uploaded char(1),
   quantity int(11),
   suspended int(1) DEFAULT '0',
   mailed int(4) DEFAULT '0' NOT NULL,
   time_to_mail timestamp(14),
   user_mail tinytext NOT NULL,
   bids_mail int(11) DEFAULT '0',
   PRIMARY KEY (id),
   KEY id (id)
);

#
# Dumping data for table 'a_views'
#


