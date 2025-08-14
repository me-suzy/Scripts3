<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Administrators settings        //
	//                                 //
	/////////////////////////////////////
	require($INC."functions/functions_admin.php");
//	require($INC."functions/functions_payment.php");
	require($INC."config/admin_pw.php");
	require($INC."config/config_member.php");
	$ADMIN_URL = "admin.php";
	
	$PAGES_LINKS_TMP = $TEMPLATES_DIR."main_pageslinks.html";
	$MAX_LINKS_PER_PAGE = 10;
	$MAIN_URL = $ADMIN_URL;
	$PAGES_PER_WINDOW = 11;
	
	//templates
	
	$ADMIN_LOGIN_FORM_TMP = $TEMPLATES_DIR."admin_login.html";
	
	$ERROR_TMP = $TEMPLATES_DIR."error.html";
	$ADMIN_MAIN_TMP = $TEMPLATES_DIR."admin_main.html";
	$ADMIN_CATGORIES_TMP = $TEMPLATES_DIR."admin_categories.html";
	$ADMIN_CATEGORIES_PARTS_TMP = $TEMPLATES_DIR."admin_categories_parts.html";
	$ADMIN_CATGORIES_ADD_TMP = $TEMPLATES_DIR."admin_categories_add.html";
	$ADMIN_MEMBERS_TMP = $TEMPLATES_DIR."admin_members.html";
	$ADMIN_MEMBERS_PARTS_TMP = $TEMPLATES_DIR."admin_members_parts.html";
	$ADMIN_MEMBERS_PARTS2_TMP = $TEMPLATES_DIR."admin_members_parts2.html";
	$ADMIN_MEMBERS_PARTS_EMPTY_TMP = $TEMPLATES_DIR."admin_members_parts_empty.html";
	$ADMIN_MEMBERS_MODIFY_TMP = $TEMPLATES_DIR."admin_modifymemberaccount.html";
	$ADMIN_MAINPAGE_TMP = $TEMPLATES_DIR."admin_mainpage.html";
	$ADMIN_SEN_MAIL_TO_MEMBER_TMP = $TEMPLATES_DIR."admin_sendmailtomember.html";
	$ADMIN_SETTINGS_TMP = $TEMPLATES_DIR."admin_settings.html";
	$ADMIN_STATISTICS_FULL_TMP = $TEMPLATES_DIR."admin_stats_main.html";
	$ADMIN_STATISTICS_FULL_PARTS_TMP = $TEMPLATES_DIR."admin_stats_main_parts.html";
	$ADMIN_STATISTICS_FULL_PARTS2_TMP = $TEMPLATES_DIR."admin_stats_main_parts2.html";
	$ADMIN_STATISTICS_DAYLY_TMP = $TEMPLATES_DIR."admin_stats_today.html";
	$ADMIN_STATISTICS_TOPSEARCHES_TMP = $TEMPLATES_DIR."admin_stats_topsearches.html";
	$ADMIN_STATISTICS_TOPBIDS_TMP = $TEMPLATES_DIR."admin_stats_topbids.html";
	$ADMIN_STATISTICS_TOPBIDS_PARTS_TMP = $TEMPLATES_DIR."admin_stats_topbids_parts.html";
	$ADMIN_STATISTICS_TOPBIDS_PARTS2_TMP = $TEMPLATES_DIR."admin_stats_topbids_parts2.html";
	$ADMIN_BANNERSSTATS_TMP = $TEMPLATES_DIR."admin_bannersstats.html";
	
	$ADMIN_STATISTICS_MONTHLY_TMP = $TEMPLATES_DIR."admin_stats_monthly.html";
	
	$ADMIN_FILTER_TMP = $TEMPLATES_DIR."admin_filter.html";
	$ADMIN_FILTER_PARTS_TMP = $TEMPLATES_DIR."admin_filter_parts.html";
	$ADMIN_FILTER_PARTS_EMPTY_TMP = $TEMPLATES_DIR."admin_filter_parts_empty.html";
	
	$ADMIN_BANNERS_TMP = $TEMPLATES_DIR."admin_banners.html";
	$ADMIN_BANNERS_PART_TMP = $TEMPLATES_DIR."admin_banners_parts.html";
	$ADMIN_BANNERS_PART_EMPTY_TMP = $TEMPLATES_DIR."admin_banners_parts_empty.html";
	$ADMIN_ADDBANNER_TMP = $TEMPLATES_DIR."admin_addbanners.html";
	$ADMIN_EDITBANNER_TMP = $TEMPLATES_DIR."admin_editbanners.html";

	$ADMIN_MEMBER_LISTINGS = $TEMPLATES_DIR."admin_memberlistings.html";
	$ADMIN_MEMBERS_LISTINGS_PART = $TEMPLATES_DIR."admin_memberlistings_part.html";
	
	$ADMIN_WAITING_BIDS = $TEMPLATES_DIR."admin_waitingbids.html";
	$ADMIN_WAITING_BIDS_PART = $TEMPLATES_DIR."admin_waitingbids_parts.html";
	$ADMIN_WAITING_BIDS_EMPTY = $TEMPLATES_DIR."admin_waitingbids_empty.html";
	
	$ADMIN_MEMBER_BALANCE = $TEMPLATES_DIR."admin_changebalance.html";
	$ADMIN_LLA_TMP = $TEMPLATES_DIR."admin_lla.html";
	$ADMIN_LLA_PARTS_TMP = $TEMPLATES_DIR."admin_lla_parts.html";
	$ADMIN_LLA_PARTS_EMPTY_TMP = $TEMPLATES_DIR."admin_lla_parts_empty.html";
	
	$ADMIN_LLA_ADD_TMP = $TEMPLATES_DIR."admin_lla_add.html";
	
	$ADMIN_LLA_EDIT_TMP = $TEMPLATES_DIR."admin_lla_edit.html";
	
	$ADMIN_BLOCKED_TMP = $TEMPLATES_DIR."admin_blocked.html";
	
	$ADMIN_PAYMENT_QUERY_TMP = $TEMPLATES_DIR."admin_pq.html";
	$ADMIN_PAYMENT_QUERY_PARTS_TMP = $TEMPLATES_DIR."admin_pq_parts.html";
	$ADMIN_PAYMENT_QUERY_PARTS_EMPTY_TMP = $TEMPLATES_DIR."admin_pq_parts_empty.html";

	$ADMIN_RESTORE_DB_TMP = $TEMPLATES_DIR."admin_restore_db.html";
	$ADMIN_RESTORE_DB_FAILED = $TEMPLATES_DIR."admin_restore_db_failed.html";
	$ADMIN_RESTORE_DB_OK = $TEMPLATES_DIR."admin_restore_db_ok.html";
	
	$ADMIN_AFFILIATES = $TEMPLATES_DIR."admin_affiliates.html";
	$ADMIN_AFFILIATES_PARTS = $TEMPLATES_DIR."admin_affiliates_parts.html";
	$ADMIN_AFFILIATES_PARTS_EMPTY = $TEMPLATES_DIR."admin_affiliates_parts_empty.html";
	
	$ADMIN_GET_MONEY_QUERY = $TEMPLATES_DIR."admin_getmoney_query.html";
	$ADMIN_GET_MONEY_QUERY_PART = $TEMPLATES_DIR."admin_getmoney_query_part.html";
	$ADMIN_GET_MONEY_QUERY_PART_EMPTY = $TEMPLATES_DIR."admin_getmoney_query_part_empty.html";

	//end of templates
	
	$DEFAULT_IPP = 10;
	$ADMIN_EMAIL = "PPC Search Engine Admin <ppc@ettica.com>";
	$MAIL_HEADERS = "From: $ADMIN_MAIL>\nMime-Version: 1.0\nContent-type: text/plain\n\n";

	$ADMIN_RIGHTS = "255";
	
	$ADMIN_MASKS = array(
		"ADMIN_MASK_WB" => 1,
		"ADMIN_MASK_PAY" => 2,
		"ADMIN_MASK_REG" => 4,
		"ADMIN_MASK_BU" => 8,
		"ADMIN_MASK_FILTER" => 16,
		"ADMIN_MASK_AM" => 32
	);
	
	$ADMIN_MASKS_DESCR = array(
		"ADMIN_MASK_WB" => "Waiting bids management",
		"ADMIN_MASK_PAY" => "Payment management",
		"ADMIN_MASK_REG" => "Registration management",
		"ADMIN_MASK_BU" => "Backup mangement",
		"ADMIN_MASK_FILTER" => "Keywords filter management",
		"ADMIN_MASK_AM" => "Affiliates management"
	);
?>