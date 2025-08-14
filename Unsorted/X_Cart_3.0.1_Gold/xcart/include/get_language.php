<?
	if ($HTTP_GET_VARS["sl"])
		$store_language = $HTTP_GET_VARS["sl"];

	if ($current_area == "C") {
		if (!$store_language and $login) {
			$r = func_query_first ("SELECT language FROM customers WHERE login='$login'");
			$store_language = $r["language"];
		}
		$customer_language = func_get_language ($store_language);
		if (!$customer_language) {
			$store_language = $default_customer_language;
			$customer_language = func_get_language ($store_language);
		}

		$res = func_query_first ("SELECT charset FROM countries WHERE code='$store_language'");
		$smarty->assign ('default_charset', $res[charset]);
	}

	$admin_language = func_get_language ($default_admin_language);

	if ($current_area == "C") {
		$smarty->assign ("lng", $customer_language);
		if ($login)
			db_query ("UPDATE customers SET language='$store_language' WHERE login='$login'");
		if ($store_language != $HTTP_COOKIE_VARS["store_language"]) {
			setcookie ("store_language", "", time()-31536000);
			setcookie ("store_language", $store_language, time()+31536000); # for one year
		}
	} else {
		$smarty->assign ("lng", $admin_language);
	}

	$d_langs = explode (",", $disabled_languages);

	if ($d_langs) {
		foreach ($d_langs as $key=>$value) {
			$d_langs [$key] = trim ($value);
		}
	}

	$all_languages = func_query ("SELECT DISTINCT(languages.code), countries.* FROM languages, countries WHERE languages.code=countries.code");
	
	$n_langs = array ();

	if ($all_languages) {
		foreach ($all_languages as $value) {
			if (!(in_array ($value["language"], $d_langs)))
				$n_langs [] = $value;
		}
	}

	$all_languages = $n_langs;
	
	$smarty->assign ("all_languages", $all_languages);
	$smarty->assign ("store_language", $store_language);
	$smarty->assign ("all_languages_numba", sizeof($all_languages));
?>
