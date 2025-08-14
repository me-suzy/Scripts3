<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * The latest version of Molins can be obtained from <http://www.phpize.com>.
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: SqlEnum.class.php,v 1.2 2005/10/11 15:57:30 slizardo Exp $
 * @package poop.dbobjects.util
 */
	
class SqlEnum {
	const EQUAL = ' = ';
	const NOT_EQUAL = ' <> ';
	const ALT_NOT_EQUAL = ' != ';
	const GREATER_THAN = ' > ';
	const LESS_THAN = ' < ';
	const GREATER_EQUAL = ' >= ';
	const LESS_EQUAL = ' <= ';
	const LIKE = ' LIKE ';
	const NOT_LIKE = ' NOT LIKE ';
	const ILIKE = ' ILIKE ';
	const NOT_ILIKE = ' NOT ILIKE ';
	const CUSTOM = ' CUSTOM ';
	const DISTINCT = ' DISTINCT ';
	const IN = ' IN ';
	const NOT_IN = ' NOT IN ';
	const ALL = ' ALL ';
	const JOIN = ' JOIN ';
	const ASC = ' ASC ';
	const DESC = ' DESC ';
	const ISNULL = ' IS NULL ';
	const ISNOTNULL = ' IS NOT NULL ';
	const CURRENT_DATE = ' CURRENTDATE ';
	const CURRENT_TIME = ' CURRENTTIME ';
	const LEFT_JOIN = ' LEFT JOIN ';
	const RIGHT_JOIN = ' RIGHT JOIN ';
	const INNER_JOIN = ' INNER JOIN ';
}

?>
