<?php 
/*
Counter & visitor statistics version 2.02 - 
Easy to use system to track users and visitor statistics

Copyright (c) 2004 - 2005, Olaf Lederer
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the finalwebsites.com nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

_________________________________________________________________________
available at http://www.finalwebsites.com 
Comments & suggestions: http://www.finalwebsites.com/contact.php

Updates:
version 2.0 - Please read the documentation file about all new features.

version 2.01 - I forgot to remove an old variable: $remote_adr, this var is replaced by $_SERVER['REMOTE_ADDR']. The error will not occur anymore.

version 2.02 - I added a new method to obtain the topXX record count from visits with the same domein name in the referer information. I grouped also some methods together to one moethod named get_data_array().

*/
error_reporting(E_ALL);
include("./config.php");

class Count_visitors {

	var $table_name = DB_TABLE;
	var $referer;
	var $delay = 1;
	
	// niet vergeten visits ouder dan een jaar te verwijderen
	function Count_visitors() {
		$this->referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : "";
		$this->db_connect();
	}
	function db_connect() {
		mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
		mysql_select_db(DB_NAME);
	}
	function check_last_visit() {
		$check_sql = sprintf("SELECT time + 0 FROM %s WHERE visit_date = CURDATE() AND ip_adr = '%s' ORDER BY time DESC LIMIT 0, 1", $this->table_name, $_SERVER['REMOTE_ADDR']);
		$check_visit = mysql_query($check_sql);
		$check_row = mysql_fetch_array($check_visit);
		if (mysql_num_rows($check_visit) != 0) {
			$last_hour = date("H") - $this->delay; 
			$check_time = date($last_hour."is");
			if ($check_row[0] < $check_time) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	function get_country() {
		$country_sql = sprintf("SELECT country FROM ip2nation WHERE ip < INET_ATON('%s') ORDER BY ip DESC LIMIT 0,1", $_SERVER['REMOTE_ADDR']);
		$country_res = mysql_query($country_sql);
		$country = mysql_result($country_res, 0, "country");
		return $country;
	}
	function insert_new_visit() {
		if ($this->check_last_visit()) {
			$insert_sql = sprintf("INSERT INTO %s (id, ip_adr, referer, country, client, visit_date, time, on_page) VALUES (NULL, '%s', '%s', '%s', '%s', CURDATE(), CURTIME(), '%s')", $this->table_name, $_SERVER['REMOTE_ADDR'], $this->referer, $this->get_country(), $_SERVER['HTTP_USER_AGENT'], $_SERVER['PHP_SELF']);
			mysql_query($insert_sql);
		}
	}
	function show_all_visits() {
		$result = mysql_query(sprintf("SELECT COUNT(*) AS count FROM %s", $this->table_name));
		$visits = mysql_result($result, 0, "count");
		return $visits;
	}
	function show_visits_today() {
		$res_today = mysql_query(sprintf("SELECT COUNT(*) AS count FROM %s WHERE visit_date = NOW()", $this->table_name));
		$today = mysql_result($res_today, 0, "count");
		return $today;
	}
	function first_last_visit($type = "last") {
		$order_dir = ($type == "last") ? "DESC" : "ASC";
		$result = mysql_query(sprintf("SELECT visit_date, time FROM %s ORDER BY visit_date %s LIMIT 0,1", $this->table_name, $order_dir));
		$first_last = mysql_result($result, 0, "visit_date");
		$first_last .= " ".mysql_result($result, 0, "time");
		return $first_last;
	}
	function results_by_day($res_month, $res_year) {
		$sql = sprintf("SELECT DAYOFMONTH(visit_date) AS visit_day, COUNT(*) AS visits_count FROM %s WHERE MONTH(visit_date) = %s AND YEAR(visit_date) = %s GROUP BY visit_date", $this->table_name, $res_month, $res_year);
		$result = mysql_query($sql);
		$visits_daily = array();
		while ($obj = mysql_fetch_object($result)) {
			$visits_daily[$obj->visit_day] = $obj->visits_count;
		}
		return $visits_daily;
	}
	function get_data_array($what, $limit) {
		switch ($what) {
			case "monthly":
			$sql = sprintf("SELECT MONTH(visit_date) AS variable, COUNT(*) AS value FROM %s GROUP BY MONTH(visit_date) ORDER BY visit_date LIMIT 0, %d", $this->table_name, $limit);
			break;
			case "country":
			$sql = sprintf("SELECT ip2nationcountries.country AS variable, COUNT(*) AS value FROM %s AS tbl LEFT JOIN ip2nationcountries ON ip2nationcountries.code = tbl.country WHERE tbl.country <> '' GROUP BY tbl.country ORDER BY 2 DESC LIMIT 0,%s", $this->table_name, $limit);
			break;
			case "referer":
			$sql = sprintf("SELECT COUNT(*) AS value, TRIM(LEADING 'www.' FROM SUBSTRING_INDEX(TRIM(LEADING 'http://' FROM referer), '/', 1)) AS variable FROM %s WHERE referer <> '' GROUP BY variable ORDER BY value DESC LIMIT 0, %d", $this->table_name, $limit);
			break;
		}
		$result = mysql_query($sql);
		$data = array();
		while ($obj = mysql_fetch_object($result)) {
			$data[$obj->variable] = $obj->value;
		}
		mysql_free_result($result);
		return $data;
	}
	function get_days($from_month, $from_year) {
		$last_day = date("t", mktime(0,0,0,$from_month,1,$from_year));
		$day_count = 1;
		while ($day_count <= $last_day) {
			$days_array[] = $day_count;
			$day_count++;
		}
		return $days_array;
	}
	function create_date($month2, $year2) {
		$date_str = date ("M y", mktime (0,0,0,$month2,0,$year2)); 
		return $date_str;
	}
	function month_last_year() {
		$i = 0;
		while ($i < 12) {
			$twelve_month[$i] = date("n", mktime(0,0,0,date("n")-$i,15,date("Y")));
			$i++;
		}
		return $twelve_month;
	}	
	function build_rows_totals($array_labels, $array_values) {
		$all_values = array_sum($array_values);
		$row = "";
		foreach($array_labels as $label) {
			if (isset($array_values[$label])) {
				$row .= "  <tr>\n";
				$row .= "	   <td>".$label."</td>\n";			
				$width = ($array_values[$label]*100)/$all_values;
				$row .= "	   <td><img src=\"".IMG."\" width=\"".round($width*3, 0)."\" height=\"10\"></td>\n";
				$row .= "	   <td>".$array_values[$label]."</td>\n";
				$row .= "  </tr>\n";
			}
		}
		return $row;
	}
	function stats_country($limit = 10) {
		$country_visits = $this->get_data_array("country", $limit);
		$country_array = array_keys($country_visits);
		$country_tbl = "<h2>Visits by country (Top ".count($country_array).")</h2>\n";
		$country_tbl .= "<table width=\"480\" border=\"1\" cellspacing=\"2\" cellpadding=\"0\">\n";
		$country_tbl .= "  <tr>\n";
		$country_tbl .= "    <th>Month</th>\n";
		$country_tbl .= "    <th>&nbsp;</th>\n";
		$country_tbl .= "    <th>Visits</th>\n";
		$country_tbl .= "	 </tr>\n";
		$country_tbl .= $this->build_rows_totals($country_array, $country_visits);
		$country_tbl .= "</table>\n";
		return $country_tbl;
	}
	function stats_top_referer($limit = 15) {
		$referer_domains = $this->get_data_array("referer", $limit);
		$domain_array = array_keys($referer_domains);
		$refer_tbl = "<h2>Visits by Referer (Top ".count($domain_array).")</h2>\n";
		$refer_tbl .= "<table width=\"480\" border=\"1\" cellspacing=\"2\" cellpadding=\"0\">\n";
		$refer_tbl .= "  <tr>\n";
		$refer_tbl .= "    <th>Referer domain</th>\n";
		$refer_tbl .= "    <th>&nbsp;</th>\n";
		$refer_tbl .= "    <th>Visits</th>\n";
		$refer_tbl .= "	 </tr>\n";
		$refer_tbl .= $this->build_rows_totals($domain_array, $referer_domains);
		$refer_tbl .= "</table>\n";
		return $refer_tbl;
	}
	function stats_totals($limit = 12) {
		$month_array = $this->month_last_year();
		krsort($month_array);
		reset($month_array);
		$all_visits_month = $this->get_data_array("monthly", $limit);
		$total_tbl = "<h2>Visits last ".count($all_visits_month)." month</h2>\n";
		$total_tbl .= "<table width=\"480\" border=\"1\" cellspacing=\"2\" cellpadding=\"0\">\n";
		$total_tbl .= "  <tr>\n";
		$total_tbl .= "    <th>Month</th>\n";
		$total_tbl .= "    <th>&nbsp;</th>\n";
		$total_tbl .= "    <th>Visits</th>\n";
		$total_tbl .= "	 </tr>\n";
		$total_tbl .= $this->build_rows_totals($month_array, $all_visits_month);
		$total_tbl .= "</table>\n";
		return $total_tbl;
	}
	function stats_monthly($month, $year) {
		$my_visits = $this->results_by_day($month, $year);
		$total_visits = array_sum($my_visits);
		$month_tbl = "<h2>Visits in ".$this->create_date($month, $year)." (total: ".$total_visits.")</h2>\n";
		$month_tbl .= "<table width=\"760\" border=\"1\" cellspacing=\"2\" cellpadding=\"0\">\n";
		$month_tbl .= "  <tr>\n";
		foreach($this->get_days($month, $year) as $day) {
			if (isset($my_visits[$day])) {
				$month_tbl .= "	   <td>".$my_visits[$day]."</td>\n";
			} else {
				$month_tbl .= "    <td>n/a</td>\n";
			}
		}
		$month_tbl .= "	 </tr>\n";
		$month_tbl .= "  <tr>\n";
		foreach($this->get_days($month, $year) as $day) {
			if (isset($my_visits[$day])) {
				$height = ($my_visits[$day]*100)/$total_visits;
				$month_tbl .= "	   <td align=\"center\" valign=\"bottom\"><img src=\"".IMG."\" width=\"10\" height=\"".round($height*20, 0)."\"></td>\n";
			} else {
				$month_tbl .= "    <td>&nbsp;</td>\n";
			}
		}
		$month_tbl .= "	 </tr>\n";
		$month_tbl .= "  <tr>\n";
		foreach($this->get_days($month, $year) as $day) {
			$month_tbl .= "	   <td>".$day."</td>\n";
		}
		$month_tbl .= "  </tr>\n";
		$month_tbl .= "</table>\n";
		return $month_tbl;
	}
}
?>