<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Members settings               //
	//                                 //
	/////////////////////////////////////
	require($INC."functions/functions_member.php");
	require($INC."functions/functions_payment.php");
	$MEMBERS_MAIL_HEADERS = "From: PPC Search Engine\nMime-Version: 1.0\nContent-type: text/html\n\n";
	
	$MEMBER_REGISTRATION_URL = "registration.php";
	$MEMBER_CP_URL = "index.php";
	$MEMBER_LOGIN_URL = "index.php?ll=1";
	
	//templates

	$ERROR_TMP = $TEMPLATES_DIR."error.html";
	
	$MEMBER_REGISTRATION_1_TMP = $TEMPLATES_DIR."member_registration_1.html";
	$MEMBER_REGISTRATION_2_TMP = $TEMPLATES_DIR."member_registration_2.html";
	$MEMBER_REGISTRATION_3_TMP = $TEMPLATES_DIR."member_registration_3.html";
	$MEMBER_LOGIN_TMP = $TEMPLATES_DIR."member_login.html";
	
	$MEMBER_CP_TMP = $TEMPLATES_DIR."member_cp.html";
	$MEMBER_CP_MAIN_TMP = $TEMPLATES_DIR."member_cp_main.html";
	$MEMBER_CP_MODIFY_TMP = $TEMPLATES_DIR."member_cp_modify.html";
	$MEMBER_CP_LIST_TMP = $TEMPLATES_DIR."member_cp_list.html";
	$MEMBER_CP_LIST_PART_TMP = $TEMPLATES_DIR."member_cp_list_part.html";
	$MEMBER_CP_LIST_PART_EMPTY_TMP = $TEMPLATES_DIR."member_cp_list_part_empty.html";
	$MEMBER_CP_ADDTERM_TMP = $TEMPLATES_DIR."member_cp_addterm.html";
	$MEMBER_CP_EDITTERM_TMP = $TEMPLATES_DIR."member_cp_editterm.html";
	$MEMBER_CP_BALANCE_TMP = $TEMPLATES_DIR."member_cp_balance.html";
	$MEMBER_CP_EDITCC_TMP = $TEMPLATES_DIR."member_cp_ccedit.html";
	$MEMBER_CP_UPDATEBIDS_TMP = $TEMPLATES_DIR."member_cp_updatebids.html";
	$MEMBER_CP_UPDATEBIDS_PART_TMP = $TEMPLATES_DIR."member_cp_updatebids_part.html";
	$MEMBER_CP_UPDATEBIDS_PART_EMPTY_TMP = $TEMPLATES_DIR."member_cp_updatebids_empty.html";
	$MEMBER_CP_STATS_TMP = $TEMPLATES_DIR."member_cp_stats.html";
	$MEMBER_CP_STATS_PART_TMP = $TEMPLATES_DIR."member_cp_stats_part.html";
	$MEMBER_CP_STATS_PART_EMPTY_TMP = $TEMPLATES_DIR."member_cp_stats_empty.html";

	$MEMBER_CP_BANNERS_TMP = $TEMPLATES_DIR."member_cp_banners.html";
	$MEMBER_CP_BANNERS_PART_TMP = $TEMPLATES_DIR."member_cp_banners_part.html";
	$MEMBER_CP_BANNERS_PART_EMPTY_TMP = $TEMPLATES_DIR."member_cp_banners_part_empty.html";
	$MEMBER_CP_ADDBANNER_TMP = $TEMPLATES_DIR."member_cp_addbanner.html";
	$MEMBER_CP_EDITBANNER_TMP = $TEMPLATES_DIR."member_cp_editbanner.html";
	$MEMBER_CP_BANNERSSTATS_TMP  = $TEMPLATES_DIR."member_cp_bannersstats.html";
	$MEMBER_CP_BANNERSSTATS_PART_TMP = $TEMPLATES_DIR."member_cp_bannersstats_part.html";
	$MEMBER_CP_BANNERSSTATS_PART_EMPTY_TMP = $TEMPLATES_DIR."member_cp_bannersstats_part_empty.html";
	
	$MEMBER_CP_NOMATCHESBID_TMP = $TEMPLATES_DIR."member_cp_nomatchesbid.html";
	$MEMBER_CP_NOMATCHESBIDEDIT_TMP = $TEMPLATES_DIR."member_cp_nomatchesbidedit.html";
	$MEMBER_CP_NMSTATS_TMP = $TEMPLATES_DIR."member_cp_nmstats.html";
	$MEMBER_CP_NMSTATS_PART_TMP = $TEMPLATES_DIR."member_cp_nmstats_part.html";
	$MEMBER_CP_NMSTATS_PART_EMPTY_TMP = $TEMPLATES_DIR."member_cp_nmstats_empty.html";
	
	$MEMBER_CP_SSEARCH_TMP = $TEMPLATES_DIR."member_cp_ssearch.html";
	$MEMBER_CP_SSEARCH_RESULTS_TMP = $TEMPLATES_DIR."member_cp_ssearch_results.html";
	$MEMBER_CP_SSEARCH_RESULTS_PARTS_TMP = $TEMPLATES_DIR."member_cp_ssearch_results_parts.html";
	$MEMBER_CP_SSEARCH_RESULTS_PARTS_EMPTY_TMP = $TEMPLATES_DIR."member_cp_ssearch_results_parts_empty.html";

	$MEMBER_CP_ADDBULK_TMP = $TEMPLATES_DIR."member_cp_addbulk.html";
	
	$MEMBER_CP_AFFILIATE_TMP = $TEMPLATES_DIR."member_cp_affiliate.html";
	$MEMBER_CP_AFFILIATE_PART_TMP = $TEMPLATES_DIR."member_cp_affiliate_part.html";
	$AFF_REPORT_TMP = $TEMPLATES_DIR."member_cp_affiliate_report_part.txt";
	$MEMBER_AFFILIATE_CODE_TMP = $TEMPLATES_DIR."member_cp_affiliate_code.html";
	$MEMBER_GET_AFF_MONEY_TMP = $TEMPLATES_DIR."member_cp_affiliate_getmoney.html";
	$MEMBER_GET_AFF_MONEY_TO_BALANCE_COMPLETE_TMP = $TEMPLATES_DIR."member_cp_affiliate_getmoney_to_balance_complete.html";
	
	$MEMBER_F_PW_TMP = $TEMPLATES_DIR."member_fp_tmp.txt";
	
	$MEMBER_COMPLETE_TRANSFER = $TEMPLATES_DIR."member_cp_complete_transfer.html";
	
	$MEMBERS_GET_MONEY_TMP = $TEMPLATES_DIR."member_cp_getmoney_fromaccount.html";
	$MEMBER_COMPLETE_GETMONEY = $TEMPLATES_DIR."member_cp_getmoney_completed.html";
	
	//end of templates
	
	$DEFAULT_IPP = 10;
	
	$BULK_SIZE = 5;

?>