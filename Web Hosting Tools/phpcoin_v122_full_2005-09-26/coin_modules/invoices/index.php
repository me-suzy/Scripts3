<?php

/**************************************************************
 * File: 		Invoices Module Index.php
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_invoices.php
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("index.php", $_SERVER["PHP_SELF"])) {
		require_once ('../../coin_includes/session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01&url=mod.php?mod=invoices');
		exit;
	}

# Get security vars
	$_SEC = get_security_flags ();
	$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);

# Include language file (must be after parameter load to use them)
	require_once($_CCFG['_PKG_PATH_LANG'].'lang_invoices.php');
	IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_invoices_override.php')) {
		require_once($_CCFG['_PKG_PATH_LANG'].'lang_invoices_override.php');
	}

# Include required functions file
	require_once ( $_CCFG['_PKG_PATH_MDLS'].$_GPV[mod].'/'.$_GPV[mod].'_funcs.php');

# Include admin functions file if admin
	IF ($_SEC['_sadmin_flg'])	{ require_once ( $_CCFG['_PKG_PATH_MDLS'].$_GPV[mod].'/'.$_GPV[mod].'_admin.php'); }

# To trigger update each load (uncomment next line)
#	IF ( $_ACFG['INVC_AUTO_UPDATE_ENABLE'] ) { do_auto_invoice_set_status ( ); }

/**************************************************************
 * Module Code
**************************************************************/
# Check $_GPV[mode] and set default
	switch($_GPV[mode])
	{
		case "add":
			IF ( $_GPV['b_delete'] != '' ) { $_GPV[mode] = 'delete'; }
			break;
		case "autocopy":
			break;
		case "autoemail":
			break;
		case "autoupdate":
			break;
		case "copy":
			break;
		case "delete":
			break;
		case "edit":
			IF ( $_GPV['b_delete'] != '' ) { $_GPV[mode] = 'delete'; }
			break;
		case "mail":
			break;
		case "paid":
			break;
		case "trans":
			break;
		case "view":
			break;
		default:
			$_GPV[mode]="view";
			break;
	}

# Set default object
	IF ( !$_GPV[obj] ) { $_GPV[obj] = 'invc'; }

# Build time_stamp values when edit or add
	IF ( $_GPV[mode] == 'add' || $_GPV[mode] == 'edit' || $_GPV[mode] == 'paid' )
		{
			IF ( $_GPV[invc_ts_year] == '' || $_GPV[invc_ts_month] == '' || $_GPV[invc_ts_day] == '' )
					{ $_GPV[invc_ts] = ''; }
			ELSE	{ $_GPV[invc_ts] = mktime( 0,0,0,$_GPV[invc_ts_month],$_GPV[invc_ts_day],$_GPV[invc_ts_year] ); }
			IF ( $_GPV[invc_ts_due_year] == '' || $_GPV[invc_ts_due_month] == '' || $_GPV[invc_ts_due_day] == '' )
					{ $_GPV[invc_ts_due] = ''; }
			ELSE	{ $_GPV[invc_ts_due] = mktime( 0,0,0,$_GPV[invc_ts_due_month],$_GPV[invc_ts_due_day],$_GPV[invc_ts_due_year] ); }
			IF ( $_GPV[invc_ts_paid_year] == '' || $_GPV[invc_ts_paid_month] == '' || $_GPV[invc_ts_paid_day] == '' )
					{ $_GPV[invc_ts_paid] = ''; }
			ELSE	{ $_GPV[invc_ts_paid] = mktime( 0,0,0,$_GPV[invc_ts_paid_month],$_GPV[invc_ts_paid_day],$_GPV[invc_ts_paid_year] ); }

			IF ( $_GPV[it_ts_year] == '' || $_GPV[it_ts_month] == '' || $_GPV[it_ts_day] == '' )
					{ $_GPV[it_ts] = ''; }
			ELSE	{ $_GPV[it_ts] = mktime( 0,0,0,$_GPV[it_ts_month],$_GPV[it_ts_day],$_GPV[it_ts_year] ); }
		}

# Check required fields (err / action generated later in cade as required)
	IF ( $_GPV[stage]==1 )
		{
			# Call validate input function
				$err_entry = do_input_validation($_GPV);
		}

# Build Data Array (may also be over-ridden later in code)
	$data	= $_GPV;


##############################
# Operation:	Any Perm Check
# Summary:
#	- Exit out on perm error.
##############################
IF ( $_SEC['_sadmin_flg'] && $_PERMS[AP16] != 1 && $_PERMS[AP08] != 1 )
	{
		$_PFLAG = ($_GPV[mode]=='add' || $_GPV[mode]=='delete' || $_GPV[mode]=='edit');
		IF ( $_PERMS[AP10] != 1 || ($_PERMS[AP10] == 1 && $_PFLAG) )
			{
				$_out .= '<!-- Start content -->'.$_nl;
				$_out .= do_no_permission_message ();
				$_out .= '<br>'.$_nl;
				echo $_out;
				exit;
			}
	}


