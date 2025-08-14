<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 RRF.ru development. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The RRF.RU DEVELOPMENT forbids, under any circumstances, the unauthorized   |
| reproduction of software or use of illegally obtained software. Making      |
| illegal copies of software is prohibited. Individuals who violate copyright |
| law and software licensing agreements may be subject to criminal or civil   |
| action by the owner of the copyright.                                       |
|                                                                             |
| 1. It is illegal to copy a software, and install that single program for    |
| simultaneous use on multiple machines.                                      |
|                                                                             |
| 2. Unauthorized copies of software may not be used in any way. This applies |
| even though you yourself may not have made the illegal copy.                |
|                                                                             |
| 3. Purchase of the appropriate number of copies of a software is necessary  |
| for maintaining legal status.                                               |
|                                                                             |
| DISCLAIMER                                                                  |
|                                                                             |
| THIS SOFTWARE IS PROVIDED BY THE RRF.RU DEVELOPMENT TEAM ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL THE RRF.RU DEVELOPMENT TEAM OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is RRF.ru development.           |
| Portions created by RRF.ru development are Copyright (C) 2001-2002          |
| RRF.ru development. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: languages.php,v 1.5 2002/06/06 10:50:51 lucky Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

$topics = array ("Labels", "Text", "Errors", "E-Mail");

if (!$topic)
	$topic = $topics [0];

$d_langs = explode (",", $disabled_languages);
if ($d_langs) {
	foreach ($d_langs as $key=>$value) {
		$d_langs [$key] = trim ($value);
	}
}

$languages = func_query ("SELECT DISTINCT(languages.code), countries.country, countries.language FROM languages, countries WHERE languages.code=countries.code ORDER BY languages.code");

if ($languages) {
	foreach ($languages as $key=>$value) {
		if (in_array ($value["language"], $d_langs))
			$languages[$key]["disabled"] = "Y";
		else
			$languages[$key]["disabled"] = "N";
	}
}

if ($mode == "update") {
	if ($var_name) {
		foreach ($var_name as $key=>$value) {
			db_query ("UPDATE languages SET descr='$var_descr[$value]', value='$var_value[$value]' WHERE code='$language' AND name='$value'");
		}
	}

	if ($new_var_name) {
		$mr = func_query_first ("SELECT * FROM languages WHERE name='$new_var_name'");
		if ($mr) {
			db_query ("UPDATE languages SET descr='$new_var_descr', value='$new_var_value' WHERE name='$new_var_name' AND code='$language'");
		} else {
			foreach ($languages as $key=>$value)
				db_query ("INSERT INTO languages (code, descr, name, value, topic) VALUES ('$value[code]','$new_var_descr','$new_var_name','$new_var_value','$topic')");
		}
	}

	header ("Location: languages.php?language=$language&page=$page&filter=".urlencode($filter)."&topic=$topic&updated");
	exit;
}

if ($mode == "delete") {
	db_query ("DELETE FROM languages WHERE name='$var'");
	
	header ("Location: languages.php?language=$language&page=$page&filter=".urlencode($filter)."&deleted");
	exit;
}

if ($mode == "del_lang") {
	db_query ("DELETE FROM languages WHERE code='$language'");
	db_query ("DELETE FROM products_lng WHERE code='$language'");
	header ("Location: languages.php?lang_deleted");
	exit;
}

$_new_languages = func_query ("SELECT * FROM countries WHERE language!='' ORDER BY language");
# Strip off existing languages

$new_languages = array ();
if ($_new_languages) {
	foreach ($_new_languages as $key=>$value) {
		$found = false;
		if ($languages) {
			foreach ($languages as $subkey=>$subvalue) {
				if ($value["code"] == $subvalue["code"])
					$found = true;
			}
		}
		if (!$found)
			$new_languages [] = $value;
	}
}

if ($mode == "add_lang") {
	if (!$new_language) {
		header ("Location: languages.php");
		exit;
	}

	$dc = func_query_first ("SELECT * FROM countries WHERE is_default='Y'");
	if ($dc)
		$dc = $dc["code"];
	else
		$dc = $location_country;

	$result = func_query ("SELECT * FROM languages WHERE code='$dc'");
	if ($result) {
		foreach ($result as $key=>$value) {
			db_query ("INSERT INTO languages (code, descr, name, value, topic) VALUES ('$new_language', '".addslashes($value[descr])."', '".addslashes($value[name])."','".addslashes($value[value])."','$value[topic]')");
		}
	}

	header ("Location: languages.php?language=$new_language&topic=$topic&page=$page");
	exit;
}

if (($mode == "change") and ($language)) {
	$result = func_query_first ("SELECT * FROM countries WHERE code='$language'");

	$new_langs = array ();
	
	if (in_array ($result["language"], $d_langs)) {
		foreach ($d_langs as $k=>$v) {
			if ($v != $result["language"])
				$new_langs [] = $v;
		}
		$d_langs = $new_langs;
	} else {
		$d_langs [] = $result["language"];
	}

	$t_langs = array ();

	if ($d_langs) {
		foreach ($d_langs as $key=>$value) {
			if ($value)
				$t_langs [] = $value;
		}
	}

	$disabled_languages = implode (",", $t_langs);
	db_query ("UPDATE config SET value='$disabled_languages' WHERE name='disabled_languages'");

	header ("Location: languages.php?language=$language&mode_changed");
	exit;
}

if ($language) {
	$r = func_query_first ("SELECT language, charset FROM countries WHERE code='$language'");
	$smarty->assign ("default_charset", $r[charset]);

	if (in_array ($r["language"], $d_langs)) {
		$lang_disabled = "Y";
	} else {
		$lang_disabled = "N";
	}

	$smarty->assign ("lang_disabled", $lang_disabled);
	
	$topic_condition = " AND topic='$topic' ";
	$query = "SELECT * FROM languages WHERE code='$language' AND (name LIKE '%$filter%' OR descr LIKE '%$filter%' or value LIKE '%$filter%') $topic_condition ";

	$products_per_page = 30;

	$total_products_in_search = count (func_query ($query));
	$total_nav_pages = ceil ($total_products_in_search/$products_per_page)+1;
	require "../include/navigation.php";
	
	$smarty->assign ("data", func_query ("$query LIMIT $first_page, $products_per_page"));

	$smarty->assign ("navigation_script", "languages.php?language=$language&topic=$topic&filter=".urlencode($filter));
}

$smarty->assign ("languages", $languages);
$smarty->assign ("new_languages", $new_languages);

$smarty->assign ("topics", $topics);

$smarty->assign("main","languages");
@include "../modules/gold_display.php";
$smarty->display("admin/home.tpl");
?>
