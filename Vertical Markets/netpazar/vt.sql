CREATE TABLE `admin` (
  `sno` tinyint(1) NOT NULL default '0',
  `user` varchar(10) NOT NULL default '',
  `password` varchar(10) NOT NULL default ''
) ENGINE=MyISAM COMMENT='Sifreleri icerir';


INSERT INTO `admin` VALUES (1, 'admin', '123');

CREATE TABLE `netpazar` (
  `kayno` int(11) NOT NULL auto_increment,
  `kabulTarihi` date NOT NULL default '0000-00-00',
  `kategori` smallint(1) NOT NULL default '0',
  `konuBaslik` char(30)  NOT NULL default '',
  `konuMetni` char(150)  NOT NULL default '',
  `gonderen` char(30)  NOT NULL default '',
  `mail` char(30)  NOT NULL default '',
  `telefon1` char(15)  NOT NULL default '',
  `telefon2` char(15)  NOT NULL default '',
  `resim` char(30)  NOT NULL default '',
  `aktif` char(1)  NOT NULL default '',
  `eklemeTarihi` date NOT NULL default '0000-00-00',
  `fiyat` float NOT NULL default '0',
  `paraTuru` char(1)  NOT NULL default '',
  `okunmaSayisi` int(11) NOT NULL default '0',
  PRIMARY KEY  (`kayno`)
) ENGINE=MyISAM  COMMENT='bit pazari' AUTO_INCREMENT=1 ;