##############################
# Mode Call: Login
# Summary:
#	- Session not Registered
##############################
IF ( !$_SEC['_suser_flg'] && !$_SEC['_sadmin_flg'] )
	{
		# Set login flag
			$_login_flag = 1;

		# Call function for articles listings
			$_out = '<!-- Start content -->'.$_nl;
			$_out .= do_login($data, 'user', '1').$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Mode Call: View
# Summary:
#	- View Invoice
##############################
IF ( !$_login_flag && $_GPV[obj]=='invc' && $_GPV[mode]=='view' ) {
	# Set content flag
		$_out = '<!-- Start content -->'.$_nl;

	# Check for $_GPV[invc_id]
		IF ( !$_GPV[invc_id] ) {
			$data['_suser_id']	= $_SEC['_suser_id'];

		# Build Title String, Content String, and Footer Menu String
			IF ( $_SEC['_sadmin_flg'] ) {
				IF ( $_GPV[invc_cl_id] > 0 ) {
					$_title	= $_LANG['_INVCS']['View_Client_Invoices'].$_sp.$_LANG['_INVCS']['l_Client_ID'].$_sp.$_GPV[invc_cl_id];

				# Add parameters "Edit" button
					IF ($_CCFG[ENABLE_QUICK_EDIT] && ($_PERMS[AP16] == 1 || $_PERMS[AP15] == 1)) {
						$_title .= ' <a href="admin.php?cp=parms&op=edit&fpg=&fpgs=invoices">'.$_TCFG['_S_IMG_PM_S'].'</a>';
					}
					$_tstr 	= do_tstr_invc_action_list($_title);
				} ELSE {
					$_title = $_LANG['_INVCS']['View_Client_Invoices'];

				# Add parameters "Edit" button
					IF ($_CCFG[ENABLE_QUICK_EDIT] && ($_PERMS[AP16] == 1 || $_PERMS[AP15] == 1)) {
						$_title .= ' <a href="admin.php?cp=parms&op=edit&fpg=&fpgs=invoices">'.$_TCFG['_S_IMG_PM_S'].'</a>';
					}
					$_tstr 	= do_tstr_invc_action_list($_title);
				}

				IF ( $_CCFG['_IS_PRINT'] == 1 ) { $_mstr_flag = '0'; } ELSE  { $_mstr_flag = '1'; }
					$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
					$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=trans&invc_cl_id='.$_GPV[invc_cl_id], $_TCFG['_IMG_INVC_TRANS_M'],$_TCFG['_IMG_INVC_TRANS_M_MO'],'','');
					IF ( $_PERMS[AP16] == 1 || $_PERMS[AP08] == 1 ) {
						$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
					}
					$_url = '&sb='.$_GPV['sb'].'&so='.$_GPV['so'].'&fb='.$_GPV['fb'].'&fs='.$_GPV['fs'].'&rec_next='.$_GPV['rec_next'];
					$_mstr .= do_nav_link ('mod_print.php?mod=invoices'.$_url, $_TCFG['_IMG_PRINT_M'],$_TCFG['_IMG_PRINT_M_MO'],'_new','');
				} ELSE {
					$_tstr = $_LANG['_INVCS']['View_Client_Invoices_For'].$_sp.':'.$_sp.$_SEC['_suser_name'];

					IF ( $_CCFG['_IS_PRINT'] == 1 ) { $_mstr_flag = '0'; } ELSE  { $_mstr_flag = '1'; }
					$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=trans&invc_cl_id='.$_GPV[invc_cl_id], $_TCFG['_IMG_INVC_TRANS_M'],$_TCFG['_IMG_INVC_TRANS_M_MO'],'','');
					$_url = '&sb='.$_GPV['sb'].'&so='.$_GPV['so'].'&fb='.$_GPV['fb'].'&fs='.$_GPV['fs'].'&rec_next='.$_GPV['rec_next'];
					$_mstr .= do_nav_link ('mod_print.php?mod=invoices'.$_url, $_TCFG['_IMG_PRINT_M'],$_TCFG['_IMG_PRINT_M_MO'],'_new','');
				}

				$_cstr .= '<br>'.$_nl;
				$_cstr .= do_view_invoices ( $data, '1' ).$_nl;
				$_cstr .= '<br>'.$_nl;

			# Call block it function
				$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
				$_out .= '<br>'.$_nl;

			# Echo final output
				echo $_out;

		} ELSE {
			$_out .= do_display_entry( $data, '1' ).$_nl;

		# Echo final output
			echo $_out;
		}
}


##############################
# Mode Call: trans
# Summary:
#	- View Transactions
##############################
IF ( !$_login_flag && $_GPV[obj]=='invc' && $_GPV[mode]=='trans' )
	{
		# Set content flag
			$_out = '<!-- Start content -->'.$_nl;

		# Check for $_GPV[invc_id]
			IF ( !$_GPV[invc_id] )
			{
				$data['_suser_id']	= $_SEC['_suser_id'];
				# Build Title String, Content String, and Footer Menu String

					IF ( $_SEC['_sadmin_flg'] )
					{
						IF ( $_GPV[invc_cl_id] > 0 )
							{ $_tstr = $_LANG['_INVCS']['View_Client_Invc_Transactions'].$_sp.$_LANG['_INVCS']['l_Client_ID'].$_sp.$_GPV[invc_cl_id]; }
						ELSE
							{ $_tstr = $_LANG['_INVCS']['View_Client_Invc_Transactions']; }

						IF ( $_CCFG['_IS_PRINT'] == 1 ) { $_mstr_flag = '0'; } ELSE  { $_mstr_flag = '1'; }
						$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
						$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=trans', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');
						$_mstr .= do_nav_link ('mod_print.php?mod=invoices&mode=trans&invc_cl_id='.$_GPV[invc_cl_id], $_TCFG['_IMG_PRINT_M'],$_TCFG['_IMG_PRINT_M_MO'],'_new','');
					}
					ELSE
					{
						$_tstr = $_LANG['_INVCS']['View_Client_Invc_Transactions_For'].$_sp.':'.$_sp.$_SEC['_suser_name'];

						IF ( $_CCFG['_IS_PRINT'] == 1 ) { $_mstr_flag = '0'; } ELSE  { $_mstr_flag = '1'; }
						$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=trans', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');
						$_mstr .= do_nav_link ('mod_print.php?mod=invoices&mode=trans&invc_cl_id='.$_GPV[invc_cl_id], $_TCFG['_IMG_PRINT_M'],$_TCFG['_IMG_PRINT_M_MO'],'_new','');
					}

					$_cstr .= '<br>'.$_nl;
					$_cstr .= do_view_transactions ( $data, '1' ).$_nl;
					$_cstr .= '<br>'.$_nl;

				# Call block it function
					$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
					$_out .= '<br>'.$_nl;

				# Echo final output
					echo $_out;
			}
			ELSE
			{
				$_out .= do_display_entry( $data, '1' ).$_nl;

				# Echo final output
					echo $_out;
			}
	}


##############################
# Mode Call: Add Entry
# Summary:
#	- For intial entry
#	- For re-entry on error
##############################
IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='add' && (!$_GPV[stage] || $err_entry['flag']))
	{
		# Call function for Add / Edit form.
			$_out = '<!-- Start content -->'.$_nl;
			$_out .= do_form_add_edit ( $data, $err_entry, '1' ).$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Mode Call: Add Entry Results
# Summary:
#	- For processing added entry
#	- Do table insert
#	- Display results
##############################
IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='add' && $_GPV[stage]==1 && !$err_entry['flag'])
	{
		# Call timestamp function
			$_uts = dt_get_uts();

		# Process inputs for quotes
			$_GPV[invc_terms]		= do_addslashes($_GPV[invc_terms]);
			$_GPV[invc_pay_link]	= do_addslashes($_GPV[invc_pay_link]);

		# Dim some Vars:
			$query = ""; $result = ""; $numrows	= 0;

			$query = "INSERT INTO ".$_DBCFG['invoices']." (";
			$query .= "invc_id, invc_status, invc_cl_id, invc_deliv_method";
			$query .= ", invc_delivered, invc_total_cost, invc_total_paid, invc_subtotal_cost";
			$query .= ", invc_tax_01_percent, invc_tax_01_amount, invc_tax_02_percent, invc_tax_02_amount";
			$query .= ", invc_tax_autocalc, invc_ts, invc_ts_due, invc_ts_paid, invc_bill_cycle";
			$query .= ", invc_recurring, invc_recurr_proc, invc_pay_link, invc_terms";
			$query .= ")";

			# Get max / create new invc_id
				$_max_invc_id	= do_get_max_invc_id ( );

			# Determine terms auto entry
				IF ( $_CCFG['INVC_TERMS_INSERT_DEF'] && !$_GPV[invc_terms] )
					{
						IF ( $_CCFG['INV_TERMS_DEF_LINE_01'] != '' ) { $_invc_terms = $_CCFG['INV_TERMS_DEF_LINE_01']; }
						IF ( $_CCFG['INV_TERMS_DEF_LINE_02'] != '' ) { $_invc_terms .= $_nl.$_CCFG['INV_TERMS_DEF_LINE_02']; }
						IF ( $_CCFG['INV_TERMS_DEF_LINE_03'] != '' ) { $_invc_terms .= $_nl.$_CCFG['INV_TERMS_DEF_LINE_03']; }
					}
				ELSE
					{ $_invc_terms = $_GPV[invc_terms]; }

			$query .= " VALUES ( $_max_invc_id+1";
			$query .= ",'$_GPV[invc_status]','$_GPV[invc_cl_id]','$_GPV[invc_deliv_method]'";
			$query .= ",'$_GPV[invc_delivered]','$_GPV[invc_total_cost]','$_GPV[invc_total_paid]','$_GPV[invc_subtotal_cost]'";
			$query .= ",'$_GPV[invc_tax_01_percent]','$_GPV[invc_tax_01_amount]','$_GPV[invc_tax_02_percent]','$_GPV[invc_tax_02_amount]'";
			$query .= ",'$_GPV[invc_tax_autocalc]','$_GPV[invc_ts]','$_GPV[invc_ts_due]','$_GPV[invc_ts_paid]','$_GPV[invc_bill_cycle]'";
			$query .= ",'$_GPV[invc_recurring]','$_GPV[invc_recurr_proc]','$_GPV[invc_pay_link]','$_invc_terms'";
			$query .= ")";

			$result 		= db_query_execute ($query) OR DIE("Unable to complete request");
			$_ins_invc_id	= $_max_invc_id+1;
			$_GPV[invc_id]	= $_ins_invc_id;

		# Insert Invoice Debit Transaction
			$_it_def = 0;
			$_it_desc	= $_LANG['_INVCS']['l_Invoice_ID'].$sp.$_ins_invc_id;
			$q_it = ""; $r_it = ""; $n_it = 0;
			$q_it = "INSERT INTO ".$_DBCFG['invoices_trans']." (";
			$q_it .= "it_ts, it_invc_id, it_type";
			$q_it .= ", it_origin, it_desc, it_amount";
			$q_it .= ") VALUES ( ";
			$q_it .= "'$_GPV[invc_ts]','$_ins_invc_id','$_it_def'";
			$q_it .= ",'$_it_def','$_it_desc','$_GPV[invc_total_cost]'";
			$q_it .= ")";
			$r_it = db_query_execute($q_it);
			$n_it = db_query_numrows($r_it);

			#########################################################################################################
			# API Output Hook:
			# APIO_trans_new: Trasaction Created hook
				$_isfunc = 'APIO_trans_new';
				IF ( $_CCFG['APIO_MASTER_ENABLE'] == 1 && $_CCFG['APIO_TRANS_NEW_ENABLE'] == 1 )
					{
						IF (function_exists($_isfunc))
							{ $_APIO = $_isfunc($_GPV); $_APIO_ret .= '<br>'.$_APIO['msg'].'<br>'; }
						ELSE
							{ $_APIO_ret .= '<br>'.'Error- no function'.'<br>'; }
					}
			#########################################################################################################

		# Content start flag
			$_out = '<!-- Start content -->'.$_nl;

		# Rebuild Data Array with returned record
			$data['stage']		= $_GPV[stage];
			$data['invc_id']	= $_ins_invc_id;

		# Call block it function
			$_out .= do_display_entry ( $data, '1' ).$_nl;
			$_out .= '<br>'.$_nl;

		# Append API results
			$_out .= $_APIO_ret;

		# Echo final output
			echo $_out;
	}


