<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Database settings              //
	//                                 //
	/////////////////////////////////////
	require($INC."functions/db_functions.php");
	require($INC."config/db_info.php");
	//Tables pseudonims

	$TVisitors = "PPC_Visitors";

	$TGMQuery  = "PPC_GetMoneyQuery";
	
	$TASearches = "PPC_AffiliatesSearches";
	$TAClicks = "PPC_AffiliatesClicks";
	
	$TCategories = "PPC_Categories";
	
	$TAdminMembersBonus = "PPC_AdminMembersBonus";
	$TAdminSettings = "PPC_AdminMembersSettings";

	$TAdminMembersBonusBU = "PPC_AdminMembersBonusBU";
	$TAdminSettingsBU = "PPC_AdminMembersSettingsBU";
	
	$TAdminSpecialAccounts = "PPC_AdminSpecialAccounts";
	
	$TSearchStatistics = "PPC_SearchStatistics";
	
	$TMembersAccounts = "PPC_MembersAccounts";
	$TMembersCC       = "PPC_MembersCC";
	$TMembersInfo     = "PPC_MembersInfo";
	$TMembersSites    = "PPC_MembersSites";
	$TMembersTerms    = "PPC_MembersTerms";
	$TMembersBids     = "PPC_MembersBids";
	$TMembersBalance  = "PPC_MembersBalance";
	$TMembersClicks   = "PPC_MembersClicks";
	$TMembersLogos   = "PPC_MembersLogos";
	
	$TNoMatchesBids = "PPC_NoMatchesBids";
	$TMembersNMClicks   = "PPC_MembersNoMatchesClicks";
	
	$TBanners = "PPC_Banners";
	$TBannersBids = "PPC_BannersBids";
	$TBannersClicks = "PPC_BannersClicks";
	$TBannersShows = "PPC_BannersShows";
	$TBannersTerms = "PPC_BannersTerms";
	
	$TFiltered = "PPC_AdminFilteredKeywords";
	
	$TTempTermsBids = "PPC_TempTermsBids";
	
	$TMembersTransfers = "PPC_MembersTransfers";
	
	//end of Tables pseudonims
	
?>