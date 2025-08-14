<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Database functions             //
	//                                 //
	/////////////////////////////////////
	//
	//----------------------------------------------------------------------------------
	//Parameters:	$table - table name
	//				$fields - fields name that will be extracted
	//				$condition - sample: "ID=1", without "where" statment
	//				$orderby - order condition without "order by" statment
	//				$limit - as is without "limit" statment
	//Returns mysql query result for ALL matched records
	//Need to use mysql_fetch_array() for reult
	//Returns an empty string ( false ), if there're errors or no records were returned
	//sample:
	//if($result = dbSelect("Table1","ID, Name", "ID=1"))
	//		while($row = mysql_fetch_array($result)){
	//			do somthing with row
	//		}
	function dbSelectAll($table, $fields = "*", $condition = "1=1", $orderby = "", $limit = ""){
		if($table!=""){
			$Q = "select ".$fields." from ".$table." where ".$condition.($orderby==""?"":" order by ".$orderby).($limit==""?"":" limit ".$limit);
			if($res = @mysql_query($Q) or die($Q."<br>".mysql_error())){
				if(mysql_num_rows($res)>0){
					return $res;
				}else return "";
			}else return "";
		}else return "";
	}
	
	//Parameters:	$table - table name
	//				$row_number - row that will be extrated
	//				$fields - the same as in dbSelectAll
	//				$condition - the same as in dbSelectAll
	//				$orderby - the same as in dbSelectAll
	//Returns an array "field"=>"value" from specified row
	//or rerurns an empty string
	function dbSelectRow($table, $row_number, $fields = "*", $condition = "1=1", $orderby = ""){
		if($res = dbSelectAll($table, $fields, $condition, $orderby, $row_number.",1")){
			$tt = mysql_fetch_array($Q);
			return $tt;
		}else return "";
	}

	//Parameters:	$table - table name
	//				$fields - the same as in dbSelectAll
	//				$condition - the same as in dbSelectAll
	//				$orderby - the same as in dbSelectAll
	//Returns an array "field"=>"value" from first row
	//or rerurns an empty string
	function dbSelect($table, $fields = "*", $condition = "1=1", $orderby = ""){
		if($res = dbSelectAll($table, $fields, $condition, $orderby, "0,1")){
			$tt = mysql_fetch_array($res);
			return $tt;
		}else return "";
	}
	//Parameters:	$table - table name
	//				$field - what field will be used
	//				$condition - the same as in dbSelectAll
	//Returns count of records
	//or rerurns an empty string
	function dbSelectCount($table, $condition = "1=1", $field = "1"){
		if($res = dbSelect($table, "count(".$field.")", $condition)){
			return $res["count(".$field.")"];
		}else return 0;
	}
	//Parameters:	$table - table name
	//				$fields - fields to insert
	//				$values - values to insert
	//returns last inserted AUTO_INCREMENT field
	//or 0
	function dbInsert($table, $fields, $values){
		$Q = "insert into ".$table.($fields==""?"":"(".$fields.")")." values(".$values.")";
		if(mysql_query($Q) or die($Q."<br>".mysql_error())) return mysql_insert_id();
		else return 0;
	}
	//Parameters:	$table - table name
	//				$values - update string "field=value, field=value,..."
	//				$condition - $condition - the same as in dbSelectAll
	//returns number of processed rows
	//or 0
	function dbUpdate($table, $values, $condition = "1=1"){
		$Q = "update ".$table." set ".$values." where ".$condition;
		if(@mysql_query($Q) or die($Q."<br>".mysql_error())) return mysql_affected_rows();
		else return 0;
	}
	//Params:	$table - table name
	//			$ID - id to delete
	//Deletes records where ID field == $ID
	//Returns number of processed rows
	// or 0
	function dbDeleteByID($table, $ID){
			global $F_ID;
			$Q = "delete from ".$table." where $F_ID=".$ID;
			if(@mysql_query($Q) or die($Q."<br>".mysql_error())) return mysql_affected_rows();
			else return 0;
	}
	//Just deletes records from $table with $condition
	function dbDelete($table, $condition="1=1"){
			$Q = "delete from ".$table." where ".$condition;
			if(@mysql_query($Q) or die($Q."<br>".mysql_error())) return mysql_affected_rows();
			else return 0;
	}
?>