<? 
# This code is released under the same license as PHP. 
# (http://www.php.net/license.html) 

$SessionTableName = "php_sessions";

    function mysql_session_open ($save_path, $session_name) { 
        return true; 
    } 

    function mysql_session_close() { 
        return true; 
    } 

    function mysql_session_read ($SessionID) { 
        global $SessionTableName; 

        $SessionID = addslashes($SessionID); 

        $session_data = @mysql_query("SELECT Data FROM $SessionTableName WHERE SessionID = '$SessionID'");   // or die(db_error_message()); 
        if (mysql_numrows($session_data) == 1) { 
            return @mysql_result($session_data, 0); 
        } else { 
            return false; 
        } 
    } 

    function mysql_session_write ($SessionID, $val) { 
        global $SessionTableName; 

        $SessionID = addslashes($SessionID); 
        $val = addslashes($val); 

        $SessionExists = @mysql_result(mysql_query("SELECT COUNT(*) FROM $SessionTableName WHERE SessionID = '$SessionID'"), 0); 

        if ($SessionExists == 0) { 
            $retval = @mysql_query("INSERT INTO $SessionTableName (SessionID, LastActive, Data) VALUES ('$SessionID', UNIX_TIMESTAMP(NOW()), '$val')");  // or die(db_error_message()); 
        } else { 
            $retval = @mysql_query("UPDATE $SessionTableName SET Data = '$val', LastActive = UNIX_TIMESTAMP(NOW()) WHERE SessionID = '$SessionID'");  // or die(db_error_message()); 
            if (mysql_affected_rows() < 0) { 
                error_log("unable to update session data for session $SessionID"); 
            } 
        } 

        return $retval; 
    } 

    function mysql_session_destroy ($SessionID) { 
        global $SessionTableName; 

        $SessionID = addslashes($SessionID); 

        $retval = @mysql_query("DELETE FROM $SessionTableName WHERE SessionID = '$SessionID'");   // or die(db_error_message()); 
        return $retval; 
    } 

    function mysql_session_gc ($maxlifetime = 300) { 
        global $SessionTableName; 
        $CutoffTime = time() - $maxlifetime; 
        $retval = @mysql_query("DELETE FROM $SessionTableName WHERE LastActive < $CutoffTime");  // or die(db_error_message()); 
        return $retval; 
    } 

    session_set_save_handler ( 
        'mysql_session_open', 
        'mysql_session_close', 
        'mysql_session_read', 
        'mysql_session_write', 
        'mysql_session_destroy', 
        'mysql_session_gc' 
    ); 
?>