##############################
# Mode Call: Edit Entry
# Summary:
#	- For editing entry
#	- For re-editing on error
##############################
IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='edit' && (!$_GPV[stage] || $err_entry['flag']))
	{
		# Dim some Vars:
			$query = ""; $result = ""; $numrows	= 0;

		# Check for $_GPV[invc_id]- will determine select string (one for edit, all for list)
			IF (!$_GPV[invc_id] || $_GPV[invc_id] == 0 )
				{
					# Set for list.
						$show_list_flag = 1;
				}
				ELSE
				{
					# Set Query for select and execute
						$query = "SELECT * FROM ".$_DBCFG['invoices'];
						$query .= " WHERE invc_id = '$_GPV[invc_id]'";

					# Do select
						$result		= db_query_execute($query);
						$numrows	= db_query_numrows($result);

					# Set for no list.
						$show_list_flag = 0;
				}

		# Check flag- condition is show list
			IF ($show_list_flag)
				{
					# Content start flag
						$_out = '<!-- Start content -->'.$_nl;

					# Build Title String, Content String, and Footer Menu String
						$_tstr = $_LANG['_INVCS']['View_Invoices'];

						$_cstr .= '<br>'.$_nl;
						$_cstr .= do_view_invoices ( $data, '1' ) .$_nl;
						$_cstr .= '<br>'.$_nl;
						$_mstr_flag	= '1';
						$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
						$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
						$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

					# Call block it function
						$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
						$_out .= '<br>'.$_nl;

					# Echo final output
						echo $_out;

				} #if flag_list set

		# Check flag- condition is not show list
			IF (!$show_list_flag)
				{
					# If Stage and Error Entry, pass field vars to form,
					# Otherwise, pass looked up record to form
					IF ( $_GPV[stage] == 1 && $err_entry['flag'] )
						{
							# Call function for Add / Edit form.
								$_out = '<!-- Start content -->'.$_nl;
								$_out .= do_form_add_edit ( $data, $err_entry, '1').$_nl;

							# Echo final output
								echo $_out;
						}
					ELSE
						{
							# Process query results (assumes one returned row above)
							IF ( $numrows )
								{
									# Process query results
										while ($row = db_fetch_array($result))
										{
											# Merge Data Array with returned row
												$data_new	= array_merge( $data, $row );
												$data		= $data_new;
										}
								}

							# Call function for Add / Edit form.
								$_out = '<!-- Start content -->'.$_nl;
								$_out .= do_form_add_edit ( $data, $err_entry, '1').$_nl;

							# Echo final output
								echo $_out;
						}
				}
	}


##############################
# Mode Call: Edit Entry Results
# Summary:
#	- For processing edited entry
#	- Do table update
#	- Display results
##############################
IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='edit' && $_GPV[stage]==1 && !$err_entry['flag'])
	{
		# Dim some Vars:
			$query = ""; $result = ""; $numrows	= 0;

		# Get invoice calc values array data
			# Array passed / and returned (?=a passed, ?=i return)
			#	do_calc_invc_values ( $adata )
			#
			# $adata['invc_id']
			# $?data['invc_total_cost']		- To be calcd
			# $?data['invc_subtotal_cost']	- To be calcd
			# $?data['invc_tax_01_percent']
			# $?data['invc_tax_01_amount']	- To be calcd (flag dependent)
			# $?data['invc_tax_02_percent']
			# $?data['invc_tax_02_amount']	- To be calcd (flag dependent)
			# $?data['invc_tax_autocalc']	- Flag for Autcalc Tax Amounts

			$idata = do_calc_invc_values ( $data );

		# Get rid of commas
			$data['invc_total_cost']		= $idata['invc_total_cost'];		str_replace(',','',$data['invc_total_cost']);
			$data['invc_subtotal_cost']		= $idata['invc_subtotal_cost'];		str_replace(',','',$data['invc_subtotal_cost']);
			$data['invc_tax_01_percent']	= $idata['invc_tax_01_percent'];
			$data['invc_tax_01_amount']		= $idata['invc_tax_01_amount'];		str_replace(',','',$data['invc_tax_01_amount']);
			$data['invc_tax_02_percent']	= $idata['invc_tax_02_percent'];
			$data['invc_tax_02_amount']		= $idata['invc_tax_02_amount'];		str_replace(',','',$data['invc_tax_02_amount']);

		# Update Trans Entry for invoice
			$_GPV[it_type] = 0; $_GPV[it_invc_id] = $_GPV[invc_id]; $_GPV[it_ts] = $_GPV[invc_ts];
			$_tret					= do_set_trans_values ( $_GPV );
			$data[invc_total_paid]	= do_currency_format ( do_get_invc_PTD($_GPV[invc_id]) );

		# Process inputs for quotes
			$_GPV[invc_terms]		= do_addslashes($_GPV[invc_terms]);
			$_GPV[invc_pay_link]	= do_addslashes($_GPV[invc_pay_link]);

		# Do update
			$query = "UPDATE ".$_DBCFG['invoices'];
			$query .= " SET invc_status = '$_GPV[invc_status]', invc_cl_id = '$_GPV[invc_cl_id]'";
			$query .= ", invc_deliv_method = '$data[invc_deliv_method]', invc_delivered = '$data[invc_delivered]'";
			$query .= ", invc_total_cost = '$data[invc_total_cost]', invc_total_paid = '$data[invc_total_paid]'";
			$query .= ", invc_subtotal_cost = '$data[invc_subtotal_cost]'";
			$query .= ", invc_tax_01_percent = '$data[invc_tax_01_percent]', invc_tax_01_amount = '$data[invc_tax_01_amount]'";
			$query .= ", invc_tax_02_percent = '$data[invc_tax_02_percent]', invc_tax_02_amount = '$data[invc_tax_02_amount]'";
			$query .= ", invc_tax_autocalc = '$_GPV[invc_tax_autocalc]'";
			$query .= ", invc_ts = '$_GPV[invc_ts]', invc_ts_due = '$_GPV[invc_ts_due]', invc_ts_paid = '$_GPV[invc_ts_paid]'";
			$query .= ", invc_bill_cycle = '$_GPV[invc_bill_cycle]', invc_recurring = '$_GPV[invc_recurring]'";
			$query .= ", invc_recurr_proc = '$_GPV[invc_recurr_proc]', invc_pay_link = '$_GPV[invc_pay_link]'";
			$query .= ", invc_terms = '$_GPV[invc_terms]'";

			$query .= " WHERE invc_id = $_GPV[invc_id]";

			$result		= db_query_execute($query) OR DIE("Unable to complete request");
			$numrows	= db_query_affected_rows ();

		# Content start flag
			$_out = '<!-- Start content -->'.$_nl;

		# Rebuild Data Array with returned record
			$data['stage']		= $_GPV[stage];
			$data['invc_id']	= $_GPV[invc_id];

		# Call block it function
			$_out .= do_display_entry ( $data, '1' ).$_nl;
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Mode Call: Delete Entry
# Summary Stage 1:
#	- Confirm delete entry
# Summary Stage 2:
#	- Do table update
#	- Display results
##############################
IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='delete' && $_GPV[stage]==1)
	{
		# Content start flag
			$_out .= '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_INVCS']['Delete_Invoice_Entry_Confirmation'];

			# Do confirmation form to content string
			$_cstr = '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=invoices&mode=delete">'.$_nl;
			$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= '<b>'.$_LANG['_INVCS']['Delete_Invoice_Entry_Message'].'='.$_sp.$_GPV[invc_id].'<br>'.$_nl;
			$_cstr .= $_LANG['_INVCS']['Delete_Invoice_Entry_Message_Cont'].'</b>'.$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '<tr><td class="TP0MED_NC">'.$_sp.'</td></tr>'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= $_GPV[invc_id].$_sp.'-'.$_sp.dt_make_datetime ( $_GPV[invc_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] ).$_sp.'-'.$_sp.$_GPV[invc_status].$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="stage" value="2">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="invc_id" value="'.$_GPV[invc_id].'">'.$_nl;
			$_cstr .= do_input_button_class_sw ('b_delete', 'SUBMIT', $_LANG['_INVCS']['B_Delete_Entry'], 'button_form_h', 'button_form', '1').$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</FORM>'.$_nl;

			$_mstr_flag	= '1';
			$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=edit&invc_id='.$_GPV[invc_id], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&invc_id='.$_GPV[invc_id], $_TCFG['_IMG_BACK_TO_INVC_M'],$_TCFG['_IMG_BACK_TO_INVC_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}

IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='delete' && $_GPV[stage]==2)
	{
		# Do query for invoice delete
			$query = ""; $result = ""; $eff_rows = 0;
			$query 			= "DELETE FROM ".$_DBCFG['invoices']." WHERE invc_id = $_GPV[invc_id]";
			$result 		= db_query_execute($query) OR DIE("Unable to complete request");
			$eff_rows		= db_query_affected_rows ();
			$_del_results	.= '<br>'.$_LANG['_INVCS']['Delete_Invoice_Entry_Results_02'].':'.$_sp.$eff_rows;

		# Do query for invoice items delete
			$query_ii = ""; $result_ii = ""; $eff_rows_ii = 0;
			$query_ii 		= "DELETE FROM ".$_DBCFG['invoices_items']." WHERE ii_invc_id = $_GPV[invc_id]";
			$result_ii 		= db_query_execute($query_ii) OR DIE("Unable to complete request");
			$eff_rows_ii	= db_query_affected_rows ();
			$_del_results	.= '<br>'.$_LANG['_INVCS']['Delete_Invoice_Entry_Results_03'].':'.$_sp.$eff_rows_ii;

		# Do query for invoice transactions delete
			$query_it = ""; $result_it = ""; $eff_rows_it = 0;
			$query_it 		= "DELETE FROM ".$_DBCFG['invoices_trans']." WHERE it_invc_id = $_GPV[invc_id]";
			$result_it 		= db_query_execute($query_it) OR DIE("Unable to complete request");
			$eff_rows_it	= db_query_affected_rows ();
			$_del_results	.= '<br>'.$_LANG['_INVCS']['Delete_Invoice_Entry_Results_04'].':'.$_sp.$eff_rows_it;

		# Content start flag
			$_out .= '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_INVCS']['Delete_Invoice_Entry_Results'];

			IF (!$eff_rows )
			{	$_cstr .= '<center>'.$_LANG['_INVCS']['An_error_occurred'].'<br>'.$_del_results.'<br></center>';	}
			ELSE
			{	$_cstr .= '<center>'.$_LANG['_INVCS']['Delete_Invoice_Entry_Results_01'].':<br>'.$_del_results.'<br></center>';	}

			$_mstr_flag	= '1';
			$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=edit', $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Mode Call: Copy Entry
