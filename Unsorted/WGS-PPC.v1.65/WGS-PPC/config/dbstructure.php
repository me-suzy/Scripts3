<?
	$DBSTRUCTURE = array(
		$TCategories           => "CategoryID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,   Parent bigint(20) unsigned,   Name varchar(255),   PRIMARY KEY (CategoryID)",
		$TAdminMembersBonus    => "Bonus int(10) unsigned",
		$TAdminMembersBonusBU  => "Bonus int(10) unsigned",
		$TAdminSettings        => "Porog int(10) unsigned,   MinBid int(10) unsigned,   MinNoMatchesBid int(10) unsigned,   CurrencySymbol varchar(255),   IPP tinyint(3) unsigned,   TopPosNumber tinyint(3) unsigned,   AcceptBids tinyint(3) unsigned, AllowSS tinyint(3) unsigned,    ACOCost int(10) unsigned,   ACLCost int(10) unsigned, AllowIPN tinyint unsigned, APPEMail varchar(255),    AllowMSN tinyint unsigned,    AllowDMOZ tinyint unsigned, AllowRevenue tinyint unsigned",
		$TAdminSettingsBU      => "Porog int(10) unsigned,   MinBid int(10) unsigned,   MinNoMatchesBid int(10) unsigned,   CurrencySymbol varchar(255),   IPP tinyint(3) unsigned,   TopPosNumber tinyint(3) unsigned,   AcceptBids tinyint(3) unsigned, AllowSS tinyint(3) unsigned,    ACOCost int(10) unsigned,   ACLCost int(10) unsigned, AllowIPN tinyint unsigned, APPEMail varchar(255),     AllowMSN tinyint unsigned,    AllowDMOZ tinyint unsigned, AllowRevenue tinyint unsigned",
		$TSearchStatistics     => "Term varchar(255),   SearchDate date",
		$TMembersAccounts      => "MemberID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,   MemberLogin varchar(255),   MemberPassword varchar(255),   STATUS tinyint(3) unsigned,   CreateDate date,   BUPW varchar(255), PRIMARY KEY (MemberID)",
		$TMembersCC            => "MemberID bigint(20) unsigned DEFAULT '0' NOT NULL,   CCNumber varchar(255),   CCName varchar(255),   CCExpires date,   PayPalAccount varchar(255), KEY MemberID (MemberID)",
		$TMembersInfo          => "MemberID bigint(20) unsigned DEFAULT '0' NOT NULL,   Name varchar(255),   Company varchar(255),   EMail varchar(255),   Street varchar(255),   City varchar(255),   State varchar(255),   Zip varchar(255),   Country varchar(255),   Phone varchar(255),   KEY MemberID (MemberID)",
		$TMembersSites         => "MemberID bigint(20) unsigned DEFAULT '0' NOT NULL,   CategoryID bigint(20) unsigned DEFAULT '0' NOT NULL,   Title varchar(255),   Descr text,   Link varchar(255),   KEY MemberID (MemberID),   KEY CategoryID (CategoryID)",
		$TMembersTerms         => "TermID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,   MemberID bigint(20) unsigned DEFAULT '0' NOT NULL,   Term varchar(255),   Title varchar(255),   Link varchar(255),   Descr text,   LLogoURL varchar(255), PRIMARY KEY (TermID),   KEY MemberID (MemberID)",
		$TMembersBids          => "TermID bigint(20) unsigned DEFAULT '0' NOT NULL,   Bid int(10) unsigned,   KEY TermID (TermID)",
		$TMembersBalance       => "MemberID bigint(20) unsigned DEFAULT '0' NOT NULL,   Balance bigint(20) unsigned,   KEY MemberID (MemberID)",
		$TMembersClicks        => "ClickID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,   TermID bigint(20) unsigned,   ClickDate date,   SearchTerm varchar(255),   PRIMARY KEY (ClickID)",
		$TMembersLogos         => "MemberID bigint(20) unsigned DEFAULT '0' NOT NULL,   LogoURL varchar(255)",
		$TNoMatchesBids        => "MemberID bigint(20) unsigned DEFAULT '0' NOT NULL,   Bid int(10) unsigned,   Title varchar(255),   Link varchar(255),   Descr text,   KEY MemberID (MemberID)",
		$TMembersNMClicks      => "ClickID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,   MemberID bigint(20) unsigned DEFAULT '0' NOT NULL,   ClickDate date,   SearchTerm varchar(255),   PRIMARY KEY (ClickID),   KEY MemberID (MemberID)",
		$TBanners              => "BannerID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,   MemberID bigint(20) unsigned DEFAULT '0',   BannerURL varchar(255),   BannerAlt varchar(255),   STATUS tinyint(3) unsigned,   Link varchar(255),   PRIMARY KEY (BannerID)",
		$TBannersBids          => "BannerID bigint(20) unsigned DEFAULT '0' NOT NULL,   ShowBid int(10) unsigned,   ClickBid int(10) unsigned,   KEY BannerID (BannerID)",
		$TBannersClicks        => "ClickID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,   BannerID bigint(20) unsigned DEFAULT '0' NOT NULL,   ClickDate date,   PRIMARY KEY (ClickID),   KEY BannerID (BannerID)",
		$TBannersShows         => "ShowID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,   BannerID bigint(20) unsigned DEFAULT '0' NOT NULL,   ShowDate date,   PRIMARY KEY (ShowID),   KEY BannerID (BannerID)",
		$TBannersTerms         => "BannerID bigint(20) unsigned DEFAULT '0' NOT NULL,   TermID bigint(20) unsigned DEFAULT '0' NOT NULL",
		$TFiltered             => "KeywordID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment, Keyword varchar(255), PRIMARY KEY (KeywordID)",
		$TTempTermsBids        => "TempID bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,   MemberID bigint(20) unsigned DEFAULT '0' NOT NULL,   Term varchar(255),   Title varchar(255),   Link varchar(255),   Descr text,   Bid int(10) unsigned,   TYPE tinyint(3) unsigned,   LLogoURL varchar(255), PRIMARY KEY (TempID)",
		$TAdminSpecialAccounts => "AccountID int unsigned not null primary key auto_increment, AccountLogin varchar(255), AccountPassword varchar(255), AccessRights tinyint unsigned",
		$TMembersTransfers     => "TransID bigint unsigned not null primary key auto_increment,	MemberID bigint unsigned,	Ammount int unsigned,	STATUS tinyint unsigned",
		$TASearches            =>"MemberID bigint unsigned,	ASDate date",
		$TAClicks              =>"MemberID bigint unsigned,	ACDate date",
		$TGMQuery              =>"TransID bigint unsigned not null primary key auto_increment,	MemberID bigint unsigned not null,		PayPalAccount varchar(255), Amount bigint unsigned not null, STATUS tinyint unsigned",
		$TVisitors             =>"IP varchar(255),	VDate datetime"
	);
	
	$INSERT_SETTINGS = array(	
		$TAdminSettings       => "500, 1, 10, '$', 20, 5, 0, 0, 5, 10, 0, 'test@test.com', 1, 1, 0",
		$TAdminSettingsBU     => "500, 1, 10, '$', 20, 5, 0, 0, 5, 10, 0, 'test@test.com', 1, 1, 0",
		$TAdminMembersBonus   => "1000",
		$TAdminMembersBonusBU => "1000"
	);
	
?>