# Summary Stage 1:
#	- Confirm copy entry
# Summary Stage 2:
#	- Do table update
#	- Display results
##############################
IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='copy' && $_GPV[stage] != 2)
	{
		# Content start flag
			$_out .= '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_INVCS']['Copy_Invoice_Entry_Confirmation'];

			# Do confirmation form to content string
			$_cstr = '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=invoices&mode=copy">'.$_nl;
			$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= '<b>'.$_LANG['_INVCS']['Copy_Invoice_Entry_Message'].'='.$_sp.$_GPV[invc_id].'<br>'.$_nl;
			$_cstr .= $_LANG['_INVCS']['Copy_Invoice_Entry_Message_Cont'].'</b>'.$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="stage" value="2">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="invc_id" value="'.$_GPV[invc_id].'">'.$_nl;
			$_cstr .= do_input_button_class_sw ('b_copy', 'SUBMIT', $_LANG['_INVCS']['B_Copy_Invoice'], 'button_form_h', 'button_form', '1').$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</FORM>'.$_nl;

			$_mstr_flag	= '1';
			$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=edit&invc_id='.$_GPV[invc_id], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&invc_id='.$_GPV[invc_id], $_TCFG['_IMG_BACK_TO_INVC_M'],$_TCFG['_IMG_BACK_TO_INVC_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}

IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='copy' && $_GPV[stage]==2)
	{
		# Call Invoice Copy function
			$_new_invc = do_invoice_copy ( $data );

		# Content start flag
			$_out .= '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String
			$_tstr = $_LANG['_INVCS']['Copy_Invoice_Entry_Results'];

			IF ( $_new_invc == 0 )
				{ $_cstr .= '<center><br>'.$_LANG['_INVCS']['Copy_Invoice_Entry_Results_01'].':'.$_sp.$_GPV[invc_id].'<br></center>'; }
			ELSE
				{
					$_cstr .= '<center><br>';
					$_cstr .= $_sp.$_LANG['_INVCS']['Copy_Invoice_Entry_Results_02'].':';
					$_cstr .= $_sp.'<a href="mod.php?mod=invoices&mode=edit&invc_id='.$_GPV[invc_id].'">'.$_GPV[invc_id].'</a>';
					$_cstr .= $_sp.$_LANG['_INVCS']['Copy_Invoice_Entry_Results_03'].':';
					$_cstr .= $_sp.'<a href="mod.php?mod=invoices&mode=edit&invc_id='.$_new_invc.'">'.$_new_invc.'</a>';
					$_cstr .= '<br></center>';
				}

			$_mstr_flag	= '1';
			$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
		#	$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=edit&invc_id='.$_GPV[invc_id], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
		#	$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&invc_id='.$_GPV[invc_id], $_TCFG['_IMG_VIEW_M'],$_TCFG['_IMG_VIEW_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=add', $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
			$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Mode Call: 	ALL Modes
# Object:		Transaction
# Summary Stage 1:
#	- Display Add/Edit Entry
# Summary Stage 2:
#	- Do table update / action
#	- Display results
##############################
IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='trans') {

	# Format amount for math operations
	    $_GPV['it_amount'] = number_format($_GPV['it_amount'], 2, '.', '');

		# View Mode
		/*
		IF ( $_GPV[mode]=='view' )
			{
				IF ( (!$_GPV[stage] || $err_entry['flag']) )
					{
						# Call function for Add / Edit form.
							$_out = '<!-- Start content -->'.$_nl;
							$_out .= do_display_items_editor ( $data, $err_entry, '1' ).$_nl;

						# Echo final output
							echo $_out;
					}
			}
		*/
		# End View Mode

		# Start Add Mode
		IF ($_GPV[mode]=='add')
			{
				IF ($_GPV[stage]!=2)
					{
						# Content start flag
							$_out .= '<!-- Start content -->'.$_nl;

						# Build Title String, Content String, and Footer Menu String
							$_tstr = $_LANG['_INVCS']['Set_Payment_Entry_Confirmation'];
							$_cstr .= do_form_add_edit_trans ( $data, $err_entry, '1' ) ;

							$_mstr_flag	= '1';
							$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
						#	$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=edit&it_invc_id='.$_GPV[it_invc_id], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&invc_id='.$_GPV[it_invc_id], $_TCFG['_IMG_BACK_TO_INVC_M'],$_TCFG['_IMG_BACK_TO_INVC_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=add&obj=trans&it_invc_id='.$_GPV[it_invc_id], $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=trans', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

						# Call block it function
							$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
							$_out .= '<br>'.$_nl;

						# Echo final output
							echo $_out;
					}
				ELSE
					{
						# Set field values for update.
							IF ( !$_GPV['it_ts'] ) { $_GPV[it_ts] = dt_get_uts(); }

						# Insert Invoice Debit Transaction
							$_it_def = 0;
							$_it_desc	= $_LANG['_INVCS']['l_Invoice_ID'].' - '.$_ins_invc_id;
							$q_it = ""; $r_it = ""; $n_it = 0;
							$q_it = "INSERT INTO ".$_DBCFG['invoices_trans']." (";
							$q_it .= "it_ts, it_invc_id, it_type";
							$q_it .= ", it_origin, it_desc, it_amount";
							$q_it .= ") VALUES ( ";
							$q_it .= "'$_GPV[it_ts]','$_GPV[it_invc_id]','$_GPV[it_type]'";
							$q_it .= ",'$_GPV[it_origin]','$_GPV[it_desc]','$_GPV[it_amount]'";
							$q_it .= ")";
							$r_it = db_query_execute($q_it);
							$i_id = db_query_insertid ();
							$_GPV[it_id] = $i_id;

						#########################################################################################################
						# API Output Hook:
						# APIO_trans_new: Trasaction Created hook
							$_isfunc = 'APIO_trans_new';
							IF ( $_CCFG['APIO_MASTER_ENABLE'] == 1 && $_CCFG['APIO_TRANS_NEW_ENABLE'] == 1 )
								{
									IF (function_exists($_isfunc))
										{ $_APIO = $_isfunc($_GPV); $_APIO_ret .= '<br>'.$_APIO['msg'].'<br>'; }
									ELSE
										{ $_APIO_ret .= '<br>'.'Error- no function'.'<br>'; }
								}
						#########################################################################################################

						# Do status calc
							$ptd = do_get_invc_PTD( $_GPV[it_invc_id]);

							IF ( $_GPV[it_set_paid] == 1 )
								{ $_us = 1; $_GPV[invc_status] = $_CCFG['INV_STATUS'][3]; }
							ELSE
								{
									# Get invoice amount
										$idata	= do_get_invc_values ( $_GPV[it_invc_id] );

									# Check against PTD
										IF ( $idata[invc_total_cost] <= $ptd )
											{  $_us = 1; $_GPV[invc_status] = $_CCFG['INV_STATUS'][3]; }
								}

						# Do update invoice record
							$query = ""; $result = ""; $numrows = 0;
							$query		= "UPDATE ".$_DBCFG['invoices']." SET ";
							$query		.= " invc_ts_paid = '$_GPV[it_ts]'";
							$query		.= ", invc_total_paid = '$ptd'";
							IF ( $_us == 1 && $_GPV[invc_status] != '' )
								{ $query .= ", invc_status = '$_GPV[invc_status]'"; }
							$query		.= " WHERE invc_id = $_GPV[it_invc_id]";

							$result		= db_query_execute($query) OR DIE("Unable to complete request");
							$numrows	= db_query_affected_rows ();

						# Trans ack email trigger / check
							IF ( $_GPV['it_email_ack'] == 1 )
								{
									# Call email trans ack function (ret=0 on error)
										$data['it_id']		= $_GPV[it_id];
										$data['invc_id']	= $_GPV[it_invc_id];
										$data['template']	= 'email_trans_ack';
										$_ret = do_mail_invoice( $data, '1' ).$_nl;
								}

						# Content start flag
							$_out = '<!-- Start content -->'.$_nl;

						# Rebuild Data Array with returned record
							$data['stage']		= $_GPV[stage];
							$data['invc_id']	= $_GPV[it_invc_id];

						# Call block it function
							$_out .= do_display_entry ( $data, '1' ).$_nl;
							$_out .= '<br>'.$_nl;

						# Append API results
							$_out .= $_APIO_ret;

						# Echo final output
							echo $_out;
					}
			}
		# End Add Mode

		# Start Edit Mode
		IF ($_GPV[mode]=='edit')
			{
				IF ($_GPV[stage]!=2)
					{
						# Set Query for select and execute
							$query = "SELECT * FROM ".$_DBCFG['invoices_trans'];
							IF ( $_GPV[it_type] == 0 )
								{ $query .= " WHERE it_id = $_GPV[it_id] AND it_type = 0"; }
							#	{ $query .= " WHERE it_invc_id = $_GPV[it_invc_id] AND it_type = 0"; }
							ELSE
								{ $query .= " WHERE it_id = $_GPV[it_id]"; }

						# Do select
							$result		= db_query_execute($query) OR DIE("Unable to complete request");
							$numrows	= db_query_numrows($result);

						# Process query results (assumes one returned row above)
							IF ( $numrows ) {
								while ($row = db_fetch_array($result))
								{
									# Merge Data Array with returned row
										$data_new			= array_merge( $data, $row );
										$data				= $data_new;
										$data['it_invc_id']	= $row[it_invc_id];
										$_GPV[it_invc_id]	= $row[it_invc_id];
								}
							}

						# Content start flag
							$_out .= '<!-- Start content -->'.$_nl;

						# Build Title String, Content String, and Footer Menu String
							$_tstr = $_LANG['_INVCS']['Set_Payment_Entry_Confirmation'];
							$_cstr .= do_form_add_edit_trans ( $data, $err_entry, '1' ) ;

							$_mstr_flag	= '1';
							$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
						#	$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=edit&invc_id='.$_GPV[it_invc_id], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&invc_id='.$_GPV[it_invc_id], $_TCFG['_IMG_BACK_TO_INVC_M'],$_TCFG['_IMG_BACK_TO_INVC_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=add&obj=trans&it_invc_id='.$_GPV[it_invc_id], $_TCFG['_IMG_ADD_NEW_M'],$_TCFG['_IMG_ADD_NEW_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=trans', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

						# Call block it function
							$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
							$_out .= '<br>'.$_nl;

						# Echo final output
							echo $_out;
					}
				ELSE
					{
						# Set field values for update.
							IF ( !$_GPV['it_ts'] ) { $_GPV[it_ts] = dt_get_uts(); }

						# do_set_trans_values ( $atdata )
							$_tret = do_set_trans_values ( $_GPV );

						# Do status calc
							$ptd = do_get_invc_PTD ( $_GPV[it_invc_id] );

							IF ( $_GPV[it_set_paid] == 1 )
								{ $_us = 1; $_GPV[invc_status] = $_CCFG['INV_STATUS'][3]; }
							ELSE
								{
									# Get invoice amount
										$idata	= do_get_invc_values ( $_GPV[it_invc_id] );

									# Check against PTD
										IF ( $idata[invc_total_cost] <= $ptd )
											{  $_us = 1; $_GPV[invc_status] = $_CCFG['INV_STATUS'][3]; }
								}

						# Do update invoice record
							$query = ""; $result = ""; $numrows = 0;
							$query		= "UPDATE ".$_DBCFG['invoices']." SET ";
							$query		.= " invc_ts_paid = '$_GPV[it_ts]'";
							$query		.= ", invc_total_paid = '$ptd'";
							IF ( $_us == 1 && $_GPV[invc_status] != '' )
								{ $query .= ", invc_status = '$_GPV[invc_status]'"; }
							$query		.= " WHERE invc_id = $_GPV[it_invc_id]";

							$result		= db_query_execute($query) OR DIE("Unable to complete request");
							$numrows	= db_query_affected_rows ();

						# Trans ack email trigger / check
							IF ( $_GPV['it_email_ack'] == 1 )
								{
									# Call email trans ack function (ret=0 on error)
										$data['it_id']		= $_GPV[it_id];
										$data['invc_id']	= $_GPV[it_invc_id];
										$data['template']	= 'email_trans_ack';
										$_ret = do_mail_invoice( $data, '1' ).$_nl;
								}

						# Content start flag
							$_out = '<!-- Start content -->'.$_nl;

						# Rebuild Data Array with returned record
							$data['stage']		= $_GPV[stage];
							$data['invc_id']	= $_GPV[it_invc_id];

						# Call block it function
							$_out .= do_display_entry ( $data, '1' ).$_nl;
							$_out .= '<br>'.$_nl;

						# Echo final output
							echo $_out;
					}
			}
		# End Edit Mode

		# Delete Mode
		IF ( $_GPV[mode]=='delete' )
			{
				IF ( $_GPV[stage]!=2 )
					{
						# Content start flag
							$_out .= '<!-- Start content -->'.$_nl;

						# Build Title String, Content String, and Footer Menu String
							$_tstr = $_LANG['_INVCS']['Delete_Trans_Entry_Confirmation'];

							# Do confirmation form to content string
							$_cstr = '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=invoices&mode=delete&obj=trans">'.$_nl;
							$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
							$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
							$_cstr .= '<b>'.$_LANG['_INVCS']['Delete_Trans_Entry_Message'].'='.$_sp.$_GPV[it_id].'?</b>'.$_nl;
							$_cstr .= '</td></tr>'.$_nl;
							$_cstr .= '<tr><td class="TP0MED_NC">'.$_sp.'</td></tr>'.$_nl;
						#	$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
						#	$_cstr .= $_GPV[ii_invc_id].'-'.$_GPV[ii_item_no].$_nl;
						#	$_cstr .= '</td></tr>'.$_nl;
							$_cstr .= '<tr><td class="TP0MED_NC">'.$_sp.'</td></tr>'.$_nl;
							$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
							$_cstr .= '<INPUT TYPE=hidden name="stage" value="2">'.$_nl;
							$_cstr .= '<INPUT TYPE=hidden name="it_id" value="'.$_GPV[it_id].'">'.$_nl;
							$_cstr .= do_input_button_class_sw ('b_delete', 'SUBMIT', $_LANG['_INVCS']['B_Delete_Entry'], 'button_form_h', 'button_form', '1').$_nl;
							$_cstr .= '</td></tr>'.$_nl;
							$_cstr .= '</table>'.$_nl;
							$_cstr .= '</FORM>'.$_nl;

							$_mstr_flag	= '1';
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=edit&obj=trans&it_id='.$_GPV[it_id], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&obj=trans&it_id='.$_GPV[it_id], $_TCFG['_IMG_BACK_TO_INVC_M'],$_TCFG['_IMG_BACK_TO_INVC_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&obj=trans&it_id='.$_GPV[it_id], $_TCFG['_IMG_IITEMS_EDITOR_M'],$_TCFG['_IMG_IITEMS_EDITOR_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=trans', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

						# Call block it function
							$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
							$_out .= '<br>'.$_nl;

						# Echo final output
							echo $_out;
					}

				IF ( $_GPV[stage]==2 )
					{
						# Do select
							$query = ""; $result = ""; $numrows	= 0;
							$query 		= "DELETE FROM ".$_DBCFG['invoices_trans'];
							$query		.= " WHERE it_id = $_GPV[it_id]";
							$result 	= db_query_execute($query) OR DIE("Unable to complete request");
							$eff_rows	= db_query_affected_rows ();

						# Do update invoice record
							$ptd = do_get_invc_PTD ( $_GPV[it_invc_id] );
							$query = ""; $result = ""; $numrows = 0;
							$query		= "UPDATE ".$_DBCFG['invoices']." SET ";
							$query		.= " invc_total_paid = '$ptd'";
							$query		.= " WHERE invc_id = $_GPV[it_invc_id]";
							$result		= db_query_execute($query) OR DIE("Unable to complete request");
							$numrows	= db_query_affected_rows ();

						#########################################################################################################
						# API Output Hook:
						# APIO_trans_del: Trasaction Deleted hook
							$_isfunc = 'APIO_trans_del';
							IF ( $_CCFG['APIO_MASTER_ENABLE'] == 1 && $_CCFG['APIO_TRANS_DEL_ENABLE'] == 1 )
								{
									IF (function_exists($_isfunc))
										{ $_APIO = $_isfunc($_GPV); $_APIO_ret .= '<br>'.$_APIO['msg'].'<br>'; }
									ELSE
										{ $_APIO_ret .= '<br>'.'Error- no function'.'<br>'; }
								}
						#########################################################################################################

						# Content start flag
							$_out .= '<!-- Start content -->'.$_nl;

						# Build Title String, Content String, and Footer Menu String
							$_tstr = $_LANG['_INVCS']['Delete_Trans_Entry_Results'];

							IF (!$eff_rows)
							{	$_cstr .= '<center>'.$_LANG['_INVCS']['An_error_occurred'].'</center>';	}
							ELSE
							{
								$_cstr	.= '<center>'.$_LANG['_INVCS']['Delete_Trans_Entry_Results_01'].'</center>';
							}

						# Append API results
							$_cstr .= $_APIO_ret;

							$_mstr_flag	= '1';
							$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=trans', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

						# Call block it function
							$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
							$_out .= '<br>'.$_nl;

						# Echo final output
							echo $_out;
					}
			}
		# End Delete Mode


	}


##############################
# Mode Call: 	ALL Modes
# Object:		Item
# Summary:
#	- For all items
##############################
IF ($_SEC['_sadmin_flg'] && $_GPV[obj]=='iitem')
	{
		# View Mode
		IF ( $_GPV[mode]=='view' )
			{
				IF ( (!$_GPV[stage] || $err_entry['flag']) )
					{
						# Call function for Add / Edit form.
							$_out = '<!-- Start content -->'.$_nl;
							$_out .= do_display_items_editor ( $data, $err_entry, '1' ).$_nl;

						# Echo final output
							echo $_out;
					}
			}
		# End View Mode

		# Add Mode
		IF ( $_GPV[mode]=='add' )
			{
				IF ( (!$_GPV[stage] || $err_entry['flag']) )
					{
						# Call function for Add / Edit form.
							$_out = '<!-- Start content -->'.$_nl;
							$_out .= do_display_items_editor ( $data, $err_entry, '1' ).$_nl;

						# Echo final output
							echo $_out;
					}
				ELSE IF ( ($_GPV[stage] && !$err_entry['flag']) )
					{
						# Dim some Vars:
							$query = ""; $result = ""; $numrows	= 0;

						# Check and if product, retrieve and set vars.
							IF ( $_GPV[ii_prod_add] && $_GPV[ii_prod_id] )
								{
									# Get current product price
										$query_prod = ""; $result_prod = ""; $numrows_prod = 0;
										$query_prod		= "SELECT * FROM ".$_DBCFG['products']." WHERE prod_id = $_GPV[ii_prod_id]";
										$result_prod 	= db_query_execute($query_prod) OR DIE("Unable to complete request");
										$numrows_prod	= db_query_numrows($result_prod);

									# Process query results
										IF ( $numrows_prod ) {
											while ($row = db_fetch_array($result_prod))
											{
												$_GPV[ii_item_name]			= $row['prod_name'];
												$_GPV[ii_item_desc]			= $row['prod_desc'];
												$_GPV[ii_item_cost]			= $row['prod_unit_cost'];
												$_GPV[ii_apply_tax_01]		= $row['prod_apply_tax_01'];
												$_GPV[ii_apply_tax_02]		= $row['prod_apply_tax_02'];
												$_GPV[ii_calc_tax_02_pb]	= $row['prod_calc_tax_02_pb'];
											}
										}
								}

						# Get max / create new ii_item_no
							$_max_invc_item_no	= do_get_max_invc_item_no ( $_GPV[invc_id] );

						# Build SQL and execute.
							$query	= "INSERT INTO ".$_DBCFG['invoices_items']." (";
							$query	.= "ii_invc_id, ii_item_no, ii_item_name";
							$query	.= ", ii_item_desc, ii_item_cost";
							$query	.= ", ii_apply_tax_01, ii_apply_tax_02, ii_calc_tax_02_pb";

							$query	.= ") VALUES (";
							$query	.= " '$_GPV[ii_invc_id]',($_max_invc_item_no+1),'$_GPV[ii_item_name]'";
							$query	.= ",'$_GPV[ii_item_desc]','$_GPV[ii_item_cost]'";
							$query	.= ",'$_GPV[ii_apply_tax_01]','$_GPV[ii_apply_tax_02]','$_GPV[ii_calc_tax_02_pb]'";
							$query	.= ")";

							$result	= db_query_execute ($query) OR DIE("Unable to complete request");

							$_ret	= do_set_invc_values ( $_GPV[ii_invc_id] );

						# Content start flag
							$_out = '<!-- Start content -->'.$_nl;

						# Rebuild Data Array with returned record
							$data['stage']				= $_GPV[stage];
							$data['mode']				= 'view';
							$data['invc_id']			= $_GPV[invc_id];
							$data['ii_invc_id']			= '';
							$data['ii_item_no']			= '';
							$data['ii_item_name']		= '';
							$data['ii_item_desc']		= '';
							$data['ii_item_cost']		= '';
						#	$data['ii_apply_tax_01']	= 1;
						#	$data['ii_apply_tax_02']	= 1;
						#	$data['ii_calc_tax_02_pb']	= 1;

						# Call block it function
							$_out .= do_display_items_editor ( $data, $err_entry, '1' ).$_nl;
							$_out .= '<br>'.$_nl;

						# Echo final output
							echo $_out;
					}
			}
		# End Add Mode

		# Start Edit Mode
		IF ( $_GPV[mode]=='edit' )
			{
				IF ( (!$_GPV[stage] || $err_entry['flag']) )
					{
						# Dim some Vars:
							$query = ""; $result = ""; $numrows	= 0;

						# Check for id's- will determine select string (one for edit, all for list)
							IF (!$_GPV[ii_invc_id] || $_GPV[ii_invc_id] == 0 || !$_GPV[ii_item_no] || $_GPV[ii_item_no] == 0)
							{
								# Set for list.
									$show_list_flag = 1;
							}
							ELSE
							{
								# Set Query for select and execute
									$query = "SELECT * FROM ".$_DBCFG['invoices_items'];
									$query .= " WHERE ii_invc_id = '$_GPV[ii_invc_id]'";
									$query .= " AND ii_item_no = '$_GPV[ii_item_no]'";

								# Do select
									$result		= db_query_execute($query);
									$numrows	= db_query_numrows($result);

								# Set for no list.
									$show_list_flag = 0;
							}

						# Check flag- condition is show list
						IF ($show_list_flag)
						{
							# Rebuild Data Array with returned record
								$data['invc_id'] = $_GPV[ii_invc_id];

							# Call function for Add / Edit form.
								$_out = '<!-- Start content -->'.$_nl;
								$_out .= do_display_items_editor ( $data, $err_entry, '1' ).$_nl;

							# Echo final output
								echo $_out;

						} #if flag_list set

						# Check flag- condition is not show list
						IF (!$show_list_flag)
						{
							# If Stage and Error Entry, pass field vars to form,
							# Otherwise, pass looked up record to form
							IF ( $_GPV[stage] == 1 && $err_entry['flag'] )
							{
								# Rebuild Data Array with returned record
									$data['invc_id'] = $_GPV[ii_invc_id];

								# Call function for Add / Edit form.
									$_out = '<!-- Start content -->'.$_nl;
									$_out .= do_display_items_editor ( $data, $err_entry, '1' ).$_nl;

								# Echo final output
									echo $_out;
							}
							ELSE
							{
								# Process query results (assumes one returned row above)
									IF ( $numrows )
										{
											# Process query results
												while ($row = db_fetch_array($result))
												{
													# Merge Data Array with returned row
														$data_new			= array_merge( $data, $row );
														$data				= $data_new;
														$data['invc_id']	= $row[ii_invc_id];
												}
										}

								# Call function for Add / Edit form.
									$_out = '<!-- Start content -->'.$_nl;
									$_out .= do_display_items_editor ( $data, $err_entry, '1' ).$_nl;

								# Echo final output
									echo $_out;
							}
						}
					}
				ELSE IF ( ($_GPV[stage] && !$err_entry['flag']) )
					{
						# Dim some Vars:
							$query = ""; $result = ""; $numrows	= 0;

						# Do update
							$query = "UPDATE ".$_DBCFG['invoices_items'];
							$query .= " SET ii_item_no = '$_GPV[ii_item_no]'";
							$query .= ", ii_item_name = '$_GPV[ii_item_name]'";
							$query .= ", ii_item_desc = '$_GPV[ii_item_desc]'";
							$query .= ", ii_item_cost = '$_GPV[ii_item_cost]'";
							$query .= ", ii_apply_tax_01 = '$_GPV[ii_apply_tax_01]'";
							$query .= ", ii_apply_tax_02 = '$_GPV[ii_apply_tax_02]'";
							$query .= ", ii_calc_tax_02_pb = '$_GPV[ii_calc_tax_02_pb]'";

							$query .= " WHERE ii_invc_id = $_GPV[ii_invc_id]";
							$query .= " AND ii_item_no = $_GPV[ii_item_no_orig]";

							$result		= db_query_execute($query) OR DIE("Unable to complete request");
							$numrows	= db_query_affected_rows ();

							$_ret	= do_set_invc_values ( $_GPV[ii_invc_id] );

						# Rebuild Data Array with returned record
							$data['stage']				= $_GPV[stage];
							$data['mode']				= 'view';
							$data['invc_id']			= $_GPV[ii_invc_id];
							$data['ii_invc_id']			= $_GPV[ii_invc_id];
							$data['ii_item_no']			= '';
							$data['ii_item_no_orig']	= '';
							$data['ii_item_name']		= '';
							$data['ii_item_desc']		= '';
							$data['ii_item_cost']		= '';
						#	$data['ii_apply_tax_01']	= 1;
						#	$data['ii_apply_tax_02']	= 1;
						#	$data['ii_calc_tax_02_pb']	= 1;

						# Call block it function
							$_out .= do_display_items_editor ( $data, $err_entry, '1' ).$_nl;
							$_out .= '<br>'.$_nl;

						# Echo final output
							echo $_out;
					}
			}
		# End Edit Mode

		# Delete Mode
		IF ( $_GPV[mode]=='delete' )
			{
				IF ( $_GPV[stage]!=2 )
					{
						# Content start flag
							$_out .= '<!-- Start content -->'.$_nl;

						# Build Title String, Content String, and Footer Menu String
							$_tstr = $_LANG['_INVCS']['Delete_IItem_Entry_Confirmation'];

							# Do confirmation form to content string
							$_cstr = '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=invoices&mode=delete&obj=iitem">'.$_nl;
							$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
							$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
							$_cstr .= '<b>'.$_LANG['_INVCS']['Delete_IItem_Entry_Message'].'='.$_sp.$_GPV[ii_invc_id].'-'.$_GPV[ii_item_no].'?</b>'.$_nl;
							$_cstr .= '</td></tr>'.$_nl;
							$_cstr .= '<tr><td class="TP0MED_NC">'.$_sp.'</td></tr>'.$_nl;
							$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
							$_cstr .= $_GPV[ii_invc_id].'-'.$_GPV[ii_item_no].$_nl;
							$_cstr .= '</td></tr>'.$_nl;
							$_cstr .= '<tr><td class="TP0MED_NC">'.$_sp.'</td></tr>'.$_nl;
							$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
							$_cstr .= '<INPUT TYPE=hidden name="stage" value="2">'.$_nl;
							$_cstr .= '<INPUT TYPE=hidden name="ii_invc_id" value="'.$_GPV[ii_invc_id].'">'.$_nl;
							$_cstr .= '<INPUT TYPE=hidden name="ii_item_no" value="'.$_GPV[ii_item_no].'">'.$_nl;
							$_cstr .= do_input_button_class_sw ('b_delete', 'SUBMIT', $_LANG['_INVCS']['B_Delete_Entry'], 'button_form_h', 'button_form', '1').$_nl;
							$_cstr .= '</td></tr>'.$_nl;
							$_cstr .= '</table>'.$_nl;
							$_cstr .= '</FORM>'.$_nl;

							$_mstr_flag	= '1';
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=edit&obj=iitem&invc_id='.$_GPV[ii_invc_id].'&ii_item_no='.$_GPV[ii_item_no], $_TCFG['_IMG_EDIT_M'],$_TCFG['_IMG_EDIT_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&invc_id='.$_GPV[ii_invc_id], $_TCFG['_IMG_BACK_TO_INVC_M'],$_TCFG['_IMG_BACK_TO_INVC_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&obj=iitem&invc_id='.$_GPV[ii_invc_id], $_TCFG['_IMG_IITEMS_EDITOR_M'],$_TCFG['_IMG_IITEMS_EDITOR_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

						# Call block it function
							$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
							$_out .= '<br>'.$_nl;

						# Echo final output
							echo $_out;
					}

				IF ( $_GPV[stage]==2 )
					{
						# Dim some Vars:
							$query = ""; $result = ""; $numrows	= 0;

						# Do select
							$query 		= "DELETE FROM ".$_DBCFG['invoices_items'];
							$query		.= " WHERE ii_invc_id = $_GPV[ii_invc_id]";
							$query		.= " AND ii_item_no = $_GPV[ii_item_no]";
							$result 	= db_query_execute($query) OR DIE("Unable to complete request");
							$eff_rows	= db_query_affected_rows ();

						# Content start flag
							$_out .= '<!-- Start content -->'.$_nl;

						# Build Title String, Content String, and Footer Menu String
							$_tstr = $_LANG['_INVCS']['Delete_IItem_Entry_Results'];

							IF (!$eff_rows)
							{	$_cstr .= '<center>'.$_LANG['_INVCS']['An_error_occurred'].'</center>';	}
							ELSE
							{
								$_ret	= do_set_invc_values ( $_GPV[ii_invc_id] );
								$_cstr	.= '<center>'.$_LANG['_INVCS']['Delete_IItem_Entry_Results_01'].'</center>';
							}

							$_mstr_flag	= '1';
							$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&obj=iitem&invc_id='.$_GPV[ii_invc_id], $_TCFG['_IMG_IITEMS_EDITOR_M'],$_TCFG['_IMG_IITEMS_EDITOR_M_MO'],'','');
							$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'].'','');

						# Call block it function
							$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
							$_out .= '<br>'.$_nl;

						# Echo final output
							echo $_out;
					}
			}
		# End Delete Mode

	} #last one for items


##############################
# Mode Call: Mail
# Summary:
#	- eMail Invoice
##############################
IF ( !$_login_flag && $_GPV[obj]=='invc' && $_GPV[mode]=='mail' ) {
	IF ( $_GPV[stage] != 2 ) {

	# Content start flag
		$_out .= '<!-- Start content -->'.$_nl;

	# Build Title String, Content String, and Footer Menu String
		$_tstr = $_LANG['_INVCS']['eMail_Invoice_Confirmation'];

	# Do confirmation form to content string
		$_cstr = '<FORM METHOD="POST" ACTION="'.$_SERVER["PHP_SELF"].'?mod=invoices&mode=mail">'.$_nl;
		$_cstr .= '<table cellpadding="5" width="100%">'.$_nl;
		$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
		$_cstr .= '<b>'.$_LANG['_INVCS']['eMail_Invoice_Message_prefix'].$_sp.$_GPV[invc_id].$_sp.$_LANG['_INVCS']['eMail_Invoice_Message_suffix'].'</b>'.$_nl;
		$_cstr .= '</td></tr>'.$_nl;
		$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
		$_cstr .= '<INPUT TYPE=hidden name="stage" value="2">'.$_nl;
		$_cstr .= '<INPUT TYPE=hidden name="invc_id" value="'.$_GPV[invc_id].'">'.$_nl;
		$_cstr .= do_input_button_class_sw ('b_email', 'SUBMIT', $_LANG['_INVCS']['B_Send_Email'], 'button_form_h', 'button_form', '1').$_nl;
		$_cstr .= '</td></tr>'.$_nl;
		$_cstr .= '</table>'.$_nl;
		$_cstr .= '</FORM>'.$_nl;

		$_mstr_flag	= '1';
		IF ( $_SEC['_sadmin_flg'] == 1 ) { do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'',''); }
		$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view&invc_id='.$_GPV[invc_id], $_TCFG['_IMG_BACK_TO_INVC_M'],$_TCFG['_IMG_BACK_TO_INVC_M_MO'],'','');
		$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');

	# Call block it function
		$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
		$_out .= '<br>'.$_nl;

	# Echo final output
		echo $_out;
	}

	IF ( $_GPV[stage] == 2 ) {

	# Call function for doing it.
		$_out = '<!-- Start content -->'.$_nl;
		$_out .= do_mail_invoice( $data, '1' ).$_nl;

	# Echo final output
		echo $_out;
	}
}


##############################
# Mode Call: Auto-Update
# Summary:
#	- Auto-Set Invoice Status
##############################
IF ( $_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='autoupdate' )
	{
		# Set content flag
			$_out = '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String

			IF ( $_SEC['_sadmin_flg'] )
			{
				$_tstr = $_LANG['_INVCS']['Auto_Invoice_Update_Results'].':';

				IF ( $_CCFG['_IS_PRINT'] == 1 ) { $_mstr_flag = '0'; } ELSE  { $_mstr_flag = '1'; }
				$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
				$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');
			}

			$_cstr .= '<div align="center">'.$_nl;
			IF ( $_ACFG['INVC_AUTO_UPDATE_ENABLE'] == 1 )
				{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Update_Status'].$_sp.do_auto_invoice_set_status ( ).$_nl; }
			ELSE
				{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Update_Status'].$_sp.$_LANG['_INVCS']['Function_Disabled'].$_nl; }
			$_cstr .= '<br><br>'.$_nl;
			$_cstr .= '</div>'.$_nl;

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Mode Call: Auto-Email
# Summary:
#	- Auto-Set Invoice Status
#	  and Email due not sent.
##############################
IF ( $_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='autoemail' )
	{
		# Set content flag
			$_out = '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String
			IF ( $_SEC['_sadmin_flg'] )
			{
				$_tstr = $_LANG['_INVCS']['Auto_Invoice_Email_Results'].':';

				IF ( $_CCFG['_IS_PRINT'] == 1 ) { $_mstr_flag = '0'; } ELSE  { $_mstr_flag = '1'; }
				$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
				$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');
			}

			$_cstr .= '<div align="center">'.$_nl;
			IF ( $_ACFG['INVC_AUTO_UPDATE_ENABLE'] == 1 )
				{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Update_Status'].$_sp.do_auto_invoice_set_status ( ).$_nl; }
			ELSE
				{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Update_Status'].$_sp.$_LANG['_INVCS']['Function_Disabled'].$_nl; }
			IF ( $_CCFG['INVC_AUTO_EMAIL_ENABLE'] == 1 )
				{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Email_Due'].$_sp.do_auto_invoice_emails ( ).$_nl; }
			ELSE
				{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Email_Due'].$_sp.$_LANG['_INVCS']['Function_Disabled'].$_nl; }
			$_cstr .= '<br><br>'.$_nl;
			$_cstr .= '</div>'.$_nl;

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}


##############################
# Mode Call: Auto-Copy
# Summary:
#	- Auto-Copy Recurring
##############################
IF ( $_SEC['_sadmin_flg'] && $_GPV[obj]=='invc' && $_GPV[mode]=='autocopy' )
	{
		# Set content flag
			$_out = '<!-- Start content -->'.$_nl;

		# Build Title String, Content String, and Footer Menu String

			IF ( $_SEC['_sadmin_flg'] )
			{
				$_tstr = $_LANG['_INVCS']['Auto_Invoice_Copy_Results'].':';

				IF ( $_CCFG['_IS_PRINT'] == 1 ) { $_mstr_flag = '0'; } ELSE  { $_mstr_flag = '1'; }
				$_mstr .= do_nav_link ('admin.php', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'','');
				$_mstr .= do_nav_link ($_SERVER["PHP_SELF"].'?mod=invoices&mode=view', $_TCFG['_IMG_LISTING_M'],$_TCFG['_IMG_LISTING_M_MO'],'','');
			}

			$_cstr .= '<div align="center">'.$_nl;
			IF ( $_ACFG['INVC_AUTO_COPY_ENABLE'] == 1 )
				{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Copy_Recurring'].$_sp.do_auto_invoice_copy ( ).$_nl; }
			ELSE
				{ $_cstr .= '<br>'.$_LANG['_INVCS']['l_Auto_Copy_Recurring'].$_sp.$_LANG['_INVCS']['Function_Disabled'].$_nl; }
			$_cstr .= '<br><br>'.$_nl;
			$_cstr .= '</div>'.$_nl;

		# Call block it function
			$_out .= do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Echo final output
			echo $_out;
	}


/**************************************************************
 * End Module Code
**************************************************************/

?>